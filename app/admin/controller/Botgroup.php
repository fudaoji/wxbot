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
use app\common\model\MemberTag as TagM;

class Botgroup extends Botbase
{
    /**
     * @var BotMember
     */
    protected $model;

    /**
     * @var TagM
     */
    private $tagM;

    /**
     * 初始化
     */
    public function initialize()
    {
        $this->needAid = false;
        parent::initialize();
        $this->model = new BotMember();
        $this->tagM = new TagM();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['type' => \app\constants\Bot::GROUP, 'uin' => $this->bot['uin']];
            !empty($post_data['search_key']) && $where['nickname|remark_name|wxid'] = ['like', '%' . $post_data['search_key'] . '%'];
            !empty($post_data['tags']) && $where['tags'] = ['like', '%' . $post_data['tags'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList([$post_data['page'], $post_data['limit']],
                    $where, ['id' => 'desc'],
                    ['id', 'nickname', 'remark_name', 'wxid', 'tags'], true
                );
            }else{
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $tip = "<ul><li>注意：</li><li>当前的备注名称就是指实际微信通讯录当中您对该群的备注</li></ul>";
        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'tags', 'title' => '分组', 'placeholder' => '分组名称'],
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '群名称、备注名称、wxid']
        ])
            ->setTip($tip)
            ->addTopButton('self', ['title'=>'拉取最新群组', 'href' => url('syncGroups'), 'data-ajax' => 1])
            ->addTableColumn(['title' => '群id', 'field' => 'wxid'])
            ->addTableColumn(['title' => '群名称', 'field' => 'nickname'])
            ->addTableColumn(['title' => '备注名称', 'field' => 'remark_name'])
            ->addTableColumn(['title' => '分组', 'field' => 'tags', 'minWidth' => 100])
            ->addTableColumn(['title' => '操作', 'minWidth' => 220, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '群成员', 'href' => url('groupmember/index', ['group_id' => '__data_id__']), 'class' => 'layui-btn layui-btn-xs'])
            ->addRightButton('edit', ['title' => '设置白名单', 'href' => url('whiteid/index', ['group_id' => '__data_id__']), 'class' => 'layui-btn layui-btn-xs layui-btn-warm'])
            ->addRightButton('edit')
            ->addRightButton('self',['title' => '退群', 'href' => url('quitGroupPost', ['id' => '__data_id__']), 'class' => 'layui-btn layui-btn-xs layui-btn-danger', 'data-ajax' => 1, 'data-confirm' => 1]);

        return $builder->show();
    }

    /**
     * 退群
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function quitGroupPost()
    {
        $id = input('id', null);
        $data = $this->model->getOneByMap(['id' => $id, 'uin' => $this->bot['uin']], true, true);

        if (!$data) {
            $this->error('参数错误');
        }
        $res = model('admin/bot')->getRobotClient($this->bot)->quitGroup([
            'robot_wxid' => $this->bot['uin'],
            'uuid' => $this->bot['uuid'],
            'group_wxid' => $data['wxid']
        ]);
        if($res['code']){
            $this->model->delOne($id);
            $this->success('操作成功');
        }else{
            $this->error($res['errmsg']);
        }
    }

    /**
     * 编辑
     */
    public function edit(){
        if(request()->isPost()){
            $post_data = input('post.');
            $bot_client = model('admin/bot')->getRobotClient($this->bot);
            $res = $bot_client->setGroupName([
                'robot_wxid' => $this->bot['uin'],
                'uuid' => $this->bot['uuid'],
                'group_wxid' => $post_data['wxid'],
                'group_name' => $post_data['nickname']
            ]);
            if($res['code'] != 1){
                $this->error('群名修改失败：' . $res['errmsg']);
            }
            return parent::savePost('/undefined', $post_data);
        }
        $id = input('id');
        $data = $this->model->getOne($id);
        if(! $data){
            $this->error('数据不存在');
        }

        $data['tags'] = empty($data['tags']) ? [] : explode(',', $data['tags']);
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('编辑')  //设置页面标题
            ->setPostUrl(url('edit')) //设置表单提交地址
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('wxid', 'hidden', 'wxid', 'wxid')
            ->addFormItem('nickname', 'text', '群名', '1-20位长度', [], 'required minlength=1 maxlength=20')
            ->addFormItem('tags', 'chosen_multi', '分组', '分组', $this->tagM->getTitleToTitle($this->bot['id']))
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 同步数据
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function syncGroups(){
        $total = $this->model->pullGroups($this->bot);
        $this->success('此次同步到' . $total . '个群');
    }
}