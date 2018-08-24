<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here download the followers and tweets in csv
 *
 **/
session_start();
error_reporting(0);
include_once("includes/config.php");
include_once("lib/auth/twitteroauth.php");
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
	
	header('Content-Type: application/excel');
	header('Content-Disposition: attachment; filename='.$fileName.'.csv');
	$fp = fopen('php://output', 'w');
	
	if($downloadType=='Tweets')
	{	
		$data = array("Name", "Username", "Tweets", "CreatedOn");
		fputcsv($fp, $data);
		$tweets = $connection->get("statuses/user_timeline", ["screen_name"=>$userName,"count" => 1000]);
		$id ="";
		while (true) {
			if(isset($tweets->errors) && $id =="")
			{
				echo "Sry , try again after 15 min...";
				break;
			}else{
				foreach ($tweets as $temp) {	
					$data = array(
						$temp->user->name,
						'@' . $temp->user->screen_name,
						$temp->text,
						$temp->created_at
					);
					fputcsv($fp, $data);	
					 $id = isset($temp->id_str)?$temp->id_str:null;
				}
				if ($id == null) {
					break;
				}
			}
			$tweets = $connection->get("statuses/user_timeline",["screen_name"=>$userName,"count" => 1000, "max_id" => $id]);
					
		}
	}else{

		$data = array("Name", "Username", "Location", "CreatedOn");
		fputcsv($fp, $data);
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
					fputcsv($fp, array(
						$profile->name,
						'@' . $profile->screen_name,
						$profile->location,
						$profile->created_at));
				}
			}
		}
	}	
	fclose($fp);
}
?>