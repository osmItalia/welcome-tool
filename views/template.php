<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <title>Welcome tool</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.css">
  <script src=" https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.5.5/showdown.min.js"></script>

  <style>
  .Site {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

.Site-content {
  flex: 1;
  margin-top: 75px;
}

.logo, .avatar {

}
  </style>
</head>
<body class="Site">
    <div class="ui fixed inverted menu">
      <div class="ui container">
        <a href="<?php echo Flight::request()->base;?>" class="header item">
          <img class="logo" src="https://placehold.it/50x50">&nbsp;
          Welcome tool
        </a>
        <a href="<?php echo Flight::request()->base;?>" class="item">Home</a>
        <?php if (isset($_SESSION['display_name'])) :?>
        <div class="ui simple dropdown item" tabindex="0">
            Admin
            <i class="dropdown icon"></i>
            <div class="menu" tabindex="-1">
              <a href="<?php echo Flight::request()->base;?>/admin/languages" class="item">Languages</a>
              <a href="<?php echo Flight::request()->base;?>/admin/snippets" class="item">Message snippets</a>
            </div>
        </div>
        <?php endif;?>
        <div class="right menu">
          <?php if (!isset($_SESSION['display_name'])) :?>
          <a class="item" href="<?php echo Flight::request()->base;?>/login">Login</a>
          <?php else :?>
          <div class="item">
              <img class="avatar" src="<?php echo $_SESSION['user_picture']; ?>">&nbsp;
              <?php echo $_SESSION['display_name']; ?>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>

      <div class="ui main text container Site-content">
        <h1 class="ui header"><?php echo $pTitle;?></h1>
        <?php
            echo $content;
        ?>
      </div>

      <div class="ui inverted vertical footer segment">
        <div class="ui center aligned container">

        </div>
      </div>
</body>
</html>
