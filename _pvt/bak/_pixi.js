

//alert("_pixi.js 10");

/*



========= CURRENT TO DO ==============

- add VIEW cmds to undo/redo

- doing an undo/redo for a 'v' we don't always need to 'eval()' the whole cmd!

- screen background color undo/redo

- add 'filters'(?) to the menu (VIEWS that only have one image and get applied to the current image)
	MERGE?

- should we keep animation???

============= BIG OPTIONS! ============

- Make accounts private but add a 'publish' option

- Have a popularity list of configs available to all users

- Create config NFT's that can be used/applied to your own content and
  then used to create an NFT that credits the original config?

- create NFT's from NFT's and give % to parents?

- Upwork... smart contracts, NFT's?


=============== OTHER =================


- re-use deleted screens?

- We really need to eliminate locktyp and just have typ which is the current locktyp!)
  
- Currently you can only have one slideshow running at a time

- rewrite options.php - remove all the junk! (and fix global.php and _pvt_template.txt)



========================== 'BIG BUGS!' ============================
- In some tiling configurations "image" breaks the rotations ((eg. 12,12,8,8,4,4)
- Masks sometimes create faint (unmasked) lines between the boxes (see "fine lines" fix in pixi.js)
- HISTORY - animating SIZE and ZOOM is jerky! it looks to be the actual image within the frame that jumps around!
- Using <!DOCTYPE html> screws up sizing, position, and zoom! (this will have to be fixed eventually) (See "quirks mode")

- "blending bug" - WORKAROUND WORKS OK - see HELP COMMENTS.
  If we set the view background to a gradient blending doesn't work unless we set 
  the gradient for the the gPic.frame to the same (or a color). 
  The problem comes in when we ZOOM THE VIEW OUT - we want a consistent background.
  If it is transparent blending doesn't work.
  So when we set the view to a flat color we just set the frame to the same so it's fine, 
  but a gradient won't match up correctly unless we sync the gradient settings 
  (when zoomed out) with the view zoom setting!



========================= MINOR 'BUGS' =========================
- new view first loads the pic in the existing view - why?
- rotations are saved to history but they don't work great
- loadVisibleDivs seems to be broken (as soon as zoom>50) - do we really need it? (turned oof for now)


=========================== PRACTICAL STUFF =======================
- Add security checks to _newutil.php, cropper.php, ... other?


=========================== OTHER IDEAS =======================
- Orbits vs Rotations??? (Background vs Foreground?)
- NFT creator?
- Fullwarp and offsetWarp settings can be used in anything that calls calcpcts! (eg. Panels, Image,,,)
  How can use this without complicating things?
- names : auto generation of a phug using name(s)
- domain names??


======================================= GENERAL NOTES =============================================

Important files:
	_toolbar.php 		The overall contents (consists of the top toolbar and frames for the left menus 
						and the main picture and it's right side menus)
	_menu.php			Left menus (folders, images, etc)
	_pixi.php			Main picture and the right menus
	_pixitoolbar.js		Right menus code (currently included in pixi.php for ease of development)
	_pixi.js			Primary codebase for creating the picture and apply the right menu settings

"Actions" 	are recorded or auto generated changes saved as an "action"
"Views"		are saved Pictures (configuration files)
"Text"		are saved text layouts that can be re-used on any screen or independantly



=========== Variable Names ===========
Picture (gPic)			The entire picture - contains multiple images/screens
Screen (scr)			an image or frame within the big picture
scr.xxx					nbr of Boxes on the X axis
scr.maxx				nbr of Panels per Box (maxx*xxx = total number of rendered panels on the X axis)
scr.maxzx				total nbr of Panels per Image			
scr.vx					the current 'root' panel (top/left) for the current display (arrow keys chg this)
scr.zx					current panel nbr within a single image
scr.vzx					current image within the complete representation
scr.maxvx				total nbr of PANELS in a complete rotation (maxzx*gMAXVX)   
scr.mirror				Vertical/Horizontal (0=n/n, 1=n/H, 2=V/n, 3=V/H
scr.locktyp				if "lock"	then "type" = /filter/fade/mask/color/blend/
						if "child" 	then "type" = /frame/child/
						if "" 		then "type" = "" (not locked to any other screen)
scr.frame				container div
scr.lockedBy			scr.scrix of the parent (locked screens are locked to the EXACT POSITION & SIZE of the parent)
scr.childBy				scr.scrix of the parent (child screens hold position RELATIVE to the parent)
gMAXVX, gMAXVY			normally set to 4 (SLIDABLE uses 6)

fullWarp, offsetWarp	these are used to control the effect of the Warp (mainly for when when we are zoomed in)
						fullwarp 	: the entire screen is warped  				(default 1)
						offsetWarp 	: it warps from the center out (sort of!)	(default 0)
						(THEY CURRENTLY BREAK SKEW! Unless set to defaults- would be nice to make it work - nice effects!)
						0,0 = even numbered divisions are fixed, odd nbrs warp
						1,0 = the entire screen is warped
						0,0 = odd numbered divisions are fixed, even nbrs warp
						1,1 = the outer boxes are fixed and the interior is all warped


NOTE: LOCK AND CHILD SCREENS HAVE BEEN REMOVED/DISABLED!
(keeping them in sync etc with parents was causing too many bugs, but the code is still there)
scr.typ and scr.lockTyp
		TYP's	  LOCKTYP's
		-----	  ----------
		"" 		= default free floating layer
		"color" = special typ of free floating layer (no image, gradient color)
		"lock"  = fade, blend, mask, filter, colorbg (these are locked to the parent)
		"child" = child, frame 
					("child" free floats but also moves and resizes with the parent)
					("frame" is just a pre-configured child that floats behind the parent)

============ TILING ============
- The Picture consists of a frame and the image itself.
- Object Hierarchy:  Picture -> Screens(frames/images) -> Boxes -> Panels
- The SCREEN's Tiling is defined by the following:
    - nbr of Boxes (xxx, yyy)
    - nbr of Panels per full image (maxzx, maxzy)
    - nbr of Panels per Box (maxx, maxy)

  (eg. Tiling: 2,2,3,3,4,4 = 2x2 Boxes, each Box has 4x4 Panels, each image takes 3x3 panels to represent it)
- screen.vx and vy are the current starting panel/point for the current display (normally 0 except for SKEWABLE etc)


========== USECANVAS ========
  0=image only (use for iframes), 1=one canvas/box, 2=one canvas/panel

  - usecanvas=0: (no longer supported but i left the code in)
    This does not create any CANVAS objects (it just uses a div and images)
    Tiling and rotations work (BUT only if there is no Image distortions,
    otherwise the results are interesting... frackish)
    Fold and Image settings only work in the default (unrotated) view.
    SVG's do NOT work properly when distorted in any way, but can still be rotated and tiled.
    ANIMATED GIFS WORK (all the time)
    Corners work great
    3D Type PANEL rotations work.

  - usecanvas=1: (RECOMMENDED)
    This creates a single CANVAS object for each box
    It allows us to do global canvas operations to a box (one image instant)
    It is quicker!
    It does NOT allow individual panels (fracked panels) to rotate (bug?)
    Doesn't rotate PANELS
    Mask operations WORK

  - usecanvas=2: (no longer supported but i left the code in)
    This creates a separate canvas for every panel (rotations are done using CSS)
    The advantage is we have a div to manipulate (rotations etc.)
    The disadvantage is we can't do global canvas operations
    Corners works on the entire image
    ANIMATED GIFS WORK (when the image is being animated)
    3D Type PANEL rotations work
    Mask operations DO NOT WORK





 ========================= FYI NOTES =========================

- 'actions' and animations are only saved with a View if they are playing at the time you save it

- Only "filters" actaully needs a scr.images array - the slideshow uses the folder's images
	and I'm not sure if even the gPic's images array is used (should it be?).  scr.imgix refers to scr.images 
	but best to leave it for now - it finds next in folder by finding the current image in the folder 
	list first - not ideal but it works and don't want to mess anything else up.

- The 'lockScrix' setting is disabled. The intent is when locked it doesn't make any difference where you 
  click with the mouse - the effect is always applied to the current screen (ie. clicking doesn't chg currency).
  However there is a bug - when you turn it on it works fine, but when it is then turned off things don't go back to normal!

- SLIDABLE (smooth rotating) has a bug (just keeps going at the edge - maybe because I ovveride offsetwarp?) 
  and the "screenOptions" (fullwarp and offsetwarp) are not required anymore - they break the skew/warp 
  work together (so i always set them to fullwarp=1 and offsetwarp=0)


*/

//------------------------- global variables -----------------------------------
var gToolbar,gPicDiv,gPic,gAniStack=",",gDebug=0;
var gOriginalPicDeleted=0;
var gEmbedded=0;
var gLoaded=0;
var gDefaultFrameColor="";
//var gDefaultPerspective="scaleX(1) scaleY(1) rotate(0deg) skew(0deg,0deg) perspective(1045) rotate3d(0,180,0,0deg) origin:0,0,";
var gDefaultPerspective=""; // this seems to work fine but not sure?
var gDefaultUseCanvas=1;
var gStdVars=",screen,panel,split,image,fold,warp,split,zoom,slide,move,size,frack,";

//----------------- SPECIALIZED CONFIGS (tileTypes) -------------------------

//--- NOTE: The tiling pattern is "screenx,screeny,imagex,imagey,panelx,panely"

//--- DEFAULT ---
//NOTE: there are 4 versions of the image (mirror and split combinations) [getMirror]
var gMAXVX=gMAXVY=4;
var gDEFtile	="1,1,4,4,4,4";	
var gNONEtile	="1,1,1,1,1,1";
var gSIMPLEtile ="1,1,2,2,2,2";



//------------------------------- onLoad ---------------------------------------  
function onLoad(){
try{if(parent.gFrameTop!=null)gEmbedded=1;}catch(e){}  //detect parent window
$(document).bind("contextmenu",function(e){return false;});
$(document).mouseup(function(e){ gPic.md=0; gPic.mousemoved=0 });
$(".radio").dgStyle();
//--- draggables ---
$("#iDragBtn").draggable({
 start: iDragBtnStart,
 drag : iDragBtnDrag,
 stop : iDragBtnEnd
});
$("#iBoxBdrTop").draggable({
 start: iDragBoxStart,
 drag : iDragBoxDrag,
 stop : iDragBoxEnd
});
//------- toolbar --------
gPicDiv=_obj("picdiv");
gToolbar=new Toolbar(_obj("iMenuControlsDiv"),gPicDiv);
gToolbar.expandAll(0);
//--- create picture ---
try{ parent.hideWinShows(); }catch(e){}
//msg("view="+gConfigName);
//alert("txt="+gConfigText);
gPic=new Picture(gConfigName,gConfigText,gToolbar,gImgIx,gImages,gDir);
//listScreens();
gToolbar.syncing=1;
gPic.setAllPerspectives();
//gPic.filters=parent.gFilters;
//--- restore pic settings --- (in config?)???
gPic.lockZindex=get("lockZindex",gPic.lockZindex);  
//if(gConfigText=="|||")gPic.ResizeChildren=get("ResizeChildren",gPic.ResizeChildren);
//---- config toolbar ---
gToolbar.setConfigTitle();
gToolbar.openMenuControls( ((gEmbedded)?1:0) );
setTimeout("gToolbar.changeMode(gPic.mousemode);",1000);
try{parent.hilitePixi(gPic.configName);}catch(e){}
gToolbar.syncing=0;
if(gPic.screen.media!=null && gPic.screen.media!="image")gPic.repaintAll();
else{
	gPic.setNaturalSizes(0); 
	//gPic.setSquareSizes(0);
}
gPic.repaintAll();
gPic.viewApplySettings();
gToolbar.viewImage();
//if(!gEmbedded)parent.flipMenu(null,1);
CP=new cpColorPicker(); 
window.focus();
gLoaded=1;
}

//--- this creates a new Picture without re-loading the page but what about the old one??? ----
//--- (better to just reload the page?)
function loadNewPicture(configName,txt){	
gPic.deletePicture();
gImgIx=_rdm(0,gImages.length-1); 
if(configName==null)configName="";
if(txt==null)txt="|||";
newPicture(configName,txt);
}

//------- create a new picture ------
function newPicture(configName,txt){
try{ parent.hideWinShows(); }catch(e){}
gPic=new Picture(configName,txt,gToolbar,gImgIx,gImages,gDir);
gToolbar.syncing=1;
gPic.setAllPerspectives();
//gPic.filters=parent.gFilters;
gToolbar.setConfigTitle();
gToolbar.syncControls();
try{parent.hilitePixi(gPic.configName);}catch(e){}
gToolbar.syncing=0;
window.focus();
}

//----------------- drag btn functions (resize btn) ---
function iDragBtnDrag(event){
var x=(event.pageX-gToolbar.mdpagex);
var y=(event.pageY-gToolbar.mdpagey);
var scr=gPic.screen;
if(scr.typ=="lock")scr=scr.lockedByScreen;
var xpct=((0-x)/eleWidth(gPic.frame))*100;
if(scr.lockPosition){
 var v=Math.round(gToolbar.mdxSize+((xpct)*-1));        //mdxquad
}else{
 var v=Math.round(gToolbar.mdxSize+((xpct*2)*-1));        //mdxquad
}
if(v<10)v=10;else if(v>199)v=199;
var xsize=v;
var ypct=((0-y)/eleHeight(gPic.frame))*100;
if(scr.lockPosition){
 var v=Math.round(gToolbar.mdySize+((ypct)*-1));
}else{
 var v=Math.round(gToolbar.mdySize+((ypct*2)*-1));
}
if(v<10)v=10;else if(v>199)v=199;
var ysize=v;
//msg("iDragBtnDrag()");
scr.natural=0;
scr.square=0;
scr.setVars("size",xsize,ysize);
gPic.repaintSizes(scr.scrix);
}



function iDragBtnMouseDown(o,e){
var scr=gPic.screen;
if(scr.typ=="lock")scr=scr.lockedByScreen;
gToolbar.mdpagex=e.pageX;
gToolbar.mdpagey=e.pageY;
gToolbar.mdxSize=scr.xSize;
gToolbar.mdySize=scr.ySize;
gToolbar.mdxMove=scr.xMove;
gToolbar.mdyMove=scr.yMove;
gToolbar.mdW=eleWidth(scr.frame);
gToolbar.mdH=eleHeight(scr.frame);
}

function iDragBtnStart(){
gPic.unDoing=1;
DragStart();
}
function iDragBtnEnd(){
gPic.unDoing=0;
gPic.saveDoneStuff();
DragEnd();
}



//--- drag box handles functions ---
function iDragBoxDrag(event){
var scr=gPic.screen;
if(scr.typ=="lock")scr=gPic.getLockParent(scr);
var x=(event.pageX-gToolbar.mdpagex);
var y=(event.pageY-gToolbar.mdpagey);
var xp=((x/eleWidth(gPic.frame)*100)*100)/100;
var yp=((y/eleHeight(gPic.frame)*100)*100)/100;
xp=_round(xp,2);
yp=_round(yp,2);
scr.xMove=gToolbar.mdxMove+xp; 
scr.yMove=gToolbar.mdyMove+yp;
gPic.placeScreen(scr);
}

function iDragBoxMouseDown(o,e){
var scr=gPic.screen;
//if(scr.typ=="lock")scr=scr.lockedByScreen;
gToolbar.mdpagex=e.pageX;
gToolbar.mdpagey=e.pageY;
gToolbar.mdxMove=scr.xMove;
gToolbar.mdyMove=scr.yMove;
}

function iDragBoxStart(){
gPic.unDoing=1;
DragStart();
}
function iDragBoxEnd(){
gPic.unDoing=0;
gPic.saveDoneStuff();
DragEnd();
}


//============================ API FUNCTIONS ===================================
//==============================================================================

var gParentLoading;


function gotoNextLoop(media){
//--- this is called by the play.php timer (not by a user clicking the toolbar)
//--- so we have to apply it to the correct media
var found=1;
if(gPic.screen.media!=media){
 found=0;
 for(var i=0; i<gPic.screens.length;i++){
  if(gPic.screens[i].DELETED)continue;
  if(gPic.screens[i].media==media){
   	gToolbar.changeScreen(i);
   	found=1;
}}}
if(found){
 gPic.screen.nextImage(1);
 gToolbar.viewImage();
 return 1;
}return 0;
}


function api_flipThumbs(x){
try{
parent.flipThumbs(x);
}catch(e){}
}

function api_thumbsOpen(){
var x=0;
try{
x=parent.thumbsOpen();
}catch(e){}
return x;
}

//--- playPause, gotoNext and gotoPrior should NOT be called by PLAY.PHP because
//--- they trigger a call to parent (play.php) to change the image
function playPause()          { gPic.playPause();   gToolbar.viewImage(); }
function gotoNext()           { gPic.screen.nextImage(1);  gToolbar.viewImage(); }
function gotoPrior()          { gPic.screen.nextImage(-1); gToolbar.viewImage(); }

function setButtons(media,on)        { var playing=(on)?0:1; gToolbar.setPlayPauseBtn(playing); try{ gPic.getVideoScreen().videoFrame.setButtons(media,on); }catch(e){} }
function setParentButtons(media,on)  { try{ parent.setButtons(media,on,0,1); }catch(e){} }

//---- use videoFrame -----
function playVideo()          { gPic.getVideoScreen().videoFrame.playVideo(); }
function pauseVideo()         { gPic.getVideoScreen().videoFrame.pauseVideo(); }
function getVolume()          { return gPic.getVideoScreen().videoFrame.getVolume(); }
function setVolume(v)         { gPic.getVideoScreen().videoFrame.setVolume(v); }
function getVideoTime()       { return gPic.getVideoScreen().videoFrame.getVideoTime(); }
function setVideoTime(t)      { gPic.getVideoScreen().videoFrame.setVideoTime(t); }

function getCurrentMedia()	{ return gPic.screen.media; }
function getCurrentSrc()			{ return gPic.screen.src;	}

function findSrcScreen(src){ 
for(var i=0; i<gPic.screens.length;i++){ 
 	if(gPic.screens[i].DELETED)continue;
	if(gPic.screens[i].src==src)return i; 
}
return null;
}


function updPlayBtn(playing){
// NOTE: This is not called for videos
//alert("playing="+playing);
for(var i=0; i<gPic.screens.length;i++){ gPic.playing=0; }
gToolbar.setPlayPauseBtn(playing);
gPic.playing=playing;
}


var gInPixiLoaded=0;


//==============================================================================
//==============================================================================
//============================== PICTURE OBJECT ================================
//==============================================================================

function Picture(configname,configtext,toolbar,imgix,images,dir){
//msg("=============");
//msg("images.length="+images.length);
//msg("imgix="+imgix);
gPic=this;
if(configname=="")configname="home";
this.configName	=configname;
this.aniName	="default";
this.media		="image";
this.toolbar	=toolbar;
this.frame		=toolbar.frame;
this.desktop	=_obj("picdiv").parentElement;
this.frame.style.overflow	="visible";
this.desktop.style.overflow	="hidden";
this.imgix		=imgix;
this.images		=images;
this.imgobjects	=new Array();
this.MIRRORobjects=new Array();   
this.screens	=new Array();
this.PATHS		=new Array();	
this.zindex		=500;
this.aniStack	=",";
this.xmode		="xImage";
this.ymode		="yImage";
this.mousemode	=parent.gMouseMode;
this.lockZindex	=0;
//this.lockScrix=0;
this.d3Mode		="window";
if(dir==null)dir=gDir;
this.gDir		=dir;
this.showBorders=0;
this.showNumbers=0;
this.lockTiledImage=1;
this.mirrorChildren=0;  
this.rgbOn		=1;
//---------- pre-load images ---------
for(var i=0;i<this.images.length;i++){
	//msg("preloading "+this.images[i]);
	var img=this.imgobjects[this.imgobjects.length]=new Image();  
	img.src=this.images[i];
	this.MIRRORobjects[i]=img;
}
//--default options---
this.useCanvas	=gDefaultUseCanvas;   
this.dopple		=0;
this.pauseDelay	=6;
this.handles	=0;
//---vview vars ---
this.desktopColor   =gDesktopBgColor;
this.gradient		="";
this.gradientType	="flat";
this.xViewZoom		=100;
this.xViewSlide		=0;
this.yViewSlide		=0;
this.blend			=0;
this.opacity2		=1;
this.cssfilters		="";
this.perspective	="";
this.toolbar.viewSyncControls();
var a=configtext.split("{{{");
if(a[0]=="")a[0]="|||";
//--------- merge view? -------------
if(a[0]!="|||" && parent.oldLayers!=null){
	//--- merge other settings - put the new view first so that existing settings take precedence ---
	var cfg=parent.oldLayers;	//see grabPicLayers()
	var oldLayers=cfg.split("|||");
	var newLayers=a[0].split("|||");
	var txt="";
	//first merge the layers where possible
	for(var i=0;i<oldLayers.length-1;i++){
		txt+=oldLayers[i];
		if(i<newLayers.length-1){
			//merge cssFilters
			var oldL=oldLayers[i].split("s.cssfilters='");
			var newL=newLayers[i].split("s.cssfilters='");
			if(oldL.length>1 && newL.length>1){
				var oldCss=oldL[1].split("';")[0].split(" ");
				var newCss=newL[1].split("';")[0];
				for(var k=0;k<oldCss.length;k++){
					if(oldCss[k]){
						var f=oldCss[k];
						var name=f.substring(0,_ix(f,"("));
						if(!_in(newCss,name))newCss+=" "+f;
					}
				}
				//note: we can leave the old cssFilters settings alone because they get overridden by 
				//		the new one that we add on the end
				newLayers[i]+="s.cssfilters='"+newCss+"';";
			}
			//remove src and zindex
			newLayers[i]=this.cleanMerge(newLayers[i]); 
			txt+=newLayers[i];
		}
		txt+="|||";
	}
	//if the new view has any more layers then add them
	if(i<newLayers.length-1){
		while(i<newLayers.length-1){
			txt+=newLayers[i]+"|||";
			i++;
		}
	}
	a[0]=txt;
	parent.oldLayers=null;
}
//---- apply config -----
var b=a[0].split("|||");
var ix=this.imgix;
for(var i=0;i<b.length-1;i++){
 	if(b[i]){
  		cfig=new Config(b[i]);
 	}else{
  		cfig=new Config(null,null,null,null,null,null,this.gDir,this.images,ix);
  		ix++;
  		if(ix>this.images.length-1)ix=0;
 	}
 	this.addNewScreen(cfig);
 	var scr=this.screen;
 	if(scr.typ=="lock" && scr.media!="video")scr.frame.style.pointerEvents="none"; //turn off for parent (why?)
 	else{
		//alert("events 4");
		this.events(scr);
	}
 	this.calcLTWH(this.scrix);
 	scr.loadImage(scr.src);
}
if(a[1]){
	var tmp=_rep(a[1],'@###@','"');
 	eval(tmp);  //saved locks, animations, desktopcolor, etc.
}else{
 	gPic.viewDesktopColor();
}
//--- create skeleton config ---
this.genericConfig=new Config();
}


Picture.prototype.cleanMerge=function(txt){ //remove src and zindex
var a=txt.split(";");
var s="";
for(var i=0; i<a.length; i++){
	if(!_in(a[i],".zindex=") && !_in(a[i],".src="))s+=a[i]+";";
}
return s;
}


//=========================== SCREEN CREATION ==================================

// NOTE: locktyp "color" is screwed up - it is a special type of free floating layer
//		 We might add new types like this but it should be a "typ" not a "locktyp"!

//----------------- add a new screen -------------------------------------------
Picture.prototype.addScreen=function(typ,locktyp,media,dir){
//@@@ we no longer have lock or child screens!
//NOTE: valid typ's = "", "color", "child", "lock"
//		"" 		= default free floating layer
//		"color" = special typ of free floating layer (no image, gradient color)
//		"child" = "child" or "frame" ("frame" is just a pre-configured child that floats behind the parent)
//		"lock"  = fade, blend, mask, filter, colorbg (these are locked on top of the parent)
	
//--- if a parent exists then add the new screen to that ---
//var parentscr=this.getLockParent(this.screen);
var oldscr	 =this.screen;
var oldzindex=this.screen.zindex;

if(typ==null)typ="";
if(locktyp==null)locktyp="";
if(media==null)media="image";
if(dir==null)dir=parent.gMenu.gDir;
//var images      =(locktyp=="filter")? parent.gFilters : gPic.images;  
var images      =gPic.images;  
var imgix       =Math.floor((Math.random()*(images.length-1))+1);
var tiletype    =this.getTileType(typ,locktyp); 
var tiling      =null;
cfig=new Config("",tiletype,tiling,typ,locktyp,media,dir,images,imgix); 
if(dir=="Filters"){
	cfig.Tiling="1,1,1,1,1,1";			// "add layer" from "../Filters"
	this.setTiling(cfig,cfig.Tiling);
}
this.addNewScreen(cfig);
//alert("tiling="+cfig.Tiling+", dir="+dir);
var scr=this.screen;
//msg("typ="+typ+", lock="+locktyp)
//@@@ we no longer have lock or child screens! (but keep this code?)
//============= NOT LOCKED SCREENS ================ (blank or CHILD!)
/*
if(typ!="lock"){	
	//--- typ is either blank (unattached screen) or "child" (child or frame) ---
	scr.lockedByScreen=null;	
	if(scr.media!="video"){
	 	if(scr.lockPosition){
			scr.xMove=scr.yMove=20;
		}else{
			scr.xMove=scr.yMove=0;
		}
	}
	//--- set default size and position ---
 	var xm,ym,xsign,ysign;
	switch(scr.scrix){
		   case 1	: xm=ym=-20; 	 xsign=-1; ysign=-1;	break;
		   case 2	: xm=ym=20;  	 xsign=1;  ysign=1;		break;
		   case 3	: xm=20; ym=-20; xsign=1;  ysign=-1;	break;
		   case 4	: xm=-20; ym=20; xsign=-1; ysign=1;		break;
		   case 5	: xm=0; ym=0; 	 xsign=0;  ysign=0;		break;
		   default	: xm=ym=25+scr.scrix; xsign=0;  ysign=0;
	}
	scr.xMove=xm;
	scr.yMove=ym;
	if(scr.media!="image"){
		scr.xZoom=50;
		scr.yZoom=50;
		if(scr.media!="video")scr.shadowsOn=0;
		scr.frameColor=gDefaultFrameColor;
	}
	switch(typ){
		case "child"	:
			switch(locktyp){
			case "frame"   :
				scr.shadowsOn=1;
				scr.xMove=parentscr.xMove;
				scr.yMove=parentscr.yMove;
				scr.xSize=parentscr.xSize+10;
				scr.ySize=parentscr.ySize+10;
				parentscr.childScreens[parentscr.childScreens.length]=scr;
				scr.childByScreen=parentscr;
				this.setZindex(scr,parentscr.zindex-1);
				break;
			case "child"   :
				parentscr=gPic.getChildParent(parentscr);	// make this an option?
				scr.shadowsOn=0;
				//msg("xs="+parentscr.xSize+",xm="+parentscr.xMove);
				scr.xSize=50;
				scr.ySize=50;
				scr.xMove=25*xsign;
				scr.yMove=25*ysign;
				//scr.xSize*=((parentscr.xSize-10)/50);	// -10 because the default size is 60 and (50/50)*50=50 which = 1/4 of the window
				//scr.ySize*=((parentscr.ySize-10)/50);	
				parentscr.childScreens[parentscr.childScreens.length]=scr;
				scr.childByScreen=parentscr;
				this.setZindex(scr,parentscr.zindex+1);
				break;
			}
			break;
		case "color"   :	// fullscreen background
			scr.hideImage		=1;
			scr.shadowsOn		=1;
			scr.shadows='1 0px 0px 20px 4px rgba(0,0,0,1) inset';
			scr.setGradient(null,"linear","top","#0e9be8","#0ddcc2",30,60);
			this.setZindex(scr,400);
			scr.lockTyp="";
			gToolbar.goFullscreen(1);
			scr.xSize	=scr.ySize	=100;
			scr.xMove	=scr.yMove	=0;
	 		gToolbar.expandFrameTools(1);
	 		gToolbar.expandBackground(1);
	 		gToolbar.expandShadows(1);
			_obj("iBackgroundTable").style.display="";
			return;
		case "" :
		default  :
			scr.xSize= scr.ySize=45;
	 }
	 this.calcLTWH(this.scrix);
	 this.events(scr);
	 this.changeScreen(this.scrix);
	 return;
}
*/

//============= LOCKED SCREENS (typ="lock") ====================
// (Note: if no locktyp then parentscr==scr)
/*@@@ (no more lock screens)
parentscr.lockedScreens[parentscr.lockedScreens.length]=scr;
scr.lockedByScreen=parentscr;
scr.lockTyp		=locktyp;
scr.lockPosition=parentscr.lockPosition;
scr.resizeChildren=parentscr.resizeChildren;
scr.xSize		=parentscr.xSize;
scr.ySize		=parentscr.ySize;
scr.xMove		=parentscr.xMove;
scr.yMove		=parentscr.yMove;
*/
scr.lock		="";
scr.shadowsOn	=0;
scr.frameColor	=scr.frame.style.backgroundColor	="transparent";
this.setZindex(scr,oldzindex+1);
//this.events(scr);
switch(locktyp){
/*	
case "filter":
	 scr.hideImage=0;
	 scr.imgix      =Math.floor((Math.random()*(parent.gFilters.length-1))+1);
	 scr.gDir		="filters";
	 scr.src		=parent.gFilters[scr.imgix];
	 if(!parent.filterObjects[scr.imgix]){
		parent.filterObjects[scr.imgix]=new Image();
	 	parent.filterObjects[scr.imgix].src=scr.src;
	 }
	 scr.gImg=parent.filterObjects[scr.imgix];
	 break;
*/
case "fade":
	 scr.hideImage=1;
	 scr.shadowsOn=1;
	 scr.shadows="1 0px 0px 50px 30px rgba(255,255,255,1) inset";
	 //gToolbar.expandFrameTools(1);
	 gToolbar.expandFrameTools(1);
	 break;
case "blend": 
	 scr.setBlend(1);
	 gToolbar.expandColorsTools(1);
	 break;
case "mask":  
	 scr.MaskOn		=1;
	 scr.maskType	=1;
	 scr.maskStart	=40;
	 scr.maskBlur 	=20;
	 scr.maskRed	=0;
	 scr.maskGreen	=0;
	 scr.maskBlue	=0;
	 scr.maskSolid	=0;
	 scr.maskSolidAlpha=128;
	 gToolbar.expandColorsTools(1);
	 gToolbar.expandBox("iMask",1);
	 break;
}

this.calcLTWH(scr.scrix);
this.events(scr);
//msg("addScreen: ix="+scr.scrix+", old="+oldscr.scrix);
this.changeScreen(scr.scrix,oldscr.scrix);
//msg(cfig);
return;
}



