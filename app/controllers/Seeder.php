<?php
  class Seeder extends Controller {
      public function __construct(){
          $this->userModel = $this->model('User');
      }

      public function index(){
          echo 'Run /seeder/admin to create an admin user.';
      }

      public function admin(){
          // Check if admin exists
          if($this->userModel->findUserByEmail('admin@admin.com')){
              die('Admin user already exists.');
          }

          $data = [
              'name' => 'Super Admin',
              'email' => 'admin@admin.com',
              'phone' => '0000000000',
              'address' => 'Admin HQ',
              'password' => password_hash('123456', PASSWORD_DEFAULT),
              'role_id' => 1,
              'status' => 'active'
          ];

          if($this->userModel->register($data)){
              echo 'Admin created successfully. Email: admin@admin.com, Pass: 123456';
          } else {
              echo 'Failed to create admin.';
          }
      }
  }
