<?php 
  function run_game() {
    global $computer, $human, $grid;

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

    // Now we have either a new game, or an existing game loaded.

    // If it's the first turn and the computer is player 1, the computer should go before the human player sees the grid.
    if ($computer == 1 && $grid == "000000000") {
      do_move(computer_move(), 1);
    } elseif (isset($_GET['move'])) {
      // Is there a valid move input?  If so, the human player should move, and then go to the computer player move.
      $move = $_GET['move'];
      if (check_valid_move($move)) {
        do_move($move, $human);
        do_move(computer_move(), $computer);
      }
    }
    // If there's no valid move input and the computer isn't entitled to go first, make no changes to the grid.
    // Now we have the grid after all moves have been made.

    // Is the game over?  (Win or draw.)  Update the appropriate variables.
    end_game();
    // Save the current state of the game to the session.
    save_game();
  }

  $game_vars = ['grid', 'human', 'computer', 'wins', 'losses', 'draws', 'done'];
  function new_game() {
    global $grid, $human, $computer, $wins, $losses, $draws, $done;
    blank_grid();
    $human = rand(1,2);
    $computer = opponent($human);
    $done = false;

    // Only set these if there's really no existing session.
    if (!isset($_SESSION['wins'])) {
      $wins = 0;
      $losses = 0;
      $draws = 0;
    }
  }

  function load_game() {
    global $game_vars;
    foreach ($game_vars as $game_var) {
      $GLOBALS[$game_var] = $_SESSION[$game_var];
    }
  }

  function save_game() {
    global $game_vars;
    foreach ($game_vars as $game_var) {
      $_SESSION[$game_var] = $GLOBALS[$game_var];
    }
  }

  function end_game() {
    global $wins, $losses, $draws, $grid, $human, $computer, $done, $winner;
    $winner = check_winner($grid);
    if ( ($winner || check_draw()) && !$done ) {
      if ($winner == $human) {
        $wins++;
      } elseif ($winner == $computer) {
        $losses++;
      } else {
        $draws++;
      }
      $done = true;
    }
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
    global $grid, $done;
    // Can this player mark this square?
    return $grid[$square] == 0 && !$done;
  }

  function display_square($square) {
    global $grid;
    // Create the HTML for a given grid square.

    // The letter to put in.
    $content = num2mark($grid[$square]);

    // If the square's free...

    if (check_valid_move($square)) {

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

  function display_grid() {
    // Create the HTML to display the tic-tac-toe grid.
    global $grid;
    for ($square = 0; $square < 9; $square++) {
      display_square($square);
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
      }
    }

    // We'd settle for preventing the other player from winning.
    for ($square = 0; $square < 9; $square++) {
      if (check_winning_move($square, $human)) {
        return $square;
      }
    }

    // If we can't do either, pick the best move available.
    // TODO:  Randomize this a bit.
    // TODO:  Computer shouldn't pick a move that's already got its win blocked.
    foreach ($move_list as $move) {
      if (check_valid_move($move)) {
        return $move;
      }
    }

  }

  function check_draw() {
    global $grid;
    $winner = check_winner($grid);
    if (!$winner) {
      for ($square = 0; $square < 9; $square++) {
        if (check_valid_move($square)) {
          return false;
        }
      }
      return true;
    }
    return false;
  }

  // File storage functions
  $save_file = "tttSave.txt";
  function save_to_file() {
    global $save_file, $grid, $human, $computer;
    $fh = fopen($save_file, 'w');
    $save_data = $grid . $human . $computer;
    fwrite($fh, $save_data);
  }

  function load_from_file() {
    global $save_file, $grid, $human, $computer;
    $fh = fopen($save_file, 'r');
    $save_data = fread($fh, filesize($save_file));
    $grid = substr($save_data, 0, 9);
    $human = $save_data[9];
    $computer = $save_data[10];
  }
?>