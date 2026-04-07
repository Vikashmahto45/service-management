<?php
  class Parties extends Controller {
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

    // Add party (POST)
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

            flash('party_message', 'Party Added Successfully');
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
        redirect('parties');
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

    // Verify GST (API Endpoint)
    public function verify_gst($gstin){
      // Make sure it's an AJAX/JSON request or similar, return exact JSON
      header('Content-Type: application/json');

      $gstin = trim(strtoupper($gstin));
      
      if(strlen($gstin) !== 15){
        echo json_encode(['success' => false, 'message' => 'Invalid GSTIN length']);
        return;
      }

      // Fetch Live Details using a Public API 
      // (Using a free public endpoint for GST search)
      $curl = curl_init();
      curl_setopt_array($curl, [
          CURLOPT_URL => "https://sheet.gstincheck.co.in/check/" . urlencode($gstin),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          // Adding a generic user agent to avoid being blocked by simple scrapers
          CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      if ($err) {
          echo json_encode([
              'success' => false,
              'message' => 'Connection error. Please try manually.'
          ]);
          return;
      }

      $data = json_decode($response, true);

      // Check if the response was successful and contains the flag
      if($data && isset($data['flag']) && $data['flag'] == true && isset($data['data'])) {
          
          $gstData = $data['data'];
          
          // Determine GST Type based on typical responses
          $gstType = 'registered_regular';
          if(isset($gstData['dty']) && stripos($gstData['dty'], 'Composition') !== false) {
              $gstType = 'registered_composition';
          } else if(isset($gstData['dty']) && stripos($gstData['dty'], 'SEZ') !== false) {
               $gstType = 'special_economic_zone';
          }

          // Format Address
          $addressParts = [];
          if(!empty($gstData['pradr']['addr']['bno'])) $addressParts[] = $gstData['pradr']['addr']['bno'];
          if(!empty($gstData['pradr']['addr']['st'])) $addressParts[] = $gstData['pradr']['addr']['st'];
          if(!empty($gstData['pradr']['addr']['loc'])) $addressParts[] = $gstData['pradr']['addr']['loc'];
          if(!empty($gstData['pradr']['addr']['dst'])) $addressParts[] = $gstData['pradr']['addr']['dst'];
          
          $addressString = implode(', ', $addressParts);
          
          // Append Pincode if available
          if(!empty($gstData['pradr']['addr']['pncd'])) {
              $addressString .= ' - ' . $gstData['pradr']['addr']['pncd'];
          }

          // Standardize State Name (Capitalize correctly)
          $stateName = !empty($gstData['pradr']['addr']['stcd']) ? ucwords(strtolower($gstData['pradr']['addr']['stcd'])) : '';

          echo json_encode([
              'success' => true,
              'data' => [
                  'name' => isset($gstData['trdNm']) && !empty($gstData['trdNm']) ? $gstData['trdNm'] : $gstData['lgnm'],
                  'billing_address' => $addressString,
                  'state' => $stateName,
                  'gst_type' => $gstType
              ]
          ]);
      } else {
           // Invalid GSTIN or not found in the public registry
           $errorMsg = isset($data['message']) ? $data['message'] : 'Invalid GSTIN or not found.';
           echo json_encode([
              'success' => false,
              'message' => $errorMsg
          ]);
      }
    }
  }
