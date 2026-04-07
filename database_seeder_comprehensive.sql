-- Comprehensive Data Seeder
-- Categories
INSERT INTO `categories` (`name`, `description`) VALUES
('Home Appliances', 'Repair and maintenance of daily household electronic appliances'),
('Water Solutions', 'RO Plants, Water Heaters, and Geysers'),
('Solar Energy', 'Solar Water Heaters and Energy Systems'),
('Plumbing & Handyman', 'General plumbing and home repair services'),
('Cleaning', 'Professional home and appliance cleaning');

-- Services

-- Home Appliances
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`) VALUES
((SELECT id FROM categories WHERE name='Home Appliances' LIMIT 1), 'AC General Service', 'Complete air conditioner cleaning and checkup', 49.99, 60),
((SELECT id FROM categories WHERE name='Home Appliances' LIMIT 1), 'AC Gas Refill', 'Refrigerant gas refill for Split/Window AC', 39.99, 45),
((SELECT id FROM categories WHERE name='Home Appliances' LIMIT 1), 'Washing Machine Repair', 'Diagnosis and repair of front/top load machines', 35.00, 60),
((SELECT id FROM categories WHERE name='Home Appliances' LIMIT 1), 'Refrigerator/Freezer Check', 'Compressor and gas checkup for deep freezers', 45.00, 50),
((SELECT id FROM categories WHERE name='Home Appliances' LIMIT 1), 'TV Repair (LED/LCD)', 'Screen and motherboard diagnostics', 30.00, 45);

-- Water Solutions
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`) VALUES
((SELECT id FROM categories WHERE name='Water Solutions' LIMIT 1), 'RO Plant Maintenance', 'Filter change, membrane cleaning, and TDS check', 25.00, 40),
((SELECT id FROM categories WHERE name='Water Solutions' LIMIT 1), 'Gas Geyser Repair', 'Burner cleaning and thermostat check', 30.00, 45),
((SELECT id FROM categories WHERE name='Water Solutions' LIMIT 1), 'Electric Water Heater Fix', 'Element replacement and tank cleaning', 35.00, 50);

-- Solar Energy
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`) VALUES
((SELECT id FROM categories WHERE name='Solar Energy' LIMIT 1), 'Solar Water Heater Service', 'Descaling and tube cleaning', 75.00, 90),
((SELECT id FROM categories WHERE name='Solar Energy' LIMIT 1), 'Solar Panel Cleaning', 'Professional cleaning of solar panels for max efficiency', 50.00, 60);

-- Plumbing
INSERT INTO `services` (`category_id`, `name`, `description`, `price`, `duration`) VALUES
((SELECT id FROM categories WHERE name='Plumbing & Handyman' LIMIT 1), 'General Plumbing Check', 'Leak detection and pipe adjustment', 20.00, 30),
((SELECT id FROM categories WHERE name='Plumbing & Handyman' LIMIT 1), 'Tap & Mixer Installation', 'Installation of bathroom fittings', 15.00, 30);

-- Inventory / Spares
INSERT INTO `products` (`name`, `sku`, `price`, `stock`) VALUES
('R32 Refrigerant Gas (1kg)', 'REF-R32', 15.00, 50),
('Split AC Filter Mesh', 'AC-FILT-01', 5.00, 100),
('Samsung Front Load Drum Belt', 'WM-BELT-S1', 12.00, 20),
('RO Membrane (100 GPD)', 'RO-MEM-100', 18.00, 40),
('Sediment Filter Cartridge', 'RO-SED-01', 5.00, 200),
('Solar Vacuum Tube (Example)', 'SOL-TUBE-01', 25.00, 30),
('Geyser Thermostat', 'GEY-TH-01', 8.00, 40),
('PVC Pipe 3/4 inch (10ft)', 'PLUMB-PVC-34', 4.00, 100),
('Brass Tap Fitting', 'PLUMB-TAP-BR', 10.00, 60);
