<?php 

    namespace app\Models;
    use app\Core\Model;

    class ArticleModel extends Model{

        public function getArticlesForList(){
            $result = $this->query("SELECT article_title, article_date, article_url FROM articles ORDER BY article_date DESC");
            return $result;
        }

        public function getArticle($articleUrl){
            $result = $this->fetch("SELECT * FROM articles WHERE article_url = :articleUrl", array(":articleUrl"=>$articleUrl));
            return $result;
        }

        public function getPageArticles($start,$total){
            $result = $this->query("SELECT articles.*, COUNT(article_comments.article_id) AS num_comments FROM articles LEFT JOIN article_comments ON article_comments.article_id = articles.article_id GROUP BY articles.article_id ORDER BY articles.pinned DESC, articles.article_date DESC LIMIT :start,:total;", array(":start"=>$start, ":total"=>$total));
            return $result;
        }

        /*
        SELECT
            articles.*,
            COUNT(articles.article_id) AS num_items
        FROM
            articles
            INNER JOIN article_comments ON article_comments.article_id = articles.article_id
        GROUP BY
            articles.article_id
        ORDER BY articles.article_date DESC
        LIMIT :start,:total;
        */
        public function totalArticles(){
            return $this->fetchColumn("SELECT COUNT(*) FROM articles",[]);
        }

        public function getArticleIdByUrl($articleUrl){
            return $this->fetchColumn("SELECT article_id FROM articles WHERE article_url = :article_url",[
                ":article_url"=>$articleUrl
            ]);
        }

        public function getArticleCommentsCount($articleId){
            return $this->fetchColumn("SELECT COUNT(*) FROM article_comments WHERE article_id=:articleId",[
                "articleId"=>$articleId
            ]);
        }

        public function getArticleComments($articleId){
            $result = $this->query("SELECT * FROM article_comments WHERE article_id=:articleId ORDER BY date DESC", [
                ":articleId"=>$articleId
            ]);
            return $result;
        }

        public function addComment($data){
            $result = $this->query("INSERT INTO article_comments (article_id, user_id, comment) VALUES(:article_id, :user_id, :comment)", [
                ":article_id"=>$data["articleId"],
                ":user_id"=>$data["user_id"],
                ":comment"=>$data["comment"]
            ]);
            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function deleteComment($data){
            $result = $this->query("DELETE FROM article_comments WHERE article_id=:article_id AND user_id=:user_id AND id=:commentId", [
                ":article_id"=>$data["articleId"],
                ":user_id"=>$data["userId"],
                ":commentId"=>$data["commentId"]
            ]);
            if($result){
                return true;
            }else{
                return false;
            }
        }

    }

?>