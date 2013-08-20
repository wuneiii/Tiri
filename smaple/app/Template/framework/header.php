<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo C('site_name')?></title>
    <link href="<?php echo Widget_Resource::cssFile('framework.css')?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo Widget_Resource::jsFile('jquery.min.js')?>"  type="text/javascript" /></script>
    <?php Tiri_Template::getInstance() -> js();?>
</head>

<body style="overflow:scroll;overflow-y:hidden;overflow-x:hidden;">
<div id="mainHeader">
    <div id="logo"></div>
    <div id="loginInfo" class="left">
        <ul>
            <li><a href="/"  class="blueBold">网站首页</a></li>
            <li class="blue">┊</li>
            <li><a href="/admin/" class="blueBold">网站后台</a></li>
            <li>&nbsp</li>
            <li>用户名:</li>
            <li><?php echo Widget_User::getInstance()->realname.'('.Widget_User::getInstance()->username.')';?></li>
            <li>级别: </li>
            <li>总编辑</li>
            <li>&nbsp</li>
            <li>&nbsp</li>
            
            <li class="blue"><a href="<?php echo U('login','logout')?>">[注销]</a></li>
        </ul>
    </div>

</div>
