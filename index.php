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

  // If anything's set in the 'reset' query variable, reset the game.
  if (isset($_GET['reset'])) {
    new_game();
  } 

  // If there's a move here for the player, do it.
  if (isset($_GET['move'])) {
    do_move($_GET['move'], $human);
  }

  // If the computer goes first or if it's after the first turn, the computer moves.
  if ($grid != "000000000" || $computer == 1) {
    do_move(computer_move(), $computer);
  }

  $winner = check_winner($grid);

  display_grid();

?></div></div>

<?php
  
  if ($winner) {
    $winner_mark = num2mark($winner);
    echo <<<HTML
      <div class="winner">$winner_mark wins!</div>
HTML;
    if ($winner == $human) {
      $wins++;
    } else {
      $losses++;
    }
  } elseif (check_draw()) {
    echo <<<HTML
      <div class="winner">It's a draw!</div>
HTML;
    $draws++;
  }

?>

<div class="scores">
  <div class="score">
    <p class="score__name">You</p>
    <p class="score__number"><?php echo $wins ?: 0; ?></p>
  </div>
  <div class="score">
    <p class="score__name">Computer</p>
    <p class="score__number"><?php echo $losses ?: 0; ?></p>
  </div>
  <div class="score">
    <p class="score__name">Draw</p>
    <p class="score__number"><?php echo $draws ?: 0; ?></p>
  </div>
</div>

<?php 
  save_game();
?>
</body>
</html>