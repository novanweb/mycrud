<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Add Action(s)</h3>
  </div>

</div>

<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">

          <p>For add action use 'add_action'  </p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['add_action'] = array(
                                      'Subcategory' => array('examples/subcategory_by_parent','btn btn-xs')
                                    );

          </pre>
          </div>

          Example
        <div class="well">

        <pre class="brush: js; first-line: 10">


          // views/example.php
          // Just Put this code into Views file

          $this->load->library('mycrud');

          $config['table'] = 'category';
          $config['subject'] = 'Category';
          $config['add_action'] = array(
                                    'Subcategory' => array('examples/subcategory_by_parent','btn btn-xs')
                                  );

          $mycrud = new Mycrud();
          $mycrud->initialize($config);
          $mycrud->render();

        </pre>
        </div>

        <?php
        // views/example.php
        // Just Put this code into Views file

        $this->load->library('mycrud');

        $config['table'] = 'category';
        $config['subject'] = 'Category';
        $config['add_action'] = array(
                                  'Subcategory' => array('examples/subcategory_by_parent','btn btn-xs btn-info')
                                );

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
        ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
