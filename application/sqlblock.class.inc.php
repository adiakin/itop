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
 * SqlBlock - display tables or charts, given an SQL query - use cautiously!
 *  
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');

require_once(APPROOT.'/pages/php-ofc-library/open-flash-chart.php');

/**
 * Helper class to design optimized dashboards, based on an SQL query
 *  
 */
class SqlBlock
{
	protected $m_sQuery;
	protected $m_aColumns;
	protected $m_sTitle;
	protected $m_sType;
	                            
	public function __construct($sQuery, $aColumns, $sTitle, $sType)
	{
		$this->m_sQuery = $sQuery;
		$this->m_aColumns = $aColumns;
		$this->m_sTitle = $sTitle;
		$this->m_sType = $sType;
	}
	
	/**
	 * Constructs a SqlBlock object from an XML template
	/*
	 *
	 *		<sqlblock>
	 *			<sql>SELECT date_format(start_date, '%d') AS Date, count(*) AS Count FROM ticket WHERE DATE_SUB(NOW(), INTERVAL 15 DAY) &lt; start_date AND finalclass = 'UserIssue' GROUP BY date_format(start_date, '%d')</sql>
	 *			<type>table</type>
	 *			<title>UserRequest:Overview-Title</title>
	 *			<column>
	 *				<name>Date</name>
	 *				<label>UserRequest:Overview-Date</label>
	 *				<drilldown></drilldown>
	 *			</column>
	 *			<column>
	 *				<name>Count</name>
	 *				<label>UserRequest:Overview-Count</label>
	 *				<drilldown>SELECT UserIssue WHERE date_format(start_date, '%d') = :Date</drilldown>
	 *			</column>
	 *		</sqlblock>
	 *
	 * Tags
	 * - sql: a (My)SQL query. Do not forget to use html entities (e.g. &lt; for <)
	 * - type: table (default), bars or pie. If bars or pie is selected only the two first columns are taken into account.
	 * - title: optional title, typed in clear or given as a dictionnary entry
	 * - column: specification of a column (not displayed if omitted)
	 * - column / name: name of the column in the SQL query (use aliases)
	 * - column / label: label, typed in clear or given as a dictionnary entry
	 * - column / drilldown: NOT IMPLEMENTED YET - OQL with parameters corresponding to column names (in the query)
	 *
	 * @param $sTemplate string The XML template
	 * @return DisplayBlock The DisplayBlock object, or null if the template is invalid
	 */
	public static function FromTemplate($sTemplate)
	{
		$oXml = simplexml_load_string('<root>'.$sTemplate.'</root>', 'SimpleXMLElement', LIBXML_NOCDATA);
		if (false)
		{
			// Debug
			echo "<pre>\n";
			print_r($oXml);
			echo "</pre>\n";
		}

		if (isset($oXml->title))
		{
			$sTitle = (string)$oXml->title;
		}
		if (isset($oXml->type))
		{
			$sType = (string)$oXml->type;
		}
		else
		{
			$sType = 'table';
		}
		if (!isset($oXml->sql))
		{
			throw new Exception('Missing tag "sql" in sqlblock');
		}
		$sQuery = (string)$oXml->sql;

		$aColumns = array();
		if (isset($oXml->column))
		{
			foreach ($oXml->column AS $oColumnData)
			{
				if (!isset($oColumnData->name))
				{
					throw new Exception("Missing tag 'name' in sqlblock/column");
				}
				$sName = (string) $oColumnData->name;
				if (strlen($sName) == 0)
				{
					throw new Exception("Empty tag 'name' in sqlblock/column");
				}

				$aColumns[$sName] = array();
				if (isset($oColumnData->label))
				{
					$sLabel = (string)$oColumnData->label;
					if (strlen($sLabel) > 0)
					{
						$aColumns[$sName]['label'] = Dict::S($sLabel);
					}
				}
				if (isset($oColumnData->drilldown))
				{
					$sDrillDown = (string)$oColumnData->drilldown;
					if (strlen($sDrillDown) > 0)
					{
						$aColumns[$sName]['drilldown'] = $sDrillDown;
					}
				}
			}
		}

		return new SqlBlock($sQuery, $aColumns, $sTitle, $sType);		
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		if (empty($aExtraParams['currentId']))
		{
			$sId = 'sqlblock_'.$oPage->GetUniqueId(); // Works only if the page is not an Ajax one !
		}
		else
		{
			$sId = $aExtraParams['currentId'];
		}
//		$oPage->add($this->GetRenderContent($oPage, $aExtraParams, $sId));

		$res = CMDBSource::Query($this->m_sQuery);
		$aQueryCols = CMDBSource::GetColumns($res);

		// Prepare column definitions (check + give default values)
		//
		foreach($this->m_aColumns as $sName => $aColumnData)
		{
			if (!in_array($sName, $aQueryCols))
			{
				throw new Exception("Unknown column name '$sName' in sqlblock column");
			}
			if (!isset($aColumnData['label']))
			{
				$this->m_aColumns[$sName]['label'] = $sName;
			}
			if (isset($aColumnData['drilldown']) && !empty($aColumnData['drilldown']))
			{
				// Check if the OQL is valid
				try
				{
					$this->m_aColumns[$sName]['filter'] = DBObjectSearch::FromOQL($aColumnData['drilldown']);
				}
				catch(OQLException $e)
				{
					unset($aColumnData['drilldown']);
				}
			}
		}

		if (strlen($this->m_sTitle) > 0)
		{
			$oPage->add("<h2>".Dict::S($this->m_sTitle)."</h2>\n");
		}

		switch ($this->m_sType)
		{
		case 'bars':
		case 'pie':
			$aColNames = array_keys($this->m_aColumns);
			$sXColName = $aColNames[0];
			$sYColName = $aColNames[1]; 
			$aData = array();
			$aRows = array();
			while($aRow = CMDBSource::FetchArray($res))
			{
				$aData[$aRow[$sXColName]] = $aRow[$sYColName];
				$aRows[$aRow[$sXColName]] = $aRow;
			}
			$this->RenderChart($oPage, $sId, $aData, $this->m_aColumns[$sYColName]['drilldown'], $aRows);
			break;

		default:
		case 'table':
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			if (!empty($sContext))
			{
				$sContext = '&'.$sContext;
			}
			$aDisplayConfig = array();
			foreach($this->m_aColumns as $sName => $aColumnData)
			{
				$aDisplayConfig[$sName] = array('label' => $aColumnData['label'], 'description' => '');
			}
	
			$aDisplayData = array();
			while($aRow = CMDBSource::FetchArray($res))
			{
				$aSQLColNames = array_keys($aRow);
				$aDisplayRow = array();
				foreach($this->m_aColumns as $sName => $aColumnData)
				{
					if (isset($aColumnData['filter']))
					{
						$sFilter = $aColumnData['drilldown'];
						$sClass = $aColumnData['filter']->GetClass();
						$sFilter = str_replace('SELECT '.$sClass, '', $sFilter);
						foreach($aSQLColNames as $sColName)
						{
							$sFilter = str_replace(':'.$sColName, "'".addslashes( $aRow[$sColName] )."'", $sFilter);
						}
						$sURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search_oql&search_form=0&oql_class='.$sClass.'&oql_clause='.urlencode($sFilter).'&format=html'.$sContext;
						$aDisplayRow[$sName] = '<a href="'.$sURL.'">'.$aRow[$sName]."</a>";					
					}
					else
					{
						$aDisplayRow[$sName] = $aRow[$sName];
					}
				}
				$aDisplayData[] = $aDisplayRow;
			}
			$oPage->table($aDisplayConfig, $aDisplayData);
			break;
		}
	}
	
