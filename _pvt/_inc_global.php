<?
/* Copyright ©, 2005, Walter Long */

//===================== global php functions, etc. =============================
//NOTE: It must not write to the page on it's own! (inside functions only)
//==============================================================================


//--------------------- php error handling ------------------------
function errorHandler($errno, $errstr, $errfile,$errline) {  
 echo "<script>alert(' [$errno] , $errstr, $errfile, $errline ');</script>";
}
//set_error_handler("errorHandler");   //***** comment out to send errors to error.log


session_start();

//======================= global vars ============================


//---- domain vars ----
$domain=$_SERVER["SERVER_NAME"];
$domainid=_rep($domain,"www.","");
$subdomain=getSubdomain($domain);
$subdomains=getSubdomains($domain);
$gRoot="http://".strtolower($domain)."/";
$gContent="Content";
$gExePath="_pvt/";
$source=""; //not used but still referenced

$gPage=$_SERVER["PHP_SELF"];
if(_in($gPage,"_pvt/"))$gPath="../".$gContent."/";
else $gPath=$gContent."/";
$gPage=_rep($gPage,"/".$gPath,"");

// buufer output so we set cookies in newutil
if(basename($_SERVER['PHP_SELF'])=="_newutil.php")ob_start();


//---- "no" login used by takepic.php ----
$login=myGet("login");
$login="no";
$gAutoLogin=0;


//------- path and dir vars ---------
$dir=rqst("dir");
if($dir==$gContent)$dir=""; 
$dir=str_replace($gContent."/","",$dir); 
$dir=str_replace("%20"," ",$dir);
if(myGet('goback')==1)$dir=getPath($dir);
$dirpath=$gPath.$dir;
$dirlink=getDir($dir); //same as dirpath?
if(!empty($dirlink))$dirlink=$dirlink."/";
$path=getDir($dir);
if(!empty($path))$path=$path."/";
$dirdisplay="/".getTail($dir);
$shortdirdisplay=getShortName($dirdisplay,16);


//------- login/account vars ----------
$gID        =mySession('id');
$gLoginPath =mySession('loginpath');

$gLoginDir="";
$gLoginDirLink="";
//exit("debug... ID:$gID, PATH:$gLoginPath, DIR:$dir");
if(!$gID){
	 $gID=myCookie('id');
	 $gLoginPath=myCookie('path');
	 $_SESSION['id']=$gID;
	 $_SESSION['loginpath']=$gLoginPath;
}

//echo("gID=".$gID);
//echo(",LoginPath=".$gLoginPath);
//$gID        ="";
//$gLoginPath ="";

if($gID && !$dir && myGet("firsttime") && $gID!=$domainid)$dir=$gID;	//if firsttime and logged in go to user's folder
$gAdmin=($gID==$domainid)?1:0;
if($gID){
	 $gLoginDirLink=_rep($gLoginPath,"../","");  //the 'active' logged in dir
	 //this code is also in index.php and toolbar.php in getgDir()
	 if($dir && (_ix($dirlink,$gLoginDirLink)==0 || _ix($gLoginDirLink,$dirlink)==0) && $dirlink!=$gLoginDirLink)$gLoginDirLink=$dirlink;
	 $gLoginDir=$gLoginDirLink;
	 $gLoginDir=substr($gLoginDir,0,strlen($gLoginDir)-1); //strip off trailing "/"
}
//--- see hasDirRights() in _inc_header ---  ("hasRights" and "gLoggedIn" currently play the same role)
$gLoggedIn  =($gID &&
              (
               //(!$dir && $gLoginPath==$gPath.$gID."/")          // we are at the root and the login is 1 level above root
               //||
               //(_rep($gLoginPath,$gPath.$dir."/","")==$gID."/") // the login is 1 level above the current dir
               //||
               (_rep($gLoginPath,$gPath.$dir."/","")=="")         // the login is the current dir
               ||
               (_in($gPath.$dir."/",$gLoginPath))                 // the login is a higher dir
              )
             )?1:0;
			 
			 
$gAcctAdmin =($gAdmin || ($gLoggedIn && _in($gPath.$dir."/" , $gLoginPath)))?1:0;
/*
wrtb(" dir=".$dir);
wrtb(" gPath=".$gPath);
wrtb(" loginDir=".$gLoginDir);
wrtb(" loginDirLink=".$gLoginDirLink);
wrtb(" loginPath=".$gLoginPath);
wrtb(" gAcctAdmin=".$gAcctAdmin);
*/

//exit("id=".$gID.", admin=".$gAdmin.", loggedin=".$_SESSION['loggedin']." , ".$gLoggedIn);
//alert("$$$VARS: Page=".$gPage.", gID=".$gID.", gLoggedIn=".$gLoggedIn.", gAdmin=".$gAdmin.", gAcctAdmin=".$gAcctAdmin.", gLoginPath=".$gLoginPath.", gDir=".$gDir);
//alert("$$$VARS: gID=".$gID.", gLoggedIn=".$gLoggedIn.", gAdmin=".$gAdmin.", gAcctAdmin=".$gAcctAdmin.
//		", gLoginDir=".$gLoginDir.", gLoginPath=".$gLoginPath.", gDir=".$gDir);
//echo("<br>gLoginDirLink=".$gLoginDirLink);
//echo("<br>gLoginPath=".$gLoginPath);


