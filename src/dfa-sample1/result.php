<?php

const ACCEPT_STATES = ['1'];

$input = htmlspecialchars($_GET['input'], ENT_QUOTES);
$state = htmlspecialchars($_GET['state'], ENT_QUOTES);
$position = htmlspecialchars($_GET['position'], ENT_QUOTES);

// TODO 入力形式のチェック

$current_state = $state;
for ($i = intval($position); $i < strlen($input); $i++) {
    $c = $input[$i];
    switch ($current_state) {
        case '0':
            if ($c === '0') {
                $current_state = '2';
            } else {
                $current_state = '0';
            }
            break;
        case '1':
            if ($c === '0') {
                $current_state = '1';
            } else {
                $current_state = '1';
            }
            break;
        case '2':
            if ($c === '0') {
                $current_state = '2';
            } else {
                $current_state = '1';
            }
            break;
    }
}
$accept = in_array($current_state, ACCEPT_STATES);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFA</title>
    <link rel="stylesheet" href="css/result.css">
</head>

<body>
    <div class="dfa-result">
        <h2 class="dfa-result__title">結果</h2>
        <?php if ($accept) : ?>
            <p class="dfa-result__text--accept">受理</p>
        <?php else : ?>
            <p class="dfa-result__text--reject">拒否</p>
        <?php endif; ?>
    </div>
    <div class="dfa-input">
        <h2 class="dfa-input__title">入力</h2>
        <p class="dfa-input__text"><?php echo $input ?></p>
    </div>
    <div class="dfa-table">
        <h2 class="dfa-table__title">状態遷移表</h2>
        <table class="dfa-table__inner">
            <tr class="dfa-table__row">
                <td class="dfa-table__data"></td>
                <th class="dfa-table__header">0</th>
                <th class="dfa-table__header">1</th>
            </tr>
            <tr class="dfa-table__row <?php echo $current_state === '0' ? 'dfa-table__current-state' : '' ?>">
                <th class="dfa-table__header">q0</th>
                <td class="dfa-table__data">q2</td>
                <td class="dfa-table__data">q0</td>
            </tr>
            <tr class="dfa-table__row <?php echo $current_state === '1' ? 'dfa-table__current-state' : '' ?>">
                <th class="dfa-table__header">q1</th>
                <td class="dfa-table__data">q1</td>
                <td class="dfa-table__data">q1</td>
            </tr>
            <tr class="dfa-table__row <?php echo $current_state === '2' ? 'dfa-table__current-state' : '' ?>">
                <th class="dfa-table__header">q2</th>
                <td class="dfa-table__data">q2</td>
                <td class="dfa-table__data">q1</td>
            </tr>
        </table>
    </div>
    <div class="dfa-form">
        <form class="dfa-form__inner" method="get">
            <h2 class="dfa-form__title"><label for="dfa-form__label">再入力</label></h2>
            <div class="dfa-form__text-wrapper">
                <input type="text" name="input" id="dfa-form__text" pattern="[01]*">
            </div>
            <div class="dfa-form__buttons">
                <button class="dfa-form__run-button" formaction="result.php">RUN</button>
                <button class="dfa-form__step-button" formaction="step.php">STEP</button>
            </div>
            <input type="hidden" name="state" value="0">
            <input type="hidden" name="position" value="0">
        </form>
    </div>
</body>

</html>