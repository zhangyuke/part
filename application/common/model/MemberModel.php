<?php
namespace app\common\model;

use think\Model;

/**
 * 用户
 * Class MemberModel
 * @package app\common\model
 */
class MemberModel extends Model{


    protected $table='store_member';

    /**
     * 关联收货地址表
     */
    public function address()
    {
        return $this->hasMany('MemberAddressModel','mid');
    }



}