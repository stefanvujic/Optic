<?

/* Copyright ©, 2005, Walter Long */



include_once "_inc_global.php";





//include_once "_inc_header.php";



/* Copyright ©, 2005, Walter Long */



//======================= COPIED FROM _inc_header.php =========================



//================ Default page header, styles, and scripts ====================

// NOTE: 1. It requires that _inc_global.php has been loaded first.

//       2. The <HEAD> still needs to be closed by the calling page.

//==============================================================================



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



<?



//====================== END OF _inc_header.php =====================



//----- login token ----

if($gLoggedIn)$updDisplay="";

else $updDisplay="DISPLAY:NONE;";



//----- get the vars and options for this dir -----

$noreload=myGet('noreload'); //whether to reload the content iframe

$tmp=myGet('autoload'); //overrides 'noreload' the first time (see _toolbar.php)

if($tmp)$autoload=$tmp;

else $autoload="no";



$grooze=myGet('grooze');

if($grooze!="")$gFetchFromGrooze=$grooze;



//--- overrides for this page

if($gIE){

 $btnh="19";

 $btnw="19";

 $btntop='1';

 $popozie="";

}else{

 $btnh="16";

 $btnw="14";

 $btntop='2';

 //$popozie="position:absolute;";

 $popozie="";

}

$btnbg=$btnbgcolor;

$btnbh=$btnhicolor;

$btnfg=$btncolor;

$btnbc=$btnbordercolor;



if($optonlyimages=="yes"){

	$showfolders=0;

	$showvids	=0;

	$showfiles	=0;

	$showactions=0;

	$showpixis	=0;

	$showfilters=0;

	$showpics	=1;

}



$iwin="iwin";

srand((double)microtime()*1000000);

$vstart="";

$vbreak="";

$vbreak2="<div style='height:2px;'></div>";

$vbreak5="<div style='height:5px;'></div>";

$vbreak8="<div style='height:8px;'></div>";

$flistwidth='124px';

$ddwidth="88px";

$vbigbreak="<div style='height:10px;'></div>";

$tmp_mnuborder="";  //options?





//==================== get the listings =====================

$fileslist="";

$filterslist="";

$pixislist="";

$rekordslist="";

$dirslist="";

$imageslist="";

$videoslist="";

$gImage="";

$gFile="";

$gPixi="";

$gRekord="";

$gVideo="";

$gFilter="";



$folderix=0;

$imgix=0;

$filix=0;

$pixix=0;

$rekix=0;

$vidix=0;

$fltix=0;



$folders="";

$images="";

$files="";

$videos="";

$filters="";

$linkwins="";

$titles="";

$pixis="";

$rekords="";



$imgcnt=0;

$filcnt=0;

$pixcnt=0;

$rekcnt=0;

$dircnt=0;

$vidcnt=0;

$lnkcnt=0;



$filenames=array();

$OtherImages=array();

$ThumbImages=array();

$NoThumbs=array();



$dir_handle=@opendir($dirpath) or die("Unable to open $dirpath");

while($filename=readdir($dir_handle))$filenames[]=$filename;

sort($filenames);

foreach($filenames as $filename){

  $file=$filename;

  if(_ix($file,"_pvt")==0 && $file!=$videofile)continue;

  if(!$gAdmin && ($file==$fileid || $file=="_pvt"))continue;  //never show id file

  $ext=get_ext($file);

  $name=getName($file);

  if($file=="."||$file==".."||$name==""||$name=="error_log"||$name=="cgi-bin"||$name=="index")continue;

  $typ=myFileType($file);

  //wrtb("file=".$file);

  //wrtb("typ=".$typ);

  if(!$typ && $ext!=".txt")continue;

  $tmp="";

  $a=explode(".",$file);

  if($typ!="folder"){		// blank=videofile, etc

   //wrtb("FILE=".$file);   

   $url=$gPath.$dirlink.$file;

   if(isImage($file)){

    //---images

    if(strstr($file,"_tn.")>-1 || isSvg($file)){

	     $imgcnt++;

	     $big_file=str_replace("_tn.",".",$file);

	     $ThumbImages[]=$big_file;

	     $tmp=$vstart."<DIV id=img_div_".$imgix." class=c_thumbdivs oncontextmenu='return false;'>";

	     $tmp=$tmp."<img id=img_".$imgix." class=c_thumbs src='".((rqst("selectvar")=="ssframeimage")?_rep($url,"_tn.","."):$url)."' onmousedown='popup(event,".$imgix.",this)' onclick='gotoImage($imgix,\"$url\")'>";

	     $tmp=$tmp."<DIV id=img_mnu_".$imgix." onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>";

	     $tmp=$tmp."</DIV>".$vbreak;

	     if(empty($gImage))$gImage=$dir."/".$big_file;

	     $typ="image";

	     $imgix+=1;

	     $images.="'$dirlink$file',";

    }else{

	     $OtherImages[]=$filename;

    }

   }else{

    //---other files

    if($file==$videofile){

     $tmp=loadVideosFile($file); 

     //wrtb("tmpt ".$tmp);

     $typ="video";

    }

	

	//if(_in($file,".ix"))

	//wrtb("file=".$file);

	

    if( $file!=$videofile){



      //--- pixi files ---

     if(isPixi($file)){

		  //if(_in($file,"_lastpic"))continue;

	      $pixcnt++;

	      $tmp="<DIV id=pix_".$pixix." class=lnk ".$lnkmovers." style=' min-width:100px; padding-right:8px; ".$tmp_mnuborder.";padding-bottom:0px;'  oncontextmenu='return false;' onmousedown='popup(event,".$pixix.",this)'  onclick='gotoPixi($pixix,\"$url\")'>"._rep($file,".ixi","")."</DIV>";

	      //$tmp="<DIV id=pix_".$pixix." class=lnk ".$lnkmovers." style=' min-width:100px; padding-right:8px; ".$tmp_mnuborder.";padding-bottom:0px;'  oncontextmenu='return false;' onmousedown='popup(event,".$pixix.",this)'  onclick='parent.applyPixi(\"$url\",gDir)'>"._rep($file,".ixi","")."</DIV>";

	      $tmp.="<DIV id=pix_mnu_".$pixix." contextmenu='return false;' onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV></td></tr>";

	      if(empty($gPixi))$gPixi=$dir."/".$file;

	      $typ="pixi";

	      $pixix+=1;

	      $pixis.="'$dirlink$file',";

     }else{



      //--- filter files ---

     if(isFilter($file)){

		  //wrtb("<br><br><br>filter=".$file);

		  //if(_in($file,"_lastpic"))continue;

	      $fltcnt++;

	      $tmp="<DIV id=flt_".$fltix." class=lnk ".$lnkmovers." style=' min-width:100px; padding-right:8px; ".$tmp_mnuborder.";padding-bottom:0px;'  oncontextmenu='return false;' onmousedown='popup(event,".$fltix.",this)'  onclick='applyFilter($fltix,\"$url\")'>"._rep($file,".ixx","")."</DIV>";

	      $tmp.="<DIV id=flt_mnu_".$fltix." contextmenu='return false;' onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV></td></tr>";

	      if(empty($gFilter))$gFilter=$dir."/".$file;

	      $typ="filter";

	      $fltix+=1;

	      $filters.="'$dirlink$file',";

     }else{



     //--- rekord files ---

     if(isRekord($file)){

	      $rekcnt++;

	      //if($usedropdowns=="yes"){

	      // $tmp="<option value='$url'>$file</option>";

	      //}else{

	       $tmp="<DIV id=rek_".$rekix." class=lnk ".$lnkmovers." style=' min-width:100px; padding-right:8px; ".$tmp_mnuborder.";padding-bottom:0px;'  oncontextmenu='return false;' onmousedown='popup(event,".$rekix.",this)'  onclick='gotoRekord($rekix,\"$url\")'>"._rep($file,".iki","")."</DIV>";

	       $tmp.="<DIV id=rek_mnu_".$rekix." contextmenu='return false;' onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV></td></tr>";

	      //}

	      if(empty($gRekord))$gRekord=$dir."/".$file;

	      $typ="rekord";

	      $rekix+=1;

	      $rekords.="'$dirlink$file',";

     }else{





      //------------------------------------ regular files (.htm/text) --------------------------------------------- 

      $filcnt++;

      //if($usedropdowns=="yes"){

      // $tmp="<option value='$url'>$file</option>";

      //}else{

       $tmp="<DIV id=fil_".$filix." class=lnk ".$lnkmovers." style=' min-width:100px; padding-right:8px; padding-bottom:0px; ".$tmp_mnuborder.";'  oncontextmenu='return false;' onmousedown='popup(event,".$filix.",this)'  onclick='gotoFile($filix,\"$url\")'>"._rep($file,".htm","")."</DIV>";

       $tmp=$tmp."<DIV id=fil_mnu_".$filix." oncontextmenu='return false;' onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>";

      //}

      if(empty($gFile))$gFile=$dir."/".$file;

      $typ="file";

      $filix+=1;

      $files.="'$dirlink$file',";

   }}}}}

  }else{

  

   //-----------------------------------------FOLDERS ------------------------------------------------------------

   if(  count($a)<2 &&                          //check it's a folder

        //($gAdmin || !_in($file,"_pvt")) &&      //only domain admin can look in _pvt

        (!_in($file,"_pvt")) && 

		//can only look at root folders or your own folder(s)

		($gAdmin || $gAcctAdmin || $gID==$file || $file=="Frames")

     ){  

    $tmpdir=getShortName($file,17); 

    $dircnt++;

    //if($usedropdowns=="yes"){

    // $tmp="<option value='$file'>$tmpdir</option>";

    //}else{

     $tmp="<DIV id='fdr_".$folderix."' class=lnk ".$lnkmovers." style=' min-width:100px; padding-right:8px;' oncontextmenu='return false;' onmousedown='popup(event,".$folderix.",this)' onclick='deselectFile();gFolderIX=$folderix;gotoDir(\"$dirlink$file\")'";

     if(strlen($dirlink.$file)!=strlen($tmpdir))$tmp.=" title='$dirlink$file'";

     $tmp.=">$tmpdir</DIV>";

     $tmp.="<DIV id=fdr_mnu_".$folderix." oncontextmenu='return false' onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;z-index:2;left:-1px;".$popozie."'></DIV>";

    //}

    $typ="folder";

    $folderix+=1;

    $folders.="'$dirlink$file',";

   }

  }

  if($tmp!=""){

   switch($typ){

    case "image" :$imageslist.=$tmp; break;

    case "file"  :$fileslist.=$tmp;  break;

    case "pixi"  :$pixislist.=$tmp;  break;

    case "rekord":$rekordslist.=$tmp;break;

    case "folder":$dirslist.=$tmp;   break;

    case "video" :$videoslist.=$tmp; break;

    case "filter":$filterslist.=$tmp;break;

  }$tmp="";}

}



