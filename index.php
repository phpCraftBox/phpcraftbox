<?php

ini_set('memory_limit',
        '512M');
ini_set("max_execution_time",
        720);

require(dirname(__FILE__) . '/Library/config.inc.php');

Config::setDatabase();
ContainerFactoryLanguage::setCore();

if (ContainerFactorySession::check()) {
    if (!ContainerFactorySession::get('/user/id')) {
        ContainerFactorySession::destroy();
    }
    else {
        Container::getInstance('ContainerFactoryUser',
                               ContainerFactorySession::get('/user/id'));
    }
}
else {
    Container::getInstance('ContainerFactoryUser',
                           0);
}

/** @var ContainerFactoryUser $user */
$user = Container::getInstance('ContainerFactoryUser',
                               0);

CoreDebugLog::addLog('User',
                     'Login as # ' . $user->getUserId() . ', ' . $user->getUserName() . ' | User Group # ' . $user->getUserGroupId() . ', ' . $user->getUserGroupName() . ' | Access: ' . implode(', ',
                                                                                                                                                                                                  array_flip($user->getUserAccess())));

$cookieBannerRequest = new ContainerFactoryRequest(ContainerFactoryRequest::REQUEST_TYPE_COOKIE,
                                                   Config::get('/environment/cookie/name') . 'cookiebanner');

if (ContainerFactorySession::check()) {
    $user = (int)ContainerFactorySession::get('/user/id');
}
else {
    $user = 0;
}

$container = new Container();
$container->register(ContainerFactoryRouter::class,
                     new ContainerFactoryRouter(Config::get('/server/http/path')));
$container->register(ContainerFactoryUser::class,
                     $user);
$container->register(ContainerIndexPage::class);
$container->register(ContainerIndexPage::class);


$container = Container::DIC([
                                '/User'                      => $user,
                                '/Config'                    => new Config(),
                                '/Router'                    => new ContainerFactoryRouter(Config::get('/server/http/path')),
                                '/Page'                      => new ContainerIndexPage(),
                                '/Cookie/CookieBanner'       => (int)($cookieBannerRequest->exists()),
                                '/Cookie/CookieBanner/value' => (int)($cookieBannerRequest->get()),
                            ]);

/** @var ContainerFactoryRouter $router */
$router = Container::getInstance('ContainerFactoryRouter');

if (Config::get('/server/http/path') !== '') {
    $router->analyzeUrl(Config::get('/server/http/path'));
}

ContainerFactoryUserConfig::setDatabase();


switch (Container::getInstance('ContainerFactoryRouter')
                 ->getTarget()) {
    default:
    case 'index':
        CoreIndex::execute();
        exit;
        break;
    case 'ajax':
        $classPhp = $router->getApplication() . '_ajax';
        /** @var ContainerExtensionAjax_abstract $classPhpAjax */
        $classPhpAjax = new $classPhp();
        print $classPhpAjax->get();
        break;
    case 'free':
        $class  = $router->getApplication() . '_free';
        $object = Container::get($class);
//
//        \Event::trigger('allExecFree',
//                        'allExecFree',
//                        'allExecFree',
//                        null,
//                        $scope);
//        \Event::trigger('allExec',
//                        'allExec',
//                        'allExec',
//                        null,
//                        $scope);
        break;
}
