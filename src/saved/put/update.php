<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app = new \Slim\App;

// show uncaught exceptions
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

# Update a Post
// http://slimapp/api/post/put/3
// method: PUT
//             Headers  Key: Content-Type  Value: application/json
//             Body  raw
//   {
//     "title": "Post Updated",
//     "body": "Post edited by Me",
//     "author": "Me",
//     "category_id": "17"
//   }

$app->put('/api/post/put/{id}', function (Request $request, Response $response, $args) {
    // Get ID (attribute that's what we use for the URL)
    // $id = $request->getAttribute('id');
    $id = $args['id'];

    // Get the parameters through a form
    $title = $request->getParam('title');
    $body = $request->getParam('body');
    $author = $request->getParam('author');
    $category_id = $request->getParam('category_id');


    // Create query
    $sql = "UPDATE posts
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
            WHERE
                id = :id";

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

        // Clean data
        $title = htmlspecialchars(strip_tags($title));
        $body = htmlspecialchars(strip_tags($body));
        $author = htmlspecialchars(strip_tags($author));
        $category_id = htmlspecialchars(strip_tags($category_id));


        // Execute query
        $stmt->execute(['id' => $id, 'title' => $title, 'body' => $body, 'author' => $author, 'category_id' => $category_id]);

        echo '{"notice": {"text": "Post Updated"}';

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});