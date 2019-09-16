<?php


namespace app\index\controller;

use app\common\model\PartMemberModel;
use app\common\model\PartModel;
use Endroid\QrCode\QrCode;
use think\Cache;

/**
 * 打卡签到
 * Class Index
 * @package app\index\controller
 */
class PartAuth extends Base
{

    /**
     * 我的兼职列表
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

        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();exit();

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







}
