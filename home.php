#!/usr/local/bin/php

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
  
 </head>
 <body style="background-color:#E6E6E6;">
  <?php  
	session_start();
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

	$usrn = $_SESSION['user'];

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
	
	$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$user_id'");
	$frdreqcount = pg_fetch_result($frdreq,0,0);
	
	  
	$_SESSION['userid'] = pg_fetch_result($result,0,3);	
	$_SESSION['fn'] = pg_fetch_result($result,0,0);
	$_SESSION['ln'] = pg_fetch_result($result,0,1);
	$_SESSION['usrn'] = pg_fetch_result($result, 0, 4);
	}
  ?>
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
        <li class="active"><a href="#">Home</a></li>
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
        <button type="submit" class="btn btn-success" requierd />Search</button>
	   </form>
      </div><!--/.nav-collapse -->
     </div>
  </div>
  <div id="jumborn">
  <div id="table" style="display: table; width:100%;" align="center">
  
   <div style="display: table-row;">
    <div style="display: table-cell; vertical-align:top;" align="center">
     <div class="container" style="margin-top:60px; padding-left:100px; width:500px;">      
      <?php 
 	   echo '<div border-bottom:30px ><h2 style="color:#00CCFF"> Welcome, '.$dbfn.' '.$dbln.'</h2></u></div><br/>';	
	   require 'functions.php';	
	   $path = null;
	   $path=profile_picture($user_id);
	   $path = '<img src="'.$path. '" alt="image" width=200 height=auto class="img-thumbnail"/>';
	   echo $path;
      ?>
     </div>
	<br/>
     <div class="container" style=" padding-left:100px; width:500px;">
      <div class="starter-template">
	   <h1></h1> 
	   <!--<form action="upload_file.php" method="post" enctype="multipart/form-data">-->
		<form class="navbar-form navbar-center" name="form" action="upload_file.php" method = "post" enctype="multipart/form-data">
	    
		<div id=fileinputs style="position: relative"  align="left">
	    <label for="file">Filename: </label>
	    <input type="file" name="file" id="file"><br />
	    </div>
		
		<!--Album Name: <input type = "Text" value = "" name = "an" id="an"><br />-->	
		<div id=textsub style="position: relative"  align="left">
		<input type="text" class="form-control" placeholder="Album Name" name="an" id="an"><br /><br/>		
	    <button type="submit" class="btn btn-info btn-sm" name="submit" value="Upload">Upload</button>
		</div>
	   </form>
      </div>      
     </div>

    </div>
	
    <div style="margin-top:90px; margin-right:-80px; height:530px; width:600px; overflow-y:scroll;"> 
    <div style="display: table-cell; margin-right:500px;">
     
	  <?php
	   include 'newsfeed.php';	  
  	   if (!$news_feed_result ) {
        echo "An error occurred.\n";
    	exit;
  	   }
	    
	   echo '<table style="width:560px; height:200px; overflow:auto;">';
  	   while ( $row = pg_fetch_row($news_feed_result )) {    	   
	   echo '<tr> <td style="width:300px;" align=right>';
       echo "$row[2] $row[3] added photo to $row[1]";
	   echo '</td> <td align=right>';
       echo '<img src="'.$row[0].'" alt="image" width="200" height="auto" class="img-thumbnail"/> <br /> <br />';
       echo '</td></tr>';	
	   }	
	   echo'</table>';
	  ?>    
     
    </div>
    </div>
    </div>
   </div>
  </div>

  <!--</div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>