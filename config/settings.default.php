<?php

use Pilot\Component\Logger\Types\LoggerType;
use Pilot\Component\VarEnvironment\Factories\VarEnvironmentFactory;

// Configure defaults for the whole application.

// Error reporting
// error_reporting(0);
// ini_set('display_errors', '0');
// ini_set('display_startup_errors', '0');
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if ($errno == E_WARNING) throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Timezone
date_default_timezone_set('UTC');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';


$settings['application'] = [
    'app_name' => 'hub-ft-factory-stock',
    'version' => '1.0.0',
];

/**
 * Configuracion para el log de X-Ray para registro de tracking de llamadas entre aplicaciones.
 */
$settings['XrayMiddleware'] = [
    'app_name' => $settings['application']['app_name'],
    'version' => $settings['application']['version'],
    'sampling_percentage' => 100, // # the percentage of requests to trace
];

/**
 * Configuracion del comportamiento de log de errores.
 */
$settings['DefaultErrorHandler'] = [
    // SHOULD BE set to FALSE for the production environment
    'display_error_details' => false,
    // SHOULD BE set to FALSE for the test environment
    // true => en la clase errorHandle intenta crear un log
    'log_errors' => true,
    // Display error details in error log
    'log_error_details' => false,
];

// Logger settings
$settings['logger'] = [
    'name' => $settings['application']['app_name'],
    'handler' => LoggerType::CLOUDWATCH_LOGGER,
    'cloudwatch_config' => [
        'aws' => [
            'version' => 'latest',
            'region' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_CLOUDWATCH_REGION'),
            'credentials' => [
                'key' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_SDK_CLOUDWATCH_KEY'),
                'secret' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_SDK_CLOUDWATCH_SECRET'),
            ],
        ],
        'group_name' => VarEnvironmentFactory::create()->get('GLOBAL_AWS_CLOUDWATCH_LOG_GROUP_NAME'),
        'stream_name' => '',
    ],
];

/**
 * Verificacion de la aplicacion que determina si el usuario logeado tiene acceso al modulo
 * Indicar el codigo de objeto tipo 1, de la tabla de objetos.
 */
$settings['ModuleSecurityMiddleware'] = [
    'module_code' => 'hub_bi_factory_stock',
];


return $settings;
