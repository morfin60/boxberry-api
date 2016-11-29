<?php

namespace Morfin60\BoxberryApi;

use Symfony\Component\Validator\Constraints as Assert;

use Morfin60\BoxberryApi\Exception\ApiException;
use Morfin60\BoxberryApi\Validation\Validator;


/**
 * Класс, реализующий JSON и SOAP API Boxberry
 * @author Alexander N <morfin60@gmail.com
 * @package boxberry-api
 */
class BoxberryApi implements ApiInterface
{
    const API_JSON = 'json';
    const API_SOAP = 'soap';

    const API_URL = 'api.boxberry.de';
    const API_URL_TEST = 'test.api.boxberry.de';

    /**
     * @var string
     */
    private $base_url;

    /**
     * @var ApiInterface
     */
    private $impl;

    /**
     * @var string[] Разрешенные типы
     */
    private $types;

    /**
     * @var Validation\Validator
     */
    private $validator;

    /**
     * @var Mapper\Mapper
     */
    private $mapper;

    /**
     * @param string $api_key
     * @param string $type
     * @param bool $use_https
     * @param bool $test
     */
    public function __construct($api_key, $type = BoxberryApi::API_SOAP, $use_https = false, $test = false)
    {
        $this->types = [self::API_JSON, self::API_SOAP];

        $this->validator = new Validator();

        $values = [
            'api_key' => $api_key,
            'type' => $type,
            'use_https' => $use_https,
            'test' => $test
        ];

        $constraint = new Assert\Collection([
            'fields' => [
                'api_key' => new Assert\Required([
                    new Assert\NotNull(['message' => 'api_key should not be null']),
                    new Assert\Type(['type' => 'string', 'message' => 'api_key should be {{ type }}'])
                ]),
                'type' => new Assert\Required([
                    new Assert\Type(['type' => 'string', 'message' => 'type should be {{ type }}']),
                    new Assert\Choice(['choices' => $this->types, 'message' => 'type should be either JSON or SOAP'])
                ]),
                'use_https' => new Assert\Required([
                    new Assert\Type(['type' => 'bool', 'message' => 'use_https should be {{ type }}'])
                ]),
                'test' => new Assert\Required([
                    new Assert\Type(['type' => 'bool', 'message' => 'test should be {{ type }}'])
                ])
            ]
        ]);

        $this->validator->validateValues($values, $constraint);

        $class = __NAMESPACE__.'\\Implementation\\'.ucfirst($type);
        if (class_exists($class)) {
            $working_url = (true == $test) ? self::API_URL_TEST : self::API_URL;
            $this->impl = new $class($api_key, $working_url, $use_https);
        }
        else {
            throw new \InvalidArgumentException('Class for type {$type} does not exist', ApiException::BAD_API_CLASS);
        }

        $this->mapper = new Mapper\Mapper();
    }

    /**
     * {@inheritdoc}
     * @return Types\City[] список городов в виде массива
     */
    public function listCities()
    {
        return $this->mapper->map($this->impl->listCities(), 'City');
    }

    /**
     * {@inheritdoc}
     * @return Types\DeliveryPoint[] список пунктов выдачи заказов
     */
    public function listPoints($city_code = 0, $prepaid = 0)
    {

        $values = [
            'city_code' => $city_code,
            'prepaid' => $prepaid
        ];

        $constraint = new Assert\Collection([
            'city_code' => new Assert\Required([
                new Assert\Type(['type' => 'numeric', 'message' => 'city_code should be either json or soap']),
                new Assert\GreaterThanOrEqual(['value' => 0, 'message' => 'city_code should be greater or equal than {{ compared_value }}'])
            ]),
            'prepaid' => new Assert\Required([
                new Assert\Type(['type' => 'numeric', 'message' => 'prepaid should be {{ type }}']),
                new Assert\Choice(['value' => [0, 1], 'message' => 'prepaid should be either 0 or 1'])
            ])
        ]);

        $this->validator->validateValues($values, $constraint);
        return $this->mapper->map($this->impl->listPoints($city_code, $prepaid), 'DeliveryPoint');
    }

    /**
     * {@inheritdoc}
     * @return Types\ZipCode[] список почтовых индексов
     */
    public function listZips()
    {
        return $this->mapper->map($this->impl->listZips(), 'Zip');
    }

    /**
     * {@inheritdoc}
     * @return Types\ZipDelivery[] информация о возможности осуществления курьерской доставки для индекса
     */
    public function zipCheck($zip)
    {
        if (!isset($zip)) {
            throw new \InvalidArgumentException("Zip is mandatory");
        }
        $this->validator->validateZip($zip);
        $result = $this->impl->zipCheck($zip);
        $result = $this->mapper->map($result[0], 'ZipDelivery');
        return $result;
    }

    /**
     * {@inheritdoc}
     * @return Types\Status[] массив статусов посылки
     */
    public function listStatuses($im_id)
    {
        if (!isset($im_id)) {
            throw new \InvalidArgumentException("ImId is mandatory");
        }
        $this->validator->validateImId($im_id);
        $result = $this->mapper->map($this->impl->listStatuses($im_id), 'Status');
        return $result;
    }

    /**
     * {@inheritdoc}
     * @return Types\StatusFull[] массив статусов посылки
     */
    public function listStatusesFull($im_id)
    {
        if (!isset($im_id)) {
            throw new \InvalidArgumentException("ImId is mandatory");
        }
        $this->validator->validateImId($im_id);
        return $this->mapper->map($this->impl->listStatusesFull($im_id), 'StatusFull');
    }

