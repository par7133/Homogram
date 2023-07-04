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
 
 $curPicture = "";
 $prevPicture = "";
 $nextPicture = "";
 $curLocale = APP_LOCALE;
 

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
   
   if (left($str, 1) === "'") {
     $ipos = stripos($str, "'", 1);
     if ($ipos > 0) {
       $param1 = substr($str, 0, $ipos+1);
       $str = substr($str, $ipos+1);
     } else {
       $param1 = $str;
       return;
     }  
   } else {   
     $ipos = stripos($str, PHP_SPACE);
     if ($ipos > 0) {
       $param1 = left($str, $ipos);
       $str = substr($str, $ipos+1);
     } else {
       $param1 = $str;
       return;
     }	     
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
	  echo("WARNING: invalid options<br>");	
    return false;
  }	
	//param1!="" and isword  
	$test_param1 = trim($param1,"'");
  if (($test_param1===PHP_STR) || !is_word($test_param1)) {
	  echo("WARNING: invalid image file<br>");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $test_param1;
	if (!file_exists($path)) {
    echo("WARNING: file must exists<br>");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    echo("WARNING: invalid image file<br>");	
	  return false;
	}  	
  //param1 file extension == gif | png | jpg | jpeg 
  if (!is_image($test_param1)) {
	  echo("WARNING: invalid image file<br>");	
	  return false;
  }    
  
	return true;
 }  


 function myExecPrivatifyCommand() {
   global $param1;
   global $curPath;
   
   $privateData = [];
   $publicData = [];
   
   $real_param1 = trim($param1, "'");
   
   $curFile = substr($curPath, strlen(APP_REPO_PATH)) . DIRECTORY_SEPARATOR . $real_param1;
   //echo "curFile=$curFile";
   
   if (APP_DEFAULT_CONTEXT === "PUBLIC") {
   
     // Insert in .public
  
     $privateFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".private";
     //echo "curFile=$privateFile";
     
     if (file_exists($privateFile)) {
       $privateData = file($privateFile);   
     }  
     if (!in_array($curFile . "\n", $privateData)) {
       $privateData[] = $curFile . "\n";  
       file_put_contents($privateFile, implode('', $privateData));
     }
   
   } else {
   
     // Cut off from .private
   
     $publicFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".public";
     //echo "curFile=$publicFile";
     
     if (file_exists($publicFile)) {
       $publicData = file($publicFile);   
     }  
     $key = array_search($curFile . "\n", $publicData);  
     if ($key!==false) {
       unset($publicData[$key]);  
       file_put_contents($publicFile, implode('', $publicData));
     }
   }   
 }


 function publicifyparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  echo("WARNING: invalid options<br>");	
    return false;
  }	
	//param1!="" and isword  
  $test_param1 = trim($param1,"'");
	if (($test_param1===PHP_STR) || !is_word($test_param1)) {
	  echo("WARNING: invalid image file<br>");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $test_param1;
	if (!file_exists($path)) {
    echo("WARNING: file must exists<br>");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    echo("WARNING: invalid image file<br>");	
	  return false;
	}  	
  //param1 file extension == gif | png | jpg | jpeg 
  if (!is_image($test_param1)) {
	  echo("WARNING: invalid image file<br>");	
	  return false;
  }    
  
	return true;
 }


 function myExecPublicifyCommand() {
   global $param1;
   global $curPath;
   
   $privateData = [];
   $publicData = [];
   
   $real_param1 = trim($param1, "'");
   
   $curFile = substr($curPath, strlen(APP_REPO_PATH)) . DIRECTORY_SEPARATOR . $real_param1;
   //echo "curFile=$curFile";
   
   if (APP_DEFAULT_CONTEXT === "PRIVATE") {
   
     // Insert in .public
  
     $publicFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".public";
     //echo "curFile=$publicFile";
     
     if (file_exists($publicFile)) {
       $publicData = file($publicFile);   
     }  
     if (!in_array($curFile . "\n", $publicData)) {
       $publicData[] = $curFile . "\n";  
       file_put_contents($publicFile, implode('', $publicData));
     }
   
   } else {
   
     // Cut off from .private
   
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
 }


 function delparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  echo("WARNING: invalid options<br>");	
    return false;
  }	
	//param1!="" and isword
  $test_param1 = trim($param1,"'");  
  //echo("WARNING: ".$test_param1."<br>");
	if (($test_param1===PHP_STR) || !is_word($test_param1)) {
	  echo("WARNING: invalid image file<br>");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $test_param1;
	if (!file_exists($path)) {
    echo("WARNING: file must exists<br>");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    echo("WARNING: invalid image file<br>");	
	  return false;
	}  	
  //param1 file extension == gif | png | jpg | jpeg 
  if (!is_image($test_param1)) {
	  echo("WARNING: invalid image file<br>");	
	  return false;
  }    
  
	return true;
 }


 function myExecDelCommand() {
   global $param1;
   global $curPath;
   
   $real_param1 = trim($param1, "'");
   $curFile = $curPath . DIRECTORY_SEPARATOR . $real_param1;
   
   unlink($curFile);
   
   $curFileThumb = $curPath . DIRECTORY_SEPARATOR . "thumbs"  . DIRECTORY_SEPARATOR . $real_param1;
   
   if (is_readable($curFileThumb)) {
     unlink($curFileThumb);
   }   
   
 }  


 function makedirparamValidation() {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  echo("WARNING: invalid options<br>");	
    return false;
  }	
	//param1!="" and isword
  $test_param1 = trim($param1,"'");  
	if (($test_param1===PHP_STR) || !is_word($test_param1)) {
	  echo("WARNING: invalid folder name<br>");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    echo("WARNING: invalid parameters<br>");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $test_param1;
	if (file_exists($path)) {
    echo("WARNING: file must not exists<br>");	
	  return false;
	}  	
  //param1 file extension != gif | png | jpg | jpeg 
  if (is_image($test_param1)) {
	  echo("WARNING: invalid folder name<br>");	
	  return false;
  }    
  
	return true;
   
 }  


 function myExecMakeDirCommand() {
   global $param1;
   global $curPath;

   $real_param1 = trim($param1, "'");
   $newpath = $curPath . DIRECTORY_SEPARATOR . $real_param1;
   
   mkdir($newpath, 0777);   
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
       echo("WARNING: No file uploaded.<br>");
       return;
     } 
 
     foreach($uploads as &$upload) {
		
       switch ($upload['error']) {
       case PHP_UPLOAD_ERR_OK:
         break;
       case PHP_UPLOAD_ERR_NO_FILE:
         echo("WARNING: One or more uploaded files are missing.<br>");
         return;
       case PHP_UPLOAD_ERR_INI_SIZE:
         echo("WARNING: File exceeded INI size limit.<br>");
         return;
       case PHP_UPLOAD_ERR_FORM_SIZE:
         echo("WARNING: File exceeded form size limit.<br>");
         return;
       case PHP_UPLOAD_ERR_PARTIAL:
         echo("WARNING: File only partially uploaded.<br>");
         return;
       case PHP_UPLOAD_ERR_NO_TMP_DIR:
         echo("WARNING: TMP dir doesn't exist.<br>");
         return;
       case PHP_UPLOAD_ERR_CANT_WRITE:
         echo("WARNING: Failed to write to the disk.<br>");
         return;
       case PHP_UPLOAD_ERR_EXTENSION:
         echo("WARNING: A PHP extension stopped the file upload.<br>");
         return;
       default:
         echo("WARNING: Unexpected error happened.<br>");
         return;
       }
      
       if (!is_uploaded_file($upload['tmp_name'])) {
         echo("WARNING: One or more file have not been uploaded.<br>");
         return;
       }
      
       // name	 
       $name = (string)substr((string)filter_var($upload['name']), 0, 255);
       if ($name == PHP_STR) {
         echo("WARNING: Invalid file name: " . $name."<br>");
         return;
       } 
       $upload['name'] = $name;
       
       // fileType
       $fileType = substr((string)filter_var($upload['type']), 0, 30);
       $upload['type'] = $fileType;	 
       
       // tmp_name
       $tmp_name = substr((string)filter_var($upload['tmp_name']), 0, 300);
       if ($tmp_name == PHP_STR || !file_exists($tmp_name)) {
         echo("WARNING: Invalid file temp path: " . $tmp_name."<br>");
         return;
       } 
       $upload['tmp_name'] = $tmp_name;
       
       //size
       $size = substr((string)filter_var($upload['size'], FILTER_SANITIZE_NUMBER_INT), 0, 12);
       if ($size == "") {
         echo("WARNING: Invalid file size.<br>");
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
         echo("WARNING: destination already exists.<br>");
         return;
       }	   
       
       copy($tmpFullPath, $destFullPath);

       chmod($destFullPath, 0766); 

       // Creating thumb file 
       if (is_readable($curPath . DIRECTORY_SEPARATOR . "thumbs")) {  
         $destFullPath = $curPath . DIRECTORY_SEPARATOR . "thumbs" . DIRECTORY_SEPARATOR . $destFileName;

         copy($tmpFullPath, $destFullPath);

         chmod($destFullPath, 0766); 
       }

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
  global $curLocale;
  
  $exclData = [];
  
  if (APP_DEFAULT_CONTEXT === "PUBLIC") { 
    $exclFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".private";
  } else {   
    $exclFile = APP_DATA_PATH . DIRECTORY_SEPARATOR . ".public"; 
  }  
  if (file_exists($exclFile)) {
    $exclData = file($exclFile);   
  }  
 
  ///$root = "img";
  $root = APP_REPO_PATH; 
  
  //subpath
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
    
    $title = getResource("Parent", $curLocale);
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
    $title = getResource("Parent", $curLocale);
    $cdate = date("d-m-Y", filectime($root));  
    
    echo "<table style='float:left;width:235px;height:200px;margin-top:5px;margin-right:4px;border:0px solid #D2D2D2'>";
    echo "<tr><td style='text-align:center;font-size:11px'>{$title}</td><tr>";
    echo "<tr><td style='padding:3px;'><img src='/res/folder-home-dis.png' width='100%' height='200px'></td><tr>"; 
    echo "<tr><td style='text-align:center;font-size:11px'>{$cdate}</td><tr>";
    echo "</table>";    
  }

  if ($contextType === PERSONAL_CONTEXT_TYPE) {
    $title = getResource("Add folder", $curLocale);
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
    
    if ($title === "thumbs") {
      continue;
    }

    $cdate = date("d-m-Y", filectime($fsEntry));
    
    echo "<table style='float:left;width:235px;height:200px;margin-top:5px;margin-right:4px;border:0px solid #D2D2D2'>";
    echo "<tr><td style='text-align:center;font-size:11px'>{$title}</td><tr>";
    echo "<tr><td style='padding:3px;'><a href='#' onclick='changePath(\"{$relPath}\")'><img src='/res/folder.png' width='100%' height='200px'></a></td><tr>"; 
    echo "<tr><td style='text-align:center;font-size:11px'>{$cdate}</td><tr>";
    echo "</table>";
  }

  /*
   * Display images
   */
  if (is_readable($path . "/thumbs")) { 
    $pattern = $path . "/thumbs/*";
  } else {
    $pattern = $path . "/*";
  }

  $aImages = glob($pattern);

  sort($aImages);

  $serverName = filter_input(INPUT_SERVER, "SERVER_NAME"); 

  $i=1;
  foreach ($aImages as &$fsEntry) {

    if (!is_dir($fsEntry)) {  
      
      $relPath = "/" . substr(APP_REPO_PATH, strlen(APP_PATH)+1) . substr($fsEntry, strlen(APP_REPO_PATH));
      $fileName = basename($fsEntry);

      $curFile = substr($curPath, strlen(APP_REPO_PATH)) . DIRECTORY_SEPARATOR . $fileName;
      
      if (APP_DEFAULT_CONTEXT === "PUBLIC") {
        $isPrivateFile = false;
        $imgLock = "/res/public.png";
        if (in_array($curFile . "\n",$exclData)) {
          $isPrivateFile = true;
          $imgLock = "/res/private.png";
        }  
      } else {  
        $isPrivateFile = true;
        $imgLock = "/res/private.png";
        if (in_array($curFile . "\n",$exclData)) {
          $isPrivateFile = false;
          $imgLock = "/res/public.png";
        }  
      }
      
      $ipos = mb_strripos($fsEntry, "/");
      $title = substr($fsEntry, $ipos+1);
      $ipos = mb_stripos($title, ".");
      $title = substr($title, 0, $ipos);
      
      //parsing title for underscore
      if (APP_LAST_UNDERSCORE_CHECK) {
        $ipos = mb_strripos($title, "_");
        if ($ipos === false) {
        } else {
          $title = substr($title, $ipos+1);
        }
      } 
        
      if (strlen($title)>22) {
        $title = left($title,22) . "..";
      }  

      $cdate = date("d-m-Y", filectime($fsEntry));

      if ((!$isPrivateFile && ($contextType === PUBLIC_CONTEXT_TYPE)) || ($contextType === PERSONAL_CONTEXT_TYPE)) {
        
        echo "<div class=\"image-cont\" style='float:left;width:235px;margin-right:4px;display:none;'>";
        echo "<table style='width:235px;height:230px;margin-top:5px;margin-right:4px;background-color:#e1e1e1;border:1px solid #D2D2D2;'>";
        echo "<tr>";
        if ($contextType === PERSONAL_CONTEXT_TYPE) {
          echo "<td style='width:23px;cursor:pointer; vertical-align:bottom;' ondblclick='delImg(\"{$i}\",\"{$fileName}\")'><img id='del-{$i}' class='imgdel' src='/res/del.png' style='height:19px;'></td>";
          echo "<td style='width:45px;cursor:pointer' ondblclick='changeVisibility(\"{$i}\",\"{$fileName}\")'><img id='lock-{$i}' class='imglock' src='{$imgLock}' style='height:23px;'></td>";
        } else {
          echo "<td style='width:1px;cursor:pointer; vertical-align:bottom;'><img id='del-{$i}' class='imgdel' src='/res/pxl.gif' style='height:1px;'></td>";
          echo "<td style='width:1px;cursor:pointer'><img id='lock-{$i}' class='imglock' src='/res/pxl.gif' style='height:1px;'></td>";
        }
        //echo "<td style='height:23px;text-align:center;font-size:11px;'>";
        if ($contextType === PERSONAL_CONTEXT_TYPE) {
          echo "<td style='height:23px;text-align:right;font-size:11px;'>";
          echo "{$title}&nbsp;";
        } else {
          echo "<td style='height:23px;text-align:center;font-size:11px;'>";
          echo "{$title}&nbsp;";
        }  
        echo "</td>";
        echo "<tr>";
        echo "<tr><td style='padding:3px;width:235px;height:200px;background-image:url(\"{$relPath}\");background-size:235px 200px;cursor:zoom-in;' colspan='3' onclick=\"openPic('$fileName')\"'>&nbsp;</td><tr>"; 
        echo "<tr><td style='text-align:left;font-size:11px' colspan='3'>&nbsp;{$cdate}</td><tr>";
        echo "</table>";
        echo "<div style='position:relative;top:-35px;text-align:right;padding-right:1.5px;'>";
        echo "<a href=\"https://www.facebook.com/sharer/sharer.php?u=http://{$serverName}{$relPath}&t=\" onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\" target=\"_blank\" title=\"Share on Facebook\"><img src='/res/fb.png'></a>";
        echo "<a href=\"https://twitter.com/share?url=http://{$serverName}{$relPath}&text=\" onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\" target=\"_blank\" title=\"Share on Twitter\"><img src='/res/twitter.png'></a>";
        echo "<a href=\"whatsapp://send?text=http://{$serverName}{$relPath}\" data-action=\"share/whatsapp/share\" onClick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;\" target=\"_blank\" title=\"Share on whatsapp\"><img src='/res/whatsapp.png'></a>";
        echo "</div>";
        echo "</div>";
      }
      $i++;
    }  
  }
  
}

 function openPicParamValidation() 
 {

	global $curPath;
	global $opt;
	global $param1;
	global $param2; 
	global $param3;

	//opt!=""
  if ($opt!==PHP_STR) {
	  echo("invalid options");	
    return false;
  }	
	//param1!="" and isword  
	if (($param1===PHP_STR) || !is_word($param1)) {
	  echo("invalid image file");	
    return false;
  }
	//param2==""
	if ($param2!==PHP_STR) {
    echo("invalid parameters");
    return false;
  }
  //param3==""
  if ($param3!==PHP_STR) {
    echo("invalid parameters");
    return false;
  }
	//param1 exist
	$path = $curPath . DIRECTORY_SEPARATOR . $param1;
	if (!file_exists($path)) {
    echo("file must exists");	
	  return false;
	}  	
	//param1 is_file
	if (!is_file($path)) {
    echo("invalid image file:" . $param1);	
	  return false;
	}  	
  //param1 is_image
  if (!is_image($param1)) {
	  echo("invalid image file" . $param1);	
	  return false;
  }    

	return true;
   
 }  
 
 
 function myExecOpenPicCommand() {
   
   global $curPath; 
   global $curPicture;
   global $param1;
   
   $curPicture = substr($curPath.DIRECTORY_SEPARATOR.$param1, strlen(dirname(APP_REPO_PATH)));
 
 }   
 
  
 $password = filter_input(INPUT_POST, "Password")??"";
 $password = strip_tags($password);
 if ($password==PHP_STR) {
   $password = filter_input(INPUT_POST, "Password2")??"";
   $password = strip_tags($password);
 }  
 
 $command = filter_input(INPUT_POST, "CommandLine")??"";
 $command = strip_tags($command);
 
 $pwd = filter_input(INPUT_POST, "pwd")??""; 
 $pwd = strip_tags($pwd);
 
 $hideSplash = filter_input(INPUT_POST, "hideSplash")??"";
 $hideSplash = strip_tags($hideSplash);
 
 $hideHCSplash = filter_input(INPUT_POST, "hideHCSplash")??"";
 $hideHCSplash = strip_tags($hideHCSplash);
 
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
 
 parseCommand($command);
 //echo("cmd=" . $cmd . "<br>");
 //echo("opt=" . $opt . "<br>");
 //echo("param1=" . $param1 . "<br>");
 //echo("param2=" . $param2 . "<br>");
 
 if ($password !== PHP_STR) {
      
   upload();
   
   if (mb_stripos(CMDLINE_VALIDCMDS, "|" . $command . "|")) {
 
     if ($command === "refresh") {
       // refreshing Msg Board..
     }
 
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
     } else if ($cmd === "openpic") {
       if (openPicParamValidation()) {
         myExecOpenPicCommand();
       }	
     }   
       
   } else {
     
   }
   
   $contextType = PERSONAL_CONTEXT_TYPE;
      
 } else {
 
   if (mb_stripos(CMDLINE_VALIDCMDS, "|" . $cmd . "|")) {
     if ($cmd === "openpic") {
       if (openPicParamValidation()) {
         myExecOpenPicCommand();
       }	
     }   
   }
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
  
  <script src="./js/jquery-3.6.0.min.js" type="text/javascript"></script>
  <script src="./js/common.js" type="text/javascript"></script>
  <script src="./js/bootstrap.min.js" type="text/javascript"></script>
  <script src="./js/sha.js" type="text/javascript"></script>
  
  <script src="./js/home.js" type="text/javascript" defer></script>
  
  <link href="./css/bootstrap.min.css" type="text/css" rel="stylesheet">
  <link href="./css/style.css?v=<?php echo(time()); ?>" type="text/css" rel="stylesheet">
    
</head>
<body>

<div id="HCsplash" style="padding-top: 160px; text-align:center;color:#ffffff;display:none;">
   <div id="myh1"><H1>Homogram</H1></div><br>
   <img src="res/HGlogo2.png" style="width:310px;">
</div>

<?php
//echo ("curPicture=**$curPicture**");
  if ($curPicture != PHP_STR) {
    
    $apic = glob($curPath . DIRECTORY_SEPARATOR . "*");
    $i=0;
    foreach($apic as &$path) {
      $fileName = basename($path);
      if (is_file($curPath . DIRECTORY_SEPARATOR . $fileName)) {
        $path=$fileName;
      } else {
        unset($apic[$i]); 
      } 
      $i++;  
    }
      
    $i=array_search(basename($curPicture), $apic);
    // if the only one
    if (count($apic)==1) {
      $prevPicture = basename($apic[0]);
      $nextPicture = basename($apic[0]);
    // if first  
    } else if ($i==0) {
      $prevPicture = basename($apic[count($apic)-1]);
      $nextPicture = basename($apic[1]);
    // if last        
    } else if ($i==(count($apic)-1)) {
      $prevPicture = basename($apic[$i-1]);
      $nextPicture = basename($apic[0]);      
    } else {
      $prevPicture = basename($apic[$i-1]);
      $nextPicture = basename($apic[$i+1]);      
    }    
    
    $hidePlayer = "0";
  } else {
    $hidePlayer = "1";    
  }    
?>
<div id="picPlayer" style="width:100%;height:1900px;vertical-align:middle;text-align:center;background:#000000;display:<?php echo(($hidePlayer==="1"? "none": "inline"));?>;">
   <div id="closePlayer" style="position: absolute; top:20px; left:20px; cursor:pointer;" onclick="closePlayer()"><img src="/res/parent.png" style="width:64px;"></div>
   <div id="myPicCont" style="width:100%;max-width:100%;clear:both;margin:auto;vertical-align:middle;background:#000000;"><img id="myPic" src="<?php echo($curPicture);?>" style="width:100%;vertical-align:middle;display:none;;background:#000000;"></div>
   <div id="navPlayer1" style="position:absolute;top:3000px;width:175px;cursor:pointer;overflow-x:hidden;border:0px solid red;" onclick="openPic('<?php echo($prevPicture);?>')"><img src="/res/picPrev.png" style="width:200px;position:relative;left:-125px;"></div>
   <div id="navPlayer2" style="position:absolute;top:3000px;width:175px;cursor:pointer;overflow-x:hidden;border:0px solid red;" onclick="openPic('<?php echo($nextPicture);?>')"><img src="/res/picNext.png" style="width:200px;position:relative;left:+100px;"></div>
</div>

<form id="frmHC" method="POST" action="/" target="_self" enctype="multipart/form-data" style="display:<?php echo((($hideHCSplash == "1") && ($hidePlayer == "1")?"inline":"none"));?>;">

<div class="header">
   <a id="burger-menu" href="#" style="display:none;"><img src="/res/burger-menu2.png" style="width:58px;"></a><a id="ahome" href="http://homogram.5mode-foss.eu" target="_blank" style="color:#000000; text-decoration: none;"><img  id="logo-hg" src="res/HGlogo2.png" style="width:45px;">&nbsp;Homogram</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="agithub" href="https://github.com/par7133/Homogram" style="color:#000000;"><span style="color:#119fe2">on</span> github</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="afeedback" href="mailto:posta@elettronica.lol" style="color:#000000;"><span style="color:#119fe2">for</span> feedback</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="asupport" href="tel:+39-331-4029415" style="font-size:13px;background-color:#15c60b;border:2px solid #15c60b;color:#000000;height:27px;text-decoration:none;">&nbsp;&nbsp;get support&nbsp;&nbsp;</a><div id="pwd2" style="float:right;position:relative;top:+13px;display:none"><input type="password" id="Password2" name="Password2" placeholder="password" style="font-size:13px; background:#393939; color:#ffffff; width: 125px; border-radius:3px;" value="<?php echo($password);?>" autocomplete="off"></div>
</div>

<div style="clear:both;"></div>

<table class="burger-header" style="width:100%;border:3px solid #e4f5f7;display:none;">
<tr>
<td style="width:100%;background:#e4f5f7;">    
<?php if ($password!==PHP_STR): ?>
    <div class="burger-header-ve" style="float:left;width:31%;font-size:14px;padding:4px;border:3px solid #e4f5f7;margin-top:2px;margin-right:2px;margin-bottom:2px;text-align:left;cursor:pointer;">&nbsp;&nbsp;<a href="#" style="text-decoration:none;color:black;" onclick="upload()"><?php echo(strtolower(getResource("Upload", $curLocale)));?></a></div>
<?php endif; ?>
    <div class="burger-header-ve" style="float:left;width:31%;font-size:14px;padding:4px;border:3px solid #e4f5f7;margin-top:2px;margin-right:2px;margin-bottom:2px;text-align:left;cursor:pointer;">&nbsp;&nbsp;<a href="https://github.com/par7133/Homogram" style="text-decoration:none;color:black;">on github</a></div>
    <div class="burger-header-ve" style="float:left;width:31%;font-size:14px;padding:4px;border:3px solid #e4f5f7;margin-top:2px;margin-right:2px;margin-bottom:2px;text-align:left;cursor:pointer;">&nbsp;&nbsp;<a href="mailto:posta@elettronica.lol" style="text-decoration:none;color:black;">for feedback</a></div>
    <div class="burger-header-ve" style="float:left;width:31%;font-size:14px;padding:4px;border:3px solid #e4f5f7;margin-top:2px;margin-right:2px;margin-bottom:2px;text-align:left;cursor:pointer;">&nbsp;&nbsp;<a href="tel:+39-331-4029415" style="text-decoration:none;color:black;">get support</a></div>
</td>
</tr>
</table>  

<div style="clear:both;"></div>


<div id="sidebar" style="clear:both; float:left; padding:8px; width:25%; max-width:250px; height:100%; text-align:center; border-right: 1px solid #2c2f34;">
	<div style="padding-left:12px;text-align: left;">
	  &nbsp;
    <?php if ($password!==PHP_STR): ?>
    <a href="#" id="upload" style="color:#5ab5e4;" onclick="upload()"><?php echo(getResource("Upload", $curLocale));?></a>
	  <input id="files" name="files[]" type="file" accept=".gif,.png,.jpg,.jpeg" style="visibility: hidden;" multiple>
    <?php else: ?>
    <br>
    <?php endif; ?>
	</div>
    <br><br>
    <img src="res/HGgenius.png" alt="HG Genius" title="HG Genius" style="position:relative; left:+6px; width:90%; border: 1px dashed #EEEEEE;">
    &nbsp;<br><br><br>
    <div style="text-align:left;white-space:nowrap;">
    &nbsp;&nbsp;<input type="password" id="Password" name="Password" placeholder="password" style="font-size:13px; background:#393939; color:#ffffff; width: 60%; border-radius:3px;" value="<?php echo($password);?>" autocomplete="off">&nbsp;<input type="submit" value="<?php echo(getResource(" Go ", $curLocale));?>" style="text-align:left;width:25%;"><br>
    &nbsp;&nbsp;<input type="text" id="Salt" placeholder="salt" style="position:relative; top:+5px; font-size:13px; background:#393939; color:#ffffff; width: 90%; border-radius:3px;" autocomplete="off"><br>
    <div style="text-align:center;">
    <a href="#" onclick="showEncodedPassword();" style="position:relative; left:-2px; top:+5px; color:#000000; font-size:12px;"><?php echo(getResource("Hash Me", $curLocale));?>!</a>     
    </div> 
    </div>

<input type="hidden" id="CommandLine" name="CommandLine">
<input type="hidden" id="pwd" name="pwd" value="<?php echo(substr($curPath, strlen(APP_REPO_PATH))); ?>" style="color:black">
<input type="hidden" name="hideSplash" value="<?php echo($hideSplash); ?>">
<input type="hidden" name="hideHCSplash" value="1">
   
</div>

<div id="contentbar" style="float:left; width:75%;height:100%; padding:8px;">
	
	<?php if (APP_SPLASH): ?>
	<?php if ($hideSplash !== PHP_STR): ?>
	<div id="splash" style="border-radius:20px; position:relative; left:+3px; width:98%; background-color: #33aced; padding: 20px; margin-bottom:8px;">	
	
	   <button type="button" class="close" aria-label="Close" onclick="closeSplash();" style="position:relative; left:-10px;">
        <span aria-hidden="true">&times;</span>
     </button>
	
	   Hello and welcome to Homogram!<br><br>
	   
	   Homogram is a light and simple software on premise to share your images. And it can be used as a private repository (screenshots, private shots, etc).<br><br>
	   
	   Homogram is released under GPLv3 license, it is supplied AS-IS and we do not take any responsibility for its misusage.<br><br>
	   
     The name *Homogram* comes from a prank on the name 'instagram', in fact differently from the latter Homogram gives priorities to homines. However Homogram doesn't birth as a replacement but just like its alter ego.<br><br> 
     
	   First step, use the left side panel password and salt fields to create the hash to insert in the config file. Remember to manually set there also the salt value.<br><br>
	   
	   As you are going to run Homogram in the PHP process context, using a limited web server or phpfpm user, you must follow some simple directives for an optimal first setup:<br>
	   <ol>
	   <li>Check the write permissions of your "HGRepo" folder in your web app public path; and set its path in the config file.</li>
	   <li>Check the write permissions of your "data" folder in your web app private path; and set its path in the config file.</li>
     <li>Set the default Locale.</li>
     <li>Set the default Context to PUBLIC or PRIVATE (for a private repository).</li>
	   </ol>
	   
	   <br>	
     
	   Hope you can enjoy it and let us know about any feedback: <a href="mailto:posta@elettronica.lol" style="color:#e6d236;">posta@elettronica.lol</a>
	   
	</div>	
	<?php endif; ?>
	<?php endif; ?>
  <?php   
if ($contextType === PUBLIC_CONTEXT_TYPE) { 
  echo("&nbsp;" . getResource("You are in ", $curLocale) . "<span style='color:orange;'>~/" . substr($curPath, strlen(APP_REPO_PATH)+1) . "</span>" . getResource(" as ", $curLocale) . "<span style='color:black;'>" . getResource("guest", $curLocale) . "</span><br>");
} else {
  echo("&nbsp;" . getResource("You are in ", $curLocale) . "<span style='color:orange;'>~/" . substr($curPath, strlen(APP_REPO_PATH)+1) . "</span>" . getResource(" as ", $curLocale) . "<span style='color:green;'>" . getResource("owner", $curLocale) . "</span><br>");
}    
?><br>
	<div id="Console" style="hei-ght:493px; over-flow-y:auto; margin-top:10px;">
<?php showImages(); ?>
	</div>
	
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>

</form>

<div class="footer">
<div id="footerCont">&nbsp;</div>
<div id="footer"><span style="background:#E1E1E1;color:black;opacity:1.0;margin-right:10px;">&nbsp;&nbsp;A <a href="http://5mode.com">5 Mode</a> project and <a href="http://demo.5mode.com">WYSIWYG</a> system. Some rights reserved.</span></div>	
</div>

<script>
  
function setPPlayer() {
  
  $("#picPlayer").css("height", parseInt(window.innerHeight)+"px");

  $("#myPicCont").css("height", parseInt(window.innerHeight)+"px");
  $("#myPicCont").css("max-width", parseInt(window.innerWidth)+"px");
  
  $("#closePlayer").css("left", "10px");
  $("#navPlayer1").css("top", parseInt((window.innerHeight-200)/2)+"px");
  $("#navPlayer2").css("top", parseInt((window.innerHeight-200)/2)+"px");
  $("#navPlayer2").css("left", parseInt(window.innerWidth-175)+"px");
  
  if (document.getElementById("myPic").src!="") {
    if ($("#myPic").width() > $("#myPic").height()) {
      f = $("#myPic").width() / $("#myPic").height();
      $("#myPic").css("padding-top", parseInt((window.innerHeight - $("#myPic").height()) / 2)+"px");
      $("#myPic").css("width", "100%"); //parseInt(window.innerWidth)+"px");
      $("#myPic").css("height", "");
      $("#myPic").css("max-height", parseInt(window.innerHeight)+"px");
    } else {
      $("#myPic").css("width", "");
      $("#myPic").css("max-width", parseInt(window.innerWidth)+"px");
      $("#myPic").css("height", "100%"); //parseInt(window.innerHeight)+"px");
      $("#myPicCont").css("max-width", parseInt(window.innerWidth)+"px");      
    }    
    $("#myPic").css("display", "inline");
  }  

  $(document.body).css("overflow-x","hidden");
}  

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
  
  <?php if ($hidePlayer == "1"): ?>  
  startApp();
  <?php endif; ?>
  
});	
<?php endif; ?>

window.addEventListener("load", function() {
  <?php if ($hideHCSplash != "1" || $hidePlayer != "1"): ?>
  $(document.body).css("backgrond","#000000");
  <?php else: ?>
  $(document.body).css("backgrond","#FFFFFF");
  <?php endif; ?>
});

window.addEventListener("load", function() {

 <?php if($password===PHP_STR):?>
    $("#Password").addClass("emptyfield");
 <?php endif; ?>
 //maxY = document.getElementById("Console").scrollHeight;
 //alert(maxY);

  <?php if ($hidePlayer == "0"): ?>
  setPPlayer();
  <?php endif; ?>
}, true);

window.addEventListener("resize", function() {
  <?php if ($hidePlayer == "0"): ?>
  setPPlayer();
  <?php endif; ?>
}, true);

</script>    

<!-- METRICS CODE -->
<?php if (file_exists(APP_PATH . DIRECTORY_SEPARATOR . "metrics.html")): ?>
<?php include(APP_PATH . DIRECTORY_SEPARATOR . "metrics.html"); ?> 
<?php endif; ?>
  
</body>	 
</html>	 
