<?php

namespace Webit\Bundle\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webit_notification');
				$rootNode
					->children()
					  ->scalarNode('default_scheme')->defaultValue('http')->end()
					  ->scalarNode('default_host')->end()
						->scalarNode('sms_sender')->end()
						->scalarNode('templates_path_prefix')->defaultValue('@WebitNotificationBundle:notifications:')->end()
						->arrayNode('notifications')
							->useAttributeAsKey('type')
							->prototype('array')
								->children()
									->scalarNode('interval')->end()
									->scalarNode('recipients_provider')->end()
									->arrayNode('active')
											->children()
												->scalarNode('sms')->defaultFalse()->end()
												->scalarNode('email')->defaultTrue()->end()
											->end()
									->end()
								->end()
						->end()
					->end()
				->end();
						
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
