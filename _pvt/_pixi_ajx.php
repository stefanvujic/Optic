<?
include_once "_inc_global.php";
$action=rqst("action");
switch($action){
 case "saveconfig":
  saveConfig();
  break;
 case "savefilter":
  saveFilter();
  break;
 case "saverekord":
  saveRekord();
  break;
}
exit(0);


function saveConfig(){
global $dir,$dirpath,$pixiextension;
$name=rqst("name");
$text=rqst("text");
if(!$name || !$text)exit(0);
$refresh=(file_exists($dirpath."/".$name.$pixiextension))?0:1;
myWriteFile($dirpath."/".$name.$pixiextension,$text);
if($refresh)wrt("try{parent.refresh(1);}catch(e){}");
//wrt($dirpath."/".$name.$pixiextension);
//wrt("alert('done!');");
}


function saveFilter(){
global $dir,$dirpath,$pixiextension;
$name=rqst("name");
$text=rqst("text");
if(!$name || !$text)exit(0);
$refresh=(file_exists($dirpath."/".$name.".ixx"))?0:1;
myWriteFile($dirpath."/".$name.".ixx",$text);
if($refresh)wrt("try{parent.refresh(1);}catch(e){}");
}

function saveRekord(){
global $dir,$dirpath,$rekordextension;
$name=rqst("name");
$text=rqst("text");
if(!$name || !$text)exit(0);
myWriteFile($dirpath."/".$name.$rekordextension,$text);
wrt("try{parent.refresh(1);}catch(e){}");
//wrt($dirpath."/".$name.$pixiextension);
//wrt("alert('done!');");
}


?>

