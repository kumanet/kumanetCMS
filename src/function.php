<?php
/**
 * ku-ma.net CMS 
 *
 * ファンクション
 * <br>
 * 各ドメイン共通設定
 *
 * @author Nobuyuki Kumakura
 * @date 2017/11/07
 * @since 2014/09/11
 * @version 1.8
 * 
 * copyright Nobuyuki Kumakura
 */

/*
 * アドレス(ドメイン以下のファイル名)を配列に格納する
 * 
 * @param string $scriptName ドメイン以下のファイル名を取得する。
 * @retuen array アドレス(ドメイン以下のファイル名)の配列
 * 
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function addressData($scriptName){

	//ファイル拡張子を取得する
	global $fileExtension;

	//第何階層かを格納する
	//wwwフォルダ用
	$addressData[0] = substr_count($scriptName, "/");
	
	//ku-maフォルダ用
	//$addressData[0] = substr_count($scriptName, "/") -1;
//print $addressData[0];
	//ファイル名を格納する
	$addressData[1] = basename($scriptName,$fileExtension); 
	//フォルダを格納する
	$addressArray	= explode("/", $scriptName);
	//print $scriptName;
	//print "<br>";
	//print_r($addressArray);
	//print "<br>";

	for ($i = 2; $i <= count($addressArray)-1; $i++) {
		$addressData[$i] = $addressArray[$i-1];
	}

	return $addressData;

}

/*
 * SCRIPT_NAMEを取得する
 * 
 * @retuen string ドメイン以下のファイル名を取得する。
 * 
 * @todo filter拡張モジュール検討
 *   
 * @date 2017/11/07
 * @since 2014/09/11
 * @version 1.2
 * 
*/
function addressScriptName(){

	//ファイル拡張子を取得する
	global $fileExtension;
	//ファイルのアドレスを取得する
	global $fileAddress;
	//phpがmodualかcgiかの情報を取得する
	global $phpModule;
	//本番サーバとテストサーバとの区別
	global $productionServer;
	
	//PHPがモジュールの場合
	if($phpModule){
		$addressScriptName = $_SERVER['SCRIPT_NAME'];
	//phpがCGIの場合
	}else{
	//PHP_SELFを代入
		$cgiPhpSelf = htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES);
		//拡張子を除去する
		$addressScriptName = preg_replace("/\\".$fileExtension.".*/", $fileExtension, $cgiPhpSelf);
		$addressScriptName = preg_replace("/\\.php.*/", $fileExtension, $addressScriptName);
		$addressScriptName = preg_replace("/\\.html.*/", $fileExtension, $addressScriptName);
	}

	//本番サーバーはSSL対応済み。テストサーバーはSSL非対応
	if($productionServer){
		$fileAddress = "https://".$_SERVER["HTTP_HOST"].$addressScriptName;
	}else{
		$fileAddress = "http://".$_SERVER["HTTP_HOST"].$addressScriptName;
	}
	
	return $addressScriptName;
}

