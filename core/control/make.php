<?php

namespace core\control;

use \Core\Config;

class make extends _
{
    public function _CLI_config()
    {
        $blocks = explode(',', env('CONFIG_BLOCK'));
        $cache = env('CONFIG_CACHE');
        $config = Config::singleton($blocks, $cache);
        $list = $config->make();
        echo "Make config/.cache: " .PHP_EOL;
        foreach ($list as $path) {
            echo "|- $path.ini".PHP_EOL;
        }
    }

}
