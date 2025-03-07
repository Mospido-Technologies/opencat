<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"> </i> <?php echo $success; ?></div>
  <?php } ?>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

  <div id="content" class="<?php echo $class; ?>">
    <?php echo $content_top; ?>    
    <h1>
      <?php echo $heading_title; ?>
      <div class="pull-right">
        <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <a onclick="$('#form-product').submit();" data-toggle="tooltip" class="btn btn-danger"  title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
      </div>
    </h1>

    <fieldset>
      <legend><i class="fa fa-list"></i> <?php echo $heading_title; ?></legend>
      <?php if($isMember) { ?>
      <div class="well">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-name"><?php echo $column_name; ?></label>
              <div class='input-group'>                
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />                       
                <span class="input-group-addon"><span class="fa fa-angle-double-down"></span></span>
              </div>
            </div>            
            <div class="form-group">
              <label class="control-label" for="input-price"><?php echo $column_price; ?></label>
              <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $column_price; ?>" id="input-price" class="form-control" />
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-model"><?php echo $column_model; ?></label>
              <div class='input-group'>                
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $column_model; ?>" id="input-model" class="form-control" />                                    
                <span class="input-group-addon"><span class="fa fa-angle-double-down"></span></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label" for="input-model"><?php echo $column_quantity; ?></label>
              <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $column_quantity; ?>" id="input-model" class="form-control" />
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-status"><?php echo $column_status; ?></label>                
              <select name="filter_status" class="form-control" id="input-status">
                <option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
            <a onclick="filter();" class="btn btn-primary pull-right"><?php echo $button_filter; ?></a> 
          </div>

        </div>
      </div>

      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

                <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                  <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo str_replace(' ', '', $column_name); ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_name; ?>"><?php echo str_replace(' ', '', $column_name); ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'p.model') { ?>
                  <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'p.price') { ?>
                  <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                  <?php } ?></td>
                <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                  <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'p.status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?></td>

                <td class="text-right"><?php echo $column_sold; ?></td>
                <td class="text-right"><?php echo $column_earned; ?></td>
                <td class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td style="text-align: center;"><?php if ($product['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                  <?php } ?></td>
                <td class="text-left">
                  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['thumb']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" class="img-thumbnail" />
                  &nbsp;
                  <?php if($product['status']){ ?>
                    <a href="<?php echo $product['productLink']; ?>"> <?php echo $product['name']; ?></a>
                  <?php }else{ ?>
                    <?php echo $product['name']; ?>
                  <?php } ?>

                </td>

                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left"><?php if ($product['special']) { ?>
                  <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                  <span class="text-danger"><?php echo $product['special']; ?></span>
                  <?php } else { ?>
                  <?php echo $product['price']; ?>
                  <?php } ?></td>
                <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                  <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                  <?php } elseif ($product['quantity'] <= 5) { ?>
                  <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                  <?php } else { ?>
                  <span class="label label-success"><?php echo $product['quantity']; ?></span>
                  <?php } ?></td>

                <td class="text-left"><?php echo $product['status'] ? $text_enabled : $text_disabled ; ?></td>       

                <td class="text-right">
                  <a <?php if($product['sold']){ ?> href="<?php echo $product['soldlink']; ?>" <?php } ?> style="text-decoration:none;" />                                            
                    <?php if ($product['sold'] <= 0) { ?>
                    <span class="label label-danger"><?php echo $product['sold']; ?></span>
                    <?php } elseif ($product['sold'] <= 5) { ?>
                    <span class="label label-warning" data-toggle="tooltip" title="<?php echo $text_soldlist_info; ?>"><?php echo $product['sold']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success" data-toggle="tooltip" title="<?php echo $text_soldlist_info; ?>"><?php echo $product['sold']; ?></span>
                    <?php } ?></td>
                  </a>
                </td>
                <td class="text-right">
                  <span class="text-success"><?php echo $product['totalearn']; ?></span>
                </td>

                <td class="text-right">
                  <?php if($product['action']){ ?>
                    <?php foreach ($product['action'] as $action) { ?>
                      <a href="<?php echo $action['href']; ?>" class="btn btn-info"><span data-toggle="tooltip" title="<?php echo $action['text']; ?>"><i class="fa fa-pencil"></i></span></a>
                    <?php } ?>
                  <?php } ?>
                </td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
        <div class="text-danger">
          Warning: You are not authorised to view this page, Please contact to site administrator!
        </div>
      <?php } ?>
    </fieldset>

    <?php echo $content_bottom; ?>  
  </div> 
  <?php echo $column_right; ?>
  </div>
</div>  
<script type="text/javascript"><!--

$('#form-product').submit(function(){
    if ($(this).attr('action').indexOf('delete',1) != -1) {
        if (!confirm('<?php echo $text_confirm; ?>')) {
            return false;
        }
    }
});

function filter() {
  url = 'index.php?route=account/customerpartner/productlist';
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }
  
  var filter_model = $('input[name=\'filter_model\']').val();
  
  if (filter_model) {
    url += '&filter_model=' + encodeURIComponent(filter_model);
  }
  
  var filter_price = $('input[name=\'filter_price\']').val();
  
  if (filter_price) {
    url += '&filter_price=' + encodeURIComponent(filter_price);
  }
  
  var filter_quantity = $('input[name=\'filter_quantity\']').val();
  
  if (filter_quantity) {
    url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
  }
  
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  } 

  location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('.row input').keydown(function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/product&filter_type=customerpartner_&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'filter_name\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});

$('input[name=\'filter_model\']').autocomplete({
  delay: 500,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=customerpartner/autocomplete/product&filter_type=customerpartner_&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.model,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(item) {
    $('input[name=\'filter_model\']').val(item.label);            
    return false;
  },
  focus: function(item) {
    return false;
  }
});
//--></script> 
<?php echo $footer; ?>
