<?
/* Copyright ©, 2005, Walter Long */

include_once "_inc_global.php";

$dir=rqst('dir');
$page=rqst('page');
$action=rqst('action');
$block=rqst('block');
if($block=="")$block=$_SESSION["options_block"];
else $_SESSION["options_block"]=$block;



//--- APPLY DEFAULT or SAVE TEMPLATE ---
if($action=='apply' || $action=='savetemplate'){
 $allvars="";
 chkOption('delay',$delay,0);
 chkOption('ssusepixi',$ssusepixi,1);
 chkOption('randomize',$randomize,1);
 chkOption('fgcolor',$fgcolor,0);
 chkOption('lkcolor',$lkcolor,0);
 chkOption('selcolor',$selcolor,0);
 chkOption('hicolor',$hicolor,0);
 chkOption('bgcolor',$bgcolor,0);
 chkOption('toolbarbgcolor',$toolbarbgcolor,0);
 chkOption('desktopbgcolor',$desktopbgcolor,0);
 chkOption('scrollbgcolor',$scrollbgcolor,0);
 chkOption('scrollfgcolor',$scrollfgcolor,0);
 chkOption('popupbgcolor',$popupbgcolor,0);
 chkOption('popupfgcolor',$popupfgcolor,0);
 chkOption('popupbdrcolor',$popupbdrcolor,0);
 chkOption('popupbdrwidth',$popupbdrwidth,0);
 chkOption('titlecolor',$titlecolor,0);
 chkOption('controls',$controls,1);
 chkOption('chromeless',$chromeless,1);
 chkOption('fadewinbtns',$fadewinbtns,1);
 chkOption('useaviary',$useaviary,1);
 chkOption('ssdropdown',$ssdropdown,1);
 chkOption('ssfullsize',$ssfullsize,1);
 chkOption('ssnaturalsize',$ssnaturalsize,1);
 chkOption('ssthumbs',$ssthumbs,1);
// chkOption('ssthumbsalign',$ssthumbsalign,0);
 chkOption('bordercolor',$bordercolor,0);
 chkOption('thumbbdrcolor',$thumbbdrcolor,0);
 chkOption('ssthumbbdrcolor',$ssthumbbdrcolor,0);
 chkOption('ssborder',$ssborder,0);
 //chkOption('ssbgimage',$ssbgimage,0);
 //chkOption('desktop',$desktop,0);
 //chkOption('ssframeimage',$ssframeimage,0);
 //chkOption('ssframeopacity',$ssframeopacity,0);
 //chkOption('ssvidframeimage',$ssvidframeimage,0);
 //chkOption('ssvidframeopacity',$ssvidframeopacity,0);
 chkOption('ssbgwidth',$ssbgwidth,0);
 chkOption('ssbgcolor',$ssbgcolor,0);
 chkOption('ssfgcolor',$ssfgcolor,0);
 chkOption('sshicolor',$sshicolor,0);
 //chkOption('ssbgopacity',$ssbgopacity,0);
 //chkOption('ssbgopacitycolor',$ssbgopacitycolor,0);
 chkOption('ssbtnbordercolor',$ssbtnbordercolor,0);
 chkOption('ssbtnbgcolor',$ssbtnbgcolor,0);
 chkOption('ssbtnhicolor',$ssbtnhicolor,0);
 chkOption('ssbtncolor',$ssbtncolor,0);
 chkOption('btnbordercolor',$btnbordercolor,0);
 chkOption('btnbgcolor',$btnbgcolor,0);
 chkOption('btnhicolor',$btnhicolor,0);
 chkOption('btncolor',$btncolor,0);
 chkOption('fade',$fade,1);
 chkOption('fadetoolbar',$fadetoolbar,1);
 chkOption('width',$width,0);
 chkOption('height',$height,0);
 chkOption('showthumbs',$showthumbs,1);
 chkOption('ssshowthumbbdrs',$ssshowthumbbdrs,1);
 chkOption('homepage',$homepage,0);
 //chkOption('cascade',$cascade,1);
 chkOption('optonlyimages',$optonlyimages,0);
 chkOption('optshowsstitles',$optshowsstitles,1);
 //chkOption('optdropdown1',$optdropdown1,1);
 chkOption('optdropdown2',$optdropdown2,1);
 chkOption('showexpandbtns',$showexpandbtns,1);
 chkOption('optmenu1',$optmenu1,1);
 chkOption('optmenu2',$optmenu2,1);
 chkOption('opttoolbar1',$opttoolbar1,1);
 chkOption('opttoolbar2',$opttoolbar2,1);
 chkOption('optshowfolders1',$optshowfolders1,1);
 chkOption('optshowfolders2',$optshowfolders2,1);
 chkOption('optshowfiles1',$optshowfiles1,1);
 chkOption('optshowfiles2',$optshowfiles2,1);
 chkOption('optshowpics1',$optshowpics1,1);
 chkOption('optshowpics2',$optshowpics2,1);
 chkOption('optshowvids1',$optshowvids1,1);
 chkOption('optshowvids2',$optshowvids2,1);
 chkOption('optshowlinks1',$optshowlinks1,1);
 chkOption('optshowlinks2',$optshowlinks2,1);
 chkOption('optupload1',$optupload1,1);
 chkOption('optupload2',$optupload2,1);
 chkOption('optnewfolder1',$optnewfolder1,1);
 chkOption('optnewfolder2',$optnewfolder2,1);
 chkOption('optnewfile1',$optnewfile1,1);
 chkOption('optnewfile2',$optnewfile2,1);
 chkOption('optnewvid1',$optnewvid1,1);
 chkOption('optnewvid2',$optnewvid2,1);
 chkOption('optnewlink1',$optnewlink1,1);
 chkOption('optnewlink2',$optnewlink2,1);
 chkOption('optnewfimg1',$optnewfimg1,1);
 chkOption('optnewfimg2',$optnewfimg2,1);
 chkOption('optnewsvg1',$optnewsvg1,1);
 chkOption('optnewsvg2',$optnewsvg2,1);
 chkOption('optorgfolders1',$optorgfolders1,1);
 chkOption('optorgfolders2',$optorgfolders2,1);
 chkOption('optupdsettings1',$optupdsettings1,1);
 chkOption('optupdsettings2',$optupdsettings2,0);
 chkOption('ssitems',$ssitems,0);
 chkOption('toolbaralign',$toolbaralign,0);
 //chkOption('usedropdowns',$usedropdowns,1);
 chkOption('mouseovermenus',$mouseovermenus,0);
 chkOption('showlinkicons',$showlinkicons,1);
 chkOption('fontfamily',$fontfamily,0);
 chkOption('fontsize',$fontsize,0);


 chkOption('showrefreshbtn',$showrefreshbtn,0);
 chkOption('showpicsbtn',$showpicsbtn,0);
 chkOption('showhomebtn',$showhomebtn,0);
 chkOption('showexpandbtn',$showexpandbtn,0);
 chkOption('showaddbtn',$showaddbtn,0);
 chkOption('showtoolsbtn',$showtoolsbtn,0);
 chkOption('showsearchbtn',$showsearchbtn,0);
 chkOption('showsearchmenu',$showsearchmenu,0);
 chkOption('showfindpicsmenu',$showfindpicsmenu,0);
 chkOption('showbrowsebtn',$showbrowsebtn,0);
 chkOption('showuploadbtn',$showuploadbtn,0);
 chkOption('showyoutubebtn',$showyoutubebtn,0);
 chkOption('showclipboardbtn',$showclipboardbtn,0);
 chkOption('showcopybtn',$showcopybtn,0);
 chkOption('showcamerabtn',$showcamerabtn,0);
 //chkOption('showeditbtn',$showeditbtn,0);
 chkOption('showpicxiebtn',$showpicxiebtn,0);
 chkOption('showdonatebtn',$showdonatebtn,0);
 chkOption('loginbtn',$loginbtn,0);


 //--- UPDATE AND APPLY ---
 if($action=='apply'){
	  eval($allvars);
	  //myWriteFile($gLoginPath.$templatefile,$allvars);
	  $fh=fopen($gLoginPath.$templatefile,'w');
	  fwrite($fh,$allvars);
	  fclose($fh);
	  reloadMenu($dir,0,1,-1);
	  echo("<script>");
	  echo("try{parent._obj('iToolbar').style.background='$toolbarbgcolor';}catch(e){}");
	  echo("try{parent._obj('iDesktopTable').style.background='$desktopbgcolor';}catch(e){}");
	  echo("try{parent.updPopupColors('$popupfgcolor','$popupbgcolor','$popupbdrcolor','$popupbdrwidth');}catch(e){}");
	  echo("try{parent.gHomePage='$homepage';}catch(e){}");
	  echo("</script>");
	  if($page=="slideshow")reloadSlideshow();
 }

 //--- SAVE NEW TEMPLATE ---
 /*
 if($action=='savetemplate'){
  $template=rqst("itemplate");
  $tmp=_split($template,".");
  $template=$tmp[0];
  if($template!=""){
   $template.=".txt";
   myWriteFile($gExePath."templates/_template.txt",$template);
   myWriteFile($gExePath."templates/".$template,$allvars);
   $_SESSION["skin"]=$template;
   wrt("<script>parent.setSkin('".$template."');</script>");
   eval($allvars);
   reloadMenu($dir,0,1,-1);
   if($page=="slideshow")reloadSlideshow();
  }else msg("Invalid template name");
 }
 */
 //if($action=='apply'){
 // echo("<script>");
 // echo("try{parent.closeUtil();}catch(e){}");
 // echo("</script>");
 //}
}


