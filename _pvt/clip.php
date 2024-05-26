<?
include_once "_inc_global.php";
include_once "_inc_header.php";

?>
<STYLE>
img{padding-left:3px;}
</STYLE>
<script src="_pvt_js/async.js"></script>

<SCRIPT>

function formSubmit(action){
window.location.href="clip.php?action="+action+"&dir="+parent.gDir;
}

function playAll(){
var u="_play.php?dir="+gDir+"&type=videos&source=clip";
window.open(u);
//parent.openWindow(2,u);
}

function playVideo(tid,popup){
if(tid==null)tid="";
if(tid && !_in(tid,"@vid@"))tid="@vid@"+tid;
var u="_play.php?dir="+gDir+"&type=videos&source=clip&src="+tid;
window.open(u);
//parent.openWindow(2,u);
}

function addVideo(tid,tit){
asyncP("action=addvideo&tid="+tid+"&tit="+tit+"&dir="+parent.gDir,tid);
}

function deleteVideo(tid,tit){
asyncP("action=deleteclipvideo&tid="+tid+"&tit="+tit+"&ix=0",tid);
}

function localDeleteClipVideo(ix,tid){ //called by asyncEval
tid=tid.replace("@vid@","");
try{_obj('i1_'+tid).style.display=_obj('i2_'+tid).style.display='none';}catch(e){}
}


function asyncP(parms,action){
//alert("parms="+parms+", action="+action);
asyncPOST(parms,"ajx.php",asyncEval,action);
//window.open("ajx.php?"+parms);
}

function asyncEval(req){
//alert("req="+req.responseText);
try{_obj("iBusy").style.display="none";}catch(e){}
try{eval(req.responseText);}catch(e){alert("Sorry! Remote call failed");}
}


function load(){
//_obj('pname').focus();
}



</SCRIPT>
<body onload=load() topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 style='zoom:1.5;'>

<!-- <IMG src='_pvt_images/search_videos.png' onclick='parent.loadx("youtube")' title='search for more videos' style='z-index:9999;position:absolute;top:8px;right:10px;width:34px;cursor:pointer;'> -->

<?

//wrtb("<br>dir=".rqst("dir").", action=".rqst("action"));
//exit();


$plist=$_SESSION['copy2'];

$pname=rqst("pname");
if($pname=="null")$pname="";
$tmp=(!$pname)?$videofile:$pname;
if(!$pname)$pname=$dirlink;
srchBox($pname,$plist);

$action=rqst("action");
if($action && $plist){
 $plist=",".$plist.",";
 $plist=_rep($plist,",,",",");
 //wrtb("<br><br><br><br>action=".$action);
 if(_in($action,"Replace")){
  myDeleteFile($dirlink.$videofile);
  myWriteFile($dirlink.$videofile,$plist);
 }
 if(_in($action,"Add")){
  $tmp=myReadFile($dirlink.$videofile);
  $plist=$plist.$tmp;
  //exit("<br><br><br><br>plist=".$plist.", tmp=".$tmp.", dirlink=".$dirlink);
  myWriteFile($dirlink.$videofile,$plist);
 }
 if(_in($action,"Clear")){
  wrt("<script>parent.copy2('');</script>");
  $_SESSION['copy2']="";
  $plist="";
 }
 if(_in($action,"Save") && $pname){
  if($pname==$dirlink){
   alert("Please enter a playlist name");
  }else{
   //exit("<br><br><br><br>pname=".$pname.", plist=".$plist);
   myMkdir($pname);
   myWriteFile($pname."/".$videofile,$plist);
 }}
 refreshMenu();
}
wrt("<br><br><br>");
$html=format($_SESSION['copy2']);
wrt($html);
wrt("</body></html>");
exit(0);


//---------------------------------- srch() ------------------------------------
function srchBox($pname,$plist){
global $roundedcorners,$bgcolor,$dir,$dirlink,$movers,$gLoggedIn,$shownewvid;
$displaybox=($plist)?"block":"none";
if($plist){
?>
<div style="position:relative; top:25px; border:solid 0px black;padding:1px;font-family:arial,sans-serif;font-size:12pt;;background:<?=$bgcolor;?>;">
<TABLE width=520 border=0 cellpadding=0 cellspacing=0>
<FORM name=theform method=post>
<tr>
<td valign=top align=right width=110>
<INPUT class="btn updbtn" type=submit name=action value="Save All As" style='width:100;position:relative;top:0px;' <?=$movers;?>  title='create a new playlist in the current folder'>
</td>
<td valign=top>
<span style="font-color:black;position:relative;left:4px;top:0px;font-size:20px;">&nbsp;&nbsp;/</span>
<INPUT type=text name=pname id=pname value="<?=$pname;?>" style='width:200px;position:relative; top:-2px;'>
<INPUT type=hidden name=dir   id=dir   value="<?=$dir;?>">
</td>
<td colspan=2 valign=top align=right>
&nbsp;<img src='_pvt_images/watchplay.png' onclick='playAll()' title='play all' style='width:26px;cursor:pointer;'>
<? if($shownewvid){ ?>
<img src='_pvt_images/plus.png' onclick='formSubmit("Add All")' title='add all to current folder' style='width:22px;cursor:pointer; position:relative;top:-2px;'>
<? } ?>
<img src='_pvt_images/delete.png' onclick='formSubmit("Clear")' title='clear the clipboard' style='width:26px;cursor:pointer;'>
&nbsp;&nbsp;&nbsp;
</td>
</tr>
</FORM>
</TABLE>
</div>
<? }else{ ?>
<div style="position:relative; top:25px; border:solid 0px black;padding:1px;font-family:arial,sans-serif;font-size:12pt;;background:<?=$bgcolor;?>;">
<br><center><span class=txthdr>Your clipboard is empty</span>
</div>
<?
}
}


function wrtOption($v,$def){
$sel=($v==$def)?"selected":"";
wrt("<option value='".$v."' ".$sel.">".$v."</option>");
}




function format($txt){
global $movers,$roundedcorners,$gLoggedIn,$shownewvid;
$a=_split($txt,",");
$v="";
for($i=0;$i<count($a);$i++){
 if(_in($a[$i],";")){
  $b=_split($a[$i],";");
  $tid=_rep($b[0],"@vid@","");
  $tit=$b[1];
  $v=$v.
   "<tr id='i1_".$tid."'>".
   "<td colspan=2 style='height:5px;overflow:hidden;'><div style='border-bottom:solid 1px white;height:4px;width:100%;'></div></td>".
   "<tr id='i2_".$tid."'>".
   "<td valign=top align=left width=140>".
   "<img onclick='playVideo(\"".$tid."\",1)' src='http://img.youtube.com/vi/".$tid."/default.jpg' style='cursor:pointer;'>".
   "</td>".
   "<td>".
   "<TABLE width=360 height=85 cellpadding=0 cellspacing=0>".
   "<tr><td valign=top>".$tit."</td></tr>".
   "<tr><td valign=bottom align=right>";
  if($shownewvid){
   $v=$v."<img src='_pvt_images/plus.png' onclick='addVideo(\"".$tid."\",\"".$tit."\")' title='add video to current folder' style='width:22px;cursor:pointer; position:relative;top:-2px;'>";
  }
  //$v=$v."&nbsp;&nbsp;<img src='_pvt_images/watchplay.png' onclick='playVideo(\"".$tid."\")' title='play video' style='width:26px;cursor:pointer;'>".
  $v=$v."&nbsp;<img src='_pvt_images/delete.png' onclick='deleteVideo(\"".$tid."\",\"".$tit."\")' title='delete from clipboard' style='width:26px;cursor:pointer;'>".
   "</td></tr>".
   "</TABLE>".
   "</td>".
   "</tr>";
   //"<tr><td colspan=2 height=4><div style='height:4px;'></div></td></tr>";
}}
$v="<table border=0 cellpadding=0 cellspacing=0 style='width:500px;height:86px;'>".$v."</table>";
return $v;
}




?>

