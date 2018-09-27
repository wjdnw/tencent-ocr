<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26
 * Time: 10:58
 */

namespace Packs\TencentOcr\Repositories;

use Illuminate\Config\Repository;
use Packs\TencentOcr\Http;

class BaseRepository
{
    protected $configkey = 'tencentOcrConfig';
    protected $appid;
    protected $secretid;
    protected $secretkey;
    protected $creatorqq;
    protected $EXPIRED_SECONDS;

    protected $headerHost = 'api.youtu.qq.com';

    protected $_httpInfo='';

    public function __construct( Repository $config )
    {
//        $tt = "app_id=10009633&url=http://f10.baidu.com/it/u=2772311368,822921617&fm=72";
//        echo strlen($tt);exit;
        $this->appid     = $config->get( $this->configkey.'.app.AppID' );
        $this->secretid  = $config->get( $this->configkey.'.app.SecretID' );
        $this->secretkey = $config->get( $this->configkey.'.app.SecretKey' );
        $this->creatorqq = $config->get( $this->configkey.'.app.appQQ' );
        $this->EXPIRED_SECONDS = $config->get( $this->configkey.'.app.EXPIRED_SECONDS' );
    }

    /**
     * 签名
     * @return string
     */
    public function authorization()
    {
        $now = time();
        $rdm = rand();
        $expired = $now + $this->EXPIRED_SECONDS;
        $plainText = 'a='.$this->appid.'&k='.$this->secretid.'&e='.$expired.'&t='.$now.'&r='.$rdm.'&u='.$this->creatorqq;
        $bin = hash_hmac("SHA1", $plainText, $this->secretkey, true);
        $bin = $bin.$plainText;
        $sign = base64_encode($bin);
        return $sign;

        $r = rand();
        $times = time();
        $etime = $times + $this->EXPIRED_SECONDS;
        $orignal = "u={$this->creatorqq}&a={$this->appid}&k={$this->secretid}&e={$etime}&t={$times}&r={$r}&f=";
        $sign_str = base64_encode(hash_hmac('sha1', $orignal, $this->secretkey, true).$orignal);
        return $sign_str;
    }

    /**
     * 设置头部信息
     */
    public function setHeader( $data = [])
    {
        $sign_str = $this->authorization();
        $header = [
//            'Host'           => $this->headerHost,
            'Content-Type'   => 'text/json',
//            'Content-Length' => (int)strlen(json_encode( $data )),
            'Authorization'  => $sign_str
        ];//headers
//        $header = [
//            'Host:'. $this->headerHost,
//            'Content-Type:text/json',
//            'Content-Length: 360',
//            'Authorization:'. $sign_str
//        ];//headers
        return $header;
    }


    public function httpPost( $url, $data )
    {
        $result = \App\Ryp\Http::post( $url, $data, [ 'headers'=>$this->setHeader( $data ) ] );
        dd( $result );
    }


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
    public function send( $rq ) {
        $rsp = Http::send( $rq );
        $ret = json_decode( $rsp, true );
        if ( !$ret ) {
            $data = ['status'=> 1, 'message'=>Http::statusText(), 'data'=>''];
        } else {
            $data = ['status'=> 0, 'message'=>'ok', 'data'=>$rsp];
        }
        return $data;
    }
//    public function send( $rq ) {
//        $rsp  = Http::send( $rq );
//        $ret  = json_decode( $rsp, true );
//        if ( !$ret ) {
//            return ['status'=>1];
//        }
//        if(!$ret){
//            return ['status'=>1, 'message'=>Http::statusText(),'data'=>[]];
//        }
////        return $ret;
//        $data = ['vin'=>$ret['items']['0']['itemstring'],'result'=>$ret];
//        return [ 'status'=>0, 'message'=>'ok', 'data'=>$data ];
//    }
}