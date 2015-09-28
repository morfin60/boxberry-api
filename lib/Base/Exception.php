<?php

namespace morfin60\BoxberryApi\Base;

use Morfin60\BoxberryApi\Exception\ExceptionInterface;

class Exception extends \Exception implements ExceptionInterface
{
    protected $data;

    /**
     * @param string $message
     * @param int $code
     * @param mixed $data
     */
    public function __construct($message, $code, $previous = null, $data = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}