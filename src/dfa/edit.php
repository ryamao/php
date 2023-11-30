<?php

require_once('functions/form_data.php');

function from_digit(int $max, string $digit): ?int
{
    if (strlen($digit) === 1 && ctype_digit($digit)) {
        $number = intval($digit);
        if ($number < $max) {
            return $number;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    list($dfa_judge, $dfa) = form_data_get_dfa_spec($_POST);
    if ($dfa_judge === 'error') {
        header('Location: index.php?error=dfa-spec');
        exit;
    }
    extract($dfa);

    for ($state = 0; $state < $num_states; $state++) {
        for ($symbol = 0; $symbol < $num_symbols; $symbol++) {
            $key = "q$state-$symbol";
            if (array_key_exists($key, $_POST)) {
                $target = from_digit($num_states, $_POST[$key]);
                if (!is_null($target)) {
                    $transitions[$state][$symbol] = $target;
                }
            }
        }
    }

    if (array_key_exists('start-state-radio', $_POST)) {
        $new_start_state = from_digit($num_states, $_POST['start-state-radio']);
        if (!is_null($new_start_state)) {
            $start_state = $new_start_state;
        }
    }

    if (
        array_key_exists('accept-states-checkbox', $_POST) &&
        is_array($_POST['accept-states-checkbox'])
    ) {
        $new_accept_states = [];
        foreach ($_POST['accept-states-checkbox'] as $state_str) {
            $state = from_digit($num_states, $state_str);
            if (!is_null($state)) {
                $new_accept_states[] = $state;
            }
        }
        $accept_states = array_unique($new_accept_states);
    }

    if (form_data_is_symbol_addition_button_pressed($_POST)) {
        if ($num_symbols < 10) {
            for ($state = 0; $state < $num_states; $state++) {
                $transitions[$state][$num_symbols] = $state;
            }
            $num_symbols++;
        } else {
            // TODO 文字の種類が上限に達したときの処理
        }
    }

    if (form_data_is_symbol_removal_button_pressed($_POST)) {
        if ($num_symbols >= 2) {
            $target = from_digit($num_symbols, $_POST['remove-symbol']);
            if (!is_null($target)) {
                for ($state = 0; $state < $num_states; $state++) {
                    array_splice($transitions[$state], $target, 1);
                }
                $num_symbols--;
            }
        } else {
            // TODO 文字の種類が下限に達したときの処理
        }
    }

    if (form_data_is_state_addition_button_pressed($_POST)) {
        if ($num_states < 10) {
            for ($symbol = 0; $symbol < $num_symbols; $symbol++) {
                $transitions[$num_states][$symbol] = $num_states;
            }
            $num_states++;
        } else {
            // TODO 状態数が上限に達したときの処理
        }
    }

    if (form_data_is_state_removal_button_pressed($_POST)) {
        if ($num_states >= 2) {
            $target = from_digit($num_states, $_POST['remove-state']);
            if (!is_null($target)) {
                array_splice($transitions, $target, 1);
                $num_states--;
                for ($state = 0; $state < $num_states; $state++) {
                    for ($symbol = 0; $symbol < $num_symbols; $symbol++) {
                        if ($transitions[$state][$symbol] === $target) {
                            $transitions[$state][$symbol] = $state;
                        } elseif ($transitions[$state][$symbol] > $target) {
                            $transitions[$state][$symbol]--;
                        }
                    }
                }
            }
        } else {
            // TODO 状態数が上限に達したときの処理
        }
    }

    $transitions_str = array_reduce(
        $transitions,
        fn ($str, $map) => $str . implode($map),
        ''
    );
    $accept_states_str = implode($accept_states);
    $query_string = "num-states=$num_states&num-symbols=$num_symbols&transitions=$transitions_str&start-state=$start_state&accept-states=$accept_states_str";

    if (form_data_is_save_button_pressed($_POST)) {
        header("Location: index.php?$query_string");
        exit;
    }

    header("Location: edit.php?$query_string");
    exit;
}

list($dfa_judge, $dfa) = form_data_get_dfa_spec($_GET);
if ($dfa_judge === 'error') {
    header('Location: index.php?error=dfa-spec');
    exit;
}
extract($dfa);

$transitions_str = array_reduce($transitions, fn ($str, $map) => $str . implode($map), '');
$accept_states_str = implode($accept_states);
$query_string = "num-states=$num_states&num-symbols=$num_symbols&transitions=$transitions_str&start-state=$start_state&accept-states=$accept_states_str";

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFA</title>
    <link rel="stylesheet" href="css/sanitize.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/edit.css">
</head>

<body>
    <header class="header">
        <h1 class="header__title">
            <a href="index.php" class="header__title-link">DFA</a>
        </h1>
    </header>
    <main>
        <div class="form-wrapper">
            <form class="form" method="post">
                <div class="form__save">
                    <button type="submit" formaction="edit.php" name="save" class="form__save-button">SAVE</button>
                </div>
                <div class="form__start-and-accept">
                    <table class="form__start-and-accept-inner">
                        <tr class="start-and-accept__row">
                            <th class="start-and-accept__header">初期状態</th>
                            <?php for ($state = 0; $state < $num_states; $state++) : ?>
                                <td class="start-and-accept__data">
                                    <div class="start-and-accept__data-inner">
                                        <input type="radio" name="start-state-radio" id="start-state-<?php echo $state ?>" value="<?php echo $state ?>" <?php echo $state === $start_state ? 'checked' : '' ?>>
                                        <label for="start-state-<?php echo $state ?>">q<sub><?php echo $state ?></sub></label>
                                    </div>
                                </td>
                            <?php endfor; ?>
                        </tr>
                        <tr class="start-and-accept__row">
                            <th class="start-and-accept__header">受理状態</th>
                            <?php for ($state = 0; $state < $num_states; $state++) : ?>
                                <td class="start-and-accept__data">
                                    <div class="start-and-accept__data-inner">
                                        <input type="checkbox" name="accept-states-checkbox[]" id="accept-states-<?php echo $state ?>" value="<?php echo $state ?>" <?php echo in_array($state, $accept_states) ? 'checked' : '' ?>>
                                        <label for="accept-states-<?php echo $state ?>">q<sub><?php echo $state ?></sub></label>
                                    </div>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    </table>
                </div>
                <div class="form__table">
                    <table class="table">
                        <tr class="table__row">
                            <td class="table__empty"></td>
                            <?php for ($symbol = 0; $symbol < $num_symbols; $symbol++) : ?>
                                <th class="table__symbol">
                                    <p class="table__symbol-text">
                                        <?php echo $symbol ?>
                                    </p>
                                    <div class="table__removal table__removal--symbol">
                                        <button type="submit" name="remove-symbol" value="<?php echo $symbol ?>" class="table__removal-button">削除</button>
                                    </div>
                                </th>
                            <?php endfor; ?>
                            <td class="table__addition table__addition--symbol">
                                <div class="table__addition-inner">
                                    <button type="submit" name="add-symbol" value="1" class="table__addition-button">追加</button>
                                </div>
                            </td>
                        </tr>
                        <?php for ($state = 0; $state < $num_states; $state++) : ?>
                            <tr class="table__row table__row--state">
                                <th class="table__state table__state--source">
                                    <p class="table__state-text">
                                        q<sub><?php echo $state ?></sub>
                                    </p>
                                    <div class="table__removal table__removal--state">
                                        <button type="submit" name="remove-state" value="<?php echo $state ?>" class="table__removal-button">削除</button>
                                    </div>
                                </th>
                                <?php for ($symbol = 0; $symbol < $num_symbols; $symbol++) : ?>
                                    <td class="table__state table__state--target">
                                        <select name="q<?php echo $state ?>-<?php echo $symbol ?>" required class="table__state-select">
                                            <?php for ($q = 0; $q < $num_states; $q++) : ?>
                                                <option value="<?php echo $q ?>" <?php echo $q === $transitions[$state][$symbol] ? 'selected' : '' ?>>q<?php echo "&#x208$q" ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                <?php endfor; ?>
                                <td class="table__empty"></td>
                            </tr>
                        <?php endfor; ?>
                        <tr class="table__row">
                            <td class="table__addition table__addition--state">
                                <div class="table__addition-inner">
                                    <button type="submit" name="add-state" value="1" class="table__addition-button">追加</button>
                                </div>
                            </td>
                            <?php for ($symbol = 0; $symbol < $num_symbols + 1; $symbol++) : ?>
                                <td class="table__empty"></td>
                            <?php endfor; ?>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="num-states" value="<?php echo $num_states ?>">
                <input type="hidden" name="num-symbols" value="<?php echo $num_symbols ?>">
                <input type="hidden" name="transitions" value="<?php echo $transitions_str ?>">
                <input type="hidden" name="start-state" value="<?php echo $start_state ?>">
                <input type="hidden" name="accept-states" value="<?php echo implode($accept_states) ?>">
            </form>
        </div>
    </main>
</body>

</html>