//-------------------------- addNewScreen --------------------------------------
//This is used to (re)create a screen using cfig
Picture.prototype.addNewScreen=function(cfig){
var frame=gPic.frame ;
var ix=this.screens.length;
gPic.scrix=ix;
//msg("addNewScreen()");
//msg("addNewScreen() src="+cfig.src);
var div=_newObj("DIV");
_addObj(frame,div);
div.id=this.nextID();
div.className="framediv";
var scr=new Screen(ix,gPic,div,cfig);
//if((cfig.media=="image" || cfig.media=="video") && cfig.lockTyp!="filter"){
if(cfig.media=="image" || cfig.media=="video"){
	this.createImgLoad(scr,scr.scrix);
}
this.setZindex(scr,cfig.zindex);
gPic.screens[ix]=scr;
gPic.screen=scr;
//---- add shadows div ----
var sdiv=_newObj("DIV");
_addObj(div,sdiv);
sdiv.className="framediv";
sdiv.style.pointerEvents="none";
scr.shadowsDiv=sdiv;
//sdiv.style.background="red";
//alert("addNewScreen masktyp="+scr.maskType+", start="+scr.maskStart);
}


//------------- get tile type -------------  
Picture.prototype.getTileType=function(typ,locktyp){
if(!typ)typ="";
if(!locktyp)locktyp="";
switch(typ){
	case "child"	:
		switch(locktyp){
			case "child"	:
			case "frame"	: 	return "Simple"; 
		}
		break;	
	case "lock"		:
		switch(locktyp){
			case "mask"  	:
			case "blend" 	:		return "Simple"; 
				
			case "colorbg"  :
			case "fade"  	:
			case "filter"  	:
			default 	 	: 		return "None"; 
		}	
		break;
	case "color"	: return "None";
}
return "Default";
}


//==========================Recordings ==============================

//--------------------- save pic recording --------------------------
Picture.prototype.savePicRekord=function(name){
if(!name)name=this.aniName;
var x=this.screen.history.getTxt();
gToolbar.saveRekord(name,x);
return name;
}

//----------------------- load recording ----------------------------
Picture.prototype.loadRekord=function(name,txt){
if(!name)name=this.aniName;
else{
	this.aniName=name;
	this.screen.history.loadTxt(txt);		// do we need to do this if aniName=name?
}
gToolbar.syncHistory();
this.screen.history.StartStop();
}


//========================== Z-Index ==============================

//---------------- set zIndex for a screen ------------------------
Picture.prototype.setZindex=function(scr,z,chg){
//msg("setZindex["+scr.scrix+"].z="+scr.zindex+", z="+z);
//console.trace();
if(scr.DELETED)return;
scr.zindex=(z)? z : scr.zindex+chg;
scr.frame.style.zIndex=z;
if(scr==gPic.screen)gToolbar.applyLabel(scr);	// to update the zindex display
}


//----------------- change the zIndex -----------------------------  
Picture.prototype.changeZindex=function(scr,chg,e){
//if chg==99 then put it on top
var tofront=(chg==99)?1:0; 
if(scr==null)scr=this.screen;
var ctrl=(e)? e.ctrlKey : 0;
if(ctrl){
	this.setZindex(scr,scr.zindex+chg);
	return;
}
var tmpz;
var oldz	=scr.zindex;
var newz	=(chg==1)? 9999999 : 0;
var newscr	=null;
var scrns	=this.screens;
var topz	=0;
var topscr	=null;
//msg("oldz="+oldz)
for(var i=0;i<scrns.length;i++){
	 if(scrns[i].DELETED)continue;
	 if(scr!=scrns[i]){
		 tmpz=scrns[i].zindex;
		 if(tofront){
		 	if(tmpz>topz){ topscr=scrns[i]; topz=tmpz; }
		 }else{
			//msg("tmpz="+tmpz)
		  	if(chg > 0 && tmpz > oldz && tmpz < newz){ newz=tmpz; newscr=scrns[i]; }
		  	if(chg < 0 && tmpz < oldz && tmpz > newz){ newz=tmpz; newscr=scrns[i]; }
		 }
}	}
if(tofront){
	if(!topscr || topz==0)return;
	scr.zindex=topscr.zindex+1;
	this.setZindex(scr,scr.zindex,0);
	this.setZindex(topscr,topscr.zindex,0);
}else{
	//msg("newz="+newz);
	if(newz!=9999999 && newz!=0){ 
		this.setZindex(scr,newz,0);
		this.setZindex(newscr,oldz,0);
	}
}
}

//------------------ chgLockedZindex ------------------
Picture.prototype.chgLockedZindex=function(scr){
var parentz=scr.zindex;
for(var i=0;i<scr.lockedScreens.length;i++){ 	
	var lscr=scr.lockedScreens[i];
	if(lscr.DELETED)continue;
	var z	=parentz + ((lscr.lockTyp=="colorbg")? -1 : 1);
	this.setZindex(lscr, z); 
	//this.chgLockedZindex(lscr);  //should never be any
}}


//------------------ chgChildZindex ------------------
Picture.prototype.chgChildZindex=function(scr){
var parentz=scr.zindex;
for(var i=0;i<scr.childScreens.length;i++){  
	if(scr.childScreens[i].lockTyp=="frame") {	
		var cscr=scr.childScreens[i];
		if(cscr.DELETED)continue;
		if(cscr.lockTyp=="frame")	this.setZindex(cscr, parentz-1); 
		this.chgChildZindex(cscr); 
}	}
}


//========================= Paint Functions =====================

//--------- repaintAll -----------
Picture.prototype.repaintAll=function(){
this.calcLTWHAll();
for(var i=0;i<this.screens.length;i++){
	var scr=this.screens[i];
	if(scr.DELETED)continue;
	scr.repaint(1);	// nokids
}}

//---------- repaint ------------
Picture.prototype.repaint=function(ix){
//msg("scr["+ix+"].z="+gPic.screens[ix].zindex);
this.calcLTWH(ix);
var scr=gPic.screens[ix];
//msg("scr["+ix+"].z="+scr.zindex);
scr=gPic.getLockParent(scr);
scr=gPic.getChildParent(scr);
if(scr.DELETED)return;
scr.repaint();	// repaints kids as well
//msg("scr.z="+scr.zindex);
}


//------------- repaintAllSizes -------------
Picture.prototype.repaintAllSizes=function(){
for(var i=0;i<this.screens.length;i++){
	var scr=this.screens[i];
	if(scr.DELETED)continue;
	this.calcLTWH(i);
	scr.calcSpecs();
	scr.paint();
}	
gToolbar.syncHandles();
}



//---------- repaintSizes ---------------
Picture.prototype.repaintSizes=function(ix){
var scr=this.screens[ix];
this.calcLTWH(ix);
scr.calcSpecs();
scr.paint();
gToolbar.syncHandles();
}


Picture.prototype.resizeChildren=function(scr){
if(scr.resizeChildren){
	for(var j=0;j<scr.childScreens.length;j++){
		//msg("child="+scr.childScreens[j].scrix);
		//this.calcLTWH(scr.childScreens[j].scrix);
		//scr.childScreens[j].calcSpecs();
		//scr.childScreens[j].paint();
		this.repaintSizes(scr.childScreens[j].scrix);
	}
}}


Picture.prototype.resizeLockedScreens=function(scr){
for(var j=0;j<scr.lockedScreens.length;j++){
	scr.lockedScreens[j].calcSpecs();
	scr.lockedScreens[j].paint();
	this.resizeLockedScreens(scr.lockedScreens[j]);
}}



//------------------------ .calcLTWHAll ----------------------------
Picture.prototype.calcLTWHAll=function(){
for(var i=0;i<this.screens.length;i++){
	if(this.screens[i].DELETED)continue;
	this.calcLTWH(i,1);	// nokids
}}


//------------------------ .calcLTWH ----------------------------
Picture.prototype.calcLTWH=function(ix,nokids){
var ww,hh,bdr,div,scr;
var w,h,l,t,ownrs=1;
ww=eleWidth(this.frame);
hh=eleHeight(this.frame);
var scr=gPic.screens[ix];
if(scr.DELETED)return;
div=scr.frame;
if(!scr.lockPosition){
	bdr=Math.round((ww*((100-scr.xSize)/100)));
	w=ww-bdr;
	l=(bdr/2)+((scr.xMove/100)*ww);
	bdr=Math.round((hh*((100-scr.ySize)/100)));
	h=hh-bdr;
	t=(bdr/2)+((scr.yMove/100)*hh);
}else{
	w=Math.round(ww*(scr.xSize/100));
	l=Math.round(((scr.xMove/100)*ww)*100) /100;
	h=Math.round(hh*(scr.ySize/100));
	t=Math.round(((scr.yMove/100)*hh)*100) /100;
}
scr.frmW=w;
scr.frmL=l;
scr.frmH=h;
scr.frmT=t;
if(!nokids)this.calcLocked(scr,w,l,h,t);
}


//-------------------------- calcLocked --------------------------------------- 
Picture.prototype.calcLocked=function(scr,w,l,h,t){
for(var j=0;j<scr.lockedScreens.length;j++){
   var lsc=scr.lockedScreens[j];
   lsc.frmW=w;
   lsc.frmL=l;
   lsc.frmH=h;
   lsc.frmT=t;
   this.calcLocked(lsc,w,l,h,t);
}}
	

//----------- place Screen ----------------
Picture.prototype.placeScreen=function(scr,copy){
if(scr.lockedByScreen){
	if(copy){	//if called from below
		scr.xMove				=scr.lockedByScreen.xMove;
		scr.xMove				=scr.lockedByScreen.xMove;
	}else{		//always place the parent first
		scr.lockedByScreen.xMove	=scr.xMove;
		scr.lockedByScreen.yMove	=scr.yMove;
		scr=scr.lockedByScreen;
	}
}
if(!copy)gPic.calcLTWH(scr.scrix);	// calcs for locked and child screens as well
var div			=scr.frame;
var chgL		=scr.frmL-getLeft(div);
var chgT		=scr.frmT-getTop(div);
div.style.left	=scr.frmL;
div.style.top	=scr.frmT;
for(var j=0;j<scr.lockedScreens.length;j++){
 	gPic.placeScreen(scr.lockedScreens[j],1);	//copy=1
}
this.shiftChildren(scr,chgL,chgT);
if(!copy){
	scr.history.recordAction("s.SV('move',"+scr.xMove+","+scr.yMove+");");   
	gToolbar.syncHandles();
 	gPic.saveDoneStuff();
}}


//-------------------------- shiftChildren( ---------------------------------------  
Picture.prototype.shiftChildren=function(scr,l,t){
for(var j=0;j<scr.childScreens.length;j++){
	lsc=scr.childScreens[j];
	var x=getLeft(lsc.frame);
	lsc.frmL=lsc.frame.style.left=x+l;
	var y=getTop(lsc.frame);
	lsc.frmT=lsc.frame.style.top=y+t;
	lsc.reverseEngineerSpecs();
	gPic.shiftChildren(lsc,l,t);
}
}


//----------- getLockParent ----------------------
Picture.prototype.getLockParent=function(scr){
//while(scr.typ=="lock"){ scr=scr.lockedByScreen; }	
return scr;
}


//----------- getChildParent ----------------------
Picture.prototype.getChildParent=function(scr){
//while(scr.typ=="child"){ scr=scr.childByScreen; }	
return scr;
}

//---------- getTarget -----------------------
//--- NOTE: possible locktyp's are filter/fade/mask/frame/colorbg/colorfg/blend/child
Picture.prototype.getTarget=function(scr,nm){
if(scr==null)scr=this.screen;
if(scr.typ!="lock")return scr;
var mom		=this.getLockParent(scr);
if(mom==scr)return scr;
if(nm==null)nm="";
var locktyp	=scr.lockTyp;
var stdvar	=this.stdVar(nm);
var ovarie	=this.orientationVar(nm);
var colorvar=this.colorVar(nm);
if(nm=="" || nm=="size")return mom;
// locktyps = filter/fade/mask/blend/colorbg/colorfg
//if(ovarie || nm=="size" || nm=="move" || nm=="slide")return mom;
if(locktyp=="fade")	{ if(nm=="shadows" || nm=="blend")	return scr; else return mom; }
if(colorvar) 	return scr;
if(!scr.hideimg)return scr;	//????
return mom;
}


//----------- stdVar ----------------------
//gStdVars=",screen,panel,image,fold,warp,split,zoom,slide,move,size,frack,";
Picture.prototype.stdVar=function(n){
	if(_in(gStdVars,","+n+","))return 1;
	return 0;
}

//----------- 2dVar ----------------------
Picture.prototype.orientationVar=function(n){
	var names=",2drot,skew,swivel,tilt,axis,3drot,spin,";
	if(_in(names,","+n+","))return 1;
	return 0;
}

//----------- colorVar ----------------------
Picture.prototype.colorVar=function(n){
	var names=",contrast,brightness,saturate,blur,sepia,hue-rotate,grayscale,invert,";
	if(_in(names,","+n+","))return 1;
	return 0;
}

/*
//---------------------- rotateScreen --------------------------------
Picture.prototype.rotateScreen=function(scr,vx,vy){
if(scr.images[scr.imgix]==scr.gImg.src)scr.paint();
else scr.loadImage();
}
*/

//-------------------------- setNaturalSizes -------------------------------------------
Picture.prototype.setNaturalSizes=function(nopaint){
if(gToolbar.syncing)return; //doesn't work if syncing
for(var i=0;i<this.screens.length;i++){
 if(this.screens[i].DELETED || this.screens[i].lockedByScreen)continue;
 if(this.screens[i].natural){
	this.screens[i].naturalSize();
	if(!nopaint)this.repaintSizes(i);
}}}

//-------------------------- setSquareSizes -------------------------------------------
Picture.prototype.setSquareSizes=function(nopaint){
return; //@@@
if(gToolbar.syncing)return; //doesn't work if syncing
//msg("setSquareSizes nopaint="+nopaint);
for(var i=0;i<this.screens.length;i++){
 if(this.screens[i].DELETED || this.screens[i].lockedByScreen)continue;
 if(this.screens[i].square){
	this.screens[i].squareSize();
	if(!nopaint)this.repaintSizes(i);
}}}



//=================VIEW CONTROLS ===============


//----------- load all view settings ----------
Picture.prototype.viewApplySettings=function(){
this.viewZoom();
this.viewSlide();
this.viewBlend();
//this.viewDesktopColor(gPic.desktopColor);
this.viewSetGradient();
this.viewCSSFilters(this.cssfilters);
this.viewOpacity2(this.opacity2);
gToolbar.viewSyncControls();
}


//----------- viewZoom ------------
Picture.prototype.viewZoom=function(z){
	var oldscale="scale("+this.xViewZoom/100+","+this.xViewZoom/100+")";
	if(z!=null && z!=this.xViewZoom)
		this.saveDoneStuff("gPic.viewZoom("+this.xViewZoom+");"+
						   "gPic.viewZoom("+z+");");
	if(z==null)z=this.xViewZoom;
	else this.xViewZoom=z;
	//msg("z="+z);
	z=z/100;
	var str		=this.perspective;
	var scale	="scale("+z+","+z+")";
	str=str.replace(oldscale,scale);
	if(!_in(str,scale))str=scale+" "+str;
	//msg("str="+str);
	gPic.transform(this.frame,str);
	if(z<100 && this.gradientType!="flat")this.viewSetGradient();
}

//----------- viewSlide ----------------------
Picture.prototype.viewSlide=function(x,y){
	if(x!=null || y!=null)this.saveDoneStuff("gPic.viewSlide("+this.xViewSlide+","+this.yViewSlide+");"+
						   					 "gPic.viewSlide("+x+","+y+");");
	if(x==null)x=this.xViewSlide;
	else this.xViewSlide=x;
	if(y==null)y=this.yViewSlide;
	else this.yViewSlide=y;
	this.frame.style.left=x+"px";
	this.frame.style.top =y+"px";
	if((x!=0 || y!=0) && this.gradientType!="flat")this.viewSetGradient();
}



//-------------View Blend ----------------
Picture.prototype.viewBlend=function(i){
if(i==null)i=this.blend;
else{
	//msg("i="+i+", vblend="+this.blend);
	if(i!=this.blend)this.saveDoneStuff("gPic.viewBlend("+this.blend+");gPic.viewBlend("+i+");");
	this.blend=i;
}
var b=gBlends[i];
this.frame.style.mixBlendMode=b;
}
	

//------- check if any blending ---------
Picture.prototype.anyBlending=function(){
for(var i=0; i<gPic.screens.length;i++){ 
 	if(gPic.screens[i].DELETED)continue;
	if(gPic.screens[i].blend>0)return 1; 
}
return 0;
}


//------- set the actual background color ---------	//$$$$$
Picture.prototype.viewDesktopColor=function(c){
if(c==null) c=gPic.desktopColor; 
else		gPic.desktopColor=c;
if(c=="transparent"){  //"blending bug"
	if(gPic.anyBlending())c=gPic.desktopColor;
	if(c=="transparent")c="#ffffff";
}
if(c!=gPic.lastDesktopColor && gPic.lastDesktopColor && !gPic.unDoing){
	msg("gPic.viewDesktopColor('"+gPic.lastDesktopColor+"');gPic.viewDesktopColor('"+c+"');");
	gPic.saveDoneStuff("gPic.viewDesktopColor('"+gPic.lastDesktopColor+"');gPic.viewDesktopColor('"+c+"');");
}	
gPic.desktop.style.background	=c;
gPic.frame.style.background		=c;	
}


//-----------View gradient ---------------
Picture.prototype.viewSetGradient=function(str,typ,position,color1,color2,pct1,pct2){
if(str==null)str=this.gradient;     else this.gradient=str;
if(typ==null)typ=this.gradientType; else this.gradientType=typ;
this.viewDesktopColor(this.desktopColor);	//do this regardless ("blending bug")
//msg("1style="+this.frame.style.background);
if(typ!="flat"){
	 if(str==null)str="";
	 if(position==null)position=this.getGradPos(str);
	 if(color1==null)  color1  =this.getGradColor1(str);
	 if(color2==null)  color2  =this.getGradColor2(str);
	 if(pct1==null)    pct1    =this.getGradPct1(str);
	 if(pct2==null)    pct2    =this.getGradPct2(str);
	 if(typ=="linear" && position=="center")typ="radial"; //"center" does not work with "linear"
	 str=position+", "+color1+" "+pct1+"%, "+color2+" "+pct2+"%";
	 this.gradient=str;
	 if(this.anyBlending()){   //"blending bug"
		 if(this.xViewSlide==0 && this.yViewSlide==0 && this.xViewZoom>99){	// desktop not showing?
			 //msg("setGrad");
			 setGradient(this.frame,str,typ);
		 }else{
			 //msg("set trans 1");
		 	 this.frame.style.background="transparent";	// sacrifice the blending
		 }
	 }else{
		 //msg("set trans 2");
	 	 this.frame.style.background="transparent";
	 }
	 setGradient(this.desktop,str,typ);
}
}


Picture.prototype.setGradientColor2=function(c){gPic.viewSetGradient(null,null,null,null,c);}
Picture.prototype.setGradientColor1=function(c){gPic.viewSetGradient(null,null,null,c);}
Picture.prototype.getGradPos=function(str)     {if(str==null)str=this.gradient; if(!str)return "center";  var a=str.split(", "); return a[0]; }
Picture.prototype.getGradPct1=function(str)    {if(str==null)str=this.gradient; if(!str)return 0;   var a=str.split(", ")[1].split(" ")[1].split("%"); return a[0]; }
Picture.prototype.getGradPct2=function(str)    {if(str==null)str=this.gradient; if(!str)return 100; var a=str.split(", ")[2].split(" ")[1].split("%"); return a[0]; }

Picture.prototype.getGradColor1=function(str)  {if(str==null)str=this.gradient; if(!str)return "#0e9be8"; var a=str.split(", ")[1].split(" "); return a[0]; }

Picture.prototype.getGradColor2=function(str)  {
	if(str==null)str=this.gradient; 
	if(!str)return "#0ddcc2"; 
	var a=str.split(", ")[2].split(" "); 
	return a[0]; 
}


Picture.prototype.viewCSSFilters=function(filters){   
this.cssfilters					=filters;
this.frame.style.webkitFilter	=filters;
this.frame.style.filter			=filters;
}


Screen.prototype.viewCSSFilters=function(filters){  // altho it's not for a view we NEED to call it this!
//msg("FILTERS="+filters);
this.cssfilters					=filters;
this.frame.style.webkitFilter	=filters;
this.frame.style.filter			=filters;
this.div.style.webkitFilter		=filters;
this.div.style.filter			=filters;
}

Picture.prototype.applyCSSFilter=function(obj,filter,v){   
var filters=obj.cssfilters;
if(filters==null)filters="";
//if(_in(filters,"inherit"))filters="";
var typ, txt="", filterFound=0, f=filter;
switch(filter){
	case "blur" :		typ="px"; 		break;
	case "hue-rotate" :	typ="deg";		break;
	default	:			typ="%";
}
filter=filter+"("+v+typ+")";
var a=filters.split(" ");
for(var i=0;i<a.length;i++){
	 if(a[i]){
		  if(_in(a[i],f)){ 
			  if( (v=="100" && (f=="saturate" || f=="brightness" || f=="contrast")) ||
				  (v=="0"   && (f!="saturate" && f!="brightness" && f!="contrast")) ){
				 filter=""; //ignore
			  }else{
			  	 txt+=filter+" "; 
			  }
			  filterFound=1; 
		  }else txt+=a[i]+" ";
}	}
if(!txt)txt=filter;
else if(!filterFound)txt+=filter;
//saturate(100%) brightness(100%) contrast(100%) blur(0px) hue-rotate(0deg) sepia(0%) grayscale(0%)
//msg("apply="+txt);
if(filters!=txt){
	this.saveDoneStuff("gPic.viewCSSFilters('"+filters+"');gPic.viewCSSFilters('"+txt+"');");
}
obj.viewCSSFilters(txt);
}

Picture.prototype.viewOpacity2=function(x,noapply){ 
if(noapply!=1 && this.opacity2!=x){	
	this.saveDoneStuff("gPic.viewOpacity2('"+this.opacity2+"');gPic.viewOpacity2('"+x+"');");
}
this.opacity2=x;
if(noapply!=1)setOpacity(this.frame,x*99);
}


//--------------View Perspective ------------  
Picture.prototype.viewPerspective=function(str,mode,bigmirror,rotate,skewx,skewy,persp,rotx,roty,rot3d,flipz,origin,originoffset){    
//  scaleX(1)  scaleY(1) 	 rotate(0deg) 	skew(0deg,0deg) 	perspective(612) 	rotate3d(  101,   -86,      0,  	-101deg) 
//	bigmirror				 2DRotate		SKEW				TILT						   Y-AXIS   X-AXIS	Z-AXIS 	 ROT3D
if(str!=null){
	this.perspective=str;
}else{
	str=this.perspective;
	if(rotate==null)rotate=this.getPerRotate(str);
	if(skewx==null)skewx=this.getPerSkewX(str);
	if(skewy==null)skewy=this.getPerSkewY(str);
	if(persp==null)persp=this.getPerTilt(str);
	if(rotx==null)rotx=this.getPerRotX(str);
	if(roty==null)roty=this.getPerRotY(str);
	if(rot3d==null)rot3d=this.getPerRot3d(str);
	if(flipz==null)flipz=this.getPerFlipz(str);
	if(origin==null)origin=this.getPerOrigin(str,0);
	if(originoffset==null)originoffset=this.getPerOrigin(str,1);
	str="rotate("+rotate+"deg) skew("+skewx+"deg,"+skewy+"deg) perspective("+persp+") rotate3d("+rotx+","+roty+","+flipz+","+rot3d+"deg) origin:"+origin+","+originoffset+",";
}
if(this.perspective!=str){
	this.saveDoneStuff("gPic.viewPerspective('"+this.perspective+"');gPic.viewPerspective('"+str+"');");
}
this.perspective=str;
this.viewZoom();
}




//=================== MISC ======================

//----- switch frame and pic image sets -----
Picture.prototype.swapImages=function(){
var mx=this.screens.length;
if(mx<2)return;
var scrns=new Array();
var ix=0;
for(var i=0;i<mx;i++){
 if(this.screens[i].DELETED)continue;
 if(!this.screens[i].hideImage && this.screens[i].media=="image"){scrns[ix]=this.screens[i];ix++;}
}
mx=scrns.length;
if(mx<2)return;
var imgix=scrns[0].imgix;
var gimg=scrns[0].gImg;
for(var i=0;i<mx;i++){
 var i2=i+1;
 if(i2==mx){
  scrns[i].imgix=imgix;
  scrns[i].gImg=gimg;
 }else{
  scrns[i].imgix=scrns[i2].imgix;
  scrns[i].gImg=scrns[i2].gImg;
 }
 scrns[i].paint();
}}


//-------- getVideoScreen --------
Picture.prototype.getVideoScreen=function(){
if(gPic.screen.media=="video")return gPic.screen;
for(var i=0; i<gPic.screens.length;i++){ 
 	if(gPic.screens[i].DELETED)continue;
	if(gPic.screens[i].media=="video")return gPic.screens[i]; 
}}


//--------- flipCanvas -----------
Picture.prototype.flipCanvas=function(v){
this.useCanvas=v;
alert("Canvas="+v+". Note: This only applies to NEW screens");
}


//------ flipLockPosition --------
Picture.prototype.flipResizeChildren=function(scr,v){
if(scr==null)scr=this.screen;
if(v==null)v=(scr.resizeChildren)?0:1;
scr.resizeChildren=v;
}

//------ flipLockPosition --------
Picture.prototype.flipLockPosition=function(scr,v){
if(scr==null)scr=this.screen;
if(v==null)v=(scr.lockPosition)?0:1;
scr.lockPosition=v;
if(v){
 scr.xMove=((100-scr.xSize)/2)+scr.xMove;
 scr.yMove=((100-scr.ySize)/2)+scr.yMove;
}else{
 scr.xMove=scr.xMove-((100-scr.xSize)/2);
 scr.yMove=scr.yMove-((100-scr.ySize)/2);
}
}


//============ TILING ==============



//------- setTileType ------
Picture.prototype.setTileType=function(typ){
if(!typ)typ="";
this.screen.tileType=typ;  
this.applyPattern(this.screen);
this.tileScreen(this.scrix,this.screen.Tiling);
}


//------- tile ------
Picture.prototype.tileSlider=function(n){
var Tiling;
if(n==null){
	n=this.screen.tileXY;
	n++;
	if(n>12)n=0;
	if(n<0)n=12;
}
var scr=this.screen;
gToolbar.resetTileType(scr);
switch(n){
	case  0:  Tiling=gDEFtile;     	    break;
	case  1:  Tiling="1,1,4,4,5,5";     break;
	case  2:  Tiling="1,1,4,4,8,8";     break;
	case  3:  Tiling="1,1,4,4,10,10";   break;
	case  4:  Tiling="1,1,8,8,18,18";   break;
	case  5:  Tiling="1,1,6,6,18,18";   break;
	case  6:  Tiling="1,1,6,6,24,24";   break;  
	case  7:  Tiling="1,1,5,5,24,24";   break;    
	case  8:  Tiling="1,1,4,4,24,24";   break;
	case  9:  Tiling="1,1,2,2,24,24";   break;
	case  10: Tiling="1,1,2,2,30,30";   break;
	case  11: Tiling="1,1,2,2,36,36";   break;
	case  12: Tiling="1,1,1,1,36,36";   break;   
}
scr.Tiling=Tiling;
this.tileScreen(this.scrix,Tiling);
this.screen.tileXY=n;
}



//------------------- apply Pattern -------------------
Picture.prototype.applyPattern=function(s){
// NOTE: scr could be a CONFIG!
// See gTilingPatterns in toolbar.php
if(!s)s=this.screen;
var typ=s.tileType;
if(typ==null)typ="";
gToolbar.resetTileType(s);
s.tileType=typ;
switch(typ){
 case "Simple" :
	  s.Tiling="1,1,2,2,2,2";
	  break;
 case "Complete" :	// 
	  s.Tiling="1,1,4,4,28,28";
	  break;
 case "Fabric" :	
	  s.Tiling	="1,1,2,2,32,32";
	  s.vx=s.vy=7;		
	  s.xZoom	=s.yZoom	=54;
	  break;
 case "Cornered" :
	  s.Tiling	="1,1,4,4,12,12";
	  break;
 case "None" :
	  s.Tiling	="1,1,1,1,1,1";
	  break;
 case "Deep"   :			// skewable - big buffer for skewing
	  s.Tiling	="1,1,6,6,10,10";
	  s.xZoom	=s.yZoom	=87;
	  s.vx		=s.vy		=22;
	  s.fullSplit			=1;
	  s.offsetSplit			=1;
	  break;
 case "Shallow"   :			// skewable - least nbr of panels
	  s.Tiling	="1,1,2,2,4,4";
	  s.xZoom	=s.yZoom	=100;
	  s.vx		=s.vy		=7;
	  s.fullSplit			=1;
	  s.offsetSplit			=1;
	  break;
 default :	
	  s.Tiling	="1,1,4,4,4,4";
	  break;
}
}


