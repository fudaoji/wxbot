<?php
/**
 * Created by PhpStorm.
 * Script Name: Groupmember.php
 * Create: 2021/12/21 12:00
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;


use app\common\model\Whiteid as WhiteidM;
use app\admin\model\BotGroupmember;

class Whiteid extends Botbase
{
    /**
     * @var WhiteidM
     */
    protected $model;
    /**
     * @var BotGroupmember
     */
    private $groupMemberM;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new WhiteidM();
        $this->groupMemberM = new BotGroupmember();
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
        $group = model('botMember')->getOne(input('group_id', 0));
        if (!$group) {
            $this->error('参数错误');
        }
        $data = $this->model->getOneByMap(['bot_id' => $this->bot['id'], 'group_wxid' => $group['wxid']], true, true);

        $data['wxids'] = empty($data['wxids']) ? [] : explode(',', $data['wxids']);
        $data['group_wxid'] = $group['wxid'];
        $data['bot_id'] = $this->bot['id'];

        $members = $this->groupMemberM->getField(['wxid', 'nickname'], ['group_id' => $group['id']], true);
        // 使用FormBuilder快速建立表单页面
        $builder = new FormBuilder();
        $builder->setMetaTitle('设置白名单')
            ->setTip("白名单成员即使触发了踢除机制也不会被移除")
            ->setPostUrl(url('savePost'))
            ->addFormItem('group_wxid', 'hidden', 'group_wxid', 'group_wxid')
            ->addFormItem('wxids', 'chosen_multi', '选择群友', '选择群友', $members, 'required')
            ->setFormData($data);
        !empty($data) && $builder->addFormItem('id', 'hidden', 'ID', 'ID');
        return $builder->show();
    }

    public function savePost($jump_to = '', $data = [])
    {
        $post_data = input('post.');
        $post_data['admin_id'] = $this->adminInfo['id'];
        if(empty($post_data[$this->pk])){
            $res = $this->model->addOne($post_data);
        }else {
            $res = $this->model->updateOne($post_data);
        }
        if($res){
            $this->model->getOneByMap(['bot_id' => $this->bot['id'], 'group_wxid' => $res['group_wxid']], true, true);
            $this->success('数据保存成功', $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }
}