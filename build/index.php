<?php
define('APP_PATH', dirname(__DIR__));
// $keyword  = 'MySQL';
$keyword = $argv[1];

// if results is empty, using anather API
$url      = 'http://en.wikipedia.org/w/api.php?action=opensearch&search=' . $keyword . '&limit=3&namespace=0&format=json';
$response = json_decode(file_get_contents($url), true);
if (!empty($response[1][0]) && !empty($response[2][0])) {
    echo "from widipedia \n";
    $word       = $response[1][0];
    $definition = ($response[2][0] == $word." may refer to:") ? $response[2][1] : $response[2][0];
    // $definition = $response[2][0];
} else {
    echo "from pearson \n";
    $url      = 'http://api.pearson.com/v2/dictionaries/entries?headword=' . $keyword;
    $response = json_decode(file_get_contents($url), true);

    if ($response['results']) {
        foreach ($response['results'] as $value) {
            if (!empty($value['senses'][0]['definition'][0])) {
                $senses[] = is_array($value['senses'][0]['definition']) ? $value['senses'][0]['definition'][0] : $value['senses'][0]['definition'];
                $headword[] = $value['headword'];
            }
        }

        $definition = $senses[0];
        $word       = $headword[0];
    }
    // print_r($senses);
}

if (!empty($word) && !empty($definition)) {
	$audioUrl = 'http://dict.youdao.com/dictvoice?audio=' . $word;
	$row      = "\n| $word |[:speaker:]($audioUrl) | $definition |      |      |";
	file_put_contents(APP_PATH . '/README.md', $row, FILE_APPEND);
} else {
	echo "Not find ".$keyword;
}
