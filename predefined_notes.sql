-- Create table for predefined specifications/notes
CREATE TABLE IF NOT EXISTS `predefined_product_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `note_text` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
