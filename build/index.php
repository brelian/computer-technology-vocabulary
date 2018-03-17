<?php
// $keyword  = 'MySQL';
$keyword  = $argv[1];
$url      = 'http://api.pearson.com/v2/dictionaries/entries?headword=' . $keyword . '&audio=pronunciation';
$response = json_decode(file_get_contents($url), true);

if ($response['results']) {
    // if results in not empty
    foreach ($response['results'] as  $value) {
    	$senses[] = $value['senses'][0]['definition'][0];
    	if (empty($pronunciations) && !empty($value['pronunciations'])) {
	    	$pronunciation = $value['pronunciations'][0];
    	}
    }

} else {
    // if results is empty, using anather API
    $url      = 'http://en.wikipedia.org/w/api.php?action=opensearch&search=' . $keyword . '&limit=1&namespace=0&format=json';
    $response = json_decode(file_get_contents($url), true);
    // $pronunciations = ...
    $senses = $response[2];
}

$audioUrl = 'http://dict.youdao.com/dictvoice?audio='.$keyword;


$tbody = "\n| $keyword |[:speaker:]($audioUrl) | $senses[0] |      |      |";
file_put_contents(realpath('../README.md'), $tbody,FILE_APPEND);
