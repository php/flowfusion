import os
import glob
import time
import datetime
import shutil
import threading
from fuse import Fusion
from mutator import Mutator

# Class for handling PHP fuzzing process
class PHPFuzz:

    def __init__(self):
        """
        Initialize the PHPFuzz class with various configurations and settings.
        """
        # Configurations for different fuzzing features
        self.mutation = True
        self.apifuzz = True
        self.ini = True
        self.fusion = True

        # Coverage feedback (off by default due to overhead)
        self.coverage = False
        self.test_root = "/home/phpfuzz/WorkSpace/flowfusion"
        self.php_root = f"{self.test_root}/php-src"
        self.fused = f"{self.php_root}/tests/fused"
        self.mutated = f"{self.php_root}/tests/mutated"
        self.bug_folder = f"{self.test_root}/bugs/"
        self.log_path = "/tmp/test.log"  # Log path for test execution

        # Initialize necessary folders and files
        self.patch_run_test()
        self.backup_initials()
        self.check_target_exist()
        self.init_fused_folder()
        self.init_bug_folder()
        self.init_phpt_path()
        self.moveout_builtin_phpts()

        self.total_count = 1
        self.syntax_error_count = 0 

    # PHP may mess up folders
    def backup_initials(self):
        os.system(f"cp {self.php_root}/run-tests.php {self.test_root}/backup/")
        os.system(f"cp {self.php_root}/Makefile {self.test_root}/backup/")
        os.system(f"cp {self.php_root}/libtool {self.test_root}/backup/")

    # Patch the run-tests.php script to avoid conflicts
    def patch_run_test(self):
        os.chdir(self.php_root)
        os.system("sed -i 's/foreach (\$fileConflictsWith\[\$file\] as \$conflictKey) {/foreach (\$fileConflictsWith\[\$file\] as \$conflictKey) { continue;/g' ./run-tests.php")
        os.system("sed -i 's/proc_terminate(\$workerProcs\[\$i\]);/\/\/proc_terminate(\$workerProcs\[\$i\]);/' ./run-tests.php")
        os.system("sed -i 's/unset(\$workerProcs\[\$i\], \$workerSocks\[\$i\]);/\/\/unset(\$workerProcs\[\$i\], \$workerSocks\[\$i\]);/' ./run-tests.php")
        os.system("sed -i 's/foreach (\$test_files as \$i => \$file) {/foreach (\$test_files as \$i => \$file) { continue;/' ./run-tests.php")
        os.chdir(self.test_root)

    # Remove built-in PHPT files to avoid conflicts
    def moveout_builtin_phpts(self):
        os.system(f"find {self.php_root} -name '*.phpt' | xargs rm 2>/dev/null")

    # Initialize the path to PHPT files
    def init_phpt_path(self):
        os.system(f'find {self.test_root}/phpt_seeds/ -name "*.phpt" > {self.test_root}/testpaths')

    # Create the bug folder if it doesn't exist
    def init_bug_folder(self):
        if not os.path.exists(self.bug_folder):
            os.makedirs(self.bug_folder)

    # Check if the target PHP build exists
    def check_target_exist(self):
        if not os.path.exists(self.php_root):
            print(f"{self.php_root} not found..")
            exit(-1)

    # Clean and initialize the fused test folder
    def init_fused_folder(self):
        if os.path.exists(self.fused):
            try:
                os.system(f"rm -rf {self.fused}")
            except Exception as e:
                print(f"Cannot remove fused folder: {str(e)}")
                exit(-1)
        os.system(f"mkdir {self.fused}")

        # Check for dependencies in the phpt_deps folder
        dependency = f"{self.test_root}/phpt_deps"
        if not os.path.exists(dependency):
            print(f"{dependency} not found..")
            exit(-1)

        # Restore dependencies and initials
        os.system(f"cp -R {dependency}/* {self.fused}")
        os.system(f"cp {self.test_root}/backup/run-tests.php {self.php_root}/")
        os.system(f"cp {self.test_root}/backup/Makefile {self.php_root}/")
        os.system(f"cp {self.test_root}/backup/libtool {self.php_root}/")

    # Check if the PHP build exists
    def check_build(self):
        return os.path.exists(f"{self.php_root}/sapi/cli/php")

    # Parse the test log for failed tests and possible bugs
    def parse_log(self):
        known_crash_sites = ["leak"]

        with open(self.log_path, "r") as f:
            logs = f.read().strip("\n").split("\n")

        next_log_id = len(os.listdir(self.bug_folder)) + 1
        for eachlog in logs:
            # we only care failed fusion tests
            if "FAIL" not in eachlog or "tests/fused" not in eachlog:
                continue
            casepath = self.php_root + "/" + eachlog.split("[")[-1].split("]")[0].replace(".phpt", "")
            stdouterr = f"{casepath}.out"
            if not os.path.exists(stdouterr):
                continue
            with open(stdouterr, "r", encoding="iso_8859_1") as f:
                content = f.read()
                self.total_count += 1
                if "Parse error" in content:
                    self.syntax_error_count += 1
                if "leaked in" in content:
                    # we do not care about leaks for now
                    continue
                if "Sanitizer" in content or "(core dumped)" in content:
                    os.makedirs(f"{self.bug_folder}/{next_log_id}")
                    shutil.move(f"{casepath}.out", f"{self.bug_folder}/{next_log_id}/test.out")
                    shutil.move(f"{casepath}.php", f"{self.bug_folder}/{next_log_id}/test.php")
                    shutil.move(f"{casepath}.phpt", f"{self.bug_folder}/{next_log_id}/test.phpt")
                    shutil.move(f"{casepath}.sh", f"{self.bug_folder}/{next_log_id}/test.sh")
                    next_log_id += 1

    # Clean up test artifacts like logs and output files
    def clean(self):
        os.system(f"find {self.fused} -type f -name '*.log' -o -name '*.out' -o -name '*.diff' -o -name '*.sh' -o -name '*.php' -o -name '*.phpt' | xargs rm 2>/dev/null")

    # Collect coverage information at regular intervals
    def collect_cov(self, fuzztime):
        def run_coverage_collection():
            #os.system("python3 bot.py")
            os.chdir(self.php_root)
            cmd = f"gcovr -sr . -o /tmp/gcovr-{fuzztime}.xml --xml --exclude-directories 'ext/date/lib$$' -e 'ext/bcmath/libbcmath/.*' -e 'ext/date/lib/.*' -e 'ext/fileinfo/libmagic/.*' -e 'ext/gd/libgd/.*' -e 'ext/hash/sha3/.*' -e 'ext/mbstring/libmbfl/.*' -e 'ext/pcre/pcre2lib/.*' > /dev/null"
            os.system(cmd)
            os.chdir(self.test_root)
            with open(f"/tmp/gcovr-{fuzztime}.xml", "r") as f:
                x = f.read()
            self.coverage = float(x.split('line-rate="')[1].split('"')[0])
            print(f"Coverage: {self.coverage:.2%}")

        # Create a new thread for running coverage collection
        coverage_thread = threading.Thread(target=run_coverage_collection)
        coverage_thread.start()

    # Display runtime logs with current progress
    def runtime_log(self, seconds, rounds):
        bugs_found = len(os.listdir(f"{self.test_root}/bugs/"))
        print(f"\nTime: {int(seconds)} seconds | Bugs found: {bugs_found} | Tests count: {self.total_count} | Rounds executed: {rounds} | Syntax error rate: {float(self.syntax_error_count)/self.total_count} | Coverage: {self.coverage:.2%}")

    # Main function to execute the fuzzing process
    def main(self):
        if not self.check_build():
            print("php not build")
            exit()

        count = 0
        start = time.time()
        covtime = 60*60  # Interval for counting coverage (in seconds)
        fuzztime = 0
        self.coverage = 0

        fusion_thread = None

        print("Start flowfusion...")
        while True:
            count += 1
            if count % 30 == 0:
                self.init_fused_folder()
                os.system("pkill php")
                os.system("pkill phpdbg")
            self.clean()

            # Run the fusion process in a separate thread
            if self.fusion:
                if fusion_thread is None or not fusion_thread.is_alive():
                    phpFusion = Fusion(self.test_root, self.php_root, self.apifuzz, self.ini, self.mutation)
                    fusion_thread = threading.Thread(target=phpFusion.main)
                    fusion_thread.start()

            # Run tests and parse logs
            os.chdir(self.php_root)
            os.system(f"mv /tmp/fused*.phpt {self.php_root}/tests/fused/") # load fused tests
            os.system('timeout 30 make TEST_PHP_ARGS=-j16 test 2>/dev/null | grep "FAIL" > /tmp/test.log')
            os.system(f"chmod -R 777 {self.test_root} 2>/dev/null")
            os.chdir(self.test_root)
            self.parse_log()

            # Collect coverage periodically
            end = time.time()
            timelen = end - start
            if timelen > covtime + fuzztime:
                fuzztime += covtime
                self.collect_cov(fuzztime)

            # Log runtime information
            self.runtime_log(timelen, count)

# Initialize and run the fuzzing process
fuzz = PHPFuzz()
fuzz.main()
