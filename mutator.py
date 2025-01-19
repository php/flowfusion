from random import randint, choice, shuffle, random
import re
import subprocess
import os


class Mutator:
    """
    This class aims to mutate the PHPT (PHP Test) file, specifically targeting the --FILE-- section.
    The goal is to introduce mutations in various parts of the code:
    - Special integers: -1, 0, PHP_INT_MAX, PHP_INT_MIN
    - Special characters: random byte, special encoding
    - Special class variables: random magic class variables
    - Special values: null values, etc.
    """

    def __init__(self):
        pass

    def extract_sec(self, test, section):
        """
        Extract a specific section from the PHPT file, identified by the section header.
        Args:
            test: The full PHPT file content.
            section: The section to extract (e.g., --FILE--).

        Returns:
            The content of the specified section or an empty string if not found.
        """
        if section not in test:
            return ""
        start_idx = test.find(section) + len(section)
        x = re.search("--([_A-Z]+)--", test[start_idx:])
        end_idx = x.start() if x != None else len(test) - 1
        ret = test[start_idx:start_idx + end_idx].strip("\n")
        return ret

    """
    `mr` means `mutation rule`
    Below are various mutation rules applied to the PHP code.
    """

    def _mr_arith_operators(self, phpcode):
        """
        Randomly mutate arithmetic operators such as +, -, *, /, %, **.
        99.9% of the time, this function will return the original PHP code without changes.
        """
        if random() > 0.001:
            return phpcode

        # Regular expression to match arithmetic operators
        target_regex = r'\+\+|[-*/%]|\*\*'
        replacements = ['+', '-', '*', '/', '%', '**']
        victims = re.findall(target_regex, phpcode)

        if len(victims) == 0:
            return phpcode

        # Randomly replace one arithmetic operator
        phpcode = phpcode.replace(choice(victims), choice(replacements))
        return phpcode

    def _mr_assign_operators(self, phpcode):
        """
        Randomly mutate assignment operators such as +=, -=, *=, /=, %=.
        99.9% of the time, this function will return the original PHP code without changes.
        """
        if random() > 0.001:
            return phpcode

        # Regular expression to match assignment operators
        target_regex = r'\+=|-=|\*=|/=|%='
        replacements = ['+=', '-=', '*=', '/=', '%=']

        # Find all assignment operators in the PHP code
        victims = re.findall(target_regex, phpcode)
        if len(victims) == 0:
            return phpcode

        # Randomly select a victim and a replacement operator
        victim = choice(victims)
        replace = choice([op for op in replacements if op != victim])

        # Replace a randomly chosen occurrence of the victim operator
        phpcode = re.sub(re.escape(victim), replace, phpcode, 1)
        return phpcode

    def _mr_logical_operators(self, phpcode):
        """
        Randomly mutate logical operators such as 'and', 'or', 'xor', '&&', '||'.
        99.9% of the time, this function will return the original PHP code without changes.
        """
        if random() > 0.001:
            return phpcode

        # Regular expression to match logical operators
        target_regex = r'\band\b|\bor\b|\bxor\b|&&|\|\|'
        replacements = ['and', 'or', 'xor', '&&', '||']

        # Find all logical operators in the PHP code
        victims = re.findall(target_regex, phpcode)
        if len(victims) == 0:
            return phpcode

        # Randomly select a victim and a replacement operator
        victim = choice(victims)
        replace = choice([op for op in replacements if op != victim])

        # Replace a randomly chosen occurrence of the logical operator
        phpcode = re.sub(re.escape(victim), replace, phpcode, 1)
        return phpcode

    def _mr_integer(self, phpcode):
        """
        Randomly mutate integer expressions to special boundary values like -1, 0, PHP_INT_MAX, etc.
        99.9% of the time, this function will return the original PHP code without changes.
        """
        if random() > 0.001:
            return phpcode

        # Regular expression to match integers (in decimal, octal, or hexadecimal)
        target_regex = r'(?<![a-zA-Z0-9_])(?:0x[0-9a-fA-F]+|0[0-7]*|[1-9][0-9]*|0)(?![a-zA-Z0-9_])'
        replacements = ['-1', '0', 'PHP_INT_MAX', 'PHP_INT_MIN', 'PHP_FLOAT_MIN', 'PHP_FLOAT_MAX', 'NULL', 'NAN', 'INF']

        victims = re.findall(target_regex, phpcode)
        if len(victims) == 0:
            return phpcode

        # Randomly replace one occurrence of an integer
        victim = choice(victims)
        replace = choice(replacements)
        phpcode = re.sub(re.escape(victim), replace, phpcode, 1)
        return phpcode

    def _mr_string(self, phpcode):
        """
        Randomly mutate string literals with special values like random bytes or special encoding.
        99% of the time, this function will return the original PHP code without changes.
        """
        if random() > 0.01:
            return phpcode

        # Regular expression to match single and double-quoted strings
        target_regex = r"'([^'\\]+(\\.[^'\\]*)*)'|\"([^\"\\]+(\\.[^\"\\]*)*)\""
        replacements = [f"'{chr(randint(0, 255))}'", 'NULL', "''", "'?~K?~U'", "'test\\0test'"]

        # Find all string literals in the PHP code
        victims = re.findall(target_regex, phpcode)

        # Flatten the list to get the full match
        victims = [match[0] if match[0] else match[2] for match in victims]

        if len(victims) == 0:
            return phpcode

        # Randomly replace one occurrence of a string
        victim = choice(victims)
        replace = choice(replacements)
        phpcode = re.sub(re.escape(victim), replace, phpcode, 1)
        return phpcode

    def _mr_variable(self, phpcode):
        """
        Randomly mutate variables by replacing them with other variables.
        99.5% of the time, this function will return the original PHP code without changes.
        """
        if random() > 0.005:
            return phpcode

        # Regular expression to match variables
        target_regex = r'\$\w+'
        variables = re.findall(target_regex, phpcode)

        if len(variables) == 0:
            return phpcode

        # Randomly select a victim and a replacement variable
        victim = choice(variables)
        replace = choice(variables)

        # Replace a random occurrence of the victim variable
        occurrences = [m.start() for m in re.finditer(re.escape(victim), phpcode)]
        if not occurrences:
            return phpcode

        num_replacements = choice(range(1, len(occurrences) + 1))
        selected_replacements = set(choice(occurrences) for _ in range(num_replacements))

        result = []
        last_index = 0
        for i, char in enumerate(phpcode):
            if i in selected_replacements:
                result.append(phpcode[last_index:i])
                result.append(replace)
                last_index = i + len(victim)

        result.append(phpcode[last_index:])
        return ''.join(result)

    def mutate(self, phpcode):

        # Apply all mutation rules
        phpcode = self._mr_arith_operators(phpcode)
        phpcode = self._mr_assign_operators(phpcode)
        phpcode = self._mr_logical_operators(phpcode)
        phpcode = self._mr_integer(phpcode)
        phpcode = self._mr_string(phpcode)
        phpcode = self._mr_variable(phpcode)
        
        return phpcode
