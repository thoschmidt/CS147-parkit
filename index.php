<?php
require 'functions.php';
require 'src/base_facebook.php';
require 'src/facebook.php';
$facebook = new Facebook(array(
            'appId' => $config["fb_app_id"],
            'secret' => $config["fb_app_secret"],
            'cookie' => true,
        ));

$congratulations = FALSE;
?>

<html>
	<head>
		<script src="//cdn.optimizely.com/js/16234694.js"></script>
		<script type="text/javascript">

  			var _gaq = _gaq || [];
  			_gaq.push(['_setAccount', 'UA-27438892-1']);
  			_gaq.push(['_trackPageview']);

  			(function() {
   			 var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
   			 ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   			 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  		})();
		</script>
    	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0";>
    		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.css" />
    		<link rel="stylesheet" href="style.css"/>
    		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.3.min.js"></script>
    		<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
    		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    		<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"</script> -->
    		<script type="text/javascript" src="jquery-ui-map/ui/jquery.ui.map.js"></script>
    		<script type="text/javascript" src="//connect.facebook.net/en_US/all.js"></script>
    		<script type="text/javascript" src="stopwatch.js"></script>
    		<script type="text/javascript" src="date.format.js"></script>
    </head>

	<body>
		<div id="fb-root"></div>
		<script>
			var fb_id, fb_email, fb_name;

			$(document).bind("mobileinit", function(){	  $.mobile.touchOverflowEnabled = true;	});

			//$.mobile.fixedToolbars.setTouchToggleEnabled(true);
			//$.mobile.fixedToolbars.show();

			(function() {
    			var e = document.createElement('script'); e.async = true;
        		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        		document.getElementById('fb-root').appendChild(e);
        	}());

			window.fbAsyncInit = function() {
				FB.init({
				  appId      : '185548844861355',
				  channelURL : 'http://parkit.cs147.org/parkit/channel.html', // Channel File
				  status     : true, // check login status
				  cookie     : true, // enable cookies to allow the server to access the session
				  oauth      : true, // enable OAuth 2.0
				  xfbml      : true  // parse XFBML
				});

				FB.Event.subscribe('auth.login', function() {
						window.location.reload();
				});

				FB.Event.subscribe('auth.statusChange', handleStatusChange);
				FB.getLoginStatus(handleStatusChange);
			};

			function handleStatusChange(response) {
				var oldClassName = document.body.className;
     			document.body.className = response.authResponse ? 'connected' : 'not_connected';

     			if (response.authResponse) {
       				updateUserInfo(response);
       				FB.api('/me', function(apiResponse) {
       					fb_id = apiResponse.id;
       					fb_email = apiResponse.email;
       					fb_name = apiResponse.name;
       					$.get('users.php?function=get&userID=' + fb_id, function(data){
       						if(data.length == 0)
       						{
       							window.location.href = "#newRegistration";
       						}
       					});
       					
  						/*var xmlHttp = new XMLHttpRequest();
       					var postString = "register.php?fb_id="+apiResponse.id+"&fb_name="+apiResponse.name+"&fb_email="+apiResponse.email;
       					xmlHttp.open("GET", postString, true);
       					xmlHttp.send();*/
					});
       			} else if(oldClassName == 'connected')
       			{
       				window.location.href = "#home";
       			}
     		}

			function loginUser() {
				  FB.login(function(response) { }, {scope:'email'});
			}

   			function updateUserInfo(response) {
      			FB.api('/me', function(response) {
        			document.getElementById('user-info').innerHTML = '<img src="https://graph.facebook.com/' + response.id + '/picture">';
     			});
    		}

    		$('#sell4').live('pageshow', function(event) {
				var address = document.getElementById("address").value + ", " + document.getElementById("city").value + ", " + document.getElementById("state").value + ", " + document.getElementById("zip").value;
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var coordinates = results[0].geometry.location;
						var lat = coordinates.lat();
						var lng = coordinates.lng();
						var price = document.getElementById("price").value;
						var sellStartDateTime = document.getElementById("sellStartDateTime").value;
						var sellEndDateTime = document.getElementById("sellEndDateTime").value;

						var xmlHttp = new XMLHttpRequest();
						var postString = "spots.php?function=put&sellerID=" + fb_id + "&address=" + address + "&lat=" + lat + "&lng=" + lng + "&price=" + price + "&startAvailableTime= " + sellStartDateTime + "&endAvailableTime=" + sellEndDateTime;
						xmlHttp.open("GET", postString, true);
						xmlHttp.send();
						xmlHttp.onreadystatechange= function()
						{

						}
					}
				});
			});
		</script>

		<div data-role="page" id="home">
			<div class="show_when_not_connected">
					<a href="javascript:loginUser()">
						<img src="facebook-login-button.png" style="margin-top:38%; margin-left:47%">
					</a>
					<a href="#whatis" class ="whatIsButton" data-role="button" data-direction="forward" style="margin-top:5%">What is ParkIt?</a>
			</div>
   			<div class="show_when_connected">
    			<a href="#buy2" class="splashPageButton" data-role="button" data-direction="forward" style="margin-top:50%">Buy</a>
				<a href="#sell1" class="splashPageButton" data-role="button" data-direction="forward">Sell</a>
				<a href="#account" class="splashPageButton" data-role="button" data-direction="forward">Profile</a>
  			</div>
    	</div>
    	
    	<div data-role="page" id="newRegistration" class="page">
    		<div data-role="header">
    			<h1>Registration</h1>
    		</div>
    		<div data-role="content">
    			<h3 align="left">Enter your number and provider to receive text alerts</h3>
    			<div data-role="fieldcontain">
					<div class="address_label"><label for="registrationCellNumber">Cell Number </label></div>
					<div class="address_input"><input type="tel" name="registrationCellNumber" id="registrationCellNumber" value="" /></div>
				</div>
				<div data-role="fieldcontain">
					<div class="address_label"><label for="registrationCellProvider">Cell Provider </label></div>
					<div class="state_selector">
						<select name="registrationCellProvider" id="registrationCellProvider">
						  <option value="ATT">AT&T</option>
						  <option value="Verizon">Verizon</option>
						  <option value="Sprint">Sprint</option>
						  <option value="T-Mobile">T-Mobile</option>
						</select>
					</div>
				</div>
				<a href="javascript:submitRegistration()">
				  <img src="submit.png"/>
				</a>
				<script>
					var cellNumber = 0, cellProvider = 0;
					function submitRegistration()
					{
						cellNumber = $('#registrationCellNumber').val();
						cellProvider = $('#registrationCellProvider').val();

						var xmlHttp = new XMLHttpRequest();
       					var postString = "register.php?fb_id="+ fb_id +"&fb_name="+ fb_name +"&fb_email="+ fb_email +"&cellNumber="+cellNumber+"&cellProvider="+cellProvider;
       					console.log(postString);
       					xmlHttp.open("GET", postString, true);
       					xmlHttp.send();

       					window.location.href = "#home";
					}
				</script>
			</div>
    	</div>

		<div data-role="page" id="whatis" class="page">
			<div data-role="header">
				<a data-rel="back" data-direction="reverse">Home</a>
				<h1>What is ParkIt?</h1>
			</div>
			<div data-role="content">
				<h2>Finding parking and making money has never been easier.</h2>
				<p>Have a 7:30 dinner reservation in San Francisco? Search available driveways in residences near the restaurant.</p>
				<p>Going away for the weekend? Turn your driveway into a parking spot until Sunday night.</p>
				<p>Log in with your Facebook account to start today!</p>
					<a href="javascript:loginUser()">
						<img src="facebook-login-button.png" style="margin-top:0%; margin-left:%">
					</a>
			</div>
		</div>

		<div data-role="page" id="account" class="page">
			<script>
			var purchasedSpots = new Array(), soldSpots = new Array();
			$('#account').bind('pagecreate', function(event, data){
						$.get('purchases.php?function=getBuyerSpots&buyerID=' + fb_id, function(data){
							var curDateTime = new Date();
							for(var i = 0; i < data.length; i++)
							{
								var startPurchaseTime = convertHtmlDateToJSDate(data[i].startPurchaseTime);
								var endPurchaseTime = convertHtmlDateToJSDate(data[i].endPurchaseTime);
								var startAvailableTime = convertHtmlDateToJSDate(data[i].startAvailableTime);
								var endAvailableTime = convertHtmlDateToJSDate(data[i].endAvailableTime);

								// Convert to PST time
								startPurchaseTime = new Date(startPurchaseTime.getTime() - (1000 * 60 * startPurchaseTime.getTimezoneOffset()));
								endPurchaseTime = new Date(endPurchaseTime.getTime() - (1000 * 60 * endPurchaseTime.getTimezoneOffset()));
								startAvailableTime = new Date(startAvailableTime.getTime() - (1000 * 60 * startAvailableTime.getTimezoneOffset()));
								endAvailableTime = new Date(endAvailableTime.getTime() - (1000 * 60 * endAvailableTime.getTimezoneOffset()));

								data[i].startPurchaseTime = startPurchaseTime;
								data[i].endPurchaseTime = endPurchaseTime;
								data[i].startAvailableTime = startAvailableTime;
								data[i].endAvailableTime = endAvailableTime;

								purchasedSpots[data[i].purchaseID] = data[i];
							}

							var curTime = new Date();

							for(purchase in purchasedSpots)
							{
								if(purchasedSpots[purchase].startPurchaseTime < curTime && curTime < purchasedSpots[purchase].endPurchaseTime)
								{
									var startPurchaseTimeString = formatTime(purchasedSpots[purchase].startPurchaseTime);
									var endPurchaseTimeString = formatTime(purchasedSpots[purchase].endPurchaseTime);

									var newPurchaseItem = $("<li><a href='javascript:purchaseDetailPage(" + purchasedSpots[purchase].purchaseID + ")'><h3>" + purchasedSpots[purchase].address + "</h3><p><strong>" + startPurchaseTimeString + " - " + endPurchaseTimeString + "</strong></p></a></li>");////<ul><li>From " + startPurchaseTimeString + "</li><li>To " + endPurchaseTimeString + "</li></ul></a>");
									$('#activePurchases').append(newPurchaseItem);
								}
							}
						});

						$.get('purchases.php?function=getSellerSpots&sellerID=' + fb_id, function(data){
							var curDateTime = new Date();
							for(var i = 0; i < data.length; i++)
							{
								var startAvailableTime = convertHtmlDateToJSDate(data[i].startAvailableTime);
								var endAvailableTime = convertHtmlDateToJSDate(data[i].endAvailableTime);

								// Convert to PST time
								startAvailableTime = new Date(startAvailableTime.getTime() - (1000 * 60 * startAvailableTime.getTimezoneOffset()));
								endAvailableTime = new Date(endAvailableTime.getTime() - (1000 * 60 * endAvailableTime.getTimezoneOffset()));

								data[i].startAvailableTime = startAvailableTime;
								data[i].endAvailableTime = endAvailableTime;

								soldSpots[data[i].spotID] = data[i];
							}

							for (spot in soldSpots)
							{
								var startAvailableTimeString = formatTime(soldSpots[spot].startAvailableTime);
								var endAvailableTimeString = formatTime(soldSpots[spot].endAvailableTime);

								var newSoldSpot = $("<li><a href='javascript:soldDetailPage(" + soldSpots[spot].spotID + ")'><h3>" + soldSpots[spot].address + "</h3><p><strong>" + startAvailableTimeString + " - " + endAvailableTimeString + "</strong></p></a></li>");////<ul><li>From " + startPurchaseTimeString + "</li><li>To " + endPurchaseTimeString + "</li></ul></a>");
								$('#activeSoldSpots').append(newSoldSpot);
							}
						});
						
						var userData;
						$.get('users.php?function=get&userID=' + fb_id, function(data){
							userData = data[0];
							document.getElementById('accountName').innerHTML = "<br/><h2>" + userData.fb_name + "</h2>";
							document.getElementById('accountEmail').innerHTML = "<h3>" + userData.fb_email + "</h3>";
							//document.getElementById('accountPhone').innerHTML = "<h3>" + userData.fb_name + "</h3>";
							//document.getElementById('accountPhoneNetwork').innerHTML = "<h3>" + userData.fb_name + "</h3>";
							
						});
					});
				</script>
			<div data-role="header">
				<a href="#home" data-direction="reverse">Home</a>
				<h1>Account details</h1>
					<div data-role="navbar" class="ui-navbar" role="navigation">
						<ul>
							<li><a href="#account">Account</a></li>
							<li><a href="#spotsPurchased">Purchased</a></li>
							<li><a href="#spotsSold">Sold</a></li>
						</ul>
					</div><!-- /navbar -->
			</div>
		  	<div data-role="content">
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<div id="accountName" style="display:inline;"></div>
					</div>
					<div class="ui-block-b">
						<div id="user-info" style="margin-top:10%;" ></div>
						<a href="javascript:FB.logout()" >
							<img src="facebookLogOutButton.png"/>
						</a>
					</div>
				</div>
				</br>
				<div data-role="collapsible" data-theme="a" data-content-theme="a">
				   <h3>Email</h3>
				   <div id="accountEmail"></div>
				</div>
				<div data-role="collapsible" data-theme="a" data-content-theme="a">
				   <h3>Phone</h3>
					<div id="accountPhone"></div>
					<div id="accountPhoneNetwork"></div>
				</div>
				</br>
			</div>
		</div>

		<div data-role="page" id="spotsPurchased" class="page">
			<script>
				function getMonth(monthNumber)
				{
					switch(monthNumber)
					{
						case 0: return "Jan";
						case 1: return "Feb";
						case 2: return "Mar";
						case 3: return "Apr";
						case 4: return "May";
						case 5: return "Jun";
						case 6: return "Jul";
						case 7: return "Aug";
						case 8: return "Sep";
						case 9: return "Oct";
						case 10: return "Nov";
						case 11: return "Dec";
					}
				}

				function pad(num)
				{
					if(num < 10)
						return "0" + num;
					else
						return num;
				}

				function formatTime(time)
				{
					var timeString = getMonth(time.getMonth()) + " " + time.getDate();
					if(time.getHours() > 12)
					{
						timeString = timeString + " " + (time.getHours() - 12) + ":" + pad(time.getMinutes()) + " PM";
					}
					else
					{
						timeString = timeString + " " + time.getHours() + ":" + pad(time.getMinutes()) + " AM";
					}

					return timeString;
				}

				var purchaseDetailMapHasLoaded = new Boolean();

				var curPurchase;
				function purchaseDetailPage(purchaseID)
				{
					curPurchase = purchasedSpots[purchaseID];

					if(!purchaseDetailMapHasLoaded.valueOf())
					{
						$('#purchaseDetailMap').gmap().bind('init', function(event, data){
							$('#purchaseDetailMap').gmap('addMarker', { 'position': new google.maps.LatLng(curPurchase.lat, curPurchase.lng), 'bounds': true });
							$('#purchaseDetailMap').gmap({'center': new google.maps.LatLng(parseFloat(curPurchase.lat) + .005, parseFloat(curPurchase.lng) - .007)});
							$('#purchaseDetailMap').gmap({'zoom':15});
							purchaseDetailMapHasLoaded = true;
						});
					}
					else
					{
						$('#purchaseDetailMap').gmap('clear', 'markers');
						$('#purchaseDetailMap').gmap('addMarker', { 'position': new google.maps.LatLng(curPurchase.lat, curPurchase.lng), 'bounds': true });
						$('#purchaseDetailMap').gmap({'center': new google.maps.LatLng(parseFloat(curPurchase.lat) + .005, parseFloat(curPurchase.lng) - .007)});
						$('#purchaseDetailMap').gmap({'zoom':15});
					}

					document.getElementById('purchaseDetailAddress').childNodes[0].nodeValue = curPurchase.address;
					document.getElementById('purchaseDetailPrice').childNodes[0].nodeValue = "$" + parseFloat(curPurchase.price).toFixed(2) + " / hour";
					document.getElementById('purchaseDetailTime').childNodes[0].nodeValue = formatTime(curPurchase.startPurchaseTime) + " - " + formatTime(curPurchase.endPurchaseTime);

					document.getElementById('purchaseDetailEndAvailableTime').childNodes[0].nodeValue += formatTime(curPurchase.endAvailableTime);
					document.getElementById('purchaseDetailEndPurchaseTime').childNodes[0].nodeValue += formatTime(curPurchase.endPurchaseTime);

					$('#purchaseDetailEndPurchaseTime').val(dateFormat(curPurchase.endPurchaseTime, 'yyyy-mm-dd"T"hh:MM:ssoD'));
					console.log(dateFormat(curPurchase.endPurchaseTime, 'yyyy-mm-dd"T"hh:MM:ssoD'));
					window.location.href = "#purchaseDetailPage";
				}

				var soldDetailMapHasLoaded = new Boolean();
				var curSpot;
				function soldDetailPage(spotID)
				{
					curSpot = soldSpots[spotID];

					if(!soldDetailMapHasLoaded.valueOf())
					{
						$('#soldDetailMap').gmap().bind('init', function(event, data){
							$('#soldDetailMap').gmap('addMarker', { 'position': new google.maps.LatLng(curSpot.lat, curSpot.lng), 'bounds': true });
							var myLatLng = new google.maps.LatLng(parseFloat(curSpot.lat) + .005, parseFloat(curSpot.lng) - .007);
							$('#soldDetailMap').gmap({'center': myLatLng});
							$('#soldDetailMap').gmap({'zoom':15});
							soldDetailMapHasLoaded = true;
						});
					}
					else
					{
						$('#soldDetailMap').gmap('clear', 'markers');
						$('#soldDetailMap').gmap('addMarker', { 'position': new google.maps.LatLng(curSpot.lat, curSpot.lng), 'bounds': true });
						var myLatLng = new google.maps.LatLng(parseFloat(curSpot.lat) + .005 , parseFloat(curSpot.lng) - .007);
						$('#soldDetailMap').gmap({'center': myLatLng});
						$('#soldDetailMap').gmap({'zoom':15});
					}

					document.getElementById('soldDetailAddress').childNodes[0].nodeValue = curSpot.address;
					document.getElementById('soldDetailPrice').childNodes[0].nodeValue = "$" + parseFloat(curSpot.price).toFixed(2) + " / hour";
					document.getElementById('soldDetailTime').childNodes[0].nodeValue = formatTime(curSpot.startAvailableTime) + " - " + formatTime(curSpot.endAvailableTime);

					/*document.getElementById('soldDetailStartAvailableLabel').childNodes[0].nodeValue = "From " + formatTime(curSpot.startAvailableTime);
					document.getElementById('soldDetailEndAvailableLabel').childNodes[0].nodeValue = "To " + formatTime(curSpot.endAvailableTime);*/

					window.location.href = "#soldDetailPage";
				}

				function convertHtmlDateToJSDate(date)
				{
					if(navigator.vendor == "Apple Computer, Inc.")
					{
						var dateArray = date.split(/[^0-9]/);
						return new Date(dateArray[0], dateArray[1] - 1, dateArray[2], dateArray[3], dateArray[4], dateArray[5]);
					}
					else
					{
						return new Date(date);
					}
				}

			</script>
			<div data-role="header">
				<a href="#home" data-direction="reverse">Home</a>
				<h1>Spots Purchased</h1>
					<div data-role="navbar" class="ui-navbar" role="navigation">
						<ul>
							<li><a href="#account" class="tab">Account</a></li>
							<li><a href="#spotsPurchased" class="tab">Purchased</a></li>
							<li><a href="#spotsSold" class="tab">Sold</a></li>
						</ul>
					</div><!-- /navbar -->
			</div>
			<div data-role="content">
				<h2>Active Spots</h2>
				<ul data-role="listview" id="activePurchases">
				</ul>
			</div>
		</div>

		<div data-role="page" id="spotsSold" class="page">
			<div data-role="header">
				<a href="#home" data-direction="reverse">Home</a>
				<h1>Spots Sold</h1>
					<div data-role="navbar" class="ui-navbar" role="navigation">
						<ul>
							<li><a href="#account" class="tab">Account</a></li>
							<li><a href="#spotsPurchased" class="tab">Purchased</a></li>
							<li><a href="#spotsSold" class="tab">Sold</a></li>
						</ul>
					</div><!-- /navbar -->
			</div>
			<div data-role="content">
				<h2>Active Spots</h2>
				<ul data-role="listview" id="activeSoldSpots">
				</ul>
			</div>
		</div>

		<div data-role="page" id="soldDetailPage" class="page">
			<div data-role="header">
				<a href="#spotsSold" data-direction="reverse">Sold</a>
				<h1>Spot Details</h1>
			</div>
			<div data-role="content">
				<div data-role="collapsible">
					<h3>Details</h3>
					<h3>Address</h3>
					<p id="soldDetailAddress">123 Ipsum Street</p>
					<h3>Price</h3>
					<p id="soldDetailPrice">$3 / hr</p>
					<h3>Available From</h3>
					<p id ="soldDetailTime">TempTime</p>
				</div>
				<div data-role="collapsible">
					<h3>Map</h3>
					<div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
							<div id="soldDetailMap" style="height:275px;"></div>
					</div>
				</div>
				<!--<div data-role="collapsible">
					<h3>Add Time</h3>
					<script>
							document.getElementById('confirmAdditionalPrice').childNodes[0].nodeValue += "$" + finalSale.toFixed(2);
							document.getElementById('confirmNewEndTimeDifference').childNodes[0].nodeValue += minutes.toFixed(0) + " minutes";
							document.getElementById('confirmNewEndTime').childNodes[0].nodeValue += formatTime(newEndPurchaseTime);
							var url = 'purchases.php?function=update&newEndPurchaseTime=' + $('#purchaseDetailNewEndPurchaseTime').val() + '&purchaseID=' + curPurchase.purchaseID;
							$.post(url);
					</script>
					<h3 id="soldDetailEndAvailableTime">Spot available</h3>
					<div class="ui-grid-a">
						<div class="ui-block-a">
							<h3 id="soldDetailStartAvailableLabel">From </h3>
							<div data-role="fieldcontain">
								<input type="datetime" name="soldDetailNewStartAvailableTime" id="purchaseDetailNewEndPurchaseTime" value="" />
							</div>
						</div>
						<div class="ui-block-b">
							<h3 id="soldDetailEndAvailableLabel">To </h3>
							<div data-role="fieldcontain">
								<input type="datetime" name="soldDetailNewEndAvailableTime" id="purchaseDetailNewEndPurchaseTime" value="" />
							</div>
						</div>
					</div>
					<a href="" data-role="button">Confirm</a>
				</div>-->
			</div>
		</div>

