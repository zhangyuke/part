<?php



namespace app\service\controller;

use library\Controller;

/**
 * 开放平台参数配置
 * Class Config
 * @package app\service\controller
 */
class Config extends Controller
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'WechatServiceConfig';

    /**
     * 显示参数配置
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '开放平台参数配置';
        $this->fetch();
    }

    /**
     * 修改参数配置
     * @auth true
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function edit()
    {
        $this->applyCsrfToken();
        if ($this->request->isGet()) {
            $this->fetch('form');
        } else {
            $post = $this->request->post();
            foreach ($post as $k => $v) sysconf($k, $v);
            $this->success('参数修改成功！');
        }
    }

}
