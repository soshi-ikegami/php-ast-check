<?php

require_once('php-ast/util.php');

use ast\Node;

function explorer($ast)
{
    foreach ($ast->children as $i => &$child) {
        if (isset($child->children)) {
            formatDoc($child);
            formatConst($child);
            formatArray($child);
            formatElseIf((int)$i, $child, $ast);
            formatYoda($child);
            explorer($child);
        }
    }
    return $ast;
}

// phpdocのスペースやタブが増減するとASTのハッシュが変わるので削除する
function formatDoc(Node $child) {
    // AST_FUNC_DECL: 67 関数のDoc
    // AST_METHOD: 69 メソッドのDoc
    // AST_CLASS: 70 クラスのDoc
    // AST_CONST_DECL: 139 定数のDoc
    // AST_CLASS_CONST_DECL: 140 クラス定数のDoc
    // AST_PROP_ELEM: 775 プロパティ
    if (in_array($child->kind, [67, 69, 70, 139, 140, 775], true)) {
        if (isset($child->children["docComment"])) {
            // Docにスペースやタブが増減するとASTのハッシュが変わるので削除する
            $child->children["docComment"] = preg_replace('/　|\s+/', '', $child->children["docComment"]);
            if (preg_match('/\*{3,}/',$child->children["docComment"])) {
                $child->children["docComment"] = null;
            }
        }
    }
}

// 定数の値が大文字小文字で変わるとASTのハッシュが変わるので小文字に統一する
function formatConst(Node $child) {
    // AST_NAME: 2048 名前
    if ($child->kind === 2048) {
        if (in_array(strtolower($child->children['name']), ["null", "true", "false"], true)) {
            $child->children['name'] = strtolower($child->children['name']);
        }
    }
}

// array() -> []の変換でハッシュが変わるので置換する
function formatArray(Node $child) {
    // AST_ARRAY: 129 配列
    // ARRAY_SYNTAX_LONG: 2 []
    // ARRAY_SYNTAX_SHORT: 3 array()
    if ($child->kind === 129) {
        if ($child->flags === 3) {
            $child->flags = 2;
        }
    }
}

// else if -> elseifの変換でハッシュが変わるので変換する
function formatElseIf(int $index, Node $child, Node $parent) {
    // AST_STMT_LIST: 132
    // AST_IF: 133 if(全体)
    // AST_IF_ELEM: 535 if(パーツ) (if, elseif, else)
    // 一番最初の要素(if)ではない
    if ($child->kind === 535 && $index !== 0) {
        // condがnull → else or else if
        if (is_null($child->children['cond'])) {
            if (isset($child->children['stmts']->children)) {
                if (isset($child->children['stmts']->children[0])) {
                    $stmtChild = $child->children['stmts']->children[0];
                    if ($stmtChild->kind === 133 && is_array($stmtChild->children)) {
                        // 配列のコピー
                        foreach($stmtChild->children as $node) {
                            // indexの位置から上書きしていく
                            $parent->children[$index++] = $node;
                        }
                    }
                }
            }
        }
    }
}

// ヨーダ記法の変換でハッシュが変わるので先に入れ替える
function formatYoda(Node $child) {
    // AST_BINARY_OP: 521 比較演算子
    // AST_CONST: 257
    if ($child->kind === 521) {
        if (isset($child->children['left']) && isset($child->children['right'])) {
            // leftがnumber or constであれば入れ替える
            if (is_numeric($child->children['left'])) {
                $left = $child->children['left'];
                $child->children['left'] = $child->children['right'];
                $child->children['right'] = $left;
            } else if(isset($child->children['left']->kind) && $child->children['left']->kind === 257) {
                $left = clone $child->children['left'];
                $child->children['left'] = $child->children['right'];
                $child->children['right'] = $left;
            }
        }
    }

}
