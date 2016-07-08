<?php
  include 'model.php';
  include 'controller.php';
  include 'view.php';

  $move = isset($_GET['move']) ? $_GET['move'] : null;

  // Is there an existing session?
  if (isset($_SESSION['grid'])) {
    // If so, then load the current game.
    load_game();
    // If so, is there a game reset?  If so, start a new game.
    if (isset($_GET['reset'])) {
      new_game();
    }
  } else {
    // If there's no existing session, start a new game. 
    new_game();
  }

  // Check to see if there's a file load/save.
  if (isset($_GET['save'])) {
    save_to_file();
  } elseif (isset($_GET['load'])) {
    // Load can clobber our existing game.  That's fine.
    load_from_file();
  }

  run_game();

  // Is the game over?  (Win or draw.)  Update the appropriate variables.
  end_game();
  // Save the current state of the game to the session.
  save_game();
?>
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