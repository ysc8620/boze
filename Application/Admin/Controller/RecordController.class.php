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
        $model = M('product_record'); // 实例化User对象
        $count = $model->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->order('create_time')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    /**
     * 库存详情
     */
    public function detail(){
        $model = M('product_record'); // 实例化User对象
        $count = $model->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->order('create_time')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    /**
     * 发箱子明细
     * @param batch=
     * @param
     */
    public function send(){
        $batch_id = I('request.batch',0,'intval');

        $this->assign('batch_id', $batch_id);

        $model = M('product_record'); // 实例化User对象
        $where = [];
        $where['type'] = 2;
        if($batch_id){
            $where['batch_id'] = $batch_id;
        }
        $count = $model->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->order('create_time')->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    /**
     * 收箱子明细
     */
    public function accept(){
        $batch_id = I('request.batch',0,'intval');

        $this->assign('batch_id', $batch_id);

        $model = M('product_record'); // 实例化User对象
        $where = [];
        $where['type'] = 2;
        if($batch_id){
            $where['batch_id'] = $batch_id;
        }
        $count = $model->count();// 查询满足要求的总记录数
        $Page = new Page($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->order('create_time')->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }

}