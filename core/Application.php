<?php

/**
 * Core MVC class
 * Responsible for handing all HTTP/HTTPs requests. 
 * All request will be sent to other controllers view this class object.
 * @author Mubashar Khokhar <m.khokhar@social-gizmo.com>
 */

class Application {

  // all type of request handler
  private $request;
  // all available controllers container
  private $controllers;
  // current controller
  private $controller;
  // current method called
  private $method;
  // application config container
  private $config;

  /**
   * Initialize application
   * @param mixed $config
   */
  function __construct($config) {
    // activate all initial settings required
    $this->init($config);
  }

  /**
   * Initiate all required settings/activities
   * @param mixed $config
   */
  function init($config) {
    // set all type of request variables [post/get]
    $this->request = $_REQUEST;
    // site configuration
    $this->config = $config;
    // load all controller files and initialize them
    $this->loadControllers();
    // set current controller name
    $this->controller = !empty($this->request['c']) ? $this->request['c'] : $this->config['default_controller'];
    // set current method name
    $this->method = !empty($this->request['m']) ? $this->request['m'] : 'index';
  }

  /**
   * Default method to be called
   */
  function index() {
    // current controller 
    $controller = $this->controller;
    // current method to be called
    $method = $this->method;
    // call the current method from the requested controller [default: index()]
    $this->controllers[$controller]->$method();
  }

  /*
   * Execute the application with default method
   */
  function run() {
    $this->index();
  }

  /*
   * Include all controllers files and create their objects.
   */
  function loadControllers() {
    // open ./controller dir
    if ($handle = opendir('controller')) {
      // if there are files then read one by one
      while (false !== ($controller_file = readdir($handle))) {
        if ($controller_file != "." && $controller_file != "..") {
          // include controller files
          require_once 'controller/' . $controller_file;
          // remove file extension to get the controller class name
          $controller = str_replace('.php', '', $controller_file);
          // initial controller
          $this->controllers[$controller] = new $controller();
        }
      }
      // close dir 
      closedir($handle);
    }
  }

  /**
   * Get application request $_POST variables
   * @param string|null $value
   * @return array|string
   */
  static function post($value) {
    return !empty($value) ? $_POST[$value] : $_POST;
  }
  
  /**
   * Get application request $_GET variables
   * @param string|null $value
   * @return array|string
   */
  static function get($value) {
    return !empty($value) ? $_GET[$value] : $_GET;
  }

  /**
   * Redirect request to URL
   * @param string $url URL to be directed
   */
  static function redirect($url) {
    header("Location: {$url}");
    die();
  }

  /**
   * Load the view within the controller
   * @param string $view view file name
   * @param mixed $data variables to provide to view
   */
  static function loadView($view, $data = array()) {
    extract($data);
    include "views/{$view}.php";
  }

}