//wrtb("<br>filters="._rep($filters,"<","< ")."<br><br>");  

//exit();



//--- check for images with no thumbnail ---

foreach($OtherImages as $filename){

	 $found=0;

	 foreach($ThumbImages as $thumb){

		  //if(strtolower($filename)==strtolower($thumb))$found=1;

		  if($filename==$thumb)$found=1;

	 }

	 //if($found==0){$NoThumbs[]=$filename; wrtb("<font color=white>NOT found!");}

	 //else wrtb("<font color=white>found ok!");

	 if($found==0)$NoThumbs[]=$filename;

}



foreach($NoThumbs as $filename){

	 //--- auto create thumbs if necessary ------

	

	 $imgcnt++;

	 $ext=get_ext($filename);

	 $root=get_root($filename);

	 $thumbname=thumbName($filename);

	

	 //--- create the thumb ---

	 if(!_in($dir,"_pvt")){

		  resize($dir."/".$filename , $dir."/".$thumbname , 100,80,$ext);

		  $filename=$thumbname;

	 }

	 //--- add new thumb (or image if in _pvt) to imagelist ----

	 if(empty($gImage))$gImage=$dir."/".$filename;

	 $url=$gPath.$dirlink.$filename;

	 $tmp=$vstart."<DIV id=img_div_".$imgix." class=c_thumbdivs oncontextmenu='return false;' onmousedown='popup(event,".$imgix.",this)' onclick='gotoImage($imgix,\"$url\")'>";

	 $tmp=$tmp."<img id=img_".$imgix." class=c_thumbs src='$url'>";

	 $tmp=$tmp."<DIV id=img_mnu_".$imgix." onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>";

	 $tmp=$tmp."</DIV>".$vbreak;

	 $typ="image";

	 $imgix+=1;

	 $images.="'$dirlink$filename',";

	 $imageslist=$imageslist.$tmp;

	 //$images 		= "'$dirlink$filename',".$images;

	 //$imageslist	= $tmp.$imageslist;

}





$folders=rtrim($folders,",");

$images=rtrim($images,",");

$files=rtrim($files,",");

$filters=rtrim($filters,",");

$pixis=rtrim($pixis,",");

$rekords=rtrim($rekords,",");

closedir($dir_handle);



//echo($lastimage);

//natsort($images);



//======================= write the page ===================

 $ddviz=false;

 $playviz="display:none;";

 $fileviz="display:block;";

 $filterviz="display:block;";

 $pixiviz="display:block;";

 $rekordviz="display:block;";

 $spacerheight=25;

 $fldrtop="0";

 if($dir=="")$spacerheight-=20;

 if(!$showfolders)$spacerheight-=10;

 if($spacerheight<0)$spacerheight=0;









?>

<style>

