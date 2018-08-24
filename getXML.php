<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here download  the list of followers and tweets in XML format.
 *
 **/
session_start();
include_once("includes/config.php");
include_once("lib/auth/twitteroauth.php");
error_reporting(0);
$user_name = $_SESSION['request_vars']['screen_name'];
$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);

if(isset($connection))
{
	$Arr = $_SESSION['DownloadInfo'];
	$fileName = $Arr['file_name'];
	$userName = $Arr['user_name'];
	$downloadType = $Arr['download_type'];
	Header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename='.$fileName.'.xml');
	
	if($downloadType=='Tweets')
	{	
		$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><tweets></tweets>");
		$id ="";	
		$tweets = $connection->get("statuses/user_timeline", array("count" => 3000,"screen_name"=>$userName));
		while (true) {
			foreach ($tweets as $temp) {
				if(isset($tweets->errors) && $id =="")
				{
					echo "Sry , try again after 15 min...<br>";
					break;
				}else{
					$child = $xml->addChild('tweet');		
					$child->addChild('name',$temp->user->name);
					$child->addChild('image',$temp->user->profile_image_url);
					$child->addChild('tweet',$temp->text);
					$child->addChild('date',$temp->created_at);
					$id = isset($temp->id)?$temp->id:null;
				}
			}
			if ($id == null) {
				break;
			}
			
			$tweets = $connection->get("statuses/home_timeline", ["count" => 2000, "max_id" => $id , "screen_name"=>$userName]);
		}
		print($xml->asXML());
	}else{
		$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><followers></followers>");
		$cursor = -1;
		while ($cursor!=0) {
			$ids = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$userName,'cursor'=>$cursor));
			$cursor = $ids->next_cursor_str;
			if(!isset($cursor))
			{	echo ("<p>Sry , try again after 15 min...</p>");
				break;
			}
			$ids_arrays = array_chunk($ids->ids, 100);
			foreach($ids_arrays as $implode) {
				$results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
				foreach($results as $profile) {
					$child = $xml->addChild('tweet');
					$child->addChild('name',$profile->name);
					$child->addChild('username',$profile->screen_name);
					$child->addChild('location',$profile->location);
					$child->addChild('date',$profile->created_at);
				}
			}
		}
		print($xml->asXML());
	}
	header('loaction:home.php');
}
?>