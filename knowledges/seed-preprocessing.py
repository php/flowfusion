import re
import os
import sqlite3
import subprocess

def get_php_dataflow_groups(php_script_path, dataflow_script_path='dataflow.php'):
    """
    Invokes the PHP dataflow analysis script and collects the dataflow list.

    Parameters:
        php_script_path (str): The path to the PHP file to analyze.
        dataflow_script_path (str): The path to the PHP dataflow analysis script.

    Returns:
        List[List[str]]: A list of dataflow groups, each group is a list of variable names.
    """
    try:
        # Execute the PHP dataflow analysis script
        result = subprocess.run(
            ['php', dataflow_script_path, php_script_path],
            capture_output=True,
            text=True,
            check=True
        )

        # Extract the output
        output = result.stdout.strip()

        # Use eval to parse the output
        dataflow_groups = eval(output)

        return dataflow_groups

    except subprocess.CalledProcessError as e:
        print(f"Error executing PHP script: {e.stderr}")
        return []
    except Exception as e:
        print(f"Error parsing output: {e}")
        return []


class PHPFastDataflow:
    """
    A class that performs fast, coarse-grained dataflow analysis on PHP code.
    It does not guarantee completeness but aims for soundness.
    """

    def __init__(self):
        """
        Initializes the PHPFastDataflow object with empty variables and dataflows.
        """
        self.variables = []  # List to store extracted variables from PHP code
        self.dataflows = []  # List of lists to store dataflows between variables

    def clean(self):
        """
        Resets the variables and dataflows to empty lists.
        """
        self.variables = []
        self.dataflows = []

    def extract_variables(self):
        """
        Extracts all PHP variables from the PHP code using a regular expression.
        It ensures that each variable is unique by converting the list to a set.
        """
        # Regular expression to match valid PHP variables
        regex = r"\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*"
        # Find all PHP variables in the provided code
        self.variables = re.findall(regex, self.phpcode)
        # Ensure unique variables by converting the list to a set
        self.variables = list(set(self.variables))

    def analyze_php_line(self, php_line):
        """
        Analyzes a single line of PHP code to find variables and check
        if multiple variables are interacting in the same line.

        Returns:
        - A tuple (True, [variables]) if multiple variables are found.
        - A tuple (False, None) if no interaction between variables is detected.
        """
        regex = r"\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*"
        variables = list(set(re.findall(regex, php_line)))
        if len(variables) > 1:
            return (True, variables)
        else:
            return (False, None)

    def merge_dataflows(self):
        """
        Merges the dataflows by grouping variables that interact with each other.
        Variables that appear together in dataflow analysis are merged into a single
        list to represent their relationship.
        """
        list_of_lists = self.variables

        # Convert any single variables to lists to ensure consistent structure
        for i in range(len(list_of_lists)):
            if type(list_of_lists[i]) != list:
                list_of_lists[i] = [list_of_lists[i]]

        # Initialize an empty list to store merged sublists
        merged_lists = []

        # Iterate through each sublist (representing variables that interact)
        for sublist in list_of_lists:
            merged_with_existing = False
            for merged_sublist in merged_lists:
                # If any variable in the current sublist exists in a merged sublist,
                # merge them by extending the merged list
                if any(var in merged_sublist for var in sublist):
                    merged_sublist.extend(var for var in sublist if var not in merged_sublist)
                    merged_with_existing = True
                    break

            # If no merge occurred, add the current sublist as a new group
            if not merged_with_existing:
                merged_lists.append(sublist)

        # Update the variables with the merged dataflows
        self.variables = merged_lists

    def extract_dataflow(self):
        """
        Extracts dataflows from the PHP code by analyzing each line of code.
        It identifies variables and their interactions, grouping them into dataflows.
        """
        for eachline in self.phpcode.split('\n'):
            result, variables = self.analyze_php_line(eachline)
            if result:
                for each_var in variables:
                    # If the variable is already in the list, remove and replace it
                    if each_var in self.variables:
                        self.variables.remove(each_var)
                # Add the new set of interacting variables
                self.variables.append(variables)

        # Merge dataflows to group interacting variables
        self.merge_dataflows()

    def analyze(self, phpcode):
        """
        The main function to analyze a given PHP code for dataflows.
        It extracts variables and their interactions to produce a list
        of dataflows.

        Args:
        - phpcode: The PHP source code to analyze.

        Returns:
        - A list of merged dataflows, each representing groups of interacting variables.
        """
        self.phpcode = phpcode
        self.clean()  # Reset variables and dataflows
        self.extract_variables()  # Extract variables from the code
        self.vars = []
        for each in self.variables:
            self.vars.append(each)
        self.extract_dataflow()  # Extract dataflows between variables
        return self.vars, self.variables