/*
 * コンテンツアイテムのタイトルを取得する
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen array コンテンツアイテムのタイトル
 * 
 *   
 * @todo 多言語対応の必要あり
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function addressTitle($addressDataArray){

	//言語ID
	global $langId;
	//第三階層(外国語は1階層下げ)
	global $levelContentsDirectory;
	//タイトルがありません文を取得する
	global $notFoundTitleString;

	//タイトル取得する。ドメイントップページとカテゴリトップページでは取得しない
	if ($addressDataArray[0] >= $levelContentsDirectory){
		//タイトルの有無をデータベースで検索する
		$addressTitleArray = selectcontentsItemtitle($addressDataArray);
		//タイトルが存在しない場合
		if($addressTitleArray[$langId][0] == ""){
			$addressTitleArray[$langId][0] = $notFoundTitleString;
		}
	}else{
		$addressTitleArray[$langId][0] = "";
	}
	
	return $addressTitleArray;
}

/*
 * コンテンツカテゴリを取得する
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen array コンテンツカテゴリ情報
 * 
 *   
 * @date 2015/07/06
 * @since 2014/09/23
 * @version 1.2 
 * 
*/
function categoryData($addressDataArray){

	//ドメインタイトル
	global $domainTitle;
	//言語ID
	global $langId;
	//カテゴリデータありません文
	global $notFoundCategoryDataString;

	$categoryData[$langId][0] = "";
	$categoryData[$langId][1] = "";
	$categoryData[$langId][2] = "";
	$categoryData[$langId][3] = "";


	//カテゴリ情報を取得する
	$categoryDataResult = selectContentsCategoryVariable($addressDataArray);

	if(is_array($categoryDataResult) == true){
		//取得したカテゴリ情報の結果を代入する。
		foreach($categoryDataResult as $i => $row){
			if(is_array($row) == true){
				foreach($row as $j => $row2){
					$categoryData[$row2["LANG_ID"]][0] = $row2["CONTENTSCATEGORY_NAME"];
					$categoryData[$row2["LANG_ID"]][1] = $row2["CONTENTSCATEGORY_COMMENT"];
					$categoryData[$row2["LANG_ID"]][2] = $row2["CONTENTSCATEGORY_KEYWORDS"];
					$categoryData[$row2["LANG_ID"]][3] = $row2["CONTENTSCATEGORY_DIRECTORY"];
				}
			}else{
					$categoryData[$langId][0] = $domainTitle;
					$categoryData[$langId][1] = $notFoundCategoryDataString;
					$categoryData[$langId][2] = "";
					$categoryData[$langId][3] = "";
			}
		}	
	}else{
		$categoryData[$langId][0] = $domainTitle;
		$categoryData[$langId][1] = $notFoundCategoryDataString;
		$categoryData[$langId][2] = "";
		$categoryData[$langId][3] = "";
	}

	//カテゴリがinfoの場合の処理
	if($categoryData[$langId][3] == "info"){
		$categoryData = categoryDataInfo($addressDataArray);
	}

	return $categoryData;
}

/*
 * 表示されているページがどのカテゴリかを返す設定
 * 多言語対応 
 * 
 * @param string $multiLangDate strtotime
 * @retuen string カテゴリ名を返す。トップページならHOMEを返す。
 * 
 * @date 2017/03/22
 * @since 2014/09/15
 * @version 1.1
 * 
*/
function categorySelected($addressDataArray){

	//言語コード
	global $langCode;

	//日本語の場合
	if($langCode == "ja"){
		if(!isset($addressDataArray[2])){
			$categorySelected = "HOME";
		}else{
			$categorySelected = $addressDataArray[2];
		}
	//英語
	}else if($langCode == "en"){
		if(!isset($addressDataArray[3])){
			$categorySelected = "HOME";
		}else{
			$categorySelected = $addressDataArray[3];
		}
	//ロシア語
	}else if($langCode == "ru"){
		if(!isset($addressDataArray[3])){
			$categorySelected = "HOME";
		}else{
			$categorySelected = $addressDataArray[3];
		}
	}else{
		if(!isset($addressDataArray[2])){
			$categorySelected = "HOME";
		}else{
			$categorySelected = $addressDataArray[2];
		}
	}

	return $categorySelected;
	
}

/*
 * コンテンツアイテムのデータを配列で取得する
 * 
 * @retuen boolean アクセスしているページはコンテンツアイテムのページか否か。一覧ならfalse
 * 
 * @since 2014/09/21
 * @version 1.0 
 * 
*/
function get_contentsItemDataArray($addressDataArray){

	//コンテンツディレクトリ第一階層(外国語は1階層下げ)
	global $levelContentsDirectory;

	$contentsItemDataArray = selectPhotoPosted($addressDataArray);

	return $contentsItemDataArray;

}

