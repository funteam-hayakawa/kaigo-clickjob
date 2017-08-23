<!DOCTYPE html>
<html lang="ja">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-543LDM');</script>
<!-- End Google Tag Manager -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/css/column_sp.css" media="screen">
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-543LDM" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<title><?php echo $column['ColumnHeaderContent']['title'] ?> | ケアラビNEWS | クリックジョブ介護</title>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<header>
    <div class="col-xs-12" style="margin-bottom: 2%;">
        <div class="col-xs-1" style="padding-left: 0px;">
            &nbsp;
        </div>
        <div class="col-xs-9" style="padding:3% 2% 2% 0">
            <a href="/column"><img src="/img/column/sp/logo.png" style="width:100%;"></a>
        </div>
        <div class="col-xs-2" style="padding-right: 0px;">
            <a data-toggle="collapse" data-target=".navbar-collapse"><img src="/img/column/sp/menubtn.png" width="100%" style="padding: 40% 0;margin-top:55%"></a>
        </div>
    </div>
</header>

<nav>
    <div class="collapse navbar-collapse">
        <img src="/img/column/sp/icon_menu.png" width="100%">
        <a data-toggle="collapse" data-target=".navbar-collapse">
            <img src="/img/column/sp/menubtn_close.png" width="40%" class="menuClose">
        </a>
        <ul class="nav navbar-nav navTxt" style="width: 100%;">
            <li class="dotted"><a href="/column"><img src="/img/column/sp/icon_top.png" width="10%" class="menuicon">TOP</a></li>
            <li class="dotted"><a href="/column/news"><img src="/img/column/sp/icon_news.png" width="10%" class="menuicon">ニュース</a></li>
            <li class="dotted"><a href="/column/column"><img src="/img/column/sp/icon_column.png" width="10%" class="menuicon">コラム</a></li>
            <li class="dotted"><a href="/column/knowledge"><img src="/img/column/sp/icon_knowledge.png" width="10%" class="menuicon">知識</a></li>
        </ul>
        <?php echo $this->Form->create(false, array('type' => 'post',
                                                    'class' => 'form-inline',
                                                       'url' => array('controller' => 'column', 'action' => 'index')
        )); ?>
        <div class="form-group">
            <?php echo $this->Form->input('Column.word', array(
                                          'label' => false,
                                          'div' => false,
                                          'class' => 'form-control search',
                                          'style' => 'width:84%;float:left;margin-bottom:10%',
                                          'placeholder' => '検索したいキーワード',
                                          'required' => false
                  ))
             ?>
         </div>
         <button style="width:15%;float:right;"type="submit" class="btn btn-xs btn-search"><img src="/img/column/pc/search_btn.png" width="50%"></button>
         <div style="clear:both;"></div> 
        <?php echo $this->Form->end() ?>
        <a href="/register/"><img src="/img/column/sp/banner_jobchange.png" width="100%"></a>
    </div>
</nav>

<div id="slider">
</div>
<!-- パンくず -->
<div class="bread"> 
    <div class="container breadear">
        <?php
        $categoryUrl = '';
        switch ($column['ColumnCategory']['category_id']){
            case '1':
                $categoryUrl = 'news';
            break;
            case '2':
                $categoryUrl = 'column';
            break;
            case '3':
                $categoryUrl = 'knowledge';
            break;
        } 
        ?>
        <a href="/column">ケアラビNEWS</a>&nbsp;>&nbsp;
        <?php if (!empty($column['ColumnCategory']['category_id'])): ?>
            <a href="/column/<?php echo $categoryUrl ?>"><?php echo $category[$column['ColumnCategory']['category_id']]['name'] ?></a>&nbsp;>&nbsp;
        <?php endif; ?>
        <?php 
            $s = $column['ColumnHeaderContent']['title'];
            $l = mb_strlen($s);
            if ($l > 15){
              $s = mb_substr($s, 0, 14);
              $s .= '...';
            }
            echo $s;
        ?>
    </div>
