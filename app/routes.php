<?php

foreach (glob("../app/controllers/*.controller.php") as $filename) {
    require_once($filename);
}

$app = new Application();

// GET Requests

// Base URL
$app->router->get("/", [ViewController::class, "index"]);

// Authentication
$app->router->get("/inloggen", [ViewController::class, "login"]);
$app->router->get("/uitloggen", [AuthController::class, "logout"]);

// Overviews
$app->router->get("/overzicht", [OverviewHandler::class, "getOverview"]);

// Assigments
$app->router->get("/opdracht/:id/afwijzen", [CoordController::class, "declineAssignment"]);
$app->router->get("/opdracht/:id/behandelen", [CoordController::class, "AssigmentInProgress"]);

$app->router->get("/opdracht/:id/aanmelden", [MemberController::class, "participateAssignment"]);
$app->router->get("/opdracht/:id/afmelden", [MemberController::class, "deregister"]);
$app->router->get("/opdrachten", [MemberController::class, "getRegisteredOverview"]);

$app->router->get("/leden", [CoordController::class, "getRegistry"]);


// Requests
$app->router->get("/opdracht/aanvragen", [ViewController::class, "addRequest"]);


$app->router->post("/role/change", [AuthController::class, "changeActiveRole"]);


// Details
$app->router->get("/opdracht/:id/details-lid", [MemberController::class, "getRequestDetails"]);

// Requests
$app->router->get("/addRequest", [ViewController::class, "addRequest"]);
$app->router->get("/opdracht/:id/annuleren", [ClientController::class, "cancelAssignment"]);

// POST Requests
$app->router->post("/inloggen", [AuthController::class, "login"]);

$app->router->post("/addRequest", [RequestController::class, "addRequest"]);

// Exceptions

$app->router->notFoundHandler([ExceptionController::class, "_404"]);
?>

