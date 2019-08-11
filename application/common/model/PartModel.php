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

    //计算距离
    public static function get_distance($data,$longitude1,$latitude1)
    {
        foreach ($data as $k=>$v){
            $data[$k]['distance']=getDistance($longitude1,$latitude1,$v['longitude'],$v['latitude']);
        }
        return  $data;
    }





}