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