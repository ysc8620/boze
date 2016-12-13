<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class ProductController extends BaseController
{
    /**
     * 微信openid信息
     */
    public function index()
    {
        $city = M('product'); // 实例化User对象
        $count = $city->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $city->order('create_time')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    /**
     * 客户列表
     */
    public function client(){
        $city = M('client'); // 实例化User对象
        $count = $city->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $city->order('create_time')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加属性
     */
    public function client_edit(){
        // 编辑
        if(IS_POST){
            $name = I('post.name','','trim,htmlspecialchars');
            $remark = I('post.remark','','trim,htmlspecialchars');
            $status = I('post.status','1','intval');
            $id = I('post.id',0,'intval');

            $data['name'] = $name;
            $data['status'] = $status;
            $data['remark'] = $remark;
            if($id){
                $list = M('client')->where(['name'=>$name])->select();
                foreach($list as $item){
                    if($item['id'] != $id){
                        return $this->error("客户编号已经存在~");
                    }
                }
                $res = M('client')->where(['id'=>$id])->save($data);
            }else{
                $has = M('client')->where(['name'=>$name])->find();
                if($has){
                    return $this->error("客户编号已经存在~");
                }
                $data['create_time'] = time();
                $res = M('client')->add($data);
            }
            if($res){
                return $this->success("操作成功", tsurl('product/client'));
            }else{
                return $this->success("操作失败");
            }
        }
        $id = I('id',0,'intval');
        if($id){
            $detail = M('client')->where(['id'=>$id])->find();
            $this->assign('detail', $detail);
        }
        $this->display();
    }

    /**
     * 删除产品
     */
    public function client_del(){
        $id = I('id',0,'intval');
        if($id){
            M('client')->where(['id'=>$id])->delete();
        }

        $this->success("删除成功~");
    }

    /**
     * 商品导入
     */
    public function import(){
        header("Content-type: text/html; charset=utf-8");
        setlocale(LC_ALL, 'zh_CN');
        if(IS_POST){
            if($_FILES['name']['type'] != 'text/csv'){
                return $this->error("请正确上传CSV文件~", tsurl("product/import"));
            }
            $file_path = $_FILES['name']['tmp_name'];

            if(file_exists($file_path)){
                $fp = fopen($file_path,"r");
                while($line = fgetcsv($fp,10240,"\t")){
                    $data = iconv('gb2312','utf-8',$line[0]);
                    if(strpos($data,"属性") !== false){
                        continue;
                    }
                    $item = explode(",",$data);
                    foreach($item as $i=>$row){
                        $item[$i] = trim($row);
                    }
                    if(is_numeric($item[1])){
                        $info = M('product')->where(['name'=>$item[1]])->find();
                        if(!$info){
                            M('product')->add(['name'=>$item[1], 'cate_name'=>$item[0], 'create_time'=>time(),'update_time'=>time()]);
                        }
                    }
                }
                fclose($fp);
                return $this->success("导入成功~", tsurl('product/import'));
            }else{
                return $this->error("文件上传失败~");
            }
            return $this->redirect('product/import');
        }

        $this->display();
    }

    /**
     * 添加属性
     */
    public function edit(){
        // 编辑
        if(IS_POST){
            $cate_name = I('post.cate_name','','trim,htmlspecialchars');
            $name = I('post.name','','trim,htmlspecialchars');
            $remark = I('post.remark','','trim,htmlspecialchars');
            $is_where = I('post.is_where','1','intval');
            $status = I('post.status','1','intval');
            $id = I('post.id',0,'intval');

            //
            if(empty($cate_name) || empty($name)){
                return $this->error("铁箱属性和铁箱编号不为空~");
            }
            $data['cate_name'] = $cate_name;
            $data['name'] = $name;
            $data['is_where'] = $is_where;
            $data['status'] = $status;
            $data['remark'] = $remark;
            if($id){
                $list = M('product')->where(['name'=>$name])->select();
                foreach($list as $item){
                    if($item['id'] != $id){
                        return $this->error("铁箱编号已经存在~");
                    }
                }
                $res = M('product')->where(['id'=>$id])->save($data);
            }else{
                $has = M('product')->where(['name'=>$name])->find();
                if($has){
                    return $this->error("铁箱编号已经存在~");
                }
                $data['create_time'] = time();
                $data['update_time'] = time();
                $res = M('product')->add($data);
            }
            if($res){
                return $this->success("操作成功", tsurl('product/index'));
            }else{
                return $this->success("操作失败");
            }
        }

        $id = I('id',0,'intval');
        if($id){
            $detail = M('product')->where(['id'=>$id])->find();
            $this->assign('detail', $detail);
        }
        $this->display();
    }

    /**
     * 删除产品
     */
    public function del(){
        $id = I('id',0,'intval');
        if($id){
            M('product')->where(['id'=>$id])->delete();
        }

        $this->success("删除成功~");
    }

    /**
     * 发箱操作
     */
    public function send(){
        if(IS_POST){
            $car_no = I('post.car_no','','trim,htmlspecialchars');
            $client_id = I('post.client_id','','trim,htmlspecialchars');
            $product_no = I('post.product_no','','trim,htmlspecialchars');
            $remark = I('post.remark','','trim,htmlspecialchars');
            if(empty($car_no) || empty($client_id) || empty($product_no)){
                return $this->error("请正确输入参数~");
            }
            $product_list = explode(',', $product_no);
            $err = "";
            if($product_list){
                $client = M('client')->where(['id'=>$client_id])->find();
                $batch_id = M('batch')->add(['create_time'=>time(),'client_id'=>$client_id,'car_no'=>$car_no,'remark'=>$remark, 'type'=>2]);
                foreach($product_list as $item) {
                    $item = trim($item);
                    if (empty($item)) continue;
                    $product = M('product')->where(['name' => $item])->find();
                    if (empty($product)) {
                        $err .= $product . ",";
                    } else {
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>2,'client_id'=>$client_id,'client_name'=>$client['name'],"update_time"=>time()]);
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
            return $this->success("操作成功~");
        }
        $client_list = M('client')->where(['status'=>1])->select();
        $this->assign('client_list', $client_list);
        $this->display();
    }

    /**
     * 收箱操作
     */
    public function accept(){
        if(IS_POST){
            $car_no = I('post.car_no','','trim,htmlspecialchars');
            $client_id = I('post.client_id','','trim,htmlspecialchars');
            $product_no = I('post.product_no','','trim,htmlspecialchars');
            $remark = I('post.remark','','trim,htmlspecialchars');
            if(empty($car_no) || empty($client_id) || empty($product_no)){
                return $this->error("请正确输入参数~");
            }
            $product_list = explode(',', $product_no);
            $err = "";
            if($product_list){
                $client = M('client')->where(['id'=>$client_id])->find();
                $batch_id = M('batch')->add(['create_time'=>time(),'client_id'=>$client_id,'car_no'=>$car_no,'remark'=>$remark, 'type'=>1]);
                foreach($product_list as $item) {
                    $item = trim($item);
                    if (empty($item)) continue;
                    $product = M('product')->where(['name' => $item])->find();
                    if (empty($product)) {
                        $err .= $product . ",";
                    } else {
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>1,"update_time"=>time()]);
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
            return $this->success("操作成功~");
        }
        $client_list = M('client')->where(['status'=>1])->select();
        $this->assign('client_list', $client_list);
        $this->display();
    }

    /**
     * 库存查询
     */
    public function search(){
        // ALTER TABLE `t_product` ADD `client_id` INT( 11 ) NOT NULL DEFAULT '0' COMMENT '对应客户ID' AFTER `is_where` ;
        // 工厂内
        $product_list_1 = M('product')->where(['is_where'=>1])->group("cate_name")->field("*,count(*) as total")->select();

        // 工厂外
        $product_list_2 = M('product')->where(['is_where'=>2])->group("cate_name,client_id")->field("*,count(*) as total,concat(name,',') as total_name")->select();
        foreach($product_list_2 as $i=>$item){
            $product_list_2[$i]['total_name'] = trim($item['total_name'],',');
        }
        $this->assign('product_list_1', $product_list_1);
        $this->assign('product_list_2', $product_list_2);

        $this->display();
    }

    /**
     * ALTER TABLE `t_product` ADD `update_time` INT( 10 ) NOT NULL DEFAULT '0' AFTER `client_name` ;
     */
    public function export (){
        $type = I('type',1,'intval');
        // 工厂内
        if($type == 1){
            $product_list = M('product')->where(['is_where'=>1])->select();
            $header = ["箱子属性","箱子编号","入库时间"];
            $data = [];
            foreach($product_list as $product){
                $data[] = [$product['cate_name'],$product['name'],date("Y-m-d H:i", $product['update_time'])];
            }
            return $this->exportCsv($header, $data,date("Y-m-d-")."工厂内");
        }

        if($type == 2){
            $product_list = M('product')->where(['is_where'=>2])->order("cate_name, client_id")->select();
            $header = ["箱子属性","箱子编号","客户代码","出库时间"];
            $data = [];
            foreach($product_list as $product){
                $data[] = [$product['cate_name'], $product['name'],$product['client_name'],date("Y-m-d H:i", $product['update_time'])];
            }
            return $this->exportCsv($header, $data,date("Y-m-d-")."工厂外");
        }
    }

    /**
     * 库存明细
     */
    public function search_detail(){
        $cate_name = I('cate_name','','trim');
        $product_list = M('product')->where(['is_where'=>1, "cate_name"=>$cate_name])->field("*")->select();
        //print_r($product_list);
        $this->assign("product_list", $product_list);
        $this->assign("cate_name", $cate_name);
        $this->display();
    }

    /**
     * 报警处理
     */
    public function warning(){
        $date = I('date',0,'intval');
        $name = I('name',0,'intval');
        $export = I('export',0,'intval');
        if($date){
            $time = time() - $date * 86400;
            $date_list = M('product')->where("is_where=2 AND update_time<$time")->select();

            if($export){
                $header = ["箱子属性","箱子编号","客户代码","出库时间"];
                $data = [];
                foreach($date_list as $product){
                    $data[] = [$product['cate_name'], $product['name'],$product['client_name'],date("Y-m-d H:i", $product['update_time'])];
                }
                return $this->exportCsv($header, $data,date("Y-m-d-")."工厂外");
            }
            $this->assign('date_list', $date_list);
        }

        if($name){
            $detail_list = M('product')->where(["name"=>$name])->select();

            $header = ["箱子属性","箱子位置","箱子编号","客户代码","操作时间"];
            $data = [];
            if($export){
                foreach($detail_list as $product){
                    $data[] = [$product['cate_name'],$product['is_where']==1?"在厂内":"在厂外", $product['name'],$product['client_name'],date("Y-m-d H:i", $product['update_time'])];
                }
                return $this->exportCsv($header, $data);
            }
            $this->assign('detail_list', $detail_list);
        }

        $this->assign('name',$name?$name:'');
        $this->assign('date',$date?$date:'');
        $this->display();
    }

}