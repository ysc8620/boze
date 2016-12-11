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
                    if(is_numeric($item[1])){
                        $info = M('product')->where(['name'=>$item[1]])->find();
                        if(!$info){
                            M('product')->add(['name'=>$item[1], 'cate_name'=>$item[0], 'create_time'=>time()]);
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
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>2]);
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
                        M('product')->where(['id'=>$product['id']])->save(['is_where'=>1]);
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
        $this->display();
    }


}