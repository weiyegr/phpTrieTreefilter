# phpTrieTreefilter
 基于DFA算法敏感词过滤匹配、支持断续多敏感词匹配

-用法说明
1.单个单词
2.断续多个单词以“|”分隔 如：看|照片

-例子
```/*敏感词组*/
$words = [
    '我们',
    '看|照片'
];

/*初始敏感词*/
$trie = \MadDog\TrieTree\TrieTreeServer::create();
foreach($words as $item){
   $trie->inset($item);
}

/*匹配敏感词*/
$result = $trie->filter('我们是朋友');
var_dump($result);

$result = $trie->filter('看海边的照片');
var_dump($result);```
