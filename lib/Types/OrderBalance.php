<?php

namespace Morfin60\BoxberryApi\Types;

class OrderBalance extends Base
{
    public $Id;
    public $Status;
    public $Price;
    public $DeliverySum;
    public $PaymentSum;

    public function __construct($object)
    {
        $this->Id = intval($object->ID);
        $this->Status = $object->Status;
        $this->Status = floatval($object->Price);
        $this->DeliverySum = floatval($object->Delivery_sum);
        $this->PaymentSum = floatval($object->Payment_sum);
    }
}