<?php
    // 大阪弁に変換する
    function toOsaka( $value = null ) {
        $tokyo = array("ありがとう","です。","ます。",  "ました。"   );
        $osaka = array("おおきに",  "やで。","まっせ。","ましてん。" );
        return str_replace( $tokyo, $osaka, $value );
    }
