<?php
/**
 * Created by PhpStorm.
 * Script Name: Gather.php
 * Create: 2022/3/29 15:52
 * Description: 采品群
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


class Forward extends Base
{
    protected $isCache = true;

    /**
     * 获取转播数据
     * @param array $params
     * @return mixed
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     */
    public function getGather($params = []){
        $refresh = isset($params['refresh']) ? $params['refresh'] : 0;
        unset($params['refresh']);
        $cache_key = md5(__CLASS__.__FUNCTION__.serialize($params));
        $data = cache($cache_key);

        if(is_null($data) || $refresh){
            $group_wxid = $params['group_wxid'];
            $bot_wxid = $params['bot_wxid'];
            $from_wxid = $params['from_wxid'];

            $where = ['f.status' => 1, 'f.officer' => $from_wxid, 'bot.uin' => $bot_wxid];
            if($group_wxid){
                $where['g.wxid'] = $group_wxid;
            }else{
                $where['f.group_id'] = 0;
            }

            $data = $this->getOneJoin([
                'alias' => 'f',
                'join' => [
                    ['bot', 'bot.id=f.bot_id'],
                    ['bot_member g', 'g.id=f.group_id', 'left']
                ],
                'where' => $where,
                'refresh' => $refresh,
                'field' => 'f.*'
            ]);
            if($data){
                if(empty($data['wxids'])){
                    $tags = explode(',', $data['member_tags']);
                    $wxids = [];
                    foreach ($tags as $tag){
                        $wxids = array_merge($wxids, model('admin/botMember')->getField('wxid', ['tags' => ['like', '%'.$tag.'%']]));
                    }
                }else{
                    $wxids = explode(',', $data['wxids']);
                }
                $wxids = array_unique($wxids);
                $data['wxids'] = implode(',', $wxids);
            }
        }
        cache($cache_key, is_null($data) ? [] : $data);
        return $data;
    }

    /**
     * 修改数据后回调
     * @param \think\Model $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public static function onAfterUpdate($data){
        $res = self::find($data['id']);
        if(!empty($res['group_id'])){
            $group = model('admin/botMember')->getOne($res['group_id']);
            $group_wxid = $group['wxid'];
        }else{
            $group_wxid = '';
        }
        $bot = model('admin/bot')->getOne($res['bot_id']);
        (new self())->getGather([
            'group_wxid' => $group_wxid,
            'from_wxid' => $res['officer'],
            'bot_wxid' => $bot['uin'] ?? '',
            'refresh' => true
        ]);
    }
}