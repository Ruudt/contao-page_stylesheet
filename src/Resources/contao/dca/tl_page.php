<?php

/**
 * @copyright  Ruud Walraven 2018
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    contao-page-stylesheet
 * @license    GPL-3.0+
 */


/**
 * Add the stylesheet option right before caching (which should be directly after layout)
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(';{cache_legend:hide}', ';{style_legend},stylesheet;{cache_legend:hide}', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['fields']['stylesheet'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_page']['stylesheet'],
		'exclude'                 => true,
		'filter'                  => true,
		'inputType'               => 'checkboxWizard',
		'options_callback'        => array('tl_page_stylesheets', 'getStyleSheets'),
		'eval'                    => array('multiple'=>true)
	);


/**
 * @copyright  Ruud Walraven 2018
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    contao-page-stylesheet
 * @license    GPL-3.0+
 */
class tl_page_stylesheets extends Backend
{
	/**
	 * Return all style sheets of the current theme
	 * @param DataContainer $dc
	 * @return array
	 */
	public function getStyleSheets(DataContainer $dc)
	{
		$objStyleSheet = $this->Database->prepare("SELECT id, name FROM tl_style_sheet WHERE pid=?")
										->execute($dc->activeRecord->pid);

		if ($objStyleSheet->numRows < 1)
		{
			return [];
		}

		$return = [];

		while ($objStyleSheet->next())
		{
			$return[$objStyleSheet->id] = $objStyleSheet->name;
		}

		return $return;
	}
}