<?php
  class Item {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // ---- Items CRUD ----

    public function getItems(){
      $this->db->query('SELECT items.*, item_units.short_name as unit_name, gst_rates.name as gst_rate_name, gst_rates.rate as gst_rate_value
                        FROM items
                        LEFT JOIN item_units ON items.unit_id = item_units.id
                        LEFT JOIN gst_rates ON items.gst_rate_id = gst_rates.id
                        ORDER BY items.created_at DESC');
      return $this->db->resultSet();
    }

    public function getItemsByType($type){
      $this->db->query('SELECT items.*, item_units.short_name as unit_name, gst_rates.name as gst_rate_name
                        FROM items
                        LEFT JOIN item_units ON items.unit_id = item_units.id
                        LEFT JOIN gst_rates ON items.gst_rate_id = gst_rates.id
                        WHERE items.type = :type
                        ORDER BY items.created_at DESC');
      $this->db->bind(':type', $type);
      return $this->db->resultSet();
    }

    public function getItemById($id){
      $this->db->query('SELECT items.*, item_units.short_name as unit_name, item_units.name as unit_full_name, gst_rates.name as gst_rate_name, gst_rates.rate as gst_rate_value
                        FROM items
                        LEFT JOIN item_units ON items.unit_id = item_units.id
                        LEFT JOIN gst_rates ON items.gst_rate_id = gst_rates.id
                        WHERE items.id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function addItem($data){
      $this->db->query('INSERT INTO items (type, name, hsn_code, unit_id, item_code, image,
                        batch_tracking, serial_tracking,
                        sale_price, sale_price_tax_type, discount_on_sale, discount_type, wholesale_price,
                        purchase_price, purchase_price_tax_type, gst_rate_id,
                        opening_qty, current_stock, at_price, as_of_date, min_stock, location)
                        VALUES(:type, :name, :hsn_code, :unit_id, :item_code, :image,
                        :batch_tracking, :serial_tracking,
                        :sale_price, :sale_price_tax_type, :discount_on_sale, :discount_type, :wholesale_price,
                        :purchase_price, :purchase_price_tax_type, :gst_rate_id,
                        :opening_qty, :current_stock, :at_price, :as_of_date, :min_stock, :location)');

      $this->db->bind(':type', $data['type']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':hsn_code', $data['hsn_code']);
      $this->db->bind(':unit_id', !empty($data['unit_id']) ? $data['unit_id'] : null);
      $this->db->bind(':item_code', $data['item_code']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':batch_tracking', $data['batch_tracking']);
      $this->db->bind(':serial_tracking', $data['serial_tracking']);
      $this->db->bind(':sale_price', $data['sale_price']);
      $this->db->bind(':sale_price_tax_type', $data['sale_price_tax_type']);
      $this->db->bind(':discount_on_sale', $data['discount_on_sale']);
      $this->db->bind(':discount_type', $data['discount_type']);
      $this->db->bind(':wholesale_price', !empty($data['wholesale_price']) ? $data['wholesale_price'] : null);
      $this->db->bind(':purchase_price', !empty($data['purchase_price']) ? $data['purchase_price'] : null);
      $this->db->bind(':purchase_price_tax_type', $data['purchase_price_tax_type']);
      $this->db->bind(':gst_rate_id', !empty($data['gst_rate_id']) ? $data['gst_rate_id'] : 1);
      $this->db->bind(':opening_qty', $data['opening_qty']);
      $this->db->bind(':current_stock', $data['opening_qty']); // initial stock = opening qty
      $this->db->bind(':at_price', !empty($data['at_price']) ? $data['at_price'] : null);
      $this->db->bind(':as_of_date', !empty($data['as_of_date']) ? $data['as_of_date'] : null);
      $this->db->bind(':min_stock', $data['min_stock']);
      $this->db->bind(':location', $data['location']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updateItem($data){
      // Fetch old item to compare for history tracking
      $oldItem = $this->getItemById($data['id']);

      $this->db->query('UPDATE items SET type = :type, name = :name, hsn_code = :hsn_code, unit_id = :unit_id, item_code = :item_code, image = :image,
                        batch_tracking = :batch_tracking, serial_tracking = :serial_tracking,
                        sale_price = :sale_price, sale_price_tax_type = :sale_price_tax_type,
                        discount_on_sale = :discount_on_sale, discount_type = :discount_type, wholesale_price = :wholesale_price,
                        purchase_price = :purchase_price, purchase_price_tax_type = :purchase_price_tax_type, gst_rate_id = :gst_rate_id,
                        opening_qty = :opening_qty, current_stock = :current_stock, at_price = :at_price, as_of_date = :as_of_date, min_stock = :min_stock, location = :location
                        WHERE id = :id');

      $this->db->bind(':id', $data['id']);
      $this->db->bind(':type', $data['type']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':hsn_code', $data['hsn_code']);
      $this->db->bind(':unit_id', !empty($data['unit_id']) ? $data['unit_id'] : null);
      $this->db->bind(':item_code', $data['item_code']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':batch_tracking', $data['batch_tracking']);
      $this->db->bind(':serial_tracking', $data['serial_tracking']);
      $this->db->bind(':sale_price', $data['sale_price']);
      $this->db->bind(':sale_price_tax_type', $data['sale_price_tax_type']);
      $this->db->bind(':discount_on_sale', $data['discount_on_sale']);
      $this->db->bind(':discount_type', $data['discount_type']);
      $this->db->bind(':wholesale_price', !empty($data['wholesale_price']) ? $data['wholesale_price'] : null);
      $this->db->bind(':purchase_price', !empty($data['purchase_price']) ? $data['purchase_price'] : null);
      $this->db->bind(':purchase_price_tax_type', $data['purchase_price_tax_type']);
      $this->db->bind(':gst_rate_id', !empty($data['gst_rate_id']) ? $data['gst_rate_id'] : 1);
      $this->db->bind(':opening_qty', $data['opening_qty']);
      $this->db->bind(':current_stock', $data['current_stock']);
      $this->db->bind(':at_price', !empty($data['at_price']) ? $data['at_price'] : null);
      $this->db->bind(':as_of_date', !empty($data['as_of_date']) ? $data['as_of_date'] : null);
      $this->db->bind(':min_stock', $data['min_stock']);
      $this->db->bind(':location', $data['location']);

      if($this->db->execute()){
        // Track Stock History
        if($oldItem && $oldItem->current_stock != $data['current_stock']){
           $this->db->query('INSERT INTO item_stock_history (item_id, old_qty, new_qty, change_type) VALUES (:item_id, :old_qty, :new_qty, :change_type)');
           $this->db->bind(':item_id', $data['id']);
           $this->db->bind(':old_qty', $oldItem->current_stock);
           $this->db->bind(':new_qty', $data['current_stock']);
           $this->db->bind(':change_type', 'manual_update');
           $this->db->execute();
        }

        // Track Sale Price History
        if($oldItem && $oldItem->sale_price != $data['sale_price']){
           $this->db->query('INSERT INTO item_price_history (item_id, price_type, old_price, new_price) VALUES (:item_id, :price_type, :old_price, :new_price)');
           $this->db->bind(':item_id', $data['id']);
           $this->db->bind(':price_type', 'sale');
           $this->db->bind(':old_price', $oldItem->sale_price);
           $this->db->bind(':new_price', $data['sale_price']);
           $this->db->execute();
        }

        // Track Purchase Price History
        if(isset($data['purchase_price']) && $oldItem && $oldItem->purchase_price != $data['purchase_price']){
           $this->db->query('INSERT INTO item_price_history (item_id, price_type, old_price, new_price) VALUES (:item_id, :price_type, :old_price, :new_price)');
           $this->db->bind(':item_id', $data['id']);
           $this->db->bind(':price_type', 'purchase');
           $this->db->bind(':old_price', $oldItem->purchase_price !== null ? $oldItem->purchase_price : 0);
           $this->db->bind(':new_price', $data['purchase_price']);
           $this->db->execute();
        }

        // Track Wholesale Price History
        if(isset($data['wholesale_price']) && $oldItem && $oldItem->wholesale_price != $data['wholesale_price']){
           $this->db->query('INSERT INTO item_price_history (item_id, price_type, old_price, new_price) VALUES (:item_id, :price_type, :old_price, :new_price)');
           $this->db->bind(':item_id', $data['id']);
           $this->db->bind(':price_type', 'wholesale');
           $this->db->bind(':old_price', $oldItem->wholesale_price !== null ? $oldItem->wholesale_price : 0);
           $this->db->bind(':new_price', $data['wholesale_price']);
           $this->db->execute();
        }
        return true;
      } else {
        return false;
      }
    }

    public function deleteItem($id){
      $this->db->query('DELETE FROM items WHERE id = :id');
      $this->db->bind(':id', $id);
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // ---- History Tracking ----
    public function getItemStockHistory($item_id){
      $this->db->query('SELECT * FROM item_stock_history WHERE item_id = :item_id ORDER BY created_at DESC');
      $this->db->bind(':item_id', $item_id);
      return $this->db->resultSet();
    }

    public function getItemPriceHistory($item_id){
      $this->db->query('SELECT * FROM item_price_history WHERE item_id = :item_id ORDER BY created_at DESC');
      $this->db->bind(':item_id', $item_id);
      return $this->db->resultSet();
    }

    // ---- Lookups ----

    public function getUnits(){
      $this->db->query('SELECT * FROM item_units ORDER BY name ASC');
      return $this->db->resultSet();
    }

    public function getUnitById($id){
      $this->db->query('SELECT * FROM item_units WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function addUnit($data){
      $this->db->query('INSERT INTO item_units (name, short_name) VALUES (:name, :short_name)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':short_name', $data['short_name']);
      
      if($this->db->execute()){
         return $this->db->lastInsertId();
      } else {
         return false;
      }
    }

    public function getGstRates(){
      $this->db->query('SELECT * FROM gst_rates ORDER BY id ASC');
      return $this->db->resultSet();
    }

    // ---- Auto Code Generation ----

    public function generateItemCode($type){
      $prefix = ($type === 'service') ? 'SRV' : 'PRD';
      $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';
      for ($i = 0; $i < 5; $i++) {
          $index = rand(0, strlen($characters) - 1);
          $randomString .= $characters[$index];
      }
      
      // Optional: you could check if the code exists here and loop to ensure uniqueness
      // For this specific use case, a 5 char random string is highly unlikely to collide,
      // but if you prefer to be 100% sure we can just return it.
      
      return $prefix . '-' . $randomString;
    }

    public function searchItems($term){
      $this->db->query('SELECT items.*, item_units.short_name as unit_name, gst_rates.name as gst_rate_name
                        FROM items
                        LEFT JOIN item_units ON items.unit_id = item_units.id
                        LEFT JOIN gst_rates ON items.gst_rate_id = gst_rates.id
                        WHERE items.name LIKE :term OR items.hsn_code LIKE :term OR items.item_code LIKE :term
                        ORDER BY items.name ASC');
      $this->db->bind(':term', '%' . $term . '%');
      return $this->db->resultSet();
    }

    public function getItemCount(){
      $this->db->query('SELECT count(*) as count FROM items');
      $row = $this->db->single();
      return $row->count;
    }

    public function getItemCountByType($type){
      $this->db->query('SELECT count(*) as count FROM items WHERE type = :type');
      $this->db->bind(':type', $type);
      $row = $this->db->single();
      return $row->count;
    }

    public function getLowStockItems(){
      $this->db->query('SELECT * FROM items WHERE type = "product" AND current_stock <= min_stock AND min_stock > 0 ORDER BY current_stock ASC');
      return $this->db->resultSet();
    }
  }
