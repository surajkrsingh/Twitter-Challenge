<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Logout from the site and clear the session history .
 *
 **/
if(array_key_exists('logout',$_GET))
{
    session_start();
    unlink('assets/tmp_data/'.$_SESSION['user'].'_followers.csv');
    unlink('assets/tmp_data/'.$_SESSION['user'].'_nextCursor.txt');
	session_destroy();
	header("Location:index.php");
}
?>