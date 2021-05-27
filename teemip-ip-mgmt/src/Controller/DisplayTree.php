<?php
/*
 * @copyright   Copyright (C) 2021 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace TeemIp\TeemIp\Extension\IPManagement\Controller;

use CMDBObjectSet;
use DBObjectSearch;
use Dict;
use DisplayBlock;
use iPopupMenuExtension;
use MenuBlock;
use MetaModel;
use utils;
use WebPage;

/**
 * Displays TeemIp's hierarchical objects in tree mode
 */
class DisplayTree {

	/**
	 * Display tree
	 *
	 * @param \WebPage $oP
	 * @param $oAppContext
	 * @param $sClass
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	static public function Display(WebPage $oP, $oAppContext, $sClass) {
		if (version_compare(ITOP_DESIGN_LATEST_VERSION, '3.0', '<')) {
			DisplayTree::DisplayV2($oP, $oAppContext, $sClass);
		} else {
			DisplayTree::DisplayV3($oP, $oAppContext, $sClass);
		}
	}

	/**
	 * Display tree in 2.x format
	 *
	 * @param \WebPage $oP
	 * @param $oAppContext
	 * @param $sClass
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	static private function DisplayV2(WebPage $oP, $oAppContext, $sClass) {
		// Display search bar
		$oSearch = new DBObjectSearch($sClass);
		$aParams = array('open' => true, 'table_id' => 'displaytree_search');
		$oBlock = new DisplayBlock($oSearch, 'search', false /* Asynchronous */, $aParams);
		$oBlock->Display($oP, 0);

		// Set titles
		$sClassLabel = MetaModel::GetName($sClass);
		$oP->set_title(Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':PageTitle_Class'));
		$oP->add("<p class=\"page-header\">\n");
		$oP->add(MetaModel::GetClassIcon($sClass, true)." ".Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':Title_Class', $sClassLabel));
		$oP->add("</p>\n");

		$oP->add('<div class="display_block">');

		// Get number of records
		$iCurrentOrganization = $oAppContext->GetCurrentValue('org_id');
		if ($iCurrentOrganization == '') {
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass"));
		} else {
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sClass AS c WHERE c.org_id = $iCurrentOrganization"));
		}
		$sObjectsCount = Dict::Format('UI:Pagination:HeaderNoSelection', $oSet->Count());

		// Get actions Menu
		$iListId = 'displaytree_menu'; //$oP->GetUniqueId();
		$oMenuBlock = new MenuBlock($oSet->GetFilter(), 'list');
		$sActionsMenu = $oMenuBlock->GetRenderContent($oP, array(), $iListId);

		// Get toolkit menu
		// Remove "Add To Dashboard" submenu
		$sHtml = '<div class="itop_popup toolkit_menu" id="tk_'.$iListId.'"><ul><li><img src="../images/toolkit_menu.png" alt=\"\"><ul>';
		$aActions = array();
		utils::GetPopupMenuItems($oP, iPopupMenuExtension::MENU_OBJLIST_TOOLKIT, $oSet, $aActions);
		unset($aActions['UI:Menu:AddToDashboard']);
		unset($aActions['UI:Menu:ShortcutList']);
		$sHtml .= $oP->RenderPopupMenuItems($aActions);
		$sToolkitMenu = $sHtml;

		// Display menu line
		$sHtml = "<table style=\"width:100%;\">";
		$sHtml .= "<tr><td class=\"pagination_container\">$sObjectsCount</td><td class=\"menucontainer\">$sToolkitMenu $sActionsMenu</td></tr>";
		$sHtml .= "</table>";
		$oP->Add($sHtml);

		// Dump Tree(s)
		$oP->add('<table style="width:100%"><tr><td colspan="2">');
		$oP->add('<div style="vertical-align:top;" id="tree">');
		if ($iCurrentOrganization == '') {
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT Organization"));
			while ($oOrg = $oSet->Fetch()) {
				$oP->add("<h2>".Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':OrgName', $oOrg->Get('name'))."</h2>\n");
				DisplayTree::DeployTree($oP, $oOrg->GetKey(), $sClass);
				$oP->add("<br>");
			}
		} else {
			$oOrg = MetaModel::GetObject('Organization', $iCurrentOrganization, false /* MustBeFound */);
			$oP->add("<h2>".Dict::Format('UI:IPManagement:Action:DisplayTree:'.$sClass.':OrgName', $oOrg->Get('name'))."</h2>\n");
			DisplayTree::DeployTree($oP, $iCurrentOrganization, $sClass);
		}
		$oP->add('</td></tr></table>');
		$oP->add('</div></div>');
		$oP->add_ready_script("\$('#tree ul').treeview();\n");
	}

	static private function DisplayV3(WebPage $oP, $oAppContext, $sClass) {

	}

	/**
	 * Deploy tree
	 *
	 * @param \WebPage $oP
	 * @param $iOrgId
	 * @param $sClass
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	static private function DeployTree(WebPage $oP, $iOrgId, $sClass) {
		switch ($sClass) {
			case 'IPv4Block':
			case 'IPv6Block':
			case 'Domain':
				DisplayTree::DisplayNode($oP, $iOrgId, $sClass, 0, '');
				break;

			case 'IPv4Subnet':
				DisplayTree::DisplayNode($oP, $iOrgId, 'IPv4Block', 0, 'IPv4Subnet');
				break;

			case 'IPv6Subnet':
				DisplayTree::DisplayNode($oP, $iOrgId, 'IPv6Block', 0, 'IPv6Subnet');
				break;

			default:
				break;
		}
	}

	/**
	 * Display the node of a hierarchical tree
	 *
	 * @param \WebPage $oP
	 * @param $iOrgId
	 * @param $sContainerClass
	 * @param $iContainerId
	 * @param $sLeafClass
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	static private function DisplayNode(WebPage $oP, $iOrgId, $sContainerClass, $iContainerId, $sLeafClass) {
		// Get list of Containers contained within current container defined by key $iContainerId
		//    . Blocks that belong to organization
		$sOQL = "SELECT $sContainerClass AS cc WHERE cc.org_id = :org_id AND cc.parent_id = :parent_id";
		//    . Add blocks that are delegated to
		$sOQL .= " UNION ";
		$sOQL .= "SELECT $sContainerClass AS cc WHERE cc.parent_org_id = :org_id AND cc.parent_id = :parent_id";
		//    . Add blocks that are delegated from - this should only work for level 0 where container_id is null
		$sOQL .= " UNION ";
		$sOQL .= "SELECT $sContainerClass AS cc WHERE cc.org_id = :org_id AND cc.parent_org_id != 0 AND :container_id = 0";
		$oChildContainerSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array(), array(
			'org_id' => $iOrgId,
			'parent_id' => $iContainerId,
			'container_id' => $iContainerId,
		));

		$aNodes = array();
		while ($oChildContainer = $oChildContainerSet->Fetch()) {
			$iKey = $oChildContainer->GetIndexForTree();
			$aNodes[$iKey] = $oChildContainer;
		}

		// Get list of leaves contained within current container, if any
		if ($sLeafClass != '') {
			$oLeafSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT $sLeafClass AS lc WHERE lc.block_id = :block_id"), array(), array('block_id' => $iContainerId));
			while ($oLeaf = $oLeafSet->Fetch()) {
				$iKey = $oLeaf->GetIndexForTree();
				$aNodes[$iKey] = $oLeaf;
			}
		}

		// Display Node
		ksort($aNodes);
		$bWithIcon = ($sLeafClass != '') ? true : false;
		$oP->add("<ul>\n");
		foreach ($aNodes as $id => $oObject) {
			$oP->add("<li>");
			$oObject->DisplayAsLeaf($oP, $bWithIcon, $iOrgId);
			if (get_class($oObject) == $sContainerClass) {
				DisplayTree::DisplayNode($oP, $iOrgId, $sContainerClass, $oObject->GetKey(), $sLeafClass);
			}
			$oP->add("</li>\n");
		}
		$oP->add("</ul>\n");

	}


}