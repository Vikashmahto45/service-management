<?php
class Brand {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all brands
    public function getBrands() {
        $this->db->query("SELECT * FROM brands ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Get active brands
    public function getActiveBrands() {
        $this->db->query("SELECT * FROM brands WHERE status = 'active' ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Get brand by ID
    public function getBrandById($id) {
        $this->db->query("SELECT * FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Find brand by name
    public function findBrandByName($name, $ignoreId = null) {
        if ($ignoreId) {
            $this->db->query("SELECT * FROM brands WHERE name = :name AND id != :id");
            $this->db->bind(':id', $ignoreId);
        } else {
            $this->db->query("SELECT * FROM brands WHERE name = :name");
        }
        $this->db->bind(':name', $name);

        $row = $this->db->single();

        return ($this->db->rowCount() > 0) ? true : false;
    }

    // Add brand
    public function addBrand($data) {
        $this->db->query("INSERT INTO brands (name, description, logo, status) VALUES (:name, :description, :logo, :status)");

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':logo', $data['logo']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute() ? true : false;
    }

    // Update brand
    public function updateBrand($data) {
        if (!empty($data['logo'])) {
            $this->db->query("UPDATE brands SET name = :name, description = :description, logo = :logo, status = :status WHERE id = :id");
            $this->db->bind(':logo', $data['logo']);
        } else {
            $this->db->query("UPDATE brands SET name = :name, description = :description, status = :status WHERE id = :id");
        }

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute() ? true : false;
    }

    // Delete brand
    public function deleteBrand($id) {
        $this->db->query("DELETE FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);

        return $this->db->execute() ? true : false;
    }

    // Get brand count
    public function getBrandsCount() {
        $this->db->query("SELECT COUNT(*) as count FROM brands");
        $row = $this->db->single();
        return $row->count;
    }
}