//---- other vars ----
$pixiextension=".ixi";
$rekordextension=".iki";
$fileid="_pvt__hTjID.txt";
$templatefile="_pvt_template.txt";
$fimgfile="_pvt_images.txt";
$videofile="_pvt_videos.txt";
$linkfile="_pvt_links.txt";
if(_in($gPage,"?")){$tmp=_split($gPage,"?");$gPage=$tmp[0];}
$browser=strtolower($_SERVER['HTTP_USER_AGENT']);
$gIE=$gFX=$gCHROME=0;
if(strpos($browser,'msie'))$gIE=1;
else{
 if(strpos($browser,'firefox'))$gFX=1;
 else{
  if(strpos($browser,'chrome'))$gCHROME=1;
}}
$movers="onmouseover='_hilite(this)' onmouseout='_lolite(this)'";
$menumovers="onmouseover='_menuHilite(this)' onmouseout='_menuLolite(this)'";
$lnkmovers="onmouseover='_lnkHilite(this)' onmouseout='_lnkLolite(this)'";
$roundedcorners="-moz-border-radius: 6px; -webkit-border-radius: 6px; -moz-border-radius: 6px; -webkit-border-radius: 6px;";
$noroundedcorners="-moz-border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; -webkit-border-radius: 0px;";
$roundedcorners2="-moz-border-radius: 2px; -webkit-border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px;";
$imgexts      =",.svg,.gif,.jpeg,.jpg,.bmp,.png,.SVG,.GIF,.JPEG,.JPG,.BMP,.PNG,";

$btncoloroff='#999';
$btnbgoff='#ccc';
$btnbdroff='#999';
$useFlash=0;   //html5 iframe mode fucks up when clicking "next" as of May, 2014 (some API change - effects grooze as well)

// NOTE: As of 9/15/2016 the flash player generates an error so trying "no flash" again



//------------------------ default template vars -----------------------
$vartxt=
"\$homepage='Default.ixi';".
"\$delay=6000;".
"\$ssusepixi='no';".
"\$randomize='yes';".
"\$fgcolor='#555555';".
"\$lkcolor='#0a85ad';".
"\$selcolor='#cccccc';".
"\$hicolor='#ff0000';".
"\$bgcolor='#ffffff';".
"\$toolbarbgcolor='#ffffff';".
"\$desktopbgcolor='#ffffff';".
"\$scrollbgcolor='#bbddff';".
"\$scrollfgcolor='#0ea7e9';".
"\$popupbgcolor='moccasin';".
"\$popupfgcolor='#555555';".
"\$popupwindowpadding='10';".
"\$popupbdrcolor='#aaaaaa';".
"\$popupbdrwidth='0';".
"\$titlecolor='#ff0000';".
"\$controls='yes';".
"\$chromeless='no';".
"\$fadewinbtns='no';".
//"\$useaviary='no';".
//"\$ssdropdown='no';".
"\$ssfullsize='no';".
"\$ssnaturalsize='no';".
"\$ssthumbs='no';".
"\$bordercolor='##0ee541';".
"\$thumbbdrcolor='#000000';".
"\$ssthumbbdrcolor='#000000';".
"\$ssborder='';".
"\$ssbgwidth='1';".
"\$ssbgcolor='#000000';".
"\$ssfgcolor='#ffffff';".
"\$sshicolor='#ffffff';".
"\$ssbtnbordercolor='#555555';".
"\$ssbtnbgcolor='#ffffff';".
"\$ssbtnhicolor='#dddddd';".
"\$ssbtncolor='#000000';".
"\$btnbordercolor='#666666';".
"\$btnbgcolor='#ffffff';".
"\$btnhicolor='#dddddd';".
"\$btncolor='#000000';".
"\$fade='yes';".
"\$fadetoolbar='yes';".
"\$width='70%';".
"\$height='80%';".
"\$showthumbs='yes';".
"\$showactions=1;".
"\$ssshowthumbbdrs='yes';".
"\$ssthumbsalign='right';".
"\$toolbaralign='bottom';".
"\$optonlyimages='no';".
"\$optshowsstitles='no';".
//"\$optdropdown2='no';".
"\$optmenu1='no';".
"\$optmenu2='no';".
"\$opttoolbar1='no';".
"\$opttoolbar2='no';".
"\$optshowfolders1='yes';".
"\$optshowfolders2='no';".
"\$optshowfiles1='yes';".
"\$optshowfiles2='no';".
"\$optshowpics1='yes';".
"\$optshowpics2='no';".
"\$optshowvids1='yes';".
"\$optshowvids2='no';".
"\$optshowlinks1='yes';".
"\$optshowlinks2='no';".
"\$optupload1='yes';".
"\$optupload2='yes';".
"\$optnewfolder1='yes';".
"\$optnewfolder2='yes';".
"\$optnewfile1='yes';".
"\$optnewfile2='yes';".
"\$optnewvid1='yes';".
"\$optnewvid2='yes';".
"\$optnewlink1='no';".
"\$optnewlink2='yes';".
"\$optnewfimg1='yes';".
"\$optnewfimg2='yes';".
"\$optnewsvg1='no';".
"\$optnewsvg2='yes';".
"\$optorgfolders1='yes';".
"\$optorgfolders2='yes';".
"\$optupdsettings1='yes';".
"\$optupdsettings2='yes';".
"\$ssitems='images';".
"\$mouseovermenus='yes';".
"\$fontfamily='Economica';".
"\$fontsize=24;".
"\$showcamerabtn='yes';".
"\$loginbtn='yes';".
"\$optshowpixis1='yes';".
"\$optshowpixis2='no';".
"\$optshowfilters1='yes';".
"\$optshowfilters2='no';".
"\$optshowrekords1='yes';".
"\$optshowrekords2='no';".
"\$loggedouttemplate='none';".
"\$collapsebtn='contract.gif';".
"\$expandbtn='expand.gif';".
"\$usemenubtns='yes';";
//"\$chatstart='no';".
//"\$chatroom='99';";


//---- hard coded (not in options.php) -----

$mode="";
$pixibordercolor="#cccccc";						//set to white to turn off all borders
$pixiborder="solid 1px ".$pixibordercolor.";";	//set to 0px to turn off main box border

eval($vartxt);

//----------------- load options -------------------------------
$vartxt=myReadFile($gLoginPath.$templatefile); 
eval($vartxt);


