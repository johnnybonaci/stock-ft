<?php

namespace App\Domain\Stock\Service;

use App\Domain\Stock\Repository\StockModel;
use Pilot\CacheManager\Entities\Cache;
use Pilot\Component\Sql\Core\Sql;
use Psr\Container\ContainerInterface;

final class CacheService
{
    public const CONST_MAX_CACHE_DAYS = 60 * 60 * 24 * 7; // 7 days

    private ContainerInterface $container;

    public function __construct(
        ContainerInterface $container,
    ) {
        $this->container = $container;
    }

    /**
     * Invalidate cache all.
     */
    public function deleteAll(): bool
    {
        $this->cacheInstance()->deleteByTag($this->cacheKeyPrefix());

        return true;
    }

    /**
     * Invalidate cache instance by dsn.
     *
     * @param string $dsn
     */
    public function deleteByDsn(string $dsn): bool
    {
        $this->modelInstance($dsn)->cacheDeleteByDsn();

        return true;
    }

    /**
     * Modelo instance.
     *
     * @param string $dsn
     *
     * @return StockModel
     */
    private function modelInstance(string $dsn): StockModel
    {
        $ConnectionManager = $this->container->get(Sql::class)->open($dsn);
        $model = new StockModel($ConnectionManager);

        // solo en el caso que la entidad maneje una capa de persistencia de cache
        $model->setCacheManager($this->cacheInstance());

        return $model;
    }

    /**
     * Cache Manager Instance.
     *
     * @return Cache
     */
    private function cacheInstance(): Cache
    {
        $cacheInstance = $this->container->get(Cache::class);

        return $cacheInstance
                ->setEnabled($this->container->get('VarEnvironment')->get($this->cacheVarEnvName()) == 'yes')
                ->setTtl(self::CONST_MAX_CACHE_DAYS)
                ->setCacheKeyPrefix($this->cacheKeyPrefix());
    }

    private function cacheVarEnvName(): string
    {
        return strtoupper(sprintf('%s_CACHE_ENABLED', $this->cacheKeyPrefix()));
    }

    private function cacheKeyPrefix(): string
    {
        return $this->container->get('settings')['application']['app_name'];
    }
}
