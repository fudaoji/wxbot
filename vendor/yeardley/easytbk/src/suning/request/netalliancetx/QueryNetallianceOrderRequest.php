<?php
namespace YearDley\EasyTBK\SuNing\Request\Netalliancetx;

use YearDley\EasyTBK\SuNing\SelectSuningRequest;
use YearDley\EasyTBK\SuNing\RequestCheckUtil;

/**
 * 苏宁开放平台接口 - 网盟订单信息批量查询
 *
 * @author suning
 * @date   2014-10-16
 */
class QueryNetallianceOrderRequest extends SelectSuningRequest
{

    /**
     * 查询开始时间
     */
    private $startTime;

    /**
     * 查询结束时间
     */
    private $endTime;

    /**
     * 订单行项目状态
     */
    private $orderLineStatus;

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        $this->apiParams["startTime"] = $startTime;
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

    public function getApiMethodName()
    {
        return 'suning.netalliance.order.query';
    }

    public function getApiParams()
    {
        return $this->apiParams;
    }

    public function check($pageNoMin = 1, $pageNoMax = 99999, $pageSizeMin = 10, $pageSizeMax = 50)
    {

    }

    public function getBizName()
    {
        return "queryOrder";
    }

}

?>
