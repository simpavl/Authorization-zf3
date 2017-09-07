<?php
namespace Authorizationzf3;

return [
    'access_filter' => [
    'authcontroller' => [
      \Application\Controller\TestController::class => [
        'action' => 'test',
      ],
    ],
    'controllers' => [
        /*Controller\AdminController::class => [
            // Give access to "resetPassword", "message" and "setPassword" actions
            // to anyone.
            'user' => ['index'],
            'admin' => ['index', 'editSubCategory', 'addCategory', 'editCategory',
                'deleteCategory','usersList', 'addUser', 'viewUser', 'editUser','editSubCategory',
                'addSubCategory','addProduct','editProduct','categories','subcategories','users','products',
                'orders','productImages','addProductImages','editProdimg', 'removeProdimg','deleteProduct','editOrder'
                ,'deleteOrder','orderProducts','editOrderProd','deleteOrderProd'],
        ],*/
        \Application\Controller\IndexController::class => [
          'user' => ['index'],
          'admin' => ['index'],
        ],
    ]
  ],
]
