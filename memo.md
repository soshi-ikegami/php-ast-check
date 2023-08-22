## array and []
### array
```php
<?php

$a = array();
```
↓
```
AST_STMT_LIST
    0: AST_ASSIGN
        var: AST_VAR
            name: "a"
        expr: AST_ARRAY
            flags: ARRAY_SYNTAX_SHORT (3)
```
### []
```php
<?php

$a = [];
```
↓
```
AST_STMT_LIST
    0: AST_ASSIGN
        var: AST_VAR
            name: "a"
        expr: AST_ARRAY
            flags: ARRAY_SYNTAX_LONG (2)
```

## doc
### space
```php
<?php
/**
 *  test
 */
function test()
{
}
```
↓
```
AST_STMT_LIST
    0: AST_FUNC_DECL
        name: "test"
        docComment: "/**
         *  test
         */"
        params: AST_PARAM_LIST
        stmts: AST_STMT_LIST
        returnType: null
        __declId: 0
```

### no space
```php
<?php

/**
 * test
 */
function test()
{
}
```
↓
```
AST_STMT_LIST
    0: AST_FUNC_DECL
        name: "test"
        docComment: "/**
         * test
         */"
        params: AST_PARAM_LIST
        stmts: AST_STMT_LIST
        returnType: null
        __declId: 0
```

## FALSE and false
### FALSE
```php
<?php

$a = FALSE;
```
↓
```
AST_STMT_LIST
    0: AST_ASSIGN
        var: AST_VAR
            name: "a"
        expr: AST_CONST
            name: AST_NAME
                flags: NAME_NOT_FQ (1)
                name: "FALSE"
```
### false
```php
<?php

$a = false;
```
↓
```
AST_STMT_LIST
    0: AST_ASSIGN
        var: AST_VAR
            name: "a"
        expr: AST_CONST
            name: AST_NAME
                flags: NAME_NOT_FQ (1)
                name: "false"
```

## elseif and else if
### elseif
```php
<?php

if (true) {
    $a = 1;
} elseif (true){
    $a = 2;
}
```
↓
```
AST_STMT_LIST
    0: AST_IF
        0: AST_IF_ELEM
            cond: AST_CONST
                name: AST_NAME
                    flags: NAME_NOT_FQ (1)
                    name: "true"
            stmts: AST_STMT_LIST
                0: AST_ASSIGN
                    var: AST_VAR
                        name: "a"
                    expr: 1
        1: AST_IF_ELEM
            cond: AST_CONST
                name: AST_NAME
                    flags: NAME_NOT_FQ (1)
                    name: "true"
            stmts: AST_STMT_LIST
                0: AST_ASSIGN
                    var: AST_VAR
                        name: "a"
                    expr: 2

```
### else if
```php
<?php

if (true) {
    $a = 1;
} else if (true){
    $a = 2;
}

```
↓
```
AST_STMT_LIST
    0: AST_IF
        0: AST_IF_ELEM
            cond: AST_CONST
                name: AST_NAME
                    flags: NAME_NOT_FQ (1)
                    name: "true"
            stmts: AST_STMT_LIST
                0: AST_ASSIGN
                    var: AST_VAR
                        name: "a"
                    expr: 1
        1: AST_IF_ELEM
            cond: null
            stmts: AST_STMT_LIST
                0: AST_IF
                    0: AST_IF_ELEM
                        cond: AST_CONST
                            name: AST_NAME
                                flags: NAME_NOT_FQ (1)
                                name: "true"
                        stmts: AST_STMT_LIST
                            0: AST_ASSIGN
                                var: AST_VAR
                                    name: "a"
                                expr: 2
```

### INFO: else { if () }
```php
<?php

if (true) {
    $a = 1;
} else {
    if (true){
        $a = 2;
    }
}
```
↓
```
AST_STMT_LIST
    0: AST_IF
        0: AST_IF_ELEM
            cond: AST_CONST
                name: AST_NAME
                    flags: NAME_NOT_FQ (1)
                    name: "true"
            stmts: AST_STMT_LIST
                0: AST_ASSIGN
                    var: AST_VAR
                        name: "a"
                    expr: 1
        1: AST_IF_ELEM
            cond: null
            stmts: AST_STMT_LIST
                0: AST_IF
                    0: AST_IF_ELEM
                        cond: AST_CONST
                            name: AST_NAME
                                flags: NAME_NOT_FQ (1)
                                name: "true"
                        stmts: AST_STMT_LIST
                            0: AST_ASSIGN
                                var: AST_VAR
                                    name: "a"
                                expr: 2
```



## $a === 2 and 2 === $a
### 2 === $a 
```php
<?php

$a = 1;
if (2 === $a) {

}
```
↓
```
AST_STMT_LIST
    0: AST_ASSIGN
        var: AST_VAR
            name: "a"
        expr: 1
    1: AST_IF
        0: AST_IF_ELEM
            cond: AST_BINARY_OP
                flags: BINARY_IS_IDENTICAL (16)
                left: 2
                right: AST_VAR
                    name: "a"
            stmts: AST_STMT_LIST
```
### $a === 2
```php
<?php

$a = 1;
if ($a === 2) {

}
```
↓
```
AST_STMT_LIST
    0: AST_ASSIGN
        var: AST_VAR
            name: "a"
        expr: 1
    1: AST_IF
        0: AST_IF_ELEM
            cond: AST_BINARY_OP
                flags: BINARY_IS_IDENTICAL (16)
                left: AST_VAR
                    name: "a"
                right: 2
            stmts: AST_STMT_LIST
```
