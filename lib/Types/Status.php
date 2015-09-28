<?php

namespace Morfin60\BoxberryApi\Types;

class Status extends Base
{
    public $Name;
    public $Date;
    public $Comment;

    public function __construct($object)
    {
    	$this->Name = $object->Name;
    	$this->Date = $object->Date;
    	$this->Comment = $object->Comment;
    }
}