//---------------------- fix the vars ----------------------
if($ssborder=="no")$ssborder="";
if($height=="no")$height="";
if($width=="no")$width="";
if($thumbbdrcolor=="")$thumbbdrcolor=$bgcolor;
if($ssbgwidth=="")$ssbgwidth="0";
if($ssbgcolor=="")$ssbgcolor=$bgcolor;
if($ssfgcolor=="")$ssfgcolor=$fgcolor;
if($ssbtncolor=="")$ssbtncolor=$btncolor;
if($ssbtnhicolor=="")$ssbtnhicolor=$btnhicolor;
if($ssbtnbgcolor=="")$ssbtnbgcolor=$btnbgcolor;
if($ssbtnbordercolor=="")$ssbtnbordercolor=$btnbordercolor;

//----------------- hardcoded overrides --------------------------
$homepage="Default.ixi";
$ssusepixi="no";
$ssdropdown="no";
$ssitems="images";

//----------------- menu restrictions --------------------------
$showactions=1;
$showmenu=(!$gLoggedIn && $optmenu2=="yes")?0:1;
$showfilters  	=((!$gLoggedIn && $optshowfilters2=="yes") 	|| ($optshowfilters1=="no"))?0:1;
$showtoolbar	=((!$gLoggedIn && $opttoolbar2=="yes") 		|| ($opttoolbar1=="no"))?0:1;
$showfolders	=((!$gLoggedIn && $optshowfolders2=="yes") 	|| ($optshowfolders1=="no"))?0:1;
$showfiles  	=((!$gLoggedIn && $optshowfiles2=="yes") 	|| ($optshowfiles1=="no"))?0:1;
$showpixis  	=((!$gLoggedIn && $optshowpixis2=="yes") 	|| ($optshowpixis1=="no"))?0:1;
$showrekords  	=((!$gLoggedIn && $optshowrekords2=="yes") 	|| ($optshowrekords1=="no"))?0:1;
$showpics   	=((!$gLoggedIn && $optshowpics2=="yes") 	|| ($optshowpics1=="no"))?0:1;
$showvids   	=((!$gLoggedIn && $optshowvids2=="yes") 	|| ($optshowvids1=="no"))?0:1;
$showlinks  	=((!$gLoggedIn && $optshowlinks2=="yes") 	|| ($optshowlinks1=="no"))?0:1;
$showupload 	=((!$gLoggedIn && $optupload2=="yes") 		|| ($optupload1=="no"))?0:1;
$shownewfolder 	=((!$gLoggedIn && $optnewfolder2=="yes") 	|| ($optnewfolder1=="no"))?0:1;
$shownewfile   	=((!$gLoggedIn && $optnewfile2=="yes") 		|| ($optnewfile1=="no"))?0:1;
$shownewlink   	=((!$gLoggedIn && $optnewlink2=="yes") 		|| ($optnewlink1=="no"))?0:1;
$shownewfimg   	=((!$gLoggedIn && $optnewfimg2=="yes") 		|| ($optnewfimg1=="no"))?0:1;
$shownewsvg    	=((!$gLoggedIn && $optnewsvg2=="yes")  		|| ($optnewsvg1=="no"))?0:1;
$shownewvid    	=((!$gLoggedIn && $optnewvid2=="yes") 		|| ($optnewvid1=="no"))?0:1;
$showorgfolders	=((!$gLoggedIn && $optorgfolders2=="yes") 	|| ($optorgfolders1=="no"))?0:1;
$showupdsettings=(!$gLoggedIn  || (!$gAdmin && $optupdsettings1=="no"))?0:1;
$showfimgs=0;

//--- admin overrides ---
if($gAdmin){
 $showfolders=1;
 $showfiles=1;
 $showfilters=1;
 $showpixis=1;
 $showrekords=1;
 $showpics=1;
 $showvids=1;
 $showupload=1;
 $shownewfolder=1;
 $shownewfile=1;
 $shownewfimg=1;
 $shownewvid=1;
 $showorgfolders=1;
 $showupdsettings=1;
}

if( !$shownewfolder && !$shownewfile && !$shownewlink && !$shownewfimg && 
	!$shownewsvg    && !$showupload  && !$showorgfolders)
	 $showpopupmenu=0;
else $showpopupmenu=1;

$showtoolbar=1;
$usemenubtns="yes"; //temp override until we add to options.php
if(!$showmenu)$usemenubtns="no";

 $btnh="22";
 $btnw="12";

//====================== GLOBAL PHP FUNCTIONS ========================

//------ update/change a template variable ----------
function updTemplateQuotesVar($n,$v){
global $vartxt,$gPath,$templatefile;
eval("\$oldv=\$".$n.";");
$n=str_replace("$","",$n);
$n=str_replace("'","",$n);
$v=str_replace("$","",$v);
$v=str_replace("'","",$v);
$newvars=_rep($vartxt , "\$".$n."='".$oldv."';" , "\$".$n."='".$v."';");
eval($newvars);
//myWriteFile($gPath.$templatefile,$newvars);
//wrt(_rep($newvars,"$","!"));
}


//----- get the full path for an ID -----
function getLoginPath($dir,$id){
global $gRoot,$domainid,$gPath;
if(!$dir){
 if($id==$domainid)return $gPath;  // login to the root
 else return $gPath.$id."/";
}else{
 $path=$gPath.$dir."/";
 $a=_split($dir,"/");
 if(getTail($dir)==$id)return $path;   		// login to the current dir
 return $path.$id."/";                     	// login to a sub-directory
}}


//----- clean string -----------
function okString($q,$okchars){
$validchars="abcdefghijklmnopqrstuvwxyz 0123456789-_";
if(!$okchars)$okchars="";
$okchars.=$validchars;
$x=strtolower($q);
$x=trim($x);
for($i=0;$i<strlen($x);$i++){
 $c=substr($x,$i,1);
 if(!_in($okchars,$c))return 0;
}return trim($q);}



