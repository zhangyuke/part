<?php
namespace app\common\model;

use think\Model;

/**
 * 兼职
 * Class MemberModel
 * @package app\common\model
 */
class PartModel extends Model{


    protected $table='part';

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }



}