//---------- tileScreen() -----
// This deletes the old screen and creates a new one!
Picture.prototype.tileScreen=function(ix,tiling){
var scr=gPic.screens[ix];
if(!scr)return;
if(scr.media!="image")tiling=gNONEtile;
this.setTiling(scr,tiling);

//undo/redo
var oldConfig	=scr.oldConfig;
var oldIX	 	=scr.doneIX;
var oldUnDoing	=gPic.unDoing;
//msg("tileScreen oldUnDoing="+oldUnDoing);
gPic.undDoing	=1;

var cfig=new Config(this.grabConfig(scr));
gPic.addNewScreen(cfig);
var scr2=gPic.screen;
scr2.gImg=scr.gImg;
gPic.moveLocking(scr,scr2);
this.setZindex(scr2,scr.zindex);
gPic.repaint(scr2.scrix);
scr2.history=scr.history;
scr2.animation=scr.animation;
scr2.history.screen=scr2;
scr2.animation.screen=scr2;
this.fixEvents();
gToolbar.viewImage();   
gToolbar.syncTiling();
gPic.deleteScreen(scr.scrix);
scr2.loadImage(scr2.gImg.src);
//undo/redo
scr2.oldConfig	=oldConfig;
scr.doneIX	  	=oldIX;
gPic.unDoing	=oldUnDoing;
gPic.saveDoneStuff();
}



Picture.prototype.setTiling=function(s,tiling){
s.Tiling=tiling;
var a=tiling.split(",");
s.xxx=a[0]; s.yyy=a[1]; s.maxzx=a[2]; s.maxzy=a[3]; s.maxx=a[4]; s.maxy=a[5];
s.maxvx=s.maxzx*gMAXVX;
s.maxvy=s.maxzy*gMAXVY;
if((s.vx>=s.maxvx)||(s.vy>=s.maxvy))s.vx=s.vy=0;
}


Picture.prototype.resetTiling=function(sync){  
var scr=gPic.getTarget(gPic.screen,"tiling");
this.applyPattern(scr);
if(sync)gToolbar.syncTiling();
}


//======================== DELETES AND EVENT HANDLING =========================

//-------- deletePicture ------
Picture.prototype.deletePicture=function(){
	for(var i=0;i<this.screens.length;i++){
		var scr=this.screens[i];
		this.simpleDeleteScreen(scr);
	}
	this.screens=new Array();
	this.screen=null;
	this.scrix=-1;
}


//------ This doesn't fix links/children etc -----
Picture.prototype.simpleDeleteScreen=function(scr){
scr.history.stopPlaying();
scr.animation.stopPlaying();
scr.animation=null;
scr.history=null;
scr.frame.style.display='none'; 
scr.frame.innerHTML="";
scr.frame.id="";
//scr=null;	//for dopple!
}


//---------------------------- deleteScreen ------------------------------------
//----- NB: Only use this to 'delete' a screen (locked or otherwise) -----------
//------------------------------------------------------------------------------
Picture.prototype.deleteScreen=function(ix){
var scr=gPic.screens[ix];
if(!scr)return;  
//--- mark the screen and its children as 'deleted' and hide the frames ----
this.markDeletions(scr); 
//--- disconnect the screen from it's parent if any ---
if(scr.lockedByScreen)gPic.removeLockedByScreen(scr.lockedByScreen,scr);
if(scr.childByScreen )gPic.removeChildByScreen(scr.childByScreen,scr);
//--- find a new current ---
if(gPic.screen!=scr){
	gToolbar.syncControls();
	return;
}	
var scr=null;
for(var i=0;i<gPic.screens.length;i++){
	if(gPic.screens[i].DELETED)continue;
	scr=gPic.screens[i];
	break;
}
if(scr==null){
	gPic.scrix=-1;
	gPic.screen==null;
	alert("WARNING: No layers left to make current!");
	return;
}
gPic.screen	=scr;
gPic.scrix	=scr.scrix;
gToolbar.syncControls();
}


//---------------------------- markDeletions ------------------------------------
Picture.prototype.markDeletions=function(scr){
scr.DELETED=1; 
scr.frame.style.display="none";
if(scr.media!="image")scr.frame.innerHTML="";
for(var i=0;i<scr.lockedScreens.length;i++)gPic.markDeletions(scr.lockedScreens[i]);
for(var i=0;i<scr.childScreens.length;i++) gPic.markDeletions(scr.childScreens[i]);
}	


//------------------------- eliminate deleted screens ----------------------------
Picture.prototype.fixIXnbrs=function(){
var ixs=new Array();
var lgth=gPic.screens.length;
for(var i=0;i<lgth;i++)ixs[i]=i;
//---figure out new IX NBRS for all screens
var cnt=0;
for(var i=0;i<lgth;i++){
	if(gPic.screens[i].DELETED){
		//subtract 1 from all the screens 'above' it
		for(var j=i+1;j<lgth;j++)ixs[j]--;
		ixs[i]=-1;//deleted
	}else cnt++;
}
//---- now actually delete the 'deleted' screens -----
for(var i=0;i<gPic.screens.length;i++){
	if(gPic.screens[i].DELETED)this.simpleDeleteScreen(gPic.screens[i]);
}
//---move screens down in the main array 
for(var i=0;i<ixs.length;i++){
	if(ixs[i]!=-1){
		gPic.screens[ixs[i]]=gPic.screens[i]; 
		gPic.screens[ixs[i]].scrix=ixs[i]; 
		gPic.screens[ixs[i]].frame.id="iScrn"+ixs[i];
		if(gPic.scrix==i){
			gPic.scrix=ixs[i];
			gPic.screen=gPic.screens[ixs[i]];
		}
	}
}
//---- clean up the main array
gPic.screens.splice(cnt,99);
gPic.fixEvents();
//listScreens();	
//msg("==================");
}


//-------------------------- removeLockedScreen( -------------------------------
//--- breaks the "lock" between two screens
Picture.prototype.removeLockedByScreen=function(ownr,lsc){
var ix;
for(var i=0;i<ownr.lockedScreens.length;i++){if(ownr.lockedScreens[i]==lsc)ix=i;}
if(ix==null)return;
ownr.lockedScreens.splice(ix,1);
lsc.lockedByScreen=null;
lsc.lockTyp="";
lsc.typ="";
}

//-------------------------- removeChildScreen( -------------------------------
Picture.prototype.removeChildByScreen=function(ownr,lsc){
var ix;
for(var i=0;i<ownr.childScreens.length;i++){if(ownr.childScreens[i]==lsc)ix=i;}
if(ix==null)return;
ownr.childScreens.splice(ix,1);
lsc.childByScreen=null;
lsc.lockTyp="";
lsc.typ="";
}


//-------------------------- moveLocking( --------------------------------
Picture.prototype.moveLocking=function(scr,scr2){
scr2.lockedScreens	=scr.lockedScreens;
scr2.lockedByScreen	=scr.lockedByScreen;
scr2.childScreens	=scr.childScreens;
scr2.childByScreen	=scr.childByScreen;
if(scr2.lockedByScreen){
	var parent=scr2.lockedByScreen;
	for(var i=0;i<parent.lockedScreens.length;i++){
		if(parent.lockedScreens[i]==scr){ parent.lockedScreens[i]=scr2; break; }
	}
}
if(scr2.childByScreen){
	var parent=scr2.childByScreen;
	for(var i=0;i<parent.childScreens.length;i++){
		if(parent.childScreens[i]==scr){ parent.childScreens[i]=scr2; break; }
	}
}
scr.lockedScreens	=new Array();
scr.lockedByScreen	=null;
scr.childScreens	=new Array();
scr.childByScreen	=null;
}

/*
//-------------------------- moveLocking( --------------------------------
Picture.prototype.moveLocking=function(scrfrom,scrto){
gPic.moveLockedScreens(scrfrom,scrto);
scrto.lockedByScreen=scrfrom.lockedByScreen;
scrto.childByScreen=scrfrom.childByScreen;
scrfrom.lockedByScreen=null;
scrfrom.childByScreen=null;
gPic.fixLockedByScreen(scrfrom,scrto);
}

//-------------------------- chgLockedByScreen( --------------------------------
//--- after tiling we need to chg the ownr's locked screen to the new one
Picture.prototype.fixLockedByScreen=function(oldscr,lsc){
var ownr=oldscr.lockedByScreen;
if(ownr){
	 for(var i=0;i<ownr.lockedScreens.length;i++){
  		if(ownr.lockedScreens[i]==oldscr){
   			ownr.lockedScreens[i]=lsc;
}	 }	}
var ownr=oldscr.childByScreen;
if(ownr){
	 for(var i=0;i<ownr.childScreens.length;i++){
		  if(ownr.childScreens[i]==oldscr){
			   ownr.childScreens[i]=lsc;
}	 }	  }
}


//---------------------- moveLockedScreens( ------------------------------------
Picture.prototype.moveLockedScreens=function(scr,toScr){
// locked screens
for(var i=0;i<scr.lockedScreens.length;i++){
	 var lsc=scr.lockedScreens[i];
	 toScr.lockedScreens[toScr.lockedScreens.length]=lsc;
	 lsc.lockedByScreen=toScr;
}
scr.lockedScreens=new Array();
// child screens
for(var i=0;i<scr.childScreens.length;i++){
	 var lsc=scr.childScreens[i];
	 toScr.childScreens[toScr.childScreens.length]=lsc;
	 lsc.childByScreen=toScr;
}
scr.childScreens=new Array();
}
*/


//----------------------------- fixEvents( -------------------------------------
Picture.prototype.fixEvents=function(){
//---fix events for all active screens (because ix has changed)
for(var i=0;i<gPic.screens.length;i++){
	var s=gPic.screens[i];
	if(s.DELETED)continue;
	gPic.events(s,i);
	gPic.createImgLoad(s,i);
}}



//-------------------------- .createImgLoad() ----------------------------------
Picture.prototype.createImgLoad=function(scr,ix){
//msg("createImgLoad ix="+scr.scrix+", "+ix);
scr.imgload=function(){
	//msg("imgLoad ix="+ix);
	
	var unDoing	=gPic.unDoing;	
	gPic.unDoing=1;
	//eval("msg(gPic.screens["+ix+"].gImg.src); ");
	//imgLoad unDoing="+gPic.unDoing);');
	
	eval("gPic.screens["+ix+"].setVignetteColor();");
	eval("gPic.screens["+ix+"].setDominantColor();");
	eval("gPic.screens["+ix+"].naturalSize();");
	eval("gPic.screens["+ix+"].repaint("+ix+");");
	gPic.toolbar.syncControls();
	
	gPic.unDoing=unDoing;
	
}}




//-------------------------- .events() -----------------------------------------
Picture.prototype.events=function(scr,scrix){
if(scrix==null)scrix=scr.scrix;
if(scr.media=="image"){
 	this.imageEvents(scr.frame,scr.div,scrix);
}else{
	 this.frameEvents(scr.frame,scr.div,scrix); //all this does is changeScreen() onmousedown
	 this.draggable(scr.frame.id);
	 this.resizable(scr.frame.id);
}}


Picture.prototype.resizable=function(x){
$("#"+x).resizable({
	//ghost: true,
	helper: "scrdiv-helper",
	start : function(event,ui){
		gToolbar.changeScreen(gPic.getScreenFromID(this.id));
		gPic.DragStart();
	},
	stop : function(event,ui) {
		gPic.DragEnd();
		gPic.repaintAll();	//?????
	}
});
}

Picture.prototype.draggable=function(x,op){
if(op==null)op=0.7;
$("#"+x).draggable({
 start : function(event,ui) {
  gPic.DragStart();
 },
 stop : function(event,ui) {
  gPic.DragEnd();
 },
 opacity: op
});
}

Picture.prototype.DragStart=function(){try{_obj("iDragCover").style.display="block";}catch(e){}}
Picture.prototype.DragEnd=function()  {try{_obj("iDragCover").style.display="none"; }catch(e){}}
Picture.prototype.nextID=function()   {return "iScrn"+this.scrix;}
Picture.prototype.getScreenFromID=function(id){return parseInt(id.replace("iScrn",""));}


//-------------------------- frameEevents() -----------------------------------
//--- used for videos etc. ---
Picture.prototype.frameEvents=function(frame,div,scrix){
$(frame).mousedown(function(e){
 if(scrix!=gPic.scrix){
 	gPic.changeScreen(scrix);
 	try{gPic.toolbar.syncControls();}catch(e){}
 }
});
}


//-------------------------- imageEevents() -----------------------------------
Picture.prototype.imageEvents=function(frame,ele,scrix){
var v,scr,x,y,w,h,xpct,ypct,xmode,ymode,ctrl;
var BottomRightCorner,Top100,Top50;
//msg("1. imageEvents scrix="+scrix+", gPic.scrix="+gPic.scrix+", frame.id="+frame.id);	
	
$(ele).dblclick(function(e){
 	scrix=ele.parentElement.id.replace("iScrn","")*1;
	scr=gPic.screens[scrix];
	if(scr.hideImage && scr.lockedByScreen){
		 scr=gPic.getLockParent(scr);
		 scrix=scr.scrix;
	}
	if(scrix!=gPic.scrix){
		gPic.changeScreen(scrix);
		try{gPic.toolbar.syncControls();}catch(e){}
	}
	gToolbar.goFullscreen();
	
});

	
$(ele).mousedown(function(e){
 //scrix=ele.parentElement.id.replace("iScrn","")*1;
 scrix=gPic.screen.scrix; //@@@	
 scr=gPic.screen;
 gPic.MouseDown=1;
		
	//----- color picker ---
	if(gToolbar.swapPicker || gToolbar.fillPicker){
		if(e.offsetX) { x = e.offsetX; y = e.offsetY; }
		else if(e.layerX) { x = e.layerX; y = e.layerY;	}
		gToolbar.swapPickerBox.div.style.cursor="default"; 
		if(gToolbar.fillPicker){
			gToolbar.fillPicker(x,y);
			return;
		}
		var a=e.target.id.split("_");	//target is the canvas
		var box=scr.boxes[xyKey(a[1],a[2])];
		box.boxdata = box.context.getImageData(0, 0, box.context.canvas.width, box.context.canvas.height);
		var xred=(y * box.canvas.width + x) * 4;
    	var c=rgbToHex(box.boxdata.data[xred],box.boxdata.data[xred+1],box.boxdata.data[xred+2]);
		gToolbar.swapPicker(c);
		return;
	}
	
 ctrl=(e)? e.ctrlKey : 0;
 //  ctrl=1;	//@@@ so we don't change currency	
 	
	
 if(!ctrl){	// if not ctrl then changescreen otherwise apply to current screen
	 scr=gPic.screens[scrix];
	 if(scr.hideImage){
		 if(scr.lockedByScreen){
			 scr=gPic.getLockParent(scr);
			 scrix=scr.scrix;
		 }
	 }
	 if(scrix!=gPic.scrix){
		  gPic.changeScreen(scrix);
		  try{gPic.toolbar.syncControls();}catch(e){}
	 }
 }
 if(ctrl || scrix==gPic.scrix){
	  scr=gPic.screen;
	  gPic.md=1;
	  gPic.mdpagex=e.pageX;
	  gPic.mdpagey=e.pageY;
	  gPic.lastpageX=e.pageX;
	  gPic.lastpageY=e.pageY;
	  gPic.mdx=(e.pageX-this.offsetLeft);
	  gPic.mdy=(e.pageY-this.offsetTop);
	  gPic.mdW=eleWidth(scr.frame);
	  gPic.mdH=eleHeight(scr.frame);
	  gPic.offsetX=this.offsetLeft;
	  gPic.offsetY=this.offsetTop;
	  gPic.mdxWarp=scr.xWarp;
	  gPic.mdyWarp=scr.yWarp;
	  gPic.mdxSplit=scr.xSplit;
	  gPic.mdySplit=scr.ySplit;
	  gPic.mdxFold=scr.xFold;
	  gPic.mdyFold=scr.yFold;
	  gPic.mdxCrop=scr.xCrop;
	  gPic.mdyCrop=scr.yCrop;
	  gPic.mdlCrop=scr.lCrop;
	  gPic.mdtCrop=scr.tCrop;
	  gPic.mdxImage=100-scr.xImage;
	  gPic.mdyImage=100-scr.yImage;
	  gPic.mdxMove=scr.xMove;
	  gPic.mdyMove=scr.yMove;
	  gPic.mdxSlide=scr.xSlide;
	  gPic.mdySlide=scr.ySlide;
	  gPic.xSlideAdjust=0;
	  gPic.ySlideAdjust=0;
	  gPic.mdxZoom=scr.xZoom;
	  gPic.mdyZoom=scr.yZoom;
	  gPic.mdxSize=scr.xSize;
	  gPic.mdySize=scr.ySize;
	  gPic.mdxPanelSkew=scr.xSkew;	
	  gPic.mdyPanelSkew=scr.ySkew;
	  gPic.mdxSkew=scr.picture.getPerSkewX(scr.perspectivePnl);	
	  gPic.mdySkew=scr.picture.getPerSkewY(scr.perspectivePnl);
	  gPic.mdxSwivel=scr.picture.getPerSkewX(scr.perspectiveFrm);	
	  gPic.mdySwivel=scr.picture.getPerSkewY(scr.perspectiveFrm);
	  gPic.mdRotate3d=scr.picture.getPerRotate(scr.perspectiveFrm);
	  gPic.mdxTurn=scr.vx;	
	  gPic.mdyTurn=scr.vy;
	  gPic.mdxImgix=scr.imgix;
	  gPic.mdyImgix=scr.imgix;
	  //---calc which quadrant the click occurred
	
	  //x=(e.pageX-getLeft(ele));
	  //y=(e.pageY-getTop(ele));
	  x=(e.pageX-getLeft(scr.frame));
	  y=(e.pageY-getTop(scr.frame));
	
	  //gPic.mdxQuad=(x>((gPic.mdW/6)*5))?-1:1;
	  //gPic.mdyQuad=(y>((gPic.mdH/6)*5))?-1:1;
	  //BottomRightCorner=(gPic.mdxQuad==-1 && gPic.mdyQuad==-1)?1:0;
	  gPic.mdxQuad=(x>(gPic.mdW/2))?-1:1;
	  gPic.mdyQuad=(y>(gPic.mdH/2))?-1:1;
	  Top100=(y<100)?1:0;
	  Top50=(y<50)?1:0;
 }
});
	
$(ele).mouseup(function(e){
 	 scrix=ele.parentElement.id.replace("iScrn","")*1;
	 if(ctrl || scrix==gPic.scrix){	
		  gPic.md=0;
		  scr=gPic.screen;
		  if(gPic.mousemoved==1){
			  scr.picture.toolbar.syncControls(); 
			  //if(gPic.xMode=="xRotate")scr.picture.toolbar.syncPerspective(); 
		  }
	 }
	 gPic.mousemoved=0;
	 if(gPic.MouseDown){
	 	 gPic.MouseDown=0;
		 gPic.saveDoneStuff();
	 }
});
	
$(ele).mouseleave(function(e){
 	 scrix=ele.parentElement.id.replace("iScrn","")*1;
	 if((ctrl || scrix==gPic.scrix) && gPic.md)gPic.md=2;
	 if(gToolbar.swapPicker){
	 	gToolbar.swapPicker=null;
		_obj("iPicker1").style.border="solid 2px white"; 
		_obj("iPicker2").style.border="solid 2px white"; 
		gToolbar.swapPickerBox.div.style.cursor="default"; 
	 }
	 if(gPic.MouseDown){
	 	 gPic.MouseDown=0;
		 gPic.saveDoneStuff();
	 }
});
	
$(ele).mouseover(function(e){
 	 scrix=ele.parentElement.id.replace("iScrn","")*1;
	 if((ctrl || scrix==gPic.scrix) && gPic.md)gPic.md=1;
});
	
$(ele).mousemove(function(e){
	scr=gPic.screen;
	scrix=scr.scrix;
	if(gToolbar.swapPicker || gToolbar.fillPicker){
		var a=e.target.id.split("_");	//target is the canvas
		gToolbar.swapPickerBox=scr.boxes[xyKey(a[1],a[2])]; 
		gToolbar.swapPickerBox.div.style.cursor="crosshair"; 
	}
	var xx=yy=0;
	var rbtn=getRbtn(e);// Can't capture rbtn (press and drag) for some reason! (only detects it for a right CLICK)
	//if((ctrl || scrix==gPic.scrix) && gPic.md==1){
	if(ctrl || gPic.md==1){
		//----------------- mode ----------------
		//if(rbtn && !scr.fullscreen) { 
		//	xmode="xMove";    
		//	ymode="yMove";  
		//}else{ 
			xmode=gPic.xmode; 
			ymode=gPic.ymode; 
		//}   
		gPic.mmPageX=e.pageX;
		gPic.mmPageY=e.pageY;
		x=(e.pageX-gPic.offsetX);
		y=(e.pageY-gPic.offsetY);
		
		//--- pct change from original (mousedown) position ----
		xpct=((gPic.mdx-x)/gPic.mdW)*100;
		ypct=((gPic.mdy-y)/gPic.mdH)*100;
		xpct=_round(xpct,2);
		ypct=_round(ypct,2);
		
		if(xpct!=0 || ypct!=0)gPic.mousemoved=1;
		else return;
		switch(xmode){
			case "xWarp":
			case "xSplit":
			case "xImage":
			    var sign=(xmode=="xImage")?1:-1;
			    eval("v=Math.round(gPic.md"+xmode+"+(xpct*"+sign+"));");
			    if(v<1)v=1;else if(v>99)v=99;
			    if(xmode=="xImage")v=100-v;
				xx=v;
			    var sign=(ymode=="yImage")?1:-1;
			    eval("v=Math.round(gPic.md"+ymode+"+(ypct*"+sign+"));");
			    if(v<1)v=1;else if(v>99)v=99;
			    if(ymode=="yImage")v=100-v;
			    yy=v;
				var n=xmode.toLowerCase();
				n=n.replace("x","");
				gPic.applyVars(scr,n,xx,yy);
			    break;
			case "xFold":
			    v=Math.round(gPic.mdxFold+xpct);
			    if(v<1)v=1;else if(v>99)v=99;
				xx=v;
			    v=Math.round(gPic.mdyFold+ypct);
			    if(v<1)v=1;else if(v>99)v=99;
			    yy=v;
				gPic.applyVars(scr,"fold",xx,yy);
			    break;
			case "xZoom":
			    x=(e.pageX);
			    w=eleWidth(frame);
			    xpct=((gPic.mdpagex-x)/w)*100;
			    v=Math.round(gPic.mdxZoom+(xpct*2));
			    if(v<1)v=1;else if(v>500)v=500;
			    xx=v;
			    y=(e.pageY);
			    h=eleHeight(frame);
			    ypct=((gPic.mdpagey-y)/h)*100;
			    v=Math.round(gPic.mdyZoom+(ypct*2));
			    if(v<1)v=1;else if(v>500)v=500;
			    yy=v;  
				gPic.applyVars(scr,"zoom",xx,yy);
			    break;
			case "xMove":
			    v=gPic.mdxMove+Math.round(((((gPic.mdx-x)*-1)/eleWidth(gPic.frame))*100)*100) /100;   
			 	v=_round(v,2);
			    xx=v;
			    v=gPic.mdyMove+Math.round(((((gPic.mdy-y)*-1)/eleHeight(gPic.frame))*100)*100) /100;    
				v=_round(v,2);
			    yy=v; 	
				gPic.applyVars(scr,"move",xx,yy);
		    	break;
			case "xSlide":
			    if(xpct || ypct){
					var tmpx=scr.xSlide;	
					var tmpy=scr.ySlide;
					var tmpx=_round((gPic.mdxSlide-xpct)+gPic.xSlideAdjust,2);
					var tmpy=_round((gPic.mdySlide-ypct)+gPic.ySlideAdjust,2);
					gPic.applyVars(scr,"slide",tmpx,tmpy);
				}
			    break;
			case "xSize":
			    xpct=((gPic.mdx-x)/eleWidth(gPic.frame))*100;
			    v=Math.round(gPic.mdxSize+((xpct*2)*gPic.mdxQuad));
			    if(v<10)v=10;else if(v>199)v=199;
			    var xsize=v;
			    ypct=((gPic.mdy-y)/eleHeight(gPic.frame))*100;
			    v=Math.round(gPic.mdySize+((ypct*2)*gPic.mdyQuad));
			    if(v<10)v=10;else if(v>199)v=199;
			    var ysize=v;
			 	scr.natural=0;
			 	scr.square=0;
				gPic.applyVars(scr,"size",xsize,ysize);
			 	//gPic.repaintSizes(scr.scrix);
			    break;
			case "xSkew":
			    var xskew=Math.ceil(gPic.mdxPanelSkew+(((xpct/200)*360)*gPic.mdyQuad));
			    if(xskew<180){xskew+=180; xskew=180+xskew;}
			    if(xskew>180){xskew-=180; xskew=-180+xskew;}
			    xx=xskew;
			    var yskew=Math.ceil(gPic.mdyPanelSkew+(((ypct/200)*360)*gPic.mdxQuad));
				if(yskew<180){yskew+=180; yskew=180+yskew;}
			    if(yskew>180){yskew-=180; yskew=-180+yskew;}
			    yy=yskew;
				gPic.applyVars(scr,"panelskew",xx,yy);
			    break;
			case "xTilt":
			    var xskew=Math.ceil(gPic.mdxSwivel+(((xpct/200)*360)*gPic.mdyQuad));
			    if(xskew<180){xskew+=180; xskew=180+xskew;}
			    if(xskew>180){xskew-=180; xskew=-180+xskew;}
			    xx=xskew;
			    var yskew=Math.ceil(gPic.mdySwivel+(((ypct/200)*360)*gPic.mdxQuad));
			    if(yskew<180){yskew+=180; yskew=180+yskew;}
			    if(yskew>180){yskew-=180; yskew=-180+yskew;}
			    yy=yskew;
				gToolbar.chgD3Mode(1);
				gPic.applyVars(scr,"swivel",xx,yy);
			    break;
			case "xTurn":	
		    	var xturn=Math.ceil(gPic.mdxTurn+((xpct/100)*24));
		    	xturn=scr.validateXStart(xturn);
		    	scr.vx=xturn;
				var yturn=Math.ceil(gPic.mdyTurn+((ypct/100)*24));
			    yturn=scr.validateYStart(yturn);
				scr.vy=yturn;
				gPic.applyVars(scr,"turn",xturn,yturn);
			    break;
			case "xRotate":	
				var pct=xpct;
				var quad=gPic.mdyQuad;
				var xrotate=Math.ceil(gPic.mdRotate3d+((pct/100)*360));
				gToolbar.chgD3Mode(1);
				if(xrotate<0)xrotate=360+xrotate; 
				else if(xrotate>360)xrotate=xrotate-360;
				xrotate=xrotate*-1;
				if(xrotate==360)xrotate=0;
				gPic.applyVars(scr,"2drot",xrotate,0);
			    break;
		}
		
	}
});
}

//-------------------------- makeCurrent ---------------------------------------
Picture.prototype.makeCurrent=function(scr){
var ix=scr.scrix;
this.screens[ix]=scr;
this.screen=scr;
this.scrix=ix;
}


//---------------------- change screen currency --------------------------------
Picture.prototype.changeScreen=function(ix,oldix){
//msg("changeScreen ix="+ix);
if(this.screens.length==1)return;
if(oldix==null)oldix=this.scrix;
if(ix && (ix<0 || ix>(this.screens.length-1)))ix=null;
if(ix!=null){
 	scr=this.screens[ix];
}else{
 	scr=this.findNextScreen();
}
//msg(this.scrix+"???"+oldix);
//console.trace();
if(oldix!=scr.ix){
	this.saveDoScreenSwitch(oldix,scr.ix);
}
this.screen=scr;
this.scrix=scr.scrix;
gToolbar.setPlayPauseBtn(this.playing);
//gToolbar.syncFullscreenBtns();
//if(!this.lockZindex)this.changeZindex(this.screen,1);  
if(!this.lockZindex)this.changeZindex(this.screen,99);  
}


//------- find the next valid screen ------------
Picture.prototype.findNextScreen=function(){
var ix=this.scrix;
var startix=ix;
var scr=null;
while(scr==null){
	ix++;
	if(ix>this.screens.length-1)ix=0;
	if(!this.screens[ix].DELETED)scr=this.screens[ix];
	if(ix==startix)scr=this.screens[ix];
}
return scr;
}

//---------------- home -----------------------
Picture.prototype.home=function(){this.screen.vx=0;this.screen.vy=0;this.screen.paint();}


//-------------- strTiling ---------------------
Picture.prototype.strTiling=function(xxx,yyy,maxzx,maxzy,maxx,maxy){
return xxx+","+yyy+","+maxzx+","+maxzy+","+maxx+","+maxy;}



//======================== PERSPECTIVE FUNCTIONS ============================
//--- This whole thing needs a rewrite - see ThreeD.js -----

//------------------------- setAllPerspectives ------------------------------
Picture.prototype.setAllPerspectives=function(){
	this.setPerspectives("window");
	this.setPerspectives("image");
	this.setPerspectives("panels");
}

//--------------------------- setPerspectives -------------------------------
Picture.prototype.setPerspectives=function(d3mode){
if(d3mode==null)mode=this.d3Mode;
for(var i=0;i<gPic.screens.length;i++){
	var scr=this.screens[i];
	if(scr.DELETED)continue;
	scr.dontpaint=1;
	var str=this.getPerspectiveStr(scr,d3mode);
	if(!scr.lockedByScreen)gPic.setPerspective(scr,str,d3mode);	
	scr.dontpaint=0;
}}


//------------------------------- getPerspectiveVars -----------------------------------
Picture.prototype.getPerspectiveVars=function(str,vars,scr){
//perSP: range= -1000 to 0.  1000=none. 45=full. Actual range is 1045-45. So we have to reverse (x*-1)+45;
if(scr==null)scr=this.screen;
var bigmirror="";
var rotate=per3d=perskx=persky=0;
var persp=-1000, perrx=0, perry=180;
bigmirror=gPic.getBigMirror(str,scr);	
rotate=gPic.getPerRotate(str);
per3d=gPic.getPerRot3d(str);
perskx=gPic.getPerSkewX(str);
persky=gPic.getPerSkewY(str);
persp =gPic.getPerTilt(str); persp=(persp*-1)+45;  
perrx=gPic.getPerRotX(str);
perry=gPic.getPerRotY(str);
vars[0]=bigmirror;
vars[1]=rotate;
vars[2]=per3d;
vars[3]=perskx;
vars[4]=persky;
vars[5]=persp;
vars[6]=perrx;
vars[7]=perry;
return vars;	
}

