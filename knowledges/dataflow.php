<?php
declare(strict_types=1);

if ($argc !== 2) {
    exit(1);
}

$filename = $argv[1];

if (!file_exists($filename)) {
    exit(1);
}

$code = file_get_contents($filename);

use ast\Node;

$astVersion = 70; // Automatically use the correct AST version
$ast = ast\parse_code($code, $astVersion);

$parent = [];
$allVars = [];

function analyze(Node $node, array &$parent, array &$allVars)
{
    foreach ($node->children as $child) {
        if ($child instanceof Node) {
            switch ($child->kind) {
                case ast\AST_ASSIGN:
                    handleAssign($child, $parent, $allVars);
                    break;
                default:
                    analyze($child, $parent, $allVars);
            }
        } elseif ($child !== null) {
            // Handle simple variable usage (e.g., variable declarations without assignments)
            $vars = getVars($child);
            foreach ($vars as $var) {
                $allVars[$var] = true;
            }
        }
    }
}

function handleAssign(Node $assignNode, array &$parent, array &$allVars)
{
    $varNode = $assignNode->children['var'];
    $exprNode = $assignNode->children['expr'];

    $lhsVars = getVars($varNode);
    $rhsVars = [];

    // Check if RHS is a function call
    if ($exprNode instanceof Node && $exprNode->kind === ast\AST_CALL) {
        $funcVars = getFuncCallVars($exprNode);
        $rhsVars = array_merge($rhsVars, $funcVars);
    } else {
        $rhsVars = getVars($exprNode);
    }

    // Collect all variables
    foreach ($lhsVars as $var) {
        $allVars[$var] = true;
    }
    foreach ($rhsVars as $var) {
        $allVars[$var] = true;
    }

    // Union LHS and RHS variables
    foreach ($lhsVars as $lhsVar) {
        foreach ($rhsVars as $rhsVar) {
            union($lhsVar, $rhsVar, $parent);
        }
    }
}

function getVars($node)
{
    $vars = [];
    if ($node instanceof Node) {
        if ($node->kind === ast\AST_VAR) {
            $vars[] = '$' . $node->children['name'];
        } else {
            foreach ($node->children as $child) {
                $vars = array_merge($vars, getVars($child));
            }
        }
    }
    return $vars;
}

function getFuncCallVars(Node $callNode)
{
    $vars = [];
    $argsNode = $callNode->children['args'];
    foreach ($argsNode->children as $arg) {
        $vars = array_merge($vars, getVars($arg));
    }
    return $vars;
}

// Union-Find Functions
function find($item, &$parent)
{
    if (!isset($parent[$item])) {
        $parent[$item] = $item;
    }
    if ($parent[$item] !== $item) {
        $parent[$item] = find($parent[$item], $parent);
    }
    return $parent[$item];
}

function union($item1, $item2, &$parent)
{
    $root1 = find($item1, $parent);
    $root2 = find($item2, $parent);
    if ($root1 !== $root2) {
        $parent[$root2] = $root1;
    }
}

analyze($ast, $parent, $allVars);

// Group variables by their root parent
$groups = [];
foreach ($allVars as $var => $_) {
    $root = find($var, $parent);
    $groups[$root][] = $var;
}

// Remove duplicates and sort the groups
foreach ($groups as &$group) {
    $group = array_unique($group);
    sort($group);
}
unset($group);

// Output the dataflow groups in a format that can be eval'd by Python
$group_strings = [];
foreach ($groups as $group) {
    $escaped_vars = array_map(function($var) {
        return "'" . addslashes($var) . "'";
    }, $group);
    $group_strings[] = "[" . implode(", ", $escaped_vars) . "]";
}
echo "[" . implode(", ", $group_strings) . "]";
