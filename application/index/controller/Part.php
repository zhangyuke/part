<?php



namespace app\index\controller;

use app\common\model\PartModel;
use library\Controller;

/**
 * 兼职入口
 * Class Index
 * @package app\index\controller
 */
class Part extends Controller
{
    /**
     * 入口跳转链接
     */
        public function index()
    {
        //兼职轮播图
        $banner=db('part_banner')->where('status',1)->order('sort','desc')->limit(6)->select();
        //兼职分类
        $cate=db('part_cate')->where('status',1)->order('sort','desc')->limit(6)->select();
        //兼职列表
        $part=PartModel::where('status',1)->order('sort','desc')->limit(8)->select();

        $longitude=input('longitude');
        $latitude=input('latitude');

        $part=PartModel::get_distance($part,$longitude,$latitude);
        $data=[
            'cate'=>$cate,
            'banner'=>$banner,
            'part'=>$part
        ];
        return return_json($data,'成功',200);
    }


    /**
     * 兼职列表
     */
    public function get_part()
    {
        $key=input('key');
        $cate_id=input('cate_id');
        $page=input('page') ?? 1;
        $part_list=PartModel::where('status',1)->order('sort','desc')->order('id','desc')->page($page,15);
        if($key){
            $part_list=$part_list->where('title','like','%'.$key.'%');

        }
        if($cate_id){
            $part_list=$part_list->where('cate_id',$cate_id);

        }

        $part_list=$part_list->select();

        $longitude=input('longitude');
        $latitude=input('latitude');

        $part_list=PartModel::get_distance($part_list,$longitude,$latitude);



        return return_json($part_list,'成功',200);


    }

    /**
     *兼职详情
     */
    public function get_part_info()
    {
        $part_id=input('part_id');
        $longitude=input('longitude');
        $latitude=input('latitude');
        if(!$part_id){
            return return_json([],'参数错误',400);
        }
        $part_info=PartModel::where('id',$part_id)->where('status',1)->find();

        if($part_info){
            $part_info['distance']=getDistance($longitude,$latitude,$part_info['longitude'],$part_info['latitude']);
            $part_info['image']=string_to_arr($part_info['image']);
        }


        return return_json($part_info,'成功',200);
    }
}
