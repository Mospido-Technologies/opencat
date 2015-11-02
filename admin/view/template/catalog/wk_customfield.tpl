<style>
.wkFieldDes {
    border-bottom: 1px dotted #CCCCCC;
    color: #777777;
    display: block;
    height: auto;
    margin-bottom: 20px;
    margin-top: 10px;
    padding: 10px;
    position: relative;
    width: 98%;
}
.wkFieldDes > span {
    color:#444;
    font-weight:bold;
    font-size:14px;
}
input [type="checkbox"], input [type="radio"]{
    margin-right: 5px;
}
</style>
<div class="row">
   <div class="col-sm-3">
                <ul class="nav nav-pills nav-stacked" id="vtab-option">
                  <?php  $tabCount = 0; $wkcustom_option_row = 0;
                    if(!empty($wkPreCustomFields)) { 
                      foreach($wkPreCustomFields as $tabs) { ?>
                        <li>
                          <a href="#wktab-option-<?php echo $tabCount; ?>" data-toggle="tab" id="wkoption-<?php echo $tabCount; ?>" >
                            <i class="fa fa-minus-circle" onclick="$('#wkoption-<?php echo $tabCount; ?>').remove(); $('#wktab-option-<?php echo $tabCount; ?>').remove(); $('#vtab-option li a:first').trigger('click'); return false;" />
                            </i>
                            <?php echo $tabs["fieldName"]; ?>
                          </a>
                        </li>
                      <?php $tabCount++; 
                      } 
                    }
                  ?>  
                  <li id="optionSelector">
                    <select name="wkcustomfield" class="form-control">
                      <?php if(!empty($wkcustomFields)){ ?>
                        <option value=""></option>
                          <?php  foreach($wkcustomFields as $field) { ?>
                            <option value="<?php echo $field['id']; ?>" data-type = "<?php echo $field['fieldType']; ?>" data-name="<?php echo $field['fieldName']; ?>" data-des="<?php echo $field['fieldDescription']; ?>" title="<?php echo $field['fieldDescription']; ?>" data-isRequired = "<?php echo $field['isRequired']; ?>">
                              <?php echo $field['fieldName']; ?>
                            </option>
                          <?php 
                          } 
                        }
                      ?>
                    </select>
                  </li>
                </ul> <!-- left-panel --> 
              </div>  <!-- col-sm-3 --> 
              <div class="col-sm-9">
                <div class="tab-content" id="wk_customfieldcontent">
                  <?php  $tabCount = 0; $wkcustom_option_row = 0;
                    if(!empty($wkPreCustomFields)) { 
                      foreach($wkPreCustomFields as $tabs) { ?>
                        <div id="wktab-option-<?php echo $tabCount; ?>" class="tab-pane">
                          <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <?php  echo $tabs['fieldDes']; ?>
                          </div>
                          <?php if(isset($error) && $error == $tabs['fieldId']) { ?>
                              <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle"></i>
                                <?php echo $error_warning_mandetory; ?>
                              </div>
                          <?php } ?>
                          <input type="hidden" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_name]" value="<?php echo $tabs['fieldName']; ?>">
                          <input type="hidden" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_type]" value="<?php echo $tabs['fieldType']; ?>">
                          <input type="hidden" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_id]" value="<?php echo $tabs['fieldId']; ?>">
                          <input type="hidden" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_des]" value="<?php echo $tabs['fieldDes']; ?>">
                              <?php if($tabs['fieldType'] == 'select') { ?>
                                <div class="form-group <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                  <label class="col-sm-3 control-label">Select option:</label>
                                  <div class="col-sm-9">
                                    <select class="form-control" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" >
                                      <?php  foreach($tabs['preFieldOptions'] as $options) { ?>
                                        <option value="<?php echo $options['optionId']; ?>" <?php if(!empty($tabs['fieldOptions'])) { foreach($tabs['fieldOptions'] as $option) { if($option['optionId'] == $options['optionId']) echo "selected"; } } ?> >
                                          <?php echo $options['optionValue']; ?>
                                        </option>
                                      <?php
                                      }  ?>
                                    </select>
                                  </div>
                                 </div>
                                <?php 
                                
                                }else if($tabs['fieldType'] == 'checkbox' || $tabs['fieldType'] == 'radio' ) { ?>
                                  <div class="form-group  <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                  <label class="control-label">Select option:</label>
                                  <div class="">
                                <?php  foreach($tabs['preFieldOptions'] as $options) { ?>
                                  
                                    <div class="radio checkbox">
                                      <label for="<?php echo $wkcustom_option_row.$options['optionId']; ?>">
                                        <input type='<?php echo $tabs['fieldType']; ?>' id="<?php echo $wkcustom_option_row.$options['optionId']; ?>" value="<?php echo $options['optionId']; ?>" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" <?php if(!empty($tabs['fieldOptions'])) { foreach($tabs['fieldOptions'] as $option) { if($option['optionId'] == $options['optionId']) echo "checked"; } } ?> />
                                        <?php echo $options['optionValue']; ?>
                                      </label>
                                    </div>
                                 
                                <?php }  ?>
                                 </div>
                                </div>
                                <?php }else if($tabs['fieldType'] == 'text') { ?>
                                  <div class="form-group <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                    <label class="col-sm-3 control-label">Enter Text:</label>
                                    <div class="col-sm-9">
                                      <input class="form-control" type="text" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" value="<?php if(isset($tabs['fieldOptions']['option_id'])) echo $tabs['fieldOptions']['option_id']; ?>" />
                                    </div>
                                  </div>
                                  <?php
                                  }else if($tabs['fieldType'] == 'textarea') { ?>
                                    <div class="form-group <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                      <label class="col-sm-3 control-label">Enter Text:</label>
                                      <div class="col-sm-9">
                                        <textarea class="form-control" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" rows="7" >
                                        <?php if(isset($tabs['fieldOptions']['option_id'])) echo $tabs['fieldOptions']['option_id']; ?>
                                      </textarea>
                                      </div>
                                    </div>
                                  <?php
                                  }else if($tabs['fieldType'] == 'date') { ?>
                                    <div class="form-group <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                      <label class="col-sm-3 control-label">Select Date:</label>
                                      <div class="col-sm-6">
                                        <div class="input-group date">
                                          <input type="text" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" value="<?php if(isset($tabs['fieldOptions']['option_id'])) echo $tabs['fieldOptions']['option_id']; ?>" class="form-control">
                                          <span class="input-group-btn">
                                            <button type="button" class="btn btn-default">
                                              <i class="fa fa-calendar"></i>
                                            </button>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                  <?php
                                  }else if($tabs['fieldType'] == 'time') { ?>
                                    <div class="form-group <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                      <label class="col-sm-3 control-label">Enter Text:</label>
                                      <div class="col-sm-6">
                                        <div class="input-group time">
                                           <input class="form-control" type="text" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" value="<?php if(isset($tabs['fieldOptions']['option_id'])) echo $tabs['fieldOptions']['option_id']; ?>" >
                                          <span class="input-group-btn">
                                            <button type="button" class="btn btn-default">
                                              <i class="fa fa-clock-o"></i>
                                            </button>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                    <?php
                                    }else if($tabs['fieldType'] == 'datetime') { ?>
                                      <div class="form-group  <?php if($tabs['isRequired'] == 'yes') echo 'required'; ?>">
                                        <label class="col-sm-3 control-label">Select date-time:</label>
                                        <div class="col-sm-6">
                                          <div class="input-group datetime">
                                            <input type="text" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" value="<?php if(isset($tabs['fieldOptions']['option_id'])) echo $tabs['fieldOptions']['option_id']; ?>" class="form-control">
                                            <span class="input-group-btn">
                                              <button type="button" class="btn btn-default">
                                                <i class="fa fa-clock-o"></i>
                                              </button>
                                            </span>
                                          </div>
                                        </div>
                                      </div>
                                    <?php
                                    } ?>
                              </div>
                            <?php $tabCount++; $wkcustom_option_row++; 
                          } 
                        } ?>
                      </div>
              </div> 
            </div>