function getSubdomain($domain){
$sub="";
$a=explode(".",$domain);
if(count($a)>2){
 $sub=$a[0];
 if(strtolower($sub)=="www")$sub="";
}
return $sub;}



function getSubdomains($domain){
$subs="";
$a=explode(".",$domain);
if(count($a)>2){
 for($i=0;$i<(count($a)-2);$i++){
  $sub=$a[$i];
  if(strtolower($sub)!="www"){
   if($i>0)$subs.=".";
   $subs.=$sub;
}}}
return $subs;}


function isSvg($u){
if(isImage($u) && _in(strtolower($u),".svg"))return 1;  
return 0;
}


function myFileType($s){	//$$$$
$ext=get_ext($s);
$name=getName($s);
if($s=="."||$s==".."|| $s=="error_log"||$s=="cgi-bin"||$name=="index")return "";
if(isFilter($s))return "filter";
if(isPixi($s))return "pixi";
if(isRekord($s))return "rekord";
if(isImage($s))return "image";
if(_in($s,"@vid@"))return "video";
if(_in($s,"@lnk@"))return "link";
if($ext==".htm")return "file";
if($ext=="")return "folder";
return "";
}

function isFilter($s){
$s=get_ext($s);
//for($i=0;$i<count($a);$i++){if($s==$a[$i])return true;}
if($s==".ixx")return true;
return false;}


function isPixi($s){
$s=get_ext($s);
//for($i=0;$i<count($a);$i++){if($s==$a[$i])return true;}
if($s==".ixi")return true;
return false;}

function isRekord($s){
$s=get_ext($s);
//for($i=0;$i<count($a);$i++){if($s==$a[$i])return true;}
if($s==".iki")return true;
return false;}


function isImage($s){
global $imgexts;
$s=get_ext($s);
//for($i=0;$i<count($a);$i++){if($s==$a[$i])return true;}
if(_in($imgexts,",".$s.","))return true;
return false;}

function validFile($s){   //$$$$
global $gLoggedIn,$mode;
$a=",.gif,.jpeg,.jpg,.bmp,.png,.htm,.ixi,.iki,";
$ext=strtolower(get_ext($s));
if($s=="")return false;
if(_in($s,"_pvt_"))return false;
if(_in($a,",".$ext.","))return true;
return false;}


function getName($s){
$s=explode('.',$s);
$s=$s[0];
return $s;}


function get_ext($s){
if(!_in($s,"."))return "";
//if(_ix($s,".")==0)return "";
$s=explode('.',$s);
$s='.'.end($s);    //?????
//$s='.'.end($s);
return $s;}

function get_root($s){
$s=explode('.',$s);
$s=strtolower($s[0]);          //?????
//$s=$s[0];
return $s;}


//--- get the dir (remove filename) ---
function getDir($d){
$d=rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $d), "/");
$e=explode("/",ltrim($d,"/"));
if(substr($d,0,1)=="/")$e[0]="/".$e[0];
$c=count($e);
$cp=$e[0];
for($i=1; $i<$c; $i++){
 if(!_in($e[$i],"."))$cp.="/".$e[$i];
}
return $cp;}


//--- get the path (not including last folder or file) ---
function getPath($d){
$d=rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $d), "/");
$e=explode("/",ltrim($d,"/"));
if(substr($d,0,1)=="/")$e[0]="/".$e[0];
$c=count($e);
$cp=$e[0];
for($i=1;$i<($c-1);$i++)$cp.="/".$e[$i];
if($cp==$d)$cp="";
return $cp;}


//------------ get the last folder ---------------
function getTail($d){
if($d=="")return "";
$tail="/".$d."/";
$tail=str_replace("//","/",$tail);
$tail=_split($tail,"/");
if(count($tail)>1)$tail=$tail[count($tail)-2];
else $tail="";
//wrtb(" (tail=".$tail.") ");
return $tail;}


//------------ get the file name ---------------
function getFile($d){
$tmp=$d;
$tmp=_split($tmp,"/");
$f=$tmp[count($tmp)-1];
if(_in($f,"."))return $f;
return "";}


//------------ get a shortened name ---------------
function getShortName($d,$len){
if(strlen($d)<$len)return $d;
$tmp=_split($d,",");
$d=$tmp[0];
if(strlen($d)<$len)return $d;
$tmp=_split($d," ");
$d=$tmp[0];
if(strlen($d)<$len){  //try to get 2 words (eg. st nbr + street)
 if(count($tmp)>1)$d=$d." ".$tmp[1];
 if(strlen($d)>$len)$d=$tmp[0]; //too long so just get first
}
if(strlen($d)<$len)return $d;
return substr($d,0,$len);
}


function thumbName($f){
if(_in($f,"_tn."))return $f;
$a=_split($f,".");
$x="";
for($i=0;$i<(count($a)-1);$i++){
 if($i)$x.=".";
 $x.=$a[$i];
}
$x.="_tn.".$a[count($a)-1];
return $x;
}

function getNewName($user,$n){
//$n=strtolower($n);
//$n=str_replace("%20","_",$n);
//$n=str_replace("index","indx",$n);
//$a=_split($n
return $n;}



//========================== file handling =====================================

//---- check if update is allowed ---
function hasRights($dirpath){
global $gAcctAdmin;
if(!$gAcctAdmin)return 0;
return 1;
}


//---- check if a dir/file exists (if not create it, else return contents)
function chkExists($dirpath,$file,$txt){
if(!hasRights($dirpath))return;
if(substr($dirpath,0,1)=="/")$dirpath=substr($dirpath,1);
if($dirpath!=""){
 if(!file_exists($dirpath)){
  myMkDir($dirpath);
  if($file)myWriteFile($dirpath."/".$file,$txt);
  return ",";
}}
if($file){
 $dirpath=getDirSlash($dirpath);
 if(!file_exists($dirpath.$file)){
  myWriteFile($dirpath.$file,$txt);
  return $txt;
 }else{
  return myReadFile($dirpath.$file);
}}
return "";
}


