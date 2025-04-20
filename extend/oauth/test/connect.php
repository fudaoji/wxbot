<?php
/**
 * 彩虹聚合登录SDK
 * 1.0
**/

error_reporting(0);
session_start();
@header('Content-Type: text/html; charset=UTF-8');

include '../Oauth.config.php';
include '../Oauth.class.php';

$type = isset($_GET['type'])?$_GET['type']:'qq';

if($_GET['code']){
    //step2  根据code获取信息
	if($_GET['state'] != $_SESSION['Oauth_state']){
		exit("The state does not match. You may be a victim of CSRF.");
	}
	$Oauth=new Oauth($Oauth_config);
	$arr = $Oauth->callback();
	if(isset($arr['code']) && $arr['code']==0){
		$openid=$arr['social_uid'];
		$access_token=$arr['access_token'];
		/* 处理用户登录逻辑 */

		$_SESSION['user'] = $arr;
		exit("<script language='javascript'>window.location.href='./';</script>");

	}elseif(isset($arr['code'])){
		exit('登录失败，返回错误原因：'.$arr['msg']);
	}else{
		exit('获取登录数据失败');
	}
}else{
    //step1: 获取跳转登录地址
	$Oauth=new Oauth($Oauth_config);
	$arr = $Oauth->login($type);
	if(isset($arr['code']) && $arr['code']==0){
		exit("<script language='javascript'>window.location.href='{$arr['url']}';</script>");
	}elseif(isset($arr['code'])){
		exit('登录接口返回：'.$arr['msg']);
	}else{
		exit('获取登录地址失败');
	}
}
