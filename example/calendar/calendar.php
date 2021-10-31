<?php

require_once( "../../Skinny.php" );

$out = array();

// 引数から処理年月を取得。無ければ当月。
$ym = isset($_GET['ym']) ? $_GET['ym'] : date('Y-n');

// 出力用変数に代入
$indate = explode('-', $ym );
$out['year'] = $date['YEAR']  = (int)$indate[0];
$out['month']= $date['MONTH'] = (int)$indate[1];

// カレンダー取得
$cal  = _getCalender( $ym );
$out['bef']  = $cal['lastMonthYM'];
$out['aft']  = $cal['nextMonthYM'];
$out['befM'] = $cal['lastMonthM'];
$out['aftM'] = $cal['nextMonthM'];
$out['weekList'] = $cal['weekList'];

$out['list']['users']['name'] = "sasaki";
$out['list']['users']['tall'] = 173;
$out['sasaki'] = array('name'=>"sasaki", "tall"=>173, "weight"=>67 );


$Skinny->SkinnyDisplay( "calendar.html", $out );
exit;








/**
 *  指定年月のカレンダーを取得する（翌月・次月の年月も取得）
 *  @param : dateymd
 *  @return: array
 *  @return :
 *     array   weeklist[n] = array("sun"=>1, "mon"=>2, "tue"=>3.... ) ... １週間毎の Sun～Sat に対する日付
 *     string  lastMonth = 'yyyy-mm';
 *     string  nextMonth = 'yyyy-mm';
 */
function _getCalender( $Ym ) {
	$out  = array();
	$date = array();
	
	// 引数エラーは今月で処理する
	// 指定年月を $date へ配列として代入(YYYY-NN)で指定
	if ( preg_match('/([1-2][0-9][0-9][0-9])-([0-9]{1,2})/', $Ym, $matches) == 0 ) {
		$date['YEAR'] = (int)date('Y');
		$date['MONTH']= (int)date('n');
	} else {
		$date['YEAR'] = (int)$matches[1];
		$date['MONTH']= (int)$matches[2];
	}
	
	// ループ制御用 データ取得
	$days      = (int)date('d', mktime(0, 0, 0, $date['MONTH']+1, 0,     $date['YEAR']));  // 今月の日数 （※翌月の0日指定＝今月末）
	$first_day = (int)date('w', mktime(0, 0, 0, $date['MONTH'],   1,     $date['YEAR']));  // 最初の日の曜日（0:日 ～ 6:土）
	$last_day  = (int)date('w', mktime(0, 0, 0, $date['MONTH'],   $days, $date['YEAR']));  // 最後の日の曜日（0:日 ～ 6:土）
	
	// 何週あるかを取得
	$weeks = (($days + $first_day) % 7)==0 ? ($days+$first_day)/7 : ceil(($days + $first_day) / 7);
	
	// Simpley用配列を生成
	$weekList = array();
	$youbi = array('sun','mon','tue','wed','thu','fri','sat');   # 添え字は「全小文字」
	$lDay = 1;
	for ( $i=0; $i<$weeks; $i++ ) {
		$lWeek=0;
		for ( $lWeek=0; $lWeek<7; $lWeek++ ) {
			if ( ($i==0 && $lWeek<$first_day) || ($i==$weeks-1 && $lWeek>$last_day) ) {
				$weekList[$i][$youbi[$lWeek]] = NULL;
				$weekList[$i][$youbi[$lWeek]] = NULL;
				$weekList[$i][$youbi[$lWeek]] = NULL;
				$weekList[$i][$lWeek] = NULL;
			} else {
				$weekList[$i][$youbi[$lWeek]] = $lDay;
				$weekList[$i][$youbi[$lWeek]] = $lDay;
				$weekList[$i][$youbi[$lWeek]] = $lDay;
				$weekList[$i][$lWeek] = $lDay;
				$lDay++;
			}
		}
	}
	
	// 先月/次月をYYYY-NNで取得（リンク用）
	$out['lastMonthYM'] = ($date['MONTH']!=1)  ? ($date['YEAR'].'-'.($date['MONTH']-1)) : (($date['YEAR']-1).'-12');
	$out['nextMonthYM'] = ($date['MONTH']!=12) ? ($date['YEAR'].'-'.($date['MONTH']+1)) : (($date['YEAR']+1).'-1');
	
	$out['lastMonthY']  = substr($out['lastMonthYM'], 0, 4);
	$out['nextMonthY']  = substr($out['nextMonthYM'], 0, 4);
	$out['lastMonthM']  = substr($out['lastMonthYM'], 5, 2);
	$out['nextMonthM']  = substr($out['nextMonthYM'], 5, 2);
	// カレンダーボディ部リストを取得
	$out['weekList'] = $weekList;
	
	return $out;
}
