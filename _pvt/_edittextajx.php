<?
include_once "_inc_global.php";


$action=rqst("action");
$fil=rqst("fil");
switch($action){
 case "savfile":
  saveFile($dir,$fil);
  break;
}
exit(0);



//------------------------------------------------------------------------------
function saveFile($dir,$fil){
global $dirlink;
$v=rqst("iHtm");
$font=rqst("iFontHtm");
$postaction=rqst("postaction");
$font=_rep($font,'"','')."|";
$v=_rep($v,"&lt;","<");
$v=_rep($v,"&quot;","'");

$v=_rep($v,"&!!!!#","<");
$v=_rep($v,"&!!!!$",">");



/*
$txt="<HTML><HEAD><link href='http://fonts.googleapis.com/css?family=".$font."' rel='stylesheet' type='text/css'></HEAD>";
$txt=$txt."<BODY style='resize:none;overflow:hidden;background:transparent;text-align:center;padding:5px;'>";
$txt=$txt.$v."</BODY></HTML>";
*/
$txt="<link href='http://fonts.googleapis.com/css?family=".$font."' rel='stylesheet' type='text/css'>".$v;
myWriteFile($dirlink.$fil,$txt);
?>
<SCRIPT> 
parent._busy(0);
parent.doPostAction('<?=$postaction;?>');
</SCRIPT>
<?
}



?>





