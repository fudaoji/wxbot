<?php
/**
 * Created by PhpStorm.
 * Script Name: File.php
 * Create: 2022/12/16 9:42
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace app\common\service;

use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToDeleteDirectory;

class File
{
    /**
     * 移动/重命名 文件或文件夹
     * @param $ori
     * @param $dest
     * @return bool|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function renameFile($ori, $dest){
        try {
            rename($ori, $dest);
        }catch (\Exception $e){
            return $e->getMessage();
        }
        return true;
    }

    /**
     * 递归删除文件夹
     * @param $path
     * @param bool $del_dir
     * @return bool
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function delDirRecursively($path, $del_dir = true)
    {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..')
                    is_dir("$path/$item") ? self::delDirRecursively("$path/$item", $del_dir) : @unlink("$path/$item");
            }
            closedir($handle);
            if ($del_dir)
                return rmdir($path);
        } else {
            if (file_exists($path)) {
                return @unlink($path);
            }
        }
        return true;
    }

    /**
     * 删除文件夹
     * @param string $path
     * @param string $root_path
     * @return bool|string
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function delDir($root_path = '', $path = ''){
        try {
            $root_path = empty($root_path) ? $path : $root_path;
            self::fileSystem($root_path)->deleteDirectory($path);
            $res = true;
        } catch (FilesystemException | UnableToDeleteDirectory $e) {
            $res = $e->getMessage();
        }
        return $res;
    }

    /**
     * @param $root_path
     * @return \League\Flysystem\Filesystem
     * Author: fudaoji<fdj@kuryun.cn>
     */
    static function fileSystem($root_path){
        $adapter = new \League\Flysystem\Local\LocalFilesystemAdapter($root_path);
        return new \League\Flysystem\Filesystem($adapter);
    }
}