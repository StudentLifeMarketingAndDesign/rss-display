<?php
class RssDisplayExtension extends DataExtension {

	public function RSSDisplay($numItems = 30, $feedURL = "feed://afterclass.uiowa.edu/events/") {

		$output = new ArrayList();

		include_once 'simplepie.mini.php';
		$t1 = microtime(true);
		$feed = new SimplePie();
		$feed->set_feed_url($feedURL);
		$feed->enable_cache(false);
		$feed->init();

		if ($items = $feed->get_items(0, $numItems)) {

			foreach ($items as $item) {

				// Cast the Title
				$title = new Text('Title');
				$title->setValue(html_entity_decode($item->get_title()));

				// Cast the Date
				$date = new Date('Date');
				$date->setValue($item->get_date());

				$author = new Text('Author');
				$item_author = $item->get_author();

				if (isset($item_author)) {
					$author->setValue($item_author->get_name());}

				// Cast the description and strip
				$desc = new HTMLText('Description');
				$desc->setValue(strip_tags($item->get_description()));

				if ($thumbnail_enclosure = $item->get_enclosure()) {
					$thumbnail_url = new Text('ThumbnailURL');
					$thumbnail_url->setValue($thumbnail_enclosure->get_thumbnail());

					//legacy enclosure stuff for older sites like CSIL:
					$thumbnail_alt_url = new Text('ThumbnailAltURL');
					$thumbnail_alt_url->setValue($thumbnail_enclosure->link);

				} else {
					$thumbnail_url = null;

				}

				$output->push(new ArrayData(array(
					'Title' => $title,
					'Author' => $author,
					'Date' => $date,
					'Link' => $item->get_link(),
					'Description' => $desc,
					'ThumbnailURL' => $thumbnail_url,
					'ThumbnailAltURL' => $thumbnail_alt_url,
				)));
			}

			return $output;
		}

	}//end function HomeEventItems()

}