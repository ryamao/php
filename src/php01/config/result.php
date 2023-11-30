<?php
$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
$choice = htmlspecialchars($_POST['choice'], ENT_QUOTES);
$quantity = htmlspecialchars($_POST['quantity'], ENT_QUOTES);

print '私の名前は、' . $name . '<br />';
print 'ご希望の商品は、' . $choice . '<br />';
print '注文数は、' . $quantity . '<br />';
