<?php
namespace YearDley\EasyTBK\SuNing\Request\Netalliance;

use YearDley\EasyTBK\SuNing\SelectSuningRequest;
use YearDley\EasyTBK\SuNing\RequestCheckUtil;

/**
 * 苏宁开放平台接口 -
 *
 * @author suning
 * @date   2019-5-29
 */
class LeaderorderQueryRequest extends SelectSuningRequest
{

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
        return 'suning.netalliance.leaderorder.query';
    }

    public function getApiParams()
    {
        return $this->apiParams;
    }

    public function check($pageNoMin = 1, $pageNoMax = 99999, $pageSizeMin = 10, $pageSizeMax = 50)
    {
        RequestCheckUtil::checkNotNull($this->endTime, 'endTime');
        RequestCheckUtil::checkNotNull($this->orderLineStatus, 'orderLineStatus');
        RequestCheckUtil::checkNotNull($this->pageNo, 'pageNo');
        RequestCheckUtil::checkNotNull($this->pageSize, 'pageSize');
        RequestCheckUtil::checkNotNull($this->startTime, 'startTime');
    }

    public function getBizName()
    {
        return "queryLeaderorder";
    }

}

?>
