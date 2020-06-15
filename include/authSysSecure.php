<?php

   namespace Secure;

    class AuthSysSecure
    {
        public function __construct()
        {
        }
        
        public function cleanInput($valInput, $type)
        {
            switch ($type) {
                case 'str':
                    $cleanInput = filter_var($valInput, FILTER_SANITIZE_STRING);
                    break;
                case 'int':
                    $cleanInput = filter_var($valInput, FILTER_SANITIZE_NUMBER_INT);
                    break;
                default:
                    $cleanInput = filter_var($valInput, FILTER_SANITIZE_STRING);
                    break;
            }

            return $cleanInput;
        }

        public function getToken(){
            return bin2hex(random_bytes(64));
        }
    }
