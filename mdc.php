<?php


$url = 'https://megadumbcast.podbean.com/page/';
$pages = 123;
$pages = 1;
$episodeCount = 0;

for($i = $pages; $i > 0; $i--) {

//    $data = file_get_contents($url . $i . '/');
    $data = file_get_contents('page.html');

    $playlist = explode('<div class="playlist-panel">',$data)[1];
    $content = explode('<div class="navigation">',$playlist);
    $posts = explode('<div class="thumbnail">',$content[0]);

    for($i = count($posts); $i > 0; $i--) {
        $episodeCount--;
        $post = '';
        $name = str_pad(abs($episodeCount),  3, "0", STR_PAD_LEFT);
        $post = explode('<div class="info">', $posts[$i])[1];
        $post = explode("<iframe id='audio_iframe'", $post)[0];

        $xpath = new DOMXPath($post);


//        $myfile = fopen($name . ".html", "w");
//        fwrite($myfile, $post);
//        fclose($myfile);
    }

}