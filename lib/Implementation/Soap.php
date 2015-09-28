<?php

namespace Morfin60\BoxberryApi\Implementation;

use Morfin60\BoxberryApi\ApiInterface;
use Morfin60\BoxberryApi\Exception\ApiException;

/**
 * Класс, реализущий SOAP интерфейс API Boxberry
 * @author Alexander N <morfin60@gmail.com>
 * @package boxberry-api
 */
class Soap implements ApiInterface
{

    /**
     * @var SoapClient;
     */
    private $public_api;

    /**
     * @var SoapClient;
     */
    private $lc_api;

    /**
     * @var string $api_key
     * @access private
     */
    private $api_key;

    /**
     * @param string $api_key
     * @param string $base_url
     */
    public function __construct($api_key, $api_url, $use_https, $options = [])
    {

        $url = 'http://'.$api_url;

        $this->api_key = $api_key;

        $this->public_api = new \SoapClient($url.'/__soap/1c_public.php?wsdl', [
            'version' => SOAP_1_2,
            'exceptions' => true,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        ]);

        $this->lc_api = new \SoapClient($url.'/__soap/1c_lc.php?wsdl', [
            'version' => SOAP_1_2,
            'exceptions' => true,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function listCities()
    {
        return $this->sendRequest('ListCities')->result;
    }

    /**
     * {@inheritdoc}
     */
    public function listPoints($city_code = 0, $prepaid = 0)
    {
        return $this->sendRequest('listPoints', [
            'CityCode' => $city_code,
            'prepaid' => $prepaid
        ])->result;
    }

    /**
     * {@inheritdoc}
     */
    public function listZips()
    {

        $response = $this->sendRequest('listZips');
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function zipCheck($zip)
    {
        $response = $this->sendRequest('zipCheck', ['zip' => $zip]);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function listStatuses($im_id)
    {
        $response =  $this->sendRequest('listStatuses', ['imcode' => $im_id]);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function listStatusesFull($im_id)
    {
        $response = $this->sendRequest('listStatusesFull', ['imcode' => $im_id]);
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function listServices($im_id)
    {
        $response = $this->sendRequest('listServices', ['imcode' => $im_id]);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function courierListCities()
    {
        return $this->sendRequest('courierListCities')->result;
    }

    /**
     * {@inheritdoc}
     */
    public function deliveryCosts($parameters = [])
    {
        return $this->sendRequest('deliveryCosts', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function pointsForParcels()
    {
        return $this->sendRequest('pointsForParcels');
    }

    /**
     * {@inheritdoc}
     */
    public function pointsByPostCode($zip)
    {
        return $this->sendRequest('pointsByPostCode', ['zip' => $zip]);
    }

    /**
     * {@inheritdoc}
     */
    public function pointsDescription($code, $photo = 0)
    {
        $result = $this->sendRequest('pointsDescription', [
            'code' => $code,
            'photo' => $photo
        ]);

        $result->Reception = null;
        $result->IssuanceBoxberry = null;
        $result->TariffZone = null;
        $result->DeliveryPeriod = null;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function parselCreate($data)
    {
        return $this->sendRequest('parselCreate', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function parselCheck($im_id)
    {
        $response = $this->sendRequest('parselCheck', ['ImId' => $im_id]);
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function parselList()
    {
        $response = $this->sendRequest('parselList');
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function parselDel($im_id)
    {
        return $this->sendRequest('parselDel', [
            'ImId' => $im_id
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function parselStory($from = '', $to = '')
    {
        $api_parameters = [];

        if ( '' !== $from) {
            $api_parameters['from'] = $from;
        }

        if ( '' !== $to) {
            $api_parameters['to'] = $to;
        }

        $response = $this->sendRequest('parselStory', $api_parameters);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function parselSend($im_ids)
    {
        $response = $this->sendRequest('parselSend', ['ImIds' => $im_ids]);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function parselSendStory($from = '', $to = '')
    {
        $api_parameters = [];

        if ( '' !== $from) {
            $api_parameters['from'] = $from;
        }

        if ( '' !== $to) {
            $api_parameters['to'] = $to;
        }

        $response = $this->sendRequest('parselSendStory', $api_parameters);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     */
    public function ordersBalance($only_postpaid = 0)
    {
        $response = $this->sendRequest('ordersBalance', [
            'OnlyPostpaid' => $only_postpaid
        ]);
        return $response->result;
    }

    /**
     * {@inheritdoc}
     * @throws SoapFault
     */
    public function sendRequest($method, $parameters = [], $options = [])
    {
        $methods = [
            'private' => [
                'ParselCreate',
                'ParselCheck',
                'ParselList',
                'ParselDel',
                'ParselStory',
                'ParselSend',
                'ParselSendStory',
                'OrdersBalance'
            ]
        ];

        $method_name = ucfirst($method);

        $soap_client = (in_array($method_name, $methods['private']))?$this->lc_api:$this->public_api;

        $request_parameters = array_merge([
            'token' => $this->api_key
        ], $parameters);

        try {
            $result = $soap_client->{$method_name}($request_parameters);
            return  $result;
        }
        catch(\SoapFault $error) {
            //If code returned by $error->getCode() === 0 that means API error occured
            if ( 0 === $error->getCode() ) {
                throw new ApiException($error->getMessage(), ApiException::API_ERROR);
            }
            //Else it means SOAP error occured and we should rethrow with previous exception $error
            else {
                throw $error;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey()
    {
        return $this->api_key;
    }
}