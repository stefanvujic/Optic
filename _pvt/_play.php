<?
/* Copyright ©, 2005, Walter Long */

include_once "_inc_global.php";
include_once "_inc_header.php";


//http://sddsfas.com/_pvt/_play.php?inpixi=yes&usepixi=no
//&src=My2FRPA3Gf8 &source= &dir=Images &type=videos

$takepic=rqst("takepic");
$tmp=rqst("type");
$ssitems=($tmp)?$tmp:"images";
$config=rqst("config");
$source=rqst("source");     //***** NOTE: pretty sure "source" is no longer used!??? *******
$src=rqst("src");
if(!$src)$src=rqst("file");
$src=_rep($src,"http_","http://");
$title=rqst("title");
$find=rqst("find");
$winix=rqst("winix");
$tid=rqst("tid"); //only used is src=clip or youtube
$tmp=rqst("shuffle");
if($tmp=="yes")$randomize="yes";
if($tmp=="no")$randomize="no";
$includesubdirs=rqst("includesubdirs");
if(rqst("usepixi"))$ssusepixi=rqst("usepixi");  //this allows us to override the default for files and links
else if(rqst("mode")=="popup" && !$config)$ssusepixi="no";
$inpixi=(rqst("inpixi")=="yes")?"yes":"no";
if($inpixi=="yes")$ssusepixi="no";
$iframes=($ssitems=="files" || $ssitems=="links" || $ssitems=="pixis")?1:0;
if($iframes || rqst("fullscreen")=="1")$ssfullsize="yes";

//----- tmp ------------------
//$ssfullsize="yes";
//$ssnaturalsize="no";  //doesn't work
//------------------------------

$tmpfullsize=$ssfullsize;


//------------------------------------------------------------------------------


//------ usepixi does not work for files and links -----------------------------
//NOTE: We could embed play.php in pixi if necessary (same as we do for videos)
if($ssitems!="images" && $ssitems!="videos"){ $ssusepixi="no"; $inpixi="no"; }


//wrtb("includesubdirs=".$includesubdirs);
//exit();



//=============== thumbs? ===============
//NOTE: ssthumbs    = the template setting - it determines whether thumbs are show on startup.
//      ss.showthumbs  = used to force the thumbs to be ALWAYS on (since we can flipThumbs())
//if($winix){ $ssdropdown="no"; $ssthumbs="no"; }
if(rqst("ssthumbs"))$ssthumbs=rqst("ssthumbs");
if(rqst("thumbs"))$ssthumbs=rqst("thumbs");
if(rqst("thumbnails"))$ssthumbs=rqst("thumbnails");
if(rqst("autostart"))$autostart=rqst("autostart");
else $autostart='yes';


//if($ssthumbsalign=="bottom")$toolbaralign="top";
//if($ssthumbsalign=="top")$toolbaralign="bottom";

$subdomains=rqst("subdomains");
if($find && $subdomains)$find.=".".$subdomains;
if(!$find)$find=$subdomains;
$auto=($find)?rqst("auto"):"";
if($auto && $gLoggedIn)$ssthumbsalign="left";
$popozie="";

if($iframes){
 $ssbgcolor=$bgcolor;
 $sshicolor=$selcolor;
 $ssfgcolor=$fgcolor;
}
$ix=myGet('ix');
if($ix!=""){if($ssitems=="images" || $ssitems=="fimgs")$autostart='';}
else $ix=0;


//--- override vars? ---
if($iframes)$autostart='';
$frameimage="";


//--- default object arrays ----
$ssitems1=$ssitems;
$allvids=",";
$titles=array();
$objects=array();
$pdirs=array();
$imgtxt="";
$tittxt="";
$dirtxt="";
$ddoptions="";
$cnt=0;

//wrtb("src=".$src.", auto=".$auto.", find=".$find.", clip=".$_SESSION['copy2']);
//exit(0);

if($source=="youtube")  { getTxtObjects($_SESSION['lastyoutubelist'],$objects,$titles,$pdirs); /*$autostart="yes";*/ }
else if($source=="clip"){ getTxtObjects($_SESSION['copy2'],$objects,$titles,$pdirs); /*$autostart="yes";*/ }
else if($auto)          { alert("play.php auto");  getAutoObjects($find,$objects,$titles,$pdirs);  } //not currently used
else if(!$find){
 //--- default mode ---
 getAllObjects($includesubdirs,$dir,$objects,$titles,$pdirs);
}else{
 //--- ?????????????? ----
 wrtb("play.php Do we ever get in here???");
 exit();
}
$cnt=loadTextStrings($objects,$titles,$pdirs,$imgtxt,$tittxt,$dirtxt,$ddoptions);
if($cnt<2){
 $ssthumbs="no";
 $ssdropdown="no";
}
if(!$imgtxt){
 if($auto=="" && $ssitems=="videos" && $find){
  wrt("<script>window.location.href=window.location.href+'&auto=yes';</script>");
 }else{
  wrt("<big><big><center><br><br><br>Sorry, no ".$ssitems." were found.</center></big></big>");
 }
 exit(0);
}

//wrtb("objst=".count($objects).", titles=".count($tiles).", pdirs=".count($pdirs));
//for($i=0;$i<count($objects);$i++)wrtb("object=".$objects[$i].", pdir=".$pdirs[$i]);
//exit(0);


//--- set the vars ---
if(rqst("toolbar"))$controls=rqst("toolbar");
if($controls=="yes")$Dcontrols="block";else $Dcontrols="none;";
if($ssdropdown=="yes")$Ddropdown="inline";else $Ddropdown="none;";
if($ssdropdown=="yes"||$controls=="yes")$Dtoolbar="";else $Dtoolbar="display:none;";


//------------------ image box style ------------------------

$theimg=";cursor:pointer;overflow:hidden;";
if($ssfullsize=="yes"){
   $theimg.="position:absolute;left:0px;top:0px;width:100%;height:100%;";
}else{
   if($ssnaturalsize=="yes" && $usepixi!="yes"){
     $theimg.="position:absolute;left:20%;width:60%;top:10%;";
   }else{
     $theimg.="position:absolute;left:10%;width:80%;top:10%;height:78%;";
} }

if($fadetoolbar=="yes")$tmptbfade=" onmouseover='overFade(event,this)' onmouseout='outFade(event,this)' ";

//wrtb("????=".$theimg);
//exit();

//------ create the page --------
?>
<STYLE>
.gzbtn   {<?=$roundedcorners2;?>;width:36px;height:<? if($gIE)wrt("18"); else wrt("16"); ?>px;border:solid 1px <?=$ssbtnbordercolor;?>; color:<?=$ssbtncolor;?>;background:<?=$ssbtnbgcolor;?>;cursor:pointer;overflow:hidden;text-align:center;padding-left:1px;padding-right:2px;text-decoration:none;}
.buttons {display:<?=$Dcontrols;?>;}
.xbtn    {width:100px;font-size:8pt;height:19px;text-decoration:none;}
.theimg  {<?=$theimg;?>}
.toolbar1{padding:0px;display:<?=$Dcontrols;?>;}
.c_thumbox{background:; right:87%; top:0px; width:12%; height:100%;}
.c_popupmnu{border:solid 1px <?=$btnbordercolor;?>;background:<?=$selcolor;?>;width:98px;}
</STYLE>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script src="_pvt_js/async.js"></script>

<?
if($gIE || $useFlash){
 if($chromeless!='yes'){
  ?>
  <SCRIPT type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js"></SCRIPT>
  <?
 }else{
  ?>
  <SCRIPT type="text/javascript" src="http://www.google.com/jsapi"></SCRIPT>
  <SCRIPT type="text/javascript">google.load("swfobject", "2.1");  </SCRIPT>
  <?
}}
?>


<SCRIPT>
var gPopup,gPopupIx;
var gAuto="<?=$auto;?>";
var gSrc="<?=$src;?>";
var gConfig="<?=$config;?>";
var gSource="<?=$source;?>";
var gWinIX="<?=$winix;?>";
var gUsePixi=("<?=$ssusepixi;?>"=="yes")?1:0;
var gInPixi=("<?=$inpixi;?>"=="yes")?1:0;
var gPixiFrame;
var gTakepic=("<?=$takepic;?>")?"&start=<?=$takepic;?>" : "" ;
var gTakePicStart=("<?=$takepic;?>")?"<?=$takepic;?>" : "0" ;
var gLoadBoth=0;
var gFirstTime=1;
var gFlipThumbs;  //if null this does nothing, but can be set by pixi
//alert(gUsePixi+","+gInPixi);

var ss,ss1,ss2,ssv;
var objects=new Array(<?=$imgtxt;?>),titles=new Array(<?=$tittxt;?>),pdirs=new Array(<?=$dirtxt;?>);
var gSStype='<?=$ssitems;?>';
var gSSiframes=<?=$iframes;?>;
var gIE=<?=$gIE;?>;
var gToolbarAlign="<?=$toolbaralign;?>";
var gPopupWindow=<?=((rqst("mode")=="popup")?1:0)?>;
try{parent.setSStype(gSStype);}catch(e){}
var gSSborder='<?=$ssborder;?>';
var gBorderColor='<?=$bordercolor;?>';
var gSSfullSize='<?=$tmpfullsize;?>';
var gToolbarClicked=0;
var gNaturalSize=("<?=$ssnaturalsize;?>"=="yes" && !gUsePixi)?1:0;
//alert("<?=$ssfullsize;?>, typ=<?=$ssitems;?>");


//------------------------------ toolbar faders --------------------------------
/*
if($fadetoolbar=="yes")$tmptbfade=" onmouseover='overFade(event,this)' onmouseout='outFade(event,this)' ";
//if($fadetoolbar=="yes")$tmptbfade=" onmouseover='moFade(1,this,50)' onmouseout='moFade(0,this)' ";

NOT WORKING -

*/
var gToolbarTimer;


function overFade(event,tb)  {
if(event){
 var obj=event.relatedTarget;
 while(obj!=null){
  if(obj==tb)return;  //not really mouseout
  obj=obj.parentNode;
}}
//msg("tb.id="+tb.id);
moFade(1,tb,50);
//setTimeout("moFade(1,_obj('"+tb.id+"'),50);",1000);
}

