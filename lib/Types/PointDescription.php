<?php

namespace Morfin60\BoxberryApi\Types;

/**
 *
 */
class PointDescription extends Base
{
    /**
     * @access public
     * @var string
     */
    public $Name;
    /**
     * @access public
     * @var string
     */
    public $Organization;
    /**
     * @access public
     * @var integer
     */
    public $ZipCode;
    /**
     * @access public
     * @var string
     */
    public $Country;
    /**
     * @access public
     * @var string
     */
    public $objectrea;
    /**
     * @access public
     * @var string
     */
    public $CityCode;
    /**
     * @access public
     * @var string
     */
    public $CityName;
    /**
     * @access public
     * @var string
     */
    public $Settlement;
    /**
     * @access public
     * @var string
     */
    public $Metro;
    /**
     * @access public
     * @var string
     */
    public $Street;
    /**
     * @access public
     * @var string
     */
    public $House;
    /**
     * @access public
     * @var string
     */
    public $Structure;
    /**
     * @access public
     * @var string
     */
    public $Housing;
    /**
     * @access public
     * @var string
     */
    public $objectpartment;
    /**
     * @access public
     * @var string
     */
    public $objectddress;
    /**
     * @access public
     * @var string
     */
    public $objectddressReduce;
    /**
     * @access public
     * @var string
     */
    public $GPS;
    /**
     * @access public
     * @var string
     */
    public $TripDescription;
    /**
     * @access public
     * @var string
     */
    public $Phone;
    /**
     * @access public
     * @var boolean
     */
    public $ForeignOnlineStoresOnly;
    /**
     * @access public
     * @var boolean
     */
    public $PrepaidOrdersOnly;
    /**
     * @access public
     * @var boolean
     */
    public $objectcquiring;
    /**
     * @access public
     * @var boolean
     */
    public $DigitalSignature;
    /**
     * @access public
     * @var integer
     */
    public $TypeOfOffice;
    /**
     * @access public
     * @var boolean
     */
    public $CourierDelivery;
    /**
     * @access public
     * @var boolean
     */
    public $ReceptionLaP;
    /**
     * @access public
     * @var boolean
     */
    public $DeliveryLaP;
    /**
     * @access public
     * @var double
     */
    public $LoadLimit;
    /**
     * @access public
     * @var double
     */
    public $VolumeLimit;
    /**
     * @access public
     * @var boolean
     */
    public $EnablePartialDelivery;
    /**
     * @access public
     * @var boolean
     */
    public $EnableFitting;
    /**
     * @access public
     * @var string
     */
    public $WorkShedule;
    /**
     * @access public
     * @var string
     */
    public $WorkMoBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkMoEnd;
    /**
     * @access public
     * @var string
     */
    public $WorkTuBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkTuEnd;
    /**
     * @access public
     * @var string
     */
    public $WorkWeBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkWeEnd;
    /**
     * @access public
     * @var string
     */
    public $WorkThBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkThEnd;
    /**
     * @access public
     * @var string
     */
    public $WorkFrBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkFrEnd;
    /**
     * @access public
     * @var string
     */
    public $WorkSaBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkSaEnd;
    /**
     * @access public
     * @var string
     */
    public $WorkSuBegin;
    /**
     * @access public
     * @var string
     */
    public $WorkSuEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchMoBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchMoEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchTuBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchTuEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchWeBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchWeEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchThBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchThEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchFrBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchFrEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchSaBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchSaEnd;
    /**
     * @access public
     * @var string
     */
    public $LunchSuBegin;
    /**
     * @access public
     * @var string
     */
    public $LunchSuEnd;
    /**
     * @access public
     * @var string
     */
    public $Photos;
    /**
     * @access public
     * @var string
     */
    public $TerminalCode;
    /**
     * @access public
     * @var string
     */
    public $TerminalName;
    /**
     * @access public
     * @var string
     */
    public $TerminalOrganization;
    /**
     * @access public
     * @var string
     */
    public $TerminalCityCode;
    /**
     * @access public
     * @var string
     */
    public $TerminalCityName;
    /**
     * @access public
     * @var string
     */
    public $TerminalAddress;
    /**
     * @access public
     * @var string
     */
    public $TerminalPhone;

    public function __construct($object)
    {
        $object->CityCode = intval($object->CityCode);
        $object->ZipCode = intval($object->ZipCode);
        $object->TerminalCityCode = intval($object->TerminalCityCode);
        $object->CourierDelivery = boolval($object->CourierDelivery);
        $object->TariffZone = intval($object->TariffZone);
        $object->DeliveryPeriod = floatval($object->DeliveryPeriod);
        parent::__construct($object);
    }
}