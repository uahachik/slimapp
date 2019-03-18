<?php
/** When we create a restful api we create routes that deal with request and
  response objects. First two lines bringing in that request and response object.
  * Every Slim app, every route is given these two objects as arguments to the
  callback routine. Slim supports PRS7 which is PHP standard for HTTP messaging,
  and that's actual a really great approach for writing web applications.
  * "autoload.php" is created by composer and allows us to refer to the slim dependencies.
  * Then "$app" is creating main slim object. So this is we can use to create our routes.
  * "$app->get($route${dynamic variable}, callback func(Request, Response)" is a route for a get request
  * Then is taking the response, calling "getBody", write to the screen */

/*use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();*/


use Slim\Http\Request;
use Slim\Http\Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

// Register db config
 require __DIR__ . '/../src/config/DB.php';

 // Register routes
require __DIR__ . '/../src/routes/posts.php';

//require __DIR__ . '/../src/routes/get/read.php';
//require __DIR__ . '/../src/routes/post/add.php';
//require __DIR__ . '/../src/routes/put/update.php';
// require __DIR__ . '/../src/routes/get/update.php';
 //require __DIR__ . '/../src/routes/get/delete.php';

 // Run app
 $app->run();



