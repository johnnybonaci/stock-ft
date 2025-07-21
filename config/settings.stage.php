<?php

/**
 * Este archivo se carga solo en el ambiente de "stage"
 * y pisa las configuraciones por defecto para el ambiente PROD.
 */

/**
 * Configuracion del comportamiento de log de errores.
 */
$settings['DefaultErrorHandler'] = [
    // SHOULD BE set to FALSE for the production environment
    'display_error_details' => true,
    // SHOULD BE set to FALSE for the test environment
    // true => en la clase errorHandle intenta crear un log
    'log_errors' => false,
    // Display error details in error log
    'log_error_details' => true,
];
