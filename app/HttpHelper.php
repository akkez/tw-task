<?php

class HttpHelper
{
    public static function makeRequest($url, $post = null, $returnHeaders = false, $customHeaders = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        if ($returnHeaders) {
            curl_setopt($ch, CURLOPT_HEADER, $returnHeaders);
        }
        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if (count($customHeaders) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $customHeaders);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch) != 0) {
            return null;
//            echo 'cURL error: ' . curl_errno($ch) . ' / ' . curl_error($ch) . "\n";
        }
        curl_close($ch);

        return $response;
    }

}