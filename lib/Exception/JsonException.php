<?php

namespace Morfin60\BoxberryApi\Exception;

use Morfin60\BoxberryApi\Base\Exception;

/**
 * Исключение JSON, вызываемое при неудаче разбора или сериализации из/в JSON
 * @author Alexander N <morfin60@gmail.com>
 * @package boxberry-api
 */
class JsonException extends Exception
{
    const ENCODE_EXCEPTION = 0;
    const DECODE_EXCEPTION = 1;
}