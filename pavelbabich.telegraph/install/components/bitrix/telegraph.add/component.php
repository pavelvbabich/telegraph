<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule('pavelbabich.telegraph');
$HL = new PTELEGRAPHModuleMain;
$folder = COption::GetOptionString("pavelbabich.telegraph", "FOLDER");

if($_REQUEST["save"]){
    $arFields = $_REQUEST["fields"];
    $CODE = $HL->SaveTelegraphElement($arFields);
    if($CODE){
        LocalRedirect("/".$folder."/".$CODE."/");
    }else{
        $arResult["errors"] = "Ошибка сохранения!";
    }
}
    
$this->includeComponentTemplate($componentPage);
?>