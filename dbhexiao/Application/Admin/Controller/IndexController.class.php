<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
}







/*
 * 3.数据查询接口
 http://127.0.0.1:8003/dbhexiao/admin.php/Admin/Hexiao/index

 传递参数（查询条件参数，都是非必须）

 date_start 订单查询开始时间
 date_end   订单查询结束时间
 shopname   商品名称
 keystr     兑换码
 p        传递页数


 返回参数样例

 {"code":"0","message":"\u8bf7\u8fdb\u884c\u767b\u5f55","data":[]}

 {"code":"1","message":"\u6570\u636e\u83b7\u53d6\u6210\u529f","data":{"totalpage":"3","countlist":"3","nowpage":"1","list":[{"id":"855","userid":"1434","siteid":"1000","typeid":"77","childtypeid":"","mtype":"2","name":"\u5151\u5b9dDQ\u5355\u7403\u7535\u5b50\u5238","productnum":"1","orderno":"D201710231724385527","keystr":"FFTpJz","passwd":"","price":"0","score":"3000","productid":"165","status":"4","order_createtime":"2017-10-23 17:24:38","del_createtime":"0000-00-00 00:00:00","zhifu_createtime":"0000-00-00 00:00:00","pingjia_createtime":"0000-00-00 00:00:00","zhifu_order":"","confirm":"2","youxiaoqi":"0","fh_phone":"","fh_address":"","fh_fahuotime":"2017-11-29 03:32:07","fh_shouhuotime":"0000-00-00 00:00:00","fh_shouhuoren":"","address_id":"0","shiping_name":"\u7533\u901a\u5feb\u9012","shipingorder":"733773823738","remark":""}]}}

 字段说明
 totalpage 总页数
 countlist 总条数
 nowpage  当前页
 list[]   查询的列表数据
 status 订单的状态  当status=3 时，前端展示为‘兑换’，status=4 时 前端展示为 ‘查询’


 4.兑换，查看详情页
 http://127.0.0.1:8003/dbhexiao/admin.php/Admin/Hexiao/duihuandetail

 传递参数
 id  查询id  855

 返回样例
 {"code":"1","message":"\u83b7\u53d6\u6210\u529f","data":{"id":"855","userid":"1434","siteid":"1000","typeid":"77","childtypeid":"","mtype":"2","name":"\u5151\u5b9dDQ\u5355\u7403\u7535\u5b50\u5238","productnum":"1","orderno":"D201710231724385527","keystr":"FFTpJz","passwd":"","price":"0","score":"3000","productid":"165","status":"4","order_createtime":"2017-10-23 17:24:38","del_createtime":"0000-00-00 00:00:00","zhifu_createtime":"0000-00-00 00:00:00","pingjia_createtime":"0000-00-00 00:00:00","zhifu_order":"","confirm":"2","youxiaoqi":"0","fh_phone":"","fh_address":"","fh_fahuotime":"2017-11-29 03:32:07","fh_shouhuotime":"0000-00-00 00:00:00","fh_shouhuoren":"","address_id":"0","shiping_name":"\u7533\u901a\u5feb\u9012","shipingorder":"733773823738","remark":""}}

 * //订单查询页--只查询实物+兑换码的
 public function index(){

 //获取该商户的id
 $sitelist = session('adminUser');
 $siteid = $sitelist['id'];

 if(!$siteid){
 return show('0','请进行登录');
 }
 //用户是否登录判断
 //$ssion = sessionUser();

 //获取查询参数
 $datestart = isset($_POST['date_start'])?$_POST['date_start']:'';
 $dateend   = isset($_POST['date_end'])?$_POST['date_end']:'';
 $shopname  = isset($_POST['shopname'])?$_POST['shopname']:'';//商品名称
 $duihuanma = isset($_POST['keystr'])?$_POST['keystr']:'';//兑换码

 $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
 // 		echo $page;
 //$pageSize = 10;

 if($datestart==''){
 $datestart = date('2017-01-01 00:00:00');
 }
 if($dateend==''){
 $dateend = date('Y-m-01 23:59:59');
 }

 //生成where条件判断字符串
 $sql_where = "siteid='".$siteid."' and mtype='2' and zhifu_order='' and " ;

 if($datestart!=''){
 $sql_where .= "order_createtime>='".$datestart."' and ";
 }
 if($dateend!=''){
 $sql_where .= "order_createtime<='".$dateend."' and ";
 }

 if($shopname!=''){
 $sql_where .= "name like '%".$shopname."%' and ";
 }
 if($duihuanma!=''){
 $sql_where .= "keystr='".$duihuanma."' and ";
 }

 $sql_where = rtrim($sql_where,'and ');

 $pageSize = 1;
 //$offset = ($page - 1) * $pageSize;
 $firstpage = ($page-1)*$pageSize;

 //数据的总页数
 $listcount = M('shop_userbuy')->where($sql_where)->count();

 //总页数
 $countpage = ceil($listcount/$pageSize);

 //按条件查询出来的数据
 $list = M('shop_userbuy')
 ->where($sql_where)
 ->order('id desc')
 ->limit($firstpage,$pageSize)
 ->select();

 // 		$model = new \Think\Model();
 // 		echo $model->getLastSql();exit;


 // 		$res  =  new \Think\Page($listcount,$pageSize);
 // 		$pageres = $res->show();

 // 		echo $pageres;

 $returnarr = array(
 'totalpage' => (string)$countpage,      //总页数
 'countlist' => $listcount,//总条数
 'nowpage'   => $page,//当前页
 'list' => $list

 );

 return show('1','数据获取成功',$returnarr);

 }


 //兑换，查看详情页
 public function duihuandetail(){

 //用户是否登录判断
 $ssion = sessionUser();
 //获取查询id
 $orderid = $_POST['id'];
 if(!$orderid || !isset($orderid)){
 return show('0','id不存在');
 }

 //数据查询
 $list = M('shop_userbuy')->where('id="'.$orderid.'"')->find();

 return show('1','获取成功',$list);

 }


 //确认核销
 public function hexiao(){

 //用户是否登录判断
 $ssion = sessionUser();
 //获取参数
 $id     = $_POST['id'];//查询数据id
 $keystr = $_POST['keystr'];//兑换码
 $remark = $_POST['remark'];//备注
 // 		$id = '855';
 // 		$keystr = 'FFTpJz';
 if(!$id || !isset($id)){
 return show('0','id不存在,非法操作');
 }
 if(!$keystr || !isset($keystr)){
 return show('0','兑换码不能为空');
 }

 //输入兑换码进行判断
 $list = M('shop_userbuy')->where('id="'.$id.'"')->find();

 if($list['status']!='3'){
 return show('0','该商品已核销');
 }

 if($list['keystr']!=$keystr){
 return show('0','核销码错误，兑换失败');
 }

 //状态的更新，兑换时间的更新
 $data = array();
 $data['status'] = '4';
 $data['fh_fahuotime']= date('Y-m-d H:i:s');
 $data['remark'] = $remark;

 $updatelist = M('shop_userbuy')->where('id="'.$id.'"')->save($data);

 if($updatelist){
 return show('1','核销成功');
 }else{
 return show('0','核销失败');
 }
 } */