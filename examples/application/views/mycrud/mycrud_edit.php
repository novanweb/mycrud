<?php
   //show field
   $query_field = "SHOW COLUMNS FROM $mycrud->table";
   $query_field = $this->db->query($query_field);
   $array_field_type = $mycrud->change_field_type;

   ?>

<?php if($mycrud->no_load_asset_css == false) {?>
<link rel="stylesheet" href="<?=base_url()?>assets/mycrud/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" href="<?=base_url()?>assets/mycrud/jquery-ui/datetimepicker-addon/jquery-ui-timepicker-addon.min.css"/>
<?php } ?>
<link rel="stylesheet" href="<?=base_url()?>assets/mycrud/jquery-ui/jquery-ui.css"/>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/jquery-ui.js"></script>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/datetimepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<?php if(count($mycrud->text_editor) > 0) { ?>
<script type="text/javascript" src="<?=base_url() ?>assets/mycrud/ckeditor/ckeditor.js"></script>
<?php } ?>
<style>
   .table td , .table th {
   font-size: 12px;
   }
</style>
<!-- Page Content -->

      <form action="?view=edit&act=update&id=<?=$mycrud->id ?>" method="post" enctype="multipart/form-data">

               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4>Edit - <?=$mycrud->subject ?></h4>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                     <?=$this->session->flashdata('message') ?>
                     <!-- Nav tabs -->
                     <ul class="nav nav-tabs">
                        <li class="active"><a aria-expanded="true" href="#detail" data-toggle="tab">Detail</a>
                        </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="tab-pane in fade" id="detail">
                        <br/>
                        <?=$mycrud->notification; ?>
                        <table class="table">
                           <tbody>
                              <?php if(count($mycrud->fields) > 0) {?>
                              <?php
                                 // Fetch Fields Information
                                 foreach($query_field->result() as $fetch_fields):
                                 $columns[$fetch_fields->Field] = array('Type' => $fetch_fields->Type);
                                 endforeach;

                                 foreach($mycrud->fields as $fields):
                                 $label_fields = ucfirst(str_replace('_',' ',$fields));
                                 if(!in_array($fields,$mycrud->disable_fields))
                                         {
                                 		// if type hidden then dont show TR
                                 		if(array_key_exists($fields, $array_field_type))
                                 		{
                                 			if($array_field_type[$fields][0] != 'hidden')
                                 			{

                                 ?>
                              <tr>
                                 <td width="25%"><strong><?=$mycrud->display_alias($fields) ?> </strong></td>
                                 <td width="2%"> : </td>
                                 <td>
                                    <?php } ?>
                                    <?php } else { ?>
                              <tr>
                                 <td width="25%"><strong><?=$mycrud->display_alias($fields) ?> </strong></td>
                                 <td width="2%"> : </td>
                                 <td>
                                    <?php } ?>
                                    <?php
                                       if(array_key_exists($fields,$mycrud->callback_fields))
                                       {
                                       $this_callback_field = $mycrud->callback_fields[$fields];

                                       $callback_class = $this_callback_field[0];
                                       $callback_function = $this_callback_field[1];

                                       $callback_class->$callback_function($detail['id'],$detail[$fields]);

                                       }
                                       elseif(array_key_exists($fields,$mycrud->callback_edit_fields))
                                       {
                                       $this_callback_field = $mycrud->callback_edit_fields[$fields];

                                       $callback_class = $this_callback_field[0];
                                       $callback_function = $this_callback_field[1];

                                       $callback_class->$callback_function($detail['id'],$detail[$fields]);

                                       }
                                       elseif(array_key_exists($fields, $mycrud->set_relation_nn))
                                       {
                                       	$options = $mycrud->set_relation_nn[$fields];
                                        if(count($mycrud->set_relation_nn[$fields]) > 5)
                                        {
                                          $where = explode("=",$mycrud->set_relation_nn[$fields][5]);
                                        }
                                        else
                                        {
                                          $where = array();
                                        }
                                        echo $mycrud->set_relation_nn_field($fields,$options[0],$options[1],$options[2],$options[3],$options[4],$where,$detail['id']);
                                       }
                                       elseif(array_key_exists($fields, $mycrud->set_relation))
                                       {
                                         //check parent, child or no
                                         if(array_key_exists($fields,$mycrud->set_parent_dropdown)) //parent
                                         {
                                           $p_options = $mycrud->set_parent_dropdown[$fields];

                                           if(count($mycrud->set_relation[$fields]) > 2)
                                           {
                                             $options = $mycrud->set_relation[$fields];
                                             $where = explode("=",$mycrud->set_relation[$fields][2]);
                                           }
                                           else
                                           {
                                             $options = $mycrud->set_relation[$fields];
                                             $where = array();
                                           }
                                           $child = $mycrud->set_relation[$p_options[0]];
                                           $mycrud->set_child_dropdown[] = $p_options[0];
                                           echo $mycrud->set_relation_field_parent($fields,$options[0],$options[1],$where,$p_options,$child,$detail[$fields]);
                                         }
                                         else
                                         if(in_array($fields,$mycrud->set_child_dropdown)) //child
                                         {
                                           if(count($mycrud->set_relation[$fields]) > 2)
                                           {
                                             $options = $mycrud->set_relation[$fields];
                                             $where = explode("=",$mycrud->set_relation[$fields][2]);
                                           }
                                           else
                                           {
                                             $options = $mycrud->set_relation[$fields];
                                             $where = array();
                                           }
                                           echo $mycrud->set_relation_field_child($fields,$options[0],$options[1],$where,$detail[$fields]);

                                         }
                                         else
                                         { // no
                                           if(count($mycrud->set_relation[$fields]) > 2)
                                           {
                                             $options = $mycrud->set_relation[$fields];
                                             $where = explode("=",$mycrud->set_relation[$fields][2]);
                                           }
                                           else
                                           {
                                             $options = $mycrud->set_relation[$fields];
                                             $where = array();
                                           }
                                           echo $mycrud->set_relation_field($fields,$options[0],$options[1],$where,$detail[$fields]);
                                         }
                                       }
                                       elseif(array_key_exists($fields, $mycrud->set_upload_image))
                                       {
                                       $options = $mycrud->set_upload_image[$fields];
                                       echo $mycrud->set_upload_image_field($fields,$options[0],$options[1],$options[2],$detail[$fields]);
                                       }
                                       elseif(array_key_exists($fields, $mycrud->set_upload_file))
                                       {
                                       $options = $mycrud->set_upload_file[$fields];
                                       echo $mycrud->set_upload_file_field($fields,$options[0],$options[1],$options[2],$detail[$fields]);
                                       }
                                       else
                                       if(array_key_exists($fields, $array_field_type))
                                       {
                                       echo $mycrud->change_field($fields,$array_field_type[$fields][0],$array_field_type[$fields][1],$detail[$fields]);
                                       }
                                       else
                                       if(in_array($fields, $mycrud->text_editor))
                                       {
                                       echo $mycrud->create_text_editor($fields,$detail[$fields]);
                                       }
                                       else
                                       if(in_array($fields, $mycrud->set_password))
                                       {
                                       $options = $mycrud->set_password[$fields];
                                       echo $mycrud->set_password_field($fields,$options[0],$options[1],$detail[$fields]);
                                       }
                                       else
                                       {

                                       echo $mycrud->define_field($fields,$columns[$fields]['Type'],$detail[$fields]);
                                       }
                                       ?>
                                    <?php
                                       // if type hidden then dont show TR
                                       if(array_key_exists($fields, $array_field_type)) {
                                       		if($array_field_type[$fields][0] != 'hidden') { ?>
                                 </td>
                              </tr>
                              <?php } ?>
                              <?php } else { ?>
                              </td>
                              </tr>
                              <?php } ?>
                              <?php }
                                 endforeach; ?>
                              <?php } else { ?>
                              <?php
                                 foreach($query_field->result() as $fields):
                                 $label_fields = ucfirst(str_replace('_',' ',$fields->Field));
                                 if($fields->Key != 'PRI'){
                                 	if(!in_array($fields->Field,$this->disable_fields))
                                 	{

                                 // if type hidden then dont show TR
                                 if(array_key_exists($fields->Field, $array_field_type))
                                 {
                                 if($array_field_type[$fields->Field][0] != 'hidden')
                                 {
                                 ?>
                              <tr>
                                 <td width="25%"><strong><?=$mycrud->display_alias($fields->Field) ?></strong></td>
                                 <td width="2%"> : </td>
                                 <td>
                                    <?php } ?>
                                    <?php } else { ?>
                              <tr>
                                 <td width="25%"><strong><?=$mycrud->display_alias($fields->Field) ?></strong></td>
                                 <td width="2%"> : </td>
                                 <td>
                                    <?php } ?>
                                    <?php
                                       if(array_key_exists($fields->Field,$mycrud->callback_fields))
                                       {
                                       $this_callback_field = $mycrud->callback_fields[$fields->Field];

                                       $callback_class = $this_callback_field[0];
                                       $callback_function = $this_callback_field[1];

                                       $callback_class->$callback_function($detail['id'],$detail[$fields->Field]);

                                       }
                                       // if fields defined
                                       elseif(array_key_exists($fields->Field,$mycrud->callback_edit_fields))
                                       {
                                       $this_callback_field = $mycrud->callback_edit_fields[$fields->Field];

                                       $callback_class = $this_callback_field[0];
                                       $callback_function = $this_callback_field[1];

                                       $callback_class->$callback_function($detail['id'],$detail[$fields->Field]);

                                       }
                                       elseif(array_key_exists($fields->Field, $mycrud->set_relation))
                                       {
                                         //check parent, child or no
                                         if(array_key_exists($fields->Field,$mycrud->set_parent_dropdown)) //parent
                                         {
                                           $p_options = $mycrud->set_parent_dropdown[$fields->Field];

                                           if(count($mycrud->set_relation[$fields->Field]) > 2)
                                           {
                                             $options = $mycrud->set_relation[$fields->Field];
                                             $where = explode("=",$mycrud->set_relation[$fields->Field][2]);
                                           }
                                           else
                                           {
                                             $options = $mycrud->set_relation[$fields->Field];
                                             $where = array();
                                           }
                                           $child = $mycrud->set_relation[$p_options[0]];
                                           $mycrud->set_child_dropdown[] = $p_options[0];
                                           echo $mycrud->set_relation_field_parent($fields->Field,$options[0],$options[1],$where,$p_options,$child,$detail[$fields->Field]);
                                         }
                                         else
                                         if(in_array($fields->Field,$mycrud->set_child_dropdown)) //child
                                         {
                                           if(count($mycrud->set_relation[$fields->Field]) > 2)
                                           {
                                             $options = $mycrud->set_relation[$fields->Field];
                                             $where = explode("=",$mycrud->set_relation[$fields->Field][2]);
                                           }
                                           else
                                           {
                                             $options = $mycrud->set_relation[$fields->Field];
                                             $where = array();
                                           }
                                           echo $mycrud->set_relation_field_child($fields->Field,$options[0],$options[1],$where,$detail[$fields->Field]);
                                         }
                                         else
                                         { // no
                                           if(count($mycrud->set_relation[$fields->Field]) > 2)
                                           {
                                             $options = $mycrud->set_relation[$fields->Field];
                                             $where = explode("=",$mycrud->set_relation[$fields->Field][2]);
                                           }
                                           else
                                           {
                                             $options = $mycrud->set_relation[$fields->Field];
                                             $where = array();
                                           }
                                           echo $mycrud->set_relation_field($fields->Field,$options[0],$options[1],$where,$detail[$fields->Field]);
                                         }
                                       }
                                       elseif(array_key_exists($fields->Field, $mycrud->set_upload_image))
                                       {
                                       $options = $mycrud->set_upload_image[$fields->Field];
                                       echo $mycrud->set_upload_image_field($fields->Field,$options[0],$options[1],$options[2],$detail[$fields->Field]);
                                       }
                                       elseif(array_key_exists($fields->Field, $mycrud->set_upload_file))
                                       {
                                       $options = $mycrud->set_upload_file[$fields->Field];
                                       echo $mycrud->set_upload_file_field($fields->Field,$options[0],$options[1],$options[2],$detail[$fields->Field]);
                                       }
                                       elseif(array_key_exists($fields->Field, $array_field_type)) {
                                       echo $mycrud->change_field($fields->Field,$array_field_type[$fields->Field][0],$array_field_type[$fields->Field][1],$detail[$fields->Field]);
                                       }
                                       else
                                       if(in_array($fields->Field, $mycrud->text_editor))
                                       {
                                       echo $mycrud->create_text_editor($fields->Field,$detail[$fields->Field]);
                                       }
                                       else
                                       if(in_array($fields->Field, $mycrud->set_password))
                                       {
                                       $options = $mycrud->set_password[$fields->Field];
                                       echo $mycrud->set_password_field($fields->Field,$options[0],$options[1],$detail[$fields->Field]);
                                       }
                                       else
                                       {
                                       echo $mycrud->define_field($fields->Field,$fields->Type,$detail[$fields->Field]);
                                       }

                                       ?>
                                    <?php
                                       // if type hidden then dont show TR
                                       if(array_key_exists($fields->Field, $array_field_type)) {
                                       		if($array_field_type[$fields->Field][0] != 'hidden') { ?>
                                 </td>
                              </tr>
                              <?php } ?>
                              <?php } else { ?>
                              </td>
                              </tr>
                              <?php } ?>
                              <?php
                                 } }
                                 endforeach; ?>
                              <?php if(count($mycrud->set_relation_nn) > 0) {
                                 foreach($mycrud->set_relation_nn as $key=>$val) :
                                 $options = $val;
                                 if(count($options) > 5)
                                 {
                                   $where = explode("=",$options[5]);
                                 }
                                 else
                                 {
                                   $where = array();
                                 }
                                 ?>
                              <tr>
                                 <td><strong><?=ucfirst($key)?></strong></td>
                                 <td>: </td>
                                 <td><?=$mycrud->set_relation_nn_field($key,$options[0],$options[1],$options[2],$options[3],$options[4],$where,$detail['id']); ?></td>
                              </tr>
                              <?php
                                 endforeach;
                                 } ?>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <!-- /.panel-body -->
               <button type="submit" class="btn btn-success" name="mycrud_update" value="ok">Edit</button>
               <a href="?view=list" class="btn btn-primary">Back to List</a>
            </div>
            <!-- /.panel -->

            <div class="clearfix"></div>
            <hr/>

   </form>


<script src="<?=base_url()?>assets/mycrud/jquery-ui/external/jquery/jquery.js"></script>
<?php if($mycrud->no_load_asset_js == false) { ?>
<script src="<?=base_url()?>assets/mycrud/bootstrap/js/bootstrap.min.js"></script>
<?php } ?>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/jquery-ui.js"></script>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/datetimepicker-addon/jquery-ui-timepicker-addon.min.js"></script>

<script type="text/javascript">
$( ".datepicker-date" ).datepicker({
  dateFormat: "yy-mm-dd",
});

$( ".datepicker-time" ).timepicker({
  timeFormat: "HH:mm:ss"
});

$( ".datepicker-datetime" ).datetimepicker({
  dateFormat: "yy-mm-dd",
  timeFormat: "HH:mm:ss"
});

</script>
