<?php 

    namespace app\Models;
    use app\Core\Model;

    class ArticleManagerModel extends Model{

        public function createArticle($data){
            $result = $this->query("INSERT INTO articles (article_title, article_image, article_content, article_url) VALUES(:title, :image, :content, :url)", array(
                ":title"=>$data["articleTitle"],
                ":content"=>$data["articleContent"],
                ":image"=>$data["articleImage"],
                ":url"=>$data["articleUrl"]
            ));
            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function editArticle($data){//$title, $content, $image, $url, $editToUrl
            $result = $this->query("UPDATE articles SET article_title = :title, article_image = :image, article_content = :content, article_url = :url WHERE article_url = :currArticleUrl", array(
                ":title"=>$data["articleTitle"],
                ":content"=>$data["articleContent"],
                ":image"=>$data["articleImage"],
                ":url"=>$data["articleUrl"],
                ":currArticleUrl"=>$data["currArticleUrl"]
            ));

            return $result;
        }

        public function deleteArticle($id){
            $result = $this->query("DELETE FROM articles WHERE article_id = :article_id", array(":article_id"=>$id));
            $this->query("DELETE FROM article_comments WHERE article_id = :article_id", array(":article_id"=>$id));
            return $result;
        }

    }

?>