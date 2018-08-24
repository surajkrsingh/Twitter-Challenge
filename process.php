<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here authenticate with twitter and get token .
 * Set the followers list of logged-in user in csv file
 *
 **/
session_start();
include_once("includes/config.php");
include_once("lib/auth/twitteroauth.php");
include_once("includes/Users.php");
if(isset($_REQUEST['oauth_token']) && $_SESSION['token']  !== $_REQUEST['oauth_token']) {
	//If token is old, distroy session and redirect user to index.php
	session_destroy();
	header("Location:index.php");
}
elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {

	//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($connection->http_code == '200')
	{
		//Redirect user to twitter
		$_SESSION['status'] = 'verified';
		$_SESSION['request_vars'] = $access_token;
		
		//Insert user into the database
		$user_info = $connection->get('account/verify_credentials'); 	
		$name = explode(" ",$user_info->name);
		$fname = isset($name[0])?$name[0]:'';
		$lname = isset($name[1])?$name[1]:'';
		$db_user = new Users();
		$db_user->checkUser('twitter',$user_info->id,$user_info->screen_name,$fname,$lname,$user_info->lang,$access_token['oauth_token'],$access_token['oauth_token_secret'],$user_info->profile_image_url);
		
		//Get followers list in file for autosearch
        $user_name = $user_info->screen_name;
        $last_id_file ='assets/tmp_data/'.$user_name."_nextCursor.txt";
        $count=null;
        if(file_exists($last_id_file))
            $count = file_get_contents($last_id_file);
        else
        {
            $fp = fopen($last_id_file, "w");
            fclose($fp);
        }
        $fileName ='assets/tmp_data/'.$user_name.'_followers.csv';
        $ffp = fopen($fileName,"a");
        $count =($count!='')?($count):(-1);
        while($count != 0)
        {
            $ids = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$user_name,'cursor'=>$count));
            //var_dump($ids['error']);
            if(isset($ids->error))
            {
               header('location:index.php?msg=Error');
            }
           
            $count = $ids->next_cursor_str;
            $ids_arrays = array_chunk($ids->ids, 100);
            foreach($ids_arrays as $implode) {
                $results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
                foreach ($results as $profile) {
                    fputcsv($ffp, array($profile->screen_name));
                }
            }
            if(isset($count) || $count > 0)
                file_put_contents($last_id_file, $count);
            else
                break;
        }
        fclose($ffp);
        
		
		//Unset no longer needed request tokens
		unset($_SESSION['token']);
		unset($_SESSION['token_secret']);
		header('Location: home.php');
		
	}else{
		die("error, try again later!");
	}
		
}else{

	if(isset($_GET["denied"]))
	{
		header('Location: index.php');
		die();
	}

	//Fresh authentication
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	
	//Received token info from twitter
	$_SESSION['token'] 			= $request_token['oauth_token'];
	$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
	
	//Any value other than 200 is failure, so continue only if http code is 200
	if($connection->http_code == '200')
	{
		//redirect user to twitter
		$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
		header('Location: ' . $twitter_url); 
	}else{
		die("error connecting to twitter! try again later!");
	}
}
?>
