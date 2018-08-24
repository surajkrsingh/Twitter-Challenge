<?php
/**
 * @version 1.0
 * @package  Twitter Challenge
 * @category PHP
 * @author   Suraj kumar Singh <spsrga@gmail.com>
 * @since    24-08-2018
 * @link     https://surajkrsingh.000webhostapp.com/Twitter/
 *
 * Here getting the tweets of given follower and display in slider and return it in to the home page.
 *
 **/
session_start();
include_once("includes/config.php");
include_once("lib/auth/twitteroauth.php");

if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified')
{
    //Retrive session value
    $screen_name = $_REQUEST['screen_name'];
    $oauth_token 		= $_SESSION['request_vars']['oauth_token'];
    $oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
    $my_tweets = $connection->get('statuses/user_timeline', array('screen_name' => $screen_name, 'count' => 10));
  
    if(count($my_tweets) < 1)
    {
        echo "User not tweet yet or Not authorized.";
        exit;
    }
    ?>
<div id="myCarousel" class="carousel slide" data-ride="carousel">

    <!-- Wrapper for slides -->
    <div class="carousel-inner text-center">
        <div class="item active">
            <center>
                <img src="<?php if(isset($my_tweets)) echo $my_tweets[0]->user->profile_image_url;?>" class="img-circle"/>
            </center>
            <p><?php if(isset($my_tweets)) echo $my_tweets[0]->text; ?></p>
            <p><?php if(isset($my_tweets)) echo $my_tweets[0]->created_at; ?></p>
        </div>


        <?php
        foreach ($my_tweets  as $my_tweet) {
            if($my_tweets[0]->id == $my_tweet->id)
                continue;
            ?>
            <div class="item">
                <center>
                    <img src="<?php if(isset($my_tweets)) echo $my_tweet->user->profile_image_url;?>" class="img-rounded"/>

                    <p><?php if(isset($my_tweets)) echo $my_tweet->text; ?></p>

                    <p><?php if(isset($my_tweets)) echo $my_tweet->created_at; ?></p>
            </div>
            </center>
        <?php } ?>
    </div>

    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<?php } ?>