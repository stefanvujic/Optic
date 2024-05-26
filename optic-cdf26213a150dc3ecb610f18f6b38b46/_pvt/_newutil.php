<?
include_once "_inc_global.php";
include_once "_inc_header.php";

//-------load the correct function--------
$page=rqst("page");
$page=strtolower($page);
$link=rqst("link");


//----------- NB: WE NEED TO ADD SECURITY CHECKS TO ALL FUNCTIONS! -------------

//--- OLD WAY: if(!$gLoggedIn && $page!="logout")$page="login";  //all functions require login


if(strpos($page,"file")){
	 $act=str_replace("file","",$page);
	 $typ="file";
	 $file=rqst("file");
	 $filetyp=myFileType($file);
}else{
	if(strpos($page,"filter")){
		 $act=str_replace("filter","",$page);
		 $typ="file";
		 $file=rqst("file");
		 $filetyp=myFileType($file);
	}else{
		 $act=str_replace("dir","",$page);
		 $typ="folder";
		 $file="";
		 $filetyp="";
}	}

if($file!="")$dirfile=$dirlink.$file;
else $dirfile=$dir;

//wrt("<!-- typ=".$typ.", filetype=".$filetyp.", dir=".$dir.", file=".$file.", dirfile=".$dirfile.", page=".$page.", act=".$act." -->");;

?>
<SCRIPT>
var typ="<?=$typ;?>";
</script>

<STYLE>
.foldername{font-size:26px;padding-left:0px;padding-right:0px;color:<?=$lkcolor?>;}
.outerspan{text-align:center;padding:5px;}
.ddown{}
.popupMsgtxt {font-size:22px;}
.txtlabel	 {font-size:22px;}
</STYLE>
</HEAD>
<BODY style='text-align:center;'>
<DIV class=outerspan>
<FORM name="f1" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
<?
switch($page){
 case 'saverekord'	: saverekordOLD();   			break;	//--- remove this once we update the NEW
 case 'savereplay'	: saverekord("Replay");   	break;
 case 'saveautoplay': saverekord("Autoplay");   break;
 case 'savepixi'  : savepixi();     break;
 case 'savefilter': savefilter();   break;
 case 'logout'    : logout();       break;
 case 'login'     : login();        break;
 case 'msg'       : myMsg($dir,""); break;
 case 'newdir'    : newDir();       break;
 case 'deletelink': deleteLink();   break;
 case 'deletedir' :
 case 'deletefile': deleteX();      break;
 case 'renamedir' :
 case 'renamefile':
 case 'copydir'   :
 case 'copyfilter':
 case 'copyfile'  : renameX();      break;
 case 'editfile'  : editFile();     break;
 case 'newfile'   : newFile();      break;
 case 'newtext'   : newText();      break;
 case 'editurl'   : editUrl();      break;
 case 'sethome'   : setHome();      break;
 case 'addvideo'  : addVideo();     break;
 case 'addfile'   : addFile();      break;
 case 'help'      : help();         break;
}
if(_in($page,"deleteall")){
 deleteAll($page);
}
?>

<input type=hidden id='page'  name='page'  value='<?=$page;?>'>
<input type=hidden id='dir'   name='dir'   value='<?=$dir;?>'>
<input type=hidden id='file'  name='file'  value='<?=$file;?>'>
<input type=hidden id='link'  name='link'  value='<?=$link;?>'>

</FORM></DIV>
</BODY></HTML>

<?
ob_end_flush();  //flush the buffer
exit();




//----------- SAVE PIXI -------------
function savepixi(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$fimgfile;
$action=myPost('action');
$txt=myPost('txt');
if($action=='update'){
 if(empty($txt) || _in(strtolower($txt),"http://") || _in(strtolower($txt),".")){
  errorMsg("Invalid name");
  $action='';
 }
}else{
 $txt=rqst("name");
}
if($action=='update'){
  $txt=str_ireplace("<","",$txt);
  ?>
  <script>
  parent.gPixi.gPic.savePicConfig(null,null,"<?=$txt;?>");
  parent.closeWindow();
  </script>
  <?
  return;
}
?>
<input type=hidden value='update' name=action>
<center>
<br>
<TABLE width="60%" border="0" cellpadding="5" cellspacing="0" style="zoom:1.5;">
<tr height=20><td width=40><big>Name:&nbsp;</big></td><td><input type=text name=txt value="<?=$txt;?>" style='width:120px;height:25px;'></td></tr>
<tr height=20><td colspan=2 align=center><input id="iSubmitBtn" class='btn' type="submit" value="Save" <?=$movers;?> style='width:100px;position:relative;top:8px;height:25px;'></td></tr>
</TABLE>
</center>
<script>_obj("iSubmitBtn").focus(); </script>
<?
}


//----------- SAVE FILTER -------------
function savefilter(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$fimgfile;
$action=myPost('action');
$txt=myPost('txt');
if($action=='update'){
 if(empty($txt) || _in(strtolower($txt),"http://") || _in(strtolower($txt),".")){
  errorMsg("Invalid name");
  $action='';
 }
}else{
 $txt=rqst("name");
}
if($action=='update'){
  $txt=str_ireplace("<","",$txt);
  ?>
  <script>
  parent.gPixi.gPic.saveFilter("<?=$txt;?>");
  parent.closeWindow();
  </script>
  <?
  return;
}
?>
<input type=hidden value='update' name=action>
<center>
<br>
<TABLE width="60%" border="0" cellpadding="5" cellspacing="0" style="zoom:1.5;">
<tr height=20><td width=40><big>Name:&nbsp;</big></td><td><input type=text name=txt value="<?=$txt;?>" style='width:120px;height:25px;'></td></tr>
<tr height=20><td colspan=2 align=center><input id="iSubmitBtn" class='btn' type="submit" value="Save" <?=$movers;?> style='width:100px;position:relative;top:8px;height:25px;'></td></tr>
</TABLE>
</center>
<script>_obj("iSubmitBtn").focus(); </script>
<?
}

//----------- SAVE RECORDING -------------
function saverekord($anityp){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$fimgfile;
$action=myPost('action');
$txt=myPost('txt');
if($action=='update'){
 if(empty($txt) || _in(strtolower($txt),"http://") || _in(strtolower($txt),".")){
  errorMsg("Invalid name");
  $action='';
 }
}else{
 $txt=rqst("name");
}
if($action=='update'){
  $txt=str_ireplace("<","",$txt);
  ?>
  <script>
  parent.gPixi.gPic.savePic<?=$anityp;?>("<?=$txt;?>");
  parent.closeWindow();
  </script>
  <?
  return;
}
?>
<input type=hidden value='update' name=action>
<center>
<br>
<TABLE width="60%" border="0" cellpadding="5" cellspacing="0" style="zoom:1.5;">
<tr height=20><td width=40><big>Name:&nbsp;</big></td><td><input type=text name=txt value="<?=$txt;?>" style='width:120px;height:25px;'></td></tr>
<tr height=20><td colspan=2 align=center><input id="iSubmitBtn" class='btn' type="submit" value="Save" <?=$movers;?> style='width:100px;position:relative;top:8px;height:25px;'></td></tr>
</TABLE>
</center>
<script>_obj("iSubmitBtn").focus(); </script>
<?
}


