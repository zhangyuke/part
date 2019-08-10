<?php
namespace app\common\model;

use think\Model;

/**
 * 兼职 记录
 * Class MemberModel
 * @package app\common\model
 */
class PartMemberModel extends Model{


    protected $table='part_member';

    const STATUS=[0=>'申请报名',1=>'报名通过',2=>'报名拒绝',3=>'待结算',4=>'已结算'];

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }


    /**
     * 关联兼职
     */
    public function part()
    {
        return $this->belongsTo('PartModel','id','part_id');
    }


}