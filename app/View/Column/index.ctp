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
<!--meta name="viewport" content="width=device-width, initial-scale=1"-->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
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
        slideWidth  : 515,
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

    var flg = "close";

    $("#button").click(function(){
        $('#slide_menu').fadeIn("slow", function(){
            flg = "open";
        });
    });
    $("#close").click(function(){
          $('#slide_menu').fadeOut("slow");  
          flg = "close";
    });
    $(document).on('click touchend', function(event) {
      if (flg == 'open' && !$(event.target).closest('#slide_menu').length) {
        $('#slide_menu').fadeOut("slow");  
        flg = "close";
      }
    });
    $(".btnUpper").click(function(){
        $('body').scrollTop(0);
    });
});    
</script>
<link rel="stylesheet" type="text/css" href="/css/column.css" media="screen">
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
<nav class="navbar navbar-default">
    <div class="col-xs-12">
        <div class="col-xs-4" style="padding: 45px 0">
            <?php echo $this->Form->create(false, array('type' => 'post',
                                                        'class' => 'form-inline',
                                                           'url' => array('controller' => 'column', 'action' => 'index')
            )); ?>
            <div class="form-group">
                <?php echo $this->Form->input('Column.word', array(
                                              'label' => false,
                                              'div' => false,
                                              'class' => 'form-control search',
                                              'placeholder' => '検索したいキーワード',
                                              'required' => false
                      ))
                 ?>
             </div>
             <button type="submit" class="btn btn-xs btn-search"><img src="/img/column/pc/search_btn.png" width="50%"></button>
            <?php echo $this->Form->end() ?>
        </div>
        <div class="col-xs-4 text-center">
            <a href="/column"><img src="/img/column/sp/logo.png" style="width:325px;padding-top: 8px;"></a>
        </div>
        <div class="col-xs-4 text-right" style="padding: 40px 0">
            <a href="https://www.facebook.com/clickjobkaigo/?fref=ts"><img style="margin-right:3px;" src="/img/column/sp/fb_share_circle.png" width="30px;"></a>
            <a href="https://twitter.com/clickjobkaigo"><img style="margin-right:14px;" src="/img/column/sp/tweet_circle.png" width="30px;"></a>
            <a id="button"><img style="margin-right:15px;" src="/img/column/sp/menubtn.png" width="50px;"></a>
        </div>
    </div>
</nav>

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
                            <?php if (!empty($rt['ColumnCategory']['category_id']) && $rt['ColumnCategory']['category_id'] == 1): ?>
                                <img style="margin:1px 0 9px 0;" src="/img/column/pc/icon_news.png" width="40px" class="bannerIcon">&nbsp;<span class="sliderCat">
                            <?php elseif (!empty($rt['ColumnCategory']['category_id']) && $rt['ColumnCategory']['category_id'] == 2): ?>
                                <img style="margin:4px 6px 8px 3px;" src="/img/column/pc/icon_column.png" width="31px" class="bannerIcon">&nbsp;<span class="sliderCat">
                            <?php elseif (!empty($rt['ColumnCategory']['category_id']) && $rt['ColumnCategory']['category_id'] == 3): ?>
                                <img style="margin:0 12px 7px 11px;" src="/img/column/pc/icon_knowlege.png" width="17px" class="bannerIcon">&nbsp;<span class="sliderCat">
                            <?php endif; ?>
                            <?php echo !empty($rt['ColumnCategory']['category_id']) ? $category[$rt['ColumnCategory']['category_id']]['name'] : '未設定' ?>
                            </span><br />
                            <span class="sliderText"><?php echo $rt['ColumnHeaderContent']['title'] ?></span>
                        </div>
                    </a>
                <?php endif; ?>
            </li>
            <?php endfor; ?>
        </ul>
        <div id="slideFilterL"></div>
        <div id="slideFilterR"></div>
    </div><!-- --/#topSlider ---->
</div>

