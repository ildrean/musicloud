<!DOCTYPE HTML><html><head>	<title><?php echo h($page_title); ?></title>	<style type="text/css">		@import '../css/tabbed.css';	</style>	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>  	<script> 		$(document).ready(function(){			$.ajaxSetup ({				cache: false			});						$("#Login, #Signup").hide();			$("#error, #errorsignup").hide();						$("a.tab").click(function () {	//sliding panel js				if(!$(this).hasClass("active")){					var $that =  $(this);					var current_id = '#'+$(".tabs a.tab.active").attr("rel");					//slide all content elements up  					$(current_id).slideUp("slow", function(e){						//remove active class from current tab 						$(".active").removeClass("active");						//add active class to new tab						$that.addClass("active");						//Find title attribute value and find element with that id 						var content_show = $that.attr("rel"); 						$("#"+content_show).slideDown("slow");					});  				}			});			 			$("#loginform").submit(function(e){//log in js				var user = $("#username").val();				var pass = $("#password").val();				if(user && pass){					$.ajax({						dataType: "json",						type: "POST",						url: "/login.php",						data: {"user": user, "pass": pass},						success: function(data){							if(data["result"] == "success"){								window.location = "user/"+user;							}else if(data["result"] == "error"){								$("#error").html(data.error);								$("#error").slideDown();							}						}					});				}else{					$("#error").html("Please input the required information.");					$("#error").slideDown();				}				return false;				}); 						$("#signupform").submit(function(e){//sign up js				var user = $("#usernamereg").val();				var pass = $("#passwordreg").val();				var email = $("#emailreg").val();				if(user && pass && email){					$.ajax({						dataType: "json",						type: "POST",						url: "/signup.php",						data: {"user": user, "pass": pass, "email": email},						success: function(data){							if(data["result"] == "success"){								window.location = "user/"+user;							}else if(data["result"] == "error"){								$("#errorsignup").html(data["error"]);								$("#errorsignup").slideDown();							}						}					});				}else{					$("#errorsignup").html("Please input the required information.");					$("#errorsignup").slideDown();				}				return false;				});		});  	</script></head><body>	<div id="tabbed_box_1" class="tabbed_box">		<img src="/img/musicloud.png" class="logo">    <div class="tabbed_area">		<ul class="tabs">			<li><a href="#" rel="About" class="tab active">About</a></li>			<li><a href="#" rel="Login" class="tab">Log In</a></li>			<li><a href="#" rel="Signup" class="tab">Sign Up</a></li>		</ul>        <div id="About" class="content">			<div id="aboutpanel" class="gutter">				<h3>Musicloud is your music, anywhere you want it!</h3>				<p>Musicloud is a work in progress coded and designed by Griffin Sylvester.<p/>				<p>It is intended to be a service offering access to a user's music library from anywhere, be it on a remote computer, a netbook with limited storage, a cell phone with a data plan, or any other place away from home.<p/>				<p>Planned features include playlists, album art, integration with last.fm and similar music information services, and general song information display (time, artist, album, genre, rating, play count, etc.)<p/>			</div>		</div>		        <div id="Login" class="content">			<div id="loginpanel" class="gutter">			<div id="error"></div>				<form id="loginform">					<p>						<label>Username:</label>						<input id="username" type="text" size="15" name="user" />						<br />					</p>					<p>						<label>Password:</label>						<input id="password" type="password" size="15" name="pass" />						<br />					</p>					<p>						<input class="button" type="submit" name="login" value="Login" />					</p>				</form>			</div>        </div>		        <div id="Signup" class="content">			<div id="signuppanel" class="gutter">			<div id="errorsignup"></div>				<form id="signupform">					<p> 						<label>Username:</label>						<input id="usernamereg" type="text" size="15" name="userreg" />						<br />					</p>					<p>						<label>Password:</label>						<input id="passwordreg" type="password" size="15" name="passreg" />						<br />					</p>					<p>						<label>Email:</label>						<input id="emailreg" type="text" size="15" name="emailreg" />						<br />					</p>					<input class="button" type="submit" name="signupreg" value="Sign Up" />				</form>			</div>        </div>		    </div></div></body></html>