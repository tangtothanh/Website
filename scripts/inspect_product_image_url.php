<?php
require __DIR__ . '/../App/functions.php';

if (!function_exists('product_image_url')) {
    echo "function not found\n";
    exit(1);
}

$f = new ReflectionFunction('product_image_url');
echo "file=" . $f->getFileName() . "\n";
echo "start=" . $f->getStartLine() . "\n";
echo "end=" . $f->getEndLine() . "\n";
$lines = file($f->getFileName());
for ($i = $f->getStartLine() - 1; $i < min($f->getEndLine(), count($lines)); $i++) {
    echo str_pad($i + 1, 4, ' ', STR_PAD_LEFT) . ': ' . $lines[$i];
}
