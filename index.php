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

  function num2mark ($num) {
    switch ($num) {
      case 1:
        return "X";
        break;
      case 2:
        return "O";
        break;
      default:
        return "";
        break;
    }
  }

  function display_box($grid, $i, $player) {
    $content = num2mark($grid[$i]);

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

  $wins = [[0,1,2], [3,4,5], [6,7,8], [0,3,6], [1,4,7], [2,5,8], [0,4,8], [2,4,6]];

  foreach ($wins as $win) {
    if ($grid[$win[0]] == $grid[$win[1]] && $grid[$win[0]] == $grid[$win[2]] && $grid[$win[0]] != 0) {
      $winner = num2mark($grid[$win[0]]);
      echo "$winner wins!";
    }
  }

?></div>
<a href="index.php" class="reset">Reset</a>


</body>
</html>