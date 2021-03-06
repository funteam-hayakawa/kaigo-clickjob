<?php

define("COMMON_FILE_PATH", ROOT . DS . APP_DIR . "/tmp/files/");

// サイトタイプ定義
if ( isset($_SERVER['HTTP_USER_AGENT']) && mb_ereg("iPhone|iPod|Android.*Mobile|Windows.*Phone", $_SERVER['HTTP_USER_AGENT']) ) {
    // スマートフォン
    define('SITE_TYPE', 's');
}
else {
    define('SITE_TYPE', 'p');
}
/* とりあえず80年前から10年前を選べることにする */
$config['birthday_year_selector'] = array(
    'from' => date('Y',strtotime("-80 year")),
    'to' => date('Y',strtotime("-10 year")),
);
$config['wareki'] = array(
  '明治' => array('st' => 1868, 'ed' => 1912),
  '大正' => array('st' => 1912, 'ed' => 1926),
  '昭和' => array('st' => 1926, 'ed' => 1989),
  '平成' => array('st' => 1989, 'ed' => 9999),
);

//職種 (職種カテゴリ)
$config['occupation'] = array(
  1  => '介護職',
  3  => 'ケアマネジャー',
  4  => '生活相談員',
  2  => 'サービス提供責任者',
  5  => 'オペレーター',
  6  => '管理職（リーダー）',
  7  => '管理職（管理者・施設長）',
  8  => 'リハビリ職（理学療法士）',
  9  => 'リハビリ職（作業療法士）',
  10 => '看護職',
  63 => 'その他',
);

//働く施設 (施設タイプ)
$config['institution_type'] = array(
  4  => '特別養護老人ホーム',
  5  => '介護付有料老人ホーム',
  26 => '住宅型有料老人ホーム',
  3  => '介護老人保健施設',
  22 => 'サービス付き高齢者向け住宅',
  19 => 'グループホーム',
  24 => '小規模多機能',
  12 => 'デイサービス・デイケア',
  20 => '訪問介護',
  25 => '定期巡回・夜間対応',
  21 => '居宅介護支援事業所',
  16 => '地域包括支援センター',
  7  => '訪問入浴',
  1  => '病院',
  2  => 'クリニック',
  6  => '訪問看護',
  8  => '健診',
  9  => '企業',
  10 => 'ショートステイ',
  11 => '治験',
  14 => 'その他',
  15 => '重症心身障害児施設',
  18 => '学校',
  23 => '障害者施設',
);

//働く施設 (施設タイプ 検索用選択枝)
$config['institution_type_search_disp'] = array(
  '1'  => array('text' => '特別養護老人ホーム',       'search_key' => array(4)),
  '2'  => array('text' => '有料老人ホーム',           'search_key' => array(5, 26)),
  '3'  => array('text' => '介護老人保健施設',         'search_key' => array(3)),
  '5'  => array('text' => 'グループホーム',           'search_key' => array(19)),
  '4'  => array('text' => 'サービス付き高齢者向け住宅', 'search_key' => array(22)),
  '6'  => array('text' => '小規模多機能',             'search_key' => array(24)),
  '7'  => array('text' => 'デイサービス・デイケア',    'search_key' => array(12)),
  '8'  => array('text' => '訪問介護',                'search_key' => array(20)),
  '9'  => array('text' => '定期巡回・夜間対応',        'search_key' => array(25)),
  '10' => array('text' => '居宅介護支援事業所',        'search_key' => array(21)),
  '11' => array('text' => '地域包括支援センター',      'search_key' => array(16)),
  '12' => array('text' => '訪問入浴',                'search_key' => array(7)),
  '13' => array('text' => '病院・クリニック',         'search_key' => array(1, 2)),
  '14' => array('text' => 'その他',                 'search_key' => array(6, 8, 9, 10, 11, 14, 15, 18, 23)),
);

