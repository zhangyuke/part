<?php



namespace app\company\controller;

use library\Controller;

/**
 * 网络打卡管理
 * Class Clock
 * @package app\company\controller
 */
class Clock extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'CompanyUserClock';

    /**
     * 网络打卡管理
     * @auth true
     * @auth true
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '网络打卡管理';
        $this->_query($this->table)->like('name')->equal('date')->order('id asc')->page();
    }

}
