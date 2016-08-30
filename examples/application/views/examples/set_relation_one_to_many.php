<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Set Relation</h3>
  </div>

</div>

<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">
          <p>For set relation into your CRUD, use 'set_relation' parameter</p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['set_relation'] = array('title','news_date');

          </pre>
          </div>

          Example
        <div class="well">
        <pre class="brush: js; first-line: 10">


          $this->load->library('mycrud');

          $config['table'] = 'product';
          $config['subject'] = 'Product';
          $config['set_relation'] = array(
                                  'category_id' => array('category','name'),
                                  'subcategory_id' => array('subcategory','name'));
          $config['set_relation_by_parent'] = array('subcategory_id' => array('category_id','category_id'));

          $mycrud = new Mycrud();
          $mycrud->initialize($config);
          $mycrud->render();

        </pre>
        </div>

        <?php
        // views/example.php
        // Just Put this code into Views file

        $this->load->library('mycrud');

        $config['table'] = 'product';
        $config['subject'] = 'Product';
        $config['fields'] = array('category_id','subcategory_id');
        $config['set_relation'] = array(
                                'category_id' => array('category','name'),
                                'subcategory_id' => array('subcategory','name'));
        $config['set_parent_dropdown'] = array('category_id' => array('subcategory_id','category_id'));

        $config['attribute'][0] = array(
                                    'foreign_key' => 'prod_id',
                                    'table' => 'images',
                                    'subject' => 'Photo'
                                    'fields' => array('image'),
                                    'set_upload_image' => array('image','media/images','jpg|png|JPG|PNG')
                                  );

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
         ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
