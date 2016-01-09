<?php
/**
	[test.php]

	Copyright (c) [2014] [2ch.sc]

	This software is released under the MIT License.

	http://opensource.org/licenses/mit-license.php

*/
@header('HTTP/1.1 200 OK');
//@header('application/xhtml+xml; charset=Shift_JIS');
@header ('"Content-type: application/xhtml+xml"; charset=Shift_JIS');
@header('Content-Language: ja');
//$time_start = microtime(true);
/*
	Include Library
*/

include "libs/bbslist.class.php";
include "libs/common.class.php";
include "libs/get.class.php";
include "libs/parser.class.php";
include "libs/util.class.php";
include "imports/Cache/Lite.php";
include "Smarty/Smarty.class.php";
ini_set("default_charset", "Shift_JIS");

/*
	config
*/
$conf = "config.cgi";
$url = @$_SERVER['REQUEST_URI'];
@list( ,$arg1,$arg2,$arg3,$arg4,$mode,$arg7) = explode("/",$url);

/*
	Instance
*/
$list = new lists();
$com = new common();
$get = new get();
$parser = new parser();
$smarty = new Smarty;
$cacheOptions = array (
	'cacheDir' => $com->load($conf,"cachedir")."/",
	'lifeTime' => $com->load($conf,"cachelife")
);
$objCache = new Cache_Lite($cacheOptions);
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
$ver = $com->version($conf);
global $svs;
$svs = $list->svlist($com->load($conf,"json"));
//echo("arg1=>".$arg1." | arg2=>".$arg2." | arg3=>".$arg3." | arg4=>".$arg4."  |mode=>".$mode);

