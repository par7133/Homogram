<?php

/**
 * Copyright 2021, 2024 5 Mode
 *
 * This file is part of Homogram.
 *
 * Homogram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Homogram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.  
 * 
 * You should have received a copy of the GNU General Public License
 * along with Homogram. If not, see <https://www.gnu.org/licenses/>.
 *
 * home.php
 * 
 * Homogram home page.
 *
 * @author Daniele Bonini <my25mb@aol.com>
 * @copyrights (c) 2021, 2024, 5 Mode      
 */
 
 $contextType = PUBLIC_CONTEXT_TYPE;
 
 $cmd = PHP_STR;
 $opt = PHP_STR;
 $param1 = PHP_STR;
 $param2 = PHP_STR;
 $param3 = PHP_STR;
 
 
 function myExecPrivatifyCommand() {
   global $param1;
   global $curPath;
   
   $privateData = [];
   
   $curFile = substr($curPath, strlen(APP_REPO_PATH)) . DIRECTORY_SEPARATOR . $param1;
   //echo "curFile=$curFile";
   
   // Update .private file
   $privateFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".private";
   //echo "curFile=$privateFile";
   
   if (file_exists($privateFile)) {
     $privateData = file($privateFile);   
   }  
   if (!in_array($curFile . "\n", $privateData)) {
     $privateData[] = $curFile . "\n";  
     file_put_contents($privateFile, implode('', $privateData));
   }
 }

 function myExecDelCommand() {
   global $param1;
   global $curPath;
   
   $curFile = $curPath . DIRECTORY_SEPARATOR . $param1;
   
   unlink($curFile);
   
 }  

 function myExecPublicifyCommand() {
   global $param1;
   global $curPath;
   
   $privateData = [];
   
   $curFile = substr($curPath, strlen(APP_REPO_PATH)) . DIRECTORY_SEPARATOR . $param1;
   //echo "curFile=$curFile";
   
   // Update .private file
   $privateFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".private";
   //echo "curFile=$privateFile";
   
   if (file_exists($privateFile)) {
     $privateData = file($privateFile);   
   }  
   $key = array_search($curFile . "\n", $privateData);  
   if ($key!==false) {
     unset($privateData[$key]);  
     file_put_contents($privateFile, implode('', $privateData));
   }
 }

 function myExecMakeDirCommand() {
   global $param1;
   global $curPath;

   $newpath = $curPath . DIRECTORY_SEPARATOR . $param1;
   
   mkdir($newpath, 0777);   
 }   

 function parseCommand() {
   global $command;
   global $cmd;
   global $opt;
   global $param1;
   global $param2;
   global $param3;
   
   $str = trim($command);
   
   $ipos = stripos($str, PHP_SPACE);
   if ($ipos > 0) {
     $cmd = left($str, $ipos);
     $str = substr($str, $ipos+1);
   } else {
	 $cmd = $str;
	 return;
   }	     
   
   if (left($str, 1) === "-") {
	 $ipos = stripos($str, PHP_SPACE);
	 if ($ipos > 0) {
	   $opt = left($str, $ipos);
	   $str = substr($str, $ipos+1);
	 } else {
	   $opt = $str;
	   return;
	 }	     
   }
   
   $ipos = stripos($str, PHP_SPACE);
   if ($ipos > 0) {
     $param1 = left($str, $ipos);
     $str = substr($str, $ipos+1);
   } else {
	 $param1 = $str;
	 return;
   }	     
  
   $ipos = stripos($str, PHP_SPACE);
   if ($ipos > 0) {
     $param2 = left($str, $ipos);
     $str = substr($str, $ipos+1);
   } else {
	 $param2 = $str;
	 return;
   }
   
   $ipos = stripos($str, PHP_SPACE);
   if ($ipos > 0) {
     $param3 = left($str, $ipos);
     $str = substr($str, $ipos+1);
   } else {
	 $param3 = $str;
	 return;
   }	     
 	     
 }
 
 
 function is_subfolderdest(string $path): bool 
 {
	global $curPath;
	
	$ret=false;
	
	if ($path === "../") {
	  return $ret;	
	}	
	
	if ($path!=PHP_STR) {
	  $folderName = left($path, strlen($path)-1);

      if (!is_word($folderName)) {
		return $ret;  
	  }	  

      if (is_dir($curPath . PHP_SLASH . $folderName) && (right($path,1)==="/")) {
	    $ret=true;	
	  }
    }
    return $ret;  
 }


 function privatifyparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  //updateHistoryWithErr("invalid options");	
    return false;
  }	
	//param1!="" and isword  
	if (($param1===PHP_STR) || !is_word($param1)) {
	  //updateHistoryWithErr("invalid image file");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $param1;
	if (!file_exists($path)) {
    //updateHistoryWithErr("file must exists");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    //updateHistoryWithErr("invalid inventory file");	
	  return false;
	}  	
  //param1 file extension == gif | png | jpg | jpeg 
  $fileExt = strtolower(pathinfo($param1, PATHINFO_EXTENSION));
  if ($fileExt !== "gif" && $fileExt !== "png" && $fileExt !== "jpg" && $fileExt !== "jpeg") {
	  //updateHistoryWithErr("invalid inventory file");	
	  return false;
  }    
  
	return true;
 }  

 function publicifyparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  //updateHistoryWithErr("invalid options");	
    return false;
  }	
	//param1!="" and isword  
	if (($param1===PHP_STR) || !is_word($param1)) {
	  //updateHistoryWithErr("invalid image file");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $param1;
	if (!file_exists($path)) {
    //updateHistoryWithErr("file must exists");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    //updateHistoryWithErr("invalid inventory file");	
	  return false;
	}  	
  //param1 file extension == gif | png | jpg | jpeg 
  $fileExt = strtolower(pathinfo($param1, PATHINFO_EXTENSION));
  if ($fileExt !== "gif" && $fileExt !== "png" && $fileExt !== "jpg" && $fileExt !== "jpeg") {
	  //updateHistoryWithErr("invalid inventory file");	
	  return false;
  }    
  
	return true;
 }

 function delparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  //updateHistoryWithErr("invalid options");	
    return false;
  }	
	//param1!="" and isword  
	if (($param1===PHP_STR) || !is_word($param1)) {
	  //updateHistoryWithErr("invalid image file");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $param1;
	if (!file_exists($path)) {
    //updateHistoryWithErr("file must exists");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    //updateHistoryWithErr("invalid inventory file");	
	  return false;
	}  	
  //param1 file extension == gif | png | jpg | jpeg 
  $fileExt = strtolower(pathinfo($param1, PATHINFO_EXTENSION));
  if ($fileExt !== "gif" && $fileExt !== "png" && $fileExt !== "jpg" && $fileExt !== "jpeg") {
	  //updateHistoryWithErr("invalid inventory file");	
	  return false;
  }    
  
	return true;
 }

 function makedirparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  //updateHistoryWithErr("invalid options");	
    return false;
  }	
	//param1!="" and isword  
	if (($param1===PHP_STR) || !is_word($param1)) {
	  //updateHistoryWithErr("invalid folder name");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    //updateHistoryWithErr("invalid parameters");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $param1;
	if (file_exists($path)) {
    //updateHistoryWithErr("file must not exists");	
	  return false;
	}  	
  //param1 file extension != gif | png | jpg | jpeg 
  $fileExt = strtolower(pathinfo($param1, PATHINFO_EXTENSION));
  if ($fileExt === "gif" || $fileExt === "png" || $fileExt === "jpg" || $fileExt === "jpeg") {
	  //updateHistoryWithErr("invalid inventory file");	
	  return false;
  }    
  
	return true;
   
 }  
  
 function upload() {

   global $curPath;
   global $prompt;

   //if (!empty($_FILES['files'])) {
   if (!empty($_FILES['files']['tmp_name'][0])) {
	   
     // Updating history..
     //$output = [];
     //$output[] = $prompt . " " . "File upload" . "\n";   
     //updateHistory($output, HISTORY_MAX_ITEMS);
	   	 
     $uploads = (array)fixMultipleFileUpload($_FILES['files']);
     
     //no file uploaded
     if ($uploads[0]['error'] === PHP_UPLOAD_ERR_NO_FILE) {
       //updateHistoryWithErr("No file uploaded.", false);
       return;
     } 
 
     foreach($uploads as &$upload) {
		
       switch ($upload['error']) {
       case PHP_UPLOAD_ERR_OK:
         break;
       case PHP_UPLOAD_ERR_NO_FILE:
         //updateHistoryWithErr("One or more uploaded files are missing.", false);
         return;
       case PHP_UPLOAD_ERR_INI_SIZE:
         //updateHistoryWithErr("File exceeded INI size limit.", false);
         return;
       case PHP_UPLOAD_ERR_FORM_SIZE:
         //updateHistoryWithErr("File exceeded form size limit.", false);
         return;
       case PHP_UPLOAD_ERR_PARTIAL:
         //updateHistoryWithErr("File only partially uploaded.", false);
         return;
       case PHP_UPLOAD_ERR_NO_TMP_DIR:
         //updateHistoryWithErr("TMP dir doesn't exist.", false);
         return;
       case PHP_UPLOAD_ERR_CANT_WRITE:
         //updateHistoryWithErr("Failed to write to the disk.", false);
         return;
       case PHP_UPLOAD_ERR_EXTENSION:
         //updateHistoryWithErr("A PHP extension stopped the file upload.", false);
         return;
       default:
         //updateHistoryWithErr("Unexpected error happened.", false);
         return;
       }
      
       if (!is_uploaded_file($upload['tmp_name'])) {
         //updateHistoryWithErr("One or more file have not been uploaded.", false);
         return;
       }
      
       // name	 
       $name = (string)substr((string)filter_var($upload['name']), 0, 255);
       if ($name == PHP_STR) {
         //updateHistoryWithErr("Invalid file name: " . $name, false);
         return;
       } 
       $upload['name'] = $name;
       
       // fileType
       $fileType = substr((string)filter_var($upload['type']), 0, 30);
       $upload['type'] = $fileType;	 
       
       // tmp_name
       $tmp_name = substr((string)filter_var($upload['tmp_name']), 0, 300);
       if ($tmp_name == PHP_STR || !file_exists($tmp_name)) {
         //updateHistoryWithErr("Invalid file temp path: " . $tmp_name, false);
         return;
       } 
       $upload['tmp_name'] = $tmp_name;
       
       //size
       $size = substr((string)filter_var($upload['size'], FILTER_SANITIZE_NUMBER_INT), 0, 12);
       if ($size == "") {
         //updateHistoryWithErr("Invalid file size.", false);
         return;
       } 
       $upload["size"] = $size;

       $tmpFullPath = $upload["tmp_name"];
       
       $originalFilename = pathinfo($name, PATHINFO_FILENAME);
       $originalFileExt = pathinfo($name, PATHINFO_EXTENSION);
       $FileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
       
       if ($originalFileExt!==PHP_STR) {
         $destFileName = $originalFilename . "." . $originalFileExt;
       } else {
         $destFileName = $originalFilename;  
       }	   
       $destFullPath = $curPath . DIRECTORY_SEPARATOR . $destFileName;
       
       if (file_exists($destFullPath)) {
         //updateHistoryWithErr("destination already exists", false);
         return;
       }	   
        
       copy($tmpFullPath, $destFullPath);

       // Updating history..
       //$output = [];
       //$output[] = $destFileName . " " . "uploaded" . "\n";   
       //updateHistory($output, HISTORY_MAX_ITEMS);
    
       // Cleaning up..
      
       // Delete the tmp file..
       unlink($tmpFullPath); 
        
     }	 
 
   }
 }	  
  
 
