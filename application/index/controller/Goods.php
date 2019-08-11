<?php



namespace app\index\controller;

use app\common\model\GoodsModel;
use app\common\model\PartModel;
use library\Controller;

/**
 * 商城入口
 * Class Index
 * @package app\index\controller
 */
class Goods extends Controller
{
    /**
     * 入口跳转链接
     */
        public function index()
    {
        //轮播图
        $banner=db('store_banner')->where('status',1)->order('sort','desc')->limit(6)->select();
        //商城分类
        $cate=db('store_goods_cate')->where('status',1)->order('sort','desc')->limit(6)->select();
        //商城列表
        $goods=GoodsModel::where('status',1)->order('sort','desc')->limit(8)->select();

        $data=[
            'cate'=>$cate,
            'banner'=>$banner,
            'goods'=>$goods
        ];
        return return_json($data,'成功',200);
    }

    /**
     * 商品列表
     */
    public function get_list()
    {
        $key=input('key');
        $cate_id=input('cate_id');
        $page=input('page') ?? 1;
        $list=PartModel::where('status',1)->order('sort','desc')->order('id','desc')->page($page,15);
        if($key){
            $list=$list->where('title','like','%'.$key.'%');

        }
        if($cate_id){
            $list=$list->where('cate_id',$cate_id);

        }

        $list=$list->select();

        return return_json($list,'成功',200);


    }

    /**
     *兼职详情
     */
    public function get_info()
    {
        $goods_id=input('goods_id');

        if(!$goods_id){
            return return_json([],'参数错误',400);
        }
        $info=PartModel::where('id',$goods_id)->where('status',1)->find();

        if($info){
            $info['image']=string_to_arr($info['image']);
        }


        return return_json($info,'成功',200);
    }
}