function outFade(event,tb) {
if(event){
 var obj=event.relatedTarget;
 while(obj!=null){
  if(obj==tb)return;  //not really mouseout
  obj=obj.parentNode;
}}
moFade(0,tb);
//setTimeout("moFade(0,_obj('"+tb.id+"'));",1000);
}



//---------------------- popup menu --------------------

function popup(event,ix,typ){
//alert(typ);
//return true;
if(!gID)return true;
if(gSource=="clip")return true;
var e=_event(event),rbtn=0;
if(!gIE){if(e.which==3)rbtn=1;}
else    {if(event.button==2)rbtn=1;}
if(!rbtn)return true;
var pdir="",txt, mnu=_obj("popup_"+ss.id+"_"+ix), obj=_obj("o_"+ss.id+"_"+ix);
//if(typ=="vid")pdir=ss.slides[ix].pdir;
pdir=ss.slides[ix].pdir;
if(!hasDirRights(pdir))return true;
if(gPopup && gPopup!=mnu)gPopup.style.display="none";
if(mnu.style.display=="none"){
 gPopup=mnu;
 gPopupIx=ix;
 if(mnu.innerHTML==""){
  txt="<TABLE cellpadding=1 cellspacing=0 style='position:relative;width:98px;overflow-x:hidden;background:<?=$btncolor;?>;border:solid 1px <?=$btnhicolor;?>;'>";
  if(!gAuto){
   //if(typ!="vid" && typ!="lnk" && typ!="img")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"rename","renamefile",pdir)+"</td></tr>";
   if(typ=="vid" || typ=="image" || typ=="fimg" || typ=="link" || typ=="file" || typ=="pixi")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"delete","deletefile2",pdir)+"</td></tr>";
   if(typ=="image" || typ=="file" || typ=="pixi")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"copy","copyfile",pdir)+"</td></tr>";
  //if(typ=="image")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"edit","editfile",pdir)+"</td></tr>";
   //if(typ=="file" && _in(".htm",_getExt(ss.slides[ix].src)))txt+="<tr><td colspan=2>"+popupItem(typ,ix,"design","designfile",pdir)+"</td></tr>";
  }
  if(typ=="vid" && gSource!="clip")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"copy to clipbrd","copy2clip",pdir)+"</td></tr>";
  txt+="</TABLE>";
  mnu.innerHTML=txt;
 }
 mnu.style.display="block";
}else{
 mnu.style.display="none";
 gPopup=null;
 gPopupIx=null;
}}


function popupAction(typ,ix,action,pdir){
return;
var mnu=_obj("popup_"+ss.id+"_"+ix);
var file=ss.slides[ix].src;
//----------- immediate delete if clipboard ----------------
//if(_in(action,"delete") && gSource=="clip"){
// asyncP("action=deleteclipvideo&ix="+ix+"&tid="+file+"&tit="+ss.slides[ix].title,action);
// return;
//}
//----------- other immediate delete ----------------
// see ajx.php - deletefile2()
if(action=="deletefile2"){
 if(typ=="fimg" || typ=="vid" || typ=="link"){
  file=file.replace("http://","http_");
  file=file.replace("https://","http_");
  asyncP("action="+action+"&file="+file+"&id="+ss.id+"&ix="+ix+"&typ="+typ+"&pdir="+pdir+"&dir="+pdir,action);
  return;
}}
//---------- others -------
if(pdir=="")pdir=getPath(file);
pdir=pdir.replace(gPath,"");
file=getFile(file);
var parms="?page="+action+"&dir="+pdir+"&file="+file;
var lnktarget=file.replace("http://","");
lnktarget=lnktarget.replace("https://","");
lnktarget=lnktarget.replace("@fmg@","");
lnktarget=lnktarget.replace("@lnk@","");
var lnkparms="?page="+action+"&dir="+pdir+"&target="+escape(lnktarget);
var target=file.replace(gPath,"");
switch(action){
case "copy2clip":
 asyncP("action=copy2&video="+file+"&title="+ss.slides[ix].title,action);
 break;
case "renamefile":
 //loadUtil("_newutil.php"+parms,action);
 parent.openWindow(0,"_newutil.php"+parms,"370px","250px",null,null,"Rename File");
 break;
case "deletefile2":
 asyncP("action="+action+"&file="+file+"&id="+ss.id+"&ix="+ix+"&typ="+typ+"&pdir="+pdir+"&dir="+pdir,action);
 break;
case "copyfile":
 parent.openWindow(0,"_newutil.php"+parms,"370px","340px",null,null,"Copy File");
 break;
case "editlink":
 parent.openWindow(0,"_newutil.php"+lnkparms,"600px","280px",null,null,"Edit Link");
 break;

case "editfile":
 //alert("typ="+typ+", u="+"<?=$imgeditor?>?dir="+target+", target="+target);
 if(typ=="fimg")  { parent.openWindow(0,"_newutil.php?page=editfimg&dir="+pdir+"&target="+escape(lnktarget),"370px","260px",null,null,"Edit Link"); break; }
 if(typ=="image") { parent.openWindow(0,"<?=$imgeditor?>?dir="+pdir+file,"96%","96%","20px","5px","Edit Image"); break; }
 openWindow("_newutil.php"+parms,action);
 break;
case "designfile":
  //alert("edit.php"+parms);
  //parent.loadPixiDesign("edit.php"+parms);
  loadPage("edit.php"+parms,action,"none",target,force);
 return;

}
mnu.style.display="none";
}

function localDeleteItem(id,ix){ //called by ajx.php -> asyncEval
//alert("localdelete  id="+id+", ix="+ix);
ss.slides[ix].src='_pvt_images/deleted.png';
_obj("box_"+id+"_"+ix).style.display="none";
}


function localDeleteClipVideo(ix,tid){ //called by asyncEval
//var slide=ss.slides[ix];
//slide.loaded=false;
//slide.filetype="image";
//try{ssv.slides[ix].src='/_pvt/_pvt_images/deleted.png';}catch(e){}
try{
 //_obj("o_"+ss.id+"_"+ix).style.display='none';
 _obj("o_"+ss.id+"_"+ix).src='/_pvt/_pvt_images/deleted.png';
}catch(e){}
}


function copyAllToClipboard(){
asyncP("action=copyalltoclip","copyalltoclip");
}


function loadUtil(u,page){parent.frames[0].loadUtil(u,page);}


function popupItem(typ,ix,txt,action,pdir){return "<div onclick='closePopup();popupAction(\""+typ+"\","+ix+",\""+action+"\",\""+pdir+"\")' <?=$menumovers;?> style='background:<?=$btncolor;?>;color:<?=$btnhicolor;?>;'>"+txt+"</div>";}

function closePopup(id){
if(id==null)_xvzFlip(gPopup);
else _xvzFlip(_obj(id));
setTimeout("gPopup=null;gPopupIx=null;",500);
}

function asyncP(parms,action){
//alert("parms="+parms+", action="+action);
asyncPOST(parms,"ajx.php",asyncEval,action);
//window.open("ajx.php?"+parms);
}

function asyncEval(req){
//alert(req.responseText);
try{_obj("iBusy").style.display="none";}catch(e){}
try{eval(req.responseText);}catch(e){alert("Sorry! Remote call failed");}
}


//alert("<?=$ssfullsize;?>, typ=<?=$ssitems;?>, thumbs=<?=$ssthumbs;?>");

</SCRIPT>

</HEAD>

<BODY id=ibdy onload='_onload()' onresize='resize()' oncontextmenu='return false' topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 style='overflow:hidden;background:<?=(($ssusepixi=="yes")? "" : $ssbgcolor );?>;'>
<?
createThumbBox("left:87%;top:0px;height:100%;width:12%;DISPLAY:NONE;");
createBigBox("left:0px;top:0px;width:100%;height:100%;");
createToolbar();

//exit();

?>

<SCRIPT>


//-------------------------------- ONLOAD --------------------------------------
function _onload(){
//if(gUsePixi)alert("pauseVideo check (start of onload)");
gMobile=DetectMobile();
if(gIphone)gUseFlash=0;
if(gUsePixi)gPixiFrame=frames[0];
ss=newSlideShow("ss1","",gSStype,objects,titles,pdirs);
//alert("typ="+gSStype+", objects="+objects+", current="+ss.current+", defsrc="+gDefSrc);
ss1=ss;
if(ss.autostart=="yes"){
 ss.play();
}
if("<?=$fadetoolbar;?>"=="yes")schedFadeOut('itoolbar',5000);
try{parent.frames[0].gPlayClicked=1;}catch(e){}
if(gInPixi){ 
 parent.gInPixiLoaded=1;
}else{
 if(gFlipThumbs!=null)ss.flipThumbs(gFlipThumbs);
}}




function newSlideShow(name,id,typ,objects,titles,pdirs){
var newss=new slideshow(name,id);
ss=newss;
ss.typ=typ;
ss.media=typ.replace("s","");
ss.playBtn=_obj("iPlayBtn"+id);
ss.pauseBtn=_obj("iPauseBtn"+id);
ss.btns=_obj("iBtns"+id);
if(typ=="videos")ssv=ss;
for(i=0;i<objects.length;i++){
 var s=addNewSlide(objects[i],titles[i],pdirs[i],titles[i]);
}
ss.box=_obj("ss_box");  
if(ss.randomize=="yes" || ss.includesubdirs=="yes")ss.shuffle();
//if(ss.randomize=="yes")ss.shuffle();
if(ss.slides.length>ss.maxobjects)ss.slides.length=ss.maxobjects+1;
var defsrcFound=0;
if(!id && ss.defsrc){   
 for(i=0;i<ss.slides.length;i++){
  if(_in(ss.slides[i].src,ss.defsrc)){ ss.current=i; defsrcFound=1; }
}}
//ss.DD=_obj("ss_select"+id); 
//ss.loadDD();
ss.thumbox=_obj("ss_thumbs"+id);
ss.flipThumbs(ss.showthumbs);
if(ss.defsrc && !defsrcFound){
 //alert("play.php gotoImg("+ss.defsrc+")");
 //gotoImg(ss.defsrc,ss.deftitle,parent.gDir)  //causes problems if def pic is in a different directory
}else{
 ss.loadThumbs();
 if(id)ss.update(null,1);  
 else ss.update();
}
return newss;
}


