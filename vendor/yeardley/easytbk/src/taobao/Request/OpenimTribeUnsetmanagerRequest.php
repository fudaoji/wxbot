<?php

namespace YearDley\EasyTBK\TaoBao\Request;

use YearDley\EasyTBK\TaoBao\RequestCheckUtil;
/**
 * TOP API: taobao.openim.tribe.unsetmanager request
 *
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class OpenimTribeUnsetmanagerRequest
{
    /**
     * 用户信息
     **/
    private $member;

    /**
     * 群id
     **/
    private $tid;

    /**
     * 用户信息
     **/
    private $user;

    private $apiParas = array();

    public function setMember($member)
    {
        $this->member = $member;
        $this->apiParas["member"] = $member;
    }

    public function getMember()
    {
        return $this->member;
    }

    public function setTid($tid)
    {
        $this->tid = $tid;
        $this->apiParas["tid"] = $tid;
    }

    public function getTid()
    {
        return $this->tid;
    }

    public function setUser($user)
    {
        $this->user = $user;
        $this->apiParas["user"] = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getApiMethodName()
    {
        return "taobao.openim.tribe.unsetmanager";
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function check()
    {

        RequestCheckUtil::checkNotNull ($this->tid, "tid");
    }

    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }
}
