<div class="main">
    <div class="mainContent">
        <div class="articleList">
            <?php foreach($this->content["pageContent"] as $article): ?>
                <div class="article">
                    <?php if($article["article_image"] != "0"): ?>
                    <div class="articleImg">
                        <img src="<?=$this->url.$article["article_image"]?>" draggable="false">
                        <div class="articleHeaderContainer">
                            <div class="articleHeader">
                                <?=$article["article_title"]?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="articleContent">
                        <?php if($article["article_image"] == "0"): ?>
                            <div class="articleHeader noImg"><?=$article["article_title"]?></div>
                        <?php endif; ?>
                        <div class="articleInfo">
                            <?php if($article["pinned"]): ?>
                                <i class="las la-thumbtack pinned"></i>
                            <?php endif; ?>
                            <?=strftime('%B %e, %Y', strtotime($article["article_date"]));?> - <?=$article["num_comments"]?> Comment 
                        </div>
                        <div class="articleContentPart">
                            <p class="articleContentPreview"><?php 
                                    if(empty(trim(strip_tags(htmlspecialchars_decode($article["article_content"]))))){
                                        echo "No Content";
                                    }else{
                                        //echo trim(substr(preg_replace("/(&nbsp;)+/"," ",strip_tags(htmlspecialchars_decode($article["article_content"]))),0,150))."...";
                                        echo trim(mb_substr(preg_replace("/(&nbsp;)+/"," ",strip_tags(htmlspecialchars_decode($article["article_content"]))),0,200,"UTF-8"))."...";
                                    }
                                ?></p>
                        </div>
                        <a class="articleReadMore" href="<?=$this->url?>/article/<?=$article["article_url"]?>">READ MORE</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php 
             function closetags($html) {
                preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
                $openedtags = $result[1];
                preg_match_all('#</([a-z]+)>#iU', $html, $result);
                $closedtags = $result[1];
                $len_opened = count($openedtags);
                if (count($closedtags) == $len_opened) {
                    return $html;
                }
                $openedtags = array_reverse($openedtags);
                for ($i=0; $i < $len_opened; $i++) {
                    if (!in_array($openedtags[$i], $closedtags)) {
                        $html .= '</'.$openedtags[$i].'>';
                    } else {
                        unset($closedtags[array_search($openedtags[$i], $closedtags)]);
                    }
                }
                return $html;
            } 
                                    
        ?>
        <div class="pageBtns">
            <?php 
            if($this->content['currentPage']-1 !== 0){
                echo "<a class='prevPage' href='/page/".($this->content['currentPage']-1)."'></a>";
            }
            for($i = 1; $i < $this->content["totalPages"];$i++){
                echo "<a ".($i == $this->content['currentPage'] ? "class='currPage'" : "")." href='/page/$i'>$i</a>";
            }
            if($this->content['currentPage']+1 < $this->content["totalPages"]){
                echo "<a class='nextPage' href='/page/".($this->content['currentPage']+1)."'></a>";
            }
            ?>
        </div>
    </div>
</div>