//------------------------------- getPerspectiveStr -----------------------------------
Picture.prototype.getPerspectiveStr=function(scr,mode){
if(scr==null)scr=this.screen;
if(mode==null)mode=this.d3Mode;
switch(mode){
	case "window" : return scr.perspectiveFrm;
	case "image"  : return scr.perspectiveImg;
	case "panels" : return scr.perspectivePnl;
}}

//------------------------------- setPerspectiveStr -----------------------------------
Picture.prototype.setPerspectiveStr=function(scr,str,mode){
if(scr==null)scr=this.screen;
if(mode==null)mode=this.d3Mode;
switch(mode){
	case "window" : scr.perspectiveFrm=str; return;
	case "image"  : scr.perspectiveImg=str;	return;
	case "panels" : scr.perspectivePnl=str;	return;
}}

//-------------------------------- Perspective -----------------------------------  
// This updates the string and calls scr.setPerspective()
Picture.prototype.setPerspective=function(scr,str,mode,bigmirror,rotate,skewx,skewy,persp,rotx,roty,rot3d,flipz,origin,originoffset){    
//  scaleX(1)  scaleY(1) 	 rotate(0deg) 	skew(0deg,0deg) 	perspective(612) 	rotate3d(  101,   -86,      0,  	-101deg) 
//	bigmirror				 2DRotate		SKEW				TILT						   Y-AXIS   X-AXIS	Z-AXIS 	 ROT3D
if(scr==null)scr=this.screen;
if(mode==null)mode=this.d3Mode;
//--- if str is passed then use that ---
if(str!=null){
	this.setPerspectiveStr(scr,str,mode);
}else{
	str=this.getPerspectiveStr(scr,mode);
	if(bigmirror==null)bigmirror=this.getBigMirror(null,scr);	
	if(rotate==null)rotate=this.getPerRotate(str);
	if(skewx==null)skewx=this.getPerSkewX(str);
	if(skewy==null)skewy=this.getPerSkewY(str);
	if(persp==null)persp=this.getPerTilt(str);
	if(rotx==null)rotx=this.getPerRotX(str);
	if(roty==null)roty=this.getPerRotY(str);
	if(rot3d==null)rot3d=this.getPerRot3d(str);
	if(flipz==null)flipz=this.getPerFlipz(str);
	if(origin==null)origin=this.getPerOrigin(str,0);
	if(originoffset==null)originoffset=this.getPerOrigin(str,1);
	str=bigmirror+" rotate("+rotate+"deg) skew("+skewx+"deg,"+skewy+"deg) perspective("+persp+") rotate3d("+rotx+","+roty+","+flipz+","+rot3d+"deg) origin:"+origin+","+originoffset+",";
}
scr.setPerspective(scr,str,mode);
//---- apply to locked screens ----
for(var j=0;j<scr.lockedScreens.length;j++){
	var lsc=scr.lockedScreens[j];
	//msg("setPersp lockedscreen typ="+lsc.lockTyp);	
	lsc.setPerspective(lsc,str,mode);	
}
//---- apply to child screens ----
for(var j=0;j<scr.childScreens.length;j++){
 	var lsc=scr.childScreens[j];
 	lsc.setPerspective(lsc,str,mode);  
}
}


// ------- extract existing value from perspective string ----------
Picture.prototype.getCSSPerspective=function(str,f){  
//var str=this.getPerspectiveStr();
//msg("f="+f+", str="+str);
switch(f){
	case "bigmirror" 		: return this.getBigMirror(str,gPic.screen);
	case "2drot" 			: return this.getPerRotate(str);
	case "3drot" 			: return this.getPerRot3d(str);
	case "flipz" 			: return this.getPerFlipz(str);
	case "origin" 			: return this.getPerOrigin(str,0);
	case "originoffset"		: return this.getPerOrigin(str,1);
	case "tilt"				: return this.getPerTilt(str);
	case "Xskew"			: 
	case "Xswivel"			: return this.getPerSkewX(str);
	case "Yskew"			: 
	case "Yswivel"			: return this.getPerSkewY(str);
	case "Xaxis"			: return this.getPerRotX(str);
	case "Yaxis" 			: return this.getPerRotY(str);
}
return 0;
}


Picture.prototype.transform=function(obj,str){
if(str==null)str="";
gPic.transformOrigin(obj,str);
var a=str.split(" origin:");
var s=a[0];
obj.style.mozTransform   =s;
obj.style.webkitTransform=s;
obj.style.oTransform     =s;
obj.style.msTransform    =s;
obj.style.transform      =s;
}

Picture.prototype.getOriginTxt=function(origin){
var txt="center";
switch(origin){
	case 0: txt= "center";		break;
	case 1: txt= "top left";	break;
	case 2: txt= "top right";	break;
	case 3: txt= "bottom right";break;
	case 4: txt= "bottom left";	break;
} return txt; }

	
	
Picture.prototype.transformOrigin=function(obj,str){
var origin=this.getPerOrigin(str,0);
var originoffset=this.getPerOrigin(str,1);
var txt=this.getOriginTxt(origin);
if(originoffset!=0)txt+=" "+originoffset+"px";
obj.style.transformOrigin=txt;
}


Picture.prototype.getPerRotate=function(str){
if(!str)return 0;
var a=str.split("rotate(");
if(!a[1])return 0;
return a[1].split("deg")[0]*1; }

Picture.prototype.getPerRot3d=function(str){
if(!str)return 0;
var a=str.split("rotate3d(");
if(!a[1])return 0;
a=a[1].split("deg)"); a=a[0].split(","); return a[a.length-1]*1; }

Picture.prototype.getPerFlipz=function(str){ 
if(!str)return 0;
var a=str.split("rotate3d(");
if(!a[1])return 0;
a=a[1].split(","); 
return a[2]*1; }

Picture.prototype.getPerOrigin=function(str,offset){ 
if(!str)str=this.getPerspectiveStr();
var a=str.split("origin:");
if(!a[1])return "";
a=a[1].split(","); 
if(!a[offset])return "";
return a[offset]*1;
}


Picture.prototype.getPerRotX=function(str){
if(!str)return 0;
var a=str.split("rotate3d(");
if(!a[1])return 0;
a=a[1].split(","); return a[0]*1; }

Picture.prototype.getPerRotY=function(str){
if(!str)return 180;   
var a=str.split("rotate3d(");
if(!a[1])return 180;
a=a[1].split(","); return a[1]*1; }

Picture.prototype.getPerSkewX=function(str){
if(!str)return 0;
var a=str.split("skew(");
if(!a[1])return 0;
a=a[1].split("deg,"); return a[0]*1; }

Picture.prototype.getPerSkewY=function(str){
if(!str)return 0;
var a=str.split("skew(");
if(!a[1])return 0;
a=a[1].split("deg,"); 
return a[1].split("deg)")[0]*1; }

Picture.prototype.getPerTilt=function(str){
if(!str)return 1045;
var a=str.split("perspective(");
if(!a[1])return 1045;
a=a[1].split(") "); return a[0]*1; }


Picture.prototype.getBigMirror=function(str,scr){
var bm=this.strBigMirror(scr.bigMirror);
if(!str)return bm;
var a=str.split("rotate(");
if(a[0].length<2)return bm;
return a[0]; }


Picture.prototype.strBigMirror=function(v) {
v=v*1;
switch(v){
 case 0: return "scaleX(1)  scaleY(1) ";
 case 1: return "scaleX(-1) scaleY(1) ";
 case 2: return "scaleX(1)  scaleY(-1) ";
 case 3: return "scaleX(-1) scaleY(-1) ";
}return "";
}




//==========================================
//========== SCREEN OBJECT =================
//==========================================
function Screen(scrix,picture,frame,cfig){
var s=this;
var div;
//--- setup default vars ---
this.undoing=0;
this.cfigtxt=gPic.grabConfig(cfig);
this.scrix=scrix;
this.picture=picture;
this.frame=frame;
this.childix=0;
this.tileType="";   
this.tileXY=0;
this.lockedScreens=new Array();
this.childScreens=new Array();
this.boxes=new Array();
this.boxii=new Array();
//this.images=new Array();
this.hidden=0;
this.animation=new Animation(this);
//this.rekord=new Rekord(this);
this.history=new History(this);
this.naturalSave=0;
this.squareSave=0;
this.visibleDivs=",";
this.incomplete=0;
this.paintedonce=0;
this.applyDominantColor=0;
this.applyVignetteColor=0;
this.blend=0;
this.mirrorChildren=0;
this.randomChildren=0;
this.DELETED=0;
eval(this.cfigtxt);
//--- create the container div ----
this.div=_newObj("DIV");
_addObj(this.frame,this.div);
this.div.className="scrdiv";
//--- find the current image --
if(!this.src)this.src=this.images[this.imgix];
//--- create the boxes ----
var i=0;
for(var y=0;y<this.yyy;y++){
 	for(var x=0;x<this.xxx;x++){
  		var xy=xyKey(x,y);
  		div=_newObj("DIV");
  		_addObj(this.div,div);
  		div.className="boxdiv";
  		this.boxes[xy]=new Box(xy,this,div,this.maxx,this.maxy,x,y);
  		this.boxii[i]=this.boxes[xy];
		//if(!this.textbox)this.textbox=this.boxes[xy]; 
	 	//---- scramble images? ----  (don't do it here! this ties it to the position. Choose the image DYNAMICALLY (in p.drawImage)
  		i++;
}	}	
//--- set blend etc. ---	
this.setBlend();
}

	
//------------- findStartImage ---------
Screen.prototype.findStartImage=function(src){
// startImageIX used for scrambling and multiple images
var img;
var a=src.split("/");
var src=a[a.length-1];
this.startImageIX=0; 
for(var i=0;i<gPic.imgobjects.length;i++){
	if(_in(gPic.imgobjects[i].src,src)){ img=gPic.imgobjects[i]; this.startImageIX=i; }   
}
return img;
}



//------------------- saveDoScreenSwitch --------------
Picture.prototype.saveDoScreenSwitch=function(oldix,newix){
if(gPic.unDoing)return;
if(oldix==newix)return;
if(!this.doneStuff)this.doneStuff="";
this.doneStuff+="gToolbar.changeScreen("+oldix+");gToolbar.changeScreen("+scr.scrix+");;";
this.doneIX++;
}



//------------------- viewSaveDoneStuff --------------	
/*
Picture.prototype.viewSaveDoneStuff=function(v){
if(!v)return;
if(gPic.unDoing)return;
gPic.unDoing=0;
msg("VSAVE "+this.doneIX);
//console.trace();
if(!this.doneStuff)this.doneStuff="";
.....?
}
*/

//------------------- saveDoneStuff --------------	
Picture.prototype.saveDoneStuff=function(v){	//$$$$$
var scr=gPic.screen;
//msg("saveDoneStuff");
//msg(gPic.unDoing+","+scr.undoing);
if(gPic.unDoing || scr.undoing)return;
gPic.unDoing=0;
msg("SAVE "+this.doneIX);
//console.trace();
if(!this.doneStuff)this.doneStuff="";
var newConfig	=this.grabConfig(scr);
var newImage	=(scr.gImg)?scr.gImg.src : scr.src;
newConfig		=newConfig.substr(0,newConfig.indexOf(";s.imgix=")+1);
if(!this.doneStuff){
	scr.oldConfig =newConfig;
	scr.oldImage  =newImage;
	this.doneStuff=scr.oldConfig+";";
	this.doneIX=1;
	if(!v){
		gToolbar.syncDoneStuff();
		return;
	}
}
if(v){
	this.doneStuff+=v+";";
	//if(!_in(v,"view") && !_in(v,"View"))scr.oldConfig=newConfig;  //doesn't matter if we don't do this
	scr.oldConfig=newConfig;
	this.doneIX++;
	gToolbar.syncDoneStuff();
	return;
}
if(scr.oldImage && scr.oldImage!=newImage){
	var tmp="s.src='"+scr.oldImage+"';s.src='"+newImage+"';;";
	this.doneStuff+=tmp;
	this.doneIX++;
	scr.oldImage=newImage;
}
if(scr.oldConfig==newConfig && !v)return;
if(scr.oldConfig){
	var cmds=this.doneStuff.split(";;");
	var undoChgs=this.conciseConfig(null,newConfig,scr.oldConfig);
	var redoChgs=this.conciseConfig(null,scr.oldConfig,newConfig);
	if(this.doneIX > cmds.length-2){
		if(v){
			this.doneStuff+=v+";";
		}else{
			this.doneStuff+=undoChgs+redoChgs+";";
			//msg("added "+undoChgs+redoChgs+";");
		}
	}else{
		//--- we have done some 'undos' ---
		var newStuff="";
		if(this.doneIX<1)this.doneIX=1;
		for(i=1;i<cmds.length-1;i++){
			if(i==this.doneIX){
				if(v){
					cmds[i]=v;
				}else{
					cmds[i]=undoChgs+redoChgs;
				}
			}else{
				cmds[i]+=";";
			}
			newStuff+=cmds[i]+";";
		}
		this.doneStuff=cmds[0]+";;"+newStuff;
	}
	this.doneIX++;
	//msg("IX="+this.doneIX);
}
scr.oldConfig=newConfig;
gToolbar.syncDoneStuff();
//msg("AFTER="+this.doneStuff);
}



Picture.prototype.unDone=function(chg){
var doneAry=this.doneStuff.split(";;");
var s=this.screen;
gPic.unDoing=1;
if(chg<0 && this.doneIX < 2)return;
if(chg>0 && this.doneIX > doneAry.length-2)return;
//msg("src="+s.gImg.src+" == "+s.src);
if(s.gImg.src!=s.src){  //sometimes does not allow undon of img change????
	s.gImg.src=s.src;
}
//msg("IX="+this.doneIX+" LGTH="+doneAry.length);
if(chg<0){
	if(this.doneIX > 1){
		this.doneIX--;
		var cmds=doneAry[this.doneIX]+";";
		if(this.doneIX==0){
			eval(cmds+";");
		}else{
			var cmdsAry=cmds.split(";");
			cmds="";
			for(i=0;i<cmdsAry.length;i++)cmds=cmdsAry[i]+";"+cmds;	//reverse the cmds
			if(_in(cmds,"gPic.view")){
				var tmp=cmds.split(";");
				msg("cmd="+tmp[tmp.length-2]);
				eval(tmp[tmp.length-2]);
			}else{
				eval(cmds);
			}
		}
		if(_in(cmds,"s.src=")){
			s.addImg(this.extractDoneImg(cmds));
		}else{
			var done=this.checkCmds(s,cmds);
			if(!done)s.repaint();
		}
	}
}else{
	if(this.doneIX < doneAry.length-1){
		var cmds=doneAry[this.doneIX]+";";
		//msg("DOING:"+cmds);
		if(_in(cmds,"gPic.view")){
			var tmp=cmds.split(";");
			eval(tmp[tmp.length-2]);
		}else{
			eval(cmds);
		}
		if(_in(cmds,"s.src=")){
			s.addImg(this.extractDoneImg(cmds));
		}else{
			var done=this.checkCmds(s,cmds);
			if(!done)s.repaint();
		}
		this.doneIX++;
	}
}
if(_in(cmds,"saveDoScreenSwitch"))return;
if(_in(cmds,"gPic.view")){
	gToolbar.viewSyncControls();
	return;
}
var oldConfig=this.grabConfig(s);
s.oldConfig=oldConfig.substr(0,oldConfig.indexOf(";s.imgix=")+1);
gToolbar.syncControls();
gPic.unDoing=0;
//msg("IX="+this.doneIX);
}


//---- check to see if the change is handled outside of.repaint() -----
Picture.prototype.checkCmds=function(s,cmds){
var done=0;
if(_in(cmds,"gPic.view"))	{ return 1; }	// viewZoom,viewBlend,viewSlide,etc.
if(_in(cmds,".blend"))		{ done=1; s.setBlend(); }
if(_in(cmds,"Move="))		{ done=1; gPic.placeScreen(s); }
if(_in(cmds,"Slide="))		{ done=1; s.slide(); }
if(_in(cmds,"Size="))		{ done=1; gPic.repaintSizes(s.scrix); }
if(_in(cmds,"Tiling"))		{ done=1; gPic.tileScreen(gPic.scrix,s.Tiling); }	// disabled... doesn't always play nice
return done;
}


Picture.prototype.extractDoneImg=function(cmds){
var img=cmds.replace(".src=","");
img=img.replace("';","");
img=img.substr(img.indexOf("s.src='")+7);
img=img.substr(0,img.indexOf("';"));
return img;
}


//---------------------- REPAINT -----------------------------------------------
Screen.prototype.repaint=function(nokids){
	
//msg("------------");
//msg("Repaint "+this.scrix);

this.dontpaint=1;
this.setShadows();
this.setCorners();
this.setClipRadius();
this.setGradient();
this.setOpacity1(this.opacity1);
this.viewOpacity2(this.opacity2);
this.slide(this.xSlide,this.ySlide);  	
this.calcSpecs();
//msg("REPAINT f="+this.cssfilters);  //viewCSSFilters
this.div.style.webkitFilter   =this.div.style.filter   =this.cssfilters;
this.frame.style.webkitFilter =this.frame.style.filter =this.cssfilters;
if(this.hideScr){this.hideScr.frame.style.display="none";this.hideScr=null;}
/*
if(!nokids){
  //--- locked screens ---
  for(var j=0;j<this.lockedScreens.length;j++){
 	var lsc=this.lockedScreens[j];
 	this.picture.copyScreen(this,lsc,"position");
 	this.picture.copyScreen(this,lsc,"perspective");
 	lsc.repaint(0);
  }
  //--- child screens ---
  for(var j=0;j<this.childScreens.length;j++){	//????? do we need to repaint children?
 	var csc=this.childScreens[j];
 	csc.repaint(0);
  }
}
*/
this.setPerspective(this,this.perspectiveFrm,"window");
this.setPerspective(this,this.perspectiveImg,"image");
if(!this.hideImage)this.setPerspective(this,this.perspectivePnl,"panels");	
this.paint();
this.dontpaint=0;
gToolbar.syncHandles();
}

//----------------------------- paint ------------------------------------------
Screen.prototype.paint=function(){
//msg("Paint "+this.scrix);
//console.trace();
//msg("2 maxvx="+this.maxvx+", maxx="+this.maxx+", vx="+this.vx);
if(!this.paintedonce)this.paintedonce=1;
//else{  if(this.xZoom<50.01 && this.yZoom<50.01)this.loadVisibleDivs();  }
if(!gPic.MouseDown)gPic.saveDoneStuff();
var frm=this.frame.style;
var div=this.div.style;
//msg("frmL="+this.frmL+", frmT="+this.frmT+", fmrW="+this.frmW+", frmH="+this.frmH);
//msg("divL="+this.divL+", divT="+this.divT+", divW="+this.divW+", divH="+this.divH);
//msg("paint() ix="+this.scrix+", media="+this.media+", frmL="+this.frmL+", xMove="+this.xMove);
frm.left=this.frmL;
frm.top=this.frmT;
frm.width=this.frmW;
frm.height=this.frmH;
div.left=this.divL;
div.top=this.divT;
div.width=this.divW;
div.height=this.divH;
if(this.lockedByScreen && this.hideImage){
  //NB: this code locks the locked screen to the INNER (zoomed) div (nice feature! keep it!)
  var ownr=this.lockedByScreen;
  if(ownr.divL>0){frm.left=this.frmL+ownr.divL; frm.width=ownr.divW+5; }
  if(ownr.divT>0){frm.top=this.frmT+ownr.divT;  frm.height=ownr.divH+5;}
}
if(this.hideImage)return;	
for(var y=0;y<this.yyy;y++){
  for(var x=0;x<this.xxx;x++){
    this.boxes[xyKey(x,y)].paint();
} }

if(this.MaskOn || this.ColorRgbOn || this.SwapCsOn || this.SwapPixelsOn || this.PosterizeOn){
	this.applyPixelMasks();
}

//--- These are slower - can't figure out how to do them in applyPixelMasks ---
//--- (We put a timer on so they don't get applied until mouse events stop)
if(this.OilPaintOn){
	if(this.OilPaintID)clearTimeout(this.OilPaintID);
	this.OilPaintID = setTimeout("gPic.screens["+this.scrix+"].applyOilPaint()",50);
}
if(this.FultersOn){
	if(this.fulterID)clearTimeout(this.fulterID);
	this.fulterID = setTimeout("gPic.screens["+this.scrix+"].applyFulters()",75);
}
if(this.FillColorOn){
	if(this.FillColorID)clearTimeout(this.FillColorID);
	this.FillColorID = setTimeout("gPic.screens["+this.scrix+"].applyFillColor()",100);
}

}


//======================== PIXELS ================================

//--------- applyPixelMasks ------------
Screen.prototype.applyPixelMasks=function(){
//Note: each sawap in the list = "colorfrom,colorto,opacity,tolerance"
var scr=this,c1,c2;
if(scr.SwapCsOn){
	scr.SwapCsAry=new Array();
	if(!scr.SwapCsList)scr.SwapCsList="#ff3333,#ff9933,255,100";
	var tmp=scr.SwapCsList.split("|");
	for(var i=0;i<tmp.length;i++){
		scr.SwapCsAry[i]	= tmp[i].split(",");
		var c1				= scr.SwapCsAry[i][0];
		var c2				= scr.SwapCsAry[i][1];
		scr.SwapCsAry[i][2]*=1;
		scr.SwapCsAry[i][3]*=1;
		scr.SwapCsAry[i][4] = HexToR(c1)*1;
		scr.SwapCsAry[i][5] = HexToG(c1)*1;
		scr.SwapCsAry[i][6] = HexToB(c1)*1;
		scr.SwapCsAry[i][7] = HexToR(c2)*1;
		scr.SwapCsAry[i][8] = HexToG(c2)*1;
		scr.SwapCsAry[i][9] = HexToB(c2)*1;
	}
}
for(var y=0;y<this.yyy;y++){
  for(var x=0;x<this.xxx;x++){
    this.boxes[xyKey(x,y)].applyPixelMask();
} }
}



//-------------------------- applyPixelMask -----------------------------------------
Box.prototype.applyPixelMask=function(ctx,mtype,mstart,blur,mdirection){
var scr =this.screen;
if(!ctx)ctx=this.context;
if(!mtype)mtype=scr.maskType;
if(!mstart)mstart=scr.maskStart;
if(!blur)blur=scr.maskBlur;
if(!mdirection)mdirection=scr.maskDirection;
//msg("id="+this.id+", xxx="+this.screen.xxx+", mtype="+mtype+", mstart="+mstart+", blur="+blur+", dir="+mdirection);
//if(mtype==0)return;
var flip=axis=0;
if(scr.MaskOn){
	switch(mtype){
	 case 1: var type="linear"; break;
	 case 2: var type="radial"; break;
	 case 3: var type="edge";   break;
	}
	//msg("START="+mstart+", BLUR="+blur);
	//====== radial or edge ========
	if(type!="linear"){
		mstart = (mstart-50)*6;
		var maskend = mstart+(blur*6);
		//msg("OLD: "+mstart+" to "+maskend);
		if(mdirection==1)flip=1;
	}else{
		//====== linear =======
		if(mdirection==1 || mdirection==3)flip=1;  
		if(mdirection==2 || mdirection==3)axis=1;
		var tmp=""+flip+""+axis;
		switch(tmp){
			case "00": dir="LeftToRight"; break;
			case "10": dir="TopToBottom"; break;
			case "01": dir="RightToLeft"; break;
			case "11": dir="BottomToTop"; break;
		}
		var maskend=mstart+blur;
		if(mdirection>1){
			var tmp=maskend-mstart;
			maskend=100-mstart;
			mstart=maskend-tmp;
		}
		if( (scr.xxx==1 && scr.yyy==1) ||
			(scr.xxx==1 && (mdirection==0 || mdirection==2)) ||
			(scr.yyy==1 && (mdirection==1 || mdirection==3)) ){
			// do nothing - single box 
		}else{
			//--- diff mask for each box ---
			var xxx		=scr.xxx;
			var yyy		=scr.yyy;
			var n,mxy,xysize,xystart,xyend;
			if(dir=="LeftToRight" || dir=="RightToLeft"){
				n	=xxx;
				mxy	=parseInt( (this.id).substring(0,2) );
			}else{
				n	=yyy;
				mxy	=parseInt( (this.id).substring(2,4) );
			}
			xysize	=Math.round((100/n)*100)/100;
			xystart	=(mxy*xysize);
			xyend	=(mxy*xysize)+xysize;
			//msg("PNL: "+xystart+" to "+xyend);
			if(mstart  > xyend && mdirection<2){ 
				return;	
			}	
			if(maskend < xystart && mdirection<2){		
				mstart = -100;  
				maskend	=-10; 
			}else{
				if(maskend < xystart && mdirection>1){
					return;	
				}else{
					if(mstart < xystart)	mstart = (((xystart - mstart)/xysize)*100)*-1;
					else 					mstart = ((mstart  - (mxy * xysize))/xysize)*100;
					if(maskend > xyend)		maskend= (((maskend - xystart)/xysize)*100);
					else 					maskend= ((maskend - (mxy * xysize))/xysize)*100; 
				}
			}
		}
	}
}else{
	type="linear";	//type doesn't matter is MaskOn is off
}
mstart	=Math.round(mstart);
maskend	=Math.round(maskend);
mstart	=mstart/100;
maskend	=maskend/100;
var dir=[mstart,maskend,flip,axis];
this.ctxMask(ctx,type,dir);
}



Picture.prototype.getSwapPixelScr=function(scr){
for(var i=0;i<gPic.screens.length;i++){
	var scr2=gPic.screens[i];
	if(scr2.zindex < scr.zindex && scr2.media=="image"){
		//msg("scr2 < ="+scr2.scrix)
		return scr2;
	}
}
for(var i=0;i<gPic.screens.length;i++){ 	//try images on top...
	var scr2=gPic.screens[i];
	if(scr2.zindex > scr.zindex && scr2.media=="image"){
		//msg("scr2 > ="+scr2.scrix)
		return scr2;
	}
}
//msg("not found")
return scr;
}


//---------------- ctxMask ----------------
Box.prototype.ctxMask=function(ctx,type,dir){
var scr = this.screen;
var red   = scr.maskRed;
var green = scr.maskGreen;
var blue  = scr.maskBlue;
var solid = scr.maskSolid;
var solidAlpha = scr.maskSolidAlpha;
var offset= 10;
var c = ctx.canvas;
this.boxdata = ctx.getImageData(0, 0, c.width, c.height);
var eq = {};
var w=c.width;
var h=c.height;
var scr2;
if(solid){
	red		= ((red+255)/2);
	green	= ((green+255)/2);
	blue	= ((blue+255)/2);
	//msg("rgb="+red+","+green+","+blue);
}
if(scr.SwapPixelsOn){
	scr2=gPic.getSwapPixelScr(scr);
	if(scr2==scr){
		scr.SwapPixelsOn=0;
		msg("SwapPixelsOn but no parent!");
	}else{
		var ctx2=scr2.boxes[xyKey(this.x,this.y)].context;
		scr2.tmpData = ctx2.getImageData(0, 0, ctx2.canvas.width, ctx2.canvas.height);
	}
}
switch(type){
case "linear":
    eq = {
		start: dir[0]* (!(dir[2]) ? w : h),
		end: dir[1] * (!(dir[2]) ? w : h),
		dir: dir[2] ? "y" : "x"
         }
	var alpha,xred;
    for(var y = 0; y < h; y++){
        for(var x = 0; x < w; x++){
			xred	= (y * w + x) * 4;
			//--- mask ---
			if(scr.MaskOn){
	            var v = 0;
	            if(eq.dir == "x"){ v = x; }else if(eq.dir == "y"){ v = y; }
				if(dir[3]) 	alpha 	= 255 - ((v - eq.end) / (eq.start - eq.end)) * 255;
				else 		alpha 	= ((v - eq.end) / (eq.start - eq.end)) * 255;
				if(alpha > 0 && alpha < 256){
					var rgba = this.adjustMaskColors(scr,this.boxdata.data[xred]*1,this.boxdata.data[xred+1]*1,this.boxdata.data[xred+2]*1,red,green,blue,alpha);
					this.boxdata.data[xred]		=	rgba[0];
					this.boxdata.data[xred+1]	=	rgba[1];
					this.boxdata.data[xred+2]	=	rgba[2];
					if(rgba[3]!=-1) this.boxdata.data[xred+3]	=	rgba[3];
				}else{
	                this.boxdata.data[xred + 3] = alpha;
				}
			}
			if(this.boxdata.data[xred+3]>0){
				this.applyPixels(scr,xred,this.boxdata.data[xred]*1,this.boxdata.data[xred+1],this.boxdata.data[xred+2],scr2);
			}
        }
    }
    break;
case "radial":
    var scale = (w < h) ? w : h;
    var rs = (1 - dir[0]) * scale / 2;
    var re = (1 - dir[1]) * scale / 2;
    var cx = w /2;
    var cy = h / 2;
	var alpha,xred;
    for(var y = 0; y < h; y++){
        for(var x = 0; x < w; x++){
			xred	= (y * w + x) * 4;
			//--- mask ---
			if(scr.MaskOn){
	            var dx = x * h / w;
	            var rSqr = (dx - cy) * (dx - cy) + (y - cy) * (y - cy);
				if(dir[2]) 	alpha 	= 255 - (Math.sqrt(rSqr) - re) / (rs - re) * 255;
				else 		alpha 	= (Math.sqrt(rSqr) - re) / (rs - re) * 255;
				if(alpha > 0 && alpha < 256){
					var rgba = this.adjustMaskColors(scr,this.boxdata.data[xred]*1,this.boxdata.data[xred+1]*1,this.boxdata.data[xred+2]*1,red,green,blue,alpha);
					this.boxdata.data[xred]	=	rgba[0];
					this.boxdata.data[xred+1]	=	rgba[1];
					this.boxdata.data[xred+2]	=	rgba[2];
					if(rgba[3]!=-1) this.boxdata.data[xred+3]	=	rgba[3];
			    }else{
			        this.boxdata.data[xred + 3] = alpha;
			    }
			}
			if(this.boxdata.data[xred+3]>0){
				this.applyPixels(scr,xred,this.boxdata.data[xred]*1,this.boxdata.data[xred+1],this.boxdata.data[xred+2],scr2);
			}
		}
    }
    break;
case "edge":
    var scale = (w < h) ? w : h;
    var xs = (1 - dir[0]) * scale / 2;
    var xe = (1 - dir[1]) * scale / 2;
	var alpha,xred;
    for(var y = 0; y < h; y++){
        for(var x = 0; x < w; x++){
			xred	= (y * w + x) * 4;
			//--- mask ---
			if(scr.MaskOn){
	            var dy = Math.abs(y - h / 2);
	            var dx = Math.abs(x - w / 2) * h / w;
				if(dir[2]) 	alpha 	= 255 - (Math.max(dy, dx) - xe) / (xs - xe) * 255;
				else 		alpha 	= (Math.max(dy, dx) - xe) / (xs - xe) * 255;
				if(alpha > 0 && alpha < 256){
					var rgba = this.adjustMaskColors(scr,this.boxdata.data[xred]*1,this.boxdata.data[xred+1],this.boxdata.data[xred+2],red,green,blue,alpha);
					this.boxdata.data[xred]	=	rgba[0];
					this.boxdata.data[xred+1]	=	rgba[1];
					this.boxdata.data[xred+2]	=	rgba[2];
					if(rgba[3]!=-1) this.boxdata.data[xred+3]	=	rgba[3];
	            }else{
	                this.boxdata.data[xred + 3] = alpha;
	            }
			}
			if(this.boxdata.data[xred+3]>0){
				this.applyPixels(scr,xred,this.boxdata.data[xred]*1,this.boxdata.data[xred+1],this.boxdata.data[xred+2],scr2);
			}
        }
    }
    break;
}
ctx.putImageData(this.boxdata, 0, 0);
}




