<?php

class Mycrud extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  function ajax_get_dropdown_child($table,$key_child,$where_value)
  {
    $this->db->where($key_child,$where_value);
    $query = $this->db->get($table);

    if($query->num_rows() > 0)
    {
      $result['status'] = 'success';
      $result['data'] = $query->result();
    }
    else
    {
      $result['status'] = 'not_found';
    }

    echo json_encode($result);
  }
}
