<?php



namespace app\store\controller;

use library\Controller;

/**
 * 商品轮播
 * Class GoodsCate
 * @package app\store\controller
 */
class GoodsBanner extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'store_banner';

    /**
     * 商品分类管理
     * @auth true
     * @menu true
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '商品轮播';
        $query = $this->_query($this->table)->like('title')->equal('status');
        $query->order('sort desc,id desc')->page();
    }

    /**
     * 添加商品分类
     * @auth true
     */
    public function add()
    {
        $this->title = '添加商品轮播';
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑商品分类
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑商品轮播';
        $this->_form($this->table, 'form');
    }

    /**
     * 禁用商品分类
     * @auth true
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用商品分类
     * @auth true
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除商品分类
     * @auth true
     */
    public function remove()
    {
        $this->_delete($this->table);
    }

}