<script type="text/javascript">
$('#vtab-option a:first').tab('show');
    tabCount = '<?php echo $tabCount; ?>';
    wkcustom_option_row = '<?php echo $wkcustom_option_row; ?>';
    $('select[name="wkcustomfield"]').on('change',function(){
      value = $(this).val();
      if(value == ''){
        return;
      }
    fieldType = $('option:selected', this).attr('data-type');
    fieldName = $('option:selected', this).attr('data-name');
    fieldDes = $('option:selected', this).attr('data-des');
    fieldIsRequired = $('option:selected', this).attr('data-isRequired');
    tab = '<li><a href="#wktab-option-'+tabCount+'" data-toggle="tab" id="wkoption-'+tabCount+'"><i class="fa fa-minus-circle" onclick="$(\'#wkoption-' + tabCount + '\').remove(); $(\'#wktab-option-' + tabCount + '\').remove(); $(\'#vtab-option a:first\').trigger(\'click\'); return false;" /></i>&nbsp;'+fieldName+'</a></li>';
    $('#optionSelector').before(tab);
    html  = '';
    html += '<input type="hidden" name="product_custom_field['+wkcustom_option_row+'][custom_field_name]" value="'+fieldName+'">';
    html += '<input type="hidden" name="product_custom_field['+wkcustom_option_row+'][custom_field_type]" value="'+fieldType+'">';
    html += '<input type="hidden" name="product_custom_field['+wkcustom_option_row+'][custom_field_id]" value="'+value+'">';
    html += '<input type="hidden" name="product_custom_field['+wkcustom_option_row+'][custom_field_des]" value="'+fieldDes+'">';
    html += '<input type="hidden" name="product_custom_field['+wkcustom_option_row+'][custom_field_is_required]" value="'+fieldIsRequired+'">';
    if(fieldIsRequired == 'yes'){
      required = 'required';
    }else{
      required = '';
    }
    if(fieldType == "textarea"){

      html += '<div class="form-group '+required+' "><label class="col-sm-3 control-label">Enter Text:</label>';
      html += '<div class="col-sm-9"><textarea class="form-control" id="" value="" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]" row="7"></textarea></div></div>';
      addtoBody(html);

    }else if(fieldType == "text"){
      
      html += '<div class="form-group '+required+'"><label class="col-sm-3 control-label">Enter Text:</label>';
      html += '<div class="col-sm-9"><input type="text" class="form-control" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]" /></div></div>';
      addtoBody(html);

    }else if(fieldType == "time"){
      
      html += '<div class="form-group '+required+'"><label class="col-sm-3 control-label">Enter Text:</label>';
      html += '<div class="col-sm-6"><div class="input-group time"><input type="text" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]" class="form-control" /><span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button</span></div</div></div>';

      addtoBody(html);   

    }else if(fieldType == "datetime"){

      html += '<div class="form-group '+required+'"><label class="col-sm-3 control-label">Enter Text:</label>';
      html += '<div class="col-sm-6"><div class="input-group datetime"><input type="text" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]" class="form-control" /><span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button</span></div</div></div>';
      
      addtoBody(html);  

    }else if(fieldType == "date"){

      html += '<div class="form-group '+required+'">';
      html += '<label class="col-sm-3 control-label">Select Date:</label>';
      html += '<div class="col-sm-6"><div class="input-group date">';
      html += '<input type="text" name="product_custom_field[<?php echo $wkcustom_option_row; ?>][custom_field_value][]" class="form-control">';
      html += '<span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div></div>';

      addtoBody(html); 

    }else{
      $.ajax({        
        url:'index.php?route=wkcustomfield/wkcustomfield/getOptions&token=<?php echo $token; ?>',
        data:'&value='+value,
        dataType:'json',
        type:'post',
        success:function(data){
          innerHtml = '';
          if(fieldType == "select"){
            html += '<div class="form-group '+required+'"><label class="col-sm-3 control-label">Options</label><div class="col-sm-9" >';
          }else{
            html += '<div class="form-group '+required+'"><label class="control-label">Options</label><div>';
          }
          $.each(data, function(key,val){
            if(fieldType == "select"){
              innerHtml += '<option value="' + val.optionId +'">' + val.optionValue + '</option>'
            }else if(fieldType == "checkbox"){
            
              html += '<div class="radio checkbox"><label for="'+ wkcustom_option_row + val.optionId +'"><input type='+fieldType+' id="'+ wkcustom_option_row + val.optionId +'" value="'+val.optionId+'" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]">'+val.optionValue+'</lable></div>';
             
            }else{
              
              html += '<div class="radio checkbox"><label for="'+ wkcustom_option_row + val.optionId +'"><input type='+fieldType+' id="'+ wkcustom_option_row + val.optionId +'" value="'+val.optionId+'" value="'+val.optionId+'" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]">'+val.optionValue+'</lable></div>';

            }
          });
          if(fieldType == "select"){

            // html += '<div class="form-group '+required+'"><label class="col-sm-3 control-label" >Select Option:</label>';
            html += '<select class="form-control" name="product_custom_field['+wkcustom_option_row+'][custom_field_value][]">'+innerHtml+'</select>';
          }
          addtoBody(html);
        }
      })
    }
  });

  function addtoBody(html){
    console.log(html);
    html = '<div id="wktab-option-'+tabCount+'" class="tab-pane"><div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + fieldDes + '</div>' + html + '</div></div></div>';
    $('#wk_customfieldcontent').append(html);
    $('#wkoption-' + tabCount).trigger('click');
    tabCount++;
    wkcustom_option_row++;

    $('.date').datetimepicker({
      pickTime: false
    });

    $('.time').datetimepicker({
      pickDate: false
    });

    $('.datetime').datetimepicker({
      pickDate: true,
      pickTime: true
    });
  }
  $('.date').datetimepicker({
    pickTime: false
  });

  $('.time').datetimepicker({
    pickDate: false
  });

  $('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
  });
</script>