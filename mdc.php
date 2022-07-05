<?php

$url = 'https://megadumbcast.podbean.com/page/';
$pages = 123;
$episodeCount = 0;

$dom = new DOMDocument();

for($i = $pages; $i > 0; $i--) {

    $html = file_get_contents($url . $i . '/');

    @$dom->loadHTML($html);

    $finder = new DomXPath($dom);
    $entries = $finder->query("//*[contains(@class, 'entry')]");

    for($j = $entries->length - 1; $j >= 0; $j--) {
        $episodeCount--;
        $episodeNumber = str_pad(abs($episodeCount),  3, "0", STR_PAD_LEFT);
        $entry = $entries->item($j);    // DOMElement

        $info = $entry->childNodes->item(3);
        $body = $info->childNodes;

        $title = $body->item(1)->nodeValue;

        $date = $body->item(3)->nodeValue;

        $text = $dom->saveHTML($body->item(5));

        $mp3Url = $body->item(9)->childNodes->item(0)->getAttribute('data-uri');   // just plain guesswork
        $fileName = $episodeNumber . ' - ' . array_pop(explode('/',$mp3Url));

        print "\n=== {$episodeNumber} {$title} ===\n";

        $myfile = fopen(str_replace('.mp3','.html',$fileName), "w");
        fwrite($myfile, "<p>{$title}</p>\n");
        fwrite($myfile, "<p>{$date}</p>\n");
        fwrite($myfile, $text);
        fclose($myfile);

        $mp3Data = file_get_contents($mp3Url);
        $mp3File = fopen($fileName, "w");
        fwrite($mp3File, $mp3Data);
        fclose($mp3File);
    }
}