<?php
// 1. Database Login Details
$host = 'localhost';
$data = 'robinsnest';
$user = 'robinsnest';
$pass = 'password';
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts =
[
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 2. Create the PDO Connection
try
{
    $pdo = new PDO($attr, $user, $pass, $opts);
}
catch (\PDOException $e)
{
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// 3. Define Functions

function createTable($name, $query)
{
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
}

function queryMysql($query)
{
    global $pdo;
    return $pdo->query($query);
}

function destroySession()
{
    $_SESSION = array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

function sanitizeString($var)
{
    global $pdo;
    $var = strip_tags($var);
    $var = htmlentities($var);
    // Removed get_magic_quotes_gpc as it is removed in modern PHP
    $result = $pdo->quote($var); // This adds single quotes
    return str_replace("'", "", $result); // So now remove them
}

function showProfile($user)
{
    global $pdo; // Ensure $pdo is accessible
    
    if (file_exists("$user.jpg"))
        echo "<img src='$user.jpg' style='float:left;'>";
        
    $result = $pdo->query("SELECT * FROM profiles WHERE username='$user'");
    
    // Fixed logic: 'die' would stop the script, changed to echo
    while ($row = $result->fetch())
    {
        echo stripslashes($row['text']) . "<br style='clear:left;'><br>";
    }
}
?>