//--- fix vars ---
if($ssborder=="no")$ssborder="";
if($height=="no")$height="";
if($width=="no")$width="";
if($ssbgwidth=="")$ssbgwidth="0";
if($ssbgcolor=="")$ssbgcolor==$bgcolor;
if($ssfgcolor=="")$ssfgcolor==$fgcolor;
//if($ssbgopacity=="100")$ssbgopacity="";
//if($ssframeopacity=="100")$ssframeopacity="";
if($ssbtncolor=="")$ssbtncolor=$btncolor;
if($ssbtnhicolor=="")$ssbtnhicolor=$btnhicolor;
if($ssbtnbgcolor=="")$ssbtnbgcolor=$btnbgcolor;
if($ssbtnbordercolor=="")$ssbtnbordercolor=$btnbordercolor;

//if($ssframeimage)$frameimage=$ssframeimage;
//else if($ssvidframeimage)$frameimage=$ssvidframeimage;
//else $frameimage="";

include_once "_inc_header.php";


$fontvals=array('Arial,Helvetica,sans-serif','Times New Roman,Times,serif','Courier,mono,sans-serif','Verdana,sans-serif','Impact,sans-serif','Tauri','Emilys Candy','Economica','Cabin Sketch','Caesar Dressing','Henny Penny','Simonetta','Noto Serif','Snowburst One','Patrick Hand SC','Waiting for the Sunrise','Tulpen One','Meie Script','Monofett','Monoton','Dr Sugiyama','Risque','Special Elite','Share Tech Mono','Sacramento','Julius Sans One','Merienda','Boogaloo','Orbitron','Akronim','Ribeye Marrow','Nosifer','Aguafina Script');

//NOTE: inc_global checks to see if we are loading a template file  (see ddloadtemplate)

//$ddtemplates=getTemplates($template);
//if(!$loggedouttemplate)$loggedouttemplate="none";
//$ddloggedouttemplates=getTemplates($loggedouttemplate);

if($gIE)$btnddh="20";else $btnddh="18";
if($gFX)$btnoffset="position:relative;top:3px;";
else if(!$gIE)$btnoffset="position:relative;top:1px;";
?>
<style type="text/css">
body{margin-left:20px;}
td{font-size:16px;}
.foldername{color:<?=$lkcolor;?>;font-family:sans-serif;font-size:16px;padding-left:2px;padding-right:2px;}
.innerspan{text-align:left;padding:15px;}
.ddown{height:22px;}
.ddbtn{width:60px;height:<?=$btnddh;?>px;<?=$btnoffset;?>;}
.colorimg{cursor:pointer;position:relative;top:4px;}
.catlabel{cursor:pointer;font-size:22px;position:relative;top:3px;text-transform:uppercase;}
.imgexp{cursor:pointer;position:relative;top:3px;}
.c_pxinput{width:35px;}
.tbtns{width:22px;}
.srchbtn{cursor:pointer;width:22px;}
</style>
<script src='_pvt_js/colors.js'></script>
</HEAD><BODY onload='_onload()'>

<FORM name="f1" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data"> 
<input type=hidden id="page" name=page value='<?=$page;?>'>
<input type=hidden id="iblock" name=block value='<?=$block;?>'>
<input type=hidden name=action value=apply>
<input type=hidden name=dir value='<?=$dir;?>'>
<script>

function _onload(){
openColorBox("bgcolor");
try{parent.resetFadeWinBtns("<?=$fadewinbtns;?>");}catch(e){}
var ff=_obj("fontfamily");
var fs=_obj("fontsize");
var bw=_obj("popupbdrwidth");
for(var i=0;i<ff.options.length;i++){ if(ff.options[i].value=='<?=$fontfamily;?>')ff.options.selectedIndex=i; }
for(var i=0;i<fs.options.length;i++){ if(fs.options[i].value=='<?=$fontsize;?>')fs.options.selectedIndex=i; }
for(var i=0;i<bw.options.length;i++){ if(bw.options[i].value=='<?=$popupbdrwidth;?>')bw.options.selectedIndex=i; }
}


function updateFont(){
var ff=_obj("fontfamily");
var fs=_obj("fontsize");
var fobj=_obj("iSampleFont");
fobj.style.fontSize=fs.options[fs.options.selectedIndex].value;
fobj.style.fontFamily=ff.options[ff.options.selectedIndex].value;
}


function updatePopupBdrWidth(){
var bw=_obj("popupbdrwidth");
var w=(bw.options[bw.options.selectedIndex].value)?1:0;
_obj("iSamplePopupBox").style.borderWidth=w+"px";
}



function onChgOpt(o1){
var o2=_obj(o1.id.replace("1","2"));
o2.style.display=(o1.checked)?"block":"none";
//o2.disabled=(o1.checked)?false:true;
}


function _loadTemplate(){
var f=_obj("f1");
document.f1.action.value='loadtemplate';
document.f1.submit();}


function _saveTemplate(){
var o=_obj("itemplate"),n=o.value;
if(!n){alert("Please enter a template name");return;}
var f=_obj("f1");
document.f1.action.value='savetemplate';
document.f1.submit();}


function _deleteTemplate(){
var f=_obj("f1");
document.f1.action.value='deletetemplate';
document.f1.submit();}


function _reset(){
var f=_obj("f1");
document.f1.action.value='reset';
document.f1.submit();}

function _cancel(){
<?
if($page=="_newshow.php"){
 echo("parent.frames[0].gotoSlideshow();");
}else{
 echo("parent.frames[0].loadHome();");
}
?>
}



function myhilite(o){o.style.backgroundColor=_obj("btnhicolor").value;}
function mylolite(o){o.style.backgroundColor=_obj("btnbgcolor").value;}
function sshilite(o){o.style.backgroundColor=_obj("ssbtnhicolor").value;}
function sslolite(o){o.style.backgroundColor=_obj("ssbtnbgcolor").value;}

//----- colors ------
var gColorId,gColorInputObj;

function openColorBox(id){
var o,io=_obj(id),att;
//if(io==gColorInputObj){CP.close();return;}
if(gColorInputObj!=null)CP.close();
gColorId=id;
gColorInputObj=io;
switch(id){
 case "fgcolor"          :att="color"           ;o=_obj("iSampleDiv");     break;
 case "lkcolor"          :att="color"           ;o=_obj("iSampleLink");    break;
 case "hicolor"          :att="color"           ;o=_obj("iSampleHilite");  break;
 case "selcolor"         :att="backgroundColor" ;o=_obj("iSampleSelected");break;
 case "bgcolor"          :att="backgroundColor" ;o=_obj("iSampleDiv");     break;
 case "desktopbgcolor"   :att="backgroundColor" ;o=_obj("iSampleDiv");     break;
 case "toolbarbgcolor"   :att="backgroundColor" ;o=_obj("iSampleToolbar"); break;
 case "scrollbgcolor"    :att="backgroundColor" ;o=_obj("iSampleScrollBg");break;
 case "scrollfgcolor"    :att="backgroundColor" ;o=_obj("iSampleScrollFg");break;
 case "popupfgcolor"     :att="color"           ;o=_obj("iSamplePopup");   break;
 case "popupbgcolor"     :att="backgroundColor" ;o=_obj("iSamplePopup");   break;
 case "popupbdrcolor"    :att="borderColor"     ;o=_obj("iSamplePopupBox");break;
 case "titlecolor"       :att="color"           ;o=_obj("iSampleTitle");   break;
 case "bordercolor"      :att="borderColor"     ;o=_obj("iSampleImg");     break;
 case "thumbbdrcolor"    :att="backgroundColor" ;o=_obj("iSampleDivider"); break;
 case "ssthumbbdrcolor"  :att="backgroundColor" ;o=_obj("iSampleDivider"); break;
 case "btncolor"         :att="color"           ;o=_obj("iSampleBtn");     break;
 case "btnbgcolor"       :att="backgroundColor" ;o=_obj("iSampleBtn");     break;
 case "btnhicolor"       :att="backgroundColor" ;o=_obj("iSampleBtn");     break;
 case "btnbordercolor"   :att="borderColor"     ;o=_obj("iSampleBtn");     break;
 case "sshicolor"        :att="borderColor"     ;o=_obj("iSampleHi");      break;
 case "ssbgcolor"        :att="backgroundColor" ;o=_obj("iSampleBg");      break;
 case "ssfgcolor"        :att="color"           ;o=_obj("iSampleBg");      break;
 //case "ssbgopacitycolor" :att="backgroundColor" ;o=_obj("iSampleOpColor"); break;
 case "ssbtncolor"       :att="color"           ;o=_obj("issSampleBtn");   break;
 case "ssbtnbgcolor"     :att="backgroundColor" ;o=_obj("issSampleBtn");   break;
 case "ssbtnhicolor"     :att="backgroundColor" ;o=_obj("issSampleBtn");   break;
 case "ssbtnbordercolor" :att="borderColor"     ;o=_obj("issSampleBtn");   break;
}
CP.open(window,o,att,586,288,updColor);
_obj("iSampleDiv").style.display='block';
_obj("iSampleScrollBg").style.display='block';
}



