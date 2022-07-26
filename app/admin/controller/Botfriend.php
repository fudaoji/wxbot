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
use app\constants\Bot;
use app\constants\Common;

class Botfriend extends Botbase
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
        set_time_limit(0);
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['type' => Bot::FRIEND, 'uin' => $this->bot['uin']];
            !empty($post_data['search_key']) && $where['nickname|remark_name|username|wxid'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    [], true, true
                );
            }else{
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $tip = "<ul><li>注意：</li><li>当前的备注名称就是您对该好友的微信备注</li></ul>";
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => 'wxid、微信号、昵称、备注名称']
        ])
            ->setTip($tip)
            ->addTopButton('self', ['title'=>'拉取最新好友', 'href' => url('syncFriends'), 'data-ajax' => 1])
            ->addTableColumn(['title' => 'Wxid', 'field' => 'wxid', 'minWidth' => 90])
            ->addTableColumn(['title' => '头像', 'field' => 'headimgurl', 'type' => 'picture', 'minWidth' => 100])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 90])
            ->addTableColumn(['title' => '微信号', 'field' => 'username', 'minWidth' => 90])
            ->addTableColumn(['title' => '备注名称', 'field' => 'remark_name', 'minWidth' => 70])
            ->addTableColumn(['title' => '性别', 'field' => 'sex', 'minWidth' => 70, 'type' => 'enum', 'options' => Common::sex()])
            ->addTableColumn(['title' => '省份', 'field' => 'province', 'minWidth' => 90])
            ->addTableColumn(['title' => '城市', 'field' => 'city', 'minWidth' => 90])
            ->addTableColumn(['title' => '操作', 'minWidth' => 200, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '设置备注名'])
            ->addRightButton('delete', ['title' => '删除好友', 'href' => url('deleteFriendPost', ['id' => '__data_id__'])]);

        return $builder->show();
    }

    public function deleteFriendPost(){
        $id = input('post.ids');
        if(! $friend = $this->model->getOneByMap(['uin' => $this->bot['uin'], 'id' => $id])){
            $this->error('数据不存在');
        }
        $res = model('admin/bot')->getRobotClient($this->bot)->deleteFriend([
            'robot_wxid' => $this->bot['uin'],
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
     * 设置备注
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function edit()
    {
        $id = input('id', null);
        $data = $this->model->getOne($id);

        if (!$data) {
            $this->error('参数错误');
        }
        if(request()->isPost()){
            $post_data = input('post.');
            $res = model('admin/bot')->getRobotClient($this->bot)->setFriendRemarkName([
                'robot_wxid' => $this->bot['uin'],
                'to_wxid' => $data['wxid'],
                'note' => $post_data['remark_name']
            ]);
            if($res['code']){
                $this->model->updateOne(['id' => $id, 'remark_name' => $post_data['remark_name']]);
                $this->success('设置成功', '/undefined');
            }
            $this->error($res['errmsg']);
        }

        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('设置备注名')
            ->setPostUrl(url('edit'))
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('remark_name', 'text', '备注名称', '30字内', [], 'required maxlength=30')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 同步好友数据
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function syncFriends(){
        $total = $this->model->pullFriends($this->bot);
        $this->success('此次同步到' . $total . '位好友');
    }
}