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
$app->router->get("/overzicht-lid-ingeschreven", [MemberController::class, "getRegisteredOverview"]);
// Assigments
$app->router->get("/opdracht/:id/afwijzen", [CoordController::class, "declineAssignment"]);
$app->router->get("/opdracht/:id/behandelen", [CoordController::class, "AssigmentInProgress"]);

$app->router->get("/opdracht/:id/aanmelden", [MemberController::class, "participateAssignment"]);
$app->router->post("/opdracht/afmelden", [MemberController::class, "deregister"]);
$app->router->get("/opdrachten", [MemberController::class, "getRegisteredOverview"]);

$app->router->get("/leden", [CoordController::class, "getRegistry"]);
$app->router->get("/leden/aanmaken", [CoordController::class, "addMember"]);
$app->router->post("/leden/aanmaken", [CoordController::class, "createMember"]);

// Requests
$app->router->get("/opdracht/aanvragen", [ViewController::class, "addRequest"]);
$app->router->get("/opdracht/:id/annuleren", [ClientController::class, "cancelAssignment"]);

// Details
$app->router->get("/opdracht/:id/details-lid", [MemberController::class, "getRequestDetails"]);
$app->router->get("/opdracht/:id/details-lid-assigned", [MemberController::class, "getRequestDetailsAssigned"]);
$app->router->get("/opdracht/:id/details-coordinator", [CoordController::class, "getRequestDetailsAcceptDeny"]);
$app->router->get("/opdracht/:id/details-client", [ClientController::class, "getRequestDetails"]);
$app->router->get("/lid/:email/details", [CoordController::class, "getMemberAndRequestDetails"]);

// Requests
$app->router->get("/opdracht/aanvragen", [ViewController::class, "addRequest"]);
$app->router->post("/role/:role", [AuthController::class, "changeActiveRole"]);
$app->router->get("/addRequest", [ViewController::class, "addRequest"]);
$app->router->get("/opdracht/:id/annuleren", [ClientController::class, "cancelAssignment"]);


// POST Requests
$app->router->post("/inloggen", [AuthController::class, "login"]);
$app->router->post("/opdracht/aanvragen", [RequestController::class, "addRequest"]);
$app->router->post("/role/change", [AuthController::class, "changeActiveRole"]);

// Exceptions

$app->router->notFoundHandler([ExceptionController::class, "_404"]);
?>

