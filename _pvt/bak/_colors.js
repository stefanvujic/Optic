//====================================== _colors.js =========================================
// NB: Used by _pixi.php (options.php uses colors.js just to confuscious!)

// eg.
//<div id=xxx style="position:absolute;left:40;top:30;background:#00d0ff;width:300;height:300;font-size:40pt;text-align:center;">woohoo</div>
//function myFunc(v,att){alert(att+"="+v);}
//CP.open(window,_obj('xxx'),'backgroundColor',200,200,myFunc);
//CP.open(window,_obj('xxx'),'color',200,200,myFunc);


//===== NOTE: This requires the HTML COLOR BOX to work (see _pixi.php) =====

var CP;  	//colorbox object

//NOTE: Uing an ID to create another instance of the colorPicker doesn't seem to work but should (maybe it does?)
// 		For now we only have one instance and "this.id" is always blank!

function cpColorPicker(id,p){
if(id==null)id="";
var a,i,j,k,s,tmp;
this.id=id;
this.createBox(p);
this.CLR=this.clr='#00ffff';
this.fgColors=new Array('#7fffd4','#b0c4de','#ffe4b5','#eeddee','#f4a460','#f0e68c','#aaccee','#99eeff','#ffffe0','#eeffcc');
this.aC=new Array();
this.aCr=new Array(360);
this.bdy=_obj("iColorBox"+this.id);
this.hdr=_obj("iColorHeader"+this.id);
this.grid=_obj("iColorSpan1"+this.id);
this.box=_obj("iColorSpan2"+this.id);
this.trans=_obj("iColorTrans"+this.id);
this.trans.style.backgroundColor="white";
a=this.aC;
a[0]=new Array(0,1,0);a[1]=new Array(-1,0,0);a[2]=new Array(0,0,1);a[3]=new Array(0,-1,0);
a[4]=new Array(1,0,0);a[5]=new Array(0,0,-1);a[6]=new Array(255,1,1);
for(i=0;i<6;i++){
 for(j=0;j<60;j++){
  this.aCr[60*i+j]=new Array(3);
  for(k=0;k<3;k++){
   this.aCr[60*i+j][k]=a[6][k];
   a[6][k]+=(a[i][k]*4);
}}}
this.setEvents();
}


//function _btnDiv(w,h,bg,clr,sty,ct,cl,cb,cr,wt,wl,wb,wr){

cpColorPicker.prototype.createBox=function(x,y,p){
return;
}



cpColorPicker.prototype.open=function(win,o,att,x,y,func,instantUpdate,initColor,finalUpdFunc){
if(this.alreadyOpen(o,att)){this.close();return;}
if(!finalUpdFunc)finalUpdateFunc=func;
var tmp,tgt,a,i,j,s;
this.win=win;
this.doc=win.document;
this.obj=o;
this.att=att;
this.func=func;
this.finalUpdFunc=finalUpdFunc;
this.instantUpdate=instantUpdate;
this.initColor=initColor;
//msg("initColor="+initColor);
this.locked=false;
this.lastClr="";

/*
if(att=='opacity')this.tgt='this.obj.style.backgroundColor';
else this.tgt='this.obj.style.'+att;
eval('tmp='+this.tgt+';');
*/
if(att=='opacity')this.tgt=this.obj.style.backgroundColor;
else {
	var txt='this.obj.style.'+att;
	eval('tmp='+txt+';');
	this.tgt=tmp;
}
this.clr=this.CLR=tmp;
this.op=this.OP=this.getObjOpacity();
this.chgOpBoxColor(this.CLR);
this.updColorHist();
this.config(this.att);
if(x!=null&&y!=null)_place(this.bdy,x,y);
_xshow(this.bdy);
//msg("tgt="+this.tgt);
//msg("initColor="+this.initColor);
}


cpColorPicker.prototype.close=function(){
try{_cpHide();}catch(e){}
_xhide(this.bdy);}

cpColorPicker.prototype.alreadyOpen=function(obj,att){
if(this.obj==obj&&this.att==att&&_xvz(this.bdy))return true;
return false;}

