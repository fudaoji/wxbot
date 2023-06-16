<?php
/**
 * SCRIPT_NAME: Admin.php
 * Created by PhpStorm.
 * Time: 2020/9/6 23:23
 * Description: 管理员
 * @author: fudaoji <fdj@kuryun.cn>
 */

namespace app\admin\controller;
use app\common\model\AdminAddon as TenantAppM;
use app\common\service\Addon as AppService;
use think\facade\Db;

class Adminaddon extends Base
{
    /**
     * @var TenantAppM
     */
    protected $model;

    public function __construct(){
        parent::__construct();
        $this->model = new TenantAppM();
    }

    public function index(){
        if(request()->isPost()){
            $post_data = input('post.');
            $where = [];
            !empty($post_data['search_key']) && $where[] = ['title|name|mobile|realname', 'like', '%'.$post_data['search_key'].'%'];
            $query = $this->model->alias('ta')
                ->where($where)
                ->join('addon app', 'app.id=ta.addon_id')
                ->join('admin tenant', 'tenant.id=ta.company_id');
            $total = $query->count();
            if ($total) {
                $list = $query->page($post_data['page'], $post_data['limit'])
                    ->order('ta.update_time', 'desc')
                    ->field(['ta.*','app.name','app.title','tenant.realname','tenant.mobile','tenant.username'])
                    ->select();
                foreach ($list as $k => $v){
                    $v['app_info'] = $v['title'] . '('.$v['name'].')';
                    $v['tenant_info'] = $v['realname'] . '('.$v['username'].','.$v['mobile'].')';
                    $list[$k] = $v;
                }
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }

        $builder = new ListBuilder();
        $builder->setSearch([
            ['type' => 'text', 'name' => 'search_key', 'title' => '搜索词','placeholder' => '应用名称或标识、客户名称或手机号']
        ])
            ->addTopButton('addnew')
            ->addTableColumn(['title' => '应用信息', 'field' => 'app_info', 'minWidth' => 120])
            ->addTableColumn(['title' => '客户信息', 'field' => 'tenant_info', 'minWidth' => 120])
            ->addTableColumn(['title' => '到期时间', 'field' => 'deadline', 'type' => 'datetime','minWidth' => 120])
            ->addTableColumn(['title' => '首次开通时间', 'field' => 'create_time', 'minWidth' => 140])
            ->addTableColumn(['title' => '最后修改时间', 'field' => 'update_time', 'minWidth' => 140])
            ->addTableColumn(['title' => '操作', 'minWidth' => 200, 'type' => 'toolbar'])
            ->addRightButton('edit')
            ->addRightButton('delete');
        return $builder->show();
    }


    public function savePost($url = '/undefined', $data = []){
        if(request()->isPost()){
            $post_data = input('post.');
            $data = [
                'deadline' => strtotime($post_data['deadline']),
            ];
            Db::startTrans();
            try {
                if(!empty($post_data['id'])){
                    $data['id'] = $post_data['id'];
                    $this->model->update($data);
                }else{
                    $apps = explode(',', $post_data['app_name']);
                    foreach ($apps as $app_id){
                        if($this->model->where('company_id', $post_data['company_id'])
                            ->where('addon_id', $app_id)->count()){
                            continue;
                        }
                        $data['company_id'] = $post_data['company_id'];
                        $data['addon_id'] = $app_id;
                        $this->model->addOne($data);
                    }
                }
                Db::commit();
                $res = true;
            }catch (\Exception $e){
                Db::rollback();
                $res = (string)$e->getMessage();
            }
            if($res === true){
                $this->success('保存成功', $url);
            }
            $this->error('操作失败：' . $res, '', ['token' => token()]);
        }
    }

    /**
     * 新增用户应用
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add(){
        $company_id = input('company_id', 0);
        $company_list = \app\admin\model\Admin::getTenantIdToName();
        $app_list = AppService::getIdToTitle();
        $builder = new FormBuilder();
        $builder->setPostUrl(url('savePost'))
            ->addFormItem('app_name', 'chosen_multi', '应用', '应用', $app_list, 'required')
            ->addFormItem('company_id', 'chosen', '会员', '会员', $company_list, 'required')
            ->addFormItem('deadline', 'datetime', '到期时间', '到期时间', [], 'required')
            ->setFormData(['company_id' => $company_id]);
        return $builder->show();
    }

    /**
     * 编辑用户应用
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function edit(){
        $data = $this->model->find(input('id', 0));
        if(empty($data)){
            $this->error('数据不存在');
        }
        $data['deadline'] = date('Y-m-d H:i:s', $data['deadline']);
        $builder = new FormBuilder();
        $builder->setPostUrl(url('savePost'))
            ->addFormItem('id', 'hidden', 'id', 'id')
            ->addFormItem('deadline', 'datetime', '到期时间', '到期时间', [], 'required')
            ->setFormData($data);
        return $builder->show();
    }
}