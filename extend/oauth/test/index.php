<?php
error_reporting(0);
session_start();
@header('Content-Type: text/html; charset=UTF-8');

?><!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
  <meta name="renderer" content="webkit"/>
  <title>彩虹聚合登录SDK</title>
  <link href="//lib.baomitu.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container">
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<?php if(isset($_SESSION['user'])){?>
<div class="panel panel-success">
	<div class="panel-heading" style="text-align: center;"><h3 class="panel-title">
		登录成功
	</h3></div>
	<div class="panel-body">
		<div class="list-group">
			<div class="form-group">
				<label>social_uid：</label>
				<input type="text" value="<?php echo $_SESSION['user']['social_uid']?>" class="form-control" readonly="readonly"/>
			</div>
			<div class="form-group">
				<label>access_token：</label>
				<input type="text" value="<?php echo $_SESSION['user']['access_token']?>" class="form-control" readonly="readonly"/>
			</div>
			<div class="form-group">
				<label>faceimg：</label>
				<input type="text" value="<?php echo $_SESSION['user']['faceimg']?>" class="form-control" readonly="readonly"/>
			</div>
			<div class="form-group">
				<label>nickname：</label>
				<input type="text" value="<?php echo $_SESSION['user']['nickname']?>" class="form-control" readonly="readonly"/>
			</div>
			<div class="form-group">
				<label>gender：</label>
				<input type="text" value="<?php echo $_SESSION['user']['gender']?>" class="form-control" readonly="readonly"/>
			</div>
			<div class="form-group">
				<label>location：</label>
				<input type="text" value="<?php echo $_SESSION['user']['location']?>" class="form-control" readonly="readonly"/>
			</div>
		</div>
	</div>
</div>
<?php }?>
<div class="panel panel-info">
	<div class="panel-heading" style="text-align: center;"><h3 class="panel-title">
		彩虹聚合登录SDK
	</h3></div>
	<div class="panel-body" style="text-align: center;">
		<form action="./connect.php" method="get" role="form">
		<div class="list-group">
			<div class="form-group">
			<div class="input-group"><div class="input-group-addon">登录方式</div>
			  <select name="type" class="form-control">
			    <option value="qq">QQ快捷登录</option>
                <option value="wx">微信快捷登录</option>
                <option value="alipay">支付宝快捷登录</option>
                <option value="sina">新浪微博快捷登录</option>
                <option value="baidu">百度快捷登录</option>
              </select>
			</div></div>
		</div>
		<button type="submit" class="btn btn-default btn-block">提交</button>
		</form>
	</div>
</div>
</div>
</div>
</body>
</html>