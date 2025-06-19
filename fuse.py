# Import necessary libraries and modules
import sqlite3
from random import randint, choice, random
import re
import subprocess
import os
from dataflow import PHPFastDataflow
from mutator import Mutator

# Set to True to use simple concatenation as a baseline
ConcatBaseline = False

# Replace a random occurrence of a substring in a string
def replace_random_occurrence(s, old, new):
    # Find all positions of the substring 'old'
    positions = []
    start = 0
    while True:
        start = s.find(old, start)
        if start == -1:
            break
        positions.append(start)
        start += len(old)

    # If no occurrences found, return the original string
    if not positions:
        return s

    # Select a random position to replace the substring
    random_pos = choice(positions)
    return s[:random_pos] + new + s[random_pos + len(old):]

# Fusion class for handling test file fusion and mutation
class Fusion():
    # PHP code to fuse internal variables using random internal functions
    apifuzz_func = ""

    # Class attributes
    fuse_count = 0  # Number of fused test cases

    # Initialize with paths and configuration options
    def __init__(self, test_root, php_root, apifuzz, ini, mutation):
        self.test_root = test_root
        self.php_root = php_root
        self.apifuzz = apifuzz  # Whether to fuzz internal interfaces
        self.ini = ini  # Whether to fuzz execution environments (JIT, etc.)
        self.mutation = mutation  # Whether to mutate the original test case
        self.mut = Mutator()

    # Randomly generate a JIT mode configuration for opcache
    def random_jit_mode(self):
        # TODO: shall we fuzz all these jit modes?
        # jit_mode = choice(['1111','1215','1211','1213','1254','1255','1201','1202','1205','1101','1103','1105','1231','1235','1011','1015'])
        jit_mode = choice(['1254','1205'])
        jit_ini = '''
opcache.enable=1
opcache.enable_cli=1
opcache.jit=''' + jit_mode + '\n'
        return jit_ini

    # TODO: fuzz the configurations
    def get_random_config(self):
        config_options = {
            "precision": choice([10, 12, 13, 14, 17]),
            "serialize_precision": choice([5, 10, 14, 15, 75, -1]),
            "memory_limit": choice(["2M", "33M", "16M", "20M", "32M", "100M", "256M", "512M", "5M", "8M", "128M", "6G", "-1"]),
            "post_max_size": choice(["1", "1M", "1024"]),
            "max_input_vars": choice([1, 4, 5, 10, 100, 1000]),
            "max_execution_time": choice([0, 1, 2, 10, 12, 60]),
            "default_charset": choice(["cp932", "big5", "ISO-8859-1", "UTF-8", "", "cp874", "cp936", "cp1251", "cp1252", "cp1253", "cp1254", "cp1255", "cp1256"]),
            "short_open_tag": choice(["on", "off", 1]),
            "auto_globals_jit": choice([0, 1]),
            "expose_php": choice([0, "On"]),
            "implicit_flush": choice([0, 1]),
            "allow_url_include": choice([0, 1]),

            # Timezone settings
            "date.timezone": choice([
                "Europe/London", "UTC", "Atlantic/Azores", "GMT", "America/Los_Angeles", "Asia/Singapore",
                "Asia/Chongqing", "Europe/Amsterdam", "Europe/Berlin", "Europe/Paris", "America/New_York",
                "America/Montreal", "America/Sao_Paulo", "America/Vancouver", "America/Mendoza", "Europe/Rome",
                "GMT0", "Mars/Utopia_Planitia", "Incorrect/Zone"
            ]),

            # Opcache settings
            "opcache.enable": choice([0, 1]),
            "opcache.enable_cli": choice([0, 1]),
            "opcache.preload": "{PWD}/" + choice([
                "preload_undef_const_2.inc", "preload_variance_ind.inc", "preload_inheritance_error_ind.inc",
                "preload_ind.inc", "preload_bug81256.inc", "preload_user.inc"
            ]),
            "opcache.jit": choice([0, 1205, 1235, 1255]),
            "opcache.jit_buffer_size": choice(["1M", "128M", "0"]),
            "opcache.jit_blacklist_root_trace": choice(["16", "255"]),
            "opcache.jit_blacklist_side_trace": choice(["8", "255"]),
            "opcache.jit_max_loop_unrolls": choice(["8", "10"]),
            "opcache.jit_max_recursive_calls": choice(["2", "10"]),
            "opcache.jit_max_recursive_returns": choice(["2", "4"]),
            "opcache.jit_max_polymorphic_calls": choice(["2", "1000"]),
            "opcache.file_update_protection": choice([0, 2]),
            "opcache.optimization_level": choice([-1, 0, 0x7fffffff, 0x4ff, 0x7FFFBFFF]),
            "opcache.memory_consumption": choice([7, 64]),
            "opcache.max_accelerated_files": choice([10, 1000000]),
            "opcache.revalidate_freq": choice([0, 60]),
            "opcache.validate_timestamps": choice([0, 1]),
            "opcache.interned_strings_buffer": choice([-1, 16, 131072]),

            # Session settings
            "session.save_handler": choice(["files", "non-existent", "qwerty"]),
            "session.auto_start": choice([0, 1]),
            "session.use_cookies": choice([0, 1]),
            "session.cookie_httponly": choice([0, "TRUE"]),
            "session.cookie_secure": choice([0, "TRUE"]),
            "session.use_strict_mode": choice([0, 1]),
            "session.use_trans_sid": choice([0, 1]),
            "session.gc_maxlifetime": choice([300, 0]),
            "session.upload_progress.enabled": choice([0, 1]),
            "session.gc_probability": choice([0, 1]),
            "session.sid_length": choice([32]),

            # Error reporting settings
            "error_reporting": choice([0, -1, 1, 8191, 14335, 2039, 2047, "E_ALL", "E_ALL^E_NOTICE", "E_ALL & ~E_DEPRECATED", "E_ALL & ~E_WARNING & ~E_NOTICE", "E_ALL & ~E_WARNING", "E_ALL & ~E_DEPRECATED", "E_ALL & E_NOTICE | E_PARSE ^ E_DEPRECATED & ~E_WARNING | !E_ERROR"]),

            # Mail settings
            "sendmail_path": "{MAIL:{PWD}/" + choice([
                "mb_send_mail04.eml", "mailBasic7.out", "gh8086.eml", "mb_send_mail03.eml", "gh7902.eml"
            ]) + "}"
        }

        # Randomly select one key-value pair from the config options
        random_key = choice(list(config_options.keys()))
        return f"{random_key}={config_options[random_key]}"

    # Randomly generate INI settings with possible JIT configuration
    def random_inis(self):
        if self.ini==False:
            return ""
        inis = self.get_random_config() + '\n'
        if choice([True, False, False, False]):
            inis += self.random_jit_mode()
        return inis

    # Fuse two test cases by interleaving their dataflows
    def _fuse_dataflow_interleave(self, test1, test2, dataflow1, dataflow2):
        if not dataflow1 or not dataflow2:
            return test1, test2

        # we can mix our random class variables with the rest code context
        # NOTE: $clsAttr can be non-exist
        dataflow1 += [["$cls","$clsAttr"]];

        if choice([True, False]):
            test1_flow = choice(choice(dataflow1))
            test2_flow = choice(choice(dataflow2))
            test1 += f"\n$fusion = {test1_flow};\n"
            test2 = replace_random_occurrence(test2, test2_flow, "$fusion")
            return test1, test2

        # Identify the longest dataflows for interleaving
        max_dataflow_1 = 0
        max_dataflow_1_len = 0
        max_dataflow_2 = 0
        max_dataflow_2_len = 0

        for each_dataflow in dataflow1:
            if len(each_dataflow)>max_dataflow_1_len:
                max_dataflow_1_len = len(each_dataflow)
                max_dataflow_1 = each_dataflow

        for each_dataflow in dataflow2:
            if len(each_dataflow)>max_dataflow_2_len:
                max_dataflow_2_len = len(each_dataflow)
                max_dataflow_2 = each_dataflow

        test1_flow = choice(max_dataflow_1)
        test2_flow = choice(max_dataflow_2)

        # step 1: keeping the max dataflow from test 1
        test1 += f"\n$fusion = {test1_flow};\n"

        # step 2: interleave the max dataflow in test2
        test2 = replace_random_occurrence(test2, test2_flow, "$fusion")

        return test1, test2

    # return function_name, params
    def select_random_function(self):
        function_name, param_num = choice(self.apis)
        return function_name, param_num

    # fuzz the api
    # defined_vars: the defined vars in the php program to be the arguments of apis
    def _instrumentation_apifuzz(self, defined_vars):
        _instruments = []
        func, param_num = self.select_random_function()
        # we try 10 times to randomly fuzz the arguments
        for i in range(10):
            args = []
            for x in range(param_num):
                args.append(choice(defined_vars))
            _instrument = f"{func}({','.join(args)});"
            _instrument = "try {"+_instrument+"} catch (Exception $e) { echo($e); }"
            _instruments.append(_instrument)
        return '\n'+'\n'.join(_instruments)+'\n'

    def _instrumentation_classfuzz(self, defined_vars):
        _pre_instrument = []
        _after_instrument = []

        # Connect to the SQLite database
        conn = sqlite3.connect(f'{self.test_root}/knowledges/class.db')
        cursor = conn.cursor()

        # Select a random class
        cursor.execute('SELECT id, class_name FROM classes ORDER BY RANDOM() LIMIT 1')
        class_row = cursor.fetchone()

        if class_row:
            class_id, class_name = class_row
            _pre_instrument.append(f"\n$cls = new {class_name}();\n")

            # Select a random attribute for the selected class
            cursor.execute('SELECT name FROM attributes WHERE class_id = ? ORDER BY RANDOM() LIMIT 1', (class_id,))
            attr_row = cursor.fetchone()
            if attr_row:
                attr_name = attr_row[0]
                _pre_instrument.append(f"\n$clsAttr=$cls.{attr_name};\n")

            # Select a random method for the selected class
            cursor.execute('SELECT name, params_count FROM methods WHERE class_id = ? ORDER BY RANDOM() LIMIT 1', (class_id,))
            method_row = cursor.fetchone()
            if method_row:
                method_name, params_count = method_row
                # we try 10 times to randomly fuzz the arguments
                for i in range(10):
                    args = []
                    for x in range(params_count):
                        args.append(choice(defined_vars))
                    _instrument = f"$cls->{method_name}({','.join(args)});"
                    _instrument = "try {"+_instrument+"} catch (Exception $e) { echo($e); }"
                    _after_instrument.append(_instrument)
                _after_instrument = '\n'+'\n'.join(_after_instrument)+'\n'
            else:
                _after_instrument = ""
        else:
            print('No classes found in the database.')
            exit()

        # Close the database connection
        conn.close()

        _pre_instrument = '\n'+'\n'.join(_pre_instrument)+'\n'

        return _pre_instrument, _after_instrument

    # Clean up the PHP code by removing unnecessary headers and footers
    def clean_php_header_tail(self, phpcode):
        phpcode = phpcode.strip().strip('\n').strip("===DONE===").strip("==DONE==").strip("Done")
        if phpcode.startswith('<?php'):
            phpcode = phpcode[len('<?php'):].lstrip()
        if phpcode.endswith('?>'):
            phpcode = phpcode[:-len('?>')].rstrip()
        phpcode = phpcode.strip("<?php").strip("?>")
        return '\n' + phpcode + '\n'

    # Read file content
    def read_file(self, filepath):
        with open(filepath, "r", encoding="iso-8859-1") as f:
            return f.read()

    # Write content to a file
    def write_file(self, filepath, content):
        with open(filepath, "w") as f:
            f.write(content)

    # Ad-hoc patches for known syntax errors after fusion
    def adhoc_syntax_patch(self, phpt):
        phpt = phpt.replace('echo "Done"\n', 'echo "Done";\n')
        return phpt

    # Fuse two test cases while handling different sections
    def fuse(self):
        phpcode1, variable1, dataflow1, description1, configuration1, skipif1, extension1 = self.select_random_seed()
        phpcode2, variable2, dataflow2, description2, configuration2, skipif2, extension2 = self.select_random_seed()

        phpcode1 = self.mut.mutate(phpcode1)
        phpcode2 = self.mut.mutate(phpcode2)

        fused_description = f"--TEST--\n{description1} + {description2}\n"
        fused_configurations = f"\n--INI--\n{configuration1}\n{configuration2}\n{self.random_inis()}\n"

        fused_skipif = ""

        # if skipif1!="" or skipif2!="":
        #     fused_skipif = f"\n--SKIPIF--\n{skipif1}\n{skipif2.strip('<?php')}\n"
        # else:
        #     fused_skipif = ""

        if extension1!="" or extension2!="":
            fused_extension = f"\n--EXTENSION--\n{extension1}\n{extension2}\n"
        else:
            fused_extension = ""
        fused_expect = "\n--EXPECT--\nthis is a flowfusion test\n"

        phpcode1 = self.clean_php_header_tail(phpcode1)
        phpcode2 = self.clean_php_header_tail(phpcode2)

        new_phpcode1, new_phpcode2 = self._fuse_dataflow_interleave(phpcode1, phpcode2, eval(dataflow1), eval(dataflow2))

        variables = eval(variable1) + eval(variable2) + ['$fusion']

        # can be disabled for better efficiency
        # this use non-cached SQL, it might be slow :(
        _pre_class_instrument, _after_class_instrument = self._instrumentation_classfuzz(variables)

        if self.apifuzz:
            _instrument_apifuzz = self._instrumentation_apifuzz(variables)
        else:
            _instrument_apifuzz = ""

        _instrument_vardump = "\nvar_dump(get_defined_vars());\n"

        fused_file = f"\n--FILE--\n<?php\n{_pre_class_instrument}\n{new_phpcode1}\n{new_phpcode2}\n{_instrument_vardump}\n{_instrument_apifuzz}\n{_after_class_instrument}\n"

        fused_test = f"{fused_description}{fused_configurations}{fused_skipif}{fused_extension}{fused_file}{fused_expect}"

        fused_test = re.sub("\n+", "\n", fused_test)

        fused_test = self.adhoc_syntax_patch(fused_test)

        return fused_test

    def select_random_seed(self):
        return choice(self.seeds)

    def load_classes(self):
        # Connect to the SQLite database
        conn = sqlite3.connect(f'{self.test_root}/knowledges/class.db')
        cursor = conn.cursor()

        # Select a random class
        cursor.execute('SELECT id, class_name FROM classes')
        # Fetch all records
        cursor.execute(SQL)
        records = cursor.fetchall()
        conn.close()

        if records:
            self.classes = records
        else:
            print("No seeds available")
            exit()

    def load_apis(self):
        conn = sqlite3.connect(f"{self.test_root}/knowledges/apis.db")
        cursor = conn.cursor()

        # Select a random function
        SQL = "SELECT name, num_params FROM functions"

        # Fetch all records
        cursor.execute(SQL)
        records = cursor.fetchall()
        conn.close()

        if records:
            self.apis = records
        else:
            print("No APIs available")
            exit()

    def load_seeds(self):
        conn = sqlite3.connect(f"{self.test_root}/knowledges/seeds.db")
        cursor = conn.cursor()

        SQL = f"""
            SELECT phpcode, variable, dataflow, description, configuration, skipif, extension FROM seeds
            """

        # Fetch all records
        cursor.execute(SQL)
        records = cursor.fetchall()
        conn.close()

        if records:
            self.seeds = records
        else:
            print("No seeds available")
            exit()

    # Main function to handle the test fusion process
    def main(self):
        self.load_seeds()
        self.load_apis()
        while True:
            fused_test = self.fuse()
            self.fuse_count += 1
            self.fuse_count %= 10000
            self.write_file(f"/tmp/fused{self.fuse_count}.phpt", fused_test)
