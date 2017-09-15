<?php if ($this->layout == ''): ?>
<div class="header">
  <div class="header-1">
    <div class="item-logo">
      <img src="/img/pc/top-logo.png" alt="クリックジョブ看護">
    </div>
<!--
    <div class="item-1">
      <a href="#">
        <img src="../img/pc/icon-mypage.png" alt="">
        <span>マイページ登録</span>
      </a>
    </div>

    <div class="item-2">
      <a href="#">
        <img src="../img/pc/icon-login.png" alt="">
        <span>ログイン</span>
      </a>
    </div>
-->
    <div class="item-3">
      <a href="/favorite">
        <img src="/img/pc/icon-link.png" alt="">
        <span class="power">お気に入り求人</span>
      </a>
    </div>

    <div class="item-4">
      <a href="/history">
        <img src="/img/pc/icon-link.png" alt="">
        <span class="power">最近見た求人</span>
      </a>
    </div>

    <div class="item-tel">
      <img class="tel" src="/img/pc/header-tel.png" alt="電話番号">
    </div>
  </div><!-- /.header-1 -->
  <div class="header-2">
    <div class="items">
      <a class="item-1" href="/search">求人を探す</a>
      <a class="item-2" href="/service">サービス紹介</a>
      <a class="item-3" href="XXX">転職お役立ちコンテンツ</a>
      <a class="item-4" href="/column">介護 NEWS・コラム</a>
      <a class="item-5" href="/register">転職相談 <span class="power">無料</span></a>
      <div class="clearfix"></div>
    </div>
  </div><!-- /.header-2 -->
</div><!-- /.header -->
<?php else: ?>
テスト用リンク<br>
<?php echo $this->Html->link('TOPページ', '/'); ?><br/>
<?php echo $this->Html->link('求人を探す', '/search'); ?><br/>
<?php echo $this->Html->link('エリアから探す', '/area'); ?><br/>
<?php echo $this->Html->link('こだわり条件から探す', '/feature'); ?><br/>
<?php //echo $this->Html->link('サービス紹介', '/service/about'); ?><br/>
<?php //echo $this->Html->link('お役立ちコンテンツ', '/content/knowhow'); ?><br/>
<?php echo $this->Html->link('介護 NEWS・コラム', '/column'); ?><br/>
<?php echo $this->Html->link('転職相談[無料]', '/register'); ?><br/>
<?php echo $this->Html->link('お気に入り求人', '/favorite'); ?><br/>
<?php echo $this->Html->link('最近見た求人', '/history'); ?><br/>
<?php echo $this->Html->link('マイページ登録', '/member/registration'); ?><br/>
<?php echo $this->Html->link('ログイン', '/member/login'); ?><br/>
<hr>
<?php endif; ?>