	public function GetRenderContent(WebPage $oPage, $aExtraParams = array(), $sId)
	{
		$sHtml = '';
		return $sHtml;
	}

	protected function RenderChart($oPage, $sId, $aValues, $sDrillDown = '', $aRows = array())
	{
		// 1- Compute Open Flash Chart data
		//
		$aValueKeys = array();
		$index = 0;
		if ((count($aValues) > 0) && ($sDrillDown != ''))
		{
			$oFilter = DBObjectSearch::FromOQL($sDrillDown);
			$sClass = $oFilter->GetClass();
			$sOQLClause = str_replace('SELECT '.$sClass, '', $sDrillDown);
			$aSQLColNames = array_keys(current($aRows)); // Read the list of columns from the current (i.e. first) element of the array
			$oAppContext = new ApplicationContext();
			$sURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search_oql&search_form=0&oql_class='.$sClass.'&format=html&'.$oAppContext->GetForLink().'&oql_clause=';				
		}
		$aURLs = array();
		foreach($aValues as $key => $value)
		{
			// Make sure that values are integers (so that max() will work....)
			// and build an array of STRING with the keys (numeric keys are transformed into string by PHP :-(
			$aValues[$key] = (int)$value;
			$aValueKeys[] = (string)$key;
			
			// Build the custom query for the 'drill down' on each element
			if ($sDrillDown != '')
			{
				$sFilter = $sOQLClause;
				foreach($aSQLColNames as $sColName)
				{
					$sFilter = str_replace(':'.$sColName, "'".addslashes( $aRows[$key][$sColName] )."'", $sFilter);
					$aURLs[$index] = $sURL.urlencode($sFilter);
				}
			}
			$index++;
		}
	
		$oChart = new open_flash_chart();
	
		if ($this->m_sType == 'bars')
		{
			$oChartElement = new bar_glass();
		
			if (count($aValues) > 0)
			{
				$maxValue = max($aValues);
			}
			else
			{
				$maxValue = 1;
			}
			$oYAxis = new y_axis();
			$aMagicValues = array(1,2,5,10);
			$iMultiplier = 1;
			$index = 0;
			$iTop = $aMagicValues[$index % count($aMagicValues)]*$iMultiplier;
			while($maxValue > $iTop)
			{
				$index++;
				$iTop = $aMagicValues[$index % count($aMagicValues)]*$iMultiplier;
				if (($index % count($aMagicValues)) == 0)
				{
					$iMultiplier = $iMultiplier * 10;
				}
			}
			//echo "oYAxis->set_range(0, $iTop, $iMultiplier);\n";
			$oYAxis->set_range(0, $iTop, $iMultiplier);
			$oChart->set_y_axis( $oYAxis );
			$aBarValues = array();
			foreach($aValues as $iValue)
			{
				$oBarValue = new bar_value($iValue);
				$oBarValue->on_click("ofc_drilldown_{$sId}");
				$aBarValues[] = $oBarValue;
			}
			$oChartElement->set_values($aBarValues);	
			//$oChartElement->set_values(array_values($aValues));
			$oXAxis = new x_axis();
			$oXLabels = new x_axis_labels();
			// set them vertical
			$oXLabels->set_vertical();
			// set the label text
			$oXLabels->set_labels($aValueKeys);
			// Add the X Axis Labels to the X Axis
			$oXAxis->set_labels( $oXLabels );
			$oChart->set_x_axis( $oXAxis );
		}
		else
		{
			$oChartElement = new pie();
			$oChartElement->set_start_angle( 35 );
			$oChartElement->set_animate( true );
			$oChartElement->set_tooltip( '#label# - #val# (#percent#)' );
			$oChartElement->set_colours( array('#FF8A00', '#909980', '#2C2B33', '#CCC08D', '#596664') );
	
			$aData = array();
			foreach($aValues as $sValue => $iValue)
			{
				$oPieValue = new pie_value($iValue, $sValue); //@@ BUG: not passed via ajax !!!
				$oPieValue->on_click("ofc_drilldown_{$sId}");
				$aData[] = $oPieValue;
			}
	
			$oChartElement->set_values( $aData );
			$oChart->x_axis = null;
		}
	
		// Title given in HTML
		//$oTitle = new title($this->m_sTitle);
		//$oChart->set_title($oTitle);
		$oChart->set_bg_colour('#FFFFFF');
		$oChart->add_element( $oChartElement );
			
		$sData = $oChart->toPrettyString();
		$sData = json_encode($sData);

		// 2- Declare the Javascript function that will render the chart data\
		//
		$oPage->add_script(
<<< EOF
function ofc_get_data_{$sId}()
{
	return $sData;
}
EOF
		);
		
		if (count($aURLs) > 0)
		{
			$sURLList = '';
			foreach($aURLs as $index => $sURL)
			{
				$sURLList .= "\taURLs[$index] = '".addslashes($sURL)."';\n";
			}

			$oPage->add_script(
<<< EOF
function ofc_drilldown_{$sId}(index)
{
	var aURLs = new Array();
{$sURLList}
	var sURL = aURLs[index];
	
	window.location.href = sURL; // Navigate ! 
}
EOF
			);
		}

		// 3- Insert the Open Flash chart
		//
		$oPage->add("<div id=\"$sId\"><div>\n");
		$oPage->add_ready_script(
<<<EOF
swfobject.embedSWF(	"../images/open-flash-chart.swf", 
	"{$sId}", 
	"100%", "300","9.0.0",
	"expressInstall.swf",
	{"get-data":"ofc_get_data_{$sId}", "id":"{$sId}"}, 
	{'wmode': 'transparent'}
);
EOF
		);
	}
}

?>
