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
/*
$app = new \Slim\App($configuration);
// Подключение шаблонизатора к слиму. Указываем папку с шаблонами
$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');


// Поиск пользователей
$app->get('/search', function ($request, $response) use ($users){
    $term = $request->getQueryParam('term', '');
    $sortedUsers = collect($users)->sortBy('firstName');
    $userSearch = collect($sortedUsers)->filter(function ($user) use ($term) {
        return s($user['firstName'])->startsWith($term, false);
    });
    $params = [
        'userSearch' => $userSearch,
        'term' => $term
        ];
    return $this->renderer->render($response, 'users/search.phtml', $params);
});

// Выводит список пользователей
$app->get('/users', function ($request, $response) use ($users) {
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);
    $offset = ($page - 1) * $per;

    $sliceOfUsers = array_slice($users, $offset, $per);
    $params = [
        'users' => $sliceOfUsers,
        'page' => $page
    ];
    return $this->renderer->render($response, 'users/index.phtml', $params);
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

*/
// регистрация
$app = new \Slim\App($configuration);

$repo = new Repository();

$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');

$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'index.phtml');
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


