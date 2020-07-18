<?php
/**
 * Created by PhpStorm.
 * User: zhangjunjie
 * Date: 2019/4/15
 * Time: 12:05 PM
 */
namespace MadDog\TrieTree;

use think\Exception;

class TrieTreeServer {
    private $node;
    private $filterChars = array();//匹配到的关键词
    private $filterCharsGroup = array();//匹配到的关键词

    public function __construct()
    {
        if (!isset($this->node)) {
            $this->node = new TrieTreeNode();
            $this->oldNode = $this->node;
        }
    }

    public static function create()
    {
        return new static;
    }



    /**
     * 构建一颗tree
     * @param string $str
     * @return null|TrieNode
     */
    public function inset($str)
    {
        $str = strtolower($str);
        $len  = mb_strlen($str);
        $node =  $this->node;

        for ($i=0; $i<$len; $i++) {
            $char = mb_substr($str, $i, 1);
            if($char != '|'){
                $filterList = $node->findNode($char);

                if (is_null($filterList)) {//如果下层没有节点,新建节点往下
                    $filterList = new TrieTreeNode($char);
                    $node->add($filterList);
                }

                $node = $filterList;//如果下层有节点,顺着节点往下
            } else {
                $charList = explode('|', $str);
                if(!empty($charList) && (!empty($node->getFault()) || !$node->isEnd())){
                    $node->addFault($charList);
                }
                $node->setEnd();
                return $node;
            }
        }
        $node->setFaultEmpty();
        $node->setEnd();

        return $node;
    }

    /**
     *
     * @param $str
     * @return bool
     */
    public function filter($str)
    {
        $str = strtolower($str);
        $len = mb_strlen($str);
        $node = $this->node;

        for ($i=0,$tempI = 1; $i<$len; $i++) {
            $char =  mb_substr($str, $i, 1);

            $nextNode = $node->findNode($char);

            if (isset($nextNode)) {
                $this->filterChars[] = $nextNode->char();
                $tempI = $i;


                $node = $nextNode;//节点下移
                if ($node->isEnd()) {

                    $faultWords = $node->getFault();

                    if(!empty($faultWords)){
                        foreach($faultWords as $faultWord){
                            $is_true = [];
                            foreach($faultWord as $word){
                                if(!empty($word)){
                                    if(strpos($str, trim($word)) !== false){
                                        $is_true[] = 1;
                                    } else {
                                        $is_true[] = 0;
                                    }
                                }
                            }

                            if (!in_array(0, $is_true)) {
                                return true;
                            }
                        }

                        if (!is_null(@$this->filterChars)) {//之前有同步,回滚到同部位重新查找
                            $i = $tempI;
                            unset($this->filterChars);
                        }
                        $node = $this->node;//重置树
                    } else {
                        return true;
                    }

                }

            } else {
                if (!is_null(@$this->filterChars)) {//之前有同步,回滚到同部位重新查找
                    $i = $tempI;
                    unset($this->filterChars);
                }
                $node = $this->node;//重置树
            }

        }

        return false;
    }


    /**
     *
     * @param $str
     * @return bool
     */
    public function filterGroup($str)
    {
        $len = mb_strlen($str);

        $node = $this->node;

        for ($i=0,$tempI = 1; $i<$len; $i++) {
            $char =  mb_substr($str, $i, 1);

            $nextNode = $node->findNode($char);

            if (isset($nextNode)) {
                $this->filterChars[] = $nextNode->char();
                $tempI = $i;

                $node = $nextNode;//节点下移
                if ($node->isEnd()) {
                    $this->filterCharsGroup[] = implode('', $this->filterChars);
                    unset($this->filterChars);
                    $i = $tempI;
                    $node = $this->node;
                }

            } else {
                if (!is_null(@$this->filterChars)) {//之前有同步,回滚到同部位重新查找
                    $i = $tempI;
                    unset($this->filterChars);
                }
                $node = $this->node;//重置树
            }

        }

        if(empty($this->filterCharsGroup)){
            return false;
        } else {
            return $this->filterCharsGroup;
        }

    }





}