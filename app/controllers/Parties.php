<?php
  class Parties extends Controller {
    private $partyModel;

    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->partyModel = $this->model('Party');
    }

    // List all parties
    public function index(){
      $parties = $this->partyModel->getParties();
      $groups = $this->partyModel->getPartyGroups();

      $data = [
        'parties' => $parties,
        'groups' => $groups
      ];

      $this->view('parties/index', $data);
    }

    // Add party (GET shows form, POST handles save)
    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'name' => trim($_POST['name']),
          'gstin' => trim($_POST['gstin'] ?? ''),
          'phone' => trim($_POST['phone'] ?? ''),
          'email' => trim($_POST['email'] ?? ''),
          'party_group_id' => $_POST['party_group_id'] ?? null,
          'gst_type' => $_POST['gst_type'] ?? 'unregistered',
          'state' => trim($_POST['state'] ?? ''),
          'opening_balance' => floatval($_POST['opening_balance'] ?? 0),
          'opening_balance_type' => $_POST['opening_balance_type'] ?? 'to_receive',
          'credit_limit' => $_POST['credit_limit'] ?? null,
          'additional_fields' => trim($_POST['additional_fields'] ?? '')
        ];

        if(!empty($data['name'])){
          $partyId = $this->partyModel->addParty($data);
          if($partyId){
            // Add billing address
            $billingAddress = trim($_POST['billing_address'] ?? '');
            if(!empty($billingAddress)){
              $addressData = [
                'party_id' => $partyId,
                'type' => 'billing',
                'address_line1' => $billingAddress,
                'address_line2' => '',
                'city' => trim($_POST['billing_city'] ?? ''),
                'state' => trim($_POST['billing_state'] ?? $data['state']),
                'pincode' => trim($_POST['billing_pincode'] ?? ''),
                'country' => 'India',
                'is_default' => 1
              ];
              $this->partyModel->addAddress($addressData);
            }

            // Add shipping address if provided
            $shippingAddress = trim($_POST['shipping_address'] ?? '');
            if(!empty($shippingAddress)){
              $shipData = [
                'party_id' => $partyId,
                'type' => 'shipping',
                'address_line1' => $shippingAddress,
                'address_line2' => '',
                'city' => trim($_POST['shipping_city'] ?? ''),
                'state' => trim($_POST['shipping_state'] ?? ''),
                'pincode' => trim($_POST['shipping_pincode'] ?? ''),
                'country' => 'India',
                'is_default' => 1
              ];
              $this->partyModel->addAddress($shipData);
            }

            flash('party_message', 'Customer Added Successfully');
            redirect('parties');
          } else {
            flash('party_message', 'Something went wrong', 'alert alert-danger');
            redirect('parties');
          }
        } else {
          flash('party_message', 'Customer Name is required', 'alert alert-danger');
          redirect('parties');
        }
      } else {
        $groups = $this->partyModel->getPartyGroups();
        $data = [
          'groups' => $groups
        ];
        $this->view('parties/add', $data);
      }
    }

    // Edit party (GET shows form, POST updates)
    public function edit($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'id' => $id,
          'name' => trim($_POST['name']),
          'gstin' => trim($_POST['gstin'] ?? ''),
          'phone' => trim($_POST['phone'] ?? ''),
          'email' => trim($_POST['email'] ?? ''),
          'party_group_id' => $_POST['party_group_id'] ?? null,
          'gst_type' => $_POST['gst_type'] ?? 'unregistered',
          'state' => trim($_POST['state'] ?? ''),
          'opening_balance' => floatval($_POST['opening_balance'] ?? 0),
          'opening_balance_type' => $_POST['opening_balance_type'] ?? 'to_receive',
          'credit_limit' => $_POST['credit_limit'] ?? null,
          'additional_fields' => trim($_POST['additional_fields'] ?? '')
        ];

        if(!empty($data['name'])){
          if($this->partyModel->updateParty($data)){
            flash('party_message', 'Party Updated Successfully');
            redirect('parties');
          } else {
            flash('party_message', 'Something went wrong', 'alert alert-danger');
            redirect('parties');
          }
        } else {
          flash('party_message', 'Party Name is required', 'alert alert-danger');
          redirect('parties');
        }
      } else {
        $party = $this->partyModel->getPartyById($id);
        $groups = $this->partyModel->getPartyGroups();
        $addresses = $this->partyModel->getPartyAddresses($id);

        $data = [
          'party' => $party,
          'groups' => $groups,
          'addresses' => $addresses
        ];

        $this->view('parties/edit', $data);
      }
    }

    // Delete party (POST)
    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->partyModel->deleteParty($id)){
          flash('party_message', 'Party Deleted');
          redirect('parties');
        } else {
          flash('party_message', 'Something went wrong', 'alert alert-danger');
          redirect('parties');
        }
      } else {
        redirect('parties');
      }
    }

    // Add address (POST via AJAX or form)
    public function add_address($party_id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'party_id' => $party_id,
          'type' => $_POST['address_type'] ?? 'shipping',
          'address_line1' => trim($_POST['address_line1'] ?? ''),
          'address_line2' => trim($_POST['address_line2'] ?? ''),
          'city' => trim($_POST['city'] ?? ''),
          'state' => trim($_POST['state'] ?? ''),
          'pincode' => trim($_POST['pincode'] ?? ''),
          'country' => 'India',
          'is_default' => isset($_POST['is_default']) ? 1 : 0
        ];

        if(!empty($data['address_line1'])){
          $this->partyModel->addAddress($data);
          flash('party_message', 'Address Added');
        }
        redirect('parties/edit/' . $party_id);
      } else {
        redirect('parties');
      }
    }

    // Delete address (POST)
    public function delete_address($id, $party_id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $this->partyModel->deleteAddress($id);
        flash('party_message', 'Address Removed');
        redirect('parties/edit/' . $party_id);
      } else {
        redirect('parties');
      }
    }

    // Add party group (POST)
    public function add_group(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $name = trim($_POST['group_name'] ?? '');
        if(!empty($name)){
          $this->partyModel->addPartyGroup(['name' => $name]);
          flash('party_message', 'Party Group Added');
        }
        redirect('parties');
      } else {
        redirect('parties');
      }
    }


  }