//持っている資格 (必要な免許・資格)
$config['application_license'] = array(
  9  => '介護福祉士',
  16 => '介護職員実務者研修',
  6  => 'ヘルパー1級',
  8  => '介護職員基礎研修',
  14 => '介護職員初任者研修',
  7  => 'ヘルパー2級',
  5  => 'ケアマネジャー',
  15 => '社会福祉主事',
  10 => '社会福祉士',
  12 => 'PT',
  13 => 'OT',
  1  => '正看護師',
  2  => '准看護師',
  3  => '保健師',
  4  => '助産師',
  17 => '認知症実践者研修',
  18 => '認知症管理者研修',
  19 => '認知症開設者研修',
  20 => '小規模多機能研修',
  21 => 'ユニットケア研修',
  22 => '主任ケアマネ',
  23 => 'その他',
);

//持っている資格 (必要な免許・資格 検索用選択枝)
$config['application_license_search_disp'] = array(
  '1'  => array('text' => '介護福祉士',             'search_key' => array(9)),
  '2'  => array('text' => '介護職員実務者研修',      'search_key' => array(16)),
  '3'  => array('text' => 'ヘルパー1級',            'search_key' => array(6)),
  '4'  => array('text' => '介護職員基礎研修',        'search_key' => array(8)),
  '5'  => array('text' => '初任者研修（ヘルパー2級）', 'search_key' => array(7, 14)),
  '6'  => array('text' => 'ケアマネジャー',          'search_key' => array(5)),
  '7'  => array('text' => '社会福祉主事',            'search_key' => array(15)),
  '8'  => array('text' => '社会福祉士',              'search_key' => array(10)),
  '9'  => array('text' => 'PT',                'search_key' => array(12)),
  '10'  => array('text' => 'OT',                'search_key' => array(13)),
  '11' => array('text' => '正看護師',            'search_key' => array(1, 3, 4)),
  '12' => array('text' => '准看護師',            'search_key' => array(2, 3, 4)),
  '13' => array('text' => 'その他',                 'search_key' => array(17, 18, 19, 20, 21, 22, 23)),
);

//雇用形態から探す (雇用形態)
$config['employment_type'] = array(
  1 => '正社員',
  2 => '契約社員',
  6 => '正社員(日勤のみ)',
  7 => '契約社員(日勤のみ)',
  3 => 'パート(日勤夜勤あり)',
  4 => 'パート(日勤のみ)',
  5 => 'パート(夜勤のみ)',
);

//雇用形態から探す (雇用形態 検索用選択枝)
$config['employment_type_search_disp'] = array(
  '1' => array('text' => '正社員',          'search_key' => array(1, 6)),
  '2' => array('text' => '契約社員',          'search_key' => array(2, 7)),
  '5' => array('text' => 'パート',                  'search_key' => array(3, 4, 5)),
  '3' => array('text' => '正社員(日勤のみ)', 'search_key' => array(6)),
  '4' => array('text' => '契約社員(日勤のみ)', 'search_key' => array(7)),
  '6' => array('text' => 'パート(日勤のみ)',          'search_key' => array(4)),
);

//働きやすさから探す" (融通)
$config['recruit_flex_type'] = array(
  1 => '産休・育休',
  2 => 'ブランクOK',
  3 => '短時間勤務OK',
  4 => '休み多め',
  5 => '車通勤OK',
  6 => '未経験OK',
  7 => '給料多め',
  8 => '無資格可',
  9 => '寮あり',
  10 => '土日休み',
);

