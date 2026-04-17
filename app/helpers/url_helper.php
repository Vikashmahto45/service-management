<?php
  // Simple page redirect
  function redirect($page){
    $url = URLROOT . '/' . $page;
    // Replace spaces with %20 for valid HTTP Location header
    $url = str_replace(' ', '%20', $url);
    header('location: ' . $url);
    exit();
  }
