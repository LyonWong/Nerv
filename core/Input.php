<?php


namespace core;


/**
 * Input providor
 * @property-read string $DOMAIN
 * @property-read string $PORT
 * @property-read string $URI
 * @property-read string $METHOD
 * @method get(string|null $name, string|null $default)
 * @method post(string|null $name, string|null $default)
 * @method put(string|null $name, string|null $default)
 * @method delete(string|null $name, string|null $default)
 */
class Input
{
    private $vars=[];

    private $METHOD;

    private $URI;

    private $HOST;

    private $PORT;

    private $_GET = [];

    private $_POST = [];

    private $_COOKIE = [];

    private $_FILES = [];

    private $_SERVER = [];

    private $_INPUT;

    public function __construct($data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
        $alias = [
            'SERVER_NAME' => 'DOMAIN',
            'SERVER_PORT' => 'PORT',
            'REQUEST_URI' => 'URI',
            'REQUEST_METHOD' => 'METHOD',
        ];
        foreach ($alias as $from => $to) {
            $this->$to = $this->_SERVER[$from];
        }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    public function __call($method, $arguments)
    {
        $_method = '_'.strtoupper($method);
        $_name = $arguments[0] ?? null;
        $_default = $arguments[1] ?? null;
        if ($_name === null) {
            return $this->$_method;
        } else {
            return $this->$_method[$_name] ?? $_default;
        }
    }

    public function var($name)
    {
        return $this->vars[$name] ?? null;
    }

    public function header($name)
    {
        return $this->_SERVER['HTTP_'.strtoupper($name)] ?? null;
    }

    public function raw()
    {
        return $this->_INPUT ?? file_get_contents('php://input');
    }


    public function ip()
    {

    }

    public function file()
    {

    }

}
