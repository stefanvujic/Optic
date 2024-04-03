<?
include_once "_inc_global.php";
include_once "_inc_header.php";
?>
<link href='http://fonts.googleapis.com/css?family=Nosifer|Aguafina+Script|Emilys+Candy|Economica|Cabin+Sketch|Henny+Penny|Snowburst+One|Carrois+Gothic|Waiting+for+the+Sunrise|Meie+Script|Monoton|Risque|Special+Elite|Julius+Sans+One|Merienda|Orbitron|Akronim' rel='stylesheet' type='text/css'>
<?

$fil=rqst("file");
$postaction=rqst("postaction");
$filtxt="";
if($fil==""){
	$action=myPost('action');
	if($action=="update"){
		//validate and save save new file
		updateText();
	}else{
		newText();
		exit();
	}
}else{
	$filtxt=myReadFile($dirlink.$fil);
}

//$filtxt=_rep($filtxt,"<","< ");
//echo($filtxt);
//exit();

if( $filtxt=="" || ( !_in($filtxt,"<DIV") && !_in($filtxt,"<div") ) ){
	if(_in($filtxt,"<"))$filtxt="";
	if($filtxt=="")$filtxt="Click here to edit this sample text";
	$filtxt="<DIV id='iTxt' style='width:100%;height:100%;overflow:hidden;resize:none;font-size:30px;".
			"font-family:Economica;text-align:center;letter-spacing:1px;color:#000000;background:transparent;'>".
			$filtxt."</DIV>";
}else{
	/* ???
	$a=_split($filtxt,"<textarea");
	$filtxt="<textarea".$a[1];
	$a=_split($filtxt,"</textarea");
	$filtxt=$a[0]."</textarea>";
	$filtxt=_rep($filtxt,"block;","none;");
	$filtxt=_rep($filtxt,"absolute;","relative;");
 	//$txt=str_replace("style='","style='display:none;",$txt);
	*/
}

$fontvals=array('Arial,Helvetica,sans-serif','Times New Roman,Times,serif','Courier,mono,sans-serif','Impact,sans-serif','Emilys Candy','Economica','Cabin Sketch','Henny Penny','Snowburst One','Waiting for the Sunrise','Meie Script','Monoton','Risque','Special Elite','Julius Sans One','Merienda','Orbitron','Akronim','Nosifer','Aguafina Script');
$sizevals=array('1','4','8','12','16','20','30','40','60','80','100','125','160','200','250','300','400','500','600','800','1000');
$scalingvals=array('vw','vh','px','pt','rem','em','%');
$spacingvals=array('0px','1px','2px','3px','4px','5px','6px','7px','8px','9px','10px','12px','14px','16px','20px','30px','50px','70px','115px','160px','280px');
?>
<SCRIPT src="_pvt_js/async.js"></SCRIPT>
<SCRIPT>
var fontvals=new Array('Arial,Helvetica,sans-serif','Times New Roman,Times,serif','Courier,mono,sans-serif','Impact,sans-serif','Emilys Candy','Economica','Cabin Sketch','Henny Penny','Snowburst One','Waiting for the Sunrise','Meie Script','Monoton','Risque','Special Elite','Julius Sans One','Merienda','Orbitron','Akronim','Nosifer','Aguafina Script');
var sizevals=new Array('1','4','8','12','16','20','30','40','60','80','100','125','160','200','250','300','400','500','600','800','1000');
var scalingvals=new Array('vw','vh','px','pt','rem','em','%');
var spacingvals=new Array('0px','1px','2px','3px','4px','5px','6px','7px','8px','9px','10px','12px','14px','16px','20px','30px','50px','70px','115px','160px','280px');
</SCRIPT>

