<!DOCTYPE html>
<html>
<body>
<!-- プレイヤーを配置するための div -->
<div id="player"></div>

<script>
    // API を読み込み
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // プレイヤーを初期化
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '315',
            width: '560',
            videoId: 'eY-P3vswBnw',  // YouTubeの動画IDを指定
            playerVars: {
                'rel': 0,
                'modestbranding': 1,
                'controls': 1,
                'autohide': 1,
                'showinfo': 0,
                'disablekb': 1
            },
            events: {
                'onReady': onPlayerReady
            }
        });
    }

    // プレイヤーの準備ができた時の処理
    function onPlayerReady(event) {
        event.target.playVideo(); // 自動再生（必要に応じて）
    }
</script>
</body>
</html>