/*
 * コンテンツアイテムのページかどうかを判定する
 * 
 * @retuen boolean アクセスしているページはコンテンツアイテムのページか否か。一覧ならfalse
 * 
 * @since 2015/09/27
 * @since 2014/09/21
 * @version 1.1
 * 
*/
function contentsItemPage($addressDataArray){

	//一覧表示のページはindexファイルなので、ファイル名から判定する
	if($addressDataArray[1] == "index"){
		$contentsItemPage = false;
	}else{
		$contentsItemPage = true;
	} 

	return $contentsItemPage;

}

/*
 * datファイル用にディレクトリ名をコンテンツカテゴリ以下取得する。最後にスラッシュはつける。
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen string ディレクトリ名
 * 
 *   
 * @since 2014/09/21
 * @version 1.1
 * 
*/
function directoryNameAddress($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//アーカイブスカテゴリディレクトリから最後のスラッシュ文字を除外
	$archivesCategoryDirectoryRtrim = rtrim($archivesCategoryDirectory, "/");

	//カテゴリ以下存在する場合。カテゴリ名は取得する。
	if(isset($addressDataArray[2])){
		$directoryNameAddress = "";

		//ディレクトリにスラッシュを付与して合成する。最後のディレクトリはスラッシュは付与する。		
		for ($i = 2; $i <= count($addressDataArray)-1; $i++) {
//print $addressDataArray[$i];

			//アーカイブスカテゴリディレクトリは除外する
			if($i == 2 ||$i == 3){
				if($addressDataArray[$i] != $archivesCategoryDirectoryRtrim){
					$directoryNameAddress .= $addressDataArray[$i];
					$directoryNameAddress .= "/";
				}
			}else{
					$directoryNameAddress .= $addressDataArray[$i];
					$directoryNameAddress .= "/";
			}
		
		}
	//トップページの処理
	}else{
		$directoryNameAddress = "";
	}
//print "<br>directoryNameAddressTest<br>";
//print $directoryNameAddress;
//print "<br>end<br>";

	return $directoryNameAddress;
}

/*
 * sqlselect用にディレクトリ名を取得する。カテゴリ名は取得しない。最後にスラッシュはつける。
 * <br>
 * 外国語対応済み
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen string ディレクトリ名
 * 
 *   
 * @since 2014/09/18
 * @version 1.2
 * 
*/
function directoryName($addressDataArray){

	//コンテンツディレクトリ第一階層(外国語は1階層下げ)
	global $levelContentsDirectory;

	//カテゴリ以下のコンテンツディレクトリが存在する場合
	if(isset($addressDataArray[$levelContentsDirectory])){

		$directoryName = "";
		//ディレクトリにスラッシュを付与して合成する。最後のディレクトリはスラッシュは付与しない。
		for ($i = $levelContentsDirectory; $i <= count($addressDataArray)-1; $i++) {
			$directoryName .= $addressDataArray[$i];
			if($i < count($addressDataArray)-1){
				$directoryName .= "/";
			}
		}
	}else{
		$directoryName = "";
	}

	return $directoryName;
}

/*
 * 元号フォーマット
 * 
 * @param string MTSH形式の元号
 * @retuen string 元号をXX形式で返す
 *   
 * @since 2014/09/15
 * @version 1.0
 * 
*/
function gengouFormat($gengou){

	//元号フォーマット
	if($gengou == "M"){
		$gengouName = "明治";
	}else if($gengou == "T"){
		$gengouName = "大正";
	}else if($gengou == "S"){
		$gengouName = "昭和";
	}else if($gengou == "H"){
		$gengouName = "平成";
	}else{
		$gengouName = $gengou;
	}

	return $gengouName;
}