function myReadFile($f){
global $gPath;
if(_ix($f,$gPath)!=0)$f=$gPath.$f;
if(!file_exists($f))return "";
$fh=fopen($f,'r');
$theData=fread($fh,10000);
fclose($fh);
return $theData;}


function myWriteFile($f,$txt){
global $gPath;
global $gLoggedIn;
if(!hasRights($gPath.$f)){
	echo("myWriteFile not logged in!");
	exit();
}
if(_ix($f,$gPath)!=0)$f=$gPath.$f;
//wrtb($f);
$fh=fopen($f,'w');
fwrite($fh,$txt);
fclose($fh);
}


function myDeleteFile($f){
global $gPath,$dirlink;
if(!hasRights($gPath.$f)){
	echo("myDeleteFile not logged in!");
	exit();
}
//wrt("f=$f<br>");
$f=_rep($f,$gPath,"");
$f=$dirlink.$f;
//wrt("f1=$f<br>");
$ext=get_ext($f);
if(isImage($f) && !_in($f,"_tn."))$f=_rep($f,$ext,"_tn".$ext);
//wrt("f2=$f<br>ext=$ext<br>");
if(_ix($f,$gPath)!=0)$f=$gPath.$f;
//exit("deleting ".$f.",  "._ix($f,$gPath).", path=".$gPath.", dirlink=".$dirlink);
//wrt("f3=$f<br>");
if(file_exists($f))unlink($f);
if(strpos($f,"_tn.")){
 $f=str_replace("_tn.",".",$f);
 //wrt("f4=$f<br>");
 if(file_exists($f))unlink($f);
}}


function myDeleteDir($d, $DeleteMe){
//deletes all files first then the dir (if deleteme is true)
global $gPath;
if(!hasRights($gPath.$d)){
	echo("myDeleteDir not logged in!");
	exit();
}
$origdir=$d;
if(_ix($d,$gPath)!=0)$d=$gPath.$d;
if(!$dh = @opendir($d)) return;
while (($obj = readdir($dh))) {
  if($obj=='.' || $obj=='..') continue;
  //wrtb("deleting ".$d.'/'.$obj);;
  if (!@unlink($d.'/'.$obj))myDeleteDir($origdir.'/'.$obj, true);
}
if($DeleteMe){
  closedir($dh);
  @rmdir($d);
}}


function myDeleteFiles($d){
//deletes all files (web pages and text files etc.)
global $gPath,$dir;
if(!hasRights($gPath.$d)){
	echo("myDeleteFiles not logged in!");
	exit();
}
$origdir=$d;
$d=$gPath.$d;
if(!$dh = @opendir($d)) return;
while (($obj = readdir($dh))) {
  if(myFileType($obj)=="file")@unlink($d.'/'.$obj);
}}

function myDeleteActions($d){
//deletes all files (web pages and text files etc.)
global $gPath,$dir;
$origdir=$d;
//echo("gPath=".$gPath.", dir=".$dir.", d=".$d);
$d=$gPath.$d;
if(!$dh = @opendir($d)) return;
while (($obj = readdir($dh))) {
  if(myFileType($obj)=="rekord")@unlink($d.'/'.$obj);
}}

function myDeletePixis($d){
//deletes all files (web pages and text files etc.)
global $gPath,$dir;
if(!hasRights($gPath.$d)){
	echo("myDeletePixis not logged in!");
	exit();
}
$origdir=$d;
//echo("gPath=".$gPath.", dir=".$dir.", d=".$d);
$d=$gPath.$d;
if(!$dh = @opendir($d)) return;
while (($obj = readdir($dh))) {
  if(myFileType($obj)=="pixi")@unlink($d.'/'.$obj);
}}


function myDeleteImages($d){
//deletes all images but NOT the _pvt_images.txt
global $gPath;
if(!hasRights($gPath.$d)){
	echo("myDeleteImages not logged in!");
	exit();
}
$origdir=$d;
$d=$gPath.$d;
if(!$dh = @opendir($d)) return;
while (($obj = readdir($dh))) {
   if(myFileType($obj)=="image")@unlink($d.'/'.$obj);
}}



//----rename file or dir and/or create new path if necessary ---
function myRename($dir,$newdir){
global $gPath;
$path=getPath($newdir);
if(!hasRights($path)){
	echo("myRename not logged in!");
	exit();
}
if($path!="")myMkdir($path);
//wrtb("path=".$path.", renaming ".$gPath.$dir." to ".$gPath.$newdir);;
$result=rename($gPath.$dir,$gPath.$newdir);
return $result;}


//------- copy file and create new path if necessary --------
function myCopyFile($dir,$newdir){
global $gPath;
$path=getPath($newdir);
if(!hasRights($path)){
	echo("myCopyFile not logged in!");
	exit();
}
if($path!="")myMkdir($path);
if(_ix($dir,$gPath)!=0)$dir=$gPath.$dir;
if(_ix($newdir,$gPath)!=0)$newdir=$gPath.$newdir;
//wrtb("copy ".$dir." to ".$newdir);
$result=copy($dir,$newdir);
return $result;}


//------- copy a file from the root to the user's root folder --------
function myForceCopyFile($dir,$newdir){
global $gPath;
$path=getPath($newdir);
//wrtb("dir=".$gPath.$path);
if(_in($path,"/"))$v=@mkdir($gPath.$path, 0755);
if(_ix($dir,$gPath)!=0)$dir=$gPath.$dir;
if(_ix($newdir,$gPath)!=0)$newdir=$gPath.$newdir;
//wrtb("copy ".$dir." to ".$newdir);
$result=copy($dir,$newdir);
return $result;}


