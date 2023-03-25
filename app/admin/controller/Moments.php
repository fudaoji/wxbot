<?php
/**
 * Created by PhpStorm.
 * Script Name: Moments.php
 * Create: 2021/12/21 12:00
 * Description: 朋友圈
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminM;
use app\admin\model\BotMember;
use app\common\model\Moments as MomentsM;
use app\constants\Common;
use app\constants\Media;
use app\constants\Pyq;
use app\constants\Bot as BotConst;
use ky\Logger;

class Moments extends Botbase
{
    /**
     * @var MomentsM
     */
    protected $model;
    /**
     * @var \app\admin\model\Bot
     */
    private $botM;
    /**
     * @var BotMember
     */
    private $memberM;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new MomentsM();
        $this->botM = new \app\admin\model\Bot();
        $this->memberM = new BotMember();
    }

    /**
     * 朋友列表
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \Exception
     */
    public function moments(){
        $wxid = input('wxid', $this->bot['uin']);
        $pyq_id = input('pyq_id', '');
        $has_more = true;

        $client = $this->botM->getRobotclient($this->bot);
        if($wxid == $this->bot['uin']){
            $res = $client->getMoments([
                'robot_wxid' => $this->bot['uin'],
                'pyq_id' => $pyq_id,
                'num' => 10
            ]);
            $member = $this->bot;
            $member['nickname_show'] = '我';
            $heads = $this->memberM->wxidToHead($this->bot);
            $heads[$this->bot['uin']] = $this->bot['headimgurl'];
        }else{
            $res = $client->getFriendMoments([
                'robot_wxid' => $this->bot['uin'],
                'to_wxid' => $wxid,
                'pyq_id' => $pyq_id,
                'num' => 10
            ]);
            $member = $this->memberM->getOneByMap(['wxid' => $wxid, 'uin' => $this->bot['uin']], true, true);
            $member['nickname_show'] = $member['nickname'];
            $heads = [$member['wxid'] => $member['headimgurl']];
        }

        if(!empty($res['data'])){
            $list = $res['data'];
            foreach ($list as $k => $v){
                $v = array_merge($v, Pyq::decodeObject($v['object']));
                unset($v['object']);
                $list[$k] = $v;
            }
        }else{
            $has_more = false;
            $list = [];
        }

        $assign = [
            'list' => $list,
            'member' => $member,
            'has_more' => $has_more,
            'heads' => $heads
        ];
        return $this->show($assign);
    }

    /**
     * 好友列表
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function pull(){
        if($this->bot['protocol'] != BotConst::PROTOCOL_MY){
            $this->error("当前机器人不支持朋友圈接口，请切换为西瓜框架！");
        }
        $search_key = input('search_key', '');
        $where = [
            'uin' => $this->bot['uin'],
            'type' => BotConst::FRIEND
        ];
        !empty($search_key) && $where['remark_name|nickname'] = ['like', '%'.$search_key.'%'];
        $assign = [
            'friend_list' => $this->memberM->getAll([
                'where' => $where,
                'refresh' => true
            ])
        ];
        return $this->show($assign);
    }

    /**
     * 实时朋友圈
     * @return mixed
     * @throws \Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pullBak()
    {
        //$pyq_id = $pyq_id ?? '';
        if (request()->isPost()) {
            $post_data = input('post.');
            $pyq_id = cookie('pyq_id' . $this->bot['id']);
            $post_data['page'] <=1 && $pyq_id = '';
            $total = 0;
            $client = $this->botM->getRobotclient($this->bot);
            $res = $client->getMoments([
                'robot_wxid' => $this->bot['uin'],
                'pyq_id' => $pyq_id,
                'num' => 10
            ]);
            if($res['code']){
                $list = $res['data'];
                if(count($list)){
                    cookie('pyq_id' . $this->bot['id'], $list[count($list)-1]['pyq_id']);
                }

                foreach ($list as $k => $v){
                    $xml = simplexml_load_string($v['object']);
                    $v['create_time'] = date('Y-m-d H:i:s', (int)$xml->createTime);
                    $content = '<p>配文：'.(string)$xml->contentDesc."</p>";
                    $media = $xml->ContentObject;
                    switch ($media->contentStyle){
                        case Pyq::TYPE_VIDEO:
                            $_media_type = '视频';
                            //$content_url = "<a href='".$media->mediaList->media->url."' target='_blank'>点击查看</a>";
                            $content_url = $media->mediaList->media->url;
                            break;
                        case Pyq::TYPE_IMG:
                            $_media_type = '图片';
                            //$content_url = "<a href='".$media->mediaList->media->url."' target='_blank'>点击查看</a>";
                            $content_url = $media->mediaList->media->url;
                            break;
                        case Pyq::TYPE_LINK:
                            $_media_type = '链接';
                            //$content_url = "<a href='".$media->contentUrl."' target='_blank'>点击查看</a>";
                            $content_url = $media->contentUrl;
                            break;
                        default:
                            $_media_type = "其他";
                            $content_url = '';
                            break;
                    }
                    $content .= "<p>".$_media_type.":".$content_url."</p>";
                    $v['content'] = $content;
                    Pyq::decodeObject($v['object']);
                    $list[$k] = $v;
                }
                $total = count($list) * 3;
            }else{
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->addTableColumn(['title' => '朋友圈ID', 'field' => 'pyq_id', 'minWidth' => 90])
            ->addTableColumn(['title' => '昵称', 'field' => 'nickname', 'minWidth' => 90])
            ->addTableColumn(['title' => '微信号', 'field' => 'username', 'minWidth' => 90])
            ->addTableColumn(['title' => '内容', 'field' => 'content', 'type' => 'article','minWidth' => 70])
            ->addTableColumn(['title' => '发圈时间', 'field' => 'create_time', 'minWidth' => 170]);

        if($this->bot['protocol'] != BotConst::PROTOCOL_MY){
            $builder->setTip("当前机器人不支持朋友圈接口，请切换为西瓜框架！");
        }
        return $builder->show();
    }

    /**
     * 接口发表记录
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = $this->staffWhere();
            !empty($post_data['search_key']) && $where['content'] = ['like', '%' . $post_data['search_key'] . '%'];
            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList(
                    [$post_data['page'], $post_data['limit']], $where,
                    [], true, true
                );
                foreach ($list as $k => $v){
                    $v['bot_id'] = implode(',', model('admin/bot')->getField(['title'], ['id' => ['in', $v['bot_id']]]));
                    if($material = model('media_' . $v['media_type'])->getOneByMap([
                        'admin_id' => $v['admin_id'],
                        'id' => intval($v['media_id'])
                    ], true, true)){
                        $v['media_title'] = $v['media_type'] == 'text' ? $material['content'] : $material['title'];
                    }
                    $list[$k] = $v;
                }
            }else{
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '关键词', 'placeholder' => '']
        ])
            ->addTopButton('addnew', ['title' => '发朋友圈'])
            ->addTableColumn(['title' => '机器人', 'field' => 'bot_id', 'minWidth' => 90])
            ->addTableColumn(['title' => '类型', 'field' => 'media_type', 'minWidth' => 80])
            ->addTableColumn(['title' => '配文', 'field' => 'content', 'minWidth' => 120])
            ->addTableColumn(['title' => '素材', 'field' => 'media_title', 'minWidth' => 120])
            ->addTableColumn(['title' => '计划时间', 'field' => 'plan_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '发圈时间', 'field' => 'publish_time', 'type' => 'datetime', 'minWidth' => 180])
            ->addTableColumn(['title' => '状态', 'field' => 'status', 'minWidth' => 80,'type' => 'switch','options' => Common::status()])
            ->addTableColumn(['title' => '操作', 'minWidth' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');

        return $builder->show();
    }

    /**
     * 发朋友圈
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function add(){
        $builder = new FormBuilder();
        $material = [];

        $data = [
            'admin_id' => AdminM::getCompanyId($this->adminInfo),
            'bot_id' => [$this->bot['id']]
        ];

        $builder->setPostUrl(url('savePost'))
            ->setTip("如果只发送文本，可不选择素材。只有图片素材才可以多个，其他多媒体素材只能选一个。")
            ->addFormItem('admin_id', 'hidden', 'adminid', 'adminid')
            ->addFormItem('bot_id', 'chosen_multi', '选择机器人', '选择机器人', $this->getBots(), 'required')
            ->addFormItem('plan_time', 'datetime', '发送时间', '不填则立即发圈', [], '')
            ->addFormItem('content', 'textarea', '配文', '配文')
            ->addFormItem('media', 'choose_media_multi', '内容', '内容', ['types' => \app\constants\Media::types()])
            ->setFormData($data);

        return $builder->show(['material' => $material]);
    }

    /**
     * 发朋友圈
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function edit(){
        $id = input('id', null);
        $data = $this->model->getOneByMap(['id' => $id, 'admin_id' => AdminM::getCompanyId($this->adminInfo)], true, true);

        if (!$data) {
            $this->error('参数错误');
        }

        $data['bot_id'] = explode(',', $data['bot_id']);
        $data['plan_time'] = date('Y-m-d H:i:s', $data['plan_time']);
        $builder = new FormBuilder();
        $materials = [];
        if($data['media_id']){
            $media_ids = explode(',', $data['media_id']);
            foreach ($media_ids as $media_id){
                $m = model('media_' . $data['media_type'])->getOneByMap([
                    'admin_id' => $data['admin_id'],
                    'id' => $media_id
                ], true, true);
                $m['type'] = $data['media_type'];
                $materials[] = $m;
            }
        }

        $builder->setPostUrl(url('savePost'))
            ->setTip("如果只发送文本，可不选择素材。只有图片素材才可以多个，其他多媒体素材只能选一个。")
            ->addFormItem('id', 'hidden', 'ID', 'ID')
            ->addFormItem('admin_id', 'hidden', 'adminid', 'adminid')
            ->addFormItem('bot_id', 'chosen_multi', '选择机器人', '选择机器人', $this->getBots(), 'required')
            ->addFormItem('plan_time', 'datetime', '发送时间', '不填则立即发圈', [], '')
            ->addFormItem('content', 'textarea', '配文', '配文')
            ->addFormItem('media', 'choose_media_multi', '内容', '内容', ['types' => \app\constants\Media::types(), 'materials' => $materials])
            ->setFormData($data);

        return $builder->show();
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = input('post.');
        if(empty($post_data['content']) && count($post_data['media_id_type']) < 1){
            $this->error('请填写配文或选择素材');
        }
        $post_data['media_type'] = 'text';
        $msg = '发圈成功！';
        if(empty($post_data['plan_time'])){
            $post_data['plan_time'] = time();
        }else{
            $post_data['plan_time'] = strtotime($post_data['plan_time']);
            $msg = "发圈任务保存成功！";
        }

        if(!empty($post_data['media_id_type'])){
            $media_id = [];
            $media_type = '';
            foreach ($post_data['media_id_type'] as $id_type){
                list($id, $type) = explode('_', $id_type);
                $media_id[] = $id;
                $media_type = $type;
            }
            $media_id = implode(',', $media_id);
            $post_data['media_id'] = $media_id;
            $post_data['media_type'] = $media_type;
            unset($post_data['media_id_type']);
        }
        if(empty($post_data[$this->pk])){
            $res = $this->model->addOne($post_data);
        }else {
            $res = $this->model->updateOne($post_data);
        }

        if($res){
            if($res['plan_time'] <= time()){
                $this->model->publishMoments($res);
            }

            $this->success($msg, $jump_to);
        }else{
            $this->error('数据保存出错');
        }
    }

}