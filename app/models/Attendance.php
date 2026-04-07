<?php
  class Attendance {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // ========== CHECK IN / OUT ==========

    public function checkIn($user_id){
        $today = date('Y-m-d');
        $this->db->query('SELECT id FROM attendance WHERE user_id = :user_id AND date = :date');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':date', $today);
        $this->db->execute();
        
        if($this->db->rowCount() > 0){
            return false; // Already checked in
        }

        $checkInTime = date('H:i:s');
        $lateMinutes = $this->calculateLateMinutes($checkInTime);

        $this->db->query('INSERT INTO attendance (user_id, date, check_in, status, late_minutes, marked_by) 
                          VALUES (:user_id, :date, :check_in, :status, :late_minutes, :marked_by)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':date', $today);
        $this->db->bind(':check_in', $checkInTime);
        $this->db->bind(':status', $lateMinutes > 0 ? 'late' : 'present');
        $this->db->bind(':late_minutes', $lateMinutes);
        $this->db->bind(':marked_by', 'self');

        return $this->db->execute();
    }

    public function checkOut($user_id){
        $today = date('Y-m-d');
        $checkOutTime = date('H:i:s');

        // Get check-in time to calculate work hours
        $this->db->query('SELECT check_in FROM attendance WHERE user_id = :user_id AND date = :date');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':date', $today);
        $record = $this->db->single();

        $workHours = null;
        $overtime = 0;
        $status = 'present';

        if($record && $record->check_in){
            $workHours = $this->calculateWorkHours($record->check_in, $checkOutTime);
            $overtime = $this->calculateOvertime($checkOutTime);
            
            // Check for half-day
            $settings = $this->getSettings();
            if($workHours < $settings->half_day_hours){
                $status = 'half_day';
            }
        }

        $this->db->query('UPDATE attendance SET check_out = :check_out, work_hours = :work_hours, 
                          overtime_minutes = :overtime, status = CASE WHEN late_minutes > 0 THEN "late" ELSE :status END
                          WHERE user_id = :user_id AND date = :date');
        $this->db->bind(':check_out', $checkOutTime);
        $this->db->bind(':work_hours', $workHours);
        $this->db->bind(':overtime', $overtime);
        $this->db->bind(':status', $status);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':date', $today);

        return $this->db->execute();
    }

    // ========== QUERIES ==========

    public function getTodayAttendance($user_id){
        $today = date('Y-m-d');
        $this->db->query('SELECT * FROM attendance WHERE user_id = :user_id AND date = :date');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':date', $today);
        return $this->db->single();
    }

    public function getAllAttendance(){
        $this->db->query('SELECT attendance.*, users.name as user_name 
                          FROM attendance 
                          JOIN users ON attendance.user_id = users.id 
                          ORDER BY attendance.date DESC, attendance.check_in DESC');
        return $this->db->resultSet();
    }

    public function getFilteredAttendance($userId = null, $fromDate = null, $toDate = null){
        $sql = 'SELECT attendance.*, users.name as user_name 
                FROM attendance 
                JOIN users ON attendance.user_id = users.id WHERE 1=1';
        
        if($userId) $sql .= ' AND attendance.user_id = :user_id';
        if($fromDate) $sql .= ' AND attendance.date >= :from_date';
        if($toDate) $sql .= ' AND attendance.date <= :to_date';
        
        $sql .= ' ORDER BY attendance.date DESC, attendance.check_in DESC';
        
        $this->db->query($sql);
        if($userId) $this->db->bind(':user_id', $userId);
        if($fromDate) $this->db->bind(':from_date', $fromDate);
        if($toDate) $this->db->bind(':to_date', $toDate);
        
        return $this->db->resultSet();
    }

    public function getMonthlyAttendance($userId, $month, $year){
        $this->db->query('SELECT * FROM attendance 
                          WHERE user_id = :user_id AND MONTH(date) = :month AND YEAR(date) = :year 
                          ORDER BY date ASC');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        return $this->db->resultSet();
    }

    public function getMonthlyReport($month, $year){
        $this->db->query("SELECT u.id, u.name,
            SUM(CASE WHEN a.status IN ('present','late') THEN 1 ELSE 0 END) as present_days,
            SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_days,
            SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) as late_days,
            SUM(CASE WHEN a.status = 'half_day' THEN 1 ELSE 0 END) as half_days,
            COALESCE(SUM(a.work_hours), 0) as total_hours,
            COALESCE(SUM(a.overtime_minutes), 0) as total_overtime
            FROM users u
            LEFT JOIN attendance a ON u.id = a.user_id AND MONTH(a.date) = :month AND YEAR(a.date) = :year
            WHERE u.role_id IN (3, 4)
            GROUP BY u.id, u.name
            ORDER BY u.name");
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        return $this->db->resultSet();
    }

    public function getTodayStats(){
        $today = date('Y-m-d');
        $this->db->query("SELECT 
            SUM(CASE WHEN a.status IN ('present','late') THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) as late_today,
            SUM(CASE WHEN a.status = 'half_day' THEN 1 ELSE 0 END) as half_day
            FROM attendance a WHERE a.date = :today");
        $this->db->bind(':today', $today);
        return $this->db->single();
    }

    public function getAttendanceById($id){
        $this->db->query('SELECT attendance.*, users.name as user_name 
                          FROM attendance 
                          JOIN users ON attendance.user_id = users.id 
                          WHERE attendance.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // ========== MANUAL ENTRY ==========

    public function markManualAttendance($data){
        // Check if record exists for this user+date
        $this->db->query('SELECT id FROM attendance WHERE user_id = :user_id AND date = :date');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->execute();

        $workHours = null;
        $lateMinutes = 0;
        $overtime = 0;

        if(!empty($data['check_in'])){
            $lateMinutes = $this->calculateLateMinutes($data['check_in']);
        }
        if(!empty($data['check_in']) && !empty($data['check_out'])){
            $workHours = $this->calculateWorkHours($data['check_in'], $data['check_out']);
            $overtime = $this->calculateOvertime($data['check_out']);
        }

        if($this->db->rowCount() > 0){
            // Update existing
            $this->db->query('UPDATE attendance SET check_in = :check_in, check_out = :check_out, 
                              status = :status, work_hours = :work_hours, late_minutes = :late_minutes,
                              overtime_minutes = :overtime, notes = :notes, marked_by = "admin"
                              WHERE user_id = :user_id AND date = :date');
        } else {
            // Insert new
            $this->db->query('INSERT INTO attendance (user_id, date, check_in, check_out, status, 
                              work_hours, late_minutes, overtime_minutes, notes, marked_by) 
                              VALUES (:user_id, :date, :check_in, :check_out, :status, 
                              :work_hours, :late_minutes, :overtime, :notes, "admin")');
        }

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':check_in', $data['check_in'] ?: null);
        $this->db->bind(':check_out', $data['check_out'] ?: null);
        $this->db->bind(':status', $data['status'] ?? 'present');
        $this->db->bind(':work_hours', $workHours);
        $this->db->bind(':late_minutes', $lateMinutes);
        $this->db->bind(':overtime', $overtime);
        $this->db->bind(':notes', $data['notes'] ?? '');

        return $this->db->execute();
    }

    public function deleteAttendance($id){
        $this->db->query('DELETE FROM attendance WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== LEAVE MANAGEMENT ==========

    public function applyLeave($data){
        $this->db->query('INSERT INTO leaves (user_id, leave_type, from_date, to_date, days, reason) 
                          VALUES (:user_id, :leave_type, :from_date, :to_date, :days, :reason)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':leave_type', $data['leave_type']);
        $this->db->bind(':from_date', $data['from_date']);
        $this->db->bind(':to_date', $data['to_date']);
        $this->db->bind(':days', $data['days']);
        $this->db->bind(':reason', $data['reason']);
        return $this->db->execute();
    }

    public function getAllLeaves($status = null){
        $sql = 'SELECT l.*, u.name as user_name, a.name as approver_name 
                FROM leaves l 
                JOIN users u ON l.user_id = u.id 
                LEFT JOIN users a ON l.approved_by = a.id';
        if($status) $sql .= ' WHERE l.status = :status';
        $sql .= ' ORDER BY l.created_at DESC';

        $this->db->query($sql);
        if($status) $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    public function getUserLeaves($userId){
        $this->db->query('SELECT * FROM leaves WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getLeaveById($id){
        $this->db->query('SELECT l.*, u.name as user_name FROM leaves l JOIN users u ON l.user_id = u.id WHERE l.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateLeaveStatus($id, $status, $approvedBy, $remarks = ''){
        $this->db->query('UPDATE leaves SET status = :status, approved_by = :approved_by, 
                          admin_remarks = :remarks WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':approved_by', $approvedBy);
        $this->db->bind(':remarks', $remarks);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getLeaveCount($userId, $year = null){
        $year = $year ?: date('Y');
        $this->db->query("SELECT leave_type, SUM(days) as total_days 
                          FROM leaves WHERE user_id = :user_id AND status = 'approved' 
                          AND YEAR(from_date) = :year GROUP BY leave_type");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':year', $year);
        return $this->db->resultSet();
    }

    public function getPendingLeaveCount(){
        $this->db->query("SELECT COUNT(*) as cnt FROM leaves WHERE status = 'pending'");
        return $this->db->single()->cnt;
    }

    // ========== SETTINGS ==========

    public function getSettings(){
        $this->db->query('SELECT * FROM attendance_settings WHERE id = 1');
        return $this->db->single();
    }

    public function updateSettings($data){
        $this->db->query('UPDATE attendance_settings SET shift_start = :shift_start, shift_end = :shift_end, 
                          late_threshold_minutes = :late_threshold, half_day_hours = :half_day_hours, 
                          weekly_offs = :weekly_offs WHERE id = 1');
        $this->db->bind(':shift_start', $data['shift_start']);
        $this->db->bind(':shift_end', $data['shift_end']);
        $this->db->bind(':late_threshold', $data['late_threshold_minutes']);
        $this->db->bind(':half_day_hours', $data['half_day_hours']);
        $this->db->bind(':weekly_offs', $data['weekly_offs']);
        return $this->db->execute();
    }

    // ========== HELPERS ==========

    private function calculateLateMinutes($checkInTime){
        $settings = $this->getSettings();
        if(!$settings) return 0;

        $shiftStart = strtotime($settings->shift_start);
        $threshold = $shiftStart + ($settings->late_threshold_minutes * 60);
        $actualIn = strtotime($checkInTime);

        if($actualIn > $threshold){
            return round(($actualIn - $shiftStart) / 60);
        }
        return 0;
    }

    private function calculateWorkHours($checkIn, $checkOut){
        $start = strtotime($checkIn);
        $end = strtotime($checkOut);
        if($end > $start){
            return round(($end - $start) / 3600, 2);
        }
        return 0;
    }

    private function calculateOvertime($checkOutTime){
        $settings = $this->getSettings();
        if(!$settings) return 0;

        $shiftEnd = strtotime($settings->shift_end);
        $actualOut = strtotime($checkOutTime);

        if($actualOut > $shiftEnd){
            return round(($actualOut - $shiftEnd) / 60);
        }
        return 0;
    }

    // Get all employees (for dropdowns)
    public function getEmployees(){
        $this->db->query('SELECT id, name, email FROM users WHERE role_id IN (3, 4) ORDER BY name');
        return $this->db->resultSet();
    }

    public function getAttendanceStats($userId, $month, $year){
        $this->db->query("SELECT 
            SUM(CASE WHEN status IN ('present','late') THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
            SUM(CASE WHEN status = 'half_day' THEN 1 ELSE 0 END) as half_day,
            COALESCE(SUM(work_hours), 0) as total_hours
            FROM attendance WHERE user_id = :user_id AND MONTH(date) = :month AND YEAR(date) = :year");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        return $this->db->single();
    }
  }
