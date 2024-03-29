<?php

header('Access-Control-Allow-Origin: *');

// init
include __DIR__.'/../bootstrap.php';
include FRAME_DIR.'/http/php_fpm/application.php';

set_error_handler('http_err_action', E_ALL);
set_exception_handler('http_ex_action');
register_shutdown_function('http_fatal_err_action');

if_has_exception(function ($ex) {

    log_exception($ex);

    return json([
        'succ' => false,
        'msg' => $ex->getMessage(),
    ]);
});

if_verify(function ($action, $args) {

    $data = call_user_func_array($action, $args);

    if (is_string($data)) {
        return $data;
    }

    header('Content-type: application/json');

    return json($data);
});

// init interceptor

// init 404 handler
if_not_found(function () {
    return json([
        'succ' => false,
        'msg' => '404 not found',
    ]);
});

// init controller
include CONTROLLER_DIR.'/work.php';
include CONTROLLER_DIR.'/talk.php';
include CONTROLLER_DIR.'/wechat.php';
include CONTROLLER_DIR.'/slack.php';
include CONTROLLER_DIR.'/index.php';

// fix
not_found();
