<?
/* Copyright ©, 2005, Walter Long */

// if ip is not in the list, then die
if( $_SERVER['REMOTE_ADDR'] !== '89.216.112.122') {
   echo 'access denied, ip restriction';
   die();
}

// if($ip) {

// }

$domain=$_SERVER["SERVER_NAME"];
$subdomains=getSubdomains($domain);
$dir=$_GET['dir'];
$pixi=$_GET['pixi'];
$dirlink=getDir($dir);
if(!empty($dirlink))$dirlink=$dirlink."/";

//--- copied from inc_global -----
$gID        =$_SESSION['id'];
$gLoginPath =$_SESSION['loginpath'];
$gLoginDir  ="";
$gLoginDirLink="";
if(!$gID){
 $gID=$_COOKIE['id'];
 $gLoginPath=$_COOKIE['path'];
 $_SESSION['id']=$gID;
 $_SESSION['loginpath']=$gLoginPath;
}
if($gID){
 //this code is also in Inc_global and toolbar.php in getgDir()
 $gLoginDirLink=_rep($gLoginPath,"../","");  //the 'active' logged in dir
 if($dir && (_ix($dirlink,$gLoginDirLink)==0 || _ix($gLoginDirLink,$dirlink)==0)&& $dirlink!=$gLoginDirLink)$gLoginDirLink=$dirlink;
 $gLoginDir=$gLoginDirLink;
 $gLoginDir=substr($gLoginDir,0,strlen($gLoginDir)-1); //strip off trailing "/"
}
//--- auto goto login dir ---
if($gID && !$dir){
 $dir=$gLoginDir;
 $dirlink=getDir($dir);
 if(!empty($dirlink))$dirlink=$dirlink."/";
}
//exit("id=".$gID.", path=".$gPath.", dir=".$dir.", gLoginPath=".$gLoginPath.", dirlink=".$dirlink.", logindir=".$gLoginDir);


//perhaps we should just get the whole query string and pass it on?  //????
$parms="?firsttime=1&dir=".$dir."&defpixi=".$pixi."&subdomains=".$subdomains;
$u="_pvt/_toolbar.php".$parms;


?>
<!DOCTYPE html>
<HTML lang="en">
<head>
<title>Optic!</title>
<meta name="viewport" content="width=980, initial-scale=1.0" />
<script> var gFrameTop=1; </script>
</head>
<BODY topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<IFRAME src='<?=$u;?>' frameborder='0' border='0' style='position:absolute;top:0px;left:0px;width:100%;height:100%;border:solid 0px #fff;'></IFRAME>
</BODY>
</HTML>

<?



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



function myReadFile($f){
if(!file_exists($f))return "";
$fh=fopen($f,'r');
$theData=fread($fh,10000);
fclose($fh);
return $theData;}


function myWriteFile($f,$txt){
$fh=fopen($f,'w');
fwrite($fh,$txt);
fclose($fh);
}


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




function _in($txt,$v){$r=strpos($txt,$v);if($r===false)return 0;return 1;}
function _split($txt,$v){return explode($v,$txt);}
function wrtb($s){echo($s."<br>");}
function _rep($txt,$v1,$v2){return str_replace($v1,$v2,$txt);}



?>







