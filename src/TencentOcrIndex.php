<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26
 * Time: 10:36
 */

namespace Packs\TencentOcr;

use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;
use Packs\TencentOcr\Repositories\BankCardOcrRepository;
use Packs\TencentOcr\Repositories\BizLicenseOcrRepository;
use Packs\TencentOcr\Repositories\DriverLicenseOcrRepository;
use Packs\TencentOcr\Repositories\IdCardOcrRepository;
use Packs\TencentOcr\Repositories\InvoiceOcrRepository;
use Packs\TencentOcr\Repositories\LicensePlateOcrRepository;
use Packs\TencentOcr\Repositories\VinOcrRepository;

class TencentOcrIndex
{
    /**
     * @var SessionManager
     */
    protected $session;
    /**
     * @var Repository
     */
    protected $config;
    /**
     * Packagetest constructor.
     * @param SessionManager $session
     * @param Repository $config
     */
    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    /**
     * vin识别
     * @param int $type
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function vinOcr( $type=0, $url='', $base64img='' )
    {
        $vinocr = new VinOcrRepository( $this->config );
        if ( $type == 0 ) {
            $result = $vinocr->vinImage( $base64img );
        } else {
            $result = $vinocr->vinUrl( $url );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }

    /**
     * 车牌识别
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function licensePlateOcr( $url='', $base64img='' )
    {
        $ocr = new LicensePlateOcrRepository( $this->config );
        if ( $base64img ) {
            $result = $ocr->vinImage( $base64img );
        } else {
            $result = $ocr->vinUrl( $url );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }

    /**
     * 银行卡识别
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function bankCardOcr( $url='', $base64img='' )
    {
        $ocr = new BankCardOcrRepository( $this->config );
        if ( $base64img ) {
            $result = $ocr->vinImage( $base64img );
        } else {
            $result = $ocr->vinUrl( $url );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }

    /**
     * 身份证识别
     * @param $card_type  0身份证图片类型，0-正面，1-反面
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function idCardOcr( $card_type=0, $url='', $base64img='' )
    {
        $ocr = new IdCardOcrRepository( $this->config );
        if ( $base64img ) {
            $result = $ocr->vinImage( $base64img,$card_type );
        } else {
            $result = $ocr->vinUrl( $url,$card_type );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }

    /**
     * 行驶证驾驶证识别
     * @param int $type
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function driverLicenseOcr( $type=0, $url='', $base64img='' )
    {
        $ocr = new DriverLicenseOcrRepository( $this->config );
        if ( $base64img ) {
            $result = $ocr->vinImage( $base64img,$type );
        } else {
            $result = $ocr->vinUrl( $url,$type );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }

    /**
     * 营业执照识别
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function bizLicenseOcr( $url='', $base64img='' )
    {
        $ocr = new BizLicenseOcrRepository( $this->config );
        if ( $base64img ) {
            $result = $ocr->vinImage( $base64img );
        } else {
            $result = $ocr->vinUrl( $url );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }

    /**
     * 增值税发票识别
     * @param string $url
     * @param string $base64img
     * @return array
     */
    public function invoiceOcr( $url='', $base64img='' )
    {
        $ocr = new InvoiceOcrRepository( $this->config );
        if ( $base64img ) {
            $result = $ocr->vinImage( $base64img );
        } else {
            $result = $ocr->vinUrl( $url );
        }
        return ['status'=>$result['status'], 'data'=>$result['data'], 'message'=>$result['message']];
    }
}