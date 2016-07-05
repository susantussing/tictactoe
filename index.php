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
  <a href="index.php?reset=true" class="header__link">New Game</a>
  <a href="index.php?save=true" class="header__link">Save Game</a>
  <a href="index.php?load=true" class="header__link">Load Game</a>
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

  end_game();

  display_grid();

?></div></div>

<?php if ($done && $winner) { ?>

  <div class="winner">
    <?php echo num2mark($winner) ?> wins!
  </div>

<?php } elseif (check_draw()) { ?>

  <div class="winner">
  It's a draw!
  </div>

<?php } ?>

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