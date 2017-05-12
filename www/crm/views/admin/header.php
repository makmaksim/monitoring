<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/jquery-ui.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?=base_url()?>css/jquery-ui.structure.css" />
    <?php if(isset($css)): ?>
        <?php foreach($css as $val) : ?>
            <link rel="stylesheet" href="<?=base_url()?>css/<?=$val?>.css" />
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script src="<?=base_url()?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/bootstrap.min.js" type="text/javascript"></script>
    <title><?=$title?></title>
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
    <a href="#" class="navbar-brand">UMC-CRM</a>
  </div>
  <!-- Collection of nav links, forms, and other content for toggling -->
  <div id="navbarCollapse" class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li class="active"><a href="/admin/home"><?=lang('home_nav')?></a></li>
      <li><a href="/admin/fields"><?=lang('fields_nav')?></a></li>
      <li><a href="/admin/menu"><?=lang('menu_nav')?></a></li>
      <li><a href="/admin/charts"><?=lang('charts_nav')?></a></li>
      <li><a href="/admin/import"><?=lang('import_nav')?></a></li>
      <li><a href="/admin/formapi"><?=lang('formapi_nav')?></a></li>
      <li><a href="/admin/consultant"><?=lang('cons_nav')?></a></li>
      <li><a href="/admin/systemparams"><?=lang('systemparams_nav')?></a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="http://umc-crm.ru/" target="_blank">Сайт проекта</a></li>
            <li><a href="http://umc-crm.ru/dokumentatsiya/api" target="_blank">API</a></li>
            <li><a href="http://umc-crm.ru/dokumentatsiya/ustanovka-i-nastrojka" target="_blank">Настройка</a></li>
            <li><a href="http://umc-crm.ru/kontakty" target="_blank">Сообщить об ошибке</a></li>
        </ul>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="/home"><?=lang('frontend_nav')?></a></li>
      <li><a href="/login/logout"><?=lang('logout_nav')?></a></li>
    </ul>
  </div>
</nav>
<div class="main">
<div class="messages">
    <?php if(isset($mess) && is_array($mess)) : ?>
        <?php foreach($mess as $val) : ?>
            <div class="mess_type_<?=$val['type']?>"><?=$val['message']?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="page_title"><?=$page_title?></div>