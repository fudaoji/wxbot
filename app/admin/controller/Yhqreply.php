<?php

namespace app\admin\controller;
use app\common\model\yhq\Coupon;
use app\common\model\yhq\Reply;

class Yhqreply extends Botbase
{
    /**
     * @var Reply
     */
    protected $model;
    /**
     * @var array
     */
    private $events;
    /**
     * @var Coupon
     */
    private $couponM;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new Reply();
        $this->couponM = new Coupon();
        $this->events = [
            'beadded' => '被加好友',
            'friend_in' => '新人入群',
        ];
    }

    /**
     * 触发设置
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = ['bot_id' => $this->bot['id']];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    ['id' => 'desc'], true, true
                );
                foreach ($list as $k => &$v){
                    $members = model('admin/botMember')->getField(['nickname'], ['wxid' => ['in', $v['wxids']], 'uin' => $this->bot['uin']]);
                    if(($count = count($members)) > 1){
                        $v['wxids'] = $members[0] . '等'.$count.'个';
                    }else{
                        $v['wxids'] = $members[0];
                    }
                }
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->addTopButton('addnew')
            ->addTableColumn(['title' => '触发类型', 'field' => 'event', 'type' => 'enum','options'=>$this->events,'minWidth' => 80])
            ->addTableColumn(['title' => '关联券', 'field' => 'coupon_id', 'type' => 'enum','options'=>$this->getCoupons(['status' => ['>=', 0]]),'minWidth' => 100])
            ->addTableColumn(['title' => '回复内容', 'field' => 'content', 'minWidth' => 80])
            ->addTableColumn(['title' => '作用域', 'field' => 'wxids', 'minWidth' => 180])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'minWidth' => 80,'type' => 'enum','options' => [0 => '禁用', 1=> '启用']])
            ->addTableColumn(['title' => '操作', 'minWidth' => 150, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');

        return $builder->show();
    }

    public function edit(){
        $id = input('id', null);
        $reply = $this->model->getOneByMap(['id' => $id, 'bot_id' => $this->bot['id']], true, true);

        if (!$reply) {
            $this->error('参数错误');
        }

        if(!empty($reply['wxids'])){
            $reply['wxids'] = explode(',', $reply['wxids']);
        }
        $builder = new FormBuilder();
        $builder->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('event', 'select', '触发类型', '触发类型', $this->events, 'required min=0')
            ->addFormItem('coupon_id', 'chosen', '关联券', '关联券', $this->getCoupons(), 'required')
            ->addFormItem('content', 'textarea', '回复内容', '回复内容', [], 'required')
            ->addFormItem('wxids', 'chosen_multi', '作用域', '作用域', $this->getMembers(), 'required')
            ->addFormItem('status', 'radio', '状态', '状态', [1 => '启用', 0 => '禁用'], 'required')
            ->setFormData($reply);
        return $builder->show();
    }

    /**
     * 新增
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function add(){
        $data = [
            'bot_id' => $this->bot['id'],
            'status' => 1
        ];

        $builder = new FormBuilder();
        $builder->setPostUrl(url('savePost'))
            ->setTip("<ul>
<li>回复内容中的[昵称]将会被替换用户的实际昵称；</li>
<li>[优惠券码]会被替换为实际的优惠券码；</li>
<li>[优惠券链接]会被替换为实际的优惠券链接</li>
</ul>")
            ->addFormItem('bot_id', 'hidden', 'botid', 'botid')
            ->addFormItem('event', 'select', '触发类型', '触发类型', $this->events, 'required min=0')
            ->addFormItem('coupon_id', 'chosen', '关联券', '关联券', $this->getCoupons(), 'required')
            ->addFormItem('content', 'textarea', '回复内容', '回复内容', [], 'required')
            ->addFormItem('wxids', 'chosen_multi', '作用域', '作用域', $this->getMembers(), 'required')
            ->addFormItem('status', 'radio', '状态', '状态', [1 => '启用', 0 => '禁用'], 'required')
            ->setFormData($data);
        return $builder->show();
    }

    private function getCoupons($where = []){
        $where = array_merge(['bot_id' => $this->bot['id'], 'status' => 1], $where);
        return $this->couponM->getField(['id','title'], $where);
    }
}