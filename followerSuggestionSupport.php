
<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * This file read the followers from the csv and filter the followers according to the user input
 * and return the list of followers in json format.
 *
 **/
session_start();
$data = file('assets/tmp_data/'.$_SESSION['user'].'_followers.csv');
$input = $_REQUEST['term'] ;
$result = array_filter($data, function ($item) use ($input) {
    if (stripos($item, $input) !== false) {
        return true;
    }
    return false;
});
echo (json_encode($result));
exit;
?>