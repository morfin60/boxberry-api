<?php

namespace Morfin60\BoxberryApi\Types;

/**
 * Class containing information about act
 * @author Alexander N <morfin60@gmail.com
 * @package boxberry-api
 */
class Act extends Base
{
    public $Id;
    public $Label;
    public $Track;
    public $Date;

    public function __construct($object)
    {
        $this->Id = isset($object->id)?$object->id:null;
        $this->Label = $object->label;
        $this->Track = isset($object->track):$object->track:null;
        $this->Date = isset($object->date):$object->date:null;
    }
}