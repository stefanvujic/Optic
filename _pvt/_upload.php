<?
include_once "_inc_global.php";
include_once "_inc_header.php";


//-------- security --------
if(!$gAdmin && ($optupload2=="no" && $optupload2=="no"))exit("login required");
if(!$gLoggedIn && $optupload2=="no")exit("login required");

//---------allowed extensions.
$allowed_img_ext=',.gif,.jpg,.jpeg,.png,.GIF,.JPG,.JPEG,.PNG,';
$allowed_txt_ext=',.htm,.html,.txt,.js,.css,.htm,.html,.txt,.js,.css,.HTM,.HTML,.TXT,.JS,.CSS,.HTM,.HTML,.TXT,.JS,.CSS,';

$tmpdir=$gPath.$dirlink;

function getUrlTyp($url){
global $allowed_img_ext;
global $allowed_txt_ext;
$name=basename($url);
if(_in($name,"?"))$name=substr($name,0,_ix($name,"?"));
$ext=get_ext($name);
if($ext=="")return "txt";
if(ext_allowed($ext,$allowed_img_ext))return "img";
if(ext_allowed($ext,$allowed_txt_ext))return "txt";
return "uk";
}

function ext_allowed($ext,$exts){
if(!strstr($ext,'.'))return false;
if(_in($exts,",".$ext.","))return true;
return false;}


function ddsel($s,$d){
echo("value=\"$s\" ");
if("$s/"==$d)echo(" selected");}

function getBottomDir($d){
$tmp=$d."/";
$tmp=str_replace("//","/",$tmp);
$tmp=split("/",$tmp);
if(count($tmp)>1)$tmp=$tmp[count($tmp)-2];
else $tmp="";
return $tmp;}

function fetchUrl($u){
echo("<!-- ");
if(!$hostfile = fopen($u,'r')){echo(" -->");return "";}
echo(" -->");
$hostfile = fopen($u,'r');
$txt="";
while(!feof($hostfile))$txt.=fread($hostfile, 8192);
fclose($hostfile);
return $txt;}



?>
<link rel="stylesheet" type="text/css" href="_pvt_css/picup.css?08142012"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="viewport" content="width=device-width initial-scale=1.0" />
<style type="text/css">
body{margin-left:50px;margin-top:20px; }
.dirtxt{font-size:18;}
.outerdiv{width:500px;text-align:center;}
.innerdiv{width:500px;padding:15px;text-align:center;}
.output{width:600px;text-align:left;padding:5px;margin-top:2px;}
.hide{display:none;}
.show{display:inline;}
<?
if($gIE)echo(".pgif{display:inline;}");else echo(".pgif{display:none;}");
?>
</style>

<script src="_pvt_js/picup.js"></script>


<!-- temp! just to see if we can get picup it working... -->
<script src="http://picupapp.com/prototype.js"></script>
<script>
    function checkFiles() {
        var fileElement = document.getElementById("file_upload_input");
        var fileExtension = "";
        if (fileElement.value.lastIndexOf(".") > 0) {
            fileExtension = fileElement.value.substring(fileElement.value.lastIndexOf(".") + 1, fileElement.value.length);
        }
        if (fileExtension.toLowerCase() == "jpg" || fileExtension.toLowerCase() == "png" || fileExtension.toLowerCase() == "gif") {
            return true;
        }
        else {
            alert("You must select  image files (jpg,png,gif) for upload");
            return false;
        }
    }
</script>


</head><body>

<!-- absolutes -->
<div id=iBusy style="background:white;border:solid 1px gray;position:absolute;top:20px;left:20px;display:none;padding:5px;text-align:center;">
<img src='_pvt_images/busy.gif'>
<br>
<font face=helvetica color=black>Uploading...</font>
</div>
<span class="txthdr"><b>UPLOAD:</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=dirtxt><?=$dirdisplay;?></span>
<div id=iMsg style="text-align:center;"></div>


<!------------------ for picup ------------------->
<div id="output"></div>
<div id="file_input_caption" class="warning"></div>


