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
<title>ケアラビNEWS | クリックジョブ介護</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/css/column/column_sp.css" media="screen">
<style type="text/css">
</style>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-543LDM" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- ヘッダー -->
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
<!-- メニュー -->
<nav>
    <div class="collapse navbar-collapse">
            <img src="/img/column/sp/icon_menu.png" width="100%">
            <a data-toggle="collapse" data-target=".navbar-collapse">
            <img src="/img/column/sp/menubtn_close.png" width="40%" class="menuClose">
            </a>
            <ul class="nav navbar-nav navTxt" style="width: 100%;">
                <li class="dotted"><a href="/column"><img style="margin:5.5% 7% 5.5% 9%" src="/img/column/sp/icon_top.png" width="11%" class="menuicon">TOP</a></li>
                <li class="dotted"><a href="/column/news"><img src="/img/column/sp/icon_news.png" width="16%" class="menuicon">ニュース</a></li>
                <li class="dotted"><a href="/column/column"><img style="margin:6% 5% 6% 9%" src="/img/column/sp/icon_column.png" width="12%" class="menuicon">コラム</a></li>
                <li class="dotted"><a href="/column/knowledge"><img style="margin:4.2% 8% 4.2% 11.5%" src="/img/column/sp/icon_knowledge.png" width="7%" class="menuicon">知識</a></li>
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
    </div>
</nav>
<!-- スライド -->
<div id="slider">
    <div id="sliderInner">
        <ul>
            <?php for($i = 0 ; $i < 6 ; $i++): 
            $rt = $ranking['TopPickup'][$i%4];?>
            <li class="slide-li">
                <?php if (!empty($rt['Column']['id'])): ?>
                    <a href="/column/detail/<?php echo $rt['Column']['id'] ?>">
                        <?php $fname = isset($rt['ColumnHeaderContent']['ColumnImage']['file_name']) ? $rt['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                        <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                        <img src="<?php echo $fname ?>" class="slidePic">
                        <?php 
                            $slider='';
                            if ($i % 2 == 0){
                                $slider = 'slideFilterGreen';
                            } else {
                                $slider = 'slideFilterRed';
                            }
                        ?>
                        <div id="<?php echo $slider?>">
                            <div id="slideFilterTxt">
                                <?php if (!empty($rt['ColumnCategory']['category_id']) && $rt['ColumnCategory']['category_id'] == 1): ?>
                                    <img src="/img/column/sp/icon_news.png" width="12%" class="bannerIcon">&nbsp;
                                <?php elseif (!empty($rt['ColumnCategory']['category_id']) && $rt['ColumnCategory']['category_id'] == 2): ?>
                                    <img src="/img/column/sp/icon_column.png" width="12%" class="bannerIcon">&nbsp;
                                <?php elseif (!empty($rt['ColumnCategory']['category_id']) && $rt['ColumnCategory']['category_id'] == 3): ?>
                                    <img src="/img/column/sp/icon_knowledge.png" width="6%" class="bannerIcon">&nbsp;
                                <?php endif; ?>
                                <?php echo !empty($rt['ColumnCategory']['category_id']) ? $category[$rt['ColumnCategory']['category_id']]['name'] : '未設定' ?><br />
                                <span class="sliderText"><?php echo $rt['ColumnHeaderContent']['title'] ?></span>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </li>
            <?php endfor; ?>
        </ul>
    </div><!-- topSlider ---->
</div>

<div class="container">
   <!-- 記事リスト -->
   <?php foreach ($column as $idx=>$r): ?>
       <div class="row columnList">
           <div class="col-xs-3" style="padding:0 0 0 10px">
               <a href="/column/detail/<?php echo $r['Column']['id'] ?>">
                   <?php $fname = isset($r['ColumnHeaderContent']['ColumnImage']['file_name']) ? $r['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                   <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                   <img src="<?php echo $fname?>" width="100%">
               </a>
           </div>
           <div class="col-xs-9" style="height:95px; padding:0 15px 0 13px;">
               <div class="ttlList">
                   <a style="color:#666" href="/column/detail/<?php echo $r['Column']['id'] ?>">
                       <?php 
                         $s = $r['ColumnHeaderContent']['title'];
                         $l = mb_strlen($s);
                         if ($l > 39){
                           $s = mb_substr($s, 0, 38);
                           $s .= '...';
                         }
                         echo $s;
                       ?>
                   </a>
               </div>
               <div class="info" style="position:absolute;left:16px;bottom:2px">
                   <span class="date"><?php echo date_format(new DateTime($r['Column']['modified']), 'Y年m月d日') ?></span> | <span class="cat"><?php echo !empty($r['category_id']) ? $option['category'][$r['category_id']] : '未設定'; ?></span>
               </div>
               <?php 
               $m = new DateTime($r['Column']['modified']);
               $now = new DateTime();
               $diff = $m->diff($now);
               if ($diff->format('%a') < 3): /* 3日以内をnewにする */ ?>
                   <div class="new">
                       <img src="/img/column/sp/new.png" width="30%">
                   </div>
               <?php endif; ?>
           </div>
           <div class="columnSep">&nbsp;</div>
       </div>
   <?php endforeach; ?>
   <!-- Pagination ADD -->
   <div class="col-xs-12" style="padding: auto; text-align: center;">
   <nav aria-label="Page navigation">
       <ul class="pagination justify-content-center">
          <?php echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentClass' => 'active','currentTag' => 'a')); ?> 
       </ul>
   </nav>
   </div>
   <!-- バナー -->
   <div class="row">
       <div class="col-xs-12 fbBox">
           <h5 class="banner"><a href="/register/"><img src="/img/column/sp/banner_kaigo_1.png" width="100%"></a></h5>
       </div>
   </div>
   <!-- Facebook -->
   <div align="center">
      <div class="fb-page" data-href="https://www.facebook.com/clickjobkaigo/" data-tabs="timeline" data-width="500" data-height="210" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/clickjobkaigo/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/clickjobkaigo/">クリックジョブ介護</a></blockquote></div>
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
             <div class="col-xs-3" style="padding:0 0 0 10px">
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
   <div class="row">
       <div class="col-xs-12">
           <h5 class="banner"><a href="/register/"><img src="/img/column/sp/banner_kaigo_2.png" width="100%"></a></h5>
       </div>
   </div>
   <!-- 総合ランキング -->
   <div class="row">
       <div class="col-xs-12">
           <h5 class="banner"><img src="/img/column/sp/ttl_general_ranking.png" width="100%" alt="総合ランキング"></h5>
       </div>
   </div>
   <?php foreach ($ranking['CoordRanking'] as $i => $rr): ?>
       <?php if (!empty($rr)): ?>
         <div class="row columnList">
             <div class="col-xs-3" style="padding:0 0 0 10px">
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
           <a href="/register/"><img src="/img/column/sp/banner_kaigo_3.png" width="100%"></a>
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
               <img src="/img/column/sp/category_footer.png" width="100%">
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
    var w = $(window).width();
    var h =  (w*0.75);
    $(".slidePic, .bx-viewport").css('height', h + 'px');
});
</script>

</body>
</html>
