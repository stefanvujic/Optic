//============= included in _inc_header.php ============
// (I'm not sure where _utilities.js is used?)

var gIE=(navigator.appName=="Microsoft Internet Explorer")?1:0;

function myEval(txt){  
	var a=txt.split(";");
	for(var i=0;i<a.length;i++){
		if(a[i])		Function("'use strict';return ("+ a[i] + ")")();
	}
}


function getRbtn(event){
var e=_event(event),rbtn=0;
try{
 if(!gIE){if(e.which==3)rbtn=1;}
 else    {if(event.button==2)rbtn=1;}
}catch(e){}
return rbtn;
}

//--------------------- draggables (requires jQuery) ---------------------------
function resizable(x){$("#"+x).resizable({
 start: DragStart,
 stop : DragEnd
});}


function draggable(x,op){
 if(op==null)op=0.7;
 $("#"+x).draggable({
  start: DragStart,
  stop : DragEnd,
  opacity: op
 });
}


function DragStart(){try{_obj("iDragCover").style.display="block";}catch(e){}}
function DragEnd()  {try{_obj("iDragCover").style.display="none"; }catch(e){}}


//---------------------------- fader functions ---------------------------------
var fadeTimerOut=new Array(), fadeTimerIn=new Array(), fadeOutOn=new Array(), fadeInOn=new Array(), curOpacity=new Array();


function moFade(over,dv,startfrom,tm){
var timer;
if(tm==null)tm=100;
if(startfrom==null)startfrom=0;
var currentOpacity=dv.style.opacity;
if(over && currentOpacity>0.9)return;
if(!over && currentOpacity==0)return;
if(over)timer=fadeIn(dv.id,startfrom);
else timer=fadeOut(dv.id,tm);
return timer;
}


function schedFadeIn(id,delay){
curOpacity[id]=0;
fadeInOn[id]=1;
fadeTimerOut[id]=setTimeout("fadeInOn['"+id+"']=100;fadeIn('"+id+"',100)",delay);
}

function schedFadeOut(id,delay){
curOpacity[id]=100;
fadeOutOn[id]=1;
fadeTimerOut[id]=setTimeout("fadeOutOn['"+id+"']=0;fadeOut('"+id+"',100)",delay);
}

function swapImg(id1,id2){
//msg("swap");
if(fadeOutOn[id1]||fadeInOn[id2])return;
try{clearTimeout(fadeTimerIn[id2]);}catch(e){}
try{clearTimeout(fadeTimerOut[id1]);}catch(e){}
fadeIn(id2,0);
fadeOut(id1,100);
}

function fadeIn(id,opacity,chg,delay){
if(!chg)chg=2;
if(!delay)delay=20;
var tmp=chg;
if(opacity<5&&chg>4)tmp=2;
if(opacity==0){
 	if(fadeInOn[id]==1)return;
 	if(fadeOutOn[id]==1)opacity=curOpacity[id];
}
//msg("fadeIn="+opacity);
try{clearTimeout(fadeTimerOut[id]);}catch(e){}
fadeOutOn[id]=0;
obj=_obj(id);
if(opacity<=100){
 fadeInOn[id]=1;
 setOpacity(obj,opacity);
 curOpacity[id]=opacity;
 opacity+=tmp;
 fadeTimerIn[id]=window.setTimeout("fadeIn('"+id+"',"+opacity+","+chg+")",20);
 return fadeTimerIn[id];
}else{
 fadeInOn[id]=0;
 curOpacity[id]=100;
 setOpacity(obj,100);
}}


function fadeOut(id,opacity,chg){
if(!chg)chg=1;
var tmp=chg;
if(opacity>95&&chg>4)tmp=2;
if(opacity==100){
 	if(fadeOutOn[id]==1)return;
 	if(fadeInOn[id]==1)opacity=curOpacity[id];
}
//msg("fadeOut="+opacity);
try{clearTimeout(fadeTimerIn[id]);}catch(e){}
fadeInOn[id]=0;
obj=_obj(id);
if(opacity>=0){
 	fadeOutOn[id]=1;
	setOpacity(obj,opacity);
	curOpacity[id]=opacity;
	opacity-=tmp;
	fadeTimerOut[id]=window.setTimeout("fadeOut('"+id+"',"+opacity+","+chg+")",20);
	return fadeTimerOut[id];
}
fadeOutOn[id]=0;
curOpacity[id]=0;
setOpacity(obj,0);
}


