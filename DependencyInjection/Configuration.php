<?php

namespace blackknight467\S3ImageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('s3_image');

        $rootNode
            ->children()
                ->scalarNode('local_temp_image_folder')->info('the local folder where uploaded images temporarily reside')->isRequired()->end()
                ->scalarNode('image_cdn')->info('the image cdn url')->end()
                ->arrayNode('amazon_s3')
                    ->children()
                        ->scalarNode('upload_bucket_name')
                            ->info('s3 bucket name where we upload the original images to')->isRequired()
                        ->end()
                        ->scalarNode('read_bucket_name')
                            ->info('s3 bucket name where the re-sized images are')->isRequired()
                        ->end()
                        ->scalarNode('aws_key')
                            ->info('amazon aws_key')
                        ->end()
                        ->scalarNode('aws_secret_key')
                            ->info('amazon aws_secret_key')
                        ->end()
                        ->scalarNode('aws_region')
                            ->info('amazon aws region for your s3 buckets')->isRequired()
                        ->end()
                        ->scalarNode('base_url')
                            ->info('amazon base url to access your images')->isRequired()
                        ->end()
                        ->scalarNode('root_path')
                            ->info('the starting location for all uploads.  defaults to bucket root')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('image_sizes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('width')->info('the max width of the image')->isRequired()->end()
                            ->scalarNode('height')->info('the max height of the image')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
