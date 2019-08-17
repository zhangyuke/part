<?php


namespace app\index\controller;

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
        
        $user=MemberModel::get_user_info($this->user_id);

        return return_json($user);
    }











}
