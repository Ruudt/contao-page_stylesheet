<?php

declare(strict_types=1);

/**
 * @copyright  Ruud Walraven 2018
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    contao-page-stylesheet
 * @license    GPL-3.0+
 */

namespace Ruudt\ContaoPageStylesheetBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Ruudt\ContaoPageStylesheetBundle\RuudtContaoPageStylesheetBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(RuudtContaoPageStylesheetBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
