<?php

namespace Morfin60\BoxberryApi\Types;

class City extends Base
{
    public $Name;
    public $Code;

    public function __construct($object)
    {
    	//$object->Code = intval($object->Code);
        parent::__construct($object);
    }
}
