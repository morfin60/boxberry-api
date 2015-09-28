<?php

namespace Morfin60\BoxberryApi\Types;

class DeliveryPrice extends Base
{
    public $Price;
    public $PriceBase;
    public $PriceService;
    public $DeliveryPeriod;

    public function __construct($object)
    {
        $this->Price = floatval($object->price);
        $this->PriceBase = floatval($object->price_base);
        $this->PriceService = floatval($object->price_service);
        $this->DeliveryPeriod = intval($object->delivery_period);
    }
}