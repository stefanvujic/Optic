<?
// Copyright, 2005, Walter Long 

include_once "_inc_global.php";
include_once "_inc_header.php";

//exit("path=".$gLoginPath);

$action=myGet('action');
if(!empty($action)||!empty($dir))$parms="?dir=$dir&action=$action&autoload=yes";
else $parms="?autoload=yes";
$defpixi=myGet('defpixi');
$gMenuWidth=140;
//$gMenuWidth=280;//$$$$$$
$paneldividerdisplay="block";


?>
<!--
<link  href="_pvt_css/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="_pvt_js/jquery-1.8.3.js"></script>
<script src="_pvt_js/jquery-ui.js"></script>
<script src="_pvt_js/async.js"></script>
-->

<script src="_pvt_js/jquery.js"></script>
<script src="_pvt_js/util.js"></script>

<SCRIPT>


gShowToolbar=<?=$showtoolbar;?>;  //defined in _inc_header

var gPic =null; //set to gPic in pixi window in onload
var gPixi=null; //set to pixi window in onload
var gMenu=null; 
var gFrameTop=0;
var gMenuFmt="norm", gMenuOpen=1, gArrowFaded=0;
var gLastUrl="http://<?=$_SERVER["SERVER_NAME"];?>";
var gDefPixi   ="<?=$defpixi;?>";

//---dimensions---
var gMenuWidth=<?=$gMenuWidth;?>,gMenuPct;
var gWidth,gHeight,gBoxWidth,gScreenWidth;
var gPopupBgColor="<?=$popupbgcolor;?>";


//---buttons-----
var gShowCameraBtn ='<?=$showcamerabtn;?>';
var gShowLoginBtn  ='<?=$loginbtn;?>';


//----- menus ------
var gMouseModeMenu=gMusicMenu=gAddMenu=gAddNewMenu=gAddChildMenu=gCopyMenu=gSearchMenu=gSwapMenu=null;

//@@@var gModeBtns=new Array('Image','Warp','Skew','Fold','Zoom','Slide','Spin','Tilt','Turn','Size','Move');
var gModeBtns=new Array('Image','Warp','Skew','Fold','Zoom','Slide','Size','Move','Spin','Tilt');
var gMouseMode='Image';
// see "function Toolbar(" for where these names get overridden

var gTilingPatternBtns=new Array('Default', 'Simple',  'Fabric',   'Complete', 'Cornered',  'Deep',      'Shallow', 'None');
var gTilingPatternNbrs=new Array('4,4,4,4', '2,2,2,2', '2,2,32,32','4,4,28,28','4,4,12,12', '6,6,10,10', '2,2,4,4', '1,1,1,1');




//----- debug ------
function debug(){
alert("gID="+gID+", gLoggedIn="+gLoggedIn+", gAdmin="+gAdmin+", gAcctAdmin="+gAcctAdmin+
      ", gLoginPath="+gLoginPath+", gDir="+gDir);
}


//========================= INCOMING API FUNCTIONS =============================
//                         (don't change the names!)



function updPopupColors(fg,bg,bdrcolor,bdrwidth){  //called by _options.php
gPopupBgColor=bg;
for(var i=0; i<99; i++){
 try{
  var w=_obj("iWindow"+i).style;
  w.background=bg;
  w.color=fg;
  _obj("iWindowTitle"+i).style.color=fg;
  _obj("iWindowBox"+i).style.border="solid "+bdrwidth+" "+bdrcolor;
 }catch(e){i=100;}
}}




function updateButtons(recording,playing,autoplay){
_obj("iRecordBtn").src=(recording)? "_pvt_images/recordPause.png" : "_pvt_images/record.png";
//_obj("iStartStopPlay").src=(playing)? "_pvt_images/pause.png" : "_pvt_images/play.png";
_obj("iAnimateBtn").src=(autoplay)? "_pvt_images/pause.png" : "_pvt_images/play.png";
}


function hilitePixi(name){
var menu=getMenuObj();
var ix=menu.getPixiIX(name);
menu.selectPixi(ix);
}



//========================== Auto login/logout =================================

//--- called by _menu in onload ---
function resetRights(
 id,loggedin,loginpath,acctadmin,admin,
 showmenu,showtoolbar,showfolders,showfiles,
 showpics,showvids,showlinks,showupload,shownewfolder,shownewfile,
 shownewlink,shownewfimg,shownewsvg,shownewvid,showorgfolders,showupdsettings,showpopupmenu)
{
// --- give them a chance to read the msg...
//if(gLoggedIn!=loggedin || gID!=id)setTimeout("closeWindow(0);",4000);
gID=id;
gLoggedIn=loggedin;
gLoginPath=loginpath;
gAcctAdmin=acctadmin;
//alert("gAcctAdmin="+gAcctAdmin);
gAdmin=admin;
gShowmenu=showmenu;
gShowfolders=showfolders;
gShowfiles=showfiles;
gShowpics=showpics;
gShowvids=showvids;
gShowlinks=showlinks;
gShowupload=showupload;
gShownewfolder=shownewfolder;
gShownewfile=shownewfile;
gShownewlink=shownewlink;
gShownewfimg=shownewfimg;
gShownewsvg=shownewsvg;
gShownewvid=shownewvid;
gShoworgfolders=showorgfolders;
gShowupdsettings=showupdsettings;
gShowpopupmenu=showpopupmenu;
if(gShowtoolbar!=showtoolbar){
	 gShowtoolbar=showtoolbar;
 	_obj("iToolbar").style.display  =(showtoolbar)?"":"none";
 	myonresize();
}
var hasRights=hasDirRights(gDir);
//msg("=============");
//msg("resetRights: gID="+gID+", gLoggedIn="+gLoggedIn+", hasRights="+hasRights);
//msg("loginpath="+loginpath+", gPath="+gPath+", gDir="+gDir);
//msg(" ");
// try{ _obj("iLogoutBtn").style.display=_obj("iLogoutLink").style.display=(hasRights && gID && gShowLoginBtn)?"":"none"; 	}catch(e){}
try{ _obj("iLogout2Btn").style.display=(!hasRights && gID && gShowLoginBtn)?"":"none"; 									}catch(e){}
try{ _obj("iLoginBtn").style.display=_obj("iLoginLink").style.display=(!gID && gShowLoginBtn)?"":"none";				}catch(e){}
try{ if(gID){_obj("iLogoutBtn").title=_obj("iLogout2Btn").title=_obj("iLogoutLink").title="Logout ["+gID+"]";}			}catch(e){}
try{ _obj("iCameraBtn").style.display=(hasRights && gShowCameraBtn)?"":"none";						}catch(e){}
try{ _obj("iSaveConfigBtn").style.display=(hasRights)?"":"none";									}catch(e){}
try{ _obj("iPopupMenuBtn").style.display=(hasRights)?"":"none";										}catch(e){}
try{ gPixi._obj("iSaveRekordBtn").style.display=(hasRights)?"":"none";								}catch(e){}
try{ gPixi._obj("iSaveAutoplayBtn").style.display=(hasRights)?"":"none";							}catch(e){}
}



