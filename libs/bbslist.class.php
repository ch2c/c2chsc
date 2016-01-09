<?php
/**
	[bbslist.class.php]

	Copyright (c) [2014] [2ch.sc]

	This software is released under the MIT License.

	http://opensource.org/licenses/mit-license.php

*/
class lists{
	function bbslist_k($url){
		$json_local = "/path/to/cache/json.dat";
		if(file_exists($json_local)){
			$url = $json_local;
		}else{
			$json_str = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
			file_put_contents($json_local, $json_str);
			$url = $json_local;
		}
		$json = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		$obj = json_decode($json, true);
		if ($obj === NULL) {return;}
		foreach($obj as $key => $value) {
			//À‹µch,hayabusa5.2ch.sc/liveanime,ƒAƒjƒ“ÁBÀ‹µ
			$ret[$key] = array($value["category"],$value["name"],$value["server"],$value["bbs"]);
		}
		return $ret;
	}
	function _bbslist($url){
		$json = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		$obj = json_decode($json, true);
		if ($obj === NULL) {return;}
		$interenc = mb_internal_encoding();
		foreach($obj as $key => $value) {
			$ret[$key] = array($value["category"],$value["name"],$value["bbs"]);
		}
		return $ret;
	}
	function bbslist_h($url){
		$html = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		preg_match_all("/<A HREF=http:\/\/(.+?)\.2ch\.sc\/(.+?)\/>(.+?)<\/A>/",$html,$ret,PREG_PATTERN_ORDER);
		$ret2 = mb_convert_encoding($ret, 'SJIS-WIN', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		return $ret2;
	}
	function catlist($arg1){
		$html = mb_convert_encoding(file_get_contents($arg1), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		preg_match_all("/<BR><BR><B>(.+?)<\/B><BR>/",$html,$ret,PREG_PATTERN_ORDER);
		return $ret;
	}
	function _catlist($url){
		$ret = $this->catlist($url);
		$ret2[0]['name']=mb_convert_encoding("c.2ch.sc‚©‚ç‚Ì‚¨’m‚ç‚¹“™","utf-8","sjis-win");
		$ret2[0]['url'] = "0.p/";
		for($i=2;$i<count($ret[1])+1;$i++){
			if($ret[1][$i-1]==mb_convert_encoding("‚¨‚Æ‚È‚ÌŠÔ","utf-8","sjis-win")){
				$ret2[$i-1]['name'] = mb_convert_encoding("‚¨‚Æ‚È‚ÌŠÔ(¦2chŠO ‚P‚WÎ–¢–‚Í—˜—p‚Å‚«‚Ü‚¹‚ñB)","utf-8","sjis-win");
				$ret2[$i-1]['url'] = "http://xpic.sc/b/i/";
			}else if($ret[1][$i-1]==mb_convert_encoding("ƒc[ƒ‹—Ş","utf-8","sjis-win")){
			}else if($ret[1][$i-1]==mb_convert_encoding("‚Ü‚¿‚a‚a‚r","utf-8","sjis-win")){
				$ret2[$i-1]['name'] = mb_convert_encoding("‚Ü‚¿‚a‚a‚r(¦2chŠO)","utf-8","sjis-win");
				$ret2[$i-1]['url'] = "http://www.machi.to/i.html";
			}else{
				$ret2[$i-1]['name'] = $ret[1][$i-1];
				$j=$i-1;
				$ret2[$i-1]['url'] = $j.".p/";
			}
		}
		unset($ret);
		return $ret2;
	}
	function _pc_plus_catlist($url){
		$ret = $this->catlist($url);
		for($i=2;$i<count($ret[1])+1;$i++){
			if($ret[1][$i-1]==mb_convert_encoding("‚¨‚Æ‚È‚ÌŠÔ","utf-8","sjis-win")){
				$ret2['0']['name'] = mb_convert_encoding("‚¨‚Æ‚È‚ÌŠÔ(¦2chŠO ‚P‚WÎ–¢–‚Í—˜—p‚Å‚«‚Ü‚¹‚ñB)","utf-8","sjis-win");
				$ret2['0']['url'] = "http://xpic.sc/b/";
			}else if($ret[1][$i-1]==mb_convert_encoding("ƒc[ƒ‹—Ş","utf-8","sjis-win")){
			}else if($ret[1][$i-1]==mb_convert_encoding("‚Ü‚¿‚a‚a‚r","utf-8","sjis-win")){
				$ret2['1']['name'] = mb_convert_encoding("‚Ü‚¿‚a‚a‚r(¦2chŠO)","utf-8","sjis-win");
				$ret2['1']['url'] = "http://www.machi.to/";
			}else{
			}
		}
		unset($ret);
		return $ret2;
	}
	function _pc_ch2_catlist($url){
		$ret = $this->catlist($url);
		$ret2[0]['name']=mb_convert_encoding("c.2ch.sc‚©‚ç‚Ì‚¨’m‚ç‚¹“™","utf-8","sjis-win");
		$ret2[0]['url'] = "0.p/";
		for($i=2;$i<count($ret[1])+1;$i++){
			if($ret[1][$i-1]==mb_convert_encoding("‚¨‚Æ‚È‚ÌŠÔ","utf-8","sjis-win")){
			}else if($ret[1][$i-1]==mb_convert_encoding("ƒc[ƒ‹—Ş","utf-8","sjis-win")){
			}else if($ret[1][$i-1]==mb_convert_encoding("‚Ü‚¿‚a‚a‚r","utf-8","sjis-win")){
			}else{
				$ret2[$i-1]['name'] = $ret[1][$i-1];
				$j=$i-1;
				$ret2[$i-1]['url'] = $j.".p/";
			}
		}
		unset($ret);
		return $ret2;
	}

	function _catsummary($url,$jurl,$num){//num=1.p
		$ret = $this->_catlist($url);//ƒJƒeƒSƒŠƒŠƒXƒg
		$ret2 = $this->_bbslist($jurl);
		if($num=="0.p"){
			$list = file("guid/list.txt");
			for($i=0;$i<count($list);$i++){
				@list($temp_name,$temp_dat) = explode("<>",$list[$i]);
				$ret3[$i]['name']=mb_convert_encoding($temp_name,"utf-8","sjis-win");
				$ret3[$i]['url']=$temp_dat;
			}
		}
		for($i=0;$i<count($ret);$i++){
			if($ret[$i]['url']==$num."/"){
				$catename=$ret[$i]['name'];//be
			}
		}
		$k=0;
		for($j=0;$j<count($ret2);$j++){
			if($ret2[$j][0]==$catename){
				$ret3[$k]['name']=$ret2[$j][1];
				$ret3[$k]['url']=$ret2[$j][2]."/";
				$k++;
			}
		}
		
		return $ret3;
	}
	function svname($folder,$jurl){
		$raw = $this->bbslist_k($jurl);
		for($j=0;$j<count($raw);$j++){
			if($folder==$raw[$j][3]){
				$ret = $raw[$j][2];
			}
		}
		return $ret;
	}
	function bbsname($folder,$jurl){
		$raw = $this->bbslist_k($jurl);
		for($j=0;$j<count($raw);$j++){
			if($folder==$raw[$j][3]){
				$ret = $raw[$j][1];
			}
		}
		return $ret;
	}
	function svlist($jurl){
		$json_local = "/path/to/cache/json.dat";
		if(file_exists($json_local)){
			$jurl = $json_local;
		}else{
			$json_str = mb_convert_encoding(file_get_contents($jurl), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
			file_put_contents($json_local, $json_str);
			$jurl = $json_local;
		}
		$json = mb_convert_encoding(file_get_contents($jurl), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		$obj = json_decode($json, true);
		if ($obj === NULL) {return;}
		foreach($obj as $key => $value) {
			$temp[$key] = $value["server"];
		}
		//d•¡‚ğæ‚èœ‚­
		$temp = array_unique($temp);
		//ƒCƒ“ƒfƒbƒNƒX‚ğU‚è’¼‚·
		$ret = array_values($temp);
		
		return $ret;
	}
}