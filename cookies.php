<?php
header('content-type: text/plain');
setcookie('test', '123', time() + 3600, '/');

echo date('Y-m-d H:i:s', time() + 3600) . "\n";
echo 'cookie test with var_dump' . "\n";
echo $_COOKIE['test'] . "\n";
var_dump($_COOKIE);
?>