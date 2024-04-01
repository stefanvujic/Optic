<?
include_once "_inc_global.php";
include_once "_inc_header.php";

//-------load the correct function--------
$page=rqst("page");
$page=strtolower($page);


//----------- NB: WE NEED TO ADD SECURITY CHECKS TO ALL FUNCTIONS! -------------

//--- OLD WAY: if(!$gLoggedIn && $page!="logout")$page="login";  //all functions require login


if(strpos($page,"file")){
 $act=str_replace("file","",$page);
 $typ="file";
 $file=rqst("file");
 $filetyp=myFileType($file);
}else{
 $act=str_replace("dir","",$page);
 $typ="folder";
 $file="";
 $filetyp="";
}

if($file!="")$dirfile=$dirlink.$file;
else $dirfile=$dir;

wrt("<!-- typ=".$typ.", filetype=".$filetyp.", dir=".$dir.", file=".$file.", dirfile=".$dirfile.", page=".$page.", act=".$act." -->");;

?>
<SCRIPT>
var typ="<?=$typ;?>";
</script>

<STYLE>
.foldername{padding-left:2px;padding-right:2px;color:<?=$lkcolor?>;}
.outerspan{text-align:center;padding:5px;}
.ddown{}
</STYLE>
<meta charset="utf-8"/>
<!-- <link rel=stylesheet href="codemirror/doc/docs.css"> -->
<link rel="stylesheet" href="codemirror/lib/codemirror.css">
<link rel="stylesheet" href="codemirror/addon/dialog/dialog.css">
<script src="codemirror/lib/codemirror.js"></script>
<script src="codemirror/mode/xml/xml.js"></script>
<script src="codemirror/addon/dialog/dialog.js"></script>
<script src="codemirror/addon/search/searchcursor.js"></script>
<script src="codemirror/addon/search/search.js"></script>
<link rel="stylesheet" href="codemirror/theme/solarized.css">
</HEAD>
<BODY style='text-align:center;'>
<DIV class=outerspan>
<FORM name="f1" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
<?
editFile(); 
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


//========================= EDIT FILE ==============================
function editFile(){
global $typ,$dir,$file,$dirfile,$page,$act,$filetyp;
global $movers,$dirlink;
$action=$_POST['action'];
if($action=='update'){
 $txt=$_POST['itxt'];
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
<TABLE width="100%" height="96%" border="0" cellpadding="5" cellspacing="0">
<!-- <tr height=20><td class=foldername>&nbsp;/<?=$dirfile;?></span></td></tr> -->
<tr height=20><td>
<table cellspacing=0 cellpadding=0 style="width:100%;">
<tr>
<td align=center>
 <div class=corners6 style='border:solid 0px gray;padding:2px;background:;'>
	&nbsp;<input class=btn type="button" value="Find" onclick='CodeMirror.commands["find"](editor) ' <?=$movers;?> style='width:80;'>
	&nbsp;<input class=btn type="button" value="Find Next" onclick='CodeMirror.commands["findNext"](editor) ' <?=$movers;?> style='width:80;'>
	&nbsp;<input class=btn type="button" value="Replace" onclick='CodeMirror.commands["replace"](editor) ' <?=$movers;?> style='width:80;'>
	&nbsp;<input class=btn type="button" value="Replace All" onclick='CodeMirror.commands["replaceAll"](editor) ' <?=$movers;?> style='width:80;'>
	&nbsp;<input class=btn type="button" value="Reset" onclick='CodeMirror.commands["clearSearch"](editor) ' <?=$movers;?> style='width:80;'>
	&nbsp;<input id=pbtn class=btn type="button" value="Preview" onclick='preview()' <?=$movers;?> style='width:80;'>
	&nbsp;<input class='btn' type="submit" value=" UPDATE " <?=$movers;?> style='width:80;font-weight:bold;'>
 </div>
</td>
</tr>
</table>
</td></tr>
<tr><td>
<textarea  id="itxt" name="itxt" width="100%" height="100%" style="border:solid 1px black; display:block;width:100%;height:100%" wrap=hard><?=$txt;?></textarea>
</td></tr> 
</TABLE> 
</center>

<iframe id=ifrm name=ifrm src='' width="96%" height="90%" style=" z-index:9999; background:white; position:absolute; top:70px; width:99%; height:90%; border:solid 2px black; display:none; "></iframe>

<script>
var editor = CodeMirror.fromTextArea(document.getElementById("itxt"), {mode: "text/html", lineNumbers: true, lineWrapping:true });
//editor.setOption("theme", "neat");
editor.setOption("theme", "solarized light");

</script>
<?
}

//---------- preview script (see above) ---------
function previewScript($typ){
?>
<script>
function preview(){
var ox=_obj("itxt"),od=_obj("ifrm"),pb=_obj("pbtn");
if(od.style.display=="block"){
 //ox.style.display="block";
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
 //ox.style.display="none";
 od.style.display="block";
 pb.value="Close";
}}
</script>
<?
}
?>