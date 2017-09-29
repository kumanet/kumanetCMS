/*!
 * ku-ma.net CMS 
 *
 * 各ドメイン共通設定のjavascript。
 * パソコン、スマートフォン共通
 * <br>
 * レスポンシブwebの定義
 * <br>
 * 
 *
 * @author Nobuyuki Kumakura
 * @since 2014/10/29
 * @version 1.2
 * @date 2017/05/28
 * 
 * copyright Nobuyuki Kumakura
 */


/*!
 * jqueryクッキーの設定
 * 
 * @since 2014/11/2
 * @version 1.0 
 */
$(function(){ 

	//パソコンがクリックされたときの動きとクッキーの設定
    $('#selectPC').on('click',function(){ 
        $.cookie('viewMode','PC',{path:'/'}); 
        location.reload(); 
        return false; 
    }); 

	//スマートフォンがクリックされたときの動きとクッキーの設定
    $('#selectSMP').on('click',function(){ 
        $.cookie('viewMode','SMP',{path:'/'}); 
        location.reload(); 
        return false; 
    }); 

}); 

/*!
 * jqueryアコーディオンメニューの設定
 * 
 * @since 2017/05/28
 * @version 1.1
 */
$(function(){ 

	//メニューバーがクリックされたときの動き
	$('.menubarSmp').on('click',function(){ 
		$(this).next().slideToggle();
		$(this).toggleClass('selected');
	}); 

	//言語設定バーがクリックされたときの動き
	$('.langaugesSmp').on('click',function(){ 
		$(this).next().slideToggle();
		$(this).toggleClass('selected');
	});

	//メニューバーinfo設定バーがクリックされたときの動き
	$('.menubarInfoSmp').on('click',function(){ 
		$(this).next().slideToggle(),
		$(this).next().css('font-size','100%');
		$(this).toggleClass('selected');
		//$(this).next().css('font-size','');
	});

}); 
