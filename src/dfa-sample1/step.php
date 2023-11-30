<?php

const ACCEPT_STATES = ['1'];

$input = htmlspecialchars($_GET['input'], ENT_QUOTES);
$state = htmlspecialchars($_GET['state'], ENT_QUOTES);
$position = htmlspecialchars($_GET['position'], ENT_QUOTES);

// TODO 入力形式のチェック

$index = intval($position);
if ($index >= strlen($input) - 1) {
    header("Location: result.php?input=$input&state=$state&position=$position");
    exit;
}

$left_string = substr($input, 0, $index);
$current_char = $input[$index];
$right_string = substr($input, $index + 1);

switch ($state) {
    case '0':
        if ($current_char === '0') {
            $next_state = '2';
        } else {
            $next_state = '0';
        }
        break;
    case '1':
        if ($current_char === '0') {
            $next_state = '1';
        } else {
            $next_state = '1';
        }
        break;
    case '2':
        if ($current_char === '0') {
            $next_state = '2';
        } else {
            $next_state = '1';
        }
        break;
}

$next_position = strval($index + 1);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFA</title>
    <link rel="stylesheet" href="css/step.css">
</head>

<body>
    <div class="dfa-input">
        <h2 class="dfa-input__title">入力</h2>
        <p class="dfa-input__text"><span class="dfa-input__text--left"><?php echo $left_string ?></span><span class="dfa-input__text--current"><?php echo $current_char ?></span><span class="dfa-input__text--right"><?php echo $right_string ?></span></p>
    </div>
    <div class="dfa-table">
        <h2 class="dfa-table__title">状態遷移表</h2>
        <table class="dfa-table__inner">
            <tr class="dfa-table__row">
                <td class="dfa-table__data"></td>
                <th class="dfa-table__header">0</th>
                <th class="dfa-table__header">1</th>
            </tr>
            <tr class="dfa-table__row <?php echo $next_state === '0' ? 'dfa-table__current-state' : '' ?>">
                <th class="dfa-table__header">q0</th>
                <td class="dfa-table__data">q2</td>
                <td class="dfa-table__data">q0</td>
            </tr>
            <tr class="dfa-table__row <?php echo $next_state === '1' ? 'dfa-table__current-state' : '' ?>">
                <th class="dfa-table__header">q1</th>
                <td class="dfa-table__data">q1</td>
                <td class="dfa-table__data">q1</td>
            </tr>
            <tr class="dfa-table__row <?php echo $next_state === '2' ? 'dfa-table__current-state' : '' ?>">
                <th class="dfa-table__header">q2</th>
                <td class="dfa-table__data">q2</td>
                <td class="dfa-table__data">q1</td>
            </tr>
        </table>
    </div>
    <div class="dfa-form">
        <form class="dfa-form__inner" method="get">
            <div class="dfa-form__buttons">
                <button class="dfa-form__run-button" formaction="result.php">RUN</button>
                <button class="dfa-form__step-button" formaction="step.php">STEP</button>
            </div>
            <input type="hidden" name="input" value="<?php echo $input ?>">
            <input type="hidden" name="state" value="<?php echo $next_state ?>">
            <input type="hidden" name="position" value="<?php echo $next_position ?>">
        </form>
    </div>
</body>

</html>