<?php


namespace app\index\controller;

use app\common\model\OrderModel;
use think\Exception;
use think\Validate;
use app\common\model\MemberModel;

/**
 * 用户订单
 * Class Index
 * @package app\index\controller
 */
class UserOrder extends Base
{


    /**
     * 支付页面
     */
    public function index()
    {
        $status=input('status')?? 1;
        $order=OrderModel::where('status',$status)->order('id desc')->select();

        return $order;
    }

    










}
