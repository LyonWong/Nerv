<?php

require_once __DIR__.'/boot.php';

[$scope, $URI] = explode(':', $argv[1], 2);

$data = [
    'METHOD' => 'CLI',
    'URI' => $URI,
    '_CLI' => []
];
foreach ($argv as $v) {
    preg_match('#--(\w+)=(\w+)#', $v, $matches);
    if ($matches) {
        $data['_CLI'][$matches[1]] = $matches[2];
    }
}

boot($scope)->run(input($data))->send();
echo PHP_EOL;