<!--
		<div data-role="page" id="confirmNewAvailableTime" class="page">
			<div data-role="header">
				<h1>Change Confirmed</h1>
			</div>
			<div data-role="content">
				<center>
					<h2 id="confirmNewAvailableTimeDifference">Successfully Changed Availability</h2>
					<h3 id="confirmNewAvailableLabel">Now available </h3>
				</center>
				<div class="ui-grid-a">
						<div class="ui-block-a">
							<h3 id="confirmNewAvailableStartTimeLabel">From </h3>
						</div>
						<div class="ui-block-b">
							<h3 id="confirmNewAvailableEndTimeLabel">To </h3>
						</div>
				</div>
				<a href="#spotsSold" data-role="button">Spots Sold</a>
			</div>
		</div>
 -->

		<div data-role="page" id="purchaseDetailPage" class="page">
			<div data-role="header">
					<a href="#spotsPurchased" data-direction="reverse">Purchased</a>
					<h1>Spot Details</h1>
			</div>
			<div data-role="content">
				<div data-role="collapsible">
					<h3>Details</h3>
					<h3>Address</h3>
					<p id="purchaseDetailAddress">123 Ipsum Street</p>
					<h3>Price</h3>
					<p id="purchaseDetailPrice">$3 / hr</p>
					<h3>Rented From</h3>
					<p id ="purchaseDetailTime">TempTime</p>
				</div>
				<div data-role="collapsible">
					<h3>Map</h3>
					<div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
							<div id="purchaseDetailMap" style="height:275px;"></div>
					</div>
				</div>
				<div data-role="collapsible">
					<script>
						function addMoreTime()
						{
							var newEndPurchaseTime = convertHtmlDateToJSDate($('#purchaseDetailNewEndPurchaseTime').val());

							// Convert to PST
							newEndPurchaseTime.setTime(newEndPurchaseTime.getTime() - (1000 * 60 * 60 * 8));

							var minutes = (newEndPurchaseTime.getTime() - curPurchase.endPurchaseTime.getTime()) / (1000 * 60);
							var finalSale = Math.round(curPurchase.price * (minutes / 60.0) * 100) / 100;
							document.getElementById('confirmAdditionalPrice').childNodes[0].nodeValue += "$" + finalSale.toFixed(2);
							document.getElementById('confirmNewEndTimeDifference').childNodes[0].nodeValue += minutes.toFixed(0) + " minutes";
							document.getElementById('confirmNewEndTime').childNodes[0].nodeValue += formatTime(newEndPurchaseTime);
							var url = 'purchases.php?function=update&newEndPurchaseTime=' + $('#purchaseDetailNewEndPurchaseTime').val() + '&purchaseID=' + curPurchase.purchaseID;
							$.post(url);

							window.location.href= "#confirmNewEndPurchaseTime";
						}

						function validateNewPurchaseEndTime()
						{
							var newProposedTime = convertHtmlDateToJSDate($('#purchaseDetailNewEndPurchaseTime').val());
							console.log("newProposedTime " + newProposedTime.toLocaleString());
						}
					</script>
					<h3>Add Time</h3>
					<h3 id="purchaseDetailEndAvailableTime">Spot available until </h3>
					<h3 id="purchaseDetailEndPurchaseTime">Spot owned until </h3>
					<h3>Rent spot until</h3>
						<div data-role="fieldcontain">
							<input type="datetime" name="purchaseDetailNewEndPurchaseTime" id="purchaseDetailNewEndPurchaseTime" value="" />
						</div>
						<a href="javascript:addMoreTime()" data-role="button">Confirm</a>
						<h3 id="purchaseDetailNewEndPurchaseTimeVerification"></h3>
				</div>
			</div>
		</div>

		<div data-role="page" id="confirmNewEndPurchaseTime" class="page">
			<div data-role="header">
				<h1>Change Confirmed</h1>
			</div>
			<div data-role="content">
				<center>
				<h2 id="confirmNewEndTimeDifference">Successfully Added </h2>
				<h3 id="confirmNewEndTime">You now own the spot until </h3>
				<h3 id="confirmAdditionalPrice">You were charged </h3>
				<a href="#spotsPurchased" data-role="button">Purchased Spots</a>
				</center>
			</div>
		</div>

		<div data-role="page" id="buy2" class="page">
			<div data-role="header">
				<a data-rel="back" data-direction="reverse">Home</a>
				<h1>Buy Spots</h1>
			</div>
			<div data-role="content">
				<div data-role="fieldcontain" style="padding-top: 0em;">
					<form action="" name="addressForm" id="addressForm" style="height: 10px">
            		<a href="javascript:centerOnCurLocation()" data-role="button" data-iconpos="notext" data-inline="true" data-icon="home" style="float: right"></a>
					<div style="overflow: hidden; padding-right: 1.2em;">
              			<input type="search" results="4" autosave="true" name="addressSearch" data-inline="true" id="addressSearch" value="" placeholder="Search near address" />
            		</div>
					</form>
				</div>
				<script>
					$("#addressForm").submit(function()
						{
							$("#addressSearch").blur();
							updateMapForAddress($("#addressSearch").val());
							return false;
						});
				</script>
				<div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
					<div id="map_canvas" style="height:295px;"></div>
				</div>
				<script type="text/javascript">
					function getCoordinatesForAddress(address)
					{
						var geocoder = new google.maps.Geocoder();
						geocoder.geocode( { 'address': address}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								var coordinates = results[0].geometry.location;
								getParkingSpotsNearCoordinates(new google.maps.LatLng(coordinates.lat(), coordinates.lng()));
								$('#map_canvas').gmap({'center':new google.maps.LatLng(coordinates.lat() , coordinates.lng() )});
								$('#map_canvas').gmap({'zoom':15});
								return coordinates;
							}
						});
					}

					function getParkingSpotsNearCoordinates(coordinates)
					{
							var xmlHttp = new XMLHttpRequest();
							var postString = "spots.php?function=get&lat="+coordinates.lat()+"&lng="+coordinates.lng()+"&radius="+2.0;
							xmlHttp.open("GET", postString, true);
							xmlHttp.send();

							xmlHttp.onreadystatechange= function()
							{
								if (xmlHttp.readyState==4 && xmlHttp.status==200)
								{
									var spots = JSON.parse(xmlHttp.responseText);
									addSpotsToMap(spots);

								}
							}
					}

					var selectedSpotToPurchase;
					function addSpotsToMap(spots)
					{
						for(var i = 0; i < spots.length; i++)
						{
							var newParkingSpot = spots[i];
							var newParkingMarker = new google.maps.LatLng(newParkingSpot.lat, newParkingSpot.lng);
							$('#map_canvas').gmap('addMarker', {'position': newParkingMarker, 'animation': google.maps.Animation.DROP, 'title': newParkingSpot.spotID}).click(function()
							{
								spotID = $(this).get(0).getTitle();
								for(var i = 0; i < parkingSpots.length; i++)
								{
									if(parkingSpots[i].spotID == spotID)
									{
										document.getElementById("displayAddress").childNodes[0].nodeValue = parkingSpots[i].address;
										document.getElementById("displayPrice").childNodes[0].nodeValue = "$" + parseFloat(parkingSpots[i].price).toFixed(2) + " / hour";
										document.getElementById("displayPrice").setAttribute("value", parkingSpots[i].price);

										var startAvailableTime = convertHtmlDateToJSDate(parkingSpots[i].startAvailableTime);
										var endAvailableTime = convertHtmlDateToJSDate(parkingSpots[i].endAvailableTime);

										document.getElementById("displayTime").childNodes[0].nodeValue = formatTime(startAvailableTime) + " - " + formatTime(endAvailableTime);
										sellerID = parkingSpots[i].sellerID;
										spotID = parkingSpots[i].spotID;
										var displayHTML;
										
										selectedSpotToPurchase = parkingSpots[i];
										
										displayHTML = '$' + parseFloat(parkingSpots[i].price).toFixed(2) + ' / hour<br/>Available From: <br/> ' + formatTime(startAvailableTime) + '<br/>To: ' + formatTime(endAvailableTime) + '<br/><a href="#buy3" data-rel="dialog" data-transition="slidedown" button type="button">Details</a>';
										$('#map_canvas').gmap('openInfoWindow', { 'content': displayHTML }, this);
									}
								}
							});
						}

						parkingSpots = parkingSpots.concat(spots);
					}

					function updateMapForAddress(address)
					{
						getCoordinatesForAddress(address);
					}

					function updateMapForCoords(coords)
					{
						getParkingSpotsNearCoordinates(coords);
						$('#map_canvas').gmap({'center':new google.maps.LatLng(coords.lat() , coords.lng() )});
						$('#map_canvas').gmap({'zoom':15});
					}

					function centerOnCurLocation()
					{
						if(yourStartLatLng)
						{
							$('#map_canvas').gmap({'center':new google.maps.LatLng(yourStartLatLng.lat() , yourStartLatLng.lng() )});
							$('#map_canvas').gmap({'zoom':15});
						}
					}

					var parkingSpots = new Array();
					var sellerID, spotID;
					var pageHasLoaded = new Boolean();

					$('#buy2').bind("pageshow", function(event, data) {
						if(yourStartLatLng && !pageHasLoaded.valueOf())
						{
							pageHasLoaded = true;
							updateMapForCoords(yourStartLatLng);
							yourStartMarker = $('#map_canvas').gmap('addMarker', {'position': yourStartLatLng, 'animation': google.maps.Animation.DROP, title: 'You', icon: 'currentlocdot.png', zIndex: 9999});
						}
					});

					var yourStartLatLng;
					var yourStartMarker;
					navigator.geolocation.getCurrentPosition(foundLocation, noLocation);

					function foundLocation(position)
					{
						yourStartLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					}

					function noLocation()
					{
						yourStartLatLng = new google.maps.LatLng(0,0);
					}

				</script>
			</div><!-- /content -->
		</div><!-- /page -->

		<div data-role="page" id="buy3">
			<div data-role="header" data-theme="b">
				<!-- <a data-rel="back" data-direction="reverse">Map</a> -->
				<h1>Details</h1>
			</div>

			<div data-role="content">
				<h2>Location</h2>
				<p id="displayAddress">123 Ipsum Street</p>
				<h2>Price</h2>
				<p id="displayPrice">$3.00 / hr</p>
				<h2>Availibility</h2>
				<p id ="displayTime">3:00 PM - 12:00 AM</p>
				<center>
					<a href="#buy4" class="nextButton" data-role="button" data-direction="forward">Buy it</a>
				</center>
			</div>
		</div>

		<div data-role="page" id="buy4" class="page">
			<script>
				function verifyBuyTimes()
				{
					$('#buyStartDateTimeDiv').children().remove('.wrong');
					$('#buyEndDateTimeDiv').children().remove('.wrong');
					
					$('#buyStartDateTimeDiv').children().remove('#wrongStart');
					$('#buyEndDateTimeDiv').children().remove('#wrongEnd');
					
				
					var curTime = new Date();
					var curGMTTime = new Date(curTime.getTime() + (1000 * 60 * 60 * 8));
				
					var startPurchaseDate = convertHtmlDateToJSDate($('#buyStartDateTime').val());
					var endPurchaseDate = convertHtmlDateToJSDate($('#buyEndDateTime').val());
					
					var startAvailableDate = convertHtmlDateToJSDate(selectedSpotToPurchase.startAvailableTime);
					var endAvailableDate = convertHtmlDateToJSDate(selectedSpotToPurchase.endAvailableTime);
					
					if(endPurchaseDate.getTime() < startPurchaseDate.getTime())
					{
						$('#buyEndDateTimeDiv').append("<h3 id='wrongEnd' class='wrong'>End time must be later than start time</h3>");
						$('#buyEndDateTimeLabel').addClass("wrong");
						
					} else if(startPurchaseDate.getTime() < curGMTTime.getTime())
					{
						if($('#buyStartDateTimeDiv').children().length == 2)
						{
							$('#buyStartDateTimeDiv').append("<h3 id='wrongStart' class='wrong'>Start time must be later than " + formatTime(curTime) + "</h3>");
							$('#buyStartDateTimeLabel').addClass("wrong");
						}
					} else if(startPurchaseDate.getTime() < startAvailableDate.getTime() || endPurchaseDate.getTime() > endAvailableDate.getTime())
					{
						if(startPurchaseDate.getTime() < startAvailableDate.getTime() && $('#buyStartDateTimeDiv').children().length == 2)
						{
							$('#buyStartDateTimeDiv').append("<h3 id='wrongStart' class='wrong'>Start time must be later than " + formatTime(startAvailableDate) + "</h3>");
							$('#buyStartDateTimeLabel').addClass("wrong");
						}
						
						if(endPurchaseDate.getTime() > endAvailableDate.getTime() && $('#buyEndDateTimeDiv').children().length == 2)
						{
							$('#buyEndDateTimeDiv').append("<h3 id='wrongEnd' class='wrong'>End time must be earlier than " + formatTime(endAvailableDate) + "</h3>");
							$('#buyEndDateTimeLabel').addClass("wrong");
						}
						
					} else
					{
						window.location.href="#buy5";
					}
				}
			</script>
			<div data-role="header">
		  		<a data-rel="back" data-direction="reverse">Details</a>
		  		<h1>Purchase Time</h1>
		 	</div>
			<div data-role="content">
			  	<center>
					<img src="driveway.jpg" height="30%" width="60%"/>
					<div data-role="fieldcontain" id="buyStartDateTimeDiv">
						<label id="buyStartDateTimeLabel" for="buyStartDateTime">Start time: </label>
						<input type="datetime" name="buyStartDateTime" id="buyStartDateTime" value="" />
					</div>
					<div data-role="fieldcontain" id="buyEndDateTimeDiv">
						<label id="buyEndDateTimeLabel" for="buyEndDateTime">End time: </label>
						<input type="datetime" name="buyEndDateTime" id="buyEndDateTime" value="" />
					</div>
					<a href="javascript:verifyBuyTimes()" data-class="nextButton" data-role="button" data-direction="forward">Finish</a>
				</center>
			</div>
		</div>

		<div data-role="page" id="buy5" class="page">
			<div data-role="header">
				<h1>Billing</h1>
			</div>
			<div data-role="content">
				<script>
				$('#buy5').bind("pageshow", function(event, data) {
						var price = document.getElementById("displayPrice").getAttribute("value");
						var buyStartDateTime = convertHtmlDateToJSDate($('#buyStartDateTime').val());
						var buyEndDateTime = convertHtmlDateToJSDate($('#buyEndDateTime').val());
						var hours = (buyEndDateTime.getTime() - buyStartDateTime.getTime()) / (1000 * 60 * 60);
						var finalSale = Math.round(price * hours * 100) / 100;
						$("#finalSale").replaceWith('<h1>$' + finalSale + '</h1>');

						var url = 'purchases.php?function=put&buyerID=' + fb_id + '&sellerID=' + sellerID + '&spotID=' + spotID + '&startPurchaseTime=' + $('#buyStartDateTime').val() + '&endPurchaseTime=' + $('#buyEndDateTime').val();
						//$.post('purchases.php', {'function': "put", 'buyerID' : fb_id, 'spotID': spotID, 'startDateTime': $('#buyStartDateTime').val(), 'endDateTime': $('#buyEndDateTime').val()});
						$.post(url);
				});
				</script>
				<h1>You have been billed</h1>
				<h1 id="finalSale"></h1>
				<h2>Thank you for using ParkIt!</h2>
				<a href="#home" class="nextButton" data-role="button" data-direction="forward"> Home</a>
			</div>
		</div>