function updColor(clr,att){gColorInputObj.value=clr;}


function _cpHide(){
gColorInputObj=null;
_obj("iSampleDiv").style.display='none';
_obj("iSampleScrollBg").style.display='none';
}

//--- other -----
var gSelectVar="";
var gBlock="<?=$block;?>";

function expimg(block){
var img=_obj("img"+block);
var dvv=_obj("ib"+block);
if(dvv.style.display=="none"){
 img.src="_pvt_images/contract.gif";
 dvv.style.display="block";
 if(gBlock && gBlock!=block){
  try{
   _obj("img"+gBlock).src="_pvt_images/expand.gif";
   _obj("ib"+gBlock).style.display="none";
  }catch(e){}
 }
 gBlock=block;
}else{
 img.src="_pvt_images/expand.gif";
 dvv.style.display="none";
}
_obj("iblock").value=gBlock;
}



function showFullsizeOptions(o){
_obj("iFullsizeOptions").style.display=(o.checked)?"none":"";
}



function linkover(){_obj("iSampleLink").style.color=_obj("hicolor").value;}

function linkout(){_obj("iSampleLink").style.color=_obj("lkcolor").value;}


</script> 
<?

$gAdminDisplay=(($gAdmin)? "":"NONE").";";

if($ssusepixi=='yes')$CHK_ssusepixi='checked';
if($randomize=='yes')$CHK_randomize='checked';
if($ssdropdown=='yes')$CHK_ssdropdown='checked';
if($ssfullsize=='yes')$CHK_ssfullsize='checked';
if($ssnaturalsize=='yes')$CHK_ssnaturalsize='checked';
if($ssborder=='yes')$CHK_ssborder='checked';
if($ssthumbs=='yes')$CHK_ssthumbs='checked';
if($controls=='yes')$CHK_controls='checked';
if($chromeless=='yes')$CHK_chromeless='checked';
if($fadewinbtns=='yes')$CHK_fadewinbtns='checked';
if($useaviary=='yes')$CHK_useaviary='checked';
if($fade=='yes')$CHK_fade='checked';
if($fadetoolbar=='yes')$CHK_fadetoolbar='checked';
if($showthumbs=='yes')$CHK_showthumbs='checked';
if($ssshowthumbbdrs=='yes')$CHK_ssshowthumbbdrs='checked';
//if($usedropdowns=='yes')$CHK_usedropdowns='checked';
if($mouseovermenus=='yes')$CHK_mouseovermenus='checked';
if($showlinkicons=='yes')$CHK_showlinkicons='checked';
if($optonlyimages=='yes')$CHK_optonlyimages='checked';	// only show images
if($optshowsstitles=='yes')$CHK_optshowsstitles='checked';
if($optupload1=='yes')$CHK_optupload1='checked';
if($optupload2=='yes')$CHK_optupload2='checked';
if($optnewfolder1=='yes')$CHK_optnewfolder1='checked';
if($optnewfolder2=='yes')$CHK_optnewfolder2='checked';
if($optnewfile1=='yes')$CHK_optnewfile1='checked';
if($optnewfile2=='yes')$CHK_optnewfile2='checked';
if($optnewvid1=='yes')$CHK_optnewvid1='checked';
if($optnewvid2=='yes')$CHK_optnewvid2='checked';
if($optnewlink1=='yes')$CHK_optnewlink1='checked';
if($optnewlink2=='yes')$CHK_optnewlink2='checked';
if($optnewfimg1=='yes')$CHK_optnewfimg1='checked';
if($optnewfimg2=='yes')$CHK_optnewfimg2='checked';
if($optnewsvg1=='yes')$CHK_optnewsvg1='checked';
if($optnewsvg2=='yes')$CHK_optnewsvg2='checked';
if($optorgfolders1=='yes')$CHK_optorgfolders1='checked';
if($optorgfolders2=='yes')$CHK_optorgfolders2='checked';
if($optupdsettings1=='yes')$CHK_optupdsettings1='checked';
//if($optupdsettings2=='yes')$CHK_optupdsettings2='checked';
$CHK_optupdsettings2='checked';
//if($optdropdown1=='yes')$CHK_optdropdown1='checked';
if($optdropdown2=='yes')$CHK_optdropdown2='checked';
if($showexpandbtns=='yes')$CHK_showexpandbtns='checked';
if($optmenu1=='yes')$CHK_optmenu1='checked';
if($optmenu2=='yes')$CHK_optmenu2='checked';
if($opttoolbar1=='yes')$CHK_opttoolbar1='checked';
if($opttoolbar2=='yes')$CHK_opttoolbar2='checked';
if($optshowfolders1=='yes')$CHK_optshowfolders1='checked';
if($optshowfolders2=='yes')$CHK_optshowfolders2='checked';
if($optshowfiles1=='yes')$CHK_optshowfiles1='checked';
if($optshowfiles2=='yes')$CHK_optshowfiles2='checked';
if($optshowpics1=='yes')$CHK_optshowpics1='checked';
if($optshowpics2=='yes')$CHK_optshowpics2='checked';
if($optshowvids1=='yes')$CHK_optshowvids1='checked';
if($optshowvids2=='yes')$CHK_optshowvids2='checked';
if($optshowlinks1=='yes')$CHK_optshowlinks1='checked';
if($optshowlinks2=='yes')$CHK_optshowlinks2='checked';
if($optshowfiles1=='no')$CHK_optshowfiles2.=" style='display:none;' ";
if($optshowpics1=='no')$CHK_optshowpics2.=" style='display:none;' ";
if($optshowvids1=='no')$CHK_optshowvids2.=" style='display:none;' ";
if($optshowlinks1=='no')$CHK_optshowlinks2.=" style='display:none;' ";
if($optorgfolders1=='no')$CHK_optorgfolders2.=" style='display:none;' ";
if($optupdsettings1=='no')$CHK_optupdsettings2.=" style='display:none;' ";
if($optnewfolder1=='no')$CHK_optnewfolder2.=" style='display:none;' ";
if($optnewfile1=='no')$CHK_optnewfile2.=" style='display:none;' ";
if($optnewvid1=='no')$CHK_optnewvid2.=" style='display:none;' ";
if($optnewlink1=='no')$CHK_optnewlink2.=" style='display:none;' ";
if($optnewfimg1=='no')$CHK_optnewfimg2.=" style='display:none;' ";
if($optnewsvg1=='no')$CHK_optnewsvg2.=" style='display:none;' ";
if($optupload1=='no')$CHK_optupload2.=" style='display:none;' ";

if($loginbtn=='yes')$CHK_loginbtn='checked';
if($showrefreshbtn=='yes')$CHK_showrefreshbtn='checked';
if($showpicsbtn=='yes')$CHK_showpicsbtn='checked';
if($showhomebtn=='yes')$CHK_showhomebtn='checked';
if($showexpandbtn=='yes')$CHK_showexpandbtn='checked';
if($showaddbtn=='yes')$CHK_showaddbtn='checked';
if($showtoolsbtn=='yes')$CHK_showtoolsbtn='checked';
if($showsearchbtn=='yes')$CHK_showsearchbtn='checked';
if($showfindpicsbtn=='yes')$CHK_showfindpicsbtn='checked';
if($showsearchmenu=='yes')$CHK_showsearchmenu='checked';
if($showbrowsebtn=='yes')$CHK_showbrowsebtn='checked';
if($showuploadbtn=='yes')$CHK_showuploadbtn='checked';
if($showyoutubebtn=='yes')$CHK_showyoutubebtn='checked';
if($showclipboardbtn=='yes')$CHK_showclipboardbtn='checked';
if($showcopybtn=='yes')$CHK_showcopybtn='checked';
if($showcamerabtn=='yes')$CHK_showcamerabtn='checked';
//if($showeditbtn=='yes')$CHK_showeditbtn='checked';
if($showpicxiebtn=='yes')$CHK_showpicxiebtn='checked';
if($showdonatebtn=='yes')$CHK_showdonatebtn='checked';


