<?php
  
  include 'functions.php';

  function run_test($test) {
    if ($test) {
      echo "Success!\n";
    } else {
      echo "Failure.\n";
    }
  }

  // blank_grid
  echo "Checking to see if we can start a blank grid.\n";
  blank_grid();

  run_test($grid == "000000000");

  // do_move
  echo "Checking to see if we can enter a move.\n";
  blank_grid();
  do_move(4,1);

  run_test($grid == "000010000");

  // check_valid_move
  echo "Checking to see if invalid move reported.\n";
  $grid = "000010000";
  run_test(!check_valid_move(4));

  echo "Checking to see if valid move reported.\n";
  run_test(check_valid_move(0));

  // check_winner
  echo "Checking to see if winning/losing grids reported correctly.\n";

  $grid = "111000000";
  run_test(check_winner($grid) == "1");
  $grid = "200020002";
  run_test(check_winner($grid) == "2");
  $grid = "110000002";
  run_test(!check_winner($grid));

  // check_winning_move
  echo "Checking to see if winning moves are identified properly.\n";

  $grid = "110000000";
  run_test(check_winning_move(2, 1));
  run_test(!check_winning_move(2, 2));

  // computer_move

  echo "Checking to see that the computer wins if it can.\n";

  $_SESSION['human'] = 1;
  $computer = 2;

  $grid = "220010000";
  run_test(computer_move() == 2);

  echo "Checking to see that the computer blocks if it can.\n";

  $grid = "110020000";
  run_test(computer_move() == 2);

  echo "Checking to see that the computer can make other moves.\n";

  blank_grid();
  run_test(computer_move() == 4);

  $grid = "000010000";
  run_test(computer_move() == 0);
?>