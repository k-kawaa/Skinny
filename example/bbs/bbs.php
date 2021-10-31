<?php

require_once( "../../Skinny.php" );

$datafile = "datafile.dat";
$skinfile = "bbs.html";
$out = array();
$out['lognum'] = 15;    // 取得するログの件数



//==================================================
// 掲示板書込み
//==================================================
if ( $_POST['mode'] == "writeMode" ) {
  // ログの全内容を取得
  $logAll = file_get_contents( $datafile );
  
  // 今回書き込まれたデータを１行目に書き込む（タブ区切り）
  $logs = sprintf( "%s\t%s\t%s\n", time(), trim($_POST['name']), trim($_POST['body']) );
  $fp = fopen( $datafile, "w" );
  fwrite( $fp, $logs );
  // 過去のデータはその下に続ける
  fwrite( $fp, $logAll );
  fclose( $fp );
  
  // リダイレクト（多重登録禁止）
  header( "Location: bbs.php" );
}



//==================================================
// ここから掲示板読み込み～表示処理
//==================================================


// データファイルの先頭から30件までを取得
// ※) DB使ってるならこんな感じの１行で済むのよ
//     $out['bbsline'] = mysql_fetch_assoc( mysql_query("SELECT date,name,message FROM bbsdata ORDER BY id DESC LIMIT 0,30") );
$fp = fopen($datafile,"r");
$i = 0;
while ( !feof($fp) ) {
  // 指定件数分データを読む
  if ( $i++ >= $out['lognum'] ) {
    break;
  } else {
    $line = fgets( $fp );   // 配列0番目から順番に入れる
    if( strlen(trim($line)) > 0 ) {
      list( $dt, $nm, $mess, ) = explode( "\t", $line );
      $out['bbsline'][] = array( "date"=>$dt, "name"=>$nm, "message"=>$mess );
    }
  }
}
fclose( $fp );


$Skinny->SkinnyDisplay( $skinfile, $out );
exit;