def remove_php_comments(code):
    result = ''
    i = 0
    in_single_quote = False
    in_double_quote = False
    in_single_line_comment = False
    in_multi_line_comment = False
    escaped = False
    code_length = len(code)

    while i < code_length:
        c = code[i]
        next_c = code[i+1] if i+1 < code_length else ''

        # Handle string literals
        if in_single_quote:
            result += c
            if not escaped and c == '\\':
                escaped = True
            elif escaped:
                escaped = False
            elif c == "'":
                in_single_quote = False
            i += 1
            continue
        elif in_double_quote:
            result += c
            if not escaped and c == '\\':
                escaped = True
            elif escaped:
                escaped = False
            elif c == '"':
                in_double_quote = False
            i += 1
            continue

        # Handle comments
        if in_single_line_comment:
            if c == '\n':
                in_single_line_comment = False
                result += c
            i += 1
            continue
        elif in_multi_line_comment:
            if c == '*' and next_c == '/':
                in_multi_line_comment = False
                i += 2
            else:
                i += 1
            continue

        # Detect start of string literals
        if c == "'" and not in_double_quote:
            in_single_quote = True
            result += c
            i += 1
            continue
        elif c == '"' and not in_single_quote:
            in_double_quote = True
            result += c
            i += 1
            continue

        # Detect start of comments
        if c == '/' and next_c == '/':
            in_single_line_comment = True
            i += 2
            continue
        elif c == '/' and next_c == '*':
            in_multi_line_comment = True
            i += 2
            continue
        elif c == '#' and not in_single_quote and not in_double_quote:
            in_single_line_comment = True
            i += 1
            continue

        # Copy other characters
        result += c
        i += 1

    return result


# Extract a section from a test case
def extract_sec(test, section):
    if section not in test:
        return ""
    start_idx = test.find(section) + len(section)
    end_match = re.search("--([_A-Z]+)--", test[start_idx:])
    end_idx = end_match.start() if end_match else len(test) - 1
    return test[start_idx:start_idx + end_idx].strip("\n")

seeds = os.listdir("../phpt_seeds/")

# Initialize the SQLite database
conn = sqlite3.connect('seeds.db')
cursor = conn.cursor()

# Create the table if it doesn't exist
cursor.execute('''
    CREATE TABLE IF NOT EXISTS seeds (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        phpcode TEXT,
        variable TEXT,
        dataflow TEXT,
        description TEXT,
        configuration TEXT,
        skipif TEXT,
        extension TEXT,
        secondary BOOL
    )
''')

count = 0
print("dataflow pre-processing")
for seed in seeds:
    count += 1
    f = open(f"../phpt_seeds/{seed}","r",encoding="iso_8859_1")
    phpt = f.read()
    f.close()
    # when fuse, such tests should be placed in the second place
    if "--EXPECTF--" in phpt or "declare(" in phpt or "namespace" in phpt:
        secondary = True
    else:
        secondary = False
    description = extract_sec(phpt, "--TEST--")
    configuration = extract_sec(phpt, "--INI--")
    skipif = extract_sec(phpt, "--SKIPIF--")
    phpcode = extract_sec(phpt, "--FILE--")
    extension = extract_sec(phpt, "--EXTENSION--")
    phpcode = remove_php_comments(phpcode)
    f = open(f"/tmp/tmp.php", "w", encoding="iso_8859_1")
    f.write(phpcode)
    f.close()
    dataflow = PHPFastDataflow()
    variables, dataflows = dataflow.analyze(phpcode)
    # this is for PHP-AST dataflow analysis
    # dataflows = get_php_dataflow_groups("/tmp/tmp.php")
    # variables = set()
    # for i in dataflows:
    #     for j in i:
    #         variables.add(j)
    # variables = list(variables)
    cursor.execute('''
        INSERT INTO seeds (phpcode, variable, dataflow, description, configuration, skipif, extension, secondary)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ''', (phpcode, str(variables), str(dataflows), description, configuration, skipif, extension, secondary))
    
# Commit the changes and close the connection
conn.commit()
conn.close()