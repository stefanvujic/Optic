<?
include_once "_inc_global.php";


$action=rqst("action");
switch($action){
 case "loadrekord":
  loadRekord();
  break;
 case "getconfig":
  getConfig();
  break;
 //case "loadnewpicture":
 // loadNewPicture();
 // break;
 case "movedir":
  moveDir();
  break;
 case "deletedir":
  deleteDir();
  break;
 case "deletevideo":
  deleteVideo();
  break;
 case "renamedir":
  renameDir();
  break;
 case "addvideo":
  addVideo();
  break;
 case "copy2":
  copy2();
  break;
 case "copy2all":
  copy2all();
  break;
 case "clearclip":
  clearClip();
  break;
 case "copyalltoclip":
  copyAllToClip();
  break;
 case "deleteclipvideo":
  deleteClipVideo();
  break;
 case "deletefile":
  deletefile();
  break;
 case "deletelink":
  deleteLink();
  break;
 //case "deletefimg":
 // deleteFimg();
 // break;
 case "deletefile2":
  deletefile2();
  break;
 //case "loadtemplate":
 // loadTemplate();
 // break;
 case "publish":
  publish();
  break;
 case "deletevideos":
  deleteVideos();
  break;
 case "deleteimages":
  deleteImages();
  break;
//--- the following are called by youtube.php ----
 case "addallvideos":
  addAllVideos();
  break;
 case "copyallvideos":
  copyAllVideos();
  break;
 case "newplaylist":
  newPlayList();
  break;
 case "removevideo":
  removeVideo();
  break;
}
exit(0);



function getConfig(){
$fil=rqst("fil");
$txt=myReadFile($fil);
wrt('   try{applyConfig("'.$txt.'");    }catch(e){}  ');
}

function loadRekord(){
$fil=rqst("fil");
$txt=myReadFile($fil);
wrt('   try{loadRekord("'.$fil.'","'.$txt.'");    }catch(e){}  ');
}

/*
function loadNewPicture(){
global $dirlink;
$fil=rqst("fil");
$txt=myReadFile($dirlink.$fil);
wrt('   try{loadNewPicture("'.$fil.'","'.$txt.'");    }catch(e){}  ');
}
*/

function deleteLink(){
$lnkid=rqst("link");
myDeleteLink($lnkid);
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{parent.showMsg('Link deleted');}catch(e){}");
}

/*
function deleteFimg(){
$lnkid=rqst("link");
myDeleteFimg($lnkid);
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{parent.showMsg('Image link deleted');}catch(e){}");
}
*/

function clearClip(){
//called from _index.php
$_SESSION['copy2']="";
wrt("refreshClip();");
wrt("showMsg('Clipboard cleared');");
}



function removeVideo(){
$txt=rqst("vidtxt");
$vids=$_SESSION["lastyoutubelist"];
$vids=_rep($vids,$txt,"");
$vids=_rep($vids,",,","");
$_SESSION["lastyoutubelist"]=$vids;
}


function addAllVideos(){
global $dirlink,$videofile;
$vids=$_SESSION["lastyoutubelist"];
if(!$vids)exit(0);
$txt=myReadFile($dirlink.$videofile);
if($txt){
 $txt=_rep($txt,"'","");
 if(_in($vids,$txt))exit(0);
}else{
 $txt=",";
}
//$vids.=",".$txt;
$a=_split($vids,",");
for($i=0;$i<count($a);$i++){
 if($a[$i]!=""){
  $b=_split($a[$i],";");
  if(!_in($txt,$b[0]))$txt=",".$a[$i].$txt;
}}
myWriteFile($dirlink.$videofile,$txt);
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{parent.showMsg('Videos added');}catch(e){}");
}


function copyAllVideos(){
$vids=$_SESSION["lastyoutubelist"];
if(!$vids)exit(0);
$tmp=$_SESSION['copy2'].",".$vids;
$tmp=_rep($tmp,",,",",");
$_SESSION['copy2']=$tmp;
wrt("try{parent.copy2('".$vids."');}catch(e){}");
if($_SESSION["copy2msg"]==""){
 wrt("alert('The videos have been copied to your temporary playlist. Click the Clipboard icon to manage.');");
 $_SESSION["copy2msg"]="done";
}else wrt("try{parent.showMsg('Videos copied');}catch(e){}");
wrt("try{parent.refreshClip();}catch(e){}");
}



