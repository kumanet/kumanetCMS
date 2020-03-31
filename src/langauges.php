<?php
/**
 * ku-ma.net CMS 
 *
 * 言語の定義
 * <br>
 * クマネットの独自設定あり
 *
 * @author Nobuyuki Kumakura
 * @date 2017/03/23
 * @since 2014/09/10 
 * @version 1.9
 * 
 * copyright Nobuyuki Kumakura
 */

/*
 * コンテンツカテゴリの階層
 * <br>
 * 多言語ページ用。初期値設定
 * <br>
 * 外国語の階層は1段階下げるので関数categoryLevelにかける
 * 
 *　@var string $levelDown 現在の階層から1段下げる(外国語は1階層下げ)
 *　@var string $levelDomain ドメイントップ階層(外国語は1階層下げ)
 *　@var string $levelContentsCategory カテゴリトップ階層(外国語は1階層下げ)
 *　@var string $levelContentsDirectory コンテンツディレクトリ第一階層(外国語は1階層下げ)
 *　@var string $levelContentsDirectorySecond コンテンツディレクトリ第二階層(外国語は1階層下げ)
 *　@var string $levelContentsInfoCategory カテゴリトップinfo階層(外国語は1階層下げ)
 * 
 *   
 * @date 2015/09/26
 * @since 2014/09/19
 * @version 1.3
 * 
*/
$levelDown = 0;
$levelDomain = 1;
$levelContentsCategory = 2;
$levelContentsDirectory = 3;
$levelContentsDirectorySecond = 4;
$levelContentsInfoCategory = 2;


/**
 * 言語コード
 * <br>
 * 多言語ページ用。初期値設定
 *　
 *　@var string 日本語ja
 *　
 * @date 2017/03/22
 * @since 2014/09/14
 * @version 1.1
*/
$langCode = "ja";


/**
 * 言語ID
 * <br>
 * 多言語ページ用。初期値設定
 *　
 *　@var string 日本語ID
 *　
 * @since 2014/09/14
 * @version 1.1
*/
$langId = 1;


/**
 * 言語ディレクトリ
 * <br>
 * 多言語ページ用。初期値設定
 *　
 *　@var string 日本語言語ディレクトリ
 *　
 * @since 2014/09/19
 * @version 1.0
*/
$langDirectory = "";

/*
 * コンテンツカテゴリの階層を取得する
 * <br>
 * 戻り値無し
 * <br>
 * 言語によって階層が異なる
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * 
 *   
 * @date 2017/03/22
 * @since 2014/09/19
 * @version 1.3
 * 
*/
function categoryLevel($addressDataArray){

	//言語コード
	global $langCode;

	//現在の階層から1段下げる(外国語は1階層下げ)
	global $levelDown;
	//ドメイントップ階層(外国語は1階層下げ)
	global $levelDomain;
	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;
	//コンテンツディレクトリ第一階層(外国語は1階層下げ)
	global $levelContentsDirectory;
	//コンテンツディレクトリ第二階層(外国語は1階層下げ)
	global $levelContentsDirectorySecond;
	//カテゴリトップinfo階層(外国語は1階層下げ)
	global $levelContentsInfoCategory;


/*
	//現在のカテゴリから1下げる(外国語は1階層下げ)
	global $categoryLevelDown;
	//第一階層ドメイントップ(外国語は1階層下げ)
	global $categoryLevelDomain;
	//第二階層(外国語は1階層下げ)
	global $categoryLevelSecond;
	//第三階層(外国語は1階層下げ)
	global $categoryLevelThird;
	//第四階層(外国語は1階層下げ)
	global $categoryLevelFourth;
*/

	//日本語の場合
	if($langCode == "ja"){

		$levelDown = $addressDataArray[0] - 1 ;
		$levelDomain = 1;
		$levelContentsCategory = 2;
		$levelContentsDirectory = 3;
		$levelContentsDirectorySecond = 4;
		$levelContentsInfoCategory = 2;	

	//外国語の場合
	}else{

		//外国語の場合もう1段階下げる
		$levelDown = $addressDataArray[0] - 2 ;
		$levelDomain = 2;
		$levelContentsCategory = 3;
		$levelContentsDirectory = 4;
		$levelContentsDirectorySecond = 5;
		$levelContentsInfoCategory = 3;	

	}

}

