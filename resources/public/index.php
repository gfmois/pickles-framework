<?php

use Pickles\Database\Model;
use Pickles\Http\Middleware;
use Pickles\Http\Request;
use Pickles\Http\Response;
use Pickles\Kernel;
use Pickles\Routing\Route;
use Pickles\Validation\Rules\Required;
use Pickles\View\Engine;
use Pickles\View\PicklesEngine;

require_once '../../vendor/autoload.php';

$app = Kernel::bootstrap(__DIR__ . "/../..");
$engine = app(Engine::class);
if (!$engine instanceof PicklesEngine) {
    throw new \RuntimeException("The view engine is not an instance of PicklesEngine.");
}

$engine->setViewsDir(__DIR__ . "/../views/");

Route::GET("/test/{param}", function(Request $request) {
    return json(["result" => $request->routeParams()]);
});

Route::POST("/test", function(Request $request) {
    return Response::json(["result" => $request->data()]);
});

Route::GET("/redirect", function(Request $request) {
    return Response::redirect("/test/asdf");
});

Route::PUT('/test', function(Request $request) {
    return "PUT OK";
});

Route::PATCH('/test', function(Request $request) {
    return "PATCH OK";
});

Route::DELETE('/test', function(Request $request) {
    return "DELETE OK";
});

Route::post('/users/{id}/update', function (Request $request) {
    $user = User::find($request->routeParams('id'));

    $user->name = $request->data('name');
    $user->email = $request->data('email');
    
    return json($user->update()->toArray());
});

Route::delete('/users/{id}/delete', function (Request $request) {
    $user = User::find($request->routeParams('id'));

    return json($user->delete()->toArray());
});

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ($request->headers("authorization") != "asdf") {
            return json(
                [
                    "message" => "Not Authenticated!",
                    "status" => 401
                ]
            )->setStatus(401);
        }

        $response = $next($request);
        $response->setHeader("X-Custom-Header", "Working");

        return $response;
    }
}

class TestMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ($request->headers("authorization") != "asdf") {
            return json(
                [
                    "message" => "Not Authenticated!",
                    "status" => 401
                ]
            )->setStatus(401);
        }

        $response = $next($request);
        $response->setHeader("X-Custom-Header-2", "Working");

        return $response;
    }
}

Route::GET(
    "/middleware", 
    fn(Request $request) => json(["result"=> "Authenticated"])
    )->setMiddlewares([AuthMiddleware::class, TestMiddleware::class]);

Route::GET("/html", fn(Request $request) => view("home", ["user" => "some user"]));

Route::POST("/validation", fn(Request $request) => json($request->validate([
    "test" => "required",
    "num" => "number",
    "email" => ["required_when:num,>,4"],
], [
    "email" => [
        Required::class => "Email is required",
    ]
    ])));

Route::GET("/session", function(Request $request) {
    // session()->flash("tests", "value");
    return json($_SESSION);
});

Route::POST("/form", function (Request $request) {
    return json($request->validate([
        "name"=> "required",
        "email" => ["required", "email"],
    ]));
});

class User extends Model {
    private string $name;
    private string $email;

    public array $fillable = ["name", "email"];

    public function setName(string $name): void {
        $this->name = $name;
    }
    public function getName(): string {
        return $this->name;
    }
    public function setEmail(string $email): void {
        $this->email = $email;
    }
    public function getEmail(): string {
        return $this->email;
    }

}

Route::POST("/users", function (Request $request) {
    return json([
        "result" => User::create($request->data())->toArray(),
    ]);
});

Route::GET("/users/first", function (Request $request) {
    return json([
        "result" => User::firstWhere(column: "name", value: "moises")?->toArray(),
    ]);
});


Route::GET("/users/where", function (Request $request) {
    return json([
        "result" => User::mapModelsToObjects( User::where("name", "mass")),
    ]);
});

Route::GET("/users/{id}", function (Request $request) {
    $id = $request->routeParams()["id"] ?? null;
    return json([
        "result" => User::find($id)?->toArray(),
    ]);
});

Route::GET("/users", function (Request $request) {
    return json([
        "result" => User::mapModelsToObjects(User::all()),
    ]);
});

Route::GET("/env", function (Request $request) {
    return json([
        "result" => config("database.database")
    ]);
});


$app->run();