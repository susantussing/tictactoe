<?php
function run_game() {
  global $computer, $human, $grid, $move;

  // Now we have either a new game, or an existing game loaded.
  // If it's the first turn and the computer is player 1, the computer should go before the human player sees the grid.
  if ($computer == 1 && $grid == "000000000") {
    do_move(computer_move(), 1);
  } elseif (isset($move)) {
    // Is there a valid move input?  If so, the human player should move, and then go to the computer player move.
    if (check_valid_move($move)) {
      do_move($move, $human);
      do_move(computer_move(), $computer);
    }
  }
  // If there's no valid move input and the computer isn't entitled to go first, make no changes to the grid.
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

?>