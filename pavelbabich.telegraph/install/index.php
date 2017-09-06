<?
use Bitrix\Main\EventManager;
use Bitrix\Highloadblock as HL;
IncludeModuleLangFile(__FILE__, 'ru');
if(class_exists("pavelbabich_telegraph")) return;

Class pavelbabich_telegraph extends CModule
{
	var $MODULE_ID = "pavelbabich.telegraph";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;

	function pavelbabich_telegraph()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __DIR__);
		include($path . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = GetMessage("PTELEGRAPH_MAIN_MODULE_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("PTELEGRAPH_MAIN_MODULE_INSTALL_DESCR");
		$this->PARTNER_NAME = "Pavel Babich";
		$this->PARTNER_URI = "https://vk.com/skif_p";
	}

	function DoInstall()
	{
        global $DB, $APPLICATION, $step;
        
        $step = IntVal($step);
        
        if($step<2 && !$_REQUEST["public_dir"])$APPLICATION->IncludeAdminFile(GetMessage("PTELEGRAPH_INSTALL_TITLE"),
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.telegraph/install/step1.php");
        elseif($step==2){
            CModule::IncludeModule("iblock");
            CModule::IncludeModule('highloadblock');
            $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::Add(array(
                'NAME' => 'Telegraph',
                'TABLE_NAME' => 'telegraph',
            ));

            if ($hlblock->isSuccess()){
                $ID = $hlblock->getId();
                $oUserTypeEntity    = new CUserTypeEntity();
                $arTFields = array(
                    0 => array(
                        "CODE" => "AUTHOR_NAME",
                        "TYPE" => "string",
                        "NAME" => array(
                            "ru"    => "Имя автора",
                            "en"    => "Author name",
                            )
                        ),
                    1 => array(
                        "CODE" => "CREATE_DATE",
                        "TYPE" => "datetime",
                        "NAME" => array(
                            "ru"    => "Дата создания",
                            "en"    => "Create date",
                            )
                        ),
                    2 => array(
                        "CODE" => "BODY",
                        "TYPE" => "string",
                        "NAME" => array(
                            "ru"    => "Тело публикации",
                            "en"    => "Body",
                            )
                        ),
                    3 => array(
                        "CODE" => "BODY",
                        "TYPE" => "string",
                        "NAME" => array(
                            "ru"    => "Тело публикации",
                            "en"    => "Body",
                            )
                        ),
                    4 => array(
                        "CODE" => "CODE",
                        "TYPE" => "string",
                        "NAME" => array(
                            "ru"    => "Символьный код",
                            "en"    => "Code",
                            )
                        ),
                    5 => array(
                        "CODE" => "PASSWORD",
                        "TYPE" => "string",
                        "NAME" => array(
                            "ru"    => "Пароль",
                            "en"    => "Password",
                            )
                        ),
                    6 => array(
                        "CODE" => "ACTIVE",
                        "TYPE" => "boolean",
                        "NAME" => array(
                            "ru"    => "Активность",
                            "en"    => "Active",
                            )
                        )
                );
                foreach($arTFields as $TField){
                    $aUserFields    = array(
                        'ENTITY_ID'         => 'HLBLOCK_'.$ID,
                        'FIELD_NAME'        => 'UF_'.$TField["CODE"],
                        'USER_TYPE_ID'      => $TField["TYPE"],
                        'MULTIPLE'          => 'N',
                        'MANDATORY'         => 'N',
                        'SHOW_FILTER'       => 'I',
                        'EDIT_FORM_LABEL'   => $TField["NAME"]
                    );
                    $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
                }
            }
            
            COption::SetOptionString("pavelbabich.telegraph", "FOLDER", $_REQUEST["public_dir"]);
            COption::SetOptionString("pavelbabich.telegraph", "HL_ID", $ID);
            
            CopyDirFiles(__DIR__ ."/components/bitrix/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrix/", true, true);
            CopyDirFiles(__DIR__ ."/files/telegraph/", $_SERVER["DOCUMENT_ROOT"]."/".$_REQUEST["public_dir"]."/", true, true);
            
            CUrlRewriter::Add(array(
                  "SITE_ID" => "s1",
                  "CONDITION" => "#^/".$_REQUEST["public_dir"]."/#",
                  "ID" => "bitrix:telegraph",
                  "PATH" => "/".$_REQUEST["public_dir"]."/index.php",
                  "RULE" => ""
               )
            );
            
            RegisterModule($this->MODULE_ID);
                        
            /*
            EventManager::getInstance()->registerEventHandler(
                "",
                "TelegraphOnBeforeUpdate",
                $this->MODULE_ID,
                'pavelbabich\\telegraph\\PTELEGRAPHModuleMain',
                "TelegraphBeforeUpdate"
            );
            */
        
            $APPLICATION->IncludeAdminFile(GetMessage("PTELEGRAPH_INSTALL_TITLE"),
                    $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.telegraph/install/step2.php");
        }
	}

	function DoUninstall()
	{
        CModule::IncludeModule('highloadblock');
        $eventManager = \Bitrix\Main\EventManager::getInstance(); 
        $eventManager->unRegisterEventHandler(
                "",
                "TelegraphOnBeforeUpdate",
                $this->MODULE_ID,
                'pavelbabich\\telegraph\\PTELEGRAPHModuleMain',
                'TelegraphBeforeUpdate'
            );
        \Bitrix\Highloadblock\HighloadBlockTable::Delete(COption::GetOptionString("pavelbabich.telegraph", "HL_ID"));
        //DeleteDirFilesEx("/bitrix/admin/pavelbabich_telegraph.php");
		UnRegisterModule($this->MODULE_ID);
        CUrlRewriter::Delete(array("ID" => "bitrix:telegraph"));
		return true;
	}
}

?>