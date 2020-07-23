<div class="mainArticle">
    <div class="main">
        <div class="articleContainer">
            <?php if($this->articleData["article_image"] !== "0"): ?>
            <div class="articleImg">
                <img src="<?=$this->url.$this->articleData["article_image"]?>" alt="">
            </div>
            <?php endif; ?>
            <div class="articleInfo">
                <div class="articleHeader"><?=$this->articleData["article_title"]?></div>
                <div class="articleDate"><?=strftime('%B %e, %Y', strtotime($this->articleData["article_date"]));?> - <?=$this->totalComments?> Comment</div>
            </div>
            <div class="articleContent">
                <?=htmlspecialchars_decode($this->articleData["article_content"])?>
            </div>
            <div class="commentsContainer">
                <div class="commentsHeadContainer">
                    <div class="commentsHead"><?=$this->totalComments?> Comment</div>
                    <?php if($this->userInfo): ?>
                    <div class="userComment">
                        <textarea name="comment" id="comment" contenteditable="true" placeholder="Yorum Yaz..." required></textarea>
                        <input type="hidden" id="_comtoken" value="<?=$this->commentToken?>">
                        <div class="buttons">
                            <button class="btn" id="makeComment">Comment</button>
                        </div>
                    </div>
                    <?php else: ?>
                        <div><a href="<?=$this->url?>/login">Login</a> to make comment..</div>
                    <?php endif; ?>
                </div>
                <div class="commentListContainer">
                <?php 
                    if(!empty($this->commentsData)):                
                        foreach($this->commentsData as $comment):
                ?>          
                            <div class="commentItem">
                                <div class="commentHead">
                                    <span class="commentName"><?=$comment["username"]?></span>
                                    <span class="commentDate"> - <?=$comment["shortDate"]?></span>
                                    <?php if(!empty($this->userInfo["username"]) && $this->userInfo["username"] == $comment["username"]): ?>
                                        <span class="deleteComment" data-id="<?=$comment["id"]?>"><i class="las la-times"></i></span>
                                    <?php endif; ?>
                                </div>
                                <div class="commentContent"><?=$comment["comment"]?></div>
                            </div>
                <?php
                        endforeach;
                    else: 
                ?>
                    <br>
                    <div>No Comments..</div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>