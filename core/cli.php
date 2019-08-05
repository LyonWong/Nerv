<?php

require_once __DIR__.'/boot.php';

$domain = $argv[1];

$data = [
    'METHOD' => 'CLI',
    'URI' => $argv[2],
    '_CLI' => []
];
foreach ($argv as $v) {
    preg_match('#--(\w+)=(\w+)#', $v, $matches);
    if ($matches) {
        $data['_CLI'][$matches[1]] = $matches[2];
    }
}

boot($domain)->run(input($data))->send();
echo PHP_EOL;