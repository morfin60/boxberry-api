<?php

namespace Morfin60\BoxberryApi\Types;

class ZipDelivery extends Base
{
    public $ZoneExpressDelivery;
    public $ExpressDelivery;

    public function __construct($object)
    {
        $object->ZoneExpressDelivery = boolval($object->ZoneExpressDelivery);
        $object->ExpressDelivery = boolval($object->ExpressDelivery);
        parent::__construct($object);
    }
}