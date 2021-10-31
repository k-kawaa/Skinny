<?php
/**
 * 100行で作る面白Webプログラム（前世占い）
 * 入力された値からHASHを生成し、一定単位毎(default:2byte [0-255]通り)にHEX⇒DECに変換して配列からデータを取得する。
 * つまり256通りの単語データを持っておかないとだめなのよ。
 */

require_once( "../../Skinny.php" );
$out = array();

// HASH値を何バイトずつ使うか
define( "BYTE", 2 );		// 1:0-15通り   [2]:0-255通り

// エラーがあったらtrueにしてmessageに文言突っ込む
$out['error'] = false;


// 入力値取得（名前）
if ( isset($_POST) && trim($_POST['name']) != '' ) {
	$name = trim($_POST['name']);
	$out['checked'] = true;
} else {
	$name = "";
	$out['checked'] = false;
}


if ( strlen($name) > 30 ) {

	$out['error'] = true;
	$out['errorMessage'] = "名前長すぎじゃないですか？";

} else {

	if ( $name != '' ) {
		$hash = md5( $name );
		
		$zenkei_num = hexdec( substr( $hash, 0*BYTE, BYTE ) );		// 前世の形容詞
		$zenmei_num = hexdec( substr( $hash, 1*BYTE, BYTE ) );		// 前世の名詞
		
		$raikei_num = hexdec( substr( $hash, 2*BYTE, BYTE ) );		// 前世の形容詞
		$raimei_num = hexdec( substr( $hash, 3*BYTE, BYTE ) );		// 来世の名詞
		
		$syukei_num = hexdec( substr( $hash, 4*BYTE, BYTE ) );		// 前世の形容詞
		$syumei_num = hexdec( substr( $hash, 5*BYTE, BYTE ) );		// 守護霊の名詞
		
		// 簡易DB（改行区切りのテキストファイル）からデータを配列に取得
		$DBkeiyou = explode( "\n", file_get_contents("zen_keiyou.dat") );
		$DBmeishi = explode( "\n", file_get_contents("zen_meishi.dat") );
		
		// debug用 レコード数
		$DB1num = count( $DBkeiyou );
		$DB2num = count( $DBmeishi );
		
		
		// 任意の文字列を配列から取得
		$out['zenkei'] = $DBkeiyou[$zenkei_num];  //前世 形容詞
		$out['zenmei'] = $DBmeishi[$zenmei_num];  //前世 名詞
		$out['raikei'] = $DBkeiyou[$raikei_num];  //来世 形容詞
		$out['raimei'] = $DBmeishi[$raimei_num];  //来世 名詞
		$out['syukei'] = $DBkeiyou[$syukei_num];  //守護 形容詞
		$out['syumei'] = $DBmeishi[$syumei_num];  //守護 名詞
		
		// debug用
		$out['hash'] = $hash;
		$out['zenkei_num'] = $zenkei_num;
		$out['zenmei_num'] = $zenmei_num;
		$out['raikei_num'] = $raikei_num;
		$out['raimei_num'] = $raimei_num;
		$out['syukei_num'] = $syukei_num;
		$out['syumei_num'] = $syumei_num;
		$out['DB1num'] = $DB1num;
		$out['DB2num'] = $DB2num;
		
		var_dump( $out );
		
	} else {
		$out['error'] = true;
		$out['errorMessage'] = "";
	}
}

$out['name'] = $name;
$Skinny->SkinnyDisplay( "zensei.html", $out );
exit;

