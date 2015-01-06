<?php 
	require_once('../../../wp-load.php');
	$data = array();
	if (!isset($_POST['request_type']) && !isset($_GET['request_type'])) {
		echo json_encode($data);	
		return;
	}
	if (isset($_POST['request_type']) && $_POST['request_type'] == 'customdesign') {
		$uploaddir = 'design/custom/'.$_POST['folder'].'/';
		foreach($_FILES as $file) {
			if(move_uploaded_file($file['tmp_name'], $uploaddir .$_POST['resolution'].".png" ))
			{
				$files[] = $uploaddir .$_POST['resolution'].".png";
			}
			else
			{
			    $error = true;
			}		
		}
		$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
	} else if (isset($_POST['request_type']) && $_POST['request_type'] == 'shorten_link' && isset($_POST['long_url'])) {
		$data['code']     = 100;
		$data['url']      = $_POST['long_url'];
		$data['message']  = 'Get request';

		// Get params
		$savedParams = get_option("SharexyPluginAdminDisplayMode");
		$savedParams = @unserialize($savedParams);
		$accessToken = (isset($savedParams['bitly_access']))?$savedParams['bitly_access']:"NOT FIND PARAM";

		if ($savedParams['shorten_links'] == 1) {
			if ($savedParams['bitly_not'] == 1) {
				$accessToken = '45f1db8b2aa2ce4a30570c13d1fc83403451506e';
			}
			$savedUrls = get_option("SharexyBitly_".$accessToken);
			$savedUrls = @unserialize($savedUrls);
			if (isset($savedUrls[$data['url']])) {
				$data['code'] = 304;
				$data['url'] = $savedUrls[$data['url']];
			} else {
				$shrtJson = file_get_contents("https://api-ssl.bitly.com/v3/shorten?access_token=".$accessToken."&longUrl=".$data['url']);				
				echo $shrtJson;
				$shrtJson = json_decode($shrtJson);
				if ($shrtJson->status_code == 200) {
					$data['code'] = 200;
					$savedUrls[$data['url']] = $shrtJson->data->url;
					$data['url']  = $shrtJson->data->url;
					update_option("SharexyBitly_".$accessToken, serialize($savedUrls));
				}
			}
			
		}		
	} else if (isset($_POST['request_type']) && $_POST['request_type'] == 'social_click' && isset($_POST['url']) && isset($_POST['socialid'])) {
		$data['code']     = 100;
		$data['message']  = 'Get request';
		$optionName = "SharexyPlugin_".$_POST['socialid'];
		$savedCounters = get_option($optionName);
		if (!$savedCounters) {
			add_option($optionName);
			$savedCounters = get_option($optionName);		
		}
		$savedCounters = @unserialize($savedCounters);
		if (isset($savedCounters[$_POST['url']])) {
			$savedCounters[$_POST['url']]++;
			$data['code']     		 	  = 200;
			$data['message']			  = "Update counter to ".$savedCounters[$_POST['url']];
		} else {
			$savedCounters[$_POST['url']] = 1;
			$data['code']     		 	  = 200;
			$data['message']			  = "Set counter to 1";
		}
		update_option($optionName, serialize($savedCounters));
	} else if (isset($_GET['request_type']) && $_GET['request_type'] == 'getcounters' && isset($_GET['url']) && isset($_GET['callback'])) {				
		$localSocials = array("myspace", "delicious", "digg", "reddit", "add_to_favorites", "send_to_email", "print_page", "blogger", "tumblr", "buffer", "xing", "pocket", "live_journal");		
		foreach ($localSocials as $socialId) {
			$optionName = "SharexyPlugin_".$socialId;
			$savedCounters = get_option($optionName);
			if (!$savedCounters) {
				add_option($optionName);
				$savedCounters = get_option($optionName);		
			}	
			$savedCounters = @unserialize($savedCounters);
			$data[$socialId] = 0;		
			if (isset($savedCounters[$_GET['url']])) {
				$data[$socialId] = $savedCounters[$_GET['url']];
			}
		}		
		$data['url']     = $_GET['url'];	
		$result = json_encode($data);
		echo $_GET['callback']."(".$result.");";
		return;
	} else if (isset($_GET['request_type']) && $_GET['request_type'] == 'google_plus' && isset($_GET['url']) && isset($_GET['callback'])) {
		$data['counter'] = 0;
		$data['url']     = $_GET['url'];
	    $contents = file_get_contents( 'https://plusone.google.com/_/+1/fastbutton?url='.$_GET['url']);
	    preg_match( '/window\.__SSR = {c: ([\d]+)/', $contents, $matches );
	    if( isset( $matches[0] ) ) {
	    	$data['counter'] = (int) str_replace( 'window.__SSR = {c: ', '', $matches[0] );
	    }
		$result = json_encode($data);
		echo $_GET['callback']."(".$result.");";	        
	    return;		
	} else if (isset($_GET['request_type']) && $_GET['request_type'] == 'vkontakte' && isset($_GET['url']) && isset($_GET['callback'])) {
		$data['counter'] = 0;
		$data['url']     = $_GET['url'];
	    $contents = file_get_contents( 'http://vkontakte.ru/share.php?act=count&index=0&url='.$_GET['url']);
	    $contents = explode(',', $contents);
	    $contents = str_replace(");", "", $contents[1]);
	    $data['counter'] = $contents;
		$result   = json_encode($data);
		echo $_GET['callback']."(".$result.");";	        
	    return;		
	} else if (isset($_GET['request_type']) && $_GET['request_type'] == 'stumbleupon' && isset($_GET['url']) && isset($_GET['callback'])) {
		$data['counter'] = 0;
		$data['url']     = $_GET['url']; 
	    $contents = file_get_contents("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=".$_GET['url']."&format=json");
	    $contents = json_decode($contents, true);
	    if (isset($contents['result']) && isset($contents['result']['views'])) {
	    	$data['counter'] = (int)$contents['result']['views'];
	    }
		$result   		 = json_encode($data);
		echo $_GET['callback']."(".$result.");";	        
	    return;		
	} else if (isset($_GET['request_type']) && $_POST['request_type'] == 'hide_notify' && isset($_POST['guid'])) {
		$notifys = get_option("SharexyPlugin_notify");
		if (!$notifys) {
			add_option("SharexyPlugin_notify");
			$notifys = get_option("SharexyPlugin_notify");		
		}
		$notifys = @unserialize($notifys);
		$notifys[$_POST['guid']] = true;
		update_option("SharexyPlugin_notify", serialize($notifys));
		$data['code'] = 200;
	}

	echo json_encode($data);	
?>