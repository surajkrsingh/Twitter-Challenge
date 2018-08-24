## Twitter challenge (My Twitter Application)
 Author : Suraj kumar singh
 
 Email  : spsrga176@gmail.com

This twitter challenge contain code that can retrieve user data after authentication by RestApi service of Twitter and also analyse the sentiment of  tweet (like Positive , Negative , Neutral).User can download the tweets and followers list for given user name and also can move that data on google drive too .

#### USED LIBRARIES :

	Bootstrap    			: v3.3.7
	jQuery       			: v1.10.2
	FPDF 	     			: v1.81
	Twitter API  			: v1.1 
	Google API 	 			: v2.0
	Sentiment Analyser 		: v1.0

#### DEMO :

[https://surajkrsingh.000webhostapp.com/Twitter/](https://surajkrsingh.000webhostapp.com/Twitter/)
	
#### QUICK START :

 - Visit https://surajkrsingh.000webhostapp.com/Twitter/
 - Click on login to twitter (This page lead you to twitter, where you have to authorise this application to go further).
 - After authorised with twitter, page will lead you to go home.
 - Can see the User profile information.
 - Display 20 tweets from usert-timeline in the User timeline section.
 - In home your 10 tweets will displayed in slider, and 10 followers will displayed in followers section.
 - There is a Auto search box fro the followers when we type name of any followers that will showup immediatlly.
 - After select of any followers it will display 10 tweets of respective follower from user timeline.
 - Will download all followers and tweets of user .
 - In this application followers and tweets can be downloaded in CSV , PDF , XML formats.
 - It is also possible to take backup of followers and tweets in google drive (Have to authenticate with google ).
 - In home click on any download it will give a option to be download in particular format.
 
#### DIRETORY STRUCTURE :
 
 - Twitter
	- Assets
		- css
		- database
		- img
	- bootstrap
		- css
		- js
		- fonts
	- includes
		- config file
		- Usres file
	- lib
		- auth (Twitter Auth Libraray)
		- fpdf
		- google_lib (Google Auth Libraray)
		- Sentiment Analysis
		
#### LIBRARIES EXPLORE :  
   - ##### Sentiment Analyser 
        - The sentiment analyse libaray is taken from the https://github.com/NickDuncanZA/php-sentiment-analyser and modfied the required funtion according the need of this project.
        - Sentiment analyse is classified the tweet as Positive or Negative or Neutral.