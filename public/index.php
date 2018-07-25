<?php
namespace App;

require __DIR__ . '/../vendor/autoload.php';

use function Stringy\create as s;

$users = Generator::generate(100);

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

// Начало сессии в PHP
session_start(); // по умолчанию не требует хранения сессии

// регистрация
$app = new \Slim\App($configuration);

$repo = new Repository();

$container = $app->getContainer();

$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$app->get('/', function ($request, $response) {

    // Извлечение flash сообщений установленных на предыдущем запросе
    $flash = $this->flash->getMessages();

    // выводит флеш сообщения в шаблон
    $params = ['flash' => $flash];

    return $this->renderer->render($response, 'index.phtml', $params);
});

$app->get('/users', function ($request, $response) use ($repo) {
    
    $params = [
        'users' => $repo->all()
    ];
    return $this->renderer->render($response, 'users/index.phtml', $params);
});

$app->post('/users', function ($request, $response) use ($repo) {
    $validator = new Validator();
    $user = $request->getParsedBodyParam('user');
    $errors = $validator->validate($user);
    if (count($errors) === 0) {
        $repo->save($user);
        // Добавление flash сообщения. Оно станет доступным на следующий HTTP запрос.
        $this->flash->addMessage('success', 'Usser Added');
        
        return $response->withRedirect('/');
    }
    $params = [
        'user' => $user,
        'errors' => $errors
    ];
    return $this->renderer->render($response, "users/new.phtml", $params);
});

$app->get('/users/new', function ($request, $response) {
    $params = [
        'user' => [],
        'errors' => []
    ];
    return $this->renderer->render($response, "users/new.phtml", $params);
});

$app->run();




