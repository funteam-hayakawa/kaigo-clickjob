<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>クリックジョブ介護</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link href="http://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/pc/top.css">
    <link rel="stylesheet" href="/css/pc/top_add.css">
    <link rel="stylesheet" href="/css/pc/parts.css">
    <link rel="stylesheet" type="text/css" href="css/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick/slick-theme.css"/>
  </head>
  <body>
  <?php echo $this->element('header'); ?>
  <div class="key-visual">
    <div class="slide1"><img src="/img/pc/key01.png" alt="みんなが選んだNo1"></div>
    <div class="slide2"><img src="/img/pc/key02.png" alt=""></div>
  </div>

  <div class="key-button">
    <a href="/register"><img src="/img/pc/bt-key1.png" alt="ご登録はこちら"></a>
    <a href="/search"><img src="/img/pc/bt-key2.png" alt="求人を検索する"></a>
  </div>
  <div class="wrap">
    <div class="main">
      <div class="contents-1">
        <h2>
          <img src="/img/pc/h-icon-area.png" alt="">
          <span class="power">「エリア」</span>
          <span class="text">から介護求人を探す</span>
        </h2>

        <div class="search-map">
          <img src="/img/pc/top-map.png" alt="">

          <a class="okayama" href="/area/okayama"><span class="caret-right"></span><span class="text">岡山</span></a>
          <a class="shimane" href="/area/shimane"><span class="caret-right"></span><span class="text">島根</span></a>
          <a class="tokushima" href="/area/tokushima"><span class="caret-right"></span><span class="text">徳島</span></a>
          <a class="hiroshima" href="/area/hiroshima"><span class="caret-right"></span><span class="text">広島</span></a>
          <a class="yamaguchi" href="/area/yamaguchi"><span class="caret-right"></span><span class="text">山口</span></a>
          <a class="ehime" href="/area/ehime"><span class="caret-right"></span><span class="text">愛媛</span></a>
          <a class="tottori" href="/area/tottori"><span class="caret-right"></span><span class="text">鳥取</span></a>
          <a class="kagawa" href="/area/kagawa"><span class="caret-right"></span><span class="text">香川</span></a>
          <a class="kouchi" href="/area/kouchi"><span class="caret-right"></span><span class="text">高知</span></a>

          <a class="osaka" href="/area/osaka"><span class="caret-right"></span><span class="text">大阪</span></a>
          <a class="kyoto" href="/area/kyoto"><span class="caret-right"></span><span class="text">京都</span></a>
          <a class="nara" href="/area/nara"><span class="caret-right"></span><span class="text">奈良</span></a>
          <a class="hyogo" href="/area/hyogo"><span class="caret-right"></span><span class="text">兵庫</span></a>
          <a class="shiga" href="/area/shiga"><span class="caret-right"></span><span class="text">滋賀</span></a>
          <a class="wakayama" href="/area/wakayama"><span class="caret-right"></span><span class="text">和歌山</span></a>

          <a class="hokkaido" href="/area/hokkaido"><span class="caret-right"></span><span class="text">北海道</span></a>
          <a class="akita" href="/area/akita"><span class="caret-right"></span><span class="text">秋田</span></a>
          <a class="iwate" href="/area/iwate"><span class="caret-right"></span><span class="text">岩手</span></a>
          <a class="fukushima" href="/area/fukushima"><span class="caret-right"></span><span class="text">福島</span></a>
          <a class="aomori" href="/area/aomori"><span class="caret-right"></span><span class="text">青森</span></a>
          <a class="yamagata" href="/area/yamagata"><span class="caret-right"></span><span class="text">山形</span></a>
          <a class="miyagi" href="/area/miyagi"><span class="caret-right"></span><span class="text">宮城</span></a>

          <a class="nigata" href="/area/niigata"><span class="caret-right"></span><span class="text">新潟</span></a>
          <a class="ishikawa" href="/area/ishikawa"><span class="caret-right"></span><span class="text">石川</span></a>
          <a class="yamanashi" href="/area/yamanashi"><span class="caret-right"></span><span class="text">山梨</span></a>
          <a class="toyama" href="/area/toyama"><span class="caret-right"></span><span class="text">富山</span></a>
          <a class="nagano" href="/area/nagano"><span class="caret-right"></span><span class="text">長野</span></a>
          <a class="fukui" href="/area/fukui"><span class="caret-right"></span><span class="text">福井</span></a>

          <a class="tokyo" href="/area/tokyo"><span class="caret-right"></span><span class="text">東京</span></a>
          <a class="tochigi" href="/area/tochigi"><span class="caret-right"></span><span class="text">栃木</span></a>
          <a class="kanagawa" href="/area/kanagawa"><span class="caret-right"></span><span class="text">神奈川</span></a>
          <a class="ibaraki" href="/area/ibaraki"><span class="caret-right"></span><span class="text">茨城</span></a>
          <a class="saitama" href="/area/saitama"><span class="caret-right"></span><span class="text">埼玉</span></a>
          <a class="gunma" href="/area/gunma"><span class="caret-right"></span><span class="text">群馬</span></a>
          <a class="chiba" href="/area/chiba"><span class="caret-right"></span><span class="text">千葉</span></a>

          <a class="aichi" href="/area/aichi"><span class="caret-right"></span><span class="text">愛知</span></a>
          <a class="gifu" href="/area/gifu"><span class="caret-right"></span><span class="text">岐阜</span></a>
          <a class="shizuoka" href="/area/shizuoka"><span class="caret-right"></span><span class="text">静岡</span></a>
          <a class="mie" href="/area/mie"><span class="caret-right"></span><span class="text">三重</span></a>

          <a class="fukuoka" href="/area/fukuoka"><span class="caret-right"></span><span class="text">福岡</span></a>
          <a class="nagasaki" href="/area/nagasaki"><span class="caret-right"></span><span class="text">長崎</span></a>
          <a class="oita" href="/area/oita"><span class="caret-right"></span><span class="text">大分</span></a>
          <a class="kagoshima" href="/area/kagoshima"><span class="caret-right"></span><span class="text">鹿児島</span></a>
          <a class="saga" href="/area/saga"><span class="caret-right"></span><span class="text">佐賀</span></a>
          <a class="kumamoto" href="/area/kumamoto"><span class="caret-right"></span><span class="text">熊本</span></a>
          <a class="miyazaki" href="/area/miyazaki"><span class="caret-right"></span><span class="text">宮崎</span></a>
          <a class="okinawa" href="/area/okinawa"><span class="caret-right"></span><span class="text">沖縄</span></a>
        </div>
      </div><!-- /.contents-1 -->
      <div class="contents-2">
        <h2>
          <img src="/img/pc/h-icon-king.png" alt="">
          <span class="power">「人気のエリア」</span>
          <span class="text">から介護求人を探す</span>
        </h2>

        <div class="padding-10"></div>

        <div class="search-area">
          <div class="area">
            <a href="/area/tokyo/c131121"><span class="caret-right"></span> 世田谷区</a>
            <a href="/area/tokyo/c131148"><span class="caret-right"></span> 中野区</a>
            <a href="/area/tokyo/c131091"><span class="caret-right"></span> 品川区</a>
            <a href="/area/tokyo/c131105"><span class="caret-right"></span> 目黒区</a>
            <a href="/area/tokyo/c131016"><span class="caret-right"></span> 千代田区</a>
            <a href="/area/tokyo/c131032"><span class="caret-right"></span> 港区</a>
            <a href="/area/tokyo/c131211"><span class="caret-right"></span> 足立区</a>
            <a href="/area/tokyo/c131229"><span class="caret-right"></span> 葛飾区</a>
          </div>

          <div class="area">
            <a href="/area/tokyo/c131237"><span class="caret-right"></span> 江戸川区</a>
            <a href="/area/tokyo/c131024"><span class="caret-right"></span> 中央区</a>
            <a href="/area/tokyo/c132012"><span class="caret-right"></span> 八王子市</a>
            <a href="/area/tokyo/c132021"><span class="caret-right"></span> 立川市</a>
            <a href="/area/tokyo/c132098"><span class="caret-right"></span> 町田市</a>
            <a href="/area/kanagawa/s000005"><span class="caret-right"></span> 横浜市</a>
            <a href="/area/kanagawa/s000006"><span class="caret-right"></span> 川崎市</a>
            <a href="/area/kanagawa/c141011"><span class="caret-right"></span> 鶴見区</a>
          </div>

          <div class="area">
            <a href="/area/saitama/s000003"><span class="caret-right"></span> さいたま市</a>
            <a href="/area/saitama/c112038"><span class="caret-right"></span> 川口市</a>
            <a href="/area/chiba/s000004"><span class="caret-right"></span> 千葉市</a>
            <a href="/area/niigata/s000008"><span class="caret-right"></span> 新潟市</a>
            <a href="/area/ishikawa/c172014"><span class="caret-right"></span> 金沢市</a>
            <a href="/area/miyagi/s000002"><span class="caret-right"></span> 仙台市</a>
            <a href="/area/aichi/s000011"><span class="caret-right"></span> 名古屋市</a>
            <a href="/area/kyoto/s000012"><span class="caret-right"></span> 京都市</a>
          </div>

          <div class="area">
            <a href="/area/osaka/s000013"><span class="caret-right"></span> 大阪市</a>
            <a href="/area/osaka/s000014"><span class="caret-right"></span> 堺市</a>
            <a href="/area/hiroshima/s000017"><span class="caret-right"></span> 広島市</a>
            <a href="/area/hyogo/s000015"><span class="caret-right"></span> 神戸市</a>
            <a href="/area/fukuoka/s000018"><span class="caret-right"></span> 福岡市</a>
            <a href="/area/fukuoka/s000019"><span class="caret-right"></span> 北九州市</a>
          </div>
        </div>
      </div><!-- /.contents-2 -->
      <div class="contents-3">
        <h2>
          <img src="/img/pc/h-icon-search.png" alt="">
          <span class="power">「こだわり条件」</span>
          <span class="text">から介護求人を探す</span>
        </h2>
        <?php echo $this->Form->create(false, array('type' => 'post',
                                                    'class' => 'form-horizontal search-conditions',
                                                    'url' => array('controller' => 'search', 'action' => 'result')
        )); ?>
          <div class="question-1">
            <div class="key">
              <span>職種</span>
            </div>
            <div class="value">
              <?php $ct = 0; $divOpen = 0;?>
              <?php foreach ($occupation as $idx => $elm): ?>
                <?php if ($ct % 3 == 0): ?>
                  <?php $divOpen = 1; ?>
                  <div class="items">
                <?php endif; ?>
                  <?php echo '<label>' ?>
                  <?php echo $this->Form->input('Search.occupation.', array(
                                                'type' => 'checkbox',
                                                'id' => 'search_occupation_'.$idx,
                                                'div' => false,
                                                'hiddenField' => false,
                                                'label' => false,
                                                'required' => false,
                                                'value' => $idx,
                        ));
                  ?>
                  <?php echo $elm.'</label>' ?>
                <?php if ($ct % 3 == 2): ?>
                  <?php $divOpen = 0; ?>
                  </div>
                <?php endif; ?>
                <?php $ct++; ?>
              <?php endforeach; ?>
              <?php if ($divOpen): ?>
                <?php $divOpen = 0; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="clearfix"></div>
          </div><!-- /.question-1 -->
          <div class="border"></div>
          <div class="question-2">
            <div class="key">
              <span>働く施設</span>
            </div>
            <div class="value">
              <?php $ct = 0; $divOpen = 0;?>
              <?php foreach ($institution_type_search_disp as $idx => $elm): ?>
                <?php if ($ct % 3 == 0): ?>
                  <?php $divOpen = 1; ?>
                  <div class="items">
                <?php endif; ?>
                  <?php echo '<label>' ?>
                  <?php echo $this->Form->input('Search.institution_type.', array(
                                                'type' => 'checkbox',
                                                'id' => 'search_institution_type_'.$idx,
                                                'div' => false,
                                                'hiddenField' => false,
                                                'label' => false,
                                                'required' => false,
                                                'value' => $idx,
                        ));
                  ?>
                  <?php echo $elm.'</label>' ?>
                <?php if ($ct % 3 == 2): ?>
                  <?php $divOpen = 0; ?>
                  </div>
                <?php endif; ?>
                <?php $ct++; ?>
              <?php endforeach; ?>
              <?php if ($divOpen): ?>
                <?php $divOpen = 0; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="clearfix"></div>
          </div><!-- /.question-2 -->
          <div class="border"></div>
          <div class="question-3">
            <div class="key">
              <span>もっている資格</span>
            </div>
            <div class="value">
              <?php $ct = 0; $divOpen = 0;?>
              <?php foreach ($application_license_search_disp as $idx => $elm): ?>
                <?php if ($divOpen == 0): ?>
                  <?php $divOpen = 1; ?>
                  <div class="items">
                <?php endif; ?>
                  <?php echo '<label>' ?>
                  <?php echo $this->Form->input('Search.application_license.', array(
                                                'type' => 'checkbox',
                                                'id' => 'search_application_license_'.$idx,
                                                'div' => false,
                                                'hiddenField' => false,
                                                'label' => false,
                                                'required' => false,
                                                'value' => $idx,
                        ));
                  ?>
                  <?php echo $elm.'</label>' ?>
                <?php if ((($ct % 3 == 2) && ($ct != 8) && ($ct < 9)) || $ct == 9): ?>
                  <?php $divOpen = 0; ?>
                  </div>
                <?php endif; ?>
                <?php $ct++; ?>
              <?php endforeach; ?>
              <?php if ($divOpen): ?>
                <?php $divOpen = 0; ?>
                </div>
              <?php endif; ?>
              </div>
            <div class="clearfix"></div>
          </div><!-- /.question-3 -->
          <div class="border"></div>
          <div class="question-4">
            <div class="key">
              <span>雇用形態</span>
            </div>
            <div class="value">
              <?php $ct = 0; $divOpen = 0;?>
              <?php foreach ($employment_type_search_disp as $idx => $elm): ?>
                <?php if ($ct % 3 == 0): ?>
                  <?php $divOpen = 1; ?>
                  <div class="items">
                <?php endif; ?>
                  <?php echo '<label>' ?>
                  <?php echo $this->Form->input('Search.employment_type.', array(
                                                'type' => 'checkbox',
                                                'id' => 'search_employment_type_'.$idx,
                                                'div' => false,
                                                'hiddenField' => false,
                                                'label' => false,
                                                'required' => false,
                                                'value' => $idx,
                        ));
                  ?>
                  <?php echo $elm.'</label>' ?>
                <?php if ($ct % 3 == 2): ?>
                  <?php $divOpen = 0; ?>
                  </div>
                <?php endif; ?>
                <?php $ct++; ?>
              <?php endforeach; ?>
              <?php if ($divOpen): ?>
                <?php $divOpen = 0; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="clearfix"></div>
          </div><!-- /.question-4 -->
          <div class="border"></div>
          <div class="question-5">
            <div class="key">
              <span>働きやすさ</span>
            </div>
            <div class="value">
              <?php $ct = 0; $divOpen = 0;?>
              <?php foreach ($recruit_flex_type as $idx => $elm): ?>
                <?php if ($ct % 3 == 0): ?>
                  <?php $divOpen = 1; ?>
                  <div class="items">
                <?php endif; ?>
                  <?php echo '<label>' ?>
                  <?php echo $this->Form->input('Search.recruit_flex_type.', array(
                                                'type' => 'checkbox',
                                                'id' => 'search_recruit_flex_type_'.$idx,
                                                'div' => false,
                                                'hiddenField' => false,
                                                'label' => false,
                                                'required' => false,
                                                'value' => $idx,
                        ));
                  ?>
                  <?php echo $elm.'</label>' ?>
                <?php if ($ct % 3 == 2): ?>
                  <?php $divOpen = 0; ?>
                  </div>
                <?php endif; ?>
                <?php $ct++; ?>
              <?php endforeach; ?>
              <?php if ($divOpen): ?>
                <?php $divOpen = 0; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="clearfix"></div>
          </div><!-- /.question-5 -->
          <div class="border"></div>

          <div class="question-6">
            <div class="key">
              <span>働く時間</span>
            </div>
            <div class="value">
              <?php $ct = 0; $divOpen = 0;?>
              <?php foreach ($particular_ttl_hour as $idx => $elm): ?>
                <?php if ($ct % 3 == 0): ?>
                  <?php $divOpen = 1; ?>
                  <div class="items">
                <?php endif; ?>
                  <?php echo '<label>' ?>
                  <?php echo $this->Form->input('Search.particular_ttl_hour.', array(
                                                'type' => 'checkbox',
                                                'id' => 'search_particular_ttl_hour_'.$idx,
                                                'div' => false,
                                                'hiddenField' => false,
                                                'label' => false,
                                                'required' => false,
                                                'value' => $idx,
                        ));
                  ?>
                  <?php echo $elm.'</label>' ?>
                <?php if ($ct % 3 == 2): ?>
                  <?php $divOpen = 0; ?>
                  </div>
                <?php endif; ?>
                <?php $ct++; ?>
              <?php endforeach; ?>
              <?php if ($divOpen): ?>
                <?php $divOpen = 0; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="clearfix"></div>
          </div><!-- /.question-6 -->
          <div class="form-group text-center">
            <a href="/register"><img src="/img/pc/2c-button-01.png" alt="未公開求人を見る"></a>
            <input type="image" src="/img/pc/2c-button-02.png" alt="検索する">
          </div>
        <?php echo $this->Form->end() ?>
      </div><!-- /.contents-3 -->
      <div class="contents-4">
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
      </div><!-- /.contents-4 -->
      <div class="contents-5">
        <h2>
          <img src="/img/pc/h-icon-fav.png" alt="">
          <span class="text">クリックジョブ介護で自分にぴったりの職場探し</span>
        </h2>

        <div class="padding-10"></div>

        <div class="point-description">
          <div class="text">
            <div class="title">
              <span class="power">Point1</span>
              <span>一番オススメの求人をピックアップ</span>
            </div>

            <div class="body">
              クリックジョブ介護には一般には公開されていない「好条件・高待遇」のレア求人が多数ございます！
              エージェントによるヒアリングのもと、非公開求人を含めた多数の求人の中から、
              あなたにぴったりな求人を紹介します。
            </div>
          </div>

          <div class="image">
            <img src="/img/pc/point1.jpg" alt="一番オススメの求人をピックアップ">
          </div>

          <div class="clearfix"></div>
          <div class="padding-20"></div>
          <div class="border"></div>
          <div class="padding-20"></div>

          <div class="text">
            <div class="title">
              <span class="power">Point2</span>
              <span>職場の雰囲気など詳しい情報を先に知れる</span>
            </div>

            <div class="body">
              クリックジョブ介護では、足を運んでみないとわからない施設の雰囲気や職員の人柄などの詳しい情報も蓄積しています。
              事前に詳しい情報をお伝えすることができるので、効率よく新しい職場を見つけることができます。
            </div>
          </div>

          <div class="image">
            <img src="/img/pc/point2.jpg" alt="職場の雰囲気など詳しい情報を先に知れる">
          </div>

          <div class="clearfix"></div>
          <div class="padding-20"></div>
          <div class="border"></div>
          <div class="padding-20"></div>

          <div class="text">
            <div class="title">
              <span class="power">Point3</span>
              <span>豊富な転職ノウハウでチャンスを逃さない</span>
            </div>

            <div class="body">
              介護業界で多くの紹介実績を誇る転職のエージェントが、好印象を与える履歴書の記入方法や失敗しない面接での受け答えなど、最初から最後まで手厚く丁寧にサポートしていきます。
              面倒な面接日程や入職予定日の調整や、お休みや給与の交渉も全ておまかせください！
            </div>
          </div>

          <div class="image">
            <img src="/img/pc/point3.jpg" alt="豊富な転職ノウハウでチャンスを逃さない">
          </div>

          <div class="clearfix"></div>
          <div class="padding-20"></div>

          <div class="text-center">
            <img src="/img/pc/strong-tel.png" alt="">
          </div>

          <div class="padding-20"></div>

          <div class="text-center">
            <img src="/img/pc/campaign.png" alt="">
          </div>

          <div class="padding-10"></div>

          <div class="button-01">
            <a href="/register">
              <img src="/img/pc/1c-button-01.png" alt="求人を紹介してもらう">
            </a>
          </div>
        </div>
      </div><!-- /.contents-5 -->
      <section class="regist-27cp mt20">
        <div class="step_wrap">
          <h2>
          <img src="/img/pc/h-icon-call.png" alt="">
          <span class="text">介護の転職支援サービスお申し込み</span>
          <span class="power-reverse">簡単<span class="seconds">30</span><span class="byou">秒</span>登録</span>
        </h2>

        <div id="carousel-step" class="service-form carousel slide" data-ride="carousel" data-interval="false">
          <form action="" class="carousel-inner" role="listbox">
              <!-- -->
              <div class="step-1 item active">
                <div class="form-head">
                  <img src="/img/pc/step1.png" alt="">
                  <span>保有資格を選択してください</span>
                  <img class="pull-right" src="/img/pc/step1-here.png" alt="">
                </div>

                <div class="question-1">

                  <div class="value">
                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">介護福祉士</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">介護職員実務者研修</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">ヘルパー１級</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">介護職員基礎研修</span>
                      </label>
                    </div>

                    <div class="items">
                     <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text twoline">初任者研修<br>(ヘルパー２級)</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">ケアマネジャー</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">社会福祉主事</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">社会福祉士</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">PT・OT</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">正・准看護師</span>
                      </label>
                    </div>

                    <div class="items">
                      <label>
                        <input type="checkbox">
                        <span class="checkbox-icon"></span>
                        <span class="text">その他</span>
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>
                </div>
                <div class="button-02">
                  <a class="carousel-control" href="#carousel-step" role="button" data-slide="next">
                    <img src="/img/pc/1c-button-02.png" alt="次のステップへ">
                  </a>
                </div>
              </div>