//---------------- applyPixels ----------------
//--- called for EVERY pixel! ---
//--- Note: The order in which they get applied here would change the results if more than one selected ---
Box.prototype.applyPixels=function(scr,xred,r,g,b,scr2){
//----- Posterize ---
if(scr.PosterizeOn){
	var rgba = this.posterizeEffect(r,g,b,scr.PosterizeRadius,scr.PosterizeIntensity);
	r=this.boxdata.data[xred]=rgba[0]; g=this.boxdata.data[xred+1]=rgba[1]; b=this.boxdata.data[xred+2]=rgba[2];	
}
//----- colorRgb ----
if(scr.ColorRgbOn){
	var rgba = this.adjustColorRgb(scr,r,g,b);
	r=this.boxdata.data[xred]=rgba[0]; g=this.boxdata.data[xred+1]=rgba[1]; b=this.boxdata.data[xred+2]=rgba[2];					
}
//----- swapPixels ----
if(scr.SwapPixelsOn){
	var rgba = this.swapPixels(xred,r,g,b,scr2);
	r=this.boxdata.data[xred]=rgba[0]; g=this.boxdata.data[xred+1]=rgba[1]; b=this.boxdata.data[xred+2]=rgba[2];					
}
//----- swapColors ----
if(scr.SwapCsOn){
	var rgba = this.swapColors(xred,r,g,b,this.boxdata.data[xred+3]);
	r=this.boxdata.data[xred]=rgba[0]; g=this.boxdata.data[xred+1]=rgba[1]; b=this.boxdata.data[xred+2]=rgba[2];					
	this.boxdata.data[xred+3]=rgba[3];
}
}


//---------------- adjustMaskColors ----------------
Box.prototype.adjustMaskColors=function(scr,oldred,oldgreen,oldblue,red,green,blue,alpha){
if(alpha > 0 && alpha < 256){
	solidAlpha=scr.maskSolidAlpha*1;
	if(scr.maskSolid){
		if(solidAlpha){
        	red		=(oldred    * (solidAlpha/255)) + red;
        	green	=(oldgreen  * (solidAlpha/255)) + green;
        	blue	=(oldblue   * (solidAlpha/255)) + blue;
			alpha	= -1;
		}else{
			alpha	= -1;
		}
	}else{
		var factor = (255-alpha)/255;
		red		= oldred + (red * factor);
		green	= oldgreen + (green * factor);
		blue	= oldblue + (blue * factor);
	}
}
return new Array(Math.round(red),Math.round(green),Math.round(blue),Math.round(alpha));
}


//---------------- posterizeEffect ----------------
Box.prototype.posterizeEffect=function(r,g,b,radius,intensity) {
// Posterize the colors by rounding them to the nearest multiple of 32 (default)
r = Math.round(r / radius) * intensity;
g = Math.round(g / radius) * intensity;
b = Math.round(b / radius) * intensity;
return new Array(Math.round(r),Math.round(g),Math.round(b));
}



//---------------- adjustColorRgb ----------------
Box.prototype.adjustColorRgb=function(scr,oldred,oldgreen,oldblue){
var red		=scr.colorRgbRed 	+ oldred;
var green	=scr.colorRgbGreen	+ oldgreen;
var blue	=scr.colorRgbBlue	+ oldblue;
return new Array(Math.round(red),Math.round(green),Math.round(blue));
}	

//---------------- swapPixels ----------------
Box.prototype.swapPixels=function(xred,red1,green1,blue1,scr2){
var scr=this.screen;
var red2	=scr2.tmpData.data[xred];
var green2	=scr2.tmpData.data[xred+1];
var blue2	=scr2.tmpData.data[xred+2];
if(_in(scr.swapPixelsRgb,"R"))red1		=red2;
if(_in(scr.swapPixelsRgb,"G"))green1	=green2;
if(_in(scr.swapPixelsRgb,"B"))blue1		=blue2;
return new Array(red1,green1,blue1);
}

//---------------- swapColors ----------------
Box.prototype.swapColors=function(xred,red1,green1,blue1,alpha1){
var scr=this.screen;
for(var i=0;i<scr.SwapCsAry.length;i++){
	var tmp=scr.SwapCsAry[i];
	if(
		(red1   >= (tmp[4]-tmp[3]) && 
	     red1   <= (tmp[4]+tmp[3])) 
		&&
		(green1 >= (tmp[5]-tmp[3]) && 
	     green1 <= (tmp[5]+tmp[3])) 
		&&
		(blue1  >= (tmp[6]-tmp[3]) && 
	     blue1  <= (tmp[6]+tmp[3])) 
	){
		red1	 = tmp[7];
		green1	 = tmp[8];
		blue1	 = tmp[9];
        if(tmp[2]<255 && alpha1>tmp[2]) alpha1 = tmp[2];
	}
}
return new Array(red1,green1,blue1,alpha1);
}


//=================== Screen Fulters ============================


//------------- applyFulters ------ ------
Screen.prototype.applyFulters=function(){
if(!this.FultersOn)return;
if(this.fultersList=="")this.fultersList="0,-1,0,-1,5,-1,0,-1,0|";
var fultersList	=this.fultersList.split("|");
for(var i=0;i<fultersList.length;i++){
	var f=fultersList[i];
	if(f){
		if(_in(f,",")){
			var ary=f.split(",");
			this.applyFulter(ary);
		}else{
			this.applyFulter(f); 
		}
	}
}}

//------------- applyFulter ------ ------
Screen.prototype.applyFulter=function(f){
Fulters.image	=this.gImg;
for(var y=0;y<this.yyy;y++){
	for(var x=0;x<this.xxx;x++){
		var box=this.boxes[xyKey(x,y)];
		Fulters.box			=box;
		Fulters.tmpCanvas	=box.canvas;
		Fulters.tmpCtx		=box.context;
		switch(f){
			case "sobel"	: 	Fulters.sobel(0);		break;
			case "sobelbw"	: 	Fulters.sobel(1);		break;
			default			:	Fulters.custom(f);		// f is an array
		}
	}
}}



//============================= OilPaint ============================
Screen.prototype.applyOilPaint=function(){
if(!this.OilPaintOn)return;
for(var y=0;y<this.yyy;y++){
	for(var x=0;x<this.xxx;x++){
		var box=this.boxes[xyKey(x,y)];
		this.OilPaintEffect(box.canvas, box.context, this.OilPaintRadius, this.OilPaintIntensity, box)
	}
}}


Screen.prototype.OilPaintEffect=function(canvas, ctx, radius, intensity, box) {
// ----- credit https://jsfiddle.net/user/loktar/fiddles/ ---
    var width 	= canvas.width,
        height 	= canvas.height,
        imgData = ctx.getImageData(0, 0, width, height),
        pixData = imgData.data,
        pixelIntensityCount = [];
    var destImageData = ctx.createImageData(width, height),
        destPixData = destImageData.data,
        intensityLUT = [],
        rgbLUT = [];
    for (var y = 0; y < height; y++) {
        intensityLUT[y] = [];
        rgbLUT[y] = [];
        for (var x = 0; x < width; x++) {
            var idx = (y * width + x) * 4;
			//var alpha = pixData[idx + 3];
			//if(alpha>0){
	            var r = pixData[idx],
	                g = pixData[idx + 1],
	                b = pixData[idx + 2],
	                avg = (r + g + b) / 3;
	
	            intensityLUT[y][x] = Math.round((avg * intensity) / 255);
	            rgbLUT[y][x] = {
	                r: r,
	                g: g,
	                b: b
	            };
			//}
        }
    }
	//msg("radius="+radius);
	for (y = 0; y < height; y++) {
        for (x = 0; x < width; x++) {
            pixelIntensityCount = [];
            // Find intensities of nearest pixels within radius.
            for (var yy = -radius; yy <= radius; yy++) {
                for (var xx = -radius; xx <= radius; xx++) {
                    if (y + yy > 0 && y + yy < height && x + xx > 0 && x + xx < width) {
                        var intensityVal = intensityLUT[y + yy][x + xx];
                        if (!pixelIntensityCount[intensityVal]) {
                            pixelIntensityCount[intensityVal] = {
                                val: 1,
                                r: rgbLUT[y + yy][x + xx].r,
                                g: rgbLUT[y + yy][x + xx].g,
                                b: rgbLUT[y + yy][x + xx].b
                            }
                        } else {
                            pixelIntensityCount[intensityVal].val++;
                            pixelIntensityCount[intensityVal].r += rgbLUT[y + yy][x + xx].r;
                            pixelIntensityCount[intensityVal].g += rgbLUT[y + yy][x + xx].g;
                            pixelIntensityCount[intensityVal].b += rgbLUT[y + yy][x + xx].b;
                        }
                    }
                }
            }
            pixelIntensityCount.sort(function (a, b) {
                return b.val - a.val;
            });
            var curMax = pixelIntensityCount[0].val;
            var dIdx = (y * width + x) * 4;
            destPixData[dIdx] = ~~ (pixelIntensityCount[0].r / curMax);
            destPixData[dIdx + 1] = ~~ (pixelIntensityCount[0].g / curMax);
            destPixData[dIdx + 2] = ~~ (pixelIntensityCount[0].b / curMax);
            //destPixData[dIdx + 3] = 255;
            destPixData[dIdx + 3] = pixData[dIdx + 3];
        }
    }
	box.boxdata=destImageData;
    ctx.putImageData(destImageData, 0, 0);
}



Screen.prototype.applyFillColor=function(){
// r1 = existing color at x/y
// r2 = fill color
if(!this.FillColorOn)return;
//--- have to do this! (eg. only tolerance changes) ---
if(this.lastFillColor==this.FillColorClr){
	this.FillColorOn=0;
	var tmp=this.undoing;
	this.undoing=1;
	this.paint();
	this.undoing=tmp;
	this.FillColorOn=1;
}else this.lastFillColor=this.FillColorClr;
//-----------------------
//gToolbar.hiliteFCbtn(0);
var x	  =this.FillColorX;
var y	  =this.FillColorY;
var c2    =this.FillColorClr;
var alpha =this.FillColorAlpha;
var tol   =this.FillColorTolerance;
if(_in(c2,"rgb"))c2=RGBA2HEX(c2);
var box		  = this.boxes[xyKey(0,0)];
var canvas    = box.canvas;
var ctx       = canvas.getContext("2d");
box.boxdata   = ctx.getImageData(0, 0, canvas.width, canvas.height);
var imageData = box.boxdata;
var pixelStack= [[x,y]];
var pos = (y * canvas.width + x) * 4;
var r1  = imageData.data[pos];
var g1  = imageData.data[pos+1];
var b1  = imageData.data[pos+2];
var alpha1  = imageData.data[pos+3];
var r2=HexToR(c2)*1;
var g2=HexToG(c2)*1;
var b2=HexToB(c2)*1;
var fakeAlpha=(alpha1==(alpha-1))? alpha-2 : alpha-1;  // a way to tell if the pixel is already done
//msg("alpha="+alpha)
//msg("alpha1="+alpha1)
//msg("fakeAlpha="+fakeAlpha)
while (pixelStack.length) {
    var newPos, x, y, pixelPos, reachLeft, reachRight;
    newPos = pixelStack.pop();
    x = newPos[0];
    y = newPos[1];
    pixelPos = (y * canvas.width + x) * 4;
    while (y-- >= 0 && 
			matchColor(tol,imageData.data[pixelPos],
			 			   imageData.data[pixelPos+1],
						   imageData.data[pixelPos+2],
						   imageData.data[pixelPos+3],
						   r1,g1,b1,fakeAlpha)) {
     	pixelPos -= canvas.width * 4;
    }
    pixelPos += canvas.width * 4;
    ++y;
    reachLeft 	= false;
    reachRight 	= false;
	//msg("start y="+y+",pos="+pixelPos);							 
    while (y++ < canvas.height-1 && 
			matchColor(tol,imageData.data[pixelPos],
			 			   imageData.data[pixelPos+1],
						   imageData.data[pixelPos+2],
						   imageData.data[pixelPos+3],
						   r1,g1,b1,fakeAlpha)) {
		//msg("y="+y+",pos="+pixelPos);							 
		imageData.data[pixelPos]   = r2;
		imageData.data[pixelPos+1] = g2;
		imageData.data[pixelPos+2] = b2;
		imageData.data[pixelPos+3] = fakeAlpha;
		if (x > 0) {
			pos = pixelPos-4;
			r = imageData.data[pos];
			g = imageData.data[pos+1];
			b = imageData.data[pos+2];
//			if(r==r1 && g==g1 && b==b1){
			if(matchColor(tol,r,g,b,alpha,r1,g1,b1,fakeAlpha)){
			  if (!reachLeft) {
			    pixelStack.push([x - 1, y]);
			    reachLeft = true;
			  }
			} else if (reachLeft) {
			  reachLeft = false;
			}
		}
		if (x < canvas.width-1) {
			pos = pixelPos+4;
			r = imageData.data[pos];
			g = imageData.data[pos+1];
			b = imageData.data[pos+2];
			//if(r==r1 && g==g1 && b==b1){
			if(matchColor(tol,r,g,b,alpha,r1,g1,b1,fakeAlpha)){
			  if (!reachRight) {
			    pixelStack.push([x + 1, y]);
			    reachRight = true;
			  }
			} else if (reachRight) {
			  reachRight = false;
			}
		}
		pixelPos += canvas.width * 4;
    }
}
ctx.putImageData(imageData, 0, 0);
}


//======================== END PIXELS =======================



//-------------------------- .calcSpecs --------------------------------------------
Screen.prototype.calcSpecs=function(boxy){
//This sizes the divs but does not paint them
var ww,hh,w,h,l,t;
var box,div;
var x,y,xy;
//---- get default dimensions ----
this.zoom();
this.maxvx=this.maxzx*gMAXVX;
this.maxvy=this.maxzy*gMAXVY;
l=0;
t=0;
ww=this.divW;
hh=this.divH;
//--- screen split ---
this.zxfs=new Array();
this.zyfs=new Array();
if(this.xWarp!=50){
	this.zxfs=this.calcPctsArray(this.xxx,this.xWarp,this.fullWarp,this.offsetWarp);
}
if(this.yWarp!=50){
	this.zyfs=this.calcPctsArray(this.yyy,this.yWarp,this.fullWarp,this.offsetWarp);
}	
//---- size the divs ----
this.wtotal=0;
this.htotal=0;
var xskew=gPic.getPerSkewX(this.perspectivePnl,this);
var yskew=gPic.getPerSkewY(this.perspectivePnl,this);
for(y=0;y<this.yyy;y++){
	 h=(hh/this.yyy);
	 if(this.zyfs[0])h=h*(this.zyfs[y]/100);   //Warp
	 t=this.htotal;
	 h=Math.round(h);
	 this.htotal+=h;
	 if(y==this.yyy-1 && this.htotal<hh)h+=hh-this.htotal;
	 if(yskew!=0)h+=0.5;	// fine lines problem 
	 else h+=0.1;
	 this.wtotal=0;
	 for(x=0;x<this.xxx;x++){
		  xy=xyKey(x,y);
		  box=this.boxes[xy];
		  div=box.div;
		  w=(ww/this.xxx);							// width if no warp
		  if(this.zxfs[0])w=w*(this.zxfs[x]/100);   // width with warp applied
		  l=this.wtotal;
		  w=Math.round(w);
		  this.wtotal+=w;
		  if(x==this.xxx-1 && this.wtotal<ww)w+=ww-this.wtotal;	// if it's the last panel use the rest of the total width
		  // +1 to stop the fine lines
	 	  if(xskew!=0)w+=0.5;	 
		  else w+=0.1;
		  //msg("w="+w+", xskew="+xskew);
		  box.divL=l;
		  box.divT=t;
		  box.divW=w;
		  box.divH=h;
		  box.maxzx=this.maxzx;
		  box.maxzy=this.maxzy;
		  box.maxvx=this.maxvx;
		  box.maxvy=this.maxvy;
		  box.vx=(this.vx+(x*this.maxx)) % this.maxvx;
		  box.vy=(this.vy+(y*this.maxy)) % this.maxvy;
		  box.zx=box.vx % this.maxzx;
		  box.zy=box.vy % this.maxzy;
		  box.xSplit=(x % 2)?100-this.xSplit:this.xSplit;
		  box.ySplit=(y % 2)?100-this.ySplit:this.ySplit;
		  box.xWarp=this.xWarp; 		box.yWarp=this.yWarp;
		  box.xImage=this.xImage; 		box.yImage=this.yImage;	
		  box.xFold=this.xFold;   		box.yFold =this.yFold;
		  box.xStretch=this.xStretch;   box.yStretch =this.yStretch;
		  box.xScale=this.xScale;   	box.yScale =this.yScale;
		  box.xCrop=this.xCrop;   		box.yCrop =this.yCrop;
		  box.lCrop=this.lCrop;   		box.tCrop =this.tCrop;
		  box.xFrack=this.xFrack; 		box.yFrack=this.yFrack;
		  box.frackL=this.frackL; 		box.frackT=this.frackT;
		  box.calcSpecs();
}	}

}



//---- Can be used anyplace calcPcts() is used!  respects fullwarp and offsetWarp! ----
Screen.prototype.calcPctsArray=function(xxx,warp,fullwarp,offsetwarp){
    var ary=new Array();
	if(xxx>1 && !fullwarp){    			//--- not fullWarp ---
		var xboxes=2;
  		ary=this.calcPcts(ary,warp,xboxes);  
  		var a=new Array();
		var j=0;
		if(offsetwarp && xxx>3){  		// not fullWarp and offset  (offset requires at least 4 screens)
			a[0]=a[xxx-1]=100;
	  		for(var i=1;i<(xxx-1);i++){
 				a[i]=ary[j]; 
				j++; 	
				if(j==xboxes)j=0;	
			}
		}else{
	  		for(var i=0;i<xxx;i++){  	// not fullWarp and no offset
   				a[i]=ary[j]; 
				j++; 	
				if(j==xboxes)j=0;
			}
  		}
  		ary=a;
	}else{   							//--- fullWarp ---
		if(offsetwarp && xxx>3){   		
			ary=this.calcPcts(ary,warp,xxx-2); // fullWarp but still offset
  			var a=new Array();
			var j=0;
			a[0]=a[xxx-1]=100;
	  		for(var i=1;i<(xxx-1);i++){
 				a[i]=ary[j]; 
				j++; 
			}
			ary=a;
		}else{
			ary=this.calcPcts(ary,warp,xxx);   	// just fullWarp
		}
	}
	return ary;
}



//==== FEDERICO =====
// Calculate the % sizes using a warp value
Screen.prototype.calcPcts=function(fs,warp,max){
	var xtotal=100*max;
	var vx,tmp;
	var newtotal=0;
	var pct = warp;
	if (warp > 50) 	pct = 100 - warp;
	pct = (100 - (pct*2)) / 100;	
	for(var i=0;i<max;i++){
	 	vx=(xtotal-newtotal)/(max-i);
	 	let fraction = (pct/(i+1));
        // vx = new single panel share of remaining value
	 	fs[i]=(vx+(fraction*(xtotal-(newtotal+vx))));     // assign a decreasing pct (pct/(i+1)) of new remaining total to current panel
	 	newtotal+=fs[i];
	}
	let fractions = fs.map((t) => t / xtotal);
	//--- reverse ----
	if(warp<50){
	 	var a=fs.slice();
	 	for(var i=fs.length-1;i>-1;i--)
	  		fs[i]=a[fs.length-(i+1)];
	}
	//--- normalize ----
	newtotal=0;
	for(var i=0;i<fs.length-1;i++){
	 	fs[i]=Math.round(fs[i]);
	 	newtotal+=fs[i];
	}
	fs[fs.length-1]=xtotal-newtotal;
	//console.log("fs="+fs);
	return fs;
}


//=================================== FEDERICO =======================================
Screen.prototype.applySkewsAndWarps=function(xskew, yskew, xwarp, ywarp){
	// if no skew and no warp then return?  //???????
	xskew = parseFloat(xskew);
	yskew = parseFloat(yskew);
	
	let boxCntX=this.xxx;
	let boxCntY=this.yyy;
	let totalSizeX=boxCntX*100;
	let totalSizeY=boxCntY*100;

	let xSizes 	=  new Array();
	let ySizes 	=  new Array();
	//xSizes		=  this.calcPcts(xSizes,xwarp,boxCntX);
	//ySizes		=  this.calcPcts(ySizes,ywarp,boxCntY);
	// Use this new function instead of calcPcts because we have already calculated the div sizes in calcSpecs
	xSizes=this.XcalcPctsFromDivs();
	ySizes=this.YcalcPctsFromDivs();

	let xPercentages = xSizes.map((x) => x / totalSizeX);
	let yPercentages = ySizes.map((y) => y / totalSizeY);

	let panelWidth = totalSizeX / boxCntX;
	let panelHeight = totalSizeY / boxCntY;
	
	let targetWidths = xSizes.map((x) => 2 * (x / 2 + panelHeight / 2 * Math.tan(toRadians(xskew))));
	let targetHeights = ySizes.map((y) => 2 * (y / 2 + panelWidth / 2 * Math.tan(toRadians(yskew))));

	// apply skews
	for(let x = 0; x < boxCntX; x++){
		for(let y = 0;y < boxCntY; y++){
    		let dv = this.boxes[xyKey(x,y)].div;   
			let newxskew = toDegrees(Math.atan((targetWidths[x] / 2 - xSizes[x] / 2) / (ySizes[y] / 2))); 
			let xtmp = newxskew; // xskew;
			let newyskew = toDegrees(Math.atan((targetHeights[y] / 2 - ySizes[y] / 2) / (xSizes[x] / 2))); 
			let ytmp = newyskew; //yskew;
			if(_isOdd(y) && !_isOdd(x)) { 
				xtmp = xtmp*-1; //ix=4  per3d=per3d*-1;?
			} else if (!_isOdd(y) && _isOdd(x)){ 
	    		ytmp = ytmp*-1; 
    		} else if (_isOdd(y) && _isOdd(x)) { 
	    		xtmp = xtmp*-1; ytmp=ytmp*-1; 
    		} 	//4 and 2   per3d=per3d*-1;?
			let str = "skew("+ xtmp + "deg," + ytmp + "deg)";
			this.boxes[xyKey(x,y)].setPerspective(str); 
		}	
	}
}


Screen.prototype.XcalcPctsFromDivs=function(){
var ax = new Array();
var tot=100*this.xxx;
for(var x = 0; x < this.xxx; x++){
	ax[x] = Math.round(((this.boxes[xyKey(x,0)].divW/this.divW)*100)*this.xxx); 
}return ax;}

Screen.prototype.YcalcPctsFromDivs=function(){
var ay = new Array();
var tot=100*this.yyy;
for(var y = 0; y < this.yyy; y++){
	ay[y] = Math.round(((this.boxes[xyKey(0,y)].divH/this.divH)*100)*this.yyy); 
}
return ay;
}

function toRadians(degs){
	return degs / 180 * Math.PI;
}

function toDegrees(rads){
	return rads / Math.PI * 180;
}


//-------------------------- reverseEngineerSpecs ------------------------------
//-- Calculate x/yMove from position and size of the screen's frame
Screen.prototype.reverseEngineerSpecs=function(){
//alert("screen.reverseEngineerSpecs");
var l,t,w,h,dw,dh,xmove,ymove;
w=eleWidth(this.frame);
h=eleHeight(this.frame);
l=this.frmL;
t=this.frmT;
dw=_nt(gPic.frame.style.width);
dh=_nt(gPic.frame.style.height);
//--- left ----
if(this.lockPosition){
 xmove=(l/dw)*100;
 ymove=(t/dh)*100;
}else{
 xmove=l+(w/2);
 xmove=(xmove/(dw/2));
 xmove=(xmove*50)-50;
 ymove=t+(h/2);
 ymove=(ymove/(dh/2));
 ymove=(ymove*50)-50;
}
this.xMove=xmove;
this.yMove=ymove;
}



//========================== colors ==================================


//------ flipDominantColor -------
// Option to set the desktop color to the dominant color of the current image
Screen.prototype.flipDominantColor=function(v){
if(v==null)v=(this.applyDominantColor)?0:1;
this.applyDominantColor=v;
if(v)this.setDominantColor();
}


	
Screen.prototype.setDominantColor=function(){
if(!this.applyDominantColor)return;
var scr=(this.hideImage)? gPic.getLockParent(this):this;
var c=getColor(scr.gImg);
scr.dominantColor=c;
if(c!=gPic.desktopColor){
	var tmp=scr.undoing;
	scr.undoing=1;
	gPic.viewDesktopColor(c);
	scr.undoing=tmp;
}}



/*
// Loads a list of canvases that are ACTUALLY VISIBLE (plus one each side) so we don't have to paint all of them
// (i think it works except when zoom>50, but not sure we need it!)
Screen.prototype.loadVisibleDivs=function(){
//for(var i=0; i<40; i++){
//		var k="C_"+i+"_1";
//	if(_obj(k))msg("k="+k+" EXISTS");
//	else msg("k="+k+" NOT FOUND");
//}
this.visibleDivs=",";
this.incomplete=0;
//---- validate screen settings  ---
if(gDefaultPerspective!=this.perspective && this.perspective!="")	return;	 	// skew, tilt, etc. 'pull in' panels outside the frame
if(this.xScale!=0 || this.yScale!=0)return;		// more perspective
if(this.xZoom<50 && this.yZoom<50) return;		// all panels are visible anyway
//--- get the visible div ids for the top left and bottom right panels ---
var rect=this.frame.getBoundingClientRect();
var ids=",";
var d1=document.elementFromPoint(rect.left+7,rect.top+7);
var d2=document.elementFromPoint(rect.right-7,rect.bottom-7);
//---- validate the corner div ids ---
if(!d1 || !d2)return;		// msg("nothing found (blank background) ");
if(!d1.id || !d2.id)return;		// msg("no id? ids="+d1+","+d2);
if(!_in(d1.id,"C_") || !_in(d2.id,"C_"))return;		// msg("invalid ids="+d1.id+","+d2.id);
//msg("id1="+d1.id+" id2="+d2.id);
//---- load all visible divs ---
var a1=d1.id.split("_");
var a2=d2.id.split("_");
var x1=parseInt(a1[1]); var x2=parseInt(a2[1]);
var y1=parseInt(a1[2]); var y2=parseInt(a2[2]);
// Add one each side
x1--; 	if(x1<0)x1=this.xxx-1;
x2++; 	if(x2==this.xxx)x2=0;
y1--; 	if(y1<0)y1=this.yyy-1;
y2++; 	if(y2==this.yyy)y2=0;
//msg("x1="+x1+", x2="+x2+", xxx="+this.xxx);
// MUST USE WHILE! (x2 could be less than x1!)
var x=x1;
while( x!=x2){
	var y=y1;
	while(y!=y2){
		ids+="C_"+x+"_"+y+",";
		y++; 	if(y==this.yyy)y=0;
	}
	x++; 	if(x==this.xxx)x=0;
}
this.visibleDivs=ids;
this.incomplete=1;
//msg("visible divs="+ids);
}
*/

//===============================================================================


//------------------------------- zoom ---------------------------------
Screen.prototype.zoom=function(zx,zy,sx,sy){
var ww,w,hh,h,l,t,chg;
var repaint=0;
if(zx==null)zx=this.xZoom; else this.xZoom=zx;
if(zy==null)zy=this.yZoom; else this.yZoom=zy;
if(sx==null)sx=(this.xSlide-50); else sx=(sx-50);
if(sy==null)sy=(this.ySlide-50); else sy=(sy-50);
ww=this.frmW;
hh=this.frmH;
w=_round(ww*(zx/50),2);
h=_round(hh*(zy/50),2);
l=_round((ww-w)/2,2);
t=_round((hh-h)/2,2);
//if(zx==50 && zy==50 && sx==50 && sy==50)return;
if(sx || sx!=(this.xSlide-50)){
 l=l+_round(((sx/100)*ww),2);
 this.xSlide=sx+50;
}else{ if(sx!=null)this.xSlide=50; }
if(sy || sy!=(this.ySlide-50)){
 t=t+_round(((sy/100)*hh),2);
 this.ySlide=sy+50;
}else{ if(sy!=null)this.ySlide=50; }

// --- old way - using size (x and y independantly) ---
if(zx!=zy){
	this.div.style.zoom="100%";
	this.divW=w;
	this.divH=h;
	this.divL=l;
	this.divT=t;
}else{
//--- new way - using CSS ---
	this.divW=ww;
	this.divH=hh;
	var zoom=_round((w/ww)*100,2);
	this.div.style.zoom=zoom+"%";
	this.divL=(100/zoom)*l;
	this.divT=(100/zoom)*t;
}
//msg(l+","+t+","+ww+","+hh);
//msg("zoom%="+zoom);
//var neww=(zoom/100)*ww;
//msg("neww="+neww);
//msg("left%="+Math.round((l/ww)*100)+"%");

//if(repaint)this.rotateApply(1);	//is this necessary?   'rotate'
}


