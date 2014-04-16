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
		
		<title> Search </title>
 </head>
 <body>
	<?php 
	
	session_start();	
	$urid = $_SESSION['userid'];
	$dbusrn = $_SESSION['usrn'];
	
	$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
	$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$urid'");
	$frdreqcount = pg_fetch_result($frdreq,0,0);
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
          <a class="navbar-brand" href="#">My World</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="profile.php">Preview</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="friends.php">Friends</a></li>
			<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>
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
	<br /> <br /> <br />
 
	<?php  	
	require "functions.php";
	$var = $_POST['person'];  	
	$arr = explode(' ',trim($var));
	$frd = $arr[0];
	$frdU = ucwords($arr[0]);
	$userid = $_SESSION['userid'];   
	$usrn = $_SESSION["user"];
	$urid = $_SESSION["userid"];  
	
	$c = count($arr);		
	$pln = strtolower($arr[1]);
	
	$result = pg_query($dbconn, "select username, userid, firstn, lastn from users where username like '$frd%' or firstn like '$frd%' or firstn like '$frdU%'");
	$max_rows = pg_num_rows($result);
	
	
	if($c == 1 && $max_rows) {
		echo '<table align="center">';		 
		$tmp = 0;
			for($row = 0; $row < ($max_rows/5); $row++) {
				echo '<tr>';	
				for($col = 0; $col < ($tmp+5) && $col != $max_rows; $col++) {	
					$uid = pg_fetch_result($result,$tmp+$col,1);
					$fn = pg_fetch_result($result,$tmp+$col,2);
					$ln =	pg_fetch_result($result,$tmp+$col,3);
					$un = pg_fetch_result($result,$tmp+$col,0);

					if($uid) {
					?>
														
					<td ALIGN=CENTER>
					<form name="form" method="get" action="search.php">	
					<ul style="list-style: none;"><li><label><?php echo "$fn $ln ";?></label>					
					<div class="container" style="width: 175px">
	
						<?php 												
						$path = null;
						$path=profile_picture($uid);
						$destination = '<a href="friendprofile.php/?frdun='.$un;
						$path = $destination.'"><img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
					</div>															
					</form></li></ul></td>					
					<?php
					}
				}
			$tmp = $tmp+5;
			}
		
		echo '</table>';
	}
	else if($c == 2) {	
		for($i = 0; $i < $max_rows; $i++) {	
					$uid = pg_fetch_result($result,$i,1);
					$fn = pg_fetch_result($result,$i,2);
					$ln =	pg_fetch_result($result,$i,3);
					$lnL = strtolower($ln);
					$un = pg_fetch_result($result,$i,0);
					
					if($lnL == $pln) {					
					?>
					<form name="form" method="get" action="search.php">	
					<!--&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp				
					--><li><Label><?php echo "$fn $ln ";?></label>
					
					<div class="container">
	
						<?php 												
						$path = null;
						$path=profile_picture($uid);
						$destination = '<a href="friendprofile.php/?frdun='.$un;
						$path = $destination.'"><img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
					</div>						
					</form></li>					
					<?php
					}
					else {
						
					}
				}
	}
	
	pg_close($dbconn);
  ?>	
 </body>
</html>


