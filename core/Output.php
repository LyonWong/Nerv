<?php

namespace core;


class Output
{
    protected $data;

    protected $headers = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * set or get data
     *
     * @param mixed $data
     * @return self|mixed
     */
    public function data($data=null)
    {
        if ($data) {
            $this->data = $data;
        }
        return $this->data;
    }

    /**
     * add http header
     *
     * @param string $header
     * @param boolean $replace
     * @param integer $httpResponseCode
     * @return self
     */
    public function header(...$arguments)
    {
        $this->headers[] = $arguments;
        return $this;
    }

    /**
     * set multi headers or get headers
     *
     * @param array $headers
     * @return self|array
     */
    public function headers(array $headers = null)
    {
        if ($headers) {
            $this->headers = array_merge($this->headers, $headers);
            return $this;
        } else {
            return $this->headers;
        }
    }

    /**
     * response as json
     *
     * @param integer $options json_encode options
     * @return self
     */
    public function json(int $options = null)
    {
        $this->data = json_encode($this->data, $options);
        return $this;
    }

    public function send()
    {
        foreach ($this->headers as $header) {
            call_user_func_array('header', $header);
        }
        print_r($this->data);
    }
}
