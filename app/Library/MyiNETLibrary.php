<?php

namespace App\Library;

//use App\Library\Log as Log;
use Illuminate\Support\Facades\Log;
use App\Library\JwtLibrary as JwtLibrary;

class MyiNETLibrary
{
    public static $url = 'https://my.inet.vn';
    public static $type = 'html';
    public static $path = '/api/client';
    public static $email = 'tieudv@zozo.vn';

    /**
     * Send email via Worker
     * @param string $action
     */
    public static function telebotSend($action = 'send', $data, &$output = null)
    {
        $action = (strlen($action)) ? $action : 'send';
        $url = self::$url . self::$path . '/v1/telebotaccount/' . $action;

        $data['topicCode'] = 'my-task';

        // Call API service
        self::call($url, $data, $output);

        if (!isset($output->status) || (isset($output->status) && $output->status == 'error')) {
            $m = isset($output->message) ? $output->message : '';
//            Log::warning('Cannot sent to: ' . $data['accountEmail'] .', '. $m);
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return false|void
     */
    private static function call($url, $data, &$output)
    {
        try {
            $sidToken = (new JwtLibrary())->jwtEncode(array('email' => self::$email));

            $params = array();
            $params['header'] = array();
            $params['header'] = array('Content-Type: application/json', 'sid: ' . $sidToken);

            $curl = new CurlLibrary();
            $resp = $curl->post($url, json_encode($data), $params);

            $output = json_decode($resp);
        } catch (\Exception $e) {
            $message = sprintf("cannot connect to worker %s in %s%s trace: %s", $e->getMessage(), $e->getLine(), $e->getFile(), $e->getTraceAsString());
            Log::warning($message);
            return false;
        }

        return true;
    }

    /**
     * @param $str
     * @return bool
     */
    public static function isHtml($str)
    {
        if (!strlen($str)) {
            return false;
        }

        if (preg_match('#(?<=<)\w+(?=[^<]*?>)#', $str)) {
            return true;
        }

        return false;
    }
}
