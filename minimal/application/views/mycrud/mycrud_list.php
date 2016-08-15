<?php
   //show field
   $query_field = "SHOW COLUMNS FROM $mycrud->table";
   $query_field = $this->db->query($query_field);

   //list all field to array
   $array_field = array();
   foreach($query_field->result() as $fields):
   $array_field[] = $fields->Field;
   endforeach;

   $complete_parse_query = '';
   foreach($_GET as $key => $val):

   	if($key != 'per_page')
   	{
   		$complete_parse_query .= '&'.$key."=".$val;
   	}

   endforeach;
   ?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?=base_url()?>assets/mycrud/bootstrap/css/bootstrap.min.css">
<!-- Latest compiled and minified JavaScript -->
<!--<script src="<?=base_url()?>assets/mycrud/js/bootstrap.min.js"></script>-->
<style>
   .table td , .table th {
   font-size: 12px;
   }
   @media print{
   body * {
   visibility: hidden;
   }
   #mycrud-list * {
   visibility: visible;
   }
   #mycrud-list {
   position: absolute;
   left: 0;
   top: 0;
   }
   }
</style>
<div id="page-wrapper" >
   <div id="mycrud-list" class="container-fluid">
      <div class="row">
         <div class="col-lg-12">
            <div class="panel panel-default">
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <h4><?=$mycrud->subject ?></h4>
                     </div>
                     <div class="col-md-6">
                        <div class="pull-right"></div>
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <form method="get">
                     <div class="row">
                        <div class="col-sm-3">
                           <input type="text" name="q" class="form-control input-sm" placeholder="Cari..." value="<?php if(isset($_GET['q'])) echo $_GET['q'] ?>"/>
                        </div>
                        <div class="col-sm-3">
                           <select name="filter_by" class="form-control input-sm">
                              <?php if(count($mycrud->columns) > 0) { ?>
                              <?php
                                 foreach($mycrud->columns as $fields):

                                 ?>
                              <option value="<?=$fields ?>" <?php if($fields == $mycrud->filter_by) { echo "selected='selected' "; } ?>><?=$mycrud->display_alias($fields) ?></option>
                              <?php endforeach; ?>
                              <?php } else {?>
                              <?php
                                 foreach($query_field->result() as $fields):

                                 ?>
                              <?php if($fields->Key != 'PRI') { ?>
                              <option value="<?=$fields->Field ?>" <?php if($fields->Field == $mycrud->filter_by) { echo "selected='selected' "; } ?>><?=$mycrud->display_alias($fields->Field) ?></option>
                              <?php } ?>
                              <?php endforeach; ?>
                              <?php } ?>
                           </select>
                           <br/>
                        </div>
                        <div class="col-sm-2">
                           <button type="submit"  class="btn btn-primary btn-sm btn-block"/><span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Result</button>
                           <br/>
                        </div>
                        <div class="col-sm-2">
                           <?php if($this->disable_add != true){ ?>
                           <a href="?view=add" class="btn btn-success btn-sm btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add <?=$mycrud->subject ?></a>
                           <?php } ?>
                        </div>
                        <div class="col-sm-2">
                           <div class="dropdown">
                              <button class="btn btn-info btn-block btn-sm dropdown-toggle" type="button" data-toggle="dropdown"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Options
                              <span class="caret"></span></button>
                              <ul class="dropdown-menu">
                                 <li><a href="<?=current_url() ?>?<?=$complete_parse_query ?>&act=export_excel"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export Excel</a></li>
                                 <li><a href="#"  id="mycrud_export_items" ><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export Selected</a></li>
                                 <li><a href="<?=current_url() ?>?view=list&act=export_excel_all"  ><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export All Data</a></li>
                                 <li><a href="<?=current_url() ?>?view=import"  ><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Import Data</a></li>
                                 <li><a href="#"  onclick="window.print()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a></li>
                                 <li><a href="#" id="mycrud_delete_items"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete Item (s)</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </form>
                  Total Record : <?=$total ?><br/><br/>
                  <?=$mycrud->notification; ?>
                  <div class="clearfix"></div>
                  <div class="table-responsive">
                     <form method="post" action="?view=list" id="form_delete_multiple">
                        <input type="hidden" id="mycrud_selected_action" name="mycrud_selected_action" value=""/>
                        <input type="submit" name="mycrud_delete_multiple" value="ok" style="display: none"/>
                        <table class="table table-hover table-bordered table-condensed" id="table-list">
                           <thead>
                              <tr class="active">
                                 <th width="2%"><input type="checkbox" id="check_all" /></th>
                                 <?php if(count($mycrud->columns) > 0) { ?>
                                 <?php
                                    $i = 1;
                                                    		foreach($mycrud->columns as $fields):

                                                    		?>
                                 <th>
                                    <?php if(!in_array($fields,$mycrud->disable_columns)) { ?>
                                    <?php if((isset($_GET['order_by'])) and (isset($_GET['sort']))) {
                                       if(($_GET['order_by'] == $fields) and ($_GET['sort'] == 'asc')) { ?>
                                    <a href="?view=list&order_by=<?=$fields?>&sort=desc"><?=$mycrud->display_alias($fields) ?></a>
                                    <?php } else { ?>
                                    <a href="?view=list&order_by=<?=$fields?>&sort=asc"><?=$mycrud->display_alias($fields) ?></a>
                                    <?php } } else { ?>
                                    <a href="?view=list&order_by=<?=$fields?>&sort=asc"><?=$mycrud->display_alias($fields) ?></a>
                                    <?php } ?>
                                    <?php } ?>
                                 </th>
                                 <?php
                                    $i++;
                                    endforeach; ?>
                                 <?php if(count($mycrud->extra_columns)){
                                    foreach($mycrud->extra_columns as $key => $val):
                                    ?>
                                 <th><?=$key?></th>
                                 <?php endforeach; ?>
                                 <?php } ?>
                                 <?php } else { ?>
                                 <?php
                                    $i = 1;
                                                    		foreach($query_field->result() as $fields):

                                                    		?>
                                 <?php
                                    if($fields->Key != 'PRI') {
                                    	if(!in_array($fields->Field,$mycrud->disable_columns))
                                    	{
                                    ?>
                                 <th>
                                    <?php if(!in_array($fields->Field,$mycrud->disable_columns)) { ?>
                                    <?php if((isset($_GET['order_by'])) and (isset($_GET['sort']))) {
                                       if(($_GET['order_by'] == $fields->Field) and ($_GET['sort'] == 'asc')) { ?>
                                    <a href="?view=list&order_by=<?=$fields->Field?>&sort=desc"><?=$mycrud->display_alias($fields->Field) ?></a>
                                    <?php } else { ?>
                                    <a href="?view=list&order_by=<?=$fields->Field?>&sort=asc"><?=$mycrud->display_alias($fields->Field) ?></a>
                                    <?php } } else { ?>
                                    <a href="?view=list&order_by=<?=$fields->Field?>&sort=asc"><?=$mycrud->display_alias($fields->Field) ?></a>
                                    <?php } ?>
                                    <?php } ?>
                                 </th>
                                 <?php } } ?>
                                 <?php
                                    $i++;
                                    endforeach; ?>
                                 <?php if(count($mycrud->set_relation_nn) > 0){
                                    foreach($mycrud->set_relation_nn as $key => $val):
                                                   			?>
                                 <th><?=ucfirst($key) ?></th>
                                 <?php endforeach;
                                    }
                                    ?>
                                 <?php if(count($mycrud->extra_columns)){
                                    foreach($mycrud->extra_columns as $key => $val):
                                    ?>
                                 <th><?=$key?></th>
                                 <?php endforeach; ?>
                                 <?php } ?>
                                 <?php } ?>
                                 <th style="text-align:center" width="150px">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php if($total > 0) { ?>
                              <?php
                                 foreach($row->result_array() as $row): ?>
                              <tr>
                                 <td><input type="checkbox" name="mycrud_check_item_id[]" class="mycrud_check_list" value="<?=$row['id'] ?>"/></td>
                                 <?php if(count($mycrud->columns) > 0) { ?>
                                 <?php foreach($mycrud->columns as $fields):?>
                                 <?php if(!in_array($fields,$mycrud->disable_columns)){?>
                                 <?php if(array_key_exists($fields,$mycrud->callback_columns)){
                                    $val = $mycrud->callback_columns[$fields];
                                    ?>
                                 <?php if(in_array($fields,$array_field)) { ?>
                                 <td><?=$val[0]->$val[1]($row['id'],$row[$fields]) ?></td>
                                 <?php } else { ?>
                                 <td><?=$val[0]->$val[1]($row['id'],null) ?></td>
                                 <?php } ?>
                                 <?php } else if(array_key_exists($fields,$mycrud->set_upload_image)) {
                                    $options = explode(",",$mycrud->set_upload_image[$fields]);
                                    ?>
                                 <td><img src="<?=base_url().$options[0]?>/<?=$row[$fields]?>" width="50" /></td>
                                 <?php } else if(array_key_exists($fields,$mycrud->set_upload_file)) {
                                    $options = explode(",",$mycrud->set_upload_file[$fields]);
                                    ?>
                                 <td><a href="<?=base_url().$options[0]?>/<?=$row[$fields]?>" target="_blank" /><?=$row[$fields]?> </a></td>
                                 <?php } else if(array_key_exists($fields,$mycrud->set_relation)){
                                    $options = explode(",",$mycrud->set_relation[$fields][0]);
                                    ?>
                                 <td><?=$mycrud->set_relation_column($fields,$options[0],$options[1],$row[$fields]) ?></td>
                                 <?php } else if(array_key_exists($fields,$mycrud->set_relation_nn)){
                                    $options = explode(",",$mycrud->set_relation_nn[$fields]);
                                    ?>
                                 <td><?=$mycrud->set_relation_nn_column($fields,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']) ?></td>
                                 <?php } else { ?>
                                 <td>
                                    <?php
                                       $this_value = strip_tags($row[$fields]);
                                       if(strlen($this_value) > 50)
                                       {
                                       	$this_value = substr($this_value,0,50)." ...";
                                       }
                                       echo $this_value ;


                                       ?>
                                 </td>
                                 <?php } ?>
                                 <?php  } ?>
                                 <?php endforeach; ?>
                                 <?php if(count($mycrud->extra_columns)){
                                    foreach($mycrud->extra_columns as $key => $val):
                                    ?>
                                 <td><?=$val[0]->$val[1]($row['id']) ?></td>
                                 <?php endforeach; ?>
                                 <?php } ?>
                                 <?php } else { ?>
                                 <?php
                                    foreach($query_field->result() as $fields):

                                    ?>
                                 <?php if($fields->Key != 'PRI') { ?>
                                 <?php if(!in_array($fields->Field,$mycrud->disable_columns)) {?>
                                 <?php if(array_key_exists($fields->Field,$mycrud->callback_columns)){
                                    $val = $mycrud->callback_columns[$fields->Field];
                                    ?>
                                 <?php if(in_array($fields,$array_field)) { ?>
                                 <td><?=$val[0]->$val[1]($row['id'],$row[$fields->Field]) ?></td>
                                 <?php } else { ?>
                                 <td><?=$val[0]->$val[1]($row['id'],null) ?></td>
                                 <?php } ?>
                                 <?php } else if(array_key_exists($fields->Field,$mycrud->set_upload_image)) {
                                    $options = explode(",",$mycrud->set_upload_image[$fields->Field]);
                                    ?>
                                 <td><img src="<?=base_url().$options[0]?>/<?=$row[$fields->Field]?>" width="50" /></td>
                                 <?php } else if(array_key_exists($fields->Field,$mycrud->set_upload_file)) {
                                    $options = explode(",",$mycrud->set_upload_file[$fields->Field]);
                                    ?>
                                 <td><a href="<?=base_url().$options[0]?>/<?=$row[$fields->Field]?>" target="_blank" /><?=$row[$fields]?> </a></td>
                                 <?php } else if(array_key_exists($fields->Field,$mycrud->set_relation)){
                                    $options = explode(",",$mycrud->set_relation[$fields->Field][0]);
                                    ?>
                                 <td><?=$mycrud->set_relation_column($fields->Field,$options[0],$options[1],$row[$fields->Field]) ?></td>
                                 <?php } else { ?>
                                 <td>
                                    <?php
                                       $this_value = strip_tags($row[$fields->Field]);
                                       if(strlen($this_value) > 50)
                                       {
                                       	$this_value = substr($this_value,0,50)." ...";
                                       }
                                       echo $this_value;

                                       ?>
                                 </td>
                                 <?php } ?>
                                 <?php } ?>
                                 <?php } ?>
                                 <?php endforeach; ?>
                                 <?php if(count($mycrud->set_relation_nn) > 0){
                                    foreach($mycrud->set_relation_nn as $key => $val):
                                                   				$options = explode(",",$val);
                                                   			?>
                                 <td><?=$mycrud->set_relation_nn_column($key,$options[0],$options[1],$options[2],$options[3],$options[4],$row['id']) ?></td>
                                 <?php endforeach; ?>
                                 <?php } ?>
                                 <?php if(count($mycrud->extra_columns)){
                                    foreach($mycrud->extra_columns as $key => $val):
                                    ?>
                                 <td><?=$val[0]->$val[1]($row['id']) ?></td>
                                 <?php endforeach; ?>
                                 <?php } ?>
                                 <?php } ?>
                                 <td style="text-align:center">
                                    <?php if($this->disable_read != true){ ?>
                                    <a href="?view=read&id=<?=$row['id'] ?>" title="View Data" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> </a>
                                    <?php } ?>
                                    <?php if($this->disable_edit != true){ ?>
                                    <a href="?view=edit&id=<?=$row['id'] ?>" title="Edit Data" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </a>
                                    <?php } ?>
                                    <?php if($this->disable_delete != true){ ?>
                                    <a href="?view=list&act=delete&id=<?=$row['id'] ?>" class="btn btn-danger btn-xs mycrud_delete" title="Delete Data" name="mycrud_delete" value="ok"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </a>
                                    <?php } ?>
                                 </td>
                              </tr>
                              <?php
                                 $i++;
                                 endforeach; ?>
                              <?php } else { ?>
                              <?php $span = $i+2; ?>
                              <td colspan="<?=$span?>">
                                 <center><big>No Data</big></center>
                              </td>
                              <?php } ?>
                           </tbody>
                        </table>
                     </form>
                  </div>
               </div>
            </div>
            Total Record : <?=$total ?>
            <div class="pull-right">
               <?=$this->pagination->create_links() ?><br/><br/>
            </div>
            <div class="clearfix"></div>
            <hr/>
         </div>
         <!-- /.col-lg-12 -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</div>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/external/jquery/jquery.js"></script>
<script src="<?=base_url()?>assets/mycrud/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/jquery-ui.js"></script>

<script type="text/javascript">
   var current_url = "<?=current_url() ?>";
   $('#check_all').click(function() {
   	$('.mycrud_check_list').prop('checked', this.checked);
   });

   $('.mycrud_delete').click(function() {
   	var delete_confirm = confirm("Are you sure to Delete this item?");

   	if(delete_confirm == false)
   	{
   		return false;
   	}
   });

   $('#mycrud_delete_items').click(function(){

   	$("#mycrud_selected_action").val("delete");
   	var delete_confirm = confirm("Are you sure to Delete selected item(s)?");

   	if(delete_confirm == false)
   	{
   		return false;
   	}
   	else
   	{
   		$("#form_delete_multiple").submit();
   	}

   });

   $('#mycrud_export_items').click(function(){

   	$("#mycrud_selected_action").val("export");
   	var delete_confirm = confirm("Are you sure to Export selected item(s)?");

   	if(delete_confirm == false)
   	{
   		return false;
   	}
   	else
   	{
   		$("#form_delete_multiple").submit();
   	}

   });
</script>
