<?php

declare(strict_types=1);

namespace Matchish\ScoutElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Aws\ElasticsearchService\ElasticsearchPhpHandler;


final class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/elasticsearch.php', 'elasticsearch');

        $this->app->bind(Client::class, function () {
                        $handler = new ElasticsearchPhpHandler('us-east-1');
            return ClientBuilder::create()->setHandler($handler)->setHosts([config('elasticsearch.host')])->build();
        });

        $this->app->bind(
            'Matchish\ScoutElasticSearch\ElasticSearch\HitsIteratorAggregate',
            'Matchish\ScoutElasticSearch\ElasticSearch\EloquentHitsIteratorAggregate'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/elasticsearch.php' => config_path('elasticsearch.php'),
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function provides(): array
    {
        return [Client::class];
    }
}
