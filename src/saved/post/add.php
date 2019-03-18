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

# Create a Post
// http://slimapp/api/post/add
// method: POST
//             Headers  Key: Content-Type  Value: application/json
//             Body  raw
//   {
//     "title": "Post Adding",
//     "body": "Post added by Slim",
//     "author": "Slim & Me",
//     "category_id": "17"
//   }

$app->post('/api/post/add', function (Request $request, Response $response, $args) {

    // Get the parameters through a form
    $title = $request->getParam('title');
    $body = $request->getParam('body');
    $author = $request->getParam('author');
    $category_id = $request->getParam('category_id');

    // Create query
    $sql = 'INSERT INTO posts
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id';

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

        // Bind data
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':category_id', $category_id);

        // Execute query
        $stmt->execute();

        echo '{"notice": {"text": "Post Added"}';

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
