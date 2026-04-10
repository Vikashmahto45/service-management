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
        $this->db->query('
            SELECT 
                DATE_FORMAT(all_dates.date, "%b %Y") as month,
                IFNULL(SUM(invoices.total_amount), 0) as income,
                (
                    SELECT IFNULL(SUM(amount), 0) FROM expenses WHERE DATE_FORMAT(created_at, "%Y-%m") = DATE_FORMAT(all_dates.date, "%Y-%m") AND status = "approved"
                ) +
                (
                    SELECT IFNULL(SUM(net_salary), 0) FROM salary_history WHERE DATE_FORMAT(paid_date, "%Y-%m") = DATE_FORMAT(all_dates.date, "%Y-%m")
                ) +
                (
                    SELECT IFNULL(SUM(amount), 0) FROM vendor_payouts WHERE DATE_FORMAT(payout_date, "%Y-%m") = DATE_FORMAT(all_dates.date, "%Y-%m")
                ) as expense
            FROM (
                SELECT CURDATE() - INTERVAL (a.a + (10 * b.a)) MONTH as date
                FROM (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) as a
                CROSS JOIN (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) as b
            ) as all_dates
            LEFT JOIN invoices ON DATE_FORMAT(invoices.created_at, "%Y-%m") = DATE_FORMAT(all_dates.date, "%Y-%m") AND invoices.status = "paid"
            WHERE all_dates.date <= CURDATE() AND all_dates.date > CURDATE() - INTERVAL :limit MONTH
            GROUP BY month
            ORDER BY all_dates.date ASC
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