//----------- TEMP! -------------
function saverekordOLD(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$fimgfile;
$action=myPost('action');
$txt=myPost('txt');
if($action=='update'){
 if(empty($txt) || _in(strtolower($txt),"http://") || _in(strtolower($txt),".")){
  errorMsg("Invalid name");
  $action='';
 }
}else{
 $txt=rqst("name");
}
if($action=='update'){
  $txt=str_ireplace("<","",$txt);
  ?>
  <script>
  parent.gPixi.gPic.savePicRekord("<?=$txt;?>");
  parent.closeWindow();
  </script>
  <?
  return;
}
?>
<input type=hidden value='update' name=action>
<center>
<br>
<TABLE width="60%" border="0" cellpadding="5" cellspacing="0" style="zoom:1.5;">
<tr height=20><td width=40><big>Name:&nbsp;</big></td><td><input type=text name=txt value="<?=$txt;?>" style='width:120px;height:25px;'></td></tr>
<tr height=20><td colspan=2 align=center><input id="iSubmitBtn" class='btn' type="submit" value="Save" <?=$movers;?> style='width:100px;position:relative;top:8px;height:25px;'></td></tr>
</TABLE>
</center>
<script>_obj("iSubmitBtn").focus(); </script>
<?
}



//========================= LOGIN ==============================
function login(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$gRoot,$domain,$dirlink;
$id=myPost('id');
$btn=myPost('subbtn');
$pw=myPost('pw');
$newacct=(_in($btn,"New"))?1:0;
//if(!$id)$id="guest";
$success="";
if($btn){
	 if($newacct)$success=newAccount($dir,$id,$pw);
	 else $success=doLogin($dir,$id,$pw);
}
//echo("success=".$success);
if($success!=""){
	 if($success=="yes"){
		  if($dir)$tmp=(getTail($dir)==$id)?$dir:$dir."/".$id;
		  else $tmp=($id==_rep($domain,"www.",""))?"":$id;
		  ?>
		  <center>
		  <TABLE width=250 cellpadding=5 cellspacing=0>
		  <tr><td align=center><span class='successmsg'><big><big>Success!</big></big></span><br>You have been logged in.<br><br>Your current folder is now "<?=$id;?>". Click the 'Plus' sign in the top toolbar to upload images or to search for videos.<br>&nbsp;</td></tr>
		  <tr><td align=center><input class='btn' type=button onclick='_reloadWindow()' value=' Continue ' <?=$movers;?>></td></tr>
		  </TABLE>
		  </center>
		  <script>setTimeout("_reloadWindow(); ",  4000);</script>
		  <?
		  return;
	 }else{
		  errorMsg($success,350,25);
		  ?><div style='height:5px;'></div><?
}}
?>

<style>
  .txthdr {
    font-size: 16px;
  }
</style>

<center>
<TABLE class='login-fields' cellpadding=5 cellspacing=0>
<tr>
<td class=txthdr>Login ID: </td>
<td><input name=id type=text value='<?=$id;?>' style='width:200px;'></td>
</tr>
<tr>
<td class=txthdr>Password: </td>
<td><input name=pw type=password value='<?=$pw;?>' style='width:200px;'></td>
</tr>
<tr><td colspan=2 align=center><input class='btn' name=subbtn type=submit value='    Login    ' <?=$movers;?>></td></tr>
<tr><td colspan=2 align=center><div style='height:8px;'></div></td></tr>
<tr><td colspan=2 align=center><input type='button' class='btn show-register-view' class='btn' name='register' value=' Create New Account'<?=$movers;?>></td></tr>
</TABLE>

<TABLE style='display: none;' class='register-fields' cellpadding=5 cellspacing=0>

<tr>

<td class=txthdr>Login ID: </td>

<td><input name=id type=text value='<?=$id;?>' style='width:200px;'></td>

</tr>

<tr>

<td class=txthdr>Email: </td>

<td class='append-registration-fields'><input name='reg-email' type=text value='<?=$pw;?>' style='width:200px;'></td>

</tr>

<tr>

<td class=txthdr>Name: </td>
d
<td class='append-registration-fields'><input name='reg-full-name' type=text value='<?=$pw;?>' style='width:200px;'></td>

</tr>

<tr>

<td class=txthdr>Password: </td>

<td><input name=pw type=password value='<?=$pw;?>' style='width:200px;'></td>

</tr>

<td class=txthdr>Repeat Password: </td>

<td class='append-registration-fields'><input name='reg-repw' type=password value='<?=$pw;?>' style='width:200px;'></td>

</tr>
<tr><td colspan=2 align=center><input class='btn' name=subbtn type=submit value='    Create Account    ' <?=$movers;?>></td></tr>
<!-- <tr><td colspan=2 align=center><input type='button' class='btn register' name='register' value='Create Account'<?=$movers;?>></td></tr> -->

<tr><td colspan=2 align=center><input style='margin-top: 5%;' class='btn show-login-view' name='login' type=button name='login' value='Back to Login'<?=$movers;?>></td></tr>

<tr><td colspan=2 align=center><div style='height:8px;'></div></td></tr>

</TABLE>
</center>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(".show-register-view").click(function(){
    $(".login-fields").hide();
    $(".register-fields").show();
});
$(".show-login-view").click(function(){
    $(".register-fields").hide();
    $(".login-fields").show();
});
</script>
<?
}

//------------------ dologin --------------
function doLogin($dir,$id,$pw){
global $fileid,$domain;
$path=getLoginPath($dir,$id);
$txt=myReadFile($path.$fileid);
//echo("path=".$path."<br>");
//echo("dir=".$dir."<br>");
//echo("id=".$id."<br>");
//echo("pw=".$pw."<br>");
//exit("txt=".$txt);
$a=_split($txt,"|");
//exit($a[0]."==".$pw."  =".($a[0]==$pw)."<br>");
if($a[0]==$pw && $pw!=""){
	 $_SESSION['id']=$id;
	 $_SESSION['loginpath']=$path;
	 setcookie("id", $id, time()+60*60*24*30, "/", $domain, 0,0);
	 setcookie("path", $path, time()+60*60*24*30, "/", $domain, 0,0);
	 //getSettings($id,1);
	 return "yes";
}
return "Login Failed";
}
?>
<script>
  // on page load 
  $(document).ready(function(){
    console.log('dacdsafdassd')
  });
