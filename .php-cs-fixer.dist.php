<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/application')
    ->in(__DIR__.'/tests')
    ->exclude(['cache', 'logs']);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'long'],
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'trailing_comma_in_multiline' => true,
        'no_trailing_whitespace' => true,
        'single_quote' => true,
    ])
    ->setFinder($finder);
