<div class="articleManage">
    <h1 class="center">Articles</h1>
    <div class="articleManageList">
        <table>
            <thead>
                <tr>
                    <th>Article Header</th>
                    <th>Article Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->articles as $item):?>
                <tr>
                    <td><a href="<?=$this->url?>/article/<?=$item["article_url"]?>"><?=$item["article_title"]?></a></td>
                    <td><?=strftime('%B %e, %Y', strtotime($item["article_date"]));?></td>
                    <td>
                        <a class="articleBtn editArticle" href="<?=$this->url?>/managearticles/edit/<?=$item["article_url"]?>">Edit</a>
                        <a class="articleBtn deleteArticle" href="<?=$this->url?>/managearticles/delete/<?=$item["article_url"]?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div>
            <a class="articleBtn addArticle" href="<?=$this->url?>/managearticles/add">Add Article</a>
        </div>
    </div>
</div>