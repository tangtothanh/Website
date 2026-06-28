<?php
require __DIR__ . '/../App/functions.php';

$mapFile = __DIR__ . '/../config/image_map.php';
$cfg = is_file($mapFile) ? include $mapFile : [];
echo "mapFile=" . realpath($mapFile) . "\n";
echo "cfgType=" . gettype($cfg) . " count=" . count($cfg) . "\n";

$tests = [
    'Trà Sữa Truyền Thống',
    'Trà Sữa Matcha',
    'Cà Phê Đen Đá',
    'Bánh Chuối',
    'Trà Cam',
];

foreach ($tests as $t) {
    $productName = normalize_text($t);
    echo "Product: $t\n";
    echo "  normalized: $productName\n";
    echo "  config exact match: " . (isset($cfg[$productName]) ? $cfg[$productName] : 'NONE') . "\n";
    foreach ($cfg as $k => $path) {
        if ($k === '') continue;
        if (strpos($productName, $k) !== false || strpos($k, $productName) !== false) {
            echo "  substring match with key '$k' -> $path\n";
            break;
        }
    }
    $url = product_image_url(['sp_ten' => $t, 'sp_hinh' => ''], '');
    echo "  product_image_url result: $url\n\n";
}
