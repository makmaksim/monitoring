<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="main">
    <div class="page_title"><?=lang('menu_title')?></div>
    <ul class="menu_list">
        <?php foreach($menu[0] as $item) : ?>
            <li data-id="<?=$item->menu_id?>">
                <span class="glyphicon glyphicon-pencil edit_item"></span>
                <span class="glyphicon glyphicon-remove remove_item"></span>
                <?php if($item->type == 0) : ?>
                    <span><?=$item->name?></span>
                <?php else : ?>
                    <a href="/groups/<?=$item->menu_id?>" target="_blank"><?=$item->name?></a> 
                <?php endif; ?> 
                
                <?php if(isset($menu[$item->menu_id]) && !empty($menu[$item->menu_id])) : ?>   
                    <ul class="menu_cildren_list">
                        <?php foreach($menu[$item->menu_id] as $child) : ?>
                            <li data-id="<?=$child->menu_id?>">
                                <span class="glyphicon glyphicon-pencil edit_item"></span>
                                <span class="glyphicon glyphicon-remove remove_item"></span>
                                <?php if($child->type == 0) : ?>
                                    <span><?=$child->name?></span>
                                <?php else : ?>
                                    <a href="/groups/<?=$child->menu_id?>" target="_blank"><?=$child->name?></a>
                                <?php endif; ?>
                                
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?=ViewInput::_get_button(array(
        'label' => lang('add_menu_item_text'),
        'only' => TRUE
    ), 'id="add_menu_item_btn"')?>
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