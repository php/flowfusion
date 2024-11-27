import re

"""
dataflow.py is a module for conducting lightweight source code analysis on PHP code.
It performs a very coarse-grained dataflow extraction, which is different from traditional
taint analysis. The focus is on tolerance to false positives (FP) and false negatives (FN)
in the context of fuzz testing.
"""

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
        self.extract_dataflow()  # Extract dataflows between variables
        return self.variables