</script>
<?php
//------------------ create account --------------

function newAccount($dir,$id,$pw){
global $gPath,$fileid,$gLoggedIn,$gContent;
if(!okString($id,""))return "Invalid ID";
if(!$pw)return "Invalid Password";
$path=getLoginPath($dir,$id);	//only create new accounts in the root folder
//$path=$gContent."/";
//wrtb("path=".$path);
if(file_exists($path))return "Account already exists";
if(!mkdir($path,$mode=0755))return "Failed to create folder";
//---new---
if(_ix($f,$gPath)!=0)$f=$gPath.$f;
$fh=fopen($path."/".$fileid,'w');
fwrite($fh,$pw."||".date("y.m.d.H.m")."|".$gIP);
fclose($fh);
/* ---old---
$gLoggedIn=1;		// set here because myWriteFile requires login
$gAcctAdmin=1;
myWriteFile($path.$id."/".$fileid,$pw."||".date("y.m.d.H.m")."|".$gIP);
*/
return doLogin($dir,$id,$pw);
}


//========================= LOGOUT ==============================
function logout(){
global $gID,$dir,$movers,$domain;
if(!$gID){
 alert("Already logged out");
 return;
}
$path=getLoginPath($dir,$gID);
$_SESSION['id']="";
$_SESSION["loginpath"]="";
$_SESSION["loggedout"]="yes";
setcookie("id", "", time()-3600, "/", $domain, 0,0);
setcookie("path", "", time()-3600, "/", $domain, 0,0);
echo("<br><br>");
// --- changed to reload window completely ----
//reloadMenu($dir,0,1,1);  // calls resetToolbarRights() when it loads
?>
<center>
<TABLE width=250 cellpadding=5 cellspacing=0>
<tr><td align=center><span class='successmsg'><big><big>Success!</big></big></span><br>You have been logged out...<br>&nbsp;</td></tr>
<tr><td align=center><input class='btn' type=button onclick='_reloadWindow(); '  value=' Continue ' <?=$movers;?>></td></tr>
</TABLE>
</center>
<script>
setTimeout("_reloadWindow(); ",  1500);
</script>
<?
}





//========================= NEW TEXT ==============================
function newText(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink;
$action=myPost('action');
$name=myPost('fname');
$txt=myPost("itxt");
if($txt=="")$txt="Click here to edit this text";
if($action=='update'){
 if(empty($name)){
  errorMsg("please enter a name for the file");
 }else{
  $name=str_replace(".","",$name);
  if(!empty($dir))$name=$dir."/".$name;
  $fullname=$name.".htm";
  $txt=str_replace("\'","'",$txt);
  $txt=str_ireplace("<","&lt;",$txt);
  //----- NOTE: This is copied from _newedit.php ----
  $txt2="<HTML><HEAD><link href='http://fonts.googleapis.com/css?family=Nosifer|Aguafina+Script|Tauri|Emilys+Candy|Economica|Cabin+Sketch|Caesar+Dressing|Henny+Penny|Simonetta|Noto+Serif|Snowburst+One|Carrois+Gothic|Patrick+Hand+SC|Waiting+for+the+Sunrise|Tulpen+One|Meie+Script|Monofett|Monoton|Dr+Sugiyama|Risque|Special+Elite|Share+Tech+Mono|Sacramento|Julius+Sans+One|Merienda|Boogaloo|Orbitron|Akronim|Ribeye+Marrow' rel='stylesheet' type='text/css'></HEAD>";
  $txt2.="<BODY style='overflow:hidden;'>";
  $txt2.="<DIV id='iEle'  style='text-align:center; width:100%;height:100%;overflow:hidden;font-size:30pt;padding:10px; font-family:Economica; letter-spacing:0px; color:#000000;background:transparent;padding:50px; '>";
  $txt2.=$txt."</DIV></BODY></HTML>";
  //$txt2="<div....
  myWriteFile($fullname,$txt2);
  successMsg("<span class=foldername>".$name."</span> has been created");
  reloadMenu($dir,0,1,0);
  exit("</FORM></BODY>");
}}
if(empty($name))$name=rand(1000,99999);
if(!empty($dir))$displaydir.=$dir."/";
echo(previewScript("file"));
?>
<!-- <IMG id=iWindowClose onclick='parent.closeUtil()' src='_pvt_images/delete.png' style=' z-index:9991; display:block;position:absolute;right:0px;top:2px;width:30px;cursor:pointer;'> -->

<input type=hidden value='update' name=action>
<center>
<TABLE width="100%" height="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td>
	<table cellpadding=0 cellspacing=0><tr>
	<td>&nbsp;Name:&nbsp;&nbsp;</span></td>
	<td class=foldername>/<?=$displaydir;?></span></td>
	<td><input type=text name=fname value='<?=$name;?>'></td>
	<td>&nbsp;&nbsp;<input class='btn' type="submit" value="Create" <?=$movers;?> style='width:80;'></td>
	</tr></table>
</td></tr>
<tr><td>
<textarea id=itxt name="itxt" width="100%" height="100%" style="display:block;width:100%;height:100%" wrap=hard><?=$txt;?></textarea>
</td></tr> 
</TABLE> 
</center>
<?
}


//========================= NEW FILE ==============================
function newFile(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink;
$action=myPost('action');
$name=myPost('fname');
$txt=myPost('itxt');
if($action=='update'){
 if(empty($name)){
  errorMsg("please enter a name for the file");
 }else{
  $name=str_replace(".","",$name);
  if(!empty($dir))$name=$dir."/".$name;
  $ext=$myPost('ext');
  if(empty($ext))$ext=".txt";
  $name.=".".$ext;
  $txt=str_replace("\'","'",$txt);
  $txt2=str_ireplace("< textarea","<textarea",$txt);
  $txt2=str_ireplace("< /textarea","</textarea",$txt);

  //if($ext==".htm" || $ext==".html"){
  // $tmp=strtolower($txt2);
  // if(!_in($tmp,"<html" || !_in($tmp,"<head"){


  myWriteFile($name,$txt2);
  successMsg("<span class=foldername>".$name."</span> has been created");
  reloadMenu($dir,0,1,0);
}}
if(!empty($dir))$displaydir.=$dir."/";
echo(previewScript("file"));
?>
<!-- <IMG id=iWindowClose onclick='parent.closeUtil()' src='_pvt_images/delete.png' style=' z-index:9991; display:block;position:absolute;right:0px;top:2px;width:30px;cursor:pointer;'> -->

<input type=hidden value='update' name=action>
<center>
<TABLE width="100%" height="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td>
<table cellpadding=0 cellspacing=0><tr>
<td class=foldername>/<?=$displaydir;?></span></td>
<td><input type=text name=fname></td>
<td><select name=ext class=ddown>
<option value="htm">.htm</option>
<option value="html">.html</option>
<option value="css">.css</option>
<option value="js">.js</option>
<option value="txt">.txt</option>
</select></td>
</tr></table>
</td></tr>
<tr height=20><td><input class='btn' type="submit" value="Create" <?=$movers;?> style='width:80;'>&nbsp;<input id=pbtn class='btn' type="button" value="Preview" onclick='preview()' <?=$movers;?> style='width:80;'></td></tr>
<tr><td>
<textarea id=itxt name="itxt" width="100%" height="100%" style="display:block;width:100%;height:100%" wrap=hard><?=$txt;?></textarea>
<iframe id=ifrm name=ifrm src='' width="100%" height="100%" style="display:none;width:100%;height:100%;background:white;border:solid 1px black;"></iframe>
</td></tr> 
</TABLE> 
</center>
<?
}





