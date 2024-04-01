//====================================== colors.js =========================================

//========== NB!! USED by OPTIONS.PHP!! (_pixi.php uses _colors.js) ========================

//================= colors ==================
var CP;


function cpColorPicker(p){
var a,i,j,k,s,tmp;
this.createBox();
this.CLR=this.clr='#00ffff';
this.bgColors=new Array('aquamarine','lightsteelblue','moccasin','palegreen','sandybrown','khaki','#ffff00','#0000ff','#00ff00','#ff0000');
this.fgColors=new Array('dodgerblue','darkgreen','goldenrod','firebrick','maroon','tomato','#ffff00','#0000ff','#00ff00','#ff0000');
this.aC=new Array();
this.aCr=new Array(360);
this.bdy=_obj("iColorBox");
//this.hdr=_obj("iColorHeader");
this.grid=_obj("iColorSpan1");
this.box=_obj("iColorSpan2");
this.trans=_obj("iColorTrans");
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
//--- colors box ---
s='<table cellpadding=0 cellspacing=0>';
for(i=0;i<10;i++){
 s+='<tr>';
 for(j=0;j<10;j++){
  x=(i*12)+10;y=(j*12)+10;
  c=this.getColor(x,y);
  s+='<td style="background:'+c+';width:12px;height:12px;"></td>';
 }
 s+='</tr>';
}
s+='</table>';
_obj("iColorSpan1").innerHTML=s;
//--- grays box ---
this.grays=_obj("iColorGrays");
s='<table cellpadding=0 cellspacing=0><tr>';
var wdth;
for(j=0;j<11;j++){
 wdth='12';
 if((j==0&&gIE)||(!gIE&&j==10))wdth='10';
 switch(j){
 case 0:c='#000000';break;  
 case 1:c='#333333';break;
 case 2:c='#555555';break;
 case 3:c='#666666';break;
 case 4:c='#777777';break;
 case 5:c='#999999';break;
 case 6:c='#aaaaaa';break;
 case 7:c='#cccccc';break;
 case 8:c='#dddddd';break;
 case 9:c='#eeeeee';break;
 case 10:c='#ffffff';break;}
 s+='<td style="background:'+c+';width:'+wdth+';height:16px;" '+
    'onmousemove="CP.mv(null,\''+c+'\')" onMouseOut="CP.reset()" onmousedown="CP.upd()"></td>';
}
s+='</tr></table>';
_obj("iColorGrays").innerHTML=s;
//--- opacity box ---
s='<table cellpadding=0 cellspacing=0><tr>';
for(j=9;j>0;j--){
 c='0.'+j;
 s+='<td id=tdop'+j+' style="filter:alpha(opacity='+(j*10)+');-moz-opacity:'+c+';opacity:'+c+';'+
    'width:14px;height:16px;background:red;" onmouseover="CP.setOp('+j+')" onMouseOut="CP.resetOp()" onmousedown="CP.updOp()"></td>';
}
s+='</tr></table>';
_obj("iColorOpacity").innerHTML=s;
//--- opacity off box ---
s='<table cellpadding=0 cellspacing=0><tr>';
s+='<td id=tdop0 style="background:red;overflow:hidden;width:12px;height:16px;" ';
s+='onmouseover="CP.setOp(0)" onMouseOut="CP.resetOp()" onmousedown="CP.updOp()"></td>';
s+='</tr></table>';
_obj("iColorOpOff").innerHTML=s;
//--- trans box ---
s='<table cellpadding=0 cellspacing=0><tr>';
s+='<td style="overflow:hidden;width:10px;height:16px;" ';
s+='onmouseover="CP.setTrans()" onMouseOut="CP.resetTrans()" onmousedown="CP.updTrans()"></td>';
s+='</tr></table>';
_obj("iColorTrans").innerHTML=s;
this.setEvents();}


//function _btnDiv(w,h,bg,clr,sty,ct,cl,cb,cr,wt,wl,wb,wr){

