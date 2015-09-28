<?php

namespace Morfin60\BoxberryApi\Exception;

use Morfin60\BoxberryApi\Base\Exception;

/**
 * Исключение, которое вызывается при возвращении ошибки API
 * @author Alexander N <morfin60@gmail.com>
 * @package boxberry-api
 */
class ApiException extends Exception
{
    const BAD_API_TYPE = 0;
    const BAD_API_CLASS = 1;
    const BAD_API_KEY = 2;
    const BAD_METHOD_NAME = 3;
    const BAD_JSON = 4;
    const API_ERROR = 5;
    const INTERNAL_ERROR = 6;
    const HTTP_ERROR = 7;
}