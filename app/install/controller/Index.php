<?php
namespace app\install\controller;

use think\facade\Db;
use think\facade\View;

class Index extends Base
{

	protected $status;

	public function initialize() {
	    parent::initialize();
		$this->status = array(
			'index'    => 'info',
			'check'    => 'info',
			'config'   => 'info',
			'sql'      => 'info',
			'complete' => 'info',
		);
		$this->assign('stlUrl',request()->domain().'/index.php');
	}

    /**
     * 安装协议声明
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
	public function index() {
        if(is_dir(app()->getRuntimePath())){
            if(! is_writable(app()->getRuntimePath())){
                exit("请将runtime目录设置成可写");
            }
        }

		$this->status['index'] = 'primary';
		return $this->show(['status' => $this->status]);
	}

    /**
     * 环境检测
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
	public function check() {
		session('error', false);
		//环境检测
		$env = check_env();
		//目录文件读写检测
		if (IS_WRITE) {
			$dirfile = check_dirfile();
			$this->assign['dirfile'] = $dirfile;
		}
		//函数检测
		$func = check_func();
		session('step', 1);
		$this->assign['env'] = $env;
		$this->assign['func'] = $func;
		$this->status['index'] = 'success';
		$this->status['check'] = 'primary';
		$this->assign['status'] = $this->status;
		return $this->show();
	}

    /**
     * 系统配置
     * @return mixed|void
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
	public function config() {
		if (request()->IsPost()) {
		    $post_data = input('post.');
		    $db = $post_data['db'];
		    $admin = $post_data['admin'];
		    $redis = $post_data['redis'];
		    $memcache = $post_data['memcache'];

			//检测管理员信息
			if (empty($admin['username']) || empty($admin['password'] || empty($admin['repassword']))) {
				$this->error('请填写完整管理员信息');
			} else if ($admin['password'] != $admin['repassword']) {
				$this->error('两次输入的密码不一致');
			}
            //缓存管理员信息
            session('admin_info', $admin);

			//检测数据库配置
			if (empty($db['type']) || empty($db['hostname']) || empty($db['database']) || empty($db['username']) || empty($db['password'])
                || empty($db['port'])
            ) {
				$this->error('请填写完整的数据库配置');
			} else {
				//缓存数据库配置
				session('db_config', $db);
			}
			if(empty($post_data['cache_type'])){
                $this->error('请选择缓存类型');
            }
			session('cache_type', $post_data['cache_type']);
			if($post_data['cache_type'] != 'file'){
                //检测memcache
                if(empty($memcache['memcache_host']) || empty($memcache['memcache_port'])){
                    $this->error('请填写完整的Memcached配置');
                }else{
                    session('memcache_config', $memcache);
                }
            }
			//检测redis
            if(empty($redis['redis_host']) || empty($redis['redis_port'])){
                $this->error('请填写完整的redis配置');
            }else{
                session('redis_config', $redis);
            }

            //创建配置文件
            $conf = write_config();
            session('config_file', $conf);
            $this->success('success', url('sql'));
		}
        $this->status['index']  = 'success';
        $this->status['check']  = 'success';
        $this->status['config'] = 'primary';
        $this->assign['status'] = $this->status;
        return $this->show();
	}

    /**
     * 安装数据库
     * @throws \think\Exception
     * Author: fudaoji<fdj@kuryun.cn>
     */
	public function sql() {
        set_time_limit(0);
        ob_end_clean();
        ob_implicit_flush(1);
		session('error', false);
		$this->status['index']  = 'success';
		$this->status['check']  = 'success';
		$this->status['config'] = 'success';
		$this->status['sql']    = 'primary';
		$this->assign('status', $this->status);
		$this->assign('app_name', config('app.app_name'));
		echo View::fetch('', $this->assign);

        //连接数据库
        $dbconfig = session('db_config');
        $db       = Db::connect();
        //创建数据库
        $dbname = $dbconfig['database'];
        try {
            if(! $db->query("SHOW DATABASES LIKE '{$dbname}'")){
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8mb4";
                $db->execute($sql);
            }
        }catch (\Exception $e){
            $this->error('创建数据库失败:' . $e->getMessage());
        }

        //创建数据表
        create_tables($db, $dbconfig['prefix']);
        //注册创始人帐号
        $admin = session('admin_info');
        register_administrator($db, $dbconfig['prefix'], $admin);

        lockFile();
		if (session('error')) {
			show_msg('安装失败-_-!');
		} else {
			echo '<script type="text/javascript">location.href = "'.url('complete').'";</script>';
		}
	}

    /**
     * 创建完成
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
	public function complete() {
		$this->status['index']    = 'success';
		$this->status['check']    = 'success';
		$this->status['config']   = 'success';
		$this->status['sql']      = 'success';
		$this->status['complete'] = 'primary';

		$this->assign('status', $this->status);
		return $this->show();
	}
}