//------- copy dir and create new path if necessary --------
function myCopyDir($dir,$newdir){
global $gPath,$dirlink;
myMkdir($newdir);
if(_ix($dir,$gPath)!=0)$dir=$gPath.$dir;
if(_ix($newdir,$gPath)!=0)$newdir=$gPath.$newdir;
if($d=@opendir($dir)){
 while(($file=readdir($d))!==false){
  if($file!=".." && $file!="."){
   copy($gPath.$dirlink.$file,$newdir."/".$file);
 }}
 closedir($d);
}}


function myMkdir($path){
global $gPath;
$path=$gPath.$path;
if(!hasRights($path)){
	echo("myMkDir not logged in!");
	exit();
}
$xmode = 0755;
/****** THIS CREATES MULTIPLE FOLDERS (THE WHOLE PATH) - NOT A GOOD IDEA? ******** 
$path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
$e = explode("/", ltrim($path, "/"));
if(substr($path, 0, 1) == "/") {
 $e[0] = "/".$e[0];
}
$c = count($e);
$cp = $e[0];
for($i = 1; $i < $c; $i++) {
 //wrtb("cp=".$cp);
 @mkdir($cp,$xmode);
 //if(!is_dir($cp) && !@mkdir($cp, $xmode))return false;
 $cp .= "/".$e[$i];
}
*/
//wrtb("path=".$path);
$v=@mkdir($path, $xmode);
return $v;
}




//-------------------------------- LINKS --------------------------------------

function myDeleteLink($f){
global $gPath,$dirlink,$linkfile,$dir;
$found=0;
$newtxt=",";
$link=$f;
if(!_in($link,"@lnk@"))$link="@lnk@".$link;
if(_ix($link,"@lnk@http_")==0)$link=_rep($link,"http_","http://");
$link=_rep($link,"%20"," ");
$txt=myReadFile($dirlink.$linkfile);
$a=_split($txt,",");
for($i=0;$i<count($a);$i++){
 if($a[$i]!=""){
  $tmp=_rep($a[$i],"%20"," ");
  $b=_split($tmp,";");
  $tmp=$b[0];
  if($found || !_in($tmp,$link))$newtxt.=$a[$i].",";
  else $found=1;
}}
//wrtb("<br><br>".$dirlink.$linkfile.", <br><br>".$newtxt);
myWriteFile($dirlink.$linkfile,$newtxt);
return true;
}

function getLinkTitle($f){
global $gPath,$dirlink,$linkfile;
$link=$f;
if(!_in($link,"@lnk@"))$link="@lnk@".$link;
$txt=myReadFile($dirlink.$linkfile);
$a=_split($txt,",");
$t2=_rep($link,"http://","");
$t2=_rep($t2,"https://","");
for($i=0;$i<count($a);$i++){
 $t1=_rep($a[$i],"http://","");
 $t1=_rep($a[$i],"https://","");
 //wrtb("T1=".$t1." <br><br> T2=".$t2."<br>");
 if(_in($t1,_rep($t2,"@lnk@",""))){
  $b=_split($a[$i],";");
  if(count($b)>1)return $b[1];
}}
return getLinkId($link);
}


function getLinkId($f){
$link=getTail($f);
return _rep($link,"@lnk@","");
}


//-------------------------------- VIDEOS --------------------------------------

function myAddVideo($tid,$tit){
global $dirlink,$videofile,$dir;
$tit=_rep($tit,";","");
$tit=_rep($tit,",","");
$tit=_rep($tit,"~!@","'");
$txt=myReadFile($dirlink.$videofile);
if($txt=="")$txt=",";
//wrt("alert('tid=".$tid.", file=".$dirlink.$videofile.", txt=".$txt."');");
if(!_in($tid,"@vid@"))$tid="@vid@".$tid;
$tmp=$tid.";".$tit;
if(!_in($txt,$tmp)){
 //$txt.=$tid.";".$tit.",";
 $txt=",".$tid.";".$tit.$txt;
 myWriteFile($dirlink.$videofile,$txt);
}
myAddVid($dirlink,$tid,$tit);
//if($dir)myAddVid(getDir(""),$tid,$tit);
//myAddVidToGrooze($tid,$tit);
}

function myAddVid($dirlnk,$tid,$tit){
global $videofile;
$tit=_rep($tit,";","");
$tit=_rep($tit,",","");
$tit=_rep($tit,"~!@","'");
$txt=myReadFile($dirlnk.$videofile);
if($txt=="")$txt=",";
//wrt("alert('tid=".$tid.", file=".$dirlnk.$videofile.", txt=".$txt."');");
if(!_in($tid,"@vid@"))$tid="@vid@".$tid;
$tmp=$tid.";".$tit;
if(!_in($txt,$tmp)){
 //$txt.=$tid.";".$tit.",";
 $txt=",".$tid.";".$tit.$txt;
 myWriteFile($dirlnk.$videofile,$txt);
}}


function myAddVidToGrooze($tid,$tit){
global $gFetchFromGrooze;
if($gFetchFromGrooze)$txt=getUrl("http://grooze.com/_grojx.php?action=savevideo&tid=$tid&tit=$tit");
//alert($txt);
}


function myDeleteVideo($f){
global $dir,$dirlink;
myDeleteVid($dirlink,$f);
//if($dir)myDeleteVid(getDir(""),$f);
//myDeleteGroozeVid(getTail($f));
return true;
}

function myDeleteVid($dirlnk,$f){
global $gPath,$videofile;
$tit="";
$found=0;
$newtxt=",";
$video=getTail($f);
if(!_in($video,"@vid@"))$video="@vid@".$video;
$txt=myReadFile($dirlnk.$videofile);
$a=_split($txt,",");
for($i=0;$i<count($a);$i++){
 if($a[$i]!=""){
  if($found || !_in($a[$i],$video))$newtxt.=$a[$i].",";
  else $found=1;
}}
//wrtb("<br><br>".$dirlink.$videofile.", <br><br>".$newtxt);
myWriteFile($dirlnk.$videofile,$newtxt);
}


