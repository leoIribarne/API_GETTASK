<?php
use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->add(new BasepathMiddleware($app));
$app->addErrorMIddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $sql = 'SELECT cd_user, nm_login, ds_email FROM tb_user';

    try {
        $db = new DB();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($users));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});


$app->run();
//php -S localhost:8888 -t public
//utilizado para rodar no prompt de comando(CMD)






?>