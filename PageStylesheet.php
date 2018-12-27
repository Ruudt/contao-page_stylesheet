<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Ruud Walraven 2011
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @license    LGPL
 * @filesource
 */

/**
 * Class PageStylesheet
 *
 * @copyright  Ruud Walraven 2011
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 */
class PageStylesheet extends Frontend {
	public function generatePage(Database_Result $objPage, Database_Result $objLayout, PageRegular $objPageRegular)
	{
		$arrStyleSheets = deserialize($objPage->stylesheet);

		// User style sheets
		if (is_array($arrStyleSheets) && strlen($arrStyleSheets[0]))
		{
			$strStyleSheets = '';
			$objStylesheets = $this->Database->execute("SELECT *, (SELECT MAX(tstamp) FROM tl_style WHERE tl_style.pid=tl_style_sheet.id) AS tstamp2, (SELECT COUNT(*) FROM tl_style WHERE tl_style.selector='@font-face' AND tl_style.pid=tl_style_sheet.id) AS hasFontFace FROM tl_style_sheet WHERE id IN (" . implode(', ', $arrStyleSheets) . ") ORDER BY FIELD(id, " . implode(', ', $arrStyleSheets) . ")");

			while ($objStylesheets->next())
			{
				if (($objPage->outputFormat == 'xhtml') or ($objPage->outputFormat == 'html5'))
				{
					if (version_compare(VERSION . '.' . BUILD, '3.0', '>='))
					{
						$GLOBALS['TL_CSS'][] = 'assets/css/' . $objStylesheets->name . '.css|' . implode(',', deserialize($objStylesheets->media)) . '|static';
						// '.css?' . substr(md5(max($objStylesheets->tstamp, $objStylesheets->tstamp2)),0,6)
					}
					else
					{
						$strStyleSheet = sprintf('<link%s rel="stylesheet" href="%ssystem/scripts/%s" media="%s"%s',
												 (($objPage->outputFormat == 'xhtml') ? ' type="text/css"' : ''),
												 TL_SCRIPT_URL,
												 $objStylesheets->name . '.css?' . substr(md5(max($objStylesheets->tstamp, $objStylesheets->tstamp2)),0,6),
												 implode(',', deserialize($objStylesheets->media)),
												 ($objPage->outputFormat == 'xhtml') ? ' />' : '>');
					}
				}
				else
				{
					$strStyleSheet = sprintf('<link rel="stylesheet" href="%s" type="text/css" media="%s" />',
											 $objStylesheets->name . '.css?' . substr(md5(max($objStylesheets->tstamp, $objStylesheets->tstamp2)),0,6),
											 implode(',', deserialize($objStylesheets->media)));
				}

				if ($objStylesheets->cc)
				{
					$strStyleSheet = '<!--[' . $objStylesheets->cc . ']>' . $strStyleSheet . '<![endif]-->';
				}

				$strStyleSheets .= $strStyleSheet . "\n";
			}
		}
		
		// Adding to $GLOBALS['TL_HEAD'] instead of $GLOBALS['TL_CSS'] to keep in the conditional statements...
		$GLOBALS['TL_HEAD'][] = $strStyleSheets;
	}
}
