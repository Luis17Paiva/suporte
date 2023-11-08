<?php

// Esta é uma mensagem de teste
echo "O PHAR está rodando!\n";

$phar = new Phar('monitor.phar');
$phar->buildFromDirectory(__DIR__);
$phar->setStub($phar->createDefaultStub('monitor18.php'));