<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src') // Scan only the src directory
    ->name('*.php') // Scan only PHP files
    ->exclude('vendor'); // Exclude vendor directory

$config = new Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'], // Use short array syntax
    'binary_operator_spaces' => ['default' => 'single_space'], // Single space around binary operators
    'blank_line_after_namespace' => true, // Ensure there is one blank line after namespace declarations
    'blank_line_after_opening_tag' => true, // Ensure there is one blank line after the opening tag in PHP files
    'braces' => [
        'position_after_functions_and_oop_constructs' => 'next', // Place opening braces on a new line for functions and OOP constructs
        'position_after_anonymous_constructs' => 'next', // Place opening braces on a new line for anonymous functions
        'position_after_control_structures' => 'next', // Place opening braces on a new line for control structures
    ],
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline', // Ensure fully multiline for method arguments
        'keep_multiple_spaces_after_comma' => false, // Disallow multiple spaces after comma
    ],
    'no_trailing_whitespace' => true, // Disallow trailing whitespace
    'no_trailing_whitespace_in_comment' => true, // Disallow trailing whitespace in comments
    'single_blank_line_at_eof' => true, // Ensure there is a single blank line at the end of the file
    'single_import_per_statement' => true, // One import per statement
    'single_line_after_imports' => true, // Ensure there is a single line after import statements
    'single_quote' => true, // Use single quotes for strings where possible
    'whitespace_after_comma_in_array' => true, // Ensure there is a whitespace after comma in arrays
    'trailing_comma_in_multiline' => ['elements' => ['arrays']], // Add trailing comma in multiline arrays
    'trim_array_spaces' => true, // Trim spaces inside arrays
    'unary_operator_spaces' => true, // Ensure there is a space between unary operators and their operand
    'visibility_required' => ['elements' => ['property', 'method', 'const']], // Ensure visibility is declared for properties, methods, and constants
])
    ->setFinder($finder);
