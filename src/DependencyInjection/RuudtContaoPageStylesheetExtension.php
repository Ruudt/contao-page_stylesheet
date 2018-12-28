<?php

declare(strict_types=1);

/**
 * @copyright  Ruud Walraven 2018
 * @author     Ruud Walraven <ruud.walraven@gmail.com>
 * @package    contao-page-stylesheet
 * @license    GPL-3.0+
 */

namespace Ruudt\ContaoPageStylesheetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class RuudtContaoPageStylesheetBundleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('listener.yml');
        $loader->load('services.yml');
    }
}
