<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'blublog_settings';

    public static function get_blublog_version()
    {
        $value = Cache::remember('blublog.version', 86400, function () {
            $def = array(
                'ver' => config('blublog.version'),
                'msg' => 'Could not check for updates.',
            );
            if (extension_loaded('curl')) {
                $data = [
                    'software' => 'blublog',
                    'ver' => config('blublog.version'),
                ];
                if (Setting::send_post_request('https://blublog.info/api/get-update-info', $data)) {
                    return Setting::send_post_request('https://blublog.info/api/get-update-info', $data);
                }
                return $def;
            } else {
                return $def;
            }
        });
        return $value;
    }
    public static function send_post_request($url, $data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);
        return $response;
    }
    public static function set_default_settings()
    {
        $settings = config('blublog');
        $keys = array_keys($settings);
        for ($i = 4; $i < count($settings); $i = $i + 2) {
            blublog_setting($keys[$i]);
        }
        return true;
    }
    public static function PhpCheck()
    {
        if (!isset($errors)) {
            $errors = array();
        }

        if (!extension_loaded('gd')) {
            array_push($errors, __('blublog.gd_not_installed'));
        }
        if (!extension_loaded('mbstring')) {
            array_push($errors, 'Грешка. Липсва PHP разширението Multibyte String. | Error. PHP Multibyte String is NOT installed.');
        }
        if (!extension_loaded('bcmath')) {
            array_push($errors, 'Грешка. Липсва PHP разширението bcmath. | Error. PHP bcmath is NOT installed.');
        }
        if (!extension_loaded('ctype')) {
            array_push($errors, 'Грешка. Липсва PHP разширението ctype. | Error. PHP ctype is NOT installed.');
        }
        if (!extension_loaded('json')) {
            array_push($errors, 'Грешка. Липсва PHP разширението json. | Error. PHP json is NOT installed.');
        }
        if (!extension_loaded('openssl')) {
            array_push($errors, 'Грешка. Липсва PHP разширението openssl. | Error. PHP openssl is NOT installed.');
        }
        if (!extension_loaded('PDO')) {
            array_push($errors, 'Грешка. Липсва PHP разширението PDO. | Error. PHP PDO is NOT installed.');
        }
        if (!extension_loaded('Tokenizer')) {
            array_push($errors, 'Грешка. Липсва PHP разширението Tokenizer. | Error. PHP Tokenizer is NOT installed.');
        }
        if (!extension_loaded('XML')) {
            array_push($errors, 'Грешка. Липсва PHP разширението XML. | Error. PHP XML is NOT installed.');
        }
        if (!extension_loaded('zip')) {
            array_push($errors, 'Грешка. Липсва PHP разширението Zip. | Error. PHP zip is NOT installed.');
        }

        return $errors;
    }
}
