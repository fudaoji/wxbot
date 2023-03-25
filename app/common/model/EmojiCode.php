<?php
/**
 * Created by PhpStorm.
 * Script Name: EmojiCode.php
 * Create: 7/28/22 12:38 PM
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\model;


class EmojiCode extends Base
{
    protected $isCache = true;
    protected $expire = 0;


    /**
     *
     * 转换带emoji的文本为图片/标签代码
     * type:img/emoji
     * @param $text
     * @param string $to
     * @return string|string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function emojiText($text, $to = 'img')
    {
        $KyEmoji = new \ky\Emoji();
        // $text = '事实上[emoji=\ue04a]阿萨[emoji=\ue04a]';
        // $text = '事实上<span class="emoji-outer emoji-sizer"><span class="emoji-inner emoji2600"></span></span>阿萨<span class="emoji-outer emoji-sizer"><span class="emoji-inner emoji2600"></span></span>';
        // $to = 'emoji';
        if ($to == 'img') {
            $softb_unicode_arr = [];
            preg_match_all('/\[emoji=(.*?)\]/ism', $text, $res);
            if (isset($res[1])) {
                $softb_unicode_arr = $res[1];
                $emoji_data = $this->where(['softb_unicode' => $softb_unicode_arr])->select()->toArray();

                foreach ($emoji_data as &$v) {
                    $bytes = $KyEmoji->utf8Bytes($KyEmoji->unifiedToHex($v['unified']));
                    $image = $KyEmoji->emojiUnifiedToHtml($bytes);
                    $search = '[emoji='.$v['softb_unicode'].']';
                    $text = str_replace($search,$image,$text);
                }
            }
        } else {
            preg_match_all('/<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji(.*?)\"><\/span><\/span>/ism', $text, $res);
            if (isset($res[1])) {
                foreach($res[1] as &$val) {
                    $val = 'U+'.$val;
                }
                $emoji_data = $this->where(['unified' => $res[1]])->select()->toArray();
                foreach ($emoji_data as &$v) {
                    $emoji_num = substr($v['unified'],2);
                    $search = '<span class="emoji-outer emoji-sizer"><span class="emoji-inner emoji'.$emoji_num.'"></span></span>';
                    $replace = '[emoji='.$v['softb_unicode'].']';
                    $text = str_replace($search,$replace,$text);
                }
                
            }
        }
        

        return $text;
    }
}