function showImages() {
 
  global $curPath;
  global $contextType;
  
  $privateData = [];
   
  $privateFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".private";

  if (file_exists($privateFile)) {
    $privateData = file($privateFile);   
  }  
 
  ///$root = "img";
  $root = APP_REPO_PATH; 
  
  //subpath
  ///$subpath = mb_substr((string)filter_input(INPUT_GET, "path", FILTER_SANITIZE_STRING), 0, 500);
  $subpath = $curPath;
  if ($subpath!=="" && is_dir($subpath)) {
    $path = $subpath;  
  } else {
    $path = $root;   
  }

  /*
   * Display Link to Home 
   */
  if ($path!==$root) {
    
    $title = "Parent";
    $ipos = mb_strripos($subpath, "/");
    $parentPath = substr($subpath, 0, $ipos);   
    $relPath = substr($parentPath, strlen(APP_REPO_PATH));
    $cdate = date("d-m-Y", filectime($parentPath));  
    
    echo "<table style='float:left;width:235px;height:200px;margin-top:5px;margin-right:4px;border:0px solid #D2D2D2'>";
    echo "<tr><td style='text-align:center;font-size:11px'>{$title}</td><tr>";
    echo "<tr><td style='padding:3px;'><a href='#' onclick='changePath(\"{$relPath}\")'><img src='/res/folder-home.png' width='100%' height='200px'></a></td><tr>"; 
    echo "<tr><td style='text-align:center;font-size:11px'>{$cdate}</td><tr>";
    echo "</table>";    
  } else {
    $title = "Parent";
    $cdate = date("d-m-Y", filectime($root));  
    
    echo "<table style='float:left;width:235px;height:200px;margin-top:5px;margin-right:4px;border:0px solid #D2D2D2'>";
    echo "<tr><td style='text-align:center;font-size:11px'>{$title}</td><tr>";
    echo "<tr><td style='padding:3px;'><img src='/res/folder-home-dis.png' width='100%' height='200px'></td><tr>"; 
    echo "<tr><td style='text-align:center;font-size:11px'>{$cdate}</td><tr>";
    echo "</table>";    
  }

  if ($contextType === PERSONAL_CONTEXT_TYPE) {
    $title = "New folder";
    $cdate = date("d-m-Y");

    echo "<table style='float:left;width:235px;height:200px;margin-top:5px;margin-right:4px;border:0px solid #D2D2D2'>";
    echo "<tr><td style='text-align:center;font-size:11px'>{$title}</td><tr>";
    echo "<tr><td style='padding:3px;cursor:pointer;' onclick='makeNewFolder()'><img src='/res/new-folder.png' width='100%' height='200px'></td><tr>"; 
    echo "<tr><td style='text-align:center;font-size:11px'>{$cdate}</td><tr>";
    echo "</table>";    
  }  

  $pattern = $path . "/*";

  /*
   * Display subfolders
   */
  $aDirs = glob($pattern, GLOB_ONLYDIR);

  sort($aDirs);

  foreach ($aDirs as &$fsEntry) {

    $relPath = substr($fsEntry, strlen(APP_REPO_PATH));

    $ipos = mb_strripos($fsEntry, "/");
    $title = substr($fsEntry, $ipos+1);
    
    $cdate = date("d-m-Y", filectime($fsEntry));
    
    echo "<table style='float:left;width:235px;height:200px;margin-top:5px;margin-right:4px;border:0px solid #D2D2D2'>";
    echo "<tr><td style='text-align:center;font-size:11px'>{$title}</td><tr>";
    //echo "<tr><td style='padding:3px;'><a href='/index.php?path={$relPath}'><img src='/res/folder.png' width='100%' height='200px'></a></td><tr>"; 
    echo "<tr><td style='padding:3px;'><a href='#' onclick='changePath(\"{$relPath}\")'><img src='/res/folder.png' width='100%' height='200px'></a></td><tr>"; 
    echo "<tr><td style='text-align:center;font-size:11px'>{$cdate}</td><tr>";
    echo "</table>";
  }

  $aImages = glob($pattern);

  sort($aImages);

  $i=1;
  foreach ($aImages as &$fsEntry) {

    if (!is_dir($fsEntry)) {  
      
      $relPath = "/" . substr(APP_REPO_PATH, strlen(APP_PATH)+1) . substr($fsEntry, strlen(APP_REPO_PATH));
      $fileName = basename($fsEntry);

      $curFile = substr($curPath, strlen(APP_REPO_PATH)) . DIRECTORY_SEPARATOR . $fileName;
      $isPrivateFile = false;
      $imgLock = "/res/public.png";
      if (in_array($curFile . "\n",$privateData)) {
        $isPrivateFile = true;
        $imgLock = "/res/private.png";
      }  

      $ipos = mb_strripos($fsEntry, "/");
      $title = substr($fsEntry, $ipos+1);
      $ipos = mb_stripos($title, ".");
      $title = substr($title, 0, $ipos);

      $cdate = date("d-m-Y", filectime($fsEntry));

      if ((!$isPrivateFile && ($contextType === PUBLIC_CONTEXT_TYPE)) || ($contextType === PERSONAL_CONTEXT_TYPE)) {
        
        echo "<div style='float:left;width:235px'>";
        echo "<table style='width:235px;height:230px;margin-top:5px;margin-right:4px;background-color:#e1e1e1;border:1px solid #D2D2D2'>";
        echo "<tr>";
        if ($contextType === PERSONAL_CONTEXT_TYPE) {
          echo "<td style='width:23px;cursor:pointer; vertical-align:bottom;' ondblclick='delImg(\"{$i}\",\"{$fileName}\")'><img id='del-{$i}' class='imgdel' src='/res/del.png' style='height:19px;'></td>";
          echo "<td style='width:45px;cursor:pointer' ondblclick='changeVisibility(\"{$i}\",\"{$fileName}\")'><img id='lock-{$i}' class='imglock' src='{$imgLock}' style='height:23px;'></td>";
        } else {
          echo "<td style='width:23px;cursor:pointer; vertical-align:bottom;'><img id='del-{$i}' class='imgdel' src='/res/pxl.gif' style='height:19px;'></td>";
          echo "<td style='width:45px;cursor:pointer'><img id='lock-{$i}' class='imglock' src='/res/pxl.gif' style='height:23px;'></td>";
        }
        echo "<td style='height:23px;text-align:center;font-size:11px;'>{$title}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "<tr>";
        echo "<tr><td style='padding:3px;height:200px;background-image:url({$relPath});background-size:235px 200px;cursor:zoom-in;' colspan='3' onclick='openLink(\"{$relPath}\",\"_blank\")'>&nbsp;</td><tr>"; 
        echo "<tr><td style='text-align:left;font-size:11px' colspan='3'>&nbsp;{$cdate}</td><tr>";
        echo "</table>";
        echo "<div style='position:relative;top:-35px;text-align:right;padding-right:1.5px;'>";
        echo "<a href=\"https://www.facebook.com/sharer/sharer.php?u=http://homogram.com{$relPath}&t=\" onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\" target=\"_blank\" title=\"Share on Facebook\"><img src='/res/fb.png'></a>";
        echo "<a href=\"https://twitter.com/share?url=http://homogram.com{$relPath}&text=\" onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\" target=\"_blank\" title=\"Share on Twitter\"><img src='/res/twitter.png'></a>";
        echo "<a href=\"whatsapp://send?text=http://homogram.com{$relPath}\" data-action=\"share/whatsapp/share\" onClick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\" target=\"_blank\" title=\"Share on whatsapp\"><img src='/res/whatsapp.png'></a>";
        echo "</div>";
        echo "</div>";
      }
      $i++;
    }  
  }
  
}
 
  
 $password = filter_input(INPUT_POST, "Password");
 $command = filter_input(INPUT_POST, "CommandLine");
 
 $pwd = filter_input(INPUT_POST, "pwd"); 
 $hideSplash = filter_input(INPUT_POST, "hideSplash");
 $hideHCSplash = filter_input(INPUT_POST, "hideHCSplash");

 if ($password !== PHP_STR) {	
	$hash = hash("sha256", $password . APP_SALT, false);

	if ($hash !== APP_HASH) {
	  $password=PHP_STR;	
    }	 
 } 
 
 $curPath = APP_REPO_PATH;
 if ($pwd!==PHP_STR) {
   ///if (left($pwd, strlen(APP_REPO_PATH)) === APP_REPO_PATH) {
   if (file_exists(APP_REPO_PATH . $pwd) && is_dir(APP_REPO_PATH . $pwd)) {
     $curPath = APP_REPO_PATH . $pwd;
   }	    
 }	 
 chdir($curPath);
 $ipos = strripos($curPath, PHP_SLASH);
 $curDir = substr($curPath, $ipos);
 
 if ($password !== PHP_STR) {
      
   parseCommand($command);
   //echo("cmd=" . $cmd . "<br>");
   //echo("opt=" . $opt . "<br>");
   //echo("param1=" . $param1 . "<br>");
   //echo("param2=" . $param2 . "<br>");
   
   upload();
   
   if (mb_stripos(CMDLINE_VALIDCMDS, "|" . $command . "|")) {
 
   } else if (mb_stripos(CMDLINE_VALIDCMDS, "|" . $cmd . "|")) {
     
     if ($cmd === "privatify") {
       if (privatifyparamValidation()) {
         myExecPrivatifyCommand();
       }	     
     } else if ($cmd === "publicify") {
       if (publicifyparamValidation()) {
         myExecPublicifyCommand();
       }	     
     } else if ($cmd === "del") {
       if (delparamValidation()) {
         myExecDelCommand();
       }	     
     } else if ($cmd === "makedir") {
       if (makedirparamValidation()) {
         myExecMakeDirCommand();
       }	     
     } 	   
       
   } else {
     
   }
   
   $contextType = PERSONAL_CONTEXT_TYPE;
      
 } else {
 
 }
 
 ?>
 

