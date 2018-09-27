<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27
 * Time: 13:55
 */

namespace Packs\TencentOcr\Repositories;

use Illuminate\Config\Repository;
class DriverLicenseOcrRepository extends BaseRepository
{
    protected $url = 'https://api.youtu.qq.com/youtu/ocrapi/driverlicenseocr';

    public function __construct( Repository $config )
    {
        parent::__construct( $config );
    }

    /**
     * 图片连接
     * @param $url
     * @param $type
     * @return bool|string
     */
    public function vinUrl( $url,$type )
    {
        if ( !$url ) {
            return false;
        }

        $post_data = [
            'app_id'     => $this->appid,
            'url'        => $url,
            'type'       => $type
        ];

        $req = array(
            'url' => $this->url,
            'method' => 'post',
            'timeout' => 0,
            'data' => json_encode($post_data),
            'header' => array(
                'Authorization:'.$this->authorization(),
                'Content-Type:text/json'
            ),
        );
        $rsp  = $this->send($req);
        return $rsp;
    }

    /**
     * 图片base64
     * @param $base64Image
     * @param $type
     * @return bool|string
     */
    public function vinImage( $base64Image,$type )
    {
        if ( !$base64Image ) {
            return false;
        }

        if( strpos($base64Image,';base64,') !== false ) {
            $img_arr = explode(';base64,', $base64Image);
            unset( $img_arr[0] );
            $base64Image = implode( '', $img_arr );
        }

        $post_data = [
            'app_id'     => $this->appid,
            'image'      => $base64Image,
            'type'       => $type
        ];
        $req = array(
            'url' => $this->url,
            'method' => 'post',
            'timeout' => 0,
            'data' => json_encode($post_data),
            'header' => array(
                'Authorization:'.$this->authorization(),
                'Content-Type:text/json'
            ),
        );

        $rsp  = $this->send($req);
        return $rsp;
    }
}