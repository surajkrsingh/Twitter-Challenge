<?php
session_start();
error_reporting(0);
include_once("includes/config.php");
include_once("lib/auth/twitteroauth.php");
include_once('lib/sentimentAnalysis/sentiment_analyser.class.php');

if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified')
{
    //Retrive session value
    $screen_name = $_SESSION['request_vars']['screen_name'];
    $oauth_token 		= $_SESSION['request_vars']['oauth_token'];
    $oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
    $user_info = $connection->get('account/verify_credentials');
    $_SESSION['user']=$user_info->screen_name;
}

if(isset($_REQUEST['uploadDrive']))
{
	if($_REQUEST['type'] == 'Followers')
		header('location:prepareFollowerBundle.php?screen_name='.$_REQUEST['screen_name']);
	else
		header('location:prepareTweetsBundle.php?screen_name='.$_REQUEST['screen_name']);
}

if(isset($_REQUEST['btnDownload']))
{
	$filename = $_REQUEST['file_name'];
	$username = $_REQUEST['user_name'];
	$downloadtype = $_REQUEST['download_type'];
	$fileformat = $_REQUEST['file_format'];
	$arr = array("file_name"=>$filename,"user_name"=>$username,"download_type"=>$downloadtype);
	$_SESSION['DownloadInfo'] = $arr;		

	if($fileformat == 'CSV')
		header('location:getCSV.php?');
	else if($fileformat == 'XML')
		header('location:getXML.php?');
	else if($fileformat == 'PDF')
		header('location:getPDF.php?');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Twitter Dashboard </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/home.css" rel="stylesheet" />
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
        // Get Follower auto suggestion
        $(function() {
            $("#searchBox").autocomplete({
                source: "followerSuggestionSupport.php",
            });
        });
        //get Follower tweets list on slider
        function getAutoSuggest(name)
        {
            $.ajax({
                type: "POST",
                url: "followerTweetsSlider.php",
                data: {'screen_name': name},
                success: function(dataString) {
                    $('#panel_body').html(dataString);
                }
            });
        }
    function closeSelf()
    {
        document.getElementById("close").click();
    }
    </script>
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand"href="home.php">
                    <span><img src="assets/img/twitter-logo.png" width="30" height="30"/></span><span>Twitter</span>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="home.php">Home</a></li>
					<li class="dropdown" data-toggle="modal" data-target="#myFillterModal1">
						<a href="#">Download</span></a>
					</li>					
					<li class="dropdown" data-toggle="modal" data-target="#myFillterModal2">
						<a href="#">Google-spreadsheet</span></a>
					</li>
				</ul>
				 <ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<img src=<?php if(isset($user_info)) echo $user_info->profile_image_url_https; ?> class="img-circle" height="20px" width="20px"/>
						</a>
						
						<ul class="dropdown-menu">
							<li><a href="logout.php?logout"><span class="glyphicon glyphicon-off"> Logout</a></li>
						</ul>
					</li>
					
					<li data-toggle="modal" data-target="#myModal" class="tweet_btn">
						Tweet
					</li>
                </ul>
				 <form class="navbar-form navbar-right" role="search">
				  <div class="form-group ui-widget">
					<input type="text" class="form-control"  placeholder='Search by UserName..' id="searchBox" name="searchBox" 
						onchange='getAutoSuggest(this.value)' />
				</form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 well">
                 <div class="thumbnail backblurimage" style="background:url(<?php if(isset($user_info)) echo $user_info->profile_banner_url; ?>)center;background-size:cover;">
                    <div style="margin-top:10px;color=rgba(0,0,0,0.5);">
						<div class="row noblur">
							<div class="col-sm-3">
								<img src=<?php if(isset($user_info)) echo $user_info->profile_image_url_https; ?> alt="user" class="img-circle" width="60" height="60"/>
							</div>
							<div class="col-sm-9" style="margin-top:10px;color:black;">
								<strong><?php if(isset($user_info)) echo $user_info->name; ?></strong><br>
								<strong><?php if(isset($user_info)) echo '@'.$user_info->screen_name; ?></strong>
							</div>
						</div>
						<p><strong><?php if(isset($user_info)) echo $user_info->location; ?></strong></p>
						<p><?php if(isset($user_info)) echo $user_info->description; ?></p>
					</div>
					
					<div class="row text-center score_count">
						<div class="col-sm-4">
							<p>Tweets</p>
							<h4 class="label"><?php if(isset($user_info)) echo $user_info->statuses_count; ?></h4>
						</div>
						<div class="col-sm-4">
							<p>Following</p>
							<h4 class="label"><?php if(isset($user_info)) echo $user_info->friends_count; ?></h4>

						</div>
						<div class="col-sm-4">
							<p>Follower</p>
							<h4 class="label"><?php if(isset($user_info)) echo $user_info->followers_count; ?></h4>
						</div>
					</div>
                </div>
				<div class="page-header text-center primary">
				  <h4 class="blue">User Timeline</h4>
				</div>
					<?php
						$my_tweets = $connection->get('statuses/user_timeline', array('screen_name' => $screen_name, 'count' => 20));
						//var_dump($my_tweets);
						foreach ($my_tweets  as $my_tweet) {
							echo '<p><span class="list-group-item">'.$my_tweet->text.' <br />-<i>'.$my_tweet->created_at.'</i><span></p>';
						}
					?>
            </div>

			<div class="col-sm-6">
                <div class="row well">
					<?php
					//Tweet uploading confrmation..........
					if(isset($_POST["Postweets"])) 
					{
						$my_update = $connection->post('statuses/update', array('status' => $_POST["Postweets"]));
						if($my_update)
						{
					?>
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Sucessfully</strong> Your tweet is posted !. 
						<a href="https://surajkumarsingh.000webhostapp.com/Twitter/home.php" class="alert-link">Refresh</a>
					</div>
					<?php }$my_update=""; } ?>
					
					<?php 
						//File uploading confrmation..........
						
						if(isset($_GET['fileName']))
						{?>
							
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<strong>Sucessfully</strong> Your file is uploaded on drive !. 
							<a href="https://www.google.com/drive/" class="alert-link" target="_blank">Goto Google drive</a>
						</div>
					<?php			
						}
						if(isset($connection))
						{
							$my_tweets = $connection->get('statuses/home_timeline',array('count' => 10));
							if(count($my_tweets) < 2)
							{
								echo "Please try after 15mins ";
								exit();
							}
						}
					?>
                    <div class="col-sm-12" id="followerTweetsSlider">
                        <div class="panel panel-default text-left">
                            <div class="panel-body" id="panel_body">
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

                            </div>
                        </div>
                    </div>
                </div>
				<?php
				    $sa = new SentimentAnalysis();
                    $sa->initialize();
                    foreach ($my_tweets  as $my_tweet)
                    {
                        $sa->analyse($my_tweet->text);
                        $sentiment = $sa->getSentimentAnalysis();
                    
                        ?>
                <div class="row well">
                    <div class="col-sm-1">
                            <img src="<?php echo $my_tweet->user->profile_image_url;?>" class="img-circle" height="40" width="40">
                    </div>
                    <div class="col-sm-11">
							<p>
								<?php echo "<b>".$my_tweet->user->name."</b>"; ?>
								<span><?php echo "    @".$my_tweet->user->screen_name; ?></span>
							</p>
							 
                            <p><?php echo $my_tweet->text; ?></p>
							<p><?php echo $my_tweet->created_at; ?></p>
							<p style="font-weight: bolder">
                                Sentiment Level : <?php echo strtoupper($sentiment); ?>
                            </p>
                    </div>
					
                </div>

					<?php } ?>

            </div>
            <div class="col-sm-3 well">
                <div style="background-color:white;">
                    <p style="font-weight:bold;" class="text-center blue">Follower</p>
					<hr/>
					<?php if(isset($connection))
					{
						$follower = $connection->get('followers/list',array('count' => 10));
						$counter=0;	
						//print_r($following->users[1]->name);
						while($counter < count($follower->users)) {
						$screen_name = $follower->users[$counter]->screen_name;
							echo '<p>';
                            printf('<a href= "#" onClick="getAutoSuggest(\'%s\');">', $screen_name);
							echo '<span class="list-group-item follower-hover">'.
							'<img src='.$follower->users[$counter]->profile_image_url.' class="img-circle" width="30" height="30"/>'
							."  ".$follower->users[$counter]->name;'</span>
							</a></p>';
							echo '<span class="user_name"> @'.$screen_name;'</span>';
							$counter = $counter + 1;
						}	
					}
					?>
                </div>
				
            </div>
		</div>
    </div>
	
	
    <footer class="container-fluid text-center">
        <p>Developed by Suraj Singh</p>
    </footer>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		<form method="post" action="home.php">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="myModalLabel">Compose new Tweet</h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-1 text-center">
					<img src=<?php if(isset($user_info)) echo $user_info->profile_image_url_https; ?> alt="user" class="img-circle" width="30" height="30"/>
				</div>
				<div class="col-sm-11 text-right">
					<textarea class="form-control ftxt" rows="4" placeholder="What's happening?.." name="Postweets"></textarea>
					<button type="submit" class="btn btn-primary tweet_btn" >Tweet</button>
				</div>
			</div>
		  </div>
		</form>
		</div>
	  </div>
	</div>
	
	<!-- Modal for Download -->
	<div class="modal fade" id="myFillterModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		<form method="post">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="myModalLabel">Download</h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-6">
				<input type="text"  class="form-control ftxt" placeholder="Enter User Name" name="user_name">
				</div>
				<div class="col-sm-6">
					<select class="form-control" name="download_type">
					    <option value="Followers">Followers</option>
					  <option value="Tweets">Tweets</option>
					</select>
				</div>
				<div class="col-sm-6">
					<input type="text"  class="form-control ftxt" placeholder="Enter File Name" name="file_name">
				</div>
				
				<div class="col-sm-6 text-right">
					<select class="form-control" name="file_format">
					  <option value="CSV">CSV</option>
					  <option value="PDF">PDF</option>
					  <option value="XML">XML</option>
					</select>
					<button type="submit" class="btn btn-primary tweet_btn" onclick="closeSelf();" name ="btnDownload">Submit</button>
				</div>					
			</div>
		  </div>
		</form>
		</div>
	  </div>
	</div>
	<!-- Modal  -->
	<div class="modal fade" id="myFillterModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="myModalLabel">Create Google-spreadsheet in Google Drive</h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-6 text-right">
					<form method="get">
					<select class="form-control" name="type" id="type">
					  <option value="Followers">Followers</option>
					  <option value="Tweets">Tweets</option>
					</select>
				</div>
				<div class="col-sm-6 text-right">
					<input type="text"  class="form-control ftxt" placeholder="Enter User Name" name="screen_name" id="screen_name"/>
					<input type="submit" class="btn btn-primary tweet_btn" value="Submit" name="uploadDrive" 
					    onclick="closeSelf();"/>
					</form>
				</div>
			</div>
		  </div>
		</form>
		</div>
	  </div>
	</div>
</body>
</html>
