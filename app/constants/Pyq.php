<?php
/**
 * Created by PhpStorm.
 * Script Name: Pyq.php
 * Create: 9/17/22 9:41 AM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\constants;

use app\common\model\EmojiCode;
use ky\Logger;

class Pyq
{
    const TYPE_TEXT = 0; //文本
    const TYPE_IMG = 1; //图片消息
    const TYPE_VIDEO = 15; //视频
    const TYPE_LINK = 3; //分享链接
    const TYPE_FINDER = 28; //视频号动态
    const TYPE_LIVE = 34; //视频号直播
    const TYPE_MUSIC = 42; //音乐

    /**
     * 内容解析
     * @param string $xml
     * @return array
     * Author: fudaoji<fdj@kuryun.cn>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function decodeObject($xml = ''){
        $object = simplexml_load_string($xml);
        $return = [
            'id' => $object->id ,
            'username' => (string)$object->username,
            'create_time' => date('Y-m-d H:i:s', (int)$object->createTime),
            'text' => (string) $object->contentDesc,
            'location' => $object->location
        ];

        $content = $object->ContentObject;
        $return['type'] = (int) $content->contentStyle;

        switch ($content->contentStyle){
            case Pyq::TYPE_FINDER:
                $finder = $content->finderFeed;
                $return['finder'] = [
                    'nickname' => $finder->nickname,
                    'headimgurl' => $finder->avatar
                ];
                $media = $finder->mediaList->media;
                $return['cover'] = $media->thumbUrl;
                $return['url'] = $media->url;
                $return['desc'] = $finder->desc;
                $return['appname'] = "视频号 · " . $finder->nickname;
                break;
            case Pyq::TYPE_LIVE:
                $live = $content->finderLive;
                $return['finder'] = [
                    'nickname' => $live->nickname,
                    'desc' => $live->desc,
                    'headimgurl' => $live->headUrl
                ];
                $return['cover'] = $live->media->coverUrl;
                $return['live_status'] = $live->liveStatus;
                $return['appname'] = "直播 · " . $live->nickname;
                break;
            case Pyq::TYPE_MUSIC:
                $return['appname'] = (string)$object->appName;
                $return['url'] = (string)$content->contentUrl;
                $media = $content->mediaList->media;
                $return['title'] = (string)$media->title;
                $return['author'] = (string)$media->description;
                $return['thumb'] = (string)$media->thumb;
                break;
            case Pyq::TYPE_VIDEO:
                $return['url'] = (string)$content->mediaList->media->url;
                $return['thumb'] = (string)$content->mediaList->media->thumb;
                break;
            case Pyq::TYPE_IMG:
                $image_list = [];
                foreach ($content->mediaList->media as $image){
                    array_push($image_list, [
                        'url' => (string)$image->url,
                        'thumb' => (string)$image->thumb
                    ]);
                }
                $return['image_list'] = $image_list;
                break;
            case Pyq::TYPE_LINK:
                $return['title']  = (string)$content->title;
                $return['description']  = (string)$content->description;
                $return['cover'] = (string)$content->mediaList->media->url;
                $return['thumb'] = (string)$content->mediaList->media->thumb;
                $return['url'] = (string)$content->contentUrl;
                break;
            case Pyq::TYPE_TEXT:
                break;
            default:
                $return['text'] = "【内容无法解析，请用进入朋友圈查看】";
                break;
        }
        //Logger::error($return['text']);
        return $return;
    }
}