function newPlayList(){
global $dirlink,$videofile;
$vids=$_SESSION["lastyoutubelist"];
$name=rqst("name");
if(!$name || !$vids)exit(0);
myMkdir($dirlink.$name);
myWriteFile($dirlink.$name."/".$videofile,$vids);
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{parent.showMsg('Playlist added');}catch(e){}");
}


function deleteVideos(){
global $dirlink,$videofile;
myDeleteFile($dirlink.$videofile);
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{parent.showMsg('Videos deleted');}catch(e){}");
}


function deleteImages(){
global $dirlink;
//myDeleteFile($dirlink.$imagefile);
wrt("alert('Delete all images not done yet');");
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{parent.showMsg('Images deleted');}catch(e){}");
}



function publish(){
//asyncP("action=publish&pubdir="+f.gDir+"&template=&type="+typ,"publish_"+n);
global $domain,$dirlink,$gLoginPath;
if(!$gLoginPath){
 wrt("No login path found');");
 return;
}
$u=rqst("u");
$name=rqst("name");
//$subdir=($dirlink)? "."._rep($dirlink,"/",".") : "." ;
//myWriteFile("_pvt/subdomains/".$name.$subdir."txt",$u);
//wrt("alert('Success! http://".$name.$subdir.$domain." has been published');");
$subdom=_rep($gLoginPath,"../","");
wrt("alert('?subdom=".$subdom."');");
if($subdom){
 $a=_split($subdom,"/");
 //for($i=0;...
 $subdom=_rep($gLoginPath,"/","");  //tmp hack!  save 'pubs' as 'links'
}
if(!$subdom)$subdom=".";
wrt("alert('?subdom=".$subdom."');");
myWriteFile($gLoginPath."_pvt_pub/".$name.".txt",$u);
wrt("alert('?loginpath=".$gLoginPath.",  subdom=".$subdom.", Success! http://".$name.$subdom.$domain." has been published');");
}


function moveDir(){
//called by folders.php ($dir = dir to be moved)
global $dir;
$dest=rqst("dest");
if($dest=="r00t")$dest="";
$a=_split($dir,"/");
$newdir=$a[(count($a)-1)];
$newdir=$dest."/".$newdir;
myRename($dir,$newdir);
//wrt("alert('ReNamed ".$dir." to ".$newdir.");");
wrt("try{_obj('".$dir."').id='".$newdir."';}catch(e){alert('moveDir ERROR: ".$dir." TO ".$newdir."');}");
}


function deleteDir(){
//called by folders.php ($dir = dir to be moved)
global $dir;
myDeleteDir($dir,1);
wrt("JSTreeObj.__deleteComplete('".$dir."');");
}


function renameDir(){
//called by folders.php ($dir = dir to be moved)
global $dir;
$newname=rqst("newname");
if($newname){
 //wrt("alert('ajx - renameDir(".$dir." , ".$newname.");");
 $newname=_rep($newname," ","_");
 $root=getPath($dir);
 if($root)$root.="/";
 $oldname=getTail($dir);
 myRename($root.$oldname,$root.$newname);
 //wrt("alert('".$root.$oldname." , ".$root.$newname."');");
 wrt("JSTreeObj.__renameComplete('".$root.$oldname."','".$root.$newname."');");
}}


/*
function loadTemplate(){
//NB: called from _index.php so $dir is always blank
$t=rqst("template");
$txt=myReadFile("_pvt/templates/".$t);
if($txt==""){wrt("alert('Error: Template not found');");return;}
$_SESSION["skin"]=$t;
//wrt("alert('Template loaded.  Refreshing '+frames[0].gDir);");
echo("try{window.location.href='_index.php?dir='+frames[0].gDir+'&autoload=1';}catch(e){}");
}
*/