//-------------------------------- setMirror etc.-------------------------------
function setMirror(obj,m){
var str="";
switch(m){
 case 0: str="scaleX(1)  scaleY(1)";  break;
 case 1: str="scaleX(-1) scaleY(1)";  break;
 case 2: str="scaleX(1)  scaleY(-1)"; break;
 case 3: str="scaleX(-1) scaleY(-1)"; break;
}
transform(obj,str);
}


function transform(obj,str){
if(str==null)str="";
obj.style.mozTransform   =str;
obj.style.webkitTransform=str;
obj.style.oTransform     =str;
obj.style.msTransform    =str;
obj.style.transform      =str;
}



//---------------- gradients and opacity -----------------


function setGradient(obj,str,typ) {
if(typ=="linear" && _in(str,"center"))typ="radial"; //"center" does not work with "linear"
if(str=="" || str=="transparent"){
	obj.style.background="transparent";
	return;
}
if(typ=="flat"){
	obj.style.background="";
	obj.style.backgroundColor=str;
	return;
}
obj.style.background="-moz-"+typ+"-gradient("+str+")";
obj.style.background="-webkit-"+typ+"-gradient("+str+")";
obj.style.background="-o-"+typ+"-gradient("+str+")";
obj.style.background="-ms-"+typ+"-gradient("+str+")";
obj.style.background=typ+"-gradient("+str+")";
}


function setOpacity(obj,opacity) {
if(!obj)return;
opacity=(opacity==100)?99.999:opacity;
obj.style.filter = "alpha(opacity:"+opacity+")";  // IE/Win
obj.style.KHTMLOpacity = opacity/100;             // Safari<1.2, Konqueror
obj.style.MozOpacity = opacity/100;               // Older Mozilla and Firefox
obj.style.opacity = opacity/100;                  // Safari 1.2, newer Firefox and Mozilla, CSS3
}


//---------------------- animationFrame functions ------------------------------
function getAnimFrame(){
return window.requestAnimationFrame;
//var f = window.mozRequestAnimationFrame    ||
//        window.webkitRequestAnimationFrame ||
//        window.msRequestAnimationFrame     ||
//        window.oRequestAnimationFrame;
//return f;
}


function _shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;
  // While there remain elements to shuffle...
  while (0 !== currentIndex) {
    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;
    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }
  return array;
}


