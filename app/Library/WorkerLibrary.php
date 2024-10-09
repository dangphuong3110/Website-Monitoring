<?php

namespace App\Library;

use App\Library\Log as Log;
use App\Library\CurlLibrary;

class WorkerLibrary
{
    public static $url = 'https://worker.zozo.vn/api/v1';
    public static $type = 'html';
    public static $api_token = 'xtxCJuKERU9kpw4StydKb1Bxv6JmgXvGyvqg10R4ZXuRoV3IpQantgWBBEZ1';

    public static $myiNETConfig = [
        'host' => 'https://my.inet.vn',
        'path' => '/api/client',
        'email' => 'tieudv@zozo.vn'
    ];

    /**
     * Html functions
     * @return void
     */
    public static function html($action = '', $data = [], &$output = null)
    {
        if(!count($data)) {
            Log::warning('Input params nut or is invalid');
            return false;
        }

        $action = (strlen($action)) ? $action : 'encode';
        $url = self::$url . '/html/' . $action;

        // Call API service
        $resp = self::call($url, $data, $output);
        if($resp && isset($output->data)) {
            $output = $output->data;
        }

        return $resp;
    }

    /**
     * Url functions
     * @return void
     */
    public static function url($action = '', $data = [], &$output = null)
    {
        if(!count($data)) {
            Log::warning('Input params nut or is invalid');
            return false;
        }

        $action = (strlen($action)) ? $action : 'short';
        $url = self::$url . '/url/' . $action;

        if(!isset($data['domain'])) {
            $data['domain'] = 'worker.zozo.vn';
        }

        // Call API service
        $resp = self::call($url, $data, $output);
        if($resp && isset($output->data)) {
            $output = $output->data;
        }

        return $resp;
    }

    /**
     * QrCode functions
     * @return void
     */
    public static function qrcode($action = '', $data = [], &$output = null)
    {
        if(!count($data)) {
            Log::warning('Input params nut or is invalid');
            return false;
        }

        $action = (strlen($action)) ? $action : 'encode';
        $url = self::$url . '/qr-code/' . $action;

        if(!isset($data['type'])) {
            $data['type'] = 'text';
        }

        // Call API service
        $resp = self::call($url, $data, $output);
        if($resp && isset($output->data)) {
            $output = $output->data;
        }

        return $resp;
    }

    public static function callMyiNET($api, $data, &$output = null)
    {

    }

    /**
     * Send email via Worker
     * @param string $action
     */
    public static function mail($action = '', $data, &$output = null)
    {
        $action = (strlen($action)) ? $action : 'send';
        $url = self::$url . '/mail/' . $action;

        $emailConfig = config('app.email_config');

        // Lay thong tin gui mac dinh
        $pemail = $emailConfig['econtract'];

        if(isset($data['econtract']->owner) && strlen($data['econtract']->owner)) {
            $domain = explode('@', $data['econtract']->owner)[1];
            if(strlen($domain) && count(explode('.', strtolower($domain)))) {
                $brand = explode('.', strtolower($domain))[0];
                if(isset($emailConfig[$brand])) {
                    $pemail = $emailConfig[$brand];
                }
            }
        }

        $data = (array)$data;
        if(!isset($data['to']) || !strlen($data['to'])) {
            Log::warning('Email sent is invalid or has not been provided');
            return false;
        }

        if (!isset($data['fromEmail']) || !strlen($data['fromEmail'])
            || (isset($pemail['email']) && $pemail['email'] != trim($data['fromEmail']))) {
            $data['fromEmail'] = $pemail['email'];
            $data['fromName'] = $pemail['name'];
        }

        $data['engine'] = 'sendgrip';
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $data['merchantId'] = 6;
        $data['merchantName'] = $_SERVER['HTTP_HOST'] ?? (str_replace(array("http://", "https://"), "", config('app.url')));
        $data['content'] = isset($data['content']) && strlen($data['content']) ? $data['content'] : '';
        $data['contentType'] = self::isHtml($data['content']) ? 'html' : 'text';

        // Call API service
        self::call($url, $data, $output);

        if (!isset($output->status) || (isset($output->status) && $output->status == 'error')) {
            $m = $output->message ?? '';
            Log::warning('Cannot sent email to: ' . $data['to'] .', '. json_encode($m));
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
            $params = array();
//            $params['header'] = array();
            $params['header'] = array('Content-type: application/json');

            $curl = new CurlLibrary();
            $resp = $curl->post($url . '?api_token=' . self::$api_token, json_encode($data), $params);

            $output = json_decode($resp);
        } catch (\Exception $e) {
            Log::warning('Cannot connect to Worker: ' . $e->getMessage());
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
