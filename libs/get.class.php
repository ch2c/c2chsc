<?php
/**
	[get.class.php]

	Copyright (c) [2014] [2ch.sc]

	This software is released under the MIT License.

	http://opensource.org/licenses/mit-license.php

*/
class get{
	function connect($url){//file_get_contents版
	$commons = new common;
		$context = array(
			"http" => array(
				"method"  => "GET",
				"header"  => "User-Agent: ".$commons->load("config.cgi","ua")."/".$commons->version("config.cgi")."\r\n",
				"ignore_errors" => "true"
			)
		);
		$option = stream_context_create($context);
		$raw = file_get_contents($url, false, $option);
		$response = mb_convert_encoding($raw,'UTF-8','sjis-win');
		
		if($http_response_header[0]=="HTTP/1.1 200 OK"){
		}else{
			//unset($response);
			preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
			$response = $this->status($matches[1]);
		}
		unset($raw);
		return $response;
	}
	function status($code){//ステータス判定
		switch ($code) {
			case '200':
				break;
			case '302':
					$ret = $code." : dat落ち か 何らかのエラーです。(".$code.")";
				break;
			case '404':
					$ret = $code." : スレッドがない か 移転中です。。(".$code.")";
				break;
			case '503':
					$ret = $code." : 作業中です｡｡｡ (".$code.")";
				break;
			case '':
					$ret = $code." : サーバが重い か 不安定です。。。(".$code.")";
				break;
			default;
					$ret = $code." : 何らかのエラーです。。。。(".$code.")";
		}
		return $ret;
	}
	function url($server,$folder,$key){// set $key = "dummy" if create subject url
		switch($key){
			case "dummy"://subject mode
				$ret = "http://".$server."/".$folder."/subject.txt";
				break;
			default:// dat mode
				$ret = "http://".$server."/".$folder."/dat/".$key.".dat";
				break;
		}
		return $ret;
	}
	function getsubject($server,$folder){
		$url = $this->url($server,$folder,"dummy");
		$ret = $this->connect($url);
		return $ret;
	}
	function getdat($server,$folder,$key){
		$url = $this->url($server,$folder,$key);
		$ret = $this->connect($url);
		return $ret;
	}
	function getlocal($folder,$key){
		$url = $folder."/dats/".$key.".dat";
		$raw = file_get_contents($url);
		$res = mb_convert_encoding($raw,'UTF-8','sjis-win');
		return $res;
	}

}