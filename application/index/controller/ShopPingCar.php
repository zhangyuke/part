<?php



namespace app\index\controller;

use app\common\model\GoodsModel;
use app\common\model\ShopPingCarModel;
use think\Request;

/**
 * 购物车
 * Class Index
 * @package app\index\controller
 */
class ShopPingCar extends Base
{


    //加入购物车
    public function shopping_car(Request $request){

        $id   = $request->param('goods_id',0);
        $num  = $request->param('num',1);
        $g = GoodsModel::where(['id' => $id,'status'=>1])->find();
        if (!$g) {
            return return_json('','很抱歉，您查看的宝贝不存在或已经下架',400);
        }
        $data['mid'] = $this->user_id;
        $data['goods_id'] = $id;
        $data['num'] = $num;
        $res = ShopPingCarModel::where(['mid' => $this->user_id, 'goods_id' => $id])->find();
        if ($res) {
            $d['num'] = $res['num'] + $num;
            $d['update_at'] = date('Y-m-d H:i:s');
            $r = ShopPingCarModel::where(['id' => $res['id']])->update($d);
        } else {
            $data['create_at'] = date('Y-m-d H:i:s');
            $data['update_at'] = date('Y-m-d H:i:s');
            $r = ShopPingCarModel::insert($data);
        }

        if($r){
            return return_json('','添加购物车成功',200);
        }else{
            return return_json('','添加购物车失败',400);
        }
    }

    #购物车列表
    public function index(){
        $list = ShopPingCarModel::where(['mid'=>$this->user_id])->order('id desc')->select();

        $count_price=0;
        foreach ($list as $k=>$v){
            $row = GoodsModel::field(['logo','title','price'])->where(['id'=>$v['goods_id']])->find();
            if($row){
                $v->price = $row->price;
                $v->logo = $row->logo;
                $v->title = $row->title;
                $count_price+=$row->price;
            }
        }
        $data['data']=$list;
        $data['count_price']=$count_price;
        #重新排列数组
        return return_json($data,'成功',200);
    }






    //删除购物车
    public function del_car(){
        $id = input('id');
        if(!$id){
            return json(['status'=>302,'message'=>'抱歉，要删除的商品不存在']);
        }
        $id=explode(',',$id);
        $r =ShopPingCarModel::whereIn('id',$id)->delete();
        if($r){
            return return_json('','删除成功',200);
        }else{
            return return_json('','删除失败',400);
        }
    }



    //增加数量
    public function upNum(Request $request){
        $id = $request->param('id',0,'intval');
        $num = $request->param('num',0,'intval');

        if(!$id){
            return json(['status'=>302,'message'=>'抱歉，要删除的商品不存在']);
        }
        if($num){
            $data = $this->car->where(['id'=>$id])->find();

            if(!$data){
                return json(['status'=>302,'message'=>'抱歉，要删除的商品不存在']);
            }
            if($data['vid']){
                $d['num'] = $data['num']+1;
                $d['updated_at'] = time();
                $val = $this->val->where(['id'=>$data['vid']])->find();
                if($val['stock']<$d['num']){
                    return json(['status'=>306,'message'=>'库存不足']);
                }
                $r = $this->car->where(['id'=>$id])->update($d);

            }else{
                $d['num'] = $data['num']+1;
                $d['updated_at'] = time();
                $val = $this->goods->where(['id'=>$data['goods_id']])->find();
                if($val['stock']<$data['num']){
                    return json(['status'=>306,'message'=>'库存不足']);
                }
                $r = $this->car->where(['id'=>$id])->update($d);
            }
            if($r){
                return json(['status'=>200,'message'=>'添加成功']);
            }else{
                return json(['status'=>401,'message'=>'添加失败']);
            }

        }
    }

    //减少数量
    public function downNum(Request $request){
        $id = $request->param('id',0,'intval');
        $num = $request->param('num',0,'intval');
        if(!$id){
            return json(['status'=>302,'message'=>'抱歉，要删除的商品不存在']);
        }
        if($num<0){
            return json(['status'=>305,'message'=>'数量不正确']);
        }
        if($num){
            $data = $this->car->where(['id'=>$id])->find();
            if(!$data){
                return json(['status'=>302,'message'=>'抱歉，要删除的商品不存在']);
            }
            $d['num'] = $data['num']-1;
            $d['updated_at'] = time();
            if($d['num']<0){
                return json(['status'=>305,'message'=>'数量不正确']);
            }
            $r = $this->car->where(['id'=>$id])->update($d);
            if($r){
                return json(['status'=>200,'message'=>'取消成功']);
            }else{
                return json(['status'=>401,'message'=>'取消失败']);
            }
        }
    }
}