/**
 * 言語コード取得
 * <br>
 * 多言語ページ用。修正予定。
 *　
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列 
 *　@return string 言語コードを返す
 *　
 * @date 2017/03/22
 * @since 2014/09/10
 * @version 1.1
*/
function langCode($addressDataArray){

	//言語コード
	global $langCode;

	//トップページの場合
	if(!isset($addressDataArray[2])){
		$langCode = "ja";
	}else{
		//英語の場合
		if($addressDataArray[2] == "en"){
			$langCode = "en";
		//ロシア語の場合
		}else if($addressDataArray[2] == "ru"){
			$langCode = "ru";
		//コンテンツカテゴリの場合
		}else{
			$langCode = "ja";
		}
	}
	return $langCode;
}

/**
 * 言語ディレクトリ取得
 * <br>
 * 多言語ページ用。修正予定。
 *　
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列 
 *　@return string 言語ディレクトリを返す
 *　
 * @since 2014/09/15
 * @version 1.0 
*/
function langDirectory($addressDataArray){

	//言語ディレクトリ
	global $langDirectory;

	//トップページの場合
	if(!isset($addressDataArray[2])){
		$langDirectory = "";
	}else{
		//英語の場合
		if($addressDataArray[2] == "en"){
			$langDirectory = "en/";
		//ロシア語の場合
		}else if($addressDataArray[2] == "ru"){
			$langDirectory = "ru/";
		//コンテンツカテゴリの場合
		}else{
			$langDirectory = "";
		}
	}
	return $langDirectory;
}

/*
 * 多言語ファイル用にディレクトリ名をコンテンツカテゴリ以下取得する。最後にスラッシュはつける。
 * 
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 * @retuen string ディレクトリ名
 * 
 *   
 * @since 2014/09/21
 * @version 1.1
 * 
*/
function langdirectoryNameAddress($addressDataArray){

	//カテゴリトップ階層(外国語は1階層下げ)
	global $levelContentsCategory;

	//カテゴリ以下存在する場合。カテゴリ名は取得する。
	if(isset($addressDataArray[$levelContentsCategory])){
		$langdirectoryNameAddress = "";
		//ディレクトリにスラッシュを付与して合成する。最後のディレクトリはスラッシュは付与する。		
		for ($i = $levelContentsCategory; $i <= count($addressDataArray)-1; $i++) {
			$langdirectoryNameAddress .= $addressDataArray[$i];
			$langdirectoryNameAddress .= "/";
		}
	//トップページの処理
	}else{
		$langdirectoryNameAddress = "";
	}

	return $langdirectoryNameAddress;
}

/**
 * 言語ID取得
 * <br>
 * 多言語ページ用。修正予定。
 *　
 * @param array $addressDataArray アドレス(ドメイン以下のファイル名)の配列
 *　@return string 言語IDを返す
 *　
 * @since 2014/09/10
 * @version 1.0 
*/
function langId($addressDataArray){

	//言語ID
	global $langId;

	//トップページの場合
	if(!isset($addressDataArray[2])){
		$langId = 1;
	}else{
		//英語の場合
		if($addressDataArray[2] == "en"){
			$langId = 2;
		//ロシア語の場合
		}else if($addressDataArray[2] == "ru"){
			$langId = 3;
		//コンテンツカテゴリの場合
		}else{
			$langId = 1;
		}
	}
	return $langId;
}

