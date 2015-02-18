<?php

/**
 * Home controller
 * @author Mubashar Khokhar <m.khokhar@social-gizmo.com>
 */

class Home {

  function __construct() {
    ;
  }

  function index() {
    Application::loadView('home', array('name' => 'Muabshar'));
  }

  function sort() {
    $countries = Application::post('data');

    $data = array();
    if (!empty($countries)) {
      $countries_array = explode("\n", $countries);
      foreach ($countries_array as $country) {
        @$country = preg_replace('!\s+!', ' ', $country);
        @list($code, $name) = explode(" ", $country, 2);
        $data[$code] = $name;
      }
      asort($data);
      
      Application::loadView('sorted', array('data' => $data));
    }else{
      
      Application::redirect('index.php');
    }    
  }

}
