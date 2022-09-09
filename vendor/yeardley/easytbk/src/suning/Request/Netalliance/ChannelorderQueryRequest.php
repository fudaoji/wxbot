<?php
namespace YearDley\EasyTBK\SuNing\Request\Netalliance;

use YearDley\EasyTBK\SuNing\SelectSuningRequest;
use YearDley\EasyTBK\SuNing\RequestCheckUtil;

/**
 * 苏宁开放平台接口 -
 *
 * @author suning
 * @date   2019-8-1
 */
class ChannelorderQueryRequest extends SelectSuningRequest
{

    /**
     *
     */
    private $channelCode;

    /**
     *
     */
    private $endTime;

    /**
     *
     */
    private $orderLineStatus;


    /**
     *
     */
    private $startTime;

    public function getChannelCode()
    {
        return $this->channelCode;
    }

    public function setChannelCode($channelCode)
    {
        $this->channelCode = $channelCode;
        $this->apiParams["channelCode"] = $channelCode;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
        $this->apiParams["endTime"] = $endTime;
    }

    public function getOrderLineStatus()
    {
        return $this->orderLineStatus;
    }

    public function setOrderLineStatus($orderLineStatus)
    {
        $this->orderLineStatus = $orderLineStatus;
        $this->apiParams["orderLineStatus"] = $orderLineStatus;
    }


    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        $this->apiParams["startTime"] = $startTime;
    }

    public function getApiMethodName()
    {
        return 'suning.netalliance.channelorder.query';
    }

    public function getApiParams()
    {
        return $this->apiParams;
    }

    public function check($pageNoMin = 1, $pageNoMax = 99999, $pageSizeMin = 10, $pageSizeMax = 50)
    {
        RequestCheckUtil::checkNotNull($this->channelCode, 'channelCode');
        RequestCheckUtil::checkNotNull($this->endTime, 'endTime');
        RequestCheckUtil::checkNotNull($this->orderLineStatus, 'orderLineStatus');
        RequestCheckUtil::checkNotNull($this->startTime, 'startTime');
    }

    public function getBizName()
    {
        return "queryChannelorder";
    }

}

?>
