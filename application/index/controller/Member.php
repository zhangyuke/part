<?php


namespace app\index\controller;

use app\common\model\MemberAddressModel;
use think\Db;
use think\Exception;
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





    /**
     ******************************************************************************************
     */

    #我的收货地址
    public function myAddress()
    {
        $data = MemberAddressModel::getUserAddress($this->user_id);
        return return_json($data,'成功',200);
    }

    #添加收货地址
    public function addAddress()
    {
        $param = input('param.');
        if ($param['is_default'] == 2) {
            if (MemberAddressModel::where(['mid' => $this->user_id, 'is_default' => 2])->count()) {
                MemberAddressModel::where('mid', $this->user_id)->update(['is_default' => 1]);
            }
        }

        $count=MemberAddressModel::where(['mid' => $this->user_id])->count();
        if($count >= 5){
            return return_json([],'最多添加五条收货地址',400);
        }

        $data               = [];
        $data['mid']        = $this->user_id;
        $data['name']       = $param['name'];
        $data['phone']      = $param['phone'];
        $data['province']   = $param['province'];
        $data['city']       = $param['city'];
        $data['area']       = $param['area'];
        $data['address']    = $param['address'];
        $data['is_default'] = $param['is_default'];
        $data['create_at'] = date('Y-m-d H:i:s');

        MemberAddressModel::insert($data);

        return return_json();
    }

    #获取单条地址
    public function getOneAddress()
    {
        $data = MemberAddressModel::where('id', input('param.id'))->find();
        return return_json($data,'成功',200);
    }

    #确认编辑收货地址
    public function editAddress()
    {
        $address = MemberAddressModel::where('id', input('param.id'))->find();
        $param = input('param.');
        $data  = [];

        $data['name']       = $param['name'];
        $data['phone']      = $param['phone'];
        $data['province']   = $param['province'];
        $data['city']       = $param['city'];
        $data['area']       = $param['area'];
        $data['address']    = $param['address'];

        $data['update_at'] = date('Y-m-d H:i:s')();

        if (!input('param.id') || input('param.id') == '') {
            return return_json($data,'缺少参数',400);
        }
        #开启事物
        Db::startTrans();
        try {
            if($address['is_default'] != 2){
                $data['is_default'] = $param['is_default'];
            }
            MemberAddressModel::where('id', input('param.id'))->update($data);
            MemberAddressModel::where(['mid'=>$address['mid'],'id'=>['<>', input('param.id')]])->update(['is_default'=>1]);
            #提交事物
            Db::commit();
            return return_json([],'编辑成功',200);
        } catch (Exception $e) {
            #回滚事物
            Db::rollback();
            return return_json([],'编辑失败',400);
        }
    }

    #删除收货地址
    public function deleteAddress()
    {
        $id = input('param.id');

        if (MemberAddressModel::where('id', $id)->value('is_default') == 2) {
            return return_json([],'默认地址不可删除',400);
        }

         MemberAddressModel::where(['id' => $id, 'mid' => $this->user_id])->delete();

        return return_json([],'删除成功',200);

    }

    #设置默认收货地址
    public function setDefault()
    {
        $id = input('param.id');

        #设置用户的收货地址全部
        $res = MemberAddressModel::where('mid', $this->user_id)->update(['is_default' => 1, 'update_at' => date('Y-m-d H:i:s')()]);

        if (!$res) {
            return return_json([],'失败',200);
        }

        MemberAddressModel::where(['id' => $id, 'mid' => $this->user_id])->update(['is_default' => 2, 'update_at' => date('Y-m-d H:i:s')]);

        return return_json([],'设置成功',200);

    }


}
