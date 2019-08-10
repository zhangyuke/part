<?php



namespace app\part\controller;

use app\common\model\MemberModel;
use app\common\model\PartMemberModel;
use app\common\model\PartModel;
use library\Controller;

/**
 * 会员报名信息管理
 * Class Member
 * @package app\store\controller
 */
class PartMember extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'part_member';

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

        $this->title = '兼职报名管理';
        $query = $this->_query($this->table)->equal('status');
        if(isset($data['username'])){
            $member_id=MemberModel::where('username','like','%'.$data['username'].'%')->column('id');
            $query=$query->whereIn('mid',$member_id);

        }
        if(isset($data['phone'])){
            $member_id=MemberModel::where('phone','like','%'.$data['phone'].'%')->column('id');
            $query=$query->whereIn('mid',$member_id);

        }
        if(isset($data['part_name'])){
            $part_id=PartModel::where('title','like','%'.$data['part_name'].'%')->column('id');
            $query=$query->whereIn('part_id',$part_id);

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

        $this->part_status=PartMemberModel::STATUS;

        foreach ($data as $k=>$v)
        {
            //用户信息
            $member=MemberModel::where('id',$v['mid'])->find();
            $data[$k]['username']=$member['username'];
            $data[$k]['sex']=$member['sex'];
            $data[$k]['age']=$member['age'];
            $data[$k]['phone']=$member['phone'];
            //兼职信息
            $part=PartModel::where('id',$v['part_id'])->find();
            $data[$k]['part_title']=$part['title'];

            $data[$k]['status_s']=PartMemberModel::STATUS[$v['status']];

        }

    }
}
