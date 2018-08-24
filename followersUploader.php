<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here upload the list of followers in a google drive .
 *
 **/
session_start();
include_once 'lib/google_lib/Google_Client.php';
include_once 'lib/google_lib/contrib/Google_Oauth2Service.php';
require_once 'lib/google_lib/contrib/Google_DriveService.php';

$client = new Google_Client();
$client->setClientId('PUT_GOOGLE_CLIENT_ID');
$client->setClientSecret('PUT_GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('PUT_GOOGLE_REDIRECT_URL');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));


if (isset($_GET['code']) || (isset($_SESSION['access_token']))) {
	
	$service = new Google_DriveService($client);
    if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();		
    } else
        $client->setAccessToken($_SESSION['access_token']);
	
    
   //prepare a csv file for drive
    $fileName=$_SESSION['fileName'];
    $path = 'assets/tmp_data/'.$fileName;
	$file = new Google_DriveFile();
    $file->setTitle($fileName);
    $file->setMimeType('application/vnd.google-apps.spreadsheet');
    $file->setDescription('Uploading the all follower of the user');
	
    $createdFile = $service->files->insert($file, array(
			'data' => file_get_contents($path),
			'mimeType' => 'text/csv',
			'uploadType' => 'multipart',
			'fields' => 'id'));
	unlink($path);
    header('location:https://surajkrsingh.000webhostapp.com/Twitter/home.php?fileName=user');
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}
?>