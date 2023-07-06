<?php

namespace YearDley\EasyTBK\JingDong\Request;

use YearDley\EasyTBK\JingDong\RequestInterface;


/**
 * Class JdUnionPositionCreateRequest
 * @package YearDley\EasyTBK\JingDong\Request
 */
class JdUnionPositionCreateRequest implements RequestInterface
{
    /**
     * 查询推广位【申请】
     * @url https://union.jd.com/#/openplatform/api/655
     * @var string
     */
    private $method = 'jd.union.open.position.create';

    /**
     * 推广位名称集合，英文,分割；上限50
     * @var
     */
    private $spaceNameList;

    /**
     * 联盟推广位类型，1：cps推广位 2：cpc推广位
     * @var
     */
    private $unionType;

    /**
     * 站点ID，即网站ID/app ID/snsID ,当type传入4以外的值时，siteId为必填
     * @var
     */
    private $siteId;


    /**
     * 需要查询的目标联盟id
     * @var
     */
    private $unionId;

    /**
     * 站点类型 1网站推广位2.APP推广位3.社交媒体推广位4.聊天工具推广位5.二维码推广
     * @var
     */
    private $type;

    /**
     * 目标联盟ID对应的授权key，在联盟推广管理页领取
     * @var
     */
    private $key;

    /**
     * @return mixed
     */
    public function getSpaceNameList()
    {
        return $this->spaceNameList;
    }

    /**
     * @param mixed $spaceNameList
     */
    public function setSpaceNameList($spaceNameList)
    {
        $this->spaceNameList = $spaceNameList;
    }

    /**
     * @return mixed
     */
    public function getUnionType()
    {
        return $this->unionType;
    }

    /**
     * @param mixed $unionType
     */
    public function setUnionType($unionType)
    {
        $this->unionType = $unionType;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param mixed $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * @return mixed
     */
    public function getUnionId()
    {
        return $this->unionId;
    }

    /**
     * @param mixed $unionId
     */
    public function setUnionId($unionId)
    {
        $this->unionId = $unionId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }


    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getParamJson()
    {
        $params = [
            'spaceNameList' => $this->spaceNameList,
            'unionType' => $this->unionType,
            'siteId' => $this->siteId,
            'unionId' => $this->unionId,
            'type' => $this->type,
            'key' => $this->key,
        ];

        return json_encode(['positionReq' => $params]);
    }


}
