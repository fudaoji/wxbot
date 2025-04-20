<?php

/**
 * Created by PhpStorm.
 * Script Name: BaseModel.php
 * Create: 2020/2/29 下午11:37
 * Description:
 * Author: Doogie<461960962@qq.com>
 */

namespace ky;
use think\db\exception\DbException;
use think\facade\Env;
use think\Model;

abstract class BaseModel extends Model
{
    /**
     * 默认主键
     * @var string
     */
    protected $pk = 'id';
    /**
     * 数据表
     * @var string
     */
    //protected static $table = '';
    protected $autoWriteTimestamp = true;
    // 创建时间字段,无填null
    protected $createTime = 'create_time';
    // 更新时间字段,无填null
    protected $updateTime = 'update_time';

    /**
     * 是否缓存
     * @var bool
     */
    protected $isCache = false;
    /**
     * 缓存tag
     * @var string
     */
    protected $cacheTag = '';
    /**
     * 缓存时间
     * @var int
     */
    protected $expire = 3600;
    /**
     * 分表规则
     * @var array
     */
    protected $rule = [];
    /**
     * 分表/分区字段
     * @var string
     */
    protected $key = '';
    /**
     * 是否分区
     * @var array
     */
    protected $isPartition = false;

    protected $cachePrefix;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->cachePrefix = Env::get('database.hostname') . Env::get('database.database');
    }

    public function getTablePrefix(){
        return config('database.connections')[config('database.default')]['prefix'];
    }

    /**
     * 获取分组统计数据
     * @param array $params
     * @return mixed
     * e.g: model('activity')->totalGroup([
     * 'where' => ['a.id' => ['gt', 300]],
     * 'group' => 'activity.id',
     * 'having' => 'activity.id > 100',
     * 'refresh' => 1
     * ]);
     * @throws DbException
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function totalGroupJoin($params = []){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $having = empty($params['having']) ? '' : $params['having'];
        $group_field = empty($params['group']) ? '' : $params['group'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire);
        }
        if($having){
            $selector->having($having);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        $this->_where($selector, $where);
        return $selector->group($group_field)->count();
    }

    /**
     * sum join
     * @param array $params
     * @return float|Base
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function sumsJoin($params = []){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        $field = $params['field'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if(!empty($params['alias'])){
            $selector->alias($params['alias']);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        $this->_where($selector, $where);
        return $selector->sum($field);
    }

    /**
     *
     * @param array $params
     * @return array
     * @Author  Doogie<461960962@qq.com>
     */
    public function getFieldByOrder($params){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        $field = empty($params['field']) ? true : $params['field'];
        $order = empty($params['order']) ? [] : $params['order'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);

        if(count($field) == 2){
            $key = $field[0];
            unset($field[0]);
            $field = array_values($field);
        }elseif(count($field) > 2){
            $key = $field[0];
        }else{
            $key = '';
        }

        $selector = $this->getBuilder($where);
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        return $selector->order($order)->column($field, $key);
    }

    /**
     * 关联查询获取字段
     * @param array $params
     * @return array
     * @Author  Doogie<461960962@qq.com>
     */
    public function getFieldJoin($params){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        $field = empty($params['field']) ? true : $params['field'];
        $order = empty($params['order']) ? [] : $params['order'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);

        $selector = $this->getBuilder($where);
        if(!empty($params['alias'])){
            $selector->alias($params['alias']);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        return $selector->order($order)->column($field);
    }

    /**
     * 根据条件和排序获取单条数据
     * @param array $params
     * @return array|false|\PDOStatement|string|Model
     * @throws DbException
     * @Author: fudaoji <fdj@kuryun.cn>
     */
    public function getOneByOrder($params = []){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $field = empty($params['field']) ? true : $params['field'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        $this->_where($selector, $where);
        return $selector->order($order)->find();
    }

    /**
     * 根据条件删除数据
     * @param array $map
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @throws \Exception
     */
    public function delByMap($map = []){
        $selector = $this->getBuilder($map);
        $this->_where($selector, $map);
        return  $selector->delete();
    }

    /**
     * 设置临时缓存状态
     * @param bool $v
     * @return $this
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function setCache($v = false){
        $this->isCache = $v;
        return $this;
    }

    /**
     * 查询某个时间段的count统计
     * @param array $params
     * @return mixed
     * @Author  Doogie<461960962@qq.com>
     * @throws \Exception
     */
    public function totalByTime($params = []){
        ksort($params);
        if(empty($params['timeFields']) || !is_array($params['timeFields'])){
            exception('timeFields数据格式错误', 10006);
        }

        $where = empty($params['where']) ? [] : $params['where'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        $this->_where($selector, $where);
        foreach($params['timeFields'] as $item){
            $selector->whereTime($item[0], $item[1]);
        }
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->cacheTag);
        }
        return $selector->count();
    }

    /**
     * 根据主键获取单个数据
     * @param int $pk
     * @param int $refresh
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws DbException
     * @throws \think\db\exception\DbException
     * @Author  Doogie<461960962@qq.com>
     */
    public function getOne($pk = 0, $refresh = 0){
        $id = $this->getId($pk);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($pk) . $id);
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($pk)->field(true);

        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($pk));
        }
        $data = $selector->find($id);
        unset($cache_key);
        return $data;
    }

    /**
     * 新增单条数据
     * @param array $data
     * @return array
     * @Author  Doogie<461960962@qq.com>
     */
    public function addOne($data = []){
        if($this->autoWriteTimestamp){
            $this->createTime && empty($data[$this->createTime]) && $data[$this->createTime] = time();
            $this->updateTime && empty($data[$this->updateTime]) && $data[$this->updateTime] = time();
        }
        unset($data['__token__']);
        $data[$this->pk] = $this->getBuilder($data)->insertGetId($data);
        return  $data;
    }

    /**
     * 批量添加
     * @param array $arr
     * @return bool
     * @Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\Exception
     */
    public function addBatch($arr = []){
        foreach ($arr as &$data){
            if($this->autoWriteTimestamp){
                $this->createTime && empty($data[$this->createTime]) && $data[$this->createTime] = time();
                $this->updateTime && empty($data[$this->updateTime]) && $data[$this->updateTime] = time();
            }
        }
        return $this->getBuilder($arr[0])->insertAll($arr);
    }

    /**
     * 更新单条数据
     * @param array $data
     * @return bool|mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @Author  fudaoji<fdj@kuryun.cn>
     */
    public function updateOne($data = []){
        if(!isset($data[$this->pk])){
            return false;
        }
        if($this->autoWriteTimestamp){
            $this->updateTime && empty($data[$this->updateTime]) && $data[$this->updateTime] = time();
        }
        unset($data['__token__']);
        $res = $this->getBuilder($data)->update($data);
        if($res){
            $pk = $this->key ? [$this->key=>$data[$this->key], $this->pk => $data[$this->pk]] : $data[$this->pk];
            return $this->getOne($pk, 1);
        }
        return false;
    }

    /**
     * 根据条件更新数据
     * @param array $where
     * @param array $data
     * @return bool|mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author: fudaoji<fdj@kuryun.cn>
     */
    public function updateByMap($where = [], $data = []){
        if($this->autoWriteTimestamp){
            $this->updateTime && empty($data[$this->updateTime]) && $data[$this->updateTime] = time();
        }
        unset($data['__token__']);
        $selector = $this->getBuilder($where);
        $this->_where($selector, $where);
        return $selector->update($data);
    }

    /**
     * 根据主键删除单个数据
     * @param int $pk
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delOne($pk=0){
        $id = $this->getId($pk);
        return  $this->getBuilder($pk)->delete($id);
    }

    /**
     * 自带的分页
     * @param $where
     * @param $order
     * @param mixed $page_size
     * @param mixed $field
     * @param $refresh
     * @return mixed
     * @auth Doogie<461960962@qq.com>
     * @throws DbException
     */
    public function page($page_size=10, $where=[], $order=[], $field = true, $refresh=0){
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ .$current_page. serialize($page_size) . serialize($where) . serialize($order));
        $refresh && cache($cache_key, null);
        $data = cache($cache_key);
        if(empty($data)){
            //paginate不能和cache共用
            $selector = $this->getBuilder($where)->field($field);
            $this->_where($selector, $where);
            $data = $selector->order($order)->paginate($page_size);
            $this->isCache && cache($cache_key, $data,$this->expire);
        }
        return $data;
    }

    /**
     * 获取分页数据
     * @param array $limit
     * @param array $where
     * @param array $order
     * @param bool|true $field
     * @param int $refresh
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws DbException
     * @Author  Doogie<461960962@qq.com>
     */
    public function getList($limit = [], $where = [], $order = [], $field = true, $refresh = 0){
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($limit) . serialize($where) . serialize($order));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        $this->_where($selector, $where);
        return $selector->order($order)->page($limit[0], $limit[1])->select();
    }

    /**
     * count统计
     * @param array $where
     * @param int $refresh
     * @return mixed
     * @Author  Doogie<461960962@qq.com>
     * @throws DbException
     */
    public function total($where = [], $refresh = 0){
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($where));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        return $selector->count();
    }

    /**
     * 获取字段
     * @param string|array $field
     * @param array $query
     * @param int $refresh
     * @return array
     * @Author  Doogie<461960962@qq.com>
     */
    public function getField($field, $query = [],$refresh = 0){
        is_string($field) && $field = explode(',', str_replace(' ', '', $field));

        if(count($field) == 2){
            $key = $field[0];
            unset($field[0]);
            $field = array_values($field);
        }elseif(count($field) > 2){
            $key = $field[0];
        }else{
            $key = '';
        }
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($query) . __FUNCTION__ . serialize($field).':'.serialize($query));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($query);
        $this->_where($selector, $query);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($query));
        }
        $res = $selector->column($field, $key);
        return $key ? $res : array_values($res);
    }

    /**
     * 条件链接器
     * @param $self
     * @param array $where
     * eg1.
     * $map1 = [
    ['name', 'like', 'thinkphp%'],
    ['title', 'like', '%thinkphp'],
    ];

    $map2 = [
    ['name', 'like', 'kancloud%'],
    ['title', 'like', '%kancloud'],
    ];

    Db::table('think_user')
    ->whereOr([ $map1, $map2 ])
    ->select();
     *
     * eg2.
     * // 传入关联数组作为查询条件
    Db::table('think_user')->where([
    'name'	=>	'thinkphp',
    'status'=>	1
    ])->select();
     *
     * eg3.
     * // 传入索引数组作为查询条件
    Db::table('think_user')->where([
    ['name','=','thinkphp'],
    ['status','=',1]
    ])->select();
     *
     * eg4.
     * 使用字符串条件直接查询和操作，例如：
    Db::table('think_user')->whereRaw('type=1 AND status=1')->select();
     * @Author: Doogie <461960962@qq.com>
     * @return self
     */
    private function _where(&$self, $where=[]){
        if($where){
            if(isset($where['or'])){
                //高级查询: https://www.kancloud.cn/manual/thinkphp6_0/1037566
                $self->whereOr($where['or']);
                return  $self;
            }
            if(isset($where['sql'], $where['bind'])){
                $self->whereRaw($where['sql'], $where['bind']);
                return  $self;
            }elseif(isset($where['sql'])){
                $self->whereRaw($where['sql']);
                return  $self;
            }
            foreach ($where as $k => $v){
                if(is_string($k)){
                    if(is_array($v)){ //['age' => ['in', [1,2,3]]]
                        switch (count($v)){
                            case 3:
                                $self->where($k, $v[0], $v[1], $v[2]);
                                break;
                            default:
                                $self->where($k, $v[0], $v[1]);
                                break;
                        }

                    }else{//[id=>1, name=>'sss']
                        $self->where($k, '=', $v);
                    }
                }else{
                    $self->where($where); //[['id', '=', 1], ['name', '=', 'sss']]
                    break;
                }
            }
        }
        return $self;
    }

    /**
     * 根据条件获取数据
     * @param array $where
     * @param bool $field
     * @param int $refresh
     * @param array $order 排序获取
     * @return array|false|\PDOStatement|string|Model
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @Author: fudaoji <fdj@kuryun.cn>
     */
    public function getOneByMap($where=[], $field = true, $refresh = 0, $order = []){
        ksort($where);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($where) . serialize($order));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if($order){
            $selector->order($order);
        }
        return $selector->find();
    }

    /**
     * 获取builder,兼容分表和分区
     * @param array $query
     * @return mixed
     * @Author: fudaoji<461960962@qq.com>
     */
    public function getBuilder($query = []){
        if($this->isPartition){ //$this->getPartition 在特定模型中自行指定
            return $this->partition($this->getPartition($query));
        }else if($this->rule && $this->key){ //兼容之前tp6之前的分表
            $this->suffix = '_' . $this->getPartitionSuffix($query, $this->key, $this->rule);
        }
        return $this->table($this->getTable());
    }

    /**
     * 分表后缀
     * @param $data
     * @param $field
     * @param array $rule
     * @return false|float|int|mixed|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getPartitionSuffix($data, $field, $rule = []){
        $value = $data[$field];
        $type  = $rule['type'];
        switch ($type) {
            case 'id':
                // 按照id范围分表
                $step = $rule['expr'];
                $seq  = floor($value / $step) + 1;
                break;
            case 'year':
                // 按照年份分表
                if (!is_numeric($value)) {
                    $value = strtotime($value);
                }
                $seq = date('Y', $value) - $rule['expr'] + 1;
                break;
            case 'mod':
                // 按照id的模数分表
                $seq = ($value % $rule['num']) + 1;
                break;
            case 'md5':
                // 按照md5的序列分表
                $seq = (ord(substr(md5($value), 0, 1)) % $rule['num']) + 1;
                break;
            default:
                if (function_exists($type)) {
                    // 支持指定函数哈希
                    $value = $type($value);
                }

                $seq = (ord(substr($value, 0, 1)) % $rule['num']) + 1;
        }

        return $seq;
    }

    /**
     * 得到分表的的数据表名
     * @access public
     * @param  array  $data  操作的数据
     * @param  string $field 分表依据的字段
     * @param  array  $rule  分表规则
     * @return string
     */
    public function getPartitionTableName($data, $field, $rule = [])
    {
        // 对数据表进行分区
        if ($field && isset($data[$field])) {
            $value = $data[$field];
            $type  = $rule['type'];
            switch ($type) {
                case 'id':
                    // 按照id范围分表
                    $step = $rule['expr'];
                    $seq  = floor($value / $step) + 1;
                    break;
                case 'year':
                    // 按照年份分表
                    if (!is_numeric($value)) {
                        $value = strtotime($value);
                    }
                    $seq = date('Y', $value) - $rule['expr'] + 1;
                    break;
                case 'mod':
                    // 按照id的模数分表
                    $seq = ($value % $rule['num']) + 1;
                    break;
                case 'md5':
                    // 按照md5的序列分表
                    $seq = (ord(substr(md5($value), 0, 1)) % $rule['num']) + 1;
                    break;
                default:
                    if (function_exists($type)) {
                        // 支持指定函数哈希
                        $value = $type($value);
                    }

                    $seq = (ord(substr($value, 0, 1)) % $rule['num']) + 1;
            }

            return $this->getTable() . '_' . $seq;
        }
        // 当设置的分表字段不在查询条件或者数据中
        // 进行联合查询，必须设定 partition['num']
        $tableName = [];
        for ($i = 0; $i < $rule['num']; $i++) {
            $tableName[] = 'SELECT * FROM ' . $this->getTable() . '_' . ($i + 1);
        }

        return '( ' . implode(" UNION ", $tableName) . ' ) as ' . $this->name;
    }

    /**
     * 获取真正的表名
     * @param array $query
     * @return mixed
     * @Author: Doogie <461960962@qq.com>
     */
    public function getTrueTable($query = []){
        if(!$this->isPartition && $this->key && !empty($query[$this->key])){
            return $this->getPartitionTableName([$this->key => $query[$this->key]], $this->key, $this->rule);
        }else{
            return $this->getTable();
        }
    }

    /**
     * 获取真实id值
     * @param array $query
     * @return array
     * @Author: Doogie <461960962@qq.com>
     */
    public function getId($query = []){
        if($this->key && !empty($query[$this->key])){
            return $query[$this->pk];
        }else{
            return $query;
        }
    }

    /**
     * 根据条件获取数据
     * @param array $params
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws DbException
     * @author: Doogie<461960962@qq.com>
     */
    public function getAll($params){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $field = empty($params['field']) ? true : $params['field'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->cacheTag);
        }
        return $selector->order($order)->select();
    }

    /**
     *根据某个字段不重复获取数据
     * @param string $field 字段
     * @param array $where
     * @param int $refresh
     * @return mixed
     * @Author  Doogie<461960962@qq.com>
     */
    public function distinctField($field = '', $where = [], $refresh = 0){
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($field) . serialize($where));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire);
        }
        return $selector->distinct(true)->column($field);
    }

    /**
     * 获取group by结果列表
     * @param array $params 参数
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws DbException
     * @author: Doogie<461960962@qq.com>
     */
    public function getGroupList($params = []){
        if(empty($params['group_field'])){
            abort(10006, "缺少group_filed字段");
        }
        $group_field = $params['where'];
        $field = empty($params['field']) ? true : $params['field'];
        $limit = empty($params['limit']) ? [1,1] : $params['limit'];
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $having = empty($params['having']) ? '' : $params['having'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) .__FUNCTION__. serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if($having){
            $selector->having($having);
        }
        $this->_where($selector, $where);
        $data = $selector->group($group_field)->order($order)->page($limit[0], $limit[1])->select();
        return $data;
    }

    /**
     * sum求和
     * @param string $field
     * @param array $where
     * @param int $refresh
     * @return int $data
     * @author Jason<1589856452@qq.com>
     */
    public function sums($field = '', $where = [], $refresh = 0) {
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . $field . serialize($where));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        $this->_where($selector, $where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        return $selector->sum($field);
    }

    /**
     * 获取有联合查询的分页数据
     * @param array $params
     * @return mixed
     * e.g: model('activity')->getListJoin([
     * 'alias' => 'a',
     * 'join' => [[config('database.prefix').'user u', 'a.user_id=u.id', 'left']],
     * 'limit' => [1, 100],
     * 'where' => ['a.id' => ['gt', 300]],
     * 'field' => 'u.username,a.id as activity_id',
     * 'order' => ['a.id' => 'desc']
     * ]);
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws DbException
     * @Author  Doogie<461960962@qq.com>
     */
    public function getListJoin($params = []){
        $limit = $params['limit'];
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $field = empty($params['field']) ? true : $params['field'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if(!empty($params['alias'])){
            $selector->alias($params['alias']);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        $this->_where($selector, $where);
        return $selector->order($order)->page($limit[0], $limit[1])->select();
    }

    /**
     * 获取关联查询所有数据
     * @param array $params
     * @return mixed
     * e.g: model('activity')->getAllJoin([
     * 'alias' => 'a',
     * 'join' => [[config('database.prefix').'user u', 'a.user_id=u.id', 'left']],
     * 'where' => ['a.id' => ['gt', 300]],
     * 'field' => 'u.username,a.id as activity_id',
     * 'order' => ['a.id' => 'desc']
     * ]);
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws DbException
     * @author Jason<dcq@kuryun.cn>
     */
    public function getAllJoin($params = []){
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $field = empty($params['field']) ? true : $params['field'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if(!empty($params['alias'])){
            $selector->alias($params['alias']);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        $this->_where($selector, $where);
        return $selector->order($order)->select();
    }

    /**
     * 获取多表关联统计数据
     * @param array $params
     * @return mixed
     * e.g: model('activity')->totalJoin([
     * 'alias' => 'a',
     * 'join' => [[config('database.prefix').'user u', 'a.user_id=u.id', 'left']],
     * 'where' => ['a.id' => ['gt', 300]]
     * ]);
     * @throws DbException
     * @author Jason<dcq@kuryun.cn>
     */
    public function totalJoin($params = []){
        ksort($params);
        $where = empty($params['where']) ? [] : $params['where'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        $having = empty($params['having']) ? '' : $params['having'];
        $group_field = empty($params['group']) ? '' : $params['group'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if(!empty($params['alias'])){
            $selector->alias($params['alias']);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        if($having){
            $selector->having($having);
        }
        if($group_field){
            $selector->group($group_field);
        }
        $this->_where($selector, $where);
        return $selector->count();
    }

    /**
     * @param $self
     * @param $join
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function buildJoin(&$self, $join){
        // 如果为组数，则循环调用join
        foreach ($join as $key => $value) {
            if (is_array($value) && 2 <= count($value)) {
                $self->join($value[0], $value[1], isset($value[2]) ? $value[2] : 'INNER');
            }
        }
        return $self;
    }

    /**
     * 获取有联合查询的分页数据
     * @param array $params
     * @return mixed
     * e.g: model('activity')->pageJoin([
     * 'alias' => 'a',
     * 'join' => [[config('database.prefix').'user u', 'a.user_id=u.id', 'left']],
     * 'page_size' => 20,
     * 'where' => ['a.id' => ['gt', 300]],
     * 'field' => 'u.username,a.id as activity_id',
     * 'order' => ['a.id' => 'desc']
     * ]);
     * @Author  Doogie<461960962@qq.com>
     * @throws DbException
     */
    public function pageJoin($params = []){
        $page_size = $params['page_size'];
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $field = empty($params['field']) ? true : $params['field'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) .__FUNCTION__. serialize($params));
        $refresh && cache($cache_key, null);
        $data = cache($cache_key);
        if(empty($data)){
            $selector = $this->getBuilder($where)->field($field);
            if(!empty($params['alias'])){
                $selector->alias($params['alias']);
            }
            if(!empty($params['join'])){
                $this->buildJoin($selector, $params['join']);
            }
            $this->_where($selector, $where);
            $data = $selector->order($order)->paginate($page_size);
        }
        return $data;
    }

    /**
     * 获取group by结果
     * @param array $params 参数
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws DbException
     * @author: Doogie<461960962@qq.com>
     */
    public function getGroupAll($params = []){
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        $field = empty($params['field']) ? true : $params['field'];
        $where = empty($params['where']) ? [] : $params['where'];
        $order = empty($params['order']) ? [] : $params['order'];
        $having = empty($params['having']) ? '' : $params['having'];
        $group_field = empty($params['group']) ? '' : $params['group'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);

        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if($having){
            $selector->having($having);
        }
        $this->_where($selector, $where);
        return $selector->group($group_field)->order($order)->select();
    }

    /**
     * 获取单条数据的关联查询
     * @param array $params
     * @return mixed
     * e.g: model('user')->getOneJoin([
     * 'alias' => 'u',
     * 'join' => [['profile p', 'p.user_id=u.id']],
     * 'where' => ['u.id' => 1]
     * ]);
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws DbException
     * @author fudaoji<fdj@kuryun.cn>
     */
    public function getOneJoin($params = []){
        $where = empty($params['where']) ? [] : $params['where'];
        $refresh = empty($params['refresh']) ? 0 : $params['refresh'];
        $field = empty($params['field']) ? true : $params['field'];
        unset($params['refresh']);
        $cache_key = md5($this->cachePrefix . $this->getTrueTable($where) . __FUNCTION__ . serialize($params));
        $refresh && cache($cache_key, null);
        $selector = $this->getBuilder($where)->field($field);
        if($this->isCache){
            $selector->cache($cache_key, $this->expire, $this->getTrueTable($where));
        }
        if(!empty($params['alias'])){
            $selector->alias($params['alias']);
        }
        if(!empty($params['join'])){
            $this->buildJoin($selector, $params['join']);
        }
        $this->_where($selector, $where);
        return $selector->find();
    }
}