<!-- sell -->
		<div data-role="page" id="sell1" class="page">
			<script>
				function verifySellAddress()
				{
					$('#sellAddressLabel').removeClass("wrong");
					$('#sellCityLabel').removeClass("wrong");
					$('#sellStateLabel').removeClass("wrong");
					$('#sellZipLabel').removeClass("wrong");
				
				
					var errorPresent = new Boolean();
					
					if(!$('#address').val())
					{
						$('#sellAddressLabel').addClass("wrong");
						errorPresent = true;
					}
					
					if(!$('#city').val())
					{
						$('#sellCityLabel').addClass("wrong");
						errorPresent = true;
					}
					
					if(!$('#state').val())
					{
						$('#sellStateLabel').addClass("wrong");
						errorPresent = true;
					}
					
					if(!$('#zip').val())
					{
						$('#sellZipLabel').addClass("wrong");
						errorPresent = true;
					}
					
					if(!errorsPresent.valueOf())
					{
						window.location.href = "#sell2";
					}
				}
			</script>
			<div data-role="header">
				<a data-rel="back" data-direction="reverse">Home</a>
				<h1>Your Address</h1>
			</div><!-- /header -->

			<div data-role="content">
				<div id="sellAddressDiv" data-role="fieldcontain">
    				<div class="address_label"><label id="sellAddressLabel" for="address">Address</label></div>
            		<div class="address_input"><input type="text" name="address" id="address" value="" style="width:200px; height:25px" /></div>
        		</div>

				<div id="sellCityDiv" data-role="fieldcontain">
            		<div class="address_label"><label id="sellCityLabel" for="city">City</label></div>
            		<div class="address_input"><input type="text" name="city" id="city" value="" style="width:200px; height:25px"/></div>
				</div>

				<div id="sellStateDiv" data-role="fieldcontain">
            		<div class="address_label"><label id="sellStateLabel"for="state">State</label></div>
					<div class="state_selector">
						<select name="state" id="state">
						  <option value="AL">Alabama</option>
						  <option value="AK">Alaska</option>
						  <option value="AZ">Arizona</option>
						  <option value="AR">Arkansas</option>
						  <option value="CA">California</option>
						  <option value="CO">Colorado</option>
						  <option value="CT">Connecticut</option>
						  <option value="DE">Delaware</option>
						  <option value="DC">District Of Columbia</option>
						  <option value="FL">Florida</option>
						  <option value="GA">Georgia</option>
						  <option value="HI">Hawaii</option>
						  <option value="ID">Idaho</option>
						  <option value="IL">Illinois</option>
						  <option value="IN">Indiana</option>
						  <option value="IA">Iowa</option>
						  <option value="KS">Kansas</option>
						  <option value="KY">Kentucky</option>
						  <option value="LA">Louisiana</option>
						  <option value="ME">Maine</option>
						  <option value="MD">Maryland</option>
						  <option value="MA">Massachusetts</option>
						  <option value="MI">Michigan</option>
						  <option value="MN">Minnesota</option>
						  <option value="MS">Mississippi</option>
						  <option value="MO">Missouri</option>
						  <option value="MT">Montana</option>
						  <option value="NE">Nebraska</option>
						  <option value="NV">Nevada</option>
						  <option value="NH">New Hampshire</option>
						  <option value="NJ">New Jersey</option>
						  <option value="NM">New Mexico</option>
						  <option value="NY">New York</option>
						  <option value="NC">North Carolina</option>
						  <option value="ND">North Dakota</option>
						  <option value="OH">Ohio</option>
						  <option value="OK">Oklahoma</option>
						  <option value="OR">Oregon</option>
						  <option value="PA">Pennsylvania</option>
						  <option value="RI">Rhode Island</option>
						  <option value="SC">South Carolina</option>
						  <option value="SD">South Dakota</option>
						  <option value="TN">Tennessee</option>
						  <option value="TX">Texas</option>
						  <option value="UT">Utah</option>
						  <option value="VT">Vermont</option>
						  <option value="VA">Virginia</option>
						  <option value="WA">Washington</option>
						  <option value="WV">West Virginia</option>
						  <option value="WI">Wisconsin</option>
						  <option value="WY">Wyoming</option>
						</select>
					</div>
				</div>
				<div id="sellZipDiv" data-role="fieldcontain">
					<div class="address_label"><label id="sellZipLabel"for="zip">Zip Code</label></div>
					<div class="address_input"><input type="text" name="zip" id="zip" value="" pattern="[0-9]*" style="width:200px; height:25px"/></div>
				</div>
			<a href="javascript:verifySellAddress()" class="nextButton" data-role="button" data-direction="forward"> Next</a>
			</div>
		</div>

		<div data-role="page" id="sell2" class="page">
			<div data-role="header">
				<a data-rel="back" data-direction="reverse">Address</a>
				<h1>Times Available</h1>
			</div>
			<div data-role="content">
				<div data-role="fieldcontain">
					<label for="sellStartDateTime">Start time</label>
					<input type="datetime" name="sellStartDateTime" id="sellStartDateTime" value="" />
				</div>
				<div data-role="fieldcontain">
					<label for="sellEndDateTime">End time</label>
					<input type="datetime" name="sellEndDateTime" id="sellEndDateTime" value="" />
				</div>
				<a href="#sell3" class="nextButton" data-role="button" data-direction="forward"> Next</a>
			</div>
		</div>

		<div data-role="page" id="sell3" class="page">
			<div data-role="header">
				<a data-rel="back" data-direction="reverse">Time</a>
				<h1>Price</h1>
			</div>
		  	<div data-role="content">
				<div data-role="fieldcontain" style="padding-left:10px" >
					<label for="number" >Price per hour</label>
					<input type="number" name="price" id="price" value="" />
				</div>
				<a href="#sell4">
				  <img src="done.png">
				</a>
			</div>
		</div>

		<div data-role="page" id="sell4" class="page">
			<div data-role="content">
				<h1>You're done!</h1>
				<h2>We will notify you if someone buys your spot</h2>
				<a href="#home" class="nextButton" data-role="button" data-direction="forward"> Home</a>
			</div>
		</div>
	</body>
</html>