//----------------------------------- API! -------------------------------------

//--- these are called by the pixi next/prior buttons
function playPause()  { ss.PauseOrPlay(); }
function gotoNext()   { ss.next(); }   
function gotoPrior()  { ss.prior(); }
function thumbsOpen() { return ss.thumbsOpen(); }

function flipThumbs(f){ 
//try{ ss.flipThumbs(f); }catch(e){ setTimeout("ss.flipThumbs("+f+");",1000); }
try{ ss.flipThumbs(f); }catch(e){ gFlipThumbs=f; }
}



//--- attempt to goto a slide - if not found then add it (see defsrcFound) ----
// This is called from the OUTSIDE (not within play.php)
function gotoImg(src,title,dir){
//alert("play.php gotoImg("+src+")");
gToolbarClicked=0;
var ok=ss.load_slide(src);
//alert("play.php gotoImg - ok="+ok+", usePixi="+gUsePixi+", inPixi="+gInPixi);
if(!ok){
 if(gUsePixi){ return loadInPixi(src,title,dir); }
 if(!_in(src,"@vid@"))title=(title)?title.substr(0,11):"";
 //alert("title="+title);
 ok=addImg(src,title,dir);
 if(ok){
  ss.loadThumbs();
  ss.load_slide(src);
}}
//if(_obj('iPlayBtn').style.display=='none')ss.play();
return ok;}


//--- call this if we use pixi and load_slide fails ----
function loadInPixi(src,title,dir){
//var typ=_fileType(src);
//alert("play.php loadInPixi gotoImg dir="+dir);
return gPixiFrame.gotoImg(src,title,dir);   
}



//----- add a whole folder (array) to the current ss ----
// "include all" is currently commented out in menu.php because it doesn't seem to work!
function addImgs(a,titles){
var src;
var tmp=(a[0])?a[0]:a[1];
var typ=_fileType(tmp);
if(ss.typ=="images" && typ!="image" && typ!="fimg")return 0;
if(ss.typ=="videos" && typ!="video")return 0;
for(var i=0;i<a.length;i++){
 if(a[i]){
  var v=a[i];
  v=v.replace("_tn.",".");
  v=v.replace("@fmg@","");
  if(!_in(v,"http") && !_in(v,"../") && !_in(v,"@vid@"))v="../"+v;
  if(!ss.load_slide(v)){                  // it might be already loaded
   var title=(titles)?titles[i]:v;
   if(addImg(v,title)){ if(!src)src=v; }  // if not then try adding it
}}}
if(src){ //we added at least one
 ss.load_slide(src);
 ss.loadThumbs();
 //if(_obj('iPlayBtn').style.display=='none')ss.play();
}return 1;}


//--- Add a new slide to the current slideshow (if typ matches) ---
function addImg(src,title,dir){
//alert("play.php addImg("+src+")");
if(dir==null)dir=parent.gDir;
var typ=_fileType(src);
if(!title)title=getFileName(src);
var text=title;
if(ss.typ=="images"){ if(typ!="image" && typ!="fimg")return 0; }
else{ if(!_in(ss.typ,typ))return 0; }
//alert("addImg... objects="+_in(objects,src));
if(!_in(objects,src))objects[objects.length]=src;
titles[titles.length]=title;
pdirs[pdirs.length]=(dir)?dir+"/":"";
addNewSlide(src,title,pdirs[pdirs.length-1],text);
return 1;
}

//---------------------- flipSS ------------------------------------------------
//- Called by Pixi when the current screen media changes.
//- NOTE: We don't call ss.update() because this is called by Pixi which has 
//-       already loaded the new media in a new screen.
//- This is used to switch between the image and video slideshows only.
function flipSS(media){
if(!media)media=(ss.media=="video")?"image":"video";
}

function setButtons(media,on,applyupwards,donotapplydownwards){   
var sstmp=(!ss2 || ss1.media==media)?ss1:ss2;
//console.log('plain setButtons - media='+media+', on='+on);
sstmp.setButtons(on,applyupwards,donotapplydownwards);
}

function hiliteThumb(media,ix){
var sstmp=(!ss2 || ss1.media==media)?ss1:ss2;
sstmp.selectCurrent(i);
}

//------------------------------- end api --------------------------------------


function hideMenu(b){
if(b.value=="Hide Menu"){
 b.value="Show Menu";
 parent._flipMenu(0);
}else{
 b.value="Hide Menu";
 parent._flipMenu(1);
}}

function hideToolbar(b){
var o=_obj("itoolbar");
var btns=_obj("ss_controls");
if(b.value=="Hide Toolbar"){
 b.value="Show Toolbar";
 o.style.display='none';
 btns.style.display='none';
}else{
 b.value="Hide Toolbar";
 o.style.display='block';
 btns.style.display='block';
}}


function myHotlink(event){
var e=_event(event),rbtn=0;
if(!gIE){if(e.which==3)rbtn=1;}
else    {if(event.button==2)rbtn=1;}
ss.hotlink(rbtn);}

function _sshilite(o){o.style.background='<?=$ssbtnhicolor;?>';}
function _sslolite(o){o.style.background='<?=$ssbtnbgcolor;?>';}


</SCRIPT>

<script>
//================================= http://slideshow.barelyfitz.com/ ================================
// There are two objects defined in this file:
//  "slide" - contains all the information for a single slide
//  "slideshow" - consists of multiple slide objects and runs the slideshow


//==================================================
// SLIDE OBJECT
//==================================================
function slide(src,link,text,target,attr) {
 this.src = src;
 this.link = link;
 this.text = text;
 this.target = target;
 this.attr = attr; 
 this.loaded = false;

 //--------------------------------------------------
 this.load = function(){
  if(this.loaded)return;
  this.filetype=_fileType(this.src);
  switch(this.filetype){
   case "fimg":
   case "image":
    //alert("_play slide.load "+this.src);
    this.image=new Image();   
	this.src=this.src.replace("../http://","http://");
	//msg(this.src);
    this.image.src=this.src.replace("@fmg@",""); 
    this.loaded=true;
    break;
   case "file":
    this.loaded=true;
    break;
   case "video":
    this.loaded=true;
    break;
 }}

 //--------------------------------------------------
  if(this.filetype=="image" || this.filetype=="fimg"){
   if(rbtn)ss.prior();else ss.next();
  }else{
   if(this.filetype=="video")ss.PauseOrPlay();
 }

}

