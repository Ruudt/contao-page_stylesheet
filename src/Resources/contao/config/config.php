<?php

/**
 * @copyright  Ruud Walraven 2018
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    contao-page-stylesheet
 * @license    GPL-3.0+
 */

// config.php
$GLOBALS['TL_HOOKS']['generatePage'][] = ['PageStylesheet', 'generatePage'];
