<?php

require_once('config/dfa.php');

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

$current_state = $start_state;
$history = [];
for ($i = 0; $i < strlen($input); $i++) {
    $symbol = $input[$i];
    $current_state = $transitions[$current_state][$symbol];
    $history[] = ['symbol' => $symbol, 'state' => $current_state];
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
    <link rel="stylesheet" href="css/result.css">
</head>

<body>
    <header class="header">
        <h1 class="header__title"><a href="index.php" class="header__title-link">DFA</a></h1>
    </header>
    <main>
        <div class="dfa-result">
            <p class="dfa-result__text">判定：</p>
            <?php if (in_array($current_state, $accept_states)) : ?>
                <p class="dfa-result__text dfa-result__text--accept">受理</p>
            <?php else : ?>
                <p class="dfa-result__text dfa-result__text--reject">拒否</p>
            <?php endif; ?>
        </div>
        <div class="dfa-input">
            <?php if (strlen($input) === 0) : ?>
                <p class="dfa-input__text dfa-input__text--empty">&lt;入力なし&gt;</p>
            <?php else : ?>
                <p class="dfa-input__text"><?php echo $input ?></p>
            <?php endif; ?>
        </div>
        <div class="dfa-table">
            <table class="dfa-table__inner">
                <tr class="dfa-table__row">
                    <td class="dfa-table__empty-cell"></td>
                    <?php foreach ($symbols as $symbol) : ?>
                        <th class="dfa-table__symbol"><?php echo $symbol ?></th>
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
        <div class="dfa-history">
            <ol class="dfa-history__list">
                <li class="dfa-history__state">
                    <?php echo $start_state ?>
                </li>
                <?php foreach ($history as $pair) : ?>
                    <li class="dfa-history__symbol"><?php echo $pair['symbol'] ?></li>
                    <li class="dfa-history__state"><?php echo $pair['state'] ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </main>
</body>

</html>