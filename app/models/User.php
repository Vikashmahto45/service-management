<?php
  class User {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Get Customer Stats
    public function getCustomerStats($user_id){
        $stats = [
            'total_bookings' => 0,
            'pending_bookings' => 0,
            'total_spent' => 0
        ];

        // Total Bookings
        $this->db->query('SELECT COUNT(*) as count FROM bookings WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        $stats['total_bookings'] = $row->count;

        // Pending Bookings
        $this->db->query('SELECT COUNT(*) as count FROM bookings WHERE user_id = :user_id AND status = "pending"');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        $stats['pending_bookings'] = $row->count;
        
        // Total Spent (Paid Invoices)
        $this->db->query('SELECT SUM(total_amount) as total FROM invoices WHERE customer_id = :user_id AND status = "paid"');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        $stats['total_spent'] = $row->total ?? 0;

        return $stats;
    }

    // Register User (Customer)
    public function register($data){
      $this->db->query('INSERT INTO users (name, email, phone, address, password, role_id, status) VALUES(:name, :email, :phone, :address, :password, :role_id, :status)');
      // Bind values
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':phone', $data['phone']);
      $this->db->bind(':address', $data['address']);
      $this->db->bind(':password', $data['password']);
      $this->db->bind(':role_id', $data['role_id']);
      $this->db->bind(':status', $data['status']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Register Vendor or Employee (Admin Action)
    public function registerVendorOrEmployee($data){
        $this->db->query('INSERT INTO users (name, email, phone, address, password, role_id, status, kyc_document, kyc_status, profile_image, designation, aadhar_file, pan_file, employee_id, gstin, office_address) VALUES(:name, :email, :phone, :address, :password, :role_id, :status, :kyc_document, :kyc_status, :profile_image, :designation, :aadhar_file, :pan_file, :employee_id, :gstin, :office_address)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':kyc_document', $data['kyc_document'] ?? '');
        $this->db->bind(':kyc_status', $data['kyc_status']);
        $this->db->bind(':profile_image', $data['profile_image'] ?? '');
        $this->db->bind(':designation', $data['designation'] ?? '');
        
        // New Fields
        $this->db->bind(':aadhar_file', $data['aadhar_file'] ?? null);
        $this->db->bind(':pan_file', $data['pan_file'] ?? null);
        $this->db->bind(':employee_id', $data['employee_id'] ?? null);
        $this->db->bind(':gstin', $data['gstin'] ?? null);
        $this->db->bind(':office_address', $data['office_address'] ?? null);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Generate next Employee ID
    public function generateNextEmployeeId(){
        $this->db->query('SELECT employee_id FROM users WHERE employee_id IS NOT NULL ORDER BY id DESC LIMIT 1');
        $row = $this->db->single();
        
        if($row){
            // e.g. EMP-001 -> Extract '001', cast to int, add 1, format to 3 str padded
            $last_num = (int)str_replace('EMP-', '', $row->employee_id);
            $next_num = $last_num + 1;
            return 'EMP-' . str_pad($next_num, 3, '0', STR_PAD_LEFT);
        } else {
            return 'EMP-001';
        }
    }

    // Get Users by Role
    public function getUsersByRole($role_id){
        $this->db->query('SELECT * FROM users WHERE role_id = :role_id ORDER BY created_at DESC');
        $this->db->bind(':role_id', $role_id);
        return $this->db->resultSet();
    }

    // Get All Users (Joined with Roles for display)
    public function getAllUsers(){
        $this->db->query('SELECT users.*, roles.name as role_name 
                          FROM users 
                          JOIN roles ON users.role_id = roles.id 
                          ORDER BY users.created_at DESC');
        return $this->db->resultSet();
    }

    // Get Service Providers (Employees & Vendors)
    public function getServiceProviders(){
        $this->db->query('SELECT * FROM users WHERE (role_id = 3 OR role_id = 4) AND status = "active" ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Update Status
    public function updateStatus($id, $status){
        $this->db->query('UPDATE users SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Update KYC Status
    public function updateKycStatus($id, $status){
        $this->db->query('UPDATE users SET kyc_status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);

      $row = $this->db->single();

      $hashed_password = $row->password;
      if(password_verify($password, $hashed_password)){
        return $row;
      } else {
        return false;
      }
    }

    // Find user by email
    public function findUserByEmail($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);

      $row = $this->db->single();

      // Check row
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }

    // Get User by ID
    public function getUserById($id){
      $this->db->query('SELECT * FROM users WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }
    // Get User Count
    public function getUserCount(){
      $this->db->query('SELECT count(*) as count FROM users');
      $row = $this->db->single();
      return $row->count;
    }

    public function getStaffCount(){
        $this->db->query('SELECT count(*) as count FROM users WHERE role_id IN (3, 4) AND status = "active"');
        $row = $this->db->single();
        return $row->count;
    }

    // ========== PHASE 7: PROFILE & PAYROLL METHODS ==========

    // Get Extended Profile Data
    public function getUserProfile($user_id){
        $this->db->query('SELECT * FROM user_profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $row = $this->db->single();
        
        // If no profile exists yet, return empty object with default structure
        if(!$row){
            return (object)[
                'designation' => '',
                'joining_date' => '',
                'phone_alt' => '',
                'address' => '',
                'emergency_contact' => '',
                'account_holder_name' => '',
                'bank_name' => '',
                'account_no' => '',
                'ifsc_code' => '',
                'upi_id' => '',
                'pan_no' => '',
                'aadhar_no' => '',
                'driving_license' => '',
                'basic_salary' => 0.00,
                'hra_allowance' => 0.00,
                'travel_allowance' => 0.00,
                'other_allowances' => 0.00,
                'tds_deduction' => 0.00,
                'pf_deduction' => 0.00,
                'payroll_status' => 'active'
            ];
        }
        return $row;
    }

    // Update or Create Extended Profile
    public function updateUserProfile($data){
        // Check if exists
        $this->db->query('SELECT id FROM user_profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $data['user_id']);
        $exists = $this->db->single();

        if($exists){
            // Update
            $this->db->query('UPDATE user_profiles SET 
                designation = :designation,
                joining_date = :joining_date,
                phone_alt = :phone_alt,
                address = :address,
                emergency_contact = :emergency_contact,
                account_holder_name = :account_holder_name,
                bank_name = :bank_name,
                account_no = :account_no,
                ifsc_code = :ifsc_code,
                upi_id = :upi_id,
                pan_no = :pan_no,
                aadhar_no = :aadhar_no,
                driving_license = :driving_license,
                basic_salary = :basic_salary,
                hra_allowance = :hra_allowance,
                travel_allowance = :travel_allowance,
                other_allowances = :other_allowances,
                tds_deduction = :tds_deduction,
                pf_deduction = :pf_deduction,
                payroll_status = :payroll_status
                WHERE user_id = :user_id');
        } else {
            // Insert
            $this->db->query('INSERT INTO user_profiles (
                user_id, designation, joining_date, phone_alt, address, 
                emergency_contact, account_holder_name, bank_name, account_no, 
                ifsc_code, upi_id, pan_no, aadhar_no, driving_license, 
                basic_salary, hra_allowance, travel_allowance, other_allowances, 
                tds_deduction, pf_deduction, payroll_status
            ) VALUES (
                :user_id, :designation, :joining_date, :phone_alt, :address, 
                :emergency_contact, :account_holder_name, :bank_name, :account_no, 
                :ifsc_code, :upi_id, :pan_no, :aadhar_no, :driving_license, 
                :basic_salary, :hra_allowance, :travel_allowance, :other_allowances, 
                :tds_deduction, :pf_deduction, :payroll_status
            )');
        }

        // Bind all
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':designation', $data['designation']);
        $this->db->bind(':joining_date', $data['joining_date']);
        $this->db->bind(':phone_alt', $data['phone_alt']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':emergency_contact', $data['emergency_contact']);
        $this->db->bind(':account_holder_name', $data['account_holder_name']);
        $this->db->bind(':bank_name', $data['bank_name']);
        $this->db->bind(':account_no', $data['account_no']);
        $this->db->bind(':ifsc_code', $data['ifsc_code']);
        $this->db->bind(':upi_id', $data['upi_id']);
        $this->db->bind(':pan_no', $data['pan_no']);
        $this->db->bind(':aadhar_no', $data['aadhar_no']);
        $this->db->bind(':driving_license', $data['driving_license']);
        $this->db->bind(':basic_salary', $data['basic_salary']);
        $this->db->bind(':hra_allowance', $data['hra_allowance']);
        $this->db->bind(':travel_allowance', $data['travel_allowance']);
        $this->db->bind(':other_allowances', $data['other_allowances']);
        $this->db->bind(':tds_deduction', $data['tds_deduction']);
        $this->db->bind(':pf_deduction', $data['pf_deduction']);
        $this->db->bind(':payroll_status', $data['payroll_status']);

        return $this->db->execute();
    }

    // Get User by Email (returns full user row)
    public function getUserByEmail($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);
      $row = $this->db->single();

      if($this->db->rowCount() > 0){
        return $row;
      } else {
        return false;
      }
    }

    // Create Password Reset Token
    public function createPasswordReset($email, $token){
      // Delete any existing tokens for this email
      $this->db->query('DELETE FROM password_resets WHERE email = :email');
      $this->db->bind(':email', $email);
      $this->db->execute();

      // Insert new token
      $this->db->query('INSERT INTO password_resets (email, token) VALUES(:email, :token)');
      $this->db->bind(':email', $email);
      $this->db->bind(':token', $token);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Find Password Reset by Token (valid for 1 hour)
    public function findPasswordReset($token){
      $this->db->query('SELECT * FROM password_resets WHERE token = :token AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)');
      $this->db->bind(':token', $token);
      $row = $this->db->single();

      if($this->db->rowCount() > 0){
        return $row;
      } else {
        return false;
      }
    }

    // Update User Password
    public function updatePassword($email, $password){
      $this->db->query('UPDATE users SET password = :password WHERE email = :email');
      $this->db->bind(':password', $password);
      $this->db->bind(':email', $email);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Delete Password Reset Token
    public function deletePasswordReset($token){
      $this->db->query('DELETE FROM password_resets WHERE token = :token');
      $this->db->bind(':token', $token);
      return $this->db->execute();
    }
  }
