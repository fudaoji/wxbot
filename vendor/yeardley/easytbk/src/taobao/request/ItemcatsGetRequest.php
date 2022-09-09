<?php


namespace YearDley\EasyTBK\TaoBao\Request;


class ItemcatsGetRequest
{

    private $apiParas = array();

    private $cids = [];

    private $fields = [];

    private $parent_cid = 0;

    /**
     * @param array $cids
     */
    public function setCids(array $cids)
    {
        $this->cids = $cids;
        $this->apiParas['cids'] = json_encode($this->cids);
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        $this->apiParas['fields'] = json_encode($this->fields);
    }

    /**
     * @param int $parent_cid
     */
    public function setParentCid(int $parent_cid)
    {
        $this->parent_cid = $parent_cid;
        $this->apiParas['parent_cid'] = $this->parent_cid;
    }

    /**
     * @return array
     */
    public function getApiParas(): array
    {
        return $this->apiParas;
    }


    public function getApiMethodName()
    {
        return "taobao.itemcats.get";
    }

    public function check()
    {

    }

}