<DIV class=outerdiv style='width:600;'>
	<DIV id=resultsDiv>
<?
//----------------- process uploads -------------------------

$anyfileloaded=false;
$newfileloaded=false;
if(isset($_POST['Submit'])){
 echo "<br><center>";
 //---process the local files----
 for($i=0;$i<count($_FILES['file'])+1;$i++){
  $name=basename($_FILES['file']['name'][$i]);
  if(!empty($name)){
   $msg="wrong type or size";
   $newfileloaded=false;
   $size=$_FILES['file']['size'][$i];
   
   $ext=get_ext($name);
   //$root=get_root($name);
   $typ="";
   if(ext_allowed($ext,$allowed_img_ext)){
    $typ="img";
    //$newname=getNewName($tmpuser,$name);
    //$thumbname=getThumbName($tmpuser,$root,$ext);
    $newname=$name;
    $thumbname=thumbName($name);

	//echo(" size=".$size);
    $rslt=@move_uploaded_file($_FILES['file']['tmp_name'][$i],"$tmpdir"."$newname");
    $imginfo=getimagesize("$tmpdir"."$newname");   
    //echo(" size0=".$imginfo[0]);
    //echo(" size1=".$imginfo[1]);
    $w=$imginfo[0];
    $h=$imginfo[1];
    if(($w+$h)>10000){
		$w=($imginfo[0]/$imginfo[1])*5000;
		$h=($imginfo[1]/$imginfo[0])*5000;
		resize($dirlink.$newname,$dirlink.$newname,$w,$h,$ext);
	}
    resize($dirlink.$newname,$dirlink.$thumbname,100,80,$ext);
     
    ?><div class="output"><table width=600 border=0><tr><td valign=top align=left>
    Local File <?=$i+1?>: Success!
    <a href="<?="$tmpdir".htmlspecialchars($newname);?>" target=_blank><?=$newname;?></a></td>
    <td align=right><img src='<?=$tmpdir.$thumbname;?>'></td>
    </tr></table></div><?
    $newfileloaded=true;
    $anyfileloaded=true;
   }
   //if uploads are allowed when not logged in then we only permit images to be uploaded
   if(ext_allowed($ext,$allowed_txt_ext)){
    if(!$gLoggedIn || "TURNOFF"=="TURNOFF"){
     $msg="only images allowed";
    }else{
     $typ="txt";
     $newname=getNewName($tmpuser,$name);
     $rslt=@move_uploaded_file($_FILES['file']['tmp_name'][$i],"$tmpdir"."$newname");
     ?><div class="output"><table width=600 border=0><tr><td valign=top align=left>
     <?=$i+1?> Success
     <a href="<?="$tmpdir".htmlspecialchars($newname);?>" target=_blank><?="$tmpdir"."$newname";?></a></td>
     <td align=right></td>
     </tr></table></div><?
     $newfileloaded=true;
     $anyfileloaded=true;
    }
   }
   if(!$newfileloaded){
    ?>
    <div class="output">File <?=$i+1?>: Failed!&nbsp;&nbsp; - <?=$msg;?> (File: <?=$name;?>, Type: <?=$_FILES['file']['type'][$i];?>, Size: <?=$_FILES['file']['size'][$i];?>).</div>
    <?
   }
  }
 }
}

 //---process the URLS----
