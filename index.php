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

$comments = "";
//print_r($_POST);

//if(array_key_exists("python", $_GET)){
if(array_key_exists("python", $_POST)){

// Start python only if not previously started
//if($_GET["python"] === "start") {
if($_POST["python"] === "start") {
	//echo "python start";

	if (exec('fuser /dev/ttyACM0') == NULL ){
		//echo "/dev/ttyACM0 is free";
		// Start poppy-services
		exec('/home/poppy/.pyenv/shims/poppy-services poppy-humanoid --http --snap --no-browser &');
        display();
	}
//} elseif($_GET["python"] === "restart") {
} elseif($_POST["python"] === "restart") {
	//echo "Restart python";
	exec('fuser -k /dev/ttyACM0');
    exec('fuser -k /dev/ttyACM1');
	exec('/home/poppy/.pyenv/shims/poppy-services poppy-humanoid --http --snap --no-browser ');
    display();
} elseif($_POST["python"] === "stop") {
//} elseif($_GET["python"] === "stop") {
        //echo "Stop python";
        exec('fuser -k /dev/ttyACM1');
        //sleep(1);
        exec('fuser -k /dev/ttyACM0');
        display();
} elseif($_POST["python"] === "update") {
//} elseif($_GET["python"] === "update") {
        echo "Not implemented";
} 
}

//if(array_key_exists("web", $_GET)){
if(array_key_exists("web", $_POST)){


//if($_GET["web"] === "snap"){
if($_POST["web"] === "snap"){
echo "
           	<script type=\"text/javascript\">
            	window.open(\"../snap/\");
		</script>
       	";

	 global $comments;
 /* $comments ='<div class="panel panel-default">
Pour avoir les blocs Snap! pour Poppy, dans l\'onglet de Snap!, cliquez sur le fichier, puis ouvrir, puis exemples et choisissez pypot-snap-block.
  </div>';*/
    $comments ='<div class="panel panel-default">
   <p> To get Poppy\'s Snap! blocks, click on the file icon, then open->examples and select pypot-snap-block.</p>
  </div>';
   display();
   
   
	/*echo "
           	<script type=\"text/javascript\">
            	document.location.href=\"../snap/\"
		</script>
       	";*/

}
//elseif($_GET["web"] === "speak"){
elseif($_POST["web"] === "speak"){
//~ if(array_key_exists("say", $_GET)){
if(array_key_exists("say", $_POST)){
//echo $_GET["say"];
putenv("USER=poppy");
//~ exec ('picospeaker -l "fr-FR" "'.$_GET["say"].'" ');
//~ exec ('picospeaker  "'.$_GET["say"].'" ');
exec ('picospeaker  "'.$_POST["say"].'" ');

}
/*else{
echo "rien to say";
}*/

//echo shell_exec ('printenv | grep USER');
	
    display();
}
//~ elseif($_GET["web"] === "upgrade"){
elseif($_POST["web"] === "upgrade"){
    
    putenv('PATH="/home/poppy/.pyenv/shims:/home/poppy/.pyenv/bin:/home/poppy/.pyenv/shims:/home/poppy/.pyenv/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games"');
  exec('eval "$(pyenv init -)"');
 exec( 'eval "$(pyenv virtualenv-init -)"');
 
 $comments ='<div class="panel panel-default">
<p> Current versions:</p>
<p> '.checkVersions().'</p>';
    
    exec("pip install --upgrade poppy-humanoid > upgrades.log 2>&1 ");
    
    $comments =$comments.'
<p> Checking for upgrades</p>
<p> New versions:</p>
<p> '.checkVersions().'</p>
  </div>';
  display();
}
//~ elseif($_GET["web"] === "reboot"){
elseif($_POST["web"] === "reboot"){
    global $comments;
  $comments ='<div class="panel panel-default">
<h2> reboot : Are you sure ? </h2>
      <button type="button" class="btn btn-default" onclick="post(\'/index.php\', {web: \'rebootsure\'})"> 
                     Yes
                </button>
      <button type="button" class="btn btn-default" onclick="post(\'/index.php\', {})"> 
                     Cancel
                </button>
  </div>';
   display();
}
elseif($_POST["web"] === "rebootsure"){
//~ elseif($_GET["web"] === "rebootsure"){
	echo " rebooting ";
    exec("sudo reboot");
}
//elseif($_GET["web"] === "poweroff"){
elseif($_POST["web"] === "poweroff"){
    global $comments;
   // $comments = "<h2> Poweroff: Are you sure ? </h2>";
    
    
  $comments ='<div class="panel panel-default">
<h2> Poweroff: Are you sure ? </h2>
      <button type="button" class="btn btn-default" onclick="post(\'/index.php\', {web: \'poweroffsure\'})"> 
                     Yes
                </button>
      <button type="button" class="btn btn-default" onclick="post(\'/index.php\', {})"> 
                     Cancel
                </button>
  </div>';
   display();
}
//~ elseif($_GET["web"] === "poweroffsure"){
elseif($_POST["web"] === "poweroffsure"){
echo "poweroff";
	 exec("sudo poweroff");
}
}

//~ if (empty($_GET)) {
if (empty($_POST)) {
    display();
}

function checkVersions(){
$poppy_humanoid = file_get_contents("/home/poppy/dev/poppy-humanoid/software/poppy_humanoid/_version.py");
$pypot = file_get_contents("/home/poppy/dev/pypot/pypot/_version.py");
$poppy_creature = file_get_contents("/home/poppy/dev/poppy-creature/software/poppy/_version.py");

return "<ul><li>poppy_humanoid ".$poppy_humanoid."</li><li>pypot ".$pypot."</li><li>poppy_creature ".$poppy_creature."</li></ul>";
}


function display() 
  {
  
    $contents = file_get_contents("poppy_webapps.html");
    $ip =$_SERVER['SERVER_ADDR'];
$contents = str_replace("%IP%", $ip, $contents);
global $comments;
$contents = str_replace("%COMMENTS%", $comments, $contents);
echo $contents;

  }

?>
<!--</body>

</html>-->

