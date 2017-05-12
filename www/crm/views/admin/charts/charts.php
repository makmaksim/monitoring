<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=ViewInput::_get_button(array('label' => lang('add_chart_btn'), 'only' => TRUE), 'data-toggle="modal" data-target="#add_chart_form"')?>

<?php if(!empty($charts)) : ?>
    <div class="formsapi_list" style="margin-top: 20px;">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach($charts as $key => $chart) : 
                $params = unserialize($chart->params);
            ?>
                <div class="panel panel-default panel_form_<?=$chart->id?>">
                    <div class="panel-heading" role="tab" id="headingOne">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#block<?=$key?>" aria-expanded="true" aria-controls="collapseOne">
                            <?=$chart->name?>
                        </a>
                        <button type="button" class="close remove_chart" aria-label="Close" data-form="<?=$chart->id?>"><span aria-hidden="true">&times;</span></button>
                      </h4>
                    </div>
                    <div id="block<?=$key?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <?php //var_dump($chart->fields) ?>
                      <form method="post" action="/admin/charts/edit_chart" style="padding: 10px;">
                        <?=ViewInput::_text(array(
                            'name' => 'name',
                            'label' => lang('chart_name'),
                            'required' => 1,
                            'value' => $chart->name
                        ))?>
                        <?=ViewInput::_text(array(
                            'name' => 'description',
                            'label' => lang('chart_description'),
                            'value' => $chart->description
                        ))?>
                        <?=ViewInput::_checkbox(array(
                            'label' => lang('chart_status'),
                            'name' => 'status',
                            'value' => $chart->status
                        ))?>
                        <?=ViewInput::_list(array(
                            'label' => lang('chart_group'),
                            'name' => 'group_id',
                            'key' => 'group_id',
                            'val' => 'name',
                            'list' => $groups,
                            'required' => 1,
                            'value' => $chart->group_id
                        ))?>
                        <?=ViewInput::_list(array(
                            'name' => 'params[type]',
                            'label' => lang('chart_type'),
                            'value' => (isset($params['type'])) ? $params['type'] : '0',
                            'list' => $types
                        ), 'data-key="' . $key . '"')?>
                        <?=ViewInput::_list(array(
                            'name' => 'params[order_field]',
                            'label' => lang('chart_order_field'),
                            'list' => $chart->fields,
                            'key' => 'field_id',
                            'val' => 'name',
                            'value' => (isset($params['order_field'])) ? $params['order_field'] : '0'
                        ))?>
                        <div class="chart_list_fields<?=$key?>" <?=(isset($params['type']) && $params['type'] != 0) ? '' : 'style="display: none"'?>>
                            <?=ViewInput::_list_multiple(array(
                                'name' => 'params[list_fields]',
                                'label' => lang('chart_list_fields'),
                                'list' => $chart->fields,
                                'key' => 'field_id',
                                'val' => 'name',
                                'value' => (isset($params['list_fields'])) ? $params['list_fields'] : array()
                            ), 'size="' . count($chart->fields) . '"')?>
                        </div>
                        <?=ViewInput::_get_send_button(array('label' => lang('save_text')))?>
                        <input type="hidden" name="id" value="<?=$chart->id?>">
                      </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>



<div class="modal fade" id="add_chart_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('form_chart_title')?></h4>
      </div>
      <div class="modal-body">
        <form role="form" action="/admin/charts/new_chart" method="post" onsubmit="return send(this);return false;">
            <div class="errors"></div>
          <?=ViewInput::_text(array(
            'name' => 'name',
            'label' => lang('chart_name'),
            'required' => 1
          ))?>
          <?=ViewInput::_text(array(
            'name' => 'description',
            'label' => lang('chart_description')
          ))?>
          <?=ViewInput::_checkbox(array(
            'label' => lang('chart_status'),
            'name' => 'status'
          ))?>
          <?=ViewInput::_list(array(
            'label' => lang('chart_group'),
            'name' => 'group_id',
            'key' => 'group_id',
            'val' => 'name',
            'list' => $groups,
            'required' => 1
          ))?>
          <?=ViewInput::_get_send_button(array('label' => lang('save_text')))?>
        </form>
    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <p><?=lang('delete_text')?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default no_btn" data-dismiss="modal"><?=lang('no')?></button>
        <button type="button" class="btn btn-primary yes_btn" data-dismiss="modal"><?=lang('yes')?></button>
      </div>
    </div>
  </div>
</div>