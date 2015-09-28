<?php

namespace Morfin60\BoxberryApi\Mapper;

/**
 * Класс, преобразующий stdObject в другой тип
 * @author Alexander N <morfin60@gmail.com>
 * @package boxberry-api
 */
class Mapper
{

	/**
	 * @var array список соответствий имени классу
	 */
    private $mappings;

    /**
     * @param array
     */
    public function __construct($mappings = [])
    {
        $namespace = preg_replace('~^(.*)\\\\.*?$~isu', '\\1', __NAMESPACE__);
        $this->mappings = [
            'City' => $namespace.'\\Types\\City',
            'DeliveryPoint' => $namespace.'\\Types\\DeliveryPoint',
            'Zip' => $namespace.'\\Types\\ZipCode',
            'ZipDelivery' => $namespace.'\\Types\\ZipDelivery',
            'Status' => $namespace.'\\Types\\Status',
            'StatusFull' => $namespace.'\\Types\\StatusFull',
            'Service' => $namespace.'\\Types\\Service',
            'CourierCity' => $namespace.'\\Types\\CourierCity',
            'DeliveryPrice' => $namespace.'\\Types\\DeliveryPrice',
            'ParcelsPoint' => $namespace.'\\Types\\ParcelsPoint',
            'PointDescription' => $namespace.'\\Types\\PointDescription',
            'Parsel' => $namespace.'\\Types\\Parsel',
            'Act' => $namespace.'\\Types\\Act',
            'OrderBalance' => $namespace.'\\Types\\OrderBalance',
        ];
    }

    /**
     * @param array|object массив объектов или объект для преобразования
     * @param string $type тип, в который необходимо преобразовать $data
     * @return array|object
     */
    public function map($data, $type)
    {
        if (is_array($data)) {
            return $this->convertObjects($data, $this->mappings[$type]);
        }
        elseif (is_object($data)) {
            return new $this->mappings[$type]($data, $this);
        }
    }

    /**
     * @param $array массив объектов для преобразования
     * @param $type тип, в который данный массив будет преобразован
     * @return array массив преобразованных объектов
     */
    private function convertObjects($array, $class)
    {
        $result = array_map(function($object) use ($class) {
            return new $class($object, $this);
        }, $array);
        return $result;
    }
}