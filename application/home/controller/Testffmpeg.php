<?php
/**
 * Created by PhpStorm.
 * Script Name: Index.php
 * Create: 2022/3/2 16:54
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\home\controller;

use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\Point;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Filters\Video\RotateFilter;
use think\Controller;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

class Testffmpeg extends Controller
{
    private $rootPath;
    /**
     * @var \FFMpeg\Media\Audio|\FFMpeg\Media\Video
     */
    private $video;
    /**
     * @var FFProbe
     */
    private $ffprobe;
    /**
     * @var string
     */
    private $testVideo;
    /**
     * @var FFMpeg
     */
    private $ffmpeg;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $config = [
            'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe'
        ];
        $this->ffmpeg = FFMpeg::create($config);
        $this->rootPath = request()->server()['DOCUMENT_ROOT'] . '/temp/';
        $this->testVideo = $this->rootPath . "/input.mp4";
        $this->ffprobe = $this->ffmpeg->getFFProbe();
        set_time_limit(0);
    }

    /**
     * get media
     * @param string $type
     * @return \FFMpeg\Media\Audio|\FFMpeg\Media\Video
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function getMedia($type = 'video'){
        return $this->video = $this->ffmpeg->open($this->testVideo);
    }

    public function setTestVideo($filename = ''){
        $this->testVideo = $this->rootPath . $filename;
        return $this->getMedia();
    }

    /**
     * all info: index,codec_name,codec_long_name,profile,codec_type,codec_tag_string,codec_tag,width,height,coded_width,coded_height,closed_captions,film_grain,has_b_frames,pix_fmt,level,color_range,color_space,chroma_location,field_order,refs,is_avc,nal_length_size,id,r_frame_rate,avg_frame_rate,time_base,start_pts,start_time,duration_ts,duration,bit_rate,bits_per_raw_sample,nb_frames,extradata_size,disposition,tags
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function info(){
        $stream = $this->ffprobe->streams($this->testVideo) // extracts streams informations
        ->videos()// filters video streams
        ->first();
        // returns the first video stream
        dump(
            "codec_name: " . $stream->get('codec_name') . "\n" .
            "width: " . $stream->get('width') . "\n" .
            "height: " . $stream->get('height') . "\n" .
            "codec_type: " . $stream->get('codec_type') . "\n" .
            "duration: " . $stream->get('duration') . "\n"
        );
        dump(implode(',', $stream->keys()));
    }

    /**
     * 背景虚化
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addBgBlur(){
        $res = $this->getMedia()
            ->addFilter(new SimpleFilter(['-filter_complex', 'split[a][b];[a]scale=504:270,boxblur=10:5[1];[b]scale=iw-40:ih[2];[1][2]overlay=(W-w)/2']))
            ->save(new X264(), $this->rootPath . '/output_bgblur.mp4');
        dump($res);
    }

    /**
     * 添加边距
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function pad(){
        //ffmpeg -i video.mp4 -filter_complex "pad=1280:0:(ow-iw)/2" output.mp4
        $res = $this->getMedia()
            ->addFilter(new SimpleFilter(['-filter_complex','pad=iw+30:ih+30:(ow-iw)/2:(oh-ih)/2']))
            ->save(new X264(), $this->rootPath . '/output_pad.mp4');
        //获取生成命令
        //$res = $this->video->getFinalCommand(new X264(), $this->rootPath . "output_crop.mp4");
        dump($res);
    }

    /**
     * 裁剪
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function crop(){
        //ffmpeg -i test.mp4 -filter_complex "[0:v]setpts=0.5*PTS[v];[0:a]atempo=2.0[a]" -map [v] -map [a] out_test.mp4
        $this->getMedia()
            ->filters()
            ->crop( new Point(10, 0, true), new Dimension(400, 270));
        $res = $this->video->save(new X264(), $this->rootPath . "output_crop.mp4");
        //获取生成命令
        //$res = $this->video->getFinalCommand(new X264(), $this->rootPath . "output_crop.mp4");
        dump($res);
    }

    /**
     * 倍速播放
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function speed(){
        //ffmpeg -i test.mp4 -filter_complex "[0:v]setpts=0.5*PTS[v];[0:a]atempo=2.0[a]" -map [v] -map [a] out_test.mp4
        $res = $this->setTestVideo("input.wmv")
            ->addFilter(new SimpleFilter(array('-filter_complex','[0:v]setpts=0.5*PTS[v];[0:a]atempo=2.0[a]','-map', '[v]', '-map', '[a]')))
            ->save(new X264(), $this->rootPath . '/output_speed.mp4');
        dump($res);
    }

    /**
     * 压缩视频
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function compress(){
        //ffmpeg -i input.mp4 -vf scale=1280:-1 -preset veryslow -crf 24 output.mp4
        $this->setTestVideo("input.wmv")
            ->addFilter(new SimpleFilter(array('-preset','veryslow','-crf', '24')))
            ->save(new X264(), $this->rootPath . '/output_compress.mp4');
    }

    /**
     * 给视频增加背景图片
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addBgImg(){
        //ffmpeg -loop 1 -i ./bg.jpg  -i ./input.mp4 -filter_complex "overlay=(W-w)/2:(H-h)/2:shortest=1" ./output_bgimg.mp4
        $img_url = 'https://zyx.images.huihuiba.net/FiqH83tMeOrkBio3cLQhniDzsHCG?imageView2/1/w/534/h/300';
        file_put_contents($this->rootPath . 'FiqH83tMeOrkBio3cLQhniDzsHCG.png', file_get_contents($img_url));
        $format = new X264();
        $format->setInitialParameters(['-loop','1','-i', $this->rootPath . 'FiqH83tMeOrkBio3cLQhniDzsHCG.png']); //前置参数

        $res = $this->getMedia()
            ->addFilter(new SimpleFilter(['-filter_complex', 'overlay=(W-w)/2:(H-h)/2:shortest=1']))
            //->addFilter(new SimpleFilter(['-filter_complex', 'overlay=20:20:shortest=1']))
            ->save($format, $this->rootPath . '/output_addbgimg.mp4');
            //->getFinalCommand(new X264(), $this->rootPath . "output_addbgimg.mp4");
        dump($res);
    }

    /**
     * 给视频增加音频
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function addAudio(){
        //ffmpeg -i ./shoes.mp4 -i ./test.mp3 -shortest ./output_addaudio.mp4
        $this->video->addFilter(new SimpleFilter(array('-i', $this->rootPath . '/test.mp3', '-shortest')));
        $this->video->save(new X264(), $this->rootPath . '/output_addaudio.mp4');
    }

    /**
     * 去除音频
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function rmAudio(){
        //ffmpeg -i ./shoes.mp4 -map 0:0 -vcodec copy ./output_rmaudio.mp4
        $this->video->addFilter(new SimpleFilter(array('-map', '0:0'))); //使用自定义参数
        $res = $this->video->save(new X264(), $this->rootPath . '/output_rmaudio1.mp4');
        dump($res);
    }

    /**
     * 水平翻转+水印
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function hflipWatermark(){
        $watermarkPath = $this->rootPath . '/logo.png';
        $this->video
            ->filters()
            ->custom("hflip")
            ->watermark($watermarkPath, ['position' => 'relative','left' => 5,'top' => 5]);
        $res = $this->video->save(new X264(), $this->rootPath . '/output_watermark_hflip.mp4');
        dump($res);
    }

    /**
     * 水平翻转
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function hflip(){
        $this->video->filters()->custom("hflip"); //使用自定义参数
        $res = $this->video->save(new X264(), $this->rootPath . '/output_hflip.mp4');
        dump($res);
    }

    /**
     * 旋转
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function rotate(){
        $angle = RotateFilter::ROTATE_180;
        $this->video->filters()->rotate($angle);
        $res = $this->video->save(new X264(), $this->rootPath . '/output_rotate.mp4');
        dump($res);
    }

    /**
     * 改变帧频
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function framerate(){
        $framerate = new FrameRate(35);
        $this->video
            ->filters()
            ->framerate($framerate, 5);
        $res = $this->video->save(new X264(), $this->rootPath . '/output_framerate.mp4');
        dump($res);
    }

    /**
     * 添加水印
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function watermark(){
        $watermarkPath = $this->rootPath . '/logo.png';
        $this->video
            ->filters()
            ->watermark($watermarkPath, ['position' => 'relative','left' => 5,'top' => 5]);
        $res = $this->video->save(new X264(), $this->rootPath . '/output_watermark.mp4');
        dump($res);
    }

    /**
     * 视频截取
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function clip(){
        $clip = $this->getMedia()->clip(TimeCode::fromSeconds(2), TimeCode::fromSeconds(10));
        $res = $clip->save(new X264(), $this->rootPath . '/output_clip.mp4');
        dump($res);
    }

    /**
     * 视频转码
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function transcode(){
        $format = new X264();
        $format->on('progress', function ($video, $format, $percentage) {
            echo "$percentage % transcoded";
        });
        $format
            ->setKiloBitrate(1000)
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(256);
        $this->video->save($format, $this->rootPath . '/output_transcode.mp4');
    }

    /**
     * 获取截图
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function frame(){
        $res = $this->video->frame(TimeCode::fromSeconds(5))
            ->save($this->rootPath . '/cover.jpg');
        dump($res->getVideo()->getPathfile());
    }

    /**
     * 改变尺寸
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function resize(){
        $this->setTestVideo('input.wmv')
            ->filters()
            ->resize(new Dimension(480, 640), ResizeFilter::RESIZEMODE_INSET)
            ->synchronize();
        $res = $this->video->save(new X264(), $this->rootPath . '/output_resize_w480.mp4');
        dump($res);
    }

    /**
     * 改变尺寸
     * Author: fudaoji<fdj@kuryun.cn>
     */
    public function removeWatermark(){
        $res = $this->setTestVideo('output_watermark.mp4')
            ->addFilter(new CustomFilter('delogo=x=50:y=10:w=100:h=70:show=0'))
            ->save(new X264(), $this->rootPath . '/output_removewatermark.mp4');
        dump($res);
    }
}