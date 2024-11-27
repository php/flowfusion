## FlowFusion

FlowFusion is a fully automated fuzzing tool that detects various bugs (memory errors, undefined behaviors, assertion failures) in the PHP interpreter. 

The core idea behing FlowFusion is to leverage **dataflow** as an efficient representation of test cases (.phpt files) maintained by PHP developers, merging two (or more) test cases to produce fused test cases with more complex code semantics. We connect two (or more) test cases via interleaving their dataflows, i.e., bring the code context from one test case to another. This enables interactions among existing test cases, which are mostly the unit tests verifying one single functionality, making fused test cases interesting with merging code semantics.

> Why dataflow? Around 96.1% phpt files exhibit sequential control flow, executing without branching. This finding suggests that control flow contributes little to the overall code semantics. Therefore, we recognize that the code semantics of the official test programs can be effectively represented using only dataflow.

The search space of FlowFusion is huge, which means it might take months to cover all possible combinations. Reasons for huge search space are three-fold: (i) two random combinations of around 20,000 test cases can generate 400,000,000 test cases, we can combine even more; (ii) the interleaving has randomness, given two test cases, there could be multiple way to connect them; and (iii) FlowFusion also mutates the test case, fuzzes the runtime environment/configuration like JIT.

FlowFusion additionally fuzzes all defined functions and class methods using the code contexts of fused test cases. Available functions, classes, methods are pre-collected and stored in sqlite3 with necessary information like the number of parameters.

FlowFusion will never be out-of-dated if phpt files keep updating. Any new single test can bring thousands of new fused tests.

Below are instructions to fuzz the latest commit of php-src

* start docker, we suggest fuzzing inside docker (user:phpfuzz pwd:phpfuzz)
```
docker run --name phpfuzz -dit 0599jiangyc/flowfusion:latest bash
```
and goto the docker
```
docker exec -it phpfuzz bash
```

* inside the docker, clone flowfusion in /home/phpfuzz/WorkSpace
```bash
git clone https://github.com/php/flowfusion.git
```
or
```bash
git clone git@github.com:php/flowfusion.git
```
then (this takes some minutes)
```bash
cd flowfusion; ./prepare.sh
```
and start fuzzing on tmux
```bash
tmux new-session -s fuzz 'bash'
```
```bash
tmux-shell$ python3 main.py
```

* you can use the following command to view bugs:
```
find ./bugs -name "*.out" | xargs grep -E "Sanitizer|Assertion "
```
