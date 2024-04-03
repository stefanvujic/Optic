<?php

include_once "_inc_global.php";

$dir	=rqst("dir");
$name	=rqst("name");
$play	=rqst("play");
$width	=rqst("width");
$height	=rqst("height");

?>
<HTML>
<script>
function _obj(x){return document.getElementById(x);}
</script>
<BODY>
<center>
<DIV id='iTxt' style='font-size:25px;font-family:sans-serif;position:relative;top:5px;'>Please be patient...</DIV>
<img id='iBusy' src='_pvt_images/busy.gif' style='position:absolute;top:10px;left:10px;background:white;'>
<br>
<IFRAME src="_takePic.php?dir=<?=$dir;?>&name=<?=$name;?>&play=<?=$play;?>&width=<?=$width;?>&height=<?=$height;?>" 
		style="border:solid 0px white;width:90%;height:90%;"></IFRAME>
</center>
</BODY>
</HTML>
