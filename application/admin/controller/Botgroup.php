<?php
/**
 * Created by PhpStorm.
 * Script Name: Botfriend.php
 * Create: 2021/12/21 12:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\BotMember;
use ky\Bot\Wx;

class Botgroup extends Botbase
{
    /**
     * @var BotMember
     */
    protected $model;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new BotMember();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['type' => \app\constants\Bot::GROUP, 'uin' => $this->bot['uin']];
            !empty($post_data['search_key']) && $where['nickname|remark_name'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if (! $total) {
                $total = $this->pullGroups();
            }
            $list = $this->model->getList(
                [$post_data['page'], $post_data['limit']], $where,
                [], true, true
            );
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '群名称、备注名称']
        ])
            ->setTip("注意：当前的备注名称就是指实际微信通讯录当中您对该群的备注")
            ->addTopButton('self', ['title'=>'拉取最新群组', 'href' => url('syncGroups'), 'data-ajax' => 1])
            ->addTableColumn(['title' => '序号', 'field' => 'id', 'type' => 'index', 'minWidth' => 70])
            ->addTableColumn(['title' => '群名称', 'field' => 'nickname'])
            ->addTableColumn(['title' => '备注名称', 'field' => 'remark_name'])
            /*->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '设置备注名'])*/;

        return $builder->show();
    }

    /**
     * 同步好友数据
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function syncGroups(){
        $total = $this->pullGroups();
        $this->success('此次同步到' . $total . '个群');
    }

    /**
     * 拉取最新好友列表
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullGroups(){
        $bot = new Wx(['appKey' => $this->bot['app_key']]);
        $res = $bot->getGroups(['uuid' => $this->bot['uuid']]);

        if($res['code'] && !empty($res['data']['count'])){
            $list = $res['data']['groups'];
            $nickname_arr = [];
            foreach ($list as $k => $v){
                $nickname = filter_emoji($v['nick_name']);
                $remark_name = filter_emoji($v['remark_name']);
                $nickname_arr[] = $nickname;
                if($data = $this->model->getOneByMap(['uin' => $this->bot['uin'], 'nickname' => $nickname, 'remark_name' => $remark_name])){
                    $this->model->updateOne([
                        'id' => $data['id'],
                        'nickname' => $nickname,
                        'remark_name' => $remark_name,
                        'username' => $v['user_name'],
                        'alias' => $v['alias']
                    ]);
                }else{
                    $this->model->addOne([
                        'uin' => $this->bot['uin'],
                        'nickname' => $nickname,
                        'remark_name' => $remark_name,
                        'username' => $v['user_name'],
                        'alias' => $v['alias'],
                        'type' => \app\constants\Bot::GROUP
                    ]);
                }
            }
            //删除无效好友
            $nickname_arr && $this->model->delByMap(['uin' => $this->bot['uin'], 'type' => \app\constants\Bot::GROUP, 'nickname' => ['notin', $nickname_arr]]);
            return count($list);
        }
        return 0;
    }
}