function setgDir(d){
if(d){
	var c=d.substr(d.length-1,1);		//remove the trailing "/"
	if(c=="/")d=d.substr(0,d.length-1);
}
gDir=d;
gDirLink=gDir+"/";
if(!gID){
	gLoginDir="";
	gLoginDirLink="";
	return "";
}
//this code is also in _inc_global  - also see hasDirRights()
if(gAdmin || (gDir && (_ix(gDir,gLoginDir)==0 || _ix(gLoginDir,gDir)==0) && gDir!=gLoginDir)){
	gLoginDir=gDir;
	gLoginDirLink=gDir+"/";
}
var a=gLoginDirLink.split("/");
return gLoginDir;
}



function login(){
openPopup("util","_newutil.php?page=login&dir="+gDir,"Login");
}

function logout(){
openPopup("util","_newutil.php?page=logout&dir="+gDir,"Logout");
}


function moLoginBtns(img,msover){ 
var src=img.src;
if(_in(src,"logout.png"))img.src="_pvt_images/login.png";
if(_in(src,"login.png"))img.src="_pvt_images/logout.png";
}



var gFmtMenuTimer;
//--- we use a timeout so we can distinguish between click and dblclick
function schedFmtMenu(typ){
if(gFmtMenuTimer){ //dblclick
 clearTimeout(gFmtMenuTimer); gFmtMenuTimer=null;
 if(getMenuObj().gLastMenuTyp!=typ)getMenuObj().fmtMenu(typ);
 return;
}
gFmtMenuTimer=setTimeout("gFmtMenuTimer=null; getMenuObj().fmtMenu('"+typ+"')",300);
}
function fmtMenu(typ){ gFmtMenuTimer=null; getMenuObj().fmtMenu(typ); }


var gNbrName;

//----------- takePic ------------
function takePic(scrix){
if(this.picDisabled)return;
if(gNbrName)gNbrName--;
else gNbrName=_rdm(1000,99999);
var name="0_"+gNbrName;
gPixi.gPic.savePicConfig(1,scrix);
//--- get current time (video play) ----
var play="101";
try{
	play=gPixi.gPic.getVideoScreen().videoFrame.getVideoTime();
}catch(e){ play="101"; }
play=(parseInt(play)-7);
if(play<0)play=0;
var wh=gPixi.gPic.frameWidth();
var width	=wh[0];
var height	=wh[1];
//--- open the window --- (need to remove dir security on Godaddy)
var url="_takePicFrame.php?name="+name+"&dir="+gDir+"&play="+play+"&width="+width+"&height="+height;
openPopup("takepic",url,'<big>Capturing: '+name+'.png</big>');
}


//----------- takeLayerPic ------------
function takeLayerPic(){
openPopup("medium","_takeLayerPic.php","Capturing Layer Image");
}

//----------------------- on load ---------------------------

function load(){
myonresize();
resizable("iWindow0");
resizable("iWindow1");
resizable("iWindow2");
resizable("iWindow3");
draggable("iWindow0");
draggable("iWindow1");
draggable("iWindow2");
draggable("iWindow3");
$("#iMenuDivider").draggable({
 axis : "x",
 start: iMenuDividerDragStart,
 drag : iMenuDividerDrag,
 stop : iMenuDividerDragEnd
});
createAddMenu();
//createAddPixiMenu();
createChildPixiMenu();
createMouseModeMenu();
//createTilingPatternMenu();
gMenu=frames[0];
gPixi=frames[1];
gPic =gPixi.gPic;
if(gAutoLogin)welcomeMsg();
}


function iMenuDividerDragStart(){DragStart();}
function iMenuDividerDrag()     {chgMenuSplit(); }
function iMenuDividerDragEnd()  {chgMenuSplit();DragEnd();}

/*
function expandMenu(dblclick){
var mnu=_obj("td0");
var o=_obj("iMenuDivider");
var lft=eleWidth(mnu);
var v=(lft<200)?420:100;
chgMenuSplit(v);
}
*/

function chgMenuSplit(lft){
if(lft==null)lft=getLeft(_obj("iMenuDivider"));
if(lft<100){
	lft=100; 
	_obj("iMenuDivider").style.left="100px";
}
set("chgMenuSplit",lft);
lft+=34;
gMenuWidth=lft;
_obj("td0").style.width=""+lft+"px";
}

function myonresize(h){
gWidth=docWidth();
gHeight=(h!=null)?h:docHeight();  //-- see resize function below
try{  //maintable might not exist if not logged in?
	 _obj("iMainTable").style.height=gHeight;
	 _obj("iMainTable").style.width=gWidth;
}catch(e){}
}