if(isset($_POST['Submit']) || $_GET['ios']=="1"){
 for($i=1;$i<7;$i++){
  $url=$_POST['url'.$i];
  if(empty($url)){
   $url=$_GET['url'.$i];
   if(!empty($url) && $_GET['ios']=="1"){
    $url="http://i.imgur.com/".$url;
  }}
  if(!empty($url)){
   $url=urldecode($url);
   $newfileloaded=false;
   $typ=getUrlTyp($url);
   $name=basename($url);
   if(_in($name,"?"))$name=substr($name,0,_ix($name,"?"));
   $ext=get_ext($name);
   if($ext=="")$ext=".txt";
   //$root=getName($name);
   if($typ=="img"){
    //---images----
    //$newname=getNewName($tmpuser,$name);
    //$thumbname=getThumbName($tmpuser,$root,$ext);
    $newname=$name;
    $thumbname=thumbName($name);
    //wrtb("newname=".$newname.", thumbname=".$thumbname.", ext=".$ext);
    //wrtb("tmpuser=".$tmpuser.", typ=".$typ.", ext=".$ext.", root=".$root.", name=$name, tmpdir=$tmpdir, newname=$newname");;
    if(@resize($url,$dirlink.$newname,0,0,$ext)){
     $imginfo=getimagesize($tmpdir.$newname);
     if(($imginfo[0]+$imginfo[1])>2000)resize($dirlink.$newname,$dirlink.$newname,800,800,$ext);
     resize($dirlink.$newname,$dirlink.$thumbname,100,80,$ext);
     ?><div class="output"><table width=600 border=0><tr><td valign=top align=left>
     Remote File <?=$i?>: Success!
     <a href="<?=$tmpdir.htmlspecialchars($newname);?>" target=_blank><?=$newname;?></a></td>
     <td align=right><img src='<?=$tmpdir.$thumbname;?>'></td>
     </tr></table></div><?
     $newfileloaded=true;
     $anyfileloaded=true;
    }
   }
   
   if($typ=="txt_TURNOFF"){
    //---text files----
    $newname=getNewName($tmpuser,$name);
    $txt=fetchUrl($url);
    if(!empty($txt)){
     myWriteFile($dirlink.$newname,$txt);
     ?><div class="output"><table width=600 border=0><tr><td valign=top align=left>
     Remote File <?=$i?>: Success!
     <a href="<?=$tmpdir.htmlspecialchars($newname);?>" target=_blank><?=$tmpdir.$newname;?></a></td>
     <td align=right></td>
     </tr></table></div><?
     $newfileloaded=true;
     $anyfileloaded=true;
    }
   }
   if(!$newfileloaded){
    ?>
    <div class="output">Remote File <?=$i?>: Failed!<br>(<?=$url;?>)</div>
    <?
   }
  }
 }
}
if($anyfileloaded)reloadMenu($dir,0,1,0);

?>
	</DIV>  <!-- end resultsDiv -->