//==================================================
// SLIDESHOW OBJECT
//==================================================
function slideshow(slideshowname,id){
  if(slideshowname==null)slideshowname="";
  if(id==null)id="";
  //--- public variables
  this.maxobjects=75;
  this.name = slideshowname;
  this.id=id;
  this.box;               // DIV CONTAINER
  this.repeat = true;
  this.prefetch = 5;     // -1=preload all, 0=preload none, n=preload n images
  this.timeout = <?=$delay;?>;
  this.zindex=0;
  this.videoPlayerLoaded=0;
  this.playing=0;
  this.autostart='<?=$autostart;?>';
  this.randomize="<?=$randomize;?>";
  if(gUsePixi){
   this.fade="no";
  }else{
   this.fade="<?=$fade;?>";
  }
  this.showthumbs=("<?=$ssthumbs;?>"=="yes")?1:0;;
  this.includesubdirs="<?=$includesubdirs;?>";
  this.thumbsalign="<?=$ssthumbsalign;?>";
  this.fullsize="<?=$ssfullsize;?>";
  this.thumbsX="0px";
  this.thumbsY="0px";
  this.itemsDeleted=",";
  if(!this.id){
   this.defsrc="<?=_rep($src,"_tn.",".");?>";
   this.deftitle="<?=$title;?>";
  }else{
   this.defsrc="";    
   this.deftitle="";  
  }

  //--- post_update ------
  this.post_update_hook=function(){
   if(this.id)return;
   var src=this.slides[this.current].src;
   if(!gInPixi)try{parent.frames[0].selectCurrent(src);}catch(e){}   // update the menu
   this.selectCurrent(this.current);
  //this.DD.selectedIndex=this.current;
   var url=window.location.href;
   var a=url.split("file=");
   if(a.length>1){
    var b=a[1].split("&");
    url=url.replace("file="+b[0],"file="+getFileName(src));
   }else{
    url+="&file="+getFileName(src);
   }
   var pdir=this.slides[this.current].pdir;
   if(pdir)pdir=pdir.substr(0,pdir.length-1);
   url=url.replace("dir="+gDir,"dir="+pdir);
   if(!gWinIX)try{parent.gWinPage.load(url,"slideshow",this.slides[this.current].filetype,src,pdir);}catch(e){}
  }

  //--- private variables
  this.slides = new Array();
  this.current = <?=$ix;?>; //this gets overriden if 'src' (defsrc) is passed - good!
  this.lastcurrent = null;
  this.timeoutid = 0;

  //--------------------------------------------------
  // Public methods
  //--------------------------------------------------

    //var gThumbsAlready

  this.thumbsOpen = function(){
   return ((this.thumbox.style.display=="block")?1:0);
  }
  
  
  //--------------------------- flipThumbs() ----------------------
  this.flipThumbs = function(force){
   var th=this.thumbox.style;
   if(force==null)force=(th.display=="block")?0:1;
   var bb=_obj("ss_box").style;
   var tb=_obj("itoolbar").style;
   if(!force){
    th.display="none";
    bb.width="100%";  
	tb.width='100%';
    return;
   }
   th.display="block";
   //th.left="87%";  th.width="12%";  
   bb.width="87%";
   tb.width='87%';
}


  //---------------------------- UPDATE ----------------------------------------
  this.update = function(finished,nostart,forcemedia){   
   if(this.slides.length==0)return;
   var objId,lastObjId,lastslide;
   var slide=this.slides[this.current];
   this.slide=slide;

   //--- check to see if this item has been deleted ---
   if(_in(slide.src,"deleted.png")){
    if(!_in(this.itemsDeleted,","+this.current+","))this.itemsDeleted+=""+this.current+",";
    var ok=0;
    for(var i=0;i<this.slides.length;i++){if(!_in(this.itemsDeleted,","+i+","))ok=1;}  //all items deleted?
    if(ok)this.next();
    return;
   }

   if(this.lastcurrent!=null){
    lastslide=this.slides[this.lastcurrent];
    if(this.lastslide)this.lastlastslide=this.lastslide;
    this.lastslide=lastslide;
   }
   //alert("finished="+finished+", nostart="+nostart+", this.playing="+this.playing);
   //alert("??? .update, nostart="+nostart+", filetype="+this.slide.filetype);
   if(!nostart){
    if(this.slide.filetype=="video")this.loadVideo(slide,finished);
    else slide.load();
   }

   //---- load the new image/page ----
   if(slide.filetype!="video"){
    this.zindex+=1;
    if(!slide.obj || this.fullsize=="no")this.addSlideObj(slide,forcemedia);
    else{
     slide.obj.style.display="none";
     slide.obj.style.zIndex=this.zindex;
    }
    if(this.lastlastslide && this.fullsize=="yes")this.lastlastslide.obj.style.display="none";     // fades screw up unless we do this
    if(this.fade!="yes" || !lastslide){
     if(lastslide)lastslide.obj.style.display="none";
     slide.obj.style.display="block";
     try{ slide.objbox.style.display="block"; }catch(e){}
    }else{
     if(this.fullsize=="yes"){
      setOpacity(slide.obj,0);
      slide.obj.style.display="block";
      fadeIn(slide.obj.id,0,4,30);
      fadeOut(lastslide.obj.id,100,20,120);
     }else{
      setOpacity(slide.objbox,0);
      slide.objbox.style.display="block";
      fadeIn(slide.objbox.id,0,4,30);
      fadeOut(lastslide.objbox.id,100,20,120);
     }
   }}
   this.post_update_hook();
   //this.selectCurrent(this.current);
   //try{parent.frames[0].selectCurrent(slide.src);}catch(e){}
   if(this.prefetch>0){
    var next, prev, count;
    next=this.current;
    prev=this.current;
    count=0;
    do{
     if(++next>=this.slides.length)next=0;
     if(--prev<0)prev=this.slides.length-1;
     this.slides[next].load();
     this.slides[prev].load();
    }while(++count<this.prefetch);
   }
  }

  //--------------------------------------------------
  this.loadVideo = function(slide,finished){
   setVidVars(this);
   var vid=slide.src.replace("@vid@","");
   try{ parent.frames[0].selectCurrent("@vid@"+vid); }catch(e){}
   try{ parent.parent.frames[0].selectCurrent("@vid@"+vid); }catch(e){}
   var tit=slide.title;
   this.playAnyVideo(vid,tit,finished);
  }


  //--------------------------------------------------
  this.playAnyVideo = function(vid,tit,finished){
   gLastTime2=(finished)?0:getVideoTime();   //only skip if the prev video was interrupted
   gLastVid2=gLastVid1;
   gLastTit2=gLastTit1;
   gLastVid1=vid;
   gLastTit1=tit;
   if(gUsePixi){
    //alert("playAnyVideo");
    return gPixiFrame.gotoImg("@vid@"+vid,tit,this.slide.pdir,1);
   }
   if(gMobile){
    var autoplay=(gPaused)?0:1;
    _obj("iPlayerDivRoot").innerHTML=
      "<IFRAME frameborder='0' style='height:100%; width:100%; ' "+
      "src='http://www.youtube.com/embed/"+vid+"?controls=1&autoplay=1"+gTakepic+"&showinfo=0&showsearch=0&modestbranding=1'>"+
      "</IFRAME>";
    return;
   }
   if(gIE || gUseFlash){  //IE uses FLASH (ugh!)
    if(!this.videoPlayerLoaded){
     loadPlayer(vid);
     this.videoPlayerLoaded=gVideoPlayerLoaded=1;
    }else{
     clearVideo();
     if(gLastNewState!="2" && gLastNewState!="5")loadVideo(vid);
     else cueVideo(vid);
    }
   }else{
    //alert("??? .playAnyVideo, videoPlayerLoaded="+this.videoPlayerLoaded+", gLastNewState="+gLastNewState);
    if(!this.videoPlayerLoaded){
     this.videoPlayerLoaded=gVideoPlayerLoaded=2;
     //alert("load playAnyVideo vid");
     loadApiPlayer(vid);
    }else{
     //alert("stop playAnyVideo vid");
     if(this.videoPlayerLoaded==2)return;  //still loading!
     stopVideo();
     //alert("gInPixi="+gInPixi+", gLastNewState="+gLastNewState);
     if(gLastNewState=="1")loadVideo(vid); 
     else cueVideo(vid); 

     //--- It always insists on starting when going to "next" so use this hack ---
     //alert("finished="+finished+", playing="+this.playing);
     //if(!finished && !this.playing)setTimeout("pauseVideo();",1200);  //still screws up... if playing then click anotherb thumb it stops next vid
     

  }}}


  //----------------------------------------------------------------------------
  this.addSlideObj = function(slide,forcemedia){
   if(slide.filetype=="video"){
    slide.obj=_obj("ssimage1");
    return;
   }
   if((slide.filetype=="image" || slide.filetype=="fimg") && this.fullsize=="no"){
    if(this.fade=="no"){
     slide.obj=_obj("ssimage1");
	 slide.objbox=_obj("ssimgbox1");	//????
    }else{
     var o1=_obj("ssimage1");
     var o2=_obj("ssimage2");
     var b1=_obj("ssimgbox1");
     var b2=_obj("ssimgbox2");
     if(!this.lastslide){
	 	slide.obj=o1;
		slide.objbox=b1;
     }else{
      if(this.lastslide.obj==o1){
	  	o2.style.display="";
		slide.obj=o2;
		slide.objbox=b2;
      }else{
	  	slide.obj=o1;
		slide.objbox=b1;
    }}}
    //alert(slide.src+","+slide.title+","+slide.pdir);
    if(gUsePixi){
     //alert("_play .addslideobj slide.src="+slide.src+", toolbarclicked="+gToolbarClicked);
     //if(!gToolbarClicked)gPixiFrame.gotoImg(slide.src,slide.title,slide.pdir);
     //else gPixiFrame.findImg(slide.src,slide.title,slide.pdir);
     if(!gFirstTime) return gPixiFrame.gotoImg(slide.src,slide.title,slide.pdir,null,null,forcemedia); 
     gFirstTime=0;
    }else{
     slide.src=slide.src.replace("../http://","http://");	//$$$$
     this.slide.obj.src=slide.src.replace("@fmg@","");	
	 if(gNaturalSize==1){
	 	if(!this.naturalSize(slide.obj)){
	 		var iw=this.slide.obj.width;
	 		var ih=this.slide.obj.height;
			var tmp=this.slide.obj.style;
	 		tmp.top="8%";
	 		tmp.height="";
			if(iw>ih){
	 			tmp.width="60%";
	 			tmp.left="20%";
			}else{
	 			tmp.width="36%";
	 			tmp.left="32%";
			}
		}
	 }
    }
    return;
   }
   var obj,id="ss_obj"+this.current;
   switch(slide.filetype){
   case "link":
   case "file":
   case "pixi":
    obj=_newObj("IFRAME");
    obj.style.width=obj.style.height="100%";
    obj.frameBorder=0;
    obj.margin=0;
    obj.border=0;
    if(slide.filetype!="pixi"){
     obj.src=slide.src.replace("@lnk@","");
    }else{
     var config=_getName(slide.src);
     //Note: inslideshow=0 because we are loading it as a regular page, not a slideshow within this slideshow
     obj.src="_pixi.php?dir="+slide.pdir+"&config="+config+"&inslideshow=0";
    }
    break;
   case "fimg":
   case "image":
    obj=_newObj("IMG");
    obj.src=slide.src.replace("@fmg@","");
   break;
   }
   obj.style.display="none";
   obj.style.zIndex=this.zindex;
   obj.id=id;
   obj.name=id;
   obj.className="theimg";
   _addObj(this.box,obj);
   slide.obj=obj;
  }

//------------------------- Natural Size  --------------------------------------
this.naturalSize = function(obj){
var sty=obj.style;
var iw=obj.width;
var ih=obj.height;
//var dw=obj.parent.offsetWidth;
//var dh=obj.parent.offsetHeight;
var dw=docWidth();
var dh=docHeight();
if(iw==0||ih==0)return 0;
if(dw<10)dw=10;
//console.log("1. naturalSize iw="+iw+", ih="+ih+", dw="+dw+", dh="+dh+"");  
while( (iw>dw) || (ih>dh) ){
  iw=(80/100)*iw;
  ih=(80/100)*ih;
}
//console.log("2. naturalSize iw="+iw+"px, ih="+ih+"px");  
iw=Math.round((iw/dw)*100)-10;
ih=Math.round((ih/dh)*100)-10;
if(iw<100 || ih<100)return 0;
sty.width =iw+"%";
sty.height=ih+"%";
sty.left  =((100-iw)/2)+"%";
sty.top   =((100-ih)/2)+"%";
//console.log("3. naturalSize iw="+iw+"%, ih="+ih+"%");  
return 1;
}


  //----------------------------------------------------------------------------
  this.add_slide = function(slide) {
   this.slides[this.slides.length]=slide;
   if(this.prefetch==-1)slide.load();
  }


  //--------------------------------------------------
  this.loop = function(force) {
  //NOTE: At some point we need to put the TIMER LOOP in pixi (when using pixi).
  //      This is because play.php only has one ss for images and pixi could have several 
  //      image screens loaded - some playing and some not!
  //ALSO: Since we have only one image thumblist we need to updae the current thumb
  //      based on which screen is current in pixi!
   var forcemedia=0;
   if(gUsePixi){  
    var pixiMedia=gPixiFrame.getCurrentMedia();
    if(this.media!=pixiMedia)forcemedia=1;
   }
   forcemedia=1;
   this.next(forcemedia);
   this.play();
  }


  //--------------------------------------------------
  //--- This SCHEDULES the next slide ----------------
  //--------------------------------------------------
  //NOTE: At some point we need to put the TIMER LOOP in pixi (when using pixi).
  //      This is because play.php only has one ss for images and pixi could have several 
  //      image screens loaded - some playing and some not!
  //ALSO: Since we have only one image thumblist we need to updae the current thumb
  //      based on which screen is current in pixi!
  this.play = function(timeout){
   if(this.slides.length==0)return;
   this.slide=this.slides[this.current];
   this.pause();
   this.playing=1;
   if(this.slide.filetype=="video"){
    playVideo();
    return;
   }
   if(timeout)this.timeout=timeout;
   if(typeof this.slides[this.current].timeout!='undefined')timeout=this.slides[this.current].timeout;
   else timeout=this.timeout;
   //alert(".play timeout="+timeout);
   this.timeoutid = setTimeout( this.name + ".loop()", timeout);
   this.playBtn.style.display="none";
   this.pauseBtn.style.display="block";
  }

  //--------------------------------------------------
  //--- This CANCELS the next scheduled slide --------
  //--- (and if video then pause it)          --------
  //--------------------------------------------------
  this.pause = function(){
   //alert("pause() playing="+this.playing);
   this.playing=0;
   if(this.slide.filetype=="video"){
    pauseVideo();
   }
   if(this.timeoutid!=0){
    clearTimeout(this.timeoutid);
    this.timeoutid=0;
   }
   this.playBtn.style.display="block";
   this.pauseBtn.style.display="none";
  }


  //----------------------------------------------------------------------------
  this.goto_slide = function(n){
   //alert("n="+this.slides[n].src);
   try{if(n==gPopupIx)return;}catch(e){}
   this.lastcurrent=this.current;
   if(n==-1)n=this.slides.length-1;
   if(n<this.slides.length && n>=0)this.current=n;
   //var src=this.slides[this.current].src;
   //alert("goto_slide("+n+") usePixi="+gUsePixi+", inPixi="+gInPixi+", playing="+this.playing+"src="+src);
   if(this.playing){
    this.pause();
    this.update();
    this.play();
   }else{
    this.update();
  }}


  //----------------------------------------------------------------------------
  this.load_slide = function(src) {
   // This finds a slide using the src (url)
   src=_rep(src,"_tn.",".");
   //if(this.slides[this.current].src==src)return 1;  //current src not always what is loaded in pixi!
   for(var i=0;i<this.slides.length;i++){
    //if(_in(this.slides[i].src,"alexkna"))alert("load_slide() "+this.slides[i].src+" == "+src);
    var src1=this.slides[i].src.replace("@fmg@","");
    var src2=src.replace("@fmg@","");
    if(src1==src2){
     this.goto_slide(i);
     return 1;
   }}
   return 0;
  }



  //--------------------------------------------------
  this.goto_random_slide = function(include_current){
   var i=this.get_random_slide(include_current);
   this.goto_slide(i);
  }

  //--------------------------------------------------
  this.get_random_slide = function(include_current){
   var i;
   var lastc=(this.lastcurrent && this.slides.length>2)?1:0;
   if(this.slides.length<2)return 0;
   do{
    i=Math.floor(Math.random()*this.slides.length);
   }while((i==this.current || (lastc && i==this.lastcurrent)) && !include_current);
   return i;
  }



  //--------------------------------------------------
  this.next = function(forcemedia){
   gToolbarClicked=1;
   if(this.slides.length<2)return;
   if(this.slide.filetype=="video"){
    this.nextVid();
    return;
   }
   this.lastcurrent=this.current;
   //if(this.current<this.slides.length-1 && this.current<(this.maxobjects+1))this.current++;
   if(this.current<this.slides.length-1)this.current++;
   else this.current=0;
   //alert("next fm="+forcemedia);
   this.update(null,null,forcemedia);
  }


  //--------------------------------------------------
  this.nextVid = function(finished){
   gToolbarClicked=1;
   //alert("nextVid()");
   var lastc=this.lastcurrent;
   var curr=this.current;
   this.lastcurrent=this.current;
   //if(this.current<this.slides.length-1 && this.current<(this.maxobjects+1))this.current++;
   if(this.current<this.slides.length-1)this.current++;
   else this.current=0;
   var nexttime=gNextTime;
   if(gNextVid){
    var newvid=this.slides[this.current].src.replace("@vid@","");
    if(gNextVid!=newvid){
     queVideo(gNextVid,gNextTit);
     playQue();
     if(!finished)setVideoTime(nexttime);  //only skip if the prev video was interrupted
     this.lastcurrent=lastc;
     this.current=curr;
     gNextVid="";
     gNextTit="";
     return;
   }}
   if(playQue())return;
   //alert("??? .nextVid, gNextVid="+gNextVid+", finished="+finished);
   this.update(finished);

   //--- Tried this to fix the bug where vid plays when you click the next btn
   //--- even tho the current vid is paused. No joy.  No idea why!
   //--- Also, the setVideoTime does not work if you go back to a prev. playing vid.
   //alert("using goto_slide()");     
   //this.goto_slide(this.current);   //?????

   setVideoTime(nexttime);
   gNextTime=0;
   gNextVid="";
   gNextTit="";
  }



  //--------------------------------------------------
  this.prior = function(){
   /*
   if(gUsePixi){  
    var currentPixiMedia=gPixiFrame.getCurrentMedia();
    var currentSsMedia=ss.typ.replace("s",""); //hack
    if(currentSsMedia!=currentPixiMedia){
     // different media so no sense in getting the prior vid/image
     gPixiFrame.gotoPrior();
     return;
   }}
   */
   gToolbarClicked=1;
   if(this.slides.length<2)return;
   if(this.slide.filetype=="video"){
    this.priorVideo();
    return;
   }
   this.lastcurrent=this.current;
   if(this.current>0)this.current--;
   else{
    var i=this.slides.length-1;
    this.current=i;
   }
   this.update();
  }


  //--------------------------------------------------
  this.priorVideo = function(){
   gToolbarClicked=1;
   var lastc=this.lastcurrent;
   var curr=this.current;
   var vid=this.slide.src.replace("@vid@","");
   var tit=this.slide.title;
   gNextTime=getVideoTime();  
   gNextVid=vid;
   gNextTit=tit;
   //alert("prev: "+gNextTit+","+gNextTime);
   this.lastcurrent=this.current;
   if(this.current>0)this.current--;
   else{
    var i=this.slides.length-1;
    //if(i>this.maxobjects)i=this.maxobjects;
    this.current=i;
   }
   var lasttime=gLastTime2;
   if(gLastVid2){
    var newvid=this.slides[this.current].src.replace("@vid@","");
    if(gLastVid2!=newvid){
     queVideo(gLastVid2,gLastTit2);
     playQue();
     setVideoTime(lasttime);
     this.lastcurrent=lastc;
     this.current=curr;
     gLastVid1=gLastVid2="";
     gLastTit1=gLastTit2="";
     return;
   }}
   this.update();
   setVideoTime(lasttime);
   gLastTime2=0;
   gLastVid1=gLastVid2="";
   gLastTit1=gLastTit2="";
  }


  //--------------------------------------------------
  this.PauseOrPlay = function(p){
   gToolbarClicked=1;
   var pb=this.playBtn;
   var paused=(pb.style.display.toLowerCase()=="block")?1:0;
   if(p!=null && p==paused)return;
   if(gUsePixi)gPixiFrame.updPlayBtn(paused);
   if(!paused){
    this.setButtons(1);
    this.pause();
   }else{
    this.setButtons(0);
    if(this.typ!="videos")this.next();
    this.play();
  }}


  //--------------------------------------------------
  this.setButtons = function(on,applyupwards,donotapplydownwards){   //??????
   //console.log('setButtons on='+on);
   if(!donotapplydownwards && gUsePixi)gPixiFrame.setButtons(this.media,on);
   if(applyupwards && gInPixi)parent.setParentButtons(this.media,on);
   var pb1=this.playBtn;
   var pb2=this.pauseBtn;
   pb1.style.display=(on==1)?"block":"none";
   pb2.style.display=(on==1)?"none":"block";
   this.playing=(pb1.style.display.toLowerCase()=="block")?0:1;
   //console.log('this.playing='+this.playing);
  }



  //--------------------------------------------------
  this.shuffle = function(){
   if(this.slides.length<3)return;
   var i, i2, slides_copy, slides_randomized;
   slides_copy = new Array();
   for(i=0; i<this.slides.length; i++)slides_copy[i]=this.slides[i];
   slides_randomized=new Array();
   do{
     i=Math.floor(Math.random()*slides_copy.length);
     slides_randomized[slides_randomized.length]=slides_copy[i];
     for(i2=i+1; i2<slides_copy.length; i2++)slides_copy[i2-1]=slides_copy[i2];
     slides_copy.length--;
   }while(slides_copy.length);
   this.slides=slides_randomized;
  }


  //--------------------------------------------------
  this.get_text = function(){
   return(this.slides[ this.current ].text);
  }


  //--------------------------------------------------
  this.get_all_text = function(before_slide, after_slide){
   // Return the text for all of the slides.
   // For the text of each slide, add "before_slide" in front of the
   // text, and "after_slide" after the text.
   // For example:
   // document.write("<ul>");
   // document.write(s.get_all_text("<li>","\n"));
   // document.write("<\/ul>");
   all_text = "";
   for(i=0; i<this.slides.length; i++){
    slide = this.slides[i];
    if(slide.text)all_text += before_slide + slide.text + after_slide;
   }
   return(all_text);
  }

  //--------------------------------------------------
  this.hotlink = function(rbtn){
   this.slides[this.current].hotlink(rbtn);
  }


  //--------------------------------------------------
  this.save_position = function(cookiename) {
   if(!cookiename)cookiename=this.name + '_slideshow';
   document.cookie = cookiename + '=' + this.current;
  }


  //--------------------------------------------------
  this.restore_position = function(cookiename) {
   if(!cookiename)cookiename = this.name + '_slideshow';
   var search = cookiename + "=";
   if(document.cookie.length>0){
    offset=document.cookie.indexOf(search);
    if(offset!=-1){
     offset += search.length;
     end=document.cookie.indexOf(";", offset);
     if(end==-1)end=document.cookie.length;
     this.current=parseInt(unescape(document.cookie.substring(offset,end)));
  }}}




  //--------------------------------------------------
  /*
  this.loadDD = function(){
   var s=this.DD,a,src,tit;
   s.options.length = 0;
   for(var i=0;i<this.slides.length;i++){
    s.options[i]=new Option();
    src=this.slides[i].src;
    tit=this.slides[i].title;
    a=src.split("/");
    //s.options[i].text=_rep(a[a.length-1],"@vid@","");
    s.options[i].text=tit;
    s.options[i].value=src;
   }
   s.selectedIndex=this.current;
  }
*/



  //--------------------------------------------------
  this.loadThumbs = function(){
   //alert("ss.loadThumbs id="+ss.thumbox.id);
   var a,src,tit,typ,pdir,thmb;
   var css1=css2=vstart=vend="";
   //var txt="<DIV class='c_thumbox' style='";
   var txt="<TABLE class='c_thumbox' style='";
   var hh=docHeight();
   var ww=docWidth();

   if(ss.thumbsalign=="top" || ss.thumbsalign=="bottom"){
    if(this.typ=="files" || this.typ=="links" || this.typ=="pixis"){
     hh=Math.round(hh/32);
     ww=Math.round(100);
    }else{
     hh=Math.round(hh/11.8);
     ww=Math.round(hh*1.35);
    }
    css1="height:"+hh+"px; width:"+ww+"px; padding:3px; float:left;";
    css2="height:"+hh+"px; width:"+ww+"px; cursor:pointer;";
    txt+="height:86%;"+((ss.thumbsalign=="bottom")?"position:relative;top:"+Math.round(hh/15)+"px;":"")+"'>";
    txt+="<tr>";
    vstart="<td valign=top align=center>";
    vend="</td>";
   }else{
    if(this.typ=="files" || this.typ=="links" || this.typ=="pixis"){
     ww=Math.round(ww/11);
     hh=Math.round(22);
    }else{
     ww=Math.round(ww/11.5);
     hh=Math.round(ww*0.70);
    }
    css1="height:"+hh+"px; width:"+ww+"px; padding:2px; float:left;";
    css2="height:"+hh+"px; width:"+ww+"px; cursor:pointer;";
    txt+="width:98%;'>";
    vstart="<tr><td align=center>";
    vend="</td></tr>";
   }
   for(var i=0; i<this.slides.length; i++){
    tit=this.slides[i].title;
    src=this.slides[i].src;
    pdir=this.slides[i].pdir;
    typ=_fileType(src);
    switch(typ){
     case "fimg":
      src=src.replace("@fmg@","");
      tit=tit.replace("@fmg@","");
     case "image":
	  src=src.replace("../http://","http://");
	  //msg("src="+src);//$$$$
      txt+=vstart+"<DIV id=box_"+this.id+"_"+i+" style='"+css1+"' onclick='gToolbarClicked=0;ss.goto_slide("+i+")' title='"+getFile(tit)+"'>";
      txt+="<img id=o_"+this.id+"_"+i+" style='background:white;"+css2+"' src='"+((_in(src,"http://"))?src:getThumb(src))+"' ";
      //txt+="<img id=o_"+this.id+"_"+i+" style='background:white;"+css2+"' src='"+((typ=="fimg")?src:getThumb(src))+"' ";
      if(gID)txt+=" oncontextmenu='return false;' onmousedown='popup(event,"+i+",\""+typ+"\")'><DIV id=popup_"+this.id+"_"+i+" class=c_popupmnu style='display:none;<?=$popozie;?>'></DIV>";
      else txt+=">";
      txt+="</DIV>"+vend;
      break;
     case "video":
      src=src.replace("@vid@","");
      tit=tit.replace("@vid@","");
      txt+=vstart+"<DIV id=box_"+this.id+"_"+i+" style='"+css1+"' onclick='ss.goto_slide("+i+")' title='"+tit+"'>";
      txt+="<img  id=o_"+this.id+"_"+i+" style='"+css2+"' src='http://img.youtube.com/vi/"+src+"/default.jpg' ";
      if(gID)txt+=" oncontextmenu='return false;' onmousedown='popup(event,"+i+",\"vid\")'><DIV id=popup_"+this.id+"_"+i+" class=c_popupmnu style='display:none;<?=$popozie;?>'></DIV>";
      else txt+=">";
      txt+="</DIV>"+vend;
      break;
     case "link":
     case "file":
     case "pixi":
      txt+=vstart+"<DIV id=box_"+this.id+"_"+i+" style='"+css1+"' onclick='gToolbarClicked=0;ss.goto_slide("+i+")' style='text-align:center;'>";
      txt+="<SPAN id=o_"+this.id+"_"+i+" class=lnk <?=$lnkmovers;?> style='"+css2+"padding:2px; width:98%;' ";
      var tmp=(typ=="file" || typ=="pixi")?_getName(getFile(src)):tit;
      if(gID)txt+=" oncontextmenu='return false;' onmousedown='popup(event,"+i+",\""+typ+"\")'>&nbsp;"+tmp+"&nbsp;</SPAN><DIV id=popup_"+this.id+"_"+i+" class=c_popupmnu style='display:none;<?=$popozie;?>'></DIV>";
      else txt+=">&nbsp;"+tmp+"&nbsp;</SPAN>";
      txt+="</DIV>"+vend;
      break;
    }
   }
   //txt+="</DIV>";
   if(ss.thumbsalign=="top" || ss.thumbsalign=="bottom")txt+="</tr>";
   txt+="</TABLE>";
   try{this.thumbox.innerHTML=txt;}catch(e){}
   //alert("loadThumbs id="+this.thumbox.id+", html="+this.thumbox.innerHTML);
  }

  //--------------------------------------------------
  this.selectCurrent = function(i){
   var thumb=_obj("box_"+this.id+"_"+i);
   try{
    if(this.currentThumb)this.currentThumb.style.background="";
    thumb.style.background="<?=$sshicolor;?>";
    this.currentThumb=thumb;
    if(gInPixi)parent.hiliteParentThumb(this.media,i);
    //scrollThumbs(this.current);  
   }catch(e){}
  }



}


