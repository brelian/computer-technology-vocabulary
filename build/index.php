<?php

$keyword  = 'MySQL';
$url      = 'http://api.pearson.com/v2/dictionaries/entries?headword=' . $keyword . '&audio=pronunciation';
$response = json_decode(file_get_contents($url), true);

if ($response['results']) {
    // if results in not empty
    $pronunciation = $response['result']['pronunciations'][0];
    $sense         = $response['result']['senses'][0];
} else {
    // if results is empty, using anather API
    $url      = 'http://en.wikipedia.org/w/api.php?action=opensearch&search=' . $keyword . '&limit=1&namespace=0&format=json';
    $response = json_decode(file_get_contents($url), true);
    print_r($response);
}
