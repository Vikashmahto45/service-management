<?php
  class Amc {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Get all AMC contracts with party names
    public function getContracts(){
      $this->db->query('SELECT amc.*, p.name as customer_name 
                        FROM amc_contracts amc
                        JOIN parties p ON amc.party_id = p.id
                        ORDER BY amc.created_at DESC');
      return $this->db->resultSet();
    }

    // Get expiring contracts (within N days)
    public function getExpiringContracts($days = 30){
        $this->db->query('SELECT amc.*, p.name as customer_name 
                          FROM amc_contracts amc
                          JOIN parties p ON amc.party_id = p.id
                          WHERE amc.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
                          AND amc.status = "active"
                          ORDER BY amc.end_date ASC');
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    // Add New AMC Contract
    public function addContract($data){
        $this->db->query('INSERT INTO amc_contracts (party_id, contract_no, start_date, end_date, total_amount, visits_per_year, status, notes) 
                          VALUES(:party_id, :contract_no, :start_date, :end_date, :total_amount, :visits_per_year, :status, :notes)');
        
        $this->db->bind(':party_id', $data['party_id']);
        $this->db->bind(':contract_no', $data['contract_no']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':visits_per_year', $data['visits_per_year']);
        $this->db->bind(':status', $data['status'] ?? 'active');
        $this->db->bind(':notes', $data['notes'] ?? null);

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Add Item to Contract
    public function addItem($amc_id, $product_id){
        $this->db->query('INSERT INTO amc_items (amc_id, product_id) VALUES(:amc_id, :product_id)');
        $this->db->bind(':amc_id', $amc_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }

    // Generate Scheduled Visits
    public function generateVisits($amc_id, $start_date, $visits_count){
        $interval = 12 / $visits_count; // Rough monthly interval
        for($i = 0; $i < $visits_count; $i++){
            $scheduled_date = date('Y-m-d', strtotime("+$i months", strtotime($start_date)));
            if($i > 0){
                $months = round($i * $interval);
                $scheduled_date = date('Y-m-d', strtotime("+$months months", strtotime($start_date)));
            }
            
            $this->db->query('INSERT INTO amc_visits (amc_id, scheduled_date, status) VALUES(:amc_id, :scheduled_date, "pending")');
            $this->db->bind(':amc_id', $amc_id);
            $this->db->bind(':scheduled_date', $scheduled_date);
            $this->db->execute();
        }
        return true;
    }

    public function getContractById($id){
        $this->db->query('SELECT amc.*, p.name as customer_name, p.phone as customer_phone, p.email as customer_email, 
                                 COALESCE(pa.address_line1, p.state, "No Address Provided") as customer_address
                          FROM amc_contracts amc
                          JOIN parties p ON amc.party_id = p.id
                          LEFT JOIN party_addresses pa ON p.id = pa.party_id AND pa.is_default = 1
                          WHERE amc.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getContractItems($amc_id){
        $this->db->query('SELECT ai.*, cp.product_name, cp.model_no, at.name as appliance_type
                          FROM amc_items ai
                          JOIN customer_products cp ON ai.product_id = cp.id
                          LEFT JOIN appliance_types at ON cp.appliance_type_id = at.id
                          WHERE ai.amc_id = :amc_id');
        $this->db->bind(':amc_id', $amc_id);
        return $this->db->resultSet();
    }

    public function getContractVisits($amc_id){
        $this->db->query('SELECT * FROM amc_visits WHERE amc_id = :amc_id ORDER BY scheduled_date ASC');
        $this->db->bind(':amc_id', $amc_id);
        return $this->db->resultSet();
    }

    // Update Visit Status
    public function updateVisit($id, $data){
        $this->db->query('UPDATE amc_visits SET actual_date = :actual_date, status = :status, remarks = :remarks, completed_by = :completed_by WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':actual_date', $data['actual_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':remarks', $data['remarks']);
        $this->db->bind(':completed_by', $data['completed_by']);
        return $this->db->execute();
    }

    public function getPendingVisitsStats(){
        $this->db->query('SELECT COUNT(*) as total FROM amc_visits WHERE status = "pending" AND scheduled_date <= CURDATE()');
        return $this->db->single()->total ?? 0;
    }
  }
