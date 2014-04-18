#!/usr/local/bin/php

<!DOCTYPE html>
<html>
	
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>My World</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
    <script src="http://maps.google.com/maps/api/js?sensor=false"
              type="text/javascript"></script>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script> $( document ).ready(function() {
        console.log( "document loaded" );
		$('#propic1').click(function(){
			console.log("propic click");
			$.ajax({
			  type: "POST",
			  url: "bucket.php",
			  data: { name: "John", location: "Boston" }
			})
			  .done(function( msg ) {
				alert( "Data Saved: " + msg );
			  });
		return false;
		});
		
    });
	</script>
  <title>
   My World
  </title>
    
 </head>
 <body>
  <?php  
	session_start();
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

	$usrn = $_SESSION['user'];
	$unalbum = null;
	$albumid = null;
	
	$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $result = pg_query($dbconn, "select firstn, lastn, pw, userid, username from users natural join password where username='$usrn'");
	
	if (!$result) {
	 echo "An error has occurred.\n";
	 exit;
	}
	$dbfn = pg_fetch_result($result, 0, 0);
	$dbln = pg_fetch_result($result, 0, 1);
	$dbpass = pg_fetch_result($result, 0, 2);
	$user_id = pg_fetch_result($result,0,3);
	$dbusrn = pg_fetch_result($result, 0, 4);
	
	 $_SESSION['userid'] = pg_fetch_result($result,0,3);	
	 }
	 
	$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$user_id'");
	$frdreqcount = pg_fetch_result($frdreq,0,0);
	  ?>
	<!-- < ? /*if($result) { ? >
	<div id="map_canvas"></div>
	< ?php } else { ? >
    <div class="myotherdiv">Unable to upload image. No location detected.</div>
	< ?php }*/ ? >
	-->
   <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.php">My World</a>
      </div>
      <div class="collapse navbar-collapse">
       <ul class="nav navbar-nav">
        <?php echo '<li><a href="profile.php?un='.$dbusrn.'">Preview</a></li>';?>	
        <li><a href="home.php">Home</a></li>
        <li><a href="friends.php">Friends</a></li>
	    <li><a href="friendreq.php">
	     <?php 
	 	  if(!($frdreqcount)) {echo 'Friend Requests';}			
		  else{echo 'Friend Requests ('.$frdreqcount.')';}
	     ?>
	    </a></li>			
       </ul>
	   <form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
	    <button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
	   </form>
	   <form class="navbar-form navbar-right" name="form" action="search.php" method = "post">   <!--this is now a test for search.php -->         
	    <div class="form-group">
	     <input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person">					
	    </div>
			<button type="submit" class="btn btn-success">Search</button>
			</form>

        </div><!--/.nav-collapse -->
      </div>
    </div>
    

    </div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>
