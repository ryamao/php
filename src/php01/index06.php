<?php

function hr() {
    echo '<hr />';
}

function outputNumber($a) {
    echo '引数の値は' . $a . 'です';
    return;
}

outputNumber($a);

hr();

function outputHello() {
    echo 'Hello world';
}

outputHello();

hr();

function text($number1, $number2) {
    $value = $number1 + $number2;
    return $value;
}

$total = text(2, 4);
echo $total;

hr();

function outputFive() {
    echo 5;
}

outputFive();

hr();

function addNumber($a, $b) {
    $add = $a + $b;
    return $add;
}

$total = addNumber(2, 3);
print $total;

hr();

function score($score1, $score2, $score3) {
    $total = $score1 + $score2 + $score3;
    if ($total > 210) {
        echo '合計点は' . $total . 'なので合格です<br />';
    } else {
        echo '合計点は' . $total . 'なので不合格です<br />';
    }
}

score(80, 45, 72);
score(80, 65, 72);

hr();

function areaOfTriangle($base, $height) {
    return $base * $height / 2;
}

function areaOfRectangle($width, $height) {
    return $width * $height;
}

function areaOfTrapezoid($length1, $length2, $height) {
    return ($length1 + $length2) * $height / 2;
}

echo areaOfTriangle(2, 3) . '<br />';
echo areaOfRectangle(2, 3) . '<br />';
echo areaOfTrapezoid(2, 3, 3) . '<br />';
