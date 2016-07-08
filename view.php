<?php
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
?>