cpColorPicker.prototype.config=function(att){
//msg("config att="+att);
var h	=_obj('iColorHeader'+this.id); 
var b	=_obj('iColorBox'+this.id).style; 
var hist=_obj('iColorHist'+this.id).style;
var c1	=_obj('iColorSpan1'+this.id).style;
var c2	=_obj('iColorSpan2'+this.id).style;
var g	=_obj('iColorGrays'+this.id).style;
var o	=_obj('iColorOpacity'+this.id).style;
var p	=_obj('iColorOpOff'+this.id).style;
this.trans.style.display="";
_obj("iCPHeader").style.display="";
this.att=att;
if(this.att=='opacity'){
	 o.display=_obj('iColorTrans'+this.id).style.display=p.display='block';
	 c1.display=c2.display=g.display=hist.display='none';
	 b.height='32px';
}else{
	 //o.display=_obj('iColorTrans'+this.id).style.display=p.display='none';
	 o.display=p.display='none';
	 c1.display=c2.display=g.display=hist.display='block';
	 b.height='152px';
}
switch(att){
case 'color':
	 //h.innerHTML='foreground&nbsp;&nbsp;';
	 this.clr=this.CLR=this.obj.style.color; 
	 break;
case 'borderColor':
	 //h.innerHTML='borders&nbsp;&nbsp;';
	 this.clr=this.CLR=this.obj.style.borderColor.split(' ')[0]; 
	 break;
case 'backgroundColor':
	 //h.innerHTML='background&nbsp;&nbsp;';
	 //this.clr=this.CLR=this.obj.style.backgroundColor;
	 this.clr=this.CLR=this.obj.style.backgroundColor;
	 break;
case 'opacity':
	 //h.innerHTML='opacity&nbsp;&nbsp;';
	 //this.clr=this.CLR=this.obj.style.backgroundColor;
	 this.clr=this.CLR=this.obj.style.background;
	 this.chgOpBoxColor(this.CLR);
	 break;
case 'shadowColor':
	 this.trans.style.display="none";
	 _obj("iCPHeader").style.display="none";
case  'gradientColor':
case 'frameColor':
	 this.clr=this.CLR=this.initColor;
	 this.chgOpBoxColor(this.CLR);
	 break;
}
//msg("trans="+_obj("iColorTrans"+this.id).style.display);
}


//--- colors -----

cpColorPicker.prototype.updObj=function(v,att){
if(this.locked)return false;
if(!att)att=this.att;
var sObj,cmd,lock=this.locked;
try{
switch(att){
  case 'backgroundColor':this.obj.style.background=v;break;
  case 'color':this.obj.style.color=v;break;
  case 'shadowColor':
  case 'gradientColor':
  case 'frameColor':
   this.func(v);
   break;
  case 'opacity':
   if(!v){f=''; 
          v='inherit';}
   else  {f='progid:DXImageTransform.Microsoft.alpha(opacity='+(v*10)+')';
          v='0.'+v;}
   this.obj.style.MozOpacity=v;
   this.obj.style.opacity=v;
   this.obj.style.filter=f;
   break;
  case 'borderColor':
   var a,b,err=0;
   //try{
   // b=BD.getBorder(null,null,null,v);
   // BD.wdg.tryAtt(b);
   // BD.updAtt();
   //}catch(e){err=1;}
   //if(err){
    this.obj.style.borderColor=v;
   //}
   break;
}
}catch(e){}
return lock;}


cpColorPicker.prototype.mv=function(event,clr){
if(event)_event(event).cancelBubble=true;
if(this.locked)return false;
var e,x,y,c;
gPic.unDoing=1;
if(!clr){
 e=_event(event);
 //x=(gIE)?e.offsetX:e.layerX;
 //y=(gIE)?e.offsetY:e.layerY;
 x=e.offsetX;
 y=e.offsetY;
 c=this.getColor(y,x);
 //msg("x="+x+", y="+y);
 //msg("c="+c);
 e.cancelBubble=true;
}else c=clr;
if(this.lastClr==c){ return; }
this.lastClr=c;
this.clr=window.status=c;
this.updObj(c);
if(this.att=='backgroundColor')this.chgOpBoxColor(c);
if(this.func && this.instantUpdate)
	try{
		this.func(this.clr);
	}catch(e){}
//alert('mv: this.Clr='+this.CLR);;
gPic.unDoing=0;
return false;}


cpColorPicker.prototype.upd=function(event,c){
if(event)_event(event).cancelBubble=true;
if(this.locked)return false;
if(c!=null)this.clr=c;
//this.locked=this.updObj(this.clr);
//if(this.func)try{this.func(this.clr);}catch(e){}
//gPic.unDoing=0;
if(this.finalUpdFunc)
	try{
		gPic.unDoing=0;
		this.finalUpdFunc(this.clr);
	}catch(e){}
this.addFgColor(this.clr);
this.updColorHist();
this.CLR=this.clr;
var lock=this.updObj(this.clr);
//_dbg("lock="+lock+" upd("+this.clr+")");
if(lock){this.locked=true;this.close();}
try{
if(this.att=='color'||this.att=='backgroundColor')_logHist(this.doc,this.obj,this.att,this.clr);
}catch(e){}
this.lastClr="";
this.close();
return false;}



cpColorPicker.prototype.reset=function(event){
if(event)_event(event).cancelBubble=true;
if(this.locked)return false;
if(this.bdy.style.display=="none")return; //update done
gPic.unDoing=1;	
//msg("display="+this.bdy.style.display);
if(_in(this.obj.id,"iSwap")){	// swapcolors does actual updates when mouse moved so we have to do a full reset
	this.func(this.initColor);
}else{	
	this.updObj(this.CLR);
}
if(this.att=='opacity')this.chgOpBoxColor(this.CLR);
this.clr=this.CLR;
gPic.unDoing=0;	
//if(event)_event(event).cancelBubble=true;
}

