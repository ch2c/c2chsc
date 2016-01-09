# What is this ?
source code for c.2ch.sc
# summary
## 仕組み・基本的な考え方
主に，ライブラリ等はすべて別ファイルにしてメインから呼び出す.

変更・修正が必要な場合,該当するライブラリファイルの修正だけを目指す.

別ファイルにすることにより,メンテナンス性や可読性やコードの再利用性が高まるはず.

基本的に,中途半端なオブジェクト指向擬きを考えて作っています.

## ライセンス
MIT Licenseとします.

その為,必要となる別ライブラリ等は同梱いたしません.

使用者の責任において,取得し指定のディレクトリに設置してください.

## ディレクトリ構造および説明
cache : キャッシュ用フォルダ

configs : config用フォルダ(未定)

libs : 独自のライブラリ用フォルダ

libs/bbslist.class.php : bbsmenu をパースするライブラリ

libs/common.class.php : 共通のライブラリ

libs/get.class.php : datやsubject を取得するライブラリ

libs/parer.class.php : datやsubject.txt をパースするライブラリ

libs/util.class.php : ユーティリティのライブラリ(未使用)

imports : 外部(サードパーティ)からインポートしたライブラリ用フォルダ

imports/cache : cache lite (PEAR CacheLite) *1

ime.nu.php : リンククッション

Smarty : html出力するフレームワーク用フォルダ(Smarty) *2

plugins : Smartyのプラグイン用フォルダ *3

templates : 各画面のパターン(テンプレート)用フォルダ

config.cgi : 各種設定用ファイル

.htaccess : 

index.php : メインのプログラム

## 必要なライブラリ
### Cache_Lite 
https://pear.php.net/package/Cache_Lite/ より取得後,*1のフォルダに保存
### Smarty
http://www.smarty.net/ より取得後,本体は*2のフォルダに,プラグインは*3のフォルダに保存
## 変更が必要なファイル
config.cgi : /path/to/ を設置ディレクトリに適合させる

libs/bbslist.class.php : $json_localの/path/to/を設置ディレクトリに適合させる