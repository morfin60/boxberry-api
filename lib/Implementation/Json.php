<?php

namespace Morfin60\BoxberryApi\Implementation;

use Morfin60\BoxberryApi\ApiInterface;
use Morfin60\BoxberryApi\Exception\ApiException;
use Morfin60\BoxberryApi\Exception\JsonException;


/**
 * Класс, реализущий JSON интерфейс API Boxberry
 * @author Alexander N <morfin60@gmail.com>
 * @package boxberry-api
 */
class Json implements ApiInterface
{
    /**
     * @var \GuzzleHttp\Client()
     * @access private
     */
    private $client;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var string $api_key
     */
    private $api_key;

    /**
     * @param string $api_key
     * @param string $base_url
     */
    public function __construct($api_key, $api_url, $use_https)
    {

        $url = (( true === $use_https)?'https':'http').'://'.$api_url;

        $this->url = $api_url.'/json.php';
        $this->api_key = $api_key;
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * {@inheritdoc}
     */
    public function listCities()
    {
        return $this->sendRequest('ListCities');
    }

    /**
     * {@inheritdoc}
     */
    public function listPoints($city_code = 0, $prepaid = 0)
    {
        return $this->sendRequest('listPoints', [
            'CityCode' => $city_code,
            'prepaid' => $prepaid
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function listZips()
    {

        $response = $this->sendRequest('listZips');
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function zipCheck($zip)
    {
        $response = $this->sendRequest('zipCheck', ['Zip' => $zip]);
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function listStatuses($im_id)
    {
        return $this->sendRequest('listStatuses', ['ImId' => $im_id]);
    }

    /**
     * {@inheritdoc}
     */
    public function listStatusesFull($im_id)
    {
        return $this->sendRequest('listStatusesFull', ['ImId' => $im_id]);
    }

    /**
     * {@inheritdoc}
     */
    public function listServices($im_id)
    {
        return $this->sendRequest('listServices', ['ImId' => $im_id]);
    }

    /**
     * {@inheritdoc}
     */
    public function courierListCities()
    {
        return $this->sendRequest('courierListCities');
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

        if ($photo) {
            $result->Photos = $result->photos;
            unset($result->photos, $result->photo_link);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function parselCreate($data)
    {
        $response = $this->sendRequest('parselCreate', [
            'sdata' => json_encode($data)
        ], ['type' => 'POST']);
        return $response;
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

        return $this->sendRequest('parselStory', $api_parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function parselSend($im_ids)
    {
        return $this->sendRequest('parselSend', ['ImIds' => $im_ids]);
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

        return $this->sendRequest('parselSendStory', $api_parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function ordersBalance($only_postpaid = 0)
    {
        return $this->sendRequest('ordersBalance', [
            'OnlyPostpaid' => $only_postpaid
        ]);
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function sendRequest($method, $parameters = [], $options = [])
    {
        $type = 'GET';
        if (array_key_exists('type', $options)) {
            $allowed_types = ['GET', 'POST'];
            if (!in_array($options['type'], $allowed_types)) {
                throw new InvalidArgumentException('Invalid request type '.$options['type']);
            }
            else {
                $type = $options['type'];
            }
        }

        $method_name = ucfirst($method);

        $request_parameters = array_merge([
            'method' => $method_name,
            'token' => $this->api_key
        ], $parameters);

        try {
            if ( 'GET' === $type) {
                $response = $this->client->get($this->url, [
                    'query' => $request_parameters,
                    'connect_timeout' => 10
                ]);
            }
            else {
                $response = $this->client->post($this->url, [
                    'form_params' => $request_parameters,
                    'connect_timeout' => 10
                ]);
            }

            $json_data = $response->getBody()->getContents();
        }
        catch(\GuzzleHttp\Exception\GuzzleException $error) {
            throw new ApiException($error->getMessage(), ApiException::HTTP_ERROR, $error);
        }

        //Удаление BOM в выводе если есть
        $json_data = preg_replace('~^\xEF\xBB\xBF~isu','', $json_data);
        $data = json_decode($json_data, false);

        //В случае неудачи разбора JSON кидаем исключение JsonException
        if (NULL === $data) {
            throw new JsonException('Failed to parse received json with message '.json_last_error_msg(), JsonException::DECODE_EXCEPTION, null, $json_data);
        }
        //Если API вернуло какую-то ошибку
        if (
                is_array($data) &&
                isset($data[0]) &&
                isset($data[0]->err)
        ) {
            throw new ApiException('Errors occured while processing API request', ApiException::API_ERROR, null, $data[0]->err);
        }
        elseif (is_object($data) && isset($data->err)) {
            throw new ApiException('Errors occured while processing API request', ApiException::API_ERROR, null, $data->err);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey()
    {
        return $this->api_key;
    }
}