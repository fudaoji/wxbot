<?php
/**
 * SCRIPT_NAME: Admin.php
 * Created by PhpStorm.
 * Time: 2016/6/6 22:23
 * Description: 管理员
 * @author: Doogie <461960962@qq.com>
 */

namespace app\admin\controller;
use app\admin\model\AdminLog as LogM;
use app\admin\model\Admin;
use app\common\service\AdminLog as LogService;

class Adminlog extends Base
{
    /**
     * @var LogM
     */
    protected $model;
    protected $adminM;

    public function initialize()
    {
        parent::initialize();
        $this->model = new LogM();
        $this->adminM = new Admin();
    }

    public function index()
    {
        $cur_year = date('Y');
        if(request()->isPost()){
            $post_data = input('post.');
            $year = input('year', $cur_year);
            $where = ['year' => $year];
            !empty($post_data['search_key']) && $where = ['desc' => ['like', '%' . $post_data['search_key'] . '%']];
            !empty($post_data['admin_id']) && $where['admin_id'] = $post_data['admin_id'];
            !empty($post_data['type']) && $where['type'] = $post_data['type'];

            $total = $this->model->total($where, true);
            if ($total) {
                $list = $this->model->getList([$post_data['page'], $post_data['limit']], $where, ['id' => 'desc'], true, 1);
            } else {
                $list = [];
            }

            $this->success('success', '', ['total' => $total, 'list' => $list]);
        }
        $year_list = range(2023, $cur_year);
        $year_list = array_combine($year_list, $year_list);

        // 使用Builder快速建立列表页面。
        $builder = new ListBuilder();
        $builder = $builder->setMetaTitle('操作日志')// 设置页面标题
            ->setSearch([
                ['title' => '年份', 'name' => 'year', 'type' => 'select', 'options' => $year_list, 'value' => $cur_year],
                ['title' => '管理员', 'name' => 'admin_id', 'type' => 'select', 'options' => [0 => '所有'] + Admin::getTeamIdToName()],
                ['title' => '操作类型', 'name' => 'type_id', 'type' => 'select', 'options' => ['' => '所有'] + LogService::types()],
            ])
            ->addTableColumn(['field' => 'type', 'title' => '类型', 'type' => 'enum', 'options' => LogService::types(), 'minWidth' => 120])
            ->addTableColumn(['field' => 'desc', 'title' => '操作详情', 'minWidth' => 120])
            ->addTableColumn(['field' => 'admin_username', 'title' => '操作用户', 'minWidth' => 120])
            ->addTableColumn(['field' => 'ip', 'title' => 'IP', 'minWidth' => 100])
            ->addTableColumn(['field' => 'create_time', 'title' => '操作时间', 'type' => 'datetime','minWidth' => 120]);
        return $builder->show();
    }
}