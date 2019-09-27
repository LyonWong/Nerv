<?php

namespace core;


class Config
{
    use plugin\instance;

    protected $dir = PATH_ROOT . '/config';

    protected $cache = PATH_ROOT . '/config/.cache';

    protected $blocks;


    /**
     * @param array $blocks 优先级高的在前
     * @param mixed $cache 缓存开关/路径
     */
    public function __construct(array $blocks=['default'], $cache=null)
    {
        $this->blocks = array_reverse($blocks);
        if (is_dir($cache)) {
            $this->cache = $cache;
        } else {
            $this->cache = $cache ? PATH_ROOT . '/config/.cache' : false;
        }
    }

    public function get($file, $section=null, $item=null, $default=null)
    {
        if (empty($this->cfg[$file])) {
            $this->cfg[$file] = $this->load($file);
        }
        if ($section === null) {
            return $this->cfg[$file] ?? $default;
        }
        if ($item === null) {
            return $this->cfg[$file][$section] ?? $default;
        }
        return $this->cfg[$file][$section][$item] ?? $default;
    }

    public function load($file)
    {
        $_file = "$this->cache/$file.php";
        if ($this->cache && is_file($_file)) {
            return require($_file);
        }
        $cfg = [];
        foreach ($this->blocks as $block) {
            $ini = "$this->dir/$block/$file.ini";
            if (is_file($ini)) {
                $cfg = arrayJoin($cfg, parse_ini_file($ini, true));
            }
        }
        return $cfg;
    }


    public function make($blocks=null)
    {
        if (!$this->cache) {
            return [];
        }
        $blocks = $blocks ?? $this->blocks;
        $confs = [];
        foreach ($this->blocks as $block) {
            $confs = arrayJoin($confs, $this->map($block, function($file) {
                return parse_ini_file($file, true);
            }));
        }
        foreach ($confs as $conf => $data) {
            $file = "$this->cache/$conf.php";
            $cdir = dirname($file);
            if (!is_dir($cdir)) {
                mkdir($cdir, 0777, true);
            }
            file_put_contents($file, "<?php return " . var_export($data, true) . ";");
        }
        return array_keys($confs);
    }

    protected function map($block, \Closure $closure)
    {
        $paths = glob("$this->dir/$block/*");
        $res = [];
        foreach ($paths as $path) {
            $pathinfo = pathinfo($path);
            $key = "$block/$pathinfo[filename]";
            if (is_dir($path)) {
                $res = arrayJoin($res, $this->map($key, $closure));
            } else {
                $res[strstr($key, '/')] = $closure($path);
            }
        }
        return $res;
    }
}