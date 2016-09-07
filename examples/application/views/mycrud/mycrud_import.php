<?php
   //show field
   $query_field = "SHOW COLUMNS FROM $mycrud->table";
   $query_field = $this->db->query($query_field);
   $array_field_type = $mycrud->change_field_type;

   ?>

   <?php if($mycrud->no_load_asset_css == false) {?>
   <link rel="stylesheet" href="<?=base_url()?>assets/mycrud/bootstrap/css/bootstrap.min.css"/>
   <link rel="stylesheet" href="<?=base_url()?>assets/mycrud/jquery-ui/jquery-ui.css"/>
   <?php } ?>

<?php if(count($mycrud->text_editor) > 0) { ?>
<script type="text/javascript" src="<?=base_url() ?>assets/mycrud/ckeditor/ckeditor.js"></script>
<?php } ?>
<style>
   .table td , .table th {
   font-size: 12px;
   }
</style>
<!-- Page Content -->

            <form action="?view=import&act=upload" method="post" enctype="multipart/form-data">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4>Import - <?=$mycrud->subject ?></h4>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                     <?=$this->session->flashdata('message') ?>
                     <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                     <br/>
                     <?=$mycrud->notification; ?>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="well">
                              <strong>Select Excel (.xls) File to Import <br/><br/></strong>
                              <input type="file" name="import_file"/>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <strong>Importing Guidance<br/><br/></strong>
                           <ol>
                              <li>File must .xls Extension</li>
                              <li>First Row must define some Fields</li>
                              <li>Field name must be same with on Database field</li>
                              <li>White Space character will be converted to _ (underscore) and field name will be converted to Lowercase automatically</li>
                           </ol>
                           <br/>
                           For Example <strong><?=$mycrud->subject ?></strong> Import XLS format <a href="?view=import&act=export_example">Download Here </a>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- /.panel-body -->
               <button type="submit" class="btn btn-success" name="mycrud_insert_import" value="ok">Import</button>
               <a href="?view=list" class="btn btn-primary">Back to List</a>

               <div class="clearfix"></div>
               <hr/>

         </div>
         <input type="hidden" name="mycrud_import" value="ok"/>
         </form>
         <!-- /.panel -->
  

<script src="<?=base_url()?>assets/mycrud/jquery-ui/external/jquery/jquery.js"></script>
<?php if($mycrud->no_load_asset_js == false) { ?>
<script src="<?=base_url()?>assets/mycrud/bootstrap/js/bootstrap.min.js"></script>
<?php } ?>
<script src="<?=base_url()?>assets/mycrud/jquery-ui/jquery-ui.js"></script>
