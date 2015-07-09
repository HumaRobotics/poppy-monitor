 <!--<!DOCTYPE html>
<html>
<head>
<title>Poppy script page</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="css/style.css" />-->
<?php
function tty($kill) {
	if($kill == true) {
		exec('fuser -k /dev/ttyACM0');
	} else {
		exec('fuser /dev/ttyACM0');
	}
}

if(array_key_exists("python", $_GET)){

// Start python only if not previously started
if($_GET["python"] === "start") {
	echo "python start";

	if (exec('fuser /dev/ttyACM0') == NULL ){
		echo "/dev/ttyACM0 is free";
		// Start poppy-services
		exec('/home/poppy/.pyenv/shims/poppy-services poppy-humanoid --http --snap --no-browser &');
        //shell_exec( 'poppy-services --http poppy-humanoid &');
        display();
	}
} elseif($_GET["python"] === "restart") {
	echo "Restart python";
	exec('fuser -k /dev/ttyACM0');
    exec('fuser -k /dev/ttyACM1');
	exec('/home/poppy/.pyenv/shims/poppy-services poppy-humanoid --http --snap --no-browser ');
    display();
} elseif($_GET["python"] === "stop") {
        echo "Stop python";
        exec('fuser -k /dev/ttyACM1');
        //sleep(1);
        exec('fuser -k /dev/ttyACM0');
        display();
} elseif($_GET["python"] === "update") {
        echo "Not implemented";
} 
}

if(array_key_exists("web", $_GET)){


if($_GET["web"] === "snap"){
	echo "Snap redirection";
	echo "
           	<script type=\"text/javascript\">
            	document.location.href=\"../snap/\"
		</script>
       	";
}
elseif($_GET["web"] === "poppy-monitor"){
	echo "
                <script type=\"text/javascript\">
                document.location.href=\"poppy-monitor/\"
                </script>
	";
}
elseif($_GET["web"] === "speak"){
if(array_key_exists("say", $_GET)){
//echo $_GET["say"];
putenv("USER=poppy");
exec ('picospeaker -l "fr-FR" "'.$_GET["say"].'" ');
}
/*else{
echo "rien to say";
}*/

//echo shell_exec ('printenv | grep USER');
	
    display();
}
}

if (empty($_GET)) {
    display();
}

function display() 
  {
    $contents = file_get_contents("index.html");
    $ip =$_SERVER['REMOTE_ADDR'];
$contents = str_replace("%IP%", $ip, $contents);
echo $contents;
  }

?>
<!--</body>

</html>-->