//--------------------------- calcPcts (before skew/warp fix) --------------------------------   
// Calculate the % sizes using a split value
/*
Screen.prototype.calcPcts=function(fs,splt,max){
var vx,tmp,xtotal=100*max;
var newtotal=0;
var pct=splt;
if(splt>50)pct=100-splt;
pct=(100-(pct*2))/100;
for(var i=0;i<max;i++){
 vx=(xtotal-newtotal)/(max-i);                        // vx = new single panel share of remaining value
 fs[i]=(vx+((pct/(i+1))*(xtotal-(newtotal+vx))));     // assign a decreasing pct (pct/(i+1)) of new remaining total to current panel
 newtotal+=fs[i];
}
//--- reverse ----
if(splt<50){
 var a=fs.slice();
 for(var i=fs.length-1;i>-1;i--)
  fs[i]=a[fs.length-(i+1)];
}
//--- normalize ----
newtotal=0;
for(var i=0;i<fs.length-1;i++){
 fs[i]=Math.round(fs[i]);
 newtotal+=fs[i];
}
fs[fs.length-1]=xtotal-newtotal;
//---- print ----
//for(var i=0;i<fs.length;i++)msg("fs["+i+"]="+fs[i]);
//msg(fs);
return fs;

}
*/


//-------------- settings -----------


//--------- getVar() ---------- (called by animation.js)
Screen.prototype.getVar=function(n,ydim,defv){
var nm=capFirstLetter(n);
if(gPic.stdVar(n)){
	var x,y;
	eval("x=this.x"+nm+";");
	eval("y=this.y"+nm+";");
	if(ydim)return y;
	return x;
}	
return defv;
}


//--------- SV() ----------
Screen.prototype.SV=function(n,x,y){       //--- compact version called by animations
this.setVars(n,x,y);
}
	

//----------- stdVar ----------------------
//(this is duplicated here because somehow history uses it but i don't know how or where)
Screen.prototype.stdVar=function(n){
	if(_in(gStdVars,","+n+","))return 1;
	return 0;
}


//===================== rotations ===========================

Screen.prototype.rotate=function(k){
//msg("vx="+this.vx+", maxvx="+this.maxvx);
switch(k){
 case 1:this.vx--; break;
 case 2:this.vy--; break;
 case 3:this.vx++; break;
 case 4:this.vy++; break;
}
//msg("vx="+this.vx+", vy="+this.vy);
this.history.recordAction("s.rotate("+k+");");	//doesn't work!
this.rotateApply();	
}

//----------------- validate start point then apply rotation -------------------
Screen.prototype.rotateApply=function(){	
this.vx=this.validateXStart(this.vx);
this.vy=this.validateYStart(this.vy);
this.calcSpecs();
this.paint();
}


//----------------- validate start point  -------------------
Screen.prototype.validateXStart=function(x){
if(x<0)x=this.maxvx+x;
if(x>=this.maxvx)x=x-this.maxvx;
return x;
}
Screen.prototype.validateYStart=function(y){
if(y<0)y=this.maxvy+y;
if(y>=this.maxvy)y=y-this.maxvy;
return y;
}


//-------------------------- slide  -----------------------------------------
Screen.prototype.slide=function(x,y){	
if(x==null)x=this.xSlide;
if(y==null)y=this.ySlide;
this.zoom(null,null,x,y);
this.div.style.left=this.divL;
this.div.style.top =this.divT;
}

	
Screen.prototype.slideAble=function(){   
if(this.tileType=="Slidable")return 1;
return 0;
}


//---- for future use (use zbilbo?) ----
/*
Screen.prototype.chgLinkedDimensions=function(n,x,y){  
return;
var scr=this;
switch(n){
	case "image" :  scr.xSplit=x; scr.ySplit=y;	break;
	case "warp"  :  scr.xFold=x;  scr.yFold=y;	break;
	case "fold"  :  scr.xWarp=x;  scr.yWarp=y;	break;
	case "split" :  scr.xImage=x; scr.yImage=y; break;
}}


Screen.prototype.applyCSSFilter=function(filter,v){   
var filters=this.cssfilters;
if(filters==null)filters="";
if(_in(filters,"inherit"))filters="";
var typ, txt="", filterFound=0, f=filter;
switch(filter){
	case "blur" :		typ="px"; 		break;
	case "hue-rotate" :	typ="deg";		break;
	default	:			typ="%";
}
filter=filter+"("+v+typ+")";
var a=filters.split(" ");
for(var i=0;i<a.length;i++){
 if(a[i]){
  if(_in(a[i],f)){ txt+=filter+" "; filterFound=1; }
  else txt+=a[i]+" ";
}}
if(!txt)txt=filter;
else if(!filterFound)txt+=filter;
this.viewCSSFilters(txt);
}
*/


Screen.prototype.hideImg=function(v){
if(v==null)v=!this.hideImage;
this.hideImage=v*1;
for(var i=0;i<this.boxii.length;i++){
 this.boxii[i].div.style.display=(v)?"none":"block";
 this.history.recordAction("s.hideImg("+v+");");
 gPic.saveDoneStuff();
}}




//--------- Natural Size  ---------
Screen.prototype.naturalSize=function(){ 
var scr=this;
if(scr.media!="image" || !scr.natural || scr.hideImage || scr.typ=="lock")return;
var scr=this;
var toolboxWidth=(gToolbar.menucontrols.style.display=="block")?gToolbar.menucontrols.offsetWidth:0;
var iw=scr.gImg.width;
var ih=scr.gImg.height;
if(iw==0||ih==0)return;
scr.xSizeSave=scr.xSize;
scr.ySizeSave=scr.ySize;
var dw=((docWidth())*1)-toolboxWidth; if(dw<10)dw=10;
var dh=(docHeight())*1;
if(iw>ih){
	var ratio=(ih/iw);
	//var tmp=(scr.xSize>95)?90:scr.xSize;
	var tmp=80;
	var iw=(tmp/100)*dw;
	ih=iw*ratio;
}else{
	var ratio=(iw/ih);
	//var tmp=(scr.ySize>95)?90:scr.ySize;
	var tmp=80;
	var ih=(tmp/100)*dh;
	iw=ih*ratio;
}
var xsize=((iw/dw)*100);
var ysize=((ih/dh)*100);
scr.setVars("size",xsize,ysize);   
gPic.repaintSizes(scr.scrix);
}


//------------------------- Square Size  --------------------------------------
Screen.prototype.squareSize=function(v){  
return; //@@@
var scr=gPic.getTarget(scr,"size");
if(v==null)v=scr.square;
else scr.square=v;
//msg("scr.squareSize() ix="+scr.scrix+", v="+v);
if(!v){
	 //----turn square size off
	 var xsize=scr.xSizeSave;
	 scr.xMove=scr.xMoveSave;
	 var ysize=scr.ySizeSave;
	 scr.yMove=scr.yMoveSave;
	 scr.setVars("size",xsize,ysize);   
	 return;
}
 //----turn square size on
var toolboxWidth=(gToolbar.menucontrols.style.display=="block")?gToolbar.menucontrols.offsetWidth:0;
var dw=((docWidth())*1)-toolboxWidth;
var dh=((docHeight())*1);

//var iw=(60/100)*dw;
var iw=(scr.xSize/100)*dw;

var ih=iw;
var xsize=((iw/dw)*100);
var ysize=((ih/dh)*100);
scr.setVars("size",xsize,ysize);
}


Screen.prototype.isFullscreen=function(){
if(this.xSize==100 && this.ySize==100)return 1;
return 0;
}




//-------------------------------- setSizes ---------------------------------
Screen.prototype.setSizes=function(x,y){
var scr=gPic.getTarget(this,"size");
if(x==null)x=scr.xSize;
if(y==null)y=scr.ySize;
x=_round(x,1);
y=_round(y,1);
var xdiff=scr.xSize-x;
var ydiff=scr.ySize-y;
if(scr.xSize!=x || scr.ySize!=y){
	 scr.xSize=x;
	 scr.ySize=y;
}
scr.setChildSizes(scr,xdiff,ydiff);
scr.setLockedSizes(scr,xdiff,ydiff);
gToolbar.syncFullscreenBtns();  
}


Screen.prototype.setLockedSizes=function(scr,xdiff,ydiff){
for(var j=0;j<scr.lockedScreens.length;j++){
 	var lsc=scr.lockedScreens[j];
	if(lsc.xSize!=(lsc.xSize-xdiff) || lsc.ySize!=(lsc.ySize-ydiff)){
  		lsc.xSize-=xdiff;
  		lsc.ySize-=ydiff;
	}
	this.setLockedSizes(lsc,xdiff,ydiff);
}}


Screen.prototype.setChildSizes=function(scr,xdiff,ydiff){
for(var j=0;j<scr.childScreens.length;j++){
 	var lsc=scr.childScreens[j];
 	if(lsc.lockTyp=="frame"  ||  gPic.ResizeChildren){
  		lsc.xSize-=xdiff;
  		lsc.ySize-=ydiff;
	}
	this.setChildSizes(lsc,xdiff,ydiff);
}}


//-------------------------------- perspective ---------------------------------
Screen.prototype.setPerspective=function(scr,str,d3mode){
// called by screen.paint
if(scr==null)scr=this;
if(d3mode==null)d3mode=gPic.d3Mode;
var oldstr=gPic.getPerspectiveStr(scr,d3mode);
if(str==null)str=oldstr;
else{
	switch(d3mode){
	case "window"	: scr.perspectiveFrm=str; break;
	case "image"	: scr.perspectiveImg=str; break;
	case "panels"	: scr.perspectivePnl=str; break;
}	}
//msg("oldstr="+oldstr);
//msg("newstr="+str);
//if(oldstr==str && str=="")return;	//???? This does not RESET properly so maybe force it when you do a RESET?
if(d3mode=="window" && scr.mirrorChildren==2){
	str=" rotate("+str.split("rotate(")[1];
	var scale=gPic.strBigMirror(scr.frmMirror);
	str=scale+str;
}

switch(d3mode){
	case "window"	: gPic.transform(scr.frame,str); break;
	case "image"	: gPic.transform(scr.div,str);	 break;
	case "panels"	: break;
}
if(d3mode=="panels" && !scr.hideImage){	
	var xskew=gPic.getPerSkewX(str);
	var yskew=gPic.getPerSkewY(str);
	this.applySkewsAndWarps(xskew, yskew, this.xWarp, this.yWarp);
}
if(!scr.dontpaint)scr.repaint();	//????? 
}



//------------------------------ Set Blend --------------------------------------
Screen.prototype.setBlend=function(i){
if(i==null)i=this.blend;
else this.blend=i;
var b=gBlends[i];
//this.frame.style.isolation="auto";
this.frame.style.mixBlendMode=b;
if(gPic.gradientType!="flat")gPic.viewSetGradient();
}
	


//------------------------------ gradient --------------------------------------
Screen.prototype.setGradient=function(str,typ,position,color1,color2,pct1,pct2){
if(str==null)str=this.gradient;     else this.gradient=str;
if(typ==null)typ=this.gradientType; else this.gradientType=typ;
if(typ=="flat"){
 	 this.setFrameColor(this.frameColor);
}else{
	 if(str==null)str="";
	 if(position==null)position=this.getGradPos(str);
	 if(color1==null)  color1  =this.getGradColor1(str);
	 if(color2==null)  color2  =this.getGradColor2(str);
	 if(pct1==null)    pct1    =this.getGradPct1(str);
	 if(pct2==null)    pct2    =this.getGradPct2(str);
	 if(typ=="linear" && position=="center")typ="radial"; //"center" does not work with "linear"
	 str=position+", "+color1+" "+pct1+"%, "+color2+" "+pct2+"%";
	 this.gradient=str;
	 //msg("c1="+color1+", c2="+color2);
	 setGradient(this.frame,str,typ);
}
gToolbar.applyLabel(this);	// to update the thumbnail background
}


Screen.prototype.setGradientColor2=function(c){gPic.screen.setGradient(null,null,null,null,c);}
Screen.prototype.setGradientColor1=function(c){gPic.screen.setGradient(null,null,null,c);}
Screen.prototype.getGradPos=function(str)     {if(str==null)str=this.gradient; if(!str)return "center";  var a=str.split(", "); return a[0]; }
Screen.prototype.getGradPct1=function(str)    {if(str==null)str=this.gradient; if(!str)return 0;   var a=str.split(", ")[1].split(" ")[1].split("%"); return a[0]; }
Screen.prototype.getGradPct2=function(str)    {if(str==null)str=this.gradient; if(!str)return 100; var a=str.split(", ")[2].split(" ")[1].split("%"); return a[0]; }

Screen.prototype.getGradColor1=function(str)  {if(str==null)str=this.gradient; if(!str)return "#0e9be8"; var a=str.split(", ")[1].split(" "); return a[0]; }

Screen.prototype.getGradColor2=function(str)  {
	if(str==null)str=this.gradient; 
	if(!str)return "#0ddcc2"; 
	/*
	if(!str){ 
		if(this.frameColor && this.frameColor!="transparent")return this.frameColor; 
		else return "#0ddcc2"; 
	}  
	*/
	var a=str.split(", ")[2].split(" "); 
	return a[0]; 
}

Screen.prototype.setFrameColor=function(v)   {
	setGradient(this.frame,"flat"); 
	this.frame.style.background="";
	this.frameColor=this.frame.style.backgroundColor=v; 
	gToolbar.applyLabel(this);	// to update the thumbnail background
}


Screen.prototype.setOpacity1=function(x,noapply){  
this.opacity1=x;
if(noapply!=1)setOpacity(this.frame,x*99);
}

Screen.prototype.viewOpacity2=function(x,noapply){ //we need to call this 'view..' to make undo/redo work (trust me!)
this.opacity2=x;
if(noapply!=1)setOpacity(this.div,x*99);
}

//------------------------------- ClipRadius -------------------------------- 
Screen.prototype.setClipRadius=function(radius){
if(radius==null)radius=this.clipRadius;
else this.clipRadius=radius;
for(var j=0;j<this.lockedScreens.length;j++){
 	this.lockedScreens[j].clipRadius=radius;
}}



//------------------------------- corners --------------------------------------  
Screen.prototype.setCornersApplyTo=function(v) {
this.cornersApplyTo=v;
}

Screen.prototype.setCorners=function(radius,applyto) {
//msg("scrix="+this.scrix+", radius="+radius+", applyTo="+applyto+", len="+this.lockedScreens.length);
//--- applyto: 1=frame, 2=image, 3=both
if(radius==null)radius=this.cornerRadius;
else this.cornerRadius=radius;
if(applyto==null)applyto=this.cornersApplyTo;
else this.cornersApplyTo=applyto;
if(applyto!=2)setRadius(this.frame,radius); else setRadius(this.frame,"0%");
if(applyto!=1)setRadius(this.div,radius); else setRadius(this.div,"0%");
for(var j=0;j<this.lockedScreens.length;j++){
	this.lockedScreens[j].setCorners(radius,applyto);
}}

//===================== shadows ========================

Screen.prototype.setShadows=function(str,on) {
// eg. "1 0px 0px 16px 2px rgba(0,0,0,1), ..., ...";
if(str==null)str=this.shadows;
else this.shadows=str;
if(on==null)on=this.shadowsOn;
else this.shadowsOn=on;
//msg("setShadows(on="+on+", str="+str);

//---- initilaize shadows ---
setShadow(this,this.frame,"");
setShadow(this,this.shadowsDiv,"");   
setShadow(this,this.div,"");  
//--- apply shadows if any ---
if(!on)return;
var a=str.split("|");
var frameTxt="";
var imageTxt="";
for(var i=a.length-1 ; i>-1 ; i--){
	var shad=a[i].split(" ");
	if(shad.length<6)continue;
	var applyto=shad[0];
	var txt=shad[1]+" "+shad[2]+" "+shad[3]+" "+shad[4]+" "+shad[5];
	if(shad.length>6)txt+=" "+shad[6];	//inset
	if(applyto==1){
		if(frameTxt)frameTxt+=",";
		frameTxt+=txt;
	}else{
		if(imageTxt)imageTxt+=",";
		imageTxt+=txt;
	}
}
if(frameTxt)setShadow(this,this.frame,frameTxt);
if(imageTxt)setShadow(this,this.div,imageTxt);
if(frameTxt && _in(frameTxt,"inset"))setShadow(this,this.shadowsDiv,frameTxt);
}


//----------------------------------- shadows etc. -----------------------------
function setShadow(scr,obj,str){
obj.style.mozBoxShadow=str;
obj.style.webkitBoxShadow=str;
obj.style.boxShadow=str;
}


//------- flipVignetteColor --------
// Option to set the vignette (fade) to the dominant color of the current image
Screen.prototype.flipVignetteColor=function(v){
if(v==null)v=(this.applyVignetteColor)?0:1;
this.applyVignetteColor=v;
if(v)this.setVignetteColor();
}


Screen.prototype.setVignetteColor=function(){
var scr=(this.hideImage)? gPic.getLockParent(this):this;
if(scr.applyVignetteColor!=1)return;
var currentRgb	= gToolbar.getRgb(scr.shadows,0);
var opacity		= gToolbar.getRgbaOpacity(currentRgb);
var imgColor	= getColor(scr.gImg);
var imgRgb 		= HEX2RGBA(imgColor,opacity);
if(currentRgb!=imgRgb){
	var shadowsAry	= scr.shadows.split("|");
	var currentStr	= shadowsAry[0];
	var newStr		= currentStr.replace(currentRgb,imgRgb);
	var tmp=scr.undoing;
	scr.undoing=1;
	gToolbar.updateShadow(scr,newStr,0);
    gToolbar.syncShadows();
	scr.undoing=tmp;
}}


//============== SCREEN VIDEO CONTROLS =================

Screen.prototype.getVolume=function()     { return this.videoFrame.getVolume(); }
Screen.prototype.setVolume=function(v)    { this.videoFrame.setVolume(v); }
Screen.prototype.getVideoTime=function()  { return this.videoFrame.getVideoTime(); }
Screen.prototype.setVideoTime=function(v) { this.videoFrame.setVideoTime(v); }

Screen.prototype.mute=function(force){
var v=this.getVolume();
if(v==0 && this.oldVolume==null)this.oldVolume=100;
if(v>0)this.oldVolume=v;
if(force==null)force=(v)?1:0;
this.videoFrame.setVolume(((force)?0:this.oldVolume));
}

Screen.prototype.hide=function(force){
var hidden=(this.frame.style.display!="none")?0:1;
if(force==null)force=(hidden)?0:1;
this.frame.style.display=(force)?"none":"block";
}





//======================================================
//====================== BOX OBJECT ====================
//======================================================
// Each box has a number of panels.
// This creates all the panels, but does not size or paint them.
function Box(id,screen,div,maxx,maxy,xx,yy){
//msg("new box xy="+id);
var x,y,xy;
//--- defaults ---
this.vx=0;
this.vy=0;
this.maxx=2;
this.maxy=2;
this.mirror=0;
//--- passed vars ---
this.id=id;
this.screen=screen;
this.div=div;
this.maxx=maxx;
this.maxy=maxy;
this.x=xx;
this.y=yy;
//--- create the canvas ---
if(this.screen.useCanvas==1){
	 this.canvasObj=new Canvas(div,"C_"+this.x+"_"+this.y);	
	 this.context=this.canvasObj.context;
	 this.canvas=this.canvasObj.canvas;
}
//--- create the PANELS ----
this.panels=new Array();
this.panelii=new Array();
var i=0;
for(y=0;y<this.maxy;y++){
	 for(x=0;x<this.maxx;x++){
		  xy=xyKey(x,y);
		  this.panels[xy]=new Panel(this,x,y);
		  this.panelii[i]=this.panels[xy];
		  i++;
}}	}


//-------------------------- .calcSpecs --------------------------------------------
Box.prototype.calcSpecs=function(){
//This sizes the divs but does not paint them
var scr=this.screen;
var ww,hh,w,h,l,t;
var panel,div;
var x,y,xy;
var zx,zy,vx,vy;
var bdrwidth=(this.screen.picture.showBorders)?1:0;
//var bdrwidth=0;
//---- get default dimensions ----
ww=this.divW;
hh=this.divH;
l=0;
t=0;
//--- canvas size ---
if(scr.useCanvas==1){
	 this.canvas.width=ww;
	 this.canvas.height=hh;
	 //msg("calcspecs ww="+ww+", hh="+hh);
}
//-------- calc the split factors -------
//--- box split ---
this.xfs=new Array();
this.yfs=new Array();
if(this.xSplit!=50){
	this.xfs=this.screen.calcPctsArray(this.maxx,this.xSplit,scr.fullSplit,scr.offsetSplit);
	//msg("xfs="+this.xfs);
	//this.xfs=this.screen.calcPcts(this.xfs,this.xSplit,this.maxx);
}
if(this.ySplit!=50){
	this.yfs=this.screen.calcPctsArray(this.maxy,this.ySplit,scr.fullSplit,scr.offsetSplit);
	//this.yfs=this.screen.calcPcts(this.yfs,this.ySplit,this.maxy);
}
//--- image split ---
this.xfi1=new Array();  //for regular images
this.yfi1=new Array();
if(this.xImage!=50){
	this.xfi1=this.screen.calcPctsArray(this.maxzx,100-this.xImage,1,0);
	//this.xfi1=this.screen.calcPcts(this.xfi1,100-this.xImage,this.maxzx);
}
if(this.yImage!=50){
	this.yfi1=this.screen.calcPctsArray(this.maxzy,100-this.yImage,1,0);
	//this.yfi1=this.screen.calcPcts(this.yfi1,100-this.yImage,this.maxzy);
}
//--- set zx, vx, etc. --
vx=this.vx;
vy=this.vy;
zx=vx % this.maxzx;
zy=vy % this.maxzy;
//msg("vx="+vx+", maxzx="+this.maxzx+", zx="+zx);
var savezx=zx;
var savevx=vx;
//---- do the dirty ----
this.wtotal=0;
this.htotal=0;
for(y=0;y<this.maxy;y++){
	 h=(hh/this.maxy);
	 if(this.yfs[0])h=h*(this.yfs[y]/100);
	 h=Math.round(h);
	 t=this.htotal;
	 this.htotal+=h;
	 if(y==(this.maxy-1) && this.htotal<hh)h+=hh-this.htotal;
	 if(this.ySkew!=0)h+=0.5;	// fine lines problem 
	 this.wtotal=0;
	 for(x=0;x<this.maxx;x++){
		  xy=xyKey(x,y);
		  panel=this.panels[xy];
		  panel.zx=zx;	// nbr of panels from the image edge?
		  panel.zy=zy;
		  panel.vx=vx;	// nbr of panels from the root/start (home edge)?
		  panel.vy=vy;
		  w=(ww/this.maxx);
		  if(this.xfs[0])w=w*(this.xfs[x]/100);
		  w=Math.round(w);
		  panel.mirror=this.getMirror(panel,vx,vy);
		  l=this.wtotal;
		  this.wtotal+=w;
		  if(x==(this.maxx-1) && this.wtotal<ww)w+=ww-this.wtotal;
	 	  if(this.xSkew!=0)w+=0.5;	// fine lines problem 
		  //--- set the sizes ---
		  panel.ctxtW=w;
		  panel.ctxtH=h;
		  panel.ctxtL=l;
		  panel.ctxtT=t;
		  //msg("calcSpecs: xy="+xy+", vx="+vx);
		  this.xImageSpecs(panel);
		  this.yImageSpecs(panel);
		  vx++;
		  if(vx==this.maxvx)vx=0;
		  zx++;
		  if(zx==this.maxzx)zx=0;
	 }
	 vx=savevx;
	 zx=savezx;
	 vy++;
	 if(vy==this.maxvy)vy=0;
	 zy++;
	 if(zy==this.maxzy)zy=0;
}}


//------------------------------- getMirror ------------------------------------
Box.prototype.getMirror=function(panel,vx,vy){   
//msg("vx="+vx+", maxzx="+this.maxzx);
var hm=vm=0;
var zx=zy=z=0;
var xcut=Math.round(this.maxzx/2);
var ycut=Math.round(this.maxzy/2);
var oddzx=(xcut!=(this.maxzx/2))?1:0;
var oddzy=(ycut!=(this.maxzy/2))?1:0;
for(var i=0;i<(vx+1);i++){
	if(zx==this.maxzx){    //--- edge of image so ALWAYS flip the mirror
		hm=(hm==0)?1:0; 
		zx=1; 
		z++; 
	}else{                 //--- inside the image
		if(!oddzx){
			//msg("xcut="+xcut+", z="+z);
			if(!oddzx && zx==xcut)hm=(z==2||z==3||z==6||z==7||z==10||z==11)?1:0;       //[getMirror]
		}
		zx++;
}	}
panel.vzx=z;
z=0;
for(var i=0;i<(vy+1);i++){
	if(zy==this.maxzy){ vm=(vm==0)?2:0; zy=1; z++; }
	else{
		if(!oddzy && zy==ycut)vm=(z==2||z==3||z==6||z==7||z==10||z==11)?2:0;
		zy++;
}	}
//_obj("iDebugText").value="vx="+vx+", vy="+vy+", z="+z;
panel.vzy=z;
var mirr=hm+vm;
return mirr;}


//---------------------------- xImageSpecs -----------------------------------
//This calculates all the image settings relative to the containing box whose
//settings are calculated in resize(). (It creates specs for the rendering engine)
Box.prototype.xImageSpecs=function(panel){
var divw,w,l,tmp;
var imgl,imgw,imgt,imgh;
var x=panel.zx;	//???? zx= current panel within the image
divw=panel.ctxtW;

//--- get 'regular' image dimensions
w=divw*this.maxzx;
l=(((w/this.maxzx)*x)*-1);	
if(this.screen.useCanvas==0){ if(panel.mirror==1 || panel.mirror==3)l=(((w/this.maxzx)*(this.maxzx-(panel.zx+1)))*-1); }

//--- mid image splits don't work with odd numbered maxzx values ---
var xcut=Math.round(this.maxzx/2);
var oddzx=(xcut!=(this.maxzx/2))?1:0;
//msg("xImageSpecs: panel.zx="+x+", w="+w+", maxzx="+this.maxzx+", l="+l);

//------- calc image split -----
if(this.xImage!=50){ 
	 //msg("box.maxzx="+this.maxzx+", oddz="+oddzx+", panel.vzx="+panel.vzx);
	 // .vzx is the image the panel lies in (4x4 image array)
	 if(oddzx || panel.vzx==0 || panel.vzx==2){   //no halfway split/mirror   
		  var pct=this.xfi1[x];
		  w=divw*((this.maxzx*100)/pct);
		  l=0;
		  for(var i=0;i<x;i++){l+=this.xfi1[i];}
		  l=(w*(l/(this.maxzx*100)))*-1;
	 }else{
		  l=0;
		  if(x<this.maxzx/2){		// (maxzx=total nbr of panels per image)
			   var pct=this.xfi1[x]/2;
			   w=divw*(((this.maxzx/2)*100)/pct);
			   for(var i=0;i<x;i++){l+=this.xfi1[i];}
			   l=(w*(l/(this.maxzx*100)))*-1;
			   //if(panel.y==0)msg("x="+x+", pct="+pct+", l="+l+", w="+w);
		  }else{
			   var x2=(this.maxzx-1)-x;		// comes in here for anything that is greater than 1/4?
			   var pct=this.xfi1[x2]/2;
			   w=divw*(((this.maxzx/2)*100)/pct);
			   for(var i=0; i<this.maxzx/2;i++){l+=this.xfi1[i];}
			   for(var i=(this.maxzx/2); i>(this.maxzx-x) ;i--){ l-=this.xfi1[(x-(this.maxzx-i))];}
			   l=(this.maxzx*100)-l;
			   l=(w*(l/(this.maxzx*100)))*-1;
			   //if(panel.y==0)msg("x="+x+", x2="+x2+", pct="+pct+", l="+l+", w="+w);
		  }
	 }
}
//-------- calc img Fold ---------
var xs=this.xFold;
if(xs!=50){
	 var result=this.calcFold(l,w,xs,panel.mirror,1);  
	 l=result.v1;
	 w=result.v2;
}
//---- save the settings ---
panel.imgW=w;
panel.imgL=l;
//if(scr.media=="video"){ gPanel=panel; alert("panel.id=, box.xCrop="+xf+", w="+w+", l="+l); }
}



//---------------------------- yImageSpecs -----------------------------------
//This calculates all the image settings relative to the containing box whose
//settings are calculated in resize(). (It creates specs for the rendering engine)
Box.prototype.yImageSpecs=function(panel){
var divh,hh,h,t,tmp;
divh=panel.ctxtH;
var y=panel.zy;
//--- get 'regular' image dimensions --
h=divh*this.maxzy;
t=(((h/this.maxzy)*y)*-1);
if(this.screen.useCanvas==0){ if(panel.mirror==2 || panel.mirror==3)t=(((h/this.maxzy)*(this.maxzy-(panel.zy+1)))*-1); }
//--- mid image splits don't work with odd numbered maxzy values ---
var ycut=Math.round(this.maxzy/2);
var oddzy=(ycut!=(this.maxzy/2))?1:0;
//------- calc image split -----
if(this.yImage!=50){
 if(oddzy || panel.vzy==0 || panel.vzy==2){    //no halfway split/mirror
  var pct=this.yfi1[y];
  h=divh*((this.maxzy*100)/pct);
  t=0;
  for(var i=0;i<y;i++){t+=this.yfi1[i];}
  t=(h*(t/(this.maxzy*100)))*-1;
 }else{
  t=0;
  if(y<this.maxzy/2){
   var pct=this.yfi1[y]/2;
   h=divh*(((this.maxzy/2)*100)/pct);
   for(var i=0;i<y;i++){t+=this.yfi1[i];}
   t=(h*(t/(this.maxzy*100)))*-1;
  }else{
   var y2=(this.maxzy-1)-y;
   var pct=this.yfi1[y2]/2;
   h=divh*(((this.maxzy/2)*100)/pct);
   for(var i=0; i<this.maxzy/2;i++){t+=this.yfi1[i];}
   for(var i=(this.maxzy/2); i>(this.maxzy-y) ;i--){t-=this.yfi1[(y-(this.maxzy-i))];}
   t=(this.maxzy*100)-t;
   t=(h*(t/(this.maxzy*100)))*-1;
   //if(panel.y==0)msg("x="+x+", x2="+x2+", pct="+pct+", l="+l+", w="+w);
}}}
//-------- calc img Fold ---------
var ys=this.yFold;
if(ys!=50){
 var result=this.calcFold(t,h,ys,panel.mirror,0);
 t=result.v1;
 h=result.v2;
}
//---- save the settings ---
panel.imgH=h;
panel.imgT=t;
}



