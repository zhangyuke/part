<?php
namespace app\common\model;

use think\Model;

/**
 * 订单
 * Class MemberModel
 * @package app\common\model
 */
class OrderModel extends Model{


    protected $table='store_order';

    const STATUS=[1=>'待付款',2=>'待发货',3=>'待收货',4=>'已完成'];
    const PAY_TYPE=[1=>'微信支付',2=>'积分支付',3=>'积分加微信'];

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }






}