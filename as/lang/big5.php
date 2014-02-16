<?php
/**
 * 中文(繁体) language file.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
	define('DASHBOARD','控制台');
	define('CATEGORY','分類');
	define('POST','文章');
	define('LISTS','列表');
	define('CREATE','新建');
	define('COMMENT','評論');
	define('SETTING','設置');
	define('AD','廣告管理');
	define('LINKS','鏈接管理');
	define('LINK_TIP','友情鏈接');
	define('TRACK','流量分析');
	define('TIP_VIEWTRACK','點擊日期以顯示圖表.');
	define('THEME','樣式');
	define('DATABACKUP','數據備份');
	define('DATAIMPORT','數據導入');
	define('DATAIMPORT_TIPS','您確認將網站數據庫替換為此數據版本?');
	define('DATA','數據');
	define('DATACONVERTER','數據轉換');
	define('DATAOPTIMIZER','數據庫優化');
	define('DATABASE_CONVERT_SUCCESSFULLY','數據轉換完成!');
	define('NEED_FORM_DATA','請填寫下面的表單');
	define('DATAIMPORT_TIP1',' 已經導入到您的數據庫,也許這裡會有一些提示信息');
	define('DATAIMPORT_TIP2','請選擇數據文件導入或者保存到本地.');
	define('DATABASE_BAKUP_OK','您的數據庫備份完成!');
	define('SQLITEBACKUP_TIP','您當前的數據是保存在SQLITE數據庫,不需要此操作!');
	define('DATABASE_BACKUP_TIP','請選擇數據表以進行備份,當前使用的數據庫是');
	define('DATABASE_CONVERTER_TIP','請選擇數據表以進行轉換,當前使用的數據庫是');
	define('DATABASE_OPTIMIZE_TIP',' 請選中數據表以進行優化,當前的數據庫是');
	define('HOME','首頁');
	define('LOGOUT','退出');
	define('ADMIN_ACCOUNT','帳號');
	define('LOGIN_TIPS','請登錄');
	define('ADMIN_PASSWORD','密碼');
	define('LOGIN','登錄');
	define('CAT_NAME','名稱');
	define('CAT_NAME_TIPS','分類名稱,在頁面中向分類頁鏈接的文字');
	define('KEYWORD','頁面Keywords');
	define('KEYWORD_TIP','顯示在網頁KEYWORDS標籤中');
	define('DESCRIPTION','頁面Description');
	define('DESCRIPTION_TIP','顯示在頁面DESCRIPTION標籤中');
	define('SLUG','鏈接字');
	define('SLUG_TIPS','英文,數據及-組成,用於生成分類鏈接');
	define('TITLE','頁面Title');
	define('TITLE_TIP','頁面TITLE,顯示在頁面的TITLE標籤中');
	define('TOP_WORD','短說明');
	define('TOP_WORD_TIPS','顯示在頁面最上邊LOGO右側');
	define('DONE','完成');
	define('BACK','回退');
	define('NO','編號');
	define('ADMIN','管理');
	define('BODY','內容');
	define('TIMES','時間');
	define('DELETE_TIP','刪除');
	define('DELETE_CONFIRM','真的要刪除它嗎?');
	define('REPLY','回复');
	define('SYSTEM_INFORMATION','<strong>%s</strong>的系統信息');
	define('SITE_STATUS','網站狀態: <strong>%s</strong>');
	define('DATABASE_STATUS','數據庫 <strong>%s</strong> <strong>%s</strong> <strong>%s</strong>');
	define('CONNECTED','連接成功');
	define('DNS_CONNECTED','未連接');
	define('SQLITE_DRIVER','SQLite驅動');
	define('TOTAL','總共');
	define('URL_REWRITE_TIP','URL重寫');
	define('OPEN','打開');
	define('CLOSE_TIP','關閉');
	define('OPEN_TIPS','您可以通過<a href="./?type=setting">站點設置</a>修改');
	define('CREAT_CAT_TIPS','點擊<a href="./?type=category&mode=insert">這裡</a>創建分類');
	define('CREAT_POST_TIPS','點擊<a href="./?type=post&mode=insert">這裡</a>發布文章');
	define('DEFAULT_TIP','默認');
	define('CHANGE_THEME_TIP','您可以通過<a href="./?type=setting">站點設置</a>選擇網站模板');
	define('OPTION_POST','發布選項');
	define('PUBLISH','發布');
	define('ALLOW_COMMENT','允許留言');
	define('ATTACHMENT','附件');
	define('FILENAME','文件名');
	define('UPLOAD_TIME','上傳時間');
	define('TAG','標籤');
	define('TAG_TIP','用逗號隔開');
	define('SLUG_POST_TIP','由英文,數字及-_組成,生成文章的鏈接,如果為空則係統會自動分配一個唯一的編號');
	define('MODIFY','修改');
	define('SITE_NAME','站點名稱');
	define('WEBMASTER','站長');
	define('DATABASE','數據庫');
	define('DATABASE_HOST','數據庫[Mysql]地址');
	define('DATABASE_HOST_TIP','通常為localhost');
	define('DATA_PORT','數據庫連接端口號');
	define('DATA_ACCOUNT','數據庫帳號');
	define('DATA_PASSWORD','數據庫密碼');
	define('DATA_NAME','數據庫名稱');
	define('DATA_PREFIX','數據表前綴');
	define('SITE_CLOSE_TIP','網站關閉提示');
	define('NEED_SERVER_SUPPORT','需要服務器支持');
	define('SITE_CLOSE_TIPS','網站升級或關閉時顯示以提示用戶.');
	define('SITE_CLOSE','關閉網站');
	define('EMAIL','郵箱');
	define('CURRENT_THEME','當前您選擇的模板是');
	define('THEME_CREAT_TIP','製作模板非常簡單,可查看案例模板_themes/mobile下的theme.config文件,按照其中的格式增加或者修改模板文件代碼,系統會自動匹配對應頁面的模板');
	define('THEME_DONE_TIP','編輯文件有風險,建議修改前先備份');
	define('COMMENT_USER','留言者信息');
	define('INSTALL_INFORMATION','歡迎選擇SweetRice,看到這個頁面說明您還沒有安裝完成本系統.<br />希望SweetRice安裝程序可以幫您完成安裝');
	define('INC_ISWRITE','inc目錄不可寫,請將其設置為777可讀寫');
	define('ROOT_ISWRITE','根目錄不可寫,請將其設置為777可讀寫');
	define('LIBDIR_ISWRITE','as/lib 不可寫,請將其設置為777可讀寫.');
	define('ATTACHMENT_ISWRITE','attachment目錄不可寫,請將其設置為777可讀寫');
	define('INSTALL_PERMISION','請正確設置上述目錄權限,否則安裝或使用中會遇到困難.');
	define('DB_ERROR','數據庫連接有誤!');
	define('AUTH','作者');
	define('ACCEPT','同意');
	define('NOTACCEPT','不同意');
	define('CREAT_LINK_TIP','編輯鏈接內容.');
	define('CREAT_AD_TIP','您可以在這裡編輯廣告代碼然後引用到模板中, 也可以點<a href="./?type=theme">這裡</a>直接編輯模板.');
	define('ADS_NAME','廣告名稱');
	define('ADS_CODE','廣告代碼');
	define('UPLOAD','上傳');
	define('UPLOAD_FILESIZE_TIP','上傳文件的大小受服務器設置的限制');
	define('ADD_FILE','增加文件');
	define('REMOVE_FILE','去除文件');
	define('DOWNLOAD_TIMES','下載次數');
	define('NO_CATEGORY_TIP','如果不選擇分類,則文章URL不帶分類目錄並且後綴是.html');
	define('CHECK','檢查');
	define('TIPS_UPDATE','請更新您的系統到最新版本.重要提示:在執行升級前請<a href="./?type=data&mode=db_backup">備份數據庫</a>及數據文件.');
	define('RELEASED','發布');
	define('LANG','語言');
	define('REPLACE_TIP','替換');
	define('PLUGIN','插件');
	define('NAME','名稱');
	define('INSTALL','安裝');
	define('DEINSTALL','卸載');
	define('VERSION','版本');
	define('PLUGIN_DESCRIPTION','插件註釋');
	define('AUTHOR','作者');
	define('CONTACT','聯繫');
	define('HOME_PAGE','主頁');
	define('PARENT','父分類');	
	define('PASSWORD','密碼');
	define('REPEAT_PASSWORD','確認密碼');
	define('TIP_RESET_PASSWORD','請重新設置您的密碼');
	define('FORGOT_PASSWORD','忘記密碼');
	define('TIP_INPUT_PASSWORD','請輸入您的管理郵箱');
	define('TIP_VISIT_EMAIL','請查看您的郵件以修改密碼');
	define('TIP_WRONG_EMAIL','錯誤的郵箱');
	define('MAIL_TEXT_RESET_PASSWORD','
Hi,%s :
請點擊下面的鏈接以修改您管理SweetRice系統的密碼
%s%s/?type=password&mode=re&r=%s .
如果這不是你請求的修改,直接刪除此郵件即可.



祝愉快!
SweetRice
	');
	define('MAIL_HTML_RESET_PASSWORD','
Hi,%s :<br />
請點擊下面的鏈接以修改您管理SweetRice系統的密碼<br />
<a href="%s%s/?type=password&mode=re&r=%s ">%s%s/?type=password&mode=re&r=%s </a>.<br />
如果這不是你請求的修改,直接刪除此郵件即可.<br />
<br />
<br />
<br />
祝愉快!<br />
SweetRice網站管理系統
	');
	define('TIP_WRONG_SECRET_CODE','錯誤的安全碼.');
	define('TIP_RESET_PASSWORD_OK','您的密碼修改成功');
	define('MAIL_TEXT_NOTICE_RESET_PASSWORD_OK','
Hi,%s :
您在SweetRice系統中的管理密碼修改成功,您可以登錄SweetRice後台管理您的網站.
%s%s/


祝愉快!
SweetRice網站管理系統
	');
	define('MAIL_HTML_NOTICE_RESET_PASSWORD_OK','
Hi,%s :<br />
您在SweetRice系統中的管理密碼修改成功,您可以登錄SweetRice後台管理您的網站.<br />
<a href="%s%s/">%s%s/</a><br />
<br />
<br />
祝愉快!<br />
SweetRice網站管理系統
	');
	define('TIP_INVALID_PASSWORD','無效的密碼.');
	define('TIP_INVALID_RESET_PASSWORD','無效的密碼或郵箱.');
	define('DASHBOARD_DIRECTORY','控制台目錄');
	define('DASHBOARD_DIRECTORY_TIP','修改控制台目錄');
	define('USER_TRACK','用戶分析');
	define('MAX_UPLOAD_FILE_TIP','最大可上傳文件大小');
	define('MEDIA_CENTER','媒體中心');
	define('MEDIA_DELETE_TIP',' 已經刪除成功!');
	define('MEDIA_NOEXISTS_TIP',' 文件(夾)不存在或者為空.');
	define('MEDIA',' 文件(夾)');
	define('DIRECTORY','目錄');
	define('FILE_TYPE','文檔類型');
	define('DATE_TIP','日期');
	define('FILE_SIZE','文件大小');
	define('CACHE','緩存');
	define('FULL','全部');
	define('EXPIRED','过期');
	define('S',' 秒 0為永不過期');
	define('CACHE_TIPS','啟用數據緩存,通常會節省數據庫資源讀取時間.');
	define('CLEAN','清理');
	define('CACHE_CLEAR_SUCCESSFULLY','緩存已經清除完成.');
	define('FILES','文件(夹)');
	define('QUOTE_CODE','Quote Code');
	define('TEMPLATE','模板');
	define('SEARCH','搜索');
	define('CREATE_ANOTHER_ONE','創建另一個?');
	define('ENLARGE','在窗口中查看圖片');
	define('SAVE','保存');
	define('ACTION','動作');
	define('BULK','批量');
	define('UPDATE','更新');
	define('META','Meta');
	define('PREVIEW','預覽');
	define('GENERAL','全局');
	define('PERMALINKS','永久鏈接');
	define('RSSFEED','RSS訂閱');
	define('SITEMAP','網站地圖');
	define('CHART','圖表');
	define('HTACCESS_TITLE','此設置只對 Apache 服務器有效');
	define('HTACCESS_TIPS','提示:請不要修改"RewriteBase%--%",它將被自動替換成真實路徑.');
	define('EDIT','編輯');
	define('NO_RECORD_SELECTED','未選中記錄');
	define('NO_PACTION_SELECTED','未選中批量操作的動作');
	define('CURRENT_VERSION','當前版本');
	define('LASTEST_VERSION','最新版本');
	define('AUTOMATICALLY','自動');
	define('MANUALLY','手動');
	define('ALL','全部');
	define('UPDATE_FILES_TIP','這些文件(夾)將被更新');
	define('EXTRACT_TIP','解壓縮');
	define('SUCCESSFULLY','完成');
	define('FAILED','失敗');
	define('TEMPORARY','臨時');
	define('UPGRADE','升級');
	define('DOWNLOAD','下載');
	define('UPDATE_FAILED_CONNECT_SERVER','更新失敗- 不能連接更新服務器.');
	define('Updating SweetRice','升級 SweetRice');
	define('ABORTED','中止');
	define('DB_UPGRADE_TIP','您可以稍後手動升級數據庫,更多信息請查看SweetRice根目錄下的upgrade_db.php');
	define('NEXT_STEP','下一步');
	define('URL_REDIRECT','URL 重定向');
	define('URL_REDIRECT_TITLE','在這裡設置301重定向 - 此功能只適應於URL重寫功能啟用的情況');
	define('URL_REDIRECT_TIPS','<p>輸入規則:請注意源地址不要包含"http://"和您的域名.</p>示例:<ol><li>輸入<strong>source.html->destination.html</strong>則重定向http://yourdomain.com/source.html 到 http://yourdomain.com/destination.html</li><li>輸入<strong>source.html->http://otherdomain.com/destination.html</strong> 重定向http://yourdomain.com/source.html 到http://otherdomain.com/destination.html< /li><li>支持正則規則,示例: <strong>/^page\/([a-z0-9]+)\.html$/i->action=post&sys_name=$1</strong> 解析網址, <strong>/^page\/([a-z0-9]+)\.html$/i->$1.html</strong> 重定向網址.</li></ol>');
	define('VISITED_PAGE','訪問的頁面');
	define('REFERRER_PAGE','訪問來源');
	define('IP','IP地址');
	define('REMEMBER_ME','記住登錄狀態');
	define('TOP_VISITED_PAGE','前10 共%total_pages% 被瀏覽的頁面');
	define('TOP_REFERRER_PAGE','前10 共%total_froms% 引用頁面');
	define('TOP_IP','前10 共%total_ips% ip地址');
	define('SERVER_TIME','服務器時間 : %s');
	define('UPGRADE_SR_SUCCESSFULLY','升級SweetRice到%s版本成功');
	define('DOWNLOAD_SR_SUCCESSFULLY','下載SweetRice_core.zip(文件大小:%s)成功');
	define('DATABASE_UPGRADE_FAILED','數據庫升級失敗.<br />這裡可能有一些錯誤提示:<br />%s');
	define('DATABASE_UPGRADE_SUCCESSFULLY','數據庫升級成功.');
	define('CURRENT_VERSION_TIP','當前版本號:%s');
	define('LASTEST_VERSION_TIP','最新版本號:%s');
	define('EXTRACT_SR_SUCCESSFULLY','解壓縮SweetRice成功');
	define('EXTRACT_SR_FAILED','解壓縮SweetRice失敗');
	define('UPDATE_SR_FILE_FAILED','更新SweetRice文件失敗');
	define('UPDATE_SR_FILE_SUCCESSFULLY','更新SweetRice文件成功');
	define('CLEAN_TEMPORARY_FILES_SUCCESSFULLY','清理臨時文件成功');
	define('CLEAN_TEMPORARY_FILES_FAILED','清理臨時文件失敗');
	define('CHECK_UPDATE','檢查更新');
	define('SR_INSTALLER','SweetRice 安裝');
	define('REMOTE_FILE','遠程文件');
	define('ATTACH_FILE','附加文件');
	define('NEW_DIRECTORY','新目錄');
	define('LOADING','加載...');
	define('OPTIMIZE_SUCCESSFULLY','優化成功');
	define('OPTIMIZE_DOES_FAILED','優化失敗');
	define('SYSTEM_SETTING','系統設置');
	define('WEB_SETTING','網站設置');
	define('UNCATEGORY','未分類');
	define('TIME_ZONE','時區');
	define('ADMIN_EMAIL','管理員郵箱');
	define('CHOOSE_TIME_ZONE','選擇時區');
	define('REQUEST','請求');
	define('SYSTEM','系統');
	define('CUSTOM','自定義');
	define('HIDDEN','不顯示');
	define('SITES','站點');
	define('SITE_LIST','站點列表');
	define('SITES_MANAGEMENT','站點管理');
	define('SITE_ATTACHMENT_DIR','站點附件目錄');
	define('HOST','主機');
	define('SITE_CONFIGURATION','站點配置');
	define('PLUGIN_INSTALLED','%s已經安裝成功.');
	define('PLUGIN_DEINSTALLED','%s已經反安裝成功.');
	define('DATE_FORMAT','m月d日 Y年 H:i');
	define('ALERT_REDIRECT_TIP','您將在3秒鐘內被重定向到新頁面.');
	define('ADD_PARAMETER','增加參數');
	define('REMOVE_PARAMETER','移除參數');
	define('CUSTOM_URL_TIP','不需要帶域名,只需要輸入目錄或頁面名稱,例如: /custom_dir/custom_page.html /custom_dir/custom_page/ 之類.');
	define('DELETE_SUCCESSFULLY','%s (%s) 刪除成功.');
	define('DB_BACKUP_DONT_EXISTS','數據庫備份文件不存在.');
	define('URL_UPDATE_SUCCESSFULLY','鏈接更新完成.');
	define('URL_UPDATE_FAILED','鏈接更新失敗.');
	define('SYSTEM_TIP','系統');
	define('PLUGIN_LIST','插件列表');
	define('PLUGIN_EXISTS','插件已經存在.');
	define('INVALID_PLUGIN','無效的插件- 丟失插件名稱');
	define('LOGIN_SUCCESS','登錄成功');
	define('LOGIN_FAILED','登錄失敗');
	define('EXAMPLE_LINK','示例鏈接');
	define('DO_NOT_CHANGE','不修改');
	define('ORIGINAL_URL','原始鏈接');
	define('IS_INDEX','已設為主頁');
	define('HOMEPAGE','設為主頁');
	define('CANCEL','取消');
	define('URL_PARSE','URL解析');
	define('ADD_URL_RULE','增加URL規則');
	define('SQL_EXECUTE','SQL操作');
	define('SQL_EXECUTE_TIP','輸入SQL並執行,請在此操作前備份數據庫.');
	define('SQL_CONTENT','SQL內容');
	define('SQL_EXECUTE_SUCCESS','SQL操作完成');
	define('NUMS_SETTING','數量設置');
	define('NS_POST_CATEGORIES','分類下顯示的文章');
	define('NS_POST_UNCATEGORIES','未分類下顯示的文章數量');
	define('NS_TAGS','在標籤雲中最多顯示的標籤數量');
	define('NS_POST_CATEGORY','分類頁中默認的文章數量');
	define('NS_POST_HOME','主頁中默認的文章數量');
	define('NS_POST_TAG','標籤頁面默認文章數量');
	define('NS_POST_RELATED','相關文章數量');
	define('NS_POST_PINS','在瀑布流模式下加載的文章數量');
	define('NS_RSSFEED','在Rssfeed下最多顯示的文章數量');
	define('NS_COMMENT_LIST','評論頁面默認顯示的評論數量');
	define('NS_COMMENT_PINS','瀑布流模式下加載的評論數量');
	define('TEMPLATE_HISTORY','模板歷史');
	define('CLEAN_BACKUP','清除備份');
	define('VISUAL','可視化');
	define('HTML','源代碼');
	define('HEADER_304','開啟304頭信息');
	define('HEADER_304_TIP','如果頁面沒有更新則輸出304信息,開啟此項功能將有效的改善服務器壓力');
	define('CUSTOM_FIELD_TIP','一個名稱對應一個值,可能通過方法get_custom_field 列出此數據,如果選擇"保存到參數列表",新建的項目表單中將顯示此參數');
	define('SAVE_TO_LIST','保存到參數列表');
	define('CUSTOM_FIELD','自定義參數');
	define('ADD_CUSTOM_FIELD','增加自定義參數');
	define('TEXT','文本');
	define('SINGLE','單選');
?>