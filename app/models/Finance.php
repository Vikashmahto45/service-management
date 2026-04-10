<?php
  class Finance {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Get Total Revenue from Paid Invoices
    public function getTotalRevenue(){
        $this->db->query('SELECT SUM(total_amount) as total FROM invoices WHERE status = "paid"');
        return $this->db->single()->total ?? 0;
    }

    // Get Total Operational Expenses (Approved)
    public function getTotalExpenses(){
        $this->db->query('SELECT SUM(amount) as total FROM expenses WHERE status = "approved"');
        return $this->db->single()->total ?? 0;
    }

    // Get Total Salaries Paid
    public function getTotalSalaries(){
        $this->db->query('SELECT SUM(net_salary) as total FROM salary_history');
        return $this->db->single()->total ?? 0;
    }

    // Get Total Vendor Payouts
    public function getTotalVendorPayouts(){
        $this->db->query('SELECT SUM(amount) as total FROM vendor_payouts');
        return $this->db->single()->total ?? 0;
    }

    // Get Monthly Income vs Expense Data (for Charts)
    public function getMonthlyBreakdown($limit = 6){
        // Highly compatible query for strict MySQL modes
        $this->db->query('
            SELECT 
                DATE_FORMAT(finance_data.date, "%b %Y") as month,
                SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense,
                DATE_FORMAT(finance_data.date, "%Y-%m") as month_sort
            FROM (
                SELECT created_at as date, total_amount as amount, "income" as type FROM invoices WHERE status = "paid"
                UNION ALL
                SELECT created_at as date, amount, "expense" as type FROM expenses WHERE status = "approved"
                UNION ALL
                SELECT paid_date as date, net_salary as amount, "expense" as type FROM salary_history
                UNION ALL
                SELECT payout_date as date, amount, "expense" as type FROM vendor_payouts
            ) as finance_data
            WHERE finance_data.date > DATE_SUB(NOW(), INTERVAL :limit MONTH)
            GROUP BY month, month_sort
            ORDER BY month_sort ASC
        ');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get Unified Account Ledger for a specific user
    public function getAccountLedger($user_id){
        // Combine Invoices, Payouts, and Salaries into one timeline
        $this->db->query('(
            SELECT created_at as date, "Invoice" as type, CONCAT("Invoice #", invoice_number) as description, total_amount as amount, "in" as direction
            FROM invoices WHERE customer_id = :user_id
        ) UNION (
            SELECT paid_date as date, "Salary" as type, CONCAT("Salary for ", DATE_FORMAT(paid_date, "%M %Y")) as description, net_salary as amount, "out" as direction
            FROM salary_history WHERE user_id = :user_id
        ) UNION (
            SELECT payout_date as date, "Vendor Payout" as type, notes as description, amount as amount, "out" as direction
            FROM vendor_payouts WHERE vendor_id = :user_id
        ) ORDER BY date DESC');
        
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Record Vendor Payout
    public function addVendorPayout($data){
        $this->db->query('INSERT INTO vendor_payouts (vendor_id, amount, payout_date, payment_method, transaction_id, notes) VALUES (:vendor_id, :amount, :payout_date, :payment_method, :transaction_id, :notes)');
        $this->db->bind(':vendor_id', $data['vendor_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':payout_date', $data['payout_date']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }
  }
