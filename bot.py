# this file is to automatically generate bug reports

import os
import re
import json
import time
import html
import signal
from reduce import reduce_php


def handler(signum, frame):
    raise Exception("end of time")

test_root = "/home/phpfuzz/WorkSpace/flowfusion"

plain_text_bug_report = """
================
PHP Bug Report

**PHP Commit:**
{php_commit}

**Compiling Flags:**
{php_config}

**Crash Site:**
{crashsite}

**Keywords:**
{keyword}

**Reproducing config:**
{reducedconfig}

**Reproducing PHP (best-effort reduced):**
{reducedphp}

**Output:**
{bugout}

**Reproducing PHP:**
{bugphp}

**Reproducing PHPT:**
{bugphpt}

**This report is automatically generated via FlowFusion**
================
"""


# copy dependencies for reproducing
if os.path.exists("/tmp/flowfusion_reproducing/")==False:
    os.mkdir("/tmp/flowfusion_reproducing/")
os.system(f"cp -R {test_root}/phpt_deps/* /tmp/flowfusion_reproducing/")

# Change directory to the "bugs" folder
os.chdir(f"{test_root}/bugs")

# Find all '.out' files, search for 'Sanitizer' (excluding 'leak') and store the results in a log file
os.system("find ./ -name '*.out' | xargs grep -E 'Sanitizer|Assertion ' | grep -v 'leak' > /tmp/flowfusion_bug.log")

os.chdir(f"{test_root}")

print("Filtering finished")

# Initialize lists to store unique bug identifiers and bug information
identifiers = []
bugs_info = []

if not os.path.exists(f'{test_root}/bug_reports/'):
    os.mkdir(f'{test_root}/bug_reports/')

if os.path.exists(f'{test_root}/bug_reports/bugs.json'):
    with open(f'{test_root}/bug_reports/bugs.json', 'r') as file:
        bugs_info = json.load(file)
        identifiers = [bug['identifier'] for bug in bugs_info]

for each_existing_bug in bugs_info:
    each_existing_bug['new'] = 0

# Read the contents of the bug log file
with open('/tmp/flowfusion_bug.log', 'r') as f:
    bugs = f.read().strip('\n').split('\n')

# Regular expression to extract identifier patterns from the log
identifier_pattern = r"(\/php-src\/[^:]+:\d+)"


# last_modified_time = os.path.getmtime(file_path)

# Loop through each bug entry in the log
for eachbug in bugs:
    # Search for the identifier using the regular expression
    identifier = re.search(identifier_pattern, eachbug)
    if identifier:
        identifier = identifier.group()
        # If the identifier is new, add it to the identifiers list and create a bug entry
        if identifier not in identifiers:
            identifiers.append(identifier)
            bug_folder = eachbug.split('/')[1]
            last_modified_time = os.path.getmtime(f"{test_root}/bugs/{bug_folder}")
            readable_time = time.ctime(last_modified_time)
            bugs_info.append({
                "bugID": len(bugs_info) + 1,  # Assign a unique ID to each bug
                "identifier": identifier,  # Store the identifier (file path and line number)
                "details": [eachbug.split('/')[1]],
                "mtime": readable_time,
                "new": 1
            })
        else:
            # If the identifier already exists, update the existing bug entry
            bug_idx = identifiers.index(identifier)
            bug_folder = eachbug.split('/')[1]
            mtime = bugs_info[bug_idx]["mtime"]
            parsed_time = time.strptime(mtime, "%a %b %d %H:%M:%S %Y")
            # Convert struct_time to a timestamp (seconds since epoch)
            timestamp = time.mktime(parsed_time)
            last_modified_time = os.path.getmtime(f"{test_root}/bugs/{bug_folder}")
            if last_modified_time > timestamp:
                readable_time = time.ctime(last_modified_time)
                bugs_info[bug_idx]["mtime"] = readable_time

# Convert the bug information into a JSON format for further processing
# Load the list of bug information into a JSON-compatible Python dictionary
data = json.loads(str(bugs_info).replace("'", '"'))

