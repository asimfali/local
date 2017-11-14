<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 14.11.2017
 * Time: 13:32
 */

namespace Custom;


class MyPost
{
    public function __construct($url, $arr)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
                'content' => $arr,
            ]
        ]);
//        echo file_get_contents(
//            $file =
//        );
    }
}