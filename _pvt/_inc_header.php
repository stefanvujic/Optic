<?
/* Copyright ©, 2005, Walter Long */

//================ Default page header, styles, and scripts ====================
// NOTE: 1. It requires that _inc_global.php has been loaded first.
//       2. The <HEAD> still needs to be closed by the calling page.
//==============================================================================


//--- local override for slideshow image selection ---
//if(rqst("selectvar")=="ssframeimage")$bgcolor=$ssbgcolor;

//<link href='http://fonts.googleapis.com/css?family=Nosifer|Aguafina+Script|Tauri|Emilys+Candy|Economica|Cabin+Sketch|Caesar+Dressing|Henny+Penny|Simonetta|Noto+Serif|Snowburst+One|Carrois+Gothic|Patrick+Hand+SC|Waiting+for+the+Sunrise|Tulpen+One|Meie+Script|Monofett|Monoton|Dr+Sugiyama|Risque|Special+Elite|Share+Tech+Mono|Sacramento|Julius+Sans+One|Merienda|Boogaloo|Orbitron|Akronim|Ribeye+Marrow' rel='stylesheet' type='text/css'>

// NOTE: Using <!DOCTYPE html> screws up sizing, position, and zoom! (this will have to be fixed eventually)
?>

<!-- <?=$gPage;?> -->
<HTML lang="en">
<HEAD>
<link href='http://fonts.googleapis.com/css?family=Economica|<?=$fontfamily;?>' rel='stylesheet' type='text/css'>
<script src='_pvt_js/util.js'></script>

