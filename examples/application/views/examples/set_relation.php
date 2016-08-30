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


          // views/example.php
          // Just Put this code into Views file

          $this->load->library('mycrud');

          $config['table'] = 'product';
          $config['subject'] = 'Product';
          $config['set_relation'] = array('category_id' => array('category','name'));

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
        $config['set_relation'] = array('category_id' => array('category','name'),'subcategory_id' => array('subcategory','name'));

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
         ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
