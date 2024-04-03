<?

// Copyright, 2005, Walter Long 


include_once "_inc_global.php";
include_once "_inc_header.php";

//----- login token ----
if($gLoggedIn)$updDisplay="";
else $updDisplay="DISPLAY:NONE;";


$play=rqst("play");  //takepic parms
$src=rqst("src");
if(!$src)$src="home.ixi";
$src=urldecode($src);
$src=_rep($src,"http_","http://");
$typ=myFileType($src);
$config="";
$configtext="";
switch($typ){
 	case "pixi"   :
  		$config=_rep($src,$pixiextension,"");
  		$configtext=myReadFile($dirlink.$config.$pixiextension);
  		if($configtext=="")$configtext="|||";
  		$configtext=_rep($configtext,'"','\"');
  		break;
 	case "video"  	: break;
 	case "link"   	: break;
 	case "file"   	: break;
 	case "image"  	: break;
 	default       		: 
		exit("Invalid src type=".$typ);
		break;
}
$filenames=array();
$OtherImages=array();
$ThumbImages=array();
$images=",";
$localimagefound=0;


//-------------------- FETCH FILES ---------------------------------------------
 $ix=0;
 $dir_handle=@opendir($dirpath) or die("Unable to open $dirpath");
 while($filename=readdir($dir_handle))$filenames[]=$filename;
 sort($filenames);
 foreach($filenames as $filename){
	  $file=$filename;
	  //if(!$gAdmin && _ix($file,"_pvt")==0)continue;
	  $ext=get_ext($file);
	  $name=getName($file);
	  if(!validFile($file))continue;
	  if(isImage($file) && (strstr($file,"_tn.")>-1)){ 
	   		$big_file=str_replace("_tn.",".",$file);
	   		$url=$gPath.$dirlink.$big_file;
	   		if(!_in($images,",".$url.","))$images.=$url.",";
	   		$localimagefound=1;
	   		$ix++;
 }	  }

if($typ=="image" && !_in($images,$src)){
 	$images.=$src.",";
}

if($images==",")$images.="/_pvt/_pvt_images/keepme.png,";


//-------------------------- load the fimgs ------------------------------------
function loadImagesFile(){
global $images,$fimgfile,$dirlink,$src;
$txt=myReadFile($dirlink.$fimgfile);
$a=explode(",",$txt);
for($i=0;$i<count($a);$i++){
 if($a[$i]=="")continue;
 $b=explode(";",$a[$i]);
 $fmgid=$b[0];
 $url=_rep($fmgid,"@fmg@","");
 if(!_in($images,",".$url.","))$images.=$url.",";
 //$images.=$url.",";
}}

?>
<SCRIPT>
window.onerror = function (errorMsg, url, lineNumber, column, errorObj) {
console.trace();
alert('Error: ' + errorMsg + ' Script: ' + url + ' Line: ' + lineNumber + ' Column: ' + column + ' StackTrace: ' +  errorObj);
}
</SCRIPT>
<!--
<link  href="_pvt_css/jquery-ui.css" rel="stylesheet" type="text/css" />
<link  href="_pvt_css/slider.css" rel="stylesheet" type="text/css" />
<link  href="_pvt_css/checkbox.css" rel="stylesheet" type="text/css" />
-->
<link  href="_pvt_css/_pixi.css" rel="stylesheet" type="text/css" />

<!-- 
<script src="_pvt_js/jquery-1.8.3.js"></script>
<script src="_pvt_js/jquery-ui.js"></script>
<script src="_pvt_js/simple-slider.js"></script>
<script src="_pvt_js/checkbox.js"></script>
<script src="_pvt_js/async.js"></script>
-->
<script src="_pvt_js/_pixi_jquery_etc.js"></script>

<script src='_pvt_js/_pixi.js'></script>
<script src='_pvt_js/_animation.js'></script>



<STYLE>
*    { margin: 0; padding: 0; }
td   {font-size:16px;}
hr   {position:relative;top:5px;left:-2px;}
.c_barbtn    {cursor:pointer;height:16px;width:16px;padding-left:0px;margin-left:2px;}
.c_btnbig    {cursor:pointer;height:20px;width:20px;padding-left:0px;margin-left:2px;}
.rotateBtn   {cursor:pointer;height:16px;width:16px;font-size:10px;position:absolute;overflow:hidden;
			  text-align:center;padding-left:1px;padding-right:1px;text-decoration:none;}