<!-- <title><?=$_SERVER["SERVER_NAME"];?></title> -->
<title>Optic</title>
<STYLE type="text/css">
html       {background:<?=$bgcolor?>;  }
body       {background:transparent;color:<?=$fgcolor?>;font-family:<?=$fontfamily;?>;font-size:<?=$fontsize;?>px;margin:0; cursor: default;}
.txthdr    {font-size:22px;letter-spacing:1px;color:<?=$titlecolor?>;}
.listhdr   {font-size:<?=$fontsize;?>px;text-transform:uppercase;letter-spacing:2px;color:<?=$titlecolor?>;}
.hdrlnk    {color:<?=$fgcolor;?>;cursor:pointer;}
.hdrlnk:hover{color:<?=$hicolor;?>;}
.lnk       {font-size:<?=$fontsize;?>px;color:<?=$lkcolor?>;cursor:pointer;padding-left:2px;padding-right:2px;}
.lnk:hover {color:<?=$hicolor?>;}
a          {color:<?=$lkcolor?>;}
a:hover    {color:<?=$hicolor?>;}
.modebtn   {color:<?=$btncolor;?>;background:<?=$btnbgcolor;?>;overflow:hidden;padding-left:2px;width:51px;height:21px;}
.sqbtn     {height:<?=$btnh;?>px;border:solid 1px <?=$btnbordercolor;?>;color:<?=$btncolor;?>;background:<?=$btnbgcolor;?>;overflow:hidden;text-align:center;padding-left:2px;padding-right:3px;text-decoration:none;cursor:pointer;}
.sqbtnoff  {height:<?=$btnh;?>px;border:solid 1px <?=$btnbdroff;?>;color:<?=$btncoloroff;?>;background:<?=$btnbgoff;?>;    overflow:hidden;text-align:center;padding-left:2px;padding-right:3px;text-decoration:none;}
.btn       {height:<?=$btnh;?>px;border:solid 1px <?=$btnbordercolor;?>;color:<?=$btncolor;?>;background:<?=$btnbgcolor;?>;overflow:hidden;text-align:center;padding-left:2px;padding-right:3px;text-decoration:none;<?=$roundedcorners;?>;cursor:pointer;}
.btnoff    {height:<?=$btnh;?>px;border:solid 1px <?=$btnbdroff;?>;color:<?=$btncoloroff;?>;background:<?=$btnbgoff;?>;    overflow:hidden;text-align:center;padding-left:2px;padding-right:3px;text-decoration:none;<?=$roundedcorners;?>;}
.mnubtn    {<?=$noroundedcorners;?>;height:16px;text-align:left;border:solid 0px white;}
.c_gobtn   {height:<?=$btnh;?>px;width:<?=$btnw;?>px;overflow:hidden;}
.c_closebtn{height:<?=($fontsize+2);?>px;width:16px;position:absolute;top:0;left:100;overflow:hidden;}
.c_thumbdivs {float:left; width:100px; height:70px; padding:4px; background-color:<?=$bgcolor;?>; }
.c_thumbs    {float:left; width:100px;  height:70px; cursor:pointer; }
.c_filter_thumbs    {float:left; width:98px;  height:68px; cursor:pointer; border:solid 1px #ffffff;}
.c_folder  {overflow:hidden;}


.opacity1{ filter:alpha(opacity:10); KHTMLOpacity:0.1; MozOpacity:0.1; opacity:0.1; }
.opacity2{ filter:alpha(opacity:20); KHTMLOpacity:0.2; MozOpacity:0.2; opacity:0.2; }
.opacity4{ filter:alpha(opacity:40); KHTMLOpacity:0.4; MozOpacity:0.4; opacity:0.4; }
.opacity6{ filter:alpha(opacity:60); KHTMLOpacity:0.6; MozOpacity:0.6; opacity:0.6; }
.opacity8{ filter:alpha(opacity:80); KHTMLOpacity:0.8; MozOpacity:0.8; opacity:0.8; }
.opacity10{filter:alpha(opacity:100);KHTMLOpacity:1.0; MozOpacity:1.0; opacity:1.0; }

.corners {  -moz-border-radius: 0px; -webkit-border-radius: 0px; border-radius: 0px; }
.corners2{  -moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px; }
.corners4{  -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; }
.corners6{  -moz-border-radius: 6px; -webkit-border-radius: 6px; border-radius: 6px; }
.corners8{  -moz-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; }
.left_corners{
 -moz-border-radius: 4px 0 0 4px;
 -webkit-border-radius: 4px  0 0 4px;
 border-radius: 4px  0 0 4px;
}
.right_corners{
 -moz-border-radius: 0 4px 4px 0;
 -webkit-border-radius: 0 4px 4px  0;
 border-radius: 0 4px 4px  0;
}
.c_shadowssmall{-moz-box-shadow: 0px 0px 6px #000000;	-webkit-box-shadow: 0px 0px 6px #000000; box-shadow: 0px 0px 6px #000000;}
.c_shadows   {-moz-box-shadow: 0px 0px 15px #000000;	-webkit-box-shadow: 0px 0px 15px #000000; box-shadow: 0px 0px 15px #000000;}
.c_shadowsbig{-moz-box-shadow: 0px 0px 30px #000000;	-webkit-box-shadow: 0px 0px 30px #000000; box-shadow: 0px 0px 30px #000000;}
.c_shadowsinset{-moz-box-shadow: inset 0px 0px 5px #000000;	-webkit-box-shadow: inset 0px 0px 5px #000000; box-shadow: inset 0px 0px 5px #000000;}

::-webkit-scrollbar {
width: 8px;
height: 8px;
}
::-webkit-scrollbar-track {
background: <?=$scrollbgcolor;?>;
-webkit-border-radius: 8px;
-webkit-box-shadow: inset 0 0 2px rgba(0,0,0,0.3);
border-radius: 8px;
}
::-webkit-scrollbar-thumb {
-webkit-border-radius: 8px;
-webkit-box-shadow: inset 0 0 8px rgba(0,0,0,0.5);
border-radius: 8px;
background: <?=$scrollfgcolor;?>;
}

.slideHeight{
-webkit-transition: height 0.5s;
-moz-transition: height 0.5s;
-o-transition: height 0.5s;
-ms-transition: height 0.5s;
transition: height 0.5s;
}

.slideWidth{
-webkit-transition: width 0.5s, height 0.5s;
-moz-transition: width 0.5s, height 0.5s;
-o-transition: width 0.5s, height 0.5s;
-ms-transition: width 0.5s, height 0.5s;
transition: width 0.5s, height 0.5s;
}

.slideBg{
-webkit-transition: background 1.0s;
-moz-transition: background 1.0s;
-o-transition: background 1.0s;
-ms-transition: background 1.0s;
transition: background 1.0s;
}

.slideOpacity{
-webkit-transition: opacity 1.0s;
-moz-transition: opacity 1.0s;
-o-transition: opacity 1.0s;
-ms-transition: opacity 1.0s;
transition: opacity 1.0s;
}


</STYLE>



<SCRIPT>
//================================= JAVASCRIPT =================================


var gRoot 		="<?=$gRoot;?>";
var gContent	="<?=$gContent;?>";
var gPath 		="<?=$gPath;?>";
var gMode 		="<?=$mode;?>";
var gDir  		="<?=$dir;?>";
var gDirLink  	="<?=$dirlink;?>";
var gID         ="<?=$gID;?>";
var gLoggedIn   =(<?=$gLoggedIn;?>)?1:0; 
var gLoginPath  ="<?=$gLoginPath;?>";
var gLoginDirLink="<?=$gLoginDirLink;?>"; 
var gLoginDir   ="<?=$gLoginDir;?>";      //this gets changed to be become the 'active' dir within the login path
var gAcctAdmin  =(<?=$gAcctAdmin;?>)?1:0;
var gAdmin      =(<?=$gAdmin;?>)?1:0;
var gAutoLogin  =<?=$gAutoLogin;?>;

//alert("INC_HEADER: gID="+gID+", gLoggedIn="+gLoggedIn+", gAdmin="+gAdmin+", gAcctAdmin="+gAcctAdmin+
//      ", gLoginPath="+gLoginPath+", gDir="+gDir+", gAutoLogin="+gAutoLogin);



//---- auto login/logout variables ----
var gShowmenu=<?=$showmenu;?>;
var gShowtoolbar=<?=$showtoolbar;?>;
var gShowfolders=<?=$showfolders;?>;
var gShowfiles=<?=$showfiles;?>;
var gShowpics=<?=$showpics;?>;
var gShowvids=<?=$showvids;?>;
var gShowlinks=<?=$showlinks;?>;
var gShowupload=<?=$showupload;?>;
var gShownewfolder=<?=$shownewfolder;?>;
var gShownewfile=<?=$shownewfile;?>;
var gShownewlink=<?=$shownewlink;?>;
var gShownewfimg=<?=$shownewfimg;?>;
var gShownewsvg=<?=$shownewsvg;?>;
var gShownewvid=<?=$shownewvid;?>;
var gShoworgfolders=<?=$showorgfolders;?>;
var gShowupdsettings=<?=$showupdsettings;?>;
var gShowpopupmenu=<?=$showpopupmenu;?>;


var gShowactions="<?=$showactions;?>";


//---- template variables -----
var gMouseoverMenus="<?=$mouseovermenus;?>";
var gHomePage="<?=$homepage;?>";

//---- other variables ----
var gValidChars="abcdefghijklmnopqrstuvwxyz 0123456789-";





//=============================== POPUP MENUS ==================================
//NOTE: slideHeight transition is set to 0.5s

var popupMenus=new Array();

function popupMenu(id,h){
popupMenus[id]=this;
this.id=id;
this.hght=h;
this.timer=null;
this.disabled=0;
this.div=_newObj("DIV");
_addObj(document.body,this.div);
this.div.className="c_popupmenu slideHeight";
if(!gIE && gMouseoverMenus=="yes"){
 this.div.addEventListener("mouseover",this.mouseOverMenu.bind(this),false);
 this.div.addEventListener("mouseout",this.mouseOutMenu.bind(this),false);
}}


popupMenu.prototype.mouseOverMenu=function(event){ //mouseover the menu itself
this.clearTimers();
}

popupMenu.prototype.mouseOutMenu=function(event){
if(gIE)return;
if(event){
 var obj=event.relatedTarget;
 while(obj!=null){
  if(obj==this.div)return;  //not really mouseout
  obj=obj.parentNode;
}}
this.schedHideMenu();
}


popupMenu.prototype.schedShowMenu=function(btn){  //mouseover btn
if(gIE || gMouseoverMenus!="yes" || this.disabled)return;
this.btn=btn
try{ clearTimeout(this.timerHide);}catch(e){} this.timerHide=null;
this.timerShow=setTimeout("try{popupMenus['"+this.id+"'].showMenu();}catch(e){}",200);
}

popupMenu.prototype.schedHideMenu=function(){  //mouseout btn
if(gIE || gMouseoverMenus!="yes" || this.disabled)return;
try{ clearTimeout(this.timerShow);}catch(e){} this.timerShow=null;
this.timerHide=setTimeout("try{popupMenus['"+this.id+"'].hideMenu();}catch(e){}",500);
}

popupMenu.prototype.showMenu=function(){
this.clearTimers();
this.hideAllMenus(this.id);
this.div.style.display="block";
this.div.style.height=this.hght;
this.div.style.top='30px';
if(this.btn.id=='iShowPixiMenu' || this.btn.id=='iMouseModeMenu' ||  this.btn.id=='iSwapMenu'){
 	this.div.style.left='';
 	this.div.style.right='278px';
}else{
	if(this.btn.id=='iAniMouseModeMenu' ){
	 	this.div.style.left='';
 		this.div.style.right='174px';
		this.div.style.top='324px';
	}else{
 		this.div.style.right='';
 		this.div.style.left=getLeft(this.btn)-4;
	}
}
this.clickedTimer=setTimeout("popupMenus['"+this.id+"'].btnClicked=1;",500);
}

popupMenu.prototype.hideMenu=function(){
this.clearTimers();
this.div.style.height="0px";
this.clickedTimer=setTimeout("popupMenus['"+this.id+"'].btnClicked=0;",500);
}

popupMenu.prototype.hideAllMenus=function(exceptid){
for(var id in popupMenus){
 if(id!=exceptid){
  if(popupMenus[id].div.style.height!="0px"){
   popupMenus[id].div.style.height="0px";
   popupMenus[id].div.style.display="none";
}}}}


popupMenu.prototype.clearTimers=function(){
try{clearTimeout(this.timerShow);}catch(e){} this.timerShow=null;
try{clearTimeout(this.timerHide);}catch(e){} this.timerHide=null;
try{clearTimeout(this.clickedTimer);}catch(e){} this.clickedTimer=null;
}

popupMenu.prototype.clickMenuBtn=function(btn,event,rbtnid,side){
if(this.disabled)return false;
var e=_event(event),rbtn=0;
try{
 if(!gIE){if(e.which==3)rbtn=1;}
 else    {if(event.button==2)rbtn=1;}
}catch(e){}
if(rbtn && rbtnid){
 if(side)_obj(rbtnid).style.left=(getLeft(btn)+30)+"px";
 _obj(rbtnid).style.display=(_obj(rbtnid).style.display=="none")?"":"none";
 this.btnClicked=1;
}
this.btn=btn;
if(this.btnClicked==1){ this.hideMenu(); this.btnClicked=0; }
else{ this.showMenu(); this.btnClicked=1 }
return false;
}



//----------------------------- FUNCTIONS --------------------------------------
function resetToolbarRights(){
resetRights(
 gID,gLoggedIn,gLoginPath,gAcctAdmin,gAdmin,
 gShowmenu,gShowtoolbar,gShowfolders,gShowfiles,
 gShowpics,gShowvids,gShowlinks,gShowupload,gShownewfolder,gShownewfile,
 gShownewlink,gShownewfimg,gShownewsvg,gShownewvid,gShoworgfolders,gShowupdsettings,gShowpopupmenu,gShowactions);
}


function getTopToolbarObj(){
var o=parent;
while(o.gFrameTop==0)o=o.parent;
return o.frames[0];
}


function hasDirRights(dir){
if(gAdmin)return 1;
if(!gID || !gLoggedIn)return 0;
//var ok=(_in(gPath+dir+"/",gLoginPath))?1:0;
var ok=(_ix(gPath+dir+"/",gLoginPath)==0)?1:0;
return ok;
}



//--------------------------------mouseovers -----------------------------------
function _hilite(o){o.style.background='<?=$btnhicolor;?>';}
function _lolite(o){o.style.background='<?=$btnbgcolor;?>';}
function _lnkHilite(o){o.style.color='<?=$hicolor?>';}
function _lnkLolite(o){o.style.color='';}
function _menuHilite(o){o.style.color='<?=$btnbgcolor;?>';}
function _menuLolite(o){o.style.color='<?=$btnhicolor;?>';}
function _hiliteImg(o){o.style.border='solid 1px <?=$btnhicolor;?>';}
function _loliteImg(o){o.style.border='solid 1px white';}


</SCRIPT>

