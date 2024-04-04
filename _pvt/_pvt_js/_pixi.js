



//alert("_pixi.js 10");



/*



========= CURRENT TO DO ==============



- login/logout/account btn?

- can you edit a filter?

- codemirror "success!" msg



- does a slideshow save with a view?



- how do we handle different domains?



- help file!



- home page greeting

- first login image

- httpS?





============= BIG OPTIONS! ============



- share??



- Upwork... smart contracts, create NFTs



- animations need work



=============== OTHER =================





- should fixed sizes be by screen and not global? 

- maintain ratio when loading a view on it's own? (except when loading grabzit!)





- re-use deleted screens?



- rewrite options.php - remove all the junk! (and fix global.php and _pvt_template.txt)







========================== 'BIG BUGS!' ============================

- In some tiling configurations "image" breaks the rotations ((eg. 12,12,8,8,4,4)



- Masks sometimes create faint (unmasked) lines between the boxes (see "fine lines" fix in pixi.js)



- HISTORY - animating SIZE and ZOOM is jerky! it looks to be the actual image within the frame that jumps around!



- Using <!DOCTYPE html> screws up sizing, position, and zoom! (this will have to be fixed eventually) 

	(See "quirks mode")





========================= MINOR 'BUGS' =========================

- loadVisibleDivs seems to be broken (as soon as zoom>50) - do we really need it? (turned oof for now)







=========================== OTHER IDEAS =======================

- Orbits vs Rotations??? (Background vs Foreground?)

- NFT creator?





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

scr.frame				container div

gMAXVX, gMAXVY			normally set to 4 (SLIDABLE uses 6)



fullWarp, offsetWarp	these are used to control the effect of the Warp (mainly for when when we are zoomed in)

						fullwarp 	: the entire screen is warped  				(default 1)

						offsetWarp 	: it warps from the center out (sort of!)	(default 0)

						(THEY CURRENTLY BREAK SKEW! Unless set to defaults- would be nice to make it work - nice effects!)

						0,0 = even numbered divisions are fixed, odd nbrs warp

						1,0 = the entire screen is warped

						0,0 = odd numbered divisions are fixed, even nbrs warp

						1,1 = the outer boxes are fixed and the interior is all warped





====== File Extensions ====

.ixi = view

.iki = action

.htm = text

.ixx = filter





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



- to turn off the mouse : scr.frame.style.pointerEvents="none"; 





*/



//------------------------- global variables -----------------------------------

var gToolbar,gPicDiv,gPic,gAniStack=",",gDebug=0;

var gOriginalPicDeleted=0;

var gEmbedded=0;

var gLoaded=0;

var gDefaultFrameColor="";

var gDefaultPerspective="scaleX(1) scaleY(1) rotate(0deg) skew(0deg,0deg) perspective(1045) rotate3d(0,180,0,0deg) origin:0,0,";

//var gDefaultPerspective=""; // this seems to work fine but not sure?

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

document.onmouseup=function(e){ gPic.md=0; gPic.mousemoved=0; }

if(gEmbedded){

	parent.$(_obj("iDragBtn")).draggable({

	 start: gPixi.iDragBtnStart,

	 drag : gPixi.iDragBtnDrag,

	 stop : gPixi.iDragBtnEnd

	});

	parent.$(_obj("iBoxBdrTop")).draggable({

	 start: gPixi.iDragBoxStart,

	 drag : gPixi.iDragBoxDrag,

	 stop : gPixi.iDragBoxEnd

	});

}



//------- toolbar --------

gPicDiv=_obj("picdiv");

gToolbar=new Toolbar(_obj("iMenuControlsDiv"),gPicDiv);

gToolbar.expandAll(0);

//--- create picture ---

try{ parent.hideWinShows(); }catch(e){}

gPic=new Picture(gConfigName,gConfigText,gToolbar,gImgIx,gImages,gDir);

gPic.unDoing=1;

gToolbar.syncing=1;

gPic.setAllPerspectives();

gPic.filterName="Default";

//--- restore pic settings --- (put this in config?)

gPic.lockZindex=get("lockZindex",gPic.lockZindex);  

//---- config toolbar ---

gToolbar.setConfigTitle();

gToolbar.openMenuControls( ((gEmbedded)?1:0) );

setTimeout("gToolbar.changeMode(gPic.mousemode);",1000);

gToolbar.syncing=0;

gPic.setFixRatios(0);

gPic.repaintAllSizes();

gPic.repaintAll();

gPic.viewApplySettings();

gToolbar.viewImage();

if(gEmbedded)CP=new cpColorPicker(); 

window.focus();

gLoaded=1;

gPic.unDoing=0;	

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









//--- drag box handles functions ---

function iDragBoxDrag(event){

var scr=gPic.screen;

var x=(event.pageX-gToolbar.mdpagex);

var y=(event.pageY-gToolbar.mdpagey);

var xp=((x/eleWidth(gPic.frame)*100)*100)/100;

var yp=((y/eleHeight(gPic.frame)*100)*100)/100;

xp=_round(xp,2);

yp=_round(yp,2);

scr.xMove=gToolbar.mdxMove+xp; 

scr.yMove=gToolbar.mdyMove+yp;

gFrmL=scr.frmL;

gFrmT=scr.frmT;

gPic.placeScreen(scr);

}



function iDragBoxStart(){

gPic.unDoing=1;

gPic.DragStart();

}

function iDragBoxEnd(){

gPic.unDoing=0;

gPic.saveDoneStuff();

gPic.DragEnd();

}



function iDragBoxMouseDown(e){

var scr=gPic.screen;

gToolbar.mdpagex=e.pageX;

gToolbar.mdpagey=e.pageY;

gToolbar.mdxMove=scr.xMove;

gToolbar.mdyMove=scr.yMove;

}





//----------------- drag btn functions (resize btn) ---

function iDragBtnDrag(event){

var x=(event.pageX-gToolbar.mdpagex);

var y=(event.pageY-gToolbar.mdpagey);

var scr=gPic.screen;

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

scr.setVars("size",xsize,ysize);

gPic.repaintSizes(scr.scrix);

}







function iDragBtnMouseDown(e){

var scr=gPic.screen;

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

//gPic.DragStart();

}

function iDragBtnEnd(){

gPic.unDoing=0;

gPic.saveDoneStuff();

//gPic.DragEnd();

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

if(configname=="")configname="Default";

this.configName	=configname;

this.hisName	="Replay";

this.aniName	="Autoplay";

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

this.d3Mode		="window";

if(dir==null)dir=gDir;

this.gDir		=dir;

this.showBorders=0;

this.showNumbers=0;

this.lockTiledImage=1;

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

this.fixRatios		=1;		

//this.docWidth		=0;

//this.docHeight	=0;

this.toolbar.viewSyncControls();

var a=configtext.split("{{{");

if(a[0]=="")a[0]="|||";

//---- apply config -----

var b=a[0].split("|||");

var ix=this.imgix;

for(var i=0;i<b.length-1;i++){

 	if(b[i]){

  		cfig=new Config(b[i]);

 	}else{

  		cfig=new Config(null,null,null,null,null,this.gDir,this.images,ix);

  		ix++;

  		if(ix>this.images.length-1)ix=0;

 	}

 	this.addNewScreen(cfig);

 	var scr=this.screen;

 	this.events(scr);

	this.calcLTWH(this.scrix);

 	scr.loadImage(scr.src);

}

if(a[1]){

	var tmp=_rep(a[1],'@###@','"');

 	eval(tmp);  //saved animations, desktopcolor, etc.

}else{

 	gPic.viewDesktopColor();

}

//--- create skeleton config ---

this.genericConfig=new Config();

}





//=========================== SCREEN CREATION ==================================





//----------------- add a new screen -------------------------------------------

