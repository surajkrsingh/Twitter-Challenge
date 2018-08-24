<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here prepare the download file for followers in CVS.
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
    $last_id_file ='assets/tmp_data/'.$user."_nextCursor_for_follower.txt";
    $count=null;
    if(file_exists($last_id_file))
        $count = file_get_contents($last_id_file);
    else
    {
        $fp = fopen($last_id_file, "w");
        fclose($fp);
    }
    $filename =$user.'_followers.csv';
    $ffp = fopen('assets/tmp_data/'.$filename,"a");
    $count =($count!='')?($count):(-1);

    while($count != 0)
    {
        $ids = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$user,'cursor'=>$count));
        $count = $ids->next_cursor_str;
        if(isset($count) and $count > 0)
            file_put_contents($last_id_file, $count);
        else
            break;

        $ids_arrays = array_chunk($ids->ids, 100);
        foreach($ids_arrays as $implode) {
            $results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
            foreach($results as $profile) {
                fputcsv($ffp,array($profile->screen_name));
            }
        }
    }
    fclose($ffp);
	$_SESSION['fileName']=$filename;
	header("location:followersUploader.php");
}
?>