/*
	Router
	/					:TOP
	/x.p/				:カテゴリ
	/test/-/XXX			:スレッド一覧
	/test/-/XXX/YYY		:スレッド表示
	/test/-/XXX/YYY/mode:モード
	$arg1				:基本的になし(カテゴリ別(x.p)またはスレッド関係(test)
	$arg2				:基本的に -
	$arg3				:板フォルダ名
	$arg4				:- /31 (スレ一覧用)
	$arg5				:スレッドkey
	$mode				:write or レス番号
	$arg7				:PC用?
*/
switch($arg1){
	case'':
	default://TOP
		$views="index.tmpl.html";
		$smarty->assign("TITLE",Moji("c.2ch カテゴリ一覧"),true);
		$smarty->assign("MESSG",Moji("カテゴリ一覧"),true);
		$category = $list->_catlist($com->load($conf,"bbsmenu"));
		//var_dump($category);
		$smarty->assign("body",$category);
		break;
	case'pc':
		$views="pc.tmpl.html";
		//echo("arg1=>".$arg1." | arg2=>".$arg2." | arg3=>".$arg3." | arg4=>".$arg4."  |mode=>".$mode);
		/* menu 用 */
		$category = $list->_pc_ch2_catlist($com->load($conf,"bbsmenu"));
		$pluscategory = $list->_pc_plus_catlist($com->load($conf,"bbsmenu"));
		$smarty->assign("site","http://c.2ch.sc",true);
		$smarty->assign("menu",$category);
		$smarty->assign("plusmenu",$pluscategory);
		switch($arg2){
			case '':
			default:
				break;
			case 'test':
				switch($arg3){
					default:
					case '':
						break;
					case '-':
						switch($arg4){
							default://スレッド一覧
							case '':
							$sv = $list->svname($arg4,$com->load($conf,"json"));
							$bbsname = $list->bbsname($arg4,$com->load($conf,"json"));
							$subject = $get->getsubject($sv,$arg4);
							switch($mode){
								case '':
								default:
								if($mode==""){
									$sublist = $parser->subject_l($subject,$arg4,"29","");
								}else{
									$sublist = $parser->subject_l($subject,$arg4,"29",$mode);
								}
								$next=$mode+30;
								if($mode>30){
									$goback = $mode-30;
								}else{
									$goback = $mode-29;
								}
								$smarty->assign("type","boad",true);
								$smarty->assign("subject", $sublist);
								$smarty->assign("snext",$next);
								$smarty->assign("sgoback",$goback);
								break;
							}
					case strlen($mode)>9:
						/*
						switch ($arg7){
							case is_null($arg7):
							case 'i':
							default:
								$sv = $list->svname($arg4,$com->load($conf,"json"));
								$cacheId = $mode;
								if ($cache = $objCache->get($cacheId)) {
									$dat = $cache;
								} else {
									$dat = $get->getdat($sv,$arg4,$mode);
									$objCache->save($dat, $cacheId);
								}
								$title = $parser->threadtitle($dat);
								$smarty->assign("TITLE",$title);
								$smarty->assign("MESSG",Moji("スレッド表示"));
								$smarty->assign("BBS",$title);
								$counts = $parser->newcount($dat);
								if($mode==""){
									$test = $parser->dat_l($dat,"10","");
								}else if($mode=="i"){
									if($counts<=10){
										$test = $parser->dat_l($dat,"10","");
									}else{
										$test = $parser->dat_l($dat,"10",$arg7);
									}
								}else{
									$test = $parser->dat_l($dat,"10",$arg7);
								}
								$smarty->assign("body", $test);
								if($mode=="i"){
									$next=$parser->newcount($dat);
								}else if($mode=="n"){
									$next=$parser->newcount($dat);
								}else{
									$mode=ltrim($mode,"-");
									$next=$mode+10;
								}
								$next2 = ltrim($next,"-");
								$smarty->assign("LATEST","n");
								$smarty->assign("FIRST","0-");
								$smarty->assign("ITA","../");
								if($next2>11){
									$goback = $next2-20;
								}else{
									$goback = $next2;
								}
								if($mode=="i"){
									$goback = $next2-9;
								}
								if($goback<=0){$goback=1;}
								if($next2<=0){
									$smarty->assign("GOBACK","10");
								}else{
									$smarty->assign("GOBACK",$goback);
								}
								$smarty->assign("NEXTU",$next2);
								$smarty->assign("header","TITLE","c2ch");
								$smarty->assign("messg","MESSG","書き込み");
								$sv = $list->svname($arg4,$com->load($conf,"json"));
								$dat = $get->getdat($sv,$arg4,$mode);
								$title = $parser->threadtitle($dat);
								$smarty->assign("BBS",$title);
								$smarty->assign("SVNAME", $sv);
								$smarty->assign("BBSNAME", $arg4);
								$smarty->assign("THREDKEY", $mode);
								$smarty->assign("TIME","1");
								$smarty->assign("type","thread",true);
								break;
						}*/
						break;
					}
					break;
				}
				break;
			case is_numeric(cate($arg2))://カテゴリ別
				$categorysumm = $list->_catsummary($com->load($conf,"bbsmenu"),$com->load($conf,"json"),$arg2);
				$categorysumm_s = mb_convert_encoding($categorysumm,"SJIS","UTF-8,EUC-JP,auto");
				$smarty->assign("type","category",true);
				$smarty->assign("cate",$categorysumm);
				break;
		}
		break;
	case'test':
		switch($arg3){
			default://スレッド一覧
				$sv = $list->svname($arg3,$com->load($conf,"json"));
				$bbsname = $list->bbsname($arg3,$com->load($conf,"json"));
				$subject = $get->getsubject($sv,$arg3);
				switch($arg4){
					case '':
					default:
						$views="board.tmpl.html";
						$smarty->assign("TITLE",$bbsname,true);
						$smarty->assign("BBSNAME",$bbsname,true);
						$smarty->assign("BBSURL",$sv."/".$arg3,true);
						$smarty->assign("MESSG",Moji("スレッド一覧"),true);
						if($arg4==""){
							$test = $parser->subject_l($subject,$arg3,"29","");
						}else{
							$test = $parser->subject_l($subject,$arg3,"29",$arg4);
						}
						$next=$arg4+30;
						if($arg4>30){
							$goback = $arg4-30;
						}else{
							$goback = $arg4-29;
						}
						$smarty->assign("body", $test);
						break;
					case 'w':
						$views="create.tmpl.html";
						$smarty->assign("TITLE",$bbsname);
						$smarty->assign("BBSNAME",$bbsname);
						$smarty->assign("BBSURL",$sv."/".$arg3);
						$smarty->assign("MESSG",Moji("スレ立て"));
						$smarty->assign("ITA",$bbsname);
						$smarty->assign("SVNAME", $sv);
						$smarty->assign("BBSNAME", $arg3);
						$smarty->assign("TIME","1");
						$next=$arg5+10;
						$goback = $arg5-10;
						break;
				}
				if($goback<=0){
					$smarty->assign("GOBACK","../../../");
				}else{
					$smarty->assign("GOBACK",$goback);
				}
				$smarty->assign("NEXTU",$next);
				break;
			//case'-'://スレッド表示
			case strlen($arg4)>9:
				
				switch ($mode){
					case is_null($mode):
					case 'i':
					default:
						$views="thread.tmpl.html";
						$sv = $list->svname($arg3,$com->load($conf,"json"));
						$cacheId = $arg4;
						if ($cache = $objCache->get($cacheId)) {
								$dat = $cache;
						} else {
								$dat = $get->getdat($sv,$arg3,$arg4);
								$objCache->save($dat, $cacheId);
						}
						$title = $parser->threadtitle($dat);
						$smarty->assign("TITLE",$title);
						$smarty->assign("MESSG",Moji("スレッド表示"));
						$smarty->assign("BBS",$title);
						$counts = $parser->newcount($dat);
						if($mode==""){
							$test = $parser->dat_l($dat,"10","");
						}else if($mode=="i"){
							if($counts<=10){
								$test = $parser->dat_l($dat,"10","");
							}else{
								$test = $parser->dat_l($dat,"10",$mode);
							}
						}else{
							$test = $parser->dat_l($dat,"10",$mode);
						}
						$smarty->assign("body", $test);
						if($mode=="i"){
							$next=$parser->newcount($dat);
						}else if($mode=="n"){
							$next=$parser->newcount($dat);
						}else{
							$mode=ltrim($mode,"-");
							$next=$mode+10;
						}
						$next2 = ltrim($next,"-");
						$smarty->assign("LATEST","n");
						$smarty->assign("FIRST","0-");
						$smarty->assign("ITA","../");
						if($next2>11){
							$goback = $next2-20;
						}else{
							$goback = $next2;
						}
						if($mode=="i"){
							$goback = $next2-9;
						}
						if($goback<=0){$goback=1;}
						if($next2<=0){
							$smarty->assign("GOBACK","10");
						}else{
							$smarty->assign("GOBACK",$goback);
						}
						$smarty->assign("NEXTU",$next2);
						//}
						break;
					case 'w':
						$views="res.tmpl.html";
						$smarty->assign("header","TITLE","c2ch");
						$smarty->assign("messg","MESSG","書き込み");
						$sv = $list->svname($arg3,$com->load($conf,"json"));
						$dat = $get->getdat($sv,$arg3,$arg4);
						$title = $parser->threadtitle($dat);
						$smarty->assign("BBS",$title);
						$smarty->assign("SVNAME", $sv);
						$smarty->assign("BBSNAME", $arg3);
						$smarty->assign("THREDKEY", $arg4);
						$smarty->assign("TIME","1");
						break;
				}
				break;
			case 'guid':
				switch($arg4){
					default:
						$views="info.tmpl.html";
						$bbsname = Moji("お知らせ");
						$sv = "http://c.2ch.sc";
						$smarty->assign("TITLE",$bbsname,true);
						$smarty->assign("BBSNAME",$bbsname,true);
						$smarty->assign("BBSURL",$sv."/".$arg3,true);
						$smarty->assign("MESSG",Moji("スレッド一覧"),true);
						$smarty->assign("TEST",Moji("<a href=\"/0.p\">一覧に戻る</a>"),true);
						break;
					case is_numeric($arg4):
						$views="infothread.tmpl.html";
						$cacheId = $arg4;
						if ($cache = $objCache->get($cacheId)) {
								$dat = $cache;
						} else {
								$dat = $get->getlocal($arg3,$arg4);
								$objCache->save($dat, $cacheId);
						}
						$title = $parser->threadtitle($dat);
						$smarty->assign("TITLE",$title);
						$smarty->assign("MESSG",Moji("スレッド表示"));
						$smarty->assign("BBS",$title);
						$counts = $parser->newcount($dat);
						if($mode==""){
							$test = $parser->dat_l($dat,"10","");
						}else if($mode=="i"){
							if($counts<=10){
								$test = $parser->dat_l($dat,"10","");
							}else{
								$test = $parser->dat_l($dat,"10",$mode);
							}
						}else{
							$test = $parser->dat_l($dat,"10",$mode);
						}
						$smarty->assign("body", $test);
						if($mode=="i"){
							$next=$parser->newcount($dat);
						}else if($mode=="n"){
							$next=$parser->newcount($dat);
						}else{
							$mode=ltrim($mode,"-");
							$next=$mode+10;
						}
						$next2 = ltrim($next,"-");
						$smarty->assign("FIRST","0-");
						$smarty->assign("ITA","../../../0.p/");
						if($next2>11){
							$goback = $next2-20;
						}else{
							$goback = $next2;
						}
						if($mode=="i"){
							$goback = $next2-9;
						}
						if($goback<=0){$goback=1;}
						if($next2<=0){
							$smarty->assign("GOBACK","10");
						}else{
							$smarty->assign("GOBACK",$goback);
						}
						$smarty->assign("NEXTU",$next2);
						//}
						break;
				}
			break;
		}
		break;
	case is_numeric(cate($arg1))://カテゴリ別
		$views="category.tmpl.html";
		$smarty->assign("TITLE",Moji("カテゴリ別"),true);
		$smarty->assign("MESSG",Moji("カテゴリ別"),true);
		$categorysumm = $list->_catsummary($com->load($conf,"bbsmenu"),$com->load($conf,"json"),$arg1);
		$categorysumm_s = mb_convert_encoding($categorysumm,"SJIS","UTF-8,EUC-JP,auto");
		$smarty->assign("body",$categorysumm);
		break;
	//case debug:
	//	var_dump($svs['0']);
	//	break;
}
// display
$smarty->display($views);
function cate($url)
{
	$url = trim($url,"/");
	return trim($url,".p/");
}

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
// test
/*
$ua = file_get_contents("test.dat");
$ua .=  $_SERVER['HTTP_USER_AGENT']."\r\n";
file_put_contents("test.dat", $ua);*/

//$time_end = microtime(true);
//$time = $time_end - $time_start;
//echo "<!-- $time -->";