cpColorPicker.prototype.createBox=function(x,y){
var s='',tmp;
if(x==null)x=y=100;
s+='<DIV id=iColorBox unselectable=on style="background:white;position:absolute;left:'+x+';top:'+y+';'+
   'width:130px;height:168px;z-index:99993;overflow:hidden;display:none;border:solid 1px black;cursor:default;text-align:center;">';
s+=
'<span id=iColorSpan1   style="position:absolute;left:11;top:16; width:119px;height:128px;cursor:default;background:white;"></span>'+
'<span id=iColorSpan2   style="position:absolute;left:11;top:16; width:119px;height:128px;cursor:default;cursor:hand;text-align:center;"></span>'+
'<span id=iColorGrays   style="position:absolute;left:0;  top:136;width:130px;height:16px;cursor:default;overflow:hidden;"></span>'+
'<span id=iColorOpOff   style="position:absolute;left:0;  top:16;width:12px; height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;"></span>'+
'<span id=iColorOpacity style="position:absolute;left:'+((gIE)?10:12)+';top:16;width:108px; height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;background:white;"></span>'+
'<span id=iColorTrans   style="position:absolute;left:'+((gIE)?118:120)+';top:16;width:10px;height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;"></span>'+
'<span id=iColorHist style="background:pink;position:absolute;left:0;top:16; width:12px;height:120px;overflow:hidden;cursor:default;cursor:hand;text-align:center;"></span>';
s+='<span style="position:absolute;left:0;top:15; width:130px;height:1px;overflow:hidden;background:#cccccc;"></span>';
s+='<div style="position:absolute;left:1;top:1;width:14;height:14;font-size:12pt;font-weight:bold;cursor:pointer;color:red;" '+
   'onclick="CP.close();try{BD.close();}catch(e){}">X</div>';
//   'onclick="_xhide(CP.bdy);try{_xhide(BD.box);}catch(e){}">X</div>';
s+='<div id=iColorHeader style="position:absolute;left:30;top:1;width:80px;background:white;font-family:sans-serif;font-size:8pt;color:#999999;">Background</div>';
s+='</DIV>';
document.write(s);}

cpColorPicker.prototype.open=function(win,o,att,x,y,func){
if(this.alreadyOpen(o,att)){this.close();return;}
var tmp,tgt,a,i,j,s;
this.win=win;
this.doc=win.document;
this.obj=o;
this.att=att;
this.func=func;
this.locked=false;
if(att=='opacity')this.tgt='this.obj.style.backgroundColor';
else this.tgt='this.obj.style.'+att;
//try{
 eval('tmp='+this.tgt+';');
 this.clr=this.CLR=tmp;
 this.op=this.OP=this.getObjOpacity();
 this.chgOpBoxColor(this.CLR);
 if(this.att!='opacity')this.updColorHist();
 this.config(this.att);
//}catch(e){alert(99);}
if(x!=null&&y!=null)_place(this.bdy,x,y);
_xshow(this.bdy);
}


cpColorPicker.prototype.close=function(){
try{_cpHide();}catch(e){}
_xhide(this.bdy);}

cpColorPicker.prototype.alreadyOpen=function(obj,att){
if(this.obj==obj&&this.att==att&&_xvz(this.bdy))return true;
return false;}

cpColorPicker.prototype.config=function(att){
var h=_obj('iColorHeader'),b=_obj('iColorBox').style,hist=_obj('iColorHist').style;
var c1=_obj('iColorSpan1').style,c2=_obj('iColorSpan2').style,g=_obj('iColorGrays').style;
var o=_obj('iColorOpacity').style,t=_obj('iColorTrans').style,p=_obj('iColorOpOff').style;
this.att=att;
if(this.att=='opacity'){
 o.display=t.display=p.display='block';
 c1.display=c2.display=g.display=hist.display='none';
 b.height='32px';
}else{
 o.display=t.display=p.display='none';
 c1.display=c2.display=g.display=hist.display='block';
 b.height='152px';
}
switch(att){
case 'color':
 h.innerHTML='foreground&nbsp;&nbsp;';
 this.clr=this.CLR=this.obj.style.color; 
 break;
case 'borderColor':
 h.innerHTML='borders&nbsp;&nbsp;';
 this.clr=this.CLR=this.obj.style.borderColor.split(' ')[0]; 
 break;
case 'backgroundColor':
 h.innerHTML='background&nbsp;&nbsp;';
 //this.clr=this.CLR=this.obj.style.backgroundColor;
 this.clr=this.CLR=this.obj.style.backgroundColor;
 break;
case 'opacity':
 h.innerHTML='opacity&nbsp;&nbsp;';
 //this.clr=this.CLR=this.obj.style.backgroundColor;
 this.clr=this.CLR=this.obj.style.background;
 this.chgOpBoxColor(this.CLR);
 break;
}
//alert('config: this.Clr='+this.CLR);;
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
if(!clr){
 e=_event(event);
 //x=(gIE)?e.offsetX:e.layerX;
 //y=(gIE)?e.offsetY:e.layerY;
 x=e.offsetX;
 y=e.offsetY;
 c=this.getColor(y,x);
 e.cancelBubble=true;
}else c=clr;
this.clr=window.status=c;
this.updObj(c);
if(this.att=='backgroundColor')this.chgOpBoxColor(c);
//alert('mv: this.Clr='+this.CLR);;
return false;}