<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
	
  <meta charset="UTF-8"/>
  <meta name="style" content="day1"/>
  
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  
<!--
    Copyright 2021, 2024 5 Mode

    This file is part of Homogram.

    Homogram is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Homogram is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Homogram. If not, see <https://www.gnu.org/licenses/>.
 -->
    
  <title>Homogram: every person its pictures..</title>
	
  <link rel="shortcut icon" href="./res/favicon.ico?v=<?php echo(time()); ?>" />
    
  <meta name="description" content="Welcome to <?php echo(APP_NAME); ?>"/>
  <meta name="author" content="5 Mode"/> 
  <meta name="robots" content="index,follow"/>
  
  <script src="./js/jquery-3.1.0.min.js" type="text/javascript"></script>
  <script src="./js/common.js" type="text/javascript"></script>
  <script src="./js/bootstrap.min.js" type="text/javascript"></script>
  <script src="./js/sha.js" type="text/javascript"></script>
  
  <script src="./js/home.js" type="text/javascript" defer></script>
  
  <link href="./css/bootstrap.min.css" type="text/css" rel="stylesheet">
  <link href="./css/style.css?v=<?php echo(time()); ?>" type="text/css" rel="stylesheet">
     
  <script>
  
	 $(document).ready(function() {

		 $("#Password").on("keydown",function(e){
		   key = e.which;
		   //alert(key);
		   if (key===13) {
			 e.preventDefault();
			 frmHC.submit();
		   } else { 
			 //e.preventDefault();
		   }
		 });

   });
		  
   window.addEventListener("load", function() {		 
		 <?php if($password===PHP_STR):?>
		    $("#Password").addClass("emptyfield");
		 <?php endif; ?>
     //maxY = document.getElementById("Console").scrollHeight;
     //alert(maxY);
	 }, true);

  function hideTitle() {
    $("#myh1").hide("slow");
  }
  
  function startApp() {
	  $("#HCsplash").hide("slow");
    $(document.body).css("background","#ffffff");
	  $("#frmHC").show();
	}			
  <?php if($hideHCSplash!=="1"): ?>
	window.addEventListener("load", function() {
	
    $(document.body).css("background","#000000");
	  $("#HCsplash").show("slow");	  
	  setTimeout("hideTitle()", 2000);
    setTimeout("startApp()", 4000);

	}, true);
	<?php else: ?>
  window.addEventListener("load", function() {
		  
	  startApp();
	  
	});	
  <?php endif; ?>

  </script>    
    
