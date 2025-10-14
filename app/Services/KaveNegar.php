<?php

namespace App\Services;

use Exception;
use GuzzleHttp\{Client, Exception\GuzzleException, RequestOptions};
use Illuminate\Support\Facades\Log;

/**
 * Class KaveNegar
 * @package App\Services\KaveNegar
 * @since 1.0
 * @author Parviz Ansaryan
 */
class KaveNegar
{
    const APIKEY = "70374D436D6D4D65616B3048314359434872353034456B514B66306A506B6B3257306C7A5A33306A6D68553D";
    const BASEURL = "https://api.kavenegar.com";

    /**
     * executes the main method.
     *
     * @param string $method
     * @param string $url url
     * @return object Indicates the curl execute result
     * @throws GuzzleException
     */
    private static function execute(string $method, string $url): object
    {
        $client = new Client([
            'verify' => false 
        ]);

        $result = $client->$method(
            $url,
            [
                RequestOptions::JSON => [],
                'headers' => [
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded',
                    'charset: utf-8'
                ],
                'connect_timeout' => 30
            ]
        );

        return json_decode($result->getBody());
    }

    /**
     * @param string $message
     * @param array $phones
     * @return false|object|void
     * @throws GuzzleException
     */
    public static function sendSms(string $message, array $phones)
    {
        $parameters = ['receptor' => implode(',', $phones), 'message' => $message];

        try {
            $result = self::execute('post', self::buildUrl('sms', 'send', $parameters));

            if ($result->return->status == 200) return $result->entries;

            return false;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param int $messageId
     * @return false|object|void
     * @throws GuzzleException
     */
    public static function getSms(int $messageId)
    {
        $parameters = ['messageid' => $messageId];

        try {
            $result = self::execute('get', self::buildUrl('sms', 'select', $parameters));

            if ($result->return->status == 200) return $result->entries;

            return false;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    private static function buildBaseUrl(string $base, string $method): string
    {
        return self::BASEURL . "/v1/" . self::APIKEY . "/{$base}/{$method}.json";
    }

    private static function buildUrl(string $base, string $method, array $data): string
    {
        return self::buildBaseUrl($base, $method) . '?' . http_build_query($data);
    }
}
