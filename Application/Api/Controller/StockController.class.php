<?php
namespace Api\Controller;
use Think\Controller;
class StockController extends BaseController {
    /**
     * 入库操作
     */
    public function import(){
        $json = $this->simpleJson();
        do{
            $car_no = I('car_no','','trim,htmlspecialchars');
            $client_id = I('client_id','','trim,htmlspecialchars');
            $product_no = I('product_no','','trim,htmlspecialchars');
            $remark = I('remark','','trim,htmlspecialchars');
            if(empty($client_id) || empty($product_no)){
                $json['status'] = 201;
                $json['msg'] = '请正确设置参数';
                break;
            }
            $client = M('client')->where(['id'=>$client_id])->find();
            if(empty($client)){
                $json['status'] = 201;
                $json['msg'] = '请正确选择库房';
                break;
            }
            $product_no = trim($product_no,',');
            $product_list = explode(",", $product_no);
            $err = [];
            $succ = [];
            if($product_list){
                $batch_id = M('batch')->add(['create_time'=>time(),'client_id'=>$client_id,'car_no'=>$car_no,'remark'=>$remark, 'type'=>1]);
                foreach($product_list as $item) {
                    $item = trim($item);
                    if (empty($item)) continue;
                    $product = M('product')->where(['name' => $item])->find();
                    if (empty($product)) {
                        $err[] = $product;
                    } else {
                        $succ[] = $product;
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>1,"update_time"=>time(),'client_id'=>$client_id,'client_name'=>$client['name']]);
                        $data = [
                            'type' => 1,
                            'product_id' => $product['id'],
                            'product_name' => $product['name'],
                            'cate_name' => $product['cate_name'],
                            'client_id' => $client_id,
                            'client_name' => $client['name'],
                            'car_no' => $car_no,
                            'batch_no' => $batch_id,
                            'remark' => $remark,
                            'date'=>date("Y-m-d"),
                            'create_time' => time()
                        ];
                        M('product_record')->add($data);
                    }
                }
            }
            $json['data']['success'] = $succ;
            $json['data']['error'] = $err;
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 出库操作
     */
    public function out(){
        $json = $this->simpleJson();
        do{
            $car_no = I('car_no','','trim,htmlspecialchars');
            $client_id = I('client_id','','trim,htmlspecialchars');
            $product_no = I('product_no','','trim,htmlspecialchars');
            $remark = I('remark','','trim,htmlspecialchars');
            if(empty($client_id) || empty($product_no)){
                $json['status'] = 201;
                $json['msg'] = '请正确设置参数';
                break;
            }

            $client = M('client')->where(['id'=>$client_id])->find();
            if(empty($client)){
                $json['status'] = 201;
                $json['msg'] = '请正确选择库房';
                break;
            }
            $product_no = trim($product_no,',');
            $product_list = explode(",", $product_no);
            $err = [];
            $succ = [];
            if($product_list){
                $batch_id = M('batch')->add(['create_time'=>time(),'client_id'=>$client_id,'car_no'=>$car_no,'remark'=>$remark, 'type'=>1]);
                foreach($product_list as $item) {
                    $item = trim($item);
                    if (empty($item)) continue;
                    $product = M('product')->where(['name' => $item])->find();
                    if (empty($product)) {
                        $err[] = $product;
                    } else {
                        $succ[] = $product;
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>2,"update_time"=>time(),'client_id'=>$client_id,'client_name'=>$client['name']]);
                        $data = [
                            'type' => 2,
                            'product_id' => $product['id'],
                            'product_name' => $product['name'],
                            'cate_name' => $product['cate_name'],
                            'client_id' => $client_id,
                            'client_name' => $client['name'],
                            'car_no' => $car_no,
                            'batch_no' => $batch_id,
                            'remark' => $remark,
                            'date'=>date("Y-m-d"),
                            'create_time' => time()
                        ];
                        M('product_record')->add($data);
                    }
                }
            }
            $json['data']['success'] = $succ;
            $json['data']['error'] = $err;
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 移动库房
     */
    public function move(){
        $json = $this->simpleJson();
        do{
            $car_no = I('car_no','','trim,htmlspecialchars');
            $client_id = I('client_id','','trim,htmlspecialchars');
            $client_id2 = I('client_id2','','trim,htmlspecialchars');
            $product_no = I('product_no','','trim,htmlspecialchars');
            $remark = I('remark','','trim,htmlspecialchars');
            if( empty($client_id) || empty($product_no)){
                $json['status'] = 201;
                $json['msg'] = '请正确设置参数';
                break;
            }

            $client = M('client')->where(['id'=>$client_id])->find();
            if(empty($client)){
                $json['status'] = 201;
                $json['msg'] = '请正确选择来自库房';
                break;
            }

            $client2 = M('client')->where(['id'=>$client_id2])->find();
            if(empty($client2)){
                $json['status'] = 201;
                $json['msg'] = '请正确选择移动库房';
                break;
            }
            $product_no = trim($product_no,',');
            $product_list = explode(",", $product_no);
            $err = [];
            $succ = [];
            if($product_list){
                $batch_id = M('batch')->add(['create_time'=>time(),'client_id'=>$client_id2,'car_no'=>$car_no,'remark'=>$remark, 'type'=>3]);
                foreach($product_list as $item) {
                    $item = trim($item);
                    if (empty($item)) continue;
                    $product = M('product')->where(['name' => $item])->find();
                    if (empty($product)) {
                        $err[] = $product;
                    } else {
                        $succ[] = $product;
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>$client2['type'],"update_time"=>time(),'client_id'=>$client_id2,'client_name'=>$client2['name']]);
                        $data = [
                            'type' => 3,
                            'product_id' => $product['id'],
                            'product_name' => $product['name'],
                            'cate_name' => $product['cate_name'],
                            'client_id' => $client_id2,
                            'client_name' => $client2['name'],
                            'car_no' => $car_no,
                            'batch_no' => $batch_id,
                            'remark' => $remark,
                            'date'=>date("Y-m-d"),
                            'create_time' => time()
                        ];
                        M('product_record')->add($data);
                    }
                }
            }
            $json['data']['success'] = $succ;
            $json['data']['error'] = $err;
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 查询
     */
    public function search(){
        $ids = I('product_no','','trim');
        $ids = trim($ids,',');
        $ids = explode(',', $ids);

        $json = $this->simpleJson();
        $json['post'] = $_POST;
        $json['get'] = $_GET;
        $json['request'] = $_REQUEST;
        do{
            $ids2 = [];
            foreach($ids as $id){
                $id = intval($id);
                if($id){
                    $ids2[] = $id;
                }
            }
            if($ids2){
                $list = M('product')->where(['name'=>['in', $ids]])->field('id,cate_name,name,remark,is_where,client_id,client_name')->select();
                $json['sql'] = M()->getLastSql();
            }else{
                $list = [];
            }
            $json['data'] = $list;

        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 获取所有库存
     */
    public function client_list(){
        $json = $this->simpleJson();
        $type = I('type',0,'intval');
        do{
            $where = [
                'status'=>1
            ];
            if($type){
                $where['type'] = $type;
            }
            $list = M('client')->where($where)->field('id,name,type,remark')->select();
            $json['data'] = $list;
        }while(false);

        $this->ajaxReturn($json);
    }

    /**
     *
     */
    public function record(){
        $client_id = I('client_id',0,'intval');
        $type = I('type',0,'intval');
        $name = I('name','','trim');
        $from = I('from','','trim');
        $to = I('to','','trim');

        $json = $this->simpleJson();
        $where = [];
        do{
            if($type){
                $where['type'] = $type;
            }

            if($client_id){
                $where['client_id'] = $client_id;
            }

            if($name){
                $where['_string'] = "product_name='{$name}' OR product_remark='{$name}'";
            }

            if($from && $to){
                $where['create_time'] = ['be'];
            }


        }while(false);
        $this->ajaxReturn($json);
    }
}