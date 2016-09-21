<?php

class Examples extends CI_Controller
{
  Public function __construct()
  {
    parent::__construct();
  }

  function index()
  {
    $this->load->view('examples/index');
  }

  function set_columns()
  {
    $this->load->view('examples/set_columns');
  }

  function hide_columns()
  {
    $this->load->view('examples/hide_columns');
  }

  function set_fields()
  {
    $this->load->view('examples/set_fields');
  }

  function hide_fields()
  {
    $this->load->view('examples/hide_fields');
  }

  function set_upload_file()
  {
    $this->load->view('examples/set_upload_file');
  }

  function set_upload_image()
  {
    $this->load->view('examples/set_upload_image');
  }

  function set_texteditor()
  {
    $this->load->view('examples/set_texteditor');
  }

  function disable_action()
  {
    $this->load->view('examples/disable_action');
  }

  function add_action()
  {
    $this->load->view('examples/add_action');
  }

  function where()
  {
    $this->load->view('examples/where');
  }

  function set_relation()
  {
    $this->load->view('examples/set_relation');
  }

  function set_relation_by_parent()
  {
    $this->load->view('examples/set_relation_by_parent');
  }

  function set_attribute_relation()
  {
    $this->load->view('examples/set_attribute_relation');
  }

  function callback_column()
  {
    $data['my'] = $this;
    $this->load->view('examples/callback_column',$data);
  }

  function callback_column_function($row_id = null,$value = null)
  {
    echo $row_id.' - '.$value;
  }

  function callback_fields()
  {
    $data['my'] = $this;
    $this->load->view('examples/callback_fields',$data);
  }

  function callback_fields_function($row_id = null,$value = null)
  {
    echo '<input type="text" value="Novan" name="created_by" readonly="readonly" class="form-control">';
  }

  function callback_before_insert()
  {
    $data['my'] = $this;
    $this->load->view('examples/callback_before_insert',$data);
  }

  function callback_before_insert_function()
  {
    $encrypt_name = md5($this->input->post('created_by'));
    $_POST['created_by'] = $encrypt_name;
    return true;
  }

  function callback_after_insert()
  {
    $data['my'] = $this;
    $this->load->view('examples/callback_before_insert',$data);
  }

  function callback_after_insert_function()
  {
    $encrypt_name = md5($this->input->post('created_by'));
    $_POST['created_by'] = $encrypt_name;
    return true;
  }


}

?>
