<?php
/**
	[common.class.php]

	Copyright (c) [2014] [2ch.sc]

	This software is released under the MIT License.

	http://opensource.org/licenses/mit-license.php

*/
class common{
	function load($config,$para){//load a config
		$settings = file($config);
		$ret = "Loading config Failed";
		foreach($settings as $subset){
			@list($arg1,$arg2) = explode("<>",$subset);
			if($para==$arg1){
				$ret = rtrim($arg2);
			}
		}
		return $ret;
	}
	function save($config,$para,$add){//save a config
		//$config .=".cgi";
		$settings = file($config);
		$ret = "Save config Failed";
		$fp = fopen($config, "rb+");
		foreach($settings as $subset){
			@list($arg1,$arg2) = explode("<>",$subset);
			if($para==$arg1){
				if(flock($fp, LOCK_EX)){// lock the file
					fwrite($fp, $para."<>".$add."\n");
					fclose($fp);//unlock the file
				}
			}
		}
		return $ret;
	}
	function version($file){//version
		$ret = $this->load($file,"version");
		$ret .= date("YmdHis", filemtime($file));
		return $ret;
	}
}