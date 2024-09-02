<?php

        // app/Helpers/Multilingual_helper.php
    if (!function_exists('translate_phrase')) {
        function translate_phrase($phrase)
        {
            $multilingual = new \App\Libraries\Multilingual();
            return $multilingual->_ph($phrase);
        }
    }
