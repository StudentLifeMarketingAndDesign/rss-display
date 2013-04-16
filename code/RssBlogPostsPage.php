<?php
class RssBlogPostsPage extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

}
class RssBlogPostsPage_Controller extends Page_Controller {

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
	public static $allowed_actions = array (
	);

	public function init() {
		parent::init();
	}
function RSSEvents($numItems = 30, $feedURL="http://hulk.imu.uiowa.edu/afterclass_dev/events/newrss/") {
			// echo "1";return new DataObjectSet();
			
			$output = new DataObjectSet();
			$output->setPageLength(3);
			include_once('simplepie/simplepie.inc');
			$t1 = microtime(true);
			$feed = new SimplePie($feedURL, TEMP_FOLDER);
			$feed->enable_order_by_date(false);
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
				
				$dates = new Text('Dates');
				$dates->setValue($item->get_item_tags(null,'dates'));
				$dates->setValue($dates->value[0]['data']);
				
				$cost = new Text('Cost');
				$cost->setValue($item->get_item_tags(null,'cost'));
				$cost->setValue($cost->value[0]['data']);
				
				$location = new Text('Location');
				$location->setValue($item->get_item_tags(null,'location'));
				$location->setValue($location->value[0]['data']);
				
				$description = new Text('Description');
				$description->setValue($item->get_item_tags(null,'description'));
				$description->setValue(strip_tags(html_entity_decode($description->value[0]['data'])));
				
				$smallimage = new Text('Smallimage');
				$smallimage->setValue($item->get_item_tags(null,'smallimage'));
				$smallimage->setValue($smallimage->value[0]['data']);
				
				if(isset($item_author)){
					$author->setValue($item_author->get_name());}
				
				// Cast the description and strip
				$desc = new HTMLText('Description');
				$desc->setValue(strip_tags($item->get_description()));
				
				$output->push(new ArrayData(array(
				   'Title'		=> $title,
				   'Dates'		=> $dates,
				   'Cost'		=> $cost,
				   'Location'	=> $location,
				   'Description'=> $description,
				   'Smallimage' => $smallimage,
				   'Link'		=> $item->get_link()
				)));
			 }
			
			 return $output;
		  } 
		}//end function RSSEvents()		
	/*function RSSEvents($numItems = 30, $feedURL="http://afterclass.uiowa.edu/rss/") {
		
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
				
				$dates = new Text('Dates');
				$dates->setValue($item->get_item_tags(null,'dates'));
				$dates->setValue($dates->value[0]['data']);
				
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
				$desc->setValue( htmlspecialchars ($item->get_description()));
				
				$output->push(new ArrayData(array(
				   'Title'		=> $title,
				   'Dates'		=> $dates,
				   'Cost'		=> $cost,
				   'Location'	=> $location,
				   'Link'		=> $item->get_link()
				)));
			 }
			
			 return $output;
		  } 
		}//end function HomeEventItems()
*/
}