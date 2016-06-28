<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

<div class="grid">
<?php

  function display_box($grid, $i, $player) {
    switch ($grid[$i]) {
      case 1:
        $content = "X";
        break;
      case 2:
        $content = "O";
        break;
      default:
        $content = "";
        break;
    }
    $newplayer = ($player == 1) ? 2 : 1;
    $newgrid = substr_replace($grid, $player, $i, 1);
    echo <<<HTML
      <a class="box" href="?grid=$newgrid&player=$newplayer">$content</a>
HTML;
  }
  parse_str($_SERVER['QUERY_STRING'], $query);
  $grid = isset($query['grid']) ? $query['grid'] : "000000000";
  $player = isset($query['player']) ? $query['player'] : 1;



  for ($i = 0; $i < 9; $i++) {
    display_box($grid, $i, $player);
  }



?></div>
<a href="index.php" class="reset">Reset</a>


</body>
</html>