//========================= EDIT FILE ==============================
function editFile(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink;
$action=myPost('action');
if($action=='update'){
 $txt=$myPost('itxt');
 $txt=str_replace("\'","'",$txt);
 $txt2=str_ireplace("< textarea","<textarea",$txt);
 $txt2=str_ireplace("< /textarea","</textarea",$txt);
 myWriteFile($dirfile,$txt2);
 successMsg("");
}else{
 $txt=myReadFile($dirfile);
 $txt=str_ireplace("<textarea","< textarea",$txt);
 $txt=str_ireplace("</textarea","< /textarea",$txt);
 $txt=str_replace("\'","'",$txt);
}
echo(previewScript("file"));
?>
<!-- <IMG id=iWindowClose onclick='parent.closeUtil()' src='_pvt_images/delete.png' style=' z-index:9991; display:block;position:absolute;right:0px;top:2px;width:30px;cursor:pointer;'> -->
<input type=hidden value='update' name=action>
<center>
<TABLE width="100%" height="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td class=foldername>&nbsp;/<?=$dirfile;?></span></td></tr>
<tr height=20><td><input class='btn' type="submit" value="UPDATE" <?=$movers;?> style='width:80;font-weight:bold;'>&nbsp;<input id=pbtn class=btn type="button" value="Preview" onclick='preview()' <?=$movers;?> style='width:80;'></td></tr>
<tr><td>
<textarea id=itxt name="itxt" width="100%" height="100%" style="display:block;width:100%;height:100%" wrap=hard><?=$txt;?></textarea>
<iframe id=ifrm name=ifrm src='' width="100%" height="100%" style="display:none;width:100%;height:100%;background:white;border:solid 1px black;"></iframe>
</td></tr> 
</TABLE> 
</center>
<?
}

//---------- preview script (see above) ---------
function previewScript($typ){
?>
<script>
function preview(){
var ox=_obj("itxt"),od=_obj("ifrm"),pb=_obj("pbtn");
if(ox.style.display=="none"){
 ox.style.display="block";
 od.style.display="none";
 pb.value="Preview";
}else{
 var htxt=ox.value;
 htxt=_rep(htxt,"< textarea","<textarea");
 htxt=_rep(htxt,"< /textarea","</textarea");
 htxt=_rep(htxt,"< TEXTAREA","<TEXTAREA");
 htxt=_rep(htxt,"< /TEXTAREA","</TEXTAREA");
 if(typ=="link"){
  window.ifrm.src=htxt;
 }else{
  window.ifrm.document.open();
  window.ifrm.document.writeln(htxt);
  window.ifrm.document.close();
 }
 ox.style.display="none";
 od.style.display="block";
 pb.value="Close";
}}
</script>
<?
}


//========================= MSG DISPLAY ==============================
function myMsg($dir,$msg){
if(empty($msg))$msg=myGet('msg');
switch($msg){
 case "deleteFolder": winSuccessMsg("The folder <span class=foldername>/".$dir."</span> has been deleted"); break;
}} 



