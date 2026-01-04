<?php // Example 10: friends.php 
require_once 'header.php';

if (!$loggedin) die("</div></body></html>");

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else                      $view = $user; // $user comes from session

if ($view == $user)
{
    $name1 = $name2 = "Your";
    $name3 = "You are";
}
else
{
    $name1 = "<a data-transition='slide'
              href='members.php?view=$view&r=$randstr'>$view</a>'s";
    $name2 = "$view's";
    $name3 = "$view is";
}

// Uncomment this line if you wish the user's profile to show here
// showProfile($view);

$followers = array();
$following = array();

// Get people following the user (i.e., who added $view as friend)
$result = queryMysql("SELECT * FROM friends WHERE username='$view'");
while ($row = $result->fetch())
{
    $followers[] = $row['friend']; // Append to array
}

// Get people the user is following
$result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
while ($row = $result->fetch())
{
    $following[] = $row['username']; // Append to array
}

// Calculate Mutual Friends (Intersection of both arrays)
$mutual    = array_intersect($followers, $following);

// Remove mutuals from the main lists to avoid duplicates
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);

$friends   = FALSE;

echo "<br>";

if (sizeof($mutual))
{
    echo "<span class='subhead'>$name2 mutual friends</span><ul data-role='listview' data-inset='true'>";
    foreach($mutual as $friend)
        echo "<li><a data-transition='slide'
              href='members.php?view=$friend&r=$randstr'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
}

if (sizeof($followers))
{
    echo "<span class='subhead'>$name2 followers</span><ul data-role='listview' data-inset='true'>";
    foreach($followers as $friend)
        echo "<li><a data-transition='slide'
              href='members.php?view=$friend&r=$randstr'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
}

if (sizeof($following))
{
    echo "<span class='subhead'>$name3 following</span><ul data-role='listview' data-inset='true'>";
    foreach($following as $friend)
        echo "<li><a data-transition='slide'
              href='members.php?view=$friend&r=$randstr'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
}

if (!$friends) echo "<br>You don't have any friends yet.";
?>
    </div><br>
  </body>
</html>
