<?php
namespace Api\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function _initialize(){
        //parse_str(file_get_contents('php://input'), $_POST);
    }

    /**
     * json返回格式
     */
    public function simpleJson(){
        return [
            'status' => 200,
            'msg'  => '',
            'time' => time(),
            'data' => []
        ];
    }

    public function initMongo(){
        if(self::$mongo == null){
            try{
                self::$mongo = new \Mongo("mongodb://".C('MONGO_USER').":".C('MONGO_PWD')."@".C('MONGO_HOST').":".C('MONGO_PORT'));
            }catch (\Exception $e){
                exit('mongodb连接失败');
            }
        }

        return self::$mongo;
    }


    /**
     * 导出csv文件
     * @param $head = array ('订单号','应付结算','是否已回款','是否已提批次','备注');
     * @param $data=array();  输出文件内容
     * @param $filename   文件名称
     */
    public function exportCsv($head,$data,$filename="文件.csv"){
        // 输出Excel文件头
        //header ( 'Content-Type: application/vnd.ms-excel' );
        header("Content-type:text/csv");
        header ( "Content-Disposition: attachment;filename=$filename.csv");
        header ( 'Cache-Control: max-age=0' );
        // 打开PHP文件句柄，php://output 表示直接输出到浏览器
        $fp = fopen ( 'php://output', 'a' );
        //文件的标题头部
        foreach ( $head as $i => $v ) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $head [$i] = iconv ( 'utf-8', 'gbk', $v );
        }
        // 将数据通过fputcsv写到文件句柄
        fputcsv ( $fp, $head );

        //文件的内容
        $cnt = 0;// 计数器
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 1000;
        foreach ( $data as $rows ) {
            $cnt ++;
            if ($limit == $cnt) { // 刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush ();
                flush ();
                $cnt = 0;
            }
            // 读取表数据
            $content = array ();
            foreach($rows as $keyName=>$value){// 列写入
                $content [] = iconv ( 'utf-8', 'gbk', $value);
//     			$a = @iconv("utf-8","gbk",$res);$b = @iconv("gbk","utf-8",$a);
            }
            fputcsv ( $fp, $content );


        }
        fclose($fp);
    }

    public function __destruct()
    {
    }

}