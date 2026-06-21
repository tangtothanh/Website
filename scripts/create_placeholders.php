<?php
$uploadsDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
// 1x1 transparent PNG
$png_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=';
$files = ['banh_chuoi.png','tra_sua.png','ca_phe.png','tra_cam.png','banh_flan.png'];
foreach ($files as $f) {
    $path = $uploadsDir . $f;
    if (!file_exists($path)) {
        file_put_contents($path, base64_decode($png_base64));
        echo "Created placeholder: uploads/$f\n";
    } else {
        echo "Exists: uploads/$f\n";
    }
}
echo "Done.\n";