.c_fldr    {font-size:<?=$fontsize;?>px;color:#996666;cursor:default;}

.c_ddaction{width:<?=$ddwidth?>;height:20;overflow:hidden;}

.c_spacer  {height:<?=$spacerheight;?>px;overflow:hidden;}

.c_files   {<?=$fileviz;?>;}

.c_flist   {width:100%;height:100%; overflow-y:auto; overflow-x:hidden; scrollbar-base-color:<?=$bgcolor;?>;}

.c_popupmnu{position:relative;left:0px;}

.c_barbtn{DISPLAY:NONE;width:14px;height:14px;position:relative;top:-2px;cursor:pointer;opacity:0.6;}

</style>



<script>

var gAction='';

var gFolders=new Array(<?=$folders;?>);

var gImages=new Array(<?=$images;?>);

var gFiles=new Array(<?=$files;?>);

var gPixis=new Array(<?=$pixis;?>);

var gRekords=new Array(<?=$rekords;?>);

var gVideos=new Array(<?=$videos;?>);

var gFilters=new Array(<?=$filters;?>);

var gLinkWins=new Array(<?=$linkwins;?>);

var gTitles=new Array(<?=$titles;?>);

var gFolder='',gImage='<?=$gImage;?>',gFile='<?=$gFile;?>',gPixi='<?=$gPixi;?>',gRekord='<?=$gRekord;?>',gVideo='<?=$gVideo;?>',gFilter='<?=$gFilter;?>';

var gPopup,gFolderIX,gFilIX,gPixIX,gFltIX,gRekIX,gImgIX,gVidIX,gLnkIX;

var ssItems='<?=$ssitems;?>';

var dircnt=<?=$dircnt;?>;

var filcnt=<?=$filcnt;?>;

var pixcnt=<?=$pixcnt;?>;

var rekcnt=<?=$rekcnt;?>;

var imgcnt=<?=$imgcnt;?>;

var vidcnt=<?=$vidcnt;?>;

var lnkcnt=<?=$lnkcnt;?>;









//---------------------- flipping menu functions -------------------------------

var gFlips="1,1,1,1,1,1,1";

var gMenuFmt,gLastMenuTyp="all";





function popup(event,ix,ownr,typ,func){

var e=_event(event),rbtn=0;

if(!gIE){if(e.which==3)rbtn=1;}

else    {if(event.button==2)rbtn=1;}

if(!rbtn){

 if(func)func();  //--- NOTE: This way of passing the click function is only used by flipImages() currently (both ways work in Chrome)

 return false;    //--- Both approaches work but keep this way in case other browsers don't like having a onmousedown AND a onclick event on the element

}

var txt, oid=ownr.id;

if(typ==null)typ=oid.substring(0,3);

//alert("popup: oid="+oid+", typ="+typ);

var id=typ+"_mnu_"+ix, mnu=_obj(id);

if(gPopup && gPopup!=mnu)gPopup.style.display="none";

gPopup=mnu;

//if(mnu.style.display=="none" && typ!="flt"){

if(mnu.style.display=="none"){

	 if(mnu.innerHTML==""){

	  var mnutyp=(typ=="fdr")?"dir":"file";

	  //if(typ=="flt")mnutyp="filter";

	  txt="<TABLE cellpadding=1 cellspacing=0 style='z-index:9999; position:relative;width:100px;overflow-x:hidden;background:<?=$btnbgcolor;?>;color:<?=$btncolor;?>;border:solid 1px <?=$btnbordercolor;?>;'>";

	  //-------------- titles ------------

	  if(_in(typ,"tit")){

		   //msg(typ);

		   	if(typ=="vidtit"){

				txt+="<tr><td colspan=2>"+popupItem(typ,ix,"open playlist","slideshow")+"</td></tr>";

	   			//txt+="<tr><td colspan=2>"+popupItem(typ,ix,"popup","popup"+mnutyp)+"</td></tr>";

		   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"copy all","copy2all")+"</td></tr>";

		   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"clipboard","openClipboard")+"</td></tr>";

			}				

			if(typ=="imgtit"){

				txt+="<tr><td colspan=2>"+popupItem(typ,ix,"slideshow","slideshow")+"</td></tr>";

	   			//txt+="<tr><td colspan=2>"+popupItem(typ,ix,"popup","popup"+mnutyp)+"</td></tr>";	

			}

			if(gLoggedIn)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"delete all","deleteAll"+typ.substring(0,3))+"</td></tr>";

	  }else{

	  	//------------ items -------------

	   if(typ=="img"){

	  		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"add new layer","layer")+"</td></tr>";

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"new view","pixi")+"</td></tr>";

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"open","view"+mnutyp)+"</td></tr>";

	   		if(gLoggedIn)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"crop","crop")+"</td></tr>"; 

	   		if(gLoggedIn)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"rotate","rotate")+"</td></tr>"; 

	   		if(!gLoggedIn && gID)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"copy","copy"+mnutyp)+"</td></tr>"; //$$$$$

	   }

	   if(typ=="vid"){

	   		//txt+="<tr><td colspan=2>"+popupItem(typ,ix,"popup","slideshow"+mnutyp)+"</td></tr>";

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"open","view"+mnutyp)+"</td></tr>";

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"clipboard+","copy2")+"</td></tr>";

		}

		if(typ=="flt"){

	   		if(gAdmin)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"edit","edit"+mnutyp)+"</td></tr>";

		}

		if(typ=="fil"){

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"add new layer","addText")+"</td></tr>";

	   		if(gAdmin)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"edit","edit"+mnutyp)+"</td></tr>";

	   		if(gLoggedIn)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"design","design"+mnutyp)+"</td></tr>";

		}

	   	if(typ=="rek"){

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"load","loadOnly"+mnutyp)+"</td></tr>";

	   		if(gAdmin)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"edit","edit"+mnutyp)+"</td></tr>";

		}

		if(typ=="pix"){	//view

	   		txt+="<tr><td colspan=2>"+popupItem(typ,ix,"open","view"+mnutyp)+"</td></tr>";

			if(gAdmin)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"edit","edit"+mnutyp)+"</td></tr>";

		}

	   	if(gLoggedIn && typ!="vid")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"copy","copy"+mnutyp)+"</td></tr>";

	   	if(gLoggedIn && typ!="vid")txt+="<tr><td colspan=2>"+popupItem(typ,ix,"rename","rename"+mnutyp)+"</td></tr>";

	   	if(gLoggedIn)txt+="<tr><td colspan=2>"+popupItem(typ,ix,"delete","delete"+mnutyp)+"</td></tr>";

	  }

	  txt+="</TABLE>";

	  mnu.innerHTML=txt;

	 }

	 mnu.style.display="block";

}else{

	 mnu.style.display="none";

}

}





function popupItem(typ,ix,txt,action){return "<div onclick='popupAction(\""+typ+"\",\""+ix+"\",\""+action+"\",0,\""+txt+"\")' <?=$movers;?> style='cursor:pointer;'>&nbsp;"+txt+"</div>";}



function popupAction(typ,ix,action,subdirs,txt){

var mnu=_obj(typ+"_mnu_"+ix);

//msg("popupAction: typ="+typ+", ix="+ix+", action="+action);

if(ix!="cf"){  //not a title

	 switch(typ){

	  case "img":selectImage(ix);break;

	  case "fil":selectFile(ix);break;

	  case "pix":selectPixi(ix);break;

	  case "rek":selectRekord(ix);break;

	  case "fdr":selectFolder(ix,1);break;

	  case "vid":selectVideo(ix);break;

	  case "flt":selectFilter(ix);break;

	 }

	 goAction(typ,1,action,null,ix,subdirs,txt);

}else{

	 goAction(typ,0,action,null,null,subdirs,txt);

}

mnu.style.display="none";

}





