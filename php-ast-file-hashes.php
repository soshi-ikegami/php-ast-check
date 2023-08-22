<?php
require_once('php-ast/util.php');
require_once('./replace.php');

$version = (int)$argv[1];
$path = $argv[2];

$ast = ast\parse_file($path, $version);
$ast = explorer($ast);
$ast = ast_dump($ast);
$hash = md5($ast);
echo "{$hash},{$path}\n";