//========================= RENAME ==============================
//-------- This renames OR copies a file OR a directory ---------
function renameX(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp,$gID,$gLoggedIn,$gAcctAdmin;
global $movers,$gPath,$dirlink,$domainid;

if(!$gLoggedIn && $gID && $filetyp=="image" && $act=="copy"){
	$forcecopy=1;
}else{
	//msg("acctAdmin="+gAcctAdmin);
	//if($dir==$gID){
	if(!gAcctAdmin){
	  errorMsg("Invalid action");
	  exit(0);	
	}
	$forcecopy=0;
}

$newdir=rqst('newdir');
$ext=rqst('ext');
$newdir=$newdir.$ext;
//wrtb("gPath=".$gPath);
if($newdir){
	if($gID!=$domainid)$newdir=$gID."/".$newdir;
	$success="";
}
$ext=get_ext($file);
//wrtb("2newdir=".$newdir);
if($filetyp=="image" && get_ext($dirfile)!=".svg"){
	 $ThumbExists=true;
	 $realname=str_replace("_tn.",".",$dirfile);
	 $thumbname=str_replace($ext,"_tn".$ext,$realname);
}else{
	 $ThumbExists=false;
	 $realname=$dirfile;
	 $thumbname="";
}
//wrtb("dirfile=".$dirfile.", newdir=".$newdir.", realname=".$realname.", thumbname=".$Thumbname);
//wrtb("dir=".$dir.", file=".$file.", newdir=".$newdir);
if(!empty($newdir)){
	 //$newdir=str_replace("%20"," ",$newdir);
	 if($ThumbExists){
		  $ext=get_ext($realname);
		  $newname=str_replace("_tn.",".",$newdir);
		  $newthumbname=str_replace($ext,"_tn".$ext,$newname);
	 }else{
		  $newname=$newdir;
		  $newthumbname="";
	 }
	 if(_in($newname,"..") || _in($newname,"Content")){
		  errorMsg("Invalid name");
		  $success="no";
	 }
	 if($newname==$realname){
		  errorMsg("New name same as old");
		  $success="no";
	 }
	 if($success==""){
		  $path=getPath($newname);
		  //wrtb("path=".$path);
		  myMkdir($path);
		  //wrtb("typ=".$typ.", filetype=".$filetyp."<br>realname=".$realname." , newname=".$newname);
		  if($act=="rename"){
		  		myRename($realname,$newname);
		  }else{
		   		if($typ=="file")myCopyFile($realname,$newname);
		   		else myCopyDir($realname,$newname);
		  }
		  if($ThumbExists){
			   if($act=="rename")myRename($thumbname,$newthumbname);
			   else{
			    	if($typ=="file")myCopyFile($thumbname,$newthumbname);
			    	//else myCopyDir($realname,$newname);
		   }	}
		  $realname=$newname;
		  $success="yes";
		  $dirfile=$newdir;
		  wrt("<br>");
		  winSuccessMsg("");
		  if($typ=="folder"){ 
			   refreshMenu();
		  }else{
			   refreshMenu();
		  }
		  $dir=getPath($dirfile);
		  $file=getFile($dirfile);
		  exit(0);
}	 }

//wrtb("dir=".$dir.", realname=".$realname.", newname=".$newname.", ThumbNail=".$ThumbNail);
//wrtb("dirfile=".$dirfile);

//------------------ if copied from root folder just do it! -----------------
if($forcecopy){
    //wrtb($realname."<br>".$gID."/".$realname."<br><br>");
    myForceCopyFile($realname,$gID."/".$realname);
	?>
	<center>
	<TABLE cellpadding=5 cellspacing=0>
	<tr><td align=center class=popupMsgtxt><?=$realname;?></td></tr>
	<tr></tr><td align=center class=popupMsgtxt style="color:red;">has been copied to</td></tr>
	<tr><td align=center class=popupMsgtxt><?=$gID."/".$realname;?><br>&nbsp;</td></tr>
	</TABLE>
	
	<?
    winSuccessMsg("");
	exit(0);
}


?>
<center>
<TABLE cellpadding=5 cellspacing=0>
<!-- <tr><td class=txthdr><?=ucfirst($act);?> <?=ucfirst($typ);?>:</td></tr> -->
<? 
//wrtb("act=".$act.", typ=".$typ.", filetype=".$filetyp."<br>realname=".$realname." , newname=".$newname.", thumbname=".$thumbname.", newthumbname=".$newthumbname);
$tmp=_rep($dirfile,$gID."/","");
$tmp=_rep($tmp,"_tn.",".");
$tmp=_rep($tmp,$ext,"");
if($act=="copy"){ 
?>
	<tr>
	<td colspan=2><span class=foldername>&nbsp;<?=$tmp;?></span></td>
	</tr>
	<tr><td colspan=2><span class=txtlabel style='position:relative;top:6px;'>New Name:</span></td></tr>
<? } ?>
<tr> 
<td colpsan=2>
<input name=ext type=hidden value='<?=$ext;?>'>
<input name=newdir type=text value='<?=$tmp;?>' style='width:250px;'>
</td>
</tr>
<tr><td colspan=2 align=center><input class='btn' type=submit value=' <?=ucfirst($act);?> ' <?=$movers;?>></tr></td>
<? if($ThumbExists){ ?>
<tr><td colspan=2 align=center>
<br><img src='<?=$gPath.$thumbname;?>'></td></tr>
<? }else{
     if($filetyp=="image" && get_ext($dirfile)==".svg"){ ?>
      <tr><td colspan=2 align=center>
      <br><img src='<?=$gPath.$realname;?>' style='width:200px;height:150px;'>
      </td></tr>
<? }} ?>
</TABLE>
</center>
<?
}

//========================= NEW FOLDER ==============================
function newDir(){
global $gID,$typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$gPath,$dirlink;
$newdir=rqst('newdir');
$success="";
if(_in($newdir,"..") || _in($newdir,"Content"))$success="no";
if($success=="" && !empty($newdir)){
	//$newdir=str_replace("%20","_",$newdir);
	//$newdir=str_replace(" ","_",$newdir);
	if($dir!="")$path=$dir."/".$newdir;
	else $path=$newdir;
	//wrtb("path=".$path);
	if(mkdir($gPath.$path,$mode=0755))$success="yes";
	else $success="no";
}
$dirdisplay=$dir."/";
if($dir!="")$dirdisplay="/".$dirdisplay;
$dirdisplay=str_replace("//","/",$dirdisplay);
if($success!=""){
	if($success=="yes"){
		  wrt("<br><br>");
		  winSuccessMsg("The folder has been created!");
		  refreshMenu();
		  return;
 	}else errorMsg("There was an unknown error creating the folder");
}
$tmp=_rep($dirdisplay,"/".$gID."/","");
?>
<br><br>
<center>
<TABLE cellpadding=5 cellspacing=0>
<tr><td colspan=2 class=txthdr>New Folder Name:</td></tr>
<tr>
<td align=right><span class=foldername><?=$tmp;?></span></td>
<td><input name=newdir type=text value='' style='width:200px;'></td>
</tr>
<tr><td colspan=2 align=center><input class='btn' type=submit value=' Create New Folder ' <?=$movers;?>></td></tr>
</TABLE>
</center>
<?
}


//========================= DELETE ==============================
function deleteX(){
global $gID,$typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$gPath,$dirlink;
//wrtb("dirfile=".$dirfile.", dir=".$dir.", filetype=".$filetyp.", file=".$file);
if($dirfile=="")return false;
if($filetyp=="video"){
 deleteVideo();
 return;
}
if($filetyp=="link"){
 deleteLink();
 return;
}
if($filetyp=="image"){
 $ThumbExists=true;
 $realname=str_replace("_tn.",".",$dirfile);
}else{
 $ThumbExists=false;
 $realname=$dirfile;
}
$action=myPost('action');
$success="";
if($action=="delete"){
 wrt("<br><br>");
 if($typ=="folder"){
  myDeleteDir($dir,true);
  //reloadMenu($dir,1,1,0);
  refreshMenu();
  winSuccessMsg("The folder ($dir) has been deleted");
  exit();
 }else{
  myDeleteFile($dirfile);
  refreshMenu();
  winSuccessMsg("The file ($dirfile) has been deleted");
  exit();
 }
}
//wrtb("dirfile=".$dirfile);
$tmp=_rep($dirfile,$gID."/","");
$tmp=_rep($tmp,"_tn.",".");
?>
<input type=hidden name=action value='delete'>
<center>
<TABLE border=0 cellpadding=2 cellspacing=0 width=300>
<!-- <tr><td class=txthdr>Delete <?=ucfirst($typ);?>:</td></tr> -->
<tr><td class=foldername>&nbsp;<?=$tmp;?></td></tr>
<? if($typ=="folder"){ ?>
<tr><td>&nbsp;</td></tr><tr><td align=center class=txthdr>WARNING!</td></tr>
<tr><td align=center class=popupMsgtxt>If you proceed the folder and <br>all it's contents will be deleted!<br>&nbsp;</td></tr>
<? } ?>
<tr><td align=center>
<input class='btn' type=submit value=' Delete <?=ucfirst($typ);?>! ' <?=$movers;?>>
<!-- <input class='btn' type=button value=' Cancel ' onclick='parent.closeUtil()' <?=$movers;?>> -->
</td></tr>
<? if(strpos($dirfile,"_tn.")!=false){ ?>
<tr><td align=center><br><IMG src='<?=$gPath.$dirfile;?>'></td></tr>
<? }else{
     //if($typ!="folder")wrt("<tr><td align=left><IFRAME src='".$gPath.$dirfile."' style='width:600;height:500;'></IFRAME></td></tr>");
} ?>
</TABLE>
</center>
<?
}



