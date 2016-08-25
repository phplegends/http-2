<?php

namespace PHPLegends\Http;

/**
 * Get all header via variable $_SERVER 
 * 
 * @return array
 * */
function get_all_headers()
{
    static $headers;

    if (!empty($headers)) {
        return $headers;
    }

    $headers = [];

    foreach ($_SERVER as $name => $value) {

        if (substr($name, 0, 5) === 'HTTP_') {

            $header_name = mb_convert_case(strtr(substr($name, 5), ['_' => '-']), MB_CASE_TITLE);

            $headers[$header_name] = $value;
        }
    }

    return $headers;
}
