<?php

/**
 * Created by PhpStorm.
 * Script Name: Setting.php
 * Create: 2020/5/24 上午10:25
 * Description: 站点配置
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\common\model\tpzs\Config;
use app\constants\Bot;

class Tpzsconfig extends Botbase
{
    protected $model;
    /**
     * @var array
     */
    private $tabList;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new Config();
        $this->tabList = [
            'time' => [
                'title' => '发单时间',
                'href' => url('index', ['name' => 'time'])
            ],
            /*'union' => [
                'title' => '联盟账号',
                'href' => url('index', ['name' => 'union'])
            ],*/
        ];
    }

    /**
     * 设置调度群
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function groups(){
        $settings = $this->model->getField(['key', 'value'], ['admin_id' => $this->adminInfo['id']], true);
        if(request()->isPost()){
            $post_data = input('post.');
            unset($post_data['__token__']);
            foreach ($post_data as $k => $v){
                if($res = $this->model->getOneByMap(['admin_id' => $this->adminInfo['id'], 'key' => $k], true, true)){
                    $this->model->updateOne([
                        'id' => $res['id'],
                        'value' => $v,
                        'bot_id' => $this->bot['id']
                    ]);
                }else{
                    $this->model->addOne([
                        'key' => $k,
                        'value' => $v,
                        'admin_id' => $this->adminInfo['id'],
                        'bot_id' => $this->bot['id']
                    ]);
                }
            }
            $this->success('保存成功');
        }

        if(!empty($settings['officer'])){
            $settings['officer'] = explode(',', $settings['officer']);
        }else{
            $settings['officer'] = [];
        }
        $groups = model('botMember')->getField('id,nickname',['uin' => $this->bot['uin'], 'type' => Bot::GROUP], true);
        $members = model('botGroupmember')->getField('wxid,nickname',['group_id' => ['in', array_keys($groups)]], true);
        $builder = new FormBuilder();
        $builder->setTip("调度群：所有发单机器人集合群，选品人员把商品素材发到此群，那么群里的所有机器人会各自采集然后转发各自负责的群。")
            ->addFormItem('central_group', 'chosen', '选择调度群', '选择调度群', $groups, 'required')
            ->addFormItem('officer', 'chosen_multi', '指挥官', '该微信发表的消息才会被转发', $members, 'required')
            ->setFormData($settings);
        return $builder->show();
    }

    /**
     * 设置
     * @return mixed
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        $current_name = input('name', 'time');
        $settings = $this->model->getField(['key', 'value'], ['admin_id' => $this->adminInfo['id']], true);
        if(request()->isPost()){
            $post_data = input('post.');
            unset($post_data['__token__']);
            foreach ($post_data as $k => $v){
                if($res = $this->model->getOneByMap(['admin_id' => $this->adminInfo['id'], 'key' => $k])){
                    $this->model->updateOne([
                        'id' => $res['id'],
                        'value' => $v
                    ]);
                }else{
                    $this->model->addOne([
                        'key' => $k,
                        'value' => $v,
                        'admin_id' => $this->adminInfo['id']
                    ]);
                }
            }
            $this->success('保存成功');
        }

        $builder = new FormBuilder();
        switch ($current_name){
            default:
                if(empty($settings['step_tasktime'])){
                    $settings['step_tasktime'] = 600;
                }
                $builder->addFormItem('step_tasktime', 'number', '待发间隔时间(s)', '单位秒', [], 'required min=1')
                    ->addFormItem('time_on', 'time', '早上开始时间', '最早的时间', [], 'required')
                    ->addFormItem('time_off', 'time', '晚上结束时间', '晚上最迟', [], 'required');
                break;
        }
        $builder->setTabNav($this->tabList, $current_name)
            ->setFormData($settings);
        return $builder->show();
    }
}