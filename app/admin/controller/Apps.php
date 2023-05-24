<?php
/**
 * Created by PhpStorm.
 * Script Name: Apps.php
 * Create: 2022/12/15 8:14
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminM;
use app\common\model\Addon;
use app\common\model\AdminAddon;
use app\common\service\Addon as AppService;
use think\facade\Db;


class Apps extends Base
{
    /**
     * @var Addon
     */
    protected $model;
    /**
     * @var AdminAddon
     */
    private $adminAppM;

    public function __construct(){
        parent::__construct();
        $this->model = new Addon();
        $this->adminAppM = new AdminAddon();
    }

    /**
     * 我的应用
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function index(){
        $company_id = AdminM::getCompanyId($this->adminInfo);
        if(request()->isPost()){ //开启关闭
            $id = input('post.id');
            if(empty($ta = $this->adminAppM->getOneByMap(['id' => $id, 'company_id' => $company_id]))){
                $this->error('数据不存在');
            }
            $this->adminAppM->update(['id' => $id, 'status' => abs($ta['status'] - 1)]);
            $this->success('操作成功');
        }

        $page_size = 12;
        $status = input('status', -1);
        $search_key = input('search_key', '');
        $where = [
            ['ta.deadline', '>', time()],
            ['ta.company_id', '=', $company_id],
        ];

        $status != -1 && $where[] = ['ta.status', '=', $status];
        $search_key && $where[] = ['app.title|app.desc', 'like', '%'.$search_key.'%'];
        $query = $this->adminAppM->alias('ta')
            ->where($where)
            ->join('addon app', 'app.name=ta.app_name')
            ->join('admin admin', 'admin.id=ta.company_id');
        $data_list = $query->order('ta.update_time', 'desc')
            ->field([
                'ta.*','app.logo','app.desc','app.name','app.title','app.admin_url','app.admin_url_type',
                'admin.realname', 'admin.mobile','admin.username'
            ])
            ->paginate($page_size);
        $page = $data_list->appends(['status' => $status, 'search_key' => $search_key])->render();

        $assign = [
            'data_list' => $data_list,
            'search_key' => $search_key,
            'page' => $page,
            'status' => $status
        ];
        return $this->show($assign);
    }

    /**
     * 过期应用
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function overtime(){
        $company_id = AdminM::getCompanyId($this->adminInfo);
        $page_size = 12;
        $status = input('status', -1);
        $search_key = input('search_key', '');
        $where = [
            ['ta.deadline', '<=', time()],
            ['ta.company_id', '=', $company_id],
        ];

        $search_key && $where[] = ['app.title|app.desc', 'like', '%'.$search_key.'%'];
        $query = $this->adminAppM->alias('ta')
            ->where($where)
            ->join('addon app', 'app.name=ta.app_name')
            ->join('admin admin', 'admin.id=ta.company_id');
        $data_list = $query->order('ta.update_time', 'desc')
            ->field([
                'ta.*','app.logo','app.desc','app.name','app.title','app.admin_url','app.admin_url_type',
                'admin.realname', 'admin.mobile','admin.username'
            ])
            ->paginate($page_size);
        $page = $data_list->appends(['status' => $status, 'search_key' => $search_key])->render();

        $assign = [
            'data_list' => $data_list,
            'search_key' => $search_key,
            'page' => $page
        ];
        return $this->show($assign);
    }
}