<style>
.csstxt{color:#ffffff;font-family:monospace;font-size:8pt;}
.cssinp{font-family:arial;font-size:8pt;}
.mnusel2{background:#ffeecc;      border:solid 1px #ffcc99; color:#0066ff; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.mnuh2  {background:#ffffff;      border:solid 1px #ddeeff; color:#0066ff; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.mnu2   {background:transparent;  border:solid 1px #ddeeff; color:#0066ff; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.mnu2off{background:transparent;  border:solid 1px #ddeeff; color:#999999; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.eleon  {background:#ffffff;      color:black;cursor:pointer;padding-left:3px;padding-right:3px;font-size:8pt;font-family:serif;}
.eleoff {color:blue; cursor:pointer;padding-left:3px;padding-right:3px;font-size:8pt;font-family:serif;}
.btnSel{background:#ffeecc;  border:solid 1px #ffcc99; color:#0066ff; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.btnHi {background:#dddddd;  border:solid 1px #ddeeff; color:#0066ff; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.btnLo {background:#ffffff;  border:solid 1px #ddeeff; color:#0066ff; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
.btnOff{background:#ffffff;  border:solid 1px #ddeeff; color:#999999; text-decoration:none;cursor:pointer;font-family:arial,cursive;font-size:12;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;text-align:center;}
</style>


<SCRIPT>
var spacingbtn, fontbtn, sizebtn, scalingbtn;
var curspacing,curfont,cursize,curscaling;
var fontvals,sizevals,scalingvals,spacingvals;
var gTxt;
var postaction='<?=$postaction;?>';
//var gFile='<?=$gPath.$dir.$fil;?>';
var gFile='<?=$fil;?>';

//----------------------------------- onload -----------------------------------
function load(){
gTxt=_obj("iTxt");
curfont   	=_rep(gTxt.style.fontFamily,", ",",");
cursize   	=getSize(gTxt.style.fontSize);
curscaling	=getScaling(gTxt.style.fontSize); _obj("iScalingBtn").innerHTML=curscaling;
curspacing	=gTxt.style.letterSpacing;
fontbtn=getBtn(curfont,"iFont",fontvals,"");
sizebtn=getBtn(cursize,"iSize",sizevals,"");
scalingbtn=getBtn(curscaling,"iScaling",scalingvals,"");
spacingbtn=getBtn(curspacing,"iSpacing",spacingvals,"");
liteSel(fontbtn);
liteSel(sizebtn);
liteSel(scalingbtn);
liteSel(spacingbtn);
CP=new cpColorPicker();
}


//--------------------------- save file ----------------------------
function saveFile(){
var of1=_obj("f1");
var tmp=txt=gTxt.innerHTML;
txt=_rep(txt,"<","&!!!!#");
txt=_rep(txt,">","&!!!!$");
gTxt.innerHtml=txt;
var v=gTxt.outerHTML;
var font=gTxt.style.fontFamily;
_obj("iFontHtm").value=font;
_obj("iHtm").value=v;
of1.submit();
_busy(1);
gTxt.innerHtml=tmp;
}



function getBtn(v,x,vals,sfx){
for(var i=0;i<vals.length;i++){ 	
	var vi=vals[i]+sfx; 	
	if(vi==v) return _obj(x+i);
	if('"'+vi+'"'==v) return _obj(x+i);  		// if the font has 2 words then vi is contained within double quotes
}
msg("btn NOT FOUND ... "+v);
var o=_obj(x+'1');
return o;
}

//-------------- formatting functions ------------------
function eReload(){window.location.href=window.location.href;}
function eBold(){gTxt.style.fontWeight=gTxt.style.fontWeight=(gTxt.style.fontWeight=="bold")?"":"bold";}
function eItalic(){gTxt.style.fontStyle=gTxt.style.fontStyle=(gTxt.style.fontStyle=="italic")?"":"italic";}
function eUnderline(){gTxt.style.textDecoration=gTxt.style.textDecoration=(gTxt.style.textDecoration=="underline")?"":"underline";}
function eAlign(v){
switch(v){
	case 0 : v="left"; break; 
	case 1 : v="center"; break; 
	case 2 : v="right"; break; 
} gTxt.style.textAlign=gTxt.style.textAlign=v;}


function editText(){
var v=gTxt.innerHTML;
v=prompt("Enter the new text",v);
if(v)gTxt.innerHTML=v;
}

//---------- called by _edittextajx--------------------------------------
function doPostAction(){
//msg("postaction="+postaction);
//msg("gDir="+gDir+", gFile="+gFile);
switch(postaction){
	case "1"	:	// new layer - this is called by clicking "Add Text" in the LEFT "+" menu
		parent.gotoImg(getSrc(gDir,gFile));
		parent.closeWindow(1);
		break;
	case "2"	:	// new layer - "Add Text" in the RIGHT "+" menu
		parent.addLayer(gDir,gFile,"file");		
		parent.closeWindow(1);
	case "3"	:	// existing layer edited
		parent.refreshLayer(getSrc(gDir,gFile));
		parent.closeWindow(1);
	default	:
		parent.refreshLayer(getSrc(gDir,gFile));
		parent.closeWindow(1);
}}


function _busy(on){
var d,o=_obj("iBusy").style;
if(on==null){d=(o.display=="none")?"block":"none";}
else        {d=(on)?"block":"none";}
o.display=d;}

function openColor(){ CP.open(window,gTxt,'color',230,30,setColor); }
function setColor(v,att){ gTxt.style.color=v; }

function openBackground(){ CP.open(window,gTxt,'backgroundColor',260,30,setBgColor); }
function setBgColor(v,att){ gTxt.style.background=v; }

function closeAll(){
closeFont();
closeSize();
closeScaling();
closeSpacing();
}


//------------- font ----------------
function openFonts(v){
if(v==null)v=(_obj("iFontBox").style.display=="")?0:1;
if(!v){ closeFont(); return; }
curfont=gTxt.style.fontFamily;
_obj("iFontBox").style.display="";
}
function closeFont(){
curfont=gTxt.style.fontFamily;
_obj("iFontBox").style.display="none";
}
function tryFont(v){gTxt.style.fontFamily=v; }
function resetFont(){if(curfont)gTxt.style.fontFamily=curfont;}
function setFont(v,btn){curfont=v; gTxt.style.fontFamily=v;  fontbtn.className="btnLo"; fontbtn=btn; closeFont(); }


//------------- size ----------------
function openSize(v){
if(v==null)v=(_obj("iSizeBox").style.display=="")?0:1;
if(!v){ closeSize(); return; }
cursize=getSize(gTxt.style.fontSize);
_obj("iSizeBox").style.display="";
}
function closeSize(){
cursize=getSize(gTxt.style.fontSize);
_obj("iSizeBox").style.display="none";
}
function trySize(v){gTxt.style.fontSize=v+curscaling;  } 
function resetSize(){if(cursize)gTxt.style.fontSize=cursize+curscaling;		} 
function setSize(v,btn){cursize=v; gTxt.style.fontSize=v+curscaling;	 sizebtn.className="btnLo"; sizebtn=btn; closeSize(); }	
function getSize(v){ for(var i=0;i<scalingvals.length;i++){v=v.replace(scalingvals[i],"");} return v; }

//------------- scaling ----------------
function openScaling(v){
if(v==null)v=(_obj("iScalingBox").style.display=="")?0:1;
if(!v){ closeScaling(); return; }
curscaling=getScaling(gTxt.style.fontSize);
_obj("iScalingBox").style.display="";
}
function closeScaling(){
curscaling=getScaling(gTxt.style.fontSize);
_obj("iScalingBox").style.display="none";
}
function tryScaling(v){gTxt.style.fontSize=cursize+v;  } 
function resetScaling(){if(curscaling){  _obj("iScalingBtn").innerHTML=curscaling; gTxt.style.fontSize=cursize+curscaling;		}} 
function setScaling(v,btn){curscaling=v; _obj("iScalingBtn").innerHTML=v; gTxt.style.fontSize=cursize+v; scalingbtn.className="btnLo"; scalingbtn=btn; closeScaling(); }
function getScaling(v){ for(var i=0;i<scalingvals.length;i++){if(_in(v,scalingvals[i]))return scalingvals[i]; } return "px";}


//------------- spacing ----------------
function openSpacing(v){
if(v==null)v=(_obj("iSpacingBox").style.display=="")?0:1;
if(!v){ closeSpacing(); return; }
curspacing=gTxt.style.letterSpacing;
_obj("iSpacingBox").style.display="";
}
function closeSpacing(){
curspacing=gTxt.style.letterSpacing;
_obj("iSpacingBox").style.display="none";
//msg("CLOSE curspacing="+curspacing);
}
function trySpacing(v){gTxt.style.letterSpacing=v;}
function resetSpacing(){if(curspacing)gTxt.style.letterSpacing=curspacing;	}	//msg("RESET spacing="+curspacing); }
function setSpacing(v,btn){curspacing=v; gTxt.style.letterSpacing=v;  spacingbtn.className="btnLo"; spacingbtn=btn; closeSpacing(); 	}	//msg("SET spacing="+v);		}

//------------- utilities ----------------
function liteLo(o,curbtn){
//msg("o.id="+o.id);
//if(curbtn)msg("curbtn.id="+curbtn.id);
o.className=(o==curbtn)?"btnSel":"btnLo";
}
function liteHi(o){o.className="btnHi";}
function _lolite3(o){o.style.backgroundColor="";}
function _hilite3(o){o.style.backgroundColor="#ccddee";}

function liteSel(o,oldbtn){ 
if(oldbtn)oldbtn.className="btnLo"; 
o.className="btnSel"; }



</SCRIPT>


</HEAD>
<BODY onload="load()" onresize="" style="padding:0px;background:transparent;overflow:hidden;">


<!-- =================== MAIN TABLE =========================== -->
<TABLE cellspacing=0 cellpadding=0 style='position:absolute;top:0px;left:0px;width:100%;height:100%;overflow:hidden;background:transparent;'>
<tr><td style="height:30px;">

<!-- ================ TOOLBAR PANEL (ipanel1) ============================== -->
<DIV id=ipanel1 style="width:100%;height:30px; border:solid 1px #999999; overflow:hidden; display:block; background:#aaccff;">
<TABLE cellspacing=0 cellpadding=0 style="width:100%;">
<tr style='border-bottom:solid 1px #6699cc;'><td>
<?
wrt("<DIV id=itoolbar0>");
wrt("<TABLE cellspacing=0 cellpadding=0 style='width:100%;'><tr>");
wrt("<td width=5></td>");
wrt("<td width=28>".iBtn("btnfont","font","openFonts()","on","Font")."</td>");
wrt("<td width=28>".iBtn("btnsize","size","openSize()","on","Size")."</td>");
wrt("<td width=28>".scalingBtn()."</td>");
wrt("<td width=28>".iBtn("btnspacing","spacing","openSpacing()","on","Letter Spacing")."</td>");
wrt("<td width=28>".iBtn("btnbold","bold","eBold()","on","")."</td>");
wrt("<td width=28>".iBtn("btnitalic","italic","eItalic()","on","")."</td>");
wrt("<td width=28>".iBtn("btnunderline","underline","eUnderline()","on","")."</td>");
wrt("<td width=28>".iBtn("btnleft","justifyleft","eAlign(0)","on","")."</td>");
wrt("<td width=28>".iBtn("btncenter","justifycenter","eAlign(1)","on","")."</td>");
wrt("<td width=28>".iBtn("btnright","justifyright","eAlign(2)","on","")."</td>");
wrt("<td width=28>".iBtn("btncolor","color","openColor()","on","Text Color")."</td>");
wrt("<td width=28>".iBtn("btnbcolor","backcolor","openBackground()","on","Background Color")."</td>");
wrt("<td width=28>".iBtn("btnundo","undo","eReload()","on","")."</td>");
wrt("<td width=28>".iBtn("edit","edit","editText()","on","")."</td>");
wrt("<td align=right>".iBtn("btnsave","save","saveFile()","on","Save")."</td>");
//wrt("<td width=28 id='btnhtmTD' align=right style='display:none;'>".iBtn("btnhtm","edit","openText()","on","Edit Text")."</td>");
//wrt("<td width=28 id='btnviewTD' align=right>".iBtn("btnview","view","openText()","on","View Text")."</td>");
wrt("<td width=5></td>");
wrt("</tr></TABLE>");
wrt("</DIV>");
?>
</td></tr>
</TABLE>
</DIV>  <!--- end iPanel1 --->

</td></tr>

<!-- ==================== TEXT AREA ==================================== -->
<tr><td align=center onclick='editText()'>
<?=$filtxt;?>
</td></tr>

</TABLE> <!------ end of main table ---->


<!-- ==================== FLOATERS ==================================== -->

<?
makeFontBox();
makeSizeBox();
makeScalingBox();
makeSpacingBox();
//makePaddingBox();
?>


<IFRAME   id="ipost"  name="ipost" style='display:none;'></IFRAME>
<FORM id=f1 method=post action='_edittextajx.php' target='ipost' style='display:none;'>
<input type=hidden id=action name=action value='savfile'>
<input type=hidden id=postaction name=postaction value='<?=$postaction;?>'>
<input type=hidden id=dir name=dir value='<?=$dir;?>'>
<input type=hidden id=fil name=fil value='<?=$fil;?>'>
<input type=hidden id=iFontHtm name=iFontHtm value=''>
<TEXTAREA id='iHtm' name='iHtm'></TEXTAREA>
</FORM>


<DIV id=iBusy style="z-index:20;position:absolute;top:35;left:320;display:none;color:black;background:#ffffff;padding:10px;font-family:sans-serif;font-size:12pt;text-align:center;border:outset 1px #6699cc;">
<IMG src="_pvt_images/edit/busy.gif"><br>
<center><span style="position:relative;top:3px;">Processing...</span>
</DIV>


<!-- ===================== COLOR BOX ===================== -->
<DIV id="iColorBox" unselectable="on" style="DISPLAY:NONE; background:white;position:absolute;left:100px;top:100px;width:130px;height:150px;z-index:9999;overflow:hidden;border:solid 1px black;cursor:default;text-align:center;">

<!--- all the colors --->
<span id="iColorSpan1" style="position:absolute;left:11px;top:16px; width:119px;height:128px;cursor:default;background:white;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
		<td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#e1ffff;width:12px;height:12px;"></td><td style="background:#cbffff;width:12px;height:12px;"></td><td style="background:#cbffff;width:12px;height:12px;"></td><td style="background:#e1ffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td></tr><tr><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#f6ffff;width:12px;height:12px;"></td><td style="background:#b4ffff;width:12px;height:12px;"></td><td style="background:#82ffff;width:12px;height:12px;"></td><td style="background:#66ffff;width:12px;height:12px;"></td><td style="background:#66ffff;width:12px;height:12px;"></td><td style="background:#82ffff;width:12px;height:12px;"></td><td style="background:#b4ffff;width:12px;height:12px;"></td><td style="background:#f6ffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td></tr><tr><td style="background:#ffefff;width:12px;height:12px;"></td><td style="background:#b4b0ff;width:12px;height:12px;"></td><td style="background:#66a3ff;width:12px;height:12px;"></td><td style="background:#27a8ff;width:12px;height:12px;"></td><td style="background:#0ec3e6;width:12px;height:12px;"></td><td style="background:#0ee6c3;width:12px;height:12px;"></td><td style="background:#27ffa8;width:12px;height:12px;"></td><td style="background:#66ffa3;width:12px;height:12px;"></td><td style="background:#b4ffaa;width:12px;height:12px;"></td><td style="background:#ffffef;width:12px;height:12px;"></td></tr><tr><td style="background:#ffc8ff;width:12px;height:12px;"></td><td style="background:#bb6eff;width:12px;height:12px;"></td><td style="background:#3418ff;width:12px;height:12px;"></td><td style="background:#0b30bf;width:12px;height:12px;"></td><td style="background:#086b8e;width:12px;height:12px;"></td><td style="background:#088e6b;width:12px;height:12px;"></td><td style="background:#0bbf30;width:12px;height:12px;"></td><td style="background:#34ff18;width:12px;height:12px;"></td><td style="background:#c1ff6e;width:12px;height:12px;"></td><td style="background:#ffffc8;width:12px;height:12px;"></td></tr><tr><td style="background:#ffb3ff;width:12px;height:12px;"></td><td style="background:#e054ff;width:12px;height:12px;"></td><td style="background:#5e00e6;width:12px;height:12px;"></td><td style="background:#2c008e;width:12px;height:12px;"></td><td style="background:#03103f;width:12px;height:12px;"></td><td style="background:#033f10;width:12px;height:12px;"></td><td style="background:#2c8e00;width:12px;height:12px;"></td><td style="background:#5ee600;width:12px;height:12px;"></td><td style="background:#e6ff54;width:12px;height:12px;"></td><td style="background:#ffffb3;width:12px;height:12px;"></td></tr><tr><td style="background:#ffb3ff;width:12px;height:12px;"></td><td style="background:#ff54ff;width:12px;height:12px;"></td><td style="background:#a300e6;width:12px;height:12px;"></td><td style="background:#73008e;width:12px;height:12px;"></td><td style="background:#43002f;width:12px;height:12px;"></td><td style="background:#432f00;width:12px;height:12px;"></td><td style="background:#738e00;width:12px;height:12px;"></td><td style="background:#a3e600;width:12px;height:12px;"></td><td style="background:#ffff54;width:12px;height:12px;"></td><td style="background:#ffffb3;width:12px;height:12px;"></td></tr><tr><td style="background:#ffc8ff;width:12px;height:12px;"></td><td style="background:#ff6eff;width:12px;height:12px;"></td><td style="background:#ff18ff;width:12px;height:12px;"></td><td style="background:#ca008f;width:12px;height:12px;"></td><td style="background:#970024;width:12px;height:12px;"></td><td style="background:#972400;width:12px;height:12px;"></td><td style="background:#ca8f00;width:12px;height:12px;"></td><td style="background:#ffff18;width:12px;height:12px;"></td><td style="background:#ffff6e;width:12px;height:12px;"></td><td style="background:#ffffc8;width:12px;height:12px;"></td></tr><tr><td style="background:#ffefff;width:12px;height:12px;"></td><td style="background:#ff9dff;width:12px;height:12px;"></td><td style="background:#ff54ff;width:12px;height:12px;"></td><td style="background:#ff188e;width:12px;height:12px;"></td><td style="background:#f30023;width:12px;height:12px;"></td><td style="background:#f32300;width:12px;height:12px;"></td><td style="background:#ff8e18;width:12px;height:12px;"></td><td style="background:#ffff54;width:12px;height:12px;"></td><td style="background:#ffff9d;width:12px;height:12px;"></td><td style="background:#ffffef;width:12px;height:12px;"></td></tr><tr><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffdcff;width:12px;height:12px;"></td><td style="background:#ff9dff;width:12px;height:12px;"></td><td style="background:#ff6ee0;width:12px;height:12px;"></td><td style="background:#ff5479;width:12px;height:12px;"></td><td style="background:#ff7454;width:12px;height:12px;"></td><td style="background:#ffda6e;width:12px;height:12px;"></td><td style="background:#ffff9d;width:12px;height:12px;"></td><td style="background:#ffffdc;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td></tr><tr><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffefff;width:12px;height:12px;"></td><td style="background:#ffc8ff;width:12px;height:12px;"></td><td style="background:#ffb3d5;width:12px;height:12px;"></td><td style="background:#ffd5b3;width:12px;height:12px;"></td><td style="background:#ffffc8;width:12px;height:12px;"></td><td style="background:#ffffef;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td><td style="background:#ffffff;width:12px;height:12px;"></td>
	</tr></tbody></table>
</span>

<!-- for mouse events -->
<span id="iColorSpan2" style="position:absolute;left:11px;top:16px; width:119px;height:128px;cursor:default;cursor:hand;text-align:center;"></span>


<!-- opacity off --->
<span id="iColorOpOff" style="DISPLAY:NONE;position:absolute;left:0px;top:16px;width:12px; height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td id="tdop0" style="background:red;overflow:hidden;width:12px;height:16px;" onmouseover="CP.setOp(0)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td>
	</tr></tbody></table>
</span>

<!-- opacity --->
<span id="iColorOpacity" style="position:absolute;left:12;top:16;width:108px; height:16px;cursor:default;overflow:hidden;font-family:monospace;font-size:8pt;text-align:center;background:white;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td id="tdop9" style="filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9;width:14px;height:16px;background:red;" onmouseover="CP.setOp(9)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop8" style="filter:alpha(opacity=80);-moz-opacity:0.8;opacity:0.8;width:14px;height:16px;background:red;" onmouseover="CP.setOp(8)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop7" style="filter:alpha(opacity=70);-moz-opacity:0.7;opacity:0.7;width:14px;height:16px;background:red;" onmouseover="CP.setOp(7)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop6" style="filter:alpha(opacity=60);-moz-opacity:0.6;opacity:0.6;width:14px;height:16px;background:red;" onmouseover="CP.setOp(6)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop5" style="filter:alpha(opacity=50);-moz-opacity:0.5;opacity:0.5;width:14px;height:16px;background:red;" onmouseover="CP.setOp(5)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop4" style="filter:alpha(opacity=40);-moz-opacity:0.4;opacity:0.4;width:14px;height:16px;background:red;" onmouseover="CP.setOp(4)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop3" style="filter:alpha(opacity=30);-moz-opacity:0.3;opacity:0.3;width:14px;height:16px;background:red;" onmouseover="CP.setOp(3)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop2" style="filter:alpha(opacity=20);-moz-opacity:0.2;opacity:0.2;width:14px;height:16px;background:orange;" onmouseover="CP.setOp(2)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td><td id="tdop1" style="filter:alpha(opacity=10);-moz-opacity:0.1;opacity:0.1;width:14px;height:16px;background:red;" onmouseover="CP.setOp(1)" onmouseout="CP.resetOp()" onmousedown="CP.updOp()"></td>
	</tr></tbody></table>
</span>

<!--- the grays --->
<span id="iColorGrays" style="position:absolute;left:0px;top:136px;width:130px;height:16px;cursor:default;overflow:hidden;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td style="background:#000000;width:12;height:16px;" onmousemove="CP.mv(null,'#000000')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#333333;width:12;height:16px;" onmousemove="CP.mv(null,'#333333')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#555555;width:12;height:16px;" onmousemove="CP.mv(null,'#555555')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#666666;width:12;height:16px;" onmousemove="CP.mv(null,'#666666')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#777777;width:12;height:16px;" onmousemove="CP.mv(null,'#777777')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#999999;width:12;height:16px;" onmousemove="CP.mv(null,'#999999')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#aaaaaa;width:12;height:16px;" onmousemove="CP.mv(null,'#aaaaaa')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#cccccc;width:12;height:16px;" onmousemove="CP.mv(null,'#cccccc')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#dddddd;width:12;height:16px;" onmousemove="CP.mv(null,'#dddddd')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#eeeeee;width:12;height:16px;" onmousemove="CP.mv(null,'#eeeeee')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td><td style="background:#ffffff;width:10;height:16px;" onmousemove="CP.mv(null,'#ffffff')" onmouseout="CP.reset()" onmousedown="CP.upd()"></td>
	</tr></tbody></table>
</span>

<!-- transparent box used for opacity only? -->
<span id="iColorTrans" style="position: absolute; left:120px; top:16px; width: 10px; height: 16px; cursor: pointer; overflow: hidden; font-family: monospace; font-size: 8pt; text-align: center; background-color: white;">
	<table cellpadding="0" cellspacing="0"><tbody><tr>
	<td style="overflow:hidden;width:10px;height:16px;" onmouseover="CP.setTrans()" onmouseout="CP.resetTrans()" onmousedown="CP.updTrans()"></td>
	</tr></tbody></table>
</span>

<!--- the color history (with defaults) --->
<span id="iColorHist" 	style="background:white;position:absolute;left:0px;top:16px; width:12px;height:120px;overflow:hidden;cursor:default;cursor:hand;text-align:center;">

</span>

<!-- other -->
<div id="iColorClose" 	style="position:absolute;left:-1px;top:-2px;width:14px;height:14px;font-size:12pt;font-weight:bold;font-family:monospace;cursor:pointer;color:red;" onclick="CP.close();try{BD.close();}catch(e){}">X</div>
<div id="iColorHeader" 	style="display:;position:absolute;left:30px;top:1px;width:80px;background:white;font-family:sans-serif;font-size:8pt;color:#999999;"></div>


<!-- transparent box used for opacity only? -->
<div id="iCPHeader" 	style="position:absolute;right:0px;top:1px;width:80px;background:white;font-family:sans-serif;font-size:8pt;color:#999999;">
	<img src="_pvt_images/trans.gif" onmouseover="CP.setTrans()" onmouseout="CP.resetTrans()" onmousedown="CP.updTrans()" style="position:absolute;right:3px;top:1px;width:20px;cursor:pointer;" title="transparent">
</div>

</DIV>


<SCRIPT src="_pvt_js/_colors.js"></SCRIPT>
</BODY>
</HTML>


<?
//============================== PHP FUNCTIONS ==================================


function makeFontBox(){
global $fontvals;
?><DIV id=iFontBox style="display:none; position:absolute;left:2px;top:31px;width:85px;z-index:10;overflow:hidden;display:none;border:solid 1px #cccccc;cursor:default;font-size:10pt;background:white;"><?
for($i=0;$i<sizeof($fontvals);$i++){
 		$v=$fontvals[$i];
		$class="btnLo";
		$short=_leftStr($v,",");
 		?><div id='iFont<?=$i;?>' class='btnLo' style="font-family:<?=$v;?>;width:100%;height:16px;overflow:hidden;text-align:left;" onmouseover="tryFont('<?=$v;?>');liteHi(this);" onmouseout="resetFont();liteLo(this,fontbtn);" onmousedown="setFont('<?=$v;?>',this);liteSel(this,fontbtn);"><?=$short;?></div><?
}
?></DIV><?
}

function makeSizeBox(){
global $sizevals;
?><DIV id=iSizeBox style="display:none; position:absolute;left:33px;top:31px;width:50px;z-index:10;overflow:hidden;display:none;border:solid 1px #cccccc;cursor:default;font-size:10pt;background:white;"><?
for($i=0;$i<sizeof($sizevals);$i++){
		//$sizevals[$i].="vw";
 		$v=$sizevals[$i];
		?><div id='iSize<?=$i;?>' class=btnLo style="width:100%;height:16px;overflow:hidden;text-align:left;"  onmouseover="trySize('<?=$v;?>');liteHi(this);" onmouseout="resetSize();liteLo(this,sizebtn);" onmousedown="setSize('<?=$v;?>',this);liteSel(this,sizebtn);"><?=$v;?></div><? 
}
?></DIV><?
}

function makeScalingBox(){
global $scalingvals;
?><DIV id=iScalingBox style="display:none; position:absolute;left:60px;top:31px;width:50px;z-index:10;overflow:hidden;display:none;border:solid 1px #cccccc;cursor:default;font-size:10pt;background:white;"><?
for($i=0;$i<sizeof($scalingvals);$i++){
		//$scalingvals[$i].="vw";
 		$v=$scalingvals[$i];
		?><div id='iScaling<?=$i;?>' class=btnLo style="width:100%;height:16px;overflow:hidden;text-align:left;"  onmouseover="tryScaling('<?=$v;?>');liteHi(this);" onmouseout="resetScaling();liteLo(this,scalingbtn);" onmousedown="setScaling('<?=$v;?>',this);liteSel(this,scalingbtn);"><?=$v;?></div><? 
}
?></DIV><?
}

function makeSpacingBox(){
global $spacingvals;
?><DIV id=iSpacingBox style="display:none; position:absolute;left:90px;top:31px;width:50px;z-index:10;overflow:hidden;display:none;border:solid 1px #cccccc;cursor:default;font-size:10pt;background:white;"><?
for($i=0;$i<sizeof($spacingvals);$i++){
 		$v=$spacingvals[$i];
 		?><div id='iSpacing<?=$i;?>' class=btnlo style="width:100%;height:16px;overflow:hidden;text-align:left;" onmouseover="trySpacing('<?=$v;?>');liteHi(this);" onmouseout="resetSpacing();liteLo(this,spacingbtn);" onmousedown="setSpacing('<?=$v;?>',this);liteSel(this,spacingbtn);"><?=$v;?></div><?
}
?></DIV><?
}

function eBtn($id,$nam,$act,$status,$width,$ttip){
//** status=none - no off btn
//** status=on   - off btn=off
//** status=off  - off btn=on
$ttip="";
$ht="20";
if($width!="")$width="width:".$width.";";
$sty=$width.
"height:".ht."px;overflow:hidden;padding-left:3px;padding-right:3px;padding-top:1px;padding-bottom:0px;".
"font-family:sans-serif;font-size:14pt;text-align:center;".
"border-top:solid 0px #cceeff;border-left:solid 0px #cceeff;".
"border-bottom:solid 0px #6699cc;border-right:solid 0px #6699cc;".
"background:#aaccff;";
if($status=="off"){
 $sty1="color:#333333;cursor:pointer;display:none;".$sty;
 $sty2="color:#999999;cursor:default;display:block;".$sty;
}else{
 $sty1="color:#333333;cursor:pointer;display:block;".$sty;
 $sty2="color:#999999;cursor:default;display:none;".$sty;
}
$txt="<div id='".$id."On' style='".$sty1."' ";
//$txt=$txt."onmouseover='_hilite3(this,\"".$ttip."\")' onMouseout='_lolite3(this)' onclick='".$act."'>".$nam."</div>";
$txt=$txt."onmouseover='_hilite3(this)' onMouseout='_lolite3(this)' onclick='".$act."'>".$nam."</div>";
if($status!="none")$txt=$txt."<div id='".$id."Off' style='".$sty2."'>".$nam."</div>";
return $txt;
}


function iBtn($id,$nam,$act,$status,$ttip){
//** status=none - no off btn
//** status=on   - off btn=off
//** status=off  - off btn=on
$imgxy="width:28px;height:28px;";
switch($nam){
	case "save" : 
		$imgxy="width:40px;height:34px;position:relative;top:-2px;";
 		$imgname="save1.png" ; 
		break;
	case "view" : 
		$imgxy="width:26px;height:26px;";
 		$imgname="edit.png" ; 
		break;
	case "edit" : 
		$imgxy="width:20px;height:19px;padding:4px;";
 		$imgname="pencil.png" ; 
		break;
	case "undo" : 
 		$imgname="reset.png" ; 
		break;
	default :
 		$imgname="edit/".$nam.".gif" ; 
		break;
}
$styOn="cursor:pointer;".$imgxy;
$txt=$txt."<img id='".$id."On' src='_pvt_images/".$imgname."' style='".$styOn.";' ";
$txt=$txt."onmouseover='_hilite3(this)' onMouseout='_lolite3(this)' onclick='".$act."' title='".$ttip."'>";
return $txt;
}

function scalingBtn(){
$txt="<div id='iScalingBtn' onclick='openScaling()' onmouseover='_hilite3(this)' onMouseout='_lolite3(this)' ".
	 "style='font-size:20px;font-weight:bold;color:black;text-align:center;cursor:pointer;height:24px;width:30px;position:relative;top:-2px;' title='size units'>".$curscaling."</div>";
return $txt;
}


//========================= NEW TEXT ==============================
function newText(){
global $movers,$dirlink,$dir,$postaction;
$displaydir="";
$name=myPost('fname');
$txt=myPost("itxt");
if($txt=="")$txt="Sample Text";
if(empty($name))$name=rand(1000,99999);
if(!empty($dir))$displaydir.=$dir."/";


/*
// default name and text and go straight to "edit"?
prompt for name?

		$fil=$name.".htm";
		$filtxt=$txt;
  		myWriteFile($dirlink.$fil,$txt);  
  		reloadMenu($dir,0,1,0);
*/



?>
</HEAD>
<BODY>
<FORM name="f1" method="post" action="<?=$_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
<input type=hidden value='sample text' name=itxt>
<input type=hidden value='update' name=action>
<input type=hidden id='dir'   name='dir'   value='<?=$dir;?>'>
<input type=hidden id='postaction'   name='postaction'   value='<?=$postaction;?>'>
<center>
	<br><br><br>
	<TABLE cellpadding=0 cellspacing=5 style='font-size:22px;'>
	<tr>
		<td align=center style='font-size:30px;letter-spacing:2px;'><b>Name:</b>&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
		<td class=foldername style='font-size:28px;color:#ff6666;'>/<?=$displaydir;?></span></td>
		<td><input type=text name=fname id="iname" value='<?=$name;?>'style='width:180;font-size:30px;letter-spacing:2px;'></td>
	</tr>
	<tr>
	<td colspan=3 align=center><br><input class='btn' type="submit" value="   CREATE   " <?=$movers;?> style='letter-spacing:2px;height:40;font-size:28px;'></td>
	</tr>
	</TABLE>
</center>
</FORM>
<script>document.getElementById("iname").focus();</script>
</BODY></HTML>
<?
}

function updateText(){
	global $fil, $filtxt, $dirlink,$dir;
	$name=myPost("fname");
	$txt=myPost("itxt");
	if($txt=="")$txt="Click here to edit this sample text";
	if(empty($name)){
		newText();
		exit();
  		//errorMsg("please enter a name for the file");
 	}else{
  		$name=str_replace(".","",$name);
  		$txt=str_ireplace("<","&lt; ",$txt);					// we don't allow html tags in the text itself
		if(empty($name)){ newText(); exit(); }
		$fil=$name.".htm";
		$filtxt=$txt;
  		myWriteFile($dirlink.$fil,$txt);  
  		reloadMenu($dir,0,1,0);
	}
}

?>