/*
 * 多言語設定の案内
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string 多言語切り替え案内タブをhtmlタグで返す。
 * 
* @date 2017/03/22
 * @since 2014/07/21
 * @version 1.8
 * 
*/
function langaugesNavi($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//コンテンツアイテムID
	global $contentsItemId;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//本番サーバかテストサーバかの値を取得する。
	global $productionServer;
	//表示モード
	global $viewMode;

	//ファイル名の初期設定
	$filenameString = "";
	//外国語設定
	$langCode_en = true;
	$langCode_ru = true;
	//GET一覧の値
	$listCountNow = listCountNow();

	//一覧ではない、または一覧(list)の初期値が0のとき
	if($listCountNow == 0){
		//ファイル名がindexではない場合
		if($addressDataArray[1] != "index"){
			//ファイル名を拡張子つきで生成する
			$filenameString = $addressDataArray[1].$fileExtension;
		}
	}else{

		//本番サーバの場合とテストサーバの場合での動作
		if($productionServer){
			$filenameString = $addressDataArray[1].$listCountNow.$fileExtension;
		}else{
			$filenameString = $addressDataArray[1].$fileExtension."?list=".$listCountNow;
		}
	}
	
	//コンテンツアイテムの外国語版が登録されているか否かを判定する。
	//コンテンツアイテムのページを表示している場合
	if($contentsItemId != 0){
	
		$contentsitemfLangListArray = selectContentsitemfLangList($contentsItemId);
		//検索結果が存在する場合
		if(isset($contentsitemfLangListArray)){
			//英語のページが登録されているか
			if(!isset($contentsitemfLangListArray[2])){
				$langCode_en = false;
			}
			//ロシア語のページが登録されているか
			if(!isset($contentsitemfLangListArray[3])){
				$langCode_ru = false;
			}
		}else{
			$langCode_en = false;
			$langCode_ru = false;
		}
	}


	$langaugesNavi  = "";
	//スマートフォンでは表示する
	if($viewMode == "SMP"){

		if($langCode == "ja"){
			$langaugeMenuString = "言語設定(Langauge setting)";
		}else if($langCode == "en"){
			$langaugeMenuString = "Langauge setting";
		}else if($langCode == "ru"){
			$langaugeMenuString = "Langauge setting";
		}else{
			$langaugeMenuString = "言語設定(Langauge setting)";
		}

	$langaugesNavi .= "<div class=\"langaugesSmp\">\n";
	$langaugesNavi .= $langaugeMenuString;
	$langaugesNavi .= "</div>\n";
	}
	
	$langaugesNavi .= "<nav class=\"langauges\">\n";
	//$langaugesNavi .= "<div class=\"langauges\">\n";
	$langaugesNavi .= "<ul>\n";

	//言語ごとに選択する。
	//日本語
	if($langCode == "ja"){
		$langaugesNavi .= "		<li class=\"langaugesSelected\">\n";
		$langaugesNavi .= "			<span class=\"langaugesSelectedSpan\">\n";
		$langaugesNavi .= "				日本語(Japanese)\n";
		$langaugesNavi .= "			</span>\n";
		$langaugesNavi .= "		</li>\n";
	}else{
	
		$metaTagDataJa = metaTagTitle($addressDataArray,1,"current");
	
		$langaugesNavi .= "		<li>\n";
		$langaugesNavi .= "			<a href=\"".linkLevel($addressDataArray[0]).$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" target=\"_top\" title=\"".$metaTagDataJa."\">\n";
		$langaugesNavi .= "				日本語(Japanese)\n";
		$langaugesNavi .= "			</a>\n";
		$langaugesNavi .= "		</li>\n";
	}
	//英語
	if($langCode_en){
		if($langCode == "en"){
			$langaugesNavi .= "		<li class=\"langaugesSelected\">\n";
			$langaugesNavi .= "			<span class=\"langaugesSelectedSpan\">\n";
			$langaugesNavi .= "				English\n";
			$langaugesNavi .= "			 </span>\n";
			$langaugesNavi .= "		</li>\n";
		}else{	

			$metaTagDataEn = metaTagTitle($addressDataArray,2,"current");

			$langaugesNavi .= "		<li>\n";
			$langaugesNavi .= "			<a href=\"".linkLevel($addressDataArray[0])."en/".$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" target=\"_top\" title=\"".$metaTagDataEn."\">\n";
			$langaugesNavi .= "				English\n";
			$langaugesNavi .= "			</a>\n";
			$langaugesNavi .= "		</li>\n";
		}
	}
	//ロシア語
	if($langCode_ru){
		if($langCode == "ru"){
			$langaugesNavi .= "		<li class=\"langaugesSelected\">\n";
			$langaugesNavi .= "			<span class=\"langaugesSelectedSpan\">\n";
			$langaugesNavi .= "				Русский язык(Russian)\n";
			$langaugesNavi .= " 		</span>\n";
			$langaugesNavi .= "		</li>\n";
		}else{	

			$metaTagDataRu = metaTagTitle($addressDataArray,3,"current");

			$langaugesNavi .= "		<li>\n";
			$langaugesNavi .= "			<a href=\"".linkLevel($addressDataArray[0])."ru/".$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" target=\"_top\" title=\"".$metaTagDataRu."\">\n";
			$langaugesNavi .= "				Русский язык(Russian)\n";
			$langaugesNavi .= "			</a>\n";
			$langaugesNavi .= "		</li>\n";
		}
	}
	$langaugesNavi .= "	</ul>\n";
	//$langaugesNavi .= "</div>\n";
	$langaugesNavi  .= "</nav>\n";

	return $langaugesNavi;

}

