<?php
require_once "db.php";
require_once "functions.php";

$time_start = microtime(true);

$db = Db::getInstance();
$db->createTable();

$i = 0;
$c = 0;
$chunk_size = 500;
$query = "";

$file = fopen('FirstNames.csv', 'r');
echo "<div style='white-space: pre-wrap; font-family: tahoma;font-size: 12px;'>";
echo "=-=-= Reading Names =-=-=\n";

while (($line = fgetcsv($file)) !== FALSE) {
    if ($i++ == 0)
        continue;
    echoLine($i - 1, $line);
    $query .= $db->getQuery($line[0], $line[1] == 1 ? '1' : '0', $line[2] == 1 ? '1' : '0', getRarityLevelId($line[3]));
    $c++;
    if ($c == $chunk_size) {
        echo "\n\n=-=-= Executing Chunk, size = $c =-=-=\n\n";
        $db->multiQuery($query);
        $query = "";
        $c = 0;
        flush_buffers();
    }
}

if ($query != "") {
    echo "\n\n=-=-= Executing Chunk, size = $c =-=-=\n\n";
    $db->multiQuery($query);
}

fclose($file);

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo "=-=-= Done! =-=-=\n";
echo "Time: $execution_time seconds\n";

echo "</div>";