function flipMenu(show,dblclick){
if(!gShowToolbar)dblclick=0;
if(show==null){show=(_obj("imenu").style.display=="none")?1:0;}
mainFlipMenu(show,dblclick); 
if(dblclick){
	_obj("iToolbar").style.display=(show)?"":"none";
	gPixi.gToolbar.openMenuControls(show);
	_obj("iFlipMenu").style.opacity=(show)?1:0;
	gArrowFaded=(show)?0:1;
}else{
	var showtools=(gPixi.gToolbar.menucontrols.style.display=="block")?1:0;
	gPixi.gToolbar.openMenuControls(showtools);
}
set("flipMenu_show",show);
set("flipMenu_dblclick",dblclick);
}


function mainFlipMenu(menu){
if(menu==null)menu=(_obj("imenu").style.display=="block")?1:0;
var toolbar=(_obj("iToolbar").style.display=="none")?0:1;
if(menu){
	 _obj("imenu").style.display="block";
	 _obj("td0").style.width=""+gMenuWidth+"px";
	 _obj("iMenuDivider").style.display=((!gShowToolbar)?"none":"block");
	 _obj("iFlipMenu").src="_pvt_images/arrow_red.png";
	 gMenuOpen=1;
}else{
	 _obj("imenu").style.display="none";
	 _obj("td0").style.width="0px";
	 _obj("iMenuDivider").style.display="none";
	 _obj("iFlipMenu").src="_pvt_images/arrow_green.png";
	 gMenuOpen=0;
}
spaceMenu();
}

function arrowFade(over){
var toolbarHidden=(_obj("iToolbar").style.display=="none")?1:0;
var menuHidden  =(_obj("imenu").style.display=="none")?1:0;
var arrow=_obj("iFlipMenu");
//msg("over="+over+", hidden="+toolbarHidden);
if(over){
	if(gArrowFaded){
		moFade(1,arrow,50);
		gArrowFaded=0;
	}else return;
}else{
	if(toolbarHidden && menuHidden){
		moFade(0,arrow,50);
		gArrowFaded=1;
	}else{
		moFade(1,arrow,50);
		gArrowFaded=0;
	}
}
}

function spaceMenu(){
var menu=(_obj("imenu").style.display!="none")?1:0;
var toolbar=(_obj("iToolbar").style.display!="none")?1:0;
if(!menu)return;
if(!toolbar){
	frames[0]._obj("iNavSpacer").style.height="30px";
	frames[0]._obj("iMenuSpacer").style.height=(frames[0].gDir)?"30px":"15px";
}else{
	frames[0]._obj("iNavSpacer").style.height="0px";
	frames[0]._obj("iMenuSpacer").style.height="0px";
}
}


//--------------------------- refresh lowest menu ----------------------------
function refresh(noreload){
getMenuObj().refresh(noreload);
}


//--------------------- refresh all logged in menus ----------------------------
function refreshMenus(dir,goback,noreload,loggingout){
if(loggingout)noreload=0;
getMenuObj().gotoDir(dir,goback,null,1,noreload);
}


//--- try and find u in a popup window ---
function gotoPopup(u,ix,title){
var ok;
var toolbar=getTopToolbarObj();
try{
 for(var i=2; i<toolbar.frames.length; i++){
  if(toolbar.frames[i].gWinIX==ix){
   ok=toolbar.frames[i].gotoImg(u,title,gDir);
   if(toolbar._obj("iWinShow"+ix).style.display=="block")toolbar.showWindow(ix);
   i=99;
 }}
}catch(e){ok=0;}
return ok;
}


function loadHome(){ 	
var dir=(gAdmin)?"":gID;
getMenuObj().gotoDir(dir);
loadPixi(gHomePage,dir); 
}


function applyFilter(src,dir){	
//alert("applyFilter("+src+","+dir+")");
if(src==null)return;
if(dir==null)dir=gDir;
//msg("applyFilter="+gPath+dir+"/"+src);
asyncP("action=getconfig&fil="+gPath+dir+"/"+src,"getconfig");
//window.open("ajx.php?action=getconfig&fil="+gPath+dir+"/"+src);
}

var gConfigTxt="";

function applyConfig(txt){ //called by _ajx.js (getConfig)
//msg("txt="+txt);
gConfigTxt=txt;
gPixi.gPic.applyConfig();
}


function loadPixi(src,dir){	
//alert("loadPixi("+src+","+dir+")");
if(src==null)src="";
if(dir==null)dir=gDir;
var url="_pixi.php?dir="+dir+"&src="+src;
_obj("iscreen").src=url;
}


function loadRekord(name,txt){
gPixi.gPic.loadRekord(name,txt);
return 1;
}



function loadImage(dir,u,popup,mode,typ,title){
//alert("_toolbar:  loadImage("+dir+" , "+u+" , "+popup+")");
var ok=1;
if(!mode)mode="";
if(!typ)typ="images";
u=u.replace("_tn.",".");
if(!_in(u,"../"))u="../"+u;
return gotoImg(u,title,gDir); 
}


//--- try and find u in the current main window ---
function gotoImg(u,title,dir){
if(dir==null)dir=gDir;
return gPixi.gotoImg(u,title,dir);
}

function loadVideo(dir,vid,popup,title){
//alert(" loadVideo: gDir="+gDir+", popup="+popup+", title="+title);
if(!popup && gotoImg(vid,title,gDir))return "pixi";
if(gotoPopup(vid,2,title))return "popup";
if(popup){
 	openPopup("video","_play.php?dir="+dir+"&includesubdirs=no&type=videos&mode=popup&src="+vid+"&title="+title);
 	return "popup";
}
loadPixi(vid);
return "desktop";
}


function loadx(x,force){
var m=getMenuObj();
m.goAction(null,0,x,force,null,1);
return 1;
}



function refreshLayer(src,ix){
//--- if we just edited a text file - if it is a layer then refresh that layer ---
if(ix==null)ix=gPixi.findSrcScreen(src);
//msg("src="+src+", ix="+ix);
if(ix==null)return;
gPixi.gPic.screens[ix].reloadFile();
}


