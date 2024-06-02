<?php
include_once "_inc_global.php";
include("Grabzit/lib/GrabzItClient.php");

$dir	=rqst("dir");
$name	=rqst("name");
$play	=rqst("play");
$width	=rqst("width");
$height	=rqst("height");


$url	="http://".$domain."/_pvt/_pixi.php?src=_lastpic.ixi&login=no&dir=".$dir."&play=".$play."&refresh=".rand(1000,10000);
$name	=$gPath.$dirlink.$name.".png";
//wrtb($name);
//exit("EXIT!");

?>
<HTML>
<HEAD>
<script>
function _obj(x){return document.getElementById(x);}
function onload(){
	try{ parent.parent.refresh(); }catch(e){}
	try{ parent._obj("iBusy").style.display="none";}catch(e){}
	try{ parent._obj("iTxt").innerHTML="Done. You may now close this window";}catch(e){}
}
</script>
</HEAD>
<BODY onload="onload()">
<center>
<?

// Create the GrabzItClient class
$grabzIt = new \GrabzIt\GrabzItClient("MjU4MDY1ZmQyOThmNGNkZjg2N2UyMzdlNjg0MWVmOTU=", 
									  "DCQ1HylTPz8/ND8fDFx0QD8/Vz8/ej96MT92SDE/P00=");

$options = new \GrabzIt\GrabzItImageOptions();
$options->setFormat("png");
$options->setWidth($width*2);
$options->setHeight($height*2);
$options->setDelay(7000);
$grabzIt->URLToImage($url, $options); 	
$grabzIt->SaveTo($name);

echo("<img src='".$name."' style='width:100%;'>");
?>

</center>
</BODY>
</HTML>

