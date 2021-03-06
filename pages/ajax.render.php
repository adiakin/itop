<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Handles various ajax requests
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');
require_once(APPROOT.'/application/ui.linkswidget.class.inc.php');
require_once(APPROOT.'/application/ui.extkeywidget.class.inc.php');

try
{
	require_once(APPROOT.'/application/startup.inc.php');
	require_once(APPROOT.'/application/user.preferences.class.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(false /* bMustBeAdmin */, true /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed
	
	$oPage = new ajax_page("");
	$oPage->no_cache();

	
	$operation = utils::ReadParam('operation', '');
	$sFilter = stripslashes(utils::ReadParam('filter', '', false, 'raw_data'));
	$sEncoding = utils::ReadParam('encoding', 'serialize');
	$sClass = utils::ReadParam('class', 'MissingAjaxParam', false, 'class');
	$sStyle = utils::ReadParam('style', 'list');

	switch($operation)
	{
		case 'pagination':
		$oPage->SetContentType('text/html');
		$sExtraParams = stripslashes(utils::ReadParam('extra_param', '', false, 'raw_data'));
		$aExtraParams = array();
		if (!empty($sExtraParams))
		{
			$aExtraParams = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
		}
		if ($sEncoding == 'oql')
		{
			$oFilter = CMDBSearchFilter::FromOQL($sFilter);
		}
		else
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
		}
		$iStart = utils::ReadParam('start',0);
		$iEnd = utils::ReadParam('end',1);
		$iSortCol = utils::ReadParam('sort_col','null');
		$sSelectMode = utils::ReadParam('select_mode', '');
		$bDisplayKey = utils::ReadParam('display_key', 'true') == 'true';
		$aList = utils::ReadParam('display_list', array());

		$sClassName = $oFilter->GetClass();
		//$aList = cmdbAbstractObject::FlattenZList(MetaModel::GetZListItems($sClassName, 'list'));

		// Filter the list to removed linked set since we are not able to display them here
		$aOrderBy = array();
		$aConfig = array();
		$iSortIndex = 0;
		if ($sSelectMode != '')
		{
			$aConfig['form::select'] = array();
			$iSortIndex++; // Take into account the extra (non-sortable) column for the checkbox/radio button.
		}
		if ($bDisplayKey)
		{
			$aConfig['key'] = array();
			if (($iSortCol != 'null') && ($iSortIndex == $iSortCol))
			{
				$aOrderBy['friendlyname'] = (utils::ReadParam('sort_order', 'asc') == 'asc');
			}
			$iSortIndex++;
		}
		foreach($aList as $sAttCode)
		{
			$aConfig[$sAttCode] = array();
		}

		foreach($aList as $index => $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
			if ($oAttDef instanceof AttributeLinkedSet)
			{
				// Removed from the display list
				unset($aList[$index]);
			}
			if ($iSortCol == $iSortIndex)
			{
				if ($oAttDef->IsExternalKey())
				{
					$sSortCol = $sAttCode.'_friendlyname';
				}
				else
				{
					$sSortCol = $sAttCode;
				}
				$aOrderBy[$sSortCol] = (utils::ReadParam('sort_order', 'asc') == 'asc');
			}
			$iSortIndex++;
		}
		
		if (count($aOrderBy) == 0)
		{
			$aOrderBy['friendlyname'] = true; // By default, sort by name
		}
		else
		{
//			$oPage->add("</p>ICI: OrderBy already set to: <pre>'".print_r($aOrderBy, true)."'</pre></p>\n");		
		}

		// Load only the requested columns
		$oSet = new DBObjectSet($oFilter, $aOrderBy, $aExtraParams, null, $iEnd-$iStart, $iStart);
		$sClassAlias = $oSet->GetFilter()->GetClassAlias();
		$oSet->OptimizeColumnLoad(array($sClassAlias => $aList));
		
		while($oObj = $oSet->Fetch())
		{
			$aRow = array();
			$sDisabled = '';
			switch ($sSelectMode)
			{
				case 'single':
				$aRow['form::select'] = "<input type=\"radio\" $sDisabled name=\"selectObject\" value=\"".$oObj->GetKey()."\"></input>";
				break;
				
				case 'multiple':
				$aRow['form::select'] = "<input type=\"checkBox\" $sDisabled name=\"selectObject[]\" value=\"".$oObj->GetKey()."\"></input>";
				break;
			}
			if ($bDisplayKey)
			{
				$aRow['key'] = $oObj->GetHyperLink();
			}
			$sHilightClass = $oObj->GetHilightClass();
			if ($sHilightClass != '')
			{
				$aRow['@class'] = $sHilightClass;	
			}
			foreach($aList as $sAttCode)
			{
				$aRow[$sAttCode] = $oObj->GetAsHTML($sAttCode);
			}
			$sRow = $oPage->GetTableRow($aRow, $aConfig);
			$oPage->add($sRow);
		}
		break;
		
		// ui.linkswidget
		case 'searchObjectsToAdd':
		$oPage->SetContentType('text/html');
		$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$aAlreadyLinked = utils::ReadParam('aAlreadyLinked', array());
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		$oWidget->SearchObjectsToAdd($oPage, $sRemoteClass, $aAlreadyLinked);	
		break;
		
		////////////////////////////////////////////////////////////
		
		// ui.extkeywidget
		case 'searchObjectsToSelect':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sRemoteClass = utils::ReadParam('sRemoteClass', '', false, 'class');
		$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId);
		$oWidget->SearchObjectsToSelect($oPage, $sFilter, $sRemoteClass, $oObj);	
		break;
	
		// ui.extkeywidget: autocomplete
		case 'ac_extkey':
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$sContains = utils::ReadParam('q', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId);
		$oWidget->AutoComplete($oPage, $sFilter, $oObj, $sContains);
		break;
	
		// ui.extkeywidget
		case 'objectSearchForm':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sTitle = utils::ReadParam('sTitle', '', false, 'raw_data');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId);
		$oWidget->GetSearchDialog($oPage, $sTitle);
		break;

		// ui.extkeywidget
		case 'objectCreationForm':
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId);
		$oWidget->GetObjectCreationForm($oPage);
		break;
		
		// ui.extkeywidget
		case 'doCreateObject':
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$sFormPrefix = utils::ReadParam('sFormPrefix', '');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId);
		$aResult = $oWidget->DoCreateObject($oPage);
		echo json_encode($aResult);
		break;
		
		// ui.extkeywidget
		case 'getObjectName':
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$iInputId = utils::ReadParam('iInputId', '');
		$iObjectId = utils::ReadParam('iObjectId', '');
		$oWidget = new UIExtKeyWidget($sTargetClass, $iInputId);
		$sName = $oWidget->GetObjectName($iObjectId);
		echo json_encode(array('name' => $sName));	
		break;
		
		// ui.extkeywidget
		case 'displayHierarchy':
		$oPage->SetContentType('text/html');
		$sTargetClass = utils::ReadParam('sTargetClass', '', false, 'class');
		$sInputId = utils::ReadParam('sInputId', '');
		$sFilter = utils::ReadParam('sFilter', '', false, 'raw_data');
		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		$currValue = utils::ReadParam('value', '');
		if (!empty($sJson))
		{
			$oWizardHelper = WizardHelper::FromJSON($sJson);
			$oObj = $oWizardHelper->GetTargetObject();
		}
		else
		{
			// Search form: no current object
			$oObj = null;
		}
		$oWidget = new UIExtKeyWidget($sTargetClass, $sInputId);
		$oWidget->DisplayHierarchy($oPage, $sFilter, $currValue, $oObj);
		break;
		
		////////////////////////////////////////////////////
		
		// ui.linkswidget
		case 'doAddObjects':
		$oPage->SetContentType('text/html');
		$sAttCode = utils::ReadParam('sAttCode', '');
		$iInputId = utils::ReadParam('iInputId', '');
		$sSuffix = utils::ReadParam('sSuffix', '');
		$sRemoteClass = utils::ReadParam('sRemoteClass', $sClass, false, 'class');
		$bDuplicates = (utils::ReadParam('bDuplicates', 'false') == 'false') ? false : true;
		$oWidget = new UILinksWidget($sClass, $sAttCode, $iInputId, $sSuffix, $bDuplicates);
		if ($sFilter != '')
		{
			$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		}
		else
		{
			$oFullSetFilter = new DBObjectSearch($sRemoteClass);		
		}
		$oWidget->DoAddObjects($oPage, $oFullSetFilter);	
		break;
			
		case 'wizard_helper_preview':
		$oPage->SetContentType('text/html');
		$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject();
		$oObj->DisplayBareProperties($oPage); 
		break;
		
		case 'wizard_helper':
		$oPage->SetContentType('text/html');
		$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject(); 
		$sClass = $oWizardHelper->GetTargetClass();
		foreach($oWizardHelper->GetFieldsForDefaultValue() as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$defaultValue = $oAttDef->GetDefaultValue();
			$oWizardHelper->SetDefaultValue($sAttCode, $defaultValue);
			$oObj->Set($sAttCode, $defaultValue);
		}
		$sFormPrefix = $oWizardHelper->GetFormPrefix();
		foreach($oWizardHelper->GetFieldsForAllowedValues() as $sAttCode)
		{
			$sId = $oWizardHelper->GetIdForField($sAttCode);
			if ($sId != '')
			{
				if ($oObj->IsNew())
				{
					$iFlags = $oObj->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
					$iFlags = $oObj->GetAttributeFlags($sAttCode);
				}
				if ($iFlags & OPT_ATT_READONLY)
				{
					$sHTMLValue = "<span id=\"field_{$sId}\">".$oObj->GetAsHTML($sAttCode);
					$sHTMLValue .= '<input type="hidden" id="'.$sId.'" name="attr_'.$sFormPrefix.$sAttCode.'" value="'.htmlentities($oObj->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/></span>';
					$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
				}
				else
				{
					// It may happen that the field we'd like to update does not
					// exist in the form. For example, if the field should be hidden/read-only
					// in the current state of the object
					$value = $oObj->Get($sAttCode);
					$displayValue = $oObj->GetEditValue($sAttCode);
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$iFlags = MetaModel::GetAttributeFlags($sClass, $oObj->GetState(), $sAttCode);
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value, $displayValue, $sId, '', $iFlags, array('this' => $oObj, 'formPrefix' => $sFormPrefix));
					// Make sure that we immediately validate the field when we reload it
					$oPage->add_ready_script("$('#$sId').trigger('validate');");
					$oWizardHelper->SetAllowedValuesHtml($sAttCode, $sHTMLValue);
				}
			}
		}
		$oPage->add_script("oWizardHelper{$sFormPrefix}.m_oData=".$oWizardHelper->ToJSON().";\noWizardHelper{$sFormPrefix}.UpdateFields();\n");
		break;
		
		case 'obj_creation_form':
		$oPage->SetContentType('text/html');
		$sJson = utils::ReadParam('json_obj', '', false, 'raw_data');
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject(); 
		$sClass = $oWizardHelper->GetTargetClass();
		$sTargetState = utils::ReadParam('target_state', '');
		$iTransactionId = utils::ReadParam('transaction_id', '');
		$oObj->Set(MetaModel::GetStateAttributeCode($sClass), $sTargetState);
		cmdbAbstractObject::DisplayCreationForm($oPage, $sClass, $oObj, array(), array('action' => utils::GetAbsoluteUrlAppRoot().'pages/UI.php', 'transcation_id' => $iTransactionId)); 
		break;
		
		// DisplayBlock
		case 'ajax':
		$oPage->SetContentType('text/html');
		if ($sFilter != "")
		{
			$sExtraParams = stripslashes(utils::ReadParam('extra_params', '', false, 'raw_data'));
			$aExtraParams = array();
			if (!empty($sExtraParams))
			{
				$aExtraParams = json_decode(str_replace("'", '"', $sExtraParams), true /* associative array */);
			}
			if ($sEncoding == 'oql')
			{
				$oFilter = CMDBSearchFilter::FromOQL($sFilter);
			}
			else
			{
				$oFilter = CMDBSearchFilter::unserialize($sFilter);
			}
			$oDisplayBlock = new DisplayBlock($oFilter, $sStyle, false);
			$aExtraParams['display_limit'] = true;
			$aExtraParams['truncated'] = true;
			$oDisplayBlock->RenderContent($oPage, $aExtraParams);
		}
		else
		{
			$oPage->p("Invalid query (empty filter).");
		}
		break;
		
		case 'displayCSVHistory':
		$oPage->SetContentType('text/html');
		$bShowAll = (utils::ReadParam('showall', 'false') == 'true');
		BulkChange::DisplayImportHistory($oPage, true, $bShowAll);
		break;
		
		case 'details':
		$oPage->SetContentType('text/html');
		$key = utils::ReadParam('id', 0);
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddCondition('id', $key, '=');
		$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
		$oDisplayBlock->RenderContent($oPage);
		break;
		
		case 'pie_chart':
		$oPage->SetContentType('application/json');
		$sGroupBy = utils::ReadParam('group_by', '');
		if ($sFilter != '')
		{
			if ($sEncoding == 'oql')
			{
				$oFilter = CMDBSearchFilter::FromOQL($sFilter);
			}
			else
			{
				$oFilter = CMDBSearchFilter::unserialize($sFilter);
			}
			$oDisplayBlock = new DisplayBlock($oFilter, 'pie_chart_ajax', false);
			$oDisplayBlock->RenderContent($oPage, array('group_by' => $sGroupBy));
		}
		else
		{
		
			$oPage->add("<chart>\n<chart_type>3d pie</chart_type><!-- empty filter '$sFilter' --></chart>\n.");
		}
		break;
		
		case 'open_flash_chart':
		// Workaround for IE8 + IIS + HTTPS
		// See TRAC #363, fix described here: http://forums.codecharge.com/posts.php?post_id=97771
		$oPage->add_header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");
		$oPage->add_header("Cache-Control: cache, must-revalidate");
		$oPage->add_header("Pragma: public");

		$oPage->SetContentType('application/json');
		$aParams = utils::ReadParam('params', array());
		if ($sFilter != '')
		{
			$oFilter = CMDBSearchFilter::unserialize($sFilter);
			$oDisplayBlock = new DisplayBlock($oFilter, 'open_flash_chart_ajax', false);
			$oDisplayBlock->RenderContent($oPage, $aParams);
		}
		else
		{
		
			$oPage->add("<chart>\n<chart_type>3d pie</chart_type><!-- empty filter '$sFilter' --></chart>\n.");
		}
		break;
	
		case 'modal_details':
		$oPage->SetContentType('text/html');
		$key = utils::ReadParam('id', 0);
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddCondition('id', $key, '=');
		$oPage->Add("<p style=\"width:100%; margin-top:-5px;padding:3px; background-color:#33f; color:#fff;\">Object Details</p>\n");
		$oDisplayBlock = new DisplayBlock($oFilter, 'details', false);
		$oDisplayBlock->RenderContent($oPage);
		$oPage->Add("<input type=\"button\" class=\"jqmClose\" value=\" Close \" />\n");
		break;

		case 'link':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('sclass', 'logInfra', false, 'class');
		$sAttCode = utils::ReadParam('attCode', 'name');
		//$sOrg = utils::ReadParam('org_id', '');
		$sName = utils::ReadParam('q', '');
		$iMaxCount = utils::ReadParam('max', 30);
		$iCount = 0;
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddCondition($sAttCode, $sName, 'Begins with');
		//$oFilter->AddCondition('org_id', $sOrg, '=');
		$oSet = new CMDBObjectSet($oFilter, array($sAttCode => true));
		while( ($iCount < $iMaxCount) && ($oObj = $oSet->fetch()) )
		{
			$oPage->add($oObj->GetAsHTML($sAttCode)."|".$oObj->GetKey()."\n");
			$iCount++;
		}
		break;
	
		case 'combo_options':
		$oPage->SetContentType('text/html');
		$oFilter = CMDBSearchFilter::FromOQL($sFilter);
		$oSet = new CMDBObjectSet($oFilter);
		while( $oObj = $oSet->fetch())
		{
			$oPage->add('<option title="Here is more information..." value="'.$oObj->GetKey().'">'.$oObj->GetName().'</option>');
		}
		break;
		
		case 'display_document':
		$id = utils::ReadParam('id', '');
		$sField = utils::ReadParam('field', '');
		if (!empty($sClass) && !empty($id) && !empty($sField))
		{
			DownloadDocument($oPage, $sClass, $id, $sField, 'inline');
		}
		break;
		
		case 'download_document':
		$id = utils::ReadParam('id', '');
		$sField = utils::ReadParam('field', '');
		if (!empty($sClass) && !empty($id) && !empty($sField))
		{
			DownloadDocument($oPage, $sClass, $id, $sField, 'attachment');
		}
		break;
		
		case 'search_form':
		$oPage->SetContentType('text/html');
		$sClass = utils::ReadParam('className', '', false, 'class');
		$sRootClass = utils::ReadParam('baseClass', '', false, 'class');
		$currentId = utils::ReadParam('currentId', '');
		$sAction = utils::ReadParam('action', '');
		$oFilter = new DBObjectSearch($sClass);
		$oSet = new CMDBObjectSet($oFilter); 
		$sHtml = cmdbAbstractObject::GetSearchForm($oPage, $oSet, array('currentId' => $currentId, 'baseClass' => $sRootClass, 'action' => $sAction));
		$oPage->add($sHtml);
		break;
		
		case 'set_pref':
		$sCode = utils::ReadPostedParam('code', '');
		$sValue = utils::ReadPostedParam('value', '', 'raw_data');
		appUserPreferences::SetPref($sCode, $sValue);
		break;
	
		case 'erase_all_pref':
		// Can be useful in case a user got some corrupted prefs...
		appUserPreferences::ClearPreferences();
		break;

		case 'on_form_cancel':
		// Called when a creation/modification form is cancelled by the end-user
		// Let's take this opportunity to inform the plug-ins so that they can perform some cleanup
		$iTransactionId = utils::ReadParam('transaction_id', 0);
		$sTempId = session_id().'_'.$iTransactionId;
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnFormCancel($sTempId);
		}
		
		break;
			
		default:
		$oPage->p("Invalid query.");
	}

	$oPage->output();
}
catch (Exception $e)
{
	echo $e->GetMessage();
	IssueLog::Error($e->getMessage());
}



/**
 * Downloads a document to the browser, either as 'inline' or 'attachment'
 *  
 * @param WebPage $oPage The web page for the output
 * @param string $sClass Class name of the object
 * @param mixed $id Identifier of the object
 * @param string $sAttCode Name of the attribute containing the document to download
 * @param string $sContentDisposition Either 'inline' or 'attachment'
 * @return none
 */   
function DownloadDocument(WebPage $oPage, $sClass, $id, $sAttCode, $sContentDisposition = 'attachment')
{
	try
	{
		$oObj = MetaModel::GetObject($sClass, $id);
		if (is_object($oObj))
		{
			$oDocument = $oObj->Get($sAttCode);
			if (is_object($oDocument))
			{
				$oPage->SetContentType($oDocument->GetMimeType());
				$oPage->SetContentDisposition($sContentDisposition,$oDocument->GetFileName());
				$oPage->add($oDocument->GetData());
			}
		}
	}
	catch(Exception $e)
	{
		$oPage->p($e->getMessage());
	}
}
?>
