<?php
declare(strict_types=1);

interface ContainerExtensionCache_interface
{

    public function getCacheContent(ContainerExtensionCache_abstract $cacheObj, array &$scope, bool $forceCreate = false);
}
