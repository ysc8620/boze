<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class RecordController extends BaseController
{
    /**
     * 微信openid信息
     */
    public function index()
    {
        $type = I('type', 0, 'intval');
        $batch = I('batch',0,'intval');
        $client = I('client','','trim');
        $product = I('product','','trim');
        $car = I('car','','trim');
        $date = I('date','','trim');
        $export = I('export',0,'intval');

        $this->assign('car', $car);
        $this->assign('type', $type);
        $this->assign('batch_id', $batch?$batch:'');
        $this->assign('client', $client?$client:'');
        $this->assign('product', $product);
        $this->assign('date', $date);

        $model = M('product_record'); // 实例化User对象
        $where = [];

        // 记录类型
        if($type){
            $where['type'] = $type;
        }

        // 发货批次
        if($batch){
            $where['batch_no'] = $batch;
        }

        // 客户
        if($client){
            $where['client_name'] = $client;
        }

        if($car){
            $where['car_no'] = $car;
        }

        if($date){
            $where['date'] = $date;
        }

        // 箱子编号
        if($product){
            $where['product_name'] = $product;
        }

        $count = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        if($export){
            $list = $model->where($where)->order('create_time')->order('id DESC')->select();
            // 出入库
            $header = ["收发类型","箱子编号","客户代码","车牌号码","箱子属性","批次编号","记录时间"];
            $data = [];
            foreach($list as $item){
                $data[] = [$item["type"] == 1?"收箱操作":"发箱操作",$item["product_name"],$item["client_name"],$item["car_no"],$item["cate_name"],$item["batch_no"],date("Y-m-d H:i",$item["create_time"])];
            }
            return $this->exportCsv($header, $data,date("Y-m-d-")."库存明细");
        }else{
            $list = $model->where($where)->order('create_time')->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }

        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }


}