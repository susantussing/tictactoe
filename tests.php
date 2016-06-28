<?php
  include 'functions.php';

  echo "Test:  Player 1 is X, player 2 is O.\n";

  if (num2mark(1) == "X" && num2mark(2) == "O") {
    echo "Success!\n";
  } else {
    echo "Failure.\n";
  }

  echo "Test:  In a grid of 000100200, squares 3 and 6 are taken.\n";

  $grid = "000100200";
  if (!is_available($grid, 3, NULL) && !is_available($grid, 6, NULL)) {
    echo "Success!\n";
  } else {
    echo "Failure.\n";
  }

  echo "Test:  In that grid, square 0 is not available if there's a winner.\n";

  if (!is_available($grid, 3, "X")) {
    echo "Success!\n";
  } else {
    echo "Failure.\n";
  }

  echo "Test:  The grid 000000111 contains a winner.  The grid 000002220 does not.\n";
  if (check_winner("000000111") == "X" && check_winner("000002220") == "") {
    echo "Success!\n";
  } else {
    echo "Failure.\n";
  }

?>