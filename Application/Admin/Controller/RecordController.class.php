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
        $client = I('client',0,'intval');
        $product = I('product',0,'intval');

        $this->assign('type', $type);
        $this->assign('batch_id', $batch);
        $this->assign('client', $client);
        $this->assign('product', $product);

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
            $where['client_id'] = $client;
        }

        // 箱子编号
        if($product){
            $where['product_name'] = $product;
        }

        $count = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->where($where)->order('create_time')->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }


}