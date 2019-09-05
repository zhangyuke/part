<?php



namespace app\store\controller;

use app\common\model\MemberModel;
use app\common\model\PartModel;
use app\common\model\WithdrawModel;
use library\Controller;

/**
 * 会员带队记录
 * Class Member
 * @package app\store\controller
 */
class TeamAuth extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'team_auth';

    /**
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
        $data=input();

        $this->title = '带队申请';
        $query = $this->_query($this->table);
        if(isset($data['nickname'])){
            $member_id=MemberModel::where('nickname','like','%'.$data['nickname'].'%')->column('id');
            $query=$query->whereIn('mid',$member_id);

        }
        if(isset($data['phone'])){
            $query=$query->where('phone',$data['phone']);

        }
        if(isset($data['status'])){
            $query=$query->where('status',$data['status']);
        }

        $query->dateBetween('create_at')->order('id desc')->page();
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

        $this->w_status=WithdrawModel::STATUS;

        foreach ($data as $k=>$v)
        {
            //用户信息
            $member=MemberModel::where('id',$v['mid'])->find();
            $data[$k]['nickname']=$member['nickname'];
            if($v['status'] ==1 ){
                $data[$k]['status_s']='提交';
            }elseif($v['status'] ==2 ) {
                $data[$k]['status_s'] = '通过';
            }elseif($v['status'] ==3 ){
                $data[$k]['status_s']='驳回';
            }

        }

    }


    /**
     * 禁用兼职信息
     * @auth true
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => 1]);
    }

    /**
     * 启用兼职信息
     * @auth true
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => 1]);
    }



}
