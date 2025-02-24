<?php
/**
 * Created by PhpStorm.
 * Script Name: RediSearch.php
 * Create: 2025/1/16 14:30
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;
use Predis\Client as PredisClient;
use Predis\Command\Argument\Search\AggregateArguments;
use Predis\Command\Argument\Search\CreateArguments;
use Predis\Command\Argument\Search\SchemaFields\GeoField;
use Predis\Command\Argument\Search\SearchArguments;
use Predis\Command\Argument\Search\SchemaFields\NumericField;
use Predis\Command\Argument\Search\SchemaFields\TextField;
use Predis\Command\Argument\Search\SchemaFields\TagField;
use Predis\Command\Argument\Search\SchemaFields\VectorField;

class Predis
{
    const TYPE_TEXT = 'text';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_GEO = 'geo';
    const TYPE_TAG = 'tag';
    const TYPE_VECTOR = 'vector';

    /**
     * @var PredisClient
     */
    protected $redis;
    protected $indexName = '';
    protected $indexPrefix = '';
    protected $indexType = 'HASH';
    protected $lastIdSuffix = 'lastid';
    protected $language = 'english';

    protected $scheme;

    static $instance = null;
    protected $fields = [];
    protected $host = '127.0.0.1';
    protected $port = '6379';
    protected $db = 0;
    protected $password = null;

    public function __construct($host = null, $port = null,$db = null, $password = null) {
        ! is_null($host) && $this->host = $host;
        ! is_null($port) && $this->port = $port;
        ! is_null($db) && $this->db = $db;
        ! empty($password) && $this->password = $password;

        /** 创建Redis客户端 */
        $this->redis = new PredisClient([
            'scheme'   => 'tcp',
            'host'     => $this->host,
            'port'     => $this->port,
            'password' => $this->password,
            'database' => $this->db,
            /*'options' => [
                'ssl' => [
                    'verify_peer' => true, // Verify the server's SSL certificate
                    'cafile' => './redis_ca.pem', // Path to CA certificate
                    'local_cert' => './redis_user.crt', // Path to client certificate
                    'local_pk' => './redis_user_private.key', // Path to client private key
                ],
            ],*/
        ]);
        $this->setSchema();
    }

    function setSchema(){
        $schema = [];
        foreach ($this->fields as $field){
            switch ($field['type']){
                case self::TYPE_VECTOR:
                    $schema[] = new VectorField($field['name'], $field['algorithm'], $field['options']);
                    break;
                case self::TYPE_NUMERIC:
                    $schema[] = new NumericField($field['name']);
                    break;
                case self::TYPE_GEO:
                    $schema[] = new GeoField($field['name']);
                    break;
                case self::TYPE_TAG:
                    $schema[] = new TagField($field['name']);
                    break;
                default:
                    $schema[] = new TextField($field['name']);
                    break;
            }
        }

        try {
            //只有以user:开头的json数据才会被索引
            $this->redis->ftCreate($this->indexName, $schema,
                (new CreateArguments())
                    ->on($this->indexType)
                    ->language($this->language)
                    ->prefix([$this->indexPrefix]));
        } catch (\Exception $e) {
            //echo $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * 删除数据
     * @param $id
     * @return int
     * Author: fudaoji<fdj@kuryun.cn>
     */
    function delDocument($id){
        if(!is_array($id)){
            $id = array($id);
        }
        foreach ($id as $_id){
            $fields = $this->redis->hkeys($_id);
            foreach ($fields as $field){
                $this->redis->hdel($_id, $field);
            }
        }
        return count($id);
    }

    function page($params = []){
        $limit = $params['limit'] ?? null;
        $order = $params['order'] ?? null;
        $tag_filter = $params['tag_filter'] ?? null;
        $numeric_filter = $params['numeric_filter'] ?? null;
        $geo_filter = $params['geo_filter'] ?? null;
        $fields = $params['fields'] ?? null;


        $arguments = new SearchArguments();
        if($fields){
            $fields = is_array($fields) ? $fields : explode(',', $fields);
            $arguments->addReturn(count($fields), ...$fields);
        }

        if(isset($params['vector_search'])){
            $search = $params['vector_search']['query'];
            $arguments->params($params['vector_search']['params']);
        }else{
            $search = $params['search_key'] ?? '*';
        }
        if(!empty($params['language'])){
            $arguments->language($params['language']);
        }
        if(isset($params['dialect'])){
            $arguments->dialect($params['dialect']);
        }
        if($order){
            $arguments->sortBy($order[0], $order[1]);
        }
        if($limit){
            $arguments->limit(max(0,($limit[0] - 1)) * $limit[1], $limit[1]);
        }

        $res = $this->redis->ftsearch(
            $this->indexName,
            $search,
            $arguments
        );
        //return $res;
        $total = $res[0];
        $list = [];
        if($total > 0){
            $res = array_slice($res, 1, count($res) - 1);
            for($i = 0; $i<count($res); $i= $i+2){
                $item['id'] = $res[$i];
                for($j = 0; $j< (count($res[$i+1])/2); $j++){
                    $item[$res[$i+1][2*$j]] = $res[$i+1][2*$j+1];
                }
                $list[] = $item;
            }
        }
        //return $res;
        return ['total' => $total, 'list' => $list];
    }

    function addDocument($data){
        $this->redis->hmset($this->indexPrefix . $this->setLastId(), $data);
        return $data;
    }

    function updateDocument($key, $data){
        $this->redis->hmset($key, $data);
        return $data;
    }

    function getDocument($key, $fields = []){
        $res = $this->redis->hmget($key, $fields);
        return array_combine($fields, $res);
    }

    function dropScheme(){
        return $this->redis->ftdropindex($this->indexName);
    }

    function getLastId(){
        return $this->redis->get($this->indexName.$this->lastIdSuffix);
    }

    function setLastId(){
        return $this->redis->incr($this->indexName.$this->lastIdSuffix);
    }

    function schemaInfo(){
        return $this->redis->ftinfo($this->indexName);
    }
}