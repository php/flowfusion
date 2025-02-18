import os

# Change directory to 'php-src' where PHP source code is located
os.chdir("./php-src/")

# Create a directory to store merged test files
os.system("mkdir ./tests/merged/")

# Path to the log file that contains a list of PHPT file paths
phpts_filepath = "/tmp/flowfusion-prepare.log"

# Read the PHPT file paths from the log file
with open(phpts_filepath, "r") as f:
    phpts = f.read().strip("\n").split("\n")

# Begin preparing PHPT seeds
print("Preparing PHPT seeds")
for each_phpt in phpts:
    # Move each PHPT file from its current location to the 'phpt_seeds' directory
    os.system(f"mv {each_phpt} ../phpt_seeds/")

print("PHPT seeds are ready")

# List to store the unique folders that contain PHPT files
folders = []

# Extract folder paths from the list of PHPT files
for eachline in phpts:
    folder = "/".join(eachline.split("/")[:-1]) + "/"
    if folder not in folders:
        folders.append(folder)

# Begin preparing dependencies by copying required files from each folder
print("Preparing dependencies")
for each_folder in folders:
    if each_folder=='/':
        continue
    # Copy all files from each folder to 'phpt_deps' directory
    os.system(f"cp -r {each_folder}* ../phpt_deps 2>/dev/null")

print("Dependencies are ready")

print("===start configuring===")
os.system("./buildconf")
os.system('CC="clang-12" CXX="clang++-12" CFLAGS="-DZEND_VERIFY_TYPE_INFERENCE" CXXFLAGS="-DZEND_VERIFY_TYPE_INFERENCE" ./configure --enable-debug --enable-address-sanitizer --enable-undefined-sanitizer --enable-re2c-cgoto --enable-fpm --enable-litespeed --enable-phpdbg-debug --enable-zts --enable-bcmath --enable-calendar --enable-dba --enable-dl-test --enable-exif --enable-ftp --enable-gd --enable-gd-jis-conv --enable-mbstring --enable-pcntl --enable-shmop --enable-soap --enable-sockets --enable-sysvmsg --enable-zend-test --with-zlib --with-bz2 --with-curl --with-enchant --with-gettext --with-gmp --with-mhash --with-ldap --with-libedit --with-readline --with-snmp --with-sodium --with-xsl --with-zip --with-mysqli --with-pdo-mysql --with-pdo-pgsql --with-pgsql --with-sqlite3 --with-pdo-sqlite --with-webp --with-jpeg --with-freetype --enable-sigchild --with-readline --with-pcre-jit --with-iconv')
print("configuring finished")
print("start compiling")
os.system("make -j16 --silent")
print("compiling finished")
if os.path.exists("./sapi/cli/php"):
    print("compile finished!")
else:
    print("compile failed!")
    exit(-1)

os.chdir("../knowledges/")

print("preparing knowledges")

os.system("../php-src/sapi/cli/php ./function.php")

os.system("python3 function.py")

os.system("../php-src/sapi/cli/php ./class.php")

os.system("python3 class.py")

os.system("python3 seed-preprocessing.py")

print("all ready!")
