<?php

require_once('functions/form_data.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    list($dfa_judge, $dfa) = form_data_get_dfa_spec($_POST);
    if ($dfa_judge === 'error') {
        header('Location: index.php?error=dfa-spec');
        exit;
    }
    extract($dfa);

    list($input_symbols_judge, $input_symbols) = form_data_get_input_symbols($num_symbols, $_POST);
    if ($input_symbols_judge === 'error') {
        header('Location: index.php?error=input-symbols');
        exit;
    }

    if (form_data_is_clear_button_pressed($_POST)) {
        $input_symbols = [];
    }
    if (form_data_is_backspace_button_pressed($_POST)) {
        array_pop($input_symbols);
    }

    list($next_symbol_judge, $next_symbol) = form_data_get_next_symbol($num_symbols, $_POST);
    switch ($next_symbol_judge) {
        case 'ok':
            $input_symbols[] = $next_symbol;
            break;
        case 'error':
            header('Location: index.php?error=next-symbol');
            exit;
    }

    $transitions_str = array_reduce(
        $transitions,
        fn ($str, $map) => $str . implode($map),
        ''
    );
    $accept_states_str = implode($accept_states);
    $input_symbols_str = implode($input_symbols);

    $query_string = "num-states=$num_states&num-symbols=$num_symbols&transitions=$transitions_str&start-state=$start_state&accept-states=$accept_states_str&input-symbols=$input_symbols_str";

    header("Location: index.php?$query_string");
    exit;
}

list($dfa_judge, $dfa) = form_data_get_dfa_spec($_GET);
if ($dfa_judge === 'error') {
    header('Location: index.php?error=dfa-spec');
    exit;
}
extract($dfa);

list($input_symbols_judge, $input_symbols) = form_data_get_input_symbols($num_symbols, $_GET);
if ($input_symbols_judge === 'error') {
    header('Location: index.php?error=input-symbols');
    exit;
}

$is_error = form_data_has_error($_GET);
if ($is_error) {
    $input_symbols = [];
}

$current_state = $start_state;
foreach ($input_symbols as $symbol) {
    $current_state = $transitions[$current_state][$symbol];
}

$transitions_str = array_reduce($transitions, fn ($str, $map) => $str . implode($map), '');
$accept_states_str = implode($accept_states);
$input_symbols_str = implode($input_symbols);

$query_string = "num-states=$num_states&num-symbols=$num_symbols&transitions=$transitions_str&start-state=$start_state&accept-states=$accept_states_str&input-symbols=$input_symbols_str";

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
        <h1 class="header__title">
            <a href="index.php" class="header__title-link">DFA</a>
        </h1>
        <nav class="header__nav">
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a href="result.php?<?php echo $query_string ?>" class="header__nav-link">RESULT</a>
                </li>
                <li class="header__nav-item">
                    <a href="edit.php?<?php echo $query_string ?>" class="header__nav-link">EDIT</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="input">
            <?php if ($is_error) : ?>
                <p class="input__text input__text--error">エラー</p>
            <?php elseif (count($input_symbols) === 0) : ?>
                <p class="input__text input__text--empty">入力なし</p>
            <?php else : ?>
                <p class="input__text"><?php echo implode($input_symbols) ?></p>
            <?php endif; ?>
        </div>
        <div class="form-and-help">
            <div class="form-wrapper">
                <form class="form" method="post">
                    <div class="form__table">
                        <table class="table">
                            <tr class="table__row table__row--control">
                                <td class="table__controls">
                                    <div class="table__controls-inner">
                                        <button class="table__control-button table__control-button--clear" type="submit" formaction="index.php" name="control" value="clear">C</button>
                                        <button class="table__control-button table__control-button--backspace" type="submit" name="control" value="backspace">&#x25C0;</button>
                                    </div>
                                </td>
                                <?php for ($symbol = 0; $symbol < $num_symbols; $symbol++) : ?>
                                    <th class="table__symbol">
                                        <button type="submit" formaction="index.php" name="next-symbol" value="<?php echo $symbol ?>" class="table__symbol-button"><?php echo $symbol ?></button>
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
                    <div class="form__buttons">
                        <div class="form__result">
                            <button class="form__result-button" type="submit" formaction="result.php" formmethod="get">RESULT</button>
                        </div>
                        <div class="form__edit">
                            <button class="form__edit-button" type="submit" formaction="edit.php" formmethod="get">EDIT</button>
                        </div>
                    </div>
                    <input type="hidden" name="num-states" value="<?php echo $num_states ?>">
                    <input type="hidden" name="num-symbols" value="<?php echo $num_symbols ?>">
                    <input type="hidden" name="transitions" value="<?php echo $transitions_str ?>">
                    <input type="hidden" name="start-state" value="<?php echo $start_state ?>">
                    <input type="hidden" name="accept-states" value="<?php echo $accept_states_str ?>">
                    <input type="hidden" name="input-symbols" value="<?php echo $input_symbols_str ?>">
                </form>
            </div>
            <div class="help">
                <p class="help__text">右上の数字ボタンで対応する文字を入力できます。</p>
                <p class="help__text">「&#x25C0;」ボタンで入力を1文字消去します。</p>
                <p class="help__text">「C」ボタンで入力をすべて消去します。</p>
                <p class="help__text">「RESULT」ボタンで結果ページに移ります。</p>
                <p class="help__text">「EDIT」ボタンで編集ページに移ります。</p>
                <p class="help__text">左端の列の「q〜」は遷移前の状態です。</p>
                <p class="help__text">左から2列目以降の「q〜」は遷移後の状態です。</p>
                <p class="help__text">「>」の付いた状態は初期状態です。</p>
                <p class="help__text">「*」の付いた状態は受理状態です。</p>
                <p class="help__text">現在の状態は<span class="help__text--current">q〜</span>のように背景色を変えて表示します。</p>
            </div>
        </div>
    </main>
</body>

</html>