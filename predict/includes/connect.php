
<?php
function connectdatabaseBIG()
{
    $host = "";
    $user = "";
    $pwd = "";
    $database = "";
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/predict/config.php';
    if (!file_exists($file_path)) {
        echo('Config file not found. ' . $file_path);
        return (-1);
    }
    $configfile = fopen($file_path, "r");
    while (!feof($configfile)) {
        $line = fgets($configfile);
        $splitline = explode(':', $line);
        if (strcmp($splitline[0], "database") == 0) {
            $database = $splitline[1];
        } else if (strcmp($splitline[0], "user") == 0) {
            $user = $splitline[1];
        } else if (strcmp($splitline[0], "host") == 0) {
            $host = $splitline[1];
        } else if (strcmp($splitline[0], "pwd") == 0) {
            $pwd = $splitline[1];
        }
    }

    fclose($configfile);
    $host = preg_replace("/\r|\n/", "", $host);
    $user = preg_replace("/\r|\n/", "", $user);
    $pwd = preg_replace("/\r|\n/", "", $pwd);
    $database = preg_replace("/\r|\n/", "", $database);
    $connexion = new mysqli($host, $user, $pwd, $database);
    if ($connexion->connect_error) {
        echo("connectdatabaseBIG failed: " . $connexion->connect_error);
        return (-1);
    }
    return $connexion;
}
?>
