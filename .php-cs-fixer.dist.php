<?php declare(strict_types=1);

$header = <<<'EOF'
This file is part of phptailors/phpunit-extensions.

Copyright (c) Paweł Tomulik <pawel@tomulik.pl>

View the LICENSE file for full copyright and license information.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->in(__DIR__ . '/packages/*/src')
    ->in(__DIR__ . '/packages/*/tests')
    ->name('*.php')
;

$config = new PhpCsFixer\Config();

return $config
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => $header,
            'location' => 'after_declare_strict',
        ],
        'array_syntax' => ['syntax' => 'short'],
        'psr_autoloading' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'single_space'
            ],
        ],
        // 'phpdoc_to_comment' => true, didn't play well with annotations we
        // needed for psalm
        'phpdoc_to_comment' => false,
        'no_superfluous_phpdoc_tags' => false,
        'ordered_imports' => [
            'imports_order' => ['const', 'class', 'function'],
            'sort_algorithm' => 'alpha',
        ],
        'phpdoc_order' => [
            'order' => ['param', 'return', 'throws'],
        ],

        // 2026-03-20: php-cs-fixed changed the @PhPCsFixer rulesets
        // significantly between 3.54 and 3.94; for now we're disabling the
        // following rules to avoid huge code reformatting being made by the
        // new version of php-cs-fixer.
        'operator_linebreak' => false,
        'php_unit_data_provider_method_order' => false,
        'fully_qualified_strict_types' => false,
        'new_with_parentheses' => false,
        'string_implicit_backslashes' => false,
    ])
;
// vim: syntax=php sw=4 ts=4 et:
