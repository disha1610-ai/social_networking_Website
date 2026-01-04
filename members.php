<?php // Example 09: members.php
require_once 'header.php';

if (!$loggedin) die("</div></body></html>");

if (isset($_GET['view']))
{
  $view = sanitizeString($_GET['view']);
  
  if ($view == $user) $name = "Your";
  else               $name = "$view's";
  
  echo "<h3>$name Profile</h3>";
  showProfile($view);
  echo "<a data-role='button' data-transition='slide'
        href='messages.php?view=$view&r=$randstr'>View $name messages</a>";
  die("</div></body></html>");
}

if (isset($_GET['add']))
{
  $add = sanitizeString($_GET['add']);

  $result = queryMysql("SELECT * FROM friends
    WHERE username='$add' AND friend='$user'");
  
  if (!$result->rowCount())
    queryMysql("INSERT INTO friends (username, friend) VALUES ('$add', '$user')");
}
elseif (isset($_GET['remove']))
{
  $remove = sanitizeString($_GET['remove']);
  queryMysql("DELETE FROM friends
    WHERE username='$remove' AND friend='$user'");
}

/* ---- FIXED QUERY HERE ---- */
$result = queryMysql("SELECT username FROM members ORDER BY username");
$num    = $result->rowCount();

echo "<h3>Other Members</h3><ul data-role='listview' data-inset='true'>";

while ($row = $result->fetch())
{
  if ($row['username'] == $user) continue;
  
  echo "<li><a data-transition='slide' href='members.php?view=" .
    $row['username'] . "&r=$randstr'>" . $row['username'] . "</a>";
  $follow = "follow";

  /* ---- FIXED friends queries ---- */
  $result1 = queryMysql("SELECT * FROM friends WHERE
    username='" . $row['username'] . "' AND friend='$user'");
  $t1 = $result1->rowCount();
  
  $result1 = queryMysql("SELECT * FROM friends WHERE
    username='$user' AND friend='" . $row['username'] . "'");
  $t2 = $result1->rowCount();

  if (($t1 + $t2) > 1) echo " &harr; is a mutual friend";
  elseif ($t1)        echo " &larr; you are following";
  elseif ($t2)      { echo " &rarr; is following you";
                       $follow = "recip"; }
  
  if (!$t1) echo " [<a data-transition='slide'
    href='members.php?add=" . $row['username'] . "&r=$randstr'>$follow</a>]";
  else      echo " [<a data-transition='slide'
    href='members.php?remove=" . $row['username'] . "&r=$randstr'>drop</a>]";
}

echo "</ul></div></body></html>";
?>
