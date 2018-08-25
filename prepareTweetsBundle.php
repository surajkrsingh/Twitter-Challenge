<?php
**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Parepare the tweets csv file to upload on drive.
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
    $user = $_REQUEST['screen_name'];
    $fileName=$user."_tweets.csv";
    $path='assets/tmp_data/'.$fileName;
    $fp = fopen($path, 'a');
    $data = array("Name", "Username", "Tweets", "CreatedOn");
    fputcsv($fp, $data);
    $tweets = $connection->get("statuses/user_timeline", ["screen_name"=>$user,"count" => 3200]);
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
        $tweets = $connection->get("statuses/user_timeline",["screen_name"=>$user,"count" => 3200, "max_id" => $id]);
    }
	fclose($fp);
	$_SESSION['fileName']=$filename;
	header("location:tweetsUploader.php");
}
?>
