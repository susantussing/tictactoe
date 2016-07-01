<?php include 'functions.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tic-Tac-Toe</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="header">
  <a href="index.php" class="reset">Reset</a>
</div>
<div class="container">
<div class="grid">
<?php

  parse_str($_SERVER['QUERY_STRING'], $query);
  $grid = isset($query['grid']) ? $query['grid'] : "000000000";
  $player = isset($query['player']) ? $query['player'] : 1;
  $winner = check_winner($grid);

  display_grid($grid, $player, $winner);

?></div></div>

<?php
  
  if ($winner) {
    echo <<<HTML
      <div class="winner">$winner wins!</div>
HTML;
  }

?>

<div class="scores">
  <div class="score"><p class="score__name">You</p><p class="score__number">0</p></div>
  <div class="score"><p class="score__name">Computer</p><p class="score__number">0</p></div>
  <div class="score"><p class="score__name">Draw</p><p class="score__number">0</p></div>
</div>

</body>
</html>