</head>
<body>

<div id="HCsplash" style="padding-top: 160px; text-align:center;color:#ffffff;display:none;">
   <div id="myh1"><H1>Homogram</H1></div><br>
   <img src="res/HGlogo2.png" style="width:310px;">
</div>

<form id="frmHC" method="POST" action="/" target="_self" enctype="multipart/form-data" style="display:<?php echo(($hideHCSplash==="1"?"inline":"none"));?>;">

<div class="header">
   <a href="http://homogram.org" target="_blank" style="color:#000000; text-decoration: none;"><img src="res/HGlogo2.png" style="width:45px;">&nbsp;Homogram</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://github.com/par7133/Homogram" style="color:#000000;"><span style="color:#119fe2">on</span> github</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:info@homogram.org" style="color:#000000;"><span style="color:#119fe2">for</span> feedback</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tel:+39-331-4029415" style="font-size:13px;background-color:#15c60b;border:2px solid #15c60b;color:#000000;height:27px;text-decoration:none;">&nbsp;&nbsp;get support&nbsp;&nbsp;</a>
</div>
	
<div style="clear:both; float:left; padding:8px; width:15%; height:100%; text-align:center;">
	<div style="padding-left:12px;text-align: left;">
	  &nbsp;
    <?php if ($password!==PHP_STR): ?>
    <a href="#" id="upload" style="color:#5ab5e4;" onclick="upload()">Upload</a>
	  <input id="files" name="files[]" type="file" accept=".gif,.png,.jpg,.jpeg" style="visibility: hidden;">
    <?php else: ?>
    <br>
    <?php endif; ?>
	</div>
    <br><br>
    <img src="res/HGgenius.png" alt="HG Genius" title="HG Genius" style="position:relative; left:+6px; width:90%; border: 1px dashed #EEEEEE;">
    &nbsp;<br><br><br>
    &nbsp;<input type="text" id="Password" name="Password" placeholder="password" style="font-size:10px; background:#393939; color:#ffffff; width: 90%; border-radius:3px;" value="<?php echo($password);?>" autocomplete="off"><br>
    &nbsp;<input type="text" id="Salt" placeholder="salt" style="position:relative; top:+5px; font-size:10px; background:#393939; color:#ffffff; width: 90%; border-radius:3px;" autocomplete="off"><br>
    &nbsp;<a href="#" onclick="showEncodedPassword();" style="position:relative; left:-2px; top:+5px; color:#000000; font-size:12px;">Hash Me!</a>     

