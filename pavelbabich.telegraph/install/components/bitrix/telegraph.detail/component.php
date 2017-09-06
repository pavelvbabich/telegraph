<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$CACHE_ID = md5(SITE_ID."||".$APPLICATION->GetCurPage()."|".serialize($arParams));
$cache = new CPHPCache;
if ($cache->InitCache(36000000, $CACHE_ID, $APPLICATION->GetCurPage())){
   $arResult = $cache->GetVars();
}elseif($cache->StartDataCache()){
    global $CACHE_MANAGER;
    $CACHE_MANAGER->StartTagCache($APPLICATION->GetCurPage());
    
    CModule::IncludeModule('pavelbabich.telegraph');
    $HL = new PTELEGRAPHModuleMain;
    $arResult = $HL->GetTelegraphElement($arParams["ELEMENT_CODE"]);
    
    if($arResult["ID"]){
        $CACHE_MANAGER->RegisterTag("HL_TELEMENT_".$arResult["ID"]);
        $CACHE_MANAGER->EndTagCache();
        $cache->EndDataCache($arResult);
    }
}

$this->includeComponentTemplate($componentPage);
?>