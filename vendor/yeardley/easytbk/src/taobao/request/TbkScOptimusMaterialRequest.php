<?php

namespace YearDley\EasyTBK\TaoBao\Request;

use YearDley\EasyTBK\TaoBao\RequestCheckUtil;
/**
 * TOP API: taobao.tbk.sc.optimus.material request
 *
 * @author auto create
 * @since 1.0, 2018.09.13
 */
class TbkScOptimusMaterialRequest
{
    /**
     * mm_xxx_xxx_xxx的第三位
     **/
    private $adzoneId;

    /**
     * 内容详情ID
     **/
    private $contentId;

    /**
     * 内容渠道信息
     **/
    private $contentSource;

    /**
     * 设备号加密类型：MD5
     **/
    private $deviceEncrypt;

    /**
     * 设备号加密后的值
     **/
    private $deviceType;

    /**
     * 设备号类型：IMEI，或者IDFA，或者UTDID
     **/
    private $deviceValue;

    /**
     * 官方的物料Id(详细物料id见：https://tbk.bbs.taobao.com/detail.html?appId=45301&postId=8576096)
     **/
    private $materialId;

    /**
     * 第几页，默认：1
     **/
    private $pageNo;

    /**
     * 页大小，默认20，1~100
     **/
    private $pageSize;

    /**
     * mm_xxx_xxx_xxx的第二位
     **/
    private $siteId;

    private $apiParas = array();

    public function setAdzoneId($adzoneId)
    {
        $this->adzoneId = $adzoneId;
        $this->apiParas["adzone_id"] = $adzoneId;
    }

    public function getAdzoneId()
    {
        return $this->adzoneId;
    }

    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
        $this->apiParas["content_id"] = $contentId;
    }

    public function getContentId()
    {
        return $this->contentId;
    }

    public function setContentSource($contentSource)
    {
        $this->contentSource = $contentSource;
        $this->apiParas["content_source"] = $contentSource;
    }

    public function getContentSource()
    {
        return $this->contentSource;
    }

    public function setDeviceEncrypt($deviceEncrypt)
    {
        $this->deviceEncrypt = $deviceEncrypt;
        $this->apiParas["device_encrypt"] = $deviceEncrypt;
    }

    public function getDeviceEncrypt()
    {
        return $this->deviceEncrypt;
    }

    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
        $this->apiParas["device_type"] = $deviceType;
    }

    public function getDeviceType()
    {
        return $this->deviceType;
    }

    public function setDeviceValue($deviceValue)
    {
        $this->deviceValue = $deviceValue;
        $this->apiParas["device_value"] = $deviceValue;
    }

    public function getDeviceValue()
    {
        return $this->deviceValue;
    }

    public function setMaterialId($materialId)
    {
        $this->materialId = $materialId;
        $this->apiParas["material_id"] = $materialId;
    }

    public function getMaterialId()
    {
        return $this->materialId;
    }

    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
        $this->apiParas["page_no"] = $pageNo;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        $this->apiParas["page_size"] = $pageSize;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        $this->apiParas["site_id"] = $siteId;
    }

    public function getSiteId()
    {
        return $this->siteId;
    }

    public function getApiMethodName()
    {
        return "taobao.tbk.sc.optimus.material";
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function check()
    {

        RequestCheckUtil::checkNotNull ($this->adzoneId, "adzoneId");
        RequestCheckUtil::checkMaxValue ($this->pageSize, 100, "pageSize");
        RequestCheckUtil::checkMinValue ($this->pageSize, 1, "pageSize");
        RequestCheckUtil::checkNotNull ($this->siteId, "siteId");
    }

    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }
}