//======================================== page load functions =====================================



function addNewSlide(src,title,pdir,text){
var s=new slide();
var typ=_fileType(src);
s.filetype=typ;
if((typ=="image" || typ=="file" || typ=="pixi") && !_in(src,"<?=$gPath;?>"))src="<?=$gPath;?>"+src;
s.text=s.link=s.src=src;
src=src.replace("../http://","http://");
//s.title=title.substr(0,11);
s.title=title;
s.target="ss_popup";
s.attr="fullscreen=yes,width=100%,height=100%,resizable=yes,scrollbars=yes";
s.pdir=pdir;
s.text=text;
ss.add_slide(s);
return s;
}


//------- resize -------
var gResizeEndTimer;

function resize(){
clearTimeout(gResizeEndTimer);
gResizeEndTimer = setTimeout(function(){resizeEnd();}, 200);
}

function resizeEnd(){
ss.loadThumbs();
ss.selectCurrent(ss.current);
}


</script>




<SCRIPT>

//============================== Que functions =================================
var gQue=new Array();
var gQueTitles=new Array();


//-------------------- playQue() -----------------------
function playQue(){
var a,vid,title;
if(gQue.length<1)return 0;
vid=gQue[gQue.length-1];
title=gQueTitles[gQueTitles.length-1];
gQue.pop();
gQueTitles.pop();
if(!vid)return 0;
vid=vid.replace("@vid@","");
title=_rep(title,"&amp amp","&");
title=_rep(title,"& amp","&");
//gTitleBox.innerHTML=title;
//_obj("iFunUrl").value="http://"+gDomain+"/?vid="+vid;
setQueVidVars(vid,title);
ssv.playAnyVideo(vid,title);
//var j=ssv.findVid(vid);
//if(j>-1){ try{ ssv.hiliteThumb(j); scrollThumbs(j); }catch(e){} }
return 1;
}


