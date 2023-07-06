<?php

namespace YearDley\EasyTBK\JingDong\Request;

use YearDley\EasyTBK\JingDong\RequestInterface;


/**
 * Class JdUnionUserPidGetRequest
 * @package YearDley\EasyTBK\JingDong\Request
 */
class JdUnionUserPidGetRequest implements RequestInterface
{
    /**
     *  获取PID
     * @url https://union.jd.com/#/openplatform/api/646
     * @var string
     */
    private $method = 'jd.union.open.user.pid.get';

    /**
     * 推广类型,1APP推广 2聊天工具推广
     * @var
     */
    private $promotionType;

    /**
     * 媒体名称，即子站长的app应用名称，推广方式为app推广时必填，且app名称必须为已存在的app名称
     * @var
     */
    private $mediaName;

    /**
     * 子站长ID
     * @var
     */
    private $childUnionId;

    /**
     * 联盟ID
     * @var
     */
    private $unionId;

    /**
     * 子站长的推广位名称，如不存在则创建，不填则由联盟根据母账号信息创建
     * @var
     */
    private $positionName;

    /**
     * @return mixed
     */
    public function getPromotionType()
    {
        return $this->promotionType;
    }

    /**
     * @param mixed $promotionType
     */
    public function setPromotionType($promotionType)
    {
        $this->promotionType = $promotionType;
    }

    /**
     * @return mixed
     */
    public function getMediaName()
    {
        return $this->mediaName;
    }

    /**
     * @param mixed $mediaName
     */
    public function setMediaName($mediaName)
    {
        $this->mediaName = $mediaName;
    }

    /**
     * @return mixed
     */
    public function getChildUnionId()
    {
        return $this->childUnionId;
    }

    /**
     * @param mixed $childUnionId
     */
    public function setChildUnionId($childUnionId)
    {
        $this->childUnionId = $childUnionId;
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
    public function getPositionName()
    {
        return $this->positionName;
    }

    /**
     * @param mixed $positionName
     */
    public function setPositionName($positionName)
    {
        $this->positionName = $positionName;
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
            'promotionType' => $this->promotionType,
            'mediaName' => $this->mediaName,
            'childUnionId' => $this->childUnionId,
            'unionId' => $this->unionId,
            'positionName' => $this->positionName
        ];

        return json_encode(['pidReq' => $params]);
    }


}