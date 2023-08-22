## セットアップ方法

1. git clone https://github.com/nikic/php-ast
1. https://github.com/nikic/php-ast#installation

## 利用方法
1. `php php-ast-hashes.php 70 /path/to/format/dir ./old-ast-hash`
1. format php files (i.g. php-cs-fixer, ...)
1. `php php-ast-hashes.php 70 /path/to/format/dir ./new-ast-hash`
1. diff old-ast-hash new-ast-hash
