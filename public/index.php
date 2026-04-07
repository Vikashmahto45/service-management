<?php
require_once '../app/config/config.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/url_helper.php';
require_once '../app/helpers/rbac_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className){
    // Try to load from core
    if (file_exists('../app/core/' . $className . '.php')) {
        require_once '../app/core/' . $className . '.php';
    } 
    // Try to load from controllers
    elseif (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
    }
    // Try to load from models
    elseif (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
    }
});

// Init Core Library
$init = new App();