function editText(src){
if(!src){
	if(gPixi.getCurrentMedia()!="file")return;
	src=gPixi.getCurrentSrc();
	if(!src)return;
}
//msg("editText src="+src);
src=src.replace("../","");
var dir=getPath(src);
var file=getFile(src);
//msg("dir="+dir+", fil="+file);
openPopup("edittext","_edittext.php?dir="+dir+"&file="+file+"&postaction=3",file.replace(".htm",""));
//fillBox()
}


function saveFilter(){
openPopup("util","_newutil.php?page=savefilter&dir="+gDir+"&name="+gPixi.gPic.filterName,"Save Filter");
}


function savePixi(){
openPopup("util","_newutil.php?page=savepixi&dir="+gDir+"&name="+gPixi.gPic.configName,"Save View");
}

function saveReplay(){
openPopup("saverekord","_newutil.php?page=savereplay&dir="+gDir+"&name="+gPixi.gPic.hisName,"Save Action");
}

function saveAutoplay(){
openPopup("saverekord","_newutil.php?page=saveautoplay&dir="+gDir+"&name="+gPixi.gPic.aniName,"Save Action");
}



function getMenuObj(){return frames[0];}

//--- get the lowest toolbar object ---
function getToolbarObj(){
var o,o2,x="",x2="";
for(var i=1;i<999;i++){
 if(i>1)x2+=".";
 x2+="frames[1]";
 eval("o2="+x2);
 if(o2){ if(o2.gFrameTop==null)o2=null; }
 if(!o2){ 
  if(x)eval("o="+x);
  else o=window;
  return o;
 }
 if(i>1)x+=".";
 x+="frames[1]";
}}


function getToolbarObjIX(ix){
alert("getToolbarObjIX not needed?");
if(ix==0)return window;
var o,x="";
for(var i=1;i<ix+1;i++){
 if(i>1)x+=".";
 x+="frames[1]";
}
eval("o="+x);
return o;
}


function popupMsg(title,msg,l,t,w,h){
if(l==null){ l="300px"; t="100px"; w="300px"; h="200px"; }
pageLoader("msg",msg,title,l,t,w,h);
}



//================================= floating windows ===========================

var iWindowLeft=new Array(),iWindowRight=new Array(),iWindowTop=new Array(),iWindowWidth=new Array(),iWindowHeight=new Array();
var iHiddenWindow,iWindowZindex=999,iWindowFadeTimer;
var iWindowExpanded=new Array(),gLastExpandedWindow=0;


function getWindowFrame(i){
return window.frames["iWindowFrame"+i];
}


function newText(postaction){
openPopup("newtext","_edittext.php?dir="+gDir+"&postaction="+postaction,"New Text");	
}


function addText(dir,url,typ){
if(dir==null)dir=gMenu.gDir;
if(typ==null)typ="";	
if(url==null){
	if(gMenu.gFiles.length>0){
		url=gMenu.gFiles[0];
	}else{
		openPopup("edittext","_edittext.php?dir="+gDir+"&postaction=2","New Text");	
		return;
}	}
gPixi.gToolbar.addText(dir,url,typ);
gPixi.gToolbar.showHandles(1);
}


function openPopup(name,u,title){ 
var btns=0,ix=0;
var left="140px", top="30px", width="75%", height="85%";
var typ=ucWords(_fileType(u));
if(typ=="Pixi")typ="View";
if(typ=="File")typ="Text";
//msg("name="+name);
//msg("typ="+typ);
//msg("u="+u);
switch(name){
	 case "takepic"  :
		left="250px"; top="0px"; width="65%"; height="95%";
		break;
	 case "saverekord"  :
		left="250px"; top="80px"; width="370px"; height="300px";
		title="Save Action";
		ix=1;
		break;
	 case "rename"  :
		left="250px"; top="80px"; width="370px"; height="300px";
		title="Rename "+typ;
		ix=1;
		break;
	 case "Copy"  :
		left="250px"; top="80px"; width="370px"; height="300px";
		title="Copy "+typ;
		ix=1;
		break;
	 case "msg"  :
	 case "util" :
		left="250px"; top="80px"; width="370px"; height="300px";
		ix=1;
		break;
	 case "medium" :
		width="60%"; height="75%";
		break;
	 case "colorpicker" :
		left="10px"; top="35px"; width="350px"; height="350px";
		break;
	 case "youtube" :
	  	title="Search Youtube";
		ix=2;
	  	break;
	 case "clip" :
	  	title="Video Clipboard";
		ix=3;
	  	break;
	 case "options" :
	  	title="Settings";
		width="70%";
		left="100px";
		height="90%";
	  	break;
	 case "upload" :
	  	title="Upload";
	  	break;
	 case "editfile" :
		left="27px"; top="5px"; width="94%"; height="95%"; 
		var a=u.split("=");
	  	title="Edit File :&nbsp;&nbsp;<font color=white><b>"+a[a.length-1]+"</b></font>";
	  	break;
	 case "newfile" :
		left="25px"; top="5px"; width="95%"; height="95%"; 
	  	title="New File";
	  	break;
	 case "newtext" :
	  	title="New Text";
	  	break;
}
if(ix==0)gPixi.gPic.gOpenPopup=name;
if(title==null)title="";
var win=_obj("iWindow"+ix).style;
win.top		=top; 
win.left	=left;
win.width	=width; 
win.height	=height;
if(_in(u,"_play.php")){
	 u+=(_in(u,"?"))?"&":"?";
	 u+="winix="+ix;
	 if(!_in(u,"mode="))u+="&mode=popup";
}
if(name=="msg"){
	 var x="<center><SPAN style='color:black;font-size:22px;'>"+u+"</SPAN></center>";
}else{
	 var x="<IFRAME id='iWindowFrame"+ix+"' name='iWindowFrame"+ix+"' src='"+u+"' style='width:100%;height:100%;' frameborder='0' border='0' allowTransparency='true'></IFRAME>";
	 //window.open(u);
	 //return;
}
//msg("ix="+ix);
_obj("iWindowBox"+ix).innerHTML		=x;
_obj("iWindowTitle"+ix).innerHTML	=title;
_obj("iWindow"+ix).style.display	="block";
//_obj("iWinSave"+ix).style.display	="none";
}




