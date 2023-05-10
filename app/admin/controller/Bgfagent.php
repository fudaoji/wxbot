<?php
/**
 * Created by PhpStorm.
 * Script Name: Tenant.php
 * Create: 2023/2/16 14:37
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminM;
use app\common\model\bgf\Agent as AgentM;
use app\constants\Common;

class Bgfagent extends Base
{
    /**
     * @var AgentM
     */
    protected $model;

    public function initialize(){
        parent::initialize();
        $this->model = new AgentM();
    }

    /**
     * 绑定群
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function bindGroup(){
        $id = input('id', 0);
        $data = $this->model->getOne($id);
        $data['groups'] = empty($data['groups']) ? [] : explode(',', $data['groups']);
        $groups = model('admin/botMember')->getField('wxid,nickname',['uin' => $this->bot['uin'], 'type' => \app\constants\Bot::GROUP], true);
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('关联群')  //设置页面标题
            ->setPostUrl(url('savePost')) //设置表单提交地址
            ->addFormItem('id', 'hidden', 'channel id', 'channel id')
            ->addFormItem('title', 'static', '代理', $data['title'])
            ->addFormItem('groups', 'chosen_multi', '选择群聊', '群聊', $groups, 'required')
            ->setFormData($data);

        return $builder->show();
    }

    /**
     * 客户管理（自主注册）
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = ['staff_id' => $this->adminInfo['id']];
            !empty($post_data['search_key']) && $where['title|mobile'] = ['like', '%'.$post_data['search_key'].'%'];
            !empty($post_data['super_id']) && $where['super_id'] = $post_data['super_id'];

            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList([$post_data['page'], $post_data['limit']], $where, ['id' => 'desc'], true, true);
            } else {
                $list = [];
            }
            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词', 'placeholder' => '手机号、姓名'],
            ['type' => 'text', 'name' => 'super_id', 'title' => 'SuperId', 'placeholder' => 'SuperId']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => 'superId', 'field' => 'super_id'])
            ->addTableColumn(['title' => '名称', 'field' => 'title'])
            ->addTableColumn(['title' => '手机号', 'field' => 'mobile'])
            ->addTableColumn(['title' => '添加时间', 'field' => 'create_time', 'type' => 'datetime'])
            ->addTableColumn(['title' => '操作', 'minWidth' => 120, 'type' => 'toolbar'])
            ->addRightButton('edit', ['title' => '关联群', 'href' => url('bindGroup', ['id' => '__data_id__'])])
            ->addRightButton('delete');
        return $builder->show();
    }

    /**
     * 新增
     */
    public function add(){
        $list = $this->pullAgentList();
        $exists = $this->model->getField(['super_id']);
        if($this->request->isPost()){
            $post_data = input('post.');
            $supers = explode(',', $post_data['super_id']);
            if(empty($supers)){
                $this->error('请选择代理商！');
            }
            foreach ($list as $item){
                if(in_array($item['userId'], $exists) || !in_array($item['userId'], $supers)) continue;
                $this->model->addOne([
                    'staff_id' => $this->adminInfo['id'],
                    'super_id' => $item['userId'],
                    'title' => $item['name'] ?: '',
                    'mobile' => $item['phone'] ?: ''
                ]);
            }
            $this->success('操作成功！', '/undefined');
        }
        $id2name = [];
        foreach ($list as $item){
            if(in_array($item['userId'], $exists)) continue;
            $id2name[$item['userId']] = $item['name'] . "({$item['phone']})";
        }
        //使用FormBuilder快速建立表单页面。
        $builder = new FormBuilder();
        $builder->setMetaTitle('添加')  //设置页面标题
            ->setPostUrl(url('add')) //设置表单提交地址
            ->addFormItem('super_id', 'chosen_multi', '选择代理商', '选择代理商', $id2name, 'required');

        return $builder->show();
    }

    /**
     * get agent list from third
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function pullAgentList(){
        $url = 'https://appnew.baogefang.cc/houses-backstage/other/public/getAllUser';
        $data = ['pageNumber' => 1, 'pageSize' => 10000];
        if(($res = http_post($url, $data, 'json')) !== false){
            $res = json_decode($res, true);
            $list = $res['data']['records'];
        }else{
            $list = [];
        }
        return $list;
    }
}