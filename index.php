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
    // Run the game.
    run_game();

    // Now draw the grid.
    display_grid();
  ?>
  </div>
</div>

<?php 
  // If appropriate, tell the player there's a win or draw.
  if ($done && $winner) { 
?>

  <div class="winner">
    <?php echo num2mark($winner) ?> wins!
  </div>

<?php } elseif (check_draw()) { ?>

  <div class="winner">
  It's a draw!
  </div>

<?php 
  } 
  // Now list the running scores for the session.
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
</body>
</html>