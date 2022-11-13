<?php
/**
 * Created by PhpStorm.
 * Script Name: Moments.php
 * Create: 2021/12/21 12:00
 * Description: 朋友圈
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\common\model\Moments as MomentsM;
use app\constants\Media;
use app\constants\Pyq;

class Moments extends Botbase
{
    /**
     * @var MomentsM
     */
    protected $model;

    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = new MomentsM();
    }

    public function pull()
    {
        //$pyq_id = $pyq_id ?? '';
        if (request()->isPost()) {
            $post_data = input('post.');
            $pyq_id = cookie('pyq_id' . $this->bot['id']);
            $post_data['page'] <=1 && $pyq_id = '';
            $total = 0;
            $client = model('admin/bot')->getRobotclient($this->bot);
            $res = $client->getMoments([
                'robot_wxid' => $this->bot['uin'],
                'pyq_id' => $pyq_id,
                'num' => 10
            ]);
            //dump($res);exit;
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
            ->addTableColumn(['title' => '发圈时间', 'field' => 'create_time', 'minWidth' => 170])
            /*->addTableColumn(['title' => '操作', 'minWidth' => 200, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '设置备注名'])
            ->addRightButton('delete', ['title' => '删除好友', 'href' => url('deleteFriendPost', ['id' => '__data_id__'])])*/;

        return $builder->show();
    }

    public function index()
    {
        if (request()->isPost()) {
            $post_data = input('post.');
            $where = [];
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
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '机器人', 'field' => 'bot_id', 'minWidth' => 150])
            ->addTableColumn(['title' => '类型', 'field' => 'media_type', 'minWidth' => 100])
            ->addTableColumn(['title' => '配文', 'field' => 'content', 'minWidth' => 150])
            ->addTableColumn(['title' => '素材', 'field' => 'media_title', 'minWidth' => 120])
            ->addTableColumn(['title' => '发圈时间', 'field' => 'publish_time', 'type' => 'datetime', 'minWidth' => 200])
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
            'admin_id' => $this->adminInfo['id']
        ];

        $builder->setPostUrl(url('savePost'))
            ->setTip("如果只发送文本，可不选择素材")
            ->addFormItem('admin_id', 'hidden', 'adminid', 'adminid')
            ->addFormItem('bot_id', 'chosen_multi', '选择机器人', '选择机器人', $this->getBots(), 'required')
            ->addFormItem('content', 'textarea', '配文', '配文')
            ->addFormItem('media', 'choose_media_multi', '内容', '内容', ['types' => \app\constants\Media::types()], 'required')
            ->setFormData($data);

        return $builder->show(['material' => $material]);
    }

    public function savePost($jump_to = '/undefined', $data = [])
    {
        $post_data = input('post.');
        if(empty($post_data['content']) && count($post_data['media_id_type']) < 1){
            $this->error('请填写配文或选择素材');
        }
        $post_data['media_type'] = 'text';

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
            if(!empty($res['media_id'])){
                if($res['media_type'] == Media::IMAGE ){
                    $media = model('media_' . $res['media_type'])->getField('url', [
                        'admin_id' => $res['admin_id'],
                        'id' => ['in', $res['media_id']]
                    ], true);
                }else{
                    $media = model('media_' . $res['media_type'])->getOneByMap([
                        'admin_id' => $res['admin_id'],
                        'id' => $res['media_id']
                    ], true, true);
                }
            }
            $bots = explode(',', $res['bot_id']);
            foreach ($bots as $bot_id){
                $bot = model('admin/bot')->getOne($bot_id);
                $client = model('admin/bot')->getRobotClient($bot);
                switch ($res['media_type']){
                    case Media::IMAGE:
                        $send_res = $client->sendMomentsImg([
                            'robot_wxid' => $bot['uin'],
                            'content' => $res['content'],
                            'img' => implode(',', $media)
                        ]);
                        break;
                    case Media::VIDEO:
                        $send_res = $client->sendMomentsVideo([
                            'robot_wxid' => $bot['uin'],
                            'content' => $res['content'],
                            'video' => $media['url']
                        ]);
                        break;
                    case Media::LINK:
                        $send_res = $client->sendMomentsLink([
                            'robot_wxid' => $bot['uin'],
                            'content' => $res['content'],
                            'title' => $media['title'],
                            'img' => $media['image_url'],
                            'url' => $media['url']
                        ]);
                        break;
                    default:
                        $send_res = $client->sendMomentsText([
                            'robot_wxid' => $bot['uin'],
                            'content' => $res['content']
                        ]);
                        break;
                }
            }

            $res = $this->model->updateOne(['id' => $res['id'], 'publish_time' => time()]);
            $this->success('发送成功', $jump_to, $send_res);
        }else{
            $this->error('数据保存出错');
        }
    }

}