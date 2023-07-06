<?php
/**
 * User BaiXiantao
 * Date 2022/7/4
 * Time 10:34
 */

namespace app\http\controller;

use think\facade\View;

class Chat
{
	public function index()
	{
		#聊天首页
		$from_id = input('from_id',10001);
		$to_id = 1;
		
		View::assign('from_id',$from_id);
		View::assign('to_id',$to_id);
		
		return View::fetch();
	}

	public function test(){
		return View::fetch();
	}
}
