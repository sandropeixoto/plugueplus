<?php

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CategoriaController.php';
require_once __DIR__ . '/../controllers/ServicoController.php';
require_once __DIR__ . '/../controllers/CarregadorController.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/ComentarioController.php';

$router = new Router();

$router->add('POST', '/auth/register', fn () => AuthController::register());
$router->add('POST', '/auth/login', fn () => AuthController::login());
$router->add('GET', '/auth/me', fn () => AuthController::profile(), true);

$router->add('GET', '/categorias', fn () => CategoriaController::index());
$router->add('POST', '/categorias', fn () => CategoriaController::store(), true);

$router->add('GET', '/servicos', fn () => ServicoController::index());
$router->add('POST', '/servicos', fn () => ServicoController::store(), true);

$router->add('GET', '/carregadores', fn () => CarregadorController::index());
$router->add('POST', '/carregadores', fn () => CarregadorController::store(), true);

$router->add('GET', '/posts', fn () => PostController::index());
$router->add('POST', '/posts', fn () => PostController::store(), true);

$router->add('GET', '/comentarios', fn () => ComentarioController::index());
$router->add('POST', '/comentarios', fn () => ComentarioController::store(), true);

return $router;
