<?php

namespace Morfin60\BoxberryApi\Types;

/**
 *
 */
class DeliveryPoint extends Base
{
	/**
	 * @access public
	 * @var string
	 */
	public $Code;
	/**
	 * @access public
	 * @var string
	 */
	public $Name;
	/**
	 * @access public
	 * @var string
	 */
	public $objectddress;
	/**
	 * @access public
	 * @var string
	 */
	public $Phone;
	/**
	 * @access public
	 * @var string
	 */
	public $WorkSchedule;
	/**
	 * @access public
	 * @var string
	 */
	public $TripDescription;
	/**
	 * @access public
	 * @var string
	 */
	public $DeliveryPeriod;
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
	public $TariffZone;
	/**
	 * @access public
	 * @var string
	 */
	public $Settlement;
	/**
	 * @access public
	 * @var string
	 */
	public $objectrea;
	/**
	 * @access public
	 * @var string
	 */
	public $Country;
	/**
	 * @access public
	 * @var string
	 */
	public $GPS;
	/**
	 * @access public
	 * @var string
	 */
	public $objectddressReduce;
	/**
	 * @access public
	 * @var string
	 */
	public $OnlyPrepaidOrders;
	/**
	 * @access public
	 * @var string
	 */
	public $objectcquiring;
	/**
	 * @access public
	 * @var string
	 */
	public $DigitalSignature;

    public function __construct($object)
    {
    	//$object->Code = intval($object->Code);
    	$object->CityCode = isset($object->CityCode)?intval($object->CityCode):null;
    	$object->TariffZone = isset($object->TariffZone)?intval($object->TariffZone):null;
    	$object->DeliveryPeriod = isset($object->DeliveryPeriod)?intval($object->DeliveryPeriod):null;
        parent::__construct($object);
    }
}
