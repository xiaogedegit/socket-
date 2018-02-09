    <?php
    class Hxcall
    {
        
        private $app_key = '1177180207178456#tongxun';
        private $client_id = 'YXA6044IcAvkEeibuW8abrZ1TQ';
        private $client_secret = 'YXA6S0UGxk0N7FUpY7_hneGyKJj8b88';
        private $url = "https://a1.easemob.com/1177180207178456/tongxun";
        /*
         * 获取APP管理员Token
         */
        function __construct()
        {
            $this->token = $this->get_token();
        }

        /*
         * 获取token
         */
        public function get_token(){
        	session_start();
        	if ( $_SESSION['token_value'] ) {
        		return $_SESSION['token_value'];
        	} else {
        		$url = $this->url . "/token";
	            $data = array(
	                'grant_type' => 'client_credentials',
	                'client_id' => $this->client_id,
	                'client_secret' => $this->client_secret
	            );
            	$rs = json_decode($this->curl($url, $data), true);
     			$_SESSION['token_value'] = $rs['access_token'];
     			return $_SESSION['token_value'];
        	}
        	
        }
        
        /*
         * 注册IM用户(授权注册)
         */
        public function hx_register($username, $password, $nickname)
        {
            $url = $this->url . "/users";
            $data = array(
                'username' => $username,
                'password' => $password,
                'nickname' => $nickname
            );
            $header = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, $data, $header, "POST");
        }
        /*
         * 给IM用户的添加好友
         */
        public function hx_contacts($owner_username, $friend_username)
        {
            $url = $this->url . "/users/${owner_username}/contacts/users/${friend_username}";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "POST");
        }
        /*
         * 解除IM用户的好友关系
         */
        public function hx_contacts_delete($owner_username, $friend_username)
        {
            $url = $this->url . "/users/${owner_username}/contacts/users/${friend_username}";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "DELETE");
        }
        /*
         * 查看好友
         */
        public function hx_contacts_user($owner_username)
        {
            $url = $this->url . "/users/${owner_username}/contacts/users";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "GET");
        }
        
        /* 发送文本消息 */
        public function hx_send($sender, $receiver, $msg)
        {
            $url = $this->url . "/messages";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            $data = array(
                'target_type' => 'users',
                'target' => array(
                    '0' => $receiver
                ),
                'msg' => array(
                    'type' => "txt",
                    'msg' => $msg
                ),
                'from' => $sender,
                'ext' => array(
                    'attr1' => 'v1',
                    'attr2' => "v2"
                )
            );
            return $this->curl($url, $data, $header, "POST");
        }
        /* 查询离线消息数 获取一个IM用户的离线消息数 */
        public function hx_msg_count($owner_username)
        {
            $url = $this->url . "/users/${owner_username}/offline_msg_count";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "GET");
        }
        
        /*
         * 获取IM用户[单个]
         */
        public function hx_user_info($username)
        {
            $url = $this->url . "/users/${username}";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "GET");
        }
        /*
         * 获取IM用户[批量]
         */
        public function hx_user_infos($limit)
        {
            $url = $this->url . "/users?${limit}";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "GET");
        }
        /*
         * 重置IM用户密码
         */
        public function hx_user_update_password($username, $newpassword)
        {
            $url = $this->url . "/users/${username}/password";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            $data['newpassword'] = $newpassword;
            return $this->curl($url, $data, $header, "PUT");
        }
        
        /*
         * 删除IM用户[单个]
         */
        public function hx_user_delete($username)
        {
            $url = $this->url . "/users/${username}";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "DELETE");
        }
        /*
         * 修改用户昵称
         */
        public function hx_user_update_nickname($username, $nickname)
        {
            $url = $this->url . "/users/${username}";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            $data['nickname'] = $nickname;
            return $this->curl($url, $data, $header, "PUT");
        }

        /*
         * 查看用户在线状态
         */
        public function hx_friend_status($username){
        	$url = $this->url . "/users/${username}/status";
            $header = array(
                'Authorization: Bearer ' . $this->token
            );
            return $this->curl($url, "", $header, "GET");
        }
        /*
         *
         * curl
         */
        private function curl($url, $data, $header = false, $method = "POST")
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if ($header) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $ret = curl_exec($ch);
            return $ret;
        }
    }
    $rs = new Hxcall();
    // 注册的用户
    //echo $rs->hx_register('chunyu', 'admin123', '春雨' );
    // 给IM用户的添加好友
     //echo $rs->hx_contacts('xiaoge', 'chunyu');
    /* 发送文本消息 */
     //echo $rs->hx_send('xiaoge','chunyu','小哥发给春雨的消息');
    /* 消息数统计 */
     //echo $rs->hx_msg_count('xiaoge');
    /* 获取IM用户[单个] */
    // echo $rs->hx_user_info('admin888');
    /* 获取IM用户[批量] */
    // echo $rs->hx_user_infos('20');
    /* 删除IM用户[单个] */
    // echo $rs->hx_user_delete('wwwwww');
    /* 修改用户昵称 */
    // echo $rs->hx_user_update_nickname('asaxcfasdd','网络科技');
    /* 重置IM用户密码 */
    // echo $rs->hx_user_update_password('asaxcfasdd','asdad');
    /* 解除IM用户的好友关系 */
    // echo $rs->hx_contacts_delete('admin888', 'qqqqqqqq');
    /* 查看好友 */
    //echo $rs->hx_contacts_user('chunyu');
    /*查看用户在线状态*/
    //echo $rs->hx_friend_status('chunyu');
    