<?php
namespace app\common\model;

use think\Model;

/**
 * 商城
 * Class MemberModel
 * @package app\common\model
 */
class GoodsModel extends Model{


    protected $table='store_goods';

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('MemberModel','id','mid');
    }






}