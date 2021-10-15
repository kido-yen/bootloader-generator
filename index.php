<?php
$srcfile = 'bootloader_base.img';

function generate_disk_image ($filename){
  $srcfile=$GLOBALS['srcfile'];
  copy($srcfile, $filename);
  $ipxe_cfg=$filename.'.ipxe';
  $myfile = fopen($ipxe_cfg, "w") or die("Unable to open file!");
  $config='#!ipxe'."\n";
  /*
  #set KidoBDF 0000:00:08.0
  #set KidoMAC 08:00:27:6b:53:eb
  #set KidoMAC 08:00:27:89:ea:ad
  #set KidoIP 10.5.12.120
  #set KidoNETMASK 255.255.255.0
  #set KidoGATEWAY 10.5.12.1
  #set KidoFILENAME http://10.5.15.10/test/bdf.ipxe
  #set KidoDNS 8.8.8.8
  */
  if(isset($_REQUEST['KidoBDF'])) $config=$config.'set KidoBDF '.$_REQUEST['KidoBDF']."\n";
  if(isset($_REQUEST['KidoMAC'])) $config=$config.'set KidoMAC '.$_REQUEST['KidoMAC']."\n";
  if(isset($_REQUEST['KidoIP'])) $config=$config.'set KidoIP '.$_REQUEST['KidoIP']."\n";
  if(isset($_REQUEST['KidoNETMASK'])) $config=$config.'set KidoNETMASK '.$_REQUEST['KidoNETMASK']."\n";
  if(isset($_REQUEST['KidoGATEWAY'])) $config=$config.'set KidoGATEWAY '.$_REQUEST['KidoGATEWAY']."\n";
  if(isset($_REQUEST['KidoFILENAME'])) $config=$config.'set KidoFILENAME '.$_REQUEST['KidoFILENAME']."\n";
  if(isset($_REQUEST['KidoDNS'])) $config=$config.'set KidoDNS '.$_REQUEST['KidoDNS']."\n";
  fwrite($myfile, $config);
  fclose($myfile);
  $cmd=sprintf("mcopy -o -i %s %s \"::/utils/Kido.ipxe\"",$filename,$ipxe_cfg);
  shell_exec($cmd);
  unlink($ipxe_cfg);
}

function geniso($filename){
  mkdir($filename.'_iso', 0700);
  rename($filename, $filename.'_iso'.'/bootloader.img');
  shell_exec('mkisofs  -P "Kido Yen" -V "bootloader-generator" -b bootloader.img -hide bootloader.img -iso-level 3 -eltorito-alt-boot -e bootloader.img -o '.$filename.'.iso ./'.$filename.'_iso');
}

function download($filename,$format){
  if (file_exists($filename)) {
    $output_format="img";
    if($format == "iso") $output_format="iso";
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="bootloader.'.$output_format.'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
    unlink($filename);
  }
}

$file=uniqid() . ".img";
$format="disk";

if(isset($_REQUEST['format'])) $format=$_REQUEST['format'];

switch ($format) {
  case "disk":
      break;
  case "iso":
      break;
  default:
      echo "unknown file format";
      exit;
}

generate_disk_image($file);
if ($format=="iso"){
  geniso($file);
  $iso_dir=$file.'_iso';
  $file=$file.'.iso';
}

download($file,$format);
if ($format=="iso"){
  unlink($iso_dir.'/bootloader.img');
  rmdir($iso_dir);
}
exit;
?>
