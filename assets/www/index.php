<!DOCTYPE html> 
<html> 
	<head> 
	<title>SPEED</title> 
	<meta id="extViewportMeta" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />	

	<!-- Home screen icon  Mathias Bynens mathiasbynens.be/notes/touch-icons -->
	<!-- For iPhone 4 with high-resolution Retina display: -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/splash/logo_buld.png" />
	<!-- For first-generation iPad: -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/splash/logo_buld.png" />
	<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon-precomposed" href="images/splash/logo_buld.png" />
	<!-- For nokia devices: -->
	<link rel="shortcut icon" href="images/splash/logo_buld.png" />


	<link rel="stylesheet" href="css/reset.css" />
	<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.24.custom.css" />
	<link rel="stylesheet" href="css/themes/default/RSVmain.min.css" />
	<link rel="stylesheet" href="css/themes/default/jquery.mobile.structure-1.1.1.min.css" />
	<link rel="stylesheet" href="css/flexslider.css" />
	<link rel="stylesheet" href="css/photoswipe.css" />
	<link rel="stylesheet" href="css/add2home.css" />
	
	
        <link rel="stylesheet/less" href="css/style.css" />

	
	
	 <!-- fonts -->
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600' rel='stylesheet' type='text/css' />
	
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script src="js/jquery-ui-1.8.24.custom.min.js"></script>
	
	<!-- PUSH NOTIFICATION SETUP STARTS HERE -->
	<!--
	
	
	
	<script type="text/javascript" charset="utf-8" src="js/PushNotification.js"></script>
	<script type="text/javascript" charset="utf-8" src="jquery_1.5.2.min.js"></script>
        
        
        <script type="text/javascript">
            var pushNotification;
            
            function onDeviceReady() {
                $("#app-status-ul").append('<li>deviceready event received</li>');
                
				document.addEventListener("backbutton", function(e)
				{
                	$("#app-status-ul").append('<li>backbutton event received</li>');
  					
      				if( $("#home").length > 0)
					{
						// call this to get a new token each time. don't call it to reuse existing token.
						//pushNotification.unregister(successHandler, errorHandler);
						e.preventDefault();
						navigator.app.exitApp();
					}
					else
					{
						navigator.app.backHistory();
					}
				}, false);

				try 
				{ 
                	pushNotification = window.plugins.pushNotification;
                	if (device.platform == 'android' || device.platform == 'Android') {
						$("#app-status-ul").append('<li>registering android</li>');
                    	pushNotification.register(successHandler, errorHandler, {"senderID":"471924084190","ecb":"onNotificationGCM"});		// required!
					} else {
						$("#app-status-ul").append('<li>registering iOS</li>');
                    	pushNotification.register(tokenHandler, errorHandler, {"badge":"true","sound":"true","alert":"true","ecb":"onNotificationAPN"});	// required!
                	}
                }
				catch(err) 
				{ 
					txt="There was an error on this page.\n\n"; 
					txt+="Error description: " + err.message + "\n\n"; 
					alert(txt); 
				} 
            }
            
            // handle APNS notifications for iOS
            function onNotificationAPN(e) {
                if (e.alert) {
                     $("#app-status-ul").append('<li>push-notification: ' + e.alert + '</li>');
                     navigator.notification.alert(e.alert);
                }
                    
                if (e.sound) {
                    var snd = new Media(e.sound);
                    snd.play();
                }
                
                if (e.badge) {
                    pushNotification.setApplicationIconBadgeNumber(successHandler, e.badge);
                }
            }
            
            // handle GCM notifications for Android
            function onNotificationGCM(e) {
                $("#app-status-ul").append('<li>EVENT -> RECEIVED:' + e.event + '</li>');
                
                switch( e.event )
                {
                    case 'registered':
					if ( e.regid.length > 0 )
					{
						$("#app-status-ul").append('<li>REGISTERED -> REGID:' + e.regid + "</li>");
						// Your GCM push server needs to know the regID before it can push to this device
						// here is where you might want to send it the regID for later use.
						console.log("regID = " + e.regID);
					}
                    break;
                    
                    case 'message':
                    	// if this flag is set, this notification happened while we were in the foreground.
                    	// you might want to play a sound to get the user's attention, throw up a dialog, etc.
                    	if (e.foreground)
                    	{
							$("#app-status-ul").append('<li>--INLINE NOTIFICATION--' + '</li>');

							// if the notification contains a soundname, play it.
							var my_media = new Media("/android_asset/www/"+e.soundname);
							my_media.play();
						}
						else
						{	// otherwise we were launched because the user touched a notification in the notification tray.
							if (e.coldstart)
								$("#app-status-ul").append('<li>--COLDSTART NOTIFICATION--' + '</li>');
							else
							$("#app-status-ul").append('<li>--BACKGROUND NOTIFICATION--' + '</li>');
						}

						$("#app-status-ul").append('<li>MESSAGE -> MSG: ' + e.payload.message + '</li>');
						$("#app-status-ul").append('<li>MESSAGE -> MSGCNT: ' + e.payload.msgcnt + '</li>');
                    break;
                    
                    case 'error':
						$("#app-status-ul").append('<li>ERROR -> MSG:' + e.msg + '</li>');
                    break;
                    
                    default:
						$("#app-status-ul").append('<li>EVENT -> Unknown, an event was received and we do not know what it is</li>');
                    break;
                }
            }
            
            function tokenHandler (result) {
                $("#app-status-ul").append('<li>token: '+ result +'</li>');
                // Your iOS push server needs to know the token before it can push to this device
                // here is where you might want to send it the token for later use.
            }

            function successHandler (result) {
                $("#app-status-ul").append('<li>success:'+ result +'</li>');
            }
            
            function errorHandler (error) {
                $("#app-status-ul").append('<li>error:'+ error +'</li>');
            }
            
			document.addEventListener('deviceready', onDeviceReady, true);

         </script>
	
		<div id="home">
   		 <div id="app-status-div">
       		<ul id="app-status-ul">
            	<li>PushNotifications</li>
        	</ul>
    	</div>
		</div>
	
	-->
	
	<!-- PUSH NOTIFICATION SETUP ENDS HERE -->
	
	
	
	 
	
	
	<script type="text/javascript" src="js/jquery.mobile-1.1.1.min.js"></script>
        <script type="text/javascript" src="js/less-1.3.0.min.js"></script>
        <!--<script type="text/javascript" src="js/jquery-ui-effects.js"></script>-->

	<script src="js/helper.js"></script>
	<script src="js/jquery.flexslider-min.js"></script>
	<script src="js/iphone-style-checkboxes.js"></script>
	<script src="js/klass.min.js"></script>
	<script src="js/code.photoswipe.jquery-3.0.5.min.js"></script>
	<script src="js/add2home.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
        
	<script type="text/javascript" src="js/app.js?v=30"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>
	 
  	 <div id="splash"><img src="images/splash/logo_buld.png" alt="splash title" width="653" height="178" id="splash-title" /></div> 
	 
