<?php
    class Func_Sms{

        private $_api_url = 'http://sms.rhww.cn/ensms/servlet/WebSend';
        private $_api_username = 'cheyunji';
        private $_api_password = '';

        public function Func_Sms(){
            

        }
        /**
        * 发短信
        * 如果成功，返回整数表示短信ID
        * 如果失败，返回false
        * 
        * @param mixed $cell_phone
        * @param string $content
        * @return mixed
        */
        public function send($telephone , $content , $suffix = ''){
            return true;

            if($telephone == '' || $content == ''){
                return false;
            }
            //接口要求内容必须为GBK编码
            $content = mb_convert_encoding($content,'gbk','utf-8');
            $content .= $suffix;

            $this->_api_password = md5($this->_api_password);
            $_api = $this->_api_url.'?userId='.$this->_api_username.'&password='.$this->_api_password.'&mobile='.$cell_phone.'&content='.$content;
            //echo $_api;
            $ret = @file_get_contents($_api);

            $ret = 'rspCode=0&rspDesc=提交成功&msgId=14690';
            $arrRet = array('rspCode'=>'-3','msgId'=>false);
            parse_str($ret , $arrRet);

            if($arrRet['rspCode'] == 0 || $arrRet['rspCode'] == 'DELIVRD'){
                return $arrRet['msgId'];
            }else{
                return false;
            }
        }
    }
?>