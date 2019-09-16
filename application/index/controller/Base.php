<?php



namespace app\index\controller;


use think\Controller;

/**
 * 应用入口
 * Class Index
 * @package app\index\controller
 */
class Base extends Controller
{
    protected $user_id;
    protected $phone;

    public function __construct()
    {
        session('user_id',1);
        session('contact_phone',22);
        if(!session('user_id')){

           echo  return_json([],'请先登录',401);   die();
        }
        $this->user_id=session('user_id');
        $this->phone=session('contact_phone');

    }










}