<!-- end splash screen --><!--<div data-dom-cache="false" data-role="page" class="pages" id="home" data-theme="a" style="background: url(images/content/eiffel.jpg) !important; background-size: auto 100% !important;" >-->
<div data-dom-cache="false" data-role="page" class="pages" id="home" data-theme="a">
	        <div class="white-overlay"></div>
        <div data-role="header">
            
        <div class="left website-title">
                <p class="first">SPEED</p>
                <p class="second">MOB APPLICATION</p>
</div>
            
            <div class="left top-menu-button menu-trigger" defaulttext="+" othertext="x">
                <span class="left square-button">+ Menu</span>
                <!--<span class="left button-label">menu</span>-->
                <div class="clear"></div>
            </div>
            
            <div class="right page-title only-tablet"> <!-- This will only be visible on tablets and other larger displays - not on mobile phones-->
                <p>...</p>
            </div>
            
            <!--
            If you wish to have back-button on the header, uncomment below code:
            <div class="left top-back-button back-trigger">
                <span class="left square-button">&laquo;</span>
                <div class="clear"></div>
            </div>
            -->
            
            <div class="clear"></div>
	</div>
        <div class="main-menu">
            <a href="index.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">home</span>
                <div class="clear"></div>
            </a>
            
            <a href="company-profile.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">profile</span>
                <div class="clear"></div>
            </a>
            
			<a href="9thgsf.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">9th GSF</span>
                <div class="clear"></div>
            </a>
            
             <a href="associations.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">associations</span>
                <div class="clear"></div>
            </a> 
                   
            <a href="sponsors.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">sponsors</span>
                <div class="clear"></div>
            </a>
            
            <a href="testimonials.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">testimonials</span>
                <div class="clear"></div>
            </a>
            
            <a href="bylaws.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">bylaws</span>
                <div class="clear"></div>
            </a>
            
            <a href="weef.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">weef</span>
                <div class="clear"></div>
            </a>
            
            <a href="video-channel.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">videos</span>
                <div class="clear"></div>
            </a>
                                  
            <a href="contact-us.php" class="menu-button">
                <span class="left square-button">+</span>
                <span class="left button-label">contact</span>
                <div class="clear"></div>
            </a>
            
        </div>        <!-- /header -->
    
	<div data-role="content" data-theme="a" class="minus-shadow" disable-back-button="true">
		<div class="white-content-box">
			<script>
				$(document).ready(function() {
					$(".page-title").text("Home");
				});
			</script>
			<div class="cherry-slider only-tablet" style="height: 150px;">
            	<div anim="blind" anim-speed="700" anim-direction="left" anim-position-left="0" anim-position-top="20" class="anim-item"><p class="little-padding aa">STUDENT'S</p></div>
                <div anim="blind" anim-speed="700" anim-direction="left" anim-position-left="0" anim-position-top="40" class="anim-item"><p class="little-padding aa">PLATFORM FOR</p></div>
                <div anim="drop" anim-speed="700" anim-direction="down" anim-position-left="0" anim-position-top="68" class="anim-item"><p class="little-padding white-bg gray-border">ENGINEERING EDUCATION</p></div>
                <div anim="drop" anim-speed="700" anim-direction="down" anim-position-left="0" anim-position-top="90" class="anim-item"><p class="little-padding white-bg gray-border">DEVELOPMENT</p></div>
    <div anim="drop" anim-speed="500" anim-direction="down" anim-position-right="0" anim-position-top="20" class="anim-item">  <img src="images/logo_buld.png" width="180" height="90" /></div>
				
				<div anim="fade" anim-speed="3200" class="anim-item wait-item"></div>
				<div anim-action="break" anim="fade" anim-speed="700" class="anim-item"></div>
				<div anim="fade" anim-speed="700" class="anim-item wait-item"></div>
				
