import os
import subprocess

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

        return reducedphp, reduced_config



if __name__ == "__main__":

    # Define the path to the test PHP file
    testpath = "/tmp/test.php"

    #
    phppath = "/home/phpfuzz/WorkSpace/flowfusion/php-src/sapi/cli/php"

    # Configuration options for the PHP test run
    config = '-d "zend_extension=/home/phpfuzz/WorkSpace/flowfusion/php-src/modules/opcache.so" -d "opcache.enable=1" -d "opcache.enable_cli=1" -d "opcache.jit=1254"'
    config = '-d "opcache.cache_id=worker26" -d "output_handler=" -d "open_basedir=" -d "disable_functions=" -d "output_buffering=Off" -d "error_reporting=30719" -d "display_errors=1" -d "display_startup_errors=1" -d "log_errors=0" -d "html_errors=0" -d "track_errors=0" -d "report_memleaks=1" -d "report_zend_debug=0" -d "docref_root=" -d "docref_ext=.html" -d "error_prepend_string=" -d "error_append_string=" -d "auto_prepend_file=" -d "auto_append_file=" -d "ignore_repeated_errors=0" -d "precision=14" -d "serialize_precision=-1" -d "memory_limit=128M" -d "opcache.fast_shutdown=0" -d "opcache.file_update_protection=0" -d "opcache.revalidate_freq=0" -d "opcache.jit_hot_loop=1" -d "opcache.jit_hot_func=1" -d "opcache.jit_hot_return=1" -d "opcache.jit_hot_side_exit=1" -d "opcache.jit_max_root_traces=100000" -d "opcache.jit_max_side_traces=100000" -d "opcache.jit_max_exit_counters=100000" -d "opcache.protect_memory=1" -d "zend.assertions=1" -d "zend.exception_ignore_args=0" -d "zend.exception_string_param_max_len=15" -d "short_open_tag=0" -d "extension_dir=/home/phpfuzz/WorkSpace/flowfusion/php-src/modules/" -d "zend_extension=/home/phpfuzz/WorkSpace/flowfusion/php-src/modules/opcache.so" -d "session.auto_start=0" -d "zlib.output_compression=Off" -d "opcache.enable=1" -d "opcache.enable_cli=1" -d "opcache.jit=1235"'

    # The expected bug output that we are trying to reproduce
    bug_output = "heap-use-after-free"
    bug_output = 'Sanitizer'
    bug_output = 'zend_types.h:1382'
    bug_output = 'spl_array.c:79'

    print(reduce_php(testpath, phppath, config, bug_output))
