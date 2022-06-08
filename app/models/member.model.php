<?php

class MemberModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getOpenAssignments()
    {
        $this->db->query("SELECT * FROM request 
                            LEFT JOIN company ON request.companyId = company.companyId
                            LEFT JOIN grimelocation ON request.grimeLocationId = grimelocation.grimeLocationId
                            LEFT JOIN playground ON request.playgroundId = playground.playgroundId
                            LEFT JOIN contact ON request.contactId = contact.contactId
                            LEFT JOIN billingaddress ON request.billingaddressId = billingaddress.billingaddressId
                            WHERE request.approved = 1;");

        $result = $this->db->resultSet();

        return $result;
    }

    public function participateAssignment($id)
    {
        $email = Application::$app->session->get("user");

        $this->db->query("INSERT INTO solicit (email, requestId, assigned) VALUES (:email, :id, 0);");

        $this->db->bind(":email", $email);
        $this->db->bind(":id", $id);

        $result = $this->db->execute();

        return $result;
    }

    public function getRegisteredAssignments()
    {
        $email = Application::$app->session->get("user");
        $this->db->query("SELECT * FROM solicit 
                            LEFT JOIN request ON solicit.requestId = request.requestId
                            LEFT JOIN company ON request.companyId = company.companyId 
                            LEFT JOIN grimelocation ON request.grimeLocationId = grimelocation.grimeLocationId 
                            LEFT JOIN playground ON request.playgroundId = playground.playgroundId 
                            LEFT JOIN billingaddress ON request.billingaddressId = billingaddress.billingaddressId 
                            WHERE solicit.email = :email;");

        $this->db->bind(":email", $email);

        $result = $this->db->resultSet();

        return $result;
    }

    public function requestDetails($id)
    {
        $this->db->query("SELECT * FROM request 
                            LEFT JOIN company ON request.companyId = company.companyId
                            LEFT JOIN grimelocation ON request.grimeLocationId = grimelocation.grimeLocationId
                            LEFT JOIN playground ON request.playgroundId = playground.playgroundId
                            LEFT JOIN contact ON request.contactId = contact.contactId
                            LEFT JOIN billingaddress ON request.billingaddressId = billingaddress.billingaddressId
                            WHERE request.requestId = :id;");

        $this->db->bind(":id", $id);
        $result = $this->db->resultSet();

        return $result;
    }


    public function getAllMembers()
    {
        $id = 1;

        $this->db->query("SELECT * FROM user WHERE roles = :id");
        $this->db->bind(":id", $id);

        $result = $this->db->resultSet();

        foreach ($result as $key=>$member) {
            $result[$key]["completedAssignment"] = $this->getCountOfCompletedAssigments($member["email"]);
        }

        return $result;
    }

    private function getCountOfCompletedAssigments($email) {
        // TODO check on date
        $assignedId = 1;
        $approvedId = 2;

        $this->db->query("SELECT COUNT(*) AS CompletedAssignments FROM request LEFT JOIN solicit ON request.requestId = solicit.requestId WHERE email = :email AND assigned = :assignedId AND request.approved = :approvedId;");

        $this->db->bind(":email", $email);
        $this->db->bind(":assignedId", $assignedId);
        $this->db->bind(":approvedId", $approvedId);

        $result = $this->db->single();
        
        return $result["CompletedAssignments"];
    }
}