function goAction(typ,popup,action,force,ix,subdirs,txt){

var target,targetFile,targetImg,dir,thisfile,thisdir,thisext,url,title;

//if(action==null)action=getAction();

target=getTarget(typ);

thisext=_getExt(target);

if(!target && _in(action,"file") && action!="newfile"){

	alert("No file or image selected : typ="+typ+", action="+action);

	return;

}

thisfile=getFile(target);

if(typ==null)typ=_fileType2(target);

dir=(typ=="folder" && popup)?gFolder:gDir;

var parms="?page="+action+"&dir="+dir+"&file="+thisfile;

var lnktarget='', lnkparms='';

if(target){

	 lnktarget=target.replace("http://","");

	 lnktarget=lnktarget.replace("https://","");

	 lnkparms="?page="+action+"&dir="+dir+"&target="+escape(lnktarget)+"&oldnewwin="+gLinkWins[gLnkIX];

	 if(typ=="img"){

	  url=gRoot+gContent+"/"+target;

	  if(typ=="img")url=url.replace("_tn.",".");

	 }else{

	  url=target.replace("@lnk@","");

	 }

}

//msg("URL="+url+", gRoot="+gRoot+", typ="+typ);

//msg("action="+action);

//msg("parms="+parms);

//alert("url="+url);

switch(action){

case "filter":

	msg("action="+action);

	break;

case "pixi":

 	parent.loadPixi(url);

	break;

case "layer":

 	parent.gPixi.gToolbar.addLayer(dir,url);

 	break;

case "addText":

	parent.addText(dir,url);

	break;

case "home":

 loadHome();

 break;

case "renamedir":

 parent.openPopup("util","_newutil.php?page=renamedir&dir="+target,"Rename Folder");

 break;

case "deletedir":

 parent.openPopup("util","_newutil.php?page=deletedir&dir="+target,"Delete Folder");

 break;

case "openClipboard":

 parent.openPopup("clipboard","clip.php?dir="+dir,"Video Clipboard");

 break;

case "deleteAllfil": parent.openPopup("util","_newutil.php"+parms,"Delete All Text Files"); break;

case "deleteAllflt": parent.openPopup("util","_newutil.php"+parms,"Delete All Filters"); break;

case "deleteAllpix": parent.openPopup("util","_newutil.php"+parms,"Delete All Views"); break;

case "deleteAllrek": parent.openPopup("util","_newutil.php"+parms,"Delete All Actions"); break;

case "deleteAllimg": parent.openPopup("util","_newutil.php"+parms,"Delete All Images"); break;

case "deleteAllvid": parent.openPopup("util","_newutil.php"+parms,"Delete All Videos"); break;

case "copydir":

 //parent.openPopup("util","_newutil.php"+parms,"Copy Folder");

 parent.openPopup("util","_newutil.php?page=copydir&dir="+target,"Copy Folder");

 break;

case "newdir":

 parent.openPopup("util","_newutil.php"+parms,"New Folder");

 break;

case "options":

 parent.openPopup(action,"_options.php"+parms,"Settings");

 break;

case "refresh":

 refresh();

 return;

case "viewfile":

 if(_isPixi(target)){

  window.open("<?=$gRoot;?><?=$gExePath;?>_pixi.php?src="+target+"&embed=no");

  break;

 }

 if(_isVideo(target)){

  window.open("https://www.youtube.com/watch?v="+_rep(thisfile,"@vid@",""));

  break;

 }

 if(_isImage(target)){

  target=target.replace("_tn.",".");

  window.open("<?=$gRoot;?>/<?=$gPath;?>"+target);

  break;

 }

 if(_isFilter(target)){

  //parent.loadFile(gDir,target,0,"filter","desktop");

  msg("filter="+target);

  break;

 }

 if(_isFile(target)){

  //parent.loadFile(gDir,"<?=$gPath;?>"+target,0,"file","desktop");

  window.open("<?=$gRoot;?>"+target);

  break;

 }

 //loadPage("<?=$gPath;?>"+target,action,action.replace("view",""),target);

 break;

case "renamefile":

 if(_isVideo(target)){alert("Sorry... this function is not available for videos");break;}

 var h=(_isImage(target))?"320px":"250px";

 parent.openPopup("rename","_newutil.php"+parms,"Rename File");

 break;

case "deletefilter":

 //asyncP("action=deletefilter&filter="+target+"&dir="+gDir,action);

 //break;

case "deletefile":

 hideFile(typ,ix);

 if(typ=="vid"){ 

  asyncP("action=deletevideo&video="+target+"&dir="+gDir,action); 

  break; 

 }

 asyncP("action=deletefile&file="+thisfile+"&dir="+gDir,action);

 break;

case "newtext":

  parent.openPopup("newtext","_edittext.php"+parms,"New Text");

 break;

case "newfile":

 parent.openPopup(action,"_newutil.php"+parms);

 break;

case "newfilter":

 parent.openPopup("util","_newutil.php"+parms,"New Filter");

 break;

case "copyfile":

 if(_isVideo(target)){alert("Sorry... this function is not available for videos");break;}

 parent.openPopup("Copy","_newutil.php"+parms,"Copy File");

 break;

case "newfimg":

 parent.openPopup("remoteImage","crop.php"+parms,"Add Remote Image");

 break;

case "crop":

 parent.openPopup("crop","crop.php"+parms,"Crop Image");

 break;

case "rotate":

 parent.openPopup("crop","rotate.php"+parms,"Rotate Image");

 break;

case "editfile":

 if(typ=="vid") { alert("Sorry... this function is not available for videos"); break; }

 parent.openPopup(action,"codemirror.php"+parms);

 break;

case "loadOnlyfile":

 if(typ=="rek")gotoRekord(ix,target,1);

 break;

case "designfile":

  parent.editText(url);

 return;

case "logout":

 parent.logout();

 break;

case "login":

 parent.login();

 break;

case "youtube":

 parent.openPopup(action,"youtube.php?dir="+dir)

 break;

case "copy2":

 if(_isFilter(target)){alert("Sorry... this function is not available for filters");break;}

 asyncP("action=copy2&video="+target+"&title="+gTitles[ix]+"&refresh=yes",action);

 break;

case "copy2all":

 asyncP("action=copy2all&dir="+dir,action);

 break;

case "upload":

 parent.openPopup(action,"_upload.php?dir="+dir);

 break;

case "slideshowfile":

case "popupfile":

	var popup=(action=="popupfile")?1:2;

	if(_isImage(target))parent.loadImage(gDir,target,popup);

	else if(_isVideo(target))parent.loadVideo(gDir,target,popup);

	else if(_isPixi(target))parent.loadPixiPopup(gDir,thisfile);

	else parent.loadFile(gDir,target,popup,"file");

	break;



case "slideshow":	//applies to title (no specific file)

	window.open("<?=$gRoot;?><?=$gExePath;?>_play.php?dir="+gDir+"&includesubdirs=no&type="+((typ=="imgtit")?"images":"videos")+"&mode=popup");

	break;



case "loadallimages":

 loadAllImages();

 break;

case "loadallvideos":

 loadAllVideos();

 break;

default:

 alert("unknown action: "+action);

}}







function hideFile(typ,ix){

	//msg("hide ix="+ix+", typ="+typ)

	if(typ=="img" || typ=="vid")

		_obj(typ+"_div_"+ix).style.display="NONE";

	else

		_obj(typ+"_"+ix).style.display="NONE";

}





function closePopup(id){_xvzFlip(_obj(id));}







function flipMenus(flips){

if(flips)gFlips=flips;

else{                        //--- when saving a vid or something we want to keep the flips the same

 gFlips=getFlips();

 if(!gFlips)gFlips="1,1,1,1,1,1,1,0";

}

var a=gFlips.split(",");

if(a.length<8){ gFlips="1,1,1,1,1,1,1,0"; a=gFlips.split(","); }

flipFolders( a[0] ,0);

flipFiles  ( a[1] ,0);

flipImages ( a[2] ,0);

flipVideos ( a[3] ,0);

flipFilters( a[4] ,0);

flipPixis  ( a[5] ,0);

flipRekords( a[6] ,0);

saveFlips();

}







function fmtMenu(typ){

if(typ=="all"){

	if(gLastMenuTyp!="all" || gMenuFmt==1 || gMenuFmt==null){

	  flipMenus("0,0,0,0,0,0,0,0");

	  gMenuFmt=0;

	}else{

	  flipMenus("1,1,1,1,1,1,1,0");

	  gMenuFmt=1;

	}

}else{

	flipMenus("0,0,0,0,0,0,0,0");

	switch(typ){

		case "folders": flipFolders(1,0); break;

		case "files"  : flipFiles(1,0);   break;

		case "pixis"  : flipPixis(1,0);   break;

		case "rekords": flipRekords(1,0); break;

		case "pics"   : flipImages(1,0);  break;

		case "vids"   : flipVideos(1,0);  break;

		case "filters": flipFilters(1,0); break;

}	}

gLastMenuTyp=typ;

saveFlips()

}





