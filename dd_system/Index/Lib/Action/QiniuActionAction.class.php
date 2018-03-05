<?php
    class QiniuAction extends Action {
    	
    	
    	//定义各模块锁定级别
    	private $lock_index                = '9751';
    	private $lock_add                  = '9751';
    	private $lock_del                  = '9751';
    	
    	
    	
        public $accessKey = '******WqG8S6_d0z81ctXRe9q**********';//请自行去七牛申请
        public $secretKey = '*************Y6Hi7nsdaqsI**********';//请自行去七牛申请
        public $bucket    = 'test12345';    //你的七牛管理后台的某个空间名
        public $domain    = 'http://xxxxxx.clouddn.com/';//你的七牛管理后台的分配给你的域名，位于 空间设置->域名设置->七牛域名
        public $returnUrl = 'http://yourdomain.com/index.php';//上传成功后的回调地址
        public $QiniuAuth;
        
        
        protected function _initialize(){
            parent::_initialize();
            import('@.Common.Qiniu');
            $this->QiniuAuth = new Auth($this->accessKey, $this->secretKey);
        }

        //列表页
        public function index(){
        	
        	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        	//判断用户是否登陆
        	$this->loginjudgeshow($this->lock_index);
        	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        	
        	
            $auth = $this->QiniuAuth;
            $bucketMgr = new BucketManager($auth);
            $bucket = $this->bucket;
            $prefix = '';
            $marker = '';
            $limit  = 100;//显示数量
            list($iterms, $marker, $err) = $bucketMgr->listFiles($bucket, $prefix, $marker, $limit);
            if ($err !== null) {
                dump($err);
                $this->error('发生错误，请联系管理员。');
            } else {
                foreach ($iterms as $key => $val) {
                    $expire = time() + 3600;    //过期时间（秒）
                    $url    = $this->domain . $val['key'] . '?e=' . $expire;//构造URL
                    $sign   = $auth->sign($url);    //进行签名加密
                    $token  ='&token=' . $sign;     //组装签名得到的token
                    $val['url'] = $url . $token;    //生成最终url
                    $iterms[$key] = $val;
                }
                $this->assign('list', $iterms);
            }
            $this->display();
        }
        
        
        //上传（模板文件见附件）
        public function add(){
        	
        	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        	//判断用户是否登陆
        	$this->loginjudgeshow($this->lock_add);
        	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        	
        	
        	
            $auth   = $this->QiniuAuth;
            $bucket = $this->bucket;            // 要上传的空间
            $key    = time() . '.jpg';//自定义的名字，如果不设置，就跟hash相同
            $policy = array(
                'returnUrl' => $this->returnUrl,
                'returnBody'=> '{"key": $(key), "hash": $(etag), "w": $(imageInfo.width), "h": $(imageInfo.height)}'
            );
            $token = $auth->uploadToken($bucket, $key, 3600, $policy); // 生成上传 Token
            $this->assign('token', $token);
            $this->assign('key', $key);
            $this->display();
        }
        
        
        public function del(){
        	
        	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        	//判断用户是否登陆
        	$this->loginjudgeshow($this->lock_del);
        	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        	
            $key = I('get.key');
            if($key !== ''){
                $auth      = $this->QiniuAuth;
                $bucketMgr = new BucketManager($auth);
                if($err = $bucketMgr->delete($this->bucket, $key) == NULL){
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $this->error('删除失败');
                }
            }else{
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
