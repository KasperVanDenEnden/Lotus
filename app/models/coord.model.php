<?php
class CoordModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAssignmentRequests()
    {
        $this->db->query("SELECT * FROM request 
                                LEFT JOIN company ON request.companyId = company.companyId
                                LEFT JOIN grimelocation ON request.grimeLocationId = grimelocation.grimeLocationId
                                LEFT JOIN playground ON request.playgroundId = playground.playgroundId
                                LEFT JOIN contact ON request.contactId = contact.contactId
                                LEFT JOIN billingaddress ON request.billingaddressId = billingaddress.billingaddressId");

        $result = $this->db->resultSet();

        return $result;
    }

    public function declineAssignment($id)
    {
        $this->db->query("UPDATE request SET approved = 3 WHERE requestId = $id;");

        $result = $this->db->resultSet();
    }

    public function AssigmentInProgress($id)
    {
        $this->db->query("UPDATE request SET approved = 1 WHERE requestId = $id;");

        $result = $this->db->resultSet();
    }

    public function getRequestDetailsAcceptDeny($id)
    {
        $this->db->query("SELECT * FROM request
                                LEFT JOIN user ON user.email = request.clientEmail 
                                LEFT JOIN playground ON request.playGroundId = playground.playGroundId 
                                LEFT JOIN grimelocation ON request.grimeLocationId = grimelocation.grimeLocationId 
                                LEFT JOIN company ON request.companyId = company.companyId 
                                LEFT JOIN contact ON request.contactId = contact.contactId 
                                LEFT JOIN billingaddress ON request.billingAddressId = billingaddress.billingAddressId 
                                WHERE requestId = :id;");

        $this->db->bind(":id", $id);

        $results = $this->db->resultSet();

        return $results;
    }

    public function getProfile($email)
    {
        $this->db->query("SELECT * FROM user
            WHERE email = :email;");

        $this->db->bind(":email", $email);

        $results = $this->db->resultSet();

        return $results;
    }
}
