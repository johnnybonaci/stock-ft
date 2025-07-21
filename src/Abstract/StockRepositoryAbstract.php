<?php

namespace App\Abstract;

use App\Domain\Stock\Repository\StockModel;
use Pilot\Component\VarEnvironment\VarEnvironment;
use Pilot\CacheManager\Entities\Cache;
use Pilot\Component\Sql\Core\Sql;
use Psr\Container\ContainerInterface;

/**
 * Repository.
 */
abstract class StockRepositoryAbstract
{
    protected const CONST_MAX_CACHE_DAYS = 60 * 60 * 24 * 7; // 7 days

    private ContainerInterface $container;

    /**
     * The constructor.
     *
     * @param ContainerInterface $container Dependency Injections container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function getModel(string $dsn): StockModel
    {
        $connection = $this->container->get(Sql::class)->open($dsn, 'hub');
        $model = new StockModel($connection);

        $model->setCacheManager($this->cacheInstance());
        return $model;
    }

    private function cacheInstance()
    {
        $cacheInstance = $this->container->get(Cache::class);

        return $cacheInstance
                ->setEnabled($this->container->get(VarEnvironment::class)->get($this->cacheVarEnvName()) == 'yes')
                ->setTtl(self::CONST_MAX_CACHE_DAYS)
                ->setCacheKeyPrefix($this->cacheKeyPrefix());
    }

    private function cacheVarEnvName(): string
    {
        return strtoupper(sprintf('%s_CACHE_ENABLED', $this->cacheKeyPrefix()));
    }

    private function cacheKeyPrefix(): string
    {
        return str_replace('-', '_', $this->container->get('settings')['application']['app_name']);
    }
}
