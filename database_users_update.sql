ALTER TABLE `users` ADD `phone` VARCHAR(20) NULL AFTER `email`;
ALTER TABLE `users` ADD `address` TEXT NULL AFTER `phone`;
