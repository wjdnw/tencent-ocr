<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26
 * Time: 10:38
 */

namespace Packs\TencentOcr\Facades;


use Illuminate\Support\Facades\Facade;

class TencentOcrFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tencent-ocr';
    }
}