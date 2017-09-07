<?php
namespace Authorizationzf3;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;


return [
    'router' => [
      'routes' => [

      ],
    ],
    'doctrine' => [
      'driver' => [
        __NAMESPACE__ . '_driver' => [
          'class' => AnnotationDriver::class,
          'cache' => 'array',
          'paths' => [__DIR__ . '/../src/Entity']
        ],
        'orm_default' => [
          'drivers' => [
            __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
          ]
        ]
      ]
    ],
    'service_manager' => [
      'factories' => [
        \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
        Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
        Service\AuthManager::class => Service\Factory\AuthManagerFactory::class
      ],
      'aliases' => [
        'authmanager' => Service\AuthManager::class
      ],
    ],

];
