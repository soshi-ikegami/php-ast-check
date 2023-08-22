<?php
require_once('php-ast/util.php');
require_once('./replace.php');

function getFileList($dir) {
    $files = glob(rtrim($dir, '/') . '/*');
    $list = [];
    foreach ($files as $file) {
        if (is_file($file)) {
            $list[] = $file;
        }
        if (is_dir($file)) {
            $list = array_merge($list, getFileList($file));
        }
    }
    return $list;
}

$version = (int)$argv[1];
$paths_file = $argv[2];
$hashes_file = $argv[3];

$paths = getFileList($paths_file);
$hashes = '';
foreach ($paths as $path) {
    $hash = '';
    if (file_exists($path)) {
        $ast = ast\parse_file($path, $version);
        $ast = explorer($ast);
        $hash = md5(ast_dump($ast));
    }
    $hashes .= "{$hash},{$path}\n";
}
file_put_contents($hashes_file, $hashes);
