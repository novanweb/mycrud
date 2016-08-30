<?php

class Main extends CI_Controller
{
  Public function __construct()
  {
    parent::__construct();
  }

  function index()
  {
    $this->load->view("main/home");
  }

  function features()
  {
    $this->load->view("main/features");
  }

  function download()
  {
    $this->load->view("main/download");
  }

  function pricing()
  {
    $this->load->view("main/pricing");
  }


}

?>
