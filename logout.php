<?php // Example 12: logout.php
  require_once 'header.php';

  if (isset($_SESSION['user']))
  {
    destroySession();
    echo "<br><div class='center'>You have been logged out. Please
         <a data-transition='slide'
           href='index.php?r=$randstr'>click here</a>
           to refresh the screen.</div>";
  }
  else echo "<div class='center'>You cannot log out because
             you are not logged in</div>";

  // Auto-redirect after 2 seconds
  echo "<script>setTimeout(function(){ window.location.href = 'index.php?r=$randstr'; }, 2000);</script>";
?>
    </div>
  </body>
</html>