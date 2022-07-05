# Podbean scraper

I am a big fan of the MegaDumbCast (MDC) and his episodes lambasting some of the cherished Palladium RPG books from my adolescence.

Unfortunately recent events mean the creator is no longer continuing the show. This means closing the [Patreon](https://www.patreon.com/megadumbcast), the @megadumbcast Twitter and Instagram accounts, and the [Podbean](https://megadumbcast.podbean.com) hosting.

Rather than let the episodes disappear forever, this scraper will archive the public episodes and their metadata for the future.

https://feed.podbean.com/megadumbcast/feed.xml


## Usage

To download the episodes, run the PHP file in the terminal:

    php mdc.php


## Bugs

* The scraper assumes one paragraph per entry, so an entry with two paragraphs will break the `DOMElement` by looking in the wrong place for the MP3 url
* Don't change the value for `$pages` once set, or it'll mess up the episode count