<br><center>
<DIV class=innerdiv style='width:600px;'>
<FORM name="uploadfrom" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data"  onsubmit="showbusy(); _obj('resultsDiv').style.display='none'; return true;"> 
<input type=hidden name=dir value='<?=$dir;?>'>
<TABLE cellpadding=10 cellspacing=4 border=0 style='padding:10px;border:solid 0px black;border-collapse:collapse;width:530px;'>
<tr style='border-bottom:solid 0px black;'><td class=hdr>Select up to 6 images</td></tr>
<!-- Note: min and max parms for Opera only -->
<tr><td height=4><div style='height:34px;overflow:hidden;'></div></td>
<tr><td>
&nbsp;<input type="file" name="file[]" id="file_upload_input" multiple="multiple" min="1" max="10" onclick='clrmsgs();return true;' style='width:500px;'>
<br><br><br>&nbsp;<input class='btn' type="submit" name="Submit" value="Submit" style='width:100px;'>
</td></tr>
<tr><td><br><br><br>&nbsp;</td></tr>
<!--
<tr style='border-bottom:solid 0px black;'><td class=hdr>REMOTE FILES&nbsp;&nbsp;&nbsp;<font size=3>(http://..)</font></td></tr>
<tr><td height=4><div style='height:4px;overflow:hidden;'></div></td>
<tr><td>1:&nbsp;<input type="text" name="url1" id="url1" onclick='clrmsgs();return true;' style='width:500px;'></td></tr>
<tr><td>2:&nbsp;<input type="text" name="url2" id="url2" onclick='clrmsgs();return true;' style='width:500px;'></td></tr>
<tr><td>3:&nbsp;<input type="text" name="url3" id="url3" onclick='clrmsgs();return true;' style='width:500px;'></td></tr>
<tr><td>4:&nbsp;<input type="text" name="url4" id="url4" onclick='clrmsgs();return true;' style='width:500px;'></td></tr>
<tr><td>5:&nbsp;<input type="text" name="url5" id="url5" onclick='clrmsgs();return true;' style='width:500px;'></td></tr>
<tr><td>6:&nbsp;<input type="text" name="url6" id="url6" onclick='clrmsgs();return true;' style='width:500px;'></td></tr>
<tr><td>
&nbsp;&nbsp;&nbsp;&nbsp;<input class='btn' type="submit" name="Submit" value="Submit" onclick='showbusy();return true;' style='width:100px;'>
</td></tr>
-->
</TABLE>
</FORM>
</DIV>

<script>
function clrmsgs(){_obj('iMsg').style.display='none';}
function showbusy(){_obj('iBusy').style.display='block';}
function busy(tru){_obj('iBusy').style.display=(tru)?'block':'hidden'; return tru;}
function _paste(xarea){
var o=_obj(xarea);
o.focus();
var c=o.createTextRange();
c.execCommand("Paste");
}
</script>



<script>


function displayResults(paramHash){
return;
var fileDict = {}
var numFiles = 0;
for(var keyName in paramHash){
 // file[i][paramName]
 try{
  var fileIndex = keyName.match(/file\[(\d+)\]/)[1];
  var paramName = keyName.match(/file\[\d+\]\[([^\]]+)\]/)[1];
  var file = fileDict[fileIndex];
  if(!file){
   file = {};
   fileDict[fileIndex] = file;
   numFiles++;
  }
  file[paramName] = paramHash[keyName];
 }catch(err){alert("ERROR: Couldn't parse the param key "+keyName);}
}
var output="";
for(var i=0;i<numFiles;i++){
 var file = fileDict[i+""];
 output+='<h4>File '+i+'</h4>';
 output+='<div><a href="'+Picup2.urlForOptions('view', {'picID' : file.picID})+'">View in Picup</a></div>';
 output+='<pre class="output">';
 var thumbnailData = file.thumbnailDataURL;
 if(thumbnailData){
  output+='<div class="thumb_preview"><img src="'+picupUnescape(thumbnailData)+'"/></div>';
 }
 output+= '<div class="json">'+$H(file).toJSON().replace(/\"\,/gmi, '",\n')+'</div>';
 output+='</pre>';
}
$('output').innerHTML = output;
var isIPad = navigator.userAgent.indexOf('iPad') != -1;
var isIPhone = navigator.userAgent.indexOf('iPhone') != -1;
if(isIPhone && !isIPad){
 $('file_input_caption').innerHTML = "<p><strong>IMPORTANT:</strong> Close the callback window before making another selection.</p>";
}else{
 $('file_input_caption').innerHTML = "";
}
window.scrollTo(0,$('output').positionedOffset().top);
}


function picupUnescape(v){
if(decodeURI)return decodeURI(v);
return unescape(v);
}


function picupEscape(v){
if(encodeURI)return encodeURI(v);
return escape(v);
}


function escapeValue(value, paramName){
// NOTE: We want to make sure we don't escape already escaped values
var components = value.split('%');
var escapedComponents = [];
for(var i=0;i<components.length;i++){
 var token = components[i];
 if(token.indexOf("://") == -1 &&
    token.indexOf("=") == -1 &&
    token.indexOf("&") == -1 ){
  escapedComponents.push(picupEscape(token));
 }else{
  // This has URI components. Fully escape
  escapedComponents.push(escape(token));
}}
return escapedComponents.join("%");
}




</script>

<!-- <IMG id=iWindowClose onclick='parent.closeUtil()' src='_pvt_images/delete.png' style=' z-index:9991; display:block;position:absolute;left:0px;top:0px;width:20px;cursor:pointer;'> -->

</body></html>
