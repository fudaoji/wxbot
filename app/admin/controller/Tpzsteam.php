<?php

/**
 * Created by PhpStorm.
 * Script Name: Setting.php
 * Create: 2020/5/24 上午10:25
 * Description: 站点配置
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\common\model\tpzs\Team;
use app\constants\Bot;

class Tpzsteam extends Botbase
{
    protected $model;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new Team();
    }

    /**
     * 设置负责群
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        $data = $this->model->getOneByMap(['bot_id' => $this->bot['id']]);
        if($data){
            $data['groups'] = explode(',', $data['groups']);
        }
        $data['admin_id'] = $this->adminInfo['id'];
        $groups = $this->getGroups();
        $builder = new FormBuilder();
        $builder->setTip("运营经验：建议每个机器人负责的群在100个以内。")
            ->setPostUrl(url('savePost'))
            ->addFormItem('admin_id', 'hidden', 'adminid', 'adminid', [], 'required')
            ->addFormItem('bot_id', 'chosen', '机器人', '机器人', model('admin/bot')->getField('id,title',['alive' => 1,'admin_id' => $this->adminInfo['id']]), 'required')
            ->addFormItem('groups', 'chosen_multi', '负责群', '负责群', $groups, 'required')
            ->setFormData($data);
        return $builder->show();
    }

    public function savePost($jump_to = '', $data = [])
    {
        $post_data = input('post.');
        if($data = $this->model->getOneByMap(['bot_id' => $post_data['bot_id']])){
            $this->model->updateOne(array_merge(['id' => $data['id']], $post_data));
        }else{
            $this->model->addOne($post_data);
        }
        $this->success('操作成功');
    }
}