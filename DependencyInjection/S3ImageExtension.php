<?php

namespace blackknight467\S3ImageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class S3ImageExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        // upload bucket
        if (!isset($config['amazon_s3']['upload_bucket_name'])) {
            throw new \InvalidArgumentException(
                'The option "blackknight467.s3_image.upload_bucket_name" must be set.'
            );
        }
        $container->setParameter(
            'blackknight467.s3_image.upload_bucket_name',
            $config['amazon_s3']['upload_bucket_name']
        );

        // read bucket
        if (!isset($config['amazon_s3']['read_bucket_name'])) {
            throw new \InvalidArgumentException(
                'The option "blackknight467.s3_image.read_bucket_name" must be set.'
            );
        }
        $container->setParameter(
            'blackknight467.s3_image.read_bucket_name',
            $config['amazon_s3']['read_bucket_name']
        );

        // s3 region
        if (!isset($config['amazon_s3']['aws_region'])) {
            throw new \InvalidArgumentException(
                'The option "blackknight467.s3_image.aws_region" must be set.'
            );
        }
        $container->setParameter(
            'blackknight467.s3_image.aws_region',
            $config['amazon_s3']['aws_region']
        );

        // base url
        if (!isset($config['amazon_s3']['base_url'])) {
            throw new \InvalidArgumentException(
                'The option "blackknight467.s3_image.base_url" must be set.'
            );
        }
        $container->setParameter(
            'blackknight467.s3_image.base_url',
            $config['amazon_s3']['base_url']
        );

        // image sizes
        if (!isset($config['image_sizes'])) {
            throw new \InvalidArgumentException(
                'The option "blackknight467.s3_image.image_sizes" must be set.'
            );
        } else {
            if (count($config['image_sizes']) < 1) {
                throw new \InvalidArgumentException(
                    'At least one image size must be provided for blackknight467.s3_image.image_sizes'
                );
            }
        }
        $container->setParameter(
            'blackknight467.s3_image.image_sizes',
            $config['image_sizes']
        );

        //optional AWS configs(if you're on aws, these are not required as long as you have permissions set up right)
        if (isset($config['amazon_s3']['aws_key'])) {
            $container->setParameter(
                'blackknight467.s3_image.aws_key',
                $config['amazon_s3']['aws_key']
            );
        }
        if (isset($config['amazon_s3']['aws_secret_key'])) {
            $container->setParameter(
                'blackknight467.s3_image.aws_secret_key',
                $config['amazon_s3']['aws_secret_key']
            );
        }
        if (isset($config['amazon_s3']['root_path'])) {
            $container->setParameter(
                'blackknight467.s3_image.root_path',
                $config['amazon_s3']['root_path']
            );
        }
        if (isset($config['image_cdn'])) {
            $container->setParameter(
                'blackknight467.image_cdn',
                $config['image_cdn']
            );
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
