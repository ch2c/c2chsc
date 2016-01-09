<?php
/**
	[parser.class.php]

	Copyright (c) [2014] [2ch.sc]

	This software is released under the MIT License.

	http://opensource.org/licenses/mit-license.php

*/
class parser{
	function subject($txt,$folder){//parse a subject list
		foreach(explode("\n",$txt) as $key => $data){
			@list($arg1,$arg2) = explode("<>",$data);
				if($arg2==""){}//ゴミをとる
				else{
					@$list[$key]['no'] .=$key+1;
					@$list[$key]['folder'] .= $folder;
					@$list[$key]['url'] .= trim($arg1,".dat");
					@$list[$key]['name'] .= $arg2;
				}
		}
		return $list;
	}
	function subject_l($txt,$folder,$end,$start){//parse a subject list
		$list = $this->subject($txt,$folder);
		if($start==""){
			for($i=0;$i<$end;$i++){
				$ret[$i]['no'] = $i+1;
				$ret[$i]['folder'] = $list[$i]['folder'];
				$ret[$i]['url'] = $list[$i]['url']."/i";
				$ret[$i]['name'] = $list[$i]['name'];
			}
		}else{
			$fin = $end+$start;
			$j=0;
			for($i=$start;$i<=$fin;$i++){
				$endloc = $start+$j;
				if($endloc==$this->newcount($txt)){
					$endloc = $endloc -1 ;
				}
				if($endloc>=$this->newcount($txt)){
					$endloc = $this->newcount($txt);
				}
				if(@$list[$endloc]['url']==""){}
				else{
					$ret[$j]['no'] = $start+$j;
					$ret[$j]['folder'] = $list[$start+$j-1]['folder'];
					$ret[$j]['url'] = $list[$start+$j-1]['url']."/i";
					$ret[$j]['name'] = $list[$start+$j-1]['name'];
					$j++;
				}
			}
		}
		unset($list);
		return $ret;
	}
	function dat($txt){// parse a dat
		foreach(explode("\n",$txt) as $key => $data){
			@list($name,$mail,$day,$hon) = explode("<>",$data);
			@$name_ss = preg_replace("/\s*?<\/?b>\s*/i","",$name);
			@$name_s = htmlspecialchars($name_ss,ENT_QUOTES,"UTF-8");
			@$mail_s = htmlspecialchars($mail,ENT_QUOTES,"UTF-8");
			@$day_s = htmlspecialchars($day,ENT_QUOTES,"UTF-8");
			if($name_s==""&&$day==""&&$mail==""&&$hon==""){}
			else{
				@$list[$key]['no'] .= $key+1;
				@$list[$key]['mail'] .= $mail_s;
				@$list[$key]['name'] .= $name_s;
				@$list[$key]['day'] .= $day_s;
				@$list[$key]['hon'] .= $this->links($hon);
			}
		}
		unset($txt);
		return $list;
	}
	function dat2($txt){// parse a dat
		foreach(explode("\n",$txt) as $key => $data){
			@list($name,$mail,$day,$hon) = explode("<>",$data);
			@$name_ss = preg_replace("/\s*?<\/?b>\s*/i","",$name);
			@$name_s = htmlspecialchars($name_ss,ENT_QUOTES,"UTF-8");
			@$mail_s = htmlspecialchars($mail,ENT_QUOTES,"UTF-8");
			@$day_s = htmlspecialchars($day,ENT_QUOTES,"UTF-8");
			if($name_s==""&&$day==""&&$mail==""&&$hon==""){}
			else{
				@$list[$key]['no'] .= $key+1;
				@$list[$key]['mail'] .= $mail_s;
				@$list[$key]['name'] .= $name_s;
				@$list[$key]['day'] .= $day_s;
				@$list[$key]['hon'] .= $this->links2($hon);
			}
		}
		unset($txt);
		return $list;
	}
	function dat_l($txt,$end,$start){// parse a dat
		$list = $this->dat($txt);
		if($start==""){ $start="i"; }// parameterなしのときiと同じ動き
		if($start==""){
			for($i=0;$i<$end;$i++){
				if($list[$i]['day']==""){}
				else{
					$ret[$i]['no'] .= $i+1;
					@$ret[$i]['mail'] .= $list[$i]['mail'];
					@$ret[$i]['name'] .= $list[$i]['name'];
					@$ret[$i]['day'] .= $list[$i]['day'];
					@$ret[$i]['hon'] .= $list[$i]['hon'];
				}
			}
		}else if($start=="n"){//nとか
			$list = $this->dat($txt);
			$end = count($list);
			$new = $end - 10;
			$k = 0;
			for($i=$new;$i<$end;$i++){
				if($list[$k]['day']==""){}
				else{
					@$ret[$k]['no'] .= $i+1;
					@$ret[$k]['mail'] .= $list[$i]['mail'];
					@$ret[$k]['name'] .= $list[$i]['name'];
					@$ret[$k]['day'] .= $list[$i]['day'];
					@$ret[$k]['hon'] .= $list[$i]['hon'];
					$k++;
				}
			}
		}else if($start=="i"){//iとか
			$list = $this->dat($txt);
			$end = count($list);
			$new = $end - 9;
			if($new<=0){
				$new = 1;
			}
			$j = 0;
			$k = 1;
			@$ret[$j]['no'] .= $j+1;
			@$ret[$j]['mail'] .= $list[$j]['mail'];
			@$ret[$j]['name'] .= $list[$j]['name'];
			@$ret[$j]['day'] .= $list[$j]['day'];
			@$ret[$j]['hon'] .= $list[$j]['hon'];
			for($i=$new;$i<$end;$i++){
				@$ret[$k]['no'] .= $i+1;
				@$ret[$k]['mail'] .= $list[$i]['mail'];
				@$ret[$k]['name'] .= $list[$i]['name'];
				@$ret[$k]['day'] .= $list[$i]['day'];
				@$ret[$k]['hon'] .= $list[$i]['hon'];
				$k++;
			}
		}else if($start<0){//-32とか
			$ichi = ltrim($start,"-");
			if(strlen($ichi)<=1){
				$i = 0;
				$loc = $i;
				$fin = $ichi-1;
			}else{
				$loc = ltrim($ichi-$end,"-");
				$i = $loc;
				$fin = $end+$loc-1;
			}

			$k =0;
			$j =1;
			for($i;$i<=$fin;$i++){
				@$ret[$k]['no'] = $loc+$k+$j;
				@$ret[$k]['mail'] .= $list[$loc+$k]['mail'];
				@$ret[$k]['name'] .= $list[$loc+$k]['name'];
				@$ret[$k]['day'] .= $list[$loc+$k]['day'];
				@$ret[$k]['hon'] .= $list[$loc+$k]['hon'];
				$k++;
			}
		}else if($start=="-"){//- つまり-なのに指定なし 例外処理
			@$ret[0]['no'] = 1;
			@$ret[0]['mail'] = $list[0]['mail'];
			@$ret[0]['name'] = $list[0]['name'];
			@$ret[0]['day'] = $list[0]['day'];
			@$ret[0]['hon'] = $list[0]['hon'];
		}else if(is_numeric($start)){//10
			@$ret[0]['no'] = $start;
			@$ret[0]['mail'] = $list[$start-1]['mail'];
			@$ret[0]['name'] = $list[$start-1]['name'];
			@$ret[0]['day'] = $list[$start-1]['day'];
			@$ret[0]['hon'] = $list[$start-1]['hon'];
		}else if(is_numeric(trim($start,"-"))){//10-
			$loc = trim($start,"-");
			$fin = $end+$loc;//20
			$k = 0;
			if($loc==$this->newcount($txt)){
				$last = $this->newcount($txt);
				@$ret[$k]['no'] = $last;//262
				@$ret[$k]['mail'] .= $list[$last-1]['mail'];
				@$ret[$k]['name'] .= $list[$last-1]['name'];
				@$ret[$k]['day'] .= $list[$last-1]['day'];
				@$ret[$k]['hon'] .= $list[$last-1]['hon'];
			}else{
				for($i=$loc;$i<=$fin;$i++){
					@$ret[$k]['no'] = $i+1;//10
					@$ret[$k]['mail'] .= $list[$i]['mail'];
					@$ret[$k]['name'] .= $list[$i]['name'];
					@$ret[$k]['day'] .= $list[$i]['day'];
					@$ret[$k]['hon'] .= $list[$i]['hon'];
					$k++;
				}
			}
		}else{//*-**とか
			@list($start_s,$end_s) = explode("-",$start);
			$fin = $end_s-$start_s;
			$k =0;
			for($i=$end_s-1;$k<=$fin;$i++){
				@$ret[$k]['no'] = $start_s+$k;
				@$ret[$k]['mail'] .= $list[$start_s+$k-1]['mail'];
				@$ret[$k]['name'] .= $list[$start_s+$k-1]['name'];
				@$ret[$k]['day'] .= $list[$start_s+$k-1]['day'];
				@$ret[$k]['hon'] .= $list[$start_s+$k-1]['hon'];
				$k++;
			}
		}
		unset($txt);
		return $ret;
	}
	function dat_l2($txt,$end,$start){// parse a dat
		$list = $this->dat2($txt);
		if($start==""){ $start="i"; }// parameterなしのときiと同じ動き
		if($start==""){
			for($i=0;$i<$end;$i++){
				if($list[$i]['day']==""){}
				else{
					$ret[$i]['no'] .= $i+1;
					@$ret[$i]['mail'] .= $list[$i]['mail'];
					@$ret[$i]['name'] .= $list[$i]['name'];
					@$ret[$i]['day'] .= $list[$i]['day'];
					@$ret[$i]['hon'] .= $list[$i]['hon'];
				}
			}
		}else if($start=="n"){//nとか
			$list = $this->dat($txt);
			$end = count($list);
			$new = $end - 10;
			$k = 0;
			for($i=$new;$i<$end;$i++){
				if($list[$k]['day']==""){}
				else{
					@$ret[$k]['no'] .= $i+1;
					@$ret[$k]['mail'] .= $list[$i]['mail'];
					@$ret[$k]['name'] .= $list[$i]['name'];
					@$ret[$k]['day'] .= $list[$i]['day'];
					@$ret[$k]['hon'] .= $list[$i]['hon'];
					$k++;
				}
			}
		}else if($start=="i"){//iとか
			$list = $this->dat($txt);
			$end = count($list);
			$new = $end - 9;
			if($new<=0){
				$new = 1;
			}
			$j = 0;
			$k = 1;
			@$ret[$j]['no'] .= $j+1;
			@$ret[$j]['mail'] .= $list[$j]['mail'];
			@$ret[$j]['name'] .= $list[$j]['name'];
			@$ret[$j]['day'] .= $list[$j]['day'];
			@$ret[$j]['hon'] .= $list[$j]['hon'];
			for($i=$new;$i<$end;$i++){
				@$ret[$k]['no'] .= $i+1;
				@$ret[$k]['mail'] .= $list[$i]['mail'];
				@$ret[$k]['name'] .= $list[$i]['name'];
				@$ret[$k]['day'] .= $list[$i]['day'];
				@$ret[$k]['hon'] .= $list[$i]['hon'];
				$k++;
			}
		}else if($start<0){//-32とか
			$ichi = ltrim($start,"-");
			if(strlen($ichi)<=1){
				$i = 0;
				$loc = $i;
				$fin = $ichi-1;
			}else{
				$loc = ltrim($ichi-$end,"-");
				$i = $loc;
				$fin = $end+$loc-1;
			}

			$k =0;
			$j =1;
			for($i;$i<=$fin;$i++){
				@$ret[$k]['no'] = $loc+$k+$j;
				@$ret[$k]['mail'] .= $list[$loc+$k]['mail'];
				@$ret[$k]['name'] .= $list[$loc+$k]['name'];
				@$ret[$k]['day'] .= $list[$loc+$k]['day'];
				@$ret[$k]['hon'] .= $list[$loc+$k]['hon'];
				$k++;
			}
		}else if($start=="-"){//- つまり-なのに指定なし 例外処理
			@$ret[0]['no'] = 1;
			@$ret[0]['mail'] = $list[0]['mail'];
			@$ret[0]['name'] = $list[0]['name'];
			@$ret[0]['day'] = $list[0]['day'];
			@$ret[0]['hon'] = $list[0]['hon'];
		}else if(is_numeric($start)){//10
			@$ret[0]['no'] = $start;
			@$ret[0]['mail'] = $list[$start-1]['mail'];
			@$ret[0]['name'] = $list[$start-1]['name'];
			@$ret[0]['day'] = $list[$start-1]['day'];
			@$ret[0]['hon'] = $list[$start-1]['hon'];
		}else if(is_numeric(trim($start,"-"))){//10-
			$loc = trim($start,"-");
			$fin = $end+$loc;//20
			$k = 0;
			if($loc==$this->newcount($txt)){
				$last = $this->newcount($txt);
				@$ret[$k]['no'] = $last;//262
				@$ret[$k]['mail'] .= $list[$last-1]['mail'];
				@$ret[$k]['name'] .= $list[$last-1]['name'];
				@$ret[$k]['day'] .= $list[$last-1]['day'];
				@$ret[$k]['hon'] .= $list[$last-1]['hon'];
			}else{
				for($i=$loc;$i<=$fin;$i++){
					@$ret[$k]['no'] = $i+1;//10
					@$ret[$k]['mail'] .= $list[$i]['mail'];
					@$ret[$k]['name'] .= $list[$i]['name'];
					@$ret[$k]['day'] .= $list[$i]['day'];
					@$ret[$k]['hon'] .= $list[$i]['hon'];
					$k++;
				}
			}
		}else{//*-**とか
			@list($start_s,$end_s) = explode("-",$start);
			$fin = $end_s-$start_s;
			$k =0;
			for($i=$end_s-1;$k<=$fin;$i++){
				@$ret[$k]['no'] = $start_s+$k;
				@$ret[$k]['mail'] .= $list[$start_s+$k-1]['mail'];
				@$ret[$k]['name'] .= $list[$start_s+$k-1]['name'];
				@$ret[$k]['day'] .= $list[$start_s+$k-1]['day'];
				@$ret[$k]['hon'] .= $list[$start_s+$k-1]['hon'];
				$k++;
			}
		}
		unset($txt);
		return $ret;
	}
	function threadtitle($txt){
		foreach(explode("\n",$txt) as $key => $data){
			@list($name,$mail,$day,$hon,$title) = explode("<>",$data);
			@$titles[$key] .= $title;
			
		}
		if($titles['0']==""){}
		else if($title=="あぼーん"){}
		else{
			$ret = $titles['0'];
		}
		unset($txt);
		return $ret;
	}
	function imenu($txt){
		// test for replace jump.2ch.net/? to c.2ch.sc
		//$pat1 = '@(?:https?|ftp):\/\/([j]+[u]+[m]+[p]\.)+2ch\.net/+[?](.+?)@';
		//$aft = 'http://c.2ch.sc/ime.nu.php?val=h';
		//$ret2 = preg_replace($pat1,$aft,$txt);
			$pat = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
			$after = '<a href="http://c.2ch.sc/ime.nu.php?val=\1">\1</a>';
			$ret = preg_replace($pat,$after,$txt);
		//}else{
		//	$ret = $txt;
		//}
		return $ret;
	}
	function anker($txt){
		//$txt = preg_replace('@&gt;&gt;@',"<a href".'\1'."</a>",$txt);
		//var_dump($txt);
		preg_match('@<a(?:>| [^>]*?>)(.*?)</a>@',$txt,$matches, PREG_OFFSET_CAPTURE);
		//print_r($matches[0][0]);
		foreach ($matches[0] as $unker){
			$out = $unker;
			if (preg_match("@<a(?:>| [^>]*?>)(.*?)</a>@", $unker)) {
				if(str_replace("../test/read.cgi", "/test/-", $out) ==""){
					$out2 = $out;
				}else{
					$out2 = str_replace("../test/read.cgi", "/test/-", $out);
				}
				if(str_replace("/test/read.cgi", "/test/-", $out2) ==""){
					$out2 = $out2;
				}else{
					$out2 = str_replace("/test/read.cgi", "/test/-", $out2);
				}
				if(str_replace($out2,"",$out)==""){
					$out2 = $out;
				}else{
					$out2 = str_replace($out2,"",$out);
				}
				$ret .= $out2;
			}else{
				$ret .= $out;
			}
		}
		return $txt;
	}
	function directlink($txt){
		//if(preg_match("@(?:https?|ftp):\/\/c\.2ch\.sc\/@", $txt)){
			$pat = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u';
			$after = '<a href="\1">\1</a>';
			$ret = preg_replace($pat,$after,$txt);
		//}else{
		//	$ret = $txt;
		//}
		return $ret;
	}

