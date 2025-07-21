<?php
return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules(
        [
            "@PSR1" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/PSR1.rst
            "@PSR2" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/PSR2.rst
            "@Symfony" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/Symfony.rst
            "psr_autoloading" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/basic/psr_autoloading.rst
            "align_multiline_comment" => ["comment_type" => "phpdocs_only"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/align_multiline_comment.rst
            "phpdoc_to_comment" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_to_comment.rst
            "no_superfluous_phpdoc_tags" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/no_superfluous_phpdoc_tags.rst
            "array_indentation" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/whitespace/array_indentation.rst
            "array_syntax" => ["syntax" => "short"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/array_notation/array_syntax.rst
            "cast_spaces" => ["space" => "none"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/cast_notation/cast_spaces.rst
            "concat_space" => ["spacing" => "one"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/operator/concat_space.rst
            "compact_nullable_type_declaration" => true,
            // "compact_nullable_typehint" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/whitespace/compact_nullable_typehint.rst
            "declare_equal_normalize" => ["space" => "single"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/language_construct/declare_equal_normalize.rst
            "increment_style" => ["style" => "post"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/operator/increment_style.rst
            "list_syntax" => ["syntax" => "short"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/list_notation/list_syntax.rst
            "echo_tag_syntax" => ["format" => "long"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/php_tag/echo_tag_syntax.rst
            "phpdoc_add_missing_param_annotation" => ["only_untyped" => false],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_add_missing_param_annotation.rst
            "phpdoc_align" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_align.rst
            "phpdoc_no_empty_return" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_no_empty_return.rst
            "phpdoc_order" => true,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_order.rst
            "phpdoc_no_useless_inheritdoc" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/phpdoc/phpdoc_no_useless_inheritdoc.rst
            "protected_to_private" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/class_notation/protected_to_private.rst
            "yoda_style" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/control_structure/yoda_style.rst
            "method_argument_space" => ["on_multiline" => "ensure_fully_multiline"],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/function_notation/method_argument_space.rst
            "ordered_imports" => [
                "sort_algorithm" => "alpha",
                "imports_order" => ["class", "const", "function"]
            ],
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/import/ordered_imports.rst
            "single_line_throw" => false,
            // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/function_notation/single_line_throw.rst
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . "/bin")
            ->in(__DIR__ . "/src")
            ->in(__DIR__ . "/tests")
            ->name("*.php")
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );