<?
require_once 'youtube/Google/autoload.php';
require_once 'youtube/Google/Client.php';
require_once 'youtube/Google/Service/YouTube.php';
include_once "_inc_global.php";
include_once "_inc_header.php";

$cnt=0;
$query=rqst("q");
$action=trim(strToLower(rqst("action")));
$category=rqst("category");

?>
<STYLE>
img{padding-left:3px;}
</STYLE>

<script src="_pvt_js/async.js"></script>

<SCRIPT>

//------ NOT USED ANYMORE ----
//var currentDir='<?=$dir;?>';
function chgDir(dir){
alert("chgDir() not used??? in youtube.php");
var dirlink=(dir)?dir+"/":"";
_obj("iDirTxt").innerHTML="/"+dirlink;
_obj("dir").value=dir;
currentDir=dir;
}
//----------------------------


function currentDir(){ return parent.gDir; }


function playAll(){
var u="_play.php?dir="+gDir+"&type=videos&source=youtube&mode=popup";
window.open(u);
//parent.openWindow(2,u);
}


//function playVideo(tid){
//parent.frames[0].loadPage("_display.php?url=@vid@"+tid);
//}

function playVideo(tid){
if(tid==null)tid="";
if(tid && !_in(tid,"@vid@"))tid="@vid@"+tid;
var u="_play.php?dir="+gDir+"&type=videos&source=youtube&src="+tid+"&mode=popup";
//alert("u="+u);
window.open(u);
//parent.openWindow(2,u);
}


function removeVideo(vid,tit){
_obj("vid_"+vid).style.display="none";
//_obj("vid_"+vid+"_hr").style.display="none";
asyncP("action=removevideo&vidtxt=@vid@"+vid+";"+tit,"remvid");
}


function removeAll(){
_obj("q").value="";
document.f1.submit();
}



function addAll(){
asyncP("action=addallvideos&dir="+currentDir(),"addall");
}


function copyAll(){
asyncP("action=copyallvideos","copyall");
}


function newPlaylist(){
var pdir=_obj("pname").value;
if(!pdir){alert("Please enter a playlist name");return;}
asyncP("action=newplaylist&dir="+currentDir()+"&name="+pdir,"newplaylist");
//window.open("ajx.php?action=newplaylist&dir="+currentDir()+"&name="+pdir);
}


function copyVideo(tid,tit){
asyncP("action=copy2&video="+tid+"&title="+tit,tid);
try{parent.refreshClip();}catch(e){}
}


function addVideo(tid,tit){
asyncP("action=addvideo&tid="+tid+"&tit="+tit+"&dir="+currentDir(),tid);
//window.open("ajx.php?action=addvideo&tid="+tid+"&tit="+tit+"&dir="+currentDir());
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
try{}catch(e){}}


</SCRIPT>
<body onload=load() topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

<!-- <IMG id=iWindowClose onclick='parent.closeUtil()' src='_pvt_images/delete.png' style=' z-index:9991; display:block;position:absolute;right:0px;top:2px;width:30px;cursor:pointer;'> -->

<?
$allbtndisplay=($query!="" && $action=="search")?"block":"none";
srchBox($query,$category,$allbtndisplay);
if($query!="" && $action=="search"){
 $txt=getYouTubeVids($query);
 $html=format($txt);
 wrt("<script> try{ parent.getTopToolbarObj().resizeYoutubeWindow(); }catch(e){} </script>");
 wrt($html);
}
wrt("</body></html>");
exit(0);


//---------------------------------- srchBox() ------------------------------------
function srchBox($q,$category,$allbtndisplay){
global $roundedcorners,$bgcolor,$dir,$dirlink,$gLoggedIn,$shownewvid,$movers;
?>
<center>
<div style='height:5px;'></div>
<DIV style="width:90%;padding:1px;font-family:arial,sans-serif;font-size:12pt;background:white;<?=$roundedcorners;?>;border:solid 0px black;">
<TABLE style='width:100%;'>
<FORM name=f1 id=f1 method=post>
<input type=hidden id=dir name=dir value='<?=$dir;?>'>
<tr>
<td width=86 align=right><img src=_pvt_images/youtube.jpg></td>
<td width=290><input type=text name=q id=q value="<?=$q;?>" style='width:400px;height:30px;font-size:22px;<?=$roundedcorners;?>;'></td>
<td width=80><input class='btn' type=submit name=action value="   Search   " style='height:28px;'></td>
<td align=right><IMG id=iClipBtn src='_pvt_images/clip2.png' onclick='parent.openPopup("clip","clip.php")' title='video clipboard' style='cursor:pointer;width:36px;'></td>
</tr>
</FORM>
</TABLE>
</DIV>

<DIV id=iAllBtns style="width:100%;display:<?=$allbtndisplay;?>;padding-bottom:10px;font-family:arial,sans-serif;font-size:12pt;">
<br>
<TABLE width=100%>
<tr>
<td valign=top align=left>
&nbsp;&nbsp;&nbsp;&nbsp;
<input class="btn updbtn" type=button value="Save All As:" style='width:120;' onclick="newPlaylist()" <?=$movers;?> title='create a new playlist in the current folder'>
&nbsp;&nbsp;<span id=iDirTxt class=txthdr>/<?=$dirlink;?></span>
<input type=text name=pname id=pname value="<?=$q;?>" style='width:250;'>
</td>
</tr><tr>
<td align=right>
<img src='_pvt_images/clipboard_add.png' onclick='copyAll()' title='copy all to clipboard' style='width:34px;cursor:pointer; position:relative;top:6px;'>
<? if($shownewvid){ ?>
&nbsp;<img src='_pvt_images/addgreen.png' onclick='addAll()' title='add all to current folder' style='width:22px;cursor:pointer; position:relative;top:-2px;'>
<? } ?>
&nbsp;<img src='_pvt_images/watchplay.png' onclick='playVideo(null,1)' title='play all' style='width:26px;cursor:pointer;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</TABLE>
<br>&nbsp;
</DIV>
<?
}


function wrtOption($v,$def){
$sel=($v==$def)?"selected":"";
wrt("<option value='".$v."' ".$sel.">".$v."</option>");
}


//------------ get youtube vids ---------------
function getYouTubeVids($find){
$txt=";;";
if(!$find)return $txt;
$DEVELOPER_KEY = 'AIzaSyDDb6Gebr9KOsRn1ZYuUfq5cFTiLf5tiVo';
$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);
$youtube = new Google_Service_YouTube($client);
try {
 $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $find,
      'maxResults' => 30,
      'type' => 'video',
      'safeSearch' => 'none',
  ));
 $videos = '';
 foreach ($searchResponse['items'] as $searchResult) {
  switch ($searchResult['id']['kind']) {
   case 'youtube#video':
     $tit=$searchResult['snippet']['title'];
     $tit=_rep($tit,"'","");
     $tit=_rep($tit,";",":");
     $vid=$searchResult['id']['videoId'];
     $txt.="".$vid.";".$tit.";;";
     break;
  }
 }
}catch (Google_Service_Exception $e) {
  exit('<p>A service error occurred: <code>%s</code></p>'.htmlspecialchars($e->getMessage()));
}catch (Google_Exception $e) {
  exit('<p>An client error occurred: <code>%s</code></p>'.htmlspecialchars($e->getMessage()));
}
return $txt;
}


//---------------------------------- format() ------------------------------------
function format($txt){
global $cnt,$hicolor,$fgcolor,$gLoggedIn,$shownewvid;
$rawtxt=",";
$v="";
$a=_split($txt,";;");
for($i=1;$i<count($a);$i++){
 if($a[$i]){
  $b=_split($a[$i],";");
  $tid=$b[0];
  $tit=_rep($b[1],'"','');
  $txt="<table id='vid_".$tid."' cellpadding=0 cellspacing=0 style='width:540px; border-top:solid 1px #999;'>".
  "<tr><td colspan=2 align=center valign=top width=250><img src='http://i.ytimg.com/vi/".$tid."/0.jpg'  onclick='playVideo(\"".$tid."\",1)'  style='width:240px;cursor:pointer;'>".
  "</td><td>".
   "<TABLE border=0 width=290 height=190 cellpadding=3 cellspacing=0>".
   "<tr><td colspan=2 align=center valign=top style='color:".$hicolor."'>".$tit.
   "<div style='height:4px;'></div>".
   "</td></tr>".
   "<tr style='height:30px;'><td colspan=2 align=right>".
   "<img src='_pvt_images/clipboard_add.png' onclick='copyVideo(\"".$tid."\",\"".$tit."\")' title='copy to clipboard' style='width:34px;cursor:pointer; position:relative;top:6px;'>";
  if($shownewvid){
   $txt=$txt."&nbsp;<img src='_pvt_images/addgreen.png' onclick='addVideo(\"".$tid."\",\"".$tit."\")' title='add video to current folder' style='width:22px;cursor:pointer; position:relative;top:-2px;'>";
  }
  $txt=$txt."&nbsp;&nbsp;<img src='_pvt_images/delete.png' onclick='removeVideo(\"".$tid."\",\"".$tit."\")' title='remove this video from the list' style='width:26px;cursor:pointer;'>".
   "</td></tr>".
   "</TABLE>".
  "</td>";
  $v=$v.$txt;
  $rawtxt.="@vid@".$tid.";".$tit.",";
 }
}
$_SESSION["lastyoutubelist"]=$rawtxt;
$cnt=$i;
return $v;
}



//-------------------------------- decode() ----------------------------------
function decode($x){
$tmp=replace($x,"<","<br>&lt;");
//tmp=replace(tmp,"<br>&lt;/","&lt;/")
return $tmp;
}


/*
//---------------------------------- srch() ------------------------------------
function srch($q,$category,$start){
$url="http://gdata.youtube.com/feeds/videos?vq=".$q; 
if($category!="")$url.="&category=".$category;
$url.="&start-index=1&racy=include&safesearch=none&format=5&max-results=30";
//wrt("<br><big><b>URL: ".$url."</b></big><br><br>");
$txt=getUrl($url);
return $txt;
}

//---------------------------------- format() ------------------------------------
function format($txt){
global $cnt,$hicolor,$fgcolor,$gLoggedIn,$shownewvid;
$rawtxt=",";
$a=_split($txt,"<content type='html'>");
for($i=1;$i<count($a);$i++){
 $b=_split($a[$i],"</content");
 $tmp=$b[0];
 $tmp=_rep($tmp,"&lt;","<");
 $tmp=_rep($tmp,"&gt;",">");
 getYoutubeDetails($tmp,&$tid,&$tit,&$tim,&$desc,&$views);

 $desc=_left($desc,350);
 if(_len($desc)==350)$desc.="...";

 $txt="<table id='vid_".$tid."' cellpadding=0 cellspacing=0 style='width:540px; border-top:solid 1px #999;'>".
  "<tr><td colspan=2 align=center valign=top width=250><img src='http://i.ytimg.com/vi/".$tid."/0.jpg'  onclick='playVideo(\"".$tid."\",1)'  style='width:240px;cursor:pointer;'>".
  "</td><td>".
   "<TABLE border=0 width=290 height=190 cellpadding=3 cellspacing=0>".
   "<tr><td colspan=2 align=center valign=top style='color:".$hicolor."'>".$tit.
   "<div style='height:4px;'></div>".
   "</td></tr>".
   "<tr><td colspan=2 valign=top style='text-align:justify;font-size:12px;'>".$desc."</td></tr>".
   "<tr style='height:30px;'><td align=left>".
   "<span style='font-size:12px;color:".$fgcolor.";'>Views:".$views."&nbsp;&nbsp;&nbsp;[".$tim."]</span>".
   "</td><td valign=bottom align=right>".
   "<img src='_pvt_images/clipboard_add.png' onclick='copyVideo(\"".$tid."\",\"".$tit."\")' title='copy to clipboard' style='width:34px;cursor:pointer; position:relative;top:6px;'>";
  if($shownewvid){
   $txt=$txt."&nbsp;<img src='_pvt_images/addgreen.png' onclick='addVideo(\"".$tid."\",\"".$tit."\")' title='add video to current folder' style='width:22px;cursor:pointer; position:relative;top:-2px;'>";
  }
  //$txt=$txt."&nbsp;&nbsp;<img src='_pvt_images/watchplay.png' onclick='playVideo(\"".$tid."\")' title='play video' style='width:26px;cursor:pointer;'>".
  $txt=$txt."&nbsp;&nbsp;<img src='_pvt_images/delete.png' onclick='removeVideo(\"".$tid."\",\"".$tit."\")' title='remove this video from the list' style='width:26px;cursor:pointer;'>".
   "</td></tr>".
   "</TABLE>".
  "</td>";
  //"<tr><td colspan=2>&nbsp;</td></tr></table>";

 $v=$v.$txt;
 $rawtxt.="@vid@".$tid.";".$tit.",";

}
$_SESSION["lastyoutubelist"]=$rawtxt;
//$v="<table width=100% cellpadding=0 cellspacing=0 border=0>".$v."</table>";
$cnt=$i;
return $v;
}
*/



?>

