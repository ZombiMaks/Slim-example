<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

$companies = [
    [ 'name' => 'Adams-Reichel','phone' => '1-986-987-9109 x56053' ],
    [ 'name' => 'Dibbert-Morissette','phone' => '439.584.3132 x735' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Kiehn-Mann','phone' => '972-379-1995 x61054' ],
    [ 'name' => 'Bosco, Pouros and Larson','phone' => '887-919-2730 x49977' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Adams-Reichel','phone' => '1-986-987-9109 x56053' ],
    [ 'name' => 'Dibbert-Morissette','phone' => '439.584.3132 x735' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Kiehn-Mann','phone' => '972-379-1995 x61054' ],
    [ 'name' => 'Bosco, Pouros and Larson','phone' => '887-919-2730 x49977' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Adams-Reichel','phone' => '1-986-987-9109 x56053' ],
    [ 'name' => 'Dibbert-Morissette','phone' => '439.584.3132 x735' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Kiehn-Mann','phone' => '972-379-1995 x61054' ],
    [ 'name' => 'Bosco, Pouros and Larson','phone' => '887-919-2730 x49977' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Adams-Reichel','phone' => '1-986-987-9109 x56053' ],
    [ 'name' => 'Dibbert-Morissette','phone' => '439.584.3132 x735' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
    [ 'name' => 'Kiehn-Mann','phone' => '972-379-1995 x61054' ],
    [ 'name' => 'Bosco, Pouros and Larson','phone' => '887-919-2730 x49977' ],
    [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ]
];

$app->get('/companies', function ($request, $response) use ($companies) {
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);
    $offset = ($page - 1) * $per;

    $sliceOfCompanies = array_slice($companies, $offset, $per);
    $response->write(json_encode($sliceOfCompanies));
    return $response;
});

$app->run();