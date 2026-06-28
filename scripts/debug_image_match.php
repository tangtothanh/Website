<?php
require __DIR__ . '/../App/functions.php';

$mapFile = __DIR__ . '/../config/image_map.php';
$cfg = is_file($mapFile) ? include $mapFile : [];

$tests = [
    'Trà Sữa Matcha',
    'Cà Phê Đen Đá',
    'Bánh Chuối',
];

foreach ($tests as $t) {
    $productKey = normalize_text($t);
    echo "Test: $t\n";
    echo "  normalized: $productKey\n";
    foreach ($cfg as $k => $path) {
        $mapKey = normalize_text($k);
        if ($k === '') {
            continue;
        }
        if ($productKey === $mapKey) {
            echo "  exact match key='$k' mapKey='$mapKey' => $path\n";
        }
        if ($productKey !== '' && strpos($productKey, $mapKey) !== false) {
            echo "  product contains mapKey key='$k' mapKey='$mapKey' => $path\n";
        }
        if ($productKey !== '' && strpos($mapKey, $productKey) !== false) {
            echo "  mapKey contains product key='$k' mapKey='$mapKey' => $path\n";
        }
    }
    echo "  function result: " . product_image_url(['sp_ten' => $t, 'sp_hinh' => ''], '') . "\n";
    echo "\n";
}
