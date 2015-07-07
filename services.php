 <!DOCTYPE html>
<html>
<head>
<title>Poppy script page</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<?php
function tty($kill) {
	if($kill == true) {
		exec('fuser -k /dev/ttyACM0');
	} else {
		exec('fuser /dev/ttyACM0');
	}
}

// Start python only if not previously started
if($_GET["python"] === "start") {
	echo "python start";

	if (exec('fuser /dev/ttyACM0') == NULL){
		echo "/dev/ttyACM0 is free";
		// Start poppy-services
		exec('/home/poppy/.pyenv/shims/poppy-services poppy-torso --http --snap --no-browser > services.log 2>&1 &');
	}
} elseif($_GET["python"] === "restart") {
	echo "Restart python";
	exec('fuser -k /dev/ttyACM0');
	exec('/home/poppy/.pyenv/shims/poppy-services poppy-torso --http --snap --no-browser > services.log 2>&1 &');
} elseif($_GET["python"] === "stop") {
        echo "Stop python";
        exec('fuser -k /dev/ttyACM0');
        display();
} elseif($_GET["python"] === "update") {
        echo "Not implemented";
} 

if($_GET["web"] === "snap"){
	echo "Snap redirection";
	echo "
           	<script type=\"text/javascript\">
            	document.location.href=\"snap/\"
		</script>
       	";
}
if($_GET["web"] === "poppy-monitor"){
	echo "
                <script type=\"text/javascript\">
                document.location.href=\"poppy-monitor/\"
                </script>
	";
}
if($_GET["web"] === "IP"){
$ip =$_SERVER['REMOTE_ADDR'];
	echo "
                <script type=\"text/javascript\">
                document.write(\"".$ip."\");
                </script>
	";
    
    	/*echo "
                <script type=\"text/javascript\">
                alert(\"".$ip."\");
                </script>
	";*/
}
if($_GET["web"] === "speak"){
shell_exec("echo moa > /home/poppy/data.txt");
	shell_exec ('picospeaker -l "fr-FR" "bonjour"');
    display();
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
</body>

</html>

