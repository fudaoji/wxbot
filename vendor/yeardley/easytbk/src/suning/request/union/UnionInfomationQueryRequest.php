<?php
/**
 * 苏宁开放平台接口 - 批量查询联盟商品信息
 *
 * @author suning
 * @date   2015-10-28
 */

namespace YearDley\EasyTBK\SuNing\Request\Union;

use YearDley\EasyTBK\SuNing\SelectSuningRequest;

class UnionInfomationQueryRequest extends SelectSuningRequest
{

    public function getApiMethodName()
    {
        return 'suning.netalliance.unioninfomation.query';
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
        return "queryUnionInfomation";
    }

}

?>