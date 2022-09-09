<?php

namespace YearDley\EasyTBK\PinDuoDuo\Request;

use YearDley\EasyTBK\PinDuoDuo\RequestInterface;


class DdkGoodsPidQueryRequest implements RequestInterface
{
    /**
     * 查询已经生成的推广位信息
     * @var string
     */
    private $type = 'pdd.ddk.goods.pid.query';

    /**
     * 返回的页数
     * @var
     */
    private $page;

    /**
     * 返回的每页推广位数量
     * @var
     */
    private $pageSize;


    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getParams()
    {
        $params = [
            'type' => $this->type,
            'page' => $this->page,
            'page_size' => $this->pageSize
        ];
        return array_filter($params);
    }
}
