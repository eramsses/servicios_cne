<?php
/**
 * Zend Framework (http://framework.zend.com/]
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c] 2005-2014 Zend Technologies USA Inc. (http://www.zend.com]
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            
            //Ruta para el controlador Consultar
            'Consultar' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/consultar',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Consultar',
                        'action' => 'consultar',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                //SUB RUTAS
                //Subruta  getdatoslocal
                'getdatoslocal' => [
                    'type' => 'Segment',
                    'options' => [
                        'route' => '/getdatoslocal',
                        'defaults' => [
                            'controller' => 'Application\Controller\Consultar',
                            'action' => 'getdatoslocal',
                        ],
                    ],
                ],
//Fin subruta getdatoslocal
//Subruta getinfoservice
                    'getinfoservice' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/getinfoservice[/:identidad[/:solicitante]]',
                            'defaults' => [
                                'controller' => 'Application\Controller\Consultar',
                                'action' => 'getinfoservice',
                            ],
                        ],
                    ],
//Fin Subruta getinfoservice
                    
                    
                //FIN SUBRUTAS
                ],
            ], //Fin controlador Consultar
            
            
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
        'factories' => array(
            'Zend\Db\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ],
    'translator' => [
        'locale' => 'es_ES',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Consultar' => 'Application\Controller\ConsultarController',
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // Placeholder for console routes
    'console' => [
        'router' => [
            'routes' => [
            ],
        ],
    ],
];
