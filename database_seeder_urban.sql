-- Urban Company Replica Seeder
-- WARNING: This clears existing service data to ensure a clean, professional look.

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `categories`;
TRUNCATE TABLE `services`;
TRUNCATE TABLE `products`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Categories (with Icons)
INSERT INTO `categories` (`name`, `description`, `image`) VALUES
('AC & Appliance Repair', 'Repair and service for all home appliances', 'fas fa-tools'),
('Cleaning & Pest Control', 'Deep cleaning and pest removal services', 'fas fa-broom'),
('Electricians & Plumbers', 'Professional electrical and plumbing works', 'fas fa-bolt'),
('Home Painting', 'Interior and exterior painting services', 'fas fa-paint-roller'),
('Water Purification', 'RO and Water softener services', 'fas fa-hand-holding-water'),
('Solar Energy', 'Green energy solutions and maintenance', 'fas fa-solar-panel'),
('Carpenters', 'Furniture repair and making', 'fas fa-chair');

-- 2. Services

-- AC Services
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`, `image`, `rating`) VALUES
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'Split AC Service', 'Deep cleaning of filters, coils, and drain tray.', 499.00, 60, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=AC+Service', 4.8),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'Window AC Service', 'Complete jet spray cleaning and gas check.', 399.00, 45, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=Window+AC', 4.7),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'AC Gas Refill (Split)', 'Complete refrigerant top-up.', 1500.00, 30, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=Gas+Refill', 4.9),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'AC Installation', 'Professional wall mounting and pipe fitting.', 1200.00, 90, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=AC+Install', 4.6),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'Refrigerator Repair', 'Fixing cooling issues, gas refill, and parts.', 350.00, 45, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=Fridge+Repair', 4.5),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'Washing Machine Repair', 'Drum cleaning and motor repair.', 400.00, 60, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=Washing+Machine', 4.7),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'Microwave Repair', 'Magnetron and heating issue fix.', 300.00, 45, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=Microwave', 4.6),
((SELECT id FROM categories WHERE name='AC & Appliance Repair'), 'Geyser Repair', 'Thermostat replacement and element check.', 250.00, 40, 'https://placehold.co/600x400/e8f0fe/1a73e8?text=Geyser+Repair', 4.8);


-- Water Solutions
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`, `image`, `rating`) VALUES
((SELECT id FROM categories WHERE name='Water Purification'), 'RO Service', 'Filter change, membrane cleaning.', 350.00, 45, 'https://placehold.co/600x400/e0f7fa/006064?text=RO+Service', 4.8),
((SELECT id FROM categories WHERE name='Water Purification'), 'RO Installation', 'New RO unit installation.', 500.00, 60, 'https://placehold.co/600x400/e0f7fa/006064?text=RO+Install', 4.7),
((SELECT id FROM categories WHERE name='Water Purification'), 'Water Softener Install', 'Whole house water softener capability.', 1500.00, 120, 'https://placehold.co/600x400/e0f7fa/006064?text=Water+Softener', 4.9);

-- Solar Energy
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`, `image`, `rating`) VALUES
((SELECT id FROM categories WHERE name='Solar Energy'), 'Solar Panel Cleaning (Per Panel)', 'Dust removal for max efficiency.', 150.00, 30, 'https://placehold.co/600x400/fff8e1/ff6f00?text=Solar+Cleaning', 4.9),
((SELECT id FROM categories WHERE name='Solar Energy'), 'Solar Water Heater Service', 'Descaling and tube replacement.', 800.00, 90, 'https://placehold.co/600x400/fff8e1/ff6f00?text=Solar+Water', 4.8),
((SELECT id FROM categories WHERE name='Solar Energy'), 'Inverter Battery Check', 'Distilled water top-up and terminal cleaning.', 200.00, 30, 'https://placehold.co/600x400/fff8e1/ff6f00?text=Inverter', 4.7);

-- Electricians & Plumbers
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`, `image`, `rating`) VALUES
((SELECT id FROM categories WHERE name='Electricians & Plumbers'), 'Switch/Socket Replacement', 'Repair or replace faulty electrical points.', 100.00, 20, 'https://placehold.co/600x400/ffebee/b71c1c?text=Switch+Repair', 4.6),
((SELECT id FROM categories WHERE name='Electricians & Plumbers'), 'Fan Installation', 'Ceiling fan assembly and mounting.', 150.00, 30, 'https://placehold.co/600x400/ffebee/b71c1c?text=Fan+Install', 4.7),
((SELECT id FROM categories WHERE name='Electricians & Plumbers'), 'Tap Washer Change', 'Fix leaking taps.', 80.00, 15, 'https://placehold.co/600x400/e0f2f1/004d40?text=Tap+Repair', 4.5),
((SELECT id FROM categories WHERE name='Electricians & Plumbers'), 'Blockage Removal', 'Clear sink or drain blockages.', 200.00, 45, 'https://placehold.co/600x400/e0f2f1/004d40?text=Blockage', 4.8),
((SELECT id FROM categories WHERE name='Electricians & Plumbers'), 'Tank Cleaning (500L)', 'Professional mechanized tank cleaning.', 600.00, 90, 'https://placehold.co/600x400/e0f2f1/004d40?text=Tank+Cleaning', 4.9);

-- Cleaning
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`, `image`, `rating`) VALUES
((SELECT id FROM categories WHERE name='Cleaning & Pest Control'), 'Bathroom Deep Cleaning', 'Stain removal and sanitization.', 400.00, 60, 'https://placehold.co/600x400/f3e5f5/4a148c?text=Bathroom+Clean', 4.8),
((SELECT id FROM categories WHERE name='Cleaning & Pest Control'), 'Kitchen Deep Cleaning', 'Oil and grease removal from chimney and tiles.', 600.00, 90, 'https://placehold.co/600x400/f3e5f5/4a148c?text=Kitchen+Clean', 4.7),
((SELECT id FROM categories WHERE name='Cleaning & Pest Control'), 'Sofa Cleaning (3 Seater)', 'Dry vacuuming and shampooing.', 700.00, 60, 'https://placehold.co/600x400/f3e5f5/4a148c?text=Sofa+Clean', 4.8),
((SELECT id FROM categories WHERE name='Cleaning & Pest Control'), 'Full Home Deep Clean (2BHK)', 'Floor, windows, furniture, and bathroom.', 3500.00, 300, 'https://placehold.co/600x400/f3e5f5/4a148c?text=Home+Clean', 4.9);


-- 3. Inventory (Spares)
INSERT INTO `products` (`name`, `sku`, `price`, `stock`) VALUES
('Capacitor 35 MFD', 'ELEC-CAP-35', 250.00, 50),
('Copper Pipe 1/4 (per foot)', 'AC-PIPE-14', 120.00, 100),
('Drain Pipe', 'AC-DRAIN', 50.00, 200),
('MCB 32A', 'ELEC-MCB-32', 350.00, 60),
('Angle Valve', 'PLUMB-VALVE', 180.00, 80),
('Connection Pipe', 'PLUMB-CONN', 100.00, 100),
('Teflon Tape', 'PLUMB-TAPE', 20.00, 500),
('Silicone Sealant', 'GEN-SILICONE', 200.00, 40),
('Solar Inverter Fuse', 'SOL-FUSE', 50.00, 20),
('RO Pre-Filter', 'RO-PRE', 150.00, 100),
('Carbon Filter', 'RO-CARB', 300.00, 80),
('Geyser Element', 'GEY-ELEM', 450.00, 30);
