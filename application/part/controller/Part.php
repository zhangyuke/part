<?php



namespace app\part\controller;

use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 兼职信息管理
 * Class Goods
 * @package app\store\controller
 */
class Part extends Controller
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'part';

    /**
     * 兼职信息管理
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
        $this->title = '兼职信息管理';
        $query = $this->_query($this->table)->equal('status,cate_id')->like('title');
        //限制用户
        $contact_phone=session('admin_user')['username'];
        if($contact_phone != 'admin'){
            $query->where(['contact_phone' =>$contact_phone]);
        }
        $query->where(['is_deleted' => '0'])->order('sort desc,id desc')->page();
    }

    /**
     * 数据列表处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _index_page_filter(&$data)
    {
        $this->clist = Db::name('PartCate')->where(['is_deleted' => '0', 'status' => '1'])->select();



        foreach ($data as &$vo) {
            list($vo['list'], $vo['cate']) = [[], []];
            foreach ($this->clist as $cate) if ($cate['id'] === $vo['cate_id']) $vo['cate'] = $cate;
        }
    }



    /**
     * 添加兼职信息
     * @auth true
     */
    public function add()
    {
        $this->title = '添加兼职信息';
        $this->isAddMode = '1';
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑兼职信息
     * @auth true
     */
    public function edit()
    {
        $this->title = '编辑兼职信息';
        $this->isAddMode = '0';

        $this->_form($this->table, 'form');
    }

    /**
     * 表单数据处理
     * @param array $data
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function _form_filter(&$data)
    {

        // 生成兼职ID
        //if (empty($data['id'])) $data['id'] = Data::uniqidNumberCode(10);
        if ($this->request->isGet()) {


            $data['contact_phone']=session('admin_user')['username'];
            $this->cates = Db::name('PartCate')->where(['is_deleted' => '0', 'status' => '1'])->order('sort desc,id desc')->select();
        }
    }

    /**
     * 表单结果处理
     * @param boolean $result
     */
    protected function _form_result($result)
    {
        if ($result && $this->request->isPost()) {
            $this->success('兼职编辑成功！', 'javascript:history.back()');
        }
    }

    /**
     * 禁用兼职信息
     * @auth true
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用兼职信息
     * @auth true
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除兼职信息
     * @auth true
     */
    public function remove()
    {
        $this->_delete($this->table);
    }

}
