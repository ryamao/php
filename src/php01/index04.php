<?php

$a = 5;

if ($a === 5) {
    echo '$aは5です';
}

echo "<hr />";

$a = 7;

if ($a === 5) {
    echo '$aは5です';
} else {
    echo '$aは5以外です';
}

echo "<hr />";

$a = 7;

if ($a === 5) {
    echo '$aは5です';
} elseif ($a === 7) {
    echo '$aは7です';
} else {
    echo '$aは5以外です';
}

echo "<hr />";

$people = "Saburo";

switch ($people) {
    case "Taro":
        echo "太郎です";
        break;
    case "Jiro":
        echo "次郎です";
        break;
    case "Saburo":
        echo "三郎です";
        break;
}

echo "<hr />";

$a = 7;

echo $a === 7 ? "TRUE" : "FALSE";
