<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Hide Fields</h3>
  </div>

</div>
<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">
          <p>For set fileds into your Add/Edit view use 'disable_fields' parameter</p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['disable_columns'] = array('news_date');

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
          $config['disable_fields'] = array('news_date');

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
        $config['disable_fields'] = array('news_date');

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
         ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
