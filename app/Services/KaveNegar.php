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
    const APIKEY = "6130566564485A663967784B58322B787436564C4F627436496563494355464F372B2F464E486F747462343D";
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
        $client = new Client();

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
