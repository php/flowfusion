## FlowFusion — A Dataflow-Driven Fuzzer

### What is FlowFusion?

FlowFusion is a fully automated, dataflow-driven fuzzing tool that detects various bugs (e.g., memory errors, undefined behaviors, assertion failures) in the PHP interpreter.

### How Does FlowFusion Work?

The core idea behind FlowFusion is to leverage **dataflow** as an efficient representation of the official `.phpt` test files maintained by PHP developers. FlowFusion merges two (or more) test cases to produce fused test cases with more complex code semantics. It interleaves the dataflows of multiple test cases, thereby combining their code contexts. This approach enables interactions among existing unit tests (which typically verify a single functionality) to create more intricate code paths—leading to more effective bug-finding.

**Why dataflow?**  
Around 96.1% of `.phpt` files exhibit sequential control flow (i.e., they execute without branching), which means control flow alone contributes little to the overall code semantics. By focusing on dataflow, FlowFusion captures the essential semantics of these test programs.

**Why effective?**  
1. With ~20K test cases, pairwise combinations already exceed 400M fused test cases; combining more than two grows this number exponentially. 
2. The interleaving process itself has randomness, offering multiple ways to connect two test cases.  
3. FlowFusion applies additional mutations and also fuzzes runtime configurations (e.g., JIT settings).

FlowFusion additionally fuzzes all defined functions and class methods in the context of the fused test cases. A SQLite3 database stores information on available functions, classes, methods, and their parameters to guide fuzzing.

Because FlowFusion relies on the official `.phpt` files, as soon as new tests are added, thousands of new fused tests can be generated. **This ensures FlowFusion remains current and continues to reveal new bugs over time**.

---

### Instructions

Below are the steps to fuzz the latest commit of `php-src` inside a Docker container.

1. **Start Docker**  
   ```bash
   docker run --name phpfuzz -dit 0599jiangyc/flowfusion:latest bash
   ```
   - Username: `phpfuzz`
   - Password: `phpfuzz`
   
   Then enter the container:
   ```bash
   docker exec -it phpfuzz bash
   ```

2. **Clone FlowFusion & Prepare**  
   Inside the container, clone the FlowFusion repository into `/home/phpfuzz/WorkSpace`:
   ```bash
   git clone https://github.com/php/flowfusion.git
   cd flowfusion
   ./prepare.sh
   ```
   *Note:* The preparation step can take several minutes.

3. **Start Fuzzing**  
   Use `tmux` to keep the session running in the background:
   ```bash
   tmux new-session -s fuzz 'bash'
   ```
   Then run FlowFusion:
   ```bash
   python3 main.py
   ```

4. **View Found Bugs**  
   To check for bugs:
   ```bash
   find ./bugs -name "*.out" | xargs grep -E "Sanitizer|Assertion "
   ```

---

### Bugs

FlowFusion has already discovered [hundreds of bugs](https://github.com/php/php-src/issues?q=author%3AYuanchengJiang%20) in the PHP interpreter.

### Research Paper

For a more detailed explanation, see the research paper:  
[Fuzzing the PHP Interpreter via Dataflow Fusion](https://yuanchengjiang.github.io/docs/flowfusion.pdf).

---