switch($ssitems){
 case 'files' :$ssitems_files='selected';break;
 case 'videos':$ssitems_videos='selected';break;
 case 'links' :$ssitems_links='selected';break;
 case 'images':$ssitems_images='selected';break;
 default      :$ssitems_all='selected';break;
}
switch($toolbaralign){
 case 'bottom':$tba_bottom='selected';break;
 default      :$tba_top='selected';
}
/*
switch($ssthumbsalign){
 case 'bottom':$tha_bottom='selected';break;
 case 'right' :$tha_right='selected';break;
 case 'top'   :$tha_top='selected';break;
 default      :$tha_left='selected';
}
*/

?>


<!-- ========= COLOR SAMPLE =========== -->

<DIV id=iSampleScrollBg style='position:absolute; top:52px; left:722px; height:230px; width:12px;  -webkit-border-radius: 10px; -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); border-radius: 10px; background: <?=$scrollbgcolor;?>; <? if(!$gCHROME)wrt("visibility:hidden;"); ?>' title='sample scrollbar'>
<DIV id=iSampleScrollFg style='width:12px; height:100px; -webkit-border-radius: 10px; -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); border-radius: 10px; background: <?=$scrollfgcolor;?>; '></div>
</DIV>

<DIV id=iSampleDiv style='position:absolute;top:52px;left:586px;display:none;width:132px;height:230px;color:<?=$fgcolor;?>;background-color:<?=$bgcolor;?>;border:solid 1px <?=$btnbgcolor;?>;'>
<center>
<?
wrt("<div id=iSampleBg style='position:absolute;top:20px;left:0px;width:130px;height:95px;background-color:$ssbgcolor;color:$ssfgcolor;font-size:8pt;text-align:left;'>&nbsp;title</div>");
if($ssborder=="yes")$tmp="1";
else $tmp="0";

$imgvars="top:44px;left:16px;width:100px;height:58px;";

wrt("<img id=iSampleHi  src='_pvt_images/keepme.png' style='position:absolute;top:20px;left:-40px;width:35px;height:26px;border:solid 2px $sshicolor;' title='thumbnail'>");

wrt("<img id=iSampleImg src='_pvt_images/keepme.png' style='position:absolute;".$imgvars."border:solid ".$tmp."px $bordercolor;' title='slideshow'>");
wrt("<div id=iSampleToolbar style='position:absolute; top:1px; left:0px; width:100%; background:$toolbarbgcolor; font-size:15px; text-align:left;'></div>");
wrt("<div id=iSampleTitle style='position:absolute; top:-7px; left:47px; color:$titlecolor; font-size:24px; letter-spacing:2px; '>Title</div>");
wrt("<div id=iSamplePopup class=corners4 style='position:absolute; top:127px; left:79px; width:50px; height:39px; background:$popupbgcolor; color:$popupfgcolor; font-size:12px; text-align:left; letter-spacing:1px; padding-left:1px; border:solid 1px black;' title='popups'>".
    "<b>&nbsp;Popup</b>".
    "<div id=iSamplePopupBox style='background:#fff;position:absolute; top:18px; left:3px; width:45px;height:17px;'></div>".
    "</div>");
?>
<DIV id=issSampleBtn class="sqbtn" onmouseover='sshilite(this)' onmouseout='sslolite(this)' style='position:absolute;top:23px;left:50px;color:<?=$ssbtncolor;?>;background-color:<?=$ssbtnbgcolor;?>;border:solid 1px <?=$ssbtnbordercolor;?>;font-size:13pt;width:30px;height:14px;overflow:hidden;' title='slideshow button'>
<span style='position:relative;top:-4px;font-weight:bold;'>>></span>
</DIV>
<span id=iSampleText style='position:absolute;top:120px;left:10px;font-size:12pt;' title='regular text'>sample text</span>
<span id=iSampleLink class=lnk style='position:absolute;top:140px;left:10px;font-size:12pt;color:<?=$lkcolor?>;cursor:pointer;' onmouseover='linkover()' onmouseout='linkout()'>sample link</span>
<!-- <DIV  id=iSampleDivider style='position:absolute;top:158px;left:8px;height:1px;width:100px;background:<?=$thumbbdrcolor;?>;'></DIV> -->
<span id=iSampleHilite class=lnk style='position:absolute;top:160px;left:10px;font-size:12pt;color:<?=$hicolor?>;cursor:pointer;'>mouseover</span>
<span id=iSampleSelected class=lnk style='position:absolute;top:180px;left:10px;font-size:12pt;color:<?=$lkcolor?>;background-color:<?=$selcolor?>;cursor:pointer;'>current link</span>
<DIV  id=iSampleBtn class=btn onmouseover='myhilite(this)' onmouseout='mylolite(this)' style='position:absolute;top:205px;left:10px;color:<?=$btncolor;?>;background-color:<?=$btnbgcolor;?>;border:solid 1px <?=$btnbordercolor;?>;font-size:14pt;width:110px;height:20px;overflow:hidden;'>
<span style='position:relative;top:-2px;'>Sample Button</span>
</DIV>

<DIV id=iSampleFont style='position:absolute;top:393px;left:13px;font-family:<?=$fontfamily;?>;font-size:24px;'>Font Sample</DIV>

</center>
</DIV>



<!-- ========= MAIN TABLE =========== -->
<TABLE border=0 cellpadding=5 cellspacing=0 width=730 style='height:90%;'>

<!-- ===BODY ROW=== -->
<tr height=100%>

<td valign=top>

<DIV style='height:100%;width:700px;'>
<TABLE cellpadding=0 cellspacing=0 border=0 width=95%  style='padding-top:5px;'>

<tr><td align=center>

<TABLE cellpadding=2 cellspacing=0 border=0 width=100%>

<tr>
<td colspan=2 valign=top>
<TABLE border=0 cellpadding=0 cellspacing=0 width=95%>

<tr>
<td colspan=2 align=center valign=top>
<input class='btn' type=submit value='UPDATE' <?=$movers;?> style='position:relative;left:-3px;width:138px;'>
</td>
<td valign=top></td>
</tr>

<tr style='display:none;'>
<td align=right valign=top>
<TABLE cellpadding=0 cellspacing=3><tr>
<td valign=top>
<? if($template!="default.txt"){ ?>
<div class='btn ddbtn' <?=$movers;?> onclick='_deleteTemplate()'>Delete</div>
<? } ?>
</td>
<td valign=top><div class='btn ddbtn' <?=$movers;?> onclick='_cancel()'>Cancel</div></td>
</tr></TABLE>
</td>
<td align=right valign=top width=150><div class='btn ddbtn' <?=$movers;?> onclick='_saveTemplate()' title='create a new template' style='width:80px;position:relative;top:33px;left:70;'>Create&nbsp;New:</div></td>
<td valign=top>&nbsp;<input type=text value='' size=12 name=itemplate id=itemplate style='width:120px;position:relative;top:30px;left:70;''></td>
</tr>

</TABLE></td>
</tr>

<tr><td colspan=2><div style='height:5px;overflow:hidden;'></div></td></tr>


<!-- ======================= GLOBAL OPTIONS ============================= -->

<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr><td colspan=2><img id=imgglobal class=imgexp onclick='expimg("global")' src='_pvt_images/<?=getImg("global");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("global")'>Global Settings</span></td></tr>
<tr><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>

<tr><td colspan=2>
<DIV id=ibglobal style='width:100%;display:<?=chkHidden("global");?>;'>
<TABLE style='width:100%;'>
<tr><td style='width:50%;'></td><td></td></tr>
<tr style='DISPLAY:NONE;' >
<td align=right>Use Template:&nbsp;&nbsp;</td>
<td>&nbsp;<select id=loggedouttemplate name=loggedouttemplate style='width:120px;'><option value='none'>same</option><?=$ddloggedouttemplates;?></select><small>&nbsp;(when not logged in)</small></td>
</tr>

