<?php include "includes/header.php" ?>
  <center>
    <h3>make <strong>Create, Read, Update, Delete</strong> is Easy</h3>
    <h5>No needed the long Controller to build a CRUD, put MyCrud into  Views</h5>
  </center>
  <br/>
      <div class="row">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-8">

        <div class="well">
        <pre class="brush: js; first-line: 10">


          // views/example.php
          // Just Put this code into Views file

          $this->load->library('mycrud');

          $config['table'] = 'news';
          $config['subject'] = 'News';

          $mycrud = new Mycrud();
          $mycrud->initialize($config);
          $mycrud->render();

        </pre>
        </div>

        <center>
        <a href="#" class="btn btn-primary btn-lg">Download Free</a>
        <h4>
        Today, MyCRUD only available for Codeigniter
      </h4>
        </center>
        </div>

      </div>

<?php include "includes/footer.php" ?>
