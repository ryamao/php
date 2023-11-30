<?php

require_once('functions/form_data.php');

list($dfa_judge, $dfa) = form_data_get_dfa_spec($_GET);
if ($dfa_judge === 'error') {
    header('Location: index.php?error=dfa');
    exit;
}
extract($dfa);

list($input_symbols_judge, $input_symbols) = form_data_get_input_symbols($num_symbols, $_GET);
if ($input_symbols_judge === 'error') {
    header('Location: index.php?error=input-symbols');
    exit;
}

$current_state = $start_state;
$history = [];
foreach ($input_symbols as $symbol) {
    $current_state = $transitions[$current_state][$symbol];
    $history[] = [$symbol, $current_state];
}

$transitions_str = array_reduce($transitions, fn ($str, $map) => $str . implode($map), '');
$accept_states_str = implode($accept_states);
$input_symbols_str = implode($input_symbols);

$query_string = "num-states=$num_states&num-symbols=$num_symbols&transitions=$transitions_str&start-state=$start_state&accept-states=$accept_states_str&input-symbols=$input_symbols_str";

$query_string_without_input = "num-states=$num_states&num-symbols=$num_symbols&transitions=$transitions_str&start-state=$start_state&accept-states=$accept_states_str";

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
        <h1 class="header__title">
            <a href="index.php" class="header__title-link">DFA</a>
        </h1>
        <nav class="header__nav">
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a href="index.php?<?php echo $query_string ?>" class="header__nav-link">RESUME</a>
                </li>
                <li class="header__nav-item">
                    <a href="edit.php?<?php echo $query_string_without_input ?>" class="header__nav-link">EDIT</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="result">
            <p class="result__text">判定：</p>
            <?php if (in_array($current_state, $accept_states)) : ?>
                <p class="result__text result__text--accept">受理</p>
            <?php else : ?>
                <p class="result__text result__text--reject">拒否</p>
            <?php endif; ?>
        </div>
        <div class="input">
            <?php if (count($input_symbols) === 0) : ?>
                <p class="input__text input__text--empty">入力なし</p>
            <?php else : ?>
                <p class="input__text"><?php echo implode($input_symbols) ?></p>
            <?php endif; ?>
        </div>
        <div class="table-and-history">
            <div class="table-wrapper">
                <table class="table">
                    <tr class="table__row">
                        <td class="table__empty"></td>
                        <?php for ($symbol = 0; $symbol < $num_symbols; $symbol++) : ?>
                            <th class="table__symbol">
                                <?php echo $symbol ?>
                            </th>
                        <?php endfor; ?>
                    </tr>
                    <?php for ($state = 0; $state < $num_states; $state++) : ?>
                        <?php
                        $classes = '';
                        if ($state === $start_state) {
                            $classes .= 'table__state--start ';
                        }
                        if ($state === $current_state) {
                            $classes .= 'table__state--current ';
                        }
                        if (in_array($state, $accept_states)) {
                            $classes .= 'table__state--accept ';
                        }
                        ?>
                        <tr class="table__row table__row--state">
                            <th class="table__state table__state--source <?php echo $classes ?>">
                                q<sub><?php echo $state ?></sub>
                            </th>
                            <?php for ($symbol = 0; $symbol < $num_symbols; $symbol++) : ?>
                                <td class="table__state table__state--target">
                                    q<sub><?php echo $transitions[$state][$symbol] ?></sub>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
            <div class="history">
                <ol class="history__list">
                    <li class="history__state">
                        q<sub><?php echo $start_state ?></sub>
                    </li>
                    <?php foreach ($history as list($symbol, $state)) : ?>
                        <li class="history__symbol"><?php echo $symbol ?></li>
                        <li class="history__state">q<sub><?php echo $state ?></sub></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </main>
</body>

</html>