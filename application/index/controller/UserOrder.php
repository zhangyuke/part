<?php


namespace app\index\controller;

use app\common\model\OrderListModel;
use app\common\model\OrderModel;

/**
 * 用户订单
 * Class Index
 * @package app\index\controller
 */
class UserOrder extends Base
{


    /**
     * 订单列表
     */
    public function index()
    {
        $status=input('status',1);
        $page=input('page',1);
        $order=OrderModel::where('status',$status)
            ->where('mid',$this->user_id)->order('id desc')
            ->page($page,10)->select();
        foreach ($order as $k=>$v){
            $order[$k]->order_list=OrderListModel::where('order_no',$v->order_no)->select();
            $order[$k]->pay_type=OrderModel::PAY_TYPE[$v->pay_type];
        }
        return return_json($order);
    }

    /**
     * 订单详情
     */
    public function index_info()
    {
        $id=input('id');
        $order=OrderModel::where('id',$id)
            ->where('mid',$this->user_id)->find();

        if(!$order){
            return return_json('','参数错误',400);
        }
        $order['order_list']=OrderListModel::where('order_no',$order['order_no'])->select();
        $order['pay_type']  =OrderModel::PAY_TYPE[$order['pay_type']];

        return return_json($order);
    }


    /**
     * 完成订单
     */
    public function end_order()
    {
        $id=input('id');
        $order=OrderModel::where('id',$id)
            ->where('mid',$this->user_id)->where('status',3)->find();
        if(!$order){
           return return_json([],'订单不存在',400);
        }
        OrderModel::where('id',$id)->update(['status'=>4,'update_at'=>date('Y-m-d H:i:s')]);
        return return_json();
    }






}