<input type="hidden" id="CommandLine" name="CommandLine">
<input type="hidden" id="pwd" name="pwd" value="<?php echo(substr($curPath, strlen(APP_REPO_PATH))); ?>" style="color:black">
<input type="hidden" name="hideSplash" value="<?php echo($hideSplash); ?>">
<input type="hidden" name="hideHCSplash" value="1">

</div>

<div style="float:left; width:85%;height:100%; padding:8px; border-left: 1px solid #2c2f34;">
	
	<?php if (APP_SPLASH): ?>
	<?php if ($hideSplash !== PHP_STR): ?>
	<div id="splash" style="border-radius:20px; position:relative; left:+3px; width:98%; background-color: #33aced; padding: 20px; margin-bottom:8px;">	
	
	   <button type="button" class="close" aria-label="Close" onclick="closeSplash();" style="position:relative; left:-10px;">
        <span aria-hidden="true">&times;</span>
     </button>
	
	   Hello and welcome to Homogram!<br><br>
	   
	   Homogram is a light and simple software on premise to share your images.<br><br>
	   
	   Homogram is released under GPLv3 license, it is supplied AS-IS and we do not take any responsibility for its misusage.<br><br>
	   
     The name *Homogram* comes from a prank on the name 'instagram', in fact differently from the latter ones Homogram gives priorities to homines..<br><br>
     However Homogram doesn't birth as a replacement but just like its alter ego.. :o)<br><br> 
     
	   First step, use the left side panel password and salt fields to create the hash to insert in the config file. Remember to manually set there also the salt value.<br><br>
	   
	   As you are going to run Homogram in the PHP process context, using a limited web server or phpfpm user, you must follow some simple directives for an optimal first setup:<br>
	   <ol>
	   <li>Check the write permissions of your "HGRepo" folder in your web app private path; and set its path in the config file.</li>
	   <li>Check the write permissions of your "data" folder in your web app private path; and set its path in the config file.</li>
	   </ol>
	   
	   <br>	
     
	   Hope you can enjoy it and let us know about any feedback: <a href="mailto:info@homogram.org" style="color:#e6d236;">info@homogram.org</a>
	   
	</div>	
	<?php endif; ?>
	<?php endif; ?>
  <?php   
if ($contextType === PUBLIC_CONTEXT_TYPE) { 
  echo("&nbsp;You are in <span style='color:orange;'>~/" . substr($curPath, strlen(APP_REPO_PATH)+1) . "</span> as <span style='color:black;'>guest</span><br>");
} else {
  echo("&nbsp;You are in <span style='color:orange;'>~/" . substr($curPath, strlen(APP_REPO_PATH)+1) . "</span> as <span style='color:green;'>owner</span><br>");
}    
?><br>
	<div id="Console" style="hei-ght:493px; over-flow-y:auto; margin-top:10px;">
<?php showImages(); ?>
	</div>
	
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>

<div class="footer">
<div id="footerCont">&nbsp;</div>
<div id="footer"><span style="background:#E1E1E1;color:black;opacity:1.0;margin-right:10px;">&nbsp;&nbsp;A <a href="http://5mode.com">5 Mode</a> project and <a href="http://wysiwyg.systems">WYSIWYG</a> system. Some rights reserved.</span></div>	
</div>

</form>

</body>	 
</html>	 