<div class="container allContent">
    <div class="row">
        <div class="col-xs-9" style="width:72%;">
            <!-- 記事リスト -->
            <?php foreach ($column as $idx=>$r): ?>
                <div class="row columnListMain">
                    <a href="/column/detail/<?php echo $r['Column']['id'] ?>">
                    <div class="col-xs-3" style="padding-left:0">
                        <?php 
                        $m = new DateTime($r['Column']['modified']);
                        $now = new DateTime();
                        $diff = $m->diff($now);
                        if ($diff->format('%a') < 3): /* 3日以内をnewにする */ ?>
                            <div class="new">
                                <img src="/img/column/pc/new.png" width="40%">
                            </div>
                        <?php endif; ?>
                        <?php $fname = isset($r['ColumnHeaderContent']['ColumnImage']['file_name']) ? $r['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                        <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                        <img src="<?php echo $fname?>" width="100%">
                    </div>
                    <div class="col-xs-9" style="height:110px">
                        <div class="mainCat">
                            <span class="cat"><?php echo !empty($r['ColumnCategory']['category_id']) ? $category[$r['ColumnCategory']['category_id']]['name'] : '未設定'; ?></span>
                        </div>
                        <div class="ttlMainList">
                                <?php echo $r['ColumnHeaderContent']['title'] ?>
                        </div>
                        <div class="info" style="position:absolute;left:16px;bottom:2px">
                            <span class="date"><?php echo date_format(new DateTime($r['Column']['modified']), 'Y年m月d日') ?></span>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="row columnSepMain"></div>
            <?php endforeach; ?>
            <!-- Pagination -->
            <div class="col-xs-12" style="padding: auto; text-align: left;">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentClass' => 'active','currentTag' => 'a')); ?>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="col-xs-3" style="width:28%">
            <!-- バナー -->
            <div class="row">
                <div class="col-xs-12">
                    <a href="/register/"><img src="/img/column/pc/banner_kaigo_1.png" width="100%"></a>
                </div>
            </div>
            <!-- カテゴリー -->
            <div class="row">
                <div class="col-xs-12">
                    <h5 class="banner"><img src="/img/column/pc/ttl_category.png" width="100%" alt="カテゴリー"></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 catSep">
                    <a href="/column/news">
                        <img src="/img/column/pc/btn_news_sidebar.png" width="100%" alt="ニュース">
                    </a>
                </div>
                <div class="col-xs-12 catSep">
                    <a href="/column/column">
                        <img src="/img/column/pc/btn_column_sidebar.png" width="100%" alt="コラム">
                    </a>
                </div>
                <div class="col-xs-12 catSep">
                    <a href="/column/knowledge">
                        <img src="/img/column/pc/btn_knowledge_sidebar.png" width="100%" alt="知識">
                    </a>
                </div>
            </div>
            <!-- おすすめランキング -->
            <div class="row">
                <div class="col-xs-12">
                    <h5 class="banner"><img src="/img/column/pc/ttl_recommend_ranking.png" width="100%" alt="おすすめランキング"></h5>
                </div>
            </div>
            <?php foreach ($ranking['RecommendRanking'] as $i => $rr): ?>
                <?php if (!empty($rr)): ?>
                    <div class="row columnList">
                        <div class="col-xs-3 sideRankImg" style="width:30%;">
                            <a href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                                <?php $fname = isset($rr['ColumnHeaderContent']['ColumnImage']['file_name']) ? $rr['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                                <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                                <img src="<?php echo $fname ?>" width="100%">
                            </a>
                            <div class="rank">
                                <?php $rank=$i+1 ?>
                                <img src="/img/column/pc/rank_num<?php echo $rank ?>.png" width="40%">
                            </div>
                        </div>
                        <div class="col-xs-9 ranking-title" style="width:70%;">
                            <div class="ttlList">
                                <a style="color:#333" href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                                    <?php 
                                      $s = $rr['ColumnHeaderContent']['title'];
                                      $l = mb_strlen($s);
                                      if ($l > 42){
                                        $s = mb_substr($s, 0, 41);
                                        $s .= '...';
                                      }
                                      echo $s;
                                    ?>
                                </a>
                            </div>
                            <div class="info ranking-info">
                                <span class="cat">
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
                    <a href="/register/"><img src="/img/column/pc/banner_kaigo_2.png" width="100%"></a>
                </div>
            </div>
            <!-- 総合ランキング -->
            <div class="row">
                <div class="col-xs-12">
                    <h5 class="banner"><img src="/img/column/pc/ttl_general_ranking.png" width="100%" alt="総合ランキング"></h5>
                </div>
            </div>
            <?php foreach ($ranking['CoordRanking'] as $i => $rr): ?>
                <?php if (!empty($rr)): ?>
                    <div class="row columnList">
                        <div class="col-xs-3 sideRankImg" style="width:30%;">
                            <a href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                                <?php $fname = isset($rr['ColumnHeaderContent']['ColumnImage']['file_name']) ? $rr['ColumnHeaderContent']['ColumnImage']['file_name'] : '' ?>
                                <?php if (!empty($fname)) $fname = '/read/seo_column/'.$fname ?>
                                <img src="<?php echo $fname ?>" width="100%">
                            </a>
                            <div class="rank">
                                <?php $rank=$i+1 ?>
                                <img src="/img/column/pc/rank_num<?php echo $rank ?>.png" width="40%">
                            </div>
                        </div>
                        <div class="col-xs-9 ranking-title" style="width:70%;">
                            <div class="ttlList">
                                <a style="color:#333" href="/column/detail/<?php echo $rr['Column']['id'] ?>">
                                    <?php 
                                      $s = $rr['ColumnHeaderContent']['title'];
                                      $l = mb_strlen($s);
                                      if ($l > 42){
                                        $s = mb_substr($s, 0, 41);
                                        $s .= '...';
                                      }
                                      echo $s;
                                    ?>
                                </a>
                            </div>
                            <div class="info ranking-info">
                                <span class="cat">
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
                    <a href="/register/"><img src="/img/column/pc/banner_kaigo_3.png" width="100%"></a>
                </div>
            </div>
            <!-- Social Button -->
            <!-- FaceBook -->
            <div class="fb-page fbBox" data-href="https://www.facebook.com/clickjobkaigo/" data-tabs="timeline" data-width="500" data-height="225" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/clickjobkaigo/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/clickjobkaigo/">クリックジョブ介護</a></blockquote></div>
        </div>
    </div>
    <!-- Page Upper Direction ADD -->
    <div style="width:100%; margin-bottom: -20px;">
        <div style="text-align:right" class="btnUpper">
          <img style="margin:45px 10px 0 0" src="/img/column/sp/btn_upper_direction.png" width="75px">
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-9" style="width:72%;">
            <img src="/img/column/pc/category_footer.png" width="120px">
            <a href="/column/news" class="footerBtn"><img style="margin-left:19px;" src="/img/column/pc/btn_news_footer.png" width="150px"></a>
            <a href="/column/column" class="footerBtn"><img style="margin-left:9px;" src="/img/column/pc/btn_column_footer.png" width="145px"></a>
            <a href="/column/knowledge" class="footerBtn"><img style="margin-left:9px;" src="/img/column/pc/btn_knowledge_footer.png" width="110px"></a>
            <div>
                <table class="footerMenu">
                    <tr>
                        <td>
                            ▶︎  <a href="/">TOPページ</a>
                        </td>
                        <td>
                            ▶︎  <a href="/company/">会社概要</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            ▶︎  <a href="/privacy">個人情報の取り扱い</a>
                        </td>
                        <td>
                            ▶︎  <a href="/policy">利用規約</a>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
            <div class="col-xs-3 text-right footerTxt" style="width:28%;">
            <a href="/"><img style="margin-bottom:12px" src="/img/column/pc/logo_footer.png" width="100%"></a>
           介護職求人支援サービス『クリックジョブ介護』 <br />
           運営会社：日本メディカル株式会社<br />
           （厚生労働大臣許可）13-ユ-303765<br /><br />
            Copyright © Japan Medical Ltd. All Rights Reserved.
            </div>
        </div>
    </div>
</footer>

<div id='slide_menu'>
    <div class="slide_content">
        <div class="col-xs-12">
            <img src="/img/column/sp/icon_menu.png" width="100%">
            <a id="close">
                <img src="/img/column/sp/menubtn_close.png" width="40%" class="menuClose">
            </a>
            <div class="navTxt">
                <div class="menuSep"><a href="/column"><div style="width:45px;float:left;"><img style="margin-left:9px;" src="/img/column/sp/icon_top.png" width="21px" class="menuicon"></div>TOP</a></div>
                <div class="menuSep"><a href="/column/news"><div style="width:45px;float:left;"><img src="/img/column/sp/icon_news.png" width="30.5px" class="menuicon"></div>ニュース</a></div>
                <div class="menuSep"><a href="/column/column"><div style="width:45px;float:left;"><img style="margin-left:6.8px;" src="/img/column/sp/icon_column.png" width="23.5px" class="menuicon"></div>コラム</a></div>
                <div class="menuSep"><a href="/column/knowledge"><div style="width:45px;float:left;"><img style="margin-left:12px;" src="/img/column/sp/icon_knowledge.png" width="13px" class="menuicon"></div>知識</a></div>
            </div>
            <a href="/register/"><img src="/img/column/sp/banner_jobchange.png" width="100%"></a>
        </div>
    </div>
</div>

</body>
</html>
