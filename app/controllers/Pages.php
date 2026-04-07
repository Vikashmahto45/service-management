<?php
  class Pages extends Controller {
    public function __construct(){
      $this->serviceModel = $this->model('Service');
    }

    public function index(){
      $categories = $this->serviceModel->getCategories();
      $featured = $this->serviceModel->getFeaturedServices();

      $data = [
        'title' => 'Service Management System',
        'categories' => $categories,
        'featured' => $featured
      ];
     
      // Re-using Service model to get categories for the Hero Grid
      $serviceModel = $this->model('Service');
      $categories = $serviceModel->getCategories(); // Assuming getCategories exists and is public

      $data = [
        'title' => 'Home services at your doorstep',
        'description' => 'Simple, fast and affordable',
        'categories' => $categories,
        'newServices' => $this->serviceModel->getNewServices(),
        'mostBooked' => $this->serviceModel->getMostBookedServices(),
        'salonServices' => $this->serviceModel->getServicesByCategoryName('Salon for Women')
      ];

      $this->view('pages/index', $data);
    }

    public function about(){
      // Load Team Model manually or inject if preferred. 
      // Ideally should be in constructor but for now we load here.
      $teamModel = $this->model('Team');
      $members = $teamModel->getTeamMembers();

      $data = [
        'title' => 'About Us',
        'team' => $members
      ];

      $this->view('pages/about', $data);
    }
  }
