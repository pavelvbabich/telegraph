<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("telegraph");
?>
<?$APPLICATION->IncludeComponent("bitrix:telegraph","", Array(
    "HL_IBLOCK_ID" => COption::GetOptionString("pavelbabich.telegraph", "HL_ID"),
    "NEWS_COUNT" => "20",
    "SORT_BY" => "ACTIVE_FROM",
    "SORT_ORDER" => "DESC",
    "SEF_FOLDER" => "/telegraph/",
    "SEF_URL_TEMPLATES" => Array(
        "news" => "",
        "add" => "new/",
        "detail" => "#ELEMENT_CODE#/",
        "edit" => "#ELEMENT_CODE#/edit/"
    ),
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>