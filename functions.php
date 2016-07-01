<?php 

  function new_game() {
    global $grid, $human, $computer;
    $grid = "000000000";
    $human = rand(1,2);
    $computer = opponent($player);
  }

  function load_game() {
    global $grid, $human, $computer;
    $grid = $_SESSION['grid'];
    $human = $_SESSION['human'];
    $computer = $_SESSION['computer'];
  }

  function save_game() {
    global $grid, $human, $computer;
    $_SESSION['grid'] = $grid;
    $_SESSION['human'] = $human;
    $_SESSION['computer'] = $computer;
  }

  function blank_grid() {
    // Just creates a blank grid.
    global $grid;
    $grid = '000000000';
  }

  function opponent($player) {
    // Returns the other player's number.
    switch ($player) {
      case 1:
        return 2;
        break;
      case 2:
        return 1;
        break;
    }
  }

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

  function check_valid_move($square) {
    global $grid, $winner;
    // Can this player mark this square?
    return $grid[$square] == 0 && !isset($winner);
  }

  function display_square($grid, $square, $player, $winner) {
    // Create the HTML for a given grid square.

    // The letter to put in.
    $content = num2mark($grid[$square]);

    // If the square's free...
    if (is_available($grid, $square, $winner)) {

      // Render the HTML tag for a linked square.
      echo <<<HTML
        <a class="grid__square" href="?move=$square">$content</a>
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
      // Checks to see if there's a winner with the proposed grid.

      // Each sub-array is a list of the squares for a given valid win.
      // There are only eight, so this seemed cleanest.
      $wins = [[0,1,2], [3,4,5], [6,7,8], [0,3,6], [1,4,7], [2,5,8], [0,4,8], [2,4,6]];

      // For each possible win, see if all three squares are the same and NOT 0.
      foreach ($wins as $win) {
        if ($grid[$win[0]] == $grid[$win[1]] && $grid[$win[0]] == $grid[$win[2]] && $grid[$win[0]] != 0) {
          // If so, we have a winner, so return who that is.
          return $grid[$win[0]];
        }
      }

      return false;
    }

  function check_winning_move($square, $player) {
    // Checks to see if that square represents a winning move for the given player.

    global $grid;
    $temp_grid = $grid;
    $temp_grid[$square] = $player;
    return check_winner($temp_grid) == $player && check_valid_move($square);;
  }

  function do_move($square, $player) {
    global $grid;
    $grid[$square] = $player;
  }

  function computer_move() {
    // Decides on a move for the computer.
    global $grid, $human, $computer;

    // In the absence of any other moves, establish priority.

    $move_list = [4, 0, 2, 6, 8, 1, 3, 5, 7];

    // First line is to see the first of these available.

    // The thing we'd like BEST is to win.
    for ($square = 0; $square < 9; $square++) {
      if (check_winning_move($square, $computer)) {
        return $square;
        break;
      }
    }

    // We'd settle for preventing the other player from winning.
    for ($square = 0; $square < 9; $square++) {
      if (check_winning_move($square, $human)) {
        $computer_move = $square;
        break;
      }
    }

    // If we can't do either, pick the best move available.
    // TODO:  Randomize this a bit.
    foreach ($move_list as $move) {
      if (check_valid_move($move)) {
        return $move;
      }
    }

  }

?>