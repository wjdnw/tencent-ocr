<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27
 * Time: 10:47
 */

namespace Packs\TencentOcr\Repositories;

use Illuminate\Config\Repository;
class LicensePlateOcrRepository extends BaseRepository
{
    protected $url = 'https://api.youtu.qq.com/youtu/ocrapi/plateocr';

    public function __construct( Repository $config )
    {
        parent::__construct( $config );
    }

    /**
     * 图片连接
     * @param $url
     * @return bool|string
     */
    public function vinUrl( $url )
    {
        if ( !$url ) {
            return false;
        }

        $post_data = [
            'app_id'     => $this->appid,
            'url'        => $url
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
     * @return bool|string
     */
    public function vinImage( $base64Image )
    {
        if ( !$base64Image ) {
            return false;
        }

        $base64Image = str_replace('data:image/png;base64,','',$base64Image);

        $post_data = [
            'app_id'     => $this->appid,
            'image'      => $base64Image
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