Picture.prototype.addScreen=function(typ,media,dir){

var oldscr	 =this.screen;

var oldzindex=this.screen.zindex;

if(typ==null)typ="";

if(media==null)media="image";

if(dir==null)dir=parent.gMenu.gDir;

var images      =gPic.images;  

var imgix       =Math.floor((Math.random()*(images.length-1))+1);

var tiletype    =this.getTileType(typ); 

var tiling      =null;

cfig=new Config("",tiletype,tiling,typ,media,dir,images,imgix); 

this.addNewScreen(cfig);

var scr=this.screen;

scr.shadowsOn	=0;

scr.frameColor	=scr.frame.style.backgroundColor	="transparent";

this.setZindex(scr,oldzindex+1);

switch(typ){

	/*

case "fade":

	 scr.hideImage=1;

	 scr.shadowsOn=1;

	 scr.shadows="1 0px 0px 50px 30px rgba(255,255,255,1) inset";

	 //gToolbar.expandFrameTools(1);

	 gToolbar.expandFrameTools(1);

	 break;

	*/

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

this.changeScreen(scr.scrix,oldscr.scrix);

return;

}







//-------------------------- addNewScreen --------------------------------------

//This is used to (re)create a screen using cfig

Picture.prototype.addNewScreen=function(cfig){

var frame=gPic.frame ;

var ix=this.screens.length;

gPic.scrix=ix;

var div=_newObj("DIV");

_addObj(frame,div);

div.id=this.nextID();

div.className="framediv";

var scr=new Screen(ix,gPic,div,cfig);

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

Picture.prototype.getTileType=function(typ){

if(!typ)typ="";

switch(typ){

	case "mask"  	:

	case "blend" 	:		

		return "Simple"; 

}

return "Default";

}





//==========================Recordings ==============================



Picture.prototype.savePicReplay=function(name){

if(!name)name=this.hisName;

var x=this.screen.history.getTxt();

gToolbar.saveReplay(name,x);

return name;

}



Picture.prototype.savePicAutoplay=function(name){

if(!name)name=this.aniName;

var x=this.screen.animation.getTxt();

gToolbar.saveAutoplay(name,x);

return name;

}



//----------------------- load recording ----------------------------

Picture.prototype.loadRekord=function(name,txt){

if(_in(txt,"History")){

	//--- replay ----

	if(!name)name=this.hisName;

	else{

		this.hisName=name;

		this.screen.history.loadTxt(txt);		// do we need to do this if hisName=name?

	}

	//msg("loopmode="+this.screen.history.loopmode);

	gToolbar.syncHistory();

	if(!this.rekordLoadOnly)this.screen.history.StartStop();

	this.rekordLoadOnly=0;

}else{

	//--- autoplay ----

	var x=" var a=gPic.screen.animation; "+txt;

	//msg("autoplayTxt="+x);

	eval(x);

	gToolbar.syncAnimations();

	if(!this.rekordLoadOnly)this.screen.animation.StartStop(1);

	this.rekordLoadOnly=0;

}

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

this.calcLTWH(ix);

var scr=gPic.screens[ix];

if(scr.DELETED)return;

scr.repaint();	

}





//------------- repaintAllSizes -------------

Picture.prototype.repaintAllSizes=function(){

for(var i=0;i<this.screens.length;i++){

	var scr=this.screens[i];

	if(scr.DELETED)continue;

	this.calcLTWH(i);

	scr.calcSpecs();

	scr.paint();

	this.cancelDoneStuff(scr);

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

if(scr.xPixels && (scr.xSize!=100 || scr.ySize!=100)){

	scr.frmW=scr.xPixels;

	scr.frmH=scr.yPixels;

}else{

	scr.frmW=w;

	scr.frmH=h;

}

scr.frmL=l;

scr.frmT=t;

}



	



//----------- place Screen ----------------

Picture.prototype.placeScreen=function(scr,copy){

if(!copy)gPic.calcLTWH(scr.scrix);

var div			=scr.frame;

var chgL		=scr.frmL-getLeft(div);

var chgT		=scr.frmT-getTop(div);

div.style.left	=scr.frmL;

div.style.top	=scr.frmT;

if(!copy){

	scr.history.recordAction("s.SV('move',"+scr.xMove+","+scr.yMove+");");   

	gToolbar.syncHandles();

 	gPic.saveDoneStuff();

}}







//---------- getTarget -----------------------

Picture.prototype.getTarget=function(scr,nm){

if(scr==null)scr=this.screen;

return scr;

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





//--------- Fixed Ratios  ---------

Picture.prototype.setFixRatios=function(repaintsizes){ 	

if(gToolbar.syncing || !gPic.fixRatios)return;

//msg("Fixing Ratios");

for(var i=0;i<this.screens.length;i++){

	var scr=this.screens[i];

	if(scr.DELETED)continue;

	if(scr.xSize==100 && scr.ySize==100)continue;

	var toolboxWidth=(gToolbar.menucontrols.style.display=="block")?gToolbar.menucontrols.offsetWidth:0;

	var iw=eleWidth(scr.frame);	

	var ih=eleHeight(scr.frame);

	if(iw==0||ih==0)return;

	var dw=((docWidth())*1)-toolboxWidth; if(dw<10)dw=10;

	var dh=(docHeight())*1;

	var xsize=((iw/dw)*100);

	var ysize=((ih/dh)*100);

	scr.setVars("size",xsize,ysize);   

	if(repaintsizes)this.repaintSizes(scr.scrix);

}}





//--------- Frame Width (used by _takepic.php)  ---------

Picture.prototype.frameWidth=function(){ 	

	var toolboxWidth=(gToolbar.menucontrols.style.display=="block")?gToolbar.menucontrols.offsetWidth:0;

	var dw=((docWidth())*1)-toolboxWidth; 	if(dw<10)dw=10;

	var dh=(docHeight())*1; 				if(dh<10)dh=10;

	return [dw,dh];

}





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





//------- set the actual background color ---------	

Picture.prototype.viewDesktopColor=function(c){

if(c==null) c=gPic.desktopColor; 

else		gPic.desktopColor=c;

if(c=="transparent"){  //"blending bug"

	if(gPic.anyBlending())c=gPic.desktopColor;

	if(c=="transparent")c="#ffffff";

}

if(c!=gPic.lastDesktopColor && gPic.lastDesktopColor && !gPic.unDoing){

	//msg("gPic.viewDesktopColor('"+gPic.lastDesktopColor+"');gPic.viewDesktopColor('"+c+"');");

	gPic.saveDoneStuff("gPic.viewDesktopColor('"+gPic.lastDesktopColor+"');gPic.viewDesktopColor('"+c+"');");

}	

gPic.desktop.style.background	=c;

gPic.frame.style.background		=c;	

}





//-----------View gradient ---------------

Picture.prototype.viewSetGradient=function(str,typ,position,color1,color2,pct1,pct2){

var oldType=this.gradientType;

var oldGradient=(this.oldGradient==null)?this.gradient : this.oldGradient;

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

if((oldGradient!=this.gradient || oldType!=this.gradientType) && !gPic.unDoing){

	gPic.saveDoneStuff("gPic.viewSetGradient('"+oldGradient+"','"+oldType+"');gPic.viewSetGradient('"+this.gradient+"','"+this.gradientType+"');");

	this.oldGradient=null;

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

gPic.undDoing	=1;

var cfig=new Config(this.grabConfig(scr));

gPic.addNewScreen(cfig);

var scr2=gPic.screen;

scr2.gImg=scr.gImg;

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

//---- weird bug! This seems to be unnecessary so ok, but if we call it the old screen is not deleted!

//scr2.loadImage(scr2.gImg.src);	

//----

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





//------ This doesn't fix links etc? -----

Picture.prototype.simpleDeleteScreen=function(scr){

scr.history.stopPlaying();

scr.animation.stopPlaying();

scr.animation=null;

scr.history=null;

scr.frame.style.display='none'; 

scr.frame.innerHTML="";

scr.frame.id="";

}





//---------------- deleteScreen ---------------------------

//----- NB: Only use this to 'delete' a screen  -----------

//---------------------------------------------------------

Picture.prototype.deleteScreen=function(ix){

var scr=gPic.screens[ix];

if(!scr)return;  

//--- mark the screen as 'deleted' and hide the frames ----

this.markDeletions(scr); 

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

	

	//var unDoing	=gPic.unDoing;	

	//gPic.unDoing=1;

	//msg("createImgLoad");

	

	//eval("msg(gPic.screens["+ix+"].gImg.src); ");

	//imgLoad unDoing="+gPic.unDoing);');

	

	eval("gPic.screens["+ix+"].setVignetteColor();");

	eval("gPic.screens["+ix+"].setDominantColor();");

	eval("gPic.screens["+ix+"].repaint("+ix+");");

	gPic.toolbar.syncControls();



	//if(!gEmbedded)logTimeTaken("imgLoad",1);

}}





function logTimeTaken(name,popup){

var endTime=Date.now()*1;

var timeTaken=endTime-gStartTimeTaken;

msg(name+"="+timeTaken);

if(popup){

	_obj("topMsg").innerHTML="<center>"+name+"="+timeTaken+"</center>";

	_obj("topMsg").style.display="";

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

//try{

if(gEmbedded){

	parent.$(_obj(x)).resizable({

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

}



Picture.prototype.draggable=function(x,op){

if(op==null)op=0.7;

if(gEmbedded){

	parent.$(_obj(x)).draggable({

	 start : function(event,ui) {

	  gPic.DragStart();

	 },

	 stop : function(event,ui) {

	  gPic.DragEnd();

	 },

	 opacity: op

	});

}

}



Picture.prototype.DragStart=function(){try{_obj("iDragCover").style.display="block";}catch(e){}}

Picture.prototype.DragEnd=function()  {try{_obj("iDragCover").style.display="none"; }catch(e){}}

Picture.prototype.nextID=function()   {return "iScrn"+this.scrix;}

Picture.prototype.getScreenFromID=function(id){return parseInt(id.replace("iScrn",""));}





//-------------------------- frameEevents() -----------------------------------

//--- used for videos etc. ---

Picture.prototype.frameEvents=function(frame,div,scrix){

frame.mousedown=function(e){

 if(scrix!=gPic.scrix){

 	gPic.changeScreen(scrix);

 	try{gPic.toolbar.syncControls();}catch(e){}

 }

}

}





//-------------------------- imageEevents() -----------------------------------

Picture.prototype.imageEvents=function(frame,ele,scrix){

var v,scr,x,y,w,h,xpct,ypct,xmode,ymode,ctrl;

var BottomRightCorner,Top100,Top50;

//msg("1. imageEvents scrix="+scrix+", gPic.scrix="+gPic.scrix+", frame.id="+frame.id);	

//msg("ImageEvents");

	

ele.ondblclick=function(e){

 	scrix=ele.parentElement.id.replace("iScrn","")*1;

	scr=gPic.screens[scrix];

	if(scrix!=gPic.scrix){

		gPic.changeScreen(scrix);

		try{gPic.toolbar.syncControls();}catch(e){}

	}

	if(gEmbedded)gToolbar.goFullscreen();

	

}



	

ele.onmousedown=function(e){

 scrix=ele.parentElement.id.replace("iScrn","")*1;

 //scrix=gPic.screen.scrix; //@@@	

 scr=gPic.screen;

 gPic.MouseDown=1;

 //msg("mouseDown");

	

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

	 if(scrix!=gPic.scrix){

		  gPic.changeScreen(scrix);

		  try{gPic.toolbar.syncControls();}catch(e){}

	 }

 }

 //msg("scrix="+scrix+", gPic.scrix="+gPic.scrix);

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

}

	

ele.onmouseup=function(e){

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

}

	

ele.onmouseleave=function(e){

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

}

	

ele.onmouseover=function(e){

 	 scrix=ele.parentElement.id.replace("iScrn","")*1;

	 if((ctrl || scrix==gPic.scrix) && gPic.md)gPic.md=1;

}

	

ele.onmousemove=function(e){

	if(!gPic.MouseDown)return;

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

	//msg("ctrl="+ctrl+", md="+gPic.md);

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

				gPic.applyVars(scr,"size",xsize,ysize);

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

}

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

if(oldix!=scr.ix){

	this.saveDoScreenSwitch(oldix,scr.ix);

}

this.screen=scr;

this.scrix=scr.scrix;

gToolbar.setPlayPauseBtn(this.playing);

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

	gPic.setPerspective(scr,str,d3mode);	

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

this.tileType="";   

this.tileXY=0;

this.boxes=new Array();

this.boxii=new Array();

//this.images=new Array();

this.hidden=0;

this.animation=new Animation(this);

//this.rekord=new Rekord(this);

this.history=new History(this);

this.visibleDivs=",";

this.incomplete=0;

this.paintedonce=0;

this.applyDominantColor=0;

this.applyVignetteColor=0;

this.blend=0;

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





//------------------- saveDoneStuff --------------

Picture.prototype.saveDoneStuff=function(v){

var scr=gPic.screen;

//console.trace();

//msg(gPic.unDoing+","+scr.undoing);

//msg("--START--"+this.doneIX);

//if(gPic.unDoing)msg("gPic.unDoing="+gPic.unDoing);

//if(scr.undoing)msg("scr.undoing="+scr.undoing);

if(gPic.unDoing==1 || scr.undoing==1)return;

//if(v)msg("V="+v);

//msg("---saveDoneStuff---");

if(!this.doneStuff)this.doneStuff="";

var newImage	=(scr.gImg)?scr.gImg.src : scr.src;

var newConfig	=this.grabConfig(scr);

newConfig		=newConfig.substr(0,newConfig.indexOf(";s.imgix=")+1);

if(!this.doneStuff){

	scr.oldConfig =newConfig;

	scr.oldImage  =newImage;

	//msg("newimg=oldImage");

	this.doneStuff=scr.oldConfig+";";

	this.doneIX=1;

	if(!v){

		gToolbar.syncDoneStuff();

		//msg("FIRST TIME ix="+this.doneIX);

		return;

	}

}

if(v){

	this.doneStuff+=v+";";

	//if(!_in(v,"view") && !_in(v,"View"))scr.oldConfig=newConfig;  //doesn't matter if we don't do this

	scr.oldConfig=newConfig;

	this.doneIX++;

	gToolbar.syncDoneStuff();

	//msg("---DONE-V--"+this.doneIX);

	return;

}

if(scr.oldImage && scr.oldImage!=newImage){

	var tmp="s.src='"+scr.oldImage+"';s.src='"+newImage+"';;";

	this.doneStuff+=tmp;

	this.doneIX++;

	//msg("image change");

	scr.oldImage=newImage;

}

if(scr.oldConfig==newConfig && !v){

	//msg("old=new");

	//msg("---DONE---"+this.doneIX);

	return;

}

if(scr.oldConfig){

    //msg("regular chgs");

	var cmds=this.doneStuff.split(";;");

	if(this.doneIX > cmds.length-2){

		if(v){

			this.doneStuff+=v+";";

		}else{

			var undoChgs=this.conciseConfig(null,newConfig,scr.oldConfig);

			var redoChgs=this.conciseConfig(null,scr.oldConfig,newConfig);

			undoChgs=_rep(undoChgs,"; ",";");

			redoChgs=_rep(redoChgs,"; ",";");

			this.doneStuff+=undoChgs+redoChgs+";";

			//msg("chgs="+undoChgs+redoChgs);

			//console.trace();

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

					var undoChgs=this.conciseConfig(null,newConfig,scr.oldConfig);

					var redoChgs=this.conciseConfig(null,scr.oldConfig,newConfig);

					undoChgs=_rep(undoChgs,"; ",";");

					redoChgs=_rep(redoChgs,"; ",";");

					cmds[i]=undoChgs+redoChgs;

				}

			}else{

				cmds[i]+=";";

			}

			newStuff+=cmds[i]+";";

		}

		this.doneStuff=cmds[0]+";;"+newStuff;

		//msg("replaced chgs");

	}

	this.doneIX++;

}

scr.oldConfig=newConfig;

gToolbar.syncDoneStuff();

//msg("---DONE---"+this.doneStuff);

}







//------------------- cancelDoneStuff --------------

Picture.prototype.cancelDoneStuff=function(scr){

//--- cancel the last action (we update scr.oldConfig without logging changes)

//--- (specifically for window resizes which play havoc with the screen size)

if(!this.doneStuff)this.doneStuff="";

var newConfig	=this.grabConfig(scr);

newConfig		=newConfig.substr(0,newConfig.indexOf(";s.imgix=")+1);

scr.oldConfig 	=newConfig;

}



Picture.prototype.unDone=function(chg){

var doneAry=this.doneStuff.split(";;");

var s=this.screen;

gPic.unDoing=1;

if(chg<0 && this.doneIX < 2)return;

if(chg>0 && this.doneIX > doneAry.length-2)return;

//msg("src="+s.gImg.src+" == "+s.src);

if(s.gImg.src!=s.src){ 

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

				//msg("cmd="+tmp[tmp.length-2]);

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

}else{

	var oldConfig=this.grabConfig(s);

	s.oldConfig=oldConfig.substr(0,oldConfig.indexOf(";s.imgix=")+1);

}

gToolbar.syncControls();

gPic.unDoing=0;

//msg("IX="+this.doneIX);

}





//---- check to see if the change is handled outside of.repaint() -----

Picture.prototype.checkCmds=function(s,cmds){

var done=0;

if(_in(cmds,"gPic.view"))	{ return 1; }//viewZoom,viewBlend,viewSlide,viewCSS,viewOpacity2,viewDesktopColor,etc.

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

frm.width	=this.frmW;

frm.height	=this.frmH;

this.xPixels=0;	

this.yPixels=0;

div.width	=this.divW;

div.height	=this.divH;

div.left=this.divL;

div.top=this.divT;

if(this.hideImage){

	this.frame.style.display="NONE";

	return;	

}else{

	this.frame.style.display="";

}

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

var scr=this;

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







Screen.prototype.hideImg=function(v){

if(v==null)v=!this.hideImage;

this.hideImage=v*1;

for(var i=0;i<this.boxii.length;i++){

 this.boxii[i].div.style.display=(v)?"none":"block";

 this.history.recordAction("s.hideImg("+v+");");

 gPic.saveDoneStuff();

}}









//--------- Natural Size  ---------

Screen.prototype.naturalSize=function(repaintsizes){ 

var scr=this;

if(scr.media!="image" || scr.hideImage)return;

var scr=this;

var toolboxWidth=(gToolbar.menucontrols.style.display=="block")?gToolbar.menucontrols.offsetWidth:0;

var iw=scr.gImg.width;

var ih=scr.gImg.height;

if(iw==0||ih==0)return;

//scr.xSizeSave=scr.xSize;

//scr.ySizeSave=scr.ySize;

var dw=((docWidth())*1)-toolboxWidth; if(dw<10)dw=10;

var dh=(docHeight())*1;

if(iw>ih){

	var ratio=(ih/iw);

	//var tmp=(scr.xSize>95)?90:scr.xSize;

	var tmp=60;

	var iw=(tmp/100)*dw;

	ih=iw*ratio;

}else{

	var ratio=(iw/ih);

	//var tmp=(scr.ySize>95)?90:scr.ySize;

	var tmp=60;

	var ih=(tmp/100)*dh;

	iw=ih*ratio;

}

var xsize=((iw/dw)*100);

var ysize=((ih/dh)*100);

scr.setVars("size",xsize,ysize);  

if(repaintsizes)gPic.repaintSizes(scr.scrix);

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

gToolbar.syncFullscreenBtns();  

}





//-------------------------------- perspective ---------------------------------

Screen.prototype.setPerspective=function(scr,str,d3mode){

// called by screen.paint

if(scr==null)scr=this;

if(d3mode==null)d3mode=gPic.d3Mode;

var oldstr=gPic.getPerspectiveStr(scr,d3mode);

//msg("d3mode="+d3mode);

//msg("str="+str);

if(str==null){

	str=oldstr;

}else{

	switch(d3mode){

		case "window"	: scr.perspectiveFrm=str; break;

		case "image"	: scr.perspectiveImg=str; break;

		case "panels"	: scr.perspectivePnl=str; break;

}	}

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

if(!scr.dontpaint)scr.repaint();

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

var oldType=this.gradientType;

var oldGradient=(this.oldGradient==null)?this.gradient : this.oldGradient;

if(str==null)str=this.gradient;     else this.gradient=str;

if(typ==null)typ=this.gradientType; else this.gradientType=typ;

if(typ=="flat"){

 	 this.setFrameColor(this.frameColor);

	 if(oldType==this.gradientType)return;

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

//msg("oldGrad="+oldGradient);

//msg("newGrad="+str);

if((oldGradient!=this.gradient || oldType!=this.gradientType) && !gPic.unDoing){

	gPic.saveDoneStuff("s.setGradient('"+oldGradient+"','"+oldType+"');s.setGradient('"+this.gradient+"','"+this.gradientType+"');");

	this.oldGradient=null;

}

//this.oldGradient=str;

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

	//console.trace();

	setGradient(this.frame,"flat"); 

	this.frame.style.background="";

	if(v!=this.oldFrameColor && this.oldFrameColor && !gPic.unDoing){

		gPic.saveDoneStuff("s.setFrameColor('"+this.oldFrameColor+"');s.setFrameColor('"+v+"');");

	}

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

}







//------------------------------- corners --------------------------------------  

Screen.prototype.setCornersApplyTo=function(v) {

this.cornersApplyTo=v;

}



Screen.prototype.setCorners=function(radius,applyto) {

//--- applyto: 1=frame, 2=image, 3=both

if(radius==null)radius=this.cornerRadius;

else this.cornerRadius=radius;

if(applyto==null)applyto=this.cornersApplyTo;

else this.cornersApplyTo=applyto;

if(applyto!=2)this.setRadius(this.frame,radius); else this.setRadius(this.frame,"0%");

if(applyto!=1)this.setRadius(this.div,radius); 	 else this.setRadius(this.div,"0%");

}





//----------------------------------- radius -----------------------------

Screen.prototype.setRadius=function(obj,radius){

if(radius==null)radius="0%";

obj.style.borderRadius=radius;

obj.style.mozBorderRadius=radius;

}







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

var scr=this;

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

function Config(configtxt,tiletype,tiling,typ,media,dir,images,imgix){  

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

var	dbData
$.post('https://jdb.ywv.mybluehost.me/optic-cdf26213a150dc3ecb610f18f6b38b46/_pvt/ajax-controller.php', {user: 1}, function(response){ 
	sessionStorage.setItem("dbData", response)
});
dbData = JSON.parse(sessionStorage.getItem("dbData"))
// console.log(dbData.blend)

var s=this;

gPic.applyPattern(s);  				// sets tiling, zoom, fullwarp, offsetwarp, vx, vy, tileType and Tiling

gPic.setTiling(s,s.Tiling);			// sets xxx, yyy, maxzx, maxzy, maxx, maxy, maxvx, maxvy, (validates vx, vy)

//s.images=new Array();

s.imgix=0;

//s.images[0]=s.src=gPic.images[gPic.imgix];;

s.typ="";

s.lockPosition=0;

s.offsetWarp=0;

s.fullWarp=1;

s.offsetSplit=0;

s.fullSplit=1;

s.cssfilters="";	//inherit

s.media="image";

s.useCanvas=gDefaultUseCanvas;

s.gDir=gPic.gDir;

s.fullscreen=0;

s.multipleImages=0;

s.scrambleImages=0;

s.applyDominantColor=0;

s.applyVignetteColor=0;

s.blend=dbData.blend;

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

s.shadows="1 0px 0px 40px 20px rgba(0,0,0,1) inset";

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

s.xPixels=0;

s.yPixels=0;

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

";s.lockPosition="+s.lockPosition+

";s.offsetWarp="+s.offsetWarp+

";s.fullWarp="+s.fullWarp+

";s.offsetSplit="+s.offsetSplit+

";s.fullSplit="+s.fullSplit+

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

";s.multipleImages="+s.multipleImages+

";s.scrambleImages="+s.scrambleImages+

";s.applyDominantColor="+s.applyDominantColor+

";s.applyVignetteColor="+s.applyVignetteColor+

";s.blend="+s.blend+

";s.playing="+s.playing+

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



if(s.constructor.name=="Screen"){

	x+=	"s.xPixels="+eleWidth(s.frame)+";"+	

		"s.yPixels="+eleHeight(s.frame)+";";

}else{

	x+=	"s.xPixels="+s.xPixels+";"+	

		"s.yPixels="+s.yPixels+";";

}



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

var x='',anis='',c;

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

		//--- get a compressed config ---

		x+=gPic.conciseConfig(scr);

		x+="|||";

		anis+="var s=gPic.screens["+i+"];";

		if(scr.history.playing || scr.animation.playing){

			//---- replay -----

			if(scr.history.playing){

				anis+=" var h=s.history;";

				var hist=scr.history.getTxt();

				anis+=' h.loadTxt(@###@ '+hist+' @###@);'; // @###@ = double quotes

				anis+=" h.loadHistoryAll(); ";

				anis+=" h.StartStop(); ";

			}

			//---- autoplay ---

			if(scr.animation.playing){

				anis+=" var a=s.animation;";

				anis+=scr.animation.getTxt();

				anis+=" a.StartStop(1);";

				/*

				anis+=" var ani=s.animation;";

				anis+=" ani.xrangemax="+scr.animation.xrangemax+";";

				anis+=" ani.xrangemin="+scr.animation.xrangemin+";";

				anis+=" ani.yrangemax="+scr.animation.yrangemax+";";

				anis+=" ani.yrangemin="+scr.animation.yrangemin+";";

				anis+=" ani.aniXon="+scr.animation.aniXon+";";

				anis+=" ani.aniYon="+scr.animation.aniYon+";";

				anis+=" ani.delta="+scr.animation.delta+";";

				anis+=" ani.timeout="+scr.animation.timeout+";";

				anis+=" ani.loopmode='"+scr.animation.loopmode+"';";

				anis+=" ani.StartStop(1);";

				*/

			}

		}

	}

}

x+="{{{";

x+=" gPic.configName='"+name+"'; ";

x+=" gPic.xmode='"+gPic.xmode+"'; ";

x+=" gPic.ymode='"+gPic.ymode+"'; ";

x+=" gPic.mousemode='"+gPic.mousemode+"'; ";

x+=" gPic.viewDesktopColor('"+gPic.desktopColor+"'); ";

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

x+=" gPic.fixRatios="+gPic.fixRatios+"; ";

x+=" gPic.scrix="+gPic.scrix+"; ";

x+=" gPic.screen=gPic.screens["+gPic.scrix+"]; ";

x+=" gToolbar.changeMode('"+gPic.mousemode+"'); ";

x+=anis;

gToolbar.saveConfig(name,x);

gToolbar.setConfigTitle(takepic);

return name;

}





//------------------- save filter ------------------------  

Picture.prototype.saveFilter=function(name){

if(!name)name=this.filterName;

var scr=this.screen;

var txt=gPic.conciseConfig(scr);

gToolbar.saveFilter(name,txt);

}





//------------------- get a concise config ------------------------------------- 

//--- this eliminates default settings...

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

			if(a[i]!=b[i]) txt+=b[i]+"; ";  //if this is for 'doneStuff' then only get src if changed

		}else{

			if(a[i]!=b[i] || _in(a[i],".src="))	txt+=b[i]+"; ";  

}	}	}

return txt;

}





//------------ apply a 'filter' (applyFilter) ------------------  

//(called by toolbar.php for a filter)

Picture.prototype.applyConfig=function(){

var txt=parent.gConfigTxt;

var scrtxt=txt.split("s.zindex=")[0];

//msg("apply="+scrtxt);

var s=this.screen;

eval(scrtxt);

gToolbar.syncControls();

s.repaint();

}







//------------------- save a screen config -------------------------------------

Picture.prototype.saveConfig=function(s){

if(s==null)s=this.screen;

this.tmpConfig=this.grabConfig(s);

s.config=this.tmpConfig;

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

 toScr.typ			=scr.typ;

 toScr.lockPosition	=scr.lockPosition;

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

 toScr.applyVignetteColor	=scr.applyVignetteColor;

 toScr.frameColor 	=scr.frameColor;

 toScr.gradient		=scr.gradient;

 toScr.gradientType	=scr.gradientType;

 toScr.shadows		=scr.shadows;

 toScr.shadowsOn	=scr.shadowsOn;

 toScr.cornerRadius	=scr.cornerRadius;

 toScr.cornersApplyTo=scr.cornersApplyTo;

 toScr.xPixels		=scr.xPixels;	

 toScr.yPixels		=scr.yPixels;	

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





//=======================================================================

//============================ GLOBAL FUNCTIONS =========================

//=======================================================================





function asyncP(parms,action){

gToolbar.showBusy(1);

parent.asyncPOST(parms,"_pixi_ajx.php",asyncEval,action);

}



function asyncEval(req){

gToolbar.showBusy(0);

try{_obj("iBusy").style.display="none";}catch(e){}

try{eval(req.responseText);}catch(e){alert("Sorry! Remote call failed");}

}















//==============================================================================

//============================ ANIMATION =======================================

//==============================================================================





//==================== HISTORY/REPLAY OBJECT ====================================



function History(scr){

	this.screen=scr;

	this.scrix=scr.scrix;

	this.arrow=1;

	this.direction=1;

	this.loopmode="oscillate";

	this.smoothing=1;

	this.curve=50;

	this.mergeHistory=0;

	this.timeout=10;

	this.delta=25;

	this.IX=-1;

	this.ix=-1;

	this.HISTORY=new Array();

	this.HISTORYALL=new Array();

	this.ITEMS=new Array();

	//this.history=new Array();		// don't need this anymore!

	this.items=new Array();			// where we store the smoothed path

	this.playing=0; 

	// playing = whether or not the current history is being played 

	//(Note: scr.undoing indicates the current event/change was called by a play() function.

	// i.e. a manual change can be made while playing in which case undoing=0)

}







//========================= play smoothed history ========================



//-------- StartStop --------

History.prototype.StartStop=function(arrow,noprep){

//msg("startstop1");

//msg("======START======"+this.IX);

//msg("SMOOTHED="+this.smoothed);

//msg("startix="+this.IX);

//msg("ITEMS="+this.ITEMS['image']);

if(this.playing){ gPic.unDoing=0; this.stopPlaying(); return; }

gPic.unDoing=1;

this.fwdbck=arrow;

if(!noprep) this.prepPlay();

else		this.playing=1;

if(this.loopmode=="stop")this.IX=0;

this.resmooth=0;

if(arrow==null)arrow=1;

this.originalArrow=arrow;

//msg("STARTING ix="+this.IX);

//msg("merge="+this.mergeHistory);

if(this.mergeHistory)this.playMerged(arrow); 

else this.play(arrow);

this.flashOn=0;

this.playBlinker();

}





//----------------------- stopPlaying -------------------------

History.prototype.stopPlaying=function(){

this.playing=0;

clearTimeout(this.flashing);

if(this.resmooth){

	this.smoothed=0;

	this.resmooth=0;

}

setOpacity(_obj("iHistFwd"),60);

setOpacity(_obj("iHistFwd2"),60);

//setOpacity(_obj("iHistBck"),60);

//setOpacity(parent._obj("iUndoBtn"),60);

//setOpacity(parent._obj("iRedoBtn"),60);

gToolbar.syncControls();

gPic.unDoing=0;

gPic.saveDoneStuff();

}







//--------- prepPlay --------

History.prototype.prepPlay=function(){

if(this.playing){ 		this.stopPlaying(); 	return;	}

//msg("PREPPLAY");

//---------- find all cmds that occur at least twice -----------

// (NOTE: This also puts the cmds into the correct order)

var a=new Array();

var allcmds=cmds=",";

for(var i=0;i<this.HISTORYALL.length;i++){		//--- get a list of cmds that occur at least twice

	var cmd=this.HISTORYALL[i];

	if(_in(allcmds,","+cmd+",") && !_in(cmds,","+cmd+","))cmds+=cmd+",";

	else allcmds+=cmd+",";

}

for(var i=0;i<this.HISTORYALL.length;i++){		//--- create a list of valid cmds in the correct order

	var cmd=this.HISTORYALL[i];

	if(_in(cmds,","+cmd+","))a[a.length]=cmd;

}

this.HISTORY=a.slice();

//---------- smooth? -----------

if(this.smoothing && !this.smoothed)this.smooth();

//if(this.smoothing)this.smooth();

this.playing=1;

}







//--------- play -------------

History.prototype.play=function(arrow,once){

//msg("PLAY ix="+this.IX);

//msg("arrow1="+arrow);

//msg("HIST="+this.HISTORY);

//msg("--------------");

if(!this.playing)return;

if(!once)once=0;

if(this.smoothing && !this.smoothed)this.smooth();

//msg("play() 2 history.length="+this.history.length);

if(arrow==null)arrow=this.arrow;

else this.arrow=arrow;

//msg("arrow2="+arrow);

//msg("smoothing="+this.smoothing);

//=============== retrace? =======================

if(!once && this.smoothing && this.smoothPlayInterrupted){

	this.smoothPlayInterrupted=0;

	if(this.originalArrow==this.smoothArrow){

		msg("---PLAY RETRACE--- ");

		this.playSmoothedPath(this.smoothCmd,this.smoothLastix,this.smoothNewix);

		return;

}	}

//=========== First find the next cmd in history =============

var scr=s=this.screen;

if(this.HISTORY.length==0)return;

var a=this.HISTORY;

//if(this.IX>(a.length-1))this.IX=a.length-1;

var i=this.IX;

var looped=0;

i+=arrow;

//msg("i="+i+", len="+a.length);

//msg("IX+arrow="+i);

switch(this.loopmode){

//		case "retrace" :

//			if(i<0)				{ i=a.length; this.IX=i; this.stopPlaying(); setTimeout("gPic.screens["+this.scrix+"].history.StartStop("+this.originalArrow+",1);",this.timeout); return; }

//			if(i>(a.length-1))	{ i=-1; 	  this.IX=i; this.stopPlaying(); setTimeout("gPic.screens["+this.scrix+"].history.StartStop("+this.originalArrow+",1);",this.timeout); return; }

//			break;

	  	case "oscillate" :

			if(i<0)				{ i=1; 			this.arrow=arrow=1; }

			if(i>(a.length-1))	{ i=a.length-2; this.arrow=arrow=-1;}

			break;

	  	case "retrace" :

			if(i<0)				{ this.IX=0;		this.stopPlaying();		return; 	}

			if(i>(a.length-1))	{ i=a.length-2; 	this.arrow=arrow=-1;}

			break;

	  	case "stop" :

			if(i<0)				{ this.IX=0;			this.stopPlaying();		return; 	}

			if(i>(a.length-1))	{ this.IX=a.length-1;	this.stopPlaying();		return; 	}

			break;

}

//--- set cmd ix ---

this.IX=i;

//msg("new arrow="+arrow);

var cmd=this.HISTORY[this.IX];

//msg("items="+this.items[cmd]);

//============ Next find the cmd's location within in it's own history ======

// Find the nbr of cmds in history up to this point

var cmdix=0;

for(var i=0; i<this.IX; i++){ if(this.HISTORY[i]==cmd)cmdix++; }

cmdix++; // because  0=lastix

var ITMS=this.ITEMS[cmd]

var lastix=ITMS[0];

//msg("ITMS="+ITMS);

//msg("newix="+this.IX);

//msg("lastix="+lastix);

if(lastix==cmdix){ msg("lastix=cmdix setting cmdix=1"); cmdix=1;	}	// some bug causes this

//msg("cmdix="+cmdix);

ITMS[0]=cmdix;

var lastVal=ITMS[lastix];

var newVal=ITMS[cmdix];

/*

if(cmd=="boo"){

	msg("CMD="+cmd);

	msg("IX="+this.IX);

	msg("LASTIX="+lastix);

	msg("NEWIX="+cmdix);

	msg("lastVal="+lastVal);

	msg("newVal="+newVal);

}

*/

//msg("cmd="+cmd);

if(_in(cmd,".")){							// it's not a '.SV' command

	var action=cmd+"("+ITMS[cmdix]+");";

	scr.undoing=1;

	gPic.unDoing=1;

	//msg("eval="+action);

	myEval(action);	

	scr.undoing=0;

	gPic.unDoing=0;

}else{

	var newx=this.getSplitVal(newVal,0);

	var newy=this.getSplitVal(newVal,1);

	if(once || !this.smoothing){

		scr.undoing=1;

		gPic.unDoing=1;

		gPic.applyVars(s,cmd,newx,newy);

		scr.undoing=0;

		gPic.unDoing=0;

	}else{

		//================= play a smoothed path ===================

		// Find the positions of the old and new values in the smoothed items then play the path between them (duplicates?)

		//msg("cmd="+cmd+", items="+this.items[cmd]);

		//msg("items="+this.items[cmd]);

		var a=this.items[cmd];

		var lastix=a[0];

		var newix=0;

		if(lastix==newix){ // some bug causes this!

			msg("do we get here?");

			for(var i=1; i<a.length; i++){ 		// get the FIRST lastVal

				if(a[i]==lastVal){ lastix=i; break; }

			}

		}

		if(lastix==newix){ msg("and here?"); lastix=1;	}  // some bug causes this!

		for(var i=1; i<a.length; i++){ 		// get the LAST newVal

			if(a[i]==newVal)newix=i;

		}

		if(!lastix || !newix) { 	msg("MISSING VALUE : "+cmd+","+lastix+","+newix+", lastVal="+lastVal+", newVal="+newVal); return; }

		//msg("lastix "+lastix+",  newix "+newix+")");

		this.playSmoothedPath(cmd,lastix,newix);

		return;

	}

}

if(!once)setTimeout("gPic.screens["+this.scrix+"].history.play("+arrow+");",this.timeout); 

}





//--------- playSmoothedPath --------	

History.prototype.playSmoothedPath=function(cmd,lastix,targetix){

//msg("playing smoothed");

var arrow=(lastix>targetix)?-1:1;

if(!this.playing){

	this.smoothPlayInterrupted=1;

	this.smoothArrow=arrow;

	this.smoothCmd=cmd;

	this.smoothLastix=lastix;

	this.smoothNewix=targetix;

	return; 

}

var scr=this.screen;

var a=this.items[cmd];

var ix=lastix+arrow;

if(ix<1 || ix>a.length-1)ix=1; // we hit this after smoothing

var newVal=a[ix];

var newx=this.getSplitVal(newVal,0);

var newy=this.getSplitVal(newVal,1);

this.items[cmd][0]=ix;

scr.undoing=1;

gPic.unDoing=1;

gPic.applyVars(scr,cmd,newx,newy);	//!!!!!

scr.undoing=0;

gPic.unDoing=0;

if(ix==targetix){

	setTimeout("gPic.screens["+this.scrix+"].history.play();",this.timeout); 

}else{

	setTimeout("gPic.screens["+this.scrix+"].history.playSmoothedPath('"+cmd+"',"+ix+","+targetix+");",this.timeout); 

}

}







//-------------------------------- playMerged --------------------------------	

History.prototype.playMerged=function(arrow){

// ----- NOTE: We only play .SV cmds in merge mode -----

//msg("playmerged");

if(this.smoothing && !this.smoothed)this.smooth(); 

if(arrow==null)arrow=this.arrow;

var scr=s=this.screen;

if(this.HISTORY.length==0)return;

var a=this.MergeCmds;

//msg("--------------");

//msg("a="+a);

//msg("items="+this.items);



var itms=this.items[a[1]];

var lastix=itms[0];

var ix=lastix+arrow;



//--- option - oscillate or retrace?---- (NOTE: All itms[] should have the same length)

//msg("a="+a);

//msg("1 ix="+ix);

//msg("1 itms="+itms);



switch(this.loopmode){

		case "retrace" :

			if(ix<1)				{ 	ix=itms.length-1;	}

			if(ix>(itms.length-1))	{	ix=1;	}

			break;

	  	case "oscillate" :

			if(ix<1)				{ 	ix=1; 				this.arrow=arrow=1;   looped=1;	}

			if(ix>(itms.length-1))	{   ix=itms.length-1; 	this.arrow=arrow=-1;  looped=1;	}		

			break;

	  	case "stop" :

			if(ix<1)				{ 	itms[0]=1;				this.stopPlaying();		return; }

			if(ix>(itms.length-1))	{  	itms[0]=itms.length-1;	this.stopPlaying();		return; }

			break;

			

}

//msg("2 ix="+ix);

//--- set cmd ix ---

scr.undoing = 1;								// if undoing then we don't record the actions

gPic.unDoing=1;

var actions = ",";

for(var i=1;i<a.length-1;i++){

	var cmd=a[i];

	var itms=this.items[cmd];

	//msg(cmd+":"+itms[ix]);

	itms[0]=ix;

	if(_in(cmd,".")){ //--- non SV ---

		//msg("NON SV="+itms[ix]);

		if(itms[ix]=="?,?")continue;

		var action=cmd+"("+itms[ix]+");";

		myEval(action);	

	}else{

		var newx=this.getSplitVal(itms[ix],0)*1;

		var newy=this.getSplitVal(itms[ix],1)*1;

		actions = actions + gPic.applyVars(scr,cmd,newx,newy,1) + ",";	// "1" stops it from painting

	}

}

//!!!!!

if(_in(actions,",repaint")){

	gPic.repaint(scr.scrix);		 // does not call repaintSizes()

	if(_in(actions,",repaintSizes"))gPic.repaintSizes(scr.scrix);

}else{

	if(_in(actions,",repaintSizes")){ // also calls paint()

		gPic.repaintSizes(scr.scrix);

	}else{

		if(_in(actions,",paint")){

			scr.paint();

}	}	}

scr.undoing=0;

gPic.unDoing=0;

if(this.playing)setTimeout("gPic.screens["+this.scrix+"].history.playMerged("+this.arrow+");",this.timeout); 

}

	







History.prototype.playBlinker=function(){

var ani=this.screen.history;

if(!ani.playing)ani.flashOn=1;

var opacity=(ani.flashOn)?60:20;

ani.flashOn=(ani.flashOn)?0:1;

if(ani.fwdbck==-1){

	//setOpacity(_obj("iHistBck"),opacity);

	//setOpacity(parent._obj("iUndoBtn"),opacity);

}else{

	setOpacity(_obj("iHistFwd"),opacity);

	setOpacity(_obj("iHistFwd2"),opacity);

	//setOpacity(parent._obj("iRedoBtn"),opacity);

}

//if(ani.playing)this.flashing=setTimeout("gPic.screen.history.playBlinker();",300);

if(ani.playing)this.flashing=setTimeout("gPic.screens["+this.scrix+"].history.playBlinker();",300);

}

	



//----------------------- loadHistoryAll -------------------------

History.prototype.loadHistoryAll=function(){

//--- called by a config when it loads because we don't save HISTORYALL ---

this.HISTORYALL=this.HISTORY.slice();

}











//		eg. scr.history.recordAction(v); 	//where v="s.SV('rotate',-1,0"



History.prototype.recordAction=function(v){

if(!gEmbedded)return;

//if(!gLoggedIn)return;

var scr=this.screen;

if(scr.undoing)return;

if(gPic.unDoing)return;

//msg("ACTION: v="+v);  

//console.trace();

if(_in(v,".SV")){

	var cmd=this.getCmd(v);

	if(!this.ITEMS[cmd])this.addSV(this.getInitialSetting(v));

	this.addSV(v);

}else{

	this.add(v);

}}







History.prototype.addSV=function(sv){	

	if(!sv)return;

	//msg("-----------------");

	//msg("ADDSV sv="+sv);		// $$$$

	if(!_in(sv,".SV"))	return;

	sv=sv.replace(";;",";"); 

	if(!_in(sv,";"))sv+=";";

	var scr=this.screen;

	var cmd=this.getCmd(sv);

	//msg("CMD+"+cmd);

	var v=this.getVals(sv);

	var updateHistory=0;

	//--- update the cmd array ---

	if(!this.ITEMS[cmd])	{

		this.ITEMS[cmd]=new Array();

		this.ITEMS[cmd][0]=1;

		this.ITEMS[cmd][1]=v;

		updateHistory=1;	

	}else{

		var ix=this.ITEMS[cmd].length-1;

		if(this.ITEMS[cmd][ix]!=v){

			// Always update HISTORY if the cmd changes

			if(this.HISTORYALL[this.HISTORYALL.length-1]!=cmd || ix==1){		//		the last ACTUAL cmd was different	$$$$

			//if(this.HISTORY[this.HISTORY.length-1]!=cmd || ix==1){

				ix++;

				updateHistory=1;

				this.ITEMS[cmd][ix]=v; 

				this.ITEMS[cmd][0]=ix;

			}else{

				// Add a new entry only if we detect a change in direction, otherwise just overwrite the last

				// (Note: If we just overwrite then we don't add another entry to HISTORY)

				var oldxy1=this.ITEMS[cmd][ix-1];

				var oldxy2=this.ITEMS[cmd][ix];

				var oldxdiff=this.getVal(oldxy2,0)-this.getVal(oldxy1,0);

				var oldydiff=this.getVal(oldxy2,1)-this.getVal(oldxy1,1);

				var newxdiff=this.getVal(v,0)-this.getVal(oldxy2,0);

				var newydiff=this.getVal(v,1)-this.getVal(oldxy2,1);

				var xchg=((newxdiff>=0 && oldxdiff>=0) || (newxdiff<=0 && oldxdiff<=0))?0:1;		// xchg = CHANGE OF DIRECTION (do we need to change x or not)

				var ychg=((newydiff>=0 && oldydiff>=0) || (newydiff<=0 && oldydiff<=0))?0:1;

				

				/* --- THIS DOESN'T WORK --- (trying to eliminate saving a new value for minor direction changes)

				if(newxdiff<0)newxdiff*-1;

				if(newydiff<0)newydiff*-1;

				if(oldxdiff<0)oldxdiff*-1;

				if(oldydiff<0)oldydiff*-1;

				if(xchg && !ychg && newxdiff<3 && oldxdiff<3)xchg=0;					  //  ( 3 = some factor)

				if(ychg && !xchg && newydiff<3 && oldydiff<3)ychg=0;

				*/



				if(xchg || ychg){	ix++;	updateHistory=1;		}

				this.ITEMS[cmd][ix]=v; 

				this.ITEMS[cmd][0]=ix;

				//msg("xchg="+xchg+", ychg="+ychg);

			}

		}

	}

	//msg("updateHistory="+updateHistory);

	if(updateHistory){

		//--- update HISTORY if this is a different or new cmd or the direction of either x or y has changed ----

		this.HISTORYALL[this.HISTORYALL.length]=cmd;

		this.IX=this.HISTORY.length;

		this.HISTORY[this.IX]=cmd;

		//msg("addSv this.IX="+this.IX);

	}

	if(!this.playing){

		gToolbar.syncing=1;

		gToolbar.syncHistory();

		gToolbar.syncing=0;

	}

	if(this.playing)this.resmooth=1;

	else this.smoothed=0;

	this.items[cmd]=this.ITEMS[cmd].slice();

	//this.history=this.HISTORY.slice();

	//this.ix=this.IX;

	//msg("AFTER ADD HISTORY="+this.HISTORY);

	//msg("AFTER ADD history="+this.history);

	//msg("AFTER ADD items["+cmd+"]="+this.items[cmd]);

	

}







History.prototype.getInitialSetting=function(v){	

	var scr=this.screen;

	if(!_in(v,".SV"))return "";

	var f=this.getCmd(v);

	/*

	if(_in(v,"'rotate'")){

		//return tmp="s.SV('rotate',"+scr.vx+","+scr.vy+");";	//doesn't work!

		return "";

	}

	*/

	if(this.cssFilter(v)){

		var vf=gToolbar.getCSSFilter(f);

    	return "s.SV('"+f+"',"+vf+",0,1);";

	}

	if(this.cssPerspective(v)){ 

		var str=gPic.getPerspectiveStr();

		if(f=="skew" || f=="axis" || f=="swivel"){

			var vf=gPic.getCSSPerspective(str,"X"+f)+","+gPic.getCSSPerspective(str,"Y"+f)	;		

		}else{

			var vf=gPic.getCSSPerspective(str,f)+",0";

		}

	    return "s.SV('"+f+"',"+vf+",1);";

	}

	if(_in(v,"'mask'")){

		var tmp="s.maskType="+scr.maskType+"; s.maskDirection="+scr.maskDirection+";";   

		return  tmp+"[0];s.SV('mask',"+scr.maskStart+","+scr.maskEnd+");";

	}

	if(f=="shadows"){  

	    return "s.SV('shadows',"+scr.getShadow("spread")+","+scr.getShadow("blur")+",1);";

	}

	if(f=="corners"){  

    	return "s.SV('corners',"+scr.cornerRadius.replace("%","")+",0);";

	}

	if(f=="clip"){  

    	return "s.SV('clip',"+scr.clipRadius.replace("%","")+",0);";

	}

	if(_in(f,"opacity")){  

		var vf;

		eval("vf=this.screen."+f+";");    //  opacity1 and opacity2

		return "s.SV('"+f+"',"+vf+",0);";

	}

	//only 'slide' uses this i think

	var x, y;

	var tmp=f;

	if(f=="panelskew")tmp="skew";

	var vv=capFirstLetter(tmp);

	eval("x=this.screen.x"+vv+";");    

	eval("y=this.screen.y"+vv+";");    

	return "s.SV('"+f+"',"+x+","+y+",1);";			

}





//-------------------------- add etc. -----------------------------

History.prototype.add=function(chg){	

	if(!chg)return;

	if(_in(chg,".SV")){

		this.addSV(chg);

		return;

	}

	//msg("add() chg="+chg);

	var scr=this.screen;

	var a=chg.split("(");

	var cmd=a[0];

	var v="";

	for(i=1;i<a.length;i++){ if(v)v+="("; v+=a[i]; }

	var b=v.split(")");

	var v="";

	for(i=0;i<b.length-1;i++){ if(v)v+=")"; v+=b[i]; }

	var updateHistory=0;

	//--- update the cmd array ---

	//msg("cmd="+cmd);

	if(!this.ITEMS[cmd]){

		this.ITEMS[cmd]=new Array();

		var ix=1;

		this.ITEMS[cmd][0]=ix;

		this.ITEMS[cmd][1]=v;

		//msg("value="+v);

		updateHistory=0;	// don't add the first cmd if it's not an .SV cmd?

	}else{

		var a=this.ITEMS[cmd];

		if(a[a[0]]!=v || cmd=="s.rotate"){			// eg. left arrow looks the same every time

			var ix=a.length;

			a[0]=ix;

			a[ix]=v;

			this.ITEMS[cmd]=a;

			//msg("value="+v);

			updateHistory=1;

		}

	}

	//msg("done="+done+", cmd="+cmd+", ITEMS="+this.ITEMS[cmd]);

	this.HISTORYALL[this.HISTORYALL.length]=cmd;	// always add non SV cmds to historyall

	if(updateHistory){

		//--- history must always have at least 2 occurrences of each cmd ----

		if(!this.cmdExists(cmd)){

			this.IX=this.HISTORY.length;

			this.HISTORY[this.IX]=cmd;

		}

		//--- update the history array ---

		this.IX=this.HISTORY.length;

		this.HISTORY[this.IX]=cmd;

		if(!this.playing){

			gToolbar.syncing=1;

			gToolbar.syncHistory(); 

			gToolbar.syncing=0;

		}

	}

	if(this.playing){

		this.resmooth=1;

	}else{

		this.smoothed=0;

		this.items[cmd]=this.ITEMS[cmd].slice();

		//this.history=this.HISTORY.slice();

		this.ix=this.IX;

	}

	//msg("add() finished cmd="+cmd+", items="+this.items[cmd]);

	//msg("add() done LEN="+this.HISTORY.length);

}







History.prototype.getVal=function(txt,ix){

var a=txt.split(",");

var v=parseInt(a[ix]*1000)/1000;

return v;

}





//------------------------------------------------

History.prototype.smooth=function(){

//msg("--- SMOOTHING ---");

//msg("HISTORY="+this.HISTORY);



//--------- merge? ----------

if(this.mergeHistory){	

	this.merge();

	return;

}

	

//---------- smooth ---------

var scr=this.screen;

this.smoothPlayInterrupted=0;

var	cmdsdone="";

for(var j=0;j<this.HISTORY.length;j++){

	var cmd=this.HISTORY[j];

	//msg("j="+j+", cmd="+cmd+", len="+this.HISTORY.length);

	var nonSV=( _in(cmd,"."))?1:0;

	if( nonSV)continue;							// we only smooth .SV cmds 

	if( _in(cmdsdone,cmd+",") )continue;		// we only need to smooth each cmd once

	//msg("smoothing---- "+cmd);

	cmdsdone+=cmd+",";

	var b=new Array();

	//msg("ITEMS="+this.ITEMS[cmd]);

	var itms=this.ITEMS[cmd];

	b[0]=itms[0];	

	b[1]=itms[1];

	for(var i=2;i<itms.length;i++){

		var oldx= 	this.getVal(b[b.length-1],0);

		var oldy= 	this.getVal(b[b.length-1],1);

		var newx=	this.getVal(itms[i],0);

		var newy=	this.getVal(itms[i],1);	

   		//msg("oldx="+oldx+", newx="+newx+", oldy="+oldy+", newy="+newy);

		var c=this.getPath(oldx,newx,oldy,newy,cmd,this.curve,this.delta);

		//msg("c="+c);

		for(var k=0;k<c.length;k++)b[b.length]=c[k];

		b[b.length]=itms[i];

	}

	this.items[cmd]=b;

	this.items[cmd][0]=this.items[cmd].length-1;

	//msg("j="+j+" len="+this.HISTORY.length);

	//msg("AFTER smooth NATIVE ITEMS["+cmd+"]="+this.ITEMS[cmd]);

	//msg("after smooth MERGED items["+cmd+"]="+this.items[cmd]);

}

//msg("---- SMOOTHED ----");

this.smoothed=1;

}





History.prototype.getPath=function(lastvx,vx,lastvy,vy,cmdtxt,curve,delta){

var scr=this.screen;

//----------- handle special cases ---------

if(_in(cmdtxt,"opacity")){   // 0 to 1

	lastvx=lastvx*100;

	vx=vx*100;

}

if(_in(cmdtxt,"mask")){   // -3 to 3

	lastvx*=25; 	vx*=25;

	lastvy*=25; 	vy*=25;

}

//------- Have we already calculated this one? ---------------

var path=lastvx+","+vx+","+lastvy+","+vy+","+curve+","+delta;

//msg("path="+path);

/* --- don't usethis when testing ----

if(gPic.PATHS[path]){

	var xy=gPic.PATHS[path];

	xy=this.normalize(cmdtxt,xy);

	//msg("FOUND IT! xy="+xy);

	return xy;

}

*/

//--- no so create ---

var maxx=(lastvx-vx);

var maxy=(lastvy-vy);

if(maxx<0)maxx*=-1;

if(maxy<0)maxy*=-1;

var mx=(maxx>maxy)?maxx:maxy;

var ax=new Array();

var ay=new Array();

//msg("GETPATH: lastvx="+lastvx+", vx="+vx+", lastvy="+lastvy+", vy="+vy+", curve="+curve+", delta="+delta+", cnt="+cnt);

//msg("maxx="+maxx+", maxy="+maxy);

//--- populate the arrays with the longest set of nbrs (mx)  (NOTE: If curve = 50 then every value = 100) ----

ax=scr.calcPcts(ax,curve,delta);  

//--- convert %'s to integers where 100=1  ---

for(var i=0;i<ax.length;i++)ax[i]=ax[i]/100;

//---copy resulting array to ay

ay=ax.slice(); 

//---adjust the values to fit the actual range

var pct=maxx/delta;

for(var i=0;i<ax.length;i++) ax[i]=ax[i]*pct;

var pct=maxy/delta;

for(var i=0;i<ay.length;i++) ay[i]=ay[i]*pct;

// --- make the nbrs accumilative ---

if(vx!=lastvx)	{ for(var i=1;i<ax.length;i++)ax[i]+=ax[i-1]; }

if(lastvx>vx)	{ for(var i=0;i<ax.length;i++)ax[i]*=-1; }

if(vy!=lastvy){ 	for(var i=1;i<ay.length;i++)ay[i]+=ay[i-1]; }

if(lastvy>vy) {		for(var i=0;i<ay.length;i++)ay[i]*=-1; }

//msg("ax="+ax);

//msg("ay="+ay);

//--- convert nbrs into actual values ---

for(var i=0;i<ax.length;i++)ax[i]=_round(ax[i]+lastvx,3);

for(var i=0;i<ay.length;i++)ay[i]=_round(ay[i]+lastvy,3);

//msg("calcPcts 4- ax="+ax);

//msg("calcPcts 4- ay="+ay);

var xy=new Array();

for(var i=0;i<ax.length-1;i++){ 

	xy[i]=ax[i]+","+ay[i];

	//msg("xy["+i+"]="+xy[i]);

}

//---- save for future use ----

gPic.PATHS[path]=xy;

//msg("Saving it");

xy=this.normalize(cmdtxt,xy);

return xy;

}



//----------- handle special cases --------- NOT TESTED YET

History.prototype.normalize=function(cmdtxt,xy){

if(_in(cmdtxt,"opacity")){  

	for(var i=0;i<xy.length;i++){

		var a=xy[i].split(",");

		var x=a[0];

		var y=a[1];

		//msg("xy[i]="+xy[i]+", x="+x+", y="+y);

		xy[i]=""+_round((parseInt(x)/100),2)+","+y;   		

	}

}

if(_in(cmdtxt,"mask") ){  

	for(var i=0;i<xy.length;i++){

		var a=xy[i].split(",");

		var x=parseInt(a[0]);

		var y=parseInt(a[1]);

		//msg("1x="+x);

		x=_round( (x/25)  ,2);

		y=_round( (y/25)  ,2);

		xy[i]=x+","+y;   				

	}

}

return xy;

}





//----------------------------- merge the cmds ------------------------------------

History.prototype.merge=function(){

	//msg("MERGE()");

	//msg("HISTORY="+this.HISTORY);

	var hist=this.HISTORY.slice();

	var svcmds=",";

	var stdcmds=",";

	// find all cmds

	for(var i=0;i<hist.length;i++){

		if( _in(hist[i],".")){ 

			//if(!_in(stdcmds,","+hist[i]+","))stdcmds+=hist[i]+",";  	// we only smooth .SV cmds ???

			//if(!_in(svcmds,","+hist[i]+","))svcmds+=hist[i]+",";

		}else{

			if(!_in(svcmds,","+hist[i]+","))svcmds+=hist[i]+",";

		}

	}

	// find the longest path

	var a=svcmds.split(",");

	this.MergeCmds=a;

	var maxpath=0;

	for(var i=1;i<a.length-1;i++){

		var cmd=a[i];

		if((this.ITEMS[cmd].length-2)>maxpath) maxpath=this.ITEMS[cmd].length-2;

	}

	// --- smooth each path with an equal nbr of entries (maxpath*delta) ----

	var maxv=maxpath*this.delta;

	for(var i=1;i<a.length-1;i++){

		var cmd=a[i];

		var nonSV=( _in(cmd,"."))?1:0;

		var itms=this.ITEMS[cmd];

		var tmpdelta=_round(maxv/(itms.length-2),0);

		var vtot=0;

		var b=new Array();

		b[0]=0;

		b[1]=itms[1];

		//msg("cmd="+cmd);	//!!!!

		//msg("itms="+itms);

		for(var j=2;j<itms.length;j++){

			if( nonSV){ 

				var oldx=oldy=newx=newy=0;

				var tmp=(j<itms.length-1)?tmpdelta : (maxv-vtot);

				vtot+=tmp;

			}else{						// .SV cmds

				var oldx= 	this.getVal(itms[j-1],0);

				var oldy= 	this.getVal(itms[j-1],1);

				var newx=	this.getVal(itms[j],0);

				var newy=	this.getVal(itms[j],1);

				var tmp=(j<itms.length-1)?tmpdelta : (maxv-vtot);

				vtot+=tmp;

			}

   			//msg("oldx="+oldx+", newx="+newx+", oldy="+oldy+", newy="+newy);

			var c=this.getPath(oldx,newx,oldy,newy,cmd,this.curve,tmp);

			for(var k=0;k<c.length;k++){

				b[b.length]=(nonSV)? "?,?" : c[k];

			}

			b[b.length]=itms[j];

		}

		b[0]=b.length-1;

		this.items[cmd]=b;

	}

	this.smoothed=1;

}



//--------------------------- UTIL FUNCTIONS -----------------------

History.prototype.print=function(merged){

	msg("=== "+((merged)?"SMOOTHED" : "NATIVE")+" HISTORY ===");

	msg("HISTORY+"+this.HISTORY);

	var hist=this.HISTORY;

	if(merged){

		msg("Smooth="+this.smoothing+", Merge="+this.mergeHistory);

		msg("Timeout="+this.timeout+", Delta="+this.delta);

	}else{

		msg("IX="+this.IX+", ARW="+this.arrow+", CNT="+hist.length);

	}

	//msg(hist);

	if(merged && this.mergeHistory){

		msg("CMDS="+this.MergeCmds);

		var a=this.MergeCmds.split(",");

	}else{

		var txt=",";

		var x="";

		for(var i=0;i<hist.length;i++){ 					

			x+=hist[i]+"|";

			if(!_in(txt,","+hist[i]+","))txt+=hist[i]+",";

		}

		msg(x);

		var a=txt.split(",");

	}

	msg("-------------------------");

	for(var i=1;i<a.length-1;i++){ 	

		var x="";

		var itms=(merged)? this.items : this.ITEMS;

		for(var j=0;j<itms[a[i]].length;j++) x+=itms[a[i]][j]+"|";

		msg(((merged)?"items[":"ITEMS[")+a[i]+"] CNT="+(itms[a[i]].length-1));

		msg("VALUES="+x);

		msg(" ");

	}

	msg("------HISTORYALL-------");

	msg(this.HISTORYALL);

}



//----------------------------- getTxt -------------------------------

History.prototype.getTxt=function(){

	var txt="";

	var hist=this.HISTORY;

	txt+=" h.smoothing="+this.smoothing+";";

	txt+=" h.mergeHistory="+this.mergeHistory+";";

	txt+=" h.timeout="+this.timeout+";";

	txt+=" h.curve="+this.curve+";";

	txt+=" h.loopmode='"+this.loopmode+"';";

	txt+=" h.delta="+this.delta+";";

	txt+=" h.IX="+this.IX+";";

	txt+=" h.HISTORY=new Array(";

	for(var j=0;j<hist.length;j++){

		txt+="'"+hist[j]+"'";

		if(j<hist.length-1)txt+=",";

	}

	txt+=");";

	var cmds=",";

	for(var i=0;i<hist.length;i++){ 

		if(!_in(cmds,","+hist[i]+","))cmds+=hist[i]+",";

	}

	var a=cmds.split(",");

	for(var i=1;i<a.length-1;i++){ 	

		var cmd=a[i];

		//msg("cmd="+cmd);

		var itms=this.ITEMS[cmd];

		txt+="h.ITEMS['"+cmd+"']=new Array(";

		for(var j=0;j<itms.length;j++){

			var x=itms[j];

			if(j>0 && !_in(x,"'"))x="'"+x+"'";		// loadImage entries already have quotes! 

			txt+=x;

			if(j<itms.length-1)txt+=",";

		}

		txt+=");";

	}

	//msg("getTxt()="+txt);

	return txt;

}





History.prototype.loadTxt=function(txt){

	//msg("loadTxt()= "+txt);

	//msg("before HISTORY="+this.HISTORY);

	var s=this.screen;

	var h=this;

	eval(txt);

	//msg("after HISTORY="+this.HISTORY);

	this.HISTORYALL=this.HISTORY.slice();

	this.smoothed=0;

	this.IX=0;

}





History.prototype.cmdExists=function(cmd){

if(_in(this.HISTORY.toString(),cmd))return 1;

return 0;

}



History.prototype.setSmooth=function(v){

if(v==true)this.smoothed=0;

this.smoothing=(v==true)?1:0;

if(this.smoothed && !this.smoothing)	this.initSmoothedHistory();

}





History.prototype.setMerge=function(v){   

this.mergeHistory=(v==true)?1:0;

if(this.smoothed)this.initSmoothedHistory();

}



History.prototype.initSmoothedHistory=function(){

this.history=this.HISTORY.splice();

this.items=this.ITEMS.splice();

this.smoothed=0;

}





History.prototype.ixPct=function(){

if(this.history.length<1)return 100;

return Math.round((this.IX/this.history.length)*100);

}





History.prototype.prt=function(a){

	if(!a)return "empty";

	var txt="";

	for(var i=0;i<a.length;i++)txt+=a[i]+"|";

	return txt;

}



History.prototype.cssFilter=function(v){

if( _in(v,"'saturate'") || _in(v,"'contrast'") || _in(v,"'blur'") || _in(v,"'hue-rotate'") || _in(v,"'grayscale'") || _in(v,"'sepia'") || _in(v,"'brightness'") )return 1;

return 0;

}





History.prototype.cssPerspective=function(v){

if( _in(v,"'2drot'") || _in(v,"'3drot'") || _in(v,"'swivel'") || _in(v,"'axis'") || _in(v,"'tilt'") || _in(v,"'skew") )return 1;

return 0;

}





History.prototype.getCmdName=function(v){

v=v.toLowerCase();

v=v.substr(1);

return v;

}





History.prototype.getCmd=function(cmd){

var a=cmd.split("'"); 

return a[1];

}



History.prototype.getVals=function(cmd){

var a=cmd.split(","); 

var x=a[1];

var y=a[2].split(")")[0];

return x+","+y;

}



History.prototype.getSplitVal=function(vals,i){

var a=vals.split(",");

if(i<a.length)return a[i];

return 0;

}







History.prototype.reset=function(all){	

	this.arrow=1;

	this.direction=1;

	this.smoothed=0;

	this.stopPlaying();

	this.IX=-1;

	this.HISTORY=new Array();

	this.HISTORYALL=new Array();

	this.ITEMS=new Array();

	this.ix=-1;

	this.history=new Array();

	this.items=new Array();

	gToolbar.syncing=1;

	gToolbar.syncHistory();

	gToolbar.syncing=0;

}



//==============================================================================

//============================ ANIMATION OBJECT ================================

//==============================================================================



function Animation(screen){

this.screen	=screen;

this.scrix	=screen.scrix;

this.speedx	=this.speedy=this.delta=0.2;

this.timeout=0;

this.loopmode="oscillate";

this.anix=this.aniy=50;

this.xrange_min=this.xrangemin=40;

this.yrange_min=this.yrangemin=40;

this.xrange_max=this.xrangemax=60;

this.yrange_max=this.yrangemax=60;		// yrange_min

this.playing=0;

this.aniXon	=1;

this.aniYon	=1;

this.fwdbck	=1;

}





//----- startstop -----

Animation.prototype.StartStop=function(i){

	var scr=this.screen;

	var mode=gPic.mousemode;

	//msg("mode="+mode);

	//if(mode=="Turn" || mode=="Move" || mode=="Size")return;  // see changeMode() in gToolbar

	if(i==null)i=1;

	this.fwdbck=i;

	this.playing=(this.playing)?0:1;

	//---- START PLAYING ----

	if(!this.playing){

		this.stop();

		return;

	}

	this.speedx=this.delta*i;

	this.speedy=this.delta*i;

	if(this.xrangemin>this.xrangemax){

		this.speedx*=-1;

 		this.xrange_min=this.xrangemax; 

 		this.xrange_max=this.xrangemin; 

	}else{

 		this.xrange_min=this.xrangemin; 

 		this.xrange_max=this.xrangemax;

	}

 	if(this.yrangemin>this.yrangemax){

		this.speedy*=-1;

 		this.yrange_min=this.yrangemax; 

 		this.yrange_max=this.yrangemin; 

	}else{

		this.yrange_min=this.yrangemin; 

 		this.yrange_max=this.yrangemax;

	}

	//msg("STARTING ANIMATION mode="+gPic.xmode+"/"+gPic.ymode);

	//msg("xrangemax="+this.xrangemax+", xrangemin="+this.xrangemin);

	//msg("xrange_max="+this.xrange_max+", xrange_min="+this.xrange_min);

	var xdiff=this.xrange_max-this.xrange_min;

	var ydiff=this.yrange_max-this.yrange_min;

	var spdx=this.speedx;

	var spdy=this.speedy;

	//msg("1. speedx="+spdx+", speedy="+spdy+", xdiff="+xdiff+", ydiff="+ydiff);

	

	//--- If x and y are changing by different amts we need to adjust the delta/speed of the slower

	//--- so that it takes the same nbr of chgs to complete one leg.

	if(this.aniXon && this.aniYon){

		if(xdiff!=ydiff){

			if(xdiff>ydiff){

				spdy=_round(spdy*(ydiff/xdiff),3);

			}else{

				spdx=_round(spdx*(xdiff/ydiff),3);

			}

		}

	}

	this.spdxPLUS=(spdx>0)?1:0;

	this.spdyPLUS=(spdy>0)?1:0;

	this.speedx=spdx;

	this.speedy=spdy;

	//msg("2. speedx="+spdx+", speedy="+spdy);

 	switch(gPic.xmode){

	  	case "xWarp"	:

	  	case "xSplit"	:

	  	case "xImage"	:

	  	case "xFold"	:

		case "xZoom"	:

	  	case "xSlide"   :

			eval("this.anix=scr."+gPic.xmode+";this.aniy=scr."+gPic.ymode+";");

			this.relativeAdjustAll(this.xrange_min,this.xrange_max,this.yrange_min,this.yrange_max,this.anix,this.aniy,50,2,98); 

			break;

	  	case "xSize"  : 

			this.anix=scr.xSize;	this.aniy=scr.ySize;   

			this.xrange_max*=1.9;  

			this.yrange_max*=1.9;  

			break;

	  	case "xRotate": 

			this.anix=scr.picture.getPerRotate(scr.perspective,scr);  

			this.xrange_max*=3.6;

			this.yrange_max*=3.6; 

			this.timeout=40; 

			break;

	  	case "xSkew"  :

			this.xrange_min-=50;  

			this.xrange_max-=50;

			this.yrange_min-=50;  

			this.yrange_max-=50;

			eval("this.anix=scr."+gPic.xmode+";this.aniy=scr."+gPic.ymode+";");

			this.relativeAdjustAll(this.xrange_min,this.xrange_max,this.yrange_min,this.yrange_max,this.anix,this.aniy,0,-52,48); 

			break;

			

		case "xTilt"  : 

	   		this.anix=scr.picture.getPerSkewX(scr.perspective,scr);

	   		this.aniy=scr.picture.getPerSkewY(scr.perspective,scr);

	   		this.xrange_min=1; this.xrange_max=360;    this.yrange_min=1; this.yrange_max=360;

			gPic.xmode="xSwivel";

			gPic.ymode="ySwivel";

	   		break;

	 }

 	this.animate(1);

 	this.flashOn=0;

 	this.playBlinker();

}







Animation.prototype.relativeAdjustAll=function(xmin,xmax,ymin,ymax,xactual,yactual,def,MIN,MAX) { 

this.xrange_min=this.relativeAdjust(xmin,xactual,def,MIN,MAX);

this.xrange_max=this.relativeAdjust(xmax,xactual,def,MIN,MAX);

this.yrange_min=this.relativeAdjust(ymin,yactual,def,MIN,MAX);

this.yrange_max=this.relativeAdjust(ymax,yactual,def,MIN,MAX);

}



Animation.prototype.relativeAdjust=function(minmax,actual,def,MIN,MAX) { 

var r=minmax+(actual-def); 

if(r<MIN)return MIN;

if(r>MAX)return MAX;

return r;

}







Animation.prototype.stopPlaying=function() { this.stop(); }



//----- stop -----

Animation.prototype.stop=function() {

this.playing=0;

this.screen.undoing=0;

gPic.unDoing=0;

clearTimeout(this.flashing);

this.flashOn=0;

setOpacity(_obj("iAutoFwd"),60);

setOpacity(parent._obj("iAutoFwd"),60);

gPic.saveDoneStuff();

}



//----- animate -----

Animation.prototype.animate=function(firsttime) {

var scr=this.screen;

//msg("anix="+this.anix+", max="+this.xrange_max+", min="+this.xrange_min+", speedx="+this.speedx+" playing="+this.playing);

if(!this.playing)return;

//--- check to see if we have reached the end of the leg ---

if(this.aniXon){

	this.anix+=this.speedx;

	this.anix=_round(this.anix,2);

	this.anix=this.rotate("x", this.anix);

}

if(this.aniYon){

	this.aniy+=this.speedy;

	this.aniy=_round(this.aniy,2);

	this.aniy=this.rotate("y", this.aniy);

}

if(!this.playing)return;

//msg("new anix="+this.anix+" + speedx="+this.speedx);

//this.anix+=this.speedx;

//this.aniy+=this.speedy;

if(firsttime){

	this.xLoop=this.yLoop=0;

	this.anixStart=this.anix;

	this.aniyStart=this.aniy;

	//msg("START recording.... anix="+this.anix+", aniy="+this.aniy);

}else{

	//--- have we completed one full loop? ----

	if(this.anixStart==this.anix && ((this.spdxPLUS && this.speedx>0) || (!this.spdxPLUS && this.speedx<0)))this.xLoop++;

	if(this.aniyStart==this.aniy && ((this.spdyPLUS && this.speedy>0) || (!this.spdyPLUS && this.speedy<0)))this.yLoop++;

	if( (this.aniXon && this.xLoop>0) || (this.aniYon && this.yLoop>0)){

		//--- stop recording history ---

		scr.undoing=1;

		gPic.unDoing=1;

		if(this.loopmode=="retrace"){

			this.stop();

			return;

		}

	}

}

//msg("anix="+this.anix+", speedx="+this.speedx+", xrange_min="+this.xrange_min+", xrange_max="+this.xrange_max);

//msg("anix2="+this.anix);

var cmd=scr.history.getCmdName(gPic.xmode);

switch(gPic.xmode){

 case "xSkew"   :

		cmd="panelskew";

		break

 case "xTilt"   :	// tilt

 		cmd="swivel";

		break

 case "xRotate"   :	// spin

 		cmd="2drot";

		break

}

//msg("applying "+cmd);		//2drot

//msg("xmode= "+gPic.xmode);	//xRotate

gPic.applyVars(scr,cmd,this.anix,this.aniy);

//if(gPic.xmode!="xSlide" && gPic.xmode!="xSkew" && gPic.xmode!="xSwivel"){

//	this.screen.repaint();

//}

if(!this.playing)return;

	//if(gPic.xmode=="xRotate"){ this.anix+=this.speedx*2; } // default is too slow

var pause=(gPic.xmode=="xRotate" && this.timeout>0)? this.timeout/3 : this.timeout;

setTimeout("gPic.screens["+this.scrix+"].animation.animate();",pause);

}







//----- rotate -----

Animation.prototype.rotate=function(d,v){

	var scr=this.screen;

	switch(d){

		case "x"	:

			if(v<=this.xrange_min)  {

				switch(this.loopmode){

					case "retrace": 

					case "oscillate":	this.speedx*=-1;	return this.xrange_min;

					case "stop":		this.stop();		return this.xrange_min;

				}

			}

			if(v>=this.xrange_max)  {

				//msg("d="+d+", v="+v+", speedx="+this.speedx+", min="+this.xrange_min+", max="+this.xrange_max+", loopmode="+this.loopmode);

				switch(this.loopmode){

					case "retrace":	

					case "oscillate":	this.speedx*=-1;	return this.xrange_max;

					case "stop":		this.stop();		return this.xrange_max;

				}

			}

			break;

		case "y"	:

			if(v<=this.yrange_min)  {

				switch(this.loopmode){

					case "retrace":	

					case "oscillate":	this.speedy*=-1;	return this.yrange_min;

					case "stop":		this.stop();		return this.xrange_min;

				}

			}

			if(v>=this.yrange_max)  {

				switch(this.loopmode){

					case "retrace":	

					case "oscillate":	this.speedy*=-1;	return this.yrange_max;

					case "stop":		this.stop();		return this.xrange_max;

				}

			}

			break;

	}

return v;

}



//---- copy ----

Animation.prototype.copy=function(afrom,ato){

ato.screen   =afrom.screen;

ato.delta    =afrom.delta;

ato.speedx   =afrom.speedx;

ato.speedy   =afrom.speedy;

ato.anix     =afrom.anix;

ato.aniy     =afrom.aniy;

ato.playing  =afrom.playing;

ato.xrangemin =afrom.xrangemin;

ato.xrangemax =afrom.xrangemax;

ato.yrangemin =afrom.yrangemin;

ato.yrangemax =afrom.yrangemax;

ato.timeout  =afrom.timeout;

ato.aniXon=afrom.aniXon;

ato.aniYon=afrom.aniYon;

ato.fwdbck=afrom.fwdbck;

}



Animation.prototype.playBlinker=function(){

var ani=this.screen.animation;

if(!ani.playing)ani.flashOn=1;

var opacity=(ani.flashOn)?60:20;

ani.flashOn=(ani.flashOn)?0:1;

if(ani.fwdbck==-1){

	//setOpacity(_obj("iAutoBck"),opacity);

	setOpacity(parent._obj("iAutoFwd"),opacity);

}else{

	setOpacity(_obj("iAutoFwd"),opacity);

	setOpacity(parent._obj("iAutoFwd"),opacity);

}

if(ani.playing)this.flashing=setTimeout("gPic.screens["+this.scrix+"].animation.playBlinker();",300);

}

	



Animation.prototype.getTxt=function(){

var txt="";

txt+=" a.xrangemax="+this.xrangemax+";";

txt+=" a.xrangemin="+this.xrangemin+";";

txt+=" a.yrangemax="+this.yrangemax+";";

txt+=" a.yrangemin="+this.yrangemin+";";

txt+=" a.aniXon="+this.aniXon+";";

txt+=" a.aniYon="+this.aniYon+";";

txt+=" a.delta="+this.delta+";";

txt+=" a.timeout="+this.timeout+";";

txt+=" a.loopmode='"+this.loopmode+"';";

return txt;

}