function setQueVidVars(vid,title){
gVidSS=ss;
gVid=vid;
gVidKey=gVid.replace("@vid@","");
gTitle=title;
gList=ssv.slide.playlist;
if(!gList)gList=gRoot;
gDefSrc=gVid;
}


function queVideo(vid,tit){
if(!alreadyQued(vid)){
 gQue[gQue.length]=vid;
 gQueTitles[gQueTitles.length]=tit;
}}


function alreadyQued(vid){
for(var i=0; i<gQue.length; i++){ if(gQue[i]==vid)return 1; }
return 0;
}


//=========================== GENERIC VIDEO SCRIPTS ============================
var gTmpVid, gPaused;
var ytplayer,gUseFlash=<?=$useFlash;?>,gLastNewState;
var gVideoPlayerLoaded=0;
var gVid=gVidKey="";
var gLastVid1, gLastTit1;
var gLastVid2, gLastTit2, gLastTime2;
var gNextVid,  gNextTit,  gNextTime;
var gChromeless='<?=$chromeless;?>';

//======== sets the vars for the ACTUAL VIDEO (not necessarily the loaded playlist or current thumb) =====
function setVidVars(ss){
//Note: gList is the playlist for the current VIDEO, ssv.playlist is the playlist for the current SLIDESHOW
gVidSS=ssv;
gVid=ssv.slide.src;
gVidKey=gVid.replace("@vid@","");
gTitle=ssv.slide.title;
gDefSrc=gVid;

//--- these are used because the whole thing is fucked! (when using flash) ---
gJustPaused=0;
gJustPlayed=0;
}


function onPlayerStateChange(evt){
//0=video finished, -1=player loaded, 1=video started, 2=video paused, 5=player loaded
var newState=evt.data;
if(newState==null)newState=evt;  //flash just passes the code
newState+="";
//console.log('newState='+newState+', lastState='+gLastNewState+', gJustPaused='+gJustPaused);
var lastState=gLastNewState;
gLastNewState=newState;
//alert("gUsePixi="+gUsePixi+", newstate="+newState);
switch(""+newState){
 case "0":
  //alert("??? onPlayerStateChange=0");
  ssv.nextVid(1);
  break;
 case "1":
  if(gJustPaused==1){ gJustPlayed=0; pauseVideo(); return; }
  gJustPlayed=1;
  setTimeout("gJustPlayed=0;",500);
  gPaused=0;
  ssv.setButtons(0,1);
  try{clearTimeout(gTitleTimer);}catch(e){}
  break;
 case "2":
  if(gJustPlayed==1 || lastState==3){ gJustPaused=0; playVideo(); return; }
  gJustPaused=1;
  setTimeout("gJustPaused=0;",500);
  gPaused=1;
  ssv.setButtons(1,1);
  try{clearTimeout(gTitleTimer);}catch(e){}
  break;
 case "3":

  break;
}}

