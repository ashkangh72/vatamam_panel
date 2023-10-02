<?php

namespace App\Services;

use Exception;
use GuzzleHttp\{Client, Exception\GuzzleException, RequestOptions};
use Illuminate\Support\Facades\Log;

/**
 * Class FarazSms
 * @package App\Services\FarazSms
 * @since 1.0
 * @author Parviz Ansaryan
 */
class FarazSms
{
    const APIKEY = "jguzEktINc6DNctm0oEN7yKqck9s3dAXvaLvvTlfioY=";
    const PATTERN_CODE = "tu1gf9d6b4r7a2h";
    const BASEURL = "https://rest.ippanel.com";
    const ORIGINATOR = "+989999173212";
    const PATTERN_ORIGINATOR = "+983000505";

    /**
     * executes the main method.
     *
     * @param string $method
     * @param array $data array of json data
     * @param string $url url
     * @return object Indicates the curl execute result
     * @throws GuzzleException
     */
    private static function execute(string $method, array $data, string $url): object
    {
        $client = new Client();

        $result = $client->$method(
            $url,
            [
                RequestOptions::JSON => $data,
                'headers' => [
                    'Authorization' => "AccessKey " . self::APIKEY
                ],
                'connect_timeout' => 30
            ]
        );

        return json_decode($result->getBody());
    }

    /**
     * @param array $values
     * @param string $phone
     * @return false|object|void
     * @throws GuzzleException
     */
    public static function sendPattern(string $phone, array $values)
    {
        $body = [
            "pattern_code" => self::PATTERN_CODE,
            "originator" => self::PATTERN_ORIGINATOR,
            "recipient" => $phone,
            "values" => $values
        ];

        try {
            $result = self::execute('post', $body, self::BASEURL . "/v1/messages/patterns/send");

            return $result->data;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param string $message
     * @param array $phones
     * @return false|object|void
     * @throws GuzzleException
     */
    public static function sendSms(string $message, array $phones)
    {
        $body = [
            "originator" => self::ORIGINATOR,
            "recipients" => $phones,
            "message" => $message
        ];
        try {
            $result = self::execute('post', $body, self::BASEURL . "/v1/messages");

            return $result->data;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @return false|object|void
     * @throws GuzzleException
     */
    public static function getCredit()
    {
        try {
            $result = self::execute('get', [], self::BASEURL . "/v1/credit");

            return $result->data->credit;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @return false|object|void
     * @throws GuzzleException
     */
    public static function getSms(int $bulkId)
    {
        try {
            $result = self::execute('get', [], self::BASEURL . "/v1/messages/" . $bulkId);

            return $result->data->message;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

}