//flex_type freeword検索用
$config['recruit_flex_type_for_freeword'] = array(
  1  => '産休・育休',
  2  => 'ブランクOK',
  3  => '短時間勤務OK',
  4  => '休み多め',
  5  => '車通勤OK',
  6  => '未経験OK',
  7  => '給料多め',
  8  => '無資格可',
  9  => '寮あり',
  10 => '土日休み',
  11 => '外国籍可',
  12 => '4月入社OK',
  13 => '新卒OK',
  14 => '正社員登用制度あり',
  15 => '住宅手当あり',
  16 => '賞与4か月以上',
);
/* DBのrecruit_flex_typeを表示する際のカテゴリ分けmap */
$config['recruit_flex_type_label'] = array(
  /* 給与 */
  7  => array('type' => 'salary', 'text' => '給料多め'),
  16 => array('type' => 'salary', 'text' => '賞与4か月以上'),  
  15 => array('type' => 'salary', 'text' => '住宅手当あり'),
  /* 応募条件 */
  2  => array('type' => 'application_condition', 'text' => 'ブランクOK'),
  3  => array('type' => 'application_condition', 'text' => '短時間勤務OK'),
  6  => array('type' => 'application_condition', 'text' => '未経験OK'),
  8  => array('type' => 'application_condition', 'text' => '無資格可'),
  11 => array('type' => 'application_condition', 'text' => '外国籍可'),
  13 => array('type' => 'application_condition', 'text' => '新卒OK'),
  /* 入居可能住宅 */
  9  => array('type' => 'available_house', 'text' => '寮あり'),
  /* 休み */
  1  => array('type' => 'vacation', 'text' => '産休・育休'),
  4  => array('type' => 'vacation', 'text' => '休み多め'),
  10 => array('type' => 'vacation', 'text' => '土日休み'),
  /* 通勤 */
  5  => array('type' => 'commuting', 'text' => '車通勤OK'),
  /* 備考 */
  14 => array('type' => 'remarks', 'text' => '正社員登用制度あり'),
  12 => array('type' => 'remarks', 'text' => '4月入社OK'),
);
//働く時間から探す
$config['particular_ttl_hour'] = array(
  1 => '残業月10時間以下',
  2 => '日勤のみ',
  3 => '夜勤のみ',
);

/* こだわり条件のテキスト */
$config['commitment_text'] = array(
  'occupation' => '職種',
  'institution_type' => '施設タイプ',
  'application_license' => '資格',
  'employment_type' => '雇用形態',
  'recruit_flex_type' => '働きやすさ',
  'particular_ttl_hour' => '働く時間',
);