function playVideo(){
//if(gFirstPause==1){ gFirstPause++; console.log("CANCEL PLAY"); }
//console.log('playVideo');
ssv.setButtons(0);
if(gUsePixi)gPixiFrame.playVideo();
else{ if(ytplayer)ytplayer.playVideo(); }
}


function pauseVideo(){
//console.log('pauseVideo');
ssv.setButtons(1);
if(gUsePixi){ gPixiFrame.pauseVideo(); }
else{ if(ytplayer)ytplayer.pauseVideo(); }
//if(!gFirstPause)gFirstPause=1; else gFirstPause=2;
}


function onPlayerError(errorCode){
alert("error=" + errorCode);
}

function clearVideo(){
if(ytplayer)ytplayer.clearVideo();
}

function loadVideo(vid){
if(ytplayer)ytplayer.loadVideoById(vid);
}

function cueVideo(vid){
if(ytplayer)ytplayer.cueVideoById(vid);
}


function getVolume(){
if(gUsePixi){ return gPixiFrame.getVolume(); }
if(!ytplayer)return;
var v=ytplayer.getVolume();
v=Math.round(v);
return v;
}

function setVolume(v){
if(gUsePixi){ gPixiFrame.setVolume(v); return; }
if(!ytplayer)return;
ytplayer.setVolume(v);
}


function getVideoTime(){
try{ if(gUsePixi){ return gPixiFrame.getVideoTime(); } }catch(e){ return 0; }
if(!ytplayer)return;
return ytplayer.getCurrentTime();
}

function setVideoTime(t){
if(gUsePixi){ gPixiFrame.setVideoTime(t); return; }
if(!ytplayer)return;
ytplayer.seekTo(t,1);
}


//========================= FLASH ONLY VIDEO SCRIPTS ===========================

// This is automatically called by the player once it loads
function onYouTubePlayerReady(playerId) {
//alert("onYouTubePlayerReady gTmpVid="+gTmpVid);
ytplayer=_obj("ytplayer");
//setInterval(updatePlayerInfo, 250);
ytplayer.addEventListener("onStateChange", "onPlayerStateChange");
ytplayer.addEventListener("onError", "onPlayerError");
if(gChromeless=='yes'){
 ytplayer.loadVideoById(gTmpVid);
}
setVidVars(ss);
}


function loadPlayer(vid) {
//alert("loadPlayer vid="+vid);
gTmpVid=vid;
var autoplay=(ssv.autostart=="yes")?1:0;
autoplay=1; //always auto start videos
var params = { allowScriptAccess: "always", allowFullScreen: "true", border:0, egm:1, fs:1, iv_load_policy:3, rel:0 };
var atts = { id: "ytplayer" };
if(gChromeless=='yes'){
 swfobject.embedSWF("http://www.youtube.com/apiplayer?" +
                    "&enablejsapi=1&autoplay="+autoplay+gTakepic+"&playerapiid=player1",
                    "iPlayerDiv", "100%", "100%", "8", null, null, params, atts);
}else{
 swfobject.embedSWF("http://www.youtube.com/v/"+vid+"?border=0&enablejsapi=1&playerapiid=ytplayer"+
                    "&autoplay="+autoplay+gTakepic+"&fs=1&iv_load_policy=3&egm=0&rel=0&color1=0xffffff&color2=0xffffff",
                    "iPlayerDiv", "100%", "100%", "8", null, null, params, atts);
}}



//============================= NO FLASH SCRIPTS ===============================

function loadApiPlayer(vid,controls){
gVid=vid.replace("@vid@","");
var tag = document.createElement('script');
tag.src = "http://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function onYouTubePlayerAPIReady() {
var ctrls=(gChromeless=='yes')?0:1;
ytplayer = new YT.Player('iPlayerDiv', {
videoId: gVid,
playerVars: { allowScriptAccess: "always", controls: ctrls,  allowFullScreen: "true", start: gTakePicStart,  border:0, egm:0, fs:1, iv_load_policy:3, rel:0 },
events: {
 'onReady': onApiPlayerReady,
 'onStateChange': onPlayerStateChange
}});
}

function onApiPlayerReady(evt) {
ssv.videoPlayerLoaded=gVideoPlayerLoaded=1;
evt.target.playVideo();
}

function stopVideo(){
ytplayer.stopVideo();
}


//======================= Utility Functions ==================

function idocWidth(){
//var w=document.body.clientWidth;
var w=document.documentElement.clientWidth;
if(w)return w;
return $(window).width();
}

function idocHeight(){
//var h=document.body.clientHeight;
var h=document.documentElement.clientHeight;
if(h)return h;
return $(window).height();
}

</SCRIPT>



<div id=xxxx style='display:none;position:absolute;top:0;left:0;width:220;background:pink;z-index:999999;'></div>
</BODY></HTML>
<?

//================================= PHP FUNCTIONS ===================================



//------------------------------ get objects for all dirs ----------------------
function getAllObjects($includesubdirs,$dir,&$objects,&$titles,&$pdirs){
global $gAdmin,$gRoot,$gPath,$fimgfile,$videofile,$linkfile,$ssitems,$iframes,$allvids,$ssdropdown,$src;
$dirpath=$gPath.$dir;
$dirlink=getDir($dir);
if(!empty($dirlink))$dirlink=$dirlink."/";
$istart=count($objects);
$srcfound=0;
//--- process the sub-directories ---
$dir_handle=@opendir($dirpath) or die("Unable to open $dirpath");
$filenames=array();
while($filename=readdir($dir_handle))$filenames[]=$filename;
if($includesubdirs=="yes"){
 foreach($filenames as $filename){
  //NB: we don't load pvt subdirs even if logged in as admin, but if we goto that subdir it will play them
  if(myFileType($filename)!="folder" || _in($filename,".") || _in($filename,"_pvt"))continue;
  if($filename=="" || $filename=="error_log" || $filename=="cgi-bin" || $filename=="testing")continue;
  getAllObjects($includesubdirs,$dirlink.$filename,$objects,$titles,$pdirs);
}}
//--- get images file ---
if($ssitems=="all" || $ssitems=="images" || $ssitems=="fimgs"){
 $txt=myReadFile($dirlink.$fimgfile);
 $a=explode(",",$txt);
 for($i=0;$i<count($a)-1;$i++){
  $b=explode(";",$a[$i]);
  $fmgid=$b[0];
  if(_in($fmgid,"@fmg@")){
   if(count($b)>1)$fmgtit=_rep($b[1],"'","");
   else $fmgtit=$fmgid;
   $objects[]=$fmgid;
   $titles[] =_rep($fmgid,"@fmg@","");
   $pdirs[]  =$dirlink;
   if($src==_rep($fmgid,"@fmg@",""))$srcfound=1;
 }}
 if($src && !$srcfound){
  $objects[]=$src;
  $titles[] ="";
  $pdirs[]  ="";
 }
}


//--- get videos file ---
if($ssitems=="all" || $ssitems=="videos"){
 $vidfound=0;
 $txt=myReadFile($dirlink.$videofile);
 //msg($dirlink.$videofile);
 $a=explode(",",$txt);
 for($i=0;$i<count($a)-1;$i++){
  $b=explode(";",$a[$i]);
  $vidid=$b[0];
  if(_in($vidid,"@vid@")){
   if($src==$vidid)$vidfound=1;
   if("@vid@".$src==$vidid)$vidfound=1;
   if(count($b)>1)$vidtit=_rep($b[1],"'","");
   else $vidtit=$vidid;
   if(!_in($allvids,",".$vidid.",")){
    $allvids.=$vidid.",";
    $objects[]=$vidid;
    //if($ssdropdown=="yes")$titles[]=$vidtit;
    //else $titles[]="";
    $titles[]=$vidtit;
    $pdirs[]  =$dirlink;
 }}}
 //exit("vidfound=".$vidfound.", src=".$src);
 if($src && !$vidfound){
  $allvids.="@vid@".$src.",";
  $objects[]="@vid@".$src;
  $titles[] ="";
  $pdirs[]  ="";
 }
}

//--- get links file ---
if($ssitems=="all" || $ssitems=="links"){
 $txt=myReadFile($dirlink.$linkfile);
 //msg($dirlink.$videofile);
 $a=explode(",",$txt);
 for($i=0;$i<count($a)-1;$i++){
  $b=explode(";",$a[$i]);
  $lnkid=$b[0];
  if(_in($lnkid,"@lnk@")){
   if(count($b)>1)$lnktit=_rep($b[1],"'","");
   else $lnktit=$lnkid;
   $objects[]=$lnkid;
   $titles[] =$lnktit;
   $pdirs[]  =$dirlink;
}}}



//--- get other valid files ---
if($ssitems!="videos" && $ssitems!="links"){
 $dir_handle=@opendir($dirpath) or die("Unable to open $dirpath");
 $filenames=array();
 while($filename=readdir($dir_handle))$filenames[]=$filename;
 sort($filenames);
 foreach($filenames as $filename){
  if(_in($filename,"_tn.") || (_in($filename,"_pvt") && !$gAdmin) || (_ix($filename,"index.")==0 && $dir==""))continue;
  $typ=myFileType($filename);
  //wrtb($ssitems.": ".$filename." is a ".$typ);
  if($ssitems!="fimgs" || $typ!="image"){
   if($ssitems!="all" && !_in($ssitems,$typ))continue;
  }
  if(validSSFile($filename)){
   $objects[]=$dirlink.$filename;
   $titles[] =$gRoot.$dirlink.$filename;
   $pdirs[]  =$dirlink;
   if($src==$dirlink.$filename)$srcfound=1;
 }}
 if($src && !$srcfound){
  $objects[]=$src;
  $titles[] ="";
  $pdirs[]  ="";
 }
}
//exit();


//---- refresh all vidoes file ---
/*
if($includesubdirs=="yes"){
 $alltxt="";
 $pdir="";
 for($i=$istart;$i<count($objects);$i++){
  if($objects[$i] && _in($objects[$i],"@vid@")){
   if($pdirs[$i]!=$pdir){
    $pdir=$pdirs[$i];
    $alltxt.="??".$pdir;
   }
   $alltxt.=",".$objects[$i].";".$titles[$i];
  }
 }
 myWriteFile($dirlink."_pvt_allvideos.txt",$alltxt);
 return $alltxt;
}
*/

}