function closeWindow(ix){
if(ix==0)gPixi.gPic.gOpenPopup="";
for(var i=0;i<4;i++){
  if(ix==i || ix==null){
	_obj("iWindowBox"+i).innerHTML="";
	_obj("iWindowTitle"+i).innerHTML="";
	_obj("iWindow"+i).style.display="none";
  }
}}


function refreshClip(){
openPopup("clip","clip.php?dir="+gDir);
}

function clearClip(){asyncP("action=clearclip","clearclip");}


//---------------------------- end window --------------------------------------


//========================= OTHER FUNCTIONS ====================================

//function myMoFade(over,dv){if(gDir!="")return;moFade(over,dv);}

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

function getDisplayStyle(v){
if(v && v!="no")return "";
return "display:none;";
}


//------------------- specific menus --------------------

//--- add items ---
function createAddMenu(){
//alert("createAddMenu used?");
var m=gAddMenu=new popupMenu('add','270px');
var movers="<?=$movers;?>";
var txt="<table class='c_popuptable'>"+
 "<tr id=iUploadItem style='"+getDisplayStyle(gShowupload)+"height:30px;'><td "+movers+" style='cursor:pointer;' onclick='loadx(\"upload\");gAddMenu.hideMenu()'>&nbsp;&nbsp;Upload Images</td></tr>"+
 "<tr id=iNewImageItem style='"+getDisplayStyle(gShownewfimg)+"height:30px;'><td "+movers+" style='cursor:pointer;' onclick='loadx(\"newfimg\");gAddMenu.hideMenu()'>&nbsp;&nbsp;Fetch Image</td></tr>"+
 "<tr id=iNewFileItem style='"+getDisplayStyle(gShownewvid)+"'><td "+movers+" style='cursor:pointer;' onclick='loadx(\"youtube\");gAddMenu.hideMenu()'>&nbsp;&nbsp;Find&nbsp;Videos</td></tr>"+
 "<tr id=iNewFolderItem style='"+getDisplayStyle(gShownewfolder)+";height:30px;'><td "+movers+" style='cursor:pointer;' onclick='loadx(\"newdir\");gAddMenu.hideMenu()'>&nbsp;&nbsp;New Folder</td></tr>"+
 "<tr id=iNewFileItem style='"+getDisplayStyle(gShownewfile)+"'><td "+movers+" style='cursor:pointer;' onclick='newText(null);gAddMenu.hideMenu()'>&nbsp;&nbsp;New Text</td></tr>"+
"</table>";
m.div.innerHTML=txt;
}

//----- CHILD Pixi Element Menu ----
function createChildPixiMenu(){
gAddChildMenu=new popupMenu('add child layer','650px');
var tmpmovers="class='c_popuptd' onmouseover='_hilite(this)' onmouseout='_lolite(this)'";
var txt="<table class='c_popuptable'>";
txt+="<tr><td "+tmpmovers+" onclick='gPixi.gToolbar.addImageLayer();gAddChildMenu.hideMenu();' style='padding-left:2px;'>&nbsp;&nbsp;Add&nbsp;Image&nbsp;&nbsp;</td></tr>";
txt+="<tr><td "+tmpmovers+" onclick='gPixi.gToolbar.addImageLayer(\"mask\");gAddChildMenu.hideMenu();' style='padding-left:2px;'>&nbsp;&nbsp;Add&nbsp;Mask&nbsp;&nbsp;</td></tr>";
txt+="<tr><td "+tmpmovers+" onclick='gPixi.gToolbar.addImageLayer(\"blend\");gAddChildMenu.hideMenu();' style='padding-left:2px;'>&nbsp;&nbsp;Add&nbsp;Blend&nbsp;&nbsp;</td></tr>";
txt+="<tr><td "+tmpmovers+" onclick='gPixi.gToolbar.addVideo();gAddChildMenu.hideMenu();' style='padding-left:2px;'>&nbsp;&nbsp;Add&nbsp;Video&nbsp;&nbsp;</td></tr>";
if(gLoggedIn)txt+="<tr><td "+tmpmovers+" onclick='addText();gAddChildMenu.hideMenu();' style='padding-left:2px;'>&nbsp;&nbsp;Add&nbsp;Text&nbsp;&nbsp;</td></tr>";
txt+="<tr><td style='height:10px;background:#ffffff;'></td></tr>";
txt+="</table>";
gAddChildMenu.div.innerHTML=txt;
}


//----- Mouse Mode Menu ----
function createMouseModeMenu(){
gMouseModeMenu=new popupMenu('mouse mode','388px');
var txt="<table class='c_popuptable'>";
var tmpmovers="onmouseover='if(this!=gPixi.gToolbar.modeBtn)_hilite(this)' onmouseout='if(this!=gPixi.gToolbar.modeBtn)_lolite(this)'";
for(var i=0;i<gModeBtns.length;i++){
 txt+="<tr><td class='c_popuptd' "+tmpmovers+" onclick='gPixi.gToolbar.changeMode(\""+gModeBtns[i]+"\");gMouseModeMenu.hideMenu();' id='i"+gModeBtns[i]+"Btn' style='padding-left:2px;'>&nbsp;&nbsp;"+gModeBtns[i]+"&nbsp;&nbsp;</td></tr>";
}
txt+="</table>";
gMouseModeMenu.div.innerHTML=txt;
}

</SCRIPT>

