<?php

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Finnern\Plugin\Content\Remote_markdown\Extension\Remote_markdown;

    return new class() implements ServiceProviderInterface
    {
        public function register(Container $container): void
        {
            $container->set(
                PluginInterface::class,
                function (Container $container) {
    
//                    //$config = (array) PluginHelper::getPlugin('content', 'remote_markdown');
//                    $subject = $container->get(DispatcherInterface::class);
//                    $app = Factory::getApplication();
//
//                    //$plugin = new Remote_markdown($subject, $config);
//                    $plugin = new Remote_markdown($subject);
//                    $plugin->setApplication($app);

                    $plugin     = new Remote_markdown(
                        $container->get(DispatcherInterface::class),
                        (array) PluginHelper::getPlugin('content', 'remote_markdown')
                    );
                    $plugin->setApplication(Factory::getApplication());

                    return $plugin;
                }
            );
        }
    };
