![alt text](https://scrutinizer-ci.com/g/5uraj/Twitter-Challenge/badges/quality-score.png?b=master)
![alt text](https://scrutinizer-ci.com/g/5uraj/Twitter-Challenge/badges/build.png?b=master)
![alt text](https://scrutinizer-ci.com/g/5uraj/Twitter-Challenge/badges/code-intelligence.svg?b=master)
![alt text](https://scrutinizer-ci.com/g/5uraj/Twitter-Challenge/badges/coverage.png?b=master)

## Twitter challenge ([My Twitter Application](https://surajkrsingh.000webhostapp.com/Twitter/))
 >**Author      : Suraj kumar singh**
 
 >**Email       : spsrga176@gmail.com**
 
 >**Follow   :  [Facebook](https://www.facebook.com/SurajSingh176) | [Twitter](https://twitter.com/Suraj_Kr_Singh) | [Linkedin](https://www.linkedin.com/in/suraj-kumar-singh/) | [Github](https://github.com/5uraj/) | [Hackerrank](https://www.hackerrank.com/singh_surajkumar)** 

>#### INTRODUCTION :
This twitter challenge contain code that can retrieve user data after authentication by RestApi service of Twitter and also analyse the sentiment of  tweet (like Positive , Negative , Neutral).User can download the tweets and followers list for given user name and also can move that data on google drive too .

>#### THIRD-PARTY USED LIBRARIES :

	Bootstrap    			: v3.3.7
	jQuery       			: v1.10.2
	FPDF 	     			: v1.81
	Twitter API  			: v1.1 
	Google API 	 		: v2.0
	Sentiment Analyser 		: v1.0

>#### LIVE DEMO :

**[Click here..](https://surajkrsingh.000webhostapp.com/Twitter/)**
	
>#### QUICK START :

 - Visit https://surajkrsingh.000webhostapp.com/Twitter/
 - Click on login to twitter (This page lead you to twitter, where you have to authorise this application to go further).
 - After authorised with twitter, page will lead you to go home.
 - Can see the User profile information.
 - Display 20 tweets from usert-timeline in the User timeline section.
 - In home your 10 tweets will displayed in slider, and 10 followers will displayed in followers section.
 - There is a Auto search box for the followers when we type name of any followers that will showup immediatlly (Upto 75000).
 - After select of any followers it will display 10 tweets of respective follower from user timeline.
 - Will download all followers and tweets of user .
 - In this application followers and tweets can be downloaded in CSV , PDF , XML formats (Upto 3200).
 - It is also possible to take backup of followers (upto 75000) and tweets(upto 3200) in google drive (Have to authenticate with google ).
 - In home click on any download it will give a option to be download in particular format.
 
>#### DIRETORY STRUCTURE :
 
 - Twitter
	- Assets
		- css
		- database
		- img
		- tmp_data
	- bootstrap
		- css
		- js
		- fonts
	- includes
		- config.php
		- Usres.php
	- lib
		- auth (Twitter Auth Libraray)
		- fpdf
		- google_lib (Google Auth Libraray)
		- Sentiment Analysis

>#### HOW TO SETUP | RUN
   - Download [this](https://github.com/5uraj/Twitter-Challenge) github  repo.
   - Create a Twitter developer account  from [here](https://developer.twitter.com/en/apps) and get app authentication details.
   - Change the authenticate info in config.php .
   
            define('CONSUMER_KEY', "PUT_CONSUMER_KEY_OF_YOUR_TWITTER_APP");
	        define('CONSUMER_SECRET', 'PUT_CONSUMER_SECRET_OF_YOUR_TWITTER_APP');
	        define('OAUTH_CALLBACK', 'PUT_CALLBACK_URL_OF_YOUR_TWITTER_APP')
		
   - Create a Google developer account  from [here](https://console.cloud.google.com/apis/)and get drive access authentication details.
   - Set the Google Authentication Info in followersUploader.php and  tweetsUploader.php file
      	
	    	$client = new Google_Client();
	    	$client->setClientId('PUT_GOOGLE_CLIENT_ID');
	    	$client->setClientSecret('PUT_GOOGLE_CLIENT_SECRET');
	    	$client->setRedirectUri('PUT_GOOGLE_REDIRECT_URL');
	    	$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));
	    
   - Import the database and Change the Users.php database configuration.
   
            $dbServer = 'host_name';
            $dbUsername = 'database_username';
            $dbPassword = 'password';
            $dbName = 'database name';
   
   	        
>#### CODE QUALITY RATING :
   - ##### scrutinizer [click here](https://scrutinizer-ci.com/g/5uraj/Twitter-Challenge/inspections/bb8c9ef9-8ce4-49f9-88d1-a4877cb62f70)

>#### ADDTIONAL TASK  :  
   - ##### Sentiment Analyser 
        - The sentiment analyse libaray is taken from the https://github.com/NickDuncanZA/php-sentiment-analyser and modfied the required funtion according the need of this project.
        - Sentiment analyse is classified the tweet as Positive or Negative or Neutral.
	
	
##### Thanks for visit...
