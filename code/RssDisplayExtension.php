<?php
class RssDisplayExtension extends Extension{


	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */

	public function RSSDisplay($numItems = 30, $feedURL="http://afterclass.uiowa.edu/rss") {
		
			$output = new DataObjectSet();
			$output->setPageLength(3);
			include_once('simplepie/simplepie.inc');
			$t1 = microtime(true);
			$feed = new SimplePie($feedURL, TEMP_FOLDER);
			$feed->enable_cache(false);
			$feed->init();
			$feed->get_items(0, $numItems);
			
			if($items = $feed->get_items(0, $numItems)) {
		  	
			foreach($items as $item) {
			 	//do we need the simplepie rss2 namespace? test it. --actually... it isn't needed? idk why.
			 	//$custom_fields = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20,'custom_fields');
			 	$custom_fields = $item->get_item_tags(null,'custom_fields');
				
				// Cast the Date
				//$date = new Date('Date');
				//$date->setValue($item->get_date());
				
				// Cast the Title
				$title = new Text('Title');
				$title->setValue($item->get_title());
				
				$item_author = $item->get_author();
				$author = new Text('Author');
				
				$dates = new Text('Dates');
				$dates->setValue($item->get_item_tags(null,'dates'));
				$dates->setValue($dates->value[0]['data']);
				
				$pubDate = new Date('PublishedDate');
				$pubDate->setValue($item->get_date());
				
				$text = new Text('Content');
				$text->setValue(htmlspecialchars($item->get_description(), ENT_QUOTES));
				
				$cost = new Text('Cost');
				$cost->setValue($item->get_item_tags(null,'cost'));
				$cost->setValue($cost->value[0]['data']);
				
				$location = new Text('Location');
				$location->setValue($item->get_item_tags(null,'location'));
				$location->setValue($location->value[0]['data']);
				
				
				if(isset($item_author)){
					$author->setValue($item_author->get_name());}
				
				// Cast the description and strip
				$desc = new HTMLText('Description');
				$desc->setValue(strip_tags($item->get_description()));
				
				/*if(isset($custom_fields[0]['child']['']['EventCost'][0]['data'])){
					$cost = new Text('Cost');
					$cost->setValue(strip_tags($custom_fields[0]['child']['']['EventCost'][0]['data']));
				}else{ 
					$cost = null;
				}
				
				if(isset($custom_fields[0]['child']['']['EventDate'][0]['data'])){
					$event_date = new Text('EventDate');
					$event_date->setValue(strip_tags($custom_fields[0]['child']['']['EventDate'][0]['data']));
				}else{
					$event_date = null;
				}
				
				if(isset($custom_fields[0]['child']['']['EventLocation'][0]['data'])){
					$location = new Text('EventLocation');
					$location->setValue(strip_tags($custom_fields[0]['child']['']['EventLocation'][0]['data']));
				}else{
					$location = null;
				}
				if($thumbnail_enclosure = $item->get_enclosure()){
					//Thumbnail
					$thumbnail_url = new Text('ImageURL');
					$thumbnail_url->setValue($thumbnail_enclosure->link);
				}else{
					$thumbnail_url = null;
				}
				
				,
				   'Description'   => $desc,
				   'Cost' => $cost,
				   'EventDate' => $event_date,
				   "Location" => $location,
				   "ImageURL" => $thumbnail_url
				
				*/
				
				
				$output->push(new ArrayData(array(
				   'Title'		=> $title,
				   'Author' => $author,
				   'Content'    => $text,
				   'Dates'		=> $dates,
				   'PublishedDate' => $pubDate,
				   'Cost'		=> $cost,
				   'Location'	=> $location,
				   'Link'		=> $item->get_link()
				)));
			 }
			
			 return $output;
		  } 
		}//end function HomeEventItems()

}