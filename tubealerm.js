
//global
alermhours = "00";
alermminutes = "00";
youtubeid = "";
wStopableFlg = 0;
var database;

function geturl(){
	var url = window.location.href ;
//	document.write(url);
	document.write("<br>");
	vid = url.match(/[/?=]([-\w]{11})/);
	if (!vid) {
		youtubeid = "";
	} else {
		youtubeid = vid[1];
	}

	jihun = url.match(/time=..../);
	if (!jihun) {
		jihun = "";
	} else {
		jihun = jihun.join(',');
		jihun = jihun.replace(/time=/, '');
	}
	alermhours = jihun.substr(0,2);
	alermminutes = jihun.substr(2,2);
	
	alermhoursplus3 = parseInt(alermhours, 10) + 3;
	alermhoursplus3 = set2fig( alermhoursplus3 );
}


function makeform(){
	if (youtubeid == "") {
		wYoutubeid = "";
	} else {
		wYoutubeid = "https://www.youtube.com/watch?v=" + youtubeid
	}
	document.write('<form name="mainform" action="form.php" method="POST">');
	document.write('<table class="mainform"> ');
	document.write('<tr><td>URL or <br>VideoID</td><td><input type="text" name="vid" size="60" value="'+wYoutubeid+'" ><br>');
	document.write('<input type="checkbox" name="vinc" id="vinc"><label for="vinc">volume increase</label></td></tr>');
	document.write('<tr><td>時分</td><td><input type="text" name="time" size="40" value="'+jihun+'" onkeydown="enterkeysubmit();"></td></tr>');
	document.write('<tr><td colspan="2"><input type="submit" value="決定">　　');
	document.write('<input type="button" value="リセット" onClick="location.href = \'/tubealerm\';"></td></tr>');
	document.write('</table>');
	document.write('</form><br>');

	var url = window.location.href;
	if (url == "http://nyctea.me/tubealerm/" || jihun == "") {
		alermhours = "99";
		alermminutes = "99";
		document.write('<div class="alermoff">アラームなし</div><br><br>');
	} else {
		document.write('<div class="alermon">アラームセット中</div>');
		document.write('設定時刻：'+alermhours+':'+alermminutes+'<br><br>');
	}
}

//selYtHistry()のもっと見るボタン用jQuery
$(function(){
	$('.YtHistry:not(.YtHistry:first-of-type)').css('display','none'); //一番上の要素以外を非表示
	$('.showmore').nextAll('.showmore').css('display','none'); //ボタンを非表示
	$('.showmore').on('click', function() {
		$(this).css('display','none'); //押したボタンを非表示
		$(this).next('.YtHistry').slideDown('fast');
		$(this).nextAll('.showmore:first').css('display','block'); //次のボタンを表示
	});
});

/*
function enterkeysubmit(){
//EnterキーならSubmit
	if(window.event.keyCode == 13){
		mySubmit('mainform', '/tubealerm', 'GET');
	}
}
*/


// 毎秒処理
//-----------------------------------------------------------------------------

function exec1sec(){
	fhiduke();
	checknow();
}

function checknow(){
	var hiduke=new Date(); 
	var hours = set2fig( hiduke.getHours() );
	var minutes = set2fig( hiduke.getMinutes() );

	if (hours == alermhours && minutes == alermminutes) {
		if(!wStopableFlg){player.playVideo();}
		wStopableFlg = 1;
		return;
	} else {
		wStopableFlg = 0;
	}
	
//設定時刻より3時間経過でSTOP
	if (hours == alermhoursplus3 && minutes == alermminutes){
		player.stopVideo();
		wStopableFlg = 0;
	}
}

function fhiduke(){
//今日の日付データを変数hidukeに格納
	var hiduke=new Date(); 

//年・月・日・曜日を取得する
	var year = hiduke.getFullYear();
	var month = set2fig( hiduke.getMonth()+1 );
	var week = set2fig( hiduke.getDay() );
	var day = set2fig( hiduke.getDate() );
	var hours = set2fig( hiduke.getHours() );
	var minutes = set2fig( hiduke.getMinutes() );
	var seconds = set2fig( hiduke.getSeconds() );

	var dmsg = year+"/"+month+"/"+day;
	var tmsg = hours+":"+minutes+":"+seconds;

	document.getElementById("datehere").innerHTML = dmsg;
	document.getElementById("timehere").innerHTML = tmsg;
}

function set2fig(num) {
// 桁数が1桁だったら先頭に0を加えて2桁に調整する
	var ret;
	if( num < 10 ) {
		ret = "0" + num;
	} else {
		ret = num;
	}
	return ret;
}