<div anim="blind" anim-speed="700" anim-direction="left" anim-position-left="0" anim-position-top="20" class="anim-item"><p class="little-padding aa">STUDENT'S</p></div>
                <div anim="blind" anim-speed="700" anim-direction="left" anim-position-left="0" anim-position-top="40" class="anim-item"><p class="little-padding aa">PLATFORM FOR</p></div>
                <div anim="drop" anim-speed="700" anim-direction="down" anim-position-left="0" anim-position-top="68" class="anim-item"><p class="little-padding white-bg gray-border">ENGINEERING EDUCATION</p></div>
                <div anim="drop" anim-speed="700" anim-direction="down" anim-position-left="0" anim-position-top="90" class="anim-item"><p class="little-padding white-bg gray-border">DEVELOPMENT</p></div>
    <div anim="drop" anim-speed="500" anim-direction="down" anim-position-right="0" anim-position-top="20" class="anim-item">  <img src="images/logo_buld.png" width="180" height="90" /></div>
				
  <div anim="fade" anim-speed="2000" class="anim-item wait-item"></div>
				<div anim-action="break" anim="fade" anim-speed="700" class="anim-item"></div>
				<div anim-action="restart" anim="fade" anim-speed="700" class="anim-item wait-item"></div>
			</div>
			<br>Sponsored by<br><img src="images/splash/sponsors.png" width="162" height="129"  /><br><h2 class="only-tablet" align="justify">Welcome to SPEED - Student's Platform for Engineering Education Development. </h2>
			<p class="large-text only-tablet" align="justify">SPEED is a global, non-profit student organization that functions as an interdisciplinary network of engineering students, who aspire to provide opinion and create an impact on future development of engineering education and its effect on society and environment. </p>
  <p class="large-text" align="justify">SPEED was founded by student participants of the 1st Global Student Forum (GSF) in October 2006 during the 5th Global Colloquium on Engineering Education (GCEE), organised by the American Society for Engineering Education (ASEE), in Rio de Janeiro, Brazil. The following years, SPEED co-organised the 2nd and the 3rd GSF with ASEE in Istanbul, Turkey and Cape Town, South Africa, and in cooperation with IFEES the 4th GSF in Bhubaneswar, India.  
  <p class="large-text" align="justify">SPEED hopes to expand the Global Student Forum initiative significantly in the coming years through global cooperation and feedback from students and through other activities to further enrich engineering education curricula worldwide. </p>
			
		  <p class="large-text only-smartphones">Click to see the same page on tablet/desktop: <a href="index.php" target="_blank">Tablet view</a></p>
		</div>
	</div>
        
        <div class="clear"></div>
<div data-role="footer">
    <div class="footer-actions">
        <!--<a href="tel://971568899009" class="square-button">#</a>-->
        <a href="https://www.facebook.com/pages/SPEED-Student-Platform-for-Engineering-Education-Development/166731499492" class="square-button">f</a>
        <!--<a href="http://www.worldspeed.org/portal" class="square-button">w</a>-->
        <a class="square-button right back-trigger">BACK &laquo;</a>
        <div class="clear"></div>
    </div>
    <p class="right" />
    <div class="clear"></div>
</div></div><!-- /page -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34399779-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>