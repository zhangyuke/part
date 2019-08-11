<?php



namespace app\index\controller;

use library\Controller;

/**
 * 入口
 * Class Index
 * @package app\index\controller
 */
class Login extends Controller
{
    /**
     * 入口跳转链接
     */
    public function index()
    {
        session('user_id',1);
    }
}