//------------------------------------utility-----------------------------------
function _place(o,x,y){o.style.left=""+x+"px";o.style.top =""+y+"px";}
function _fx(i,zm){i=_nt(i);i=(i)?i*zm:0;return i;}
function _nt(x){try{x=x.replace("px","");}catch(e){}if(x=='NaN')x=0;return x*1;}
function _in(x,v){x=''+x;if(!x)return false;if(_ix(x,v)>-1)return true;return false;}
function _ix(x,v){if(x==null)return -1;return x.indexOf(v);}
function _rep(x,y,z){if(x==null)return '';var s=''+x;while(s.indexOf(y)!=-1)s=s.replace(y,z);return s;}
function _rdm(x,y){return Math.round(Math.random()*(y-x))+x;}
function _incr(v,i,mn,mx){v+=i;if(v>mx)v=mn+((v-1)-mx);if(v<mn)v=mx-(mn-(v+1));return v;}
function _hide(o){o.style.visibility='hidden';}
function _show(o){o.style.visibility='inherit';}
function _vz(o){if(o.style.visibility=='hidden')return false;return true;}
function _vzFlip(o){(!_vz(o))?_show(o):_hide(o);}
function _xhide(o){o.style.display='none';}
function _xshow(o){o.style.display='';}
function _xvz(o){if(o.style.display=='none')return false;return true;}
function _xvzFlip(o){(o.style.display=='none')?_xshow(o):_xhide(o);}
function _try(x){eval("try{"+x+"}catch(e){}");}
function _prt(x){document.write(x);}
function _prtb(x){document.write(x+"<br>");}
function _getParm(n){n=n.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");var s="[\\?&]"+n+"=([^&#]*)";var regex=new RegExp(s);var results=regex.exec(window.location.href);if(results==null)return "";else return results[1];}
function _isOdd(n){ return n % 2;}
function _log(x){ console.log(x); }
function _round(n,precision){ var factor = Math.pow(10, precision);   return Math.round(n * factor) / factor; }

function roundUp(num, precision) {
  precision = Math.pow(10, precision)
  return Math.ceil(num * precision) / precision
}
function roundDown(number, decimals) {
    decimals = decimals || 0;
    return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
}


//------ xykey -----
function xyKey(x,y){
x=(x<10)?"0"+x:""+x;
y=(y<10)?"0"+y:""+y;
return ""+x+y;}


function msgD(label,x,depth)	{ 
var txt=label; 
if(depth){	for(var i=0;i<depth;i++)txt="&nbsp;&nbsp;"+txt; }
if(x!=null)txt+=": "+x; 
//txt+="<br>"; 
//_obj("iMsg").innerHTML=txt+_obj("iMsg").innerHTML; 
console.log(x);
}

function msg(x)	{ console.log(x);}


function msgbr(x){console.log(x);console.log(" ");}


function _reloadWindow(){
try{
	top.window.location.href=top.window.location.href;
}catch(e){
	top.frames[0].location.href=top.frames[0].location.href;
}}


//---------------------------------------objects--------------------------------
function _obj(x){return document.getElementById(x);}
function _newObj(tag){return document.createElement(tag);}
function _delObj(obj){obj.parentNode.removeChild(obj);}
function _addObj(parent,obj,sibling){if(sibling)parent.insertBefore(obj,sibling);else parent.appendChild(obj);}


//-------------------------------- strings -------------------------------------
String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"");}
String.prototype.ltrim=function(){return this.replace(/^\s+/,"");}
String.prototype.rtrim=function(){return this.replace(/\s+$/,"");}

function cleanString(q,okchars){
var c,x="",i;
if(!okchars)okchars="";
okchars+=gValidChars;
q=q.toLowerCase();
q=q.trim();
for(i=0;i<okchars.length;i++){
 c=q.substr(i,1);
 if(_in(okchars,c) && (c!="-" || (i && i!=okchars.length-1)) )x+=c;
}return x;}

function capFirstLetter(s){ return s.charAt(0).toUpperCase() + s.slice(1); }


//------------------------------------event handling----------------------------
function _event(e){if(gIE)return window.event;return e;}
function _eventObj(e){var o=(gIE)?e.srcElement:e.target;return o;}
function _eventAdd(o,n,h){if(o.attachEvent)o.attachEvent("on"+n,h);else o.addEventListener(n,h,false);}


function hookEvent(element, eventName, callback){
if(typeof(element) == "string")element = document.getElementById(element);
if(element == null)return;
if(element.addEventListener){
 if(eventName == 'mousewheel')element.addEventListener('DOMMouseScroll', callback, false);
 element.addEventListener(eventName, callback, false);
}else{if(element.attachEvent)element.attachEvent("on" + eventName, callback);}}


function unhookEvent(element, eventName, callback){
if(typeof(element) == "string")element = document.getElementById(element);
if(element == null)return;
if(element.removeEventListener){
 if(eventName == 'mousewheel')element.removeEventListener('DOMMouseScroll', callback, false);
 element.removeEventListener(eventName, callback, false);
}else{if(element.detachEvent)element.detachEvent("on" + eventName, callback);}}


function stopProp(e) {
  if( (navigator.userAgent.indexOf("MSIE")>-1 && navigator.userAgent.indexOf("compatible")>1 && !(navigator.userAgent.indexOf("opera")>-1)))  { // IE
    if(!e) e = window.event; e.cancelBubble = true;
  } else { //Other Browsers
    e.stopPropagation();
  }
}



//------------------------------------nbrs--------------------------------------
function _nbr(s,z){s=''+s;var i,b='';for(i=0;i<s.length;i++)b+=_gb(s.charAt(i));return _crunch(b,z);}
function _crunch(b,z){var bx;while(b.length<z)b=b+b;while(b.length>z){ bx=parseInt(b.charAt(b.length-2))+parseInt(b.charAt(b.length-1)); b=b.slice(0,-2)+bx;}return b;}
function _gc(n){return String.fromCharCode(parseInt(n));}
function _gb(c){var n=_gn(c);if(n>47&&n<58)return c;(n<97)?n-=64:n-=96;if(n<1)n=n*-1;if(n<1)n=9;while(n>9)n=n-9;return n;}
function _gn(c){return ''+c.charCodeAt(0);}
function _rot(s){var i,b='';for(i=0;i<s.length;i++)b+=(9-_gb(s.charAt(i)));return b;}
function _rev(s){var i,v='';s=''+s;for(i=s.length-1;i>-1;i--)v+=s.charAt(i);if(v==s)v=_rot(s);return v;}

function _nextIX(i,max){
//while(i>max){   i=i-(max+1);  }
if(i>max)i=0;
return i;
}

function _nip(ip,z){
if(!ip)ip="00.00.00.00";
var n,a=ip.split(".");
n=""+_nbr(a[0],1)+_nbr(a[1],1)+_nbr(a[2],1);
return _nbr(n,z);
}

//----------------------------------- colors -----------------------------------
function RGB2HEX(R,G,B){return toHex(R)+toHex(G)+toHex(B)}

function RGBA2HEX(rgba){
	//msg("rgba="+rgba);
	if(!_in(rgba,"("))return "000000";
	var a = rgba.split("(");
	var b = a[1].split(",");
	return "#"+toHex(b[0])+toHex(b[1])+toHex(b[2]);
}

function toHex(N){
	if(N==null)return "00";
	N=N.trim(); N=parseInt(N); if (N==0 || isNaN(N)) return "00";
	N=Math.max(0,N); N=Math.min(N,255); N=Math.round(N);
	return "0123456789ABCDEF".charAt((N-N%16)/16)+"0123456789ABCDEF".charAt(N%16);
}

function HEX2RGB(c){var r=HexToR(c),g=HexToG(c),b=HexToB(c); return "rgb("+r+","+b+","+g+")";}
function HEX2RGBA(c,opacity){var r=HexToR(c),g=HexToG(c),b=HexToB(c); return "rgba("+r+","+g+","+b+","+opacity+")";}
function HexToR(c) {return parseInt((cutHex(c)).substring(0,2),16)}
function HexToG(c) {return parseInt((cutHex(c)).substring(2,4),16)}
function HexToB(c) {return parseInt((cutHex(c)).substring(4,6),16)}
function cutHex(c) {return (c.charAt(0)=="#") ? c.substring(1,7):c}


//------------------------ element offsets etc. ----------------------
function getLeft(obj){
var left = obj.offsetLeft/1 + 1;
while((obj = obj.offsetParent) != null){
 if(obj.tagName!='HTML')left += obj.offsetLeft;
}
if(document.all)left = left/1 - 2;
return left;
}

function getRight(obj){
var right = obj.offsetRight/1 + 1;
while((obj = obj.offsetParent) != null){
 if(obj.tagName!='HTML')right += obj.offsetRight;
}
if(document.all)right = right/1 - 2;
return right;
}

function getTop(obj){
var top = obj.offsetTop/1 + 1;
while((obj = obj.offsetParent) != null){
 if(obj.tagName!='HTML')top += obj.offsetTop;
}
if(document.all)top = top/1 - 2;
return top;
}

function eleWidth(obj){
return obj.offsetWidth;
}


function eleHeight(obj){
return obj.offsetHeight;
//Note: return $("#ele-id").height();  also works!
}

function docWidth(){
//var w=document.body.clientWidth;
var w=document.documentElement.clientWidth;
if(w)return w;
return $(window).width();
}

function docHeight(){
//var h=document.body.clientHeight;
var h=document.documentElement.clientHeight;
if(h)return h;
return $(window).height();
}

function _getLeftClick(e){ return ((!gIE)? e.pageX : event.clientX + document.body.scrollLeft); }
function _getTopClick(e) { return ((!gIE)? e.pageY : event.clientY + document.body.scrollTop); }




//-------------------------- dir/file name handling -------------------

function getSrc(dir,file){
var src=gPath;
file=getFile(file);
dir=dir.replace("../","");
file=file.replace("../","");
if(dir)src+=dir+"/";
src+=file;
return src;
}

function getFileUrl(f){
if(_in(f,"@vid@"))return f;
if(_in(f,"@lnk@")){
 f=f.replace("@lnk@");
 if(!_in(f,"http://"))f="http://"+f;
 return f;
}
if(_in(f,"http://"))return f;
f=_rep(f,"../","");
f=gRoot+f;
return f;
}


function getFileName(u){
if(!u)return "";
var a=u.split("/");
return a[a.length-1];
}


function getFile(u){
if(!u)return "";
var a=u.split("/"),f=a[a.length-1];
if(f){
 if(_in(f,"_tn."))f=f.replace("_tn.",".");
 if(_in(f,".")||_in(f,"@vid@")||_in(f,"@lnk@"))return f;
}
return "";}


function getPath(dir){
var d="",a=dir.split("/");
for(var i=0;i<a.length-1;i++)d+=a[i]+"/";
if(d)d=d.substr(0,d.length-1);
return d;}


function _getExt(u){
if(!_in(u,"."))return ""; 
var a=u.split(".");return '.'+a[a.length-1];}


function _getName(u){
var b=u.split("/");
var f=b[b.length-1];
if(!_in(f,"."))return f;
var a=f.split(".");return a[a.length-2];}


function _isFimg(u){
if(_isImage(u) && (_ix(u.toLowerCase(),"http://")==0 || _ix(u,"@fmg@")==0))return true;
return false;}


function _isSvg(u){
if(_isImage(u) && _in(u.toLowerCase(),".svg"))return true;
return false;}


function _isImage(u){
var ext,x=',.gif,.jpeg,.jpg,.png,.svg,';
ext=_getExt(u);
if(ext=="")return false;
ext=ext.toLowerCase();
if(_in(x,ext))return true;
return false;}

function _isPage(u){
if(!_in(u,"."))return false;
var ext,x=',.htm,.html,.php,';
ext=_getExt(u);
ext=ext.toLowerCase();
if(_in(x,ext))return true;
return false;}


function _isFile(u){
if(!_in(u,"."))return false;
var ext,x=',.htm,.html,.xml,.txt,.css,.js,.php,';
ext=_getExt(u);
ext=ext.toLowerCase();
if(_in(x,ext))return true;
return false;}


function _isPixi(u){
if(!_in(u,"."))return false;
var a=u.split(".");
if(a[a.length-1]=="ixi")return true;
return false;}

function _isAction(u){
if(!_in(u,"."))return false;
var a=u.split(".");
if(a[a.length-1]=="iki")return true;
return false;}

function _isFilter(u){
if(!_in(u,"."))return false;
var a=u.split(".");
if(a[a.length-1]=="ixx")return true;
return false;}


function _isVideo(u){
if(_in(u,"@vid@"))return true;
return false;}


function _fileType(f){
if(_isVideo(f))  return "video";
if(_isImage(f))  return "image";
if(_isFile(f))   return "file";
if(_isPixi(f))   return "pixi";
if(_isFilter(f)) return "filter";
if(_isAction(f)) return "action";
return "folder";
}

function _fileType2(f){
if(_isVideo(f))return "vid";
if(_isImage(f))return "img";
if(_isFile(f)) return "fil";
if(_isFilter(f)) return "flt";
if(_isPixi(f)) return "pix";
return "fdr";
}



function _fileUrl(f){
if(_isVideo(f))return f.replace("@vid@","");
if(_isImage(f))return f.replace("@img@","");
if(_isFile(f)) return f.replace("@fil@","");
return f;
}


function getThumb(f){
if(_isSvg(f))return f;
var a=f.split(".");
f=f.replace("."+a[a.length-1],"_tn."+a[a.length-1]);
return f;
}


//----------------------------- get passed parms -------------------------------
function getQueryString(n){
var v=getQueryStringArray()[n];
if(v=="undefined" || v==null)v="";
return v;
}

function getQueryStringArray(){
//--- usage: var x=getQueryString()["x"];
var result={}, queryString=location.search.substring(1), re = /([^&=]+)=([^&]*)/g, m;
while(m=re.exec(queryString))result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
return result;
}



//=================== make first letter of every word upper case ===============
function ucWords(x) {
var txt="",word,firstLetter, restOfWord, a=x.split(" ");
for(i=0;i<a.length;i++){
 word=a[i];
 if(word=="and" || (word=="the" && i>0))txt+=" "+word;
 else{
  firstLetter = a[i].substring(0, 1).toUpperCase();
  restOfWord = a[i].substring(1,30);
  txt+=" "+firstLetter+restOfWord;
}}
return txt.trim();
}


//============================= SMARTPHONE DETECTION ===========================
var gIpad=0,gAndroid=0,gIphone=0;
var gMobile=0;


function DetectMobile(){
var u=navigator.userAgent.toLowerCase();;
gIpad=(u.search("ipad")>-1)?1:0;
gAndroid=(u.search("android")>-1)?1:0;
gIphone=(u.search("iphone")>-1)?1:0;
if(u.search("iphone")>-1)return 1;
if(u.search("ipod")>-1)return 1;
if(u.search("symbian")>-1)return 1;
if(u.search("mobile")>-1)return 1;
if(u.search("android")>-1)return 1;
if(u.search("windows ce")>-1)return 1;
return 0;}



//========================= local storage functions ============================

function localStorageOK(){
try{
set("X089144a3X","x");
if(get("X089144a3X")=="x")return true;
}catch(e){return false;}
return false;}


function set(n,v,pfx){
if(v==0||v=="0")v="zero!";  //bug!
if(pfx==null)pfx="i";
n=pfx+n;
if(v==""){ del(n); return; }
if(gIE){
 ddata.setAttribute(n,v);
 ddata.save("ddata");
}else{
 //try{localStorage.setItem(n,v);}catch(e){}
 localStorage.setItem(n,v);
 //alert("n="+n+", v="+v);
}}


function get(n,d,pfx){
if(pfx==null)pfx="i";
n=pfx+n;
if(d==null)d="";
var v;
if(gIE)v=ddata.getAttribute(n);
else{
 v=localStorage.getItem(n);
 //try{v=localStorage.getItem(n);}catch(e){}
}
if(v==null)return d;
if(v=="zero!")v=0;
return v;
}


function del(n,pfx){
if(pfx==null)pfx="i";
n=pfx+n;
if(gIE){
 ddata.removeAttribute(n);
 ddata.save("ddata");
}else{
 try{localStorage.removeItem(n);}catch(e){}
}}


//------------------------------ COOKIES ---------------------------
function getCookie(name){
var xcookie=document.cookie;
var index = xcookie.indexOf(name + '=');
if (index == -1) return null;
index = xcookie.indexOf('=', index) + 1;
var endstr = xcookie.indexOf(';', index);
if (endstr == -1) endstr = xcookie.length;
return unescape(xcookie.substring(index, endstr));}

function setCookie(name,value) {
if(value==""){deleteCookie(name);return;}
var today = new Date();
var expiry = new Date(today.getTime() + 30 * 24 * 60 * 60 * 1000);
var xcookie=document.cookie;
var old=getCookie(name)+"";
//var tmp=(xcookie.length + name.length + value.length + 5) - old.length;
//if (tmp > 4000) {   alert("maximum cookie size exceeded");   return;}
document.cookie = name + '=' + escape(value) + '; expires=' + expiry.toGMTString();}

function deleteCookie(name) {document.cookie = name + '=deleted; expires=-1';}




//-------------------- get average rgb color of an image -----------------------
function getColor(img){
var c=getAverageRGB(img);
return rgbToHex(c.r,c.g,c.b);
}

function rgbToHex(r, g, b){return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);}
function componentToHex(c) {
var hex = c.toString(16);
return hex.length == 1 ? "0" + hex : hex;
}

function getAverageRGB(imgEl) {
    var blockSize = 5, // only visit every 5 pixels
        defaultRGB = {r:0,g:0,b:0}, // for non-supporting envs
        canvas = document.createElement('canvas'),
        context = canvas.getContext && canvas.getContext('2d'),
        data, width, height,
        i = -4,
        length,
        rgb = {r:0,g:0,b:0},
        count = 0;
    if (!context) {
        return defaultRGB;
    }
    height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
    width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;
    context.drawImage(imgEl, 0, 0);
    try {
        data = context.getImageData(0, 0, width, height);
    } catch(e) {
        /* security error, img on diff domain */
        return defaultRGB;
    }
    length = data.data.length;
    while ( (i += blockSize * 4) < length ) {
        ++count;
        rgb.r += data.data[i];
        rgb.g += data.data[i+1];
        rgb.b += data.data[i+2];
    }
    // ~~ used to floor values
    rgb.r = ~~(rgb.r/count);
    rgb.g = ~~(rgb.g/count);
    rgb.b = ~~(rgb.b/count);
    return rgb;
}