function myDeleteGroozeVid($tid){
global $gFetchFromGrooze;
if($gFetchFromGrooze)$txt=getUrl("http://grooze.com/_grojx.php?action=deletevideo&tid=$tid");
//alert($txt);
}



function myDeleteVideoWithDir($f,$pdir){
global $gPath,$videofile;
$found=0;
$newtxt=",";
$video=getTail($f);
if(!_in($video,"@vid@"))$video="@vid@".$video;
$txt=myReadFile($pdir.$videofile);
$a=_split($txt,",");
for($i=0;$i<count($a);$i++){
 if($a[$i]!=""){
  if($found || !_in($a[$i],$video))$newtxt.=$a[$i].",";
  else $found=1;
}}
//wrtb("<br><br>".$pdir.$videofile.", <br><br>".$newtxt);
myWriteFile($pdir.$videofile,$newtxt);
return true;
}



function getVideoTitle($f){
global $gPath,$dirlink,$videofile;
$video=getTail($f);
if(!_in($video,"@vid@"))$video="@vid@".$video;
$txt=myReadFile($dirlink.$videofile);
$a=_split($txt,",");
for($i=0;$i<count($a);$i++){
 if(_in($a[$i],$video)){ 
  $b=_split($a[$i],";");
  if(count($b)>1)return $b[1];
}}
return getVideoId($video);
}


function getVideoId($f){
$video=getTail($f);
return _rep($video,"@vid@","");
}


//-----------------------------------crop()----------------------------------------------
function crop($name,$filename,$x,$y,$w,$h,$ext){
if(!hasRights($filename)){
	echo("crop not logged in!");
	exit();
}
$ok="no";
if(preg_match("/png|PNG/",$ext)){ $src_img=imagecreatefrompng($name); $ok="png";}
if(preg_match("/gif|GIF/",$ext)){ $src_img=imagecreatefromgif($name); $ok="gif";}
if($ok=="no"){$src_img=imagecreatefromjpeg($name); $ok="jpg";} //default to jpg
if($src_img && $ok!="no"){
 	$src_w=imageSX($src_img);
 	$src_h=imageSY($src_img);
	//echo("src_w=".$src_w.", src_h=".$src_h."<br>");
 	$dst_img=ImageCreateTrueColor($w,$h);
	//imagecopyresampled() ($dst_image,$src_image ,$dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h )
	//This will take a rectangular area from src_image of width src_w and height src_h 
	//at position (src_x,src_y) and place it in a rectangular area of dst_image of width dst_w and height dst_h 
	//at position (dst_x,dst_y).
 	imagecopyresampled($dst_img,$src_img,0,0,$x,$y,$w,$h,$w,$h);
 	if($ok=="png")imagepng($dst_img,$filename);
 	if($ok=="gif")imagegif($dst_img,$filename);
 	if($ok=="jpg")imagejpeg($dst_img,$filename);
 	imagedestroy($dst_img);
 	imagedestroy($src_img);
 	return 1;
}
return 0;
}


//-----------------------------------resize()----------------------------------------------
function resize($name,$filename,$new_x,$new_y,$ext){
global $gPath;
if(!_in($name,$gPath)){ 
	if(!_in(strtolower($name),"http"))$name=$gPath.$name;
}
if(!_in($filename,$gPath))$filename=$gPath.$filename;
if(!hasRights($filename)){
	echo("resize not logged in!");
	exit();
}
$ok="no";
if(preg_match("/png|PNG/",$ext)){ $src_img=imagecreatefrompng($name); $ok="png";}
if(preg_match("/gif|GIF/",$ext)){ $src_img=imagecreatefromgif($name); $ok="gif";}
if($ok=="no"){$src_img=imagecreatefromjpeg($name); $ok="jpg";} //default to jpg
if($src_img && $ok!="no"){
	 $old_x=imageSX($src_img);
	 $old_y=imageSY($src_img);
	 if($new_x==0)$new_x=$old_x;
	 if($new_y==0)$new_y=$old_y;
	 if($old_x > $old_y){$thumb_x=$new_x;$thumb_y=$old_y*($new_x/$old_x);}
	 if($old_x < $old_y){$thumb_x=$old_x*($new_y/$old_y);$thumb_y=$new_y;}
	 if($old_x == $old_y){$thumb_x=$new_x;$thumb_y=$new_y;}
	 //wrtb("x=".$old_x.", y=".$old_y.", nx=".$new_x.", ny=".$new_y.", tx=".$thumb_x.", ty=".$thumb_y);;
	 $dst_img=ImageCreateTrueColor($thumb_x,$thumb_y);
	 if(preg_match("/png|PNG/",$ext) || preg_match("/gif|GIF/",$ext)){
	 	imagealphablending($dst_img, false);
	 	imagesavealpha($dst_img, true);
	}
	 imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_x,$thumb_y,$old_x,$old_y);
	 if($ok=="png")imagepng($dst_img,$filename);
	 if($ok=="gif")imagegif($dst_img,$filename);
	 if($ok=="jpg")imagejpeg($dst_img,$filename);
	 imagedestroy($dst_img);
	 imagedestroy($src_img);
	 return 1;
}
return 0;
}


//=============================== utilities ====================================

function str_replace_once($str_pattern, $str_replacement, $string){

    if (strpos($string, $str_pattern) !== false){
        $occurrence = strpos($string, $str_pattern);
        return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
    }

    return $string;
}

function reloadMenu($dir,$goback,$noreload,$loggingout){
echo("<script>");
//echo("try{parent.frames[0].gotoDir('".$dir."',$goback,null,1,$noreload);}catch(e){}");
echo("try{parent.refreshMenus('$dir',$goback,$noreload,$loggingout);}catch(e){}");
echo("</script>");
}


