<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Callback Before Insert</h3>
  </div>

</div>
<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">
          <p>For set Callback Before Insert, use 'callback_before_insert' parameter</p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['callback_before_insert'] = array($my,'callback_after_insert_function');

          </pre>
          </div>

          Example

        <div class="well">
        <pre class="brush: js; first-line: 10">


          // views/example.php
          // Just Put this code into Views file

          $this->load->library('mycrud');

          $config['table'] = 'report';
          $config['subject'] = 'Report';
          $config['callback_before_insert'] = array($my,'callback_after_insert_function');

          $mycrud = new Mycrud();
          $mycrud->initialize($config);
          $mycrud->render();

        </pre>
        </div>

        Your Controller
        <div class="well">
        <pre class="brush: js; first-line: 10">

          function example()
          {
            $data['my'] = $this;
            $this->load->view('examples',$data);
          }

          function callback_before_insert_function()
          {
            $encrypt_name = md5($this->input->post('created_by'));
            $_POST['created_by'] = $encrypt_name;
            return true;
          }

        </pre>
        </div>

        <?php
        // views/example.php
        // Just Put this code into Views file

        $this->load->library('mycrud');

        $config['table'] = 'report';
        $config['subject'] = 'Report';
        $config['callback_columns'] = array('created_by' => array($my,'callback_column_function'));

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
         ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
