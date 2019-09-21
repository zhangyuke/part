<?php
namespace app\common\model;

use think\Model;

/**
 * 购物车
 * Class MemberModel
 * @package app\common\model
 */
class ShopPingCarModel extends Model{


    protected $table='store_shop_ping_car';

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }






}