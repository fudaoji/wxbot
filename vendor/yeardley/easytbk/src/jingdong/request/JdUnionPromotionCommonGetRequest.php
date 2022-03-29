<?php

namespace YearDley\EasyTBK\JingDong\Request;

use YearDley\EasyTBK\JingDong\RequestInterface;


/**
 * Class JdUnionPromotionCommonGetRequest
 * @package YearDley\EasyTBK\JingDong\Request
 */
class JdUnionPromotionCommonGetRequest implements RequestInterface
{
    /**
     * 获取通用推广链接
     * @url https://union.jd.com/#/openplatform/api/10421
     * @var string
     */
    private $method = 'jd.union.open.promotion.common.get';

    /**
     * 子联盟ID
     * @var
     */
    private $subUnionId;

    /**
     * 请勿再使用，后续会移除此参数，请求成功一律返回https协议链接
     * @var
     */
    private $protocol;

    /**
     * 扩展字段
     * @var
     */
    private $ext1;

    /**
     * 站点id
     * @var
     */
    private $siteId;

    /**
     * 推广物料
     * @var
     */
    private $materialId;

    /**
     * 推广位id
     * @var
     */
    private $positionId;

    /**
     * 联盟子站长身份标识，格式：子站长ID_子站长网站ID_子站长推广位ID
     * @var
     */
    private $pid;

    /**
     * 优惠券领取链接
     * @var
     */
    private $couponUrl;

    /**
     * 礼金批次号
     * @var
     */
    private $giftCouponKey;

    /**
     * @return mixed
     */
    public function getSubUnionId()
    {
        return $this->subUnionId;
    }

    /**
     * @param mixed $subUnionId
     */
    public function setSubUnionId($subUnionId)
    {
        $this->subUnionId = $subUnionId;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param mixed $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getExt1()
    {
        return $this->ext1;
    }

    /**
     * @param mixed $ext1
     */
    public function setExt1($ext1)
    {
        $this->ext1 = $ext1;
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
    public function getMaterialId()
    {
        return $this->materialId;
    }

    /**
     * @param mixed $materialId
     */
    public function setMaterialId($materialId)
    {
        $this->materialId = $materialId;
    }

    /**
     * @return mixed
     */
    public function getPositionId()
    {
        return $this->positionId;
    }

    /**
     * @param mixed $positionId
     */
    public function setPositionId($positionId)
    {
        $this->positionId = $positionId;
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param mixed $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getCouponUrl()
    {
        return $this->couponUrl;
    }

    /**
     * @param mixed $couponUrl
     */
    public function setCouponUrl($couponUrl)
    {
        $this->couponUrl = $couponUrl;
    }

    /**
     * @return mixed
     */
    public function getGiftCouponKey()
    {
        return $this->giftCouponKey;
    }

    /**
     * @param mixed $giftCouponKey
     */
    public function setGiftCouponKey($giftCouponKey)
    {
        $this->giftCouponKey = $giftCouponKey;
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
            'subUnionId' => $this->subUnionId,
            'protocol' => $this->protocol,
            'ext1' => $this->ext1,
            'siteId' => $this->siteId,
            'materialId' => $this->materialId,
            'positionId' => $this->positionId,
            'pid' => $this->pid,
            'couponUrl' => $this->couponUrl,
            'giftCouponKey' => $this->giftCouponKey
        ];

        return json_encode(['promotionCodeReq' => $params]);
    }


}