function flipFolders(force,save){

if("<?=$showfolders;?>"==0)return;

if(force==null)force=(_obj("iDirsList").style.display=="none")?1:0;

_obj("iDirsList").style.display=(force==1)?"block":"none";

var tmp="_pvt_images/";

_obj("iDirsBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}





function flipFiles(force,save){

if("<?=$showfiles;?>"==0)return;

if(force==null)force=(_obj("iFilesList").style.display=="none")?1:0;

_obj("iFilesList").style.display=(force==1)?"block":"none";

var tmp="_pvt_images/";

_obj("iFilesBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}



function flipFilters(force,save){

if("<?=$showfilters;?>"==0)return;

if(force==null)force=(_obj("iFiltersList").style.display=="none")?1:0;

if(!gFilters.length)_obj("iFiltersList").style.display="none";

else _obj("iFiltersList").style.display=(force==1)?"block":"none";

var tmp="_pvt_images/";

_obj("iFiltersBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}





function flipPixis(force,save){

if("<?=$showpixis;?>"==0)return;

if(force==null)force=(_obj("iPixisList").style.display=="none")?1:0;

if(!gPixis.length)_obj("iPixisList").style.display="none";

else _obj("iPixisList").style.display=(force==1)?"block":"none";

var tmp="_pvt_images/";

_obj("iPixisBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}



function flipRekords(force,save){

if("<?=$showactions;?>"==0)return;

if(force==null)force=(_obj("iRekordsList").style.display=="none")?1:0;

_obj("iRekordsList").style.display=(force==1)?"block":"none";

var tmp="_pvt_images/";

_obj("iRekordsBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}





function flipVideos(force,save){

if("<?=$showvids;?>"==0)return;

if(force==null)force=(_obj("iVideosList").style.display=="none")?1:0;

_obj("iVideosList").style.display=(force==1)?"block":"none";

var tmp="_pvt_images/";

_obj("iVideosBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}





function flipImages(force,save){

if("<?=$showpics;?>"==0)return;

if("<?=$optonlyimages;?>"=="yes")force=1;

if(force==null)force=(_obj("iImagesList").style.display=="none")?1:0;

_obj("iImagesList").style.display=(force==1)?"block":"none";

//alert("force="+force+", true?="+(force==true)+", display="+_obj("iImagesList").style.display);

var tmp="_pvt_images/";

_obj("iImagesBtn").src=(force==1)?tmp+"<?=$collapsebtn;?>":tmp+"<?=$expandbtn;?>";

if(save!=0){ saveFlips(); gMenuFmt=null; }

}





function getFlips(){

//return get(gDir+"_flips");

return get("_flips");

}



function saveFlips(){

var flips="";

try{flips+=((_obj("iDirsList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iFilesList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iImagesList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iVideosList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iFiltersList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iPixisList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iRekordsList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",";

try{flips+=((_obj("iFiltersList").style.display=="block")?1:0);}catch(e){flips+="0";};

flips+=",0";

set("_flips",flips);

}









function reloadMenu(dir,goback,noreload,keeputil){

try{gotoDir(dir,goback,null,keeputil,noreload);}catch(e){}

}





function hidePopup(e,m){

if(gIE)return;

if(e){

 var obj=e.relatedTarget;

 while(obj!=null){

  if(obj==m)return;

  obj=obj.parentNode;

}}

m.style.display="none";

}







function loadHome(){

deselectFile();

parent.loadHome();

}





//------------------------ loadPage --------------------------------

function loadPage(u,action,typ,fil,nofresh,txt){

if(!_in(u,"pixi.php") && !_in(u,"toolbar.php"))alert("menu.php loadPage() still used for ???");

if(u && nofresh==0 && !_in(u,".php")){

 u+=(_in(u,"?"))?"&":"?";

 u+="fresh="+Math.random();

}

parent.loadPage(u,action,typ,fil,nofresh,txt);

}



function deleteVideos(){

var r=confirm("Delete all videos in this folder?");

if(r==true)asyncP("action=deletevideos&dir="+gDir);

}



function deleteFilters(){

var r=confirm("Delete all filters in this folder?");

if(r==true)asyncP("action=deletefilters&dir="+gDir);

}



function deleteImages(){

var r=confirm("Delete all images in this folder?");

if(r==true)asyncP("action=deleteimages&dir="+gDir);

}





function refresh(noreload){

if(noreload==null)noreload=0;

window.location.href="_menu.php?dir=<?=$dir;?>&noreload="+noreload;

}





function getTarget(typ){   

switch(typ){

	case "filter" 	:

	case "flt"		:	return gFilter;

	case "pixi" 	:

	case "pix"		:	return gPixi;

	case "imgtit"	:

	case "img"		:	return gImage;

	case "vidtit"	:

	case "vid"		:	return gVideo;

	case "fdr"		:	return gFolder;

	case "lnk"		:	return gFilter;

	case "fil"		:	return gFile;

	case "rek"		:	return gRekord;

}

return null;

}





//--------------------------- gotoDir ----------------------------

function gotoDir(dir,goback,action,keeputil,noreload){

if(dir==null)dir=gDir;

var actionParm;

if(goback==null)goback=0;

actionParm="action="+action;

if(noreload==1)noreload="&noreload=1";

else noreload="";

window.location.href="_menu.php?dir="+dir+"&goback="+goback+"&"+actionParm+noreload;

}



 



//--------------------------- gotoFile (text/.htm files) ----------------------------

function gotoFile(ix,fil){

var err=0,ok=0;

deselectFile();

gFile=gFiles[ix];

gFileIX=ix;

selectFile(ix);

parent.gPixi.gToolbar.addText(gDir,fil,"");

//ok=parent.gotoImg(fil);

//if(ok && !err)return;

//loadPixi(fil);

}





//------------------------ loadPixi -------------------------------

function loadPixi(fil){

//alert("menu.php loadPixi() fil="+fil);

parent.loadPixi(fil);

}





//--------------------------- applyFilter ----------------------------

function applyFilter(ix,filter){

gFilter=gFilters[ix];

//deselectFile();

//selectFilter(ix);

parent.applyFilter(_getName(gFilter)+".ixx",gDir); 

}



//--------------------------- gotoPixi ----------------------------

function gotoPixi(ix,pixi){

//deselectFile();

gPixi=gPixis[ix];

selectPixi(ix);

parent.loadPixi(_getName(gPixi)+".ixi",gDir); //this will reload the entire page

}





//--------------------------- gotoRekord ----------------------------  (calls ajx.php)

function gotoRekord(ix,rekord,loadOnly){

var err=0,ok=0;

//deselectFile();

gRekord=gRekords[ix];

selectRekord(ix);

parent.gPixi.gPic.rekordLoadOnly=(loadOnly)?1:0;

asyncP("action=loadrekord&fil="+gRekord,"loadrekord");

}

//--------------------------- loadRekord ----------------------------  (called by ajx.php)

function loadRekord(name,txt){

var n=_getName(name);

//alert("name="+n+", txt="+txt);

parent.loadRekord(n,txt);

}



//--------------------------- gotoImage ----------------------------

function gotoImage(ix,url){

if(parent.loadImage(gDir,url,0)){

	deselectFile();

	gImage=gImages[ix];

	gImgIX=ix;

	selectImage(ix);

}}





function getNextPic(i,mask,currentsrc){

//--- currently only called for NEXT image (i=1)

i=1;

//if(i!=1){alert("getNextPic() in menu.php has i="+i); return currentsrc; }

var p,ix=-1,newsrc;

if(gImages.length==0)return currentsrc;

for(var j=0;j<gImages.length;j++){ p=gPath+gImages[j].replace("_tn",""); if(p==currentsrc)ix=j; }

if(ix>-1)ix+=i;

if(ix>-1 && ix<gImages.length)newsrc=gPath+gImages[ix].replace("_tn","");

if(!newsrc){

 	if(gImages.length>0)newsrc=gPath+gImages[0].replace("_tn","");

 	else return currentsrc;

}

return newsrc;

}









//-------------- load all images in current slideshow ------------

function loadAllImages(){

var imgs = parent.loadAllItems(gImages);

if(!imgs)alert("no image slideshow found");  

}









//------ gotoVideo -------

function gotoVideo(ix,vid){

//var ssType=getSSType();

var err=0,ok=0;

deselectFile();

gVideo=gVideos[ix];

gVidIX=ix;

selectVideo(ix);

parent.loadVideo(gDir,gVideo,0,gTitles[ix]);

}



//------ load all videos in current slideshow ------------

function loadAllVideos(){

if(parent.loadAllItems(gVideos,gTitles))return;

alert("no video slideshow found");

}





function getRandomVideo(){

var ix=_rdm(0,gVideos.length-1);

return gVideos[ix];

}





//---- file/folder selection hilites -------



function selectCurrent(src){   // called by _play.php

//alert(src);

return;

src=src.replace(gRoot,"");

src=src.replace("../","");

var i,tmp, fil=getFile(src), typ=_fileType(src), arx;

//alert("fil="+fil+", \n\nsrc="+src+", \n\ntyp="+typ);;

switch(typ){

 case "image": arx=gImages; break;

 case "file" : arx=gFiles;  break;

 case "pixi" : arx=gPixis;  break;

 case "rekord" 	: arx=gRekords;  break;

 case "video"	: arx=gVideos; break;

 case "filter" 	: arx=gFilters; break;

 default: return;

}

for(i=0;i<arx.length;i++){

 tmp=arx[i].replace("_tn.",".");

 tmp=tmp.replace("../","");

 if(tmp==src){

  switch(typ){

   case "image": selectImage(i); return;

   case "file" : selectFile(i);  return;

   case "pixi" : selectPixi(i);  return;

   case "rekord" : selectRekord(i);  return;

   case "video": selectVideo(i); return;

   case "filter" : selectFilter(i);  return;

}}}}





function getDefaultFile(){ return gPath+gFiles[gFiles.length-1]; }



function getDefaultFilter(){ return gPath+gFilters[gFilters.length-1]; }



function selectFolder(ix,popup){

if(!popup || (gFolderIX==null && ix==null))return;

if(ix==null)ix=gFolderIX;

deselectFile();

//try{_obj("fdr_"+ix).style.background="<?=$selcolor?>";}catch(e){}

gFolder=gFolders[ix];

gFolderIX=ix;

}





function selectFile(ix){

deselectFile();

var o=_obj("fil_"+ix);

//o.style.background="<?=$selcolor?>";

gFile=gFiles[ix];

gFilIX=ix;}





function selectImage(ix){

deselectFile();

//try{

//  _obj("img_div_"+ix).style.background="<?=$sshicolor;?>";

//}catch(e){}

gImage=gImages[ix];

gImgIX=ix;

}





function selectVideo(ix){

deselectFile();

//try{

//  _obj("vid_div_"+ix).style.background="<?=$sshicolor;?>";

//}catch(e){}

gVideo=gVideos[ix];

gVidIX=ix;

}





function selectFilter(ix){

deselectFile();

//_obj("flt_"+ix).style.background="<?=$selcolor;?>";

gFilter=gFilters[ix];

gFltIX=ix;

}





function getPixiIX(name){

var txt="";

for(var i=0;i<gPixis.length;i++){

 txt+=gPixis[i]+",";

 if(gPixis[i]==name+".ixi" || gPixis[i]==name)return i;

}

//alert(name+","+txt);

return -1;}



function getRekordIX(name){

var txt="";

for(var i=0;i<gRekords.length;i++){

 txt+=gRekords[i]+",";

 if(gRekords[i]==name+".iki" || gRekords[i]==name)return i;

}

return -1;}



function selectPixi(ix){

if(gPixIX!=null){

 //try{_obj("pix_"+gPixIX).style.background="";}catch(e){}

}

if(ix<0)return;

var o=_obj("pix_"+ix);

//o.style.background="<?=$selcolor?>";

gPixi=gPixis[ix];

gPixIX=ix;}



function selectRekord(ix){

if(gRekIX!=null){

  //try{ _obj("rek_"+gRekIX).style.background="";   }catch(e){}

}

if(ix<0)return;

var o=_obj("rek_"+ix);

//o.style.background="<?=$selcolor?>";

gRekord=gRekords[ix];

gRekIX=ix;

}





function deselectFile(){

return;

if(gFolderIX!=null){

 try{_obj("fdr_"+gFolderIX).style.background="";}catch(e){}

}

if(gFilIX!=null){

 try{_obj("fil_"+gFilIX).style.background="";}catch(e){}

}

if(gImgIX!=null){

 if('<?=$showthumbs;?>'=='yes'){

  try{

   _obj("img_div_"+gImgIX).style.background="<?=$bgcolor;?>";

  }catch(e){}

 }else{

  try{_obj("img_"+gImgIX).style.background="";}catch(e){}

}}

if(gLnkIX!=null){

 try{_obj("lnk_"+gLnkIX).style.background="";}catch(e){}

}

if(gVidIX!=null){

 if('<?=$showthumbs;?>'=='yes'){

  try{

   _obj("vid_div_"+gVidIX).style.backgrounf="<?=$bgcolor;?>";

   //_obj("vid_div_"+gVidIX).style.padding=pdr+"px";

  }catch(e){}

 }else{

  try{_obj("vid_"+gVidIX).style.background="";}catch(e){}

}}

gFolder=null;

gFolderIX=null;

gFile=null;

gFilIX=null;

gImage=null;

gImgIX=null;

gVideo=null;

gVidIX=null;

gFilter=null;

gFltIX=null;

//gRekord=null;

//gRekIX=null;

//gPixi=null;   

//gPixIX=null;

}





function load(){

 gLoginDir=parent.setgDir("<?=$dir;?>");

 gLoginDirFilter=gLoginDir+"/";

 //alert("gAcctAdmin="+parent.gAcctAdmin);  

 parent.resetToolbarRights();

 flipMenus();

 set("lastdir",gDir);

}



function asyncP(parms,action){

parent.asyncPOST(parms,"ajx.php",asyncEval,action);

}



function asyncEval(req){

try{_obj("iBusy").style.display="none";}catch(e){}

//try{

var txt=req.responseText;

//alert("txt="+txt);

eval(txt);

//}catch(e){alert("Sorry! Remote call failed");}

}





</script>

</head>

<BODY onload=load() topmargin=0 leftmargin=2 marginwidth=0 marginheight=0 style='margin-left:6px;overflow-x:hidden;'>



<!--

<IMG onclick='refresh()' src='_pvt_images/reset.png' style='z-index:9999;position:absolute;top:20px;left:100px;width:24px;height:24px;cursor:pointer;' title='refresh'>

-->



<?





//======================== floating nav box START ==============================

wrt("<DIV style='position:fixed;background:".$bgcolor.";width:100%;top:0px;left:8px;'>");

//----- height adjusted by spaceMenu() in toolbar.php ---

wrt("<div id=iNavSpacer style='height:0px;'></div>");



$navspacer=5;



//----- current folder name ------

 $navspacer+=($dir=="")?0:40;

 $tmp=($dir=="")?"display:none;":"";

 wrt($vstart."<TABLE cellpadding=0 cellspacing=0 style='position:relative;left:-4px;".$tmp."' oncontextmenu='return false;' >");

 wrt("<tr><td>");

 wrt("<img src='_pvt_images/folders/back.png' onclick='gotoDir(\"$dir\",1)' style='cursor:pointer;width:26px;position:relative;top:1px;left:-1px;'>");

 wrt("</td></tr>");

 wrt("<tr><td><span class='c_fldr' id=fdr_div_cf ");

 if("/".$dir!=$shortdirdisplay)echo(" title='/".$dir."'");

 echo("><nobr>");

 if("/".$dir!=$dirdisplay)echo("..");

 if($shortdirdisplay!="/")echo($shortdirdisplay);

 echo("</nobr></span></td></tr>");

 echo("</TABLE>");

 echo("<div style='height:7px;'></div>");



wrt("</DIV>");



wrt("<div style='height:".$navspacer."px;'></div>");

//========================== floating nav box END ==============================





//----- height adjusted by spaceMenu() in toolbar.php ---

wrt("<div id=iMenuSpacer style='height:0px;'></div>");

wrt("<div style='height:16px;'></div>");





//default display for lists

$defdisplay=($optonlyimages=="yes")?"none":"block";

$def_btn="src='_pvt_images/".$collapsebtn."' style='z-index:0;cursor:pointer;width:10px; display:".(($showexpandbtns=="yes")?"":"none").";padding-right:3px;position:relative;top:-3px;'>";



//folders

if($showfolders){

	  $tmp=($dirslist!="")?"":"none";

	  echo($vstart."<span id='iDirsHdr' style='display:".$tmp.";cursor:pointer;' class='listhdr' oncontextmenu='return false;' $lnkmovers onclick='flipFolders()'>");

	  echo("<img id=iDirsBtn ".$def_btn);

	  echo("Folders</span>");

	  echo($vbreak2);

	  wrt("<DIV id='iDirsList' style='display:".$defdisplay.";'>");

	  echo($dirslist);

	  wrt("</DIV>");

}





//images

if($showpics){

	  $tmp=($imageslist!="" && $optonlyimages!="yes")?"":"none";

	  wrt("<div style='display:".$tmp.";height:2px;clear:both;'></div>");

	  echo("<table id='iImagesHdr' style='display:".$tmp.";' cellpadding=0 cellspacing=0 oncontextmenu='return false;'><tr>");

	  if($showthumbs=='yes'){

		    wrt("<td><img id=iImagesBtn onclick='flipImages()' ".$def_btn."</td>");

		    //wrt($vstart."<span class='listhdr'  onmousedown='popup(event,\"cf\",this,\"imgtit\")' onclick='flipImages()' ".$lnkmovers." style='cursor:pointer;'>Images</span>");

			wrt("<td width=75>");

		    if($imageslist!="")

			     wrt($vstart."<span class='listhdr'  onmousedown='popup(event,\"cf\",this,\"imgtit\",flipImages)' ".$lnkmovers." style='cursor:pointer;'>");

		    else

			     wrt($vstart."<span class='listhdr' oncontextmenu='return false;' style='color:#999999;'>");

		    wrt("Images</span></td>");

	  }else{

		    echo($vstart."<span class='listhdr' onmousedown='popup(event,\"cf\",this,\"imgtit\")'>Images&nbsp;</span></td>");

	  }

	  echo("</td><td align=right>");

	  wrt("&nbsp;<IMG class=c_barbtn src='_pvt_images/addgreen.png' onclick='parent.loadx(\"upload\")' style='".$updDisplay.";' title='upload'></td>");

	  echo("</td></tr>");

	  echo("<tr><td colspan=3 align=left></div><DIV id=imgtit_mnu_cf onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>");

	  echo("</td></tr></table>");

	  echo($vbreak2);

	  wrt("<DIV id='iImagesList' style='display:".$defdisplay.";'>");

	  echo($imageslist);

	  wrt("</DIV>");

}





//videos

if($showvids){

	   $tmp=($videoslist!="")?"":"none";

	   wrt("<div style='display:".$tmp.";height:2px;clear:both;'></div>");

	   echo("<TABLE id='iVideosHdr' style='display:".$tmp.";' cellpadding=0 cellspacing=0 oncontextmenu='return false;' ><tr>");

	   wrt("<td><img id=iVideosBtn onclick='flipVideos()' ".$def_btn."</td>");

	   wrt("<td width=75>");

	   if($videoslist!="")

		    echo($vstart."<span class='listhdr'  onmousedown='popup(event,\"cf\",this,\"vidtit\")' onclick='flipVideos()' $lnkmovers style='cursor:pointer;'>");

	   else

		    wrt($vstart."<span class='listhdr' oncontextmenu='return false;' style='color:#999999;'>");

	   echo("Videos</span></td>");

	   echo("</td><td align=right>");

	   wrt("&nbsp;<IMG class=c_barbtn src='_pvt_images/addgreen.png' onclick='parent.loadx(\"youtube\")' style='".$updDisplay.";' title='add new'></td>");

	   echo("</td></tr>");

	   echo("<tr><td colspan=3 align=left><DIV id=vidtit_mnu_cf onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>");

	   echo("</td></tr></TABLE>");

	   wrt("<DIV id='iVideosList' style='display:".$defdisplay.";'>");

	   echo($videoslist);

	   wrt("</DIV>");

}





//files

if($showfiles){

	$tmp=($fileslist!="")?"":"none";

	wrt("<DIV style='display:".$tmp.";height:2px;clear:both;'></div>");

	 echo("<table id='iFilesHdr' style='display:".$tmp.";' cellpadding=0 cellspacing=0 oncontextmenu='return false;'><tr>");

	 wrt("<td><img id=iFilesBtn onclick='flipFiles()' ".$def_btn."</td>");

	 wrt("<td width=75>");

	 if($fileslist!="")

	 		wrt($vstart."<span class='listhdr' onmousedown='popup(event,\"cf\",this,\"filtit\")' $lnkmovers onclick='flipFiles()' style='cursor:pointer;'>");

	 else

	 	wrt($vstart."<span class='listhdr' oncontextmenu='return false;' style='color:#999999;'>");

	 wrt("Text</span></td>");

	 echo("</td><td align=right>");

	 wrt("&nbsp;<IMG class=c_barbtn src='_pvt_images/addgreen.png' onclick='parent.loadx(\"newtext\")' style='".$updDisplay.";' title='add new'></td>");

	 echo("</td><td align=right>");

	 echo("</td></tr>");

	 echo("<tr><td colspan=3 align=left></div><DIV id=filtit_mnu_cf onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>");

	 echo("</td></tr></table>");

  	wrt("<DIV id='iFilesList' style='display:".$defdisplay.";'>");

  	echo($fileslist);

  	wrt("</DIV>");

}





//actions

if($showactions){

	  $tmp=($rekordslist!="")?"":"none";

	  wrt("<DIV style='display:".$tmp.";height:2px;clear:both;'></div>");

	   echo("<table id='iRekordsHdr' style='display:".$tmp.";' cellpadding=0 cellspacing=0 oncontextmenu='return false;'><tr>");

	   wrt("<td><img id=iRekordsBtn onclick='flipRekords()' ".$def_btn."</td>");

	   wrt("<td width=75>");

	   if($rekordslist!="")

	     wrt($vstart."<span class='listhdr' onmousedown='popup(event,\"cf\",this,\"rektit\")' $lnkmovers onclick='flipRekords()' style='cursor:pointer;'>");

	   else

	     wrt($vstart."<span class='listhdr' oncontextmenu='return false;' style='color:#999999;'>");

	   wrt("Actions</span></td>");

	   echo("</td><td align=right>");

	   wrt("&nbsp;<IMG class=c_barbtn src='_pvt_images/addgreen.png' onclick='parent.saveRekord()' style='".$updDisplay.";' title='start a new recording'></td>");

	   echo("</td><td align=right>");

	   echo("</td></tr>");

	   echo("<tr><td colspan=3 align=left></div><DIV id=rektit_mnu_cf onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>");

	   echo("</td></tr></table>");

	  wrt("<DIV id='iRekordsList' style='display:".$defdisplay.";'>");

	  echo($rekordslist);

	  wrt("</DIV>");

}





//filters

if($showfilters){

	  $tmp=($filterslist!="")?"":"none";

	  wrt("<DIV style='display:".$tmp.";height:2px;clear:both;'></div>");

	  echo("<table id='iFiltersHdr' style='display:".$tmp.";' cellpadding=0 cellspacing=0 oncontextmenu='return false;'><tr>");

	  wrt("<td><img id=iFiltersBtn onclick='flipFilters()' ".$def_btn."</td>");

	  wrt("<td width=75>");

	  if($filterslist!="")

	     wrt($vstart."<span class='listhdr' onmousedown='popup(event,\"cf\",this,\"flttit\")' $lnkmovers onclick='flipFilters()' style='cursor:pointer;'>");

	  else

	     wrt($vstart."<span class='listhdr' oncontextmenu='return false;' style='color:#999999;'>");

	  wrt("Filters</span></td>");

	  echo("</td><td align=right>");

	  wrt("&nbsp;<IMG class=c_barbtn src='_pvt_images/addgreen.png' onclick='parent.saveFilter()' style='".$updDisplay.";' title='save as'></td>");

	  echo("</td><td align=right>");

	  echo("</td></tr>");

	  echo("<tr><td colspan=3 align=left></div><DIV id=flttit_mnu_cf onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>");

	  echo("</td></tr></table>");

	  wrt("<DIV id='iFiltersList' style='display:".$tmp.";'>");

	  echo($filterslist);

	  echo($vbigbreak);

	  wrt("</DIV>");

}



//pixis

if($showpixis){

	  $tmp=($pixislist!="")?"":"none";

	  wrt("<DIV style='display:".$tmp.";height:2px;clear:both;'></div>");

	  echo("<table id='iPixisHdr' style='display:".$tmp.";' cellpadding=0 cellspacing=0 oncontextmenu='return false;'><tr>");

	  wrt("<td><img id=iPixisBtn onclick='flipPixis()' ".$def_btn."</td>");

	  wrt("<td width=75>");

	  if($pixislist!="")

	     wrt($vstart."<span class='listhdr' onmousedown='popup(event,\"cf\",this,\"pixtit\")' $lnkmovers onclick='flipPixis()' style='cursor:pointer;'>");

	  else

	     wrt($vstart."<span class='listhdr' oncontextmenu='return false;' style='color:#999999;'>");

	  wrt("Views</span></td>");

	  echo("</td><td align=right>");

	  wrt("&nbsp;<IMG class=c_barbtn src='_pvt_images/addgreen.png' onclick='parent.savePixi()' style='".$updDisplay.";' title='save as'></td>");

	  echo("</td><td align=right>");

	  echo("</td></tr>");

	  echo("<tr><td colspan=3 align=left></div><DIV id=pixtit_mnu_cf onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV>");

	  echo("</td></tr></table>");

	  wrt("<DIV id='iPixisList' style='display:".$tmp.";'>");

	  echo($pixislist);

	  echo($vbigbreak);

	  wrt("</DIV>");

}



wrt("<DIV style='height:200px;'></DIV>");



if($noreload!="1" && ($autoload=="yes" || $noreload==-1)){  // noreload of -1 FORCES the reload

 	echo("<script> if(parent.gDefPixi)loadPixi(parent.gDefPixi); else loadHome(); </script>");

}



?>

<script>

parent.spaceMenu();	//sets the visibility and size of iNavSpacer

</script>

<?





echo("</BODY>");

exit();







function loadVideosFile($file){

global $gFetchFromGrooze,$gPath,$dir,$dirlink,$showthumbs,$vidcnt,$vstart,$vbreak,$lnkmovers,$videos,$titles,$ssshowthumbbdrs,$btnbgcolor,$thumbbdrcolor,$tmp_mnuborder,$popozie;

$tmp="";

$videos="";

if($gFetchFromGrooze && !$dir){

 $txt=getUrl("http://grooze.com/_grojx.php?action=fetchplaylist&id=");

 myWriteFile($dirlink.$file,$txt);

}else{

 $txt=myReadFile($dirlink.$file);

}

$a=explode(",",$txt);

$vidix=0;

for($i=0;$i<count($a)-1;$i++){

 if(strlen($a[$i])<10)continue;

 //echo("a[i]=".$a[$i]."<br>");

 $vidcnt++;

 $b=explode(";",$a[$i]);

 $vidid=$b[0];

 $vidname=_rep($vidid,"@vid@","");

 if(count($b)>1)$vidtit=_rep($b[1],"'","");

 else $vidtit=$vidname;

 if($showthumbs=='yes'){

  $tmp.=$vstart."<DIV id=vid_div_".$vidix." class=c_thumbdivs oncontextmenu='return false;'>";

  $tmp.="<img id=vid_".$vidix." class=c_thumbs onmousedown='popup(event,".$vidix.",this)' onclick='gotoVideo($vidix,\"$vidid\")' title='$vidtit' src='http://img.youtube.com/vi/"._rep($vidid,"@vid@","")."/default.jpg' style='width:100px;'>";

  $tmp=$tmp."<DIV id=vid_mnu_".$vidix." onmouseout='hidePopup(event,this)' class=c_popupmnu style='display:none;".$popozie."'></DIV></DIV>".$vbreak;

 }else{

   $tmp.=$vstart."<TABLE cellpadding=0 cellspacing=0 id=vid_div_".$vidix." style='padding:2px;width:100%;'>";

   $tmp.="<tr><td>";

   $tmp.="<DIV id=vid_".$vidix." class=lnk ".$lnkmovers." style='width:100%;".$tmp_mnuborder.";padding-bottom:2px;' oncontextmenu='return false;' onmousedown='popup(event,".$vidix.",this)' onclick='gotoVideo($vidix,\"$vidid\")'>$vidtit</DIV>";

   $tmp.="</td></tr>";

   $tmp.="<tr><td align=center><DIV id='vid_mnu_".$vidix."' onmouseout='hidePopup(event,this)'  class=c_popupmnu style='display:none;".$popozie."'></DIV></td></tr>";

   $tmp.="</TABLE>".$vbreak;

 }

 if(empty($gVideo))$gVideo=$vidid;

 $vidix+=1;

 if(!empty($videos)){$videos.=",";$titles.=",";}

 //wrtb($vidid);;

 $videos.="'".$vidid."'";

 $titles.="'".$vidtit."'";

}

return $tmp;

}









?>