    /**
     * {@inheritdoc}
     * @return Types\Service[] массив услуг, оказанных по отправлению
     */
    public function listServices($im_id)
    {
        if (!isset($im_id)) {
            throw new \InvalidArgumentException("ImId is mandatory");
        }
        $this->validator->validateImId($im_id);

        return $this->mapper->map($this->impl->listServices($im_id), 'Service');
    }

    /**
     * {@inheritdoc}
     * @return Types\CourierCity[] массив городов
     */
    public function courierListCities()
    {
        return $this->mapper->map($this->impl->courierListCities(), 'CourierCity');
    }

    /**
     * {@inheritdoc}
     * @return Types\DeliveryPrice[] массив, содержащий полную цену, а также составляющие этой цены
     */
    public function deliveryCosts($parameters = [])
    {
        $this->validator->validateDeliveryInfo($parameters);
        return $this->mapper->map($this->impl->deliveryCosts($parameters), 'DeliveryPrice');
    }

    /**
     * {@inheritdoc}
     * @return Types\ParcelsPoint[] массив с точками приёма посылок
     */
    public function pointsForParcels()
    {
        return $this->mapper->map($this->impl->pointsForParcels(), 'ParcelsPoint');
    }

    /**
     * {@inheritdoc}
     * @return Types\DeliveryPoint[]
     */
    public function pointsByPostCode($zip)
    {
        $this->validator->validateZip($zip);
        return $this->mapper->map($this->impl->pointsByPostCode($zip), 'DeliveryPoint');
    }

    /**
     * {@inheritdoc}
     * @return Types\PointDescription[] информация о пункте выдачи в виде массива
     */
    public function pointsDescription($code, $photo = 0)
    {
        $values = [
            'code' => $code,
            'photo' => $photo
        ];

        $constraint = new Assert\Collection([
            'code' => new Assert\Required([
                new Assert\Type(['type' => 'numeric', 'message' => 'code should be {{ type }}']),
                new Assert\GreaterThan(['value' => 0, 'message' => 'code should be greater than {{ compared_value }}'])
            ]),
            'photo' => new Assert\Required([
                new Assert\Type(['type' => 'numeric', 'message' => 'photo should be {{ type }}']),
                new Assert\Choice(['value' => [0, 1], 'message' => 'photo should be either 0 or 1'])
            ]),
        ]);
        $this->validator->validateValues($values, $constraint);
        return $this->mapper->map($this->impl->pointsDescription($code, $photo), 'PointDescription');
    }

    /**
     * {@inheritdoc}
     * @return Types\Parsel[] массив, содержащий ссылку на печать этикеток и трекинг-код посылки
     */
    public function parselCreate($data)
    {
        $values = [
            'data' => $data
        ];

        $constraint = new Assert\Collection([
            'data' => new Assert\Required([
                new Assert\Type(['type' => 'array', 'message' => 'Parsel data should be an {{ type }}'])
            ])
        ]);

        $this->validator->validateValues($values, $constraint);
        return $this->mapper->map($this->impl->parselCreate($data), 'Parsel');
    }

    /**
     * {@inheritdoc}
     */
    public function parselCheck($im_id)
    {
        $this->validator->validateImId($im_id);
        $result = $this->impl->parselCheck($im_id);
        return $result->label;
    }

    /**
     * {@inheritdoc}
     */
    public function parselList()
    {
        $result = $this->impl->parselList();
        return $result->ImIds;
    }

    /**
     * {@inheritdoc}
     */
    public function parselDel($im_id)
    {
        $this->validator->validateImId($im_id);
        $result = $this->impl->parselDel($im_id);
        return $result->text;
    }

    /**
     * {@inheritdoc}
     * @return Types\Parsel[] список посылок
     */
    public function parselStory($from = '', $to = '')
    {
        $this->validator->validatePeriod($from, $to);
        return $this->mapper->map($this->impl->parselStory($from, $to), 'Parsel');
    }

    /**
     * {@inheritdoc}
     * @return Types\Act[] массив, содержащий номер акта и ссылку для получения pdf
     */
    public function parselSend($im_ids)
    {
        $this->validator->validateImId($im_ids);
        return $this->mapper->map($this->impl->parselSend($im_ids), 'Act');
    }

    /**
     * {@inheritdoc}
     * @return Types\Parsel[] массив созданных актов передачи за указанный период
     */
    public function parselSendStory($from = '', $to = '')
    {
        $this->validator->validatePeriod($from, $to);
        return $this->mapper->map($this->impl->parselSendStory($from, $to), 'Parsel');
    }

    /**
     * {@inheritdoc}
     * @return Types\OrderBalance[] список заказов
     */
    public function ordersBalance($only_postpaid = 0)
    {
        $values = [
            'only_postpaid' => $only_postpaid
        ];

        $constraint = new Assert\Collection([
            'only_postpaid' => new Assert\Required([
                new Assert\Choice(['value' => [0, 1], 'message' => 'photo should be either 0 or 1'])
            ])
        ]);

        $this->validator->validateValues($values, $constraint);
        return $this->mapper->map($this->impl->ordersBalance($only_postpaid), 'OrderBalance');
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest($method_name, $parameters = [], $options = [])
    {
        return $this->impl->sendRequest($method_name, $parameters, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey()
    {
        return $this->impl->getApiKey();
    }
}