/*
 * 元号MTSH判定
 * 
 * @param string $year 年
 * @param string $month 月
 * @retuen string 元号をMTSHのいずれかで返す
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function is_gengou($year,$zdate){

	//明治44年以前
	if($year <= 1911){
		$gengou = "M";
	//明治45年-大正元年
	}else if($year == 1912){
		if($zdate  <= 212 ){
			$gengou = "M";
		}else{
			$gengou = "T";
		}
	//大正2年-大正14年
	}else if($year >= 1913 && $year <= 1925){
		$gengou = T;
	//大正15年-昭和元年
	}else if($year == 1926){
		if($zdate  <= 359 ){
			$gengou = "T";
		}else{
			$gengou = "S";
		}
	//昭和2年-昭和63年
	}else if($year >= 1927 && $year <= 1988){
		$gengou = "S";
	//昭和64-平成元年
	}else if($year == 1989){
		if($zdate  <= 7 ){
			$gengou = "S";
		}else{
			$gengou = "H";
		}
	//平成2年以降
	}else if($year >= 1990){
			$gengou = "H";
	//明治より前
	}else{
		$gengou = NULL;
	}

	return $gengou;
}

/*
 * 一覧表示用のサムネイル画像取得
 * 
 * @param string $year 年
 * @param array $row コンテンツアイテムのSQL情報
 * @param string $year 画像サイズ
 * @retuen string 一覧表示用のサムネイル画像のhtmlタグ
 *   
 * @todo 多言語対応の必要あり
 * @date 2017/03/22
 * @since 2014/09/11
 * @version 1.1
 * 
*/
function imageListThumbnailTag($addressDataArray, $row, $imgClass){

	//言語コード
	global $langCode;
	
	//サムネイル文字列
	//日本語の場合
	if($langCode == "ja"){
		$thumbnailString = "サムネイル";
		$thumbnailPhotoString = "の写真サムネイル版";
	//英語の場合
	}else if($langCode == "en"){
		$thumbnailString = " Thumbnail";
		$thumbnailPhotoString = " Photo thumbnail version";
	//ロシア語の場合
	}else if($langCode == "ru"){
		$thumbnailString = " Миниатюра";
		$thumbnailPhotoString = " Фото с миниатюрами версия";
	}else{
		$thumbnailString = "サムネイル";
		$thumbnailPhotoString = "の写真サムネイル版";
	}

	//コンテンツアイテムに登録された画像ファイルの拡張子を取り除く
	$imagePathinfo = pathinfo($row["IMAGE_FILE_NAME"]);
	$imageFileNameNoExtension = basename($row["IMAGE_FILE_NAME"], ".".$imagePathinfo['extension']);

	//"_thumbnail.jpg"が付与されたコンテンツアイテムのサムネイルアドレスを取得する
	$addressContentsItemThumbnail = linkLevel($addressDataArray[0])."images/".$row["CONTENTSCATEGORY_DIRECTORY"]."/".$row["IMAGE_DIRECTORY_NAME"]."/".$imageFileNameNoExtension."_thumbnail.jpg";
	//コンテンツアイテムのサムネイルアドレスが存在するかチェックする
	//もし存在するならばコンテンツアイテムのアドレスを変数に代入する
	if(file_exists($addressContentsItemThumbnail)){

		//画像のALTがデータベースに格納されていた場合としていない場合との処理
		if($row["IMAGE_ALT"] != ""){
			$addressListThumbnail = "<img alt=\"".$row["IMAGE_ALT"].$thumbnailString."\" title=\"".$row["TITLE"]."\" src=\"".$addressContentsItemThumbnail."\" class=\"".$imgClass."\">";
		}else{
			$addressListThumbnail = "<img alt=\"".$row["IMAGE_TITLE"].$thumbnailPhotoString."\" title=\"".$row["TITLE"]."\" src=\"".$addressContentsItemThumbnail."\" class=\"".$imgClass."\">";
		}

	//そうでないのならばコンテンツディレクトリのアドレスを変数に代入する
	}else{
		$addressListThumbnail = "<img alt=\"".$row["CONTENTSCATEGORY_NAME"].$thumbnailPhotoString."\" title=\"".$row["TITLE"]."\" src=\"".linkLevel($addressDataArray[0])."images/".$row["CONTENTSCATEGORY_DIRECTORY"]."/indexImage_thumbnail.jpg\" class=\"".$imgClass."\">";
	}
//print $addressListThumbnail;
	return $addressListThumbnail;
}

