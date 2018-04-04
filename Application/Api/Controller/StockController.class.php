<?php
namespace Api\Controller;
use Think\Controller;
use Think\Page;

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
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>1,"update_time"=>time(),'from_id'=>$product['client_id'],'from_name'=>$product['client_name'], 'client_id'=>$client_id,'client_name'=>$client['name']]);
                        $data = [
                            'type' => 1,
                            'product_id' => $product['id'],
                            'product_name' => $product['name'],
                            'cate_name' => $product['cate_name'],
                            'from_id'=>$product['client_id'],
                            'from_name'=>$product['client_name'],
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
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>2,"update_time"=>time(),'from_id'=>$product['client_id'],'from_name'=>$product['client_name'],'client_id'=>$client_id,'client_name'=>$client['name']]);
                        $data = [
                            'type' => 2,
                            'product_id' => $product['id'],
                            'product_name' => $product['name'],
                            'cate_name' => $product['cate_name'],
                            'from_id'=>$product['client_id'],
                            'from_name'=>$product['client_name'],
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
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>$client2['type'],"update_time"=>time(),'from_id'=>$client_id,'from_name'=>$client['name'], 'client_id'=>$client_id2,'client_name'=>$client2['name']]);
                        $data = [
                            'type' => 3,
                            'product_id' => $product['id'],
                            'product_name' => $product['name'],
                            'cate_name' => $product['cate_name'],
                            'from_id'=>$client_id,
                            'from_name'=>$client['name'],
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
     * 搜索箱子的出入库时间
     */
    public function record(){
        $client_id = I('client_id',0,'intval');
        $type = I('type',0,'intval');
        $name = I('name','','trim');
        $from = I('from','','trim');
        $to = I('to','','trim');
        $limit = I('limit',10,'intval');

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
                $where['create_time'] = ['between',[strtotime($from), strtotime($to)]];
            }elseif($from){
                $where['create_time'] = ['gt', strtotime($from)];
            }elseif($to){
                $where['create_time'] = ['elt', strtotime($to)];
            }

            $total = M('product_record')->where($where)->count();
            $Page = new Page($total, $limit);// 实例化分页类 传入总记录数和每页显示的记录数(25)

            $show = $Page->show();// 分页显示输出
            // `id`, `type`, `product_id`, `product_name`, `product_remark`, `cate_name`, `from_id`, `from_name`, `client_id`, `client_name`, `car_no`, `batch_no`, `remark`, `date`, `create_time`
            $list = M('product_record')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->field('id,type,product_id,product_name as name,
             product_remark as remark,create_time as time, from_id as from_client_id, from_name as from_client_name, client_id as to_client_id, client_name as to_client_name')->order('create_time DESC')->select();

            $json['data'] = [
                'page'=>$Page->nowPage,
                'total'=>$total,
                'total_page'=>$Page->totalPages,
                'limit'=>$limit,
                'list'=>$list

            ];
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 库存统计
     */
    public function statistics(){
        $type = I('type',0,'intval');
        $limit = I('limit',10,'intval');

        $json = $this->simpleJson();
        $where = [];
        do{
            $where['status'] = 1;
            if($type){
                $where['type'] = $type;
            }

            $total = M('client')->where($where)->count();
            $Page = new Page($total, $limit);// 实例化分页类 传入总记录数和每页显示的记录数(25)

            $show = $Page->show();// 分页显示输出
            /**
             *         "client_id":0,
            "client_name":"仓库1",
            "H":20,
            "L":10,
            "total":30
             */
            $list = M('client')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->field('id as client_id, type as client_type, name as client_name')->select();
            foreach($list as $i=>$item){
                $list[$i]['total'] = M('product')->where(['client_id'=>$item['client_id']])->count();
                $list[$i]['H'] = M('product')->where(['client_id'=>$item['client_id'],'cate_name'=>'P00367-000'])->count();
                $list[$i]['L'] = M('product')->where(['client_id'=>$item['client_id'],'cate_name'=>'P00367-002'])->count();
            }
            $json['data'] = [
                'page'=>$Page->nowPage,
                'total'=>$total,
                'total_page'=>$Page->totalPages,
                'limit'=>$limit,
                'list'=>$list

            ];
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 库房详情
     */
    public function product(){
        $client_id = I('client_id',0,'intval');
        $limit = I('limit',10,'intval');

        $json = $this->simpleJson();
        $where = [];
        do{
            $where['status'] = 1;
            if($client_id){
                $where['client_id'] = $client_id;
            }

            $total = M('product')->where($where)->count();
            $Page = new Page($total, $limit);// 实例化分页类 传入总记录数和每页显示的记录数(25)

            $show = $Page->show();// 分页显示输出
            $list = M('product')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->field('id, cate_name, name, remark,client_id,client_name')->order('id DESC')->select();

            $json['data'] = [
                'page'=>$Page->nowPage,
                'total'=>$total,
                'total_page'=>$Page->totalPages,
                'limit'=>$limit,
                'list'=>$list

            ];
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     *
     */
    public function alert(){
        $type = I('type',0,'intval');
        $date = I('date',0,'intval');

        $json = $this->simpleJson();
        $where = [];
        do{
            if(empty($date)){
                $json['status'] = 201;
                $json['msg'] = '参数设置错误';
                break;
            }
            $where['status'] = 1;
            if($type){
                $where['type'] = $type;
            }

            /**
             *         "client_id":"1",#仓库编号
            "client_name":"仓库名",#仓库名称
            "H":"12",#H数量
            "L":"8",#L数量
            "total":"20"#总数
             *
             */
            $list= M('client')->where($where)->field('id as client_id, name as client_name')->select();
            foreach($list as $i=>$item){
                $list[$i]['total'] = M('product')->where(['update_time'=>['lt', time()- $date*86400]])->count();
                $list[$i]['H'] = M('product')->where(['cate_name'=>'P00367-000', 'update_time'=>['lt', time()- $date*86400]])->count();
                $list[$i]['L'] = M('product')->where(['cate_name'=>'P00367-002', 'update_time'=>['lt', time()- $date*86400]])->count();
            }

            $json['data'] = $list;
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 按库房报警
     */
    public function alert_client(){
        $client_id = I('client_id',0,'intval');
        $date = I('date',0,'intval');
        $limit = I('limit',10,'intval');

        $json = $this->simpleJson();
        $where = [];
        do{
            if(empty($date) || empty($client_id)){
                $json['status'] = 201;
                $json['msg'] = '参数设置错误';
                break;
            }
            $where['status'] = 1;
            if($client_id){
                $where['client_id'] = $client_id;
            }

            $total = M('product')->where($where)->count();
            $Page = new Page($total, $limit);// 实例化分页类 传入总记录数和每页显示的记录数(25)

            $show = $Page->show();// 分页显示输出
            /**
             *             "id": "2", #箱子编号
            "cate_name": "P00367-000", #箱子属性
            "name": "5265", # 箱子名称
            "remark": "",#箱子标签
            "is_where": "1",#箱子目前所在位置：1库房，2客户
            "client_id": "0", #箱子所在库房编号
            "client_name": ""#箱子所在库房名称
            "update_time":"1563252021"#最近移动时间
             *
             */
            $list = M('product')->where($where)->field('id,cate_name,name,remark,is_where,client_id,client_name,update_time')->order('update_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();

            $json['data'] = [
                'page'=>$Page->nowPage,
                'total'=>$total,
                'total_page'=>$Page->totalPages,
                'limit'=>$limit,
                'list'=>$list

            ];
        }while(false);
        $this->ajaxReturn($json);
    }

    /**
     * 统计
     */
    public function total(){
        $json = $this->simpleJson();
        do{
            $data = [
                'total_in'=>100,
                'total_in_h'=>45,
                'total_in_l'=>55,

                'total_out'=>168,
                'total_out_h'=> 120,
                'total_out_l'=>48,

                'today_in'=>60,
                'today_out'=>30,
                'today_move'=>28
            ];
            $json['data'] = $data;
        }while(false);
        $this->ajaxReturn($json);
    }
}