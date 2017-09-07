<?php

namespace Authorizationzf3;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\Controller\AbstractActionController;
use Authorizationzf3\Service\AuthManager;

class Module
{

  public function onBootstrap(MvcEvent $e){
    $eventManager = $e->getApplication()->getEventManager();
    $moduleRouteListener = new ModuleRouteListener;
    $moduleRouteListener->attach($eventManager);
    $sharedEventManager = $eventManager->getSharedManager();
    // Registering handler-method.
    $sharedEventManager->attach(AbstractActionController::class,
        MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
  }
  public function getConfig(){
    return include __DIR__ . '/../config/module.config.php';
  }

  public function onDispatch(MvcEvent $event)
  {
      // Получаем контроллер и действие, которому был отправлен HTTP-запрос.
      $controller = $event->getTarget();
      $controllerName = $event->getRouteMatch()->getParam('controller', null);
      $actionName = $event->getRouteMatch()->getParam('action', null);
      $config = $event->getApplication()->getServiceManager()->get('Config');
      if (isset($config['access_filter']['authcontroller']))
          $config = $config['access_filter'];
      else
          throw new \Exception('Auth controller property isnt set');
      //preparing controller name from config for return expression
      preg_match('/\w+$/',key($config['authcontroller']),$redirectController);
      $redirectController = strtolower(preg_replace('/Controller/','',$redirectController[0]));
      // Converting action to camel register.
      $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
      //getting options for return such as action name
      $redirectOptions = $config['authcontroller'][key($config['authcontroller'])];
      // Getting AuthManager exampler.
      $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
      if (!$authManager->filterAccess($controllerName, $actionName)) {

          // Remembering URL link, which used tried to achive. We will redirect user
          // to this Url after successfull auth.
          $uri = $event->getApplication()->getRequest()->getUri();
          // Doing URL relative(removing scheme, user info, host name and port),
          // to prevent redirect on other domain.
          $uri->setScheme(null)
              ->setHost(null)
              ->setPort(null)
              ->setUserInfo(null);
          $redirectUrl = $uri->toString();

          // Redirect user to the "Login" page.
          return $controller->redirect()->toRoute($redirectController,$redirectOptions,
              ['query'=>['redirectUrl'=>$redirectUrl]]);
      }
  }

}
