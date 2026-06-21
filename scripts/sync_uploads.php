<?php
// Simple script to copy sample images from public/img into public/uploads with expected filenames
$base = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
$imgDir = $base . 'img' . DIRECTORY_SEPARATOR;
$uploadsDir = $base . 'uploads' . DIRECTORY_SEPARATOR;

$map = [
    // target => source (relative to public/img)
    'banh_chuoi.png' => 'banh/banh-chuoi.png',
    'tra_sua.png' => 'tra sua/Trà Sữa.png',
    'ca_phe.png' => 'ca phe/Cà Phê Đen Đá.png',
    'tra_cam.png' => 'tra trai cay/unnamed.png',
    'banh_flan.png' => 'banh/banh-flan.png'
];

foreach ($map as $target => $sourceRel) {
    $source = $imgDir . $sourceRel;
    $dest = $uploadsDir . $target;

    // Normalize path separators
    $source = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $source);

    if (file_exists($source)) {
        if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
        if (copy($source, $dest)) {
            echo "Copied: $sourceRel -> uploads/$target\n";
        } else {
            echo "Failed to copy: $sourceRel\n";
        }
    } else {
        echo "Source not found: $sourceRel\n";
    }
}

echo "Done.\n";
