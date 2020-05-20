<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Register for Pangya</title>
	
	<!--[if IE]><script>
	$(document).ready(function() { 

$("#form_wrap").addClass('hide');

})

</script><![endif]-->

	<!-- jQuery -->
	
	<script src="./js/jquery-1.3.2.min.js" type="text/javascript"></script>


	<!-- Stylesheet -->

	<link rel="stylesheet" type="text/css" href="./styles.css" />

<style>
@font-face {
	font-family: 'YanoneKaffeesatzRegular';
	src: url('./fonts/yanonekaffeesatz-regular-webfont.eot');
	src: url('./fonts/yanonekaffeesatz-regular-webfont.eot?#iefix') format('embedded-opentype'),
	url('./fonts/yanonekaffeesatz-regular-webfont.woff') format('woff'),
	url('./fonts/yanonekaffeesatz-regular-webfont.ttf') format('truetype'),
	url('./fonts/yanonekaffeesatz-regular-webfont.svg#YanoneKaffeesatzRegular') format('svg');
	font-weight: normal;
	font-style: normal;
}
</style>
<?php
require("./config.php");
?>

	<!-- Contact Form -->
	
	<script type="text/javascript">                                         
	/* <![CDATA[ */
		$(document).ready(function(){ 
			$("#contact-form").submit(function(){
				var str = $(this).serialize();
				$.ajax({
				   type: "POST",
				   url: "./php/insciption.php",
				   data: str,
				   success: function(msg)
				   {
						$("#formstatus").ajaxComplete(function(event, request, settings){
							if(msg == 'OK'){ 
								result = '<div class="formstatusok">Welcome to this server!</div><br/> Now you can login into the game.';
								$("input").hide();
								$("label").hide();
								$("select").hide();
							}
							else{
								result = msg;
							}
							$(this).html(result);
						});
					}
				
				 });
				return false;
			});
		});
	/* ]]> */	
	</script>  
</head>

<body>
	<div id="wrap">
		<h1>Register - <?php echo SERVER_NAME;?></h1>
		<div id='form_wrap'>
			<form id="contact-form" action="javascript:alert('Bienvenue !');">

		<p id="formstatus"></p>
				<label for="name">UserName: </label>
				<input type="text" name="name" value="" id="name" />
				<label for="pass">Password: </label>
				<input type="text" name="pass" value="" id="pass" />
				<label for="email">Email: </label>
				<input type="text" name="email" value="" id="email" />
			   </br></br>
<?php
if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
{
	echo '<label for="email">Game Master Right ?: </label><input type="checkbox" name="gm" value="" id="gm" /><br/><br/>';
}
?>
				<input class="btn" type="submit" name="submit" value="Register" />
				</br></br>
				<a id="btn2" href="<?php echo DOWNLOAD_LINK;?>">Download Client</a>
			</form>
			
		</div>
	</div>
</body>
</html>