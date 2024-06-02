<?
include_once "_inc_global.php";
include_once "_inc_header.php";
$file=rqst("file");
$action=rqst("action");
$page=rqst("page");
if(!$file && !$page){ echo("<center><br><br><big>No file passed</big></br></br>"); exit(0); }
if($page=="newfimg"){ // Get the URL of a remote image
	$typ="remote";
	newFimg(); 
	$src=$file;	
}else{
	if(_in(strtolower($file),"http")){
		$typ="remote";
		$src=$file;
	}else{
		$typ="local";
		$src=$gPath.$dirlink.$file;
	}
}
//wrtb("dirlink=".$dirlink);
$cleansrc=_leftStr($src,"?");
$cleansrc=_leftStr($cleansrc,"&");

?>
<script src="_pvt_js/jquery-1.8.3.js"></script>
<script src="_pvt_js/crop-select-js.min.js"></script>
<link   href="_pvt_css/crop-select-js.min.css" rel="stylesheet" type="text/css" />
<script>
function refreshMenu(){ }
function _obj(id){return document.getElementById(id);}
function formSubmit(){
_obj("fw").value=_obj("scaled-width").innerHTML;
_obj("fh").value=_obj("scaled-height").innerHTML;
_obj("fx").value=_obj("scaled-x").innerHTML;
_obj("fy").value=_obj("scaled-y").innerHTML;
_obj("iform").submit();
}
</script>
</head>
<body>

<?php
if($action=="save"){
	$width=(rqst("w"))*1;
	$height=(rqst("h"))*1;
	$fromx=(rqst("x"))*1;
	$fromy=(rqst("y"))*1;
 	$ext=get_ext($cleansrc);
	if($typ=="remote"){
		//$newname=$gPath.rand(100,1000000).$ext;
		$cnt=1;
		$newname=$gPath.$dirlink."z".$cnt.$ext;
		while(file_exists($newname)){
			$cnt=$cnt+1;
			$newname=$gPath.$dirlink."z".$cnt.$ext;
		}
	}else{
		$cnt=1;
		$newname=_rep($cleansrc,$ext,"".$cnt.$ext);
		while(file_exists($newname)){
			$cnt=$cnt+1;
			$newname=_rep($cleansrc,$ext,"".$cnt.$ext);
		}
	}
	//wrtb("cleansrc=".$cleansrc."<br>");
	//wrtb("typ=".$typ.", newname=".$newname."<br>");
	if(crop($cleansrc,$newname,$fromx,$fromy,$width,$height,$ext)){
		$thumbname=thumbName($newname);
  		resize($newname,$thumbname,100,80,$ext);
		echo("<script>parent.refresh();</script>");
		echo("<center><br><b>SUCCESS!</b>&nbsp;&nbsp;&nbsp;The new image has been added to the current folder");
		echo("<br><br><img src='".$newname."' style='width:40%;'>");
		echo("<br><br><b>".$newname."</b>");
		echo("<br><br>You may now <input type=button class='btn' ".$movers." onclick='parent.closeWindow(0)' value='  CLOSE  '  style='height:33px;'> this window");
		echo("<br><br></center>");
	}else{
		echo("<center><br><br><b>Sorry - an unknown error occurred</b>");
	}
	exit(0);
}


$btnval=($typ=="remote")?"SAVE":"APPLY";
?>

<center>
<br>
<FORM id="iform" name="iform" action="crop.php" method="post">
<TABLE style="">
<tr><td>
	<TABLE style="border:solid 1px #999999;padding:4px;"><tr>
	<td width=30></td>
	<td align=right >Width:&nbsp;&nbsp;</td><td id="scaled-width" width=60></td>
	<td align=right>Height:&nbsp;&nbsp;</td><td id="scaled-height" width=60></td>
	<td align=right>X:&nbsp;&nbsp;</td><td id="scaled-x" width=60></td>
	<td align=right>Y:&nbsp;&nbsp;</td><td id="scaled-y" width=60></td>
	</tr></TABLE>
</td><td>
	<input type=button class='btn' <?=$movers;?> value="  <?=$btnval;?>  " onclick="formSubmit()" style="height:33px;">
</td></tr>
</TABLE>
<input type=hidden name=action value="save">
<input type=hidden name=dir value="<?=$dir;?>">
<input type=hidden name=file value="<?=$file;?>">
<input id="fw" type=hidden name="w">
<input id="fh" type=hidden name="h">
<input id="fx" type=hidden name="x">
<input id="fy" type=hidden name="y">
</FORM>

<DIV id="iContainer" class="container" style="width:40%;">
	<div id="crop-select"></div>
</DIV>
<br>
<small></small><?=_rep($cleansrc,"%20"," ");?></small>
</center>
<br><br>&nbsp;
<script>
    $(function () {
      $('#crop-select').CropSelectJs({
        imageSrc: '<?=$cleansrc;?>',
        selectionResize: function(data) {
          $('#scaled-width').html(data.widthScaledToImage);
          $('#scaled-height').html(data.heightScaledToImage);
        },
        selectionMove: function(data) {
          $('#scaled-x').html(data.xScaledToImage);
          $('#scaled-y').html(data.yScaledToImage);
        }
      });
    });
</script>
</body>
</html>

<?
//----------- NEW FIMG -------------
function newFimg(){
global $movers,$dir,$file,$action,$gRoot,$showfimgs;
$fimgaction=myPost('fimgaction');
$file=myPost('file');
$noreload=myPost('noreload');
//--- validate URL ---
if($fimgaction=="update"){
 	$displayform=0;
 	if(empty($file)){
 		errorMsg("Please enter a URL");
 		$displayform=1;
 	}else{
  		$file=str_ireplace("https","http",$file);
  		if(!_in($file,"http://"))$file="http://".$file;
  		if(myFileType($file)!="image"){
  			errorMsg("The URL does not appear to be a valid IMAGE");
  			$displayform=1;
  		}
 	}
 	if($displayform==0)return;
}
$file=_rep($file,"http_","http://");
//$ndisplay=($showfimgs)?"":"NONE";
?>
	<FORM name="f1" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
	<input type=hidden value='newfimg' name=page>
	<input type=hidden value='update' name=fimgaction>
	<input type=hidden id='dir' name='dir' value='<?=$dir;?>'>
	<center>
	<br><br>
	<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
	<tr height=20><td width=120><b>HTTP&nbsp;IMAGE&nbsp;ADDRESS:</b>&nbsp;</td><td><input id=file name="file" style="display:block;width:80%;" value="<?=$file;?>"></td></tr>
	<tr height=20><td colspan=2 align=center><input class='btn' type="submit" value="  FETCH  " <?=$movers;?> style='width:100px;position:relative;top:10px;'></td></tr>
	</TABLE>
	</FORM>
	</center>
<?
exit(0);
}

?>