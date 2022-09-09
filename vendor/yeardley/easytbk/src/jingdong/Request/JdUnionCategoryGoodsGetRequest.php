<?php

namespace YearDley\EasyTBK\JingDong\Request;

use YearDley\EasyTBK\JingDong\RequestInterface;


/**
 * Class JdUnionCategoryGoodsGetRequest
 * @package YearDley\EasyTBK\JingDong\Request
 */
class JdUnionCategoryGoodsGetRequest implements RequestInterface
{
    /**
     * 商品类目查询
     * @url https://union.jd.com/#/openplatform/api/693
     * @var string
     */
    private $method = 'jd.union.open.category.goods.get';

    /**
     * 父类目id(一级父类目为0)
     * @var
     */
    private $parentId = 0;

    /**
     * 类目级别(类目级别 0，1，2 代表一、二、三级类目)
     * @var
     */
    private $grade = 0;


    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getParamJson()
    {
        $params = [
            'parentId' => $this->parentId,
            'grade' => $this->grade
        ];

        return json_encode(['req' => $params]);
    }


}