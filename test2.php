#!/usr/local/bin/php

<html>
 <head> 
	
 </head>
 <body>
  <?php
	session_start();
  
   $frd = $_POST['person'];  
   $usrn = $_SESSION['user'];
   
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $result = pg_query($dbconn, "select username, userid from users where username='$frd'");
   $result2 = pg_query($dbconn, "select userid from users where username='$usrn'");
   $friend = pg_fetch_result($result,0,0);      
   if ($friend == $frd && $frd != $usrn) {
	$frdId = pg_fetch_result($result,0,1);
	$myId = pg_fetch_result($result2,0,0);
	$tur = pg_query($dbconn, "update friends set friendreq = (select array_append((select friend from friends where userid = $frdId),$myId)) where userid=$frdId");
   
	echo "Friend request sent to $friend.";
	}
   else {
    echo "$frd does not exist.";
	exit; 
	}
  ?>
 </body>
</html>


