<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule('pavelbabich.telegraph');
$HL = new PTELEGRAPHModuleMain;
$arResult = $HL->GetTelegraphElement($arParams["ELEMENT_CODE"]);
$folder = COption::GetOptionString("pavelbabich.telegraph", "FOLDER");

if($arResult){
    $password = htmlspecialchars($_REQUEST["password"]);
    if(!$password)$arResult["errors"] = "Требуется ввести пароль!";
    elseif($password!=$arResult["UF_PASSWORD"])$arResult["errors"] = "Не верный пароль!";
    else $arResult["access"] = true;
    
    if($arResult["access"] && ($_REQUEST["save"] || $_REQUEST["del"]=="Y")){
        if($_REQUEST["del"]=="Y")$arFields = array("UF_ACTIVE"=>0);
            else $arFields = $_REQUEST["fields"];
        if($HL->UpdateTelegraphElement($arResult["ID"], $arFields)) LocalRedirect("/".$folder."/");
            else $arResult["errors"] = "Ошибка сохранения!";
    }
    
    $this->includeComponentTemplate($componentPage);
}else{
    echo "<b style='color:red'>Элемент не найден!</b>";
}
?>