.rotatebtn:hover{background:#cccccc;}

.picdiv{					//---- the main pixi container ----
 resize:none;
 display:block;
 position:absolute;
 overflow:hidden;
 vertical-align:top;
 line-height:0px;
 z-index:2;
 left:0px; top:0px; width:120%; height:120%;
 box-sizing:border-box;
 -moz-box-sizing:border-box;
 -webkit-box-sizing:border-box;
 //transition: background-color 2.0s ease;
}

.framediv{ 						//--- a window/image FRAME container ----
 resize:none;
 display:block;
 position:absolute;
 top:0px; left:0px; width:100%; height:100%;
 overflow:hidden;
 vertical-align:top;
 line-height:0px;
 box-sizing:border-box;
 -moz-box-sizing:border-box;
 -webkit-box-sizing:border-box;
 //border:solid 1px red;
 //filter : inherit;
 //webkitFilter : inherit;
}

.scrdiv{ 						//--- a window/image INNER container ----
 resize:none;
 display:block;
 position:absolute;
 top:0px; left:0px; width:100%; height:100%;
 vertical-align:top;
 line-height:0px;
 box-sizing:border-box;
 -moz-box-sizing:border-box;
 -webkit-box-sizing:border-box;
 //overflow:hidden;
 //-webkit-transition: all 1s ease-in-out;
 //border:solid 1px blue;
 //box-shadow: 0px 0px 20px 4px #ff0000;
 //filter : inherit;
 //webkitFilter : inherit;
}

.boxdiv{ 						//--- a screen/box/panel container ----
 resize:none;
 display:block;
 position:absolute;
 top:0px; left:0px; width:100%; height:100%;
 overflow:visible;
 vertical-align:top;
 line-height:0px;
 box-sizing:border-box;
 -moz-box-sizing:border-box;
 -webkit-box-sizing:border-box;
 //-webkit-transition: all 1s ease-in-out;		// this could be uncommented on the fly for certain effects
 //border:solid 1px red;
 //box-shadow: 0px 0px 20px 4px #ffff00;
 //filter : inherit;
 //webkitFilter : inherit;

}

.textdiv{ 						//--- a screen/box/panel container ---- $$$$
 resize:none;
 display:block;
 position:absolute;
 top:0px; left:0px; width:100%; height:100%;
 overflow:hidden;
 font-size:100pt;
 vertical-align:center;
 text-align: center;
 border:solid 0px red;
 //filter : inherit;
 //webkitFilter : inherit;
}


.canvas{ 						//--- a single canvas ----
 display:block;
 position:absolute;
 top:0px; left:0px; width:100%; height:100%;
 overflow:visible;
 vertical-align:top;
 line-height:0px;
 box-sizing:border-box;
 -moz-box-sizing:border-box;
 -webkit-box-sizing:border-box;
 //border:solid 1px yellow;
 //filter : inherit;
 //webkitFilter : inherit;
}


.tmp{
 //outline:0px solid red;  //outlines do not take up space!
 //outline-offset:0px;
 //-webkit-transition: width 1s ease, height 1s ease, left 1s ease, top 1s ease;
 //-moz-transition: width 1s ease, height 1s ease, left 1s ease, top 1s ease;
 //-o-transition: width 1s ease, height 1s ease, left 1s ease, top 1s ease;
 //-ms-transition: width 1s ease, height 1s ease, left 1s ease, top 1s ease;
 //transition: width 1s ease, height 1s ease, left 1s ease, top 1s ease;
 //-webkit-transition: all 0.1s ease-in-out;
}


.txtdiv{
 resize:none;
 position:absolute;
 top:0px; left:0px; width:100%; height:100%;
 background:pink;
 color: black;
 text-align:center;
 font-size:150px;
 //-webkit-background-clip: text;
 //-moz-background-clip: text;   /* let's assume that one day it's supported */
 //background-clip: text;
 //background-position: 50% 50%;
 //background-size: 100%;
 //filter : inherit;
 //webkitFilter : inherit;
}
.popupcontrols { background:white; color:black; border:solid 1px black; }
.scrdiv-helper { border: 1px solid #F00; }
.icurrentele{position:absolute;top:23px; right:208px; width:52px; height:52px; cursor:pointer;}
.iviewimg  {position:absolute;top:3px; left:2px; width:49px; height:42px; border:solid 1px white; z-index:3; }
.rangetext {font-size:10pt;padding-left:4px;}
.modebtn   {width:51px;height:21px;}
.shadowfixdiv  { pointer-events:none; position:absolute; top:0px;    left:0px;  width:1px;  height:1px;  display:none; }
.shadowfixdivL { pointer-events:none; position:absolute; top:0px;    left:0px;  width:1px;  height:100%; background:red; }
.shadowfixdivT { pointer-events:none; position:absolute; top:0px;    left:0px;  width:100%; height:1px;  background:red; }
.shadowfixdivR { pointer-events:none; position:absolute; top:0px;    right:0px; width:1px;  height:100%; background:red; }
.shadowfixdivB { pointer-events:none; position:absolute; bottom:0px; left:0px;  width:100%; height:1px;  background:red; }
.simple-slider { width:120px;}
.c_toolbar     { }
.pixitb        { color:<?=$btncolor;?>;}
.mouseModeBtn  { background:#ffffff;border:solid 1px white;cursor:pointer;text-align:center;letter-spacing:1px; }
.resetbtn      { cursor:pointer; opacity:0.8; width:20px; height:20px;}
.expandgifbig  { width:10px;position:relative;top:0px;DISPLAY:NONE;}
.expandgif     { width:8px;position:relative;top:0px; DISPLAY:NONE;}
.dbgbtn 		{width:110px;}
.borderTop		{display:none;}
.borderBottom	{display:;border-bottom:solid 1px <?=$pixibordercolor;?>;}
.borderHilite	{display:;border-bottom:solid 1px red;}

.toolControlHdr			{ font-weight:bold; font-size:20px; letter-spacing:3px; cursor:pointer;}
.toolControlHdr:hover	{ color:#6699ff;  }
.toolControl			{ font-weight:bold; font-size:18px; letter-spacing:1px; cursor:pointer; }
.toolControl:hover		{ color:#6699ff;  }
.toolControlOff			{ font-weight:bold; font-size:18px; letter-spacing:1px; color:#888888;  }
.toolControlBold		{ font-weight:bold; font-size:18px; letter-spacing:1px; }
.xAxisBold				{ font-weight:bold; }
.xAxis					{  }
.fulterTxt			{ text-align:center; font-family:economica; width:52px;}
.fulterBtn			{ font-family:courier;font-size:14px;height:16px;border:solid 1px #ccc;padding-left:3px;cursor:pointer; }
</STYLE>


<SCRIPT>
//============================= GLOBAL VARIABLES ===============================
var gPixi=window;
var gSSType="";
var gConfigName="<?=$config;?>";
var gConfigText="<?=$configtext;?>";
var gSrc="<?=$src;?>";
var gImages=new Array();
var gImagesTxt="<?=$images;?>";
var gLocalImageFound=<?=$localimagefound;?>;
var gBgColor="<?=$bgcolor;?>";
var gDesktopBgColor="<?=$desktopbgcolor;?>";
var gTakepic="<?=$play;?>";
var gAddPixiMenu=null;

var gBlends=new Array('','hard-light','soft-light','luminosity','difference','exclusion','multiply','screen','overlay','darken','lighten','color-dodge','color-burn','difference','exclusion','hue','saturation','color');


//--- find the default pic ----
var gImgIx;
var ix=0,a=gImagesTxt.split(",");
for(var i=0;i<a.length;i++){
 if(a[i]){
  gImages[gImages.length]=a[i];
  if(a[i]==gSrc)gImgIx=ix;
  ix++;
}}
if(gImgIx==null)gImgIx=_rdm(0,gImages.length-1); 

</SCRIPT>

</HEAD>


<!-- ============================= BODY ==================================== -->

<BODY id=iBody onload="onLoad()" onresize='try{gToolbar.resize();gPic.repaintAllSizes();}catch(e){}'  onkeydown="gToolbar.fKey(1,event.keyCode,event);" style="font-family:economica; color:black; overflow:hidden; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -o-user-select: none; user-select: none;">
<?
$lightning="<img src='_pvt_images/link.png' onmouseover='_hiliteImg(this)' onmouseout='_loliteImg(this)' style='position:relative;top:2px;cursor:pointer;width:16px;padding:1px;border:solid 1px white;' ";
?>

<!-- ============== PICDIV ================= -->
<DIV id=picdiv class=picdiv style='background:transparent;'>


<!-- ----- Handles ----- -->
<DIV id=iBoxBdrTop    class='corners4' onmousedown='iDragBoxMouseDown(this,event)' style='display:none;overflow:hidden;position:absolute;left:0px;top:0px;height:26px;color:black;background:<?=$popupbgcolor;?>;z-index:9991; cursor:move;'>
<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
<tr>
<td width=4></td>
<? if($gLoggedIn){ ?>
 <td id='iEditBtn' style='width:30px;'><div  class='mouseModeBtn' style='position:relative;top:2px;' onmouseover='this.style.border="solid 1px #9999ff"' onmouseout='this.style.border="solid 1px white"' onclick='parent.editText()'>&nbsp;Edit&nbsp;</div></td>
 <? }else{ ?>
 <td id='iEditBtn' style='width:30px;'></td>
<? } ?>
<td>&nbsp;</td>
<td align=left>
</td>
<td align=right valign=bottom>
<TABLE cellpadding=0 cellspacing=0 style='position:absolute;top:2px;right:2px;background:<?=$popupbgcolor;?>;'><tr>
<td style='padding-left:2px;'><img class='c_barbtn corners4' src='_pvt_images/delete.png' onclick='gToolbar.showHandles(0)' style='width:20px;height:20px;cursor:pointer;position:relative;top:1px;' title='hide handles'>&nbsp;&nbsp;</td>
</tr></TABLE>
</td>
</tr></TABLE>
</DIV>

<!-- bounding box / handles -->
<DIV id=iBoxBdrRight  style='display:none;position:absolute;left:0px;top:0px;width:5px;  background:<?=$popupbgcolor;?>;z-index:9991;'></DIV>
<DIV id=iBoxBdrLeft   style='display:none;position:absolute;left:0px;top:0px;width:5px;  background:<?=$popupbgcolor;?>;z-index:9991;'></DIV>
<DIV id=iBoxBdrBottom style='display:none;position:absolute;left:0px;top:0px;height:5px; background:<?=$popupbgcolor;?>;z-index:9991;'></DIV>
<IMG id=iDragBtn      onmousedown='iDragBtnMouseDown(this,event)' style='display:none;position:absolute;left:0px;top:0px;height:26px; width:26px; z-index:9991; cursor:move;' src='_pvt_images/handle_resize.png'>


</DIV>  
<!-- ===== end picdiv ====== -->


<!-- ===== other floaters ====== -->
<DIV id=iDragCover style='display:none;position:absolute;left:0px;top:0px;height:100%;width:100%;z-index:9999;'></DIV>
<img id=iBusy class='corners8' onclick='gToolbar.showBusy()'  src='_pvt_images/busy.gif' style=' DISPLAY:none; z-index:999; cursor:pointer;position:absolute; top:50px; right:50px; background:white; '>


<!-- ========================================================== -->
<!-- ====================== menu controls ===================== -->
<DIV id=iMenuControlsDiv class='c_toolbar' style=' DISPLAY:NONE; z-index:9999; padding:4px; position:absolute; width:265px; height:100%; top:0px; right:0px; background:white; color:black; border-left:solid 1px #ccc;overflow-x:hidden;overflow-y:scroll;'>


<DIV id=iLayers style='overflow:hidden;background:#eee;position:absolute;top:3px;right:12px;width:242px;height:46px;border:solid 1px #ddd;'>
	<DIV id=iLayersBox style='position:absolute;top:0px;left:0px;'></DIV>
</DIV>

<img id=iLayersScrollLeft  src='_pvt_images/playleft.png' onclick='gToolbar.scrollLayers(-1);' style='position:absolute;top:19px;right:252px;width:16px;cursor:pointer;background:#ffffff;opacity:0.7; '>
<img id=iLayersScrollRight src='_pvt_images/play.png'     onclick='gToolbar.scrollLayers(1);'  style='position:absolute;top:19px;right:0px;  width:16px;cursor:pointer;background:#ffffff;opacity:0.7; '>

<DIV style='position:absolute;top:48px;right:0px;'>

	
	<DIV id=iCurrentEleID 	 class=icurrentele 	style=" DISPLAY:NONE; z-index:99999;" onclick="gToolbar.changeScreen();" title="current layer">
		<img id=iCurrentEle  src="" style="border:solid 0px black;width:100%;height:100%;">
	</DIV>
	<!-- <DIV id="iCurrentEleDiv" class=icurrentele 	style="z-index:99999; display:none;" onclick="gToolbar.changeScreen();" title="current layer"></DIV> -->
	<DIV id="iCurrentEleZndx" 					style=" DISPLAY:NONE; z-index:99999;position:absolute;top:-1px;right:0px;width:50px;text-align:center;font-family:monospace;font-size:10px;" title="z-index"></DIV>
	<DIV id="iCurrentEleDiv"  style="DISPLAY:NONE; border:solid 1px #999;position:absolute;top:38px;right:143px;width:72px;height:20px;text-align:center;font-family:monospace;font-size:10px;display:none;" title="current layer"></DIV>
	
	<img id=iLockZindexPadlock src="_pvt_images/Lock-Lock.png" onclick="gToolbar.flipLockZindex(null,1)" style="cursor:pointer;position:absolute;top:7px;right:230px;width:18px;" title="lock layer overlaps">
	<img id=iZindexF class='c_barbtn' onclick='gPic.changeZindex(null,1,event); gToolbar.configLayers();'  src='_pvt_images/move_forward.png' style='position:absolute;top:7px;right:198px;width:20px;height:20px;' title='bring forward'>
	<img id=iZindexB class='c_barbtn' onclick='gPic.changeZindex(null,-1,event);gToolbar.configLayers();' src='_pvt_images/move_backward.png' style='position:absolute;top:7px;right:168px;width:20px;height:20px;'  title='push back'>
	<img id=iShowHandles onclick='gToolbar.showHandles()' style='position:absolute;top:7px;right:137px;height:16px;width:18px; border:solid 1px #ea8;border-top:solid 4px #ea8; cursor:pointer;' src='_pvt_images/delete.png' title='show handles'>
	<img class='c_barbtn corners4' onclick='gToolbar.reset()' style='position:absolute;top:1px;right:97px;width:29px;height:29px;' src='_pvt_images/reset.png' title='reset layer'>
	<img id='iTrashcan' class='c_barbtn corners4' src='_pvt_images/trashcan.png' onclick='gPic.deleteScreen(gPic.scrix);gToolbar.syncControls();' style='position:absolute;top:7px;right:72px;cursor:pointer;width:18px;height:18px;' oncontextmenu='return false;' title='DELETE LAYER'>
	<img id='iNaturalSizeBtn' class='c_barbtn' onmousedown='gToolbar.flipNaturalSize()' src='_pvt_images/naturalOff.png' oncontextmenu='return false;' style='position:absolute;top:39px;right:134px;width:20px;height:20px;' title='natural size'></td>
	<img id=iFullScreen class='c_barbtn corners4' onclick='gToolbar.goFullscreen()' style='position:absolute;top:37px;right:98px;width:24px;height:24px;' src='_pvt_images/folders/toggle_collapse.png'>
	<img id=iHideImageEye src="_pvt_images/hideOn.png" onclick="gToolbar.hideImage()" style="cursor:pointer;position:absolute;top:39px;right:69px;width:18px;" title="hide image">
	<img id=iviewFrameColorImg2 class='c_barbtn' onclick="gPic.lastDesktopColor=gPic.desktopColor;CP.open(window,gPic.desktop,'frameColor',_getLeftClick(event)-95,_getTopClick(event)+10,gPic.setDesktopColor,0,gPic.desktopColor);"' src='_pvt_images/color.gif' style='position:absolute;top:36px;right:167px;width:24px;height:26px;' title='view background color'>
	
	<!-- @@@
	<img id=iPlayBack   src='_pvt_images/playleft.png' onclick='gPic.screen.nextImage(-1,null,1);gToolbar.viewImage();' style='position:absolute;top:54px;right:245px; width:14px; cursor:pointer;padding:2px;background:#ffffff;border:solid 0px #999999;opacity:0.7;'>
	<img id=iPlayPause src='_pvt_images/forward.png'  id=iPlayPauseBtn onclick='gPic.playPause()' style='position:absolute;top:50px; right:219px; width:21px;cursor:pointer;padding:2px;background:#ffffff;border:solid 0px #999999;opacity:0.7;' title='play/pause'>
	<img id=iPlayFwd    src='_pvt_images/play.png'  onclick='gPic.screen.nextImage(1,null,1);gPixi.gToolbar.viewImage();' style='position:absolute;top:54px;right:202px; width:14px;cursor:pointer;padding:2px;background:#ffffff;border:solid 0px #999999;opacity:0.7; '>
	<img id='' class='c_barbtn corners4' onmousedown='gPic.screen.history.StartStop(1)'  style='position:absolute;top:8px;right:186px;cursor:pointer;opacity:0.8;' src='_pvt_images/play.png' oncontextmenu='return false;' title='replay history'>
	<img style="DISPLAY:NONE;" id=iTakeLayerPic class='c_barbtn corners4' src='_pvt_images/camera.png' style='width:22px;height:22px;border:solid 0px #aaa;position:absolute;top:4px;right:156px;' onclick='parent.takeLayerPic()' style='position:relative;top:0px;cursor:pointer;width:22px;height:22px;border:solid 1px #aaa;' oncontextmenu='return false;' title='capture layer image'>
	-->

	<DIV id=iTurnBtns style='position:absolute;top:0px;right:0px;width:60px;height:59px;border:solid 0px gray;'>
	<input type=button class='rotateBtn'  value='<' onclick='gToolbar.fKey(null,37,event,"turn")' style='top:22px;left:4px;;'>
	<input type=button class='rotateBtn'  value='>' onclick='gToolbar.fKey(null,39,event,"turn")' style='top:22px;left:36px;'>
	<input type=button class='rotateBtn'  value='^' onclick='gToolbar.fKey(null,38,event,"turn")' style='top:6px;left:20px;font-size:13px;'>
	<input type=button class='rotateBtn'  value='v' onclick='gToolbar.fKey(null,40,event,"turn")' style='top:38px;left:20px;'>
	<input type=button class='rotateBtn'  value='O' onclick='gToolbar.quickReset()' style='top:22px;left:20px;'>
	</DIV>

</DIV>



<!--
<div style='position:absolute;top:53px;right:128px;font-size:14px;'>
<input id=iDopple type="checkbox" onchange="gToolbar.viewFlipDopple(this.checked)" style="position:relative;top:3px;">&nbsp;fade&nbsp;images
</div>
-->

<!-- @@@
<img class='c_barbtn' onclick='feedNbrs()' src='_pvt_images/feed.png' style='position:absolute;top:31px;right:151px;width:20px;height:20px;cursor:pointer;'  title='scramble'>
-->


<!-- ================================================  SLIDERS =========================================================== -->
<div style='height:94px;'></div>



<TABLE id=iMenuControls class='c_toolbar' cellspacing=0 cellpadding=0 border=0 style='width:258px; color:black;'>

<tr><td colspan=2 style='height:13px;'></td></tr>






<!-- ================== COLORS ================ -->
<tr id=iColorsHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iColorsTxt class=toolcontrolHdr onclick="gToolbar.expandColorsTools()">&nbsp;Colors</span></div></td>
	<td align=right>
		<IMG onclick='window.open("help.php#colors")' style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png'>
		&nbsp;
		<img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetColors()">
	</td>
</tr>
<tr><td id=iColorsBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iColorsTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:15px;'>
<tr><td colspan=2 style='height:2px;'></td></tr>



<!-- ------------- Blend ----------------->
<tr id=iBlendHdr><td colspan=2>
<TABLE id=iBlendHdrTable cellspacing=0 cellpadding=0 style='width:100%;'>
<tr><td colspan=2>
<TABLE cellpadding=0 cellspacing=0 style="width:100%;">
<tr>
	<td width=100 class=toolControl onclick="gToolbar.expandBox('iBlend')">&nbsp;&nbsp;&nbsp;&nbsp;Blend</td>
	<td><input id=iBlend type="text" data-slider="true" data-slider-range="0,15" value="0" data-slider-step="1" onchange="gToolbar.setBlend(this.value)" /></td>
	<td><?=$lightning;?> onclick="gToolbar.spin45('iBlend',1,16)"></td>
	<td><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.resetBlend()' title="cancel blend"></td>
</tr>
<tr>
	<td width=80 style="height:0px;"></td>
	<td id=iBlendTable colspan=3 style="display:none;">
		<div id=iBlendName class=xAxis style="width:95px;padding-left:5px;height:20px;border:solid 1px #ccc;">none</div>
		<div style="height:3px;"></div>
	</td>
</tr>
</TABLE>
</td></tr>
</TABLE>
</td></tr>

<tr><td colspan=2 style='height:5px;'></td></tr>


<!-- ------------- Colors ------------->
<tr id=iColorHdr><td colspan=2>
<TABLE id=iColorHdrTable cellspacing=0 cellpadding=0 style='width:100%;'>
<tr><td colspan=2>
<DIV style="height:20px;">
	<TABLE cellpadding=0 cellspacing=0 style="width:100%;">
		<tr>
		<td width=100 class=toolControl onclick="gToolbar.expandBox('iColor')">&nbsp;&nbsp;&nbsp;&nbsp;Color</td>
		<td><input id=fil_sat type="text" data-slider="true" data-slider-range="0,500" value="100" data-slider-step="1" onchange="gToolbar.setVars('saturate',this.value,0,1)" /></td>
		<td></td>
		<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.resetCSSFilters()' title="reset all color filters"></td>
		</tr>
	</TABLE>
</DIV>
</td></tr>
<tr id=iColorTable style='display:none;'><td colspan=2>
   <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
   <tr><td width=30></td>	<td width=70>Brightness</td>			<td><input id=fil_bri type="text" data-slider="true" data-slider-range="30,500" value="100" data-slider-step="1" onchange="gToolbar.setVars('brightness',this.value,0,1)" /></td>	<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_bri',100,180,280,300,500)"></td>	</tr>
   <tr><td></td>			<td>Contrast</td>						<td><input id=fil_con  type="text" data-slider="true" data-slider-range="30,500" value="100" data-slider-step="1" onchange="gToolbar.setVars('contrast',this.value,0,1)" /></td>	<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_con',100,200,400)"></td>	</tr>
   <tr><td></td>			<td>Blur</td>							<td><input id=fil_blu  type="text" data-slider="true" data-slider-range="0,20" value="0" data-slider-step="1" onchange="gToolbar.setVars('blur',this.value,0,1)" /></td>			<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_blu',0,2,4)"></td>		</tr>
   <tr><td></td>			<td>Hue</td>							<td><input id=fil_hue  type="text" data-slider="true" data-slider-range="0,360" value="0" data-slider-step="1" onchange="gToolbar.setVars('hue-rotate',this.value,0,1)" /></td>		<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_hue',0,90,180,270)"></td>	</tr>
   <tr><td></td>			<td>Sepia</td>							<td><input id=fil_sep  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.setVars('sepia',this.value,0,1)" /></td>			<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_sep',0,50,100)"></td>	</tr>
   <tr><td></td>			<td>Grayscale</td>						<td><input id=fil_gra  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.setVars('grayscale',this.value,0,1)" /></td>		<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_gra',0,50,100)"></td>	</tr>
   <tr><td></td>			<td>Invert</td>							<td><input id=fil_inv  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.setVars('invert',this.value,0,100)" /></td>		<td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'fil_inv',0,20,80,100)"></td>	</tr>
   <tr><td></td>			<td>Opacity</td>						<td><input id=opcXY type="text" data-slider="true" data-slider-range="0,1" value="1" data-slider-step="0.1" onchange="gToolbar.setVars('opacity2',this.value,0,1)" /></td>			<td><?=$lightning;?> onclick="gToolbar.spinSliderBkwrds(event,'opcXY',1,0.6,0.3)"></td></tr>
	<tr><td style='height:6px;' colspan=4></td></tr>
	<tr id='iDominantBg'><td></td>			<td colspan=3>Set window to dominant color&nbsp;&nbsp;<input id=iDominantColor type=checkbox onchange="gToolbar.flipDominantColor(this.checked)" style="cursor:pointer;position:relative;top:1px;"></td></tr>
	<tr><td style='height:4px;' colspan=4></td></tr>
   </TABLE>
</td></tr>
<tr><td colspan=2 style='height:0px;'></td></tr>
</TABLE>
</td></tr>

<tr><td colspan=2 style='height:5px;'></td></tr>


<!--- START PIXELHDR --->
<tr id=iPixelHdr><td colspan=2> 
	<TABLE cellspacing=0 cellpadding=0 border=0 style='width:100%;'>
	
		<!-- ----------------- Mask ----------------------->
		<tr id=iMaskHdr><td colspan=2>
		
			<TABLE id=iMaskHdrTable cellspacing=0 cellpadding=0 style='width:100%;'>
			<tr><td colspan=2>
				<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick="gToolbar.expandBox('iMask')">Mask</td>
					<td width=24><input id=mskON onchange="gToolbar.flipMask()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetMask()"></td>
				</tr>
				</TABLE>
			</td></tr>
			<tr id=iMaskTable style='display:none;'><td colspan=2>
				 <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				 <tr id=imasktr2>
				 <td colspan=4>
					  <TABLE cellpadding=0 cellspacing=0>
						  <tr>
						  <td width=28></td>
						  <td>Start&nbsp;</td> 
						  <td width=41></td>
						  <td><input id=mskStart type="text" data-slider="true" data-slider-range="-25,100" value="40" data-slider-step="0.2" onchange="gToolbar.chgMask()" /><td>
						  <td width=6></td>
						  <td><?=$lightning;?> onclick="gToolbar.spin1('mskStart',0.2)"></td>
						  </tr>
						  
						  <tr>
						  <td width=28></td>
						  <td>Blur&nbsp;</td> 
						  <td width=41></td>
						  <td><input id=mskBlur type="text" data-slider="true" data-slider-range="0,150" value="20" data-slider-step="0.2" onchange="gToolbar.chgMask()" /><td>
						  <td width=6></td>
						  <td><?=$lightning;?> onclick="gToolbar.spin1('mskBlur',0.2)"></td>
						  </tr>
						  
					  </TABLE>
				 </td></tr>
				 <tr><td colspan=4>
					  <TABLE cellpadding=0 cellspacing=0>
					  <tr>
						  <td width=30></td>
						  <td width=49>Type:&nbsp;</td>
						  <td><div class="radio" id="box-mskT1"><input id=mskT1 value=1 onchange="gToolbar.chgMask()" name=mskType type=radio></div></td><td><label for="mskT1">Linear&nbsp;&nbsp;</label></td>
						  <td><div class="radio" id="box-mskT2"><input id=mskT2 value=2 onchange="gToolbar.chgMask()" name=mskType type=radio></div></td><td><label for="mskT2">Radial&nbsp;&nbsp;</label></td>
						  <td><div class="radio" id="box-mskT3"><input id=mskT3 value=3 onchange="gToolbar.chgMask()" name=mskType type=radio></div></td><td><label for="mskT3">Edge&nbsp;&nbsp;</label></td>
					  </tr>
					  </TABLE>
				 </td></tr>
				 <tr id=imasktr1>
				 <td colspan=4>
				  	  <TABLE cellpadding=0 cellspacing=0>
					  <tr>
						  <td width=30></td>
						  <td>Direction:&nbsp;</td>
						  <td><div class="radio" id="box-mskD0" checked><input id=mskD0 value=0 onchange="gToolbar.chgMask()" name=mskDir type=radio checked></div></td><td><label for="mskD0"></label></td>
						  <td style='position:relative;top:-2px;font-family:monospace;font-size:20px;'>>&nbsp;</td>
						  <td><div class="radio" id="box-mskD1">		<input id=mskD1 value=1 onchange="gToolbar.chgMask()" name=mskDir type=radio></div></td>		<td><label for="mskD1"></label></td>
						  <td id="box-mskD1-label" style='position:relative;top:-2px;font-family:monospace;font-size:20px;'>v&nbsp;</td>
						  <td>
							  <TABLE id="mskDirs" cellpadding=0 cellspacing=0>
							  <tr>
								  <td><div class="radio" id="box-mskD2">		<input id=mskD2 value=2 onchange="gToolbar.chgMask()" name=mskDir type=radio></div></td>		<td><label for="mskD2"></label></td>
								  <td id="box-mskD2-label" style='position:relative;top:-2px;font-family:monospace;font-size:20px;'><&nbsp;</td>
								  <td><div class="radio" id="box-mskD3">		<input id=mskD3 value=3 onchange="gToolbar.chgMask()" name=mskDir type=radio></div></td>		<td><label for="mskD3"></label></td>
								  <td id="box-mskD3-label" style='position:relative;top:2px;font-family:monospace;font-size:26px;'>^&nbsp;</td>
							  </tr>
							  </TABLE>
						  </td>
					  </tr>
					  </TABLE>
				 </td></tr>
				 <tr style="height:26px;">
				 <td width=30></td><td colspan=3>Blur Style:</td></tr>
				 <tr><td colspan=4>
				  	  <TABLE cellpadding=0 cellspacing=0 style="width:100%;">
				 		<tr><td width=50></td>	<td>Solid?&nbsp;</td>	<td><input id=mskSolid onchange="gToolbar.chgMask()" type="checkbox">&nbsp;</td><td><input id=mskSolidAlpha type="text" data-slider="true" data-slider-range="0,255" value="128" data-slider-step="1" onchange="gToolbar.chgMask()" /></td></tr>
				 		<tr><td width=50></td>	<td>Red			</td>	<td></td> <td><input id=mskRed   type="text" data-slider="true" data-slider-range="-255,255" value="0" data-slider-step="1" onchange="gToolbar.chgMask()" /></td></tr>
				 		<tr><td width=50></td>	<td>Green		</td>	<td></td> <td><input id=mskGreen type="text" data-slider="true" data-slider-range="-255,255" value="0" data-slider-step="1" onchange="gToolbar.chgMask()" /></td></tr>
				 		<tr><td width=50></td>	<td>Blue		</td>	<td></td> <td><input id=mskBlue  type="text" data-slider="true" data-slider-range="-255,255" value="0" data-slider-step="1" onchange="gToolbar.chgMask()" /></td></tr>
				 	  </TABLE>
				 </td></tr>
				 </TABLE>
			</td></tr>
			</TABLE>
		</td></tr>
	
		<tr><td colspan=2 style='height:5px;'></td></tr>
		
		<!-- --------- Posterize -------------->
		<tr id=iPosterizeHdr><td colspan=2>
			<TABLE id=iPosterizeHdrTable cellspacing=0 cellpadding=0 style='width:100%;'>
			<tr><td>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick="gToolbar.expandBox('iPosterize')">Posterize</td>
					<td width=24><input id=iPosterizeOn onchange="gToolbar.flipPosterize()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right><img class=resetbtn src='_pvt_images/reset.png' style="position:relative;top:2px;" onclick="gToolbar.resetPosterize()"></td>
				</tr>
				</TABLE>
			</td></tr>
			<tr><td><div style='height:3px;overflow:hidden;'></div></td></tr>
			<tr id=iPosterizeTable style='display:none;'><td>
			 	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
					<tr><td width=40></td><td class=xAxis>Radius</td><td width=20></td><td><input id=iPosterizeRadius type="text" data-slider="true" data-slider-range="2,200" value="64" data-slider-step="1" onchange="gToolbar.chgPosterize(1)" /></td></tr>
					<!-- intensity behaves exactly like 'brightness' so not needed -->
					<tr style="DISPLAY:NONE;"><td width=40></td><td class=xAxis>Intensity</td><td width=20></td><td><input id=iPosterizeIntensity type="text" data-slider="true" data-slider-range="2,200" value="64" data-slider-step="1" onchange="gToolbar.chgPosterize(0)" /></td></tr>
				</TABLE>
			</td></tr>
			</TABLE>
		</td></tr>
		
		<tr><td colspan=2 style='height:5px;'></td></tr>
		
		<!-- --------- ColorRgb -------------->
		<tr id=iColorRgbHdr><td colspan=2>
		  	<TABLE id=iColorRgbHdrTable cellpadding=0 cellspacing=0 style="width:100%;">
			<tr><td>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick='gToolbar.expandBox("iColorRgb")'>RGB Colors</td>
					<td width=24><input id=iColorRgbOn onchange="gToolbar.flipColorRgb()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.resetColorRgb()'></td>
				</tr>
				</TABLE>
			</td></tr>
			<tr><td><div style='height:3px;overflow:hidden;'></div></td></tr>
			<tr id='iColorRgbTable' style='display:none;'><td>
			  	<TABLE cellpadding=0 cellspacing=0>
			 		<tr><td width=40></td><td class=xAxis width=60>Red</td>		<td><input id=colorRgbRed   type="text" data-slider="true" data-slider-range="-255,255" value="0" data-slider-step="1" onchange="gToolbar.chgColorRgb()" /></td></tr>
			 		<tr><td width=40></td><td class=xAxis width=60>Green</td>	<td><input id=colorRgbGreen type="text" data-slider="true" data-slider-range="-255,255" value="0" data-slider-step="1" onchange="gToolbar.chgColorRgb()" /></td></tr>
			 		<tr><td width=40></td><td class=xAxis width=60>Blue</td>	<td><input id=colorRgbBlue  type="text" data-slider="true" data-slider-range="-255,255" value="0" data-slider-step="1" onchange="gToolbar.chgColorRgb()" /></td></tr>
			 	</TABLE>
			</td></tr>
		 	</TABLE>
		</td></tr>
		
		<tr><td colspan=2 style='height:5px;'></td></tr>
	
		<!-- --------- swapPixels -------------->
		<tr id=iSwapPixelsHdr>
			<td colspan=2>
			  	<TABLE id=iSwapPixelsHdrTable cellpadding=0 cellspacing=0 style="width:100%;">
				<tr><td style='height:2px;'></td></tr>
				<tr><td>
					<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
					<tr>
						<td width=18></td>
						<td width=84 class=toolControl onclick='gToolbar.expandBox("iSwapPixels")'>Swap&nbsp;Pixels</span></td>
						<td width=24><input id=iSwapPixelsOn onchange="gToolbar.flipSwapPixels()" type=checkbox style="position:relative;top:2px;"></td>
						<td align=right>
							<IMG onclick='window.open("help.php#swappixels")' style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png'>
							&nbsp;
							<img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.resetSwapPixels(1)'>
						</td>
					</tr>
					</TABLE>
				</td></tr>
				<tr><td><div style='height:3px;overflow:hidden;'></div></td></tr>
				<tr id='iSwapPixelsTable' style='display:none;'><td>
				  	<TABLE cellpadding=0 cellspacing=0>
				 		<tr><td width=40></td><td class=xAxis colspan=3>
							Include:
							&nbsp;Red&nbsp;<input id=iSwapPixelsRed onchange="gToolbar.chgSwapPixels()" type=checkbox style="position:relative;top:2px;" checked>
							&nbsp;Green&nbsp;<input id=iSwapPixelsGreen onchange="gToolbar.chgSwapPixels()" type=checkbox style="position:relative;top:2px;">
							&nbsp;Blue&nbsp;<input id=iSwapPixelsBlue onchange="gToolbar.chgSwapPixels()" type=checkbox style="position:relative;top:2px;">
						</td></tr>
				 	</TABLE>
				</td></tr>
				<tr><td><div style='height:2px;overflow:hidden;'></div></td></tr>
			 	</TABLE>
				<div style='height:5px;'></div>
			</td>
		</tr>

	
		<!-- --------- swapColors -------------->
		<tr id=iSwapColorsHdr><td colspan=2>
		  	<TABLE id=iSwapColorsHdrTable cellpadding=0 cellspacing=0 style="width:100%;">
			<tr><td>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick='gToolbar.expandBox("iSwapColors")'>Swap Colors</td>
					<td width=24><input id=iSwapCsOn onchange="gToolbar.flipSwapColors()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right>
						<IMG style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png' onclick='window.open("help.php#swapcolors")'>
						&nbsp;
						<img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.resetSwapColors(1)'>
					</td>
				</tr>
				</TABLE>
			</td></tr>
			<tr><td><div style='height:3px;overflow:hidden;'></div></td></tr>
			<tr id='iSwapColorsTable' style='display:none;'><td>
			  	<TABLE cellpadding=0 cellspacing=0>
			 		<tr><td colspan=4>
			  			<TABLE cellpadding=0 cellspacing=0>
						<tr>
							<td width=40></td>
							<td class=xAxis>From:
								<img id="iPicker1" src="_pvt_images/colorpicker.png" onclick="gToolbar.openSwapPicker(1)" ondblclick="gPic.openColorPicker('iSwapColors1')" style="cursor:pointer;width:20px;height:16px;border:solid 2px white;">
								<img src="_pvt_images/color.gif" onclick="CP.open(window,_obj('iSwapSample1'),'backgroundColor',_getLeftClick(event)-60,_getTopClick(event)-165,gToolbar.setSwapColor1,1,_obj('iSwapColors1').value)" style='cursor:pointer;position:relative;left:-2px;'>
								<br><input id="iSwapColors1" type=text style='width:72px;'></td>
							<td class=xAxis>&nbsp;&nbsp;To:
								<img id="iPicker2" src="_pvt_images/colorpicker.png" onclick="gToolbar.openSwapPicker(2)" ondblclick="gPic.openColorPicker('iSwapColors2')" style="cursor:pointer;width:20px;height:16px;border:solid 2px white;">
								<img src="_pvt_images/color.gif" onclick="CP.open(window,_obj('iSwapSample2'),'backgroundColor',_getLeftClick(event)-60,_getTopClick(event)-165,gToolbar.setSwapColor2,1,_obj('iSwapColors2').value);" style='cursor:pointer;position:relative;left:-2px;'>
								<br>&nbsp;&nbsp;<input id="iSwapColors2" type=text  style='width:62px;'>&nbsp;</td>
							<td class=xAxis>&nbsp;<input type=button onclick="gToolbar.chgSwapColors(1)" value=" Apply " style='width:45px;position:relative;top:12px;'>
							</td>
						</tr><tr>
							<td width=40></td>
							<td><div id="iSwapSample1" style="height:12px;overflow:hidden;border:solid 2px #fff;">&nbsp;</div></td>
							<td><div id="iSwapSample2" style="height:12px;overflow:hidden;border:solid 2px #fff;border-left:solid 6px #fff;"></div></td>
							<td></td>
						</tr>
						</TABLE>
					</td></tr>
			 		<tr><td width=40></td><td class=xAxis width=60>Opacity</td><td><input id=iSwapColorsAlpha type="text" data-slider="true" data-slider-range="0,255" value="255" data-slider-step="1" onchange="gToolbar.chgSwapColors()" /></td><td></td></tr>
			 		<tr><td width=40></td><td class=xAxis width=60>Tolerance</td><td><input id=iSwapColorsTolerance type="text" data-slider="true" data-slider-range="0,255" value="10" data-slider-step="1" onchange="gToolbar.chgSwapColors()" /></td><td></td></tr>

					<!--  swap colors list -->
					<tr><td colspan=4 style='height:9px;'></td></tr>
					<tr><td colspan=4>
					  	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
							<tr><td style='width:20px;'></td>
								<td style='text-align:center;'><input type=button value='add a new color swap' onclick='gToolbar.addNewSwapCs()'></td>
							</tr>
							<tr><td></td>
								<td>
								<TABLE style='width:100%;' cellpadding=0 cellspacing=0>
								<tr><td style="height:8px;"></td></tr>
								<tr><td style='border-bottom:solid 1px #666;'></td></tr>
								<tr><td><DIV id='iSwapCsList'></DIV></td></tr>
								<tr><td style='border-bottom:solid 1px #666;'></td></tr>
								<tr><td style="height:14px;"></td></tr>
								</TABLE>
								</td>
							</tr>
						</TABLE>
					</td></tr>
			 	</TABLE>
			</td></tr>
		 	</TABLE>
		</td></tr>
		
		<tr><td colspan=2><div style='height:4px;'></div></td></tr>

		
		<!-- --------- OilPaint -------------->
		<tr id=iOilPaintHdr><td colspan=2>
			<TABLE id=iOilPaintHdrTable cellspacing=0 cellpadding=0 style='width:100%;'>
			<tr><td>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick="gToolbar.expandBox('iOilPaint')">Brush</td>
					<td width=24><input id=iOilPaintOn onchange="gToolbar.flipOilPaint()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right><img class=resetbtn src='_pvt_images/reset.png' style="position:relative;top:2px;" onclick="gToolbar.resetOilPaint()"></td>
				</tr>
				</TABLE>
			</td></tr>
			<tr><td><div style='height:3px;overflow:hidden;'></div></td></tr>
			<tr id=iOilPaintTable style='display:none;'><td>
			 	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
					<tr><td width=40></td><td class=xAxis>Radius</td><td width=20></td><td><input id=iOilPaintRadius type="text" data-slider="true" data-slider-range="2,10" value="4" data-slider-step="1" onchange="gToolbar.chgOilPaint()" /></td></tr>
					<tr><td width=40></td><td class=xAxis>Intensity</td><td width=20></td><td><input id=iOilPaintIntensity type="text" data-slider="true" data-slider-range="1,200" value="55" data-slider-step="1" onchange="gToolbar.chgOilPaint()" /></td></tr>
				</TABLE>
			</td></tr>
			</TABLE>
		</td></tr>
		
		<tr><td colspan=2><div style='height:4px;'></div></td></tr>
		
		<!-- --------- Fulters -------------->
		<tr id=iFulterHdr><td colspan=2>
			<TABLE id=iFulterHdrTable cellspacing=0 cellpadding=0 style='width:100%;'>
			<tr><td colspan=2>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick="gToolbar.expandBox('iFulter')">Sharpen</td>
					<td width=24><input id=FultersOn onchange="gToolbar.flipFulters()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right><img class=resetbtn src='_pvt_images/reset.png' style="position:relative;top:2px;" onclick="gToolbar.resetFulters()"></td>
				</tr>
				</TABLE>
			</td></tr>
			<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
			<tr id=iFulterTable style='display:none;'><td colspan=2>
			 	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
				<tr>
				<td width=22></td>
				<td width=30 valign=top>
					<div style="position:relative;top:0px;">
					<div class="fulterBtn" onmouseover="_hilite(this)" onmouseout="_lolite(this)" onclick="gToolbar.loadFulterArray('sharpen')">sharpen</div>
					<div class="fulterBtn" onmouseover="_hilite(this)" onmouseout="_lolite(this)" onclick="gToolbar.loadFulterArray('emboss')" >emboss</div>
					<div class="fulterBtn" onmouseover="_hilite(this)" onmouseout="_lolite(this)" onclick="gToolbar.loadFulterArray('glarify')" >glarify</div>
					<div class="fulterBtn" onmouseover="_hilite(this)" onmouseout="_lolite(this)" onclick="gToolbar.loadFulterName('sobel')" >sobel</div>
					</div>
				</td>
				<td>&nbsp;</td>
				<td align=center>
			    	<div id="customMatrix">
			          <input id="iFult0" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="0">
			          <input id="iFult1" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="-1">
			          <input id="iFult2" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="0">
			          <br>
			          <input id="iFult3" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="-1">
			          <input id="iFult4" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="5">
			          <input id="iFult5" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="-1">
			          <br>
			          <input id="iFult6" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="0">
			          <input id="iFult7" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="-1">
			          <input id="iFult8" class="fulterTxt" onchange="gToolbar.applyFulterAry(0,1)" type="number" step="0.2" value="0">
			    	</div>
				</td>
				</tr>
				<tr>
				<td colspan=4><div style='height:2px;overflow:hidden;'></div></td>
				</tr>
				<tr>
				<td colspan=4>
					<TABLE cellspacing=0 cellpadding=0>
					<tr>
						<td width=22></td>
						<td>Add&nbsp;Mode:&nbsp;</td>
						<td><input id=fultersAddMode type=checkbox style="position:relative;top:2px;"></td>
						<td width=49>&nbsp;</td>
						<td><div class="fulterBtn" onclick="gToolbar.applyFulterAry(null,0)"    onmouseover="_hilite(this)" onmouseout="_lolite(this)" style="position:relative;top:0px;">&nbsp;Apply&nbsp;</div></td>
					</tr>
					</TABLE>
				</td>
				</tr>
				<tr>
				<td colspan=4 style="height:12px;"></td>
				</tr>
				</TABLE>
			</td></tr>
			</TABLE>
		</td></tr>

		<tr><td colspan=2><div style='height:4px;'></div></td></tr>
	
		<!-- --------- FillColor -------------->
		<tr id=iFillColorHdr><td colspan=2>
		  	<TABLE id=iFillColorHdrTable cellpadding=0 cellspacing=0 style="width:100%;">
			<tr><td>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr>
					<td width=18></td>
					<td width=84 class=toolControl onclick='gToolbar.expandBox("iFillColor")'>Color Fill</td>
					<td width=24><input id=iFillColorOn onchange="gToolbar.flipFillColor()" type=checkbox style="position:relative;top:2px;"></td>
					<td align=right>
						<IMG style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png' onclick='window.open("help.php#fillcolor")'>
						&nbsp;
						<img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.resetFillColor(1)'>
					</td>
				</tr>
				</TABLE>
			</td></tr>
			<tr><td><div style='height:3px;overflow:hidden;'></div></td></tr>
			<tr id='iFillColorTable' style='display:none;'><td>
			  	<TABLE cellpadding=0 cellspacing=0>
			 		<tr><td colspan=3>
			  			<TABLE cellpadding=0 cellspacing=0 style="width:100%;">
						<tr>
							<td width=40></td>
							<td width=32>
								<img id="iFillPicker" src="_pvt_images/colorpicker.png" onclick="gToolbar.openFillPicker()" style="cursor:pointer;width:20px;height:16px;border:solid 2px white;">
							</td>
							<td width=60>
								<div id=iFillXY class=xAxis style="">10,10</div>
							</td>
							<td width=22>
								<img src="_pvt_images/color.gif" onclick="CP.open(window,_obj('iFillSample2'),'backgroundColor',_getLeftClick(event)-60,_getTopClick(event)-165,gToolbar.updFillColor,1,_obj('iFillSample2').style.backgroundColor,gToolbar.chgFillColor);" style='cursor:pointer;position:relative;left:-2px;'>
							</td>
							<td>
								<div id="iFillSample2" style="background:#ff9977;height:16px;width:50px;"></div>
							</td>
						</tr>
						</TABLE>
					</td></tr>
			 		<tr><td colspan=3 style="height:4px;"></td></tr>
					<!--
			 		<tr><td width=40></td><td class=xAxis width=60>Opacity</td><td><input id=iFillColorAlpha type="text" data-slider="true" data-slider-range="0,255" value="255" data-slider-step="1" onchange="gToolbar.updFillColor();gToolbar.hiliteFCbtn(1)" /></td></tr>
			 		<tr><td width=40></td><td class=xAxis width=60>Tolerance</td><td><input id=iFillColorTolerance type="text" data-slider="true" data-slider-range="0,200" value="15" data-slider-step="1" onchange="gToolbar.updFillColor();gToolbar.hiliteFCbtn(1)" /></td></tr>
			 		<tr><td width=40></td><td></td><td align=center><input id=iFCbtn type=button value='  Apply  ' onclick='gToolbar.flipFillColor(1)' style='color:#999;background:#ddd;border:solid 1px #aaa;cursor:pointer;' /></td></tr>
					-->
			 		<tr><td width=40></td><td class=xAxis width=60>Opacity</td><td><input id=iFillColorAlpha type="text" data-slider="true" data-slider-range="0,255" value="255" data-slider-step="1" onchange="gToolbar.updFillColor();gPic.screen.applyFillColor()" /></td></tr>
			 		<tr><td width=40></td><td class=xAxis width=60>Tolerance</td><td><input id=iFillColorTolerance type="text" data-slider="true" data-slider-range="0,200" value="15" data-slider-step="1" onchange="gToolbar.updFillColor();gPic.screen.applyFillColor()" /></td></tr>
			 		<tr><td colspan=3 style="height:6px;"></td></tr>
				</TABLE>
			</td></tr>
		 	</TABLE>
		</td></tr>
		<tr><td colspan=2 style='height:8px;'></td></tr>
		
	</TABLE>
</td></tr>	<!--- END PIXELHDR --->

	
<tr><td style='height:6px;' colspan=2></td></tr>


<tr><td colspan=2 style='height:20px;'></td></tr>

</TABLE>
</td></tr>

<tr><td id=iColorsBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>
</TABLE>
</td></tr>



<!-- ================================= IMAGE ==================================== -->
<tr id=iPicHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 border=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iPicTxt class=toolcontrolHdr onclick="gToolbar.expandPicTools();">&nbsp;Image</span></div></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetPic(1)"></td>
</tr>
<tr><td id=iPicBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iPicTools' cellspacing=0 cellpadding=0 border=0 style='width:100%;position:relative;top:10px;'>
<tr><td colspan=2 style='height:6px;'></td></tr>


<!-- --------------------------- Image -[Image]----------------------------->
<tr id=iImageHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr><td colspan=2>
<div style="height:20px;">
  <TABLE cellspacing=0 cellpadding=0 style='width:100%;'><tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iImageTable'));">&nbsp;Image</td>
   <td><input id=imgXY type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('image',this.value,this.value);" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.setVars('image',50,50);gToolbar.syncControls();" ></td>
  </tr></TABLE>
</div>
</td></tr>
<tr id=iImageTable style='display:none;'><td colspan=2>
 <TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
  <tr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
 	<tr><td width=30></td><td width=70  class="xAxis">Horizontal</td>  <td><input id=imgX  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('image',this.value,null)" /></td></tr>
  	<tr><td></td><td class="xAxis">Vertical&nbsp;&nbsp;&nbsp;</td>  <td><input id=imgY  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('image',null,this.value)" /></td></tr>
  	<tr style="DISPLAY:NONE;"><td></td><td class="xAxis">&nbsp;&nbsp;Width&nbsp;</td>   <td><input id=strX  type="text" data-slider="true" data-slider-range="0,100" value="50" data-slider-step="1" onchange="gToolbar.setVars('stretch',this.value,null)" /></td></tr>
  	<tr style="DISPLAY:NONE;"><td></td><td class="xAxis">&nbsp;&nbsp;Height&nbsp;</td>   <td><input id=strY  type="text" data-slider="true" data-slider-range="0,100" value="50" data-slider-step="1" onchange="gToolbar.setVars('stretch',null,this.value)" /></td></tr>
    </TABLE>
  </td></tr>
 </TABLE>
</td></tr>
<tr><td colspan=2 style='height:6px;'></td></tr>
</TABLE>
</td></tr>




<!-- ------------------------------- Panel [Panel] -------------------------------->
<tr id=iPanelHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr><td colspan=2>
<div style="height:20px;">
  <TABLE cellspacing=0 cellpadding=0 style='width:100%;'><tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iPanelTable'));">&nbsp;Warp</td>
   <td><input id=splXY type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('split',this.value,this.value);" /></td>
   <td align=right>
		<IMG onclick='window.open("help.php#warp")' style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png'>
		&nbsp;
   		<img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.setVars('split',50,50);gToolbar.syncControls();" >
	</td>
  </tr></TABLE>
</div>
</td></tr>
<tr id=iPanelTable style='display:none;'><td colspan=2>
 <TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
  <tr><td colspan=2>
     <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
  	 <tr><td width=30></td><td width=70  class="xAxis">&nbsp;&nbsp;Horizontal</td>  <td><input id=splX  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('split',this.value,null)" /></td></tr>
  	 <tr><td></td><td class="xAxis">&nbsp;&nbsp;Vertical</td>  <td><input id=splY  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('split',null,this.value)" /></td></tr>
     </TABLE>
  </td></tr>
  <tr><td colspan=2>
	  <TABLE id=iSplitOptions cellspacing=0 cellpadding=0 style='width:100%;'>
	  <tr><td colspan=3><div style="height:4px;"</td></tr>
	  <tr><td width=30></td><td width=70>&nbsp;&nbsp;Global</td><td title=''><input id=iFullSplit type=checkbox onchange="gToolbar.flipFullSplit(this.checked)">&nbsp;&nbsp;(full screen)</td></tr>
	  <tr><td></td><td>&nbsp;&nbsp;Offset&nbsp;Edge</td><td title=''><input id=iOffsetSplit type=checkbox onchange="gToolbar.flipOffsetSplit(this.checked)">&nbsp;&nbsp;(center vs edge)</td></tr>
	  </TABLE>
  </td></tr>
 </TABLE>
</td></tr>
<tr><td colspan=2 style='height:6px;'></td></tr>
</TABLE>
</td></tr>

<!-- ---------------------------- PanelSkew ---------------------------------->
 <tr id=iPanelSkewHdr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iPanelSkewTable'));">&nbsp;Skew</td>
   <td><input id=perPanelSKXY type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.setVars('panelskew',this.value,this.value,0);"  /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetPanelSkew(1)" ></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iPanelSkewTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=30></td><td width=70  class="xAxis">&nbsp;&nbsp;Horizontal</td>   <td><input id=perPanelSKX type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.setVars('panelskew',this.value,null,0);"  /></td></tr>
    <tr><td width=30></td><td  class="xAxis">&nbsp;&nbsp;Vertical</td>   <td><input id=perPanelSKY type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.setVars('panelskew',null,this.value,0);"  /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
 </td></tr>



<!-- ---------------------------- Fold [Fold]-------------------------------->
<tr id=iFoldHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr><td colspan=2>
<div style="height:20px;">
  <TABLE cellspacing=0 cellpadding=0 style='width:100%;'><tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iFoldTable'));">&nbsp;Fold</td>
   <td><input id=fldXY type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('fold',this.value,this.value);" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.setVars('fold',50,50);gToolbar.syncControls();" ></td>
  </tr></TABLE>
</div>
</td></tr>
<tr id=iFoldTable style='display:none;'><td colspan=2>
 <TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
  <tr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
  	<tr><td width=30></td><td width=70 class="xAxis">&nbsp;&nbsp;Horizontal</td>   <td><input id=fldX  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('fold',this.value,null)" /></td></tr>
  	<tr><td></td><td class="xAxis">&nbsp;&nbsp;Vertical&nbsp;</td>   <td><input id=fldY  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('fold',null,this.value)" /></td></tr>
    </TABLE>
  </td></tr>
 </TABLE>
</td></tr>
<tr><td colspan=2 style='height:6px;'></td></tr>
</TABLE>
</td></tr>



<!-- ---------------------- Zoom --------------------------->
 <tr id=iZoomHdr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iZoomTable'));">&nbsp;Zoom</td>
   <td><input id=zomXY type="text" data-slider="true" data-slider-range="1,1000" value="500" data-slider-step="2" onchange="gToolbar.setVars('zoom',this.value,this.value)" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetZoom(1)" title="reset zoom"></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iZoomTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=30></td><td width=70 class="xAxis" >&nbsp;&nbsp;Width&nbsp;</td> <td><input id=zomX  type="text" data-slider="true" data-slider-range="1,1000" value="500" data-slider-step="2" onchange="gToolbar.setVars('zoom',this.value,null)" /></td></tr>
    <tr><td width=30></td><td class="xAxis">&nbsp;&nbsp;Height&nbsp;</td>          <td><input id=zomY  type="text" data-slider="true" data-slider-range="1,1000" value="500" data-slider-step="2" onchange="gToolbar.setVars('zoom',null,this.value)" /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>


<!-- --------------------- Slide ---------------------->
 <tr id=iSlideHdr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iSlideTable'));">&nbsp;Slide</td>
   <td><input id=sldXY type="text" data-slider="true" data-slider-range="-150,250" value="50" data-slider-step="1" onchange="gToolbar.setVars('slide',this.value,this.value,1)" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetSlide(1)" title="reset slide"></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iSlideTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=30></td><td  width=70 class="xAxis">&nbsp;&nbsp;Horizontal&nbsp;</td>         <td><input id=sldX  type="text" data-slider="true" data-slider-range="-150,250" value="50" data-slider-step="1" onchange="gToolbar.setVars('slide',this.value,null,1)" /></td></tr>
    <tr><td width=30></td><td class="xAxis"  width=70>&nbsp;&nbsp;Vertical&nbsp;</td>         <td><input id=sldY  type="text" data-slider="true" data-slider-range="-150,250" value="50" data-slider-step="1" onchange="gToolbar.setVars('slide',null,this.value,1)" /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
 </td></tr>




<!-- ------------------------ Size --------------------------------->
 <tr id=iResizeHdr><td colspan=2>
  <TABLE border=0 cellspacing=0 cellpadding=0 style='width:100%;'>
  <tr>
   <td width=20></td>
   <td width=77 class=toolControl onclick="gToolbar.expandResize()">Size</td>
   <td><input id=rszXY class=slider type="text" data-slider="true" data-slider-range="1,400" value="100" data-slider-step="1" onchange="gToolbar.setXYSizes(this.value)" /></td>
   <td width=20 align=right><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.goFullscreen();gToolbar.syncControls();' title="reset layer size"></td>
  </tr>
  <tr>
   <td colspan=4>
      <TABLE id=iResizeTable cellspacing=0 cellpadding=0 style='display:none; position:relative; left:30px;'>
      <tr><td width=70 class="xAxis">Width</td> <td><input id=rszX type="text" data-slider="true" data-slider-range="1,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('size',this.value,null)" /></td></tr>
      <tr><td class="xAxis">Height</td> <td><input id=rszY type="text" data-slider="true" data-slider-range="1,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('size',null,this.value)" /></td></tr>
	  <tr><td colspan=2><div style='height:4px;'></div></div></td></tr>
	  <tr><td>Lock&nbsp;Position</td><td title='lock top right corner when resizing'>&nbsp;&nbsp;&nbsp;<input id=iLockPosition type=checkbox onchange="gToolbar.flipLockPosition(this.checked)"></td></tr>
 	  <tr style="DISPLAY:NONE;"><td>Resize&nbsp;Children</td><td title='resize children in sync with parent'>&nbsp;&nbsp;&nbsp;<input id=iResizeChildren type=checkbox onchange="gToolbar.flipResizeChildren(this.checked)"  checked ></td></tr>
      </TABLE>
   </td>
  </tr>
  <tr><td colspan=4 style='height:6px;'></td></tr>
  </TABLE>
 </td></tr>


<!-- ------------------------ Move --------------------------------->
 <tr id=iMoveHdr><td colspan=2>
  <TABLE border=0 cellspacing=0 cellpadding=0 style='width:100%;'>
  <tr>
   <td width=20></td>
   <td width=77 class=toolControl onclick="gToolbar.expandMove()">Move</td>
   <td><input id=movXY class=slider type="text" data-slider="true" data-slider-range="-30,30" value="0" data-slider-step="1" onchange="gToolbar.setVars('move',this.value,this.value,1)" /></td>
   <td width=20 align=right><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.goFullscreen();gToolbar.syncControls();' title="reset position"></td>
  </tr>
  <tr>
   <td colspan=4>
      <TABLE id=iMoveTable cellspacing=0 cellpadding=0 style='display:none; position:relative; left:30px;'>
      <tr><td width=70 class="xAxis">Left</td>   <td><input id=movX  type="text" data-slider="true" data-slider-range="-30,30" value="0" data-slider-step="1" onchange="gToolbar.setVars('move',this.value,null,1)" /></td></tr>
      <tr><td class="xAxis">Top</td>   <td><input id=movY  type="text" data-slider="true" data-slider-range="-30,30" value="0" data-slider-step="1" onchange="gToolbar.setVars('move',null,this.value,1)" /></td></tr>
      </TABLE>
   </td>
  </tr>
  <tr><td colspan=4 style='height:6px;'></td></tr>
  </TABLE>
 </td></tr>



<!-- ---------------------- Spin --------------------------->
 <tr id=iSpinHdr style="DISPLAY:;"><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControlBold>&nbsp;Spin</td>
   <td><input id=perSPIN  type="text" data-slider="true" data-slider-range="-180,180" value="0" data-slider-step="1" onchange="gToolbar.chgD3Mode(1);gToolbar.setVars('2drot',this.value,0)"  /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetSpin(1)" title="reset spin"></td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>



<!-- ---------------------- Tilt --------------------------->
 <tr id=iTiltHdr style="DISPLAY:;"><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iTiltTable'));">&nbsp;Tilt</td>
   <td><input id=picSWIVXY  type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.chgD3Mode(1);gToolbar.setVars('swivel',this.value,this.value)" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetTilt(1)" title="reset tilt"></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iTiltTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=30></td><td width=70 class="xAxis" >&nbsp;&nbsp;Horizontal&nbsp;</td> <td><input id=picSWIVX  type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.chgD3Mode(1);gToolbar.setVars('swivel',this.value,null)" /></td></tr>
    <tr><td width=30></td><td class="xAxis">&nbsp;&nbsp;Vertical&nbsp;</td>          <td><input id=picSWIVY  type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.chgD3Mode(1);gToolbar.setVars('swivel',null,this.value)" /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>



<!-- ---------------------- Turn --------------------------->
 <tr id=iTurnHdr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=20 align=right style="width:16px;"></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iTurnTable'));">&nbsp;Turn</td>
   <td><input id=turnXY  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('turn',this.value,this.value)" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.quickReset()" title="reset turn"></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iTurnTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=30></td><td width=70 class="xAxis" >&nbsp;&nbsp;Horizontal&nbsp;</td> <td><input id=turnX  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('turn',this.value,null)" /></td></tr>
    <tr><td width=30></td><td class="xAxis">&nbsp;&nbsp;Vertical&nbsp;</td>          <td><input id=turnY  type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('turn',null,this.value)" /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>



<tr><td colspan=2 style='height:20px;'></td></tr>


 </TABLE>
 </td></tr>

<tr><td id=iPicBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>
</TABLE>
 </td></tr>





<!-- ================================= FRAME ==================================== -->
<tr id=iFrameHdr style="DISPLAY:;"><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iFrameTxt class=toolcontrolHdr onclick="gToolbar.expandFrameTools();">&nbsp;Shadows</span></div></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetShadows()"></td>
</tr>
<tr><td id=iFrameBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iFrameTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:15px;'>
<tr><td colspan=2 style='height:2px;'></td></tr>

<!-- -------------------------- Shadows ------------------------->
<tr id=iShadowsHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr><td colspan=2>
 <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
	<tr>
		<td width=40></td>
		<td class=xAxis>On/Off</td>
		<td class=xAxis>
			&nbsp;
			<input id=shdON onchange="gToolbar.applyShadows()" type=checkbox checked style="position:relative;top:0px;">
			&nbsp;&nbsp;&nbsp;&nbsp;Color:&nbsp;
			<img src="_pvt_images/color.gif" onclick="CP.open(window,gPic.screen.div,'shadowColor',_getLeftClick(event)-150,_getTopClick(event)+10,gToolbar.setShadowColor,1,gToolbar.getShadowHexColor(gPic.screen));" style='cursor:pointer;position:relative;top:2px;'>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<IMG style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png' onclick='window.open("help.php#shadows")' title='tips'>
		</td>
	</tr>
	<tr><td></td><td  width=60 class="xAxis">Spread</td><td><input   id=shdS type="text" data-slider="true" data-slider-range="0,800" value="0" data-slider-step="1" onchange="gToolbar.applyShadows()"  /></td></tr>
	<tr><td></td><td  class="xAxis">Blur</td><td><input id=shdB type="text" data-slider="true" data-slider-range="0,1000" value="20" data-slider-step="2" onchange="gToolbar.applyShadows()"  /></td></tr>
	<tr><td></td><td  class="xAxis">X-Offset</td><td><input id=shdX type="text" data-slider="true" data-slider-range="-400,400" value="0" data-slider-step="1" onchange="gToolbar.applyShadows()" /></td></tr>
	<tr><td></td><td  class="xAxis">Y-Offset</td><td><input id=shdY type="text" data-slider="true" data-slider-range="-400,400" value="0" data-slider-step="1" onchange="gToolbar.applyShadows()"  /></td></tr>
	<tr><td></td><td  class="xAxis">Opacity</td> <td><input id=shdO type="text" data-slider="true" data-slider-range="0,1" value="1" data-slider-step="0.1" onchange="gToolbar.applyShadows()" /></td></tr>
	<tr><td align=left colspan=3>
	<TABLE border=0 cellpadding=0 cellspacing=0>
		<tr><td width=40></td><td width=70>Apply&nbsp;To:&nbsp;</td>
		<td><div class="radio" id="box-shdApplyTo1" checked><input id=shdApplyTo1 value=1 onchange="gToolbar.applyShadows()" name=shdApplyTo type=radio checked></div></td><td><label for="shdApplyTo1">Frame&nbsp;&nbsp;</label></td>
		<td>&nbsp;&nbsp;<div class="radio" id="box-shdApplyTo2"><input id=shdApplyTo2 value=1 onchange="gToolbar.applyShadows()" name=shdApplyTo type=radio></div></td><td><label for="shdApplyTo2">Image&nbsp;&nbsp;</label></td>
		</tr>
	</TABLE>
	</td></tr>
	<tr><td></td><td colspan=2>Inset&nbsp;Shadows&nbsp;&nbsp;<input id=shdI onchange="gToolbar.applyShadows()" type=checkbox  style='position:relative;top:2px;'>&nbsp;&nbsp;&nbsp;</td></tr>
	<tr><td style='height:6px;' colspan=3></td></tr>
	<tr id='iDominantShadows'><td width=40></td><td class="xAxis" colspan=2>Set <b>Shadow 1</b> to dominant color&nbsp;&nbsp;<input id=iVignetteColor type=checkbox onchange="gToolbar.flipVignetteColor(this.checked)" style="cursor:pointer;position:relative;top:1px;"></td></tr>

	<tr><td style='height:9px;' colspan=3></td></tr>
	<tr><td></td><td colspan=2 style='text-align:center;'><input type=button value='add a new shadow' onclick='gToolbar.addNewShadow()'></td></tr>

	<tr><td></td><td colspan=2>
		<TABLE  id=iShadowsListTBL style='width:100%;' cellpadding=0 cellspacing=0>
			<tr><td width=20></td><td colspan=2 style="height:8px;"></td></tr>
			<tr><td width=20></td><td colspan=2 style='border-bottom:solid 1px #666;'></td></tr>
			<tr><td width=20></td>	<td></td><td><DIV id='iShadowsList'></DIV></td></tr>
			<tr><td width=20></td><td colspan=2 style='border-bottom:solid 1px #666;'></td></tr>
			<tr><td width=20></td><td colspan=2 style="height:4px;"></td></tr>
		</TABLE>
	</td></tr>
	<tr><td colspan=3><div style='height:6px;overflow:hidden;'></div></td></tr>
	 
 </TABLE>
</td></tr>
<tr><td colspan=2><div style='height:10px;overflow:hidden;'></div></td></tr>
</TABLE>
</td></tr>



<!-- ----------------- Background ---------------------->
<tr id=iBackgroundHdr style="DISPLAY:;"><td colspan=2>
  <TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
  	<tr><td>
	  	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
			<tr>
	  		<td style='width:18px;'></td>
			<td class=toolControl onclick="gToolbar.expandBackground()" style='width:60px;'>Background&nbsp;&nbsp;</td>
			<td style='width:20px;'><img id=iFrameColorImg src="_pvt_images/color.gif" onclick="CP.open(window,gPic.screen.frame,'frameColor',_getLeftClick(event)-95,_getTopClick(event)+10,gToolbar.setFrameColor,0,gPic.screen.frameColor);" style='cursor:pointer;position:relative;top:2px;' title='flat background'></td>
			<td class=xAxis align=center><b>Hide&nbsp;Image?</b>&nbsp;<input id=iHideImage type=checkbox onchange="gToolbar.hideImage(this.checked)" style='position:relative;top:2px;'/></td>
	  		<td style='width:25px;'></td>
			</tr>
		</TABLE>
  	</td></tr>
	<tr><td style='height:4px;'></td></tr>
  	<tr><td>
	  	<TABLE id=iBackgroundTable cellpadding=0 cellspacing=0 style='display:none;width:100%;'>
		<tr><td>
		  	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
				<tr>
		  		<td width=25></td>
		  		<td><div class="radio" id="box-grdT1" checked><input id=grdT1 value=1 onchange="gToolbar.applyGradient()" name=grdType type=radio checked></div></td><td><label for="grdT1">Flat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
		  		<td><div class="radio" id="box-grdT2"><input id=grdT2 value=2 onchange="gToolbar.applyGradient()" name=grdType type=radio></div></td><td><label for="grdT2">Linear&nbsp;&nbsp;&nbsp;</label></td>
		  		<td><div class="radio" id="box-grdT3"><input id=grdT3 value=3 onchange="gToolbar.applyGradient()" name=grdType type=radio></div></td><td><label for="grdT3">Radial</label></td>
		  		<td width=25></td>
		  		</tr>
		  	</TABLE>
		</td></tr>
		<tr><td>
		  	<TABLE id=iGradientTable cellspacing=0 cellpadding=0 style='width:100%;'>
		  		<tr><td width=30></td><td width=70>Direction&nbsp;</td> <td><input id=grdP type="text" data-slider="true" data-slider-range="1,5" value="5" data-slider-step="1"  onchange="gToolbar.applyGradient()"  /></td><td></td></tr>
		  		<tr><td width=30></td><td width=70>Color1&nbsp;</td>   <td><input id=grdC2 type="text" data-slider="true" data-slider-range="0,100" value="100" data-slider-step="1" onchange="gToolbar.applyGradient()"  /><td><img src="_pvt_images/color.gif" onclick="CP.open(window,gPic.screen.div,'gradientColor',_getLeftClick(event)-95,_getTopClick(event)+10,gPic.screen.setGradientColor1,0,gPic.screen.getGradColor1());" style='cursor:pointer;'></td></tr>
		  		<tr><td width=30></td><td width=70>Color2&nbsp;</td>   <td><input id=grdC1 type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.applyGradient()"  /><td><img src="_pvt_images/color.gif" onclick="CP.open(window,gPic.screen.div,'gradientColor',_getLeftClick(event)-95,_getTopClick(event)+10,gPic.screen.setGradientColor2,0,gPic.screen.getGradColor2());" style='cursor:pointer;'></td></tr>
		  	</TABLE>
		</td></tr>
		</TABLE>
  	</td></tr>
	<tr><td style='height:4px;'></td></tr>
  </TABLE>	
</td></tr>


<!-- -------------------- Corners ------------------------------>
<tr id=iShapeHdr style="DISPLAY:;"><td colspan=2>
	<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
		<tr><td colspan=2>
		<TABLE cellpadding=0 cellspacing=0 style='width:100%;'><tr>
			<td width=20></td>
			<td width=80 class=toolControlBold>Corners</td>
			<td><input  id=shdR type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.applyCorners()"  /></td>
			<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.applyCorners(0);gToolbar.syncControls();' ></td>
			</tr>
		</TABLE>
		</td></tr>
		<tr><td colspan=2 style='height:10px;'></td></tr>
	</TABLE>
</td></tr>


<!-- ----------------- Opacity ----------------------->
<tr id=iOpacityHdr style="DISPLAY:;"><td colspan=2>
  	<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
	  	<tr><td width=20></td><td width=80 class=toolControlBold>Opacity</td><td><input id=opcDV type="text" data-slider="true" data-slider-range="0,1" value="1" data-slider-step="0.1" onchange="gToolbar.setVars('opacity1',this.value,0,1)" /></td>			<td><?=$lightning;?> onclick="gToolbar.spinSliderBkwrds(event,'opcDV',1,0.6,0.3)"></td>	<td style='width:15px;'></td></tr>
	  	<tr><td colspan=5 style='height:10px;'></td></tr>
	</TABLE>
</td></tr>



<tr><td colspan=2 style='height:20px;'></td></tr>

</TABLE>
</td></tr>

<tr><td id=iFrameBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>
</TABLE>
</td></tr>


<!-- =======================VIEW ======================= -->
<tr id=iViewHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 border=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iViewTxt class=toolcontrolHdr onclick="gToolbar.expandViewTools();">&nbsp;View</span></div></td>
	<td align=right>	
		<IMG onclick='window.open("help.php#viewsettings")' style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png'>
		&nbsp;
		<img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetView()">
	</td>
</tr>
<tr><td id=iViewBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iViewTools' cellspacing=0 cellpadding=0 border=0 style='width:100%;position:relative;top:10px;'>
<tr><td colspan=2 style='height:2px;'></td></tr>



<!-- ----------------- View Zoom ----------------------->
<tr id=iViewZoomHdr style="DISPLAY:NONE;"><td colspan=2>
  	<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
	  	<tr>
			<td width=16></td>
			<td class=toolControlBold width=80>Zoom</td>
			<td><input id=iViewZoom type="text" data-slider="true" data-slider-range="1,1000" value="100" data-slider-step="1" onchange="gPic.viewSetVars('viewzoom',this.value,null)" /></td>
	   		<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.viewResetZoom(1)"></td>
		</tr>
	  	<tr><td colspan=5 style='height:10px;'></td></tr>
	</TABLE>
</td></tr>



<!-- ---------------------View Slide ---------------------->
<tr id=iViewSlideHdr style="DISPLAY:NONE;"><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=16 align=right></td>
   <td width=80 class=toolControl onclick="gToolbar.expandControl(_obj('iViewSlideTable'));">Move</td>
   <td><input id=sldViewXY type="text" data-slider="true" data-slider-range="-500,500" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewslide',this.value,this.value)" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.viewResetSlide(1)"></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iViewSlideTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=26></td><td  width=70 class="xAxis">&nbsp;&nbsp;Horizontal&nbsp;</td>
	<td><input id=sldViewX  type="text" data-slider="true" data-slider-range="-500,500" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewslide',this.value,null)" /></td></tr>
    <tr><td width=26></td><td class="xAxis"  width=70>&nbsp;&nbsp;Vertical&nbsp;</td>
	 <td><input id=sldViewY  type="text" data-slider="true" data-slider-range="-500,500" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewslide',null,this.value)" /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>



<!-- ----------------------View Spin --------------------------->
<tr id=iViewSpinHdr style="DISPLAY:NONE;"><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=16 align=right></td>
   <td width=84 class=toolControlBold>Spin</td>
   <td><input id=iViewSpin  type="text" data-slider="true" data-slider-range="-180,180" value="0" data-slider-step="1" onchange="gPic.viewSetVars('view2drot',this.value,0)"  /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.viewResetSpin()" title="reset spin"></td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>



<!-- ----------------------View Tilt --------------------------->
<tr id=iViewTiltHdr style="DISPLAY:NONE;"><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td width=16 align=right style="width:16px;"></td>
   <td width=84 class=toolControl onclick="gToolbar.expandControl(_obj('iViewTiltTable'));">Tilt</td>
   <td><input id=iViewTilt  type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewswivel',this.value,this.value)" /></td>
   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.viewResetTilt()" title="reset tilt"></td>
  </tr>
  <tr>
   <td colspan=3>
    <TABLE id=iViewTiltTable cellspacing=0 cellpadding=0 style='display:none;'>
    <tr><td width=30></td><td width=70 class="xAxis" >&nbsp;&nbsp;Horizontal&nbsp;</td><td><input id=iViewTiltX  type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewswivel',this.value,null)" /></td></tr>
    <tr><td width=30></td><td class="xAxis">&nbsp;&nbsp;Vertical&nbsp;</td>            <td><input id=iViewTiltY  type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewswivel',null,this.value)" /></td></tr>
    </TABLE>
   </td>
  </tr>
  <tr><td colspan=3 style='height:6px;'></td></tr>
  </TABLE>
</td></tr>



<!-- ---------------------------View Blend ------------------------->
<tr id=iViewBlendHdr><td colspan=2>
<TABLE cellpadding=0 cellspacing=0 style="width:100%;">
<tr>
	<td width=16></td>
	<td width=84 class=toolControlBold>Blend</td>
	<td><input id=iViewBlend type="text" data-slider="true" data-slider-range="0,15" value="0" data-slider-step="1" onchange="gPic.viewSetVars('viewblend',this.value)" /></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.viewResetBlend()" title="cancel blend"></td>
</tr>
<tr><td colspan=4 style='height:8px;'></td></tr>
</TABLE>
</td></tr>



<!-- -------------View Colors ------------->
<tr id=iViewColorHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
	<tr><td colspan=2>
	<DIV style="height:20px;">
	<TABLE cellpadding=0 cellspacing=0 style="width:100%;">
		<tr>
		<td width=16></td>
		<td width=84 class=toolControl onclick="gToolbar.expandViewColor()">Colors</td>
		<td><input id=viewfil_sat type="text" data-slider="true" data-slider-range="0,500" value="100" data-slider-step="1" onchange="gPic.viewSetVars('saturate',this.value,0,1)" /></td>
		<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.viewResetColors()' title="reset all color filters"></td>
		</tr>
	</TABLE>
	</DIV>
	</td></tr>
	<tr id=iViewColorTable style='display:none;'><td colspan=2>
	<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
	   <tr><td width=30></td>	<td width=70>Brightness</td>			<td><input id=viewfil_bri  type="text" data-slider="true" data-slider-range="30,200" value="100" data-slider-step="1" onchange="gPic.viewSetVars('brightness',this.value,0,1)" /></td></tr>
	   <tr><td></td>			<td>Contrast</td>						<td><input id=viewfil_con  type="text" data-slider="true" data-slider-range="30,500" value="100" data-slider-step="1" onchange="gPic.viewSetVars('contrast',this.value,0,1)" /></td></tr>
	   <tr><td></td>			<td>Blur</td>							<td><input id=viewfil_blu  type="text" data-slider="true" data-slider-range="0,20" value="0" data-slider-step="1" onchange="gPic.viewSetVars('blur',this.value,0,1)" /></td>		</tr>
	   <tr><td></td>			<td>Hue</td>							<td><input id=viewfil_hue  type="text" data-slider="true" data-slider-range="0,360" value="0" data-slider-step="1" onchange="gPic.viewSetVars('hue-rotate',this.value,0,1)" /></td>	</tr>
	   <tr><td></td>			<td>Sepia</td>							<td><input id=viewfil_sep  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gPic.viewSetVars('sepia',this.value,0,1)" /></td>		</tr>
	   <tr><td></td>			<td>Grayscale</td>						<td><input id=viewfil_gra  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gPic.viewSetVars('grayscale',this.value,0,1)" /></td>	</tr>
	   <tr><td></td>			<td>Invert</td>							<td><input id=viewfil_inv  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gPic.viewSetVars('invert',this.value,0,100)" /></td>	</tr>
	   <tr><td></td>			<td>Opacity</td>						<td><input id=viewopcXY    type="text" data-slider="true" data-slider-range="0,1" value="1" data-slider-step="0.1" onchange="gPic.viewSetVars('opacity2',this.value,0,1)" /></td>		</tr>
	</TABLE>
	</td></tr>
	<tr><td colspan=2 style='height:6px;'></td></tr>
</TABLE>
</td></tr>




<!-- -----------------View Background ---------------------->
<tr id=iViewBackgroundHdr><td colspan=2>
  <TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
  	<tr><td>
	  	<TABLE cellpadding=0 cellspacing=0>
			<tr>
	  		<td width=17></td>
			<td width=86 class=toolControl onclick="gToolbar.expandViewBackground()">Background</td>
			<td><img id=iviewFrameColorImg src="_pvt_images/color.gif" onclick="gPic.lastDesktopColor=gPic.desktopColor;CP.open(window,gPic.desktop,'frameColor',_getLeftClick(event)-95,_getTopClick(event)+10,gPic.setDesktopColor,0,gPic.desktopColor);" style='cursor:pointer;position:relative;top:2px;' title='flat background'></td>
			</tr>
		</TABLE>
  	</td></tr>
	<tr><td style='height:4px;'></td></tr>
  	<tr><td>
	  	<TABLE id=iViewBackgroundTable cellpadding=0 cellspacing=0 style='display:none;width:100%;'>
		<tr><td>
		  	<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
				<tr>
		  		<td width=25></td>
		  		<td><div class="radio" id="viewbox-grdT1" checked><input id=viewgrdT1 value=1 onchange="gToolbar.applyGradient('view')" name=ViewgrdType type=radio checked></div></td><td><label for="viewgrdT1">Flat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
		  		<td><div class="radio" id="viewbox-grdT2"><input id=viewgrdT2 value=2 onchange="gToolbar.applyGradient('view')" name=ViewgrdType type=radio></div></td><td><label for="viewgrdT2">Linear&nbsp;&nbsp;&nbsp;</label></td>
		  		<td><div class="radio" id="viewbox-grdT3"><input id=viewgrdT3 value=3 onchange="gToolbar.applyGradient('view')" name=ViewgrdType type=radio></div></td><td><label for="viewgrdT3">Radial</label></td>
		  		<td width=25></td>
		  		</tr>
		  	</TABLE>
		</td></tr>
		<tr><td>
		  	<TABLE id=iviewGradientTable cellspacing=0 cellpadding=0 style='width:100%;'>
		  		<tr><td width=30></td><td width=70>Direction&nbsp;</td> <td><input id=viewgrdP type="text" data-slider="true" data-slider-range="1,5" value="5" data-slider-step="1"  onchange="gToolbar.applyGradient('view')"  /></td><td></td></tr>
		  		<tr><td width=30></td><td width=70>Color1&nbsp;</td>    <td><input id=viewgrdC2 type="text" data-slider="true" data-slider-range="0,100" value="100" data-slider-step="1" onchange="gToolbar.applyGradient('view')"  /><td><img src="_pvt_images/color.gif" onclick="CP.open(window,gPic.desktop,'gradientColor',_getLeftClick(event)-95,_getTopClick(event)+10,gPic.setGradientColor1,0,gPic.getGradColor1());" style='cursor:pointer;'></td></tr>
		  		<tr><td width=30></td><td width=70>Color2&nbsp;</td>    <td><input id=viewgrdC1 type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.applyGradient('view')"  /><td><img src="_pvt_images/color.gif" onclick="CP.open(window,gPic.desktop,'gradientColor',_getLeftClick(event)-95,_getTopClick(event)+10,gPic.setGradientColor2,0,gPic.getGradColor2());" style='cursor:pointer;'></td></tr>
		  	</TABLE>
		</td></tr>
		</TABLE>
  	</td></tr>
  </TABLE>
</td></tr>



<!-- -----------------View Options ---------------------->
<tr><td colspan=2>
  <TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
  	<tr style="height:5px;"><td colspan=3></td></tr>
 	<tr><td width=18></td>
		<td width=120 class=toolControlBold>Slideshow&nbsp;Timer</td>
		<td><input id=ssTimer type="text" value="6" onchange="gToolbar.viewPauseDelay(this.value)" style="width:30px;" />&nbsp;seconds</td>
	</tr>
  	<tr style="height:5px;"><td colspan=3></td></tr>
 	<tr><td width=18></td>
		<td width=120 class=toolControlBold>Fade&nbsp;Images</td>
		<td><input id=iDopple type="checkbox" onchange="gToolbar.viewFlipDopple(this.checked)" style="position:relative;top:3px;"></td>
	</tr>
  </TABLE>
</td></tr>



<tr><td colspan=2 style='height:20px;'></td></tr>
</TABLE>
</td></tr>

<tr><td id=iViewBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>
</TABLE>
 </td></tr>




<!-- ================================= TILING ==================================== -->
<tr id=iTileHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr>
	<td>
	<TABLE cellspacing=0 cellpadding=0>
	<tr>
		<td></td>
		<td>
			<div id="iTileTxt">
			<span class=toolcontrolHdr onclick="gToolbar.expandTileTools();">&nbsp;Tiling</span>
			</div>
		</td>
	</tr>
	</TABLE>
	</td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.tileSlider(0)"></td>
</tr>
<tr><td id=iTileBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>

<TABLE id='iTileTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:6px;'>
<tr><td colspan=2 style='height:6px;'></td></tr>

<tr><td colspan=2>
<TABLE id=iTilingHdr cellspacing=0 cellpadding=0 style='width:100%;'>

	<tr style="DISPLAY:NONE;"><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0 style="position:relative;top:-8px;letter-spacing:1px;">
		<tr>
			<td width=17></td>
			<td class="xAxis">Current&nbsp;Pattern:&nbsp;&nbsp;&nbsp;</td>
			<td width=60><span id=iTilingTxt></span></td>
			<td>&nbsp;(<span id=iTilingType></span>)&nbsp;</td>
		</tr>
		</TABLE>
	</td></tr>
	<tr style="DISPLAY:NONE;"><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
		  	<tr>
			<td width=80 class=toolControlBold>&nbsp;&nbsp;&nbsp;&nbsp;Defaults</td>
			<td><input id=tileXY type="text" data-slider="true" data-slider-range="0,12" value="0" data-slider-step="1" onchange="gToolbar.tileSlider(this.value*1)" /></td>
		    </tr>
		</TABLE>
	</td></tr>
	<tr><td colspan=2 style='height:10px;'></td></tr>
	<tr><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0 style='width:90%;'>
		   	<tr>
				<td width=60></td>
				<td colspan=3 class="xAxis" width=62>Lock X/Y</td>
		   		<td><img id=iLockTiledImage src="_pvt_images/Lock-Lock.png" onclick="gToolbar.lockTiledImage()" title="lock X and Y" style="cursor:pointer;position:relative;top:-2px;width:14px;"></td>
			</tr>
		    <tr>
				<td width=60></td>
				<td class="xAxis" width=62>X-Image</td>
				<td><input type="text" data-slider="true" data-slider-range="1,36" value="4" data-slider-step="1" id="izxaxis" onchange="_obj('izx').innerHTML=this.value;gToolbar.tileScreen(0)" /></td>
				<td width=16><?=$lightning;?> onclick="gToolbar.spin1('izxaxis',1)"></td>
				<td><span class="rangetext" id="izx"></span></td>
			</tr>
		    <tr>
				<td></td>
				<td class="xAxis">Y-Image</td>
				<td><input type="text" data-slider="true" data-slider-range="1,36"   value="4" data-slider-step="1" id="izyaxis" onchange="_obj('izy').innerHTML=this.value;gToolbar.tileScreen(1)" /></td>
				<td width=16><?=$lightning;?> onclick="gToolbar.spin1('izyaxis',1)"></td>
				<td><span class="rangetext" id="izy"></span></td>
			</tr>
		    <tr>
				<td></td>
				<td class="xAxis">X-Panels</td>
				<td><input type="text" data-slider="true" data-slider-range="1,36"  value="4" data-slider-step="1" id="ixaxis"  onchange="_obj('ixx').innerHTML=this.value;gToolbar.tileScreen(0)" /></td>
				<td width=16><?=$lightning;?> onclick="gToolbar.spin1('ixaxis',1)"></td>
				<td><span class="rangetext" id="ixx"></span></td>
			</tr>
		    <tr>
				<td></td>
				<td class="xAxis">Y-Panels</td>
				<td><input type="text" data-slider="true" data-slider-range="1,36"  value="4" data-slider-step="1" id="iyaxis"  onchange="_obj('iyy').innerHTML=this.value;gToolbar.tileScreen(1)" /></td>
				<td width=16><?=$lightning;?> onclick="gToolbar.spin1('iyaxis',1)"></td>
				<td><span class="rangetext" id="iyy"></span></td>
			</tr>
			<tr>
				<td></td>
				<td class="xAxis">Frack</td>
			    <td><input id=frkWH type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',this.value,this.value)" /></td>
				<td></td>
			    <td><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.applyClipRadius(0);gToolbar.setVars("frack",100,100);gToolbar.setVars("scale",0,0);gToolbar.syncControls();'></td>
			</tr>
		</TABLE>
	</td></tr>
	
	<!--
	<tr id=iFrackHdr><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0>
			<tr>
			   <td width=95 class=toolControl onclick="gToolbar.expandFrack()">&nbsp;&nbsp;&nbsp;Frack</td>
			   <td><input id=frkWH type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',this.value,this.value)" /></td>
			   <td><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.applyClipRadius(0);gToolbar.setVars("frack",100,100);gToolbar.setVars("scale",0,0);gToolbar.syncControls();'></td>
			</tr>
			<tr>
			   <td colspan=2>
			   <TABLE id=iFrackTable cellspacing=0 cellpadding=0 style='display:none;'>
				    <tr><td width=40></td><td  width=60 class="xAxis">Width</td> <td><input id=frkW type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',this.value,null)" /></td></tr>
				    <tr><td></td><td class="xAxis">Height</td> <td><input id=frkH type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',null,this.value)" /></td></tr>
			   </TABLE>
			   </td>
			</tr>
			<tr><td colspan=2 style='height:8px;'></td></tr>
		</TABLE>
	</td></tr>
	-->
	
</TABLE>
</td></tr>
<tr><td colspan=2 style='height:20px;'></td></tr>



</TABLE>
</td></tr>

<tr><td id=iTileBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>
</TABLE>
</tr></td>



<!-- ================================= ANIMATION ==================================== -->
<tr id=iRekordHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iRekordTxt class=toolcontrolHdr onclick="gToolbar.expandRekordTools();">&nbsp;Animation</span></div></td>
	<td align=right>
		<IMG onclick='window.open("help.php#animation")' style='position:relative;top:-1px;' class=c_barbtn src='_pvt_images/help.png'>
		&nbsp;
		<img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetRekord(1)">
	</td>
</tr>
<tr><td id=iRekordBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iRekordTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:6px;left:20px;'>
<tr><td colspan=2 style='height:2px;'></td></tr>
<tr><td>

	   	<TABLE  cellspacing=0 cellpadding=0 style='width:98%;position:relative;left:0px;'>
			<tr>
   				<td class="xAxis">Oscillate</td><td>&nbsp;<input id=iRekLoop0 name=iRekLoop value=2 onchange="gToolbar.historyLoopMode()" type=radio checked>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td class="xAxis">Restart</td>  <td>&nbsp;<input id=iRekLoop1 name=iRekLoop value=4 onchange="gToolbar.historyLoopMode()" type=radio>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td class="xAxis">or&nbsp;Stop</td>       <td>&nbsp;<input id=iRekLoop2 name=iRekLoop value=1 onchange="gToolbar.historyLoopMode()" type=radio></td>
			</tr>
			<tr><td colspan=6 style='height:10px;border-top:solid 1px #cccccc;'></td></tr>
   		</TABLE>


		<TABLE id=iReplayTable cellspacing=0 cellpadding=0 style='width:100%;' >
			<tr>
				<td width=70 class="toolControlBold">RePlay</td>
				<td>
				
				<img id='iHistBck' class='c_barbtn corners4' onmousedown='gPic.screen.history.StartStop(-1)' style='cursor:pointer;opacity:0.8;' src='_pvt_images/playleft.png' oncontextmenu='return false;' title='replay history back'>
				<img id='iHistFwd' class='c_barbtn corners4' onmousedown='gPic.screen.history.StartStop(1)'  style='cursor:pointer;opacity:0.8;' src='_pvt_images/play.png'     oncontextmenu='return false;' title='replay history fwd'>

				
				</td>
				<td align=right><img id='iHistReset' class='c_barbtn' src='_pvt_images/reset.png' onclick='gToolbar.resetHistory(0);gToolbar.syncHistory();' style='width:26px;height:26px;cursor:pointer;' title="reset history"></td>
			</tr>
   			<tr><td colspan=3><div style='height:3px;'</div></td></tr> 
   			<tr><td width=70 class="xAxis">Curve</td><td><input id=iRekCurve type="text" data-slider="true" data-slider-range="25,75" value="50" data-slider-step="1" onchange="gToolbar.historyPlayCurve(this.value)" /></td><td></td></tr>
   			<tr><td class="xAxis">Pause</td><td><input id=iHistSpeed type="text" data-slider="true" data-slider-range="0,1000" value="250" data-slider-step="1" onchange="gToolbar.histPlaySpeed(this.value)" /></td><td width=20><input id=iHistSpeedTxt type="text" value="0" onchange="gToolbar.histPlaySpeed(this.value)" style='width:28px;font-size:12px;' /></td></tr>
			<tr id=iRekDeltaTR><td class="xAxis">Delta</td><td><input id=iHistDelta type="text" data-slider="true" data-slider-range="1,50" value="25" data-slider-step="1" onchange="gToolbar.histPlayDelta(this.value)" /></td><td width=20><input id=iHistDeltaTxt type="text" value="0" onchange="gToolbar.histPlayDelta(this.value)" style='width:28px;font-size:12px;' /></td></tr>
   			<tr><td colspan=3><div style='height:0px;'</div></td></tr>
			<tr style="DISPLAY:NONE;"><td colspan=3>
   				<TABLE  cellspacing=0 cellpadding=0><tr>
					<td width=73>Relative</td>
   					<td width=22><input id=iRekRelative onchange="gToolbar.setRekRelative()" type=checkbox style='position:relative;top:2px;'></td>
   					<td></td>
				</tr></TABLE>
			</td></tr>
			<tr><td id=iRekMergeTD colspan=3>
   				<TABLE  cellspacing=0 cellpadding=0><tr>
					<td width=73>Merge</td>
   					<td width=22><input id=iRekMerge onchange="gToolbar.setMerge()" type=checkbox style='position:relative;top:2px;'></td>
   					<td></td>
				</tr></TABLE>
			</td></tr>
			<tr style="DISPLAY:NONE;"><td colspan=3>
   				<TABLE  cellspacing=0 cellpadding=0><tr>
					<td width=73>Smooth</td>
   					<td width=22><input id=iRekSmooth onchange="gToolbar.setSmooth()" type=checkbox checked style='position:relative;top:2px;'></td>
				</tr></TABLE>
			</td></tr>
   			<tr><td colspan=3><div style='height:0px;'</div></td></tr>
			<? if($gLoggedIn){ ?>
			<tr id=iSaveRekordBtn><td colspan=3 align=right class="xAxisBold">Save RePlay Action&nbsp;&nbsp;<img class='c_barbtn'  style='position:relative;top:8px;width:28px;height:28px;opacity:0.7;' src='_pvt_images/save1.png' onclick='parent.saveRekord()'  title='save action'></td>
			<? } ?>
			<tr><td colspan=3 style='height:10px;'></td>
		</TABLE>
		
</td></tr>
<tr><td style='height:10px;border-top:solid 1px #cccccc;'></td></tr>
<tr><td>
		<TABLE  id=iAutoPlayTable cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:0px;' >
			<tr><td>
			<TABLE  cellspacing=0 cellpadding=0 style='DISPLAY:;position:relative;left:0px;'>
   				<tr>
				<td width=69 class=toolControlBold>AutoPlay</td>
				<td><img id='iAutoBck' class='c_barbtn corners4' onmousedown='gPic.screen.animation.startstop(-1)' src='_pvt_images/playleft.png' oncontextmenu='return false;' title='play' style='opacity:0.7;position:relative;left:4px;top:2px;'></td>
				<td><img id='iAutoFwd' class='c_barbtn corners4' onmousedown='gPic.screen.animation.startstop(1)' src='_pvt_images/play.png' oncontextmenu='return false;' title='play' style='opacity:0.7;position:relative;left:4px;top:2px;'></td>
				<td width=20>&nbsp;</td>
				<td><span id=iMouseModeName class=xAxisBold style='color:#6699ff;'></span></td>
				<td><img class='c_barbtn'  style='DISPLAY:NONE;width:28px;height:28px;opacity:0.7;' src='_pvt_images/save1.png' onclick=''  title='save action'></td>
				</tr>
			</TABLE>
			</td></tr>
   			<tr><td><div style='height:10px;'</div></td></tr>
			<tr><td>
  			<TABLE cellspacing=0 cellpadding=0 style='DISPLAY:;'>
  				<tr><td class="xAxis" width=70>X-Min</td><td><input id=iXRekRangeMin data-slider="true" data-slider-range="0,100" value="30" data-slider-step="1" onchange="gToolbar.historyRange('Min')" /></td><td><input id=iAniX type=checkbox checked  onchange="gToolbar.aniXchg(this.checked)" style='position:relative;top:9px;left:2px;'></td></tr>
  				<tr><td class="xAxis">X-Max</td><td><input id=iXRekRangeMax data-slider="true" data-slider-range="0,100" value="70" data-slider-step="1" onchange="gToolbar.historyRange('Max')" /></td><td></td></td></tr>
   				<tr><td colspan=3><div style='height:4px;'</div></td></tr>
  				<tr><td class="xAxis">Y-Min</td><td><input id=iYRekRangeMin data-slider="true" data-slider-range="0,100" value="30" data-slider-step="1" onchange="gToolbar.historyRange('Min')" /></td><td><input id=iAniY type=checkbox checked onchange="gToolbar.aniYchg(this.checked)" style='position:relative;top:9px;left:2px;'></td></tr>
  				<tr><td class="xAxis">Y-Max</td><td><input id=iYRekRangeMax data-slider="true" data-slider-range="0,100" value="70" data-slider-step="1" onchange="gToolbar.historyRange('Max')" /></td><td></td></td></tr>
				<tr><td  class="xAxis">Pause</td><td><input id=iRekSpeed type="text" data-slider="true" data-slider-range="0,500" value="0" data-slider-step="1" onchange="gToolbar.historyPlaySpeed(this.value)" /></td><td width=20><input id=iRekSpeedTxt type="text" value="0" onchange="gToolbar.historyPlaySpeed(this.value)" style='width:28px;font-size:12px;' /></td></tr>
				<tr><td class="xAxis">Delta</td><td><input id=iRekDelta type="text" data-slider="true" data-slider-range="0.02,20" value="0.2" data-slider-step="0.02" onchange="gToolbar.historyPlayDelta(this.value)" /></td><td width=20><input id=iRekDeltaTxt type="text" value="0" onchange="gToolbar.historyPlayDelta(this.value)" style='width:28px;font-size:12px;' /></td></tr>
  			</TABLE>
 			</td></tr>
			<? if($gLoggedIn){ ?>
			<tr id=iSaveAutoplayBtn><td align=right class="xAxisBold">Save AutoPlay Action&nbsp;&nbsp;<img class='c_barbtn'  style='position:relative;top:8px;width:28px;height:28px;opacity:0.7;' src='_pvt_images/save1.png' onclick='parent.saveAutoplay()'  title='save action'></td>
			<? } ?>
			<tr><td style='height:6px;'></td>
   			<tr><td><div style='height:10px;'></div></td></tr>
			<tr><td>
			<!-- --------------------------------------------------- Images -[Images]----------------------------------------->
			<!--
			<TABLE id=iImagesSwitchHdr cellspacing=0 cellpadding=0 style='width:100%;'>
				<tr><td>
					<div style="height:20px;">
  					<TABLE cellspacing=0 cellpadding=0 style=':100%;'><tr>
   						<td width=70 style='cursor:default;font-size:18px;'>&nbsp;Images</td>
   						<td><input id=ImagesXY type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gPic.screen.setVars('images',this.value,null);" /></td>
   						<td></td>
  					</tr></TABLE>
					</div>
				</td></tr>
			</TABLE>
			-->
			</td></tr>
		</TABLE>
		
</td></tr>
<tr><td style='height:20px;'></td></tr>
		
</TABLE>
</td></tr>
<tr><td id=iRekordBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>

</TABLE>
</td></tr>


<!-- ================================= PERSPECTIVE ==================================== -->
<tr id=i3DHdr style="DISPLAY:NONE;"><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;overflow:hidden;'>
<tr>
	<td><div style="height:24px;"><span id=i3DTxt class=toolcontrolHdr onclick="gToolbar.expand3DTools();">&nbsp;Orientation</span></div></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetPerspective(1)"></td>
</tr>
<tr><td id=i3DBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>

<TABLE id='i3DTools' cellspacing=0 cellpadding=0 style='width:100%;overflow:hidden;position:relative;top:0px;'>
<tr><td colspan=2 style='height:10px;'></td></tr>

<!-- ------------------ Mode -------------------------->
<tr><td colspan=2>
 <TABLE cellspacing=0 cellpadding=0 style='width:255px;'><tr>
 <td width=10></td>
 <td class=xAxisBold>Mode:&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
 <td><div class="radio" id="box-iD3ModeW"><input id=iD3ModeW value=0 onchange="gToolbar.chgD3Mode()" name=D3Mode type=radio checked></div></td><td><label for="iD3ModeW">Frame&nbsp;&nbsp;&nbsp;</label></td>
 <td><div class="radio" id="box-iD3ModeI" checked><input id=iD3ModeI value=1 onchange="gToolbar.chgD3Mode()" name=D3Mode type=radio ></div></td><td><label for="iD3ModeI">Image&nbsp;&nbsp;&nbsp;</label></td>
 <td style="DISPLAY:NONE;"><div class="radio" id="box-iD3ModeP"><input id=iD3ModeP value=2 onchange="gToolbar.chgD3Mode()" name=D3Mode type=radio></div></td><td style="DISPLAY:NONE;"><label for="iD3ModeP">Panels&nbsp;&nbsp;&nbsp;</label></td>
 <!-- "panels" only works for skew and that's all done by Image "Skew" anyway -->
 </tr></TABLE>
</td></tr>

<tr><td colspan=2 style='height:6px;'></td></tr>


<!-- ------------------------------- Tilt ------------------------------------------>
 <tr><td colspan=2>
  <TABLE cellspacing=0 cellpadding=0 style="width:100%;">
  <tr>
   <td class=xAxisBold width=60>&nbsp;&nbsp;&nbsp;Spin</td>
   <td width=45></td>
   <td><input id=perROT  type="text" data-slider="true" data-slider-range="0,360" value="0" data-slider-step="1" onchange="gToolbar.setVars('2drot',this.value,0)"  /></td>
   <td width=45><?=$lightning;?> onclick="gToolbar.spinSlider(event,'perROT',0,45,90,135,180,225,270,315)">&nbsp;</td>
  </tr>
  <tr><td colspan=4 style='height:10px;'></td></tr>
  <tr>
	<td class=xAxis>&nbsp;&nbsp;&nbsp;<span class=xAxisBold>Tilt</span>&nbsp;XY</td>
   	<td></td>
	<td><input id=perSWIVXY type="text" data-slider="true" data-slider-range="0,180" value="0" data-slider-step="1" onchange="gToolbar.setVars('swivel',this.value,this.value,1);"  /></td>
   <td></td>
  </tr>
  <tr>
	<td class=xAxis>&nbsp;&nbsp;&nbsp;&nbsp;Horizantal</td>
   	<td></td>
	<td><input  id=perSWIVY type="text" data-slider="true" data-slider-range="0,180" value="0" data-slider-step="1" onchange="gToolbar.setVars('swivel',this.value,null,1)" /></td>
   <td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'perSWIVY',0,45,135)">&nbsp;</td>
  </tr>
  <tr>
	<td class=xAxis>&nbsp;&nbsp;&nbsp;&nbsp;Vertical</td>
   	<td></td>
	<td><input  id=perSWIVX type="text" data-slider="true" data-slider-range="0,180" value="0" data-slider-step="1" onchange="gToolbar.setVars('swivel',null,this.value,1)" /></td>
   <td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'perSWIVX',0,45,135)">&nbsp;</td>
  </tr>
  <tr><td colspan=4 style='height:10px;'></td></tr>
  <tr>
   <td class=xAxis>&nbsp;&nbsp;&nbsp;<span class=xAxisBold>Flip</span>&nbsp;XY</td>
   <td></td>
   <td><input id=perR3Dxy type="text" data-slider="true" data-slider-range="0,720" value="360" data-slider-step="1" onchange="gToolbar.flipBoth(this.value)" /></td>
   <td> <!-- <?=$lightning;?> onclick="gToolbar.spin45('perR3DB',45,16,1)">&nbsp; --> </td>
  </tr>
  <tr>
   <td class=xAxis>&nbsp;&nbsp;&nbsp;&nbsp;Flip&nbsp;X</td>
   <td></td>
   <td><input id=perR3Dx type="text" data-slider="true" data-slider-range="-180,180" value="0" data-slider-step="1" onchange="gToolbar.flipHorizontal(this.value)" /></td>
   <td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'perR3DH',0,45,135,180)">&nbsp;</td>
  </tr>
  <tr>
   <td class=xAxis>&nbsp;&nbsp;&nbsp;&nbsp;Flip&nbsp;Y</td>
   <td></td>
   <td><input id=perR3Dy type="text" data-slider="true" data-slider-range="-180,180" value="0" data-slider-step="1" onchange="gToolbar.flipVertical(this.value)" /></td>
   <td><?=$lightning;?> onclick="gToolbar.spinSlider(event,'perR3DV',0,45,135,180)">&nbsp;</td>
  </tr>
  <tr style="DISPLAY:;"><!-- doesn't appear to have any effect -->
   <td class=xAxis>&nbsp;&nbsp;&nbsp;&nbsp;Flip&nbsp;Z</td>
   <td></td>
   <td><input id=perR3Dz type="text" data-slider="true" data-slider-range="-1,1" value="0" data-slider-step="0.1" onchange="gToolbar.setVars('flipz',this.value,0)" /></td>
   <td></td>
  </tr>
  <tr>
   <td class=xAxis>&nbsp;&nbsp;&nbsp;&nbsp;Tilt</td>
   <td></td>
   <td><input id=perSP type="text" data-slider="true" data-slider-range="-1000,0" value="-1000" data-slider-step="1" onchange="gToolbar.setVars('tilt',this.value,0)" /></td>
   <td></td>
  </tr>
  <tr><td colspan=4 style='height:10px;'></td></tr>
  <tr><td colspan=4>
    <TABLE cellspacing=0 cellpadding=0 style='position:relative;left:10px;padding:4px;border:solid 1px #cccccc;'>
	<tr><td class="xAxisBold" width=70>Advanced</td><td colspan=2></td></tr>
	<tr><td class="xAxis" width=70>Custom&nbsp;Flip&nbsp;</td><td colspan=2><input id=perR3D type="text" data-slider="true" data-slider-range="0,360" value="0" data-slider-step="1" onchange="gToolbar.flipCustom(this.value)" /></td></tr>
    <tr><td class="xAxis">&nbsp;x-Axis</td><td colspan=2><input id=perRY  type="text" data-slider="true" data-slider-range="-180,180" value="0" data-slider-step="1" onchange="gToolbar.setVars('axis',null,this.value)" /></td></tr>
    <tr><td class="xAxis">&nbsp;y-Axis</td><td colspan=2><input id=perRX  type="text" data-slider="true" data-slider-range="-180,180" value="0" data-slider-step="1" onchange="gToolbar.setVars('axis',this.value,null)" /></td></tr>
    <tr><td class="xAxis">&nbsp;Origin</td><td style='width:25px;'><?=$lightning;?> onclick="gToolbar.spinOrigin()">&nbsp;</td><td><div id=iOriginTxt style='padding-left:5px;background:#dddddd;'>center</div></td></tr>
    <tr><td class="xAxis">&nbsp;Origin&nbsp;Offset</td><td colspan=2><input id=perOriginOffset  type="text" data-slider="true" data-slider-range="-500,500" value="0" data-slider-step="1" onchange="gToolbar.setVars('originoffset',this.value,0)" /></td></tr>
    <tr><td class="xAxis"></td><td></td><td><div id=iOriginOffsetTxt style='padding-left:5px;background:#dddddd;'>0px</div></td></tr>
	</TABLE>
   </td></tr>
  <tr><td colspan=4 style='height:6px;'></td></tr>
  </TABLE>
 </td></tr>

</td></tr>

<tr><td colspan=2 style='height:20px;'></td></tr>

</TABLE>
</td></tr>

<tr><td id=i3DBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>
</TABLE>
</td></tr>




<!-- ================================= SCRAMBLE ==================================== -->
<tr id=iScrambleHdr style="DISPLAY:NONE;"><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
	<tr>
	<td><div style="height:24px;"><span id=iScrambleTxt class=toolcontrolHdr onclick="gToolbar.expandScrambleTools();">&nbsp;Phugly</span></div></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetScramble(1)"></td>
	</tr>
	<tr><td id=iScrambleBdr colspan=2 class='borderTop'></td></tr>
	<tr><td colspan=2>
	<TABLE id='iScrambleTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:6px;left:0px;'>
		<tr><td colspan=2 style='height:6px;'></td></tr>
	
		<!-- ---------------- Scramble Images ----------------------->
		<tr>
		<td width=80 class=ToolControlBold> &nbsp;&nbsp;&nbsp;Images</td>
		<td><input id=iScrambleImages type=checkbox onchange="gToolbar.flipScrambleImages(this.checked)" style="position:relative;top:2px;" /> </td>
		</tr>
		<tr><td colspan=2 style='height:6px;'></td></tr>

		<!-- -------------------- Frack ----------------------------->
<!--
		<tr id=iFrackHdr><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0>
			<tr>
			   <td width=95 class=toolControl onclick="gToolbar.expandFrack()">&nbsp;&nbsp;&nbsp;Frack</td>
			   <td><input id=frkWH type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',this.value,this.value)" /></td>
			   <td><img class=resetbtn src='_pvt_images/reset.png' onclick='gToolbar.applyClipRadius(0);gToolbar.setVars("frack",100,100);gToolbar.setVars("scale",0,0);gToolbar.syncControls();'></td>
			</tr>
			<tr>
			   <td colspan=2>
			   <TABLE id=iFrackTable cellspacing=0 cellpadding=0 style='display:none;'>
				    <tr><td width=40></td><td  width=60 class="xAxis">Width</td> <td><input id=frkW type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',this.value,null)" /></td></tr>
				    <tr><td></td><td class="xAxis">Height</td> <td><input id=frkH type="text" data-slider="true" data-slider-range="100,400" value="100" data-slider-step="1" onchange="gToolbar.setVars('frack',null,this.value)" /></td></tr>
			   </TABLE>
			   </td>
			</tr>
			<tr><td colspan=2 style='height:8px;'></td></tr>
		</TABLE>
		</td></tr>
-->
		<!-- -------------------- Split ------------------------------>
		<tr id=iSplitHdr><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0  style='width:100%;position:relative;left:6px;'>
			<tr>
			<td class=toolControl  onclick="gToolbar.expandControl(_obj('iBoxesTable'));" width=90>&nbsp;&nbsp;Box Tiling</td><td><input type="text" data-slider="true" data-slider-range="1,12" value="1" data-slider-step="1" id="ixxy"    onchange="_obj('ixy').innerHTML=this.value;gToolbar.tileBoxes()" /></td><td><span class="rangetext" id="ixy">1</span></td>
			</tr>
			<tr><td colspan=3>
				<TABLE id=iBoxesTable style="display:none;">
					<tr><td width=45></td><td class="xAxis" width=55>x-Boxes&nbsp;</td><td><input type="text" data-slider="true" data-slider-range="1,12" value="1" data-slider-step="1" id="ixxx"    onchange="_obj('ix').innerHTML=this.value;gToolbar.tileScreen(null,1)" /></td><td><span class="rangetext" id="ix">1</span></td></tr>
					<tr><td width=45></td><td class="xAxis" width=55>y-Boxes&nbsp;</td><td><input type="text" data-slider="true" data-slider-range="1,12" value="1" data-slider-step="1" id="iyyy"    onchange="_obj('iy').innerHTML=this.value;gToolbar.tileScreen(null,1)" /></td><td><span class="rangetext" id="iy">1</span></td></tr>
				</TABLE>
			</td></tr>
			<tr><td colspan=3 style='height:8px;'></td></tr>
			<tr>
			<td class=toolControlBold width=90>&nbsp;&nbsp;Box Split</td><td><input id=iScaleX  type="text" data-slider="true" data-slider-range="0,100" value="0" data-slider-step="1" onchange="gToolbar.setVars('scale',this.value,0)" /></td><td></td>
			</tr>
			
			<tr><td colspan=3><div style='height:10px;overflow:hidden;'></div></td></tr>
		</TABLE>
		</td></tr>
		
		<!-- ------------------------------ Screen  ------------------------->
		<tr id=iScreenHdr style="DISPLAY:;"><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
			<tr><td colspan=2>
			<DIV style="height:20px;">
			  <TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
			  <tr>
				   <td width=10 align=right></td>
				   <td width=87 class=toolControl onclick="gToolbar.expandControl(_obj('iScreenTable'));">&nbsp;Box&nbsp;Warp</td>
				   <td><input id=splZXY type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('warp',this.value,this.value);" /></td>
				   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.setVars('warp',50,50);gToolbar.syncControls();" ></td>
			  </tr>
			  </TABLE>
			</DIV>
			</td></tr>
			<tr><td colspan=2 style='height:2px;'></td></tr>
	
			<tr id=iScreenTable style='DISPLAY:none;'><td colspan=2>
			<TABLE cellpadding=0 cellspacing=0 style='width:100%;'>
				<tr><td colspan=2>
				<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
					  <tr><td width=30></td><td width=60 class="xAxis">&nbsp;&nbsp;Horizontal&nbsp;</td> <td><input id=splZX type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('warp',this.value,null)" /></td></tr>
				  	  <tr><td width=30></td><td width=60 class="xAxis">&nbsp;&nbsp;Vertical&nbsp;</td> <td><input id=splZY type="text" data-slider="true" data-slider-range="1,99" value="50" data-slider-step="1" onchange="gToolbar.setVars('warp',null,this.value)" /></td></tr>
				</TABLE>
				</td></tr>
				<tr><td colspan=2>
				<TABLE id=iScreenOptions cellspacing=0 cellpadding=0 style='width:100%;'>
					  <tr><td colspan=3><div style="height:4px;"</td></tr>
					  <tr><td width=30></td><td width=70>&nbsp;&nbsp;Global</td><td title=''><input id=iFullWarp type=checkbox onchange="gToolbar.flipFullWarp(this.checked)" checked>&nbsp;&nbsp;(full screen)</td></tr>
					  <tr><td></td><td>&nbsp;&nbsp;Offset&nbsp;Edge</td><td title=''><input id=iOffsetWarp type=checkbox onchange="gToolbar.flipOffsetWarp(this.checked)">&nbsp;&nbsp;(center vs edge)</td></tr>
				</TABLE>
				</td></tr>
			</TABLE>
			</td></tr>
			<tr><td colspan=2 style='height:8px;'></td></tr>
		</TABLE>
		</td></tr>
			
		<!-- ---------------------------- Skew ---------------------------------->
		<tr id=iSkewHdr style="DISPLAY:;"><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0 style="width:100%;">
			  <tr>
			   <td width=10 align=right></td>
			   <td width=87 class=toolControl onclick="gToolbar.expandControl(_obj('iSkewTable'));">&nbsp;Box&nbsp;Skew</td>
			   <td><input id=perSKXY type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.setVars('skew',this.value,this.value,1);"  /></td>
			   <td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetSkew(1)" ></td>
			  </tr>
			  <tr>
			   <td colspan=3>
			    <TABLE id=iSkewTable cellspacing=0 cellpadding=0 style='display:none;'>
				    <tr><td width=40></td><td width=70  class="xAxis">&nbsp;&nbsp;Horizontal</td>   <td><input id=perSKX type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.setVars('skew',this.value,null,1);"  /></td></tr>
				    <tr><td width=40></td><td  class="xAxis">&nbsp;&nbsp;Vertical</td>   <td><input id=perSKY type="text" data-slider="true" data-slider-range="-90,90" value="0" data-slider-step="1" onchange="gToolbar.setVars('skew',null,this.value,1);"  /></td></tr>
			    </TABLE>
			   </td>
			  </tr>
			  <tr><td colspan=3 style='height:6px;'></td></tr>
		</TABLE>
		</td></tr>

		<!-- -------------------- Clip ------------------------------->
		<tr id="iClipHdr"><td colspan=2>
		<TABLE cellspacing=0 cellpadding=0 style='width:100%;position:relative;left:6px;'>
			<tr>
			<td class=toolControlBold width=92>&nbsp;&nbsp;Clip</td>         <td><input id=clipRadius type="text" data-slider="true" data-slider-range="0,400" value="0" data-slider-step="1" onchange="gToolbar.applyClipRadius()"  /></td>
			</tr>
			<tr><td colspan=3><div style='height:6px;overflow:hidden;'></div></td></tr>
		</TABLE>
		</td></tr>
		
		<tr><td colspan=2 style='height:8px;'></td></tr>
		
	</TABLE>
	</td></tr>
	<tr><td id=iScrambleBdr2 colspan=2 class='borderBottom'></td></tr>
	<tr><td colspan=2 style='height:15px;'></td></tr>

</TABLE>
</td></tr>




<!-- ================================= OPTIONS ==================================== -->
<!--	Keep this in case we need another grouping all set up to go
<tr id=iOptionsHdr><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iOptionsTxt class=toolcontrolHdr onclick="gToolbar.expandOptionsTools();">&nbsp;Options</span></div></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetOptions(1)"></td>
</tr>
<tr><td id=iOptionsBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iOptionsTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:6px;left:15px;'>
	<tr><td colspan=2 style='height:6px;'></td></tr>
	<tr><td colspan=2 style='height:20px;'></td></tr>
</TABLE>
</td></tr>
<tr><td id=iOptionsBdr2 colspan=2 class='borderBottom'></td></tr>
<tr><td colspan=2 style='height:15px;'></td></tr>

</TABLE>
</td></tr>
-->



<!-- ================================= DEBUG ==================================== -->
<tr id=iDebugHdr style="DISPLAY:;"><td colspan=2>
<TABLE cellspacing=0 cellpadding=0 style='width:100%;'>
<tr>
	<td><div style="height:24px;"><span id=iDebugTxt class=toolcontrolHdr onclick="gToolbar.expandDebugTools();">&nbsp;Debug</span></div></td>
	<td align=right><img class=resetbtn src='_pvt_images/reset.png' onclick="gToolbar.resetDebug(1)"></td>
</tr>
<tr><td id=iDebugBdr colspan=2 class='borderTop'></td></tr>
<tr><td colspan=2>
<TABLE id='iDebugTools' cellspacing=0 cellpadding=0 style='width:100%;position:relative;top:6px;left:10px;'>
 <tr>
 <td><input class=dbgbtn type=button value="  test()  "   onclick="test()"></td>
 <td><input class=dbgbtn type=button value="   paint()   "   onclick="test2()"></td>
 </tr><tr>
 <td><input class=dbgbtn type=button value=" fixed nbrs  "   onclick="gPic.fixIXnbrs()"></td>
 <td><input class=dbgbtn type=button value=" Config txt  "   onclick="listConfig()"></td>
 </tr><tr>
 <td><input class=dbgbtn type=button value=" NATIVE Hst "   onclick="gPic.screen.history.print(0)"></td>
 <td><input class=dbgbtn  type=button value="MERGED Hst"   onclick="gPic.screen.history.print(1)"></td>
 <td></td>
 </tr> <tr>
 <td><input class=dbgbtn type=button value="  lockedby  "  onclick="lockedby()"></td>
 <td><input class=dbgbtn type=button value="list screens"   onclick="listScreens()"></td>
 </tr> <tr>
 <td colspan=2>x:<input type=text id=dbgxTxt1 value="#e28f46"></td>
 </tr><tr>
 <td colspan=2>y:<input type=text id=dbgyTxt2 value="#000000"></td>
 </tr><tr>
 <td colspan=2><textarea  id="iDebugText" style='width:228px;height:200px;'></textarea></td>
 <tr><td colspan=2 style='height:20px;'></td></tr>
 </tr>

 <tr style='DISPLAY:NONE;'>
 	<td colspan=2>Show Debug Borders&nbsp;<input id=ibdrs type=checkbox onchange="gToolbar.showBorders()"></td>
    <td></td>
 </tr> 
 <tr style="DISPLAY:NONE;"><td colspan=2>Canvas Style&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  	<select id=iusecanvas onchange="gToolbar.flipCanvas(this.options[this.selectedIndex].value)" style="font-size:10px;"/>
  	<option value=0>0 -Regular images</option>
  	<option value=1>1 -A single canvas</option>
  	<option value=2>2 -One canvas p/panel</option>
  	</select>
 </td></tr>
 <tr style="DISPLAY:NONE;"><td>Show Numbers&nbsp;&nbsp;<input id=inbrs type=checkbox onchange="gToolbar.showNumbers()"></td>
 	<td></td>
 </tr>
  
</TABLE>
</td></tr>

<tr><td id=iDebugBdr2 colspan=2 class='borderBottom'></td></tr>
</TABLE>
</td></tr>



</TABLE>
</td></tr>
<!-- ============================================================= -->

</TABLE>
</DIV>



<!-- ===================== COLOR BOX ===================== -->
<DIV id="iColorBox" unselectable="on" style="DISPLAY:NONE; background:white;position:absolute;left:100px;top:100px;width:130px;height:150px;z-index:9999;overflow:hidden;border:solid 1px black;cursor:default;text-align:center;">

<!--- all the colors --->
<span id="iColorSpan1" style="position:absolute;left:11px;top:16px; width:119px;height:128px;cursor:default;background:white;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
		<td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#e1ffff;width:12px;height:12px;"></td><td style="background:#cbffff;width:12px;height:12px;"></td><td style="background:#cbffff;width:12px;height:12px;"></td><td style="background:#e1ffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td></tr><tr><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#f6ffff;width:12px;height:12px;"></td><td style="background:#b4ffff;width:12px;height:12px;"></td><td style="background:#82ffff;width:12px;height:12px;"></td><td style="background:#66ffff;width:12px;height:12px;"></td><td style="background:#66ffff;width:12px;height:12px;"></td><td style="background:#82ffff;width:12px;height:12px;"></td><td style="background:#b4ffff;width:12px;height:12px;"></td><td style="background:#f6ffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td></tr><tr><td style="background:#ffefff;width:12px;height:12px;"></td><td style="background:#b4b0ff;width:12px;height:12px;"></td><td style="background:#66a3ff;width:12px;height:12px;"></td><td style="background:#27a8ff;width:12px;height:12px;"></td><td style="background:#0ec3e6;width:12px;height:12px;"></td><td style="background:#0ee6c3;width:12px;height:12px;"></td><td style="background:#27ffa8;width:12px;height:12px;"></td><td style="background:#66ffa3;width:12px;height:12px;"></td><td style="background:#b4ffaa;width:12px;height:12px;"></td><td style="background:#ffffef;width:12px;height:12px;"></td></tr><tr><td style="background:#ffc8ff;width:12px;height:12px;"></td><td style="background:#bb6eff;width:12px;height:12px;"></td><td style="background:#3418ff;width:12px;height:12px;"></td><td style="background:#0b30bf;width:12px;height:12px;"></td><td style="background:#086b8e;width:12px;height:12px;"></td><td style="background:#088e6b;width:12px;height:12px;"></td><td style="background:#0bbf30;width:12px;height:12px;"></td><td style="background:#34ff18;width:12px;height:12px;"></td><td style="background:#c1ff6e;width:12px;height:12px;"></td><td style="background:#ffffc8;width:12px;height:12px;"></td></tr><tr><td style="background:#ffb3ff;width:12px;height:12px;"></td><td style="background:#e054ff;width:12px;height:12px;"></td><td style="background:#5e00e6;width:12px;height:12px;"></td><td style="background:#2c008e;width:12px;height:12px;"></td><td style="background:#03103f;width:12px;height:12px;"></td><td style="background:#033f10;width:12px;height:12px;"></td><td style="background:#2c8e00;width:12px;height:12px;"></td><td style="background:#5ee600;width:12px;height:12px;"></td><td style="background:#e6ff54;width:12px;height:12px;"></td><td style="background:#ffffb3;width:12px;height:12px;"></td></tr><tr><td style="background:#ffb3ff;width:12px;height:12px;"></td><td style="background:#ff54ff;width:12px;height:12px;"></td><td style="background:#a300e6;width:12px;height:12px;"></td><td style="background:#73008e;width:12px;height:12px;"></td><td style="background:#43002f;width:12px;height:12px;"></td><td style="background:#432f00;width:12px;height:12px;"></td><td style="background:#738e00;width:12px;height:12px;"></td><td style="background:#a3e600;width:12px;height:12px;"></td><td style="background:#ffff54;width:12px;height:12px;"></td><td style="background:#ffffb3;width:12px;height:12px;"></td></tr><tr><td style="background:#ffc8ff;width:12px;height:12px;"></td><td style="background:#ff6eff;width:12px;height:12px;"></td><td style="background:#ff18ff;width:12px;height:12px;"></td><td style="background:#ca008f;width:12px;height:12px;"></td><td style="background:#970024;width:12px;height:12px;"></td><td style="background:#972400;width:12px;height:12px;"></td><td style="background:#ca8f00;width:12px;height:12px;"></td><td style="background:#ffff18;width:12px;height:12px;"></td><td style="background:#ffff6e;width:12px;height:12px;"></td><td style="background:#ffffc8;width:12px;height:12px;"></td></tr><tr><td style="background:#ffefff;width:12px;height:12px;"></td><td style="background:#ff9dff;width:12px;height:12px;"></td><td style="background:#ff54ff;width:12px;height:12px;"></td><td style="background:#ff188e;width:12px;height:12px;"></td><td style="background:#f30023;width:12px;height:12px;"></td><td style="background:#f32300;width:12px;height:12px;"></td><td style="background:#ff8e18;width:12px;height:12px;"></td><td style="background:#ffff54;width:12px;height:12px;"></td><td style="background:#ffff9d;width:12px;height:12px;"></td><td style="background:#ffffef;width:12px;height:12px;"></td></tr><tr><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffdcff;width:12px;height:12px;"></td><td style="background:#ff9dff;width:12px;height:12px;"></td><td style="background:#ff6ee0;width:12px;height:12px;"></td><td style="background:#ff5479;width:12px;height:12px;"></td><td style="background:#ff7454;width:12px;height:12px;"></td><td style="background:#ffda6e;width:12px;height:12px;"></td><td style="background:#ffff9d;width:12px;height:12px;"></td><td style="background:#ffffdc;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td></tr><tr><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffefff;width:12px;height:12px;"></td><td style="background:#ffc8ff;width:12px;height:12px;"></td><td style="background:#ffb3d5;width:12px;height:12px;"></td><td style="background:#ffd5b3;width:12px;height:12px;"></td><td style="background:#ffffc8;width:12px;height:12px;"></td><td style="background:#ffffef;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td>
	</tr></tbody></table>
</span>

<!-- for mouse events -->
<span id="iColorSpan2" style="position:absolute;left:11px;top:16px; width:119px;height:128px;cursor:default;cursor:hand;text-align:center;"></span>


<!-- opacity off --->
<span id="iColorOpOff" style="DISPLAY:NONE;position:absolute;left:0px;top:16px;width:12px; height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td id="tdop0" style="background:red;overflow:hidden;width:12px;height:16px;" onmouseover="CP.setOp(0)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td>
	</tr></tbody></table>
</span>

<!-- opacity --->
<span id="iColorOpacity" style="position:absolute;left:12;top:16;width:108px; height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;background:white;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td id="tdop9" style="filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9;width:14px;height:16px;background:red;" onmouseover="CP.setOp(9)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop8" style="filter:alpha(opacity=80);-moz-opacity:0.8;opacity:0.8;width:14px;height:16px;background:red;" onmouseover="CP.setOp(8)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop7" style="filter:alpha(opacity=70);-moz-opacity:0.7;opacity:0.7;width:14px;height:16px;background:red;" onmouseover="CP.setOp(7)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop6" style="filter:alpha(opacity=60);-moz-opacity:0.6;opacity:0.6;width:14px;height:16px;background:red;" onmouseover="CP.setOp(6)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop5" style="filter:alpha(opacity=50);-moz-opacity:0.5;opacity:0.5;width:14px;height:16px;background:red;" onmouseover="CP.setOp(5)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop4" style="filter:alpha(opacity=40);-moz-opacity:0.4;opacity:0.4;width:14px;height:16px;background:red;" onmouseover="CP.setOp(4)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop3" style="filter:alpha(opacity=30);-moz-opacity:0.3;opacity:0.3;width:14px;height:16px;background:red;" onmouseover="CP.setOp(3)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop2" style="filter:alpha(opacity=20);-moz-opacity:0.2;opacity:0.2;width:14px;height:16px;background:orange;" onmouseover="CP.setOp(2)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop1" style="filter:alpha(opacity=10);-moz-opacity:0.1;opacity:0.1;width:14px;height:16px;background:red;" onmouseover="CP.setOp(1)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td>
	</tr></tbody></table>
</span>

<!--- the grays --->
<span id="iColorGrays" style="position:absolute;left:0px;top:136px;width:130px;height:16px;cursor:default;overflow:hidden;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td style="background:#000000;width:12;height:16px;" onmousemove="CP.mv(null,'#000000')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#333333;width:12;height:16px;" onmousemove="CP.mv(null,'#333333')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#555555;width:12;height:16px;" onmousemove="CP.mv(null,'#555555')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#666666;width:12;height:16px;" onmousemove="CP.mv(null,'#666666')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#777777;width:12;height:16px;" onmousemove="CP.mv(null,'#777777')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#999999;width:12;height:16px;" onmousemove="CP.mv(null,'#999999')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#aaaaaa;width:12;height:16px;" onmousemove="CP.mv(null,'#aaaaaa')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#cccccc;width:12;height:16px;" onmousemove="CP.mv(null,'#cccccc')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#dddddd;width:12;height:16px;" onmousemove="CP.mv(null,'#dddddd')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#eeeeee;width:12;height:16px;" onmousemove="CP.mv(null,'#eeeeee')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#ffffff;width:10;height:16px;" onmousemove="CP.mv(null,'#ffffff')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td>
	</tr></tbody></table>
</span>

<!-- transparent box used for opacity only? -->
<!--
<span id="iColorTrans" style="position: absolute; left:120px; top:16px; width: 10px; height: 16px; cursor: pointer; overflow: hidden; font-family: monospace; font-size: 8pt; text-align: center; background-color: white;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td style="overflow:hidden;width:10px;height:16px;" onmouseover="CP.setTrans()" onmouseout="CP.resetTrans()" onmousedown="CP.updTrans()"></td>
	</tr></tbody></table>
</span>
-->

<!--- the color history (with defaults) --->
<span id="iColorHist" 	style="background:white;position:absolute;left:0px;top:16px; width:12px;height:120px;overflow:hidden;cursor:default;cursor:hand;text-align:center;">

</span>

<!-- other -->
<div id="iColorClose" 	style="position:absolute;left:-1px;top:-2px;width:14px;height:14px;font-size:12pt;font-weight:bold;font-family:monospace;cursor:pointer;color:red;" onclick="CP.close();try{BD.close();}catch(e){}">X</div>
<div id="iColorHeader" 	style="display:;position:absolute;left:30px;top:1px;width:80px;background:white;font-family:sans-serif;font-size:8pt;color:#999999;"></div>


<!-- transparent box used for opacity only? -->
<div id="iCPHeader" 	style="position:absolute;right:0px;top:1px;width:80px;background:white;font-family:sans-serif;font-size:8pt;color:#999999;">
	<img id="iColorTrans" src="_pvt_images/trans.gif" onmouseover="CP.setTrans()" onmouseout="CP.resetTrans()" onmousedown="CP.updTrans()" style="position:absolute;right:3px;top:1px;width:20px;cursor:pointer;" title="transparent">
</div>

</DIV>


<script src="_pvt_js/_colors.js"></script>

<!-- ====================================== START _PIXITOOLBAR.JS =================================================== -->
<!-- INCLUDED HERE FOR EASIER DEVELOPMENT! -->
<script>


// Copyright, 2005, Walter Long 


//alert("_pixitoolbar.js 8");

//============================================================================================================================================================
//============================== TOOLBAR OBJECT ==============================================================================================================
//============================================================================================================================================================
function Toolbar(menucontrols,frame){
this.syncing=1;
this.menucontrols=menucontrols;
this.frame=frame;
this.mode="unlocked";
this.modes=new Array();
this.menuVisible=0;
this.currentScr=null;
//--- Modes (legacy var names fix!) ---
if(gEmbedded){
	for(var i=0;i<parent.gModeBtns.length;i++)this.modes[parent.gModeBtns[i]]=parent.gModeBtns[i];  
	this.modes["Warp"]  ="Split";
	this.modes["Panel"] ="Split";
	this.modes["Spin"]  ="Rotate";
}}


//------------------- Menu Controls-------------------------		//gEmbedded?
Toolbar.prototype.openMenuControls=function(force,norepaint){
if(force==null)force=(this.menucontrols.style.display=="block")?0:1;
if(!force){
	 this.menucontrols.style.display="none";
	 try{ parent._obj("iMenuControlsBtn").src="_pvt_images/arrow_green_left.png"; }catch(e){}
}else{
	 this.menucontrols.style.display="block";
	 try{ parent._obj("iMenuControlsBtn").src="_pvt_images/arrow_red_right.png";  }catch(e){}
}
this.resize();
if(!norepaint)gPic.repaintAllSizes(); 
}

//------------------- floating handles -------------------------
Toolbar.prototype.showHandles=function(show){
var scr=gPic.screen;
var lh=_obj("iBoxBdrLeft").style;
var rh=_obj("iBoxBdrRight").style;
var th=_obj("iBoxBdrTop").style;
var bh=_obj("iBoxBdrBottom").style;
var hh=_obj("iDragBtn").style;
if(show==null)show=(lh.display!="none")?0:1;
gPic.handles=show;
//_obj("iHandlesBtn").src=(!show)?"_pvt_images/handles.png":"_pvt_images/handlesClose.png";
if(!show){
 lh.display=rh.display=th.display=bh.display=hh.display="none";
 return;
}
//if(scr.typ=="lock")scr=scr.lockedByScreen;
var dv=scr.frame;
th.top=(getTop(dv)-26)+"px";
lh.top=rh.top=(getTop(dv)-2)+"px";
th.left=lh.left=bh.left=(getLeft(dv)-5)+"px";
th.width=bh.width=(eleWidth(dv)+9)+"px";
bh.top=((getTop(dv)+eleHeight(dv))-1)+"px";
lh.height=rh.height=(eleHeight(dv)+6)+"px";
rh.left=((getLeft(dv)+eleWidth(dv))-1)+"px";
hh.left=((getLeft(dv)+eleWidth(dv))-6)+"px";
hh.top=((getTop(dv)+eleHeight(dv))-6)+"px";
th.display=lh.display=rh.display=bh.display=hh.display="block";
}

//gPic.handles=show;
Toolbar.prototype.syncHandles=function(){
if(!gEmbedded)return;
//if(scr!=gPic.screen)return;
try{if(_obj("iBoxBdrLeft").style.display!="none")this.showHandles(1);}catch(e){}
}

//------- toolbar/picture resize ------
Toolbar.prototype.resize=function(){
var scr=gPic.screen;
var oldscrix=scr.scrix;
//if(scr.lockedByScreen && !scr.hideImage){	
//	scr=gPic.getLockParent(scr);
//	this.changeScreen(scr.scrix);
//}	
var div=this.frame;
var w=(docWidth())*1;
var h=(docHeight())*1;
div.style.top=0;
div.style.height=h;
if(this.menucontrols.style.display!="block"){
	 div.style.left="0px";
	 div.style.width=w;
}else{
	 div.style.left="0px";
	 div.style.width=""+(w-this.menucontrols.offsetWidth)+"px";  
}
gPic.setNaturalSizes(1); 
//gPic.setSquareSizes(1);
if(oldscrix!=gPic.scrix && !gLoaded){
	this.changeScreen(oldscrix);
}
}



//============================== TOOLBAR CONFIGURATION ==========================

//--- configure toolbar ----
Toolbar.prototype.configToolbar=function(scr){
if(!gEmbedded)return;
if(!scr)scr=gPic.screen;
var locktyp=scr.lockTyp;
var hideimg=scr.hideImage;
var media=scr.media;
//--- turn everything on by default ----
this.toggleImagingHidden(1);
_obj("iEditBtn").style.display="none";
// now turn off where need be
if(media!="image"){	// video, text
	this.toggleImaging(0);
   	if(media=="video")setOpacity(_obj("iCurrentEle"),50);
	if(gLoggedIn && media=="file")_obj("iEditBtn").style.display="";
}else{
	if(hideimg){	// image hidden
		this.toggleImagingHidden(0);
	}else{			// visible image
		if(scr.Tiling==gNONEtile)this.toggleImagingNoTiling(0);
	}
}
// other special cases 
//if(scr.typ=="color")parent._obj("iRotateScreen").style.display	="none"; // locked to the background
//if(scr.useCanvas!=1)this.togglePixelHdr(0);
this.configHandles();	
this.applyLabel(scr);
this.configLayers();
}


Toolbar.prototype.scrollLayers=function(n){
if(this.layersCnt<5){ this.configLayers(); return; }
this.layersStart+=n;
if(this.layersStart<0)this.layersStart=0;
if((this.layersCnt-this.layersStart)<5)this.layersStart=this.layersCnt-5;
_obj("iLayersBox").style.left=(this.layersStart*48)*-1;
_obj("iLayersScrollRight").style.display=(this.layersStart>(this.layersCnt-6))?"none":"";
_obj("iLayersScrollLeft").style.display =(this.layersStart==0)?"none":"";
}


Toolbar.prototype.configLayers=function(){
var layers=new Array();
var j=0;
if(!this.layersStart)this.layersStart=0;
for(var i=0;i<gPic.screens.length;i++){
	var scr=gPic.screens[i];
    if(!scr.DELETED){
		layers[j]=scr.zindex+"-"+scr.scrix; 
		j++;
	}
}
this.layersCnt=j;
_obj("iLayersScrollRight").style.display=(j<6 || this.layersStart==(this.layersCnt-5))?"none":"";
_obj("iLayersScrollLeft").style.display =(j<6 || this.layersStart==0)?"none":"";
layers=layers.sort();
//msg("layers="+layers);
var txt="<table cellpadding= cellspacing=0><tr>";
for(var i=layers.length-1; i>-1; i--){
	var ix=layers[i].split("-")[1]*1;
	var scr=gPic.screens[ix];
    if(!scr.DELETED){
		//if(scr==gPic.screen)this.layersCurrent=i;
		var color=(scr==gPic.screen)? "#0ea7e9" : "#eee" ;
		var opacity="";
		txt+="<TD><DIV id=iLayer"+scr.scrix+" style='cursor:pointer;width:38px;height:35px;overflow:hidden;"+
			 "background:"+scr.frame.style.background+";border:solid 2px "+color+";padding:2px;"+
			 "text-align:center;";
		if(scr.hideImage){
			if(scr.shadowsOn){
			    var c=scr.shadows.split(" ");
				var s="0px 0px 6px 3px "+c[5]+" inset"; 
				txt+="box-shadow:"+s+";";
			}
			opacity="opacity:0.2;";
		}
		txt+="'>";
		txt+="<img src='"+scr.gImg.src+"' onclick='gToolbar.changeScreen("+scr.scrix+");' "+
			 "style='width:37px;height:35px;"+
			 opacity+"'>";
		txt+="</DIV></TD>";
	}
}
txt+="</tr></table>";
_obj("iLayersBox").innerHTML=txt;
}

Toolbar.prototype.updateLayerThumb=function(scr){
var s="";
if(scr.shadowsOn){
    var c=scr.shadows.split(" ");
	s="0px 0px 6px 3px "+c[5]+" inset"; 
}
_obj("iLayer"+scr.scrix).style.boxShadow=s;
}



Toolbar.prototype.configHandles=function(){
var handles=_obj("iShowHandles").style;
if(gPic.screen.isFullscreen()){
	handles.cursor="default";
	handles.opacity="0.2";
}else{
	handles.cursor="pointer";
	handles.opacity="1";
}}

Toolbar.prototype.toggleImagingHidden=function(x){	// media=image but it's hidden
	this.toggleImaging(x);
	//this.toggleColors(x); //could have shadows
	this.toggleTurnBtns(x);
	/* keep these because it might be used for shadows
	_obj("iSlideHdr").style.display	=(x)?"":"none";
	_obj("iZoomHdr").style.display	=(x)?"":"none";
	_obj("iSpinHdr").style.display	=(x)?"":"none";
	_obj("iTiltHdr").style.display	=(x)?"":"none";
	*/
	parent._obj("iPlayBack").style.display=parent._obj("iPlayPause").style.display=parent._obj("iPlayFwd").style.display=(x)?"":"none";
}


Toolbar.prototype.toggleImaging=function(x) {	// video or text or image hidden
	this.toggleImagingNoTiling(x);
	this.togglePixelHdr(x);	
	this.toggleTileHdr(x);
	this.toggleImagingBtns(x);
	//this.toggleScrambleHdr(x);
	this.toggleImagingBtns(x);
	_obj("iFoldHdr").style.display=(x)?"":"none";
	_obj("iSplitHdr").style.display=(x)?"":"none";
	_obj("iSkewHdr").style.display=(x)?"":"none";
	_obj("iDominantBg").style.display=(x)?"":"none";
	_obj("iDominantShadows").style.display=(x)?"":"none";
}



Toolbar.prototype.toggleImagingNoTiling=function(x){	// no tiling but still have an image
	_obj("iScreenHdr").style.display=(x)?"":"none";
	_obj("iPanelHdr").style.display=(x)?"":"none";
	_obj("iImageHdr").style.display=(x)?"":"none";
	_obj("iPanelSkewHdr").style.display=(x)?"":"none";
}

Toolbar.prototype.toggleImagingBtns=function(x){	
	//@@@parent._obj("iSquareSize").style.display=(x)?"":"none";
	_obj("iNaturalSizeBtn").style.display=(x)?"":"none";
	//@@@parent._obj('iTilingPatterns').style.display=(x)?"":"none";
	parent._obj('iModeBtns').style.display=(x)?"":"none";
	//_obj('iTakeLayerPic').style.display=(x)?"":"none";
}	


//---------------------- CONFIG VISIBILITY ------------------------

//----- major groups ----
//Toolbar.prototype.togglePicHdr=function(x)		{_obj("iPicHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleFrameHdr=function(x)	{_obj("iFrameHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleColorsHdr=function(x)	{_obj("iColorsHdr").style.display=(x)?"":"none";}
Toolbar.prototype.togglePixelHdr=function(x)    {_obj("iPixelHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggle3DHdr=function(x)		{_obj("i3DHdr").style.display=(x)?"":"none";}
Toolbar.prototype.toggleTileHdr=function(x)		{_obj("iTileHdr").style.display=(x)?"":"none"; }
Toolbar.prototype.toggleRekordHdr=function(x)	{_obj("iRekordHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleScrambleHdr=function(x)	{_obj("iScrambleHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleOptionsHdr=function(x)	{_obj("iOptionsHdr").style.display=(x)?"":"none";}
Toolbar.prototype.toggleDebugHdr=function(x)	{_obj("iDebugHdr").style.display=(x)?"":"none";}


//----- minor groups ----
//Toolbar.prototype.toggleShadows=function(x)          	{_obj("iShadowsHdr").style.display=(x)?"":"none";}
Toolbar.prototype.toggle3DTools=function(x)        		{_obj("i3DTools").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleSlide=function(x)         		{_obj("iSlideHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleScreenOptions=function(x)		{_obj("iScreenOptions").style.display=(x)?"":"none";}

//Toolbar.prototype.toggleResize=function(x)          	{_obj("iResizeHdr").style.display=parent._obj("iNaturalSizeBtn").style.display=parent._obj("iFullScreen").style.display=(x)?"":"none";}
Toolbar.prototype.toggleColors=function(x)          	{_obj("iColorHdr").style.display=(x)?"":"none";}
//Toolbar.prototype.toggleBlend=function(x)          		{_obj("iBlendHdr").style.display=(x)?"":"none";}


Toolbar.prototype.toggleOpacity=function(x)          	{_obj("iOpacityHdr").style.display=(x)?"":"none";}
Toolbar.prototype.toggleBackground=function(x)    		{_obj("iBackgroundHdr").style.display=(x)?"":"none";}
Toolbar.prototype.toggleCorners=function(x)          	{_obj("iShapeHdr").style.display=(x)?"":"none";}



Toolbar.prototype.toggleTurnBtns=function(x){
	// move to toolbar.php
	//_obj("iTurnHdr").style.display=_obj("iTurnBtn").style.display=_obj("iTurnBtns").style.display=(x)?"":"none";
	_obj("iTurnHdr").style.display=_obj("iTurnBtns").style.display=(x)?"":"none";
}


Toolbar.prototype.flipNaturalSize=function(v){
var scr=gPic.screen;
if(v)scr.natural=v;
else scr.natural=(scr.natural)?0:1;
if(scr.natural){
	scr.naturalSize();  
	scr.square=0;
}else{
	if(scr.xSize==scr.xSizeSave){
		scr.xSize=100;
  		scr.ySize=100;
	}else{
		scr.xSize=scr.xSizeSave;
  		scr.ySize=scr.ySizeSave;
	}
	gPic.repaintSizes(scr.scrix);
}
this.configHandles();
this.syncFullscreenBtns();
this.syncSizes(); 
}

Toolbar.prototype.flipSquareSize=function(v){
var scr=gPic.screen;
var oldix=scr.scrix;
if(scr.typ=="lock"){
	scr=gPic.getLockParent(scr);
	this.changeScreen(scr.scrix,oldix);
}
if(v)scr.square=v;
else scr.square=(scr.square)?0:1;
if(scr.square){
	scr.squareSize();
	scr.natural=0;
	gPic.repaintSizes(scr.scrix);
}
this.syncFullscreenBtns();
this.syncSizes(); 
}


//-------------------------- expand/contract controls --------------------------

Toolbar.prototype.expandBox=function(name,v,only){
var close; 
var tbl=_obj(name+"Table");
var id=tbl.id;
if(v==null)v=(tbl.style.display!="none")?0:1;
if(!only && v==1 && _in(id,"Tools"))this.expandAll(0);

try{
	var hdr=_obj(name+"HdrTable");
	hdr.style.border=(v)?"solid 1px #bbb" : "solid 1px #fff";
	hdr.style.background=(v)?"#eee" : "#fff";
}catch(e){}

tbl.style.display=(v)?"inline":"none";
if(_in(id,"Tools")){
	var a=id.split("Tools");
	var txt=_obj(a[0]+"Txt");
	var topbdr=_obj(a[0]+"Bdr");
	var btmbdr=_obj(a[0]+"Bdr2");
	txt.style.color=(v)?"red":"";
	topbdr.className=(v)?"borderHilite":"borderTop";
	btmbdr.className=(v)?"borderHilite":"borderBottom";
}
return v;
}


Toolbar.prototype.expandControl=function(tbl,v,only){
var close; 
var id=tbl.id;
if(v==null)v=(tbl.style.display!="none")?0:1;
if(!only && v==1 && _in(id,"Tools"))this.expandAll(0);
tbl.style.display=(v)?"inline":"none";
if(_in(id,"Tools")){
	var a=id.split("Tools");
	var txt=_obj(a[0]+"Txt");
	var topbdr=_obj(a[0]+"Bdr");
	var btmbdr=_obj(a[0]+"Bdr2");
	txt.style.color=(v)?"red":"";
	topbdr.className=(v)?"borderHilite":"borderTop";
	btmbdr.className=(v)?"borderHilite":"borderBottom";
}
return v;
}



Toolbar.prototype.expandAll=function(v){
this.expandViewTools(v,1);
this.expandPicTools(v,1);
this.expandFrameTools(v,1);
this.expandColorsTools(v,1);
//this.expandPixelTools(v,1);
this.expand3DTools(v,1);
this.expandTileTools(v,1);
this.expandRekordTools(v,1);
this.expandScrambleTools(v,1);
//this.expandOptionsTools(v,1);
this.expandDebugTools(v,1);
}

//----- major groups -----
Toolbar.prototype.expandViewTools=function(v,only)    {v=this.expandControl(_obj("iViewTools"),v,only); 		set("PicTools",v); }
Toolbar.prototype.expandPicTools=function(v,only)     {v=this.expandControl(_obj("iPicTools"),v,only); 		set("PicTools",v); }
Toolbar.prototype.expandFrameTools=function(v,only)	  {v=this.expandControl(_obj("iFrameTools"),v,only); 	set("FrameTools",v); }
Toolbar.prototype.expandColorsTools=function(v,only)  {v=this.expandControl(_obj("iColorsTools"),v,only); 	set("FrameTools",v); }
//Toolbar.prototype.expandPixelTools=function(v,only)	  {v=this.expandControl(_obj("iPixelTools"),v,only); 	set("PixelTools",v); }
Toolbar.prototype.expand3DTools=function(v,only)	  {v=this.expandControl(_obj("i3DTools"),v,only); 		set("3DTools",v); }
Toolbar.prototype.expandTileTools=function(v,only)    {v=this.expandControl(_obj("iTileTools"),v,only); 	set("TileTools",v);    }
Toolbar.prototype.expandRekordTools=function(v,only)  {v=this.expandControl(_obj("iRekordTools"),v,only); 	set("RekordTools",v); }
Toolbar.prototype.expandScrambleTools=function(v,only){v=this.expandControl(_obj("iScrambleTools"),v,only); set("ScrambleTools",v); }
//Toolbar.prototype.expandOptionsTools=function(v,only) {v=this.expandControl(_obj("iOptionsTools"),v,only);	set("OptionsTools",v); }
Toolbar.prototype.expandDebugTools=function(v,only)   {v=this.expandControl(_obj("iDebugTools"),v,only); 	set("DebugTools",v); }

//----- minor groups -----
Toolbar.prototype.expandViewColor=function(v)  {this.expandControl(_obj("iViewColorTable"),v);}
Toolbar.prototype.expandViewBackground=function(v)  {this.expandControl(_obj("iViewBackgroundTable"),v);}
Toolbar.prototype.expandViewColor=function(v)  {this.expandControl(_obj("iViewColorTable"),v);}
Toolbar.prototype.expandZoom=function(v)       {this.expandControl(_obj("iZoomTable"),v);}
Toolbar.prototype.expandTurn=function(v)       {this.expandControl(_obj("iTurnTable"),v);}
Toolbar.prototype.expandTilt=function(v)       {this.expandControl(_obj("iTiltTable"),v);}
//Toolbar.prototype.expandFrack=function(v)      {this.expandControl(_obj("iFrackTable"),v);}
Toolbar.prototype.expandResize=function(v)     {this.expandControl(_obj("iResizeTable"),v);}
Toolbar.prototype.expandMove=function(v)       {this.expandControl(_obj("iMoveTable"),v);}
Toolbar.prototype.expandCorners=function(v)    {this.expandControl(_obj("iCornersTable"),v);}
Toolbar.prototype.expandImageTools=function(v) {this.expandControl(_obj("iImageTable"),v);}
Toolbar.prototype.expandScreenTools=function(v){this.expandControl(_obj("iScreenTable"),v);}
Toolbar.prototype.expandPanelTools=function(v) {this.expandControl(_obj("iPanelTable"),v);}
Toolbar.prototype.expandFoldTools=function(v)  {this.expandControl(_obj("iFoldTable"),v);}
Toolbar.prototype.expandSkew=function(v)       {this.expandControl(_obj("iSkewTable"),v);}
Toolbar.prototype.expandPanelSkew=function(v)  {this.expandControl(_obj("iPanelSkewTable"),v);}
Toolbar.prototype.expandSlide=function(v)      {this.expandControl(_obj("iSlideTable"),v);}
Toolbar.prototype.expandTiltImg=function(v)    {this.expandControl(_obj("iTiltImgTable"),v);}
Toolbar.prototype.expandTilt=function(v)       {this.expandControl(_obj("iTiltTable"),v);}
//Toolbar.prototype.expandShadows=function(v)    {this.expandControl(_obj("iShadowsTable"),v); }
Toolbar.prototype.expandBackground=function(v) {this.expandControl(_obj("iBackgroundTable"),v); }


//------------ reset Rights ---------------------
Toolbar.prototype.resetRights=function(upd){
alert("resetRights ever called???");
	_obj("iSaveRekordBtn").style.display=(upd)?"":"none";
}





//============================== HISTORY =============================


Toolbar.prototype.record=function(v){
gPic.screen.history.recordAction(v);
}

Toolbar.prototype.slideHistory=function(v){
gPic.screen.history.slide(v);
}

Toolbar.prototype.resetHistory=function(all){
gPic.screen.history.reset(all);
}

Toolbar.prototype.setSmooth=function(v){
if(this.syncing)return;
var scr=gPic.screen;
if(v==null)v=_obj("iRekSmooth").checked;
//_obj("iRekMergeTD").style.display=(v)?"":"none";
_obj("iRekDeltaTR").style.display=(v)?"":"none";
scr.history.setSmooth(v);
var speed = _obj("iHistSpeed").value;
if(speed>50 && v)    this.histPlaySpeed(30);
if(speed<100  && !v) this.histPlaySpeed(450);
}

Toolbar.prototype.setRekRelative=function(v){
if(this.syncing)return;
var scr=gPic.screen;
if(v==null)v=_obj("iRekRelative").checked;
scr.history.setRekRelative(v);
}

Toolbar.prototype.setMerge=function(v){   
if(this.syncing)return;
var scr=gPic.screen;
if(v==null)v=_obj("iRekMerge").checked;
scr.history.setMerge(v);
}

Toolbar.prototype.historyLoopMode=function(){   
if(this.syncing)return;
var scr=gPic.screen;
var mode=0;
var tmp=document.getElementsByName("iRekLoop");
for(var i=0;i<tmp.length;i++){
 if(tmp[i].checked){
  mode=i;
  break;
}}
switch(mode){
 case 0: scr.history.loopmode="oscillate"; break;
 case 1: scr.history.loopmode="restart"; break;
 case 2: scr.history.loopmode="stop"; break;
}}

Toolbar.prototype.histPlaySpeed=function(speed){  
if(this.syncing)return;
this.syncing=1;
_obj("iHistSpeedTxt").value=speed;
this.setVal("iHistSpeed",speed); 
gPic.screen.history.timeout=speed;
this.syncing=0;
gPic.screen.history.smoothed=0;
}

Toolbar.prototype.historyPlaySpeed=function(speed){  
if(this.syncing)return;
this.syncing=1;
try{
	_obj("iRekSpeedTxt").value=speed;
	this.setVal("iRekSpeed",speed); 
	gPic.screen.animation.timeout=speed;
}catch(e){}
this.syncing=0;
}

Toolbar.prototype.historyPlayCurve=function(curve){  
if(this.syncing)return;
this.syncing=1;
try{
	this.setVal("iRekCurve",curve); 
	gPic.screen.history.curve=curve;
}catch(e){}
this.syncing=0;
gPic.screen.history.smoothed=0;
}


//-------------------------------- Save Recording ----------------------------------  // savepixi
Toolbar.prototype.saveRekord=function(name,x){
if(!name)return;
gPic.aniName=name;
asyncP("action=saverekord&dir="+parent.gDir+"&name="+name+"&text="+escape(x),"saverekord");
//alert("saving rekord name="+name);
}


//======================== ANIMATION ================================

Toolbar.prototype.aniXchg=function(v){
	if(v==null)v=_obj("iAniX").checked;
	gPic.screen.animation.aniXon=(v==true)?1:0;
	if(v==false && !gPic.screen.animation.aniYon)_obj("iAniY").checked=true;
}

Toolbar.prototype.aniYchg=function(v){
	if(v==null)v=_obj("iAniY").checked;
	gPic.screen.animation.aniYon=(v==true)?1:0;
	if(v==false && !gPic.screen.animation.aniXon)_obj("iAniX").checked=true;
}

Toolbar.prototype.histPlayDelta=function(v){
if(this.syncing)return;
this.syncing=1;
var scr=gPic.screen;
if(v==null)v=_obj("iHistDelta").value;
v=_round(v,2);
this.setVal("iHistDelta",v);
_obj("iHistDeltaTxt").value=v;
this.syncing=0;
scr.history.delta=v;
scr.history.smoothed=0;
}

Toolbar.prototype.historyPlayDelta=function(v){
if(this.syncing)return;
this.syncing=1;
var scr=gPic.screen;
if(v==null)v=_obj("iRekDelta").value;
v=_round(v,2);
this.setVal("iRekDelta",v);
_obj("iRekDeltaTxt").value=v;
this.syncing=0;
scr.animation.delta=v;
scr.animation.speedx=v;
scr.animation.speedy=v;
}

Toolbar.prototype.historyRange=function(){
if(this.syncing)return;
var scr=gPic.screen;
var xmin=parseInt(_obj("iXRekRangeMin").value);
var xmax=parseInt(_obj("iXRekRangeMax").value);
var ymin=parseInt(_obj("iYRekRangeMin").value);
var ymax=parseInt(_obj("iYRekRangeMax").value);
//--- for some reason if these haven't changed we get a NaN ---
if(xmin==xmin){ scr.animation.xrangemin=xmin; scr.animation.xrange_min=xmin; }
if(xmax==xmax){ scr.animation.xrangemax=xmax; scr.animation.xrange_max=xmax; }
if(ymin==ymin){ scr.animation.yrangemin=ymin; scr.animation.yrange_min=ymin; }
if(ymax==ymax){ scr.animation.yrangemax=ymax; scr.animation.yrange_max=ymax; }
}




//========================= ADD LAYER FUNCTIONS =========================



//---------------------- addLayer --------------------------
Toolbar.prototype.addLayer=function(dir,src,media){
	if(media==null)media=_fileType(src.toLowerCase());
	//msg("media="+media+", dir="+dir+", src="+src);
	switch(media){
		case "image" :
		case "file" :
			src=getSrc(dir,src);
			//msg("src="+src);
			try{ parent.frames[0].selectCurrent(src); }catch(e){}
			gPic.addScreen(null,null,media,dir);
			gPic.screen.addImg(src,"",dir);
			break;
		case "video" :
			//--- causes  problems but works :  1. clicking on the video is passed thru to the parent  2.saved in a config doesn't load properly
			try{ if(!src)src=parent.frames[0].getRandomVideo(); }catch(e){}
			if(!src)src="@vid@My2FRPA3Gf8";
			try{ parent.frames[0].selectCurrent(src); }catch(e){}
			gPic.addScreen(null,null,"video");
			gToolbar.setPlayPauseBtn(gPic.playing);
			gPic.screen.addImg(src,"",gPic.gDir,0);
			break;
	}
}



//---------- addImageLayer -------------  
Toolbar.prototype.addImageLayer=function(typ,locktyp){
if(!typ)typ="";
if(!locktyp)locktyp="";
if(locktyp=="filter" && gPic.screen.media!="image")return;
gPic.addScreen(typ,locktyp);
gPic.screen.loadImage();
}


//---------------------- addText --------------------------
Toolbar.prototype.addText=function(dir,src,typ){
var scr=gPic.screen;
if(dir==null)dir=gDir;
if(typ==null)typ="";
src=getSrc(dir,src);
parent.frames[0].selectCurrent(src);
gPic.addScreen(typ,typ,"file",dir);	
gPic.screen.addImg(src);
}

//------------------ add Video --------------------------------
Toolbar.prototype.addVideo=function(src,child){
if(!src){ try{ src=parent.frames[0].getRandomVideo();	}catch(e){}  }
try{ parent.frames[0].selectCurrent(src); }catch(e){}
if(!src)src="@vid@My2FRPA3Gf8";
if(child)gPic.addScreen("child","child","video");
else 	 gPic.addScreen("","","video");
gToolbar.setPlayPauseBtn(gPic.playing);
gPic.screen.addImg(src,"",gPic.gDir,0);
}


//=================VIEW =================


Toolbar.prototype.setBgColor2=function(event){
gPic.gradientType="flat";
this.syncing=1;
this.syncGradient("view");
this.syncing=0;
CP.open(window,gPic.desktop,'frameColor',_getLeftClick(event)-350,_getTopClick(event)-17,gPic.setDesktopColor,0,gPic.desktopColor);
}

Toolbar.prototype.resetView=function(){  
// don't set syncing!
this.viewResetZoom();
this.viewResetSlide();
this.viewResetBlend();
this.viewResetTilt();
this.viewResetSpin();
this.viewResetColors();
}

Toolbar.prototype.viewResetZoom=function(){  
this.setVal("iViewZoom",100);
}

Toolbar.prototype.viewResetSlide=function(){ 
this.syncing=1; 
this.setVal("sldViewX",0);
this.setVal("sldViewX",0);
this.syncing=0; 
this.setVal("sldViewXY",0);
}

Toolbar.prototype.viewResetBlend=function(v){
gToolbar.setVal('iViewBlend',0);
}

Toolbar.prototype.viewResetTilt=function(){
this.syncing=1;
gToolbar.setVal('iViewTiltX',0);
gToolbar.setVal('iViewTiltY',0);
this.syncing=0;
gToolbar.setVal('iViewTilt',0);
}

Toolbar.prototype.viewResetSpin=function(){
gToolbar.setVal('iViewSpin',0);
}

Toolbar.prototype.viewResetColors=function(){
//this.syncing=1;
this.resetCSSVals("view");
//this.syncing=0;
}


//------------------view sync controls with current gPic -------------------------   
Toolbar.prototype.viewSyncControls=function(){
if(!gEmbedded)return;
this.syncing=1;
this.syncCSSFilters("view");
this.syncGradient("view");
this.setVal("ssTimer",gPic.pauseDelay);
_obj("iusecanvas").selectedIndex=gPic.useCanvas;
this.setVal('iViewZoom',gPic.xViewZoom);
this.setVal('iViewBlend',gPic.blend);
this.setVal("sldViewXY",gPic.xViewSlide);
this.setVal("sldViewX", gPic.xViewSlide);
this.setVal("sldViewY", gPic.yViewSlide);
var str=gPic.perspective;
//msg("str="+str);
//msg("2drot="+gPic.getPerTilt(str));
this.setVal('iViewSpin', gPic.getPerRotate(str));
this.setVal('iViewTilt', gPic.getPerSkewX(str));
this.setVal('iViewTiltX',gPic.getPerSkewX(str));
this.setVal('iViewTiltY',gPic.getPerSkewY(str));
this.syncing=0;
}



//==================================== SYNCING AND SETVARS ====================================

Picture.prototype.viewSetVars=function(n,x,y) { 
if(this.syncing)return; 
var scr=gPic.screen;
scr.history.recordAction("gPic.viewSetVars('"+n+","+x+","+y+");");
switch(n){
	case "viewzoom"		: 	
		this.viewZoom(x);
		break;
	case "viewslide"	: 	
		this.viewSlide(x,y);
		break;
	case "viewblend"	: 	
		this.viewBlend(x);
		break;
	case "view2drot"	:
		this.viewPerspective(null,null,null,x);	
		break;
	case "viewswivel"	:
		this.viewPerspective(null,null,null,null,x,y);
		break;
	case "opacity2" 	:	
		this.viewOpacity2(x);  
		break; 
	case "saturate" :
	case "contrast" :
	case "brightness" :
	case "blur" :
	case "sepia" :
	case "hue-rotate" :
	case "grayscale" :
	case "invert" :
		this.applyCSSFilter(gPic,n,x); 		
		break;
	
}}



//( "image","warp","split","panelskew","fold","2drot","swivel","saturate","brightness","sepia","contrast");
var vars=new Array( "image","warp","split","panelskew","2drot","saturate","brightness");
//var vars=new Array("2drot");

function feedNbrs(){
var nbrs="";
for(var k=0; k<vars.length; k++){
	var x=_rdm(30,70);
	var y=_rdm(30,70);
	nbrs+=""+x;
	nbrs+=""+y;
}
gPic.screen.feed(nbrs);
gToolbar.syncControls();
}

Screen.prototype.feed=function(nbrs){
var scr=gPic.screen;
//msg(nbrs);
var k=0;
for(var i=3; i<nbrs.length && k<vars.length; i+=4){
	var x=parseInt( nbrs.charAt(i-3) + nbrs.charAt(i-2) ,10);
	var y=parseInt( nbrs.charAt(i-1) + nbrs.charAt(i) ,10);
	var name=vars[k];
	switch(name){
		case "2drot" 	:
			gToolbar.chgD3Mode(1);
			x-=50; x=Math.round(x/2);
			y-=50; y=Math.round(y/2);
			scr.xZoom=scr.yZoom=57;
			break
		case "panelskew":
			x-=50;
			y-=50;
			break
		case "saturate":
		case "brightness":
			x+=65;
			break
		case "contrast": 	continue; x+=80; break
		case "sepia": 		continue; x=(x>50)?100:0; break
		case "fold" : 		continue; break;
		case "swivel":		continue; x-=50; x=Math.round(x/2); y-=50; y=Math.round(y/2); break
			
		default		:
			break;
	}
	// use bilbo??!
	//msg("xy="+x+","+y+","+name);
	gToolbar.setVars(name,x,y);
	k++;
}
this.calcSpecs();
this.paint();
}



Toolbar.prototype.setVars=function(n,x,y,nopaint) { 
// only called by toolabr - to first adjust values if necessary
if(this.syncing)return; 
var scr=gPic.screen;	
switch(n){
	case "zoom"  : 
		if(x){if(x<500)x=x/10;else x=x-450;}
		if(y){if(y<500)y=y/10;else y=y-450;}
		break;
	case "flipz" : 
		x=(Math.round(x*100))/100; 
		break;
	case "tilt"  : 
		x=(x*-1)+45;   
		break;
	case "size"  : 
		if(x==null || y==null)scr.natural=scr.square=0; 		
		break;
	case "originoffset"	: 
		_obj("iOriginOffsetTxt").innerHTML=x+"px"; 	
		break;
}
gPic.applyVars(scr,n,x,y,nopaint);
gPic.saveDoneStuff();
}

//------ applyVars() ------
Picture.prototype.applyVars=function(scr,n,x,y,nopaint) { 		
if(this.syncing)return; 
var paint=(nopaint)?0:1;
//msg("n="+n+", x="+x+", y="+y);
scr=scr.setVars(n,x,y);  	//scr=scr.setVars(n,x,y);  ....do we need to call getTarget here?
switch(n){

	// we need to paint() for these
	case "image"	:
	case "fold"		:
	case "zoom"		:
	case "split"	:
	case "frack"	:
	case "zoom"		:
	case "warp"		: 	
	case "panelskew":
	case "turn"		:
	case "clip"		:
		if(paint)scr.paint();
		return "paint";

	case "size"		:
		if(paint)gPic.repaintSizes(scr.scrix);
		return "repaintSizes";

	// no need to paint (updates applied in setVars()
	case "slide"	:
	case "move"		:
	case "2drot" 	:
	case "skew"		:
	case "swivel"	:
	case "tilt"    	:
	case "axis"    	:
	case "3drot" 	:
	case "flipz" 	:
	case "origin" 	:
	case "originoffset" :
	case "scale"	:
	case "contrast" :
	case "brightness" :
	case "saturate" :
	case "blur" 	:
	case "sepia" 	:
	case "hue-rotate" :
	case "grayscale":
	case "invert" 	:
	case "corners"	:
	case "opacity1"	:
	case "opacity2"	:
	case "shadows"	:
	case "blend"	:
	case "mask"		:
		return "none";
	
	
}
alert("shouldn't get here!");
//msg("repaint() n="+n);
if(paint)this.repaint(scr.scrix); // only need to gPic.repaint if sizes (or scale) have changed
return "repaint";
}


//var gStdVars=",screen,panel,split,image,fold,warp,split,zoom,slide,move,size,frack,";

Screen.prototype.setVars=function(n,x,y){
//--- NOTE: possible locktyp's are lock/filter/fade/mask/frame/child/sibling?/color?/overlay?/blend?
//msg("scr.setvars() n="+n+", syncing="+gToolbar.syncing);
if(gToolbar.syncing)return;
var chg=1;
var locktyp=this.lockTyp;
var typ=this.typ;
var media=this.media;
var nm=capFirstLetter(n);
//--- get the correct target ---
var scr=gPic.getTarget(this,n);
//stdVars: screen,panel,image,fold,warp,split,zoom,slide,move,size,frack
if(gPic.stdVar(n)){ 
	if(x==null)eval("x=scr.x"+nm+";");
	if(y==null)eval("y=scr.y"+nm+";");
}
//msg("!!!!n="+n);
switch(n){
	//gPic.setPerspective =function(scr,str,mode,bigmirror,rotate,skewx,skewy,persp,rotx,roty,rot3d){    
	case "2drot" 	:	//spin
        scr.history.recordAction("s.SV('2drot',"+x+",0);");  
		gPic.setPerspective(scr,null,null,null,x);					//5
		break; 
	case "skew" 	: 	//skew
		var tmp=gPic.d3Mode;
		gPic.d3Mode="panels";
		var str=gPic.getPerspectiveStr();
		if(x==null)x=gPic.getCSSPerspective(str,"Xskew");				
		if(y==null)y=gPic.getCSSPerspective(str,"Yskew");
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		gPic.setPerspective(scr,null,null,null,null,x,y);			//6 & 7 - tilt for panels
		gPic.d3Mode=tmp;
		break;
	case "swivel"	:	//labelled skew
		//var str=gPic.getPerspectiveStr();
		//if(x==null)x=gPic.getCSSPerspective(str,"Xskew");				
		//if(y==null)y=gPic.getCSSPerspective(str,"Yskew");
        scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");  
		gPic.setPerspective(scr,null,null,null,null,x,y);			//6 & 7 
		break;
	case "tilt"    	:	
        scr.history.recordAction("s.SV('"+n+"',"+x+",0);");  		// need to do this before applying so that we can record initialSettings
		gPic.setPerspective(scr,null,null,null,null,null,null,x);	//8
		break; 
	case "axis"    	:	
		var str=gPic.getPerspectiveStr();
		if(x==null)x=gPic.getCSSPerspective(str,"Xaxis");
		if(y==null)y=gPic.getCSSPerspective(str,"Yaxis");
        scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");  
		gPic.setPerspective(scr,null,null,null,null,null,null,null,x,y);	//9 & 10
		break;
	case "3drot" 	:	//flip
        scr.history.recordAction("s.SV('"+n+"',"+x+",0);");  
		gPic.setPerspective(scr,null,null,null,null,null,null,null,null,null,x);	//11
		break; 
	case "flipz" 	:	
        scr.history.recordAction("s.SV('"+n+"',"+x+",0);");  
		gPic.setPerspective(scr,null,null,null,null,null,null,null,null,null,null,x);	//12
		break; 
	case "origin" 	:	
        scr.history.recordAction("s.SV('"+n+"',"+x+",0);");  
		gPic.setPerspective(scr,null,null,null,null,null,null,null,null,null,null,null,x);	//13
		break; 
	case "originoffset" 	:	
        scr.history.recordAction("s.SV('"+n+"',"+x+",0);");  
		gPic.setPerspective(scr,null,null,null,null,null,null,null,null,null,null,null,null,x);	//14
		break; 
	case "opacity1" 	:	
        scr.history.recordAction("s.SV('opacity1',"+x+",0);");
		scr.setOpacity1(x);  
		//scr.repaint();  
		break; 
	case "opacity2" 	:	
        scr.history.recordAction("s.SV('opacity2',"+x+",0);");  
		scr.viewOpacity2(x);  
		//scr.repaint();  
		break; 
	case "contrast" :
	case "brightness" :
	case "saturate" :
	case "blur" :
	case "sepia" :
	case "hue-rotate" :
	case "grayscale" :
	case "invert" :
        scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");"); 
		//msg("n="+n+", x="+x); 
		//msg("scr.BEFORE="+scr.cssfilters);
		gPic.applyCSSFilter(scr,n,x); 		
		//msg("scr.AFTER="+scr.cssfilters);
		break;
	case "corners"	:
		scr.history.recordAction("s.SV('corners',"+x+","+y+");");
 		scr.setCorners(x+"%");
		break;	
	case "clip"	:
		scr.history.recordAction("s.SV('clip',"+x+","+y+");");
		scr.setClipRadius(x+"%");
		scr.calcSpecs();
		break;
	case "slide"	:	
		scr.history.recordAction("s.SV('slide',"+this.xSlide+","+this.ySlide+");");
		scr.slide(x,y);
		break;
	case "move"	:	
		x=x*1; y=y*1;
		x=_round(x,2);
	 	y=_round(y,2);
		// placeScreen records history so no need to call here
		scr.xMove=x; 	scr.yMove=y;
		gPic.placeScreen(scr);
		break;
	case "size"		:	
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.setSizes(x,y); 	
		break;
	case "mask"	:  
		if(x==null)x=scr.maskStart; 
		if(y==null)y=scr.maskBlur;  
        scr.history.recordAction("s.SV('mask',"+x+","+y+");");  
		scr.maskStart=x;
		scr.maskBlur=y;
		scr.applyPixelMasks();
		break;
	case "blend" 	:	
        scr.history.recordAction("s.SV('blend',"+x+",0);");  
		scr.setBlend(x);
		break; 
	case "panelskew" 	: 
		if(x==null)x=scr.xSkew; 
		if(y==null)y=scr.ySkew;  
        scr.history.recordAction("s.SV('panelskew',"+x+","+y+");");  
		scr.xSkew=x;
		scr.ySkew=y;
		break;
	case "turn"		:	
		if(x!=null)scr.vx=Math.round((x/100)*scr.maxvx);
		if(y!=null)scr.vy=Math.round((y/100)*scr.maxvy);
		scr.calcSpecs();
		break;
		
	case "image"	:
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xImage=x;	scr.yImage=y;
		scr.calcSpecs();
		break;
	case "fold"		:
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xFold=x;	scr.yFold=y;
		scr.calcSpecs();
		break;
	case "zoom"		:
		//scr.div.style.zoom=x+"%";
		//break
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xZoom=x;	scr.yZoom=y;
		scr.calcSpecs();
		break;
	case "split"	:
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xSplit=x;	scr.ySplit=y;
		scr.calcSpecs();
		break;
	case "frack"	:
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xFrack=x;	scr.yFrack=y;
		break;
	case "warp"		: 	
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xWarp=x;	scr.yWarp=y;
		scr.calcSpecs();
		break;
	case "scale"		: 	
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		scr.xScale=x;	scr.yScale=y;
		//scr.calcSpecs();
		scr.setPerspective(scr,scr.perspectiveImg,"image");
		break;
	default 		:				//????? list specifically so we don't have to do an "eval()"!
		msg("setvars n="+n+", y="+y);
		scr.calcSpecs();
		scr.history.recordAction("s.SV('"+n+"',"+x+","+y+");");
		eval("scr.x"+nm+"="+(x*1)+"; scr.y"+nm+"="+(y*1)+";");
		break;
}
return scr;
}


//------ spinOrigin -------
Toolbar.prototype.spinOrigin=function(v){
if(v==null){
	v=gPic.getPerOrigin(null,0);
	if(v=="")v=0;
	v=(v*1)+1;
}
if(v>4)v=0;
_obj("iOriginTxt").innerHTML=gPic.getOriginTxt(v);
gPic.screen.setVars("origin",v);
}




//------ setVal -------
Toolbar.prototype.setVal=function(n,v){
switch(n){
	case "zomX" : case "zomY" : case "zomXY" :	//50 is the norm (range is 0-1000)
		if(v<50)v=v*10;
		else v=v+450;
		break;
}
//msg("setVal "+n+"="+v);
try{$("#"+n).simpleSlider("setValue",v);}
catch(e){_obj(n).value=v;}
}


//-------------------- getVal -------------------------
Toolbar.prototype.getVal=function(n){
var v=_obj(n).value;
switch(n){
	case "zomX" : case "zomY" : case "zomXY" :
		if(v<500)v=v/10;
		else v=v-450;
		break;
	//case "fil_sat" : 
	//	if(v<500)v=v/5;
	//	else v=v-400;
	//	break;
}
return v;
}

//------------------ sync controls with current screen -------------------------   
Toolbar.prototype.syncControls=function(){
if(gPic.md==1 || !gEmbedded)return;
var lots=0; 
for(var i=0; i<gPic.screens.length; i++){
	if(!gPic.screens[i].DELETED)lots++;
}
if(lots==0){ msg("syncControls - no screens found"); return; }
lots=(lots>1)?1:0;
_obj("iZindexF").style.opacity =_obj("iZindexB").style.opacity =(lots)?0.8:0.4;
_obj("iZindexF").style.cursor  =_obj("iZindexB").style.cursor  =(lots)?"pointer" : "default";
_obj("iLockZindexPadlock").style.opacity =(lots)?1:0.3;
_obj("iLockZindexPadlock").style.cursor  =(lots)?"pointer" : "default";
var scr=gPic.screen;
if(!scr)return;
this.syncing=1;
//--- general config ---
this.setConfigTitle(); 
//--- screen config ---
_obj("iLockPosition").checked=(scr.lockPosition)?true:false;
_obj("iLockZindexPadlock").src=(gPic.lockZindex)?"_pvt_images/Lock-Lock.png" :  "_pvt_images/Lock-Unlock.png" ; 
//_obj("iLockScrixPadlock").src=(gPic.lockScrix)?"_pvt_images/Lock-Lock.png" :  "_pvt_images/Lock-Unlock.png" ; 
_obj("iNaturalSizeBtn").src="_pvt_images/"+((scr.natural)?"naturalOn.png":"naturalOff.png");
//@@@parent._obj("iSquareSizeBtn").src="_pvt_images/"+((scr.square)?"squareOn.png":"squareOff.png");
//setTimeout("parent.hiliteTilingPattern('"+scr.tileType+"');",1000);
_obj("iDopple").checked =(gPic.dopple)?1:0;
_obj("iScrambleImages").checked =(scr.scrambleImages)?1:0;
_obj("iVignetteColor").checked =(scr.applyVignetteColor)?1:0;
_obj("iDominantColor").checked =(scr.applyDominantColor)?1:0;
_obj("iHideImage").checked =(scr.hideImage)?1:0;
_obj("iHideImageEye").src ="_pvt_images/"+((scr.hideImage)?"hideOff.png":"hideOn.png");

//--- sliders ---
//this.setVal("turnXY",Math.round((scr.vx/scr.maxvx)*100));
this.setVal("turnX",Math.round((scr.vx/scr.maxvx)*100));
this.setVal("turnY",Math.round((scr.vy/scr.maxvy)*100));
this.setVal("splZXY",scr.xWarp);
this.setVal("splZX",scr.xWarp);
this.setVal("splZY",scr.yWarp);
this.setVal("splXY",scr.xSplit);
this.setVal("splX",scr.xSplit);
this.setVal("splY",scr.ySplit);
this.setVal("imgXY",(scr.xImage));  
this.setVal("imgX",(scr.xImage));
this.setVal("imgY",(scr.yImage));
this.setVal("fldXY",scr.xFold);
this.setVal("fldX",scr.xFold);
this.setVal("fldY",scr.yFold);
this.setVal("strX",scr.xStretch);
this.setVal("strY",scr.yStretch);
this.setVal("iScaleX",scr.xScale);
this.setVal("zomXY",scr.xZoom); 
this.setVal("zomX",scr.xZoom);
this.setVal("zomY",scr.yZoom);
//this.setVal("strXY",(100-((scr.xCrop+scr.yCrop)/2)));
//this.setVal("strX",(100-scr.xCrop));
//this.setVal("strY",(100-scr.yCrop));
//this.setVal("strL",scr.lCrop);
//this.setVal("strT",scr.tCrop);
this.setVal("frkWH",((scr.xFrack+scr.yFrack)/2));
//this.setVal("frkW",scr.xFrack);
//this.setVal("frkH",scr.yFrack);
this.setVal("sldXY",scr.xSlide);
this.setVal("sldX",scr.xSlide);
this.setVal("sldY",scr.ySlide);
this.setVal("opcDV",scr.opacity1);
this.setVal("iBlend",scr.blend);
this.setBlend(scr.blend);
//origin?

//--- other ---
this.syncFulters();
this.syncOilPaint();
this.syncPosterize();
this.syncColorRgb();
this.syncSwapColors();
this.syncFillColor();
this.syncSwapPixels();
this.syncMask();
this.syncSizes();
this.syncTiling();
this.syncPerspective();
this.syncCSSFilters();
this.syncHistory();
this.syncDoneStuff();
this.syncGradient();
this.syncAnimations();
this.syncFullscreenBtns();
this.syncShadows();   //turns .syncing off somehow so put last
this.viewImage();
this.syncing=0;
}



//------------------- sync sizes -----------------------     
Toolbar.prototype.syncSizes=function(scr){
if(!gEmbedded)return;
var sync=this.syncing;
this.syncing=1;
if(scr==null)scr=gPic.screen;
this.setVal("rszXY",scr.xSize);
this.setVal("rszX",scr.xSize);
this.setVal("rszY",scr.ySize);
this.setVal("movXY",scr.xMove);
this.setVal("movX",scr.xMove);
this.setVal("movY",scr.yMove);
this.syncing=sync;
}


//------------------- sync screen buttons -----------------------     
Toolbar.prototype.syncFullscreenBtns=function(){
if(!gEmbedded)return;
//msg(gPic.screen.xSize+","+gPic.screen.ySize);
var tmp="_pvt_images/folders/toggle_"+((gPic.screen.isFullscreen())?"collapse.png":"expand.png");
_obj("iFullScreen").src=tmp;
//tmp="_pvt_images/folders/toggle_"+((gPic.screen.hidden==1)?"maximize.png":"minimize.png");
//_obj("iToggleMinimizeBtn").src=tmp;
_obj("iNaturalSizeBtn").src="_pvt_images/"+((gPic.screen.natural)?"naturalOn.png":"naturalOff.png");
//parent._obj("iSquareSizeBtn").src="_pvt_images/"+((gPic.screen.square)?"squareOn.png":"squareOff.png");
var tmp=this.syncing;
this.syncing=1;
this.syncing=tmp;
}



//------------------- resetDoneStuff -----------------------     
Toolbar.prototype.resetDoneStuff=function(){
gPic.doneStuff=";;";
gPic.doneIX=1;
this.syncDoneStuff();
}


//------------------- syncDoneStuff -----------------------     
Toolbar.prototype.syncDoneStuff=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
var opOff 	=0.3;
var opOn  	=0.8;
var opBck	=0;
var opFwd	=0;
if(scr.oldConfig){
	var steps=gPic.doneStuff.split(";;").length-1;
	if(gPic.doneIX > 1)opBck=1;
	if(gPic.doneIX < steps)opFwd=1;
}
//msg("IX="+gPic.doneIX+", steps="+steps+", opBck="+opBck+", opFwd="+opFwd);
parent._obj("iUndoBtn").style.opacity=(opBck)?opOn:opOff;
parent._obj("iRedoBtn").style.opacity=(opFwd)?opOn:opOff;
parent._obj("iUndoBtn").style.cursor =(opBck)?"pointer":"default";
parent._obj("iRedoBtn").style.cursor =(opFwd)?"pointer":"default";
parent._obj("iDoneReset").style.opacity=(opBck || opFwd)?1:opOff;
parent._obj("iDoneReset").style.cursor =(opBck || opFwd)?"pointer":"default";
}

//------------------- syncHistory -----------------------     
Toolbar.prototype.syncHistory=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
var op=(scr.history.HISTORY.length<1)?0.3:0.8;
//---------- history ---------
try{_obj("iSaveRekordBtn").style.opacity=op;}catch(e){}
_obj("iHistBck").style.opacity	=_obj("iHistFwd").style.opacity		=op;
_obj("iHistBck").style.cursor 	=_obj("iHistFwd").style.cursor		=(op!=0.8)?"default":"pointer";
_obj("iHistReset").style.opacity=_obj("iHistReset").style.opacity 	=op;
_obj("iHistReset").style.cursor =_obj("iHistReset").style.cursor	=(op!=0.8)?"default":"pointer";

if(gShowactions!=1)return;
//-------- animations --------
_obj("iRekSmooth").checked=(scr.history.smoothing)?true:false;
_obj("iRekRelative").checked=(scr.history.relative)?true:false;
//_obj("iRekMergeTD").style.display=(scr.history.smoothing)?"":"none";
_obj("iRekDeltaTR").style.display=(scr.history.smoothing)?"":"none";
var loopmode=0;
switch(scr.history.loopmode){
	case "oscillate": 	loopmode=0;	 break;
 	case "restart"	: 	loopmode=1;	 break;
 	case "stop"		: 	loopmode=2;	 break;
}
this.setVal("iHistDelta",scr.history.delta); 
_obj("iHistDeltaTxt").value=scr.history.delta; 
_obj("iRekLoop"+loopmode).checked=true;
this.setVal("iRekCurve",scr.history.curve); 
//msg("timeout="+scr.history.timeout);
this.setVal("iHistSpeed",scr.history.timeout); 
_obj("iHistSpeedTxt").value=scr.history.timeout;
/*  i don't understand why we had this...???? keep for now
if(scr.history.loopmode=="stop"){
	//msg("ix="+scr.history.ix+", loopmode="+scr.history.loopmode+", history.length="+scr.history.history.length);
	if(scr.history.ix<1){
		_obj("iHistBck").style.opacity=0.3;
		_obj("iHistBck").style.cursor ="default";
	}
	if(scr.history.ix>(scr.history.HISTORY.length-2)){
		_obj("iHistFwd").style.opacity=0.3;
		_obj("iHistFwd").style.cursor="default";
	}
}
*/
}


//--------------- sync Animations --------------------   
Toolbar.prototype.syncAnimations=function(){
if(!gEmbedded)return;
if(gShowactions!=1)return;
var scr=gPic.screen;
_obj("iAniX").checked=(scr.animation.aniXon)?true:false;
_obj("iAniY").checked=(scr.animation.aniYon)?true:false;
this.setVal("iRekDelta",scr.animation.delta); 
this.setVal("iRekSpeed",scr.animation.timeout); 
_obj("iRekSpeedTxt").value=scr.animation.timeout;
_obj("iRekDeltaTxt").value=scr.animation.delta; 
this.setVal("iXRekRangeMin",scr.animation.xrange_min); 
this.setVal("iXRekRangeMax",scr.animation.xrange_max); 
this.setVal("iYRekRangeMin",scr.animation.yrange_min); 
this.setVal("iYRekRangeMax",scr.animation.yrange_max); 
}



//-------------------- sync Tiling ---------------------
Toolbar.prototype.syncTiling=function(){
if(!gEmbedded)return;
this.syncingTiling=1;
var tmp=this.syncing;
this.syncing=1;
var xx,yy,zx,zy,mx,my,scr=gPic.screen;
xx=_obj("ix").innerHTML  =scr.xxx;
yy=_obj("iy").innerHTML  =scr.yyy;
zx=_obj("izx").innerHTML =scr.maxzx;
zy=_obj("izy").innerHTML =scr.maxzy;
mx=_obj("ixx").innerHTML =scr.maxx;
my=_obj("iyy").innerHTML =scr.maxy;
//this.setVal("ixxy",xx);
//this.setVal("ixxx",xx);
//this.setVal("iyyy",yy);
this.setVal("izxaxis",zx);
this.setVal("izyaxis",zy);
this.setVal("ixaxis",mx);
this.setVal("iyaxis",my);
if(scr.tileXY!=null)this.setVal("tileXY",scr.tileXY);
this.setVal("zomXY",scr.xZoom); 
this.setVal("zomX",scr.xZoom);
this.setVal("zomY",scr.yZoom);
_obj("iFullWarp").checked=(scr.fullWarp)?true:false;
_obj("iOffsetWarp").checked=(scr.offsetWarp)?true:false;
_obj("iFullSplit").checked=(scr.fullSplit)?true:false;
_obj("iOffsetSplit").checked=(scr.offsetSplit)?true:false;
//var txt=zx+","+zy+"&nbsp;:&nbsp;"+xx+","+yy+"&nbsp;:&nbsp;"+mx+","+my;
var txt=zx+","+zy+",";
if(xx!=1 || yy!=1)txt+=xx+","+yy+",";
txt+=mx+","+my;
_obj("iTilingTxt").innerHTML	=txt;
_obj("iTilingType").innerHTML	=(scr.tileType)?scr.tileType:"Custom";
this.syncing=tmp;
this.syncingTiling=0;
}

//------------------ sync Perspective ----------------
Toolbar.prototype.syncPerspective=function(){
//"rotate("+rotate+"deg) skew("+perskx+"deg,"+persky+"deg) perspective("+persp+") rotate3d("+perrx+","+perry+","+flipz+","+per3d+"deg) origin:0,0,";
 //perSP: range= -1000 to 0.  1000=none. 45=full. Actual range is 1045-45. So we have to reverse (x*-1)+45;
if(!gEmbedded)return;
var tmpsyncing=this.syncing; 
this.syncing=1;
var rotate=per3d=perskx=persky=flipz=origin=originoffset=0;
var persp=-1000, perrx=0, perry=180;
var scr=gPic.screen;
var str=gPic.getPerspectiveStr();
//d3mode
if(str){
 rotate=		gPic.getPerRotate(str);
 per3d=			gPic.getPerRot3d(str);
 flipz=			gPic.getPerFlipz(str);
 origin=		gPic.getPerOrigin(str,0);
 originoffset=	gPic.getPerOrigin(str,1);
 perrx=			gPic.getPerRotX(str);
 perry=			gPic.getPerRotY(str);
 perskx=		gPic.getPerSkewX(str);
 persky=		gPic.getPerSkewY(str);
 persp=			gPic.getPerTilt(str);  persp=(persp*-1)+45;  
}
this.setVal("perROT",rotate);
this.setVal("perSWIVXY",perskx);	
this.setVal("perSWIVX",perskx);
this.setVal("perSWIVY",persky);
this.setVal("perSP",persp);
this.setVal("perflipz",flipz);
_obj("iOriginTxt").innerHTML=gPic.getOriginTxt(origin);
this.setVal("perOriginOffset",originoffset);
_obj("iOriginOffsetTxt").innerHTML=originoffset+"px";
this.syncFlip(per3d,perrx,perry);
//--- panel skew ---
str=gPic.getPerspectiveStr(scr,"panels");
perskx=gPic.getPerSkewX(str);
persky=gPic.getPerSkewY(str);
this.setVal("perSKXY",perskx);  
this.setVal("perSKX",perskx);
this.setVal("perSKY",persky);
this.setVal("perPanelSKXY",scr.xSkew);  
this.setVal("perPanelSKX",scr.xSkew);
this.setVal("perPanelSKY",scr.ySkew);
//?? what about perR3Dx and picR3Dx, etc?
this.syncD3Mode();
this.syncing=tmpsyncing;
}


Toolbar.prototype.syncFlip=function(per3d,perrx,perry){
if(!gEmbedded)return;
var tmpsyncing=this.syncing; this.syncing=1;	
this.setVal("perR3D",0);
this.setVal("perRX",perrx);	
this.setVal("perRY",perry);
this.syncing=tmpsyncing;
}

Toolbar.prototype.syncD3Mode=function(v){	//not currently called - should it be?
if(!gEmbedded)return;
if(v==null)v=gPic.d3Mode;
$("#iD3ModeW").dgUncheck($("#box-iD3ModeW"));
$("#iD3ModeI").dgUncheck($("#box-iD3ModeI"));
$("#iD3ModeP").dgUncheck($("#box-iD3ModeP"));
switch(v){
  case "window" : $("#iD3ModeW").dgCheck($("#box-iD3ModeW"));  break;
  case "image"  : $("#iD3ModeI").dgCheck($("#box-iD3ModeI"));  break;
  case "panels" : $("#iD3ModeP").dgCheck($("#box-iD3ModeP"));  break;
}
}




//--------------------- sync Gradient ---------------------
Toolbar.prototype.syncGradient=function(view){
if(!gEmbedded)return;
if(view==null)view="";
var obj=(view)? gPic : gPic.screen;
var typ=obj.gradientType;
var posi=obj.getGradPos();
switch(posi){
 case "left":  posi=1;break;
 case "top":   posi=2;break;
 case "right": posi=3;break;
 case "bottom":posi=4;break;
 case "center":posi=5;break;
}this.setVal(view+"grdP",posi);
this.setVal(view+"grdC1",obj.getGradPct1()*1);
this.setVal(view+"grdC2",obj.getGradPct2()*1);
$("#"+view+"grdT1").dgUncheck($("#"+view+"box-grdT1"));
$("#"+view+"grdT2").dgUncheck($("#"+view+"box-grdT2"));
$("#"+view+"grdT3").dgUncheck($("#"+view+"box-grdT3"));
if(typ=="flat")  $("#"+view+"grdT1").dgCheck($("#"+view+"box-grdT1"));
if(typ=="linear")$("#"+view+"grdT2").dgCheck($("#"+view+"box-grdT2"));
if(typ=="radial")$("#"+view+"grdT3").dgCheck($("#"+view+"box-grdT3"));
_obj("i"+view+"FrameColorImg").style.display=(typ=="flat")?"block":"none";
_obj("i"+view+"GradientTable").style.display=(typ!="flat")?"block":"none";
}


//=============================  TILING ========================================

//----------------------- resetTileType --------------------
Toolbar.prototype.resetTileType=function(scr){
scr.tileType		="";  
//scr.div.style.zoom	="100%";
scr.xZoom=scr.yZoom	=50;
scr.vx	=scr.vy		=0;
scr.fullSplit		=1;
scr.offsetSplit		=0;
}


//----------------------- tileScreen --------------------
Toolbar.prototype.tileScreen=function(y){
//msg("y="+y);
//msg("lock="+gPic.lockTiledImage)
//msg("syncTile="+this.syncingTiling)
//msg("sync="+this.syncing)
if(this.syncingTiling)return;
if(this.syncing)return;
this.syncing		=1;
var scr				=gPic.screen;
this.resetTileType(scr);
if(gPic.lockTiledImage){
	 if(y){
	  //this.setVal("ixxx",	_obj("iyyy").value);
	  this.setVal("izxaxis",_obj("izyaxis").value);
	  this.setVal("ixaxis",	_obj("iyaxis").value);
	 }else{
	  //this.setVal("iyyy",	_obj("ixxx").value);
	  this.setVal("izyaxis",_obj("izxaxis").value);
	  this.setVal("iyaxis",	_obj("ixaxis").value);
	 }
}
//this.setVal("ixxy",	_obj("ixxx").value);
gPic.tileScreen(scr.scrix,gPic.strTiling(1,1,_obj("izxaxis").value,_obj("izyaxis").value,_obj("ixaxis").value,_obj("iyaxis").value));
this.syncing=0;
}


//----------------------- tileBoxes --------------------
/*
Toolbar.prototype.tileBoxes=function(){
if(this.syncingTiling)return;
if(this.syncing)return;
this.syncing=1;
var scr=gPic.screen;
this.resetTileType(scr);
this.setVal("ixxx",	_obj("ixxy").value);
this.setVal("iyyy", _obj("ixxy").value);
gPic.tileScreen(scr.scrix,gPic.strTiling(_obj("ixxx").value,_obj("iyyy").value,_obj("izxaxis").value,_obj("izyaxis").value,_obj("ixaxis").value,_obj("iyaxis").value));
this.syncing=0;
}
*/


//----------------------- tile ---------------------------
Toolbar.prototype.tileSlider=function(n){
if(this.syncingTiling)return;
if(this.syncing)return;
gPic.screen.tileType="";  
gPic.tileSlider(n);
this.syncTiling();
}


//-------------- lock tiling dimensions -----------------
Toolbar.prototype.lockTiledImage=function(v){
if(v==null)v=(gPic.lockTiledImage==0)? 1:0;
_obj("iLockTiledImage").src=(v==1)? "_pvt_images/Lock-Lock.png" :  "_pvt_images/Lock-Unlock.png" ; 
gPic.lockTiledImage=v;
}



//=============================  PERSPECTIVE ===================================  
	
Toolbar.prototype.chgD3Mode=function(v){
var a=new Array('window','image','panels');
if(v==null){
	v=0;
	var tmp=document.getElementsByName("D3Mode");
	for(var i=0;i<tmp.length;i++){ if(tmp[i].checked)v=i; }
}
v=a[v];
if(gPic.d3Mode==v)return;
//msg("chgd3mode v="+v);
try{ gPic.screen.history.recordAction("gPic.d3Mode='"+v+"';");  }catch(e){}
gPic.d3Mode=v;
this.syncPerspective();
}


Toolbar.prototype.spin45=function(n,x,t,odd){ 
var v=_obj(n).value;
var v2=0;
for(i=1;i<t;i++){
	if(v<(i*x) && (odd==null || _isOdd(i)) ){v2=i*x;i=999;}
}
this.setVal(n,v2);
}

Toolbar.prototype.spin1=function(n,x){ 
var v=(_obj(n).value*1)+x;
this.setVal(n,v);
}


Toolbar.prototype.flip45=function(n){ 
var v=parseInt(_obj(n).value);
v+=45;
if(v==90 || v==270)v+=45;
if(v>359)v=0;
this.setVal(n,v);
}


Toolbar.prototype.flipCustom=function(v){ 
var d=1;
if(v>360){d=-1; v-=360;}
this.setVars('3drot',v,0);
var tmp=this.syncing;
this.syncing=1;
this.setVal("perR3Dxy",0);
this.setVal("perR3Dx",0);
this.setVal("perR3Dy",0);
this.setVal("perR3Dz",0);
this.setVal("picR3Dxy",0);
this.setVal("picR3Dx",0);
this.setVal("picR3Dy",0);
this.syncing=tmp;
}

Toolbar.prototype.flipBoth=function(v){ 
var d=1;
if(v>360){d=-1; v-=360;}
this.setVars('axis',180,180*d);
//var sync=this.syncing; this.syncing=1;this.setVal("perRX",180);this.setVal("perRY",180*d);this.syncing=sync;
this.setVars('3drot',v,0);
this.syncFlip(0,180,180*d);
}

Toolbar.prototype.flipHorizontal=function(v){ 	
this.setVars('axis',0,180);
//var sync=this.syncing; this.syncing=1;this.setVal("perRX",0);this.setVal("perRY",180);this.syncing=sync;
this.setVars('3drot',v,0);
this.syncFlip(0,0,180);
}

Toolbar.prototype.flipVertical=function(v){ 	
this.setVars('axis',180,0);
//var sync=this.syncing; this.syncing=1;this.setVal("perRX",180);this.setVal("perRY",0);this.syncing=sync;
this.setVars('3drot',v,0);
this.syncFlip(0,180,0);
}

//------------------- rotate -----------------------
Toolbar.prototype.rotate=function(e){
var scr=gPic.screen;
var rbtn=(e)? rbtn=getRbtn(e) : 0 ;
var deg=scr.picture.getPerRotate(scr.perspective,scr);
var tmp=gPic.d3Mode;
gPic.d3Mode="window";
deg=(rbtn)?deg-90:deg+90;
if(deg>359)deg=0;
if(deg<0)deg=270;
gPic.setPerspective(scr.perspective,scr,null,deg);
gPic.d3Mode=tmp;
}


//============ DALL-E =============
// try this in an unsecured site... eg. artdept.com (this gives a 400 error)
/*
function test(){
DALLE('http://optic.com/61640317_2167232323331270_5619681449427861504_n.png',
	  'generate image like Salvador Dali');
}

function DALLE(imageUrl,prompt){
	fetch('https://api.openai.com/v1/images/generations', {
	  method: 'POST',
	  headers: {
	    'Content-Type': 'application/json',
	    'Authorization': 'Bearer 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'
	  },
	  body: JSON.stringify({
	    model: 'image-alpha-001',
	    prompt: prompt,
	    num_images: 1,
	    size: '1024x1024',
	    response_format: 'url',
	    image: imageUrl
	  })
	})
	.then(response => response.json())
	.then(data => {
	    console.log(data);
	});
}
*/

//==========  SWAPPIXELS ==========  

Toolbar.prototype.flipSwapPixels=function(){
if(this.syncing)return;
var scr			=gPic.screen;
scr.SwapPixelsOn=(_obj("iSwapPixelsOn").checked)?1:0;
this.expandBox('iSwapPixels');
this.chgSwapPixels();	
}

Toolbar.prototype.chgSwapPixels=function(on){
if(this.syncing)return;
var scr		=gPic.screen;
var oldRgb	=scr.swapPixelsRgb;
var R=(_obj("iSwapPixelsRed").checked)?  "R":"";
var G=(_obj("iSwapPixelsGreen").checked)?"G":"";
var B=(_obj("iSwapPixelsBlue").checked)? "B":"";
var newRgb=scr.swapPixelsRgb=R+G+B;
if(newRgb==""){
	if(!_in(oldRgb,"R"))		{ newRgb="R"; _obj("iSwapPixelsRed").checked=true; }
	else if(!_in(oldRgb,"G"))	{ newRgb="G"; _obj("iSwapPixelsGreen").checked=true; }
	else						{ newRgb="B"; _obj("iSwapPixelsBlue").checked=true; }
	scr.swapPixelsRgb=newRgb;
}
if(newRgb=="RGB"){
	_obj("iSwapPixelsGreen").checked=false; 
	scr.swapPixelsRgb="RB";
}
scr.paint();
}

Toolbar.prototype.resetSwapPixels=function(paint){
var scr	=gPic.screen;
scr.swapPixelsRgb="R";
scr.SwapPixelsOn=0;
if(paint)scr.paint();
this.syncSwapPixels();
}

Toolbar.prototype.syncSwapPixels=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
var tmpsync	=this.syncing;
this.syncing=1;
//var scr2=gPic.getLockParent(scr);
//_obj("iSwapPixelsHdr").style.display=(scr==scr2)?"none":"";
_obj("iSwapPixelsOn").checked=(scr.SwapPixelsOn)?true:false;
_obj("iSwapPixelsRed").checked=(_in(scr.swapPixelsRgb,"R"))?true:false;
_obj("iSwapPixelsGreen").checked=(_in(scr.swapPixelsRgb,"G"))?true:false;
_obj("iSwapPixelsBlue").checked=(_in(scr.swapPixelsRgb,"B"))?true:false;
this.syncing=tmpsync;
}

//==========  SWAPCOLORS ==========  

Toolbar.prototype.flipSwapColors=function(){
if(this.syncing)return;
var scr=gPic.screen;
scr.SwapCsOn=(_obj("iSwapCsOn").checked)?1:0;
if(scr.SwapCsOn)this.expandBox("iSwapColors",1);
this.chgSwapColors();	
}

Toolbar.prototype.openSwapPicker=function(f){
if(this.swapPicker){
	this.swapPicker=null;
    _obj("iPicker1").style.border="solid 2px white";
    _obj("iPicker2").style.border="solid 2px white";
	return;
}
this.swapPicker=(f==1)? this.setSwapColor1 : this.setSwapColor2 ;
this.swapPickerIcon=(f==1)? _obj("iPicker1") : _obj("iPicker2") ;
this.swapPickerIcon.style.border="solid 2px red";
}


Toolbar.prototype.setSwapColor1=function(clr1){
var scr	=gPic.screen;
var oldc1=_obj("iSwapColors1").value;
_obj("iSwapColors1").value = clr1;
_obj("iSwapSample1").style.background=clr1;
_obj("iSwapCs1"+scr.SwapCsCurrent).style.background=clr1;
scr.SwapCsList=scr.SwapCsList.replace(new RegExp(oldc1,"g"), clr1);
_obj("iPicker1").style.border="solid 2px white";
if(scr.SwapCsOn)scr.paint();
}

Toolbar.prototype.setSwapColor2=function(clr2){
var scr	=gPic.screen;
var oldc2=_obj("iSwapColors2").value;
_obj("iSwapColors2").value = clr2;
_obj("iSwapSample2").style.background=clr2;
_obj("iSwapCs2"+scr.SwapCsCurrent).style.background=clr2;
scr.SwapCsList=scr.SwapCsList.replace(new RegExp(oldc2,"g"), clr2);
_obj("iPicker2").style.border="solid 2px white";
if(scr.SwapCsOn)scr.paint();
}

Toolbar.prototype.chgSwapColors=function(on){
if(this.syncing)return;
var scr	=gPic.screen;
if(on){	//they clicked "apply"
	scr.SwapCsOn=1;
	this.syncing=1;
	_obj("iSwapCsOn").checked=true;
	this.syncing=0;
}
var c1			=_obj("iSwapColors1").value;
var c2			=_obj("iSwapColors2").value;
var alpha 		=_obj("iSwapColorsAlpha").value*1;
var tolerance 	=_obj("iSwapColorsTolerance").value*1;
_obj("iSwapSample1").style.background=c1;
_obj("iSwapSample2").style.background=c2;
this.updateCsList(scr,scr.SwapCsCurrent,c1,c2,alpha,tolerance);
this.syncSwapCs();
scr.paint();
}


Toolbar.prototype.resetSwapColors=function(paint){
var tmp=this.syncing;
this.syncing=0;
var scr	=gPic.screen;
scr.SwapCsOn=0;
_obj("iSwapCsOn").checked=false;
this.syncing=tmp;
if(paint)scr.paint();
}

Toolbar.prototype.syncSwapColors=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
var tmpsync	=this.syncing;
this.syncing=1;
var vals=this.getCsVals(scr);
var c1=vals[0];
var c2=vals[1];
var alpha=vals[2];
var tolerance=vals[3];
_obj("iSwapCsOn").checked=(scr.SwapCsOn)?true:false;
_obj("iSwapColors1").value	=c1;
_obj("iSwapColors2").value	=c2;
_obj("iSwapSample1").style.background=c1;
_obj("iSwapSample2").style.background=c2;
this.setVal("iSwapColorsAlpha",alpha);
this.setVal("iSwapColorsTolerance",tolerance);
this.syncSwapCs();
this.syncing=tmpsync;
}


//--------------------- sync swap colors ---------------------
Toolbar.prototype.syncSwapCs=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
if( !scr.SwapCsList || !_in(scr.SwapCsList,"#") )scr.SwapCsList="#ff3333,#ff9933,255,100";  
var SwapCsAry=scr.SwapCsList.split("|");
if(scr.SwapCsCurrent==null)scr.SwapCsCurrent=0;
//--- create the list ---
var list="";
list="<TABLE style='width:100%;'>"
for(var i=0;i<SwapCsAry.length;i++){
		var str=SwapCsAry[i];
		var a=str.split(",");
		list+="<tr>";
		list+="<td width=40 onclick='gToolbar.selectSwapCs("+i+")'  style='cursor:pointer;font-weight:"+((i==scr.SwapCsCurrent)?"bold":"normal")+"' onmouseover='_hilite(this)' onmouseout='_lolite(this)' >swap"+(i+1)+"</td>";
		list+="<td id='iSwapCs1"+i+"' style='width:35px;background:"+a[0]+";'></td>";
		list+="<td>&nbsp;</td>";
		list+="<td id='iSwapCs2"+i+"' style='width:35px;background:"+a[1]+";'></td>";
		list+="<td>&nbsp;&nbsp;</td>";
		list+="<td id='iSwapCsAlpha"+i+"' style='width:18px;'>"+a[2]+"</td>";
		list+="<td>&nbsp;&nbsp;</td>";
		list+="<td id='iSwapCsTolerance"+i+"' style='width:18px;'>"+a[3]+"</td>";
		list+="<td>&nbsp;</td>";
		if(SwapCsAry.length>1)list+="<td align=right><img src='_pvt_images/delete.png'  onclick='gToolbar.deleteSwapCs("+i+")' style='cursor:pointer;width:16px;' title='delete'></td>";
		else				  list+="<td style='width:16px;'></td>";
		list+="</tr>";
}
list+="</TABLE>";
//msg("list="+list);
_obj("iSwapCsList").innerHTML=list;
}


//--------------------- new swap color ---------------------
Toolbar.prototype.addNewSwapCs=function(){
var scr=gPic.screen;
var SwapCsAry=scr.SwapCsList.split("|");
switch(SwapCsAry.length+1){
	case 1  :	var str="#33ff33,#99ff33,255,100";  break;
	case 2  :	var str="#33ffff,#ffff33,255,100";  break;
	case 3  :	var str="#336699,#996633,255,100";  break;
	default :	var str="#3333ff,#9933ff,255,100";	break;
}
if(SwapCsAry[0]==""){
	scr.SwapCsList=str;
	scr.SwapCsCurrent=0;
	this.syncSwapCs();
	return;
}
scr.SwapCsCurrent=SwapCsAry.length;
scr.SwapCsList+="|"+str;
this.syncSwapColors();
if(scr.SwapCsOn)scr.paint();
}

//--------------------- delete swap color ---------------------
Toolbar.prototype.deleteSwapCs=function(ix){
var scr=gPic.screen;
var SwapCsAry=scr.SwapCsList.split("|");
var str="";
for(var i=0;i<SwapCsAry.length;i++){
	if(ix!=i){
		str+=SwapCsAry[i]+"|";
	}
}
str=str+"|";
str=_rep(str,"||","");
scr.SwapCsList=str;
scr.SwapCsCurrent=0;
this.syncSwapColors();
if(scr.SwapCsOn)scr.paint();
}


Toolbar.prototype.getCsVals=function(scr,i){
if(i==null)i=scr.SwapCsCurrent;
//msg("i="+i);
//msg("list="+scr.SwapCsList);
var tmp=scr.SwapCsList.split("|");
return tmp[i].split(",");
}

Toolbar.prototype.updateCsList=function(scr,i,c1,c2,alpha,tolerance){
if(i==null)i=scr.SwapCsCurrent;
//msg("list="+scr.SwapCsList);
var txt="";
if(!_in(scr.SwapCsList,"#"))scr.SwapCsList="#ff3333,#ff9933,255,100";
var tmp=scr.SwapCsList.split("|");
for(var i=0;i<tmp.length;i++){
	if(i==scr.SwapCsCurrent){
		tmp2=tmp[i].split(",");
		if(c1==null)c1=tmp2[0];
		if(c2==null)c2=tmp2[1];
		if(alpha==null)alpha=tmp2[2];
		if(tolerance==null)tolerance=tmp2[3];
		if(i>0)txt+="|"
		txt+=c1+","+c2+","+alpha+","+tolerance;
	}else{
		if(i>0)txt+="|"
		tmp2=tmp[i].split(",");
		txt+=tmp2[0]+","+tmp2[1]+","+tmp2[2]+","+tmp2[3];
	}
}
scr.SwapCsList=txt;
//msg("newlist="+scr.SwapCsList);
}

//------- select -------
Toolbar.prototype.selectSwapCs=function(ix){
gPic.screen.SwapCsCurrent=ix;
this.syncSwapColors();
}

//============= COLOR FILL ==============


Toolbar.prototype.flipFillColor=function(on){
if(this.syncing)return;
var scr=gPic.screen;
if(on){
	this.syncing=1;
	_obj("iFillColorOn").checked=true;
	this.syncing=0;
}
scr.FillColorOn=(_obj("iFillColorOn").checked)?1:0;
if(scr.FillColorOn){
	this.updFillColor();
	scr.applyFillColor();
	gPic.saveDoneStuff();
	
}	
else scr.paint();
}

Toolbar.prototype.chgFillColor=function(c){
_obj("iFillSample2").style.backgroundColor =c;
gPic.screen.FillColorClr =c;
gPic.screen.applyFillColor();
gPic.saveDoneStuff();
}


Toolbar.prototype.updFillColor=function(){
var scr=gPic.screen;
scr.FillColorOn			=(_obj("iFillColorOn").checked)?1:0;
scr.FillColorClr		=_obj("iFillSample2").style.backgroundColor;
scr.FillColorAlpha		=_obj("iFillColorAlpha").value*1;
scr.FillColorTolerance	=_obj("iFillColorTolerance").value*1;
}

Toolbar.prototype.setFillColorXY=function(x,y){
this.fillPicker=null;
_obj("iFillXY").innerHTML=x+","+y;
gPic.screen.FillColorX=x;
gPic.screen.FillColorY=y;
gPic.screen.applyFillColor();
gPic.saveDoneStuff();
this.fillPicker=null;
_obj("iFillPicker").style.border="solid 2px white";
}

/*
Toolbar.prototype.hiliteFCbtn=function(on){
_obj("iFCbtn").style.background=(on)? "#fcc" : "#ddd" ;
_obj("iFCbtn").style.color=(on)? "#000" : "#999" ;
}
*/

Toolbar.prototype.resetFillColor=function(paint,sync){
var scr=gPic.screen;
var tmp=this.syncing;
this.syncing=0;
//this.hiliteFCbtn(0);
if(sync){
	_obj("iFillColorOn").checked=(scr.FillColorOn)? true : false;
}else{
	_obj("iFillColorOn").checked=false;
	scr.FillColorOn=0;
}
_obj("iFillSample2").style.backgroundColor=scr.FillColorClr;
this.setVal("iFillColorAlpha",scr.FillColorAlpha);
this.setVal("iFillColorTolerance",scr.FillColorTolerance);
_obj("iFillXY").innerHTML=scr.FillColorX+","+scr.FillColorY;
if(paint)scr.paint();
this.syncing=tmp;
}

Toolbar.prototype.syncFillColor=function(){
if(!gPic.screen.FillColorX){ //tmp so existing views still work
	s.FillColorOn=0;
	s.FillColorX=10;
	s.FillColorY=10;
	s.FillColorClr="#ff9977";
	s.FillColorAlpha=255;
	s.FillColorTolerance=15;
}
this.resetFillColor(0,1);
}

Toolbar.prototype.openFillPicker=function(){
if(this.fillPicker){
	this.fillPicker=null;
    _obj("iFillPicker").style.border="solid 2px white";
	return;
}
this.fillPicker=this.setFillColorXY;
_obj("iFillPicker").style.border="solid 2px red";
}

function matchColor(tol,r,g,b,alpha,r1,g1,b1,fakeAlpha){
if(
	(r >= (r1-tol) && 
     r <= (r1+tol)) 
	&&
	(g >= (g1-tol) && 
     g <= (g1+tol)) 
	&&
	(b >= (b1-tol) && 
     b <= (b1+tol)) 
	&&
	(alpha != fakeAlpha)
)	 return true;
else return false;
}



//===============  RGB =================  

Toolbar.prototype.flipColorRgb=function(){
if(this.syncing)return;
var scr			=gPic.screen;
var on			=(_obj("iColorRgbOn").checked)?1:0;
var rgbOn		=(scr.colorRgbRed || scr.colorRgbGreen || scr.colorRgbBlue)?1:0;
scr.ColorRgbOn	=on; 
if(rgbOn)scr.paint();	
}

Toolbar.prototype.chgColorRgb=function(){
if(this.syncing)return;
var scr				=gPic.screen;
scr.colorRgbRed		=_obj("colorRgbRed").value*1;
scr.colorRgbGreen	=_obj("colorRgbGreen").value*1;
scr.colorRgbBlue	=_obj("colorRgbBlue").value*1;
var rgbOn			=(scr.ColorRgbOn && (scr.colorRgbRed || scr.colorRgbGreen || scr.colorRgbBlue))?1:0;
if(rgbOn)scr.paint();
}

Toolbar.prototype.resetColorRgb=function(){
var scr			=gPic.screen;
var rgbOn		=(scr.ColorRgbOn && (scr.colorRgbRed || scr.colorRgbGreen || scr.colorRgbBlue))?1:0;
scr.ColorRgbOn		=0;
scr.colorRgbRed		=50;
scr.colorRgbGreen	=0;
scr.colorRgbBlue	=-50;
if(rgbOn)scr.paint();
this.syncColorRgb();
}


//----------- syncColorRgb() ---------
Toolbar.prototype.syncColorRgb=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
var tmpsync	=this.syncing;
this.syncing=1;
_obj("iColorRgbOn").checked=(scr.ColorRgbOn)?true:false;
this.setVal("colorRgbRed",scr.colorRgbRed);
this.setVal("colorRgbGreen",scr.colorRgbGreen);
this.setVal("colorRgbBlue",scr.colorRgbBlue);
this.syncing=tmpsync;
}


//=============  MASK =============  

Toolbar.prototype.flipMask=function(){
var on	=(_obj("mskON").checked)?1:0;
gPic.screen.MaskOn	=on;
gPic.screen.paint();
}

Toolbar.prototype.chgMask=function(){
if(this.syncing)return;
var scr		=gPic.screen;
var on		=(_obj("mskON").checked)?1:0;
var typ		=this.maskType();
var mdir	=this.maskDir();
var mstart	=_obj("mskStart").value*1;
var blur	=_obj("mskBlur").value*1;
scr.MaskOn	=on;
scr.maskType=typ;
scr.maskDirection=mdir;
scr.maskRed		=_obj("mskRed").value*1;
scr.maskGreen	=_obj("mskGreen").value*1;
scr.maskBlue	=_obj("mskBlue").value*1;
scr.maskSolid	=(_obj("mskSolid").checked)?1:0;
scr.maskSolidAlpha	=_obj("mskSolidAlpha").value*1;
this.setVars("mask",mstart,blur,1);	
if(scr.MaskOn)scr.paint();
}

Toolbar.prototype.maskType=function() {
// 1-"linear", 2-"radial", 3-"edge"
if(_obj("mskT3").checked)return 3;
if(_obj("mskT2").checked)return 2;
return 1;
}

Toolbar.prototype.maskDir=function() {
var tmp=document.getElementsByName("mskDir");
for(var i=0;i<tmp.length;i++){  if(tmp[i].checked)  return i; }
return 0;}

Toolbar.prototype.configMask=function() {	
_obj("mskDirs").style.display=(gPic.screen.maskType==1)?"":"none";
}

Toolbar.prototype.resetMask=function() {
var scr			=gPic.screen;	
var MaskOn		=scr.MaskOn;
scr.MaskOn		=0;
scr.maskRed		=0;
scr.maskGreen	=0;
scr.maskBlue	=0;
scr.maskSolid	=0;
scr.maskSolidAlpha=128;
if(MaskOn)scr.paint();
this.syncMask();
}

Toolbar.prototype.syncMask=function(){
if(!gEmbedded)return;
var tmpsync	=this.syncing;
this.syncing=1;
var scr		=gPic.screen;
var typ		=scr.maskType;
var mdir	=scr.maskDirection;
var mstart	=scr.maskStart;
var blur	=scr.maskBlur;
//msg("mskON="+scr.MaskOn);
_obj("mskON").checked=(scr.MaskOn==1)?true:false;
if(typ>1 && mdir>1)mdir=scr.maskDirection=0;
//$("#mskT0").dgUncheck($("#box-mskT0"));
$("#mskT1").dgUncheck($("#box-mskT1"));
$("#mskT2").dgUncheck($("#box-mskT2"));
$("#mskT3").dgUncheck($("#box-mskT3"));
switch(typ){
 //case 0: $("#mskT0").dgCheck($("#box-mskT0")); break;
 case 2: $("#mskT2").dgCheck($("#box-mskT2")); break;
 case 3: $("#mskT3").dgCheck($("#box-mskT3")); break;
 case 1: default : $("#mskT1").dgCheck($("#box-mskT1")); break;
}
$("#mskD0").dgUncheck($("#box-mskD0"));
$("#mskD1").dgUncheck($("#box-mskD1"));
$("#mskD2").dgUncheck($("#box-mskD2"));
$("#mskD3").dgUncheck($("#box-mskD3"));
switch(mdir){
 case 0: $("#mskD0").dgCheck($("#box-mskD0")); break;
 case 1: $("#mskD1").dgCheck($("#box-mskD1")); break;
 case 2: $("#mskD2").dgCheck($("#box-mskD2")); break;
 case 3: $("#mskD3").dgCheck($("#box-mskD3")); break;
}
this.setVal("mskStart",mstart);
this.setVal("mskBlur",blur);
this.setVal("mskRed",scr.maskRed);
this.setVal("mskGreen",scr.maskGreen);
this.setVal("mskBlue",scr.maskBlue);
this.setVal("mskSolidAlpha",scr.maskSolidAlpha);
_obj("mskSolid").checked=(scr.maskSolid==1)?true:false;
this.configMask();
this.syncing=tmpsync;
}




//======== FULTERS ========


//------- flipFulters() ------
Toolbar.prototype.flipFulters=function(){
if(this.syncing)return;
var on=(_obj("FultersOn").checked)?1:0;
gPic.screen.FultersOn=on; 
gPic.screen.paint();	
}


//------- applyFulterAry ------
Toolbar.prototype.applyFulterAry=function(add,noforce){
if(add==null)add=(_obj("fultersAddMode").checked)?1:0;
if(this.syncing)return;
var scr=gPic.screen;
var inputs = document.getElementById('customMatrix').getElementsByTagName('input');
var txt = "";
for (var i=0; i<inputs.length; i++) {
	if(i)txt+=",";
	txt+=inputs[i].value;
}
var ary=txt.split(",");
txt+="|";
if(add)scr.fultersList	+=txt;
else   scr.fultersList	 =txt;
//--- currently applying fulters ---
if(scr.FultersOn){
	scr.paint();
	return;
}
if(noforce)return;
//--- they clicked the apply button
scr.FultersOn=1; 
this.syncing=1;
_obj("FultersOn").checked=true;
this.syncing=0;
scr.paint();
}

//--------- load a filter and apply ------
Toolbar.prototype.loadFulterName=function(name,noforce){
var add=(_obj("fultersAddMode").checked)?1:0;
if(add)gPic.screen.fultersList	+=name+"|";
else   gPic.screen.fultersList	 =name+"|";
if(gPic.screen.FultersOn){
	if(!add)gPic.screen.paint();
	else gPic.screen.applyFulter(name);
	return;
}
if(noforce)return;
gPic.screen.FultersOn=1; 
this.syncing=1;
_obj("FultersOn").checked=true;
this.syncing=0;
gPic.screen.applyFulters();
}


//--------- load a filter array and apply ------
Toolbar.prototype.loadFulterArray=function(name){
var ary;
switch(name){
	case "emboss" 	: ary= [ -0.2, -1,  0, -6.8,  5.6, 4.4, 0, -1, 0.2];	break;
	case "glarify"	: ary= [ 4, -1,  0.6, -0.6,  5, -1, 0, -1, -4.2];	break;
	case "sharpen" 	: ary= [0,-1,0,-1,5,-1,0,-1,0]; break;
	default			: ary= [0,-1,0,-1,5,-1,0,-1,0]; break;
} 
this.syncing=1;
var inputs = document.getElementById('customMatrix').getElementsByTagName('input');
for (var i=0; i<ary.length; i++) {
	inputs[i].value=ary[i];
}
this.syncing=0;
this.applyFulterAry(null,1);
}


//------- reset ------
Toolbar.prototype.resetFulters=function(){
var scr=gPic.screen;
var FultersOn	=scr.FultersOn;
scr.FultersOn	=0;
scr.fultersList	="";	
if(FultersOn)gPic.screen.paint();
this.syncFulters();
}


//------------------- sync fulters -----------------------     
Toolbar.prototype.syncFulters=function(){
if(!gEmbedded)return;
var sync=this.syncing;
this.syncing=1;
_obj("FultersOn").checked =(gPic.screen.FultersOn)?true:false;
this.syncing=sync;
}




//======================= OilPaint ========================

//-------flipOilPaint() ------
Toolbar.prototype.flipOilPaint=function(){
if(this.syncing)return;
var scr=gPic.screen;
var on=(_obj("iOilPaintOn").checked)?1:0;
scr.OilPaintOn=on; 
scr.paint();	
}

Toolbar.prototype.chgOilPaint=function(){
if(this.syncing)return;
var on=(_obj("iOilPaintOn").checked)?1:0;
var scr=gPic.screen;
scr.OilPaintRadius		=this.getVal("iOilPaintRadius");
scr.OilPaintIntensity	=this.getVal("iOilPaintIntensity");;
if(on)scr.paint();	
}


Toolbar.prototype.resetOilPaint=function(){
if(this.syncing)return;
var scr=gPic.screen;
var OilPaintOn			=scr.OilPaintOn;
scr.OilPaintOn			=0;
scr.OilPaintRadius		=4;
scr.OilPaintIntensity	=55;
if(OilPaintOn)scr.paint();	
this.syncOilPaint();
}


Toolbar.prototype.syncOilPaint=function(){
if(!gEmbedded)return;
var sync=this.syncing;
this.syncing=1;
var scr=gPic.screen;
_obj("iOilPaintOn").checked =(gPic.screen.OilPaintOn)?true:false;
this.setVal("iOilPaintRadius",scr.OilPaintRadius);
this.setVal("iOilPaintIntensity",scr.OilPaintIntensity);
this.syncing=sync;
}




//======================= Posterize ========================

//-------flipPosterize() ------
Toolbar.prototype.flipPosterize=function(){
if(this.syncing)return;
var scr=gPic.screen;
var on=(_obj("iPosterizeOn").checked)?1:0;
scr.PosterizeOn=on; 
scr.paint();	
}

Toolbar.prototype.chgPosterize=function(radius_changed){
if(this.syncing)return;
var on=(_obj("iPosterizeOn").checked)?1:0;
var scr=gPic.screen;
scr.PosterizeRadius		=this.getVal("iPosterizeRadius");
if(radius_changed){
	scr.PosterizeIntensity	=scr.PosterizeRadius;
	this.setVal("iPosterizeIntensity",scr.PosterizeIntensity);
}else{
	scr.PosterizeIntensity	=this.getVal("iPosterizeIntensity");
}
if(on)scr.paint();	
}


Toolbar.prototype.resetPosterize=function(){
if(this.syncing)return;
var scr=gPic.screen;
var PosterizeOn			=scr.PosterizeOn;
scr.PosterizeOn			=0;
scr.PosterizeRadius		=64;
scr.PosterizeIntensity	=64;
if(PosterizeOn)scr.paint();	
this.syncPosterize();
}


Toolbar.prototype.syncPosterize=function(){
if(!gEmbedded)return;
var sync=this.syncing;
this.syncing=1;
var scr=gPic.screen;
_obj("iPosterizeOn").checked =(gPic.screen.PosterizeOn)?true:false;
this.setVal("iPosterizeRadius",scr.PosterizeRadius);
this.setVal("iPosterizeIntensity",scr.PosterizeIntensity);
this.syncing=sync;
}



//========================= GRADIENT ========================

Toolbar.prototype.applyGradient=function(view) {
if(this.syncing)return;
this.syncing=1;
if(view==null)view="";
var position=_obj(view+"grdP").value*1;
var typ=_obj(view+"grdT1").checked;
if(typ)typ="flat";
else{typ=_obj(view+"grdT2").checked;if(typ)typ="linear";else typ="radial";}
var pct1=_obj(view+"grdC1").value*1;
var pct2=_obj(view+"grdC2").value*1;
switch(position){
 case 1: position="left";   break;
 case 2: position="top";    break;
 case 3: position="right";  break;
 case 4: position="bottom"; break;
 case 5: position="center"; break;
}
if(typ=="linear" && position=="center")position="right";
_obj("i"+view+"FrameColorImg").style.display=(typ=="flat")?"block":"none";
_obj("i"+view+"GradientTable").style.display=(typ!="flat")?"block":"none";
if(view){
	gPic.viewSetGradient(null,typ,position,null,null,pct1,pct2);
}else{
	gPic.screen.setGradient(null,typ,position,null,null,pct1,pct2);
}
this.syncing=0;
}


Toolbar.prototype.setFrameColor=function(v)  {
gToolbar.record("s.setFrameColor('"+v+"');",1);	
gPic.screen.setFrameColor(v);
}







//=============================  SHADOWS ===================================  
// eg. "1 0px 0px 16px 2px rgba(0,0,0,1)| ...| ...";

//---- apply shadows -------
Toolbar.prototype.applyShadows=function(color) {
if(this.syncing==1)return;
var scr=gPic.screen;
if(scr.currentShadow==null)scr.currentShadow=0;
var oldStr=gToolbar.getCurrentShadow(scr);
var on=(_obj("shdON").checked)?1:0;
scr.shadowsOn=on;
var x=_obj("shdX").value;
var y=_obj("shdY").value;
var b=_obj("shdB").value;
var s=_obj("shdS").value;
var o=_obj("shdO").value;
//--applyto
var applyTo=_obj("shdApplyTo2").checked; 
if(applyTo)applyTo=2; 	//image
else applyTo=1;				//frame
//--inset
var inset=(_obj("shdI").checked)?" inset":"";
if(scr.lockTyp=="fade" && scr.currentShadow==0)inset=" inset"; 		//override it
//--color
if(!color){
	color	= gToolbar.getShadowColor(scr,o);
}else{
	if(!_in(color,"rgba"))color = HEX2RGBA(color,o);
}
//--string
var str=applyTo+" "+x+"px "+y+"px "+b+"px "+s+"px "+color+inset ;
this.updateLayerThumb(scr);
//--- apply to current shadow
this.updateShadow(scr,str,scr.currentShadow);
this.syncShadowsList();
}

//--------------------- updateShadow ---------------------
Toolbar.prototype.updateShadow=function(scr,str,ix){
var shadowsAry=scr.shadows.split("|");
if(shadowsAry.length>1){
	var txt="";
	for(var i=0;i<shadowsAry.length;i++){
		if(shadowsAry[i]){
			if(i==ix)txt+=str;
			else txt+=shadowsAry[i];
			if(i<shadowsAry.length-1)txt+="|";
		}
	}
	str=txt;
}
gToolbar.record("s.setShadows('"+str+"',"+scr.shadowsOn+");",1);
scr.setShadows(str,scr.shadowsOn);
gPic.saveDoneStuff();
}


//--------------------- resetshadow ---------------------
Toolbar.prototype.resetShadows=function(scr){
scr.shadows="1 0px 0px 16px 2px rgba(0,0,0,1)";  
scr.currentShadow=0;
if(scr.locktyp=="fade")scr.shadows+=" inset";
this.syncShadows();
gPic.saveDoneStuff();
}

//--------------------- get current shadow ---------------------
Toolbar.prototype.getCurrentShadow=function(scr,ix){
var shadowsAry=scr.shadows.split("|");
if(scr.currentShadow==null)scr.currentShadow=0;
if(ix==null)ix=scr.currentShadow;
return shadowsAry[ix];
}


//--------------------- get shadow hex color ---------------------
Toolbar.prototype.getShadowHexColor=function(scr){
var rgba=this.getShadowColor(scr);
return RGBA2HEX(rgba);
}

//--------------------- get rgb ---------------------
Toolbar.prototype.getRgb=function(shadows,ix){
var shadowsAry=shadows.split("|");
var str=shadowsAry[ix];
var a=str.split(" ");
return a[5];
}


//--------------------- get shadow color ---------------------
Toolbar.prototype.getShadowColor=function(scr,opacity){
var shadows=scr.shadows;
var shadowsAry=shadows.split("|");
if(scr.currentShadow==null)scr.currentShadow=0;
var str=shadowsAry[scr.currentShadow];
var a=str.split(" ");
var rgba = a[5];
if(opacity==null)return rgba;
var a=rgba.split(",");
var b=a[3].split(")");
return a[0]+","+a[1]+","+a[2]+","+opacity+")";
}

//--------------------- set shadow color ---------------------
Toolbar.prototype.setShadowColor=function(color){
//called by _colors.js
//msg("set="+color);
gToolbar.applyShadows(color);
this.syncShadows();
gPic.saveDoneStuff();
}


//--------------------- get shadow opacity ---------------------
Toolbar.prototype.getShadowOpacity=function(scr,ix){
if(scr.currentShadow==null)scr.currentShadow=0;
if(ix==null)ix=scr.currentShadow;
var shadows=scr.shadows;
var shadowsAry=shadows.split("|");
var str=shadowsAry[ix];
return this.getRgbaOpacity(str);
}


//--------------------- get rgba opacity ---------------------
Toolbar.prototype.getRgbaOpacity=function(str){
var a = str.split("rgba(");
var b = a[1].split(",");
return b[3].split(")")[0]*1;
}

//--------------------- new shadow ---------------------
Toolbar.prototype.addNewShadow=function(){
var scr=gPic.screen;
var shadowsAry=scr.shadows.split("|");
switch(shadowsAry.length){
	case 1  :	var str="0 0px 0px 0px 60px rgba(0,255,255,1) inset";  break;
	case 2 	:	var str="0 0px 0px 10px 40px rgba(255,200,255,1) inset";  break;
	case 3 	:	var str="0 0px 0px 0px 20px rgba(200,0,255,1) inset";  break;
	case 4 	:	var str="0 0px 0px 100px 10px rgba(0,0,0,1) inset";  	  break;
	case 8	:	var str="0 -10px -10px 10px 10px rgba(255,255,255,1) inset";break;
	case 7 	:	var str="0 -20px -20px 0px 5px rgba(100,255,0,1) inset";  break;
	case 6 	:	var str="0 -30px -30px 10px 0px rgba(255,50,200,1) inset"; break;
	case 5 	:	var str="0 -40px -40px 90px 20px rgba(50,100,200,1) inset"; break;
	default :	var str="0 0px 0px 20px 20px rgba(200,100,200,1) inset";  break;
}
if(shadowsAry[0]==""){
	scr.shadows=str;
	scr.currentShadow=0;
	this.syncShadows();
	return;
}
scr.currentShadow=shadowsAry.length;
scr.shadows+="|"+str;
this.syncShadows();
gPic.saveDoneStuff();
}

//--------------------- delete shadow ---------------------
Toolbar.prototype.deleteShadow=function(ix){
var scr=gPic.screen;
var shadowsAry=scr.shadows.split("|");
//msg("ary="+shadowsAry);
var str="";
for(var i=0;i<shadowsAry.length;i++){
	if(ix!=i){
		if(str)str+="|";
		str+=shadowsAry[i];
	}
}
scr.shadows=str;
msg("deleted ix="+ix+" str="+str)
scr.currentShadow=0;
this.syncShadows();
gPic.saveDoneStuff();
}


//--------------------- select shadow ---------------------
Toolbar.prototype.selectShadow=function(ix){
gPic.screen.currentShadow=ix;
this.syncShadows();
}


//--------------------- sync Shadows ---------------------
Toolbar.prototype.syncShadowsList=function(){
if(!gEmbedded)return;
var scr=gPic.screen;
if( !scr.shadows || !_in(scr.shadows,"rgba(") )scr.shadows="1 0px 0px 16px 2px rgba(0,0,0,1)";  
var shadowsAry=scr.shadows.split("|");
if(scr.currentShadow==null)scr.currentShadow=0;
if(scr.currentShadow > shadowsAry.length-1)scr.currentShadow=0;
//--- create list of shadows
var list="";
//if(shadowsAry.length>1){
list="<TABLE style='width:100%;'>"
for(var i=0;i<shadowsAry.length;i++){
		var str=shadowsAry[i];
		var a=str.split(" ");
		var applyto=(a[0]=="1")?"frame":"image";
		var inset=(_in(str,"inset"))?"inset":"outer";
		var color=this.getRgb(scr.shadows,i);
		list+="<tr>";
		list+="<td width=60 onclick='gToolbar.selectShadow("+i+")'  style='cursor:pointer;font-weight:"+((i==scr.currentShadow)?"bold":"normal")+"' onmouseover='_hilite(this)' onmouseout='_lolite(this)' >&nbsp;&nbsp;shadow "+(i+1)+"&nbsp;&nbsp;</td>";
		list+="<td width=28>"+applyto+"&nbsp;&nbsp;</td>";
		list+="<td width=28>"+inset+"&nbsp;&nbsp;</td>";
		list+="<td width=28><div style='width:15px;height:10px;background:"+color+";'></div></td>";
		if(shadowsAry.length>1)list+="<td align=right><img src='_pvt_images/delete.png'  onclick='gToolbar.deleteShadow("+i+")' style='cursor:pointer;width:16px;' title='delete'></td>";
		list+="</tr>";
}
list+="</TABLE>";
//msg("list="+list);
_obj("iShadowsList").innerHTML=list;
}




//--------------------- sync Shadows ---------------------	//$$$$$
Toolbar.prototype.syncShadows=function(){
//--- this syncs shadows, corners, and clipRadius ----
if(!gEmbedded)return;
var scr=gPic.screen;
if( !scr.shadows || !_in(scr.shadows,"rgba(") )scr.shadows="1 0px 0px 16px 2px rgba(0,0,0,1)";  
var shadowsAry=scr.shadows.split("|");
//msg("syncShadows");
if(scr.currentShadow==null)scr.currentShadow=0;
this.syncShadowsList();
//--- sync the controls
var str			=shadowsAry[scr.currentShadow];
var on			=scr.shadowsOn;
var radius		=scr.cornerRadius.replace("%","")*1;
var clipradius	=scr.clipRadius.replace("%","")*1;
var opacity		=this.getShadowOpacity(scr);
var a			=str.split(" ");
var applyto		=a[0];
var x			=a[1].replace("px","")*1;
var y			=a[2].replace("px","")*1;
var b			=a[3].replace("px","")*1;
var s			=a[4].replace("px","")*1;
var inset		=(_in(str,"inset"))?true:false;
_obj("shdON").checked=(on==1)?true:false;
this.setVal("shdS",s);
this.setVal("shdX",x);
this.setVal("shdY",y);
this.setVal("shdB",b);
this.setVal("shdO",opacity);
this.setVal("shdR",radius);
this.setVal("clipRadius",clipradius);
_obj("shdI").checked=inset;
$("#shdApplyTo1").dgUncheck($("#box-shdApplyTo1"));
$("#shdApplyTo2").dgUncheck($("#box-shdApplyTo2"));
if(applyto==1)$("#shdApplyTo1").dgCheck($("#box-shdApplyTo1"));
else		  $("#shdApplyTo2").dgCheck($("#box-shdApplyTo2"));
}


//---- flipShadows --------
Toolbar.prototype.flipShadows=function(){
//msg("sh="+gPic.screen.shadowsOn);
gPic.screen.shadowsOn=(gPic.screen.shadowsOn==1)?0:1;
_obj("shdON").checked=(gPic.screen.shadowsOn==1)?true:false;
this.applyShadows();
}

//----------------------------- corners ----------------------------------------  
Toolbar.prototype.applyCorners=function(radius) {
if(this.syncing==1)return;
if(radius==null)radius=_obj("shdR").value;
gPic.applyVars(gPic.screen,"corners",radius,1);
gPic.saveDoneStuff();
}


//----------------------------- clipRadius ----------------------------------------
Toolbar.prototype.applyClipRadius=function(radius){
if(this.syncing==1)return;
if(radius==null)radius=_obj("clipRadius").value;
gPic.applyVars(gPic.screen,"clip",radius,1);
gPic.saveDoneStuff();
}



//======================== MOUSE MODE ==============================

/*
Toolbar.prototype.myonclick=function(obj,mode){  
if(obj.timerID){
   clearTimeout(obj.timerID);
   obj.timerID=null;
   this.changeMouseMode(mode);
}else{
   obj.timerID=setTimeout(function(){ obj.timerID=null; gToolbar.expandControl(_obj("i"+mode+"Table")); }, 350); 
}}

Toolbar.prototype.changeMouseMode=function(mode){	// where is this called from??
console.trace();
try{
this.changeMode(mode);
}catch(e){}
}
*/

Toolbar.prototype.changeMode=function(mode){  
var btn=parent._obj("i"+mode+"Btn");
var realmode=gToolbar.modes[mode]; //legacy var name fix
//--- do the side menu ---
//try{_obj("i"+gPic.mousemode+"Mouse").style.display="none";}catch(e){}
//try{_obj("i"+mode+"Mouse").style.display="";}catch(e){}
gPic.mousemode=mode;
this.modeBtn=btn;
//----- the animation mode ----
_obj('iMouseModeName').innerHTML="&nbsp;[&nbsp;"+mode+"&nbsp;]&nbsp;";
try{parent._obj('iMouseModeMenuName').innerHTML=mode;}catch(e){}
gPic.xmode="x"+realmode;
gPic.ymode="y"+realmode;
//---- the handles mode ----
try{
	for(var i=0;i<parent.gModeBtns.length;i++){
		var o=parent._obj("i"+parent.gModeBtns[i]+"Btn");	
		o.style.background=(mode==parent.gModeBtns[i])?"#adf":"#ffffff";
	}
}catch(e){}
var ani=(mode=="Turn" || mode=="Move" || mode=="Size")?0:1; // see animation.startStop() as well
parent._obj("iAutoFwd").style.opacity=(ani)?1:0.2;
parent._obj("iAutoFwd").style.cursor =(ani)?"pointer":"default";
}

//--- moved to toolbar.php... perhaps we should hide that menu for non images? ----
//Toolbar.prototype.checkMouseModeMenu=function(scr){
//if(scr.media!="image")_obj("iModeBtns").style.display="none";
//_obj("iMouseModeBtn").style.display=(scr.media!="image")?"none":"";
//}

Toolbar.prototype.toggleMouseModeMenu=function(force){
//if(force==null)force=(_obj("iModeBtns").style.display=="none")?1:0;
//_obj("iModeBtns").style.display=(force)?"":"none";
//set("iModeBtns",force);
}


Toolbar.prototype.resetCurrentMode=function(){
// see gToolbar.modes[mode]; //legacy var name fix
var mode=gPic.mousemode.toLowerCase();
switch(mode){
	case "warp"	:
		this.setVars("warp",50,50);
		this.syncControls();
		break;	
	case "panel"	:
		this.setVars("split",50,50);
		this.syncControls();
		break;	
	case "skew"	:
		this.resetPanelSkew(1);
		break;	
	case "slide"	:
		this.resetSlide(1);
		break;	
	case "zoom"	:
		this.resetZoom(1);
		break;	
	case "turn"	:
		this.quickReset(1);
		break;	
	case "spin"	:
		this.resetSpin(1);
		break;	
	case "tilt"	:
		this.resetTilt(1);
		break;	
	case "move"	:
		this.resetMove();
		break;	
	default		:
		this.setVars(mode,50,50);
		this.syncControls();
		break;
}
}


//========================= CSS COLOR FILTERS =========================
// CSS eg.  "saturate(275%) brightness(93%) contrast(270%) blur(9px) hue-rotate(162deg) sepia(45%) grayscale(47%)"

/*
Toolbar.prototype.viewCSSFilters=function(filters,scr){  
if(this.syncing)return;
if(scr==null)scr=gPic.screen;
scr.cssfilters=filters;
scr.div.style.webkitFilter=scr.div.style.filter=filters;
this.record("gToolbar.viewCSSFilters('"+filters+"');",1);
//msg("Toolbar.viewCSSFilters="+filters);
}
*/

//----------- sync css filters ---------


Toolbar.prototype.syncCSSFilters=function(view){
if(!gEmbedded)return;
if(view==null)view="";
var obj=(view=="view")? gPic : gPic.screen;
var filters=obj.cssfilters;
this.setVal(view+'fil_sat',this.getCSSFilter('saturate',filters));
this.setVal(view+'fil_bri',this.getCSSFilter('brightness',filters));
this.setVal(view+'fil_con',this.getCSSFilter('contrast',filters));
this.setVal(view+'fil_hue',this.getCSSFilter('hue-rotate',filters));
this.setVal(view+'fil_blu',this.getCSSFilter('blur',filters));
this.setVal(view+'fil_sep',this.getCSSFilter('sepia',filters));
this.setVal(view+'fil_gra',this.getCSSFilter('grayscale',filters));
this.setVal(view+'fil_inv',this.getCSSFilter('invert',filters));
this.setVal(view+'opcXY',obj.opacity2);
}


Toolbar.prototype.resetCSSFilters=function(scr){
if(scr==null)scr=gPic.getTarget(gPic.screen,"color");
//scr.viewCSSFilters("");
this.resetCSSVals();
this.record("s.viewCSSFilters('');",1);
scr.viewCSSFilters("");
}

Toolbar.prototype.resetCSSVals=function(view){
if(view==null)view="";
//syncing????
this.setVal(view+'fil_sat',100);
this.setVal(view+'fil_bri',100);
this.setVal(view+'fil_con',100);
this.setVal(view+'fil_hue',0);
this.setVal(view+'fil_blu',0);
this.setVal(view+'fil_sep',0);
this.setVal(view+'fil_gra',0);
this.setVal(view+'fil_inv',0);
this.setVal(view+'opcXY',1);
}

Toolbar.prototype.getCSSFilter=function(f,filters){
f=f.split("(")[0];
if(filters==null)filters="";	
if(!_in(filters,f)){
	var v=(f=="saturate" || f=="brightness" || f=="contrast")?100:0;
	return v;
}
var v=0;
a=filters.split(" ");
for(var i=0;i<a.length;i++){ 
	if(a[i]){ 
		if(_in(a[i],f)){
			var v=a[i].split("(")[1];
			v=v.split(")")[0];
			return parseInt(v);
}	}	}
return v;
}




//============================== APPLY CHANGES ===============================


Toolbar.prototype.viewFlipDopple=function(v)  { 
v=(v)?1:0;
if(!this.syncing){ 
	if(v!=gPic.dopple)gPic.saveDoneStuff("gToolbar.viewFlipDopple("+gPic.dopple+");gToolbar.viewFlipDopple("+v+");");
	gPic.dopple=v; 
}}   

Toolbar.prototype.viewPauseDelay=function(x)   { 
x=parseInt(x);  
if(x!=gPic.pauseDelay)gPic.saveDoneStuff("gToolbar.viewPauseDelay("+gPic.pauseDelay+");gToolbar.viewPauseDelay("+x+");");
gPic.pauseDelay=(x*1);  
set("pauseDelay",gPic.pauseDelay);  
}

Toolbar.prototype.flipCanvas=function(v)   { if(!this.syncing){ gPic.flipCanvas(v);  }}
Toolbar.prototype.flipMultipleImages=function(v)   { if(!this.syncing){ gPic.screen.multipleImages=v;  gPic.screen.paint(); }}
Toolbar.prototype.flipScrambleImages=function(v)   { if(!this.syncing){ gPic.screen.scrambleImages=v;  gPic.screen.paint();  }}
Toolbar.prototype.flipOffsetWarp=function(v)  { if(!this.syncing){ gPic.screen.offsetWarp=(v)?1:0; gPic.screen.repaint(); }}   
Toolbar.prototype.flipFullWarp=function(v)  { if(!this.syncing){ gPic.screen.fullWarp=(v)?1:0; gPic.screen.repaint(); }}   
Toolbar.prototype.flipOffsetSplit=function(v)  { if(!this.syncing){ gPic.screen.offsetSplit=(v)?1:0; gPic.screen.repaint(); }}   
Toolbar.prototype.flipFullSplit=function(v)  { if(!this.syncing){ gPic.screen.fullSplit=(v)?1:0; gPic.screen.repaint(); }}   
Toolbar.prototype.flipDominantColor=function(v) { if(!this.syncing){ gPic.screen.flipDominantColor( ((v)?1:0)); }}
Toolbar.prototype.flipVignetteColor=function(v) { if(!this.syncing){ gPic.screen.flipVignetteColor( ((v)?1:0)); }}
Toolbar.prototype.flipLockPosition=function(v)  { if(!this.syncing){ gPic.flipLockPosition(gPic.screen,((v)?1:0));  }}   
Toolbar.prototype.flipResizeChildren=function(v)  { if(!this.syncing){ gPic.screen.flipResizeChildren=((v)?1:0); }}

Toolbar.prototype.flipLockZindex=function(v,flip)  { 
	if(!this.syncing){ 
		var scr=gPic.screen;
		if(flip)gPic.lockZindex=(gPic.lockZindex)?0:1;
		else gPic.lockZindex=((v)?1:0); 
		set("lockZindex",gPic.lockZindex); 
		_obj("iLockZindexPadlock").src=(gPic.lockZindex)?"_pvt_images/Lock-Lock.png" :  "_pvt_images/Lock-Unlock.png" ; 
}	}

Toolbar.prototype.hideImage=function(v)    { 
	if(!this.syncing){ 
		if(v==null)v=!gPic.screen.hideImage;
		gPic.screen.hideImg(((v)?1:0)); 
		gPic.screen.paint();
		_obj("iHideImage").checked =(v)?1:0;
		_obj("iHideImageEye").src ="_pvt_images/"+((v)?"hideOff.png":"hideOn.png");
		_obj("iHideImageEye").title =(v)?"unhide image":"hide image";
		this.configToolbar();
}	}

//Toolbar.prototype.repaint=function()       { if(!this.syncing && !gPic.screen.animation.playing){ gPic.screen.repaint(); }}
//Toolbar.prototype.naturalSize=function(v)  { if(!this.syncing){  var scr=gPic.getLockParent(gPic.screen); gToolbar.changeScreen(scr.scrix); scr.naturalSize(v); gPic.repaintSizes(scr.scrix); }}
//Toolbar.prototype.squareSize=function(v)   { if(!this.syncing){  var scr=gPic.getLockParent(gPic.screen); gToolbar.changeScreen(scr.scrix); scr.squareSize(v);  gPic.repaintSizes(scr.scrix); }}



Toolbar.prototype.setBlend=function(v){
v=parseInt(v);
var n=gBlends[v];
if(n=="")n="none";
_obj("iBlendName").innerHTML=n;
//msg("blend v="+v+", n="+n);
if(!this.syncing)this.setVars("blend",v,0,1);
}
	

Toolbar.prototype.resetBlend=function(){
_obj("iBlendName").innerHTML="none";
if(!this.syncing)this.setVars("blend",0,0,1);
var tmp=this.syncing;
this.syncing=1;
this.setVal("iBlend",0);
this.syncing=tmp;
}


Toolbar.prototype.setXYSizes=function(x){ 	
if(this.syncing)return; 
var scr=gPic.screen;
var oldx=scr.xSize;
var chg=(x/oldx)*100;
var y=scr.ySize*(chg/100);
this.setVars("size",x,y);
}


Toolbar.prototype.rotatePic=function(e,mode,reset){   
this.chgD3Mode(mode);
if(reset)this.setVars('2drot',0,0);
else this.spinSlider(e,'perROT',0,90,180,270);
}

/*
Toolbar.prototype.rotateImage=function(event,reset){   
//if(reset && this.lastClick=="double")reset=1;
this.chgD3Mode(1);
if(reset){
	this.setVars('2drot',0,0);
}else{
	this.spinSlider(event,'perROT',0,90,180,270);
}}


Toolbar.prototype.centerScreen=function(scr,size,move){   
if(scr==null)scr=gPic.getTarget(gPic.screen,"size");
if(scr.hidden)this.unhideScreen(scr,scr.hiddenicon);
scr.natural=0;
scr.square=0;
if(size==null){
	if(scr.xSize==84){
		size=44; 
		move=23; 
	}else{
		size=84; 
		move=8; 
	}
}
move=(scr.lockPosition)?move:0; 
this.setVars("move",move,move); 
this.setVars("size",size,size); 
gPic.repaintSizes(scr.scrix);
this.syncFullscreenBtns();
this.syncSizes(); 
}
*/


Toolbar.prototype.centerScreen=function(){   
var scr=gPic.screen;
size=84; 
move=8; 
move=(scr.lockPosition)?move:0; 
this.setVars("move",move,move); 
this.setVars("size",size,size); 
gPic.repaintSizes(scr.scrix);
//this.syncFullscreenBtns();
this.syncSizes(); 
}


Toolbar.prototype.goFullscreen=function(v){
if(this.syncing)return;
var scr=gPic.screen;
if(scr.hidden){ this.unhideScreen(scr,scr.hiddenicon); return; }
if(v==null)v=(scr.xSize==100 && scr.ySize==100)?0:1;
//alert("v="+v+", ix="+scr.scrix);
if(v){
	scr.xSizeSave	=scr.xSize;
	scr.xMoveSave	=scr.xMove;
	scr.ySizeSave	=scr.ySize;
	scr.yMoveSave	=scr.yMove;
	scr.naturalSave =scr.natural;
	scr.natural		=0;
	scr.squareSave	=scr.square;
	scr.square		=0;
	scr.xSize		=scr.ySize  =100;
	scr.xMove		=scr.yMove  =0;
	gPic.repaintSizes(scr.scrix);
	this.syncFullscreenBtns();
	this.syncSizes(); 
	_obj("iFullScreen").src="_pvt_images/folders/toggle_collapse.png";
	var show=0;
}else{
	if(scr.xSizeSave!=100){
		scr.xSize	=scr.xSizeSave;
		scr.xMove	=scr.xMoveSave;
		scr.ySize	=scr.ySizeSave;
		scr.yMove	=scr.yMoveSave;
		scr.natural =scr.naturalSave;
		scr.square	=scr.squareSave;
		gPic.repaintSizes(scr.scrix);
		this.syncFullscreenBtns();
		this.syncSizes(); 
	}else{
		this.centerScreen();
	}
	_obj("iFullScreen").src="_pvt_images/folders/toggle_expand.png";
	var show=1;
}
this.configHandles();
//if(!show)this.showHandles(0);
}


Toolbar.prototype.hideScreen=function(scr){
if(scr==null)scr=gPic.screen;
if(scr.hidden){ this.unhideScreen(scr); return; }
//if(scr.typ=="lock")scr=scr.lockedByScreen;
var icon,src;
     if(gPic.hiddenScreen1==null){ icon="1"; gPic.hiddenScreen1=scr; }
else if(gPic.hiddenScreen2==null){ icon="2"; gPic.hiddenScreen2=scr; }
else if(gPic.hiddenScreen3==null){ icon="3"; gPic.hiddenScreen3=scr; }
else if(gPic.hiddenScreen4==null){ icon="4"; gPic.hiddenScreen4=scr; }
else if(gPic.hiddenScreen5==null){ icon="5"; gPic.hiddenScreen5=scr; }
else if(gPic.hiddenScreen6==null){ icon="6"; gPic.hiddenScreen6=scr; }
else if(gPic.hiddenScreen7==null){ icon="7"; gPic.hiddenScreen7=scr; }
else if(gPic.hiddenScreen8==null){ icon="8"; gPic.hiddenScreen8=scr; }
else if(gPic.hiddenScreen9==null){ icon="9"; gPic.hiddenScreen9=scr; }
else{ alert("Sorry, a max of 9 windows can be hidden at one time"); return; }
scr.frame.style.display="none";
//for(var j=0;j<scr.lockedScreens.length;j++){
// scr.lockedScreens[j].frame.style.display="none";
//}
scr.hidden=1;
scr.hiddenicon=icon;
var newscr;
for(var i=0;i<gPic.screens.length;i++){
 if(!gPic.screens[i].hidden && !gPic.screens[i].DELETED){
  newscr=gPic.screens[i];
  gToolbar.changeScreen(i);
  break;
}}
switch(scr.media){
 case "fimg" :
 case "image": src="pics.png";   break;
 case "video": src="videos.png"; break;
 case "file" : 
 case "link" : src="link.png";   break;
 default : src="picxie.png";
}
parent._obj("iWinShow9"+icon).src="_pvt_images/"+src;
parent._obj("iWinShow9"+icon).style.display="";
}


Toolbar.prototype.unhideScreen=function(scr,icon){
if(icon==null)icon=scr.hiddenicon;
if(scr==null)eval("scr=gPic.hiddenScreen"+icon+";");
eval("gPic.hiddenScreen"+icon+"=null;");
scr.hidden=0;
scr.frame.style.display="";
//for(var j=0;j<scr.lockedScreens.length;j++){
// scr.lockedScreens[j].frame.style.display="";
//}
parent._obj("iWinShow9"+icon).style.display="none";
gToolbar.changeScreen(scr.scrix,gPic.srix);
gToolbar.syncFullscreenBtns();
gToolbar.showHandles(1);
}



//--------------- flip Zoom ------------------------
Toolbar.prototype.flipZoom=function(event){
var scr=gPic.screen;
var v=scr.xZoom*1;
if(v<49){ this.resetZoom(1); return; }
else if(v<89)	v=90;
//else if(v<299)	v=300;
else v=38;
this.syncing=1;
this.setVal("zomX",v); 
this.setVal("zomY",v);
this.syncing=0;
this.setVal("zomXY",v); 
}

//-------------- change Screen -----------------------
Toolbar.prototype.changeScreen=function(ix,oldix){
gPic.changeScreen(ix,oldix);
gToolbar.syncControls();
}

//---------------- view Image  -----------------  
Toolbar.prototype.viewImage=function(){
var src;
var img=_obj("iCurrentEle");
var dv=_obj("iCurrentEleDiv");
var scr=gPic.screen;
if(!scr)return;
src=(scr.media=="image" && scr.gImg)? scr.gImg.src : scr.src;
if(scr.media=="image")scr.src=scr.gImg.src;
this.applyLabel(scr);
this.syncHandles();
this.configToolbar(scr);
var tmp=scr.src;
if(scr.media=="video")tmp="@vid@"+tmp;
if(scr.media=="link")tmp="@lnk@"+tmp;
try{ parent.frames[0].selectCurrent(tmp); }catch(e){}   // hilite the thumb
}


//---------------- apply label -----------------  
Toolbar.prototype.applyLabel=function(scr){
return;
/*
var src=(scr.gImg)? scr.gImg.src : "";
var label="";
var img=_obj("iCurrentEle");
var box=_obj("iCurrentEleID");
var dv=_obj("iCurrentEleDiv");
var zdv=_obj("iCurrentEleZndx");
zdv.innerHTML=scr.scrix+","+scr.frame.style.zIndex;
if(scr.gradientType!="flat")box.style.background	=scr.frame.style.background;
else 						box.style.background	=scr.frame.style.backgroundColor;
if(scr.lockedByScreen!=null){
	 if(scr.hideImage){
		src="";
	 	try{ src=gPic.getLockParent(scr).gImg.src; }catch(e){}
		setOpacity(img,10);
	 }
	 setOpacity(img,70);
	 label=scr.lockTyp;
}else{
	label=scr.lockTyp;
	if(scr.typ=="color"){
		label="bckdrop";
		if(scr.hideImage){
			setOpacity(img,0);
		}else{
			setOpacity(img,70);
		}	
	}else{
		if(scr.hideImage){
			label="hidden";
			setOpacity(img,10);
		}else{
			setOpacity(img,100);
		}
}	}
img.src=src;
if(label!=""){
 	dv.style.display="block";
 	dv.innerHTML="<center><div style='opacity:0.6;font-weight:bold;background:white;width:70px;height:18px;overflow:hidden;font-size:14px;position:relative;top:2px;letter-spacing:1px;'>&nbsp;"+label+"&nbsp;</div>";
}else{
 	dv.style.display="none";
}
*/
}


//============================= FKEYS ==============================


//--------------------------- kpress -------------------------------------------  
Toolbar.prototype.fKey=function(kbrd,k,e,mode,delta){
var rbtn=(e)? getRbtn(e) : 0 ;
var ctrl=(e)? e.ctrlKey : 0;
var scr=gPic.screen;
if(e){
	if (ctrl  &&  e.code === "KeyS") {
 		parent.savePixi();
 		e.preventDefault();
 		return false;
}	}
if(k>36&&k<41){    
	var x,y;
	switch(k){
		case 37	:	x=-1; y= 0; break;
		case 38	:	x= 0; y=-1; break;
		case 39	:	x= 1; y= 0; break;
		case 40	:	x= 0; y= 1; break;
	}
	if(mode==null)mode=gPic.mousemode.toLowerCase();
	switch(mode){
		case "image"	: this.setVars("image",scr.xImage+x,scr.yImage+y,1); 	break
		case "warp"		: this.setVars("warp",scr.xWarp+x,scr.yWarp+y,1); 		break
		case "panel"	: this.setVars("split",scr.xSplit+x,scr.ySplit+y,1); 	break
		case "skew"		: this.setVars("panelskew",scr.xSkew+x,scr.ySkew+y,1);	break
		case "fold"		: this.setVars("fold",scr.xFold+x,scr.yFold+y,1);		break
		case "zoom"		: scr.setVars("zoom",scr.xZoom+x,scr.yZoom+y,1);		break
		case "slide"	: this.setVars("slide",scr.xSlide+x,scr.ySlide+y,1); 	break
		case "move"		: this.setVars("move",scr.xMove+x,scr.yMove+y,1); 		break
		case "size"		: this.setVars("size",scr.xSize+x,scr.ySize+y,1);		break
		case "turn" 	: scr.rotate(k-36);  return;
		case "spin"		: this.chgD3Mode(1);
						  var str=gPic.getPerspectiveStr(scr,gPic.d3Mode);
						  var rotate=gPic.getPerRotate(str,scr)+x+y;
						  gToolbar.setVars('2drot',rotate,0,1); 			
						  break; 
		case "tilt"		: this.chgD3Mode(1);
						  var str=gPic.getPerspectiveStr();
						  var xx=gPic.getCSSPerspective(str,"Xskew")+x;				
						  var yy=gPic.getCSSPerspective(str,"Yskew")+y;
						  gToolbar.setVars('swivel',xx,yy,1); 
						  break; 
	}
	scr.repaint();			
	this.syncControls();
}
}




//=================================== OTHER ==================================

//------------------ showBorders ---------------------------
Toolbar.prototype.showBorders=function(){
gPic.showBorders=(_obj("ibdrs").checked)?1:0;
gPic.repaint();
}

//------------------ showNumbers ---------------------------
Toolbar.prototype.showNumbers=function(){
gPic.showNumbers=(_obj("inbrs").checked)?1:0;
gPic.repaint();
}

//--------------- show Busy -------------------------
Toolbar.prototype.showBusy=function(v){
if(v==null)v=(_obj("iBusy").style.display=="none")?1:0;
_obj("iBusy").style.display=(v)? "block" : "none" ;
}



//---------------- set Config Title --------------------
Toolbar.prototype.setConfigTitle=function(takepic){
try{parent._obj("iSaveConfigBtn").title="Save View ["+gPic.configName+"]";  }catch(e){}
if(!takepic)setTimeout("try{parent.hilitePixi(gPic.configName);}catch(e){}",1000);
}



//---------------- save Config -----------------------
Toolbar.prototype.saveConfig=function(name,x){
if(!name)return;
if(name!="_lastpic")gPic.configName=name;
asyncP("action=saveconfig&dir="+parent.gDir+"&name="+name+"&text="+escape(x),"saveconfig");
}



//---------------- lightnings -----------------------
Toolbar.prototype.spinSlider=function(e,id,v1,v2,v3,v4,v5,v6,v7,v8,v9,v10,v11,v12,v13,v14,v15,v16,v17,v18,v19,v20){ 
e.stopPropagation();
var v=this.getVal(id);
//msg("v="+v);
if(v<v1) v=v1;
else if(v2!=null && (v<v2 || v2<v1))v=v2;
else if(v3!=null && (v<v3 || v3<v1))v=v3;
else if(v4!=null && (v<v4 || v4<v1))v=v4;
else if(v5!=null && (v<v5 || v5<v1))v=v5;
else if(v6!=null && (v<v6 || v6<v1))v=v6;
else if(v7!=null && (v<v7 || v7<v1))v=v7;
else if(v8!=null && (v<v8 || v8<v1))v=v8;
else if(v9!=null && (v<v9 || v9<v1))v=v9;
else if(v10!=null && (v<v10 || v10<v1))v=v10;
else if(v11!=null && (v<v11 || v11<v1))v=v11;
else if(v12!=null && (v<v12 || v12<v1))v=v12;
else if(v13!=null && (v<v13 || v13<v1))v=v13;
else if(v14!=null && (v<v14 || v14<v1))v=v14;
else if(v15!=null && (v<v15 || v15<v1))v=v15;
else if(v16!=null && (v<v16 || v16<v1))v=v16;
else if(v17!=null && (v<v17 || v17<v1))v=v17;
else if(v18!=null && (v<v18 || v18<v1))v=v18;
else if(v19!=null && (v<v19 || v19<v1))v=v19;
else if(v20!=null && (v<v20 || v20<v1))v=v20;
else  v=v1;
this.setVal(id,v);
}

//---------------- lightnings (high to low)-----------------------
Toolbar.prototype.spinSliderBkwrds=function(e,id,v1,v2,v3,v4,v5,v6,v7,v8,v9){ 
e.stopPropagation();
var v=this.getVal(id);
v=Math.round(v*10)/10;
//msg("v="+v);
if(v>v2)				 v=v2;
else if(v3!=null && v>v3)v=v3;
else if(v4!=null && v>v4)v=v4;
else if(v5!=null && v>v5)v=v5;
else if(v6!=null && v>v6)v=v6;
else if(v7!=null && v>v7)v=v7;
else if(v8!=null && v>v8)v=v8;
else if(v9!=null && v>v9)v=v9;
else 					 v=v1;
this.setVal(id,v);
}



//============================= RESETS ==============================


//---------------- Quick Reset (unrotate) ----------------------
Toolbar.prototype.quickReset=function(scr){
if(!scr)scr=gPic.getTarget(gPic.screen,"turn");
scr.undoing=1;
gPic.applyPattern(scr);
scr.repaint();
gToolbar.syncControls();
scr.undoing=0;
}


//---------------------- discard all variables and reload ----------------------  
Toolbar.prototype.reset=function(ix){
if(ix==null)ix=gPic.scrix;
var scr=gPic.screens[ix];
var mom=gPic.getLockParent(scr);
scr.undoing=1;
mom.undoing=1;
gToolbar.resetPic(0);
gToolbar.resetColors(0);
gToolbar.resetFrame(0);
gToolbar.resetPerspective(0);
gPic.resetTiling(0);
gToolbar.resetRekord(0);
gToolbar.resetScramble(0);
gToolbar.goFullscreen(1); 
scr.repaint();
if(mom!=scr)mom.repaint();
gToolbar.syncControls();
scr.undoing=0;
mom.undoing=0;
}



//-------- major groups -------
Toolbar.prototype.resetPic=function(sync){  
	var scr=gPic.getTarget(gPic.screen,"image");
	scr.xWarp=	scr.yWarp=	50;
	scr.xImage=	scr.yImage=	50;
	scr.xSplit=	scr.ySplit=	50;
	scr.xFold=	scr.yFold=	50;
	scr.xSlide=	scr.ySlide=	50;
	scr.xCrop=	scr.yCrop=	50;
	scr.lCrop=	scr.tCrop=	0;
	scr.xStretch=scr.yStretch=0;
	scr.xSkew=	scr.ySkew=	0;
	scr.perspectivePnl=gDefaultPerspective;	//skew
	gToolbar.resetTilt();
	gPic.applyPattern(scr);
	if(sync){
		scr.repaint();
		gToolbar.syncControls();
}	}


Toolbar.prototype.resetFrame=function(sync){  
	var scr=gPic.screen;
	//this.resetShadows(scr);
	var mom=gPic.getTarget(scr,"corners");
	scr.clipRadius="0%";
	scr.setClipRadius();
	scr.cornerRadius="0%";
	scr.setCorners();
	scr.opacity1=1;
	var mom=gPic.getTarget(scr,"size");
	if(sync){
		scr.repaint();
		if(scr!=mom)mom.repaint();
		gToolbar.syncControls();
}	}



Toolbar.prototype.resetColors=function(sync){  
	var scr=gPic.screen;
	this.resetCSSFilters();
	if(scr.lockTyp!="mask"){
		this.resetMask();
	}
	this.resetBlend();
	scr.FultersOn=0;
	this.resetFulters();
	scr.OilPaintOn=0;
	this.resetOilPaint();
	scr.ColorRgbOn=0;
	this.resetColorRgb();
	scr.SwapCsOn=0;
	this.resetFillColor();
	this.resetPosterize();
	this.resetSwapColors();
	this.resetSwapPixels();
	if(sync)scr.paint();
}	


Toolbar.prototype.resetPerspective=function(sync){  
	var scr=gPic.getTarget(gPic.screen,"spin");
	scr.perspectiveFrm=gDefaultPerspective;
	scr.perspectiveImg=gDefaultPerspective;
	scr.perspectivePnl=gDefaultPerspective;
	scr.bigMirror=0;
	gPic.transform(scr.frame,"");
	gPic.transform(scr.div,"");
	if(sync){
		scr.repaint();
		if(scr!=gPic.screen)gPic.screen.repaint();
		gToolbar.syncPerspective();
}	}


Toolbar.prototype.resetRekord=function(sync){  
	var scr=gPic.screen;
	if(sync)scr.undoing=1;
	scr.history.IX=0;
	this.resetHistory(0);
	if(sync){
		this.syncHistory();
		scr.undoing=0;
}	}



Toolbar.prototype.resetScramble=function(sync){  
	var scr=gPic.getTarget(gPic.screen,"frack");
	scr.xFrack=	scr.yFrack =100;
	scr.frackL=	scr.frackT =0;
	scr.xScale=	scr.yScale =0;
	scr.multipleImages=0;
	scr.scrambleImages=0;
	scr.clipRadius="0%";
	scr.setClipRadius();
	scr.cornerRadius="0%";
	if(sync){
		scr.repaint();
		gToolbar.syncControls();
}	}

/*
Toolbar.prototype.resetOptions=function(sync){  
	var scr=gPic.screen;
	gPic.useCanvas=1;
	gPic.pauseDelay=scr.pauseDelay=10;
	if(sync){
		scr.repaint();
		gToolbar.syncViewOptions();
}	}
*/

Toolbar.prototype.resetDebug=function(){  
}



//-------- minor groups -------
Toolbar.prototype.resetZoom=function(repaint){  
	var scr=gPic.getTarget(gPic.screen,"zoom");
	gPic.applyPattern(scr);
	if(repaint){
		scr.repaint();
		gToolbar.syncControls();
}	}

Toolbar.prototype.resetSlide=function(repaint){  
	var scr=gPic.getTarget(gPic.screen,"slide");
	scr.xSlide=scr.ySlide=50;
	if(repaint){
		scr.repaint();
		gToolbar.syncControls();
}	}

//--- Note: setVars() already calls getTarget() ---
Toolbar.prototype.resetSkew=function(){  
	this.setVars('skew',0,0,0);	// always repaints
	this.syncPerspective(0);
}
Toolbar.prototype.resetPanelSkew=function(){  
	this.setVars('panelskew',0,0,0);	// always repaints
	this.syncPerspective(0);
}

Toolbar.prototype.resetTilt=function(repaint){  
this.resetPerspective(0);
if(repaint){
	gPic.screen.repaint();
	this.syncPerspective();
}}

Toolbar.prototype.resetSpin=function(repaint){  
this.resetPerspective(0);
if(repaint){
	gPic.screen.repaint();
	this.syncPerspective();
}}


Toolbar.prototype.resetMove=function(){  
	this.centerScreen();
}



//-------------------------- debug functions -----------------------------------

function test(){	
/*
var cfg=gPic.grabConfig(gPic.screen);
msg("cfg="+cfg);
msg("ix="+cfg.indexOf(";s.src="));
var c=cfg.substr(0,cfg.indexOf(";s.src=")+1);
msg(c);
*/
//msg("cfg="+cfg);
//gPic.doneStuff=cfg+";";
msg("stuff="+gPic.doneStuff);
msg("===========");
msg(gPic.doneStuff.substr(gPic.doneStuff.indexOf(";s.src=")));

msg("IX="+gPic.doneIX);
}


function test2(){
var cfg=gPic.grabConfig(gPic.screen);
msg(cfg);
}


/*
function testSwapColors(context){
//var c1=_obj("dbgxTxt1").value;
//var c2=_obj("dbgxTxt2").value;
var c1="#e28f46";
var c2="#ecd98d";
var oldRed 	 = HexToR(c1);
var oldGreen = HexToG(c1);
var oldBlue	 = HexToB(c1);
var newRed 	 = HexToR(c2);
var newGreen = HexToG(c2);
var newBlue	 = HexToB(c2);
var imageData = context.getImageData(0, 0, context.canvas.width, context.canvas.height);
var tolerance = 10;
for (var i=0;i<imageData.data.length;i+=4)
  {
	  var ixR=imageData.data[i];
	  var ixG=imageData.data[i+1];
	  var ixB=imageData.data[i+2];
      if(ixR > oldRed-tolerance 	&& ixR < oldRed+tolerance   &&
      	 ixG > oldGreen-tolerance 	&& ixG < oldGreen+tolerance &&
      	 ixB > oldBlue-tolerance 	&& ixB < oldBlue+tolerance 
	  ){
          imageData.data[i]		=newRed;
          imageData.data[i+1]	=newGreen;
          imageData.data[i+2]	=newBlue;
      }
  }
context.putImageData(imageData,0,0);
msg("DONE!");
}
*/

function test99(){
	var scr=gPic.screen;
	var box=scr.boxes["0000"];
	var img = new Image();
	img.src = box.canvas.toDataURL();
	scr.gImg=img;
	gToolbar.reset();
	setTimeout("gPic.screen.paint()",500);
}




/*
<iframe name="image_create_frame" style="z-index:999999;position:absolute;left:20px;top:20px;width:200px;height:200px;background:pink;"></iframe>
<form id="image_create_form" method="post" action="image_create.php" target="image_create_frame">
  <input id="inp_img" name="img" type="hidden" value="">
</form>
*/

function test9(v){
var parent=gPic.screen.div;
while(parent){
	if(parent.id=="picdiv")break;
	msg(parent.id+"="+parent.style.filter);
	msg("class="+parent.className);
	msg("type="+parent.nodeName);
	msg(" ");
	parent=parent.parentElement;
}}

function SpeedTest(v){
	if(v==null)v="yes";
	var start = new Date().getTime();
	for(var i=0;i<100;i++)gPic.screen.repaint();
	var end = new Date().getTime();
	var time = end - start;
	msg(v+' - time: ' + time);	
}



function listConfig(){    
	var scr=gPic.screen;
    var txt=gPic.grabConfig(scr);
	msg(txt);
}


function listScreens(){
msg("-----------------------------");
//msg("gPic.scrix="+gPic.scrix+", gPic.screen.scrix="+gPic.screen.scrix);
for(var i=0;i<gPic.screens.length;i++){
	 var scr=gPic.screens[i];
	 msg("ix="+i+", scrix="+scr.scrix+", DELETED="+scr.DELETED+", zindex="+scr.zindex);
	 /*
	 var lby=(scr.lockedByScreen)?scr.lockedByScreen.scrix:"none";
	 var cby=(scr.childByScreen)?scr.childByScreen.scrix:"none";
	 var typ=scr.typ;
	 var locktyp=scr.lockTyp;
	 msg("ix="+i+", scrix="+scr.scrix+", DELETED="+scr.DELETED+", tileType="+scr.tileType+", lockedBy="+lby+", childBy="+cby+", typ="+typ+
	     ", lockTyp="+locktyp+", lockedScreens="+scr.lockedScreens.length+", childScreens="+scr.childScreens.length
		 );
	 msg("ix="+i+", scrix="+scr.scrix+", DELETED="+scr.DELETED+", frame.id="+scr.frame.id+", lockedBy="+lby+", childBy="+cby+
	     ", lockTyp="+typ+", lockedScreens="+scr.lockedScreens.length+", childScreens="+scr.childScreens.length+
		 ", zIndex="+scr.zindex+", tileType="+scr.tileType+", Tiling="+scr.Tiling+", vx="+scr.vx+", vy="+scr.vy+
		 ", shadowsOn="+scr.shadowsOn+", xZoom="+scr.xZoom+", fullWarp="+scr.fullWarp
		 );
	 */
	 msg("-----------------------------");
}}

function lockedby(){
var scr=gPic.screen;
var lscrix=(scr.lockedByScreen)?scr.lockedByScreen.scrix:"none";
msg('gPic.scrix='+gPic.scrix+', lockedBy='+lscrix)
}

function msg0(x){ _obj("iDebugText").value=x;}



//============================= LOAD IMAGE FUNCTIONS =============================
/*
Picture.prototype.getSrcArray=function(){	//called by toolbar.php
var a=new Array();
var j=0;
for(var i=0;i<gPic.screens.length;i++){
	var scr=gPic.screens[i];
	if(!scr.DELETED && !scr.hideImage && scr.media=="image"){
		a[j]=scr.zindex+"-"+scr.scrix; 
		j++;
	}
}
a=a.sort();
a=a.reverse();
//msg("=============")
//msg("a="+a);
//msg("-------------")
var imgs=new Array();
j=0;
//for(var i=a.length-1; i>-1; i--){
for(var i=0; i<a.length; i++){
	var ix=a[i].split("-")[1]*1;
	var scr=gPic.screens[ix];
	imgs[j]=scr.src;
	j++;
}
//msg("imgs="+imgs);
//msg("-------------")
return imgs;
}
*/

Picture.prototype.openColorPicker=function(id,src){
if(!src)src=gPic.screen.gImg.src;
if(!id)id=this.gColorPickerID;
else this.gColorPickerID=id;
parent.openPopup("colorpicker","colorPick.php?id="+id+"&src="+src,"Color Picker");
}


//---------- makeDopple() -----
// This deletes the old screen and creates a new one!  (based on tileScreen)
Picture.prototype.makeDopple=function(ix,src){
var scr1=gPic.screens[ix];
if(!scr1)						return 0;
if(scr1.DELETED)				return 0;
if(scr1.media!="image")			return 0;
if(!src || src==scr1.gImg.src)	return 0;
if(scr1.doppleTimer)			return 0;
//--- add a new screen ---
var cfig=new Config(this.grabConfig(scr1));
gPic.addNewScreen(cfig);
var scr2=gPic.screen;
scr2.loadImage(src,0);
gPic.moveLocking(scr1,scr2);
gPic.setZindex(scr2,scr1.zindex-1);
scr2.history=scr1.history;
scr2.animation=scr1.animation;
scr2.history.screen=scr2;
scr2.animation.screen=scr2;
gPic.repaint(scr2.scrix);
gPic.fixEvents();
gToolbar.viewImage();   
gToolbar.syncTiling();
setOpacity(scr2.frame,100);
scr2.doppleScr	=scr1;
scr1.doppleScr	=scr2;
scr1.shadows	="";
scr1.DELETED	=1;
scr1.setShadows();
fadeOut("iScrn"+scr1.scrix,100);
gToolbar.syncControls();
scr2.doppleTimer=setTimeout(" gPic.doneDopple("+scr1.scrix+","+scr2.scrix+");", 2500);
return 1;
}



Picture.prototype.doneDopple=function(ix1,ix2){
var scr1=gPic.screens[ix1]; 
var scr2=gPic.screens[ix2]; 
try{
	var timer=scr2.doppleTimer; 
	clearTimeout(timer); 
	scr2.doppleTimer=null;
}catch(e){}  
if(gPic.playScreen==scr1)gPic.playScreen=scr2;
scr1.doppleScr	=null;
scr2.doppleScr	=null;
gPic.deleteScreen(scr1.scrix);
}



//---------------------- gotoImg -------------------------- 
// finds a screen with the correct media
function gotoImg(src,title,dir){
var oldScrix=gPic.scrix;
if(dir==null)dir="";
var media=_fileType(src.toLowerCase());
var lockTyp=gPic.screen.lockTyp;
//---- colorPicker? ----
if(media=="image"){
	if(gPic.gOpenPopup=="colorpicker"){
	    gPic.openColorPicker(gPic.colorPickerID,src);
		return 0; // 0 prevents thumb being selected
	}
}
//------ right media ------
if(gPic.screen.media==media){
	return gPic.screen.addImg(src,title,dir,gPic.dopple);
	//if(!gPic.screen.hideImage)return gPic.screen.addImg(src,title,dir,gPic.dopple);
	//if(gPic.screen.lockedByScreen){
	//	if(gPic.screen.lockedByScreen.media==media)return gPic.screen.lockedByScreen.addImg(src,title,dir);
	//}
		
}
//------ wrong media so find a screen -------------
for(var i=0; i<gPic.screens.length;i++){
	if(gPic.screens[i].DELETED)continue;
	if(gPic.screens[i].media==media && !gPic.screens[i].hideImage){
		gToolbar.changeScreen(i);
		return gPic.screens[i].addImg(src,title,dir);
}	}
//------ no suitable screen found so add a new one -------
gPic.addScreen(null,null,media,dir);
gToolbar.setPlayPauseBtn(gPic.playing);
return gPic.screen.addImg(src,title,dir);
}



//----------- Add a new image to the current screen --------------  msg("2
Screen.prototype.addImg=function(src,title,dir,dopple){
//--- This is using scr.images but I'm just going to leave it as is for now.
//--- Called by addLayer() API and by gotoimg() - not sure what calls them!
var scr=this,found=0;
var img;
//msg("addImg locktyp="+this.lockTyp);
//--- FILTER ---
/*
if(this.lockTyp=="filter"){
	if(src && !_in(src,"../filters")){
		scr=gPic.getLockParent(scr);
	}else{
		this.loadFilterImage(src);
		return 1;
	}
}
*/
//--- first check if it has already been loaded ----
for(var i=0;i<gPic.imgobjects.length;i++){
	 var longsrc=_rep(src,"../",gRoot);
	 if(longsrc==gPic.imgobjects[i].src){
	 	img=gPic.imgobjects[i];
		break;
	 }
}
scr.imgix=(img)?i:0; 
//--- img object already loaded ----
if(img){
	scr.gImg=img;
	if(dopple){
		if(gPic.makeDopple(this.scrix,src))return 1;
	}
	scr.naturalSize(1);
	scr.setVignetteColor();
  	scr.setDominantColor();
	scr.repaint();
	gPic.toolbar.syncControls();
	return 1;
}
//--- new image so load it ---
scr.loadImage(src,dopple);
gToolbar.viewImage();
return 1;
}



//--------------------------- loadImage ---------------------------------------- 
Screen.prototype.loadImage=function(src,dopple){
//msg("loadImage");
if(dopple==null)dopple=0;
if(dopple){
	if(gPic.makeDopple(this.scrix,src))return;
}
//--- FILTER ---
if(this.lockTyp=="filter"){
	this.history.recordAction("s.imgix="+this.imgix+"; s.loadImage('"+src+"',"+dopple+");");
	this.loadFilterImage();
	return;
}
//--------------
if(this.imgix > gPic.images.length-1)this.imgix=0;	
if(src==null)src=gPic.images[this.imgix];
if(this.oldsrc==null)this.oldsrc=src;
this.history.recordAction("s.loadImage('"+src+"',"+dopple+");");
this.src=src;
this.media=_fileType(src.toLowerCase());
if(this.media=="folder")this.media="image";
src=_fileUrl(src);
try{ parent.frames[0].selectCurrent(src); }catch(e){}  
var lc=src.toLowerCase();
var w,h;
w=this.divW;
h=this.divH;
//------- VIDEO --------
if(this.media=="video"){
	 var i;
	 src=_rep(src,"http://www.youtube.com/embed/","");
	 src=_rep(src,"http://www.youtu.be/embed/","");
	 src=_rep(src,"http://www.youtube.com/watch?v=","");
	 i=_ix(src,"&v="); if(i!=-1)src=src.substring(i+3);
	 i=_ix(src,"?v="); if(i!=-1)src=src.substring(i+3);
	 i=_ix(src,"?");   if(i!=-1)src=src.substring(0,i);
	 i=_ix(src,"&");   if(i!=-1)src=src.substring(0,i);
	 src=src.replace("@vid@","");
	 try{ parent.frames[0].selectCurrent("@vid@"+src); }catch(e){}  
}
this.src=src;
//------ OTHER MEDIA -------
if(this.media!="image"){
 	if(!this.gImg)this.gImg=new Image();
 	switch(this.media){
  		case "video" : this.gImg.src="_pvt_images/videos.png";   break;
  		case "file"  : this.gImg.src="_pvt_images/text.png"; 	 break;
  		default      : this.gImg.src="_pvt_images/new_link.png"; break;
 	}
 	this.repaint();
	gToolbar.syncControls();
 	return 1;
}
//----- IMAGES -------
var img;
for(var i=0;i<this.picture.imgobjects.length;i++){
	 var longsrc=_rep(src,"../",gRoot);
	 if(longsrc==this.picture.imgobjects[i].src){
	 	img=this.picture.imgobjects[i];
		break;
	 }
}
//---- IMAGE FOUND ----
if(img){
	 this.gImg=img;
	 gPic.addEvents(this.gImg,src,this);
	 return;
}
//---- NEW IMAGE -----
img=this.picture.imgobjects[this.picture.imgobjects.length]=new Image();  
this.gImg=img;
gPic.addEvents(this.gImg,src,this);
return 1;
}



//------- add images event listener -------------
//we do this so we can set naturalsize and/or vignettecolor
Picture.prototype.addEvents=function(img,src,scr){
 if(img.addEventListener){
  	img.addEventListener("load",scr.imgload,false);
 }else{
  	if(img.attachEvent)img.attachEvent("onload",scr.imgload);
  	else{if(img.onLoad)img.onload=scr.imgload;}
 }
 img.src=src;
 }



//------------ reloadFile ---------------- 
Screen.prototype.reloadFile=function(){
	var frame;
	try{ frame=_obj("iFrame"+this.scrix); }catch(e){}
	if(!frame)return;
	frame.contentWindow.location.reload();
}


//------- play next image on timer ---------
Picture.prototype.playNext=function(){
var scr=gPic.playScreen;
if(!scr)return;
//msg("playNext DEL="+scr.DELETED);
if(scr.DELETED){	//deleted therefore there's a dopple?
	if(scr.doppleScr){
		var scr2		=scr.doppleScr;
		scr.doppleScr	=null;
		scr2.doppleScr	=null;
		gPic.playScreen	=scr2;
		scr=scr2;
		msg("deleted: dopple found");
	}else{	
		gPic.stopPlaying();
		msg("deleted: NO dopple found");
		return;
	}
}
//msg("playNext="+scr.scrix)
if(gPic.playing==0){
	gPic.stopPlaying();
	return;
}
scr.nextImage(1,null);
gPic.playTimer=setTimeout("gPic.playNext()",(gPic.pauseDelay*1000));
}


//----------------------- Play Pause Functions -----------------
//NOTE: Currently you can only have one slideshow running at a time
Picture.prototype.playPause=function(){
var scr=gPic.playScreen;
if(!scr)scr=gPic.screen;
if(scr.typ=="video"){
	 alert("video");
	 return;
}
var playing=gPic.playing=(gPic.playing)?0:1;
if(!playing){
	gPic.stopPlaying();
	return;
}
gPic.playScreen=scr;
gPic.playing=1;
gPic.playNext();
gToolbar.setPlayPauseBtn(1);
}


Picture.prototype.stopPlaying=function(){
try{clearTimeout(gPic.playTimer); gPic.playTimer=null; }catch(e){}
gToolbar.setPlayPauseBtn(0);
gPic.playScreen=null;
gPic.playing=0;
}


Toolbar.prototype.setPlayPauseBtn=function(playing){
//try{_obj("iPlayPauseBtn").value=(playing)?"||":">>";}catch(e){}
parent._obj("iPlayPause").src="_pvt_images/"+((playing)?"pause.png":"forward.png"); 
}


//--------------------------- nextImage ----------------------------------------
//---- USING THE CURRENT FOLDERS LIST ----	// does it?
Screen.prototype.nextImage=function(i,n,manual){
//if(this.rekord.recording)this.rekord.recordAction("scr.nextImage("+i+","+n+");;",1);       
//msg("i="+i+", imgix="+this.imgix+", images.length="+this.images.length+", n="+n+", locktyp="+this.lockTyp);  
var ix=0;
var pic="";
if(this.media=="video"){  //videos are always played by embedding play.php
	 if(i>0)this.videoFrame.gotoNext();
	 else this.videoFrame.gotoPrior();
	 return;
}
if(this.media!="image")return;  //then not applicable
//--- FILTER ----
if(this.lockTyp=="filter"){
	this.imgix=this.imgix+i;
	this.loadFilterImage();
	return;
}
//--- first try and get from current folder/parent (if going NEXT) ---
if(i>0){    // GET FROM CURRENT DIRECTORY?
	 try{
	  	pic=parent.getNextPic(i,this.MaskOn,gPic.images[this.imgix]);
	 }catch(e){pic="";}
	 if(pic==gPic.images[this.imgix])pic="";
	 if(pic){
	  	ix=this.imgix+1;
	  	gPic.images[ix]=pic;
}	 }
var images=gPic.images;
//--- find the next regular image ----
if(i<0 || pic==""){
	 var ix=this.imgix+i;
	 if(n)ix=n;
	 else{
	  	if(ix==images.length)ix=0;
	  	if(ix<0)ix=images.length-1;
}	 }
this.imgix=ix;
var dopple=(manual && gPic.playing)?0:gPic.dopple;
if(dopple && gPic.playing){
	ix++;
	if(ix>images.length-1)ix=0;
}
this.loadImage(images[ix],dopple);
}




//--------- loadFilterImage ---------
Screen.prototype.loadFilterImage=function(src){
/*
if(src){
	for(var i=0;i<parent.gFilters.length;i++){
		if(src==parent.gFilters[i]){
			this.imgix=i;
			break;
		}
	}
	if(i==parent.gFilters.length){
		this.imgix=parent.gFilters.length;
		parent.gFilters[parent.gFilters.length]=src;
	}
}
if(this.imgix>parent.gFilters.length-1)this.imgix=0;
if(this.imgix<0)this.imgix=parent.gFilters.length-1;
if(parent.filterObjects[this.imgix])this.gImg=parent.filterObjects[this.imgix];
else{
	parent.filterObjects[this.imgix]=new Image();
	parent.filterObjects[this.imgix].src=parent.gFilters[this.imgix];
	this.gImg=parent.filterObjects[this.imgix];
}
gPic.addEvents(this.gImg,this.gImg.src,this);
scr.repaint();
gToolbar.viewImage();
*/
}

</script>


<!-- ====================================== END _PIXITOOLBAR.JS =================================================== -->




</BODY></HTML>


