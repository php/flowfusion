import os
import subprocess

stdouterr = None

# Function to run the test command and check for bug presence
def run_test(cmd, bug_output):
    """
    Executes the provided command to run the PHP test and checks
    if the expected bug output or any sanitizer error appears.
    """
    # Run the command and capture the output
    try:
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True, encoding='iso-8859-1', timeout=10)
    except:
        return False


    # Check if the bug output or any sanitizer errors are in the stdout/stderr
    if not (bug_output in result.stdout or bug_output in result.stderr) and \
       ("LeakSanitizer" not in result.stdout and "LeakSanitizer" not in result.stderr):

        # If another sanitizer message shows up, print the error
        if "Sanitizer" in result.stdout or "Sanitizer" in result.stderr:
            print("Other error messages found:")
            print(result.stdout)
            print(result.stderr)
            # Uncomment below if you want to pause for input when this happens
            # input()

    if bug_output in result.stdout or bug_output in result.stderr:
        global stdouterr
        if stdouterr == None:
            stdouterr = result.stderr

    # Return True if the bug output is found in the test results
    return bug_output in result.stdout or bug_output in result.stderr

# Function to minimize the test case by removing lines
def minimize_testcase(lines, bug_output, testpath, reproduce_cmd):
    print("reducing .. it may cost some times")
    """
    Minimizes the test case by iteratively removing lines and checking
    if the bug still reproduces. Uses a stepwise approach for efficiency.
    """
    n = len(lines)
    step = max(n // 2, 1)  # Start with removing half of the lines at a time

    init_step = step

    # Reduce the number of lines step by step
    while step > 0:
        print(f"Current step: {step}")

        # Try removing 'step' lines at a time
        for i in range(0, n, step):
            temp_lines = lines[:i] + lines[i+step:]
            with open(testpath, "w") as f:
                f.write("\n".join(temp_lines))

            # If the bug reproduces, accept this as the minimized version
            if run_test(reproduce_cmd, bug_output) or run_test(reproduce_cmd, bug_output) or run_test(reproduce_cmd, bug_output):
                lines = temp_lines
                n = len(lines)
                break
        else:
            step //= 2  # If no further reduction is found, reduce step size

    return lines, init_step

# Function for further minimizing by removing multiple lines at a time
def further_minimize_testcase(lines, bug_output, testpath, reproduce_cmd):
    """
    Further minimizes the test case by removing 2 to 5 lines at a time
    and checking if the bug still reproduces.
    """
    n = len(lines)

    # Try removing 2 to 5 lines at a time
    for count in range(2, 6):
        # print(f"Trying to remove {count} lines at a time.")

        # Try removing 'count' lines from each part of the test case
        for i in range(n - count + 1):
            temp_lines = lines[:i] + lines[i+count:]
            with open(testpath, "w") as f:
                f.write("\n".join(temp_lines))

            # If the bug reproduces, accept this as the minimized version
            if run_test(reproduce_cmd, bug_output) or run_test(reproduce_cmd, bug_output) or run_test(reproduce_cmd, bug_output):
                lines = temp_lines
                n = len(lines)
                break

    return lines

def reduce_php(testpath, phppath, config, bug_output):
    reproduce_cmd = f'{phppath} {config} {testpath}'
    # Initial test to verify if the reproduce command triggers the bug
    if not run_test(reproduce_cmd, bug_output) and not run_test(reproduce_cmd, bug_output) and not run_test(reproduce_cmd, bug_output):
        return "bug not reproduced when reducing", "bug not reproduced when reducing"
    else:
        while True:
            # Read the original test file lines
            with open(testpath, "r") as f:
                lines = f.readlines()

            # Strip any extra whitespace or newlines
            lines = [line.strip() for line in lines]

            # Begin minimizing the test case by removing lines
            minimized_lines, init_step = minimize_testcase(lines, bug_output, testpath, reproduce_cmd)

            # Further minimize by removing multiple lines at once
            further_minimized_lines = further_minimize_testcase(minimized_lines, bug_output, testpath, reproduce_cmd)

            # Restore the original test case in the file
            with open(testpath, "w") as f:
                f.write("\n".join(further_minimized_lines))

            n = len(further_minimized_lines)
            step = max(n // 2, 1)
            if step==init_step:
                print("reducing php finished")
                break
        reducedphp = "\n".join(further_minimized_lines)

        # Initialize reduced_config with the full configuration
        reduced_config = config

        while True:
            # Split the configuration into individual options
            test_config = reduced_config.split(' -d ')
            # Remove any empty strings resulting from the split
            test_config = [c for c in test_config if c != '']
            # Store the length to check for changes after iteration
            before_reduced_config_len = len(reduced_config)
            # Flag to check if a shorter configuration is found
            found_shorter_config = False

            # Iterate over a copy of the list to avoid modifying it during iteration
            for i in range(len(test_config)):
                # Create a new configuration without the current option
                test_config_copy = test_config[:i] + test_config[i+1:]
                # Reconstruct the configuration string
                if test_config_copy:
                    configstr = ' -d ' + ' -d '.join(test_config_copy)
                else:
                    configstr = ''
                # Build the command to test
                test_cmd = f'{phppath} {configstr} {testpath}'
                # Run the test to see if the bug still occurs
                if run_test(test_cmd, bug_output) or run_test(test_cmd, bug_output) or run_test(test_cmd, bug_output):
                    # Update reduced_config if the bug still occurs
                    reduced_config = configstr
                    found_shorter_config = True
                    # Break to restart the while loop with the new reduced_config
                    break
            # If no shorter configuration is found, exit the loop
            if not found_shorter_config:
                break

        return reducedphp, reduced_config.strip('\n')



if __name__ == "__main__":

    # Define the path to the test PHP file, you need to move the php to the tmp
    # best to also copy all dependencies to /tmp for reproduce
    testpath = "/tmp/test.php"

    # default php path
    phppath = "/home/phpfuzz/WorkSpace/flowfusion/php-src/sapi/cli/php"

    # Configuration options for the PHP test run
    config = ''

    # The expected bug output that we are trying to reproduce
    # if sanitizers' alerts
    bug_output = 'Sanitizer'
    # if assertion failure
    # bug_output = 'core dumped'

    reducedphp, reduced_config = reduce_php(testpath, phppath, config, bug_output)

    reduced_config = f'./php-src/sapi/cli/php {reduced_config} ./test.php'

    # auto generate bug report
    report_template = "\nThe following code:\n\n```php\n{poc}\n```\n\nResulted in this output:\n```\n{stdouterr}\n```\n\nTo reproduce:\n```\n{config}\n```\n\nCommit:\n```\n{commit}\n```\n\nConfigurations:\n```\n{php_config}\n```\n\nOperating System:\n```\n{os}\n```\n\n*This report is automatically generated by [FlowFusion](https://github.com/php/flowfusion)*\n"

    os.system("cd /home/phpfuzz/WorkSpace/flowfusion/php-src; git rev-parse origin/master > /tmp/php_commit")
    f = open("/tmp/php_commit","r")
    commit = f.read()
    f.close()

    php_config = 'CC="clang-12" CXX="clang++-12" CFLAGS="-DZEND_VERIFY_TYPE_INFERENCE" CXXFLAGS="-DZEND_VERIFY_TYPE_INFERENCE" ./configure --enable-debug --enable-address-sanitizer --enable-undefined-sanitizer --enable-re2c-cgoto --enable-fpm --enable-litespeed --enable-phpdbg-debug --enable-zts --enable-bcmath --enable-calendar --enable-dba --enable-dl-test --enable-exif --enable-ftp --enable-gd --enable-gd-jis-conv --enable-mbstring --enable-pcntl --enable-shmop --enable-soap --enable-sockets --enable-sysvmsg --enable-zend-test --with-zlib --with-bz2 --with-curl --with-enchant --with-gettext --with-gmp --with-mhash --with-ldap --with-libedit --with-readline --with-snmp --with-sodium --with-xsl --with-zip --with-mysqli --with-pdo-mysql --with-pdo-pgsql --with-pgsql --with-sqlite3 --with-pdo-sqlite --with-webp --with-jpeg --with-freetype --enable-sigchild --with-readline --with-pcre-jit --with-iconv'

    os = "Ubuntu 20.04 Host, Docker 0599jiangyc/flowfusion:latest"

    bug_report = report_template.format(
        poc = reducedphp,
        stdouterr = stdouterr,
        config = reduced_config,
        commit = commit,
        php_config = php_config,
        os = os
    )

    print('\033[94m'+bug_report+'\033[0m')
