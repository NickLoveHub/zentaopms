<?php
include '../../common/view/datatable.fix.html.php';
include '../../common/view/datatable.html.php';
js::set('confirmDelete', $lang->programplan->confirmDelete);
?>
<style>
#tableCustomBtn{display: none;}

.table-children {border-left: 2px solid #cbd0db; border-right: 2px solid #cbd0db;}
.table tbody > tr.table-children.table-child-top {border-top: 2px solid #cbd0db;}
.table tbody > tr.table-children.table-child-bottom {border-bottom: 2px solid #cbd0db;}
.table td.has-child > a:not(.plan-toggle) {max-width: 90%; max-width: calc(100% - 30px); display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.table td.has-child > .plan-toggle {color: #838a9d; position: relative; top: 1px;}
.table td.has-child > .plan-toggle:hover {color: #006af1; cursor: pointer;}
.table td.has-child > .plan-toggle > .icon {font-size: 16px; display: inline-block; transition: transform .2s; -ms-transform:rotate(-90deg); -moz-transform:rotate(-90deg); -o-transform:rotate(-90deg); -webkit-transform:rotate(-90deg); transform: rotate(-90deg);}
.table td.has-child > .plan-toggle > .icon:before {text-align: left;}
.table td.has-child > .plan-toggle.collapsed > .icon {-ms-transform:rotate(90deg); -moz-transform:rotate(90deg); -o-transform:rotate(90deg); -webkit-transform:rotate(90deg); transform: rotate(90deg);}
.main-table tbody > tr.table-children > td:first-child::before {width: 3px;}
@-moz-document url-prefix() {.main-table tbody > tr.table-children > td:first-child::before {width: 4px;}}
</style>
<form class='main-table table-programplan' id='programplanForm' method='post'>
  <div class="table-header fixed-right">
    <nav class="btn-toolbar pull-right"></nav>
  </div>
  <?php
  $vars    = "programID=$programID&productID=$productID&type=lists&orderBy=%s";
  $setting = $this->datatable->getSetting('programplan');
  $widths  = $this->datatable->setFixedFieldWidth($setting);
  $widths['leftWidth']  = 300;
  $columns = 0;
  ?>
  <table class='table has-sort-head datatable' id='programplanList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>' data-checkbox-name='programplanList[]'>
    <thead>
      <tr>
      <?php
      foreach($setting as $key => $value)
      {
          if($value->show)
          {
              $this->datatable->printHead($value, $orderBy, $vars, false);
              $columns ++;
          }
      }
      ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($plans as $plan):?>
      <tr data-id='<?php echo $plan->id?>'>
        <?php foreach($setting as $key => $value) $this->programplan->printCell($value, $plan, $users);?>
      </tr>
      <?php if(!empty($plan->children)):?>
      <?php $i = 0;?>
      <?php foreach($plan->children as $key => $child):?>
      <?php $class  = $i == 0 ? ' table-child-top' : '';?>
      <?php $class .= ($i + 1 == count($plan->children)) ? ' table-child-bottom' : '';?>
      <tr class='table-children<?php echo $class;?> parent-<?php echo $plan->id;?>' data-id='<?php echo $child->id?>'>
        <?php foreach($setting as $key => $value) $this->programplan->printCell($value, $child, $users);?>
      </tr>
      <?php $i ++;?>
      <?php endforeach;?>
      <?php endif;?>
      <?php endforeach;?>
    </tbody>
  </table>
</form>
<script>
$(function(){$('#programplanForm').table();})
$(document).on('click', '.plan-toggle', function(e)
{
    var $toggle = $(this);
    var id = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
});
</script>