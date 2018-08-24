<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here prepare a pdf file format for list of followers and tweets in a google drive.
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
	
	include 'lib/fpdf/htmlpdf.php';
	$pdf = new PDF_HTML();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	if($downloadType=='Tweets')
	{
		$pdf->WriteHTML('<br><p align="center">Tweets</p><br><hr>');
		$id = "";
		static  $cnt = 1;
		$tweets = $connection->get("statuses/user_timeline", array("count" => 200,"screen_name"=>$userName));
		
		while (true) {
			if(isset($tweets->errors) && $id == "")
			{
				$pdf->WriteHTML("<p>Sry , try again after 15 min...</p>");
				break;
				
			}else{
				foreach ($tweets as $temp) {
					$pdf->SetFontSize(10);
					$pdf->WriteHTML("<p>{$cnt} .{$temp->user->name} (@{$temp->user->screen_name})   on {$temp->created_at} .<br>{$temp->text} <br><hr><br></p>");
					$cnt = $cnt + 1;
					$id = isset($temp->id_str)?$temp->id_str:null;
				}
				
				if ($id == null) {
					break;
				}	
				$tweets = $connection->get("statuses/user_timeline", ["count" => 200, "max_id" => $id ,"screen_name"=>$userName]);
			}
		}
		
		ob_end_clean();
		$pdf->Output();
	}else{
		$pdf->WriteHTML('<br/><p align="center">Followers</p><br><hr>');
		$pdf->SetFontSize(10);
		$count = 1;
		$cursor = -1;
		while($count != 0)
		{
			$ids = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$userName,'cursor'=>$cursor));
			$cursor = $ids->next_cursor;
			if(!isset($cursor))
			{	$pdf->WriteHTML("<p>Sry , try again after 15 min...</p>");
				break;
			}
			
			$ids_arrays = array_chunk($ids->ids, 100);
			foreach($ids_arrays as $implode) {
				$results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
				foreach($results as $profile) {
					
					$pdf->WriteHTML("<p>{$count} .{$profile->name} (@{$profile->screen_name})   {$profile->location} .<br>{$profile->created_at} <br><hr><br></p>");
					$count++;
				}
			}
		}
		ob_end_clean();
		$pdf->Output();
	}
}
?>