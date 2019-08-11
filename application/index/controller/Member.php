<?php


namespace app\index\controller;

use think\Validate;
use app\common\model\MemberModel;

/**
 * 用户入口
 * Class Index
 * @package app\index\controller
 */
class Member extends Base
{


    /**
     * 用户信息
     */
    public function index()
    {

        $user=MemberModel::get_user_info($this->user_id);

        return return_json($user);
    }

    /**
     * 信息认证
     */
    public function auth_user()
    {
        $user=MemberModel::get_user_info($this->user_id);
        if($user['is_auth'] == 1){
            //return return_json([],'已认证',400);
        }
        //'mobile'=>'mobile'  'id_card'=>'idCard'  'name'=>'chs'
        $data=input();
        $rule =   [
            'username'  => 'require|chs',
            'phone'   => 'require|mobile',
            'id_card' => 'require|idCard',
        ];
        $message  =   [
            'username.require' => '姓名必须',
            'username.chs'     => '姓名必须是汉字',
            'phone.require'   => '手机号必须',
            'phone.mobile'  => '手机号格式错误',
            'id_card.require'        => '身份证号必须',
            'id_card.idCard'        => '身份证格式错误',
        ];

        $validate =new Validate($rule, $message);
        $result   = $validate->check($data);

        if($result == false){
            return return_json([],$validate->getError(),400);
        }

        $data_arr=[
            'username'=>$data['username'],
            'id_card'=>$data['id_card'],
            'is_auth'=>1,
            'sex'=>get_card_sex($data['id_card']),
            'age'=>get_card_age($data['id_card']),
            'update_at'=>date('Y-m-d H:i:s')
        ];
        MemberModel::where('id',$this->user_id)->update($data_arr);

        return return_json();

    }
}