cpColorPicker.prototype.upd=function(event,c){
if(event)_event(event).cancelBubble=true;
if(this.locked)return false;
if(c!=null)this.clr=c;
//this.locked=this.updObj(this.clr);
if(this.func)try{this.func(this.clr,this.att);}catch(e){}
switch(this.att){
 case 'color':this.addFgColor(this.clr);break;
 case 'backgroundColor':case 'borderColor':this.addBgColor(this.clr);break;
}
if(this.att!='opacity')this.updColorHist();
this.CLR=this.clr;
var lock=this.updObj(this.clr);
//_dbg("lock="+lock+" upd("+this.clr+")");
if(lock){this.locked=true;this.close();}
try{
//if(this.att=='color'||this.att=='backgroundColor')_logHist(this.doc,this.obj,this.att,this.clr);
}catch(e){}
return false;}

cpColorPicker.prototype.reset=function(event){
if(event)_event(event).cancelBubble=true;
if(this.locked)return false;
this.updObj(this.CLR);
if(this.att=='opacity')this.chgOpBoxColor(this.CLR);
this.clr=this.CLR;
//if(event)_event(event).cancelBubble=true;
}

cpColorPicker.prototype.addFgColor=function(c){
if(c=="#ffffff"||c=="#000000"||c=="black")return;
var i,j=0,a=this.fgColors;
for(i=a.length-1;i>-1&&j<10;i--){if(a[i]==c)return;j++;}
this.fgColors[this.fgColors.length]=c;}

cpColorPicker.prototype.addBgColor=function(c){
if(c=="#ffffff"||c=="white"||c=="#000000")return;
var i,j=0,a=this.bgColors;
for(i=a.length-1;i>-1&&j<10;i--){if(a[i]==c)return;j++;}
this.bgColors[this.bgColors.length]=c;}


cpColorPicker.prototype.updColorHist=function(){
var i,j=0,s='',a,ary;
a=(this.att=='color')?this.fgColors:this.bgColors;
ary=(this.att=='color')?'fgColors':'bgColors';
s='<table cellpadding=0 cellspacing=0>';
for(i=a.length-1;i>-1&&j<10;i--){
 s+='<tr><td onmouseover="CP.updObj(CP.'+ary+'['+i+'])" onmouseout="CP.reset()" onmousedown="CP.upd(null,CP.'+ary+'['+i+'])" style="background:'+a[i]+';width:12px;height:12px;overflow:hidden;"></td></tr>';
 j++;
}
_obj("iColorHist").innerHTML=s;}


//---- opacity -----
cpColorPicker.prototype.chgOpBoxColor=function(c){
if(!c)c='red';
for(var i=0;i<10;i++){eval('tdop'+i+'.style.background="'+c+'"');}}

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
sx=x-512;sy=y-512;
qx=(sx<0)?0:1;
qy=(sy<0)?0:1;
q=2*qy+qx;
qd=new Array(-180,360,180,0);
xa=Math.abs(sx);
ya=Math.abs(sy);
d=ya*45/xa;
if(ya>xa)d=90-(xa*45/ya);
deg=Math.floor(Math.abs(qd[q]-d));
sx=Math.abs(x-512);
sy=Math.abs(y-512);
r=Math.sqrt((sx*sx)+(sy*sy));
if(x==512&y==512)c="000000";
else{
 for(i=0;i<3;i++){
  r2=this.aCr[deg][i]*r/256;
  if(r>256)r2+=Math.floor(r-256);
  if(r2>255)r2=255;
  n=256*n+Math.floor(r2);
 }
 c=n.toString(16);
 while(c.length<6)c="0"+c;
}c="#"+c;return c;}

cpColorPicker.prototype.setEvents=function(){
this.box.onmousedown  =function(event){CP.upd(event);return false;}
this.box.onmousemove  =function(event){CP.mv(event);return false;}
this.box.onmouseout   =function(event){CP.reset(event);return false;}
//this.trans.onmousedown=function(event){CP.updTrans(event);return false;}
}


CP=new cpColorPicker();  //create the color box object

//Note: The "locked" and 'selection' issues screw up the draggable stuff for some reason!
//try{DG.open("iColorBox");}catch(e){} //make it draggable (requires drag.js)