$config['searchURL'] = array(
  'kaigosyoku'            => array('code' => 1,  'type' => 'occupation',          'text' => '介護職',               'search_key' => array(1)),
  'caremanager'           => array('code' => 3,  'type' => 'occupation',          'text' => 'ケアマネジャー',         'search_key' => array(3)),
  'seikatsusoudanin'      => array('code' => 4,  'type' => 'occupation',          'text' => '生活相談員',            'search_key' => array(4)),
  'serviceteikyosha'      => array('code' => 2,  'type' => 'occupation',          'text' => 'サービス提供責任者',      'search_key' => array(2)),
  'operater'              => array('code' => 5,  'type' => 'occupation',          'text' => 'オペレーター',           'search_key' => array(5)),
  'leader'                => array('code' => 6,  'type' => 'occupation',          'text' => '管理職（リーダー）',      'search_key' => array(6)),
  'shisetsucho'           => array('code' => 7,  'type' => 'occupation',          'text' => '管理職（管理者・施設長）', 'search_key' => array(7)),
  'physicaltherapist'     => array('code' => 8,  'type' => 'occupation',          'text' => 'リハビリ職（理学療法士）', 'search_key' => array(8)),
  'occupationaltherapist' => array('code' => 9,  'type' => 'occupation',          'text' => 'リハビリ職（作業療法士）', 'search_key' => array(9)),
  'nurse'                 => array('code' => 10, 'type' => 'occupation',          'text' => '看護職',                'search_key' => array(10)),
  'shokushusonota'        => array('code' => 63, 'type' => 'occupation',          'text' => 'その他',                'search_key' => array(63)),
  'tokuyou'               => array('code' => 1,  'type' => 'institution_type',    'text' => '特別養護老人ホーム',       'search_key' => array(4)),
  'yuryorojinhomu'        => array('code' => 2,  'type' => 'institution_type',    'text' => '有料老人ホーム',           'search_key' => array(5, 26)),
  'kaigohokensisetsu'     => array('code' => 3,  'type' => 'institution_type',    'text' => '介護老人保健施設',         'search_key' => array(3)),
  'serivicejyuutaku'      => array('code' => 4,  'type' => 'institution_type',    'text' => 'サービス付き高齢者向け住宅', 'search_key' => array(22)),
  'grouphome'             => array('code' => 5,  'type' => 'institution_type',    'text' => 'グループホーム',           'search_key' => array(19)),
  'syoukibotakinou'       => array('code' => 6,  'type' => 'institution_type',    'text' => '小規模多機能',             'search_key' => array(24)),
  'dayservicecare'        => array('code' => 7,  'type' => 'institution_type',    'text' => 'デイサービス・デイケア',    'search_key' => array(12)),
  'houmonkaigo'           => array('code' => 8,  'type' => 'institution_type',    'text' => '訪問介護',                'search_key' => array(20)),
  'teikisenkai'           => array('code' => 9,  'type' => 'institution_type',    'text' => '定期巡回・夜間対応',        'search_key' => array(25)),
  'jigyousyo'             => array('code' => 10, 'type' => 'institution_type',    'text' => '居宅介護支援事業所',        'search_key' => array(21)),
  'supportcenter'         => array('code' => 11, 'type' => 'institution_type',    'text' => '地域包括支援センター',      'search_key' => array(16)),
  'houmonnyuuyoku'        => array('code' => 12, 'type' => 'institution_type',    'text' => '訪問入浴',                'search_key' => array(7)),
  'hospitals'             => array('code' => 13, 'type' => 'institution_type',    'text' => '病院・クリニック',         'search_key' => array(1, 2)),
  'shisetsusonota'        => array('code' => 14, 'type' => 'institution_type',    'text' => 'その他',                 'search_key' => array(6, 8, 9, 10, 11, 14, 15, 18, 23)),
  'kaigohukushishi'       => array('code' => 1,  'type' => 'application_license', 'text' => '介護福祉士',             'search_key' => array(9)),
  'kaigojitsumukensyu'    => array('code' => 2,  'type' => 'application_license', 'text' => '介護職員実務者研修',      'search_key' => array(16)),
  'helperone'             => array('code' => 3,  'type' => 'application_license', 'text' => 'ヘルパー1級',            'search_key' => array(6)),
  'kaigokisokensyu'       => array('code' => 4,  'type' => 'application_license', 'text' => '介護職員基礎研修',        'search_key' => array(8)),
  'helpertwo'             => array('code' => 5,  'type' => 'application_license', 'text' => '初任者研修（ヘルパー2級）', 'search_key' => array(7, 14)),
  'caremanagement'        => array('code' => 6,  'type' => 'application_license', 'text' => 'ケアマネジャー',          'search_key' => array(5)),
  'syakaihukushisyuji'    => array('code' => 7,  'type' => 'application_license', 'text' => '社会福祉主事',            'search_key' => array(15)),
  'syakaihukushhishi'     => array('code' => 8,  'type' => 'application_license', 'text' => '社会福祉士',              'search_key' => array(10)),
  'ptot'                  => array('code' => 9,  'type' => 'application_license', 'text' => 'PT・OT',                'search_key' => array(12, 13)),
  'nursing'               => array('code' => 10, 'type' => 'application_license', 'text' => '正・准看護師',            'search_key' => array(1, 2, 3, 4)),
  'shikakusonota'         => array('code' => 11, 'type' => 'application_license', 'text' => 'その他',                 'search_key' => array(17, 18, 19, 20, 21, 22, 23)),
  'staff'                 => array('code' => 1,  'type' => 'employment_type',     'text' => '正社員・契約社員',          'search_key' => array(1, 2, 6, 7)),
  'nikkinstaff'           => array('code' => 2,  'type' => 'employment_type',     'text' => '正社員・契約社員(日勤のみ)', 'search_key' => array(6, 7)),
  'parttime'              => array('code' => 3,  'type' => 'employment_type',     'text' => 'パート',                  'search_key' => array(3, 4, 5)),
  'nikkinparttime'        => array('code' => 4,  'type' => 'employment_type',     'text' => 'パート(日勤のみ)',          'search_key' => array(4)),
  'manternity'            => array('code' => 1,  'type' => 'recruit_flex_type',   'text' => '産休・育休',               'search_key' => array(1)),
  'blankok'               => array('code' => 2,  'type' => 'recruit_flex_type',   'text' => 'ブランクOK',               'search_key' => array(2)),
  'shorttimeok'           => array('code' => 3,  'type' => 'recruit_flex_type',   'text' => '短時間勤務OK',             'search_key' => array(3)),
  'breaks'                => array('code' => 4,  'type' => 'recruit_flex_type',   'text' => '休み多め',                 'search_key' => array(4)),
  'cars'                  => array('code' => 5,  'type' => 'recruit_flex_type',   'text' => '車通勤OK',                 'search_key' => array(5)),
  'mikeiken'              => array('code' => 6,  'type' => 'recruit_flex_type',   'text' => '未経験OK',                 'search_key' => array(6)),
  'kyuryo'                => array('code' => 7,  'type' => 'recruit_flex_type',   'text' => '給料多め',                 'search_key' => array(7)),
  'mushikaku'             => array('code' => 8,  'type' => 'recruit_flex_type',   'text' => '無資格可',                 'search_key' => array(8)),
  'dormitory'             => array('code' => 9,  'type' => 'recruit_flex_type',   'text' => '寮あり',                  'search_key' => array(9)),
  'weekends'              => array('code' => 10, 'type' => 'recruit_flex_type',   'text' => '土日休み',                 'search_key' => array(10)),
  'zangyotenhrsless'      => array('code' => 1,  'type' => 'particular_ttl_hour', 'text' => '残業月10時間以下',          'search_key' => array(1)),
  'onlynikkin'            => array('code' => 2,  'type' => 'particular_ttl_hour', 'text' => '日勤のみ',                 'search_key' => array(2)),
  'onlyyakin'             => array('code' => 3,  'type' => 'particular_ttl_hour', 'text' => '夜勤のみ',                 'search_key' => array(3)),
);

