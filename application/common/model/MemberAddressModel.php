<?php
namespace app\common\model;

use think\Model;

/**
 * 用户地址
 * Class MemberModel
 * @package app\common\model
 */
class MemberAddressModel extends Model{


    protected $table='store_member_address';

#获取指定用户的收货地址
    public static function getUserAddress($id)
    {
        return self::where(['mid' => $id])->field('id,name,phone,address,is_default,province,city,area')->select();
    }




}