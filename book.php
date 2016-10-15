<?php
class Book {
    static $table = 'book';
    static $table_short = 'b';
    public function __construct() {
        $this->db = Mysql::getInstance();
    }
    function get($filter=NULL,$id=NULL,$request_data=NULL) {
        $error['error']=TRUE;
                    $error['type']="user";
                    $error['error_message']='Please use POST to send params';
                    return $error;
	}
	function post($request_data=NULL) {
        $params=json_decode(json_encode($request_data), true);
        //用户登录部分
        if ($params['u_usertype']=='auto'){

            /**
                新用户注册
                此部分可参考替换为您的作者数据库内容,保证用户通过您的网站注册完成并能发布作品
                此处仅为数据库验证，并未处理session部分，您可以视情况自行处理
            **/
                $user=$this->db->rs('*','user',array("email" => $params['u_email']));
                if (count($user["data"])){
                    $error['error']=TRUE;
                    $error['type']="user";
                    $error['error_message']='邮箱已经注册, 请选择非自动注册方式';
                    return $error;
                }
                $newuser['email']=$params['u_email'];
                $newuser['name']=$params['u_username'];
                $basepass=$this->generate_password();
                $newuser["password"]=md5($basepass);
                $newuser['nickname']=$params['u_nickname'];
                $newuser['picture']=$params['u_picture'];
                $newuser['description']=$params['u_description'];
                $newuser['sex']=$params['u_sex'];
                $newuser['province']=$params['u_province'];
                $newuser['city']=$params['u_city'];
                $newuser['age']=$params['u_age'];
                //default
                $newuser['created']=$params['created'];
                $newuser['updated']=$params['updated'];
                $return=$this->db->insert('user', $newuser);
                if ($return['result']){
                    $uid=$return['id'];
                    $return['usermessage']="用户自动注册成功";
                    $return['username']=$newuser['name'];
                    $return['password']=$basepass;
                }
                else {
                    return $return;
                }
        }
        else {
            /**
                验证用户登录
                此部分可参考替换为您的作者数据库内容,保证用户通过您的网站注册完成并能发布作品
                此处仅为数据库验证，并未处理session部分，您可以视情况自行处理
            **/
            $user=$this->db->rs('*','user',array("name" => $params['u_username'],"password" => md5($params['u_password'])));
            if (!count($user["data"])){
                    $error['error']=TRUE;
                    $error['type']="user";
                    $error['error_message']='用户名或密码错误';
                    return $error;
            }
            $uid=$user['data'][0]['id'];
            $return['result']=TRUE;
            $return['usermessage']="用户验证成功";
        }
        /**
                作品发布部分
                此部分可参考替换为您的作品数据库内容
                通过我们提供的参数选择合适的分类进行发布
            **/

        //default
        $newbook['isactive']=0;
        $newbook['filetype']='file';
        $newbook['booktype']="novel";
        $newbook['uid']=$uid;
        //from post
        $newbook['tag']=$params['booktag'];
        $newbook['style']=$params['bookstyle'];
        $newbook['lsize']=$params['lsize'];
        $newbook['author']=$params['author'];
        $newbook['created']=$params['created'];
        $newbook['updated']=$params['updated'];
        $newbook['name']=$params['name'];
        $newbook['bid']=$params['bid'];
        $newbook['updateDes']=$params['updateDes'];
        $newbook['pictureIncludeBookName']=$params['pictureIncludeBookName'];
        $newbook['pictureIncludeBookAuthor']=$params['pictureIncludeBookAuthor'];
        $newbook['summary']=$params['summary'];
        $newbook['ptype']=$params['publishtype'];
        $newbook['showall']=$params['showall'];
        $newbook['outlink']=$params['outlink'];
        //分类与风格可自行对接
        $newbook['tag']=$params['booktag'];
        $newbook['style']=$params['bookstyle'];
        $newbook['filepath']=$params['bookfile'];
        $newbook['coverpicturelink']=$params['bookcover'];
        //update.
        $book=$this->db->rs('*','book',array("bid" => $params['bid']));
        if (count($book["data"])){
            $tempbook=$book['data'][0];
            if ($tempbook['uid']==$uid){
                $ret=$this->db->update('book',$tempbook['id'], $newbook);
                if ($ret['error']){
                    return $ret;
                }
                else {
                    $return['bookmessage']="作品更新成功";
                }
            }
            else {
                $error['error']=TRUE;
                    $error['type']="book";
                    $error['error_message']='您无权更新此作品';
                    return $error;
            }
        }
        else {
            $ret=$this->db->insert('book', $newbook);
            if ($ret['error']){
                return $ret;
            }
            else {
                $return['bookmessage']="作品出版成功";
            }
        }
        return $return;
        
	}
    function generate_password( $length = 6 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ) 
        {
             // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }
 }
?>