<tr style='DISPLAY:NONE;'>
<td align=right>Show DropDown Menu:&nbsp;&nbsp;</td>
<td><input type=checkbox name=optdropdown1 id=optdropdown1 value='yes' <?=$CHK_optdropdown1;?>></td>
</tr><tr style='DISPLAY:NONE;'>
<td align=right>Use Dropdown Lists:&nbsp;&nbsp;</td>
<td><input type=checkbox name=usedropdowns id=usedropdowns value='yes' <?=$CHK_usedropdowns;?>></td>
</tr><tr style=' DISPLAY:NONE;'>
<td align=right>Mouseover Menus:&nbsp;&nbsp;</td>
<td><input type=checkbox name=mouseovermenus id=mouseovermenus value='yes' <?=$CHK_mouseovermenus;?>>
(Automatically show menus on mouse over)
</td>
</tr><tr style=' DISPLAY:NONE;'>
<td align=right>Show Link Icons:&nbsp;&nbsp;</td>
<td><input type=checkbox name=showlinkicons id=showlinkicons value='yes' <?=$CHK_showlinkicons;?>>
(Attempt to display favicons)
</td>
<tr>
<td align=right>Font Family:&nbsp;&nbsp;</td>
<td>
<select name=fontfamily id=fontfamily onchange='updateFont()'>
<option value='Arial,Helvetica,sans-serif'>Arial</option><option value='Times New Roman,Times,serif'>Times New Roman</option><option value='Courier,mono,sans-serif'>Courier</option><option value='Verdana,sans-serif'>Verdana</option><option value='Impact,sans-serif'>Impact</option><option value='Tauri'>Tauri</option><option value='Emilys Candy'>Emilys Candy</option><option value='Economica'>Economica</option><option value='Cabin Sketch'>Cabin Sketch</option><option value='Caesar Dressing'>Caesar Dressing</option><option value='Henny Penny'>Henny Penny</option><option value='Simonetta'>Simonetta</option><option value='Noto Serif'>Noto Serif</option><option value='Snowburst One'>Snowburst</option><option value='Patrick Hand SC'>Patrick Hand SC</option><option value='Waiting for the Sunrise'>Waiting for the Sunrise</option><option value='Tulpen One'>Tulpen One</option><option value='Meie Script'>Meie Script</option><option value='Monofett'>Monofett</option><option value='Monoton'>Monoton</option><option value='Dr Sugiyama'>Dr Sugiyama</option><option value='Risque'>Risque</option><option value='Special Elite'>Special Elite</option><option value='Share Tech Mono'>Share Tech Mono</option><option value='Sacramento'>Sacramento</option><option value='Julius Sans One'>Julius Sans One</option><option value='Merienda'>Merienda</option><option value='Boogaloo'>Boogaloo</option><option value='Orbitron'>Orbitron</option><option value='Akronim'>Akronim</option><option value='Ribeye Marrow'>Ribeye Marrow</option><option value='Nosifer'>Nosifer</option><option value='Aguafina Script'>Aguafina Script</option>
</select>
</td>
</tr><tr>
<td align=right>Font Size:&nbsp;&nbsp;</td>
<td>
<select name=fontsize id=fontsize onchange='updateFont()'>
<option value='8'>8px</option><option value='9'>9px</option><option value='10'>10px</option><option value='11'>11px</option><option value='12'>12px</option><option value='13'>13px</option><option value='14'>14px</option><option value='15'>15px</option><option value='16'>16px</option><option value='17'>17px</option><option value='18'>18px</option><option value='11'>11px</option><option value='19'>19px</option><option value='20'>20px</option><option value='21'>21px</option><option value='22'>22px</option><option value='24'>24px</option><option value='26'>26px</option><option value='22'>22px</option><option value='28'>28px</option><option value='30'>30px</option><option value='32'>32px</option><option value='34'>34px</option><option value='36'>36px</option><option value='38'>38px</option><option value='40'>40px</option>
</select>
</td>
</tr>
<tr>
<td align=right>Login/Logout Buttons:&nbsp;&nbsp;</td>
<td><input type=checkbox name=loginbtn id=loginbtn value='yes' <?=$CHK_loginbtn;?>></td>
</tr>
<tr>
<td align=right>Show Expand Buttons:&nbsp;&nbsp;</td>
<td><input type=checkbox name=showexpandbtns id=showexpandbtns value='yes' <?=$CHK_showexpandbtns;?>></td>
</tr>
<tr style='DISPLAY:NONE;'>
<td align=right>Show Thumbnails:&nbsp;&nbsp;</td>
<td><input type=checkbox name=showthumbs id=showthumbs value='yes' <?=$CHK_showthumbs;?>>
</tr>
<tr style='DISPLAY:;'>
<td align=right>Only Show Images:&nbsp;&nbsp;</td>
<td><input type=checkbox name=optonlyimages id=optonlyimages value='yes' <?=$CHK_optonlyimages;?>></td>
</tr>
<!--
<tr>
<td align=right>Enable Chat:&nbsp;&nbsp;</td>
<td><input type=checkbox name=optchat id=optchat value='yes' <?=$CHK_optchat;?>>&nbsp;&nbsp;AutoStart:&nbsp;&nbsp;<input type=checkbox name=chatstart id=chatstart value='yes' <?=$CHK_chatstart;?>></td>
</tr><tr>
-->
<tr style='DISPLAY:NONE;'>
<td align=right>Autostart Chat:&nbsp;&nbsp;</td>
<td><input type=checkbox name=chatstart id=chatstart value='yes' <?=$CHK_chatstart;?>></td>
</tr><tr style='DISPLAY:NONE;'>
<td align=right>Chat Room Name:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$chatroom;?>' size=8 name=chatroom id=chatroom></td>
<!--
</tr><tr>
<td align=right>Cascade Menus:&nbsp;&nbsp;</td>
<td><input type=checkbox name=cascade id=cascade value='yes' <?=$CHK_cascade;?>></td>
-->
</tr>


<tr style='DISPLAY:NONE;'>
<td align=right>Image Editor:&nbsp;&nbsp;</td>
<td><input type=checkbox name=useaviary id=useaviary value='yes' <?=$CHK_useaviary;?>>
</tr>


<tr style='DISPLAY:NONE;' >
<td colspan=2>
<input type=hidden name=optdropdown2 id=optdropdown2 value='no'>
</td></tr>


</TABLE>
</DIV>
</td></tr>


<!-- ======================= GLOBAL COLORS ============================= -->

<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr><td colspan=2><img id=imgcolors class=imgexp onclick='expimg("colors")' src='_pvt_images/<?=getImg("colors");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("colors")'>Global Colors</span></td></tr>
<tr><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>

<tr><td colspan=2>
<DIV id=ibcolors style='width:100%;display:<?=chkHidden("colors");?>;'>
<TABLE style='width:100%;'>
<tr><td  style='width:50%;'></td><td></td></tr>

