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

            $config['callback_columns'] = array('created_by' => array($my,'callback_column_function'));

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
          $config['callback_fields'] = array('created_by' => array($my,'callback_fields_function'));

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

          function callback_fields_function($primary_key = null,$value = null)
          {
            echo '<input type="text" value="Novan" name="created_by" readonly="readonly" class="form-control">';
          }

        </pre>
        </div>

        <?php
        // views/example.php
        // Just Put this code into Views file

        $this->load->library('mycrud');
        $config['table'] = 'report';
        $config['subject'] = 'Report';
        $config['callback_fields'] = array('created_by' => array($my,'callback_fields_function'));

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
        ?>
        </div>

      </div>

<?php include "includes/footer.php" ?>
