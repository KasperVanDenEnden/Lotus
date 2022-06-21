<?php
class RequestController extends Controller
{
    public function __construct()
    {
        $this->requestModel = $this->model("request");
        $this->registerMiddleware(new AuthMiddleware(["addRequest", "editRequest"]));
        $this->mailModel = $this->model("mail");
    }

    public function addRequest($payload)
    {
        $data = [
            "msg" => "message!"
        ];

        $houseNumberPlayGround = $payload['houseNumberPlayGround'];
        if (isset($payload['annexPlayGround'])) {
            $houseNumberPlayGround .= $payload['annexPlayGround'];
        }

        $houseNumberGatherLocation = $payload['houseNumberGatherLocation'];
        if (isset($payload['annexGatherLocation'])) {
            $houseNumberGatherLocation .= $payload['annexGatherLocation'];
        }

        $provinceGatherLocation = $payload['provincePlayGround'];
        if (isset($payload['provinceGatherLocation'])) {
            $provinceGatherLocation = $payload['provinceGatherLocation'];
        }


        $this->requestModel->addPlayGroundRequest($payload['provincePlayGround'], $payload['cityPlayGround'], $payload['streetPlayGround'], $houseNumberPlayGround, $payload['postalCodePlayGround']);
        $this->requestModel->addGrimeLocationRequest($provinceGatherLocation, $payload['cityGatherLocation'], $payload['streetGatherLocation'], $houseNumberGatherLocation, $payload['postalCodeGatherLocation']);
        $this->requestModel->addBusinessAddressRequest($this->requestModel->getLoggedInUser());
        $this->requestModel->addBillingAddressRequest($this->requestModel->getLoggedInUser());
        $this->requestModel->addContactRequest($this->requestModel->getLoggedInUser());
        $this->requestModel->addRequest($payload['summary'], $payload['comments'], $payload['playDate'], $payload['playTime'], $payload['lotusCasualties']);
        $this->mailModel->addRequestEmail($payload['summary'], $payload['playDate'], $payload['cityPlayGround'], $payload['streetPlayGround'], $houseNumberPlayGround);
        

        $this->view("/addRequest", $data);
    }

    public function editRequest($payload)
    {

        $data = [
            "msg" => "message!"
        ];

        $houseNumberPlayGround = $payload['houseNumberPlayGround'];
        if (isset($payload['annexPlayGround'])) {
            $houseNumberPlayGround .= $payload['annexPlayGround'];
        }

        $houseNumberGatherLocation = $payload['houseNumberGatherLocation'];
        if (isset($payload['annexGatherLocation'])) {
            $houseNumberGatherLocation .= $payload['annexGatherLocation'];
        }

        $houseNumberBusinessAddress = $payload['houseNumberBusinessAddress'];
        if (isset($payload['annexBusinessAddress'])) {
            $houseNumberBusinessAddress .= $payload['annexBusinessAddress'];
        }

        $houseNumberBillingAddress = $payload['houseNumberBillingAddress'];
        if (isset($payload['annexBillingAddress'])) {
            $houseNumberBillingAddress .= $payload['annexBillingAddress'];
        }


        $provinceGatherLocation = $payload['provincePlayGround'];
        if (isset($payload['provinceGatherLocation'])) {
            $provinceGatherLocation = $payload['provinceGatherLocation'];
        }

        $provinceBillingAddress = $payload['provinceBusinessAddress'];
        if ($payload['provinceBillingAddress'] = 0) {
            $provinceGatherLocation = $payload['provinceBillingAddress'];
        }

        $this->requestModel->editPlayGroundRequest($payload['playGroundId'], $payload['provincePlayGround'], $payload['cityPlayGround'], $payload['streetPlayGround'], $houseNumberPlayGround, $payload['postalCodePlayGround']);
        $this->requestModel->editGrimeLocationRequest($payload['grimeLocationId'], $provinceGatherLocation, $payload['cityGatherLocation'], $payload['streetGatherLocation'], $houseNumberGatherLocation, $payload['postalCodeGatherLocation']);
        $this->requestModel->editBusinessAddressRequest($payload['companyId'], $payload['requestName'], $payload['provinceBusinessAddress'], $payload['cityBusinessAddress'], $payload['streetBusinessAddress'], $houseNumberBusinessAddress, $payload['postalCodeBusinessAddress']);
        $this->requestModel->editBillingAddressRequest($payload['billingAddressId'], $provinceBillingAddress, $payload['cityBillingAddress'], $payload['streetBillingAddress'], $houseNumberBillingAddress, $payload['postalCodeBillingAddress']);
        $this->requestModel->editContactRequest($payload['contactId'], $payload['clientFirstName'], $payload['clientLastName'], $payload['clientEmail'], $payload['clientPhoneNumber']);
        $this->requestModel->editRequest($payload['requestId'], $payload['summary'], $payload['comments'], $payload['playDate'], $payload['playTime'], $payload['lotusCasualties']);

        $this->redirect("/overzicht");
    }
}
