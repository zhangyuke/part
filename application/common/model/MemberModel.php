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
    const USER_TYPE=[1=>'普通用户',2=>'带队'];

    /**
     * 关联收货地址表
     */
    public function address()
    {
        return $this->hasMany('MemberAddressModel','mid');
    }


    /**
     * 获取用户
     * @param $id
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function get_user_info($id)
    {
        return self::where('id',$id)->find();
    }


}