<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
<?=bitrix_sessid_post()?>
<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
<input type="hidden" name="id" value="pavelbabich.telegraph">
<input type="hidden" name="install" value="Y">
<input type="hidden" name="step" value="2">

	<table cellpadding="3" cellspacing="0" border="0" width="0%">
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" width="0%">
					<tr>
						<td><p><?= GetMessage("PTELEGRAPH_COPY_FOLDER") ?></p></td>
						<td><input type="input" name="public_dir" value="telegraph" size="40"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>		
	<input type="submit" name="inst" value="<?= GetMessage("PTELEGRAPH_MOD_INSTALL")?>">
</form>