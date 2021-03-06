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

// Add the standard menus
/*
 * +--------------------+
 * | Welcome            |
 * +--------------------+
 * 		Welcome To iTop
 * +--------------------+
 * | Tools              |
 * +--------------------+
 * 		CSV Import
 * +--------------------+
 * | Admin Tools        | << Only present if the user is an admin
 * +--------------------+
 *		User Accounts
 *		Profiles
 *		Notifications
 *		Run Queries
 *		Export
 *		Data Model
 *		Universal Search
 */


class ItopWelcome extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		$oWelcomeMenu = new MenuGroup('WelcomeMenu', 10 /* fRank */);
		new TemplateMenuNode('WelcomeMenuPage', dirname(__FILE__).'/welcome_menu.html', $oWelcomeMenu->GetIndex() /* oParent */, 1 /* fRank */);
		
		$oToolsMenu = new MenuGroup('DataAdministration', 70 /* fRank */, 'Organization', UR_ACTION_MODIFY, UR_ALLOWED_YES|UR_ALLOWED_DEPENDS);
		new WebPageMenuNode('CSVImportMenu', utils::GetAbsoluteUrlAppRoot().'pages/csvimport.php', $oToolsMenu->GetIndex(), 1 /* fRank */);
		
		// Add the admin menus
		if (UserRights::IsAdministrator())
		{
			$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
			new OQLMenuNode('UserAccountsMenu', 'SELECT User', $oAdminMenu->GetIndex(), 1 /* fRank */);
			new OQLMenuNode('ProfilesMenu', 'SELECT URP_Profiles', $oAdminMenu->GetIndex(), 2 /* fRank */);
			new TemplateMenuNode('NotificationsMenu', APPROOT.'application/templates/notifications_menu.html', $oAdminMenu->GetIndex(), 3 /* fRank */);
			new OQLMenuNode('AuditCategories', 'SELECT AuditCategory', $oAdminMenu->GetIndex(), 4 /* fRank */);
			new WebPageMenuNode('RunQueriesMenu', utils::GetAbsoluteUrlAppRoot().'pages/run_query.php', $oAdminMenu->GetIndex(), 8 /* fRank */);
			new WebPageMenuNode('ExportMenu', utils::GetAbsoluteUrlAppRoot().'webservices/export.php', $oAdminMenu->GetIndex(), 9 /* fRank */);
			new WebPageMenuNode('DataModelMenu', utils::GetAbsoluteUrlAppRoot().'pages/schema.php', $oAdminMenu->GetIndex(), 10 /* fRank */);
			new WebPageMenuNode('UniversalSearchMenu', utils::GetAbsoluteUrlAppRoot().'pages/UniversalSearch.php', $oAdminMenu->GetIndex(), 11 /* fRank */);
		}
	}
}

?>