function addVideo(){
global $dirlink,$videofile,$dir;
$tid=rqst("tid");
$tit=rqst("tit");
if(!$tit)$tit=getYoutubeTitle(_rep($tid,"@vid@",""));
if(!_in($tid,"@vid@"))$tid="@vid@".$tid;
myAddVideo($tid,$tit);
wrt("try{parent.refresh(1);}catch(e){}");
wrt("try{refresh(1);}catch(e){}");
}

function copy2(){
$video=rqst("video");
if(!_in($video,"@vid@"))$video="@vid@".$video;
$title=rqst("title");
$tmp=$_SESSION["copy2"];
if(!_in($tmp,$video.";")){
 $v=$video.";".$title;
 //wrtb("1=".$tmp);
 if($tmp)$tmp=$v.",".$tmp;
 else $tmp=$v;
 //wrtb("2=".$tmp);
 $_SESSION["copy2"]=$tmp;
 wrt("try{parent.copy2('".$_SESSION["copy2"]."');}catch(e){}");
 if($_SESSION["copy2msg"]==""){
  wrt("alert('The video has been copied to your temporary playlist (clipboard)');");
  $_SESSION["copy2msg"]="done";
 }else wrt("try{parent.showMsg('Video copied');}catch(e){}");
 wrt("try{parent.refreshClip();}catch(e){}");
}else{
 wrt("alert('The video is already in your clipboard');");
}}


function copy2all(){
global $dirlink,$videofile;
$tmp=$_SESSION["copy2"];
$txt=myReadFile($dirlink.$videofile);
if($txt)$txt=_rep($txt,"'","");
if(!_in($tmp,$txt)){
 //if($tmp)$tmp.=",";
 //$_SESSION["copy2"]=$tmp.$txt;
 if($tmp)$tmp=$txt.",".$tmp;
 else $tmp=$txt;
 $_SESSION["copy2"]=$tmp;
 wrt("try{parent.copy2('".$_SESSION["copy2"]."');}catch(e){}");
 if($_SESSION["copy2msg"]==""){
  wrt("alert('The videos have been copied to your temporary playlist. Click the Clipboard icon to manage.');");
  $_SESSION["copy2msg"]="done";
 }else wrt("try{parent.showMsg('Videos copied');}catch(e){}");
 wrt("try{parent.refreshClip();}catch(e){}");
}else{
 wrt("alert('The video(s) are already in your clipboard');");
}}



function copyAllToClip(){
$tmp=$_SESSION["copy2"];
$tmp=$tmp.",".$_SESSION["lastautoplaylist"];
$tmp=_rep($tmp,",,",",");
$_SESSION["copy2"]=$tmp;
wrt("try{parent.copy2('".$tmp."');}catch(e){}");
wrt("alert('Done.  Click the Clipboard icon to manage the temporary playlist.');");
}


function deleteClipVideo(){
$tid=rqst("tid");
if(!_in($tid,"@vid@"))$tid="@vid@".$tid;
$tit=rqst("tit");
$ix=rqst("ix");
if(!$tid)exit();
$tmp=$_SESSION["copy2"];
$tmp=_rep($tmp,$tid.";".$tit,"");
$tmp=_rep($tmp,",,",",");
$_SESSION["copy2"]=$tmp;
wrt("try{localDeleteClipVideo(".$ix.",'".$tid."');}catch(e){}");
wrt("try{parent.copy2('".$_SESSION["copy2"]."');}catch(e){}");
}


function deleteVideo(){
$video=rqst("video");
myDeleteVideo($video);
wrt("parent.refresh(1);");
}


function deleteFile(){
$file=rqst("file");
myDeleteFile($file);
//wrt("parent.refresh(1);");
}


function deleteFile2(){
//------------------  called from PLAY.PHP ----------------------
$file=rqst("file");
$id=rqst("id");
$ix=rqst("ix");
$typ=rqst("typ");
$pdir=rqst("pdir");
//wrt("alert('".$pdir." , ".$file."');");
switch($typ){
 case "vid":
  myDeleteVideoWithDir($file,$pdir);
  break;
 case "fimg": 
  myDeleteFimg($file);
  break;
 case "link": 
  myDeleteLink($file);
  break;
 default:
  myDeleteFile($file);
  break;
}
wrt("localDeleteItem('".$id."','".$ix."');");
wrt("parent.refresh(1);");
}



?>