	function links2($txt){
		$ret = null;
		//<br>ごとに区切る
		//foreach(explode("<br>",$txt) as $mat){
		//	foreach(explode("\n",$mat) as $mat2){
		//		$ret  .= $this->alllink($mat2);
		//		$ret .="\n";
		//	}
		//	$ret .="<br>";
		//}
		return $txt;
	}
	function links($txt){
		$ret = null;
		//<br>ごとに区切る
		foreach(explode("<br>",$txt) as $mat){
			//$mat=preg_replace('/&gt;&gt;([0-9]+)/','<a href="\\1">\\0</a>',$mat);
			//var_dump($mat);
			//$mat = htmlspecialchars_decode($mat);
			//$mat = $str2;
			if (preg_match("@<a(?:>| [^>]*?>)(.*?)</a>@", $mat)) {
			//アンカー
				$ret .= $this->anker($mat)."<br>";
			}else if(preg_match('/&gt;&gt;([0-9]+)/', $mat)){
				$ret .= preg_replace('/&gt;&gt;([0-9]+)/','<a href="\\1">\\0</a>',$mat);
			}else if(preg_match("@(?:https?|ftp):\/\/([A-Za-z0-9][A-Za-z0-9\-]{0,61}[A-Za-z0-9]\.)+machi\.to@",$mat)){
			//machi bbs
				$ret .= $this ->directlink($mat)."<br>";
			}else if(preg_match("@(?:https?|ftp):\/\/([A-Za-z0-9]{0,61}\.)+xpic\.sc@",$mat)){
			//machi bbs
				$ret .= $this ->directlink($mat)."<br>";
			}else if(preg_match("@(?:https?|ftp):\/\/c\.2ch\.sc\/@", $mat)){
			//c2ch内部
				$ret .= $this ->directlink($mat)."<br>";
			}else if(preg_match("@(?:https?|ftp):\/\/([A-Za-z0-9][A-Za-z0-9\-]{0,61}[A-Za-z0-9]\.)+2ch\.sc+\/test\/@",$mat)){
			//2ch内部リンク
				$ret .= $this ->link2ch($mat)."<br>";
			}else{// if(preg_match("@(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)@",$mat)){
			//外部(imenu経由)
				$ret .= $this->imenu($mat)."<br>";
			//}else{
			//	$ret .= $mat."<br>";
			}
		}
		return $ret;
	}
	function link2ch($txt){
		if(preg_match("@(?:https?|ftp):\/\/([A-Za-z0-9][A-Za-z0-9\-]{0,61}[A-Za-z0-9]\.)+2ch\.sc+\/test\/@",$txt)){
			$url2 = $txt;
			if(preg_match_all('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', $url2, $result) !== false)
			{
				foreach ($result[0] as $curl){
					$title = $curl;
					for($i=0;$i<count($GLOBALS['svs']);){
						$curl = str_replace($GLOBALS['svs'][$i]."/test/read.cgi", "c.2ch.sc/test/-", $curl);
						$ret = "<a href=\"$curl\">$title</a>";
						$i++;
					}
				}
			}
		}else{
			$ret = $txt;
		}
		return $ret;
	}
	function newcount($txt){
		$list = $this->dat($txt);
		$ret = count($list);
		return $ret;
	}
	function subject_sc($txt,$folder,$end,$start){//parse a subject list
		$list = $this->subject($txt,$folder);
		if($start==""){
			$k =0;
			for($i=0;$i<$end;$i++){
					if (preg_match("/★/u",mb_convert_encoding($list[$k]['name'],"UTF-8","ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN"))){
					$ret[$k]['no'] = $i+1;
					$ret[$k]['folder'] = $list[$i]['folder'];
					$ret[$k]['url'] = $list[$i]['url']."/i";
					$ret[$k]['name'] = $list[$i]['name'];
					$k++;
				}
			}
		}else{
			$fin = $end+$start;
			$k =0;
			for($i=$end;$i<=$fin;$i++){
				if(@$list[$fin+$k]['url']==""){}
				else{
					if (preg_match("/★/u",mb_convert_encoding($list[$k]['name'],"UTF-8","ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN"))){
						$ret[$k]['no'] = $start+$k;
						$ret[$k]['folder'] = $list[$fin+$k]['folder'];
						$ret[$k]['url'] = $list[$fin+$k]['url']."/i";
						$ret[$k]['name'] = $list[$fin+$k]['name'];
						$k++;
					}
				}
			}
		}
		return $ret;
	}
}