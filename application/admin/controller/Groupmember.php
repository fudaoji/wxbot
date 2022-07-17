<?php
/**
 * Created by PhpStorm.
 * Script Name: Groupmember.php
 * Create: 2021/12/21 12:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;


use app\admin\model\BotMember;
use app\admin\model\BotGroupmember as GM;

class Groupmember extends Botbase
{
    /**
     * @var GM
     */
    protected $model;
    /**
     * @var BotMember
     */
    private $groupM;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new GM();
        $this->groupM = new BotMember();
    }

    /**
     * 列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index()
    {
        $group_id = input('group_id', 0);
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['bot_id' => $this->bot['id'], 'group_id' => $group_id];
            !empty($post_data['search_key']) && $where['nickname|group_nickname'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if (! $total) {
                $group = $this->groupM->getOne($group_id);
                $total = $this->model->pullMembers($this->bot, $group);
            }
            $list = $this->model->getList(
                [$post_data['page'], $post_data['limit']], $where,
                [], true, true
            );
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '昵称、备注名称']
        ])
            ->setDataUrl(url('index', ['group_id' => $group_id]))
            //->setTip("注意：当前的备注名称就是指实际微信通讯录当中您对该好友的备注")
            ->addTopButton('self', ['title'=>'同步群成员', 'href' => url('syncMembers', ['group_id' => $group_id]), 'data-ajax' => 1])
            ->addTableColumn(['title' => 'wxid', 'field' => 'wxid', 'minWidth' => 170])
            ->addTableColumn(['title' => '微信昵称', 'field' => 'nickname'])
            ->addTableColumn(['title' => '群内昵称', 'field' => 'group_nickname'])
            ->addTableColumn(['title' => '微信号', 'field' => 'username'])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '设置备注名'])
            ->addRightButton('self', ['title' => '移出群聊','href' => url('removeMemberPost', ['id' => '__data_id__']),'class' => 'layui-btn layui-btn-xs layui-btn-danger','data-ajax' => 1, 'data-confirm' => 1]);

        return $builder->show();
    }

    public function removeMemberPost(){
        $id = input('post.id');
        if(! $friend = $this->model->getOneJoin([
            'alias' => 'm',
            'join' => [
                ['bot_member g', 'g.id=m.group_id']
            ],
            'where' => ['m.bot_id' => $this->bot['id'], 'm.id' => $id, 'g.type'=>\app\constants\Bot::GROUP],
            'field' => ['g.wxid as group_wxid', 'm.*']
        ])){
            $this->error('数据不存在');
        }
        $res = model('bot')->getRobotClient($this->bot)->removeGroupMember([
            'robot_wxid' => $this->bot['uin'],
            'group_wxid' => $friend['group_wxid'],
            'to_wxid' => $friend['wxid']
        ]);
        if($res['code']){
            $this->model->delOne($id);
            $this->success('操作成功');
        }else{
            $this->error($res['errmsg']);
        }
    }

    /**
     * 同步好友数据
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function syncMembers(){
        $group_id = input('group_id', 0);
        $group = $this->groupM->getOne($group_id);
        $total = $this->model->pullMembers($this->bot, $group);
        $this->success('此次同步到' . $total . '位群成员');
    }
}