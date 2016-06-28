<?php 

  function num2mark ($num) {
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

  function is_available($grid, $square) {
    return $grid[$square] == 0;
  }

  function display_box($grid, $square, $player) {
    $content = num2mark($grid[$square]);

    $newplayer = ($player == 1) ? 2 : 1;
    $newgrid = substr_replace($grid, $player, $square, 1);
    if (is_available($grid, $square)) {
      echo <<<HTML
        <a class="box" href="?grid=$newgrid&player=$newplayer">$content</a>
HTML;
    } else {
        echo <<<HTML
          <div class="box">$content</div>   
HTML;
    }
  }

  function display_grid($grid, $player) {
    for ($i = 0; $i < 9; $i++) {
      display_box($grid, $i, $player);
    }
  }

  function check_winner($grid) {
        $wins = [[0,1,2], [3,4,5], [6,7,8], [0,3,6], [1,4,7], [2,5,8], [0,4,8], [2,4,6]];

        foreach ($wins as $win) {
          if ($grid[$win[0]] == $grid[$win[1]] && $grid[$win[0]] == $grid[$win[2]] && $grid[$win[0]] != 0) {
            return num2mark($grid[$win[0]]);
          }
      }
  }
?>