//========================= DELETE ALL ==============================
function deleteAll($page){
global $dir,$movers,$gPath,$dirlink;
global $fimgfile,$videofile,$linkfile;
$success="";
$dirdisplay=$dir."/";
if($dir!="")$dirdisplay="/".$dirdisplay;
$dirdisplay=str_replace("//","/",$dirdisplay);
switch($page){
 case "deleteallfil": $vtyp="FILES";  break;
 case "deleteallimg": $vtyp="IMAGES"; break;
 case "deleteallvid": $vtyp="VIDEOS"; break;
 case "deletealllnk": $vtyp="LINKS";  break;
 case "deleteallrek": $vtyp="ACTIONS";break;
 case "deleteallpix": $vtyp="PIXIS";  break;
}
$action=myPost('action');
if($action=="delete"){
 switch($page){
  case "deleteallfil": myDeleteFiles($dir);      break;
  case "deleteallimg": myDeleteImages($dir);     myDeleteFile($fimgfile); break;
  case "deleteallvid": myDeleteFile($videofile); break;
  case "deletealllnk": myDeleteFile($linkfile);  break;
  case "deleteallrek": myDeleteActions($dir);    break;
  case "deleteallpix": myDeletePixis($dir);      break;
 }
 winSuccessMsg("The ".$vtyp." have been deleted!");
 refreshMenu();
 exit(0);
}else{
?>
<center>
<TABLE cellpadding=5 cellspacing=0>
<tr><td class=txthdr align=center><big>WARNING!</big><br>All <?=$vtyp;?> in this folder will be deleted!</td></tr>
<tr><td><div style='height:6px;'></div></td></tr>
<tr><td align=center>FOLDER: <span class=foldername><?=$dirdisplay;?></span></td></tr>
<tr><td><div style='height:6px;'></div></td></tr>
<tr><td align=center>
<input type=hidden name=action value="delete">
<input class='btn' type=submit value=' DELETE ALL <?=strtoupper($vtyp);?> ' <?=$movers;?>>
</td></tr>
</TABLE>
</center>
<?
}}




//========================= DELETE VIDEO ==============================
function deleteVideo(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$gPath,$dirlink;
$vidid=$file;
if($vidid=="")return false;
$vidid=_rep($vidid,"@vid@","");
$action=myPost('action');
$success="";
if($action=="delete"){
 myDeleteVideo($vidid);
 reloadMenu($dir,0,1,0);
 winSuccessMsg("The video has been deleted");
 exit();
}
$title=getVideoTitle($vidid);
?>
<input type=hidden name=action value='delete'>
<br>
<center>
<TABLE border=0 cellpadding=5 cellspacing=0>
<tr><td><span class=txthdr>Delete Video:&nbsp;&nbsp;</span><span class=txtlabel><?=$vidid;?></span></td></tr>
<tr><td class=foldername>&nbsp;<?=$title;?></td></tr>
<tr><td align=center><input class='btn' type=submit value=' Delete ' <?=$movers;?>></td></tr>
<tr><td align=center><br><IMG src='http://img.youtube.com/vi/<?=$vidid;?>/default.jpg'></td></tr>
</TABLE>
</center>
<?
}


//=========================== LINKS ===============================


//----------- NEW LINK -------------
function newLink(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$linkfile;
$action=myPost('action');
$name=myPost('fname');
$txt=myPost('itxt');
$newwin=(myPost('inewwin')=="yes")?1:0;
$noreload=myPost('noreload');
if($action=='update'){
 if(empty($txt)){
  errorMsg("please enter a URL");
  $action='';
 }else{
  if(!_in(strtolower($txt),"http://") && !_in(strtolower($txt),"https://"))$txt="http://".$txt;
 }
 if(empty($name)){
  $name=getFile($txt);
  $a=_split($name,".");
  $name=$a[0];
}}
if($action=='update'){
  $name=str_replace(".","",$name);
  //if(!empty($dir))$name=$dir."/".$name;
  $name=str_ireplace("<","",$name);
  $txt=str_ireplace("<","",$txt);
  $linkstxt=myReadFile($dirlink.$linkfile);
  $linkstxt=$linkstxt.",@lnk@".$txt.";".$name.";".$newwin.",";
  $linkstxt=_rep($linkstxt,",,",",");
  myWriteFile($dirlink.$linkfile,$linkstxt);
  wrt("<br>");
  winSuccessMsg("<br><span class=foldername>".$name."</span> has been added ".(($noreload)?"to your home folder":""));
  if(!$noreload)reloadMenu($dir,0,1,0);
  return;
}
if(!empty($dir))$displaydir.=$dir."/";
//if(empty($txt))$txt=_rep(rqst("url"),"http_","http://"); //passed by "Save Copied URL"
if(empty($txt)){
 $txt=rqst("url"); //passed by "Save Copied URL"
 if(substr($txt,0,5)=="http_")$txt="http://".substr($txt,5); //need to replace the first occurrence of "http_" only!
}
$noreload=rqst("noreload");
?>
<input type=hidden value='update' name=action>
<input type=hidden value='<?=$noreload;?>' name=noreload>
<center>
<br>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td width=40>Name:&nbsp;</td><td><input type=text name=fname></td></tr>
<tr height=20><td width=40>URL:&nbsp;</td><td><input id=itxt name="itxt" style="display:block;width:80%;" value="<?=$txt;?>"></td></tr>
<tr height=20><td colspan=2 align=center>Open a New Window?&nbsp;<input type=checkbox name=inewwin id=inewwin value='yes'></td></tr>
<tr height=20><td colspan=2 align=center><input class='btn' type="submit" value="Add" <?=$movers;?> style='width:100px;position:relative;top:10px;'></td></tr>
</TABLE>
</center>
<?
}


