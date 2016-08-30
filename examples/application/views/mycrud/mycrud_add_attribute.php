<?php foreach($mycrud->attribute as $attributes):
  //show field
  $query_attribute_field = "SHOW COLUMNS FROM $attributes[table]";
  $query_attribute_field = $this->db->query($query_attribute_field);
  $query_attribute_field_type = $mycrud->change_field_type;

  ?>
  <div role="tabpanel" class="tab-pane" id="<?=$attributes['table'] ?>">

    <table class="table">
      <thead>
        <tr>
          <?php if(array_key_exists('fields',$attributes)) { ?>
            <?php foreach($attributes['fields'] as $attr_fields): ?>
              <td><?=$attr_fields ?></td>
            <?php endforeach; ?>
          <?php } else { ?>
            <?php foreach($query_attribute_field->result() as $attr_fields): ?>
              <td><?=$attr_fields->Field; ?></td>
            <?php endforeach ?>
          <?php } ?>
          <td>Action</td>
        </tr>
        <tr>
          <?php if(array_key_exists('fields',$attributes)) { ?>
          <?php foreach($attributes['fields'] as $attr_fields): ?>
            <td><input type="text" class="form-control" name="attr_<?=$attributes['table'] ?>_<?=$attr_fields ?>"/></td>
          <?php endforeach; ?>
          <?php } else { ?>
            <?php foreach($query_attribute_field->result() as $attr_fields): ?>
            <td><?php echo $mycrud->define_field($attr_fields->Field,$attr_fields->Type); ?></td>
            <?php endforeach ?>
          <?php } ?>
            <td><a href="#" class="btn btn-success">+</a></td>
        </tr>
      </thead>
    </table>

  </div>
<?php endforeach; ?>
