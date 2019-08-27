<?php
namespace app\common\model;

use think\Model;

/**
 * 兼职 记录
 * Class MemberModel
 * @package app\common\model
 */
class WithdrawModel extends Model{


    protected $table='withdraw';

    const STATUS=[1=>'提交',2=>'通过',3=>'拒绝'];

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }




}