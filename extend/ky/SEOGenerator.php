<?php
/**
 * Created by PhpStorm.
 * Script Name: SEOGenerator.php
 * Create: 2025/4/20 下午3:19
 * Description:
 * Author: fudaoji<fdj@kuryun.cn>
 */

namespace ky;

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

class SEOGenerator
{
    private $stopWords = [];
    private $algorithm = 'tfidf'; // tfidf|textrank
    private $dictPath;

    /**
     * 构造函数
     *
     * @param string $dictPath 分词词典路径
     * @param string $stopWordsFile 停用词文件路径
     */
    public function __construct(string $dictPath = '', string $stopWordsFile = '')
    {
        $this->initJieba($dictPath);
        if ($stopWordsFile) {
            $this->loadStopWords($stopWordsFile);
        }
    }

    /**
     * 初始化结巴分词
     * @param string $dictPath
     */
    private function initJieba(string $dictPath): void
    {
        Jieba::init([
            'dict' => $dictPath ?: 'big',
            'mode' => 'default'
        ]);
        Finalseg::init();
    }

    /**
     * 设置停用词表
     * @param array $stopWords
     * @return SEOGenerator
     */
    public function setStopWords(array $stopWords): self
    {
        $this->stopWords = array_unique(array_merge($this->stopWords, $stopWords));
        return $this;
    }

    /**
     * 从文件加载停用词
     * @param string $filePath
     * @return SEOGenerator
     */
    public function loadStopWords(string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("停用词文件不存在: {$filePath}");
        }

        $words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->setStopWords($words);
        return $this;
    }

    /**
     * 设置算法
     * @param string $algorithm
     * @return SEOGenerator
     */
    public function setAlgorithm(string $algorithm): self
    {
        if (!in_array($algorithm, ['tfidf', 'textrank'])) {
            throw new \InvalidArgumentException("不支持的算法: {$algorithm}");
        }
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * 主处理方法
     * @param string $content
     * @param int $keywordsNum
     * @param int $descLength
     * @return array
     */
    public function generate(string $content, int $keywordsNum = 5, int $descLength = 150): array
    {
        $cleanText = $this->preprocessText($content);
        $words = $this->segmentText($cleanText);
        $keywords = $this->extractKeywords($words, $keywordsNum);
        $description = $this->generateDescription($cleanText, $keywords, $descLength);

        return [
            'keywords' => implode(',', $keywords),
            'description' => $description
        ];
    }

    /**
     * 文本预处理
     * @param string $text
     * @return string
     */
    private function preprocessText(string $text): string
    {
        // 去除HTML标签
        $text = strip_tags($text);

        // 转换HTML实体到普通字符
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 替换所有空白字符为单个空格
        $text = preg_replace('/\s+/u', ' ', $text);

        // 过滤特殊字符（保留中文、英文、数字）
        $text = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9\s]/u', '', $text);

        // 移除数字和字母的额外空格
        return preg_replace('/(?<=\d) +(?=\d)|(?<=[a-zA-Z]) +(?=[a-zA-Z])/u', '', $text);
    }

    /**
     * 中文分词
     * @param string $text
     * @return array
     */
    private function segmentText(string $text): array
    {
        // 使用结巴分词
        //Jieba::loadUserDict();
        $words = Jieba::cut($text);
        return array_filter($words, function ($word) {
            // 过滤单字符和非有效内容
            return mb_strlen($word, 'UTF-8') > 1
                && !preg_match('/^[\d\s]+$/', $word);
        });
    }

    /**
     * 提取关键词
     * @param array $words
     * @param int $num
     * @return array
     */
    private function extractKeywords(array $words, int $num): array
    {
        $filtered = $this->filterStopWords($words);
        switch ($this->algorithm){
            case 'tfidf':
                $res = $this->tfidfAlgorithm($filtered, $num);
                break;
            case 'textrank':
                $res = $this->textrankAlgorithm($filtered, $num);
                break;
            default:
                throw new \RuntimeException('未知算法');
        }

        return $res;
    }

    /**
     * TF-IDF算法实现
     * @param array $words
     * @param int $num
     * @return array
     */
    private function tfidfAlgorithm(array $words, int $num): array
    {
        $freq = array_count_values($words);
        arsort($freq);
        return array_slice(array_keys($freq), 0, $num);
    }

    /**
     * TextRank算法实现（简化版）
     * @param array $words
     * @param int $num
     * @return array
     */
    private function textrankAlgorithm(array $words, int $num): array
    {
        // 此处应实现完整TextRank算法，以下为简化示例
        $windowSize = 5;
        $graph = [];

        // 构建词图
        for ($i = 0; $i < count($words); $i++) {
            for ($j = max(0, $i - $windowSize); $j < min(count($words), $i + $windowSize); $j++) {
                if ($i !== $j) {
                    $graph[$words[$i]][$words[$j]] = ($graph[$words[$i]][$words[$j]] ?? 0) + 1;
                }
            }
        }

        // 简单评分排序
        $scores = array_map('count', $graph);
        arsort($scores);
        return array_slice(array_keys($scores), 0, $num);
    }

    /**
     * 过滤停用词
     * @param array $words
     * @return array
     */
    private function filterStopWords(array $words): array
    {
        return array_values(array_diff($words, $this->stopWords));
    }

    /**
     * 生成描述
     * @param string $text
     * @param array $keywords
     * @param int $length
     * @return string
     */
    private function generateDescription(string $text, array $keywords, int $length): string
    {
        // 优先选择包含关键词的句子
        $sentences = preg_split('/[。！？]/u', $text);
        foreach ($sentences as $sentence) {
            foreach ($keywords as $keyword) {
                if (strpos($sentence, $keyword) !== false) {
                    return $this->truncateText($sentence, $length);
                }
            }
        }

        // 无匹配则截取开头
        return $this->truncateText($text, $length);
    }

    /**
     * 安全截取中文字符
     * @param string $text
     * @param int $length
     * @return string
     */
    private function truncateText(string $text, int $length): string
    {
        // 先进行最终清理
        $clean = preg_replace('/\s+/u', ' ', trim($text));
        $trimmed = mb_substr($clean, 0, $length, 'UTF-8');

        // 确保不会在结尾截断词语
        if (mb_strlen($clean, 'UTF-8') > $length) {
            $lastSpace = mb_strrpos($trimmed, ' ', 0, 'UTF-8');
            $trimmed = $lastSpace !== false ? mb_substr($trimmed, 0, $lastSpace, 'UTF-8') : $trimmed;
            return $trimmed . '...';
        }
        return $trimmed;
    }
}
