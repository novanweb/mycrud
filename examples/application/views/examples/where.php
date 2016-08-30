<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Where in CRUD</h3>
  </div>

</div>

<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">
          <p>For add WHERE clause to your MyCRUD, use parameter with 'true' value </p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['where'] = array('publish' => 'Yes');

          </pre>
          </div>

          <p>More WHERE clause </p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['where'] = array('publish' => 'Yes','news_date <' => '2016-08-08');

          </pre>
          </div>


          Example
        <div class="well">

        <pre class="brush: js; first-line: 10">


          // views/example.php
          // Just Put this code into Views file

          $this->load->library('mycrud');

          $config['table'] = 'news';
          $config['subject'] = 'News';
          $config['where'] = array('publish' => 'Yes');

          $mycrud = new Mycrud();
          $mycrud->initialize($config);
          $mycrud->render();

        </pre>
        </div>

        <?php
        // views/example.php
        // Just Put this code into Views file

        $this->load->library('mycrud');

        $config['table'] = 'news';
        $config['subject'] = 'News';
        $config['where'] = array('publish' => 'Yes');

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
        ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
