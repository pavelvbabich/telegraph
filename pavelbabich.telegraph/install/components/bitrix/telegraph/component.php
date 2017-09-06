<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arVariables = array();
$arDefaultUrlTemplates404 = Array(
        "news" => "",
        "add" => "new/",
        "detail" => "#ELEMENT_CODE#/",
        "edit" => "#ELEMENT_CODE#/edit/"
    );
$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404,
    $arParams["SEF_URL_TEMPLATES"]);

$componentPage = CComponentEngine::ParseComponentPath(
    $arParams["SEF_FOLDER"],
    $arUrlTemplates,
    $arVariables
);
if(!$componentPage)$componentPage = "news";

$arResult = array(
    "URL_VARIABLES" => $arVariables,
    "URL_TEMPLATES" => $arUrlTemplates
);
$this->includeComponentTemplate($componentPage);
?>