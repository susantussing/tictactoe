<?php
  session_start();
  $game_vars = ['grid', 'human', 'computer', 'wins', 'losses', 'draws', 'done'];
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

  // File storage functions
  $save_file = "tttSave.txt";
  function save_to_file() {
    global $save_file, $grid, $human, $computer, $done;
    $fh = fopen($save_file, 'w');
    $save_data = $grid . $human . $computer . $done;
    fwrite($fh, $save_data);
  }

  function load_from_file() {
    global $save_file, $grid, $human, $computer, $done;
    $fh = fopen($save_file, 'r');
    $save_data = fread($fh, filesize($save_file));
    $grid = substr($save_data, 0, 9);
    $human = $save_data[9];
    $computer = $save_data[10];
    $done = $save_data[11];
  }


?>