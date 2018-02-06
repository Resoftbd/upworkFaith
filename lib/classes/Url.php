<?php
    class Url {
        static function getCurrent() {
            $params = explode('/', $_SERVER['REQUEST_URI']);
            array_pop($params);
            $path = implode('/', $params);
            return (isset($_SERVER['HTTPS']) ? "https" : "http").'://'.$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$path;
        }
    }