</div>
<div class="container">
    <!-- 記事 -->
    <div class="row">
        <div class="col-xs-12">
            <?php 
            $designCls = "ttl1";
            if ($column['ColumnHeaderContent']['title_design'] == '1'){
                $designCls = "ttl1";
            } else if ($column['ColumnHeaderContent']['title_design'] == '2'){
                $designCls = "ttl2";
            }?>
            <h1 class="<?php echo $designCls ?>"><?php echo $column['ColumnHeaderContent']['title'] ?></h1>
            <div class="info">
                <span class="date"><?php echo date_format(new DateTime($column['Column']['modified']), 'Y年m月d日') ?></span> | <span class="cat">
                    <?php echo !empty($column['ColumnCategory']['category_id']) ? $category[$column['ColumnCategory']['category_id']]['name'] : '未設定'; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 contents">
            <?php echo $column['ColumnHeaderContent']['introduction'] ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php $fname = isset($column['ColumnHeaderContent']['ColumnImage']['file_name']) ? $column['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
            <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
            <img src="<?php echo $fname ?>" width="100%">
        </div>
    </div>
    <!-- Social Btn -->
    <div class="row btnGrp">
        <div class="col-xs-12" style="padding: auto; text-align: center;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode("https://kaigo.clickjob.jp/column/detail/".$column['Column']['id']) ?>" target="_blank"><img src="/img/column/sp/fb_share.png" width="22%" style="margin-top: 12px"></a>
                    <div class="fb-like" data-href="https://kaigo.clickjob.jp/column/detail/<?php echo $column['Column']['id'] ?>" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                    <a href="http://twitter.com/share?text=<?php echo rawurlencode($column['ColumnHeaderContent']['title']."\n")."https://kaigo.clickjob.jp/column/detail/".$column['Column']['id'] ?>&url=<?php echo rawurlencode("https://kaigo.clickjob.jp/column/detail/".$column['Column']['id']) ?>&hashtags=" onClick="window.open(encodeURI(decodeURI(this.href)), 'tweetwindow', 'width=650, height=470, personalbar=0, toolbar=0, scrollbars=1, sizable=1'); return false;" rel="nofollow"><img src="/img/column/sp/tweet.png" width="22%" style="margin-top: 12px"></a>
        </div>
    </div>
    <?php foreach ($column['ColumnContent'] as $c): ?>
        <?php switch($c['content_type']): 
        case '1': ?>
        <?php 
        $designCls = "ttl1";
        if ($column['ColumnHeaderContent']['headline_design'] == '1'){
            $designCls = "ttl1";
        } else if ($column['ColumnHeaderContent']['headline_design'] == '2'){
            $designCls = "ttl2";
        } else if ($column['ColumnHeaderContent']['headline_design'] == '3'){
            $designCls = "ttl3";
        }?>
        <div class="row">
            <div class="col-xs-12">
                <h2 class="<?php echo $designCls ?>"><?php echo $c['text'] ?></h2>
            </div>
        </div>
        <?php //endif; ?>
        <?php break; ?>
        <?php case '2': ?>
        <?php 
        $designCls = "ttl1";
        if ($column['ColumnHeaderContent']['sub_headline_design'] == '1'){
            $designCls = "ttl1";
        } else if ($column['ColumnHeaderContent']['sub_headline_design'] == '2'){
            $designCls = "ttl2";
        } else if ($column['ColumnHeaderContent']['sub_headline_design'] == '3'){
            $designCls = "ttl3";
        }?>
        <div class="row">
            <div class="col-xs-12">
                <h3 class="<?php echo $designCls ?>"><?php echo $c['text'] ?></h3>
            </div>
        </div>
        <?php //endif; ?>
        <?php break; ?>
        <?php case '3': ?>
        <div class="row">
            <div class="col-xs-12 contents">
                <?php echo $c['text'] ?>
            </div>
        </div>
        <?php break; ?>
        <?php case '4': ?>
        <div class="row">
            <div class="col-xs-12">
                <?php $fname = isset($c['ColumnImage']['file_name']) ? $c['ColumnImage']['file_name'] : '' ?>
                <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                <img src="<?php echo $fname ?>" width="100%">
            </div>
        </div>
        <?php break; ?>
        <?php endswitch; ?>
    <?php endforeach; ?>
   <!-- Social Button -->
   <div class="row">
       <div class="col-xs-12" style="padding: auto; text-align: center;">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode("https://kaigo.clickjob.jp/column/detail/".$column['Column']['id']) ?>" target="_blank"><img src="/img/column/sp/fb_share.png" width="22%" style="margin-top: 12px"></a>
                    <div class="fb-like" data-href="https://kaigo.clickjob.jp/column/detail/<?php echo $column['Column']['id'] ?>" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                    <a href="http://twitter.com/share?text=<?php echo rawurlencode($column['ColumnHeaderContent']['title']."\n")."https://kaigo.clickjob.jp/column/detail/".$column['Column']['id'] ?>&url=<?php echo rawurlencode("https://kaigo.clickjob.jp/column/detail/".$column['Column']['id']) ?>&hashtags=" onClick="window.open(encodeURI(decodeURI(this.href)), 'tweetwindow', 'width=650, height=470, personalbar=0, toolbar=0, scrollbars=1, sizable=1'); return false;" rel="nofollow"><img src="/img/column/sp/tweet.png" width="22%" style="margin-top: 12px"></a>
       </div>
   </div>
   <!-- バナー -->
   <div class="row" style="margin-top: 4%;">
       <div class="col-xs-12">
           <h5 class="banner"><a href="/register/"><img src="/img/column/sp/banner_kaigo_1.png" width="100%"></a></h5>
       </div>
   </div>
   <!-- 関連ランキング -->
   <div class="row">
       <div class="col-xs-12">
           <h5 class="banner"><img src="/img/column/sp/ttl_relative_article.png" width="100%" alt="関連ランキング"></h5>
       </div>
   </div>
   <?php foreach ($column['ColumnHeaderContent']['RelationRanking'] as $i => $rr): ?>
       <?php if (!empty($rr)): ?>
         <div class="row columnList">
             <div class="col-xs-3">
                 <a href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                     <?php $fname = isset($rr['ColumnHeaderContent']['ColumnImage']['file_name']) ? $rr['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                     <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                     <img src="<?php echo $fname ?>" width="100%">
                 </a>
                 <div class="rank">
                 <?php $rank=$i+1 ?>
                 <img src="/img/column/sp/rank_num<?php echo $rank ?>.png" width="30%">
                 </div>
             </div>
             <div class="col-xs-9" style="height:95px; padding:0 15px 0 13px;">
                 <div class="ttlList">
                     <a style="color:#666" href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                        <?php 
                          $s = $rr['ColumnHeaderContent']['title'];
                          $l = mb_strlen($s);
                          if ($l > 39){
                            $s = mb_substr($s, 0, 38);
                            $s .= '...';
                          }
                          echo $s;
                        ?>
                     </a>
                 </div>
                 <div class="info">
                     <span class="date"><?php echo date_format(new DateTime($rr['ColumnHeaderContent']['modified']), 'Y年m月d日') ?></span> | <span class="cat">
                        <?php echo !empty($rr['ColumnCategory']['category_id']) ? $category[$rr['ColumnCategory']['category_id']]['name'] : '未設定'; ?>
                     </span>
                 </div>
             </div>
             <div class="columnSep">&nbsp;</div>
         </div>
       <?php endif; ?>
   <?php endforeach; ?>
   <!-- バナー -->
   <div class="row">
       <div class="col-xs-12">
           <h5 class="banner"><a href="/register/"><img src="/img/column/sp/banner_kaigo_2.png" width="100%"></a></h5>
       </div>
   </div>
   <!-- おすすめランキング -->
   <div class="row">
       <div class="col-xs-12">
           <h5 class="banner"><img src="/img/column/sp/ttl_recommend_ranking.png" width="100%" alt="おすすめランキング"></h5>
       </div>
   </div>
   <?php foreach ($ranking['RecommendRanking'] as $i => $rr): ?>
       <?php if (!empty($rr)): ?>
         <div class="row columnList">
             <div class="col-xs-3">
                 <a href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                     <?php $fname = isset($rr['ColumnHeaderContent']['ColumnImage']['file_name']) ? $rr['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                     <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                     <img src="<?php echo $fname ?>" width="100%">
                 </a>
                 <div class="rank">
                 <?php $rank=$i+1 ?>
                 <img src="/img/column/sp/rank_num<?php echo $rank ?>.png" width="30%">
                 </div>
             </div>
             <div class="col-xs-9" style="height:95px; padding:0 15px 0 13px;">
                 <div class="ttlList">
                     <a style="color:#666" href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                        <?php 
                          $s = $rr['ColumnHeaderContent']['title'];
                          $l = mb_strlen($s);
                          if ($l > 39){
                            $s = mb_substr($s, 0, 38);
                            $s .= '...';
                          }
                          echo $s;
                        ?>
                     </a>
                 </div>
                 <div class="info">
                     <span class="date"><?php echo date_format(new DateTime($rr['ColumnHeaderContent']['modified']), 'Y年m月d日') ?></span> | <span class="cat">
                        <?php echo !empty($rr['ColumnCategory']['category_id']) ? $category[$rr['ColumnCategory']['category_id']]['name'] : '未設定'; ?>
                     </span>
                 </div>
             </div>
             <div class="columnSep">&nbsp;</div>
         </div>
       <?php endif; ?>
   <?php endforeach; ?>
   <!-- バナー -->
   <div class="row">
       <div class="col-xs-12">
           <h5 class="banner"><a href="/register/"><img src="/img/column/sp/banner_kaigo_3.png" width="100%"></a></h5>
       </div>
   </div>
   <!-- Social Button Circle -->
   <div class="row" style="margin: 10% 0 0 0;">
       <div class="col-xs-12" style="padding: auto; text-align: center;">
         <a href="https://www.facebook.com/clickjobkaigo/?fref=ts"><img src="/img/column/sp/fb_share_circle.png" width="15%"></a>
         <a href="https://twitter.com/clickjobkaigo"><img src="/img/column/sp/tweet_circle.png" width="15%"></a>
       </div>
   </div>
   <!-- Page Upper Direction -->
   <div class="row" style="width:100%">
       <div style="text-align:right" class="btnUpper">
           <img src="/img/column/sp/btn_upper_direction.png" width="20%">
       </div>
   </div>
</div>

<footer class="footer">
    <div class="container">
       <div class="col-xs-12">
           <div class="col-xs-3">
           </div>
           <div class="col-xs-6">
               <a href="/column"><img src="/img/column/sp/category_footer.png" width="100%"></a>
           </div>
           <div class="col-xs-3">
           </div>
       </div>
       <div class="col-xs-12 text-center" style="margin: 5% 0;">
           <a href="/column/news"><img src="/img/column/sp/btn_news_footer.png" width="30%"></a>
           <a href="/column/column"><img src="/img/column/sp/btn_column_footer.png" width="29%"></a>
           <a href="/column/knowledge"><img src="/img/column/sp/btn_knowledge_footer.png" width="22%"></a>
       </div>
       <div class="row">
           <div class="col-xs-12">
               <div class="col-xs-2">
               </div>
               <div class="col-xs-8">
                   <a href="/"><img src="/img/column/sp/logo_footer.png" width="100%"></a>
               </div>
               <div class="col-xs-2">
               </div>
           </div>
       </div>
       <div class="text-center footerTxt">
           介護職求人支援サービス『クリックジョブ介護』 <br />
           運営会社：日本メディカル株式会社<br />
           （厚生労働大臣許可）13-ユ-303765<br /><br />
            Copyright © Japan Medical Ltd. All Rights Reserved.
      </div>
    </div>
</footer>
<script
src="https://code.jquery.com/jquery-1.12.4.min.js"
integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
crossorigin="anonymous"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/js/jquery.bxslider.min.js"></script>
<script type="text/javascript">
$(function(){
    var slide = $('#slider ul').bxSlider({
        slideMargin : 0,
        controls    : true,
        auto        : true,
        minSlides   : 6,
        maxSlides   : 6,
        moveSlides  : 1,
        speed       : 1000,
        pause       : 5000,
        onSlideAfter: function(){
            slide.startAuto();
        }
    });
});
$(document).ready(function(){
    var flg = "close";

    $(".navbar-toggle").click(function(){
        if(flg == "close"){
            $(this).addClass("menuOpen");
            flg = "open";
        }else{
            $(this).removeClass("menuOpen");
            flg = "close";
        }
    });
    $(document).on('click touchend', function(event) {
      if ($('.navbar-collapse').attr('aria-expanded') == 'true' && !$(event.target).closest('.navbar-collapse').length) {
        $(".navbar-collapse").collapse('hide');
      }
    });

    $(".btnUpper").click(function(){
        $('body').scrollTop(0);
    });
});
</script>

</body>
</html>
