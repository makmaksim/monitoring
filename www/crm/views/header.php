<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/jquery-ui.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/jquery-ui.structure.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/front.css" />
    
    
    <?php if(isset($css)): ?>
        <?php foreach($css as $val) : ?>
            <link rel="stylesheet" href="<?=base_url()?>css/<?=$val?>.css" />
        <?php endforeach; ?>
    <?php endif; ?>
    <title><?=$title?></title><script src="<?=base_url()?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/jquery.cookie.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var $CONSUPDATETIME = <?=$this->config->item('cons_update_time')?>;
    </script>
    <?php if($isset_cons) : ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                getNewConsMess();
            });
        </script>
    <?php endif; ?>
    <link rel="stylesheet" href="<?=base_url()?>css/chat.css" />
    <?php if($control_chat) : ?>
        <script src="<?=base_url()?>js/chat.js" type="text/javascript"></script>
    <?php endif; ?>
</head>
<body id="top">
<div id="body">
<nav role="navigation" class="navbar navbar-inverse">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a href="http://umc-crm.ru/" target="_blank" class="navbar-brand">UMC-CRM</a>
  </div>
  <!-- Collection of nav links, forms, and other content for toggling -->
  <div id="navbarCollapse" class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li class="active"><a href="/home"><?=lang('lk_nav')?></a></li>
      <?php if(!empty($menu)) : ?>
          <?php foreach($menu[0] as $item) : ?>
            <li <?=(isset($menu[$item->menu_id])) ? ' class="dropdown"' : ''?>>
                    <a href="<?=($item->type == 0) ? '#' : '/groups/' . $item->menu_id?>" <?=(isset($menu[$item->menu_id])) ? 'class="dropdown-toggle" data-toggle="dropdown"' : ''?>><?=$item->name?><?=(isset($menu[$item->menu_id])) ? '<b class="caret"></b>' : ''?></a>
                <?php if(isset($menu[$item->menu_id])) : ?>
                <ul class="dropdown-menu">
                    <?php foreach($menu[$item->menu_id] as $child) : ?>
                    <li>
                        <?php if($child->type == 0) : ?>
                            <span><?=$child->name?></span>
                        <?php else : ?>
                            <a href="/groups/<?=$child->menu_id?>"><?=$child->name?></a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
          <?php endforeach; ?>
      <?php endif; ?>
      <?php if($isset_cons) : ?>
        <li>
            <a href="/consultant" id="cons_header_link" <?=($cons_count_new_messages->count) ? 'style="color: red;"' : ''?>><?=lang('cons_nav')?></a>
        </li>
      <?php endif; ?>
      <?php if($control_chart) : ?>
        <li>
            <a href="/charts"><?=lang('charts_nav')?></a>
        </li>
      <?php endif; ?>
    </ul>
    
    <ul class="nav navbar-nav navbar-right">
        <?php if($this->session->userdata('is_admin')) : ?>
            <li><a href="/admin/fields"><?=lang('admin_nav')?></a></li>
        <?php endif; ?>
      <li><a href="/login/logout"><?=lang('logout_nav')?></a></li>
    </ul>
    <form class="navbar-form navbar-right" role="search" method="GET" action="/groups">
        <div class="form-group">
            <input type="text" class="form-control" id="global_search_input" name="global_search" placeholder="<?=lang('search_text')?>">
        </div>
    </form>
  </div>
</nav>
<div class="messages">
    <?php if(isset($mess) && is_array($mess)) : ?>
        <?php foreach($mess as $val) : ?>
            <div class="mess_type_<?=$val['type']?>"><?=$val['message']?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="main">
