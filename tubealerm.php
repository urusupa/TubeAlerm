<?php

//-----------------------------------------------------------------------------
// include
//-----------------------------------------------------------------------------

require_once "common.php";

//-----------------------------------------------------------------------------
// 変数
//-----------------------------------------------------------------------------

$vid = null;
$time = null;
$pdo = null;

$wNowURL = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];


//-----------------------------------------------------------------------------
// 関数群
//-----------------------------------------------------------------------------


function selYtHistry(){
	global $pdo;
	global $time;

	echo "<br><br>履歴\n";
	ConnectMySQL();
	echo "<ul>\n";
	$result = $pdo->query("SELECT * FROM V_TUBEALERM WHERE 1=1 AND USE_KBN = '0' LIMIT 0,100");
	$count = 0;
	while($row = $result -> fetch(PDO::FETCH_ASSOC)) {
		$video_id = $row["VIDEO_ID"];
		$title = $row["TITLE"];
		echo "<li>";
		echo "<a class='InLinkYt' href='http://nyctea.me/tubealerm/?vid=" . $video_id . "&time=". $time . "'>" . $title . "</a> | <a class='ExLinkYt'  target='_blank' href='https://www.youtube.com/watch?v=" . $video_id . "'>YouTube<img src='img/external-link-symbol.png'></a>";
		echo "</li>\n";
		
		$count++;
		if($count == 10){ //「履歴もっと見る」のために10件で区切る
			echo "<div class='showmore'><br>もっと見る</div>\n";
			echo "<div class='YtHistry'>\n";
		}
	}
	//	echo "<li><a href='#'>more</a></li>"; //そのうち実装する。「履歴もっと見る」機能
	echo "</ul>\n";
	echo "</div>\n";
	CloseMySQL();

}


function getRandomVID(){
	global $pdo;
	ConnectMySQL();
	$result = $pdo->query("SELECT VIDEO_ID FROM V_TUBEALERM WHERE USE_KBN = '0' ORDER BY RAND() LIMIT 1");
	$result = $result->fetch(PDO::FETCH_ASSOC);
	CloseMySQL();

	return $result['VIDEO_ID'];
}

//-----------------------------------------------------------------------------
// メイン
//-----------------------------------------------------------------------------

//動画IDの取得
if ( $wNowURL == "nyctea.me/tubealerm/") {
	$vid = getRandomVID();
} else {
	preg_match("/[\/?=]([-\w]{11})/i",$_SERVER["REQUEST_URI"],$tmp);
	$vid = $tmp[1];
}

//アラーム時刻取得
	if(empty($_GET['time'])){
		$time = "";
	}else{
		$time =  $_GET['time'];
	}
	
//ツイートボタン作成
if ($wNowURL == "nyctea.me/tubealerm/"){
	$wShareTitle = "TubeAlerm - YouTubeを指定時間に再生します";
	$wShareURL = "http://" . $wNowURL;
} elseif ( $time == "" || $time == "9999"){
	$wShareTitle = "TubeAlerm - " . getYtInfo($vid) . " ";
	$wShareURL = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
} else {
	$wShareTitle = "アラームを設定しました - "  . substr($_GET['time'],0,2) . ":" . substr($_GET['time'],2,2) . "->" . getYtInfo($vid) . " ";
	$wShareURL = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}


//HTML出力
//-----------------------------------------------------------------------------
echo "<!DOCTYPE html>\n\n";
echo "<html>\n\n";
echo "<head>\n";

if ( $time == "" || $time == "9999"){
	$htmltitle = "<title>TubeAlerm - " . getYtInfo($vid) . "</title>\n";
	echo $htmltitle;
} else {
	$htmltitle = "<title>TubeAlerm - "  . substr($time,0,2) . ":" . substr($time,2,2) . "->" . getYtInfo($vid) . "</title>\n";
	echo $htmltitle;
}

echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>\n";
echo "<link href='tubealerm.css' rel='stylesheet'>\n";
echo "<script type='text/javascript' src='/misc/jquery-2.2.2.min.js'></script>\n";
echo "<script type='text/javascript' src='tubealerm.js'></script>\n";
echo "</head>\n";
echo "<body>\n";
echo "\n";
echo "<h1>\n";
echo "<a class='titlelink' href='/tubealerm'>TubeAlerm</a>\n";
echo "</h1>\n";
echo "\n";
echo "\n";
echo "<hr>\n";
echo "<ul>\n";
echo "<li>指定の時刻になったら、指定のyoutube動画をループ再生します</li>\n";
echo "<li>youtubeのみ対応</li>\n";
echo "</ul>\n";

$wSocialButton = "<a href='https://twitter.com/share' class='twitter-share-button' data-url='" . $wShareURL . "' data-text='" . $wShareTitle . "' data-size='large'>Tweet</a>";
$wSocialButton = $wSocialButton . "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
echo $wSocialButton;
echo "<hr>\n";
echo "<div id='datehere'></div>\n";
echo "<div id='timehere'></div>\n";
echo "<script>\n";
echo "wStopableFlg = 0;\n";
echo "setInterval( 'exec1sec()', 1000 );\n";
echo "geturl();\n";
echo "</script>\n";
echo "<br>\n";
echo "<br>\n";
echo "\n";
echo "<script>\n";
echo "makeform();\n";
echo "</script>\n";
echo "\n";
echo getYtInfo($vid);
echo "<br><br>\n";
echo "<!--youtube埋め込みここから-->\n";
echo "<script>youtubeid = " . '"' . $vid . '";' . "</script>\n";
echo "<div id='player'></div>\n";
echo "<script type='text/javascript' src='youtubeapi.js'></script>\n";
echo "<!--youtube埋め込みここまで-->\n";
echo "\n";
echo "<br>\n";
echo "\n";
selYtHistry();
echo "\n";
echo "\n";
echo "<br>\n";
echo "<a class='' href='http://nyctea.me/'>@nyctea.me</a><br><br>\n";
echo "\n";
echo "</body>\n";
echo "</html>\n";


?>