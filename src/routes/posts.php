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


# Get All Posts
// http://slimapp/api/posts
// method: GET

$app->get('/api/posts', function (Request $request, Response $response) {

    // Create query
    $sql = 'SELECT * FROM posts';

    try {
        // Instantiate db connect
        // Get db Object
        $pdo = new DB();
        // Connect
        $pdo = $pdo->connect();

        // Prepare statement
        $stmt = $pdo->query($sql);

        // Connect End
        $pdo = null;

        // Execute query
        $posts = $stmt->fetchAll();

        // both ways are right
        //  return $response->withStatus(200)->withJson($post);
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($posts));

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});


# Get Single Post
// http://slimapp/api/post/2
// method: GET

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

        // fetching one post
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


# Delete a Post
// http://slimapp/api/post/del/3
// method: DELETE

$app->delete('/api/post/del/{id}', function (Request $request, Response $response, array $args) {

    // Get ID (attribute that's what we use for the URL)
//  $id = $request->getAttribute('id');
    $id = $args['id'];

    // Create query
//  $sql = 'SELECT * FROM posts WHERE id = ' . $id .'';
    $sql = 'DELETE FROM posts WHERE id = ?';

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

        echo '{"notice": {"text": "Post Deleted"}';

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});