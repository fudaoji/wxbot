<?php

namespace app\home\controller;
use think\Controller;

class Index extends Controller
{
    public function Index(){
        return $this->fetch();
    }
}