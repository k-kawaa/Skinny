<?php
    // Skinny 呼び出し
    require_once( "../../Skinny.php" );
    // Skinnyへ渡す配列宣言（$outとします）
    $out = array();
    // テンプレートで出力したい内容を連想配列に追加
    $out['title']   = "Hello world.";
    $out['nowtime'] = time();  // 現在時刻とか
    $out['my_age']  = 25;      // 年齢とか
    $out['message'] = "動きました\nおめでとう!!\n";   // メッセージとか
    // $outの内容をSkinnyで出力
    $Skinny->SkinnyDisplay( "sample01.html", $out );
?>