<STYLE>
.c_vfloat    {position:absolute;top:3px;left:3px;width:<?=$gMenuWidth;?>px;height:96%;padding:1px;<?=$roundedcorners;?>;z-index:9;}
.c_vfloatx   {position:absolute;top:0;height:100%;left:12%;width:88%;z-index:8;}
.c_barbtn    {cursor:pointer;height:22px;width:22px;padding-left:0px;float:left;}
.c_menubtn   {cursor:pointer;height:24px;width:24px;padding-left:8px;float:left;}
.c_popupmenu {position:absolute;z-index:9999;height:0px;overflow:hidden;top:28px;}
.c_popuptable{font-family:economica;font-size:18pt;padding:3px;color:black;background:<?=$btnbgcolor;?>;color:<?=$btncolor;?>;border:solid 1px <?=$btnbordercolor;?>;";
.c_popuptd   {cursor:pointer;}
.mouseModeBtn{ background:#ffffff;border:solid 1px white;cursor:pointer;text-align:center;letter-spacing:1px; }
</STYLE>
</HEAD>
<BODY id=gBody onload='load()'  onresize='myonresize()' onkeydown="gPixi.gToolbar.fKey(1,event.keyCode,event)" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 style='background:white;overflow:hidden;'>

<!--
<FORM id="layerPicForm" method="post" action="_takeLayerPic.php" target="iWindowFrame0">
  <input id="layerPicDir"  name="dir" type="hidden" value="">
  <input id="layerPicData" name="img" type="hidden" value="">
</FORM>
-->

<?


//============================== WINDOWS ======================================= (ABSOLUTE)

for($tmp=0;$tmp<4;$tmp++){
	 wrt("<DIV id='iWindow".$tmp."' class='corners8 slideBg' style='resize:none; display:none; border:solid 1px black;padding:".$popupwindowpadding."px; background:$popupbgcolor; color:$popupfgcolor; position:absolute;");
	 if     ($tmp==0)wrt(" z-index:999; left:145px; top:28px; width:40%; height:90%;'>"); //small
	 else if($tmp==1)wrt(" z-index:9991;left:145px; top:28px; width:40%; height:90%;'>"); //big
	 else if($tmp==2)wrt(" z-index:9992;right:20px; top:51%;  width:35%; height:45%;'>"); //clipboard
	 else if($tmp==3)wrt(" z-index:9993;right:20px; top:28px; width:35%; height:45%;'>"); //?
	 wrt("<TABLE border=0 cellpadding=0 cellspacing=0 style='resize:none;width:100%;height:100%;' ");
	 wrt("><tr id=iWindowToolbar".$tmp." style='height:34px;border-bottom:solid 0px black;color:white;overflow:hidden;cursor:move;'");
	 wrt("><td id=iWindowTitle".$tmp." align=left style='color:$popupfgcolor;cursor:move;font-size:22pt;letter-spacing:2px;padding-left:15px;position:relative;top:-2px;' ></td>");
	 wrt("<td align=right valign=top><TABLE border=0 id=iWinBtns".$tmp." class='slideOpacity' cellpadding=0 cellspacing=0><tr>");
	 wrt("<td width=34><IMG id=iWinHide".$tmp." onclick='hideWindow(".$tmp.")' src='_pvt_images/folders/toggle_minimize.png' style='display:none;width:28px;cursor:pointer;position:relative;top:0px;left:0px;'></td>".
	     "<td width=34><IMG id=iWinToggle".$tmp." onclick='expandWindow(".$tmp.")' src='_pvt_images/folders/toggle_expand.png' style='display:none;width:28px;cursor:pointer;position:relative;top:0px;left:0px;'></td>".
	 	 "<td width=34><IMG onclick='closeWindow(".$tmp.")' src='_pvt_images/close.png' style='width:26px;cursor:pointer;position:relative;top:0px;left:0px;'></td>".
	     "</tr></TABLE>".
	     "</td></tr><tr>".
	     "<td colspan=2 id=iWindowBox".$tmp." style='resize:none;border:solid 1px ".$popupbdrcolor."; background:white;'></td>".
	     "</tr></TABLE></DIV>");
}

?>


<!-- this div is required whenver we use the jQuery draggable or resizable functions ---->
<DIV id=iDragCover style='display:none;position:absolute;left:0px;top:0px;height:100%;width:100%;z-index:99991;'></DIV>

<!-- =============================================== MAIN TABLE ======================================= -->
<TABLE id=iMainTable border=0 cellpadding=0 cellspacing=0 width=100% height=100% style='position:absolute;top:0px;left:0px;width:100%;height:100%;overflow:hidden;'>
 
 
 <!--================================================ 1ST ROW ========================================== -->
 <tr id=iToolbar style='<? if(!$showtoolbar)echo("display:none;") ?>' >
 
 <td align=left colspan=2 style='border-bottom:solid 1px #ccc; height:28px;overflow:hidden;'>
	 <?
	 //<!-- ============================ 1ST ROW TABLE =================================== -->
	 wrt("<TABLE border=0 cellpadding=0 cellspacing=0 style='width:100%;'><tr>");
	 wrt("<td style='width:146px;height:28px;border-right:solid 0px #ccc;'>");
	 
		//<!-- ============================ ADMIN BUTTONS =================================== -->
		wrt("<TABLE id=iAdminButtons border=0 cellpadding=0 cellspacing=0 style='display:block;overflow:hidden;'><tr>");
			wrt("<td><div id=iToolbarSpacer style='width:34px;'></div></td>");
			
			wrt("<td style='DISPLAY:NONE;'>");
				wrt("<IMG id=iLogoutBtn   src='_pvt_images/login.png'   onclick='logout()' style='".getDisplayStyle($gLoggedIn)."position:relative;top:1px;cursor:pointer;width:20px;height:20px;padding-left:1px;' onmouseover='moLoginBtns(this,1)' onmouseout='moLoginBtns(this,0)' title='logout'>");
				wrt("<IMG id=iLogout2Btn  src='_pvt_images/logout2.png' onclick='logout()' style='display:none;position:relative;top:1px;cursor:pointer;width:20px;height:20px;padding-left:1px;' onmouseover='moLoginBtns(this,1)' onmouseout='moLoginBtns(this,0)' title='logout'>");
				wrt("<IMG id=iLoginBtn    src='_pvt_images/logout.png'  onclick='login()'  style='".(($gLoggedIn)?"display:none;":"").";position:relative;top:1px;cursor:pointer;width:20px;height:20px;padding-left:1px;' onmouseover='moLoginBtns(this,1)' onmouseout='moLoginBtns(this,0)' title='login'>");
			wrt("</td>");
			
	 		wrt("<td style='width:13px;'></td>");
	 		wrt("<td id=iPopupMenuBtn style='".getDisplayStyle($showupdsettings)."' ><IMG class=c_barbtn src='_pvt_images/add_new.png' onclick='gAddMenu.clickMenuBtn(this)' style='position:relative;top:1px;left:0px;height:16px;width:16px;opacity:0.8;' title='add NEW'></td>");
	 		wrt("<td style='width:15px;'></td>");
			wrt("<td><IMG class=c_barbtn src='_pvt_images/home.png' onclick='loadHome()' title='go home' style='".getDisplayStyle($showupdsettings).";position:relative;top:2px;width:18px;height:18px;'></td>");
		 	wrt("<td style='width:15px;'></td>");
		 	//wrt("<td id=iShareBtn><IMG class=c_barbtn src='_pvt_images/share.png' onclick='' style='position:relative;top:2px;opacity:0.7;' title='share'></td>");
		 	//wrt("<td style='width:10px;'></td>");
		 	wrt("<td id=iSettingsBtn style='".getDisplayStyle($showupdsettings)."' ><IMG class=c_barbtn src='_pvt_images/settings.png' onclick='loadx(\"options\")' style='position:relative;top:1px;left:0px;height:16px;width:16px;opacity:0.8;' title='settings'></td>");
		 	wrt("<td style='width:12px;'></td>");
		wrt("</tr></TABLE>");
		//<!-- ================== END ADMIN BUTTONS =================== -->
	 ?>
	 </td>
	 
	 <td style='width:34px;'><img src='_pvt_images/profile.png' style='padding-top:px;position:relative;top:0px;cursor:pointer;opacity:0.9;height:16px;cursor:pointer;overflow:hidden;' title='profile'></td>
	 <td align=center style='width:68px;letter-spacing:2px;font-size:14px;cursor:pointer;'>
	 <DIV id='iLoginLink'  style='height:16px;border:solid 1px #666;padding:1px;' onclick='login()'  onmouseover='this.style.fontWeight="bold"' onmouseout='this.style.fontWeight=""' >LOGIN</DIV>
	 <DIV id='iLogoutLink' style='height:16px;border:solid 1px #666;padding:1px;display:none;' onclick='logout()'  onmouseover='this.style.fontWeight="bold"' onmouseout='this.style.fontWeight=""' >LOGOUT</DIV>
     <td style='width: 0px;'><DIV id='iLoginLink' style='height:16px;border:solid 1px #666;padding:1px;padding-left: 14px;padding-right: 14px;margin-left: 10px;' onclick='login()'  onmouseover='this.style.fontWeight="bold"' onmouseout='this.style.fontWeight=""' >SAVE</DIV></td>
	 </td>

	 <script>
		if(gLoggedIn){
			_obj("iLogoutLink").style.display="block";
			_obj("iLoginLink").style.display="none";
		}else{
			_obj("iLoginLink").style.display="block";
			_obj("iLogoutLink").style.display="none";
		}
	 </script>


	 <td align=center>
	 <img id=iPlayBack   src='_pvt_images/playleft.png' onclick='gPixi.gPic.screen.nextImage(-1,null,1);gPixi.gToolbar.viewImage();' style='position:relative;top:-2px;width:18px;cursor:pointer;background:#ffffff;opacity:0.7;'>
	 &nbsp;&nbsp;
	 <img id=iPlayPause  src='_pvt_images/forward.png'	onclick='gPixi.gPic.playPause()' style='position:relative;top:1px;width:25px;cursor:pointer;background:#ffffff;opacity:0.7;' title='play/pause'>
	 &nbsp;
	 <img id=iPlayFwd    src='_pvt_images/play.png'		onclick='gPixi.gPic.screen.nextImage(1,null,1);gPixi.gToolbar.viewImage();' style='position:relative;top:-2px;width:18px;cursor:pointer;background:#ffffff;opacity:0.7; '>
	 </td>

	 <td align=right style='width:140px;'>
		<!-- toolbar -->
		<DIV id=iBoxBdrTop class='corners4' style='overflow:hidden;height:28px;color:black;'>
			<TABLE cellpadding=0 cellspacing=0 style='overflow:hidden;'>
			<tr>
			<td align=left style=''>
				<TABLE id=iModeBtns cellpadding=0 cellspacing=0 style='background:#def;padding-bottom:2px;overflow:hidden;position:relative;top:2px;'>
				<tr>
				<td style='padding-left:2px;'></td>
				<td><img id=iMouseModeBtn class='c_barbtn corners4' src='_pvt_images/mouse.png' onclick='gMouseModeMenu.clickMenuBtn(this)' style='width:20px;height:20px;' ></td>
				<td><div id='iMouseModeMenuName' style='width:44px;padding-left:3px;background:white;'>Image</div></td>
				<td><img class='c_barbtn corners4' src="_pvt_images/reset.png" onclick="gPixi.gToolbar.resetCurrentMode()" style="cursor:pointer;" /></td>
				<td style='padding-left:4px;'><img id='iAutoFwd' class='c_barbtn corners4' onmousedown='gPixi.gPic.screen.animation.startstop(1)' src='_pvt_images/play.png' oncontextmenu='return false;' title='AutoPlay' style='opacity:0.7;position:relative;left:0px;top:0px;'></td>
				</tr>
				</TABLE>
			</td>
			<td width=4></td>
			</tr>
			</TABLE>
		</DIV>
		
		<?
 		wrt("<td style='padding-left:0px;'></td>");
		//wrt("<td width=24 style='padding-left:0px;'><IMG class=c_barbtn src='_pvt_images/home.png' onclick='loadHome()' title='home view' style='position:relative;top:1px;width:19px;height:19px;left:2px;'></td>");
		wrt("</td>");
	 	wrt("<td align=left style='height:28px;width:273px;border-left:solid 1px #ccc;'>");
	 
		 //<!-- ======================= RIGHT BUTTONS ======================== -->
		 wrt("<TABLE cellspacing=0 cellpadding=0 style='width:100%;overflow:hidden;'><tr>");
			wrt("<td id='iAddChildMenu' style='height:28px;width:22px;padding-left:4px;'><img class='c_barbtn' src='_pvt_images/addgreen.png' onclick='gAddChildMenu.clickMenuBtn(this)' style='position:relative;top:0px;height:20px;width:20px;padding:1px;' title='new layer'></td>");
			wrt("<td align=right style=''>");
		 	wrt("<TABLE cellspacing=0 cellpadding=0><tr>");
				?><td><img class='c_barbtn' onclick="gPixi.gPic.lastDesktopColor=gPixi.gPic.desktopColor;gPixi.CP.open(window,gPixi.gPic.desktop,'frameColor',_getLeftClick(event)-195,_getTopClick(event)-15,gPixi.gPic.viewDesktopColor,0,gPixi.gPic.desktopColor);"' src='_pvt_images/color.gif' style='width:24px;height:26px;' title='view background color'></td><?
		 		wrt("<td style='padding-left:6px;'></td>");
				wrt("<td><img id=iUndoBtn class='c_barbtn corners4' onclick='gPixi.gPic.unDone(-1)' style='position:relative;top:-2px;left:2px;width:20px;height:20px;opacity:0.3;' src='_pvt_images/undo.png' title='undo'></td>");
				wrt("<td><img id='iDoneReset' class='c_barbtn' src='_pvt_images/reset.png' onclick='gPixi.gToolbar.resetDoneStuff()' style='position:relative;top:5px;width:16px;height:16px;cursor:pointer;opacity:0.3;' title='reset history'></td>");
				wrt("<td><img id=iRedoBtn class='c_barbtn corners4' onclick='gPixi.gPic.unDone(1)' style='position:relative;top:-2px;left:-2px;width:20px;height:20px;opacity:0.3;' src='_pvt_images/redo.png' title='redo'></td>");
				wrt("<td><img id=iSaveConfigBtn class='c_btnbig' src='_pvt_images/save1.png' onclick='savePixi()'  style='padding-left:5px;width:33px;height:33px;cursor:pointer;' title='save view'></td>");
				wrt("<td width=6></td>");
				wrt("<td><IMG id=iCameraBtn style='width:24px;height:25px;position:relative;top:2px;left:0px;' class=c_barbtn src='_pvt_images/camera.png' onclick='takePic()' title='capture view image'></td>");
				wrt("<td width=8></td>");
				wrt("<td><IMG style='position:relative;top:2px;' class=c_barbtn src='_pvt_images/help.png' onclick='window.open(\"help.php\")' title='tips'></td>");
				wrt("<td width=14></td>");
				wrt("<td width=31 ><img id=iMenuControlsBtn class='c_barbtn corners4' src='_pvt_images/arrow_red.png' onclick='gPixi.gToolbar.openMenuControls()' style='width:20px;height:20px;position:relative;top:2px;' title='tools'></td>");
				wrt("<td width=10></td>");
			wrt("</tr></TABLE>");
			wrt("</td></tr>");
		 wrt("</tr></TABLE>");
		 //<!-- ======================= END RIGHT BUTTONS ======================== -->
	 
	 wrt("</td></tr>");
	 wrt("</tr></TABLE>");
	 //============================ END 1ST ROW TABLE ========================
	 
 wrt("</td></tr>");

 //=================================================== 2nd ROW ========================================================
 wrt("<tr>");

 //============================== SIDE MENU ======================
 $tmp=(!$showmenu)?1:$gMenuWidth;
 wrt("<td id=td0 class='XslideWidth' style='width:".$tmp."px;overflow-x:hidden;border-right:solid 1px #ccc;border-left:solid 1px #ccc;' valign=top>");
 	wrt("<IFRAME id=imenu name=imenu src='_menu.php".$parms."' frameborder='0' border='0' style='width:100%;height:100%;overflow-x:hidden;'></IFRAME>");
 wrt("</td>");

 //============================== PIXI BOX =======================
 wrt("<td align=right>");
 	wrt("<TABLE id=iDesktopTable cellpadding=0 cellspacing=0 width=100% height=100%><tr>");
 		wrt("<td id=td2>");
 		wrt("<IFRAME id=iscreen name=iscreen src='' frameborder='0' border='0' style='width:100%;height:100%;overflow:hidden;'></IFRAME>");
 		wrt("</td></tr>");
 	wrt("</TABLE>");
 wrt("</td>");


//================================================ END MAIN TABLE =====================================================
wrt("</tr></TABLE>");





//============================== FLOATERS ======================================

$tmp=(!$showmenu || !$showtoolbar)?"display:none;":"display:block;";
$tmp2=(!$showmenu)?"50px":"129px";
$tmp3=(!$showtoolbar || !$showmenu)?"display:none;":"display:block;";
?>
<IMG id=iFlipMenu onmouseover="arrowFade(1)" onmouseout="arrowFade(0)" src="_pvt_images/arrow_red.png" onclick="flipMenu(null,0)" ondblclick="flipMenu(null,1)" style="width:20px;height:20px;<?=$tmp;?>position:absolute;top:5px;left:8px;cursor:default;z-index:9999;cursor:pointer;">
<IMG id=iMenuDivider src="_pvt_images/leftright.png" style="<?=$tmp3;?>width:22px;position:absolute;top:36px;left:<?=($gMenuWidth-30);?>;cursor:pointer;z-index:1; " title="drag to resize">
<?

$tmp2=(!$showmenu)?"display:none;":"display:block;";
$tmp="background:$btnbgcolor;color:$btncolor;border:solid 1px $btnbordercolor;";


?>
</BODY>
</HTML>
<?


?>

<SCRIPT>
//--- set menu split ---
if(gShowmenu){
	 var lft=get("chgMenuSplit");
	 if(!lft)lft=100;
	 lft=lft*1;
	 chgMenuSplit(lft); 
	_obj("iMenuDivider").style.left=lft+"px";
}
</SCRIPT>





