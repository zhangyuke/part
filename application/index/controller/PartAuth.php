<?php


namespace app\index\controller;

use app\common\model\MemberModel;
use app\common\model\PartMemberModel;
use app\common\model\PartModel;
use Endroid\QrCode\QrCode;
use think\facade\Cache;
use think\Db;

/**
 * 打卡签到
 * Class Index
 * @package app\index\controller
 */
class PartAuth extends Base
{

    /**
     * 带队我的兼职列表
     */
    public function get_part()
    {
        $page=input('page',1);
        $part_list=PartModel::where('status',1)
            ->where('contact_phone',$this->phone)
            ->order('sort','desc')
            ->order('id','desc')->page($page,15);
        $part_list=$part_list->select();

        return return_json($part_list);
    }

    /**
     * 带队我的兼职详情
     */
    public function get_part_info()
    {
        $part_id=input('part_id');
        //查询今天的兼职
        $part=PartModel::where('id',$part_id)->where('contact_phone',$this->phone)->find();
        if(!$part){
            return return_json([],'不存在的信息',400);
        }
        //报名人员
        $mid=PartMemberModel::where('part_id',$part_id)
            ->column('mid');

        $part->member=MemberModel::whereIn('id',$mid)->select();

        return return_json($part);
    }


    /**
     * 带队二维码
     */
    public function team_code()
    {
        //兼职id
        $part_id=input('part_id');

        //查询今天的兼职
        $part=PartModel::where('id',$part_id)->where('contact_phone',$this->phone)->find();
        if(!$part){
            return return_json([],'不存在的信息',400);
        }
        if($part['status'] != 1){
            return return_json([],'招聘已经关闭',400);
        }
        $str = "1234567890asdfghjklqwertyuiopzxcvbnmASDFGHJKLZXCVBNMPOIUYTREWQ";
        $str_rand=substr(str_shuffle($str),0,6);
        Cache::set($str_rand,$str_rand,600);
        $urlToEncode=WEB_URL.'/index/part_auth/check_in?part='.$part['id'].'&str_rand='.$str_rand;

        $qrCode = new QrCode($urlToEncode);

        //header('Content-Type: '.$qrCode->getContentType());


        $app_path=\think\facade\Env::get('root_path');
        // 移动到服务器的上传目录 并且设置不覆盖
        $path=$app_path.'public/qrcode/'.date('Ymd').time().rand(0000,66666).$part_id.'qrcode.png';

        $qrCode->writeFile($path);
        $path_url=str_replace($app_path.'public/','',$path);
        return return_json(WEB_URL.'/'.$path_url,'成功',200);
        //echo $qrCode->writeString();exit();

    }


    /**
     * 学生打卡
     */
    public function check_in()
    {
        $str_rand=input('str_rand');
        if(!Cache::get($str_rand)){
           return return_json('','二维码已过期',400);
        }
        $part_id=input('part');
        $part=PartModel::where('id',$part_id)
            ->where('status',1)->find();
//        if($part['word_time'] != date('Y-m-d')){
//            return return_json('','工作日期不符',400);
//        }
        //查询是否报名
        $part_member=PartMemberModel::where(['mid'=>$this->user_id,'part_id'=>$part['id'],'status'=>1])->find();
        if(!$part_member){
            return return_json('','您报名未通过或活动已结束',400);
        }
        //获取当前时间 上午还是下午
        $h_time=date('H');
        if($h_time <12){//上午
            if($part_member['go_work_time']){
                return return_json('','已签到',400);
            }else{
                PartMemberModel::where('id',$part_member['id'])->update(['go_work_time'=>date('Y-m-d H:i:s'),'update_at'=>date('Y-m-d H:i:s')]);
            }
        }else{//下午
            PartMemberModel::where('id',$part_member['id'])->update(['off_work_time'=>date('Y-m-d H:i:s'),'update_at'=>date('Y-m-d H:i:s')]);
        }
        return return_json('','打卡成功');
    }

    /**
     * 学生 报名
     */
    public function add_part()
    {
        $part_id=input('part_id');
        //查询今天的兼职
        $part=PartModel::where('id',$part_id)->where('status',1)->find();
        if(!$part){
            return return_json([],'不存在的信息',400);
        }
        if(PartMemberModel::where(['mid'=>$this->user_id])->count()){
            return return_json([],'已报名',400);
        }
        /**
         * 添加记录
         */
        //添加订单
        Db::startTrans();
        try{
            PartMemberModel::insert([
                'status'=>0,
                'create_at'=>date('Y-m-d H:i:s'),
                'update_at'=>date('Y-m-d H:i:s'),
                'mid'=>$this->user_id,
                'part_id'=>$part['id'],
            ]);
            Db::commit();
            return return_json([],'报名成功，请等待审核',200);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return return_json('','提交失败',400);
        }

    }

    /**
     * 学生报名记录
     */
    public function get_part_member()
    {
        $page=input('page',1);

        $part_list=PartMemberModel::where('mid',$this->user_id)
            ->order('id','desc')->page($page,15);
        $part_list=$part_list->select();
        foreach ($part_list as $k=>$v)
        {
            $v->part=PartModel::where('id',$v['part_id'])->find();
            $v->status=PartMemberModel::STATUS[$v->status];
        }


        return return_json($part_list);

    }






}