//-------------------- PLAYLIST FROM TEXT STRING  ------------------------------

function getTxtObjects($plist,&$objects,&$titles,&$pdirs){
$a=_split($plist,",");
for($i=0;$i<count($a);$i++){
 if(_in($a[$i],";")){
  $b=_split($a[$i],";");
  $objects[]=$b[0];
  $titles[] =$b[1];
  $pdirs[]  ="";
}}}




//------------------------- ON THE FLY PLAYLISTS -------------------------------

function getAutoObjects($find,&$objects,&$titles,&$pdirs){
//$tmptitles="";
//$cnt=0;
$rawtxt=",";
$url="http://gdata.youtube.com/feeds/videos?vq="._rep($find," ","%20");
$url.="&category=music&start-index=1&racy=include&safesearch=none&format=5&max-results=30";
$txt=getUrl($url);
$a=_split($txt,"<content type='html'>");
for($i=1;$i<count($a);$i++){
 $b=_split($a[$i],"</content");
 $tmp=$b[0];
 $tmp=_rep($tmp,"&lt;","<");
 $tmp=_rep($tmp,"&gt;",">");
 getYoutubeDetails($tmp,$tid,$tit,$tim,$desc,$views);
 //alert($tit);
 $t=strtolower($tit);
 $t=_rep($t,strtolower($find),"");
 $objects[]="@vid@".$tid;
 $titles[] =$tit;
 $pdirs[]  ="";
 $rawtxt.="@vid@".$tid.";".$tit.",";
}
$_SESSION["lastautoplaylist"]=$rawtxt;
}


//------------------------------- load the texts trings ------------------------
function loadTextStrings($objects,$titles,$pdirs,&$imgtxt,&$tittxt,&$dirtxt,$ddoptions){
$cnt=count($objects);
for($i=0;$i<$cnt;$i++){
 $imgtxt.="\n'"._rep($objects[$i],"'","`")."',";
 $tittxt.="\n'"._rep($titles[$i],"'","`")."',";
 $dirtxt.="\n'"._rep($pdirs[$i],"'","`")."',";
}
$imgtxt=str_replace("@fmg@","",$imgtxt);
$imgtxt=$imgtxt.",";
$imgtxt=str_replace(",,","",$imgtxt);
$tittxt=$tittxt.",";
$tittxt=str_replace(",,","",$tittxt);
$dirtxt=$dirtxt.",";
$dirtxt=str_replace(",,","",$dirtxt);
if($imgtxt==",")$imgtxt="";
if($tittxt==",")$tittxt="";
if($dirtxt==",")$dirtxt="";
return $cnt;
}




//------------------------- validSSFile --------------------------------
function validSSFile($s){
$a=array('.svg','.gif','.jpeg','.jpg','.bmp','.png','.html','.htm','.ixi');
$s=strtolower(get_ext($s));
for($i=0;$i<count($a);$i++){if($s==$a[$i])return true;}
return false;}



function fix4sub($x){return strtolower(_rep($x," ","_"));}




//============================= create the player box ==========================
function createBigBox($pos){
global $dir,$ssitems,$fade,$ssfullsize,$frameimage,$frameopacity,$theimg,$ssusepixi,$src,$source,$config,$ssborder,$bordercolor;

$border=($ssborder=="yes")? "border:solid 1px ".$bordercolor.";" : "";
//wrt("<script>alert('frameimage=".$frameimage."');</script>");
echo("<DIV id='ss_box' onmousedown='myHotlink(event)' style='position:absolute;".$border.$pos."'>");
//wrtb($ssusepixi.",".$ssitems);

//--- NOTE: 'thimg' class does not seem to work so we override with this ---
//$imgstyle="left:8%;top:8%;width:84%;height:80%;";
$imgstyle="left:0px;top:0px;width:100%;height:100%;";

//---- USEPIXI  --------
if($ssusepixi=="yes"){
 $tmptyp=($ssitems!="videos")?"":"&sstype=videos";
 echo("<TABLE cellpadding=0 cellspacing=0 style='left:0px;top:0px;width:100%;height:100%;'><tr><td align=center valign=middle>");
 echo("<IFRAME class='theimg' id='ssimage1' src='_pixi.php?inslideshow=1&dir=".$dir.$tmptyp."&source=".$source."&src="._rep($src,"http://","http_")."&config=".$config."' frameborder='0' border='0' allowTransparency='true'></IFRAME>");
 echo("</td></tr></TABLE>");
}else{
//---- NO PIXI -------
 if($ssitems=="videos"){
  echo("<DIV id='ssimage1' style='display:block;z-index:1;left:0px;top:0px;height:100%;width:100%;'>");
  echo("<DIV id='iPlayerDivRoot' style='position:absolute;width:100%;height:100%;'>");
  echo("<DIV id='iPlayerDiv' style='position:absolute;width:100%;height:100%;'>");
  echo("</DIV></DIV></DIV>");
 }
 //exit($ssitems." , ".$ssfullsize." , ".$frameimage);
 //if(($ssitems=="images" || $ssitems=="fimgs") && $ssfullsize=="no"){
 if($ssitems=="images" || $ssitems=="fimgs") {
  if($fade=="no"){
   echo("<TABLE cellpadding=0 cellspacing=0 style='position:absolute; ".$imgstyle." '><tr><td align=center valign=middle>");
   echo("<IMG id='ssimage1' class='theimg'>");  
   echo("</td></tr></TABLE>");
  }else{
   echo("<TABLE id='ssimgbox1' cellpadding=0 cellspacing=0 style='z-index:1;position:absolute; ".$imgstyle." '><tr><td align=center valign=middle>");
   echo("<IMG id='ssimage1' class='theimg' src='_pvt_images/black.png' style='display:none;'>");
   echo("</td></tr></TABLE>");
   echo("<TABLE id='ssimgbox2' cellpadding=0 cellspacing=0 style='z-index:1;position:absolute; ".$imgstyle." '><tr><td align=center valign=middle>");
   echo("<IMG id='ssimage2' class='theimg' src='_pvt_images/black.png' style='display:none;'>");
   echo("</td></tr></TABLE>");
 }}
 if($frameimage){
  $tmp=($frameopacity)?"opacity:".($frameopacity/100).";":"";
  echo("<IMG id='ss_frame' src='$frameimage' style='z-index:99; pointer-events:none; $tmp position:absolute;top:0px;left:0px;width:100%;height:100%;'>");
}}
echo("</DIV>");
}




//------------------------- create the toolbar --------------------------  (see flipthumbs)
function createToolbar(){
global $auto,$ssitems,$iframes,$tmptbfade,$ssfullsize,$toolbaralign,$ssthumbsalign,$ssshowthumbbdrs;
//if($toolbaralign=="bottom")$pos="left:32%;bottom:2px;width:36%;";
//else $pos="left:32%;top:2px;width:36%;";
//echo("<DIV id=itoolbar style='position:absolute;".$pos.";z-index:99999;' ".$tmptbfade." >");

if($toolbaralign=="bottom")$pos="bottom:".(($ssitems=="videos")? "60px;" : "5px;");
else $pos="top:2px;";
echo("<DIV id=itoolbar style='position:absolute;width:100%;".$pos.";z-index:99999;' ".$tmptbfade." >");
?>

<div style='display:<? if($ssshowthumbbdrs!="yes")wrt("none"); ?>;'>
<img onclick='ss.flipThumbs();' class='corners4' src='_pvt_images/thumbs.png' style='position:absolute;bottom:3px;left:3px;cursor:pointer;width:26px;height:20px;padding-left:5px;padding-right:5px;' >
</div>

<CENTER>
<TABLE border=0 class='toolbar1' cellspacing=0 cellpadding=0 style='width:150px;'>
<TR>
<td>&nbsp;&nbsp;&nbsp;</td>
<TD>
<TABLE ID='ss_controls' class='' cellspacing=0 cellpadding=0 style='position:relative;left:0px;'>
<tr>
<td valign=bottom align=center><div id=iPrevDiv class="gzbtn" onclick='ss.prior();' onmouseover='_sshilite(this)' onmouseout='_sslolite(this)' style='text-align:center;'>
<center><img src='_pvt_images/rewind.png' style='display:block;width:16px;'></center></div></td>

<td style='width:3px;'></td>

<td valign=bottom align=center><div id=iPlayDiv class="gzbtn" onclick='ss.PauseOrPlay()' onmouseover='_sshilite(this)' onmouseout='_sslolite(this)' style='text-align:center;'>
<center>
<div id=iBtns style='display:block;'>
<img id=iPlayBtn src='_pvt_images/play.png' style='display:block;width:16px;'>
<img id=iPauseBtn src='_pvt_images/pause.png' style='display:none;width:16px;'>
</div>

</center></div></td>

<td style='width:3px;'></td>

<td valign=bottom align=center><div id=iNextDiv class="gzbtn" onclick='ss.next();' onmouseover='_sshilite(this)' onmouseout='_sslolite(this)' style='text-align:center;'>
<center><img src='_pvt_images/forward.png' style='display:block;width:16px;'></center></div></td>

<td style='width:5px;'></td>

</tr>
</TABLE>
</TD>
<td>&nbsp;&nbsp;</td>
</TR>
</TABLE>
</CENTER>
<?
echo("</DIV>");
}


function createThumbBox($pos){
global $ssbgcolor,$ssthumbsalign;
$tmp=($ssthumbsalign=="left" || $ssthumbsalign=="right")?";overflow-x:hidden;overflow-y:auto;" : ";overflow-y:hidden;overflow-x:auto;";
echo("<DIV id=ss_thumbs style='z-index:9999;position:absolute;".$pos."padding:1px;text-align:center;background:".$ssbgcolor.$tmp."'></DIV>");
}

?>
