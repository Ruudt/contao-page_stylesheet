<?php 

/**
 * Class PageStylesheet
 *
 * @copyright  Ruud Walraven 2018
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    contao-page-stylesheet
 * @license    GPL-3.0+
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
                    $GLOBALS['TL_CSS'][] = 'assets/css/' . $objStylesheets->name . '.css|' . implode(',', deserialize($objStylesheets->media)) . '|static';
                    // '.css?' . substr(md5(max($objStylesheets->tstamp, $objStylesheets->tstamp2)),0,6)
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