function refreshMenu(){
?>
<script>
var err=0;
//try{parent.frames[0].refresh(1);}catch(e){err=1;}
//if(err)parent.frames[0].gotoDir('<?=$dir;?>',1,null,1,0);
parent.getToolbarObj().frames[0].refresh(1);
</script>
<?
}


function fetch($v,$att,$ett,$length){
if(_in($v,$att)){
 $a=_split($v,$att);
 if(_in($a[1],$ett)){
  $b=_split($a[1],$ett);
  $x=$b[0];
  if($length>0)$x=_left($x,$length);
  $x=_rep($x,"'","");
  $x=_rep($x,"\"","");
  $x=_rep($x,"<","");
 }
}
return $x;
}

function winSuccessMsg($msg){
global $movers;
echo("<center><div style='text-align:center;'><span class='successmsg'><big><big>Success!</big></big></span>");
if($msg)echo("<br><span class=msgtxt>".$msg."</span>");
echo("<br><br><input type=button class=btn value='Continue' ".$movers." onclick='parent.closeWindow();' style='width:80px;'></div>");
}


function successMsg($msg,$left=550,$top=10){
// this used to be used to do closeUtil()
global $movers;
echo("<div style='padding-top:6px;text-align:center;'><span class='successmsg'><big>Success!</big></span>");
if($msg)echo("<br><span class=msgtxt>".$msg."</span></div>");
echo("</div>");
//echo("<br><br><input class=btn value='Continue' ".$movers." onclick='parent.closeWindow(1)' style='width:80px;'></div>");
}

function errorMsg($msg,$left=550,$top=10){
echo("<div style='padding-top:5px;text-align:center;'><span class='errormsg'><big><big>Error!</big></big></span><br><span class=msgtxt>".$msg."</span></div>");
}

function reload(){
 //echo("<script>try{top.window.location.href=top.window.location.href;}catch(e){alert(e);}</script>");
  echo("<script>_reloadWindow();</script>");
 }

function myGet($x){ if(isset($_GET[$x]))$v=$_GET[$x]; else $v="";  return $v;}
function myPost($x){ if(isset($_POST[$x]))$v=$_POST[$x]; else $v="";  return $v;}
function rqst($x){$v=myGet($x);if($v=="")$v=myPost($x);return $v;}
function rqstq($x){return myGet($x);}
function getIF($x){$v=myGet($x); if(!empty($v))return "\$".$x."='".$v."';"; return ""; }
function mySession($x){ if(isset($_SESSION[$x])) $v=$_SESSION[$x]; else $v=""; return $v; }
function myCookie($x){ if(isset($_COOKIE[$x])) $v=$_COOKIE[$x]; else $v=""; return $v; }

function opacityTxt($i){ $i=_rep($i,"%",""); return "filter:alpha(opacity = ".$i."); moz-opacity:".($i/100)."; opacity:".($i/100).";";}
function reloadSlideshow(){echo("<script>try{parent.frames[0].gotoSlideshow();}catch(e){}</script>");exit();}
function reloadMsg($msg,$dir){echo("<script>try{window.location.href='_newutil.php?page=msg&msg=".$msg."&dir=".$dir."';}catch(e){}</script>");}
function wrt($s){echo($s);}
function wrtb($s){echo($s."<br>");}
function _in($txt,$v){if(empty($txt) || empty($v))return 0;
 //wrtb("<br><br>".$txt."-contains?-".$v."<br><br>");;
 $r=strpos($txt,$v);if($r===false)return 0;return 1;}
function _ix($txt,$v){if(empty($txt) || empty($v))return false;$r=strpos($txt,$v);if($r===false)return -1;return $r;}
function _rep($txt,$v1,$v2){return str_replace($v1,$v2,$txt);}
function _split($txt,$v){return explode($v,$txt);}
//function split($v,$txt){return explode($v,$txt);}  // removed  in php 7.0.0
function _left($x,$i){return substr($x,0,$i);}
function _right($x,$i){return substr($x,($i*-1));}
function _len($x){return strlen($x);}
function alert($x){echo("<script>alert('$x');</script>");}
function getUrl($url){$url=_rep($url," ","%20"); return file_get_contents($url);}
function xtml($x){return _rep($x,"<","&lt;");}
function _leftStr($txt,$v){	$r=strpos($txt,$v);	if(!$r)return $txt;	return _left($txt,$r);	}



function getDisplayStyle($show){
if($show && $show!="no")return "";
return "display:none;";
}

function getDisplayStyleAND($show1,$show2){
if($show1 && $show1!="no" && $show2 && $show2!="no")return "";
return "display:none;";
}


//--------------------------- GET YOUTUBE TITLE --------------------------------
function getYoutubeTitle($vid){
$json = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$vid."?v=2&alt=jsonc"));
//echo '<img src="' . $json->data->thumbnail->sqDefault . '">';
return $json->data->title;
}


//--------------------------- getYoutubeDetails() ------------------------------
function getYoutubeDetails($v,&$tid,&$tit,&$tim,&$desc,&$views){
global $movers,$roundedcorners;
$tid=fetch($v,"watch?v=","\">",0);
$tmpid=_split($tid,"&");
$tid=$tmpid[0];
$tit=fetch($v,"none;\" href=\"http://www.youtube.com/watch?v=".$tid,"</a>",0);
$a=_split($tit,">");
$tit=$a[1];
$b=_split($tit,"<");
$tit=$b[0];
$tit=_rep($tit,"&amp;#39;","");
$tit=_rep($tit,"&amp;quot;","");
$tit=_rep($tit,"&amp;","");
$tit=_rep($tit,";","");
$tit=_rep($tit,"'","");
$tit=_rep($tit,",","");
$tit=_rep($tit,"??","?");
$tim=fetch($v,"font-size: 11px; font-weight: bold;\">","</span>",0);
$desc=fetch($v,"3px 0px;\"><span>","</span>",0);
$desc=_rep($desc,"&amp;#39;","'");
$views=fetch($v,"Views:</span>","</div>",0);
}



?>
