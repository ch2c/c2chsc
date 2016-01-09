<?php
/*
//$smarty->assign("ibis",$ibis);
// 	<a href="http://ibis.ne.jp/br/request?mode=3&url={$ibis}" accesskey=7 utn>7. ibisBrowser (※有料アプリ)</a></br>
*/
@header('HTTP/1.1 200 OK');
@header ('"Content-type: application/xhtml+xml"; charset=Shift_JIS');
@header('Content-Language: ja');

$conf = "config.cgi";
$url = @$_REQUEST['val'];
$url = str_replace("\">","",$url);
$url = str_replace("<script>","",$url);
$url = str_replace("</script>","",$url);
if($url==""){
	echo "<a href=\"http://c.2ch.sc\">c.2ch.sc</a>";
	exit();
}
include "libs/common.class.php";
include "Smarty/Smarty.class.php";
ini_set("default_charset", "Shift_JIS");
$com = new common();
$smarty = new Smarty;
$cacheOptions = array (
	'cacheDir' => $com->load($conf,"cachedir")."/",
	'lifeTime' => $com->load($conf,"cachelife")
);
// template, cache, configuration files
$smarty->debugging =  false;
$smarty->caching =true;
$smarty->cache_lifetime = $com->load($conf,"cachelife");
$smarty->template_dir = $com->load($conf,"viewdir");
$smarty->compile_dir  = $com->load($conf,"compdir");
$smarty->config_dir   = $com->load($conf,"configdir");
$smarty->cache_dir = $com->load($conf,"cachedir");
$smarty->left_delimiter ='{';
$smarty->right_delimiter ='}';
$smarty->registerFilter("pre","filterUtf8");
$smarty->registerFilter("output","filterSjis");

$views="links.tmpl.html";
$enurl= preg_replace('/%/','!',urlencode($url));
//var_dump($enurl);
//$ibis = urlencode($url);
$smarty->assign("url",$url);
$smarty->assign("enurl",$enurl);
// display
$smarty->display($views);
//UTF-8からShift-JISへ
function filterSjis($buff, &$smarty)
{
	return mb_convert_encoding($buff,"SJIS","UTF-8");
}
//Shift-JISからUTF-8へ
function filterUtf8($buff, &$smarty)
{
	return mb_convert_encoding($buff,"UTF-8","SJIS");
}
//文字フィルタ
function Moji($str)
{
	return mb_convert_encoding($str,"UTF-8","SJIS");
}


?>