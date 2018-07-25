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
    $users = $repo->all();
    $term = $request->getQueryParam('term', '');
    $sortedUsers = collect($users)->sortBy('name');
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);
    $offset = ($page - 1) * $per;

    $userSearch = collect($sortedUsers)->filter(function ($user) use ($term) {
        return s($user['name'])->startsWith($term, false);
    });

    $sliceOfUsers = array_slice($users, $offset, $per);
    
    $params = [
        'users' => $users,
        'page' => $page,
        'userSearch' => $userSearch,
        'term' => $term
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

// Поиск пользователей
$app->get('/search', function ($request, $response) use ($users){
    $term = $request->getQueryParam('term', '');
    $sortedUsers = collect($users)->sortBy('name');
    $userSearch = collect($sortedUsers)->filter(function ($user) use ($term) {
        return s($user['firstName'])->startsWith($term, false);
    });
    $params = [
        'userSearch' => $userSearch,
        'term' => $term
        ];
    return $this->renderer->render($response, 'users/search.phtml', $params);
});

// Выводит информацию о пользователях
$app->get('/users/{id}', function ($request, $response, $args) use ($users) {
    $id = (int) $args['id'];
    $user = collect($users)->first(function ($user) use ($id) {
        return $user['id'] == $id;
    });
    $params = ['user' => $user];
    return $this->renderer->render($response, 'users/show.phtml', $params);
});

$app->run();




