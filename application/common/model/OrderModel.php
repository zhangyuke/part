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

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }






}