<!--end step1 -->

              <div class="step-2 item">
                <div class="form-head">
                  <img src="/img/pc/step2.png" alt="">
                  <span>誕生年・お電話番号を入力</span>
                  <img class="pull-right" src="/img/pc/step2-here.png" alt="">
                </div>
                <div class="question-2">
                  <div class="key">
                    <span class="text">誕生年</span>
                    <span class="power-reverse">必須</span>
                  </div>
                  <div class="value form-group">
                    <div class="items">
                      <div>
                        <select class="form-control">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="key">
                    <span class="text">電話番号</span>
                    <span class="power-reverse">必須</span>
                  </div>
                  <div class="value form-group">
                    <div class="items">
                      <div>
                        <input class="form-control" type="text">
                      </div>
                    </div>
                  </div>
               </div><!-- /.question-2 -->

                <div class="button-02">
                  <a href="#carousel-step" class="button-02ex-prev glay_line carousel-control-prev" role="button" data-slide="prev">戻る</a>
                  <a href="#carousel-step" class="button-02ex-next btn_blue carousel-control-next" role="button" data-slide="next">次のステップへ</a>
                </div>

                <div class="padding-20"></div>
              </div><!-- /.step-2 -->

              <div class="step-3 item">
                <div class="form-head">
                  <img src="/img/pc/step3.png" alt="">
                  <span>住所を入力</span>

                  <img class="pull-right" src="/img/pc/step3-here.png" alt="">
                </div>

                <div class="question-3">
                  <div class="key">
                    <span class="text">郵便番号</span>
                    <span class="not-power-reverse">任意</span>
                  </div>
                  <div class="value form-group">
                    <div class="items">
                      <div>
                        <input class="form-control" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="key">
                    <span class="text">都道府県</span>
                    <span class="power-reverse">必須</span>
                  </div>
                  <div class="value form-group">
                    <div class="items">
                      <div>
                        <select class="form-control">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="key">
                    <span class="text">市区町村</span>
                    <span class="not-power-reverse">任意</span>
                  </div>
                  <div class="value form-group">
                    <div class="items">
                      <div>
                        <select class="form-control">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="clearfix"></div>
                </div><!-- /.question-3 -->

                <div class="padding-20"></div>

                <div class="button-02">
                  <a href="#carousel-step" class="button-02ex-prev glay_line carousel-control-prev" role="button" data-slide="prev">戻る</a>
                  <a href="#carousel-step" class="button-02ex-next btn_blue carousel-control-next" role="button" data-slide="next">次のステップへ</a>
                </div>
              </div><!-- /.step-3 -->

              <div class="step-4 item">
                <div class="form-head">
                  <img src="/img/pc/step4.png" alt="">
                  <span>お名前・パスワードを入力</span>

                  <img class="pull-right" src="/img/pc/step4-here.png" alt="">
                </div>

                <div class="question-4">
                  <div class="key">
                    <span class="text">お名前</span>
                    <span class="power-reverse">必須</span>
                  </div>

                  <div class="value">
                    <div class="items">
                      <input class="form-control" type="text">
                    </div>
                  </div>

                  <div class="clearfix"></div>
                  <div class="padding-20"></div>

                  <div class="key">
                    <span class="text">パスワード</span>
                    <span class="power-reverse">必須</span>
                  </div>

                  <div class="value">
                    <div class="items">
                      <input class="form-control" type="text">
                    </div>
                  </div>

                  <div class="clearfix"></div>
                  <div class="padding-20"></div>

                  <div class="key">
                    <span class="text">その他</span>
                    <span class="not-power-reverse">任意</span>
                  </div>

                  <div class="value">
                    <div class="items">
                      <input class="form-control" type="text">
                    </div>
                  </div>

                  <div class="clearfix"></div>
                  <div class="padding-20"></div>

                  <div class="key">
                 </div>

                  <div class="value">
                    <div class="items">
                    <input type="checkbox" checked="checked">
                    <span class="text">クリックジョブ介護の転職サポートを受ける。(無料)</span><br>
                    <span class="text">　※サポートが不要の方はチェックを外してください。</span>
                    </div>
                  </div>

                  <div class="clearfix"></div>
                </div><!-- /.question-4 -->

                <div class="padding-20"></div>

                <div class="button-02">
                  <a href="#carousel-step" class="button-02ex-prev glay_line carousel-control-prev2" role="button" data-slide="prev">戻る</a>
                  <button class="button-02ex-next btn_blue carousel-control-submit" >下記に同意して登録する</button>
                  <a href="XXX" class="kiyaku1"><span class="caret-right"></span><span class="text">利用規約について</span></a>
                  <a href="XXX" class="kiyaku2"><span class="caret-right"></span><span class="text">個人情報の取り扱いについて</span></a>
                </div>
                <div class="padding-20"></div>
              </div><!-- /.step-4 -->
            </form>
        </div>
        </div>
      </section><!-- /.contents-6 -->
      <div class="padding-30"></div>

      <div>
        <a href="/register/"><img src="/img/pc/pc_1.jpg" alt="転職サポートはこちら"></a>
      </div>

      <div class="padding-30"></div>
      
      <div class="contents-7" >
        <h2>
          <img src="/img/pc/h-icon-point.png" alt="">
          <span class="text">介護の転職おすすめ情報</span>
        </h2>

        <div class="recommend-info">
          <div>
            <div class="key">
              <span class="text">お役立ち情報</span>
            </div>

            <div class="value">
              <div class="items">
                <a href="XXX"><span class="caret-right"></span><span class="text">転職ノウハウ</span></a>
                <a href="XXX"><span class="caret-right"></span><span class="text">初めての介護転職</span></a>
                <a href="XXX"><span class="caret-right"></span><span class="text">転職事例</span></a>
              </div>

              <div class="items">
                <a href="XXX"><span class="caret-right"></span><span class="text">介護転職お悩み相談室</span></a>
                <a href="XXX"><span class="caret-right"></span><span class="text">介護業界給与データ</span></a>
              </div>
            </div>

            <div class="clearfix"></div>
            <div class="border"></div>

            <div class="key">
              <span class="text">サービス紹介</span>
            </div>

            <div class="value">
              <div class="items2">
                <a href="XXX"><span class="caret-right"></span><span class="text">クリックジョブ介護について</span></a>
                <a href="XXX"><span class="caret-right"></span><span class="text">ご利用の流れ</span></a>
                <a href="XXX"><span class="caret-right"></span><span class="text">友人紹介キャンペーン</span></a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="text-center">
            <a href="XXX contact">
              <img src="/img/pc/1c-button-03.png" alt="サービスに関するお問い合わせ">
            </a>
          </div>
        </div>
      </div><!-- /.contents-7 -->
      
      <div class="padding-30"></div>

      <div class="contents-8">
        <h2>
          <img src="/img/pc/h-icon-point.png" alt="">
          <div class="text">人気の介護求人</div>
          <div class="power-reverse">新着</div>
        </h2>
        <div class="popular-offer mt10">
          <?php $ct = 0;?>
          <?php foreach($ranking as $r): ?>
            <a href="<?php echo '/detail/'.$r['RecruitSheet']['recruit_sheet_id'] ?>" class="offer">
              <div class="offer-top">
                <?php if (!empty($r['RecruitSheet']['Office']['OfficeImage']) && !empty($r['RecruitSheet']['Office']['OfficeImage']['name'])): ?>
                <img src="<?php echo '/read/hospital/'.$r['RecruitSheet']['Office']['OfficeImage']['name']; ?>" alt="">
                <?php else: ?>
                <img src="<?php echo '/img/hospital/nophoto'.($r['RecruitSheet']['Office']['id']%50+1).'.png'; ?>" alt="">
                <?php endif; ?>
                <div>
                  <?php 
                    $str = '';
                    $str .= $r['RecruitSheet']['Office']['Prefecture']['name'];
                    if (!empty($r['RecruitSheet']['Office']['State'])){
                      $str .= $r['RecruitSheet']['Office']['State']['name'];
                    }
                    if (!empty($r['RecruitSheet']['Office']['City'])){
                      $str .= $r['RecruitSheet']['Office']['City']['name'];
                    }
                   ?>
                  <span><?php echo $r['RecruitSheet']['Office']['name']; ?><br/><?php echo $str ;?></span>
                </div>
                <div class="clearfix"></div>
              </div>
              <div class="offer-middle">
                <?php 
                  foreach ($r['RecruitSheet']['recruit_flex_type_label'] as $k => $label){
                      foreach ($label as $l){
                          echo '<span>'.$l.'</span>';
                      }
                  }
                ?>
              </div>

              <div class="offer-bottom">
                <div class="item-1">
                  <span class="key">給与</span>
                  <span class="value"><?php echo $r['RecruitSheet']['salary']?></span>
                </div>

                <div class="item-2">
                  <span class="key">応募条件</span>
                  <span class="value"><?php echo $r['RecruitSheet']['application_conditions']?></span>
                </div>
              </div>
            </a>
            <?php if ($ct % 3 == 2): ?>
              <?php if ($ct + 1 == count($ranking)): ?>
                <div class="clearfix"></div>
              <?php else: ?>
                <div class="clearfix pt10"></div>
              <?php endif; ?>
            <?php endif; ?>
            <?php $ct++ ?>
          <?php endforeach; ?> 
      </div><!-- /. popular-offer -->
      </div><!-- /.contents-8 -->
      <div class="padding-30"></div>

      <div>
        <a href="/register"><img src="/img/pc/pc2.jpg" alt="非公開求人を紹介してもらう"></a>
      </div>

      <div class="padding-30"></div>

      <div class="contents-9">
        <h2>
          <img src="/img/pc/h-icon-no1.png" alt="">
          <span class="text">クリックジョブ介護が選ばれる理由</span>
          <span class="power-reverse">介護士の満足度Ｎｏ.１</span>
        </h2>

        <div class="reason">
          <div>
            <div class="title">
              <span class="power">01</span>
              <span class="text">多くの求人情報の中から、ご希望にピッタリの職場を紹介します!</span>
            </div>
            <div class="body">
              【クリックジョブ介護】は、医療介護業界に特化した転職支援サイトです。厚生労働省の許可を受けた正式な団体が運営する、介護業界で働く方のための無料の転職支援サービス、いわば介護版ハローワークです。
              対象エリアは全国、お住まいの地域の求人情報に精通したエージェントが、あなたのご希望に合った求人をご紹介いたします。
              対象職種は、介護職、ケアマネジャー、生活相談員、オペレーター、管理職(リーダー、管理者、施設長)、リハビリ職(理学療法士、作業療法士)、看護職など。
              働く施設は、病院・クリニック、特別養護老人ホーム、有料老人ホーム、介護老人保健施設、デイサービス・デイケア、訪問介護など、非公開求人も多数ご紹介できますので、あなたにピッタリの新しい職場が見つかります。
            </div>
          </div>

          <div class="padding-20"></div>
          <div class="border"></div>
          <div class="padding-20"></div>

          <div>
            <div class="title">
              <span class="power">02</span>
              <span class="text">専任の転職エージェントが、転職活動全般をプロの立場からサポートします!</span>
            </div>
            <div class="body">
              「限られた求人情報や間違った知識で転職をしてほしくない」という思いから、私たちは、お一人お一人に専任の転職エージェントが、転職活動を最初から最後までバックアップする様々なサービスを提供しております。
              転職エージェントがご登録いただいたお客様一人一人に、転職に関するご希望をお伺いし、当社独自のネットワークで得た情報を駆使し、あなたにピッタリの求人をご紹介いたします。
              気に入った求人を見つけた後も、書類選考や面接の手続き、履歴書・職務経歴書の添削や面接練習、その他条件交渉など、手間のかかる作業や専門知識は専任の転職エージェントがサポートいたします。
            </div>
          </div>

          <div class="padding-20"></div>
          <div class="border"></div>
          <div class="padding-20"></div>

          <div>
            <div class="title">
              <span class="power">03</span>
              <span class="text">面接の度に交通費 1,000 円を支給します!</span>
            </div>
            <div class="body">
              【クリックジョブ介護】のサービスは、すべて無料でご利用いただけます。ご登録から、
              求人検索・紹介、書類選考・ 面接のサポート、専任の転職エージェントへのご相談まで、すべてのサービスをご利用いただくのに、費用は一切かかりません。
              さらに、面接の度に交通費 1,000 円を支給しております。
              皆様が、納得のいく施設に出会えるまで転職 活動をやり遂げられるよう、経済的にも支援をさせていただいております。
              【クリックジョブ介護】は、「転職して良かったな」と思っていただけるように、あなたの転職活動をあらゆる面からサポートいたします。
            </div>
          </div>
        </div>
      </div><!-- /.contents-9 -->

      <div class="padding-30"></div>

    </div><!-- /.main -->
    
    <div class="side">
      <div class="contents-1">
        <div class="border-left">
          <strong>介護専門の転職サポート</strong>
          <span class="power-reverse">無料</span>
        </div>

        <div class="padding-10"></div>
        <div class="border"></div>
        <div class="padding-10"></div>

        <div class="description">
          <span>働く前に、職場の詳しい情報がわかります</span>
        </div>

        <div class="side-tel">
          <img src="/img/pc/side-tel.png" alt="">
        </div>

        <div class="bt-01">
          <a href="/register"><img src="/img/pc/bt-01.png" alt="無料転職相談"></a>
        </div>

        <div class="padding-30"></div>

        <div class="update">
          <div class="update-head">
            <span><?php echo date('m月d日H:m更新', strtotime($recruitSheetCount['modified'])) ?></span>
          </div>

          <div class="update-body">
            <div>
              <div class="key">
                <span>掲載求人数：</span>
              </div>
              <div class="value">
                <span class="power"><?php echo number_format($recruitSheetCount['all']) ?></span> 件
              </div>
            </div>

            <div>
              <div class="key">ハローワーク求人：</div>
              <div class="value">
                <span class="power"><?php echo number_format($recruitSheetCount['hw']) ?></span> 件
              </div>
            </div>
          </div>
        </div><!-- /.update -->
      </div><!-- /.contents-1 -->
      <div class="padding-30"></div>

      <div class="contents-2">
        <div class="attention">
          <div class="border-left">
            <strong>注目の求人特集</strong>
          </div>

          <div class="padding-10"></div>
          <div class="border"></div>
          <div class="padding-20"></div>

          <div class="items">
            <div class="item">
              <a href="/feature/kyuryo"><img src="/img/pc/side-point-1.png" alt=""></a>
            </div>

            <div class="item">
              <a href="/feature/mikeiken"><img src="/img/pc/side-point-2.png" alt=""></a>
            </div>

            <div class="item">
              <a href="/feature/mushikaku"><img src="/img/pc/side-point-3.png" alt=""></a>
            </div>

            <div class="item">
              <a href="/feature/onlynikkin"><img src="/img/pc/side-point-4.png" alt=""></a>
            </div>

            <div class="item">
              <a href="/search?employment_type%5B0%5D=1"><img src="/img/pc/side-point-5.png" alt=""></a>
            </div>

            <div class="item">
              <a href="/feature/weekends"><img src="/img/pc/side-point-6.png" alt=""></a>
            </div>

            <div class="clearfix"></div>
          </div>
        </div><!-- /.attention -->
      </div><!-- /.contents-2 -->

      <div class="contents-3">
        <div class="border-left">
          <strong>ご登録からご入職までの流れ</strong>
        </div>

        <div class="padding-10"></div>
        <div class="border"></div>

        <div class="side-congratration">
          <img src="/img/pc/side-congratration.png" alt="内定・転職おめでとう">
        </div>

        <div class="padding-30"></div>

        <div>
          <a href="/register"><img src="/img/pc/bt-02.png" alt="簡単30秒転職相談"></a>
        </div>
      </div><!-- /.contents-3 -->
      <div class="padding-30"></div>

      <div class="contents-4">
        <div class="side-offer">
          <div class="border-left">
            <strong>高収入求人特集</strong>
          </div>

          <div class="padding-10"></div>
          <div class="border"></div>
          <div class="padding-20"></div>
          <?php foreach($highIncome as $i => $r): ?>
            <a href="<?php echo '/detail/'.$r['RecruitSheet']['recruit_sheet_id'] ?>" class="item">
              <div class="image">
                <?php if (!empty($r['Office']['OfficeImage']) && !empty($r['Office']['OfficeImage']['name'])): ?>
                <img src="<?php echo '/read/hospital/'.$r['Office']['OfficeImage']['name']; ?>" alt="">
                <?php else: ?>
                <img src="<?php echo '/img/hospital/nophoto'.($r['Office']['id']%50+1).'.png'; ?>" alt="">
                <?php endif; ?>
                <span class="rank"><?php echo ($i+1) ?></span>
              </div>

              <div class="description-1">
                <?php 
                  $str = '';
                  $str .= $r['Office']['Prefecture']['name'];
                  if (!empty($r['Office']['State'])){
                    $str .= $r['Office']['State']['name'];
                  }
                  if (!empty($r['Office']['City'])){
                    $str .= $r['Office']['City']['name'];
                  }
                 ?>
                <span><?php echo $r['Office']['name']; ?><?php echo $str ;?></span>
                <?php 
                  foreach ($r['RecruitSheet']['recruit_flex_type_label'] as $k => $label){
                      foreach ($label as $l){
                          echo '<span class="tag">'.$l.'</span>';
                      }
                  }
                ?>
              </div>

              <div class="clearfix"></div>

              <div class="description-2">
                <span class="key">給与</span>
                <span class="value"><?php echo $r['RecruitSheet']['salary']?></span>

                <span class="key">応募条件</span>
                <span class="value"><?php echo $r['RecruitSheet']['application_conditions']?></span>
              </div>
            </a><!-- /.item -->
            <div class="padding-20"></div>
          <?php endforeach; ?>
        </div><!-- /.side-offer -->
        <div class="side-offer side-offer-2">
          <div class="border-left">
            <strong>人気の求人特集</strong>
          </div>
          <div class="padding-10"></div>
          <div class="border"></div>
          <div class="padding-20"></div>
          <?php foreach($ranking as $i => $r): ?>
            <a href="<?php echo '/detail/'.$r['RecruitSheet']['recruit_sheet_id'] ?>" class="item">
              <div class="image">
                <?php if (!empty($r['RecruitSheet']['Office']['OfficeImage']) && !empty($r['RecruitSheet']['Office']['OfficeImage']['name'])): ?>
                <img src="<?php echo '/read/hospital/'.$r['RecruitSheet']['Office']['OfficeImage']['name']; ?>" alt="">
                <?php else: ?>
                <img src="<?php echo '/img/hospital/nophoto'.($r['RecruitSheet']['Office']['id']%50+1).'.png'; ?>" alt="">
                <?php endif; ?>
                <span class="rank"><?php echo ($i+1) ?></span>
              </div>

              <div class="description-1">
                <?php 
                  $str = '';
                  $str .= $r['RecruitSheet']['Office']['Prefecture']['name'];
                  if (!empty($r['RecruitSheet']['Office']['State'])){
                    $str .= $r['RecruitSheet']['Office']['State']['name'];
                  }
                  if (!empty($r['RecruitSheet']['Office']['City'])){
                    $str .= $r['RecruitSheet']['Office']['City']['name'];
                  }
                 ?>
                <span><?php echo $r['RecruitSheet']['Office']['name']; ?><?php echo $str ;?></span>
                <?php 
                  foreach ($r['RecruitSheet']['recruit_flex_type_label'] as $k => $label){
                      foreach ($label as $l){
                          echo '<span class="tag">'.$l.'</span>';
                      }
                  }
                ?>
              </div>

              <div class="clearfix"></div>

              <div class="description-2">
                <span class="key">給与</span>
                <span class="value"><?php echo $r['RecruitSheet']['salary']?></span>

                <span class="key">応募条件</span>
                <span class="value"><?php echo $r['RecruitSheet']['application_conditions']?></span>
              </div>
            </a><!-- /.item -->
            <div class="padding-20"></div>
          <?php endforeach; ?>
        </div><!-- /.side-offer -->
      </div><!-- /.contents-4 -->
      <div class="contents-5">
        <div class="bn_kearabi">
          <a href="/column"><img src="/img/pc/bn_kearabi.png" alt="ケアラビNEWS"></a>
        </div>

        <div class="padding-20"></div>

        <div class="bn_contents">
          <a href="XXX"><img src="/img/pc/bn_contents.png" alt="お役立ちコンテンツ"></a>
        </div>

        <div class="padding-20"></div>

        <div class="bn_faq">
          <a href="XXX"><img src="/img/pc/bn_faq.png" alt="FAQよくある質問"></a>
        </div>
      </div><!-- /.contents-5 -->

      <div class="padding-30"></div>

      <div class="contents-6">
        <div class="contact">
          <div class="border-left">
            <strong>クリックジョブ介護のご意見・ご感想</strong>
          </div>

          <div class="padding-10"></div>
          <div class="border"></div>
          <div class="padding-20"></div>

          <div class="caption">
            <input type="checkbox">
            <span>お客さまのメールアドレスまたはお電話番号を明記の上、内容を確認しチェックを入れてください。</span>
          </div>

          <div class="padding-20"></div>

          <form action="">
            <textarea cols="30" rows="5" placeholder="転職のご相談や求人に関するご質問など、なんでもお気軽にお問い合わせください。"></textarea>

            <div class="padding-20"></div>

            <div class="text-center">
              <input type="image" src="/img/pc/side-button-sent.png">
            </div>
          </form>
        </div><!-- /.contact -->
      </div><!-- /.contents-6 -->

    </div><!-- /.side -->
  </div><!-- /.wrap -->

  <div class="clearfix"></div>

  <?php echo $this->element('footer'); ?>

<hr>
  </body>
  <script type="text/javascript" src="/js/jquery.1.12.4.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/slick/slick.min.js"></script>
  <script>
$(document).ready(function(){
  $('.key-visual').slick({
    autoplay: true,
    autoplaySpeed: 5000,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 1000,
    centerMode: true,
    variableWidth: false,
    arrows: true,
    centerPadding:'0px'
  });
});
  </script>
</html>