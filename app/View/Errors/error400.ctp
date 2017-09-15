<?php $this->layout = ''; ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>クリックジョブ介護</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link href="http://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/pc/parts.css">
    <link rel="stylesheet" href="/css/pc/single_clum.css">
  </head>
  <body>
  <?php echo $this->element('header'); ?>
  
  <div class="wrap wrap_s">
    <div class="main main_s">
      <section class="notfound mt30 mb30">
        <p class="cap">ページが見つかりません。</p>
        <p class="read">アクセスしようとしたページが見つかりません。<br>削除または移動された可能性があります。</p>
        <div class="two-button mt40">
          <a href="/" class="btn_blue">トップページへ戻る</a>
          <a href="/search" class="btn_blue2">求人を探す</a>
        </div>
      </section>
      <section class="free_search mb50">
        <h2>
          <img src="/img/pc/h-icon-pencil.png" alt="">
          <span class="power">「フリーワード」</span>から介護求人を探す
        </h2>
        <div class="padding-10"></div>
        
        <?php echo $this->Form->create(false, array('type' => 'post',
                                                    'class' => 'search-freeword',
                                                    'url' => array('controller' => 'search', 'action' => 'result')
        )); ?>
            <div class="text">
              <?php echo $this->Form->input('Search.freeword', array(
                                            'label' => false,
                                            'div' => false,
                                            'class' => 'form-control',
                                            'placeholder' => '施設名や条件、職者や地域などのキーワート',
                                            'required' => false
                    ))
               ?>
            </div>
            <div class="submit">
              <input type="image" src="/img/pc/mini-button-search.png">
            </div>
            <div class="clearfix"></div>
        <?php echo $this->Form->end() ?>
        
      </section>

    </div><!-- /.main -->
  </div><!-- /.wrap -->
  <div class="clearfix"></div>

  <?php echo $this->element('footer'); ?>

  </body>
  <script type="text/javascript" src="/js/jquery.1.12.4.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
</html>