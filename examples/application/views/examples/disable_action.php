<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Disable Action(s)</h3>
  </div>

</div>

<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">
          <p>For disable Add action use 'disable_add' parameter with 'true' value </p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['disable_add'] = true;

          </pre>
          </div>

          <p>For disable Edit action use 'disable_edit' parameter with 'true' value </p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['disable_edit'] = true;

          </pre>
          </div>

          <p>For disable Delete action use 'disable_delete' parameter with 'true' value </p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['disable_delete'] = true;

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
          $config['disable_edit'] = true;

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
        $config['disable_edit'] = true;

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
        ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
