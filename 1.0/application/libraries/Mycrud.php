<?php
/*

MYCRUD LIBRARY
Author : Novan Bagus
Version : 1.0
Website : http://novanbagus.com/mycrud

*/

class Mycrud extends CI_Controller
{
	var $table = '';
	var $id = null;

	var $subject = '';
	var $perpage = 20;
	var $order_by = array();

	var $columns = array();
	var $extra_columns = array();
	var $fields = array();
	var $disable_columns = array();
	var $disable_fields = array();
	var $alias = array();
	var $where = array();
	var $where_in = array();
	var $or_where = array();
	var $items_id = array();

	var $disable_add = FALSE;
	var $disable_edit = FALSE;
	var $disable_read = FALSE;
	var $disable_delete = FALSE;
	var $disable_export = FALSE;
	var $disable_print = FALSE;

	var $callback_columns = array();
	var $callback_fields = array();
	var $callback_add_fields = array();
	var $callback_edit_fields = array();
	var $callback_before_insert = array();
	var $callback_after_insert = array();
	var $callback_before_update = array();
	var $callback_after_update = array();
	var $callback_before_delete = array();
	var $callback_after_delete = array();

	var $change_field_type = array();
	var $text_editor = array();
	var $set_password = array();
	var $set_upload_file = array();
	var $set_upload_image = array();
	var $set_relation = array();
	var $set_relation_nn = array();
	var $validation = array();
	var $myconfig = array();
	var $base_url = '';

	var $notification = null;

