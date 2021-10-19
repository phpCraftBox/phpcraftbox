<?php
declare(strict_types=1);

/**
 * Class ContainerExtensionCache_abstract
 * @method mixed get()
 */
abstract class ContainerExtensionCache_abstract extends Base
{
    const TARGET_INTERN = 1;
    const TARGET_EXTERN = 2;

    protected array                                     $parameter           = [];
    protected string                                    $ident               = '';
    protected                                           $cacheContent        = null;
    protected int                                       $target              = self::TARGET_INTERN;
    protected int                                       $ttl                 = 0;
    protected                                           $ttlDatetime;
    protected int                                       $size                = 0;
    protected bool                                      $persistent          = false;
    protected string                                    $dataVariableUpdated = '';
    protected bool                                      $isCreated           = false;
    protected static ?ContainerExtensionCache_interface $cacheResource       = null;

    public function __construct(...$parameter)
    {
        $this->parameter = $parameter;
        $this->prepare();

        if (self::$cacheResource === null) {

            $cacheSource = 'sqlite';
            if (Config::get('/ContainerExtensionCache/source') === 'redis') {
                if (ContainerExtensionCacheRedis::connection()) {
                    $cacheSource = 'redis';
                };
            }
            elseif (Config::get('/ContainerExtensionCache/source') === 'memcached') {
                if (ContainerExtensionCacheMemcached::connection()) {
                    $cacheSource = 'memcached';
                };
            }

            if ($cacheSource === 'redis') {
                self::$cacheResource = new ContainerExtensionCacheRedis();
            }
            elseif ($cacheSource === 'memcached') {
                self::$cacheResource = new ContainerExtensionCacheMemcached();
            }
            else {
                self::$cacheResource = new ContainerExtensionCacheSqlite();
            }

            if ($cacheSource !== Config::get('/ContainerExtensionCache/source')) {
                CoreDebugLog::addLog('/System/Cache',
                                     'Fallback from ' . Config::get('/ContainerExtensionCache/source'),
                                     CoreDebugLog::LOG_TYPE_WARNING);

            }
        }

    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    /**
     * @return string
     */
    public function getTtlDatetime(): string
    {
        return (empty($this->ttlDatetime) ? '0000-00-00 00:00:00' : $this->ttlDatetime);
    }

    /**
     * @param string $datetime
     *
     * @return void
     */
    public function setTtlDatetime(string $datetime): void
    {
        $this->ttlDatetime = $datetime;
    }

    /**
     * @param array $scope
     * @param false $forceCreate
     *
     * @return mixed
     * @throws DetailedException
     * @CMSprofilerSet          _class ContainerExtensionCache
     * @CMSprofilerSetFromScope cacheClassName
     * @CMSprofilerSetFromScope cacheName
     * @CMSprofilerSetFromScope isCreated
     */
    public function _get(array &$scope, bool $forceCreate = false)
    {

        self::$cacheResource->get($this,
                                  $scope,
                                  $forceCreate);

        return $this->cacheContent;
    }

    abstract function prepare(): void;

    abstract function create(): void;

    /**
     * @return int
     */
    public function getTarget(): int
    {
        return $this->target;
    }

    /**
     * @param int $target
     */
    public function setTarget(int $target): void
    {
        $this->target = $target;
    }

    /**
     * @param $cacheContent
     */
    public function setCacheContent($cacheContent): void
    {
        $this->cacheContent = $cacheContent;
    }

    /**
     * @return bool
     */
    public function getPersistent(): bool
    {
        return $this->persistent;
    }

    /**
     * @param bool $persistent
     */
    public function setPersistent(bool $persistent): void
    {
        $this->persistent = $persistent;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getDataVariableUpdated(): string
    {
        return $this->dataVariableUpdated;
    }

    /**
     * @param string $dataVariableUpdated
     */
    public function setDataVariableUpdated(string $dataVariableUpdated): void
    {
        $this->dataVariableUpdated = $dataVariableUpdated;
    }

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->isCreated;
    }

    /**
     * @param bool $isCreated
     */
    public function setIsCreated(bool $isCreated): void
    {
        $this->isCreated = $isCreated;
    }

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * @return string
     */
    public function getCacheContent()
    {
        return $this->cacheContent;
    }

}
