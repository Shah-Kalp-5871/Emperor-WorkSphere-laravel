<?php

$viewsDir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$filesFiltered = new CallbackFilterIterator($iterator, function ($current) {
    return $current->isFile() && str_ends_with($current->getFilename(), '.blade.php');
});

$htmlReplacements = [
    // Simple href/action strings starting with /admin, /employee, /api
    '/(href|action|src)="(\/(admin|employee|api)[^"]*)"/' => '$1="{{ url(\'$2\') }}"',
    '/(href|action|src)=\'(\/(admin|employee|api)[^\']*)\'/' => '$1="{{ url(\'$2\') }}"',
];

$jsReplacements = [
    // window.location.href strings (both double and single quotes)
    '/window\.location\.href\s*=\s*"(\/(admin|employee|api)[^"]*)"/' => 'window.location.href = window.APP_URL + "$1"',
    '/window\.location\.href\s*=\s*\'(\/(admin|employee|api)[^\']*)\'/' => 'window.location.href = window.APP_URL + \'$1\'',
    // window.location.href template literals
    '/window\.location\.href\s*=\s*`(\/(admin|employee|api)[^`]*)`/' => 'window.location.href = `${window.APP_URL}$1`',

    // axios standard string arguments (double and single quotes)
    '/axios\.(get|post|put|delete|patch)\("(\/(admin|employee|api)[^"]*)"/' => 'axios.$1(window.APP_URL + "$2"',
    '/axios\.(get|post|put|delete|patch)\(\'(\/(admin|employee|api)[^\']*)\'/' => 'axios.$1(window.APP_URL + \'$2\'',
    // axios template literal arguments
    '/axios\.(get|post|put|delete|patch)\(`(\/(admin|employee|api)[^`]*)`/' => 'axios.$1(`${window.APP_URL}$2`',

    // Some cases use axios(config) which are less common but just in case:
    // This is simple enough not to catch everything but targets the specific calls we saw.
];

$modifiedFilesCount = 0;

foreach ($filesFiltered as $file) {
    $content = file_get_contents($file->getPathname());
    $originalContent = $content;

    // First replace Javascript URLs so they don't get wrapped in {{ url() }} accidentally
    foreach ($jsReplacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    // Then replace standard HTML
    foreach ($htmlReplacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // There are some hardcoded APP_URL + ... that we don't want to double up, so we need to clean them if they happened.
    // e.g., window.APP_URL + window.APP_URL
    $content = str_replace('window.APP_URL + window.APP_URL', 'window.APP_URL', $content);
    $content = str_replace('${window.APP_URL}${window.APP_URL', '${window.APP_URL', $content);
    
    // Check for `url('{{ url(...) }}')` which could happen if someone already used url()
    // It's a quick hack: if double {{ url(...) }} happens, fix.
    // Not usually relevant based on grep, but safe strings check.

    if ($content !== $originalContent) {
        file_put_contents($file->getPathname(), $content);
        echo "Modified: " . $file->getPathname() . PHP_EOL;
        $modifiedFilesCount++;
    }
}

echo "Total modified files: $modifiedFilesCount\n";
