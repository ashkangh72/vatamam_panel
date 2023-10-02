<?php

namespace App\Services;

use Exception;
use GuzzleHttp\{Client, Exception\GuzzleException, RequestOptions};
use Illuminate\Support\Facades\Log;

/**
 * Class NajvaService
 * @package App\Services\NajvaService
 * @since 1.0
 * @author Parviz Ansaryan
 */
class NajvaService
{
    const APIKEY = "af41ac9d-2137-49e1-acfe-d09625e7114d";
    const TOKEN = "e3992a478142301fee83c7eb54fd45ad376fd2c1";
    const BASEURL = "https://app.najva.com/api";

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
                    'Authorization' => "Token " . self::TOKEN,
                    'Content-Type' => 'application/json',
                    'X-api-key' => self::APIKEY
                ],
                'connect_timeout' => 30
            ]
        );

        return json_decode($result->getBody());
    }

    /**
     * @param array $data
     * @param array $subscribers
     * @return object|void
     * @throws GuzzleException
     */
    public static function send(array $data, array $subscribers = [])
    {
        $body = [
            'title' => $data['title'],
            'body' => $data['body'],
            'onclick_action' => 0,
            'url' => array_key_exists('url', $data) ? $data['url'] : env('WEBSITE_URL'),
            'light_up_screen' => true,
            'buttons' => [
                [
                    'title' => 'مشاهده',
                    'url' => array_key_exists('url', $data) ? $data['url'] : env('WEBSITE_URL')
                ]
            ],
        ];

        if (empty($subscribers)) {
            $endPoint = self::BASEURL . "/v2/notification/management/send-campaign";
        } else {
            $body['subscribers'] = $subscribers;

            $endPoint = self::BASEURL . "/v2/notification/management/send-direct";
        }

        try {
            return self::execute('post', $body, $endPoint);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

}
