<?
use Bitrix\Highloadblock as HL;
IncludeModuleLangFile(__FILE__, "ru");
CModule::IncludeModule('highloadblock');

$hlblock = HL\HighloadBlockTable::getById(COption::GetOptionString("pavelbabich.telegraph", "HL_ID"))->fetch();
$entity = HL\HighloadBlockTable::compileEntity( $hlblock );
$entityClass = $entity->getDataClass();

class PTELEGRAPHModuleMain extends \TelegraphTable{
    
    public static function getEntity()
    {
        return static::$entity['\\TelegraphTable'];
    }
    
    public static function GetTelegraphElement($CODE){
        $rsData = static::getList(array(
           'select' => array('*'),
           'filter' => array("UF_ACTIVE" => 1, "UF_CODE"=>$CODE)
        ));
        return $rsData->Fetch();
    }
    
    public static function GetTelegraphList($newsCount, $sortBy, $sortOrder){
        $rsData = static::getList(array(
           'select' => array('*'),
           'filter' => array("UF_ACTIVE" => 1)
        ));
        while($data = $rsData->Fetch()){
            $arData["ITEMS"][] = $data;
        }
        return $arData;
    }
    
    public static function UpdateTelegraphElement($ID, $arFields){
        foreach($arFields as &$field) $field = htmlspecialchars($field);
        if(static::update($ID, $arFields)){
            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag("HL_TLPH");
            $CACHE_MANAGER->ClearByTag("HL_TELEMENT_".$ID);
            return true;
        }else return false;
    }
    
    public static function SaveTelegraphElement($arFields){
        foreach($arFields as &$field) $field = htmlspecialchars($field);
        $arFields["UF_CODE"] = Cutil::translit($arFields["UF_AUTHOR_NAME"],"ru");
        $arFields["UF_ACTIVE"] = 1;
        $arFields["UF_CREATE_DATE"] = date("d.m.Y H:i:s");
        $result = static::add($arFields);
        if($result->isSuccess()){
            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag("HL_TLPH");
            return $arFields["UF_CODE"];
        }else{
            //implode(', ', $result->getErrors())
            return false;
        }
    }
    
    public static function TelegraphBeforeUpdate(\Bitrix\Main\Entity\Event $event)
    {
        global $CACHE_MANAGER;
        $param = $event->getParameters();
        $CACHE_MANAGER->ClearByTag("HL_TELEMENT_".$param["id"]["ID"]);
    }
}
?>