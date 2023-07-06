<?php
namespace YearDley\EasyTBK\SuNing\Request\Netalliance;

use YearDley\EasyTBK\SuNing\SelectSuningRequest;
use YearDley\EasyTBK\SuNing\RequestCheckUtil;

/**
 * 苏宁开放平台接口 -
 *
 * @author suning
 * @date   2019-10-28
 */
class TwittersuccessQueryRequest extends SelectSuningRequest
{

    /**
     *
     */
    private $beginDate;

    /**
     *
     */
    private $endDate;


    public function getBeginDate()
    {
        return $this->beginDate;
    }

    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;
        $this->apiParams["beginDate"] = $beginDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        $this->apiParams["endDate"] = $endDate;
    }


    public function getApiMethodName()
    {
        return 'suning.netalliance.twittersuccess.query';
    }

    public function getApiParams()
    {
        return $this->apiParams;
    }

    public function check($pageNoMin = 1, $pageNoMax = 99999, $pageSizeMin = 10, $pageSizeMax = 50)
    {
        RequestCheckUtil::checkNotNull($this->beginDate, 'beginDate');
        RequestCheckUtil::checkNotNull($this->endDate, 'endDate');
        RequestCheckUtil::checkNotNull($this->pageNo, 'pageNo');
        RequestCheckUtil::checkNotNull($this->pageSize, 'pageSize');
    }

    public function getBizName()
    {
        return "queryTwittersuccess";
    }

}

?>