//---------------------------- calcFold -------------------------------------
Box.prototype.calcFold=function(v1,v2,f,m,xaxis){
var L1=v1;
var W1=v2;
var lt50v2=Math.round((v2/2)*(100/f));
var lt50v1=v1+(v2-lt50v2);
var gt50v2=Math.round((v2/2)*(100/(100-f)));
var gt50v1=v1+(v2-gt50v2);
if(f<50){
 if(m==3 || (xaxis && m==1) || (!xaxis && m==2) )v1=lt50v1;
 v2=lt50v2;
}else{
 if(m==0 || (xaxis && m==2) || (!xaxis && m==1) )v1=gt50v1;
 //if(m==0||m==2)v1=gt50v1;
 v2=gt50v2;
}  
return{v1:v1,v2:v2};}



//-------------------------------- perspective ---------------------------------  
Box.prototype.setPerspective=function(str){	//federico
// eg. s.perspective='scale(1)  rotate(0deg) skew(47deg,7deg) perspective(1045) rotate3d(20,0,0,0deg) '
var scr=this.screen;
//var scale=(scr.xScale>0)? _round(1-(scr.xScale/100),3)  :  1 ;
var scale=_round(1-(scr.xScale/100),3);
str=str.replace("scaleX(1) scaleY(1) ","");
str="scale("+scale+") "+str;
gPic.transform(this.div,str);
}


//----------------------------- paint ------------------------------------------
Box.prototype.paint=function(){
var x,y,xy,p;
var scr=this.screen;
this.div.style.left=this.divL;
this.div.style.top=this.divT;
this.div.style.width=this.divW;
this.div.style.height=this.divH;
if(scr.clipRadius!="0%" && scr.useCanvas==1)this.clipCanvas(scr.clipRadius);
this.applySkews(scr.xSkew, scr.ySkew, this.maxx, this.maxy);

for(y=0;y<this.maxy;y++){
	for(x=0;x<this.maxx;x++){
		 xy=xyKey(x,y);
		 p=this.panels[xy];
		 //msg("hideImage="+scr.hideImage+", usecanvas="+scr.useCanvas+", m="+scr.media);
		 if(!scr.hideImage && !scr.picture.showNumbers){
			  switch(scr.useCanvas){
			   case 0: 
					p.drawImage0(scr.gImg); 
					break;
			   case 1:
					p.drawImage1(scr.gImg);
					break;
			   case 2: 
					p.drawImage2(scr.gImg); 
					break;
			  }
		 }else{
			  //if(scr.useCanvas==2 && scr.picture.showBorders==1){
			  if(scr.picture.showNumbers==1){
				   if(!p.div)p.drawImage0(scr.gImg); //create the divs but don't paint
				   p.div.innerHTML="<center><div style='position:relative;top:20px;'><font face=arial color=red size=3>"+
				                   "<font color=gray>mr:</font>"+p.mirror+"<br><br><br>"+
				                   "<font color=gray>vx,vy:</font>"+p.vx+","+p.vy+"<br><br><br>"+
				                   "<font color=gray>zx,zy:</font>"+p.zx+","+p.zy+"<br><br><br>"+
				                   "<font color=gray>x,y:</font>"+p.x+","+p.y+"<br><br><br>"+
				                   "<font color=gray>vzx,vzy:</font>"+p.vzx+","+p.vzy+"<br><br><br>"+
				                   "</font></div></center>";
				   p.div.style.border="solid 1px blue";
		  	  }
	}	}
}
if(scr.useCanvas==1){
	var ctx=this.context;
	//--- clip ----
	if(this.screen.clipRadius!="0%"){
		ctx.closePath();
		ctx.restore();
	}
	/*
	msg("9?");
    ctx.save();
  	var imgData = ctx.getImageData(-30,-30,this.canvas.height,this.canvas.width);
	ctx.translate(this.canvas.width/2,this.canvas.height/2);
	ctx.rotate(convertToRadians(45));
  	ctx.putImageData(imgData,30,30);
    ctx.restore();
	*/
}
}



//========= FEDERICO ==========
Box.prototype.applySkews=function(xskew, yskew, cntx, cnty){
	if(xskew==0 && yskew==0){
		for(let x = 0; x < cntx; x++){
			for(let y = 0;y < cnty; y++){
				var p=this.panels[xyKey(x,y)];
				p.xSkewRad=0; 
				p.ySkewRad=0; 
			}
		}
		return;
	}
	xskew = parseFloat(xskew);
	yskew = parseFloat(yskew);
	
	let totalSizeX=cntx*100;
	let totalSizeY=cnty*100;

	let xSizes 	=  new Array();
	let ySizes 	=  new Array();
	xSizes=this.XcalcPctsFromPanels();
	ySizes=this.YcalcPctsFromPanels();

	let xPercentages = xSizes.map((x) => x / totalSizeX);
	let yPercentages = ySizes.map((y) => y / totalSizeY);

	let panelWidth = totalSizeX / cntx;
	let panelHeight = totalSizeY / cnty;
	
	let targetWidths = xSizes.map((x) => 2 * (x / 2 + panelHeight / 2 * Math.tan(toRadians(xskew))));
	let targetHeights = ySizes.map((y) => 2 * (y / 2 + panelWidth / 2 * Math.tan(toRadians(yskew))));

	// apply skews
	for(let x = 0; x < cntx; x++){
		for(let y = 0;y < cnty; y++){
			let newxskew = toDegrees(Math.atan((targetWidths[x] / 2 - xSizes[x] / 2) / (ySizes[y] / 2))); 
			let xtmp = newxskew; // xskew;
			let newyskew = toDegrees(Math.atan((targetHeights[y] / 2 - ySizes[y] / 2) / (xSizes[x] / 2))); 
			let ytmp = newyskew; //yskew;
			if(_isOdd(y) && !_isOdd(x)) { 
				xtmp = xtmp*-1; 
			} else if (!_isOdd(y) && _isOdd(x)){ 
	    		ytmp = ytmp*-1; 
    		} else if (_isOdd(y) && _isOdd(x)) { 
	    		xtmp = xtmp*-1; ytmp=ytmp*-1; 
    		} 
			//let str = "skew("+ xtmp + "deg," + ytmp + "deg)";
			var p=this.panels[xyKey(x,y)];
			p.xSkewRad=toRadians(xtmp); 
			p.ySkewRad=toRadians(ytmp); 
		}	
	}
}

Box.prototype.XcalcPctsFromPanels=function(){
var ax = new Array();
var tot=100*this.maxx;
for(var x = 0; x < this.maxx; x++){
	ax[x] = Math.round(((this.panels[xyKey(x,0)].ctxtW/this.divW)*100)*this.maxx); 
	//msg("ctxtW="+this.panels[xyKey(x,0)].ctxtW+", divW="+this.divW);
}return ax;}

Box.prototype.YcalcPctsFromPanels=function(){
var ay = new Array();
var tot=100*this.maxy;
for(var y = 0; y < this.maxy; y++){
	ay[y] = Math.round(((this.panels[xyKey(0,y)].ctxtH/this.divH)*100)*this.maxy); 
}
return ay;
}




//----------------------------- clipCanvas() ----------------------------------
Box.prototype.clipCanvas=function(radius){
if(radius==null)radius=this.screen.clipRadius;
if(radius=="0%")return;
radius=parseInt(radius.replace("%",""));
var ctx=this.context,x=y=radius;
var width=this.canvas.width-(radius*2);
var height=this.canvas.height-(radius*2);
radius=radius*5;
ctx.beginPath();
ctx.moveTo(x + radius, y);
ctx.lineTo(x + width - radius, y);
ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
ctx.lineTo(x + width, y + height - radius);
ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
ctx.lineTo(x + radius, y + height);
ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
ctx.lineTo(x, y + radius);
ctx.quadraticCurveTo(x, y, x + radius, y);
ctx.closePath();
ctx.clip();
}



//======================================================
//================== PANEL OBJECT ======================
//======================================================
//=== PUT ALL HTML5 SPECIFIC CODE IN THE PANEL OBJECT ==
//======================================================
/* x=   physical panel nbr
   zx=  panel nbr within an image
*/
function Panel(box,x,y,zx,zy){
this.name="panel";
this.box=box;
this.x=x;
this.y=y;
this.zx=zx;
this.zy=zy;
if(this.box.screen.useCanvas==1){
 this.canvas=box.canvas;
 this.context=box.context;
}else{
}}


Panel.prototype.drawImageAt = function(img, ctx, crpl, crpt, crpw, crph, cx, cy, cw, ch, xSkew, ySkew, xScale, yScale){
	ctx.save();	
	ctx.translate(cx, cy);
    let transform = ctx.getTransform();
    ctx.setTransform(transform.a, Math.tan(ySkew), Math.tan(xSkew), transform.d, transform.e, transform.f);
    ctx.scale(xScale, yScale);
    ctx.drawImage(img, crpl, crpt, crpw, crph, -cw / 2, -ch / 2, cw, ch);
    ctx.restore();
}




//-------------------------------- drawImage1() -----  ( DEFAULT use canvas=1)---------------------------------
Panel.prototype.drawImage1=function(img){
var scr=this.box.screen;
var xy=this.x+""+this.y;
var mirror=this.mirror;
// --- Do we need to paint it? ---
if(scr.incomplete && !_in(scr.visibleDivs,","+this.box.canvas.id+","))	return;
//--- locate an image ---	
if(!scr.scrambleImages){   
	try{
		//msg("src="+scr.gImg.src);
		scr.findStartImage(scr.gImg.src);
	}catch(e){ 
		//msgbr("drawImage1 error - no img"); 
		//msg("scr.imgix="+scr.imgix);
		//msg("scr.src="+scr.src);
		//msg("scr.gImg.src="+scr.gImg.src);  //gives error
		msg("scr.gImg="+scr.gImg);  //gives error
		//console.trace();
		return; 
	}
}
//--- scramble images ---
if(scr.scrambleImages){   
	var ix=_nextIX( (scr.startImageIX+1) , (gPic.MIRRORobjects.length-1) );
	img=gPic.MIRRORobjects[ix];    
	mirror=0;
	scr.startImageIX=ix;
}
//--- multiple images ---
if(scr.multipleImages){	
	var ix=_nextIX( (scr.startImageIX + mirror) , (gPic.MIRRORobjects.length-1) );
	img=gPic.MIRRORobjects[ix];
	mirror=0;
}	

//------ draw the image ------	
if(img.src=="")return;
var crpl,crpw,crpt,crph;
var cw,ch,cl,ct,iw,ih;
var w,h,l,t;
var ctx=this.context;
ctx.save();
//ctx.imageSmoothingEnabled=true;  //false is the default
//ctx.imageSmoothingQuality="high";
//---- convert co-ordinates to use a big single canvas ----
w=this.imgW;
l=this.imgL;
h=this.imgH;
t=this.imgT;
cw=this.ctxtW;
ch=this.ctxtH;
cl=this.ctxtL;
ct=this.ctxtT;
iw=img.width;
ih=img.height;
crpl=l*-1;
crpl=crpl*(iw/w);
crpw=cw*(iw/w);
crpt=t*-1
crpt=crpt*(ih/h);
crph=ch*(ih/h);
//--- apply the settings ----
var vw=this.canvas.width;
var vh=this.canvas.height;
let ySkew = this.ySkewRad;
let xSkew = this.xSkewRad;
if(mirror==1){
	 this.trans(vw,0);
	 cl=vw-(cl+cw);
	 crpl=iw-(crpl+crpw);
	 ctx.scale(-1,1);
     ySkew *= -1;
}
if(mirror==2){
	 this.trans(0,vh);
	 ct=vh-(ct+ch);
	 crpt=ih-(crpt+crph);
	 ctx.scale(1,-1);
     xSkew *= -1;
}
if(mirror==3){
	 this.trans(vw,vh);
	 cl=vw-(cl+cw);
	 crpl=iw-(crpl+crpw);
	 ct=vh-(ct+ch);
	 crpt=ih-(crpt+crph);
	 ctx.scale(-1,-1);
     xSkew *= -1;
     ySkew *= -1;
}
//---- image frackScreen ---
if(scr.frackL!=0 || scr.xFrack!=100){
	crpw=(crpw*(scr.xFrack/100));
}
if(scr.frackT!=0 || scr.yFrack!=100){
	crph=(crph*(scr.yFrack/100));
}

//--- validate the nbrs ---
if(crpw<1)crpw=1;
if(crph<1)crph=1;
if((crpl+crpw)>iw)crpl=iw-crpw;
if((crpt+crph)>ih)crpt=ih-crph;
if(crpl<0)crpl=0;
if(crpt<0)crpt=0;

crpl=Math.round(crpl);
crpw=Math.round(crpw);


//-------------- draw the image using canvas ----------
//msg("1: xy="+xy+", cl="+cl+", cw="+cw+", w="+w+", ct="+ct+", ch="+ch+", h="+h);
//msg("skewRads="+this.xSkewRad+","+this.ySkewrad);
/*
if(!xSkew && !ySkew){
	ctx.drawImage(img,crpl,crpt,crpw,crph,cl,ct,cw,ch);
}else{
	ctx.translate(cl + cw / 2, ct + ch / 2);
	let transform = ctx.getTransform();
	ctx.setTransform(transform.a, Math.tan(ySkew), Math.tan(xSkew), transform.d, transform.e, transform.f);
	ctx.drawImage(img, crpl, crpt, crpw, crph, -cw/2, -ch/2, cw, ch);
}
ctx.restore();
*/


//if(!xSkew && !ySkew && w=="SKIP!"){
if(!xSkew && !ySkew){
	ctx.drawImage(img,crpl,crpt,crpw,crph,cl,ct,cw,ch);
}else{

    ctx.save();
    ctx.translate(cl + cw / 2, ct + ch / 2);
    let transform = ctx.getTransform();
    ctx.setTransform(transform.a, Math.tan(ySkew), Math.tan(xSkew), transform.d, transform.e, transform.f);
    ctx.drawImage(img, crpl, crpt, crpw, crph, -cw/2, -ch/2, cw, ch);
    ctx.restore();
	
	//--- apply xyBuffer? ----
	//if (xSkew || ySkew){
		let x = this.x;
		let y = this.y;

		// swap coordinates if mirrored
		if (mirror == 1 || mirror == 3){
			if (x == 0){
				x = this.box.maxx -1;
			}else if (x == this.box.maxx - 1){
				x = 0;
			}
		}
		if (mirror == 2 || mirror == 3){
			if (y == 0){
				y = this.box.maxy -1;
			}else if (y == this.box.maxy - 1){
				y = 0;
			}
		}
		// sides
		if (x == 0){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl - cw / 2, ct + ch / 2, cw, ch, xSkew, -1 * ySkew, -1, 1);
		}
		if (y == 0){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl + cw / 2, ct - ch / 2, cw, ch, -1 * xSkew, ySkew, 1, -1);
		}
		if(x == this.box.maxx-1){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl + cw * 3 / 2, ct + ch / 2, cw, ch, xSkew, -1 * ySkew, -1, 1);
		}
		if(y == this.box.maxy-1){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl + cw / 2, ct + 3 * ch / 2, cw, ch, -1 * xSkew, ySkew, 1, -1);
		}

		// corners
		if (x == 0 && y == 0){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl - cw / 2, ct - ch / 2, cw, ch, -1 * xSkew, -1 * ySkew, -1, -1);			
		}
		if (x == 0 && y == this.box.maxy-1){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl - cw / 2, ct + 3 * ch / 2, cw, ch, -1 * xSkew, -1 * ySkew, -1, -1);			
		}
		if (x == this.box.maxx-1 && y == 0){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl + cw * 3 / 2, ct - ch / 2, cw, ch, -1 * xSkew, -1 * ySkew, -1, -1);			
		}
		if (x == this.box.maxx-1 && y ==this.box.maxy-1){
			this.drawImageAt(img, ctx, crpl, crpt, crpw, crph, cl + cw * 3 / 2, ct + 3 * ch / 2, cw, ch, -1 * xSkew, -1 * ySkew, -1, -1);			
		}
}
ctx.restore();
}


//-------------------------------- drawImage0() ---------------------------------
//(usecanvas==0)
//Doing it this way has problems... the images don't rotate! (but iframes do)
//Also: I have not included support for fracking
//---- BUT USE THIS FOR IFRAMES -----
Panel.prototype.drawImage0=function(img){
if(img && img.src=="")return;
var scr=this.box.screen;
var xy=this.x+""+this.y;
var cw,ch,cl,ct,iw,ih;
var w,h,l,t;
var div,olddivimg;
w=this.imgW;
l=this.imgL;
h=this.imgH;
t=this.imgT;
cw=this.ctxtW;
ch=this.ctxtH;
cl=this.ctxtL;
ct=this.ctxtT;
//--- do the div ---
if(!this.div){
 this.divimg=null;
 div=this.div=_newObj("div");
 div.className="scrdiv";
 _addObj(this.box.div,this.div);
}else{
 div=this.div;
}
div.style.left=cl;
div.style.top=ct;
div.style.width=cw;
div.style.height=ch;
//div.style.backgroundColor="pink";
//div.style.border="solid 1px black";
if(!gPic.showNumbers)setMirror(div,this.mirror);
olddivimg=this.divimg;
//------- other media -------
if(this.box.screen.media!="image"){
 var src=this.box.screen.src;
 if(this.divimg){
	  if(this.box.screen.media=="video" && this.divimgSrc && this.divimgSrc!=src){
		   var ok=1;
		   var tmpsrc=(this.box.screen.media=="video")?"@vid@"+src:src;
		   try{
			    //eval("ok=i"+this.box.screen.media+"Frame.gotoImg('"+tmpsrc+"');");
			    eval("ok=iFrame"+scr.scrix+".gotoImg('"+tmpsrc+"');");
		   }catch(e){ok=0; }
		   if(ok){
			    this.divimgUrl=this.divimgUrl.replace(this.divimgSrc,src);
			    this.divimgSrc=src;
	  }	   }
  	  if(this.divimgSrc!=src)this.divimg=null;
 }
 if(!this.divimg){
  //alert("loadImage gDir="+gDir+", src="+src);
  //var url=(this.box.screen.media=="video")?"_play.php?inpixi=yes&usepixi=no&ssthumbs=no&toolbar=no&src="+src+"&dir="+gDir+"&fullscreen=1&includesubdirs=no&type=videos":src;
  var dirr=this.box.screen.gDir;
  //var url=(this.box.screen.media=="video")?"_play.php?inpixi=yes&usepixi=no&ssthumbs=no&toolbar=no&src="+src+"&dir="+dirr+"&fullscreen=1&includesubdirs=no&type=videos&takepic="+gTakepic  :  src;
  var url=(this.box.screen.media=="video")?"_play.php?inpixi=yes&usepixi=no&src="+src+"&dir="+dirr+"&fullscreen=1&includesubdirs=no&type=videos&takepic="+gTakepic  :  src;
  var styl="width:"+w+"px; height:"+h+"px; left:"+l+"px;  top:"+t+"px;";
  //div.innerHTML='<iframe id="i'+this.box.screen.media+'Frame" class=scrdiv style="'+styl+'" src="'+url+'" frameborder="0"></iframe>';
  div.innerHTML='<iframe id="iFrame'+scr.scrix+'" class=scrdiv style="'+styl+'" src="'+url+'" frameborder="0"></iframe>';
  //msg("i"+this.box.screen.media+"Frame");
  if(scr.media=="video")gPic.videoFrame=scr.videoFrame=frames[frames.length-1];
  this.divimg=div.firstChild;
  this.divimgSrc=src;
  this.divimgUrl=url;
 }else{
  var ds=this.divimg.style;
  ds.width=w;
  ds.height=h;
  ds.left=l;
  ds.top=t;
}}
//-------- images --------
if(this.box.screen.media=="image"){
 if(!this.divimg){
  var styl="width:"+w+"px; height:"+h+"px; left:"+l+"px;  top:"+t+"px;";
  div.innerHTML="<img src='"+img.src+"' class='scrdiv' style='"+styl+"'>";
  this.divimg=div.firstChild;
  this.divimgSrc=img.src;
 }else{
  if(this.divimg.src!=img.src)this.divimg.src=img.src;
  var ds=this.divimg.style;
  ds.width=w;
  ds.height=h;
  ds.left=l;
  ds.top=t;
}}
this.divimg.style.border=(gPic.showBorders)? "solid 1px pink" : "solid 0px pink" ;
}




//----------------------------- drawImage2() -----------------------------------
//(usecanvas==2)
Panel.prototype.drawImage2=function(img){
if(img.src=="")return;
var scr=this.box.screen;
var xy=this.x+""+this.y;
var iw,ih,crpl,crpw,crpt,crph;
var cw,ch,cl,ct,iw,ih;
var w,h,l,t;
var div,divimg;
var canvas,ctx;
w=this.imgW;
l=this.imgL;
h=this.imgH;
t=this.imgT;
cw=this.ctxtW;
ch=this.ctxtH;
cl=this.ctxtL;
ct=this.ctxtT;
iw=img.width;
ih=img.height;
crpl=l*-1;
crpl=crpl*(iw/w);
crpw=cw*(iw/w);
crpt=t*-1
crpt=crpt*(ih/h);
crph=ch*(ih/h);
//--- get the div and canvas ---
if(!this.div){
 div=this.div=_newObj("div");
 div.className="scrdiv";
 _addObj(this.box.div,this.div);
 this.canvas=new Canvas(div);
 canvas=this.canvas.canvas;
 ctx=this.canvas.context;
}else{
 div=this.div;
 canvas=this.canvas.canvas;
 ctx=this.canvas.context;
}
div.style.left=cl;
div.style.top=ct;
div.style.width=cw;
div.style.height=ch;
//div.style.backgroundColor="pink";
//div.style.border="solid 1px black";
canvas.width=cw;
canvas.height=ch;
if(!gPic.showNumbers)setMirror(div,this.mirror);
//--- adjust for mirrors ---
var vw=this.canvas.width;
var vh=this.canvas.height;
if(this.mirror==1||this.mirror==3){
 cl=vw-(cl+cw);
 crpl=iw-(crpl+crpw);
}
if(this.mirror==2||this.mirror==3){
 ct=vh-(ct+ch);
 crpt=ih-(crpt+crph);
}
//---- image frackScreen ---
if(scr.frackL!=0 || scr.xFrack!=100){
 //var xfrackl=scr.frackL*this.box.maxzx*2;
 //crpl=(crpl+(crpw*(xfrackl/100)));
 crpw=(crpw*(scr.xFrack/100));
 //crpl=(crpl*(scr.xFrack/100));
}
if(scr.frackT!=0 || scr.yFrack!=100){
 //var yfrackl=scr.frackT*this.box.maxzy*2;
 //crpt=(crpt+(crph*(yfrackl/100)));
 crph=(crph*(scr.yFrack/100));
 //crpt=(crpt*(scr.yFrack/100));
}
//--- validate the nbrs ---
if(crpw<1)crpw=1;
if(crph<1)crph=1;
if((crpl+crpw)>iw)crpl=iw-crpw;
if((crpt+crph)>ih)crpt=ih-crph;
if(crpl<0)crpl=0;
if(crpt<0)crpt=0;

//--- attempt to apply fades and overlap panels? ---
/* --- doesn't work! ---
if(_in(img.src,"sddsfas")){
 div.style.left=cl-(cl*0.05);
 div.style.top=ct-(ct*0.05);
 div.style.width=cw+(cw*0.1);
 div.style.height=ch+(ch*0.1);
 crpl=crpl-(crpl*0.05);
 crpt=crpt-(crpt*0.05);
 crpw=crpw+(crpw*0.1);
 crph=crph+(crph*0.1);
 this.box.applyMask(ctx,3,0.099,0.1,1);
}
*/

//--- draw the image ---
if(this.box.screen.hideImage==0){
 ctx.save();
 ctx.drawImage(img,crpl,crpt,crpw+1,crph+1,0,0,cw+1,ch+1);
 ctx.restore();
}
//msg("xy="+xy+", crpl="+crpl+", crpw="+crpw+", crpt="+crpt+", crph="+crph+", iw="+iw+", ih="+ih);
//msg("xy="+xy+", cl="+cl+", cw="+cw+", w="+w+", ct="+ct+", ch="+ch+", h="+h);
div.style.border=(gPic.showBorders)? "solid 1px lightblue" : "solid 0px lightblue";
}


//---------------------------- canvas transformations --------------------------
Panel.prototype.trans=function(w,h){this.context.translate(w,h);}
Panel.prototype.rotate=function(deg){this.context.rotate(convertToRadians(deg));}
function convertToRadians(degree){	return degree*(Math.PI/180); }




//=====================================================
//==============  CANVAS OBJECT =======================
//=====================================================
function Canvas(div,id){
this.canvas=_newObj("CANVAS");
_addObj(div,this.canvas);
if(id)this.canvas.id=id;
this.canvas.className="canvas";
//this.context=this.canvas.getContext('2d');
this.context=this.canvas.getContext('2d', { willReadFrequently: true });
}



//======================================================
//====================== FULTERS =======================
//======================================================
Fulters={};

Fulters.custom = function(arr) {
  Fulters.runFilter('custom', Fulters.convolute, arr, true);
};
	//case "glarify"	: ary= [ 4, -1,  0.6, -0.6,  5, -1, 0, -1, -4.2];		break;

Fulters.getPixels = function(c) {
  //var ctx = c.getContext('2d');
  var ctx = c.getContext('2d', { willReadFrequently: true });
  return ctx.getImageData(0,0,c.width-1,c.height-1);
};


Fulters.filterImage = function(filter, image, var_args) {
  var args = [this.getPixels(this.tmpCanvas)];
  for (var i=2; i<arguments.length; i++) {
    args.push(arguments[i]);
  }
  return filter.apply(null, args);
};

Fulters.createImageData = function(w,h) {
  return this.tmpCtx.createImageData(w,h);
};


Fulters.runFilter = function(id, filter, arg1, arg2, arg3) {
  var idata = Fulters.filterImage(filter, Fulters.image, arg1, arg2, arg3);
  Fulters.tmpCanvas.width  = idata.width;
  Fulters.tmpCanvas.height = idata.height;
  //var ctx = Fulters.tmpCanvas.getContext('2d');
  var ctx = Fulters.tmpCanvas.getContext('2d', { willReadFrequently: true });
  this.box.boxdata=idata;
  ctx.putImageData(idata, 0, 0);
};



Fulters.convolute = function(pixels, weights, opaque) {
  var side = Math.round(Math.sqrt(weights.length));
  var halfSide = Math.floor(side/2);
  var src = pixels.data;
  var sw  = pixels.width;
  var sh  = pixels.height;
  // pad output by the convolution matrix
  var w = sw;
  var h = sh;
  var output = Fulters.createImageData(w, h);
  var dst = output.data;
  // go through the destination image pixels
  var alphaFac = opaque ? 1 : 0;
  for (var y=0; y<h; y++) {
    for (var x=0; x<w; x++) {
      var sy = y;
      var sx = x;
      var dstOff = (y*w+x)*4;
      // calculate the weighed sum of the source image pixels that
      // fall under the convolution matrix
      var r=0, g=0, b=0, a=0;
      for (var cy=0; cy<side; cy++) {
        for (var cx=0; cx<side; cx++) {
          var scy = sy + cy - halfSide;
          var scx = sx + cx - halfSide;
		  //federico - fixs the edge problem
   		  if (scx < 0)scx = 0;
   		  if (scy < 0)scy = 0;
   		  if (scx >= sw)scx = sw - 1;
   		  if (scy >= sh)scy = sh - 1;
          //if (scy >= 0 && scy < sh && scx >= 0 && scx < sw) {
            var srcOff = (scy*sw+scx)*4;
            var wt = weights[cy*side+cx];
            r += src[srcOff] * wt;
            g += src[srcOff+1] * wt;
            b += src[srcOff+2] * wt;
            a += src[srcOff+3] * wt;
          //}
        }
      }
      dst[dstOff]   = r;
      dst[dstOff+1] = g;
      dst[dstOff+2] = b;
      //dst[dstOff+3] = a + alphaFac*(255-a);	//preserve the alpha so we don't mess up masking?
      dst[dstOff+3] = src[srcOff+3];
    }
  }
  return output;
};

// ------------ sobel --------------
Fulters.sobel=function(bw){
let source =this.getPixels(this.tmpCanvas);
var w  = source.width;
var h  = source.height;
var v;
let buffer = this.createImageData(w, h);
for(let x = 1; x < w; x++){
	for(let y = 1; y < h; y++){
		let r = dosobel(source, x, y, 0) * 3;
		let g = dosobel(source, x, y, 1) * 3;
		let b = dosobel(source, x, y, 2) * 3;
		if(bw)r=g=b	=(r+g+b)/3;
		r=Math.round(255-r); g=Math.round(255-g); b=Math.round(255-b); 
		//cnt++; 	if(cnt>1000){ msg("rgb="+r+","+g+","+b); cnt=0; }
		setRGB(buffer, x, y, [r, g, b]);
	}
}
this.box.boxdata=buffer;
this.tmpCtx.putImageData(buffer, 0, 0);
}

