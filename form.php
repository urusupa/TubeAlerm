<?php

//-----------------------------------------------------------------------------
// include
//-----------------------------------------------------------------------------

require_once "common.php";

//-----------------------------------------------------------------------------
// 変数
//-----------------------------------------------------------------------------

$gvid = null;
$gtime = null;
$pdo = null;

//-----------------------------------------------------------------------------
// 関数
//-----------------------------------------------------------------------------



function escapePost(){
	global $gvid;
	global $gtime;
	
	$gvid = htmlspecialchars($_POST['vid'], ENT_QUOTES, 'UTF-8');
	preg_match("/[\/?=]{0,1}([-\w]{11})/i",$gvid,$tmp);
	$gvid = $tmp[1];
	
	$gtime = mb_convert_kana($_POST['time'],'n','UTF-8');
	$gtime = htmlspecialchars($gtime, ENT_QUOTES, 'UTF-8');

}

function execSubmit() {
	global $gvid;
	global $gtime;
	global $pdo;
	global $wLatestV;

	$wLatestV = selLatestVID();
	if ($wLatestV["VIDEO_ID"] == $gvid){
		updH_TUBEALERM();
	} else {
		insH_TUBEALERM($gvid);
	}
	
	$param = "http://nyctea.me/tubealerm/?vid=" . $gvid . "&time=" . substr($gtime,0,4);
	header("Location: " . $param);
	
}

function selLatestVID() {
	global $pdo;

	ConnectMySQL();
	$result = $pdo->query("SELECT ID,VIDEO_ID FROM H_TUBEALERM WHERE USE_KBN = '0' ORDER BY RCD_KSN_TIME DESC LIMIT 1");
	while($row = $result -> fetch(PDO::FETCH_ASSOC)) {
		$id = $row["ID"];
		$video_id = $row["VIDEO_ID"];
		return array("ID"=>$id,"VIDEO_ID"=>$video_id);
	}
	CloseMySQL();
}

function updH_TUBEALERM() {
	global $pdo;
	global $wLatestV;

	$id = $wLatestV["ID"];
	$timestmp = date( 'YmdHis' . '000' );
	ConnectMySQL();
	$sql = $pdo -> prepare("UPDATE H_TUBEALERM SET RCD_KSN_TIME = :timestmp WHERE ID = :id");
	$sql -> bindValue(':timestmp', $timestmp, PDO::PARAM_STR);
	$sql -> bindValue(':id', $id, PDO::PARAM_INT);
	$sql -> execute();
	CloseMySQL();
}

function insH_TUBEALERM($gvid) {
	global $pdo;

	$title = getYtInfo($gvid);
	$timestmp = date( 'YmdHis' . '000' );
	ConnectMySQL();
	$result = $pdo -> prepare("INSERT INTO H_TUBEALERM (`VIDEO_ID`,`TITLE`,`USE_KBN`,`USER`,`RCD_KSN_TIME`,`RCD_TRK_TIME`) VALUES ( :gvid ,  :title , 0 , 'TubeAlerm' , :timestmp1 , :timestmp2 )");
	$result -> bindValue(':gvid', $gvid, PDO::PARAM_STR);
	$result -> bindValue(':title', $title, PDO::PARAM_STR);
	$result -> bindValue(':timestmp1', $timestmp, PDO::PARAM_STR);
	$result -> bindValue(':timestmp2', $timestmp, PDO::PARAM_STR);
	$result -> execute();
	CloseMySQL();
}



//-----------------------------------------------------------------------------
// メイン
//-----------------------------------------------------------------------------

if ( $_POST["vid"] == "" || $_POST["time"] == "9999"){
	$param = "http://nyctea.me/tubealerm/";
	header("Location: " . $param);

} else {
	escapePost();
	execSubmit();
}

?>