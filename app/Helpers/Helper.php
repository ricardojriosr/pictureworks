<?php

namespace App\Helpers;

class Helper {
    public static function isJson($jsonString) {
        json_decode($jsonString);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

?>