/*
 * ファイル名がindexだった時の処理
 * 
 * @param string $filename FILE_NAMEと拡張子
 * @retuen string indexと拡張子ならから文字を返す
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function indexFile($filename){

	//ファイル拡張子を取得する
	global $fileExtension;

	//INDEXファイルならばから文字を返す。
	if($filename == "index".$fileExtension){
		$indexFile = "";
	}else{
		$indexFile = $filename;
	}	

	return $indexFile;

}

/*
 * ドメイン以下の階層数を計算する
 * 
 * @param string $scriptName ドメイン以下のファイル名を取得する。
 * @retuen int 階層数
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function levelCount($scriptName){

	$scriptCount = substr_count($scriptName, "/");
	
	return $scriptCount;
}

/*
 * 階層数にあわせて相対パスを取得する
 * 
 * @param string $level 階層数
 * @retuen string 相対パス
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function linkLevel($level){

	if($level == 0){
		$linkLevel = "";
	}else if($level == 1){
		$linkLevel = "";
	//2階層以下の処理
	}else if($level >= 2){
		$linkLevel = "";
		for($i = 2; $i <= $level; $i++){
		$linkLevel .= "../";	
		}
	}else{
		$linkLevel = "";
	}

	return $linkLevel;

}

/*
 * getの値から一覧の表示件数(FROM TO)を取得する。
 * 
 * @retuen array 表示件数(FROM TO)
 *   
 * @since 2014/09/17
 * @version 1.1 
 * 
*/
function listCount(){

	//最大表示件数の初期値を取得する。
	global $listMax;

	//getが存在するならば
	//if(array_key_exists ('list', $_GET)){
	if(isset($_GET["list"])){	
		$getList = $_GET["list"];
		//getのlistの値が半角数字であるのならば
		if(preg_match("/^[0-9]+$/", $getList)){
			$listCountMin = $getList;
			//SQLでは使わない
			//$listCountMax = $getList + $listMax;
			$listCountMax = $listMax;
		//半角数字でないのならば初期値
		}else{
			$listCountMin = 0;
			$listCountMax = $listMax;
		}
	//getが存在しないのならば初期値
	}else{
			$listCountMin = 0;
			$listCountMax = $listMax;
	}

	//表示件数を代入する。
	$listCountArray[0] = $listCountMin;
	$listCountArray[1] = $listCountMax;
//print $listCountArray[0];
//print $listCountArray[1];
	return $listCountArray;
}

/*
 * getの一覧の値を取得する
 * 
 * @retuen string getの一覧の値
 *   
 * @since 2014/09/17
 * @version 1.0
 * 
*/
function listCountNow(){

	if(isset($_GET["list"])){	
		$getList = $_GET["list"];
		//getのlistの値が半角数字であるのならば
		if(preg_match("/^[0-9]+$/", $getList)){
			$listCountNow = $getList;
		//半角数字でないのならば初期値
		}else{
			$listCountNow = 0;
		}
	//getが存在しないのならば初期値
	}else{
			$listCountNow = 0;
	}

	return $listCountNow;
}

