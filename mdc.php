<?php

$url          = 'https://megadumbcast.podbean.com/page/';
$pages        = 123;    // Work backwards from the end, to get episode #1
$episodeCount = 0;
$resumeAt     = 0;      // Resume at this episode after a crash
$dir          = '';

if($dir) {
    $dir .= '/';
    mkdir(getcwd().'/'.$dir);
}

$dom = new DOMDocument();

for($i = $pages; $i > 0; $i--) {

    $html = file_get_contents($url . $i . '/');

    @$dom->loadHTML($html);

    $finder = new DomXPath($dom);
    $entries = $finder->query("//*[contains(@class, 'entry')]");

    for($j = $entries->length - 1; $j >= 0; $j--) {
        $episodeCount--;
        print "p{$i}, e{$j}: {$episodeCount}\n";
        if(abs($episodeCount) >= $resumeAt) {
            $episodeNumber = str_pad(abs($episodeCount),  4, "0", STR_PAD_LEFT);
            $entry = $entries->item($j);    // DOMElement

            $info = $entry->childNodes->item(3);
            $body = $info->childNodes;

            $count = $body->length; // Use this to count backwards for the length of the $text and position of $mp3Url

            $title = $body->item(1)->nodeValue;

            $date = $body->item(3)->nodeValue;

            $text = '';
            for($k=5; $k <= ($count - 8); $k++) {
                $text .= trim($dom->saveHTML($body->item($k)))."\n";
            }

            try {
                $mp3Url = $body->item($count - 4)->childNodes->item(0)->getAttribute('data-uri');   // just plain guesswork
                $fileName = $dir . $episodeNumber . ' - ' . array_pop(explode('/',$mp3Url));

                print "=== {$episodeNumber} {$title} ===\n";

                $myfile = fopen(str_replace('.mp3','.html',$fileName), "w");
                fwrite($myfile, "<p>{$title}</p>\n");
                fwrite($myfile, "<p>{$date}</p>\n");
                fwrite($myfile, $text);
                fclose($myfile);

                $mp3Data = file_get_contents($mp3Url);
                $mp3File = fopen($fileName, "w");
                fwrite($mp3File, $mp3Data);
                fclose($mp3File);
            } catch(Exception $e) {
                print $e->getMessage()."\n";
            }
        }
    }
}