	//FILTERING
	var $filter_by = null;

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper('Mycrud');
	}

	public function initialize($config = array())
	{
		$this->base_url = base_url();

		if(count($config) > 0)
		{
			$this->order_by = array("id","ASC");

			foreach($config as $key => $val):
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			endforeach;
		}

		$this->myconfig = $config;
	}

	public function render()
	{
		if(isset($_GET['view']))
		{
			if($_GET['view'] == 'add')
			{
				if(isset($_GET['act']))
				{
					if( ($_GET['act'] == 'insert') and ($this->input->post('mycrud_insert') ) )
					{
						$this->add_process();
					}
				}
				$this->view_add();
			}
			if($_GET['view'] == 'read' and $_GET['id'] > 0)
			{
				$this->id = $_GET['id'];
				$this->view_read();
			}
			elseif($_GET['view'] == 'edit' and $_GET['id'] > 0)
			{
				$this->id = $_GET['id'];
				if(isset($_GET['act']))
				{
					if( ($_GET['act'] == 'update') and ($this->input->post('mycrud_update') ) )
					{
						$this->edit_process();
					}

				}
				$this->view_edit();
			}
			elseif($_GET['view'] == 'list')
			{
				if($this->input->post('mycrud_selected_action') == 'delete')
				{
					$this->delete_multiple_process();
					$this->view_list();
				}
				else
				if($this->input->post('mycrud_selected_action') == 'export')
				{
					$this->export_to_excel_selected();
					$this->view_list();
				}
				else
				if(isset($_GET['act']))
				{
					if($_GET['act'] == 'delete')
					{
						$this->id = $_GET['id'];
						$this->delete_process();
						$this->view_list();
					}
					else
					if($_GET['act'] == 'export_excel_all')
					{
						$this->export_to_excel_all();
						$this->view_list();
					}
					else{
						$this->view_list();
					}
				}
				else
				{
					$this->view_list();
				}


			}
			elseif($_GET['view'] == 'import')
			{
				if($this->input->post('mycrud_import') == 'ok')
				{
					if($this->import_data_process() == true)
					{
						$this->notification ='<div class="alert alert-success">Import Success</div>';
					}

					$this->view_import();


				}
				else
				if(isset($_GET['act']))
				{
					if($_GET['act'] == 'export_example')
					{
						$this->export_to_excel_example();
					}
				}
				$this->view_import();
			}

		}
		else
		{
			$this->view_list();
		}
	}

	function display_alias($field_name)
	{
		if(array_key_exists($field_name,$this->alias))
		{
			$data_return = $this->alias[$field_name];
		}
		else
		{
			$data_return = ucfirst(str_replace('_',' ',$field_name));
		}

		return $data_return;

	}

	function view_add()
	{
		if($this->disable_add == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div></div>";
			exit;
		}
		$data['mycrud'] = $this;
		$this->load->view('mycrud/mycrud_add',$data);
	}

	function view_list()
	{
		$data['mycrud'] = $this;

		if(isset($_GET['per_page']))
		{
			$offset = $_GET['per_page'];
		}
		else
		{
			$offset = 0;
		}

		$parse_query = '';
		foreach($_GET as $key => $val):

			if($key != 'per_page')
			{
				$parse_query .= '&'.$key."=".$val;
			}


		endforeach;

		// GET TOTAL QUERY
		$this->db->select('*');
		$this->db->from($this->table);

		if(isset($_GET['filter_by']))
		{
			$this->filter_by = $_GET['filter_by'];
			if(array_key_exists($_GET['filter_by'],$this->set_relation))
			{
				$options = explode(",",$this->set_relation[$this->filter_by][0]);
				$query_clause = $options[0].".id = ".$this->table.".".$this->filter_by;
				$like_clause = $options[0].".".$options[1];
				$this->db->join($options[0], ''.$query_clause.'','LEFT');
				$this->db->like($like_clause,$_GET['q'],'both');
			}
			else
			if(array_key_exists($_GET['filter_by'],$this->set_relation_nn))
			{
				$options = explode(",",$this->set_relation_nn[$this->filter_by]);
				$array_rel_id = $this->return_array_search_relation_nn($options,$_GET['q']);

				/*
				$i = 1;
				$array_ids = array();
				foreach($array_rel_id as $ids):

					$array_ids[] = $ids;
					if($i == 1)
					{
						$return_array_join_id .= "'".$ids."'";
					}
					else
					{
						$return_array_join_id .= ",'".$ids."'";
					}

					$i++;
				endforeach;

				$query_list = "SELECT * FROM $this->table WHERE id IN ($return_array_join_id) ";
				$query = $this->db->query($query_list);

				echo $query->num_rows();
				exit;
				*/

				$this->db->where_in('id',$array_rel_id);
				$query = $this->db->get($this->table);
				echo $query->num_rows();
				exit;
			}
			else
			{
				$this->db->like($_GET['filter_by'],$_GET['q'],'both');
			}
		}
		else
		{
			if(count($this->where) > 0)
			{
				foreach($this->where as $field => $val):

					$this->db->where($field,$val);

				endforeach;
			}

			if(count($this->or_where) > 0)
			{
				foreach($this->or_where as $field => $val):

					$this->db->or_where($field,$val);

				endforeach;
			}

			if(count($this->where_in) > 0)
			{
				foreach($this->where_in as $field => $val):

					$this->db->where_in($field,$val);

				endforeach;
			}


		}


		$data_total = $this->db->get();

		$this->load->library('pagination');
		$config = array (
							'base_url' => current_url()."?".$parse_query,
							'per_page' => $this->perpage,
							'total_rows' => $data_total->num_rows(),
							'anchor_class' => ' class="btn btn-default btn-sm" ',
							'cur_tag_open' => ' <strong><a href="#" class="btn btn-success btn-sm">',
							'cur_tag_close' => '</a></strong>',
							'page_query_string' => TRUE
						);

		$this->pagination->initialize($config);

		// LIST VIEW QUERY

		$this->db->select('*');
		$this->db->from($this->table);



		if(isset($_GET['filter_by']))
		{
			$this->filter_by = $_GET['filter_by'];
			if(array_key_exists($_GET['filter_by'],$this->set_relation))
			{

				$options = explode(",",$this->set_relation[$this->filter_by][0],$_GET['q']);

				$query_clause = $options[0].".id = ".$this->table.".".$this->filter_by;
				$like_clause = $options[0].".".$options[1];
				$this->db->join($options[0], ''.$query_clause.'','LEFT');
				$this->db->like($like_clause,$_GET['q'],'both');


			}
			if(array_key_exists($_GET['filter_by'],$this->set_relation_nn))
			{
				$options = explode(",",$this->set_relation_nn[$this->filter_by]);
				$array_rel_id = $this->return_array_search_relation_nn($options,$_GET['q']);

				$this->db->where_in('id',$array_rel_id);



			}
			else
			{
				$this->db->like($_GET['filter_by'],$_GET['q'],'both');
			}

			//print_r($array_rel_id);
			//exit;

		}
		else
		{
			if(count($this->where) > 0)
			{
				foreach($this->where as $field => $val):

					$this->db->where($field,$val);

				endforeach;
			}

			if(count($this->or_where) > 0)
			{
				foreach($this->or_where as $field => $val):

					$this->db->or_where($field,$val);

				endforeach;
			}

			if(count($this->where_in) > 0)
			{
				foreach($this->where_in as $field => $val):

					$this->db->where_in($field,$val);

				endforeach;
			}
		}

		if(isset($_GET['order_by']))
		{
			if(array_key_exists($_GET['order_by'],$this->set_relation))
			{

				$options = explode(",",$this->set_relation[$_GET['order_by']][0]);
				$query_clause = $options[0].".id = ".$this->table.".".$_GET['order_by'];
				$this->db->join($options[0], ''.$query_clause.'','LEFT');

				$this->db->order_by($options[0].".".$options[1],$_GET['sort']);
			}
			else
			{
				$this->db->order_by($_GET['order_by'],$_GET['sort']);
			}

		}
		else
		{
			$this->db->order_by($this->order_by[0],$this->order_by[1]);
		}


		$this->db->limit($this->perpage,$offset);

		$data['row'] = $this->db->get();

		$data['total'] = $data_total->num_rows();

		$this->ci = & get_instance();
		$this->ci->load->view('mycrud/mycrud_list',$data);

		if(isset($_GET['act']))
		{
			if($_GET['act'] == 'export_excel')
			{
				$result_array = $data['row']->result_array();
				$this->export_to_excel($result_array);
			}
		}

	}

	function return_array_search_relation_nn($options = array(),$q)
	{

		$this->db->like($options[4],$q);

		$query_sql_join = "SELECT * FROM $options[1] WHERE $options[4] LIKE '%$q%' ";
		$query_join = $this->db->query($query_sql_join);

		$array_join_id = array();
		$return_array_join_id = '';
		$i = 1;
		foreach($query_join->result() as $joins):

			$array_join_id[] = $joins->id;
			if($i == 1)
			{
				$return_array_join_id .= "'".$joins->id."'";
			}
			else
			{
				$return_array_join_id .= ",'".$joins->id."'";
			}

		$i++;
		endforeach;

		$query_sql_rel = "SELECT * FROM $options[0] WHERE $options[3] IN ($return_array_join_id)";
		$query_rel = $this->db->query($query_sql_rel);

		$array_rel_id = array();
		foreach($query_rel->result_array() as $rels):

			$array_rel_id[] = $rels[$options[2]];

		endforeach;

		return $array_rel_id;

	}

	function view_read()
	{
		if($this->disable_read == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div><a href='?view=list'>Back to list</a></div>";
			exit;
		}

		$data['mycrud'] =  $this;

		$this->db->where('id',$this->id);
		$data['detail'] = $this->db->get($this->table)->row_array();

		$this->load->view('mycrud/mycrud_read',$data);
	}

	function view_edit()
	{
		if($this->disable_edit == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div><a href='?view=list'>Back to list</a></div>";
			exit;
		}

		$data['mycrud'] =  $this;

		$this->db->where('id',$this->id);
		$data['detail'] = $this->db->get($this->table)->row_array();

		$this->load->view('mycrud/mycrud_edit',$data);
	}

	function view_import()
	{
		$data['mycrud'] = $this;
		$this->load->view('mycrud/mycrud_import',$data);
	}

	function add_process()
	{
		// Generate rules validation
		$this->load->library('form_validation');

		if(count($this->validation) > 0)
		{
			foreach($this->validation as $key => $val):
				$this_field = $key;
				$this_rules = explode('|',$val[0]);

				if(count($val) > 1)
				{
					$this_label = $val[1];
				}
				else
				{
					$this_label = ucfirst(str_replace('_',' ',$this_field));
				}

				foreach($this_rules as $rules):

					$this->form_validation->set_rules($this_field,$this_label,$rules);

				endforeach;
			endforeach;
		}

		if($this->disable_add == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div><a href='?view=list'>Back to list</a></div>";
			exit;
		}

		// Validation
		if(count($this->validation) > 0){
			if($this->form_validation->run() == TRUE)
			{
				$this->insert_process();
			}
			// End validation
		}
		else
		{
			$this->insert_process();
		}

	}

	function insert_process()
	{
		// Data
		$query_field = $this->db->query("SHOW COLUMNS FROM $this->table ");

		if(count($this->callback_before_insert) > 0)
		{
			$callback_class = $this->callback_before_insert[0];
			$callback_function = $this->callback_before_insert[1];

			$callback_class->$callback_function();
		}

		if(count($this->fields) > 0)
		{
			foreach($this->fields as $fields):
				if(!in_array($fields,$this->disable_fields))
				{
					if(!array_key_exists($fields,$this->set_relation_nn))
					{
						if((array_key_exists($fields,$this->set_upload_image)) or (array_key_exists($fields,$this->set_upload_file)))
						{
							if(array_key_exists($fields,$this->set_upload_image))
							{
								$options = explode(",",$this->set_upload_image[$fields]);
							}
							else
							{
								$options = explode(",",$this->set_upload_file[$fields]);
							}

							$config['upload_path'] = './'.$options[0].'/';
							$config['allowed_types'] = $options[1];
							$config['max_size']	= $options[2];

							//$this->load->library('upload', $config);
							$this->load->library('upload');
							$this->upload->initialize($config);

							if ( ! $this->upload->do_upload($fields))
							{
								$error = $this->upload->display_errors();

								if($error == 'You did not select a file to upload.')
								{
									$data_insert[$fields] = '';
								}
								else
								{
									print($error);
									exit;
								}
							}
							else
							{
								$upload_data = $this->upload->data();

								$data_insert[$fields] = $upload_data['file_name'];
							}
						}
						else
						{
							$data_insert[$fields] = $this->input->post($fields);
						}

					}
				}
			endforeach;
			$this->db->insert($this->table,$data_insert);
		}
		else
		{
			foreach($query_field->result() as $fields):

			if(!in_array($fields->Field,$this->disable_fields))
			{

				if((array_key_exists($fields->Field,$this->set_upload_image)) or (array_key_exists($fields->Field,$this->set_upload_file)))
				{
					if(array_key_exists($fields->Field,$this->set_upload_image))
					{
						$options = explode(",",$this->set_upload_image[$fields->Field]);
					}
					else
					{
						$options = explode(",",$this->set_upload_file[$fields->Field]);
					}

					$config['upload_path'] = './'.$options[0].'/';
					$config['allowed_types'] = $options[1];
					$config['max_size']	= $options[2];

					//$this->load->library('upload', $config);
					$this->load->library('upload');
					$this->upload->initialize($config);

					if ( ! $this->upload->do_upload($fields->Field))
					{
						$error = $this->upload->display_errors();

						if($error == 'You did not select a file to upload.')
						{
							$data_insert[$fields] = '';
						}
						else
						{
							print($error);
							exit;
						}

					}
					else
					{
						$upload_data = $this->upload->data();

						$data_insert[$fields->Field] = $upload_data['file_name'];
					}
				}
				else
				{
					$data_insert[$fields->Field] = $this->input->post($fields->Field);
				}

			}

			endforeach;

			$this->db->insert($this->table,$data_insert);
		}


		$primary_key = mysql_insert_id();

		if(count($this->set_relation_nn) > 0)
		{
			foreach($this->set_relation_nn as $key => $val):
				$options = explode(",",$val);
				$value = $this->input->post($key);
				$this->set_relation_nn_insert($key,$options[0],$options[1],$options[2],$options[3],$primary_key,$value);
			endforeach;
		}

		$this->notification = "<div class='alert alert-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Data has been Added</div>";

		if(count($this->callback_after_insert) > 0)
		{
			$callback_class = $this->callback_after_insert[0];
			$callback_function = $this->callback_after_insert[1];

			$callback_class->$callback_function($primary_key);
		}
	}

	function edit_process()
	{
		// Generate rules validation
		$this->load->library('form_validation');
		if(count($this->validation) > 0)
		{
			foreach($this->validation as $key => $val):
				$this_field = $key;
				$this_rules = explode('|',$val[0]);

				if(count($val) > 1)
				{
					$this_label = $val[1];
				}
				else
				{
					$this_label = ucfirst(str_replace('_',' ',$this_field));
				}

				foreach($this_rules as $rules):

			    $pos = strpos($rules,'is_unique');
			    if ($pos === false) {
			    	$this->form_validation->set_rules($this_field,$this_label,$rules);
			    }

				endforeach;
			endforeach;
		}

		if($this->disable_edit == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div><a href='?view=list'>Back to list</a></div>";
			exit;
		}

		// Validation
		if(count($this->validation) > 0){
			if($this->form_validation->run() == TRUE)
			{
				$this->update_process();
			}
			else {
				echo validation_errors();

			}
			// End validation
		}
		else
		{
			$this->update_process();
		}

	}

	function update_process()
	{
		// Data
		$query_field = $this->db->query("SHOW COLUMNS FROM $this->table ");

		if(count($this->callback_before_update) > 0)
		{
			$callback_class = $this->callback_before_update[0];
			$callback_function = $this->callback_before_update[1];

			$callback_class->$callback_function($this->id);
		}


		$where = array('id' => $this->id);

		if(count($this->fields) > 0)
		{
			foreach($this->fields as $fields):
				if(!in_array($fields,$this->disable_fields))
				{
					if(!array_key_exists($fields,$this->set_relation_nn))
					{
						if((array_key_exists($fields,$this->set_upload_image)) or (array_key_exists($fields,$this->set_upload_file)))
						{
							if(array_key_exists($fields,$this->set_upload_image))
							{
								$options = explode(",",$this->set_upload_image[$fields]);
							}
							else
							{
								$options = explode(",",$this->set_upload_file[$fields]);
							}

							$config['upload_path'] = './'.$options[0].'/';
							$config['allowed_types'] = $options[1];
							$config['max_size']	= $options[2];

							//$this->load->library('upload', $config);
							$this->load->library('upload');
							$this->upload->initialize($config);

							if ( ! $this->upload->do_upload($fields))
							{
								$error = $this->upload->display_errors();

								if(strip_tags($error) != "You did not select a file to upload.")
								{
									print($error);
									exit;
								}


							}
							else
							{
								$upload_data = $this->upload->data();

								$data_update[$fields] = $upload_data['file_name'];
							}
						}
						else
						{
							$data_update[$fields] = $this->input->post($fields);
						}

					}
				}
			endforeach;
			$this->db->update($this->table,$data_update,$where);
		}
		else
		{

			foreach($query_field->result() as $fields):
			if($fields->Key != 'PRI')
			{
				if(!in_array($fields->Field,$this->disable_fields))
				{
					if((array_key_exists($fields->Field,$this->set_upload_image)) or (array_key_exists($fields->Field,$this->set_upload_file)))
					{
						if(array_key_exists($fields->Field,$this->set_upload_image))
						{
							$options = explode(",",$this->set_upload_image[$fields->Field]);
						}
						else
						{
							$options = explode(",",$this->set_upload_file[$fields->Field]);
						}

						$config['upload_path'] = './'.$options[0].'/';
						$config['allowed_types'] = $options[1];
						$config['max_size']	= $options[2];

						//$this->load->library('upload', $config);
						$this->load->library('upload');
						$this->upload->initialize($config);

						if ( ! $this->upload->do_upload($fields->Field))
						{
							$error = $this->upload->display_errors();

							if(strip_tags($error) != "You did not select a file to upload.")
							{
								print($error);
								exit;
							}

						}
						else
						{
							$upload_data = $this->upload->data();

							$data_update[$fields->Field] = $upload_data['file_name'];
						}
					}
					else
					{
						$data_update[$fields->Field] = $this->input->post($fields->Field);
					}

				}
			}
			endforeach;

			$this->db->update($this->table,$data_update,$where);
		}

		$primary_key = $this->id;

		if(count($this->set_relation_nn) > 0)
		{
			foreach($this->set_relation_nn as $key => $val):
				$options = explode(",",$val);
				$value = $this->input->post($key);
				$this->set_relation_nn_update($key,$options[0],$options[1],$options[2],$options[3],$primary_key,$value);
			endforeach;
		}

		$this->notification = "<div class='alert alert-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Data has been Updated</div>";

		if(count($this->callback_after_update) > 0)
		{
			$callback_class = $this->callback_after_update[0];
			$callback_function = $this->callback_after_update[1];

			$callback_class->$callback_function($primary_key);
		}
	}

	function delete_process()
	{
		if($this->disable_delete == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div><a href='?view=list'>Back to list</a></div>";
			exit;
		}

		if(count($this->callback_before_delete) > 0)
		{
			$callback_class = $this->callback_before_delete[0];
			$callback_function = $this->callback_before_delete[1];

			$callback_class->$callback_function($this->id);
		}

		// Data
		$where = array('id' => $this->id);
		$this->db->delete($this->table,$where);
		$this->notification = "<div class='alert alert-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Data has been Deleted</div>";

		if(count($this->callback_after_delete) > 0)
		{
			$callback_class = $this->callback_after_delete[0];
			$callback_function = $this->callback_after_delete[1];

			$callback_class->$callback_function($this->id);
		}
	}

	function delete_multiple_process()
	{
		if($this->disable_delete == true)
		{
			echo "<div class='container-fluid'><div class='alert alert-danger'>You don't have permission to Access</div><a href='?view=list'>Back to list</a></div>";
			exit;
		}

		$items_id = $this->input->post('mycrud_check_item_id');
		foreach($items_id as $id):

			$this->id = $id;
			$this->delete_process();

		endforeach;
	}



	function define_field($field_name,$type,$value = null)
	{
		// Split Data Type Format
		$type = explode('(',$type);
		$type_data = $type[0];

		if(count($type) > 1)
		{
			$value_data = substr($type[1],0,-1);
		}

		// Define field type
		if(strtolower($type_data) == 'varchar')
		{
			$data_return = "<input type='text' class='form-control  input-sm' name='".$field_name."' value='".$value."' />";
		}
		else
		if(strtolower($type_data) == 'int')
		{
			$data_return = "<input type='number'  class='form-control  input-sm' name='".$field_name."' value='".$value."' />";
		}
		else
		if(strtolower($type_data) == 'text')
		{
			$data_return = "<textarea class='form-control  input-sm' name='".$field_name."'>".$value."</textarea>";
		}
		else
		if(strtolower($type_data) == 'enum')
		{
			$data_return = "<select class='form-control  input-sm' name='".$field_name."'>";

			$explode_value_data = explode(",",$value_data);

			foreach($explode_value_data as $value_data_item):

				$value_data_item = str_replace("'","",$value_data_item);

				if($value_data_item == $value)
				{
					$data_return .= "<option value='".$value_data_item."' selected='selected'>".$value_data_item."</option>";
				}
				else
				{
					$data_return .= "<option value='".$value_data_item."'>".$value_data_item."</option>";
				}


			endforeach;

			$data_return .="</select>";
		}
		elseif(strtolower($type_data) == 'date')
		{
			$data_return = "<input type='text' name='".$field_name."' value='".$value."' class='form-control input-sm datepicker-date' />";
		}
		else
		{
			$data_return = "<textarea class='form-control input-sm' name='".$field_name."'>".$value."</textarea>";
		}

		return $data_return;
	}

	function change_field($field_name,$type,$value = null,$current_value = null)
	{
		if(strtolower($type) == 'hidden')
		{
			$data_return = "<input type='hidden' name='".$field_name."' value='".$value."' />";

			return $data_return;
		}
		else
		if(strtolower($type) == 'dropdown')
		{
			$trim_data = explode(",",$value);

			$data_return = "<select name='".$field_name."' class='form-control  input-sm'>";
			foreach($trim_data as $trim_data_list):

				$trim_value_label = explode('|',$trim_data_list);

				$data_return .= "<option value='".$trim_value_label[0]."'>".$trim_value_label[1]."</option>";

			endforeach;
			$data_return .= "</select>";

			return $data_return;
		}
		else
		if(strtolower($type) == 'radio')
		{
			$trim_data = explode(",",$value);

			$data_return = "";
			foreach($trim_data as $trim_data_list):

				$trim_value_label = explode('|',$trim_data_list);

				$data_return .= "<div class='radio'><label><input type='radio' name='".$field_name."' value='".$trim_value_label[0]."'>".$trim_value_label[1]."</label></div>";

			endforeach;

			return $data_return;
		}
	}

	function create_text_editor($field_name,$value = null)
	{
		$data_return = "<textarea name='".$field_name."' id='".$field_name."' >".$value."</textarea>";
		$data_return .= "<script type='text/javascript'> CKEDITOR.replace( '".$field_name."' );</script>";

		return $data_return;
	}

	function set_password_field($field_name,$type = 'ci_encrypt',$secret_key = null,$value = null)
	{
		if($value != null)
		{
			if($secret_key != null)
			{
				$view_password = $this->encrypt->decode($value,$secret_key);
			}
			else {
				$view_password = $this->encrypt->decode($value);
			}
		}
		else {
			$view_password = null;
		}

		return $data_return = "<input type='password' class='form-control' name='".$field_name."' id='".$field_name."' value='".$view_password."' />";
	}

	function set_relation_column($field_name,$rel_table,$rel_label_field,$value = null)
	{
		$this->db->where('id',$value);
		$get_data = $this->db->get($rel_table);

		if($get_data->num_rows() == 1)
		{
			$data = $get_data->row_array();

			$data_return = $data[$rel_label_field];
		}
		else
		{
			$data_return = '-';
		}

		return $data_return;
	}

	function set_relation_field($field_name,$rel_table,$rel_label_field,$where = array(),$value = null)
	{
		// Get all data
		if(count($where) > 0)
		{
			$this->db->where($where[0],$where[1]);
		}

		$rel_data = $this->db->get($rel_table);

		$data_return = "<select name='".$field_name."' class='form-control  input-sm'><option value=''>- Please Select ".ucfirst($rel_label_field)." -</option>";
		foreach($rel_data->result() as $rel_data_list):

			if($rel_data_list->id == $value)
			{
				$data_return .= "<option value='".$rel_data_list->id."' selected='selected'>".$rel_data_list->$rel_label_field."</option>";
			}
			else
			{
				$data_return .= "<option value='".$rel_data_list->id."'>".$rel_data_list->$rel_label_field."</option>";
			}


		endforeach;
		$data_return .= "</select>";

		return $data_return;
	}

	function set_relation_nn_field($field_name,$rel_table,$join_table,$rel_id,$join_id,$join_label,$value = null)
	{
		$data_join = $this->db->get($join_table);

		$data_return = "<select name='".$field_name."[]' class='form-control input-sm' multiple>";
		foreach($data_join->result() as $joins):

			if($value != null)
			{
				$this->db->where($rel_id,$value);
				$this->db->where($join_id,$joins->id);

				$this_data_rel = $this->db->get($rel_table);
				if($this_data_rel->num_rows() > 0)
				{
					$data_return .= "<option value='".$joins->id."' selected='selected'>".$joins->$join_label."</option>";
				}
				else
				{
					$data_return .= "<option value='".$joins->id."'>".$joins->$join_label."</option>";
				}

			}
			else
			{
				$data_return .= "<option value='".$joins->id."'>".$joins->$join_label."</option>";
			}

		endforeach;

		$data_return .= "</select>";

		return $data_return;
	}

	function set_relation_nn_column($field_name,$rel_table,$join_table,$rel_id,$join_id,$join_label,$value = null)
	{
		$this->db->where($rel_id,$value);

		$data_rel = $this->db->get($rel_table);

		$data_return = "";

		$i = 1;
		foreach($data_rel->result() as $rels):

			if($i != 1)
			{
				$data_return .= ", ";
			}

			$this->db->where('id',$rels->$join_id);
			$data_join = $this->db->get($join_table);

			if($data_join->num_rows() > 0)
			{
				$joins = $data_join->row_array();
				$data_return .= $joins[$join_label];
			}
			else
			{
				$data_return .= '<font color="red">Relation data not found</a>';
			}

		$i++;
		endforeach;

		return $data_return;
	}

	function set_upload_image_field($field_name,$path,$ext,$size,$value = null)
	{
		$data_return = "<input type='file' name='".$field_name."' /><br/>Allowed Extension : ".$ext." - Max Size: ".$size." Kb ";
		if($value != null)
		{
			$data_return .= "<br/><a href='".base_url().$path."/".$value."' target='_blank'><img width='100' class='img-thumbnail' src='".base_url().$path."/".$value."' /></a>";
		}

		return $data_return;

	}

	function set_upload_file_field($field_name,$path,$ext,$size,$value = null)
	{
		$data_return = "<input type='file' name='".$field_name."' /><br/>Allowed Extension : ".$ext." - Max Size: ".$size." Kb ";
		if($value != null)
		{
			$data_return .= "<br/><a href='".base_url().$path."/".$value."' />".$value."</a>";
		}

		return $data_return;

	}

	private function set_relation_nn_insert($fields,$rel_table,$join_table,$rel_id,$join_id,$primary_key,$value = array())
	{
		foreach($value as $val):

			$data_insert[] = array($rel_id => $primary_key, $join_id => $val);

		endforeach;

		$this->db->insert_batch($rel_table,$data_insert);
	}

	private function set_relation_nn_update($fields,$rel_table,$join_table,$rel_id,$join_id,$primary_key,$value = array())
	{
		$this->db->where($rel_id,$primary_key);
		$data_rel_exists = $this->db->get($rel_table);

		$data_array_exists = array();
		foreach($data_rel_exists->result() as $data_exists):

			if($value != null)
			{
				if(in_array($data_exists->$join_id,$value))
				{
					$data_array_exists[] = $data_exists->$join_id;
				}
				else
				{
					$where = array('id' => $data_exists->id);
					$this->db->delete($rel_table,$where);
				}
			}
			else
			{
				$where = array('id' => $data_exists->id);
				$this->db->delete($rel_table,$where);
			}

		endforeach;

		if($value != null)
		{
			$data_insert = array();
			foreach($value as $val):

				if(!in_array($val,$data_array_exists))
				{
					$data_insert[] = array($rel_id => $primary_key, $join_id => $val);
				}

			endforeach;

			if(count($data_insert) > 0)
			{
				$this->db->insert_batch($rel_table,$data_insert);
			}

		}

	}


	// Read
	function set_upload_image_read($field_name,$path,$ext,$size,$value = null)
	{
		$data_return = "";
		if($value != null)
		{
			$data_return .= "<br/><a href='".base_url().$path."/".$value."' target='_blank'><img width='100' class='img-thumbnail' src='".base_url().$path."/".$value."' /></a>";
		}

		return $data_return;

	}

	function set_upload_file_read($field_name,$path,$ext,$size,$value = null)
	{
		$data_return = "";
		if($value != null)
		{
			$data_return .= "<br/><a href='".base_url().$path."/".$value."' />".$value."</a>";
		}

		return $data_return;

	}

	function set_relation_read($field_name,$rel_table,$rel_label_field,$where = array(),$value = null)
	{
		// Get all data

		$this->db->where('id',$value);
		$rel_data = $this->db->get($rel_table)->row_array();
		$data_return = $rel_data[$rel_label_field];

		return $data_return;
	}

	function set_relation_nn_read($field_name,$rel_table,$join_table,$rel_id,$join_id,$join_label,$value = null)
	{
		$this->db->where($rel_id,$value);
		$data_rel = $this->db->get($rel_table);

		$data_return = '';
		$i = 1;
		foreach($data_rel as $rels):

			$this->dn->where('id',$rels->$join_id);
			$this_data = $this->db->get($join_table)->row_array();
			if($i != 1)
			{
				$data_return .= ', ';
			}
			$data_return .= $this_data[$join_label];

		$i++;
		endforeach;

		return $data_return;
	}

	function export_to_excel($data_row)
	{
		//load PHPExcel library
	    $this->load->library('Mycrud_excel');

	    // Create new PHPExcel object
	    $objPHPExcel = new PHPExcel();

	    // Set document properties
	    $objPHPExcel->getProperties()->setCreator("mohamadikhwan.com")
	            ->setLastModifiedBy("mohamadikhwan.com")
	            ->setTitle("Office 2007 XLSX Test Document")
	            ->setSubject("Office 2007 XLSX Test Document")
	            ->setDescription("Test document for Office 2007 XLSX, generated by PHP classes.")
	            ->setKeywords("office 2007 openxml php")
	            ->setCategory("Test result file");


	    // Add some data


	    $active_sheet = $objPHPExcel->setActiveSheetIndex(0);

	   	$array_alphabet = range('A','Z');

		// SET Header
	   	if(count($this->columns) > 0)
	   	{
			$i = 0;
			foreach($this->columns as $fields):

			 	if(!in_array($fields,$this->disable_columns))
			 	{
					$active_sheet->setCellValue($array_alphabet[$i].'1', $this->display_alias($fields));
					$i++;
				}

			endforeach;
		}
		else
		{
			//exit("B");
			$query_field = "SHOW COLUMNS FROM $this->table";
			$query_field = $this->db->query($query_field);

			$i = 0;
			foreach($query_field->result() as $fields):
    			if($fields->Key != 'PRI') {
    				if(!in_array($fields->Field,$this->disable_columns))
    				{

    					$active_sheet->setCellValue($array_alphabet[$i].'1', $this->display_alias($fields->Field));
    					$i++;
    				}
    			}

    		endforeach;

		}

		// SET Value

		$line = 2;
		$array_alphabet = range('A','Z');
		foreach($data_row as $row):
		   	if(count($this->columns) > 0)
		   	{

				$i = 0;
				foreach($this->columns as $fields):

				 	if(!in_array($fields,$this->disable_columns))
				 	{
				 		if(array_key_exists($fields,$this->set_relation))
				 		{
				 			$options = explode(",",$this->set_relation[$fields][0]);
							$value = $this->set_relation_column($fields,$options[0],$options[1],$row[$fields]);
						}
						elseif(array_key_exists($fields,$this->set_relation_nn))
						{
							$options = explode(",",$this->set_relation_nn[$fields]);
							$value = $this->set_relation_nn_column($fields,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']);
						}
						else
						{
							$value = $row[$fields];
						}

						$active_sheet->setCellValue($array_alphabet[$i].$line, $value);

						//echo $array_alphabet[$i].$line ." = ".$row[$fields];
						$i++;
					}

				endforeach;
			}
			else
			{

				$query_field = "SHOW COLUMNS FROM $this->table";
				$query_field = $this->db->query($query_field);

				$i = 0;
				foreach($query_field->result() as $fields):
	    			if($fields->Key != 'PRI') {
	    				if(!in_array($fields->Field,$this->disable_columns))
	    				{

	    					if(array_key_exists($fields->Field,$this->set_relation))
					 		{
					 			$options = explode(",",$this->set_relation[$fields->Field][0]);
								$value = $this->set_relation_column($fields->Field,$options[0],$options[1],$row[$fields->Field]);
							}
							elseif(array_key_exists($fields->Field,$this->set_relation_nn))
							{
								$options = explode(",",$this->set_relation_nn[$fields->Field]);
								$value = $this->set_relation_nn_column($fields->Field,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']);
							}
							else
							{
								$value = $row[$fields->Field];
							}

							$active_sheet->setCellValue($array_alphabet[$i].$line, $value);

	    					$i++;
	    				}
	    			}

	    		endforeach;

			}

		$line++;
		endforeach;


		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);


	    // Rename worksheet (worksheet, not filename)
	    $objPHPExcel->getActiveSheet()->setTitle('Export '.$this->subject);


	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	    $objPHPExcel->setActiveSheetIndex(0);

	    // Redirect output to a client’s web browser (Excel2007)
	    //clean the output buffer
	    ob_end_clean();

	   // Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->subject.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	function export_to_excel_selected()
	{
		//load PHPExcel library
	    $this->load->library('Mycrud_excel');

	    // Create new PHPExcel object
	    $objPHPExcel = new PHPExcel();

	    // Set document properties
	    $objPHPExcel->getProperties()->setCreator("mohamadikhwan.com")
	            ->setLastModifiedBy("mohamadikhwan.com")
	            ->setTitle("Office 2007 XLSX Test Document")
	            ->setSubject("Office 2007 XLSX Test Document")
	            ->setDescription("Test document for Office 2007 XLSX, generated by PHP classes.")
	            ->setKeywords("office 2007 openxml php")
	            ->setCategory("Test result file");


	    // Add some data


	    $active_sheet = $objPHPExcel->setActiveSheetIndex(0);

	   	$array_alphabet = range('A','Z');

		// Data selected
		$items_id = $this->input->post('mycrud_check_item_id');
		$this->db->where_in('id',$items_id);
		$data_row = $this->db->get($this->table)->result_array();

		// SET Header
	   	if(count($this->columns) > 0)
	   	{
			$i = 0;
			foreach($this->columns as $fields):

			 	if(!in_array($fields,$this->disable_columns))
			 	{
					$active_sheet->setCellValue($array_alphabet[$i].'1', $this->display_alias($fields));
					$i++;
				}

			endforeach;
		}
		else
		{
			//exit("B");
			$query_field = "SHOW COLUMNS FROM $this->table";
			$query_field = $this->db->query($query_field);

			$i = 0;
			foreach($query_field->result() as $fields):
    			if($fields->Key != 'PRI') {
    				if(!in_array($fields->Field,$this->disable_columns))
    				{

    					$active_sheet->setCellValue($array_alphabet[$i].'1', $this->display_alias($fields->Field));
    					$i++;
    				}
    			}

    		endforeach;

		}

		// SET Value

		$line = 2;
		$array_alphabet = range('A','Z');
		foreach($data_row as $row):
		   	if(count($this->columns) > 0)
		   	{

				$i = 0;
				foreach($this->columns as $fields):

				 	if(!in_array($fields,$this->disable_columns))
				 	{
				 		if(array_key_exists($fields,$this->set_relation))
				 		{
				 			$options = explode(",",$this->set_relation[$fields][0]);
							$value = $this->set_relation_column($fields,$options[0],$options[1],$row[$fields]);
						}
						elseif(array_key_exists($fields,$this->set_relation_nn))
						{
							$options = explode(",",$this->set_relation_nn[$fields]);
							$value = $this->set_relation_nn_column($fields,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']);
						}
						else
						{
							$value = $row[$fields];
						}

						$active_sheet->setCellValue($array_alphabet[$i].$line, $value);

						//echo $array_alphabet[$i].$line ." = ".$row[$fields];
						$i++;
					}

				endforeach;
			}
			else
			{

				$query_field = "SHOW COLUMNS FROM $this->table";
				$query_field = $this->db->query($query_field);

				$i = 0;
				foreach($query_field->result() as $fields):
	    			if($fields->Key != 'PRI') {
	    				if(!in_array($fields->Field,$this->disable_columns))
	    				{

	    					if(array_key_exists($fields->Field,$this->set_relation))
					 		{
					 			$options = explode(",",$this->set_relation[$fields->Field][0]);
								$value = $this->set_relation_column($fields->Field,$options[0],$options[1],$row[$fields->Field]);
							}
							elseif(array_key_exists($fields->Field,$this->set_relation_nn))
							{
								$options = explode(",",$this->set_relation_nn[$fields->Field]);
								$value = $this->set_relation_nn_column($fields->Field,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']);
							}
							else
							{
								$value = $row[$fields->Field];
							}

							$active_sheet->setCellValue($array_alphabet[$i].$line, $value);

	    					$i++;
	    				}
	    			}

	    		endforeach;

			}

		$line++;
		endforeach;


		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);


	    // Rename worksheet (worksheet, not filename)
	    $objPHPExcel->getActiveSheet()->setTitle('Export '.$this->subject);


	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	    $objPHPExcel->setActiveSheetIndex(0);

	    // Redirect output to a client’s web browser (Excel2007)
	    //clean the output buffer
	    ob_end_clean();

	   // Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->subject.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	function export_to_excel_all()
	{
		//load PHPExcel library
	    $this->load->library('Mycrud_excel');

	    // Create new PHPExcel object
	    $objPHPExcel = new PHPExcel();

	    // Set document properties
	    $objPHPExcel->getProperties()->setCreator("mohamadikhwan.com")
	            ->setLastModifiedBy("mohamadikhwan.com")
	            ->setTitle("Office 2007 XLSX Test Document")
	            ->setSubject("Office 2007 XLSX Test Document")
	            ->setDescription("Test document for Office 2007 XLSX, generated by PHP classes.")
	            ->setKeywords("office 2007 openxml php")
	            ->setCategory("Test result file");


	    // Add some data


	    $active_sheet = $objPHPExcel->setActiveSheetIndex(0);

	   	$array_alphabet = range('A','Z');

	   	// Data
	   	$data_row = $this->db->get($this->table)->result_array();

		// SET Header
	   	if(count($this->columns) > 0)
	   	{
			$i = 0;
			foreach($this->columns as $fields):

			 	if(!in_array($fields,$this->disable_columns))
			 	{
					$active_sheet->setCellValue($array_alphabet[$i].'1', $this->display_alias($fields));
					$i++;
				}

			endforeach;
		}
		else
		{
			//exit("B");
			$query_field = "SHOW COLUMNS FROM $this->table";
			$query_field = $this->db->query($query_field);

			$i = 0;
			foreach($query_field->result() as $fields):
    			if($fields->Key != 'PRI') {
    				if(!in_array($fields->Field,$this->disable_columns))
    				{
    					$active_sheet->setCellValue($array_alphabet[$i].'1', $this->display_alias($fields->Field));
    					$i++;
    				}
    			}

    		endforeach;

		}

		// SET Value

		$line = 2;
		$array_alphabet = range('A','Z');
		foreach($data_row as $row):
		   	if(count($this->columns) > 0)
		   	{

				$i = 0;
				foreach($this->columns as $fields):

				 	if(!in_array($fields,$this->disable_columns))
				 	{
				 		if(array_key_exists($fields,$this->set_relation))
				 		{
				 			$options = explode(",",$this->set_relation[$fields][0]);
							$value = $this->set_relation_column($fields,$options[0],$options[1],$row[$fields]);
						}
						elseif(array_key_exists($fields,$this->set_relation_nn))
						{
							$options = explode(",",$this->set_relation_nn[$fields]);
							$value = $this->set_relation_nn_column($fields,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']);
						}
						else
						{
							$value = $row[$fields];
						}

						$active_sheet->setCellValue($array_alphabet[$i].$line, $value);

						//echo $array_alphabet[$i].$line ." = ".$row[$fields];
						$i++;
					}

				endforeach;
			}
			else
			{

				$query_field = "SHOW COLUMNS FROM $this->table";
				$query_field = $this->db->query($query_field);

				$i = 0;
				foreach($query_field->result() as $fields):
	    			if($fields->Key != 'PRI') {
	    				if(!in_array($fields->Field,$this->disable_columns))
	    				{

	    					if(array_key_exists($fields->Field,$this->set_relation))
					 		{
					 			$options = explode(",",$this->set_relation[$fields->Field][0]);
								$value = $this->set_relation_column($fields->Field,$options[0],$options[1],$row[$fields->Field]);
							}
							elseif(array_key_exists($fields->Field,$this->set_relation_nn))
							{
								$options = explode(",",$this->set_relation_nn[$fields->Field]);
								$value = $this->set_relation_nn_column($fields->Field,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']);
							}
							else
							{
								$value = $row[$fields->Field];
							}

							$active_sheet->setCellValue($array_alphabet[$i].$line, $value);

	    					$i++;
	    				}
	    			}

	    		endforeach;

			}

		$line++;
		endforeach;


		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);


	    // Rename worksheet (worksheet, not filename)
	    $objPHPExcel->getActiveSheet()->setTitle('Export '.$this->subject);


	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	    $objPHPExcel->setActiveSheetIndex(0);

	    // Redirect output to a client’s web browser (Excel2007)
	    //clean the output buffer
	    ob_end_clean();

	   // Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->subject.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	function export_to_excel_example()
	{
		//load PHPExcel library
	    $this->load->library('Mycrud_excel');

	    // Create new PHPExcel object
	    $objPHPExcel = new PHPExcel();

	    // Set document properties
	    $objPHPExcel->getProperties()->setCreator("mohamadikhwan.com")
	            ->setLastModifiedBy("mohamadikhwan.com")
	            ->setTitle("Office 2007 XLSX Test Document")
	            ->setSubject("Office 2007 XLSX Test Document")
	            ->setDescription("Test document for Office 2007 XLSX, generated by PHP classes.")
	            ->setKeywords("office 2007 openxml php")
	            ->setCategory("Test result file");


	    // Add some data

	    $active_sheet = $objPHPExcel->setActiveSheetIndex(0);

	   	$array_alphabet = range('A','Z');


		//exit("B");
		$query_field = "SHOW COLUMNS FROM $this->table";
		$query_field = $this->db->query($query_field);

		$i = 0;
		foreach($query_field->result() as $fields):
		if($fields->Key != 'PRI') {

			$active_sheet->setCellValue($array_alphabet[$i].'1', $fields->Field);
			$i++;

			if($i > 25)
			{
				break;
			}

		}

		endforeach;

		// SET Value

		$line = 2;
		$array_alphabet = range('A','Z');
		for($x=1;$x<=5;$x++)
		{
			$query_field = "SHOW COLUMNS FROM $this->table";
			$query_field = $this->db->query($query_field);

			$i = 0;
			foreach($query_field->result() as $fields):
			if($fields->Key != 'PRI') {


				$active_sheet->setCellValue($array_alphabet[$i].$line, 'Sample Data '.$fields->Field);

				$i++;
				if($i > 25)
				{
					break;
				}

			}
			endforeach;


			$line++;
		}



		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);


	    // Rename worksheet (worksheet, not filename)
	    $objPHPExcel->getActiveSheet()->setTitle('Export '.$this->subject);


	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	    $objPHPExcel->setActiveSheetIndex(0);

	    // Redirect output to a client’s web browser (Excel2007)
	    //clean the output buffer
	    ob_end_clean();

	   // Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->subject.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	function import_data_process()
	{
		$config['upload_path'] = './assets/mycrud/data/';
		$config['allowed_types'] = 'xls|xlsx|XLS|XLSX';
		//$config['max_size']	= '';

		//$this->load->library('upload', $config);
		$this->load->library('upload');
		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('import_file'))
		{
			$error = $this->upload->display_errors();

			if(strip_tags($error) != "You did not select a file to upload.")
			{
				print($error);
				exit;
			}
		}
		else
		{
			$upload_data = $this->upload->data();

			$file_name = $upload_data['file_name'];
		}

		/** PHPExcel_IOFactory */
		// load PHPExcel library
	    $this->load->library('Mycrud_excel');

	    // Create new PHPExcel object
	    $objPHPExcel = new PHPExcel();

		$inputFileName = './assets/mycrud/data/'.$file_name;
		/*
		echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
		*/
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

		/*
		echo '<hr />';
		*/

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

		/*
		echo '<pre>';
		print_r($sheetData);
		echo '</pre>';
		*/

		$i = 1;
		foreach($sheetData as $data_row):

			$data_insert = array();

			if($i == 1)
			{
				foreach($data_row as $key_cell => $value):

					$field[$key_cell] = $value;

				endforeach;
			}
			else
			{
				$count_column = count($data_row);
				$count_null = 0;
				foreach($data_row as $key_cell => $value):

					if(($value == '') or ($value == null))
					{
						$count_null++;
						$this_field = $field[$key_cell];
						$data_insert[$this_field] = $value;
					}
					else{
						$this_field = $field[$key_cell];
						$data_insert[$this_field] = $value;
					}



				endforeach;
				if($count_null != $count_column)
				{
					$this->db->insert($this->table,$data_insert);
				}
			}

		$i++;
		endforeach;

		return true;
	}
}