/*
 * 一覧の表示件数(FROM TO)の文字列を取得する。
 * 
 * @param string $list 現在のページ位置
 * @param string $localLangId この中で使われる言語ID
 * @param array $listCountArray 一覧表示数を取得する
 * @retuen string 一覧(List)FROM-TO
 *   
 * @date 2015/09/27
 * @since 2014/09/11
 * @version 1.3
 * 
*/
function listCountWord($list,$localLangId,$listCountArray){

	//最大表示件数の初期値を取得する。
	global $listMax;

	$listStringArray = listStringArray();

	//タグが必要な場合
	if($listCountArray[0] > 0){

		if($list == "current"){
			$listCountWord  = $listStringArray[$localLangId];
			$listCountWord .= $listCountArray[0] + 1; 
			$listCountWord .= "-";
			$listCountWord .= $listCountArray[0] + $listMax;
		}else if($list == "prev"){
			
			//0件だったら不要
			if($listCountArray[0] - $listMax == 0){
				$listCountWord = "";
			}else{
				$listCountWord  = $listStringArray[$localLangId];
				$listCountWord .= $listCountArray[0] - $listMax + 1;
				$listCountWord .= "-";
				$listCountWord .= $listCountArray[0];
			}
			
		}else if($list == "next"){
			$listCountWord  = $listStringArray[$localLangId];
			$listCountWord .= $listCountArray[0] + $listMax + 1; 
			$listCountWord .= "-";
			$listCountWord .= $listCountArray[0] + $listMax + $listMax;
		}else{
			$listCountWord  = $listStringArray[$localLangId];
			$listCountWord .= $listCountArray[0]; 
			$listCountWord .= "-";
			$listCountWord .= $listCountArray[0] + $listMax;
		}		

	//タグが不要な場合
	}else{
		if($list == "next"){
			$listCountWord  = $listStringArray[$localLangId];
			$listCountWord .= $listCountArray[0] + $listMax + 1; 
			$listCountWord .= "-";
			$listCountWord .= $listCountArray[0] + $listMax + $listMax;
		}else{
			$listCountWord = "";
		}
	}

	return $listCountWord;
}

/*
 * 画像サイズを一覧向けに小さく表示できるようcssのclass名を変換
 * 
 * @param string $imageSize 本来の画像サイズクラス名
 * @retuen string 一覧用の画像サイズクラス名
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function listImageSize($imageSize){

	if(strpos($imageSize,"Turn") == true){
		if(strpos($imageSize,"Film") == true){
			$listImageSize = "qvgaFilmTurn";
		}else{
			$listImageSize = "qvgaTurn";
		}
	}else{
		if(strpos($imageSize,"Film") == true){
			$listImageSize = "qvgaFilm";
		}else{
			$listImageSize = "qvga";
		}
	}

	return $listImageSize;

}

/*
 * メタタグ用のカテゴリタイトルを生成する
 * コンテンツアイテムからカテゴリトップへの遷移用
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @param string $localLangId この中で使われる言語ID
 * @param array $listSortCountArray コンテンツアイテムのソート順
 * @retuen string メタタグ用のタイトル
 *   
 * @since 2015/09/27
 * @version 1.0
 * 
*/
function metaTagCategoryTitle($addressDataArray,$localLangId,$listSortCountArray){

	//カテゴリデータ
	global $categoryDataArray;

	//ドメインタイトル
	$domainTitleArray = domainTitleArray();

	//一覧の件数表示
	$listCountWord = listCountWord("current",$localLangId,$listSortCountArray);

	$metaTagCategoryTitle = "";

	$metaTagCategoryTitle .= $domainTitleArray[$localLangId].":".$categoryDataArray[$localLangId][0];
	if($listSortCountArray[0] != 0){
		$metaTagCategoryTitle .= ":".$listCountWord;
	}

	return $metaTagCategoryTitle;

}

