<?php
/**
 * Created by PhpStorm.
 * User: zhangjunjie
 * Date: 2019/4/15
 * Time: 12:26 PM
 */
namespace MadDog\TrieTree;

class TrieTreeNode
{
    private $char;//当前字符
    private $isEnd;//是否最后节点
    private $trieTree;//树
    private $faultWords = []; //是否断层

    /**
     * 初始化trie tree
     * TrieNode constructor.
     */
    public function __construct($char = null)
    {

        $this->char = $char;
        $this->isEnd = false;
    }

    /**
     * 添加子接点
     * @param array $node
     */
    public function add(TrieTreeNode $node)
    {
        $this->trieTree[$node->char()] = $node;
    }

    /**
     * 当前节点文字
     * @return string
     */
    public function char()
    {
        return $this->char;
    }

    /**
     * 是否结尾
     * @return mixed
     */
    public function isEnd()
    {
        return $this->isEnd;
    }

    /**
     * 标记结尾
     * @return bool
     */
    public function setEnd()
    {
        return $this->isEnd = true;
    }

    /**
     * 是否断层词
     * @return mixed
     */
    public function getFault(){
        return $this->faultWords;
    }

    /**
     * 设置为断层词
     */
    public function addFault($word){
        $this->faultWords[] = $word;
    }

    public function setFaultEmpty(){
        $this->faultWords = [];
    }

    /**
     * 查找有无相同节点
     * @param $char
     * @return null
     */
    public function findNode($char)
    {

        if (is_null(@$this->trieTree[$char])) {
            return null;
        } else {
            return $this->trieTree[$char];
        }
    }

}