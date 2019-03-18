<?php
// http://slimapp/api/post/2

use Slim\Http\Request;
use Slim\Http\Response;


$app = new \Slim\App;

// show uncaught exceptions
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

# Get Single Post
$app->get('/api/post/{id}', function (Request $request, Response $response, array $args) {

    // Get ID (attribute that's what we use for the URL)
//  $id = $request->getAttribute('id');
    $id = $args['id'];

    // Create query
//  $sql = 'SELECT * FROM posts WHERE id = ' . $id .'';
    $sql = 'SELECT * FROM posts WHERE id = ?';

    try {
        // Instantiate db connect
        // Get db Object
        $pdo = new DB();
        // Connect
        $pdo = $pdo->connect();

        // Prepare statement
        $stmt = $pdo->prepare($sql);

        // Connect End
        $pdo = null;

        // Bind ID
        $stmt->bindParam(1, $id);

        // Execute query
        $stmt->execute();

        // fetching one array
        $post = $stmt->fetch();

        // both ways are right
        //  return $response->withStatus(200)->withJson($post);
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($post));

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});