/*
 * メタタグ用のタイトルを生成する
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @param string $localLangId この中で使われる言語ID
 * @param string $list 一覧ページ
 * @retuen string メタタグ用のタイトル
 *   
 * @date 2017/03/26
 * @since 2015/08/17
 * @version 1.4
 * 
*/
function metaTagTitle($addressDataArray,$localLangId,$list){

	//カテゴリデータ
	global $categoryDataArray;
	//言語ID
	//global $langId;
	//ドメイントップ階層(外国語は1階層下げ)
	global $levelDomain;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	//一覧表示数
	global $listCountArray;
	//タイトル
	global $titleDataArray;

	//ドメインタイトル
	$domainTitleArray = domainTitleArray();

	//一覧の件数表示
	$listCountWord = listCountWord($list,$localLangId,$listCountArray);

	$metaTagTitle = "";

	//トップページとそうでない場合で処理を分ける
	//トップページ
	if ($addressDataArray[0] == $levelDomain){
		$metaTagTitle .= $domainTitleArray[$localLangId];
		if($listCountWord != ""){
			$metaTagTitle .= ":".$listCountWord;
		}
	//カテゴリトップ及びinfoページ
	}else if ($addressDataArray[0] > $levelDomain && $addressDataArray[0] <= $levelContentsCategory){
		$metaTagTitle .= $domainTitleArray[$localLangId].":".$categoryDataArray[$localLangId][0];
		if($listCountWord != ""){
			$metaTagTitle .= ":".$listCountWord;
		}
	//コンテンツアイテム
	}else{
		//タイトルからカテゴリの文字列分だけ削除
		if(array_key_exists($localLangId,$titleDataArray)){
			for ($i = count($titleDataArray[$localLangId]); $i > 0; $i--) {
				if($i < count($titleDataArray[$localLangId])){
					$titleDataReplaceArray[$localLangId][$i-1] = preg_replace("/^[ 　]+/u", "", str_replace($titleDataArray[$localLangId][$i], "", $titleDataArray[$localLangId][$i-1]));
				}else{
					$titleDataReplaceArray[$localLangId][$i-1] = $titleDataArray[$localLangId][$i-1];
				}
			}
		}

		//タイトル取得
		if(array_key_exists($localLangId,$titleDataArray)){
			for ($i = 0; $i < count($titleDataArray[$localLangId]); $i++) {
				$metaTagTitle .= $titleDataReplaceArray[$localLangId][$i].":";
			}
		}
		if($listCountWord != ""){
			$metaTagTitle .= $listCountWord.":";
		}
		$metaTagTitle .= $domainTitleArray[$localLangId].":".$categoryDataArray[$localLangId][0];
	}

	return $metaTagTitle;

}

/*
 * 多言語日付取得
 * 
 * @param string $multiLangDate strtotime
 * @retuen string 言語別の日付形式で返す
 *   
 * @date 2017/03/22
 * @since 2014/09/15
 * @version 1.1
 * 
*/
function multiLangDate($multiLangDate){

	//言語コード
	global $langCode;

	//日付取得	
	$multiLangDate_year = date("Y", $multiLangDate);
	$multiLangDate_month = date("n", $multiLangDate);
	$multiLangDate_month_eu = date("M", $multiLangDate);
	$multiLangDate_date = date("j", $multiLangDate);
	$multiLangDate_zdate = date("z", $multiLangDate) + 1;

	//言語別で日付取得
	if($langCode == "ja"){
		//元号MTSH取得
		$gengou = is_gengou($multiLangDate_year,$multiLangDate_zdate);
		//和暦取得
		$wareki = wareki($multiLangDate_year,$gengou);
		//元号フォーマット
		$gengouName = gengouFormat($gengou);
		//日付フォーマット
		$multiLangDate = $gengouName.$wareki."(".$multiLangDate_year.")"."/".$multiLangDate_month."/".$multiLangDate_date;
	}else if($langCode == "en"){
		//日付フォーマット
		$multiLangDate = $multiLangDate_date."/".$multiLangDate_month_eu."/".$multiLangDate_year;
	}else if($langCode == "ru"){
		//日付フォーマット
		$multiLangDate = $multiLangDate_month."/".$multiLangDate_date."/".$multiLangDate_year;
	}else{
		//日付フォーマット
		$multiLangDate = $multiLangDate_year."/".$multiLangDate_month."/".$multiLangDate_date;
	}

	return $multiLangDate;
}

/*
 * 西暦を和暦に変換する
 * 
 * @param string $year 年
 * @param string $gengou 元号のMTSH形式
 * @retuen string 和暦
 *   
 * @since 2014/09/11
 * @version 1.0 
 * 
*/
function wareki($year,$gengou){

	//明治
	if($gengou == "M"){
		$wareki = $year - 1867;
	//大正
	}else if($gengou == "T"){
		$wareki = $year - 1911;
	//昭和
	}else if($gengou == "S"){
		$wareki = $year - 1925;
	//平成
	}else if($gengou == "H"){
		$wareki = $year - 1988;
	//明治より前
	}else{
		$wareki = $year;
	}

	return $wareki;
}

?>