/*
 * 多言語設定されたページのヘッダ情報
 * 
 * @param array　$addressDataArray　ページアドレス情報の配列
 * @retuen string 多言語ヘッダ情報をlinkタグで返す。
 * 
* @date 2017/03/23
 * @since 2017/03/22
 * @version 1.1
 * 
*/
function langaugesNaviHeadLink($addressDataArray){

	//アーカイブスカテゴリディレクトリ
	global $archivesCategoryDirectory;
	//コンテンツアイテムID
	global $contentsItemId;
	//ドメインアドレス
	global $domainAddress;
	//ファイル拡張子を取得する
	global $fileExtension;
	//言語コード
	global $langCode;
	//言語ID
	global $langId;
	//本番サーバかテストサーバかの値を取得する。
	global $productionServer;

	//ファイル名の初期設定
	$filenameString = "";
	//外国語設定
	$langCode_en = true;
	$langCode_ru = true;
	//GET一覧の値
	$listCountNow = listCountNow();

	
	//一覧ではない、または一覧(list)の初期値が0のとき
	if($listCountNow == 0){
		//ファイル名がindexではない場合
		if($addressDataArray[1] != "index"){
			//ファイル名を拡張子つきで生成する
			$filenameString = $addressDataArray[1].$fileExtension;
		}
	}else{

		//本番サーバの場合とテストサーバの場合での動作
		if($productionServer){
			$filenameString = $addressDataArray[1].$listCountNow.$fileExtension;
		}else{
			$filenameString = $addressDataArray[1].$fileExtension."?list=".$listCountNow;
		}
	}
	//コンテンツアイテムの外国語版が登録されているか否かを判定する。
	//コンテンツアイテムのページを表示している場合
	if($contentsItemId != 0){
	
		$contentsitemfLangListArray = selectContentsitemfLangList($contentsItemId);
		//検索結果が存在する場合
		if(isset($contentsitemfLangListArray)){
			//英語のページが登録されているか
			if(!isset($contentsitemfLangListArray[2])){
				$langCode_en = false;
			}
			//ロシア語のページが登録されているか
			if(!isset($contentsitemfLangListArray[3])){
				$langCode_ru = false;
			}
		}else{
			$langCode_en = false;
			$langCode_ru = false;
		}
	}

	$langaugesNaviHeadLink  = "";
	$langaugesNaviHeadLink .= "<link rel=\"alternate\" href=\"".$domainAddress.$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" hreflang=\"x-default\">\n";
	//$langaugesNaviHeadLink .= "<link rel=\"canonical\" href=\"".$domainAddress.$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\">\n";
	//$langaugesNaviHeadLink .= "<link rel=\"canonical\" href=\"".$domainAddress.$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" hreflang=\"ja\">\n";

	//言語ごとに選択する。
	//日本語
	$langaugesNaviHeadLink .= "<link rel=\"alternate\" href=\"".$domainAddress.$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" hreflang=\"ja\">\n";
	//英語
	if($langCode_en){
		$langaugesNaviHeadLink .= "<link rel=\"alternate\" href=\"".$domainAddress."en/".$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" hreflang=\"en\">\n";
	}
	//ロシア語
	if($langCode_ru){
			$langaugesNaviHeadLink .= "<link rel=\"alternate\" href=\"".$domainAddress."ru/".$archivesCategoryDirectory.langdirectoryNameAddress($addressDataArray).$filenameString."\" hreflang=\"ru\">\n";
	}

	return $langaugesNaviHeadLink;
}

?>