function pxlValue(img, x, y, color){
	var px	=((y * (4 * img.width)) + (4 * x));
    return	img.data[px+color]*1;
}
/*
function pxlValue(img, x, y){
	var px	=((y * (4 * img.width)) + (4 * x));
    var r	=img.data[px]*1;
	var g	=img.data[px+1]*1;
	var b	=img.data[px+2]*1;
	return ((r+g+b)/3);
}
*/
function setRGB(img, x, y, rgb){
	img.data[((y * (4 * img.width)) + (4 * x)) + 0] = rgb[0];
	img.data[((y * (4 * img.width)) + (4 * x)) + 1] = rgb[1];
	img.data[((y * (4 * img.width)) + (4 * x)) + 2] = rgb[2];
	img.data[((y * (4 * img.width)) + (4 * x)) + 3] = 255;
}


function dosobel(img, x, y, color){
	var gradX = 0;
	var gradY = 0;

	gradX += pxlValue(img, x-1, y-1, color) * -1;
	gradX += pxlValue(img, x-1, y+0, color) * -2;
	gradX += pxlValue(img, x-1, y+1, color) * -1;
	gradX += pxlValue(img, x+1, y-1, color) *  1;
	gradX += pxlValue(img, x+1, y+0, color) *  2;
	gradX += pxlValue(img, x+1, y+1, color) *  1;

	gradY += pxlValue(img, x-1, y-1, color) * -1;
	gradY += pxlValue(img, x+0, y-1, color) * -2;
	gradY += pxlValue(img, x+1, y-1, color) * -1;
	gradY += pxlValue(img, x-1, y+1, color) *  1;
	gradY += pxlValue(img, x+0, y+1, color) *  2;
	gradY += pxlValue(img, x+1, y+1, color) *  1;

	gradX /= 9;
	gradY /= 9;

	return Math.sqrt(Math.pow(gradX, 2) + Math.pow(gradY, 2));
}


//------- glow() -------- doesn't work!
/*
Fulters.glow = function(passes,glowPasses){
var pixels	=this.getPixels(this.tmpCanvas);
var orig_pixels =pixels;
var image	=this.image;
var w		=pixels.width;
var h		=pixels.height;
for(var i=0; i < passes; i++){  
    pixels = Fulters.convolute(pixels, 
        [1/9,  1/9,  1/9,
        1/9,  1/9,  1/9,
        1/9,  1/9,  1/9 ]);
}
var tempCanvas = document.createElement("canvas"),
    glowCanvas = document.createElement("canvas"),
    tCtx = tempCanvas.getContext("2d"),
    gCtx = glowCanvas.getContext("2d");
tempCanvas.width  = glowCanvas.width  = w;
tempCanvas.height = tempCanvas.height = h;
tCtx.putImageData(pixels, 0, 0);
gCtx.putImageData(orig_pixels, 0, 0);
//gCtx.drawImage(image, 0, 0);	//can't get this to work!
gCtx.globalCompositeOperation = "lighter";
for(i = 0; i < glowPasses; i++){
    gCtx.drawImage(tempCanvas,0,0);
}
//msg("w="+w+", h="+h);
pixels = this.getPixels(glowCanvas);
this.tmpCtx.putImageData(pixels, 0, 0);	
}
*/


//====================================================================
//===================== CONFIG OBJECT ================================
//====================================================================
function Config(configtxt,tiletype,tiling,typ,locktyp,media,dir,images,imgix){  
var s=this;
//=========== Set defaults =============   
s.setDefaults();
if(configtxt)eval(configtxt); 
if(tiletype){
	s.tileType =tiletype;
	gPic.applyPattern(s);
	gPic.setTiling(s,s.Tiling);
}else{
	if(tiling){
		gToolbar.resetTileType(s);
		s.Tiling=tiling;
		gPic.setTiling(s,s.Tiling);
	}else{
		//???? rely on setDefaults?
	}
}
if(typ!=null)		s.typ=typ; 
if(locktyp!=null)	s.lockTyp=locktyp;
if(dir!=null)		s.gDir=dir;
if(media!=null)		s.media=media;
//if(images!=null)	s.images=images;
if(imgix!=null){
	s.imgix	=imgix;
	s.src	=images[imgix];
}
if(s.media!="image"){
 	s.useCanvas=0;
 	gPic.setTiling(s,gNONEtile);
}
s.playing=(s.media=="video")?1:0;
}


// NB: Changes made here (new variables!) must be also made to CopyScreen() and grabConfig()
Config.prototype.setDefaults=function(){
var s=this;
gPic.applyPattern(s);  				// sets tiling, zoom, fullwarp, offsetwarp, vx, vy, tileType and Tiling
gPic.setTiling(s,s.Tiling);			// sets xxx, yyy, maxzx, maxzy, maxx, maxy, maxvx, maxvy, (validates vx, vy)
//s.animation=new Animation(); //???? causes error because no screen is passed!
//s.images=new Array();
s.imgix=0;
//s.images[0]=s.src=gPic.images[gPic.imgix];;
s.typ="";
s.lockTyp="";
s.lockPosition=0;
s.resizeChildren=1;
s.offsetWarp=0;
s.fullWarp=1;
s.offsetSplit=0;
s.fullSplit=1;
s.childix=0;
s.cssfilters="";	//inherit
s.media="image";
s.useCanvas=gDefaultUseCanvas;
s.gDir=gPic.gDir;
s.fullscreen=0;
s.natural=0;
s.square=0;
s.multipleImages=0;
s.scrambleImages=0;
s.applyDominantColor=0;
s.applyVignetteColor=0;
s.blend=0;
s.mirrorChildren=0;
s.xSize=100;
s.ySize=100;
s.xSizeSave=100;
s.ySizeSave=100;
if(s.lockPosition){
 s.xMove=s.yMove=0;
 s.xMoveSave=s.yMoveSave=0;
}else{
 s.xMove=s.yMove=0;
 s.xMoveSave=s.yMoveSave=0;
}
s.xWarp=50;
s.yWarp=50;
s.xSplit=50;
s.ySplit=50;
s.xImage=50;
s.yImage=50;
s.xFold=50;
s.yFold=50;
s.xSlide=50;
s.ySlide=50;
s.xStretch=0;
s.yStretch=0;
s.xScale=0;
s.yScale=0;
s.xCrop=50;
s.yCrop=50;
s.lCrop=0;
s.tCrop=0;
s.xFrack=100;
s.yFrack=100;
s.frackL=0;
s.frackT=0;
s.OilPaintOn=0;
s.OilPaintRadius=4;
s.OilPaintIntensity=55;
s.PosterizeOn=0;
s.PosterizeRadius=64;
s.PosterizeIntensity=64;
s.FultersOn=0;
s.fultersList="";
s.xSkew=0;
s.ySkew=0;
s.MaskOn=0;
s.maskType=1;
s.maskDirection=0;
s.maskStart=40;
s.maskBlur=20;
s.maskRed	=0;
s.maskGreen	=0;
s.maskBlue	=0;
s.maskSolid	=0;
s.maskSolidAlpha=128;
s.SwapPixelsOn	=0;
s.swapPixelsRgb ="R";
s.SwapCsOn	=0;
s.SwapCsList="#ff3333,#ff9933,255,100";
s.SwapCsCurrent =0;
s.FillColorOn=0;
s.FillColorX=10;
s.FillColorY=10;
s.FillColorClr="#ff9977";
s.FillColorAlpha=255;
s.FillColorTolerance=15;
s.ColorRgbOn	=0;
s.colorRgbRed	=50;
s.colorRgbGreen	=0;
s.colorRgbBlue	=-50;
s.opacity1=1;
s.opacity2=1;
s.shadows="1 0px 0px 280px 60px rgba(104,214,255,1)";
s.shadowsOn=0;
s.cornersApplyTo=3;
s.cornerRadius="0%";
s.clipRadius="0%";
s.frameColor=gDefaultFrameColor;    
s.gradient="";
s.gradientType="flat";
s.hideImage=0;
s.selectmode=0;
s.bigMirror=0;
s.playing=0;
//s.pauseDelay=gPic.pauseDelay;
//s.dopple=0;
s.zindex=gPic.zindex+(gPic.screens.length*100);
s.perspectiveFrm=gDefaultPerspective;
s.perspectiveImg=gDefaultPerspective;
s.perspectivePnl=gDefaultPerspective;
}

Config.prototype.set=function(n,v){this[n]=v;}

//----------------- create a screen config ------------------------------------- 
Picture.prototype.grabConfig=function(s,chgsonly){
// NB: New variables added here must be also be added to CopyScreen() and Config()
if(s==null)s=this.screen;
var x="";
x+=
";s.media='"+s.media+"'"+
";s.typ='"+s.typ+"'"+
";s.lockTyp='"+s.lockTyp+"'"+
";s.lockPosition="+s.lockPosition+
";s.resizeChildren="+s.resizeChildren+
";s.offsetWarp="+s.offsetWarp+
";s.fullWarp="+s.fullWarp+
";s.offsetSplit="+s.offsetSplit+
";s.fullSplit="+s.fullSplit+
";s.childix="+s.childix+
";s.cssfilters='"+s.cssfilters+"'"+
";s.useCanvas="+s.useCanvas+
";s.gDir='"+s.gDir+"'"+
";s.hideImage="+s.hideImage+
";s.bigMirror="+s.bigMirror+
";s.xFold="+s.xFold+
";s.yFold="+s.yFold+
";s.xStretch="+s.xStretch+
";s.yStretch="+s.yStretch+
";s.xScale="+s.xScale+
";s.yScale="+s.yScale+
";s.xZoom="+s.xZoom+
";s.yZoom="+s.yZoom+
";s.xSlide="+s.xSlide+
";s.ySlide="+s.ySlide+
";s.xCrop="+s.xCrop+
";s.yCrop="+s.yCrop+
";s.lCrop="+s.lCrop+
";s.tCrop="+s.tCrop+
";s.xSize="+s.xSize+
";s.ySize="+s.ySize+
";s.xMove="+s.xMove+
";s.yMove="+s.yMove+
";s.xWarp="+s.xWarp+
";s.yWarp="+s.yWarp+
";s.xSplit="+s.xSplit+
";s.ySplit="+s.ySplit+
";s.xImage="+s.xImage+
";s.yImage="+s.yImage+
";s.xFrack="+s.xFrack+
";s.yFrack="+s.yFrack+
";s.frackL="+s.frackL+
";s.frackT="+s.frackT+
";s.OilPaintOn="+s.OilPaintOn+
";s.OilPaintRadius="+s.OilPaintRadius+
";s.OilPaintIntensity="+s.OilPaintIntensity+
";s.PosterizeOn="+s.PosterizeOn+
";s.PosterizeRadius="+s.PosterizeRadius+
";s.PosterizeIntensity="+s.PosterizeIntensity+
";s.FultersOn="+s.FultersOn+
";s.fultersList='"+s.fultersList+"'"+
";s.xSkew="+s.xSkew+
";s.ySkew="+s.ySkew+
";s.MaskOn="+s.MaskOn+
";s.maskType="+s.maskType+
";s.maskDirection="+s.maskDirection+
";s.maskStart="+s.maskStart+
";s.maskBlur="+s.maskBlur+
";s.maskRed="+s.maskRed+
";s.maskGreen="+s.maskGreen+
";s.maskBlue="+s.maskBlue+
";s.SwapPixelsOn="+s.SwapPixelsOn+
";s.swapPixelsRgb='"+s.swapPixelsRgb+"'"+
";s.SwapCsOn="+s.SwapCsOn+
";s.SwapCsList='"+s.SwapCsList+"'"+
";s.SwapCsCurrent="+s.SwapCsCurrent+
";s.FillColorOn="+s.FillColorOn+
";s.FillColorX="+s.FillColorX+
";s.FillColorY="+s.FillColorY+
";s.FillColorClr='"+s.FillColorClr+"'"+
";s.FillColorAlpha="+s.FillColorAlpha+
";s.FillColorTolerance="+s.FillColorTolerance+
";s.ColorRgbOn="+s.ColorRgbOn+
";s.colorRgbRed="+s.colorRgbRed+
";s.colorRgbGreen="+s.colorRgbGreen+
";s.colorRgbBlue="+s.colorRgbBlue+
";s.maskSolid="+s.maskSolid+
";s.maskSolidAlpha="+s.maskSolidAlpha+
";s.xxx="+s.xxx+
";s.yyy="+s.yyy+
";s.maxx="+s.maxx+
";s.maxy="+s.maxy+
";s.vx="+s.vx+
";s.vy="+s.vy+
";s.opacity1="+s.opacity1+
";s.opacity2="+s.opacity2+
";s.shadows='"+s.shadows+"'"+
";s.shadowsOn="+s.shadowsOn+
";s.cornersApplyTo="+s.cornersApplyTo+
";s.cornerRadius='"+s.cornerRadius+"'"+
";s.clipRadius='"+s.clipRadius+"'"+
";s.frameColor='"+s.frameColor+"'"+
";s.gradient='"+s.gradient+"'"+
";s.gradientType='"+s.gradientType+"'"+
";s.xSizeSave="+s.xSizeSave+
";s.ySizeSave="+s.ySizeSave+
";s.xMoveSave="+s.xMoveSave+
";s.yMoveSave="+s.yMoveSave+
";s.natural="+s.natural+
";s.square="+s.square+
";s.multipleImages="+s.multipleImages+
";s.scrambleImages="+s.scrambleImages+
";s.applyDominantColor="+s.applyDominantColor+
";s.applyVignetteColor="+s.applyVignetteColor+
";s.blend="+s.blend+
";s.mirrorChildren="+s.mirrorChildren+
";s.playing="+s.playing+
//";s.pauseDelay="+s.pauseDelay+
//";s.dopple="+s.dopple+
";s.perspectiveFrm='"+s.perspectiveFrm+"'"+
";s.perspectiveImg='"+s.perspectiveImg+"'"+
";s.perspectivePnl='"+s.perspectivePnl+"'"+
//tiling is actually handled quite well by saveDoneStuff() (but not perfect!... it doesn't pick up .src image chnages??)
";s.Tiling='"+s.Tiling+"'"+		
";s.tileType='"+s.tileType+"'"+
";s.maxzx="+s.maxzx+
";s.maxzy="+s.maxzy+
";s.maxvx="+s.maxvx+
";s.maxvy="+s.maxvy+
//---- Variables after this are not implicitly picked up by saveDoneStuff().
//---- We can either handle them explicitly or just ignore them if they don't play nice!
";s.imgix="+s.imgix+
";s.zindex="+s.zindex+
";s.src='"+((s.media=="video")?"@vid@":"")+s.src+"'"+  
";";
//---images
//if(!chgsonly){
// x+="s.images[0]=s.src='"+s.src+"';s.imgix=0;";
 //if(s.playing){
 // for(var i=0;i<s.images.length;i++)x+="s.images["+i+"]='"+s.images[i]+"';";
 // x+="s.images["+s.imgix+"]='"+s.src+"';";  //just in case (it sometimes seems to get srewed up!)
 //}else{
//}
return x;
}


//------------------- save pic config ------------------------------------------  
Picture.prototype.savePicConfig=function(takepic,scrix,name){
if(takepic)name="_lastpic";
else if(!name)name=this.configName;
var x='',locks='',anis='',c;
var gofullscreen=(takepic && scrix!=null && !gPic.screen.isFullscreen())? 1 : 0 ;
//if(gofullscreen)gPic.screen.goFullscreen(1);
//---- remove deleted screens ----
gPic.fixIXnbrs();
//---- sort screens in zindex order ----
var a=new Array();
for(var i=0;i<gPic.screens.length;i++){
	var scr=gPic.screens[i];
	a[i]=scr.zindex+"-"+scr.scrix; 
}
a=a.sort();
a=a.reverse();
b=new Array();
for(var i=0; i<a.length; i++){
	var ix=a[i].split("-")[1]*1;
	var scr=gPic.screens[ix];
	b[i]=scr;
}
gPic.screens=b;
//---- process each screen ----
for(var i=0; i<this.screens.length;i++){
	var scr=this.screens[i];
	//--- do we want to include this screen? ---
	if(scrix==null || scr.scrix==scrix){  
		//msg("scr["+i+"].z="+scr.zindex);
		//--- var shortcuts ---
		anis+="var s=gPic.screens["+i+"];";
		anis+="var h=s.history;";
		//--- get a compressed config ---
		var tmp=gPic.conciseConfig(scr);
		//msg("concise for ix "+scr.scrix+" = "+tmp);
		x+=gPic.conciseConfig(scr);
		x+="|||";
		locks+=this.saveLocks(scr);
		//---- history ---
		//---- we don't want to always save history with a config but what if we added an 'action'?
		/*
		//if(gShowactions=="yes"){
			var hist=scr.history.getTxt();
			anis+=' h.loadTxt(@###@ '+hist+' @###@);'; // @###@ = double quotes
			anis+="h.loadHistoryAll(); ";
			if(scr.history.playing)anis+="h.StartStop(); ";							//plays HISTORY/ACTIONS
			if(scr.playing)anis+=" gPic.changeScreen("+i+"); gToolbar.playPause(); ";
		}
		*/
		//---- history ----
		if(scr.history.playing){
			var hist=scr.history.getTxt();
			anis+=' h.loadTxt(@###@ '+hist+' @###@);'; // @###@ = double quotes
			anis+="h.loadHistoryAll(); ";
			anis+="h.StartStop(); ";							//plays HISTORY/ACTIONS
		}
		//---- animation ---
		if(scr.animation.playing){
			anis+=" s.animation.startstop(1); ";				//plays ANIMATION
		}
	}
}
x+="{{{"+locks+anis;
x+=" gPic.configName='"+name+"'; ";
//x+=" gPic.ResizeChildren="+gPic.ResizeChildren+"; ";
x+=" gPic.xmode='"+gPic.xmode+"'; ";
x+=" gPic.viewDesktopColor('"+gPic.desktopColor+"'); ";
x+=" gToolbar.showHandles("+gPic.handles+"); ";
x+=" api_flipThumbs("+api_thumbsOpen()+"); ";

//--- view vars ---
x+=" gPic.desktopColor='"+gPic.desktopColor+"'; ";
x+=" gPic.gradient='"+gPic.gradient+"'; ";
x+=" gPic.gradientType='"+gPic.gradientType+"'; ";
x+=" gPic.xViewZoom="+gPic.xViewZoom+"; ";
x+=" gPic.xViewSlide="+gPic.xViewSlide+"; ";
x+=" gPic.yViewSlide="+gPic.yViewSlide+"; ";
x+=" gPic.blend="+gPic.blend+"; ";
x+=" gPic.opacity2="+gPic.opacity2+"; ";
x+=" gPic.cssfilters='"+gPic.cssfilters+"'; ";
x+=" gPic.perspective='"+gPic.perspective+"'; ";
x+=" gPic.pauseDelay="+gPic.pauseDelay+"; ";
x+=" gPic.dopple="+gPic.dopple+"; ";

x+=" gPic.scrix="+gPic.scrix+"; ";
x+=" gPic.screen=gPic.screens[gPic.scrix];";    
gToolbar.saveConfig(name,x);
gToolbar.setConfigTitle(takepic);
return name;
}


//------------------- get a concise config ------------------------------------- 
//--- this eliminates default settings...

//Picture.prototype.grabConfig=function(s,chgsonly){

Picture.prototype.conciseConfig=function(s,gx,sx){
if(s==null)s=this.screen;
var txt="";
if(!gx)gx=this.grabConfig(this.genericConfig);
if(!sx)sx=this.grabConfig(s);
var a=gx.split(";");
var b=sx.split(";");
for(var i=0;i<a.length;i++){
	if(_in(a[i],"nativeTxt")) i=9999;
	else{   
		if(gx){
			if(a[i]!=b[i]) txt+=b[i]+";";  //if this is for 'doneStuff' then only get src if changed
		}else{
			if(a[i]!=b[i] || _in(a[i],".src="))	txt+=b[i]+";";  
}	}	}
return txt;
}


//------------------- grab layer configs ------------------------------------------  
//(called by toolbar.php for a merge)
Picture.prototype.grabPicLayers=function(){
var x="";
//---- process each screen ----
for(var i=0; i<this.screens.length;i++){
 	if(gPic.screens[i].DELETED)continue;
	var scr=this.screens[i];
	x+=gPic.mergeConfig(scr);
	x+="|||";
}
return x;
}

//------------------- get a merge config ------------------------------------- 
Picture.prototype.mergeConfig=function(s){
if(s==null)s=this.screen;
var txt="";
var gx=this.grabConfig(this.genericConfig);
var sx=this.grabConfig(s);
var a=gx.split(";");
var b=sx.split(";");
for(var i=0;i<a.length;i++){
	if(_in(a[i],"nativeTxt")) i=9999;
	else{   
		if(a[i]!=b[i]){
			txt+=b[i]+";";  
}	}	}
return txt;
}

//------------------- save a screen config -------------------------------------
Picture.prototype.saveConfig=function(s){
if(s==null)s=this.screen;
this.tmpConfig=this.grabConfig(s);
s.config=this.tmpConfig;
}


//------------------- save a screen locks -------------------------------------  
Picture.prototype.saveLocks=function(s){
var txt="";
if(s==null)s=this.screen;
for(var j=0;j<s.lockedScreens.length;j++){
	var lsc=s.lockedScreens[j];
	txt+="gPic.screens["+s.scrix+"].lockedScreens["+j+"]=gPic.screens["+lsc.scrix+"]; ";
	txt+="gPic.screens["+lsc.scrix+"].lockedByScreen=gPic.screens["+s.scrix+"]; ";
}
for(var j=0;j<s.childScreens.length;j++){
	var csc=s.childScreens[j];
	txt+="gPic.screens["+s.scrix+"].childScreens["+j+"]=gPic.screens["+csc.scrix+"]; ";
	txt+="gPic.screens["+csc.scrix+"].childByScreen=gPic.screens["+s.scrix+"]; ";
}
return txt;
}



//------------------------- copyscreen() ---------------------------------------
Picture.prototype.copyScreen=function(scr,toScr,typ){
// NB: Changes made here must be also made to Config() and grabConfig()	
if(typ==null)typ="all";	
//msg("copyScreen typ="+typ);

//--- only copy if all ---	
if(typ=="all"){
 toScr.zindex  		=scr.zindex;
 toScr.Tiling   	=scr.Tiling;
 toScr.tileType 	=scr.tileType;
 toScr.tileXY  		=scr.tileXY;
 toScr.xxx     		=scr.xxx;
 toScr.yyy     		=scr.yyy;
 toScr.maxzx   		=scr.maxzx;
 toScr.maxzy   		=scr.maxzy;
 toScr.maxx    		=scr.maxx;
 toScr.maxy    		=scr.maxy;
 toScr.maxvx   		=scr.maxvx;
 toScr.maxvy   		=scr.maxvy;
 toScr.useCanvas	=scr.useCanvas;
 toScr.media    	=scr.media;
 toScr.childix  	=scr.childix;
 toScr.typ			=scr.typ;
 toScr.lockTyp		=scr.lockTyp;
 toScr.lockPosition	=scr.lockPosition;
 toScr.resizeChildren=scr.resizeChildren;
 toScr.offsetWarp	=scr.offsetWarp;
 toScr.fullWarp		=scr.fullWarp;
 toScr.offsetSplit	=scr.offsetSplit;
 toScr.fullSplit	=scr.fullSplit;
 //toScr.pauseDelay   =scr.pauseDelay;
 //toScr.dopple   	=scr.dopple;
}

//------- images ------
if(typ=="all" || typ=="image"){
 toScr.gDir    	=scr.gDir;
 toScr.src     	=scr.src;
 toScr.gImg    	=scr.gImg;
// toScr.images  	=scr.images;
 toScr.imgix   	=scr.imgix;
 toScr.hideImage=scr.hideImage;
 toScr.natural  =scr.natural;
 toScr.applyDominantColor=scr.applyDominantColor;
}

//--- size and position ---
if(typ=="all" || typ=="position"){
 toScr.divL    =scr.divL;
 toScr.divW    =scr.divW;
 toScr.divT    =scr.divT;
 toScr.divH    =scr.divH;
 toScr.frmL    =scr.frmL;
 toScr.frmT    =scr.frmT;
 toScr.frmW    =scr.frmW;
 toScr.frmH    =scr.frmH;
 toScr.square  =scr.square;
 toScr.xSize   =scr.xSize;
 toScr.ySize   =scr.ySize;
 toScr.xMove   =scr.xMove;
 toScr.yMove   =scr.yMove;
 toScr.xMoveSave=scr.xMoveSave;
 toScr.yMoveSave=scr.yMoveSave;
}

//------ perspective ------
if(typ=="all" || typ=="perspective"){
 toScr.perspectiveFrm=scr.perspectiveFrm;
 toScr.perspectiveImg=scr.perspectiveImg;
 toScr.perspectivePnl=scr.perspectivePnl;
 toScr.bigMirror=scr.bigMirror;
}

//------- rotation etc. --------
if(typ=="all" || typ=="rotation"){
 toScr.vx      =scr.vx;
 toScr.vy      =scr.vy;
}

//--- box configuration ---
if(typ=="all" || typ=="config"){
 toScr.xWarp   =scr.xWarp;
 toScr.yWarp   =scr.yWarp;
 toScr.xSplit  =scr.xSplit;
 toScr.ySplit  =scr.ySplit;
 toScr.xImage  =scr.xImage;
 toScr.yImage  =scr.yImage;
 toScr.xFold   =scr.xFold;
 toScr.yFold   =scr.yFold;
 toScr.xStretch=scr.xStretch;
 toScr.yStretch=scr.yStretch;
 toScr.xScale  =scr.xScale;
 toScr.yScale  =scr.yScale;
 toScr.xZoom   =scr.xZoom;
 toScr.yZoom   =scr.yZoom;
 toScr.xCrop   =scr.xCrop;
 toScr.yCrop   =scr.yCrop;
 toScr.lCrop   =scr.lCrop;
 toScr.tCrop   =scr.tCrop;
 toScr.xSlide  =scr.xSlide;
 toScr.ySlide  =scr.ySlide;
 toScr.xSkew   =scr.xSkew;
 toScr.ySkew   =scr.ySkew;
}

//-------- mask --------
if(typ=="all" || typ=="mask"){
 toScr.MaskOn        =scr.MaskOn;
 toScr.maskType      =scr.maskType;
 toScr.maskDirection =scr.maskDirection;
 toScr.maskStart     =scr.maskStart;
 toScr.maskBlur      =scr.maskBlur;
 toScr.maskRed		 =scr.maskRed;
 toScr.maskGreen	 =scr.maskGreen;
 toScr.maskBlue		 =scr.maskBlue;
 toScr.maskSolid	 =scr.maskSolid; 
 toScr.maskSolidAlpha=scr.maskSolidAlpha;
}

//-------- frame --------
if(typ=="all" || typ=="frame"){
 toScr.cssfilters	=scr.cssfilters;
 toScr.SwapPixelsOn	=scr.SwapPixelsOn;
 toScr.swapPixelsRgb=scr.swapPixelsRgb;
 toScr.SwapCsOn		=scr.SwapCsOn;
 toScr.SwapCsList	=scr.SwapCsList;
 toScr.SwapCsCurrent=scr.SwapCsCurrent;
 toScr.FillColorX	=scr.FillColorX;
 toScr.FillColorY	=scr.FillColorY;
 toScr.FillColorClr	=scr.FillColorClr;
 toScr.FillColorAlpha=scr.FillColorAlpha;
 toScr.FillColorTolerance=scr.FillColorTolerance;
 toScr.ColorRgbOn	=scr.ColorRgbOn;
 toScr.colorRgbRed	=scr.colorRgbRed;
 toScr.colorRgbGreen=scr.colorRgbGreen;
 toScr.colorRgbBlue	=scr.colorRgbBlue;
 toScr.opacity1		=scr.opacity1;
 toScr.opacity2		=scr.opacity2;
 toScr.blend		=scr.blend;
 toScr.OilPaintOn		=scr.OilPaintOn;
 toScr.OilPaintRadius	=scr.OilPaintRadius;
 toScr.OilPaintIntensity=scr.OilPaintIntensity;
 toScr.PosterizeOn		=scr.PosterizeOn;
 toScr.PosterizeRadius	=scr.PosterizeRadius;
 toScr.PosterizeIntensity=scr.PosterizeIntensity;
 toScr.FultersOn	=scr.FultersOn;
 toScr.fultersList	=scr.fultersList;
 toScr.mirrorChildren		=scr.mirrorChildren;
 toScr.randomChildren		=scr.randomChildren;
 toScr.applyVignetteColor	=scr.applyVignetteColor;
 toScr.frameColor 	=scr.frameColor;
 toScr.gradient		=scr.gradient;
 toScr.gradientType	=scr.gradientType;
 toScr.shadows		=scr.shadows;
 toScr.shadowsOn	=scr.shadowsOn;
 toScr.cornerRadius	=scr.cornerRadius;
 toScr.cornersApplyTo=scr.cornersApplyTo;
}

//-------- scramble -------
if(typ=="all" || typ=="scramble"){
 toScr.xFrack  =scr.xFrack;
 toScr.yFrack  =scr.yFrack;
 toScr.frackL  =scr.frackL;
 toScr.frackT  =scr.frackT;
 toScr.multipleImages	=scr.multipleImages;
 toScr.scrambleImages	=scr.scrambleImages;
 toScr.clipRadius		=scr.clipRadius;
}

//-------- animation --------
if(typ=="all" || typ=="animation"){
 	toScr.animation.copy(scr.animation,toScr.animation);
	toScr.history.copy(scr.rekord,toScr.rekord);			
}
}


//============================================================================================================================================================
//============================================================================================================================================================
//============================================================================================================================================================
//============================================================================================================================================================
//============================ GLOBAL FUNCTIONS ==============================================================================================================
//============================================================================================================================================================


function asyncP(parms,action){
gToolbar.showBusy(1);
asyncPOST(parms,"_pixi_ajx.php",asyncEval,action);
}

function asyncEval(req){
gToolbar.showBusy(0);
try{_obj("iBusy").style.display="none";}catch(e){}
try{eval(req.responseText);}catch(e){alert("Sorry! Remote call failed");}
}



//----------------------------------- radius -----------------------------
function setRadius(obj,radius){
if(radius==null)radius="0%";
obj.style.borderRadius=radius;
obj.style.mozBorderRadius=radius;
}





