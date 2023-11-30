<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DFA</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="dfa-regexp">
        <h2 class="dfa-regexp__title">正規表現</h2>
        <p class="dfa-regexp__text">1*00*1(0+1)*</p>
    </div>
    <div class="dfa-table">
        <h2 class="dfa-table__title">状態遷移表</h2>
        <table class="dfa-table__inner">
            <tr class="dfa-table__row">
                <td class="dfa-table__data"></td>
                <th class="dfa-table__header">0</th>
                <th class="dfa-table__header">1</th>
            </tr>
            <tr class="dfa-table__row dfa-table__current-state">
                <th class="dfa-table__header">q0</th>
                <td class="dfa-table__data">q2</td>
                <td class="dfa-table__data">q0</td>
            </tr>
            <tr class="dfa-table__row">
                <th class="dfa-table__header">q1</th>
                <td class="dfa-table__data">q1</td>
                <td class="dfa-table__data">q1</td>
            </tr>
            <tr class="dfa-table__row">
                <th class="dfa-table__header">q2</th>
                <td class="dfa-table__data">q2</td>
                <td class="dfa-table__data">q1</td>
            </tr>
        </table>
    </div>
    <div class="dfa-start-state">
        <h2 class="dfa-start-state__title">初期状態</h2>
        <p class="dfa-start-state__text">q0</p>
    </div>
    <div class="dfa-accept-states">
        <h2 class="dfa-accept-states__title">受理状態</h2>
        <p class="dfa-accept-states__text">q1</p>
    </div>
    <div class="dfa-input">
        <form class="dfa-input__inner" method="get">
            <h2 class="dfa-input__title"><label for="dfa-input__label">入力</label></h2>
            <div class="dfa-input__text-wrapper">
                <input type="text" name="input" id="dfa-input__text" pattern="[01]*">
            </div>
            <div class="dfa-input__buttons">
                <button class="dfa-input__run-button" formaction="result.php">RUN</button>
                <button class="dfa-input__step-button" formaction="step.php">STEP</button>
            </div>
            <input type="hidden" name="state" value="0">
            <input type="hidden" name="position" value="0">
        </form>
    </div>
</body>

</html>