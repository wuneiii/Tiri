<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo Widget_Resource::cssFile('system.css')?>" rel="stylesheet" type="text/css" />

    </head>

    <body class="login">
        <div class="title">&nbsp;</div>

        <div class="mod01">
            <h3><?php echo C('site_name')?></h3>
            <div class="cont">
                <form action="<?php echo U('login','auth');?>" method="post">
                

                <p><span>用户名：</span>
                    <input type="text" class="btn_02" name="username" autocomplete="off"/>
                </p>
                <p><span>密码：</span>
                    <input type="password" class="btn_02" name="password" autocomplete="off"/>
                </p>
                
                
                <div style="padding-left:65px;"><input type="submit" class="btn_01"  value="登录"/>
                    <a href="#" class="c213">忘记秘密？</a></div>
            </div>
        </div>

    </body>
</html>
