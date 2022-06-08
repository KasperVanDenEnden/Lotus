<?php
class CoordController extends Controller
{
    public function __construct()
    {
        $this->coordModel = $this->model("coord");
        $this->registerMiddleware(new AuthMiddleware(["getOverview"]));
    }

    public function getOverview()
    {

        $resultSet = $this->coordModel->getAssignmentRequests();

        if (sizeOf($resultSet) > 0) {
            $this->view("coord/overview", $resultSet);
        } else {
            echo "No requests found.";
        }
    }

    public function declineAssignment($data)
    {
        $id = $data["params"]["id"];

        $this->coordModel->declineAssignment($id);

        Application::$app->controller->redirect("/overzicht-coordinator");
    }

    public function acceptAssignment($data)
    {
        $id = $data["params"]["id"];

        $this->coordModel->acceptAssignment($id);
        Application::$app->controller->redirect("/overzicht-coordinator");

    }

    public function getRequestDetailsAcceptDeny($data) {
        $id = $data["params"]["id"];

        $result =  $this->coordModel->getRequestDetailsAcceptDeny($id);

        if ($result) {
            self::view("/coord/requestDetails", $result);
        } else {
            echo "The request with id: ".$id." is not found. Make sure you got the right id!";
        }
    }

    public function getMemberDetails($data) {
        $email = $data["params"]["email"];

        $result = $this->coordModel->getMemberDetails($email);
        if ($result) {
            self::view("/coord/memberDetails", $result);
        } else {
            echo "The request with email: ".$email." is not found. Make sure you got the right email!";
        }
    }

    public function getMemberRequestsByEmail($data) {
        $email = $data["params"]["email"];

        $result = $this->coordModel->getMemberRequestsByEmail($email);
        if ($result) {
            self::view("/coord/memberDetails", $result);
        } else {
            echo "This user has no requests";
        }


    }


}