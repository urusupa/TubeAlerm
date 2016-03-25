


var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
		height: '390',
		width: '640',
		videoId: youtubeid,
		playerVars: {
			rel: 0, 
			loop: 1,
			playlist: youtubeid
		},
		events: {
			//'onReady': onPlayerReady,
			//'onStateChange': onPlayerStateChange,
			//'onError': 
		}
	});
}

function onPlayerReady(event) {
  event.target.playVideo();
}

var done = false;
function onPlayerStateChange(event) {
//  var ytStatus = event.data;
//  // Ä¶I—¹‚µ‚½‚Æ‚«
//  if (ytStatus == YT.PlayerState.ENDED) {
//    console.log('Ä¶I—¹');
//    // “®‰æÄ¶
//    event.target.playVideo();
//  }
//  if (event.data == YT.PlayerState.PLAYING && !done) {
//    setTimeout(stopVideo, 6000);
//    done = true;
//  }
}

function stopVideo() {
  player.stopVideo();
}

//http://www.tam-tam.co.jp/tipsnote/javascript/post6217.html
//https://developers.google.com/youtube/iframe_api_reference

//ie‚¾‚Æplayall‚¶‚á‚È‚¢‚Æˆê‰ñ‚Å‚¨‚í‚Á‚¿‚á‚¤