//----------- EDIT LINK -------------
function editLink(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$linkfile;
$action=myPost('action');
$name=myPost('fname');
$txt=myPost('itxt');
$newwin=(myPost('inewwin')=="yes")?1:0;
if($action=='update'){
 if(empty($txt)){
  errorMsg("please enter a URL");
 }else{
  if(empty($name)){
   $name=getFile($txt);
   $a=_split($name,".");
   $name=$a[0];
  }
  $oldname=rqst("oldname");
  $oldlink=rqst("oldlink");
  $oldnewwin=rqst("oldnewwin");
  //if(!empty($dir))$name=$dir."/".$name;
  $name=str_ireplace("<","",$name);
  $txt=str_ireplace("<","",$txt);
  $linkstxt=myReadFile($dirlink.$linkfile);
  $linkstxt=_rep($linkstxt,",".$oldlink.";".$oldname.";".$oldnewwin."," , ",@lnk@".$txt.";".$name.";".$newwin.",");
  //wrtb("linkstxt=".$linkstxt);
  //wrtb("");
  //wrtb("old text=".",".$oldlink.";".$oldname.";".$oldnewwin.",");
  //wrtb("");
  //wrtb("new text=".",@lnk@".$txt.";".$name.";".$newwin.",");
  //wrtb("");
  myWriteFile($dirlink.$linkfile,$linkstxt);
  wrt("<br>");
  winSuccessMsg("<br><span class=foldername>".$name."</span> has been updated");
  reloadMenu($dir,0,1,0);
  return;
}}
$lnkid=rqst("target");
$title=getLinkTitle($lnkid);
$lnkid=_rep($lnkid,"@lnk@","@lnk@http://");
$oldnewwin=rqst("oldnewwin");
//wrtb("title=".$title.", oldnewwin=".$oldnewwin);
if(!empty($dir))$displaydir.=$dir."/";
?>
<input type=hidden value='update' name=action>
<input type=hidden name=oldname value='<?=$title;?>'>
<input type=hidden name=oldlink value='<?=$lnkid;?>'>
<input type=hidden name=oldnewwin value='<?=$oldnewwin;?>'>
<center>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td width=40>Name:&nbsp;</td><td><input type=text name=fname value='<?=$title;?>' style="display:block;width:100px;" ></td></tr>
<tr height=20><td width=40>URL:&nbsp;</td><td><input id=itxt name="itxt" style="display:block;width:90%;" value="<?=_rep($lnkid,"@lnk@","");?>"></td></tr>
<tr height=20><td colspan=2 align=center>Open a New Window?&nbsp;<input type=checkbox name=inewwin id=inewwin value='yes' <? if($oldnewwin==1)echo("checked"); ?>></td></tr>
<tr height=20><td colspan=2 align=center><br><input class='btn' type="submit" value="Update" <?=$movers;?> style='width:100px;'></td></tr>
</TABLE>
</center>
<?
}



//----------- DELETE LINK -------------
/*

NOT USED????

function deleteLink(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$gPath,$dirlink,$linkfile;
$lnkid=rqst("target");
if($lnkid=="")return false;
$action=myPost('action');
$success="";
if($action=="delete"){
 myDeleteLink($lnkid);
 reloadMenu($dir,0,1,0);
 winSuccessMsg("The link has been deleted");
 exit();
}
$title=getLinkTitle($lnkid);
?>
<input type=hidden name=action value='delete'>
<input type=hidden name=target value='<?=$lnkid;?>' name=action>
<br>
<center>
<TABLE border=0 cellpadding=5 cellspacing=0>
<tr><td class=foldername>&nbsp;<?=$title;?></td></tr>
<tr><td><span class=txthdr>Delete Link:&nbsp;&nbsp;</span><span class=txtlabel><?=_rep($lnkid,"@lnk@","");?></span></td></tr>
<tr><td align=center><input class='btn' type=submit value=' Delete ' <?=$movers;?>></td></tr>
</TABLE>
</center>
<?
}
*/



//=========================== FIMGS ===============================


//----------- NEW FIMG -------------
function newFimg(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp,$showfimgs;
global $movers,$dirlink,$fimgfile;
$action=myPost('action');
$name=myPost('fname');
$txt=myPost('itxt');
$noreload=myPost('noreload');
if($action=='update'){
 if(empty($txt)){
  errorMsg("Please enter a URL");
  $action='';
 }else{
  $txt=str_ireplace("https","http",$txt);
  if(!_in($txt,"http://"))$txt="http://".$txt;
  if(myFileType($txt)!="image"){
   errorMsg("The URL does not appear to be a valid IMAGE");
   $action='';
}}}
if($action=='update'){
  if(!$showfimgs){
 	parent.openWindow(0,"crop.php?file=".$txt,"70%","90%","140px","30px","Load Image");
	document.window.close();
	return;
  }
  //---- no longer used (we now copy the image to the local folder) but keep the code ----
  if(empty($name)){
   $name=getFile($txt);
   $a=_split($name,".");
   $name=$a[0];
  }
  $name=str_replace(".","",$name);
  $name=str_ireplace("<","",$name);
  $txt=str_ireplace("<","",$txt);
  $linkstxt=myReadFile($dirlink.$fimgfile);
  $linkstxt=$linkstxt.",@fmg@".$txt.";".$name.",";
  $linkstxt=_rep($linkstxt,",,",",");
  myWriteFile($dirlink.$fimgfile,$linkstxt);
  wrt("<br>");
  winSuccessMsg("<br><span class=foldername>".$name."</span> has been added ".(($noreload)?"to your home folder":""));
  if(!$noreload)reloadMenu($dir,0,1,0);
  return;
}
if(empty($name))$name=rand(1000,99999);
if(!empty($dir))$displaydir.=$dir."/";
if(!$txt)$txt=rqst("src");
$noreload=rqst("noreload");
$txt=_rep($txt,"http_","http://");
$txt=_rep($txt,"../",$groot);
$ndisplay=($showfimgs)?"":"NONE";
?>
<input type=hidden value='update' name=action>
<input type=hidden value='<?=$noreload;?>' name=noreload>
<center>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20 style="DISPLAY:<?=$ndisplay;?>;"><td width=40>Name:&nbsp;</td><td><input type=text name=fname value='<?=$name;?>'></td></tr>
<tr height=20><td width=40>URL:&nbsp;</td><td><input id=itxt name="itxt" style="display:block;width:80%;" value="<?=$txt;?>"></td></tr>
<tr height=20><td colspan=2 align=center><input class='btn' type="submit" value="Add" <?=$movers;?> style='width:100px;position:relative;top:10px;'></td></tr>
</TABLE>
</center>
<?
}


