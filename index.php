<?php session_start();
  include 'functions.php'; ?>
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
  <a href="index.php?reset=true" class="reset">Reset</a>
</div>
<div class="container">
<div class="grid">
<?php
  // If there's a grid variable in this session, we have a game in progress.
  if (!isset($_SESSION['grid'])) {
    new_game();
  } else {
    load_game();
  } 
  parse_str($_SERVER['QUERY_STRING'], $query);

  // If anything's set in the 'reset' query variable, reset the game.
  if (isset($query['reset'])) {
    new_game();
  } 

  if (isset($query['move'])) {
    do_move($query['move'], $human);
  }

  $winner = check_winner($grid);

  display_grid();

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

<?php 
  save_game();
?>
</body>
</html>