<tr>
<td align=right>Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$bgcolor;?>' size=8 name=bgcolor id=bgcolor><img class=colorimg onclick='openColorBox("bgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr style="DISPLAY:NONE;">
<td align=right>Foreground:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$fgcolor;?>' size=8 name=fgcolor id=fgcolor><img class=colorimg onclick='openColorBox("fgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr style="DISPLAY:NONE;">
<td align=right>Toolbar Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$toolbarbgcolor;?>' size=8 name=toolbarbgcolor id=toolbarbgcolor><img class=colorimg onclick='openColorBox("toolbarbgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr style="DISPLAY:NONE;">
<td align=right>Desktop Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$desktopbgcolor;?>' size=8 name=desktopbgcolor id=desktopbgcolor><img class=colorimg onclick='openColorBox("desktopbgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Title:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$titlecolor;?>' size=8 name=titlecolor id=titlecolor><img class=colorimg onclick='openColorBox("titlecolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Links:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$lkcolor;?>' size=8 name=lkcolor id=lkcolor><img class=colorimg onclick='openColorBox("lkcolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Link Hilite:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$hicolor;?>' size=8 name=hicolor id=hicolor><img class=colorimg onclick='openColorBox("hicolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Current Link:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$selcolor;?>' size=8 name=selcolor id=selcolor><img class=colorimg onclick='openColorBox("selcolor")' src='_pvt_images/color.gif'></td>
</tr>

<tr  style='DISPLAY:NONE;' >
<td align=right>Thumbnail Border:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$thumbbdrcolor;?>' size=8 name=thumbbdrcolor id=thumbbdrcolor><img class=colorimg onclick='openColorBox("thumbbdrcolor")' src='_pvt_images/color.gif'></td>
</tr>

<tr>
<td align=right>Button Foreground:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$btncolor;?>' size=8 name=btncolor id=btncolor><img class=colorimg onclick='openColorBox("btncolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Button Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$btnbgcolor;?>' size=8 name=btnbgcolor id=btnbgcolor><img class=colorimg onclick='openColorBox("btnbgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Button Hilite:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$btnhicolor;?>' size=8 name=btnhicolor id=btnhicolor><img class=colorimg onclick='openColorBox("btnhicolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Button Border:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$btnbordercolor;?>' size=8 name=btnbordercolor id=btnbordercolor><img class=colorimg onclick='openColorBox("btnbordercolor")' src='_pvt_images/color.gif'></td>
</tr>
<tr>
<td align=right>Thumbnail Hilite:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$sshicolor;?>' size=8 name=sshicolor id=sshicolor><img class=colorimg onclick='openColorBox("sshicolor")' src='_pvt_images/color.gif'></td>
</tr>
<tr>
<td align=right>ScrollBar Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$scrollbgcolor;?>' size=8 name=scrollbgcolor id=scrollbgcolor><img class=colorimg onclick='openColorBox("scrollbgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>ScrollBar Foreground:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$scrollfgcolor;?>' size=8 name=scrollfgcolor id=scrollfgcolor><img class=colorimg onclick='openColorBox("scrollfgcolor")' src='_pvt_images/color.gif'></td>
</tr>


<tr>
<td align=right>Popup Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$popupbgcolor;?>' size=8 name=popupbgcolor id=popupbgcolor><img class=colorimg onclick='openColorBox("popupbgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Popup Foreground:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$popupfgcolor;?>' size=8 name=popupfgcolor id=popupfgcolor><img class=colorimg onclick='openColorBox("popupfgcolor")' src='_pvt_images/color.gif'></td>
</tr>
<tr style="DISPLAY:NONE;">
<td align=right>Popup Border:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$popupbdrcolor;?>' size=8 name=popupbdrcolor id=popupbdrcolor><img class=colorimg onclick='openColorBox("popupbdrcolor")' src='_pvt_images/color.gif'></td>
</tr>
<tr style='DISPLAY:NONE;'>
<td align=right>Show Popup Borders:&nbsp;&nbsp;</td>
<td>
<select id=popupbdrwidth name=popupbdrwidth onchange='updatePopupBdrWidth()'>
<option value='0'>0</option>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
<option value='5'>5</option>
</select> 
</td>
</tr>


</TABLE>
</DIV>
</td></tr>


<!--
<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr><td colspan=2><img id=imgtoolbar class=imgexp onclick='expimg("toolbar")' src='_pvt_images/<?=getImg("toolbar");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("toolbar")'>Other Buttons</span></td></tr>
<tr><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>
<tr><td colspan=2>
<DIV id=ibtoolbar style='width:100%;display:<?=chkHidden("toolbar");?>;'>
<TABLE style='width:100%;'>
</TABLE>
</DIV>
</td></tr>
-->



<!-- ======================= SLIDESHOW OPTIONS ============================= -->

<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr><td colspan=2><img id=imgslideshow class=imgexp onclick='expimg("slideshow")' src='_pvt_images/<?=getImg("slideshow");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("slideshow")'>Slideshow Settings</span></td></tr>
<tr><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>

<tr>

<td colspan=2>
<DIV id=ibslideshow style='width:100%;display:<?=chkHidden("slideshow");?>;'>

<TABLE border=0 style='width:100%;'>
<tr><td  style='width:50%;'></td><td></td></tr>
<tr style="DISPLAY:NONE;">
<td align=right>Default Type:&nbsp;&nbsp;</td>
<td><select style='width:100px;' name=ssitems id=ssitems>
<option value='videos' <?=$ssitems_videos;?>>videos</option>
<option value='images' <?=$ssitems_images;?>>images</option>
<option value='files'  <?=$ssitems_files;?>>pages</option>
<option value='links'  <?=$ssitems_links;?>>links</option>
<!-- <option value='all'    <?=$ssitems_all;?>>all</option> -->
</select>
</td>
</tr>

<tr style="DISPLAY:NONE;">
<td align=right>Use Pixi:&nbsp;&nbsp;</td>
<td><input type=checkbox name=ssusepixi id=ssusepixi value='yes' <?=$CHK_ssusepixi;?>></td>
</tr>


<tr style="DISPLAY:NONE;">
<td align=right>Fade Popup Toolbars:&nbsp;&nbsp;</td>
<td><input type=checkbox name=fadewinbtns id=fadewinbtns value='yes' <?=$CHK_fadewinbtns;?>>
</tr>

<tr>
<td align=right>Show Toolbar:&nbsp;&nbsp;</td>
<td>
<input type=checkbox name=controls id=controls value='yes' <?=$CHK_controls;?>>
&nbsp;&nbsp;Fade:&nbsp;&nbsp;<input type=checkbox name=fadetoolbar id=fadetoolbar value='yes' <?=$CHK_fadetoolbar;?>>
</td>
</tr>

<tr>
<td align=right>Toolbar Position:&nbsp;&nbsp;</td>
<td><select style='width:84px;' name=toolbaralign id=toolbaralign>
<option value='top' <?=$tba_top;?>>top</option>
<option value='bottom' <?=$tba_bottom;?>>bottom</option>
</select></td>
</tr>


<tr style='DISPLAY:;'>
<td align=right>Show Thumbnails:&nbsp;&nbsp;</td>
<td><input type=checkbox name=ssthumbs id=ssthumbs value='yes' <?=$CHK_ssthumbs;?>>
</td>
</tr>

<tr>
<td align=right>Show Thumbnails Button:&nbsp;&nbsp;</td>
<td><input type=checkbox name=ssshowthumbbdrs id=ssshowthumbbdrs value='yes' <?=$CHK_ssshowthumbbdrs;?>>
</tr>

<tr style='DISPLAY:;'>
<td align=right>Fullscreen:&nbsp;&nbsp;</td>
<td><input type=checkbox name=ssfullsize id=ssfullsize value='yes' <?=$CHK_ssfullsize;?> onclick='showFullsizeOptions(this)'>
<small>&nbsp;(Expand to fit)</small>
</td>
</tr>


<!-- ========= fullsize(off) options =========== -->
<? $tmp=($CHK_ssfullsize)?"none":""; ?>
<tr id=iFullsizeOptions style='display:<?=$tmp;?>;'><td colspan=2>
<TABLE style='width:100%;'><tr>
<td align=right width=60%>Images Natural Size:&nbsp;&nbsp;</td>
<td width=40%><input type=checkbox name=ssnaturalsize id=ssnaturalsize value='yes' <?=$CHK_ssnaturalsize;?>></td>
</tr>
<tr style='DISPLAY:NONE;'>
<td align=right>Background Image:&nbsp;&nbsp;</td>
<td>
<TABLE cellpadding=0 cellspacing=0><tr>
<td><input type=text value='<?=$ssbgimage;?>' size=25 name=ssbgimage id=ssbgimage></td>
<td><input class=btn type=button <?=$movers;?> onclick='' value="find"></td>
</tr></TABLE>
</td>
</tr><tr style='DISPLAY:NONE;'>
<td align=right>Background Opacity:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbgopacity;?>' name=ssbgopacity id=ssbgopacity style='width:20px;'> %</td>
</tr>
</TABLE>
</td></tr>

<tr>
<td align=right>Fade Image Transitions:&nbsp;&nbsp;</td>
<td><input type=checkbox name=fade id=fade value='yes' <?=$CHK_fade;?>></td>
</tr><tr>
<tr><td align=right>Slide Duration:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$delay;?>' size=8 name=delay id=delay><span style='position:relative;top:-2px;'>&nbsp;<small>(milliseconds)</small></span></td>
</tr>


<tr>
<td align=right>Display in Random Order:&nbsp;&nbsp;</td>
<td><input type=checkbox name=randomize id=randomize value='yes' <?=$CHK_randomize;?>></td>
</tr>


<tr style='DISPLAY:NONE;'>
<td align=right>Show Item DropDown List:&nbsp;&nbsp;</td>
<td><input type=checkbox name=ssdropdown id=ssdropdown value='yes' <?=$CHK_ssdropdown;?>></td>
</tr>



<tr style='DISPLAY:NONE;'>
<td align=right>Hide Video Controls:&nbsp;&nbsp;</td>
<td><input type=checkbox name=chromeless id=chromeless value='yes' <?=$CHK_chromeless;?> >
</td>
</tr>

<tr style='DISPLAY:NONE;'>  
<td align=right>Show Player Border:&nbsp;&nbsp;</td>
<td>
<input type=checkbox name=ssborder id=ssborder value='yes' <?=$CHK_ssborder;?>>
<input type=text value='<?=$bordercolor;?>' size=8 name=bordercolor id=bordercolor><img class=colorimg onclick='openColorBox("bordercolor")' src='_pvt_images/color.gif'>
</td>
</tr>

<tr>
<td align=right>Slideshow Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbgcolor;?>' size=8 name=ssbgcolor id=ssbgcolor><img class=colorimg onclick='openColorBox("ssbgcolor")' src='_pvt_images/color.gif'></td>
</tr>
<tr>
<td align=right>Button Foreground:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbtncolor;?>' size=8 name=ssbtncolor id=ssbtncolor><img class=colorimg onclick='openColorBox("ssbtncolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Button Background:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbtnbgcolor;?>' size=8 name=ssbtnbgcolor id=ssbtnbgcolor><img class=colorimg onclick='openColorBox("ssbtnbgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr>
<td align=right>Button Hilite:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbtnhicolor;?>' size=8 name=ssbtnhicolor id=ssbtnhicolor><img class=colorimg onclick='openColorBox("ssbtnhicolor")' src='_pvt_images/color.gif'></td>
</tr><tr style=' DISPLAY:; '>
<td align=right>Button Border:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbtnbordercolor;?>' size=8 name=ssbtnbordercolor id=ssbtnbordercolor><img class=colorimg onclick='openColorBox("ssbtnbordercolor")' src='_pvt_images/color.gif'></td>
</tr>

<!---------------------- inactive options --------------------------------->
<tr style="DISPLAY:NONE;">
<td align=right>Add a Frame (Images):&nbsp;&nbsp;</td>
<td>
<TABLE cellpadding=0 cellspacing=0><tr>
<td><input type=text value='<?=$ssframeimage;?>' size=25 name=ssframeimage id=ssframeimage></td>
<td><input class=btn type=button <?=$movers;?> onclick='' value="Find"></td>
<td><input class=btn type=button <?=$movers;?> onclick='' value="View"></td>
</tr></TABLE>
</td>
</tr><tr style="DISPLAY:NONE;">
<td align=right>Frame Opacity (Images):&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssframeopacity;?>' name=ssframeopacity id=ssframeopacity style='width:20px;'> %</td>
</tr><tr style="DISPLAY:NONE;">
<td align=right>Add a Frame (Videos):&nbsp;&nbsp;</td>
<td>
<TABLE cellpadding=0 cellspacing=0><tr>
<td><input type=text value='<?=$ssvidframeimage;?>' size=25 name=ssvidframeimage id=ssvidframeimage></td>
<td><input class=btn type=button <?=$movers;?> onclick='' value="Find"></td>
<td><input class=btn type=button <?=$movers;?> onclick='' value="View"></td>
</tr></TABLE>
</td>
</tr><tr style="DISPLAY:NONE;">
<td align=right>Frame Opacity (Videos):&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssvidframeopacity;?>' name=ssvidframeopacity id=ssvidframeopacity style='width:20px;'> %</td>
</tr>
</tr><tr style='DISPLAY:NONE;'>
<td align=right width=30%>Thumbnail Border Color:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssthumbbdrcolor;?>' size=8 name=ssthumbbdrcolor id=ssthumbbdrcolor><img class=colorimg onclick='openColorBox("ssthumbbdrcolor")' src='_pvt_images/color.gif'></td>
</tr>
<tr style='DISPLAY:NONE;'>
<td align=right>Show Titles:&nbsp;&nbsp;</td>
<td><input type=checkbox name=optshowsstitles id=optshowsstitles value='yes' <?=$CHK_optshowsstitles;?>></td>
</tr>
<tr style='DISPLAY:NONE;'>
<td align=right>Player Width:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$width;?>' size=8 name=width id=width></td>
</tr><tr style='DISPLAY:NONE;'>
<td align=right>Player Height:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$height;?>' size=8 name=height id=height></td>
</tr>
</TABLE>
</DIV>
</td></tr>
<tr style='DISPLAY:NONE;'><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr style='DISPLAY:NONE;'><td colspan=2><img id=imgslideshowbg class=imgexp onclick='expimg("slideshowbg")' src='_pvt_images/<?=getImg("slideshowbg");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("slideshowbg")'>Slideshow Background</span></td></tr>
<tr style='DISPLAY:NONE;'><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>
<tr style='DISPLAY:NONE;'><td colspan=2>
<DIV id=ibslideshowbg style='width:100%;display:<?=chkHidden("slideshowbg");?>;'>
<TABLE style='width:100%;'>
<tr style='DISPLAY:NONE;'>
<td align=right>Text Color:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssfgcolor;?>' size=8 name=ssfgcolor id=ssfgcolor><img class=colorimg onclick='openColorBox("ssfgcolor")' src='_pvt_images/color.gif'></td>
</tr><tr style='DISPLAY:NONE;'>
<td align=right>Bg Image Border Width:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbgwidth;?>' size=8 name=ssbgwidth id=ssbgwidth>&nbsp;<small>%</small></td>
</tr><tr style='DISPLAY:NONE;'>
<td align=right>Bg Image Opacity Color:&nbsp;&nbsp;</td>
<td><input type=text value='<?=$ssbgopacitycolor;?>' size=4 name=ssbgopacitycolor id=ssbgopacitycolor><img class=colorimg onclick='openColorBox("ssbgopacitycolor")' src='_pvt_images/color.gif'></td>
</tr>
</TABLE>
</DIV>
</td></tr>




<!-- ======================= TOOLBAR BUTTONS ============================= -->

<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr style="DISPLAY:NONE;"><td colspan=2><img id=imgbuttons class=imgexp onclick='expimg("buttons")' src='_pvt_images/<?=getImg("buttons");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("buttons")'>Toolbar Buttons</span></td></tr>
<tr style="DISPLAY:NONE;"><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>

<tr style="DISPLAY:NONE;"><td colspan=2>
<DIV id=ibbuttons style='width:100%;display:<?=chkHidden("buttons");?>;'>
	<TABLE border=0 style='width:90%;'>
	<tr><td  style='width:40%;'></td><td style='width:10%;'></td><td style='width:50%;'></td></tr>
	
	<tr>
	<td align=right>Home Page Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src='_pvt_images/home.png'>&nbsp;</td>
	<td><input type=checkbox name=showhomebtn id=showhomebtn value='yes' <?=$CHK_showhomebtn;?>></td>
	</tr><tr style='DISPLAY:NONE;'>
	<td align=right>Chat Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showchatbtn id=showchatbtn value='yes' <?=$CHK_showchatbtn;?>></td>
	</tr>
	<tr style='DISPLAY:;'>
	<td align=right>Search For Videos:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showyoutubebtn id=showyoutubebtn value='yes' <?=$CHK_showyoutubebtn;?>></td>
	</tr>
	<tr>
	<td align=right>Add Items Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showaddbtn id=showaddbtn value='yes' <?=$CHK_showaddbtn;?>></td>
	</tr><tr>
	<td align=right>Upload Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showuploadbtn id=showuploadbtn value='yes' <?=$CHK_showuploadbtn;?>></td>
	</tr><tr>
	<td align=right>Options Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showtoolsbtn id=showtoolsbtn value='yes' <?=$CHK_showtoolsbtn;?>>
	</td>
	</tr>
	<tr style='DISPLAY:NONE;'>
	<td align=right>Duck Duck Go:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showsearchbtn id=showsearchbtn value='yes' <?=$CHK_showsearchbtn;?>></td>
	</tr>
	<tr>
	<td align=right>Camera Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showcamerabtn id=showcamerabtn value='yes' <?=$CHK_showcamerabtn;?>></td>
	</tr>
	
	
	<tr style='DISPLAY:NONE;'>
	<td align=right>Refresh Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showrefreshbtn id=showrefreshbtn value='yes' <?=$CHK_showrefreshbtn;?>></td>
	</tr><tr style='DISPLAY:NONE;'>
	<td align=right>Menu Expand Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showexpandbtn id=showexpandbtn value='yes' <?=$CHK_showexpandbtn;?>></td>
	</tr>
	<tr style='DISPLAY:NONE;'>
	<td align=right>Search Menu:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showsearchmenu id=showsearchmenu value='yes' <?=$CHK_showsearchmenu;?>></td>
	</tr>
	<tr style='DISPLAY:NONE;'>
	<td align=right>Browse The Web:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showbrowsebtn id=showbrowsebtn value='yes' <?=$CHK_showbrowsebtn;?>></td>
	</tr><tr style='DISPLAY:NONE;'>
	<td align=right>Video Clipboard:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showclipboardbtn id=showclipboardbtn value='yes' <?=$CHK_showclipboardbtn;?>></td>
	</tr>
	<tr style='DISPLAY:NONE;'>
	<td align=right>Search For Images:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showfindpicsbtn id=showfindpicsbtn value='yes' <?=$CHK_showfindpicsbtn;?>></td>
	<td align=right>Copy URL Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showcopybtn id=showcopybtn value='yes' <?=$CHK_showcopybtn;?>></td>
	</tr>
	<tr style='DISPLAY:none;'>
	<td align=right>Edit Current Page:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showeditbtn id=showeditbtn value='yes' <?=$CHK_showeditbtn;?>></td>
	</tr><tr style='DISPLAY:NONE;'>
	<td align=right>Picxie Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showpicxiebtn id=showpicxiebtn value='yes' <?=$CHK_showpicxiebtn;?>></td>
	</tr><tr style='DISPLAY:NONE;'>
	<td align=right>Donate Button:&nbsp;&nbsp;</td>
	<td><img class=tbtns src=''>&nbsp;</td>
	<td><input type=checkbox name=showdonatebtn id=showdonatebtn value='yes' <?=$CHK_showdonatebtn;?>></td>
	</tr>
	
	
	</TABLE>
</DIV>
</td></tr>


<!-- ============== hardcore stop display - start ====== -->
<tr style='DISPLAY:NONE;'><td colspan=2>
<TABLE>

<!-- ======================= MENU RESTRICTIONS ============================= -->
<?
$onchg=" onchange='onChgOpt(this)' ";
?>

<tr><td colspan=2><div style='height:6px;overflow:hidden;'></div></td></tr>
<tr><td colspan=2><img id=imgloginopts class=imgexp onclick='expimg("loginopts")' src='_pvt_images/<?=getImg("colors");?>'>&nbsp;<span class=catlabel <?=$lnkmovers;?> onclick='expimg("loginopts")'>Menu Restrictions</span></td></tr>
<tr><td colspan=2><div style='height:1px;overflow:hidden;background:<?=$btnbgcolor;?>;'></div></td></tr>

<tr><td colspan=2>
<DIV id=ibloginopts style='width:100%;display:<?=chkHidden("loginopts");?>;'>
<TABLE style='width:90%;'>
<tr><td  style='width:50%;'></td><td></td></tr>
<tr>
<td colspan=2></td>
<td align=left><u>Require Login?</u></td>
</tr><tr>
<td align=right>Show Toolbar:&nbsp;&nbsp;</td>
<td align=left style='width:40px;'><input type=checkbox name=opttoolbar1 id=opttoolbar1 value='yes' checked disabled></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=opttoolbar2 id=opttoolbar2 value='yes' <?=$CHK_opttoolbar2;?>></td>
</tr>

<tr style='DISPLAY:NONE;'>
<td align=right width=30%>Show Side Menu:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optmenu1 id=optmenu1 value='yes' checked disabled></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optmenu2 id=optmenu2 value='yes' <?=$CHK_optmenu2;?>></td>
</tr><tr>
<td align=right>Show Folders:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optshowfolders1 id=optshowfolders1 value='yes' <?=$onchg;?> <?=$CHK_optshowfolders1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optshowfolders2 id=optshowfolders2 value='yes' <?=$CHK_optshowfolders2;?>></td>
</tr><tr>
<td align=right>Show Files:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optshowfiles1 id=optshowfiles1 value='yes' <?=$onchg;?> <?=$CHK_optshowfiles1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optshowfiles2 id=optshowfiles2 value='yes' <?=$CHK_optshowfiles2;?>></td>
</tr><tr>
<td align=right>Show Videos:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optshowvids1 id=optshowvids1 value='yes' <?=$onchg;?> <?=$CHK_optshowvids1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optshowvids2 id=optshowvids2 value='yes' <?=$CHK_optshowvids2;?>></td>
</tr><tr>
<td align=right>Show Links:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optshowlinks1 id=optshowlinks1 value='yes' <?=$onchg;?> <?=$CHK_optshowlinks1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optshowlinks2 id=optshowlinks2 value='yes' <?=$CHK_optshowlinks2;?>></td>
</tr><tr>
<td align=right>Show Images:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optshowpics1 id=optshowpics1 value='yes' <?=$onchg;?> <?=$CHK_optshowpics1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optshowpics2 id=optshowpics2 value='yes' <?=$CHK_optshowpics2;?>></td>
</tr><tr>
<td align=right>Upload Files:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optupload1 id=optupload1 value='yes' <?=$onchg;?> <?=$CHK_optupload1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optupload2 id=optupload2 value='yes' <?=$CHK_optupload2;?>></td>
</tr><tr>
<td align=right>Add New Folders:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optnewfolder1 id=optnewfolder1 value='yes' <?=$onchg;?> <?=$CHK_optnewfolder1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optnewfolder2 id=optnewfolder2 value='yes' <?=$CHK_optnewfolder2;?>></td>
</tr><tr>
<td align=right>Add New Files:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optnewfile1 id=optnewfile1 value='yes' <?=$onchg;?> <?=$CHK_optnewfile1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optnewfile2 id=optnewfile2 value='yes' <?=$CHK_optnewfile2;?>></td>
</tr><tr>
<td align=right>Add New Videos:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optnewvid1 id=optnewvid1 value='yes' <?=$onchg;?> <?=$CHK_optnewvid1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optnewvid2 id=optnewvid2 value='yes' <?=$CHK_optnewvid2;?>></td>
</tr><tr>
<td align=right>Add New Images:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optnewfimg1 id=optnewfimg1 value='yes' <?=$onchg;?> <?=$CHK_optnewfimg1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optnewfimg2 id=optnewfimg2 value='yes' <?=$CHK_optnewfimg2;?>></td>
</tr><tr>
<td align=right>Add New SVG:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optnewsvg1 id=optnewsvg1 value='yes' <?=$onchg;?> <?=$CHK_optnewsvg1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optnewsvg2 id=optnewsvg2 value='yes' <?=$CHK_optnewsvg2;?>></td>
</tr><tr>
<td align=right>Add New Links:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optnewlink1 id=optnewlink1 value='yes' <?=$onchg;?> <?=$CHK_optnewlink1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optnewlink2 id=optnewlink2 value='yes' <?=$CHK_optnewlink2;?>></td>
</tr><tr>
<td align=right>Organize Folders:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optorgfolders1 id=optorgfolders1 value='yes' <?=$onchg;?> <?=$CHK_optorgfolders1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optorgfolders2 id=optorgfolders2 value='yes' <?=$CHK_optorgfolders2;?>></td>
</tr><tr>
<td align=right>Modify Settings:&nbsp;&nbsp;</td>
<td align=left><input type=checkbox name=optupdsettings1 id=optupdsettings1 value='yes' <?=$onchg;?> <?=$CHK_optupdsettings1;?>></td>
<td align=left>&nbsp;&nbsp;&nbsp;<input type=checkbox name=optupdsettings2 id=optupdsettings2 value='yes' disabled <?=$CHK_optupdsettings2;?>></td>
</tr>
</TABLE>
</DIV>
</td></tr>

<!-- ============== hardcore stop display - end ====== -->
</TABLE>
</td></tr>

</td></tr>
</TABLE>
</DIV>
</td>

<!-- image select column -->
<td width=130>
</td>

<tr></TABLE>
</FORM>
</BODY></HTML>


<?
exit();


function chkHidden($thisblock){
global $block;
if($block==""){echo("none");return;}
if($block==$thisblock){echo("inline");return;}
echo("none");
}

function getImg($thisblock){
global $block;
if($block==""){echo("expand.gif");return;}
if(_in($block,$thisblock)){echo("contract.gif");return;}
echo("expand.gif");
}


function chkOption($opt,$v,$blankIsNo){
global $allvars;
$v2=rqst($opt);
if($blankIsNo && empty($v2))$v2="no";
if($opt=="homepage")$v2=_rep($v2,"http://","http_");
$tmp="$".$opt."='".$v2."';\n";
$allvars=$allvars."$".$opt."='".$v2."';\n";  //NOTE: allvars is used when saving a template
}


function getTemplates($t){
$ddtemplates="";
$dir_handle=@opendir("templates") or die("Unable to open /templates");
$filenames=array();
while($filename=readdir($dir_handle))$filenames[]=$filename;
sort($filenames);
foreach($filenames as $filename){
  //wrtb("n=".$filename);
  if(_ix($filename,".")>0 && $filename!="_template.txt"){
   $ddtemplates.="<option value='".$filename."'";
   if($filename==$t)$ddtemplates.=" selected";
   $ddtemplates.=">".getName($filename)."</option>";
} }
//exit(0);
return $ddtemplates;
}


function chkSelect($x,$v){
if($x==$v)return "selected";
return "";
}



?>