//----------- EDIT FIMG -------------
function editFimg(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink,$fimgfile;
$action=myPost('action');
$name=myPost('fname');
$txt=myPost('itxt');
if($action=='update'){
 if(empty($txt)){
  errorMsg("please enter the URL");
 }else{
  if(empty($name)){
   $name=getFile($txt);
   $a=_split($name,".");
   $name=$a[0];
  }
  $oldname=rqst("oldname");
  $oldlink=rqst("oldlink");
  $name=str_ireplace("<","",$name);
  $txt=str_ireplace("<","",$txt);
  $linkstxt=myReadFile($dirlink.$fimgfile);
  $linkstxt=_rep($linkstxt,",".$oldlink.";".$oldname."," , ",@fmg@".$txt.";".$name.",");
  myWriteFile($dirlink.$fimgfile,$linkstxt);
  wrt("<br>");
  winSuccessMsg("<br><span class=foldername>".$name."</span> has been updated");
  reloadMenu($dir,0,1,0);
  return;
}}
$lnkid=rqst("target");
if(!_in($lnkid,"@fmg@"))$lnkid="@fmg@".$lnkid;
$lnkid=_rep($lnkid,"@fmg@","@fmg@http://");
$title=getFimgTitle($lnkid);
if(!empty($dir))$displaydir.=$dir."/";
?>
<input type=hidden value='update' name=action>
<input type=hidden name=oldname value='<?=$title;?>'>
<input type=hidden name=oldlink value='<?=$lnkid;?>'>
<br>
<center>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td width=40>Name:&nbsp;</td><td><input type=text name=fname value='<?=$title;?>' style="display:block;width:100px;" ></td></tr>
<tr height=20><td width=40>URL:&nbsp;</td><td><input id=itxt name="itxt" style="display:block;width:90%;" value="<?=_rep($lnkid,"@fmg@","");?>"></td></tr>
<tr height=20><td colspan=2 align=center><br><input class='btn' type="submit" value="Update" <?=$movers;?> style='width:100px;'></td></tr>
</TABLE>
</center>
<?
}


//----------- EDIT URL -------------
function editUrl(){
global $movers;
$action=myPost('action');
$fil=myPost('fil');
$url=myPost('url');
if($action=='update'){
 $url=str_replace("<","",$url);
 $url=_rep($url,"http://","http_");
 $fil=_rep($fil,"http://","http_");
 winSuccessMsg("<br>The URL has been updated");
 wrt("<script>parent.gWinPage.updateUrl('".$url."','".$fil."');</script>");
 return;
}
$url=rqst("url");
$fil=rqst("fil");
$url=str_replace_once('http_', 'http://', $url);  //replace first occurrence only
$fil=_rep($fil,"http_","http://");
?>
<input type=hidden value='update' name=action>
<center>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td width=40>FILE:&nbsp;</td><td><input id=fil name="fil" style="display:block;width:90%;" value="<?=$fil;?>"></td></tr>
<tr height=20><td width=40>URL:&nbsp;</td><td><input id=url name="url" style="display:block;width:90%;" value="<?=$url;?>"></td></tr>
<tr height=20><td colspan=2 align=center><br><input class='btn' type="submit" value="Update" <?=$movers;?> style='width:100px;'></td></tr>
</TABLE>
</center>
<?
}

//----------- SET HOME PAGE -------------
function setHome(){
global $homepage;
$url=rqst("url");
$url=_rep($url,"http_","http://");
$url=str_replace("<","",$url);
$url=str_replace("'","",$url);
updTemplateQuotesVar("homepage",$url);
winSuccessMsg("<br>(TEMPLATE NOT BEING STORED YET!)<br>The HOME PAGE has been set to:<br><br>".$url);
wrt("<script> parent.gHomePage='".$url."'; parent.frames[0].gHomePage='".$url."'; parent.gWinPage.setCurrentUrlBtns(); </script>");
return;
}


//----------- ADD A VIDEO -------------
function addVideo(){
$action=myPost('action');
$vid=myPost('vid');
$tit=myPost('tit');
$noreload=myPost('noreload');
if($action=='update'){
 if(!_in($vid,"@vid@"))$vid="@vid@".$vid;
 myAddVideo($vid,$tit);
 winSuccessMsg("<br>The Video has been added ".(($noreload)?"to your home folder":""));
 wrt("<br><img src='http://img.youtube.com/vi/"._rep($vid,"@vid@","")."/hqdefault.jpg' style='width:300px;'>");
 if(!$noreload)wrt("<script>try{parent.refresh(1);}catch(e){}</script>");
 return;
}
$vid=rqst("vid");
$tit=rqst("tit");
$noreload=rqst("noreload");
if(!$tit)$tit=getYoutubeTitle(_rep($vid,"@vid@",""));
$tit=_rep($tit,"'","");
?>
<input type=hidden value='update' name=action>
<input type=hidden value='<?=$vid;?>' name=vid>
<input type=hidden value='<?=$tit;?>' name=tit>
<input type=hidden value='<?=$noreload;?>' name=noreload>
<center>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td align=center><?=$tit;?></td></tr>
<tr height=20><td align=center><img src='http://img.youtube.com/vi/<?=_rep($vid,"@vid@","");?>/hqdefault.jpg'></td></tr>
<tr height=20><td align=center><br><input class='btn' type="submit" value="Save" <?=$movers;?> style='width:100px;'></td></tr>
</TABLE>
</center>
<?
}



//----------- ADD A FILE TO ANOTHER FOLDER -------------
function addFile(){
global $gRoot,$gPath,$dir,$dirlink;
$action=myPost('action');
$noreload=myPost('noreload');
if($action=='update'){
 $oldfile=myPost('oldfile');
 $newfile=myPost('newfile');
 myCopyFile($oldfile,$newfile);
 winSuccessMsg("<br>The File has been added ".(($noreload)?"to your home folder":""));
 if(!$noreload)wrt("<script>try{parent.refresh(1);}catch(e){}</script>");
 return;
}
$oldfile=rqst("oldfile");
$noreload=rqst("noreload");
$newfile=$gPath.$dirlink.getFile($oldfile);
$oldf=_rep($oldfile,$gPath,$gRoot);
$newf=_rep($newfile,$gPath,$gRoot);
?>
<input type=hidden value='update' name=action>
<input type=hidden value='<?=$oldfile;?>' name=oldfile>
<input type=hidden value='<?=$newfile;?>' name=newfile>
<input type=hidden value='<?=$noreload;?>' name=noreload>
<center>
<TABLE width="90%" border="0" cellpadding="5" cellspacing="0">
<tr height=20><td align=right>Existing File:&nbsp;</td><td><?=$oldf;?></td></tr>
<tr height=20><td align=right>New File:&nbsp;</td><td><?=$newf;?></td></tr>
<tr height=20><td align=center colspan=2><br><input class='btn' type="submit" value="Save New File" <?=$movers;?> style='width:140px;'></td></tr>
</TABLE>
</center>
<?
}



?>
