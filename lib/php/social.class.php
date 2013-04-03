<?php
class social{
	/**
	* Retrieve transient value.
	*
	* Returns a transient value, or updates that transient if it has expired.
	*
	* @param $name String. Name of the service, e.g. twitter
	* @return String. New transient value.
	*/
	function pbd_get_transient($name = 'twitter',$user_id='',$cacheTime = 60) {
		/*$cacheTime  in Minutes */	
		$transName = "pbd-transient-$name-$user_id"; // Name of value in database.
		//$cacheTime = 8; // Time in hours between updates.
	
		// Do we already have saved tweet data? If not, lets get it.
		if(false === ($trans = get_transient($transName) ) || $trans = get_transient($transName) == '' || 'Function not found.' == get_transient($transName)) :
	
			// Make the name of the function that gets our transient value.
			$func = "pbd_get_$name";   
			if(method_exists('social',$func) ) { 
				$trans = self::$func($user_id);
			} else {
				$trans = 'Function not found.';
			}
	
			// Did we get a positive number? If not, use the old value.
			if(!absint($trans) > 0)
				$trans = get_transient($transName . '-old');
	
			// Save our new transient, plus save it in the longer "backup" transient.
			set_transient($transName, $trans, 60 * $cacheTime);
			set_transient($transName.'-old', $trans, 3 * 60 * $cacheTime);

		else:
			$trans = get_transient($transName);
		endif;
	
		return $trans;
	}

	/**
	* Get Facebook Page Fan Count.
	*/
	function pbd_get_facebook($user_id = '') {
		$json = wp_remote_get("http://graph.facebook.com/$user_id");
	
		if(is_wp_error($json))
			return false;
	
		$fbData = json_decode($json['body'], true);
	
		if(isset($fbData['likes'])){
			$nr_likes = intval($fbData['likes']);
		}else{
			$nr_likes = '';
		}
		return $nr_likes;
	}
}

?>