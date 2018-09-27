<?php

namespace Packs\TencentOcr;

class Http
{
    public static $_httpInfo = '';

    /**
     * send http request
     * @param  array $rq http请求信息
     *                   url        : 请求的url地址
     *                   method     : 请求方法，'get', 'post', 'put', 'delete', 'head'
     *                   data       : 请求数据，如有设置，则method为post
     *                   header     : 需要设置的http头部
     *                   host       : 请求头部host
     *                   timeout    : 请求超时时间
     *                   cert       : ca文件路径
     *                   ssl_version: SSL版本号
     * @return string    http请求响应
     */
    public static function send($rq) {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $rq['url']);
        switch (true) {
            case isset($rq['method']) && in_array(strtolower($rq['method']), array('get', 'post', 'put', 'delete', 'head')):
                $method = strtoupper($rq['method']);
                break;
            case isset($rq['data']):
                $method = 'POST';
                break;
            default:
                $method = 'GET';
        }
        $header = isset($rq['header']) ? $rq['header'] : array();
        $header[] = 'Method:'.$method;
        $header[] = 'User-Agent:'.self::getUA();
        isset($rq['host']) && $header[] = 'Host:'.$rq['host'];
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $method);
        isset($rq['timeout']) && curl_setopt($curlHandle, CURLOPT_TIMEOUT, $rq['timeout']);
        isset($rq['data']) && in_array($method, array('POST', 'PUT')) && curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $rq['data']);
        $ssl = substr($rq['url'], 0, 8) == "https://" ? true : false;
        if( isset($rq['cert'])){
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER,true);
            curl_setopt($curlHandle, CURLOPT_CAINFO, $rq['cert']);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST,2);
            if (isset($rq['ssl_version'])) {
                curl_setopt($curlHandle, CURLOPT_SSLVERSION, $rq['ssl_version']);
            } else {
                curl_setopt($curlHandle, CURLOPT_SSLVERSION, 4);
            }
        }else if( $ssl ){
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER,false);   //true any ca
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST,2);       //check only host
            if (isset($rq['ssl_version'])) {
                curl_setopt($curlHandle, CURLOPT_SSLVERSION, $rq['ssl_version']);
            } else {
                curl_setopt($curlHandle, CURLOPT_SSLVERSION, 4);
            }
        }
        $ret = curl_exec($curlHandle);
//        self::$_httpInfo = curl_getinfo($curlHandle);
        curl_close($curlHandle);
        return $ret;
    }

    public static function info() {
        return self::$_httpInfo;
    }

    // 标示SDK 版本
    public static function getUA() {
        $pkg_version = '1.0.*';
        return 'YoutuPHP/'.$pkg_version.' ('.php_uname().')';
    }

    /**
     * return the status message
     */
    public static function statusText()
    {
        $info=self::info();
        $status=$info['http_code'];
        switch ($status)
        {
            case 0:
                $statusText = 'CONNECT_FAIL';
                break;
            case 200:
                $statusText = 'HTTP OK';
                break;
            case 400:
                $statusText = 'BAD_REQUEST';
                break;
            case 401:
                $statusText = 'UNAUTHORIZED';
                break;
            case 403:
                $statusText = 'FORBIDDEN';
                break;
            case 404:
                $statusText = 'NOTFOUND';
                break;
            case 411:
                $statusText = 'REQ_NOLENGTH';
                break;
            case 423:
                $statusText = 'SERVER_NOTFOUND';
                break;
            case 424:
                $statusText = 'METHOD_NOTFOUND';
                break;
            case 425:
                $statusText = 'REQUEST_OVERFLOW';
                break;
            case 500:
                $statusText = 'INTERNAL_SERVER_ERROR';
                break;
            case 503:
                $statusText = 'SERVICE_UNAVAILABLE';
                break;
            case 504:
                $statusText = 'GATEWAY_TIME_OUT';
                break;
            default:
                $statusText =$status;
                break;
        }
        return $statusText;
    }
}