//アクセス方法
$config['access_type'] = array(
  1 => '送迎バス',
  2 => 'バス',
  3 => '徒歩',
);

//加入保険
$config['social_insurance'] = array(
  1 => '厚生年金',
  2 => '健康保険',
  3 => '雇用保険',
  4 => '労災保険',
);

//定年
$config['retirement'] = array(
  1 => 'なし',
  2 => 'あり',
);

//再雇用
$config['reemployment'] = array(
  1 => 'なし',
  2 => 'あり',
);

//退職金
$config['retirement_pay'] = array(
  1 => 'なし',
  2 => 'あり',
);

//単身用住宅の有無
$config['house_for_single'] = array(
  1 => 'なし',
  2 => 'あり',
);

//家族用住宅の有無
$config['house_for_family'] = array(
  1 => 'なし',
  2 => 'あり',
);

//車通勤
$config['mycar'] = array(
  1 => '不可',
  2 => '可',
);

//通勤手当
$config['commutation'] = array(
  1 => '支給なし',
  2 => '月上限 {%1}円まで支給',
  3 => '全額支給',
);

$config['column_category'] = array(
  1 => array('url' => 'news', 'name' => 'ニュース'),
  2 => array('url' => 'column', 'name' => 'コラム'),
  3 => array('url' => 'knowledge', 'name' => '知識'),
);

$config['inquiry_type'] = array(
  1 => '求人に応募したい',
  2 => '求人の募集情報について確認したい',
  3 => '求人の詳細について詳しく聞きたい',
  4 => '職場の内部情報について事前に知りたい',
  5 => '条件の調整・交渉について相談したい',
);
