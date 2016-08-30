<?php include "includes/header.php" ?>
  <div class="col-md-9">
    <h3>Set Upload Image</h3>
  </div>

</div>
<hr/>
      <div class="row">
        <div class="col-lg-3">
          <?php include "includes/sidemenu.php" ?>

        </div>
        <div class="col-lg-9">
          <p>For set upload image fields, use 'set_upload_image' parameter</p>
          <div class="well">
          <pre class="brush: js; first-line: 17">

            $config['set_upload_image'] = array('attachment' => array('media/images','png|PNG|jpg|JPG','2000'));

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
          $config['set_upload_image'] = array('attachment' => array('media/images','png|PNG|jpg|JPG','2000'));

          $mycrud = new Mycrud();
          $mycrud->initialize($config);
          $mycrud->render();

        </pre>
        </div>

        <?php
        // views/example.php
        // Just Put this code into Views file

        $this->load->library('mycrud');

        $config['table'] = 'report';
        $config['subject'] = 'Report';
        $config['set_upload_image'] = array('attachment' => array('media/images','png|PNG|jpg|JPG','2000'));

        $mycrud = new Mycrud();
        $mycrud->initialize($config);
        $mycrud->render();
         ?>


        </div>

      </div>

<?php include "includes/footer.php" ?>
