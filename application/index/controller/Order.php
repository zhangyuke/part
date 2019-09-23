<?php


namespace app\index\controller;

use app\common\model\GoodsModel;
use app\common\model\MemberAddressModel;
use app\common\model\OrderListModel;
use app\common\model\OrderModel;
use app\common\model\ShopPingCarModel;
use think\Db;
use think\Exception;
use think\Validate;
use app\common\model\MemberModel;

/**
 * 订单
 * Class Index
 * @package app\index\controller
 */
class Order extends Base
{


    /**
     * 支付页面
     */
    public function index()
    {
        $order_no=input('order_no');
        $pay_price=OrderModel::where('order_no',$order_no)->value('pay_price');
        if(!$pay_price){
            return return_json('','参数错误',400);
        }
        $balance=MemberModel::where('id',$this->user_id)->value('balance');

        return return_json(['price'=>$pay_price,'balance'=>$balance]);
    }


    /**
     * 确认订单页面
     *
     */
    public function is_add_order()
    {
        $goods_id=input('goods_id');

        if($goods_id > 0){
            $goods = GoodsModel::field(['logo','title','price'])->where(['id'=>$goods_id,'status'=>1])->select();

            return return_json($goods,'成功',200);

        }
        $car_id=input('car_id');

        if(!$car_id){
            return return_json('','参数错误',400);
        }
        $car_id=explode(',',$car_id);

        $goods_id =ShopPingCarModel::whereIn('id',$car_id)->column('goods_id');

        if(count($goods_id)>0){

            foreach ($goods_id as $k=>$v){
                $goods = GoodsModel::field(['logo','title','price'])->where(['id'=>$v,'status'=>1])->find();
                if(!$goods){
                    return return_json('',$goods['title'].'商品不存在',400);
                }
            }

        }else{
            return return_json('','商品不存在',400);
        }
        $all_goods=GoodsModel::field(['logo','title','price'])->whereIn('id',$goods_id)->select();

        return return_json($all_goods);

    }



    /**
     * 下单
     */
    public function add_order()
    {
        $goods_id=input('goods_id');
        if(!$goods_id){
            return return_json('','参数错误',400);
        }
        $goods=GoodsModel::where('id',$goods_id)->where('status',1)->find();
        if(!$goods){
            return return_json('','商品不存在',400);
        }
        $a_id=input('a_id');
        $address = MemberAddressModel::where('id', $a_id)->where('mid',$this->user_id)->find();
        if(!$address){
            return return_json('','收货地址不存在',400);
        }
        $count=OrderModel::where(['mid'=>$this->user_id,'status'=>1,'price_goods'=>$goods->price])->count();
        if($count > 0){
            return return_json('','请勿重复下单',400);
        }

        do{
            $order_no=date('Ymdhis').mt_srand(000000,999999).time();

        }while(OrderModel::where('order_no',$order_no)->find());
        //添加订单
        Db::startTrans();
        try{
        OrderModel::insert(
            [
                'mid'=>$this->user_id,
                'order_no'=>$order_no,
                'price_goods'=>$goods->price,
                'pay_price'=>$goods->price,
                'price_total'=>$goods->price,
                'status'=>1,
                'express_address_id'=>$address->id,
                'express_name'=>$address->name,
                'express_phone'=>$address->phone,
                'express_province'=>$address->province,
                'express_city'=>$address->city,
                'express_area'=>$address->area,
                'express_address'=>$address->address,
                'create_at'=>date('Y-m-d H:i:s'),
                'update_at'=>date('Y-m-d H:i:s')
            ]
        );
        //添加订单详情
        OrderListModel::insert([
            'order_no'=>$order_no,
            'goods_id'=>$goods->id,
            'goods_title'=>$goods->title,
            'goods_logo'=>$goods->logo,
            'price_selling'=>$goods->price,
            'number_goods'=>1,
            'create_at'=>date('Y-m-d H:i:s'),
        ]);
            Db::commit();
            return return_json(['order_no'=>$order_no],'订单创建成功',200);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return return_json('','提交失败',400);
        }


    }


    /**
     * 购物车下单
     */
    public function car_add_order()
    {
        $car_id=input('car_id');

        if(!$car_id){
            return return_json('','参数错误',400);
        }
        $car_id=explode(',',$car_id);

        $car =ShopPingCarModel::whereIn('id',$car_id)->select();

        if(count($car)>0){

            foreach ($car as $k=>$v){
                $goods = GoodsModel::where(['id'=>$v['goods_id'],'status'=>1])->count();
                if($goods < 1 ){
                    return return_json('',$goods['title'].'商品不存在',400);
                }
            }

        }else{
            return return_json('','商品不存在',400);
        }
        $a_id=input('a_id');
        $address = MemberAddressModel::where('id', $a_id)->where('mid',$this->user_id)->find();
        if(!$address){
            return return_json('','收货地址不存在',400);
        }

        do{
            $order_no=date('Ymdhis').mt_srand(000000,999999).time();

        }while(OrderModel::where('order_no',$order_no)->find());
        $count_price=0;



        //添加订单
        Db::startTrans();
        try{



            foreach ($car as $k=>$v){

                $goods = GoodsModel::field(['logo','title','price','id'])->where(['id'=>$v['goods_id']])->find();
                if($goods){
                    $count_price+=$goods->price * $v['num'];

                    //添加订单详情
                    OrderListModel::insert([
                        'order_no'=>$order_no,
                        'goods_id'=>$goods->id,
                        'goods_title'=>$goods->title,
                        'goods_logo'=>$goods->logo,
                        'price_selling'=>$goods->price,
                        'price_real'=>$goods->price,
                        'number_goods'=>$v['num'],
                        'create_at'=>date('Y-m-d H:i:s'),
                    ]);
                }
            }

            OrderModel::insert(
                [
                    'mid'=>$this->user_id,
                    'order_no'=>$order_no,
                    'price_goods'=>$count_price,
                    'pay_price'=>$count_price,
                    'price_total'=>$count_price,
                    'status'=>1,
                    'express_address_id'=>$address->id,
                    'express_name'=>$address->name,
                    'express_phone'=>$address->phone,
                    'express_province'=>$address->province,
                    'express_city'=>$address->city,
                    'express_area'=>$address->area,
                    'express_address'=>$address->address,
                    'create_at'=>date('Y-m-d H:i:s'),
                    'update_at'=>date('Y-m-d H:i:s')
                ]
            );
//删除购物车
            ShopPingCarModel::whereIn('id',$car_id)->delete();
            Db::commit();
            return return_json(['order_no'=>$order_no],'订单创建成功',200);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return return_json('','提交失败',400);
        }


    }






}
