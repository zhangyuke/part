<?php



namespace app\store\controller;

use app\common\model\MemberModel;
use library\Controller;

/**
 * 会员信息管理
 * Class Member
 * @package app\store\controller
 */
class Member extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StoreMember';

    /**
     * 会员信息管理
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
        $this->title = '会员信息管理';
        $query = $this->_query($this->table)->like('nickname,username,phone')->equal('is_auth');
        $query->dateBetween('create_at')->order('id desc')->page();
    }


    /**
     * 数据列表处理
     * @param $data
     */
    protected function _index_page_filter(&$data)
    {

        foreach ($data as &$vo) {
            $vo['user_type']=MemberModel::USER_TYPE[$vo['user_type']];

        }
    }

}
