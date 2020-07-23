<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?=$this->url?>/assets/images/icons/icon-16x16.png" sizes="16x16">
    <link rel="stylesheet" href="<?=$this->url?>/assets/css/main.css">
    <meta name="description" content="Some Blog Content">
    <meta name="keywords" content="Blog, Awesome, Content">
    <meta name="author" content="Ramazan Öztürk">
    <meta property="og:title" content="<?php echo !empty($this->_title) ? $this->_title . " - " : null ?>Ramazan Öztürk" />
    <meta property="og:url" content="<?=__URL__."/".trim($_SERVER["REQUEST_URI"], "/\\")?>" />
    <meta property="og:description" content="<?=!empty($this->_metas["og:description"]) ? $this->_metas["og:description"] : "Some Blog Content"; ?>"/>
    <?php if(!empty($this->articleData["article_image"])): ?>
        <meta property="og:image" content="<?=__URL__.$this->articleData["article_image"] ?>">
    <?php endif; ?>

    <script>const _url_ = "<?=__URL__?>"</script>
    <?php
        if(!empty($this->_extra)){
            foreach($this->_extra as $element){
                echo $element."\n";
            }
        }
    ?>
    <script src="<?=$this->url?>/assets/js/main.js"></script>
    <title><?php echo !empty($this->_title) ? $this->_title . " - " : null ?>Blog</title>
</head>
<?php
   
?>
<body class="preload">
    <div class="mainContainer">
        <div class="contentWrapper"> 
            <div class="header">
                <div class="headerInner">
                    <div class="headerLogo">
                        <a href="<?=$this->url?>/">
                            <div class="logo">
                                <img src="<?=$this->url?>/assets/images/icons/icon-196x196.png" alt="Logo">
                            </div>
                        </a>
                    </div>
                    <div class="headerContent">
                        <div class="navContainer">
                            <ul class="mainNav">
                                <li>
                                    <a href="<?=$this->url?>/">HOME</a>
                                </li>
                                <li>
                                    <a href="<?=$this->url?>/aboutme">ABOUT ME</a>
                                </li>
                                <li>
                                    <a href="<?=$this->url?>/contact">CONTACT</a>
                                </li>
                                <?php if(!$this->userInfo): ?>
                                <li class="rightSide loginBtn">
                                    <a href="<?=$this->url?>/login">LOGIN</a>
                                </li>
                                <?php else: ?>
                                    <li class="rightSide profileBtn">
                                        <div class="profileName"><?=$this->userInfo["username"]?></div>
                                        <div class="profileMenu">
                                            <ul>
                                                
                                                <?php if (in_array($this->userInfo["user_level"],["author", "admin"])): ?>
                                                    <li><a href="<?=$this->url?>/managearticles">Articles</a></li>
                                                <?php endif; ?>
                                                <li><a href="<?=$this->url?>/sign-out">Log Out</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="menuBtn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>