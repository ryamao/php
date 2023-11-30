<?php

for ($i = 0; $i < 4; $i++) {
    echo $i;
}

echo "<hr />";

for ($i = 2; $i <= 10; $i += 2) {
    echo $i;
    echo "<br />";
}

echo "<hr />";

for ($i=1; $i <= 5; $i++) { 
    echo $i * 2 . '<br />';
}

echo "<hr />";

$i = 0;
while ($i < 3) {
    echo 'i = ' . $i . '<br />';
    $i += 1;
}

echo "<hr />";

$count = 1;
while ($count <= 20) {
    echo $count . '<br />';
    $count++;
}

echo "<hr />";

$i = 0;
while ($i < 10) {
    if ($i == 5) {
        break;
    }
    echo $i;
    $i++;
}

echo "<hr />";

$i = 0;
while ($i < 10) {
    if ($i == 5) {
        $i++;
        continue;
    }
    echo $i;
    $i++;
}

echo "<hr />";

$count = 0;
while ($count <= 100) {
    $count++;
    if ($count > 19) {
        break;
    }
    if ($count % 3 === 0) {
        continue;
    }
    echo $count . '<br />';
}

echo "<hr />";

$i = 0;
do {
    echo $i . '<br />';
    $i++;
} while ($i < 5);

echo "<hr />";

$num = 0;
do {
    echo 'num = ' . $num . '<br />';
    $num++;
} while ($num < 3);

echo "<hr />";

for ($i = 1; $i <= 50; $i++) {
    if ($i % 15 == 0) {
        echo 'FizzBuzz<br />';
    } elseif ($i % 5 == 0) {
        echo 'Buzz<br />';
    } elseif ($i % 3 == 0) {
        echo 'Fizz<br />';
    } else {
        echo $i . '<br />';
    }
}

echo "<hr />";

for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {
        echo '●';
    }
    echo '<br />';
}