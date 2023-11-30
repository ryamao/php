<?php

require_once('config/dfa.php');

$bad_input = $bad_symbol = false;
if (array_key_exists('error', $_GET)) {
    $bad_input = $_GET['error'] === 'bad-input';
    $bad_symbol = $_GET['error'] === 'bad-symbol';
}

if (array_key_exists('input', $_GET)) {
    $input = $_GET['input'];
    for ($i = 0; $i < strlen($input); $i++) {
        if (!in_array($input[$i], $symbols)) {
            header('Location: index.php?error=bad-input');
            exit;
        }
    }
} else {
    $input = '';
}

if (array_key_exists('control', $_GET)) {
    switch ($_GET['control']) {
        case 'reset':
            $input = '';
            break;
        case 'backspace':
            $input = substr($input, 0, -1);
            break;
    }
}

if (array_key_exists('symbol', $_GET)) {
    $symbol = $_GET['symbol'];
    if (!in_array($symbol, $symbols)) {
        header('Location: index.php?error=bad-symbol');
        exit;
    }
    $input .= $symbol;
}

$current_state = $start_state;
for ($i = 0; $i < strlen($input); $i++) {
    $current_state = $transitions[$current_state][$input[$i]];
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFA</title>
    <link rel="stylesheet" href="css/sanitize.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <header class="header">
        <h1 class="header__title"><a href="index.php" class="header__title-link">DFA</a></h1>
    </header>
    <main>
        <div class="dfa-input">
            <?php if ($bad_input) : ?>
                <p class="dfa-input__text dfa-input__text--empty">&lt;エラー&gt;</p>
            <?php elseif ($bad_symbol) : ?>
                <p class="dfa-input__text dfa-input__text--empty">&lt;エラー&gt;</p>
            <?php elseif (strlen($input) === 0) : ?>
                <p class="dfa-input__text dfa-input__text--empty">&lt;入力なし&gt;</p>
            <?php else : ?>
                <p class="dfa-input__text"><?php echo $input ?></p>
            <?php endif; ?>
        </div>
        <div class="dfa-form">
            <form class="dfa-form__inner" method="get">
                <div class="dfa-table">
                    <table class="dfa-table__inner">
                        <tr class="dfa-table__row">
                            <td class="dfa-table__controls">
                                <div class="dfa-table__controls-inner">
                                    <button type="submit" formaction="result.php">End</button>
                                    <button type="submit" formaction="index.php" name="control" value="reset">Reset</button>
                                    <button type="submit" formaction="index.php" name="control" value="backspace">BS</button>
                                </div>
                            </td>
                            <?php foreach ($symbols as $symbol) : ?>
                                <th class="dfa-table__symbol">
                                    <button type="submit" formaction="index.php" name="symbol" value="<?php echo $symbol ?>"><?php echo $symbol ?></button>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                        <?php foreach ($transitions as $from_state => $symbol_state_map) : ?>
                            <?php
                            $start_state_class = $current_state_class = $accept_state_class = '';
                            if ($from_state === $start_state) {
                                $start_state_class = 'dfa-table__from-state--start';
                            }
                            if ($from_state === $current_state) {
                                $current_state_class = 'dfa-table__from-state--current';
                            }
                            if (in_array($from_state, $accept_states)) {
                                $accept_state_class = 'dfa-table__from-state--accept';
                            }
                            ?>
                            <tr class="dfa-table__row">
                                <th class="dfa-table__from-state <?php echo $start_state_class ?> <?php echo $current_state_class ?> <?php echo $accept_state_class ?>">
                                    <?php echo $from_state ?>
                                </th>
                                <?php foreach ($symbols as $symbol) : ?>
                                    <td class="dfa-table__to-state"><?php echo $symbol_state_map[$symbol] ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <input type="hidden" name="input" value="<?php echo $input ?>">
            </form>
        </div>
        <div class="dfa-info">
            <p class="dfa-info__text">「->」は初期状態、「*」は受理状態を表します。</p>
            <p class="dfa-info__text">現在の状態は<span class="dfa-info__text--state">q?</span>のように背景色が変わります。</p>
        </div>
    </main>
</body>

</html>