<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'blublog_settings';

    public static function set_default_settings(){
        $settings = config('blublog');
        $keys = array_keys($settings);
        for ($i=4; $i < count($settings); $i = $i + 2) {
            blublog_setting($keys[$i]);
        }
        return true;
    }
    public static function PhpCheck(){
        if(!isset($errors)){
            $errors = array();
        }

        if(!extension_loaded('gd')){
            array_push($errors, __('blublog.gd_not_installed'));
        }
        if(!extension_loaded('mbstring')){
            array_push($errors, 'Грешка. Липсва PHP разширението Multibyte String. | Error. PHP Multibyte String is NOT installed.');
        }
        if(!extension_loaded('bcmath')){
            array_push($errors, 'Грешка. Липсва PHP разширението bcmath. | Error. PHP bcmath is NOT installed.');
        }
        if(!extension_loaded('ctype')){
            array_push($errors, 'Грешка. Липсва PHP разширението ctype. | Error. PHP ctype is NOT installed.');
        }
        if(!extension_loaded('json')){
            array_push($errors, 'Грешка. Липсва PHP разширението json. | Error. PHP json is NOT installed.');
        }
        if(!extension_loaded('openssl')){
            array_push($errors, 'Грешка. Липсва PHP разширението openssl. | Error. PHP openssl is NOT installed.');
        }
        if(!extension_loaded('PDO')){
            array_push($errors, 'Грешка. Липсва PHP разширението PDO. | Error. PHP PDO is NOT installed.');
        }
        if(!extension_loaded('Tokenizer')){
            array_push($errors, 'Грешка. Липсва PHP разширението Tokenizer. | Error. PHP Tokenizer is NOT installed.');
        }
        if(!extension_loaded('XML')){
            array_push($errors, 'Грешка. Липсва PHP разширението XML. | Error. PHP XML is NOT installed.');
        }
        if(!extension_loaded('zip')){
            array_push($errors, 'Грешка. Липсва PHP разширението Zip. | Error. PHP zip is NOT installed.');
        }

        return $errors;

    }

}
