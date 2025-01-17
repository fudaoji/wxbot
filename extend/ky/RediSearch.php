<?php
/**
 * Created by PhpStorm.
 * Script Name: RediSearch.php
 * Create: 2025/1/16 14:30
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;
use Ehann\RediSearch\Document\DocumentInterface;
use Ehann\RediSearch\Fields\GeoLocation;
use Ehann\RedisRaw\PhpRedisAdapter;
use Ehann\RediSearch\Index;
use Ehann\RediSearch\Language;

class RediSearch
{
    const TYPE_TEXT = 'text';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_GEO = 'geo';
    const TYPE_TAG = 'tag';

    /**
     * @var \Ehann\RedisRaw\RedisRawClientInterface
     */
    protected $redis;
    protected $indexName = '';
    /**
     * @var Index
     */
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
        $this->redis = (new PhpRedisAdapter())->connect($this->host, $this->port, $this->db, $this->password);
        $this->scheme = (new Index($this->redis, $this->indexName))->language('chinese');
        $this->setScheme();
    }

    function setScheme(){
        foreach ($this->fields as $field){
            switch ($field['type']){
                case self::TYPE_NUMERIC:
                    $this->scheme = $this->scheme->addNumericField($field['name']);
                    break;
                case self::TYPE_GEO:
                    $this->scheme = $this->scheme->addGeoField($field['name'], new GeoLocation($field['options'][0], $field['options'][1]));
                    break;
                case self::TYPE_TAG:
                    $this->scheme = $this->scheme->addGeoField($field['name'], $field['options']);
                    break;
                default:
                    $this->scheme = $this->scheme->addTextField($field['name']);
                    break;
            }
        }
        try {
            $this->scheme->create();
        }catch (\Exception $e){
            //var_dump("索引'{$this->>indexName}' 已存在！");
        }
    }

    function delDocument($id){
        return $this->scheme->delete($id);
    }

    function page($params = []){
        $limit = $params['limit'] ?? null;
        $order = $params['order'] ?? null;
        $search = $params['search_key'] ?? '*';
        if($limit){
            $this->scheme = $this->scheme->limit(max(0,($limit[0] - 1)) * $limit[1], $limit[1]);
        }
        if($order){
            $this->scheme = $this->scheme->sortBy($order[0], $order[1]);
        }
        return $this->scheme->search($search);
    }

    function addDocument($data){
        $this->scheme->add($data);
        return $data;
    }

    function updateDocument($document, $data){
        $new_document = $this->scheme->makeDocument($document->id);
        foreach ($this->fields as $field){
            $new_document->$field['name']->setValue($data[$field['name']] ?? $document->$field['name']);
        }
        $this->scheme->replace($new_document);
        return $new_document;
    }

    function dropScheme(){
        return $this->scheme->drop();
    }
}