# Pretty-print the JSON data to a file for easy readability
with open(f'{test_root}/bug_reports/bugs.json', 'w') as file:
    json.dump(data, file, indent=4)

#with open("/tmp/flowfusion-php-commit","r") as file:
#    php_commit =  file.read()

php_commit = "test"

#with open(f"{test_root}/php-src/config.log","r") as file:
#    while True:
#        line = file.readline()
#        if "./configure" in line:
#            php_config = line.strip(' ').strip('$')
#            break

php_config = "test"

with open(f"{test_root}/bug_reports/bugs.json", 'r') as file:
    data = json.load(file)

if os.path.exists(f"{test_root}/bugs")==False:
    print("Please run in flowfusion folder")
    exit()

errors = ["stack-overflow","stack-underflow","heap-buffer-overflow","null pointer","integer overflow","heap-use-after-free","SEGV","core dumped"]

# Accessing the parsed data
for bug in data:
    upload_bug_folder_name = bug['identifier'].split('/php-src/')[1].replace('/','_').replace('.','_').replace(':','_')
    # if bug['new']==0 and os.path.exists(f"{test_root}/../flowfusion-php.github.io/{upload_bug_folder_name}"):
    #     # sed -i -E 's/this bug has been detected for [0-9]+ times/this bug has been detected for 2 times/g' ./sapi_phpdbg_phpdbg_bp_c_132/index.html
    #     continue
    print(f"analyzing and uploading {upload_bug_folder_name}")
    bug_folder = f"./bugs/{bug['details'][0]}/"

    # get bugout
    f = open(f"{bug_folder}/test.out", "r", encoding="iso_8859_1")
    bugout = f.read()
    f.close()

    # get keywords
    keywords = []
    for error in errors:
        if error in bugout:
            keywords.append(error)

    dangerous = 0
    # if "heap-buffer-overflow" in keywords or "heap-use-after-free" in keywords:
    #     dangerous = 1

    # get bugphp
    f = open(f"{bug_folder}/test.php", "r")
    bugphp = f.read()
    f.close()

    # get bugphpt
    f = open(f"{bug_folder}/test.phpt", "r")
    bugphpt = f.read()
    f.close()

    # get bugsh
    f = open(f"{bug_folder}/test.sh", "r")
    bugsh = f.read()
    f.close()

    bug_outputs = ["UndefinedBehaviorSanitizer: undefined-behavior", "AddressSanitizer", "core dumped"]
    # get reducedphp
    os.system(f"cp {bug_folder}/test.php /tmp/flowfusion_reproducing/")
    bug_output = ""
    for each in bug_outputs:
        if each in bugout:
            bug_output = each
            break
    bug_config = ""
    for eachline in bugsh.split('\n'):
        if "gdb --args" in eachline:
            bug_config = eachline.split(' -d ')[1:]
            bug_config[-1] = bug_config[-1].split(' -f ')[0]
            bug_config = ' -d '+' -d '.join(bug_config)
            break
    
    signal.signal(signal.SIGALRM, handler)
    # set 5 mins for reducing one bug
    signal.alarm(300)
    try:
        reducedphp, reduced_config = reduce_php(
            testpath = "/tmp/flowfusion_reproducing/test.php",
            phppath = f"{test_root}/php-src/sapi/cli/php",
            config = bug_config,
            bug_output = bug_output
        )
    except:
        reducedphp = 'reducing timeout ..'
        reduced_config = 'reducing timeout ..'

    bug_report = plain_text_bug_report.format(
        php_commit = php_commit,
        php_config = php_config,
        crashsite = bug['identifier'],
        keyword = str(keywords),
        bugout = bugout,
        bugphp = bugphp,
        bugphpt = bugphpt,
        reducedconfig = reduced_config,
        reducedphp = reducedphp
    )

    f = open(f"{test_root}/bug_reports/{upload_bug_folder_name}.md", "w")
    f.write(bug_report)
    f.close()


