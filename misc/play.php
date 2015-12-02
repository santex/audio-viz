<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Big Nerd Ranch D3 Blog Post</title>
    <link rel="stylesheet" href="application.css" />
  </head>
  <?php
  ?>
  <body>
    <audio id="audioElement" src="./audio/1.mp3"></audio>
    <div>
      <button onclick="document.getElementById('audioElement').play()">Play the Audio</button>
      <button onclick="document.getElementById('audioElement').pause()">Pause the Audio</button>
      <button onclick="document.getElementById('audioElement').volume+=0.1">Increase Volume</button>
      <button onclick="document.getElementById('audioElement').volume-=0.1">Decrease Volume</button>
    </div>
    <script src="jquery-2.1.3.min.js"></script>
    <script src="d3.min.js" charset="utf-8"></script>
    <script src="app.js"></script>
  </body>
</html>