cpColorPicker.prototype.addFgColor=function(c){
if(c=="#ffffff"||c=="#000000"||c=="black")return;
var i,j=0,a=this.fgColors;
for(i=a.length-1;i>-1&&j<10;i--){if(a[i]==c)return;j++;}
this.fgColors[this.fgColors.length]=c;}


cpColorPicker.prototype.updColorHist=function(){
var i,j=0,s='',a,ary;
a=this.fgColors;
ary='fgColors';
s='<table cellpadding=0 cellspacing=0>';
for(i=a.length-1;i>-1&&j<10;i--){
 	//s+='<tr><td onmouseover="CP'+this.id+'.updObj(CP'+this.id+'.'+ary+'['+i+'])" onmouseout="CP'+this.id+'.reset()" onmousedown="CP'+this.id+'.upd(null,CP'+this.id+'.'+ary+'['+i+'])" style="background:'+a[i]+';width:12px;height:12px;overflow:hidden;"></td></tr>';
	s+='<tr><td onmousemove="CP.mv(null,CP.fgColors['+i+'])" onmouseout="CP.reset()" onmousedown="CP.upd(null,CP.fgColors['+i+'])" style="background:'+a[i]+';width:12px;height:12px;overflow:hidden;"></td></tr>';
	j++;
}
_obj("iColorHist"+this.id).innerHTML=s;}

cpColorPicker.prototype.chgOpBoxColor=function(c){
if(!c)c='red';
for(var i=0;i<10;i++){eval('_obj("tdop'+i+this.id+'").style.background="'+c+'"');}}


//---- opacity -----

cpColorPicker.prototype.setOp=function(j){
if(this.locked)return false;
this.op=j;
this.updObj(j,'opacity');}

cpColorPicker.prototype.updOp=function(){
if(this.locked)return false;
this.OP=this.op;}

cpColorPicker.prototype.resetOp=function(){
if(this.locked)return false;
this.updObj(this.OP,'opacity');
this.op=this.OP;}

cpColorPicker.prototype.getObjOpacity=function(){
var v=this.obj.style.opacity;
if(!v)return 0;
if(!_in(v,'.'))return 0;
var tmp=v.split('.');
v=parseInt(tmp[1]);
return v;
}



//--- trans ----
cpColorPicker.prototype.setTrans=function(){
if(this.locked)return false;
this.updObj('transparent','backgroundColor');
this.clr="transparent";}


cpColorPicker.prototype.updTrans=function(){
if(this.locked)return false;
this.CLR="transparent";}


cpColorPicker.prototype.resetTrans=function(){
if(this.locked)return false;
this.updObj(this.CLR,'backgroundColor');
this.clr=this.CLR;}



//---------------
cpColorPicker.prototype.getColor=function(x,y){
var sx,sy,qx,qy,q,qd,xa,ya,d,deg,n=0,r,r2,c;
x=8*x;y=8*y;
//msg("<br>");
//msg("x="+x+", y="+y);
sx=x-512;sy=y-512;
qx=(sx<0)?0:1;
qy=(sy<0)?0:1;
q=2*qy+qx;
qd=new Array(-180,360,180,0);
xa=Math.abs(sx);
ya=Math.abs(sy);
//msg("xa="+xa+", ya="+ya);
d=ya*45/xa;
if(ya>xa)d=90-(xa*45/ya);
deg=Math.floor(Math.abs(qd[q]-d));
sx=Math.abs(x-512);
sy=Math.abs(y-512);
r=Math.sqrt((sx*sx)+(sy*sy));
//msg("r="+r+", deg="+deg);
if(x==512&y==512)c="000000";
else{
 for(i=0;i<3;i++){
  r2=this.aCr[deg][i]*r/256;
  //msg("i="+i+", r2="+r2);
  if(r>256)r2+=Math.floor(r-256);
  if(r2>255)r2=255;
  //msg("i="+i+", r2="+r2);
  n=256*n+Math.floor(r2);
  //msg("i="+i+", r="+r+", r2="+r2+", deg="+deg+", n="+n);
 } 
 c=n.toString(16);
 //msg("n="+n+", c="+c);
 while(c.length<6)c="0"+c;
}
c="#"+c;
return c;}




cpColorPicker.prototype.setEvents=function(){
if(this.id=="S"){
 this.box.onmousedown  =function(event){ CPS.upd(event);   return false;}
 this.box.onmousemove  =function(event){ CPS.mv(event);    return false;}
 this.box.onmouseout   =function(event){ CPS.reset(event); return false;}
}else{
 this.box.onmousedown  =function(event){ CP.upd(event);   return false;}
 this.box.onmousemove  =function(event){ CP.mv(event);    return false;}
 this.box.onmouseout   =function(event){ CP.reset(event); return false;}
 //this.trans.onmousedown=function(event){CP.updTrans(event);return false;}
}}


//CP=new cpColorPicker();  //create the color box object (moved to onload() in _pixi.js)

//Note: The "locked" and 'selection' issues screw up the draggable stuff for some reason!
//try{DG.open("iColorBox");}catch(e){} //make it draggable (requires drag.js)

