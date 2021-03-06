<?php



namespace app\part\controller;

use library\Controller;

/**
 * 兼职分类管理
 * Class GoodsCate
 * @package app\store\controller
 */
class PartCate extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'part_cate';

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
        $this->title = '兼职分类管理';
        $query = $this->_query($this->table)->like('title')->equal('status');
        $query->where(['is_deleted' => '0'])->order('sort desc,id desc')->page();
    }

    /**
     * 添加商品分类
     * @auth true
     */
    public function add()
    {
        $this->title = '添加兼职分类';
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑兼职分类
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑兼职分类';
        $this->_form($this->table, 'form');
    }

    /**
     * 禁用兼职分类
     * @auth true
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用兼职分类
     * @auth true
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除兼职分类
     * @auth true
     */
    public function remove()
    {
        $this->_delete($this->table);
    }

}
