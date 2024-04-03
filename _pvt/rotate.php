<?
include_once "_inc_global.php";
include_once "_inc_header.php";
$file=rqst("file");
$action=rqst("action");
$mode=rqst("mode");
if(!$file){ echo("<center><br><br><big>No file passed</big></br></br>"); exit(0); }
$src=$gPath.$dirlink.$file;
$cleansrc=_leftStr($src,"?");
$cleansrc=_leftStr($cleansrc,"&");
?>
<script>
function refreshMenu(){ }
function _obj(id){return document.getElementById(id);}

function formSubmit(mode){
	_obj("imode").value=mode;
	_obj("iform").submit();
}
</script>
</head>
<body>

<?php
if($action=="save"){
	//echo("filename=".$cleansrc."<br><br>");
	$ext=get_ext($cleansrc);
	$r=rand(1,999);
	$newname=_rep($cleansrc, $ext, $r.$ext);
	//echo("mode=".$mode.", ext=".$ext.", newname=".$newname."<br><br>");
	//exit(0);
	NEW_rotate($cleansrc,$newname,$mode);
	echo("<script>parent.refresh();</script>");
	echo("<center><br><b>SUCCESS!</b>&nbsp;&nbsp;&nbsp;The new image has been rotated");
	echo("<br><br><img src='".$newname."' style='width:40%;'>");
	echo("<br><br><b>".$newname."</b>");
	echo("<br><br>You may now <input type=button class='btn' ".$movers." onclick='parent.closeWindow(0)' value='  CLOSE  '  style='height:33px;'> this window");
	echo("<br><br></center>");
	exit(0);
}
?>

<center>
<br>
<FORM id="iform" name="iform" action="rotate.php" method="post">
<TABLE style=""><tr>
	<td><input type=button class='btn' <?=$movers;?> value="  ROTATE  HORIZONTAL  " onclick="formSubmit(2)" style="height:33px;"></td>
	<td style='width:30px;'></td>
	<td><input type=button class='btn' <?=$movers;?> value="   ROTATE  VERTICAL   " onclick="formSubmit(1)" style="height:33px;"></td>
	<td style='width:30px;'></td>
	<td><input type=button class='btn' <?=$movers;?> value="     ROTATE  BOTH     " onclick="formSubmit(3)" style="height:33px;"></td>
</tr></TABLE>
<input type=hidden name=action value="save">
<input type=hidden name=dir value="<?=$dir;?>">
<input type=hidden name=file value="<?=$file;?>">
<input type=hidden id="imode" name=mode value="3">
</FORM>

<DIV style="width:40%;">
	<img src="<?=$cleansrc;?>" style="width:100%;">
</DIV>
<br>
<small></small><?=$cleansrc;?></small>
</center>
<br><br>&nbsp;
</body>
</html>

<?

//-----------------------------------rotate()-----------------------------------
function NEW_rotate($filename,$newname,$mode){
global $gPath;
$ext=get_ext($filename);
if(!_in($filename,$gPath))$filename=$gPath.$filename;
$ok="no";
if(preg_match("/png|PNG/",$ext)){ $img=imagecreatefrompng($filename);  $ok="png";}
if(preg_match("/gif|GIF/",$ext)){ $img=imagecreatefromgif($filename);  $ok="gif";}
if($ok=="no")					{ $img=imagecreatefromjpeg($filename); $ok="jpg";} //default to jpg
if(!$img || $ok=="no")return 0;
$img=myImageFlip($img,$mode);
if($ok=="png")imagepng($img,$newname);
if($ok=="gif")imagegif($img,$newname);
if($ok=="jpg")imagejpeg($img,$newname);
imagedestroy($img);
return 1;
}


function myImageFlip($imgsrc,$mode){
    $width                        =    imagesx ( $imgsrc );
    $height                       =    imagesy ( $imgsrc );
    $src_x                        =    0;
    $src_y                        =    0;
    $src_width                    =    $width;
    $src_height                   =    $height;
    switch($mode){
        case '1': //vertical
            $src_y                =    $height -1;
            $src_height           =    -$height;
        	break;
        case '2': //horizontal
            $src_x                =    $width -1;
            $src_width            =    -$width;
        	break;
        case '3': //both
            $src_x                =    $width -1;
            $src_y                =    $height -1;
            $src_width            =    -$width;
            $src_height           =    -$height;
        	break;
        default:
            return $imgsrc;
    }
    $imgdest=imagecreatetruecolor($width,$height);
    if(imagecopyresampled($imgdest, $imgsrc, 0, 0, $src_x, $src_y , $width, $height, $src_width, $src_height))
    	return $imgdest;
	else
    	return $imgsrc;
}


?>