<?php 

  function num2mark ($num) {
    // Converts a numerical grid square assignment to its appropriate mark.

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

  function is_available($grid, $square, $winner) {
    // Can this player mark this square?
    return $grid[$square] == 0 && !isset($winner);
  }

  function display_square($grid, $square, $player, $winner) {
    // Create the HTML for a given grid square.

    // The letter to put in.
    $content = num2mark($grid[$square]);

    // If the square's free...
    if (is_available($grid, $square, $winner)) {
      // If we're currently player 1, next move is player 2.
      // Otherwise, we're player 2, and next move is player 1.
      $newplayer = ($player == 1) ? 2 : 1;

      // This square's link should go to a grid that has this player's
      // mark in this square.
      $newgrid = substr_replace($grid, $player, $square, 1);

      // Render the HTML tag for a linked square.
      echo <<<HTML
        <a class="grid__square" href="?grid=$newgrid&player=$newplayer">$content</a>
HTML;
    } else {
      // If the square isn't free, then make an unlinked box with the 
      // current mark.
      echo <<<HTML
        <div class="grid__square">$content</div>   
HTML;
    }
  }

  function display_grid($grid, $player, $winner) {
    // Create the HTML to display the tic-tac-toe grid.
    for ($i = 0; $i < 9; $i++) {
      display_square($grid, $i, $player, $winner);
    }
  }

  function check_winner($grid) {
    // Check the current grid to see if anybody's won.
    // Each sub-array is a list of the squares for a given valid win.
    // There are only eight, so this seemed cleanest.
    $wins = [[0,1,2], [3,4,5], [6,7,8], [0,3,6], [1,4,7], [2,5,8], [0,4,8], [2,4,6]];

    // For each possible win, see if all three squares are the same and NOT 0.
    foreach ($wins as $win) {
      if ($grid[$win[0]] == $grid[$win[1]] && $grid[$win[0]] == $grid[$win[2]] && $grid[$win[0]] != 0) {
        // If so, we have a winner, so return the player's mark.
        return num2mark($grid[$win[0]]);
      }
    }
  }
?>