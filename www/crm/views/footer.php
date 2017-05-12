<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
</div>
</div>
<footer>
        <div class="col-xs-4">
                2012 - <?=date('Y')?> <br>
                &copy; <a href="http://umc-crm.ru/" target="_blank">umc-crm.ru</a><br> 
                <?php if($segment == 'admin') : ?>2.1.7<?php endif; ?>
        </div>
        <div class="col-xs-4" style="text-align: center;">
            <?php if($segment == 'admin') : ?>
                <div style="text-align: center; font-size: 11px;">
                    Эта версия UMC-CRM является бесплатной. Если Вы загрузили ее не из <a href="http://umc-crm.ru/" target="_blank">официального сайта</a> - будьте осторожны, мы не гарантируем отсутствие вредоносного кода в системе
                </div>
                <div style="clear: both;">
                    <div style="display: inline-block;vertical-align: middle;">Поддержать проект</div> 
                    <a class="donate" href="http://umc-crm.ru/index.php/podderzhat-proekt" target="_blank"></a>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-xs-4">
            <p class="pull-right" style="margin-right: 40px;">
                <a href="#top" id="back-top">Наверх</a>
            </p>
        </div>
</footer>
<?php if($vk_params['enable_vk_messages']) : ?>
<script src="//vk.com/js/api/xd_connection.js?2" type="text/javascript"></script>
<script type="text/javascript">
function h_update_1() {
VK.callMethod("resizeWindow", 1000, $('#body').height());   
setTimeout(h_update_1, 1000);
}
 
setTimeout(h_update_1, 1000);
</script>
<?php endif; ?>
<script type="text/javascript">
                jQuery(document).ready(function($){
                    $("#global_search_input").autocomplete({
                        source: function(request, response){
                            $.ajax({
                                type: "POST",
                                url: "/home/get_user_autocomplete",
                                dataType: "json",
                                data:{
                                    limit: 10, 
                                    text: request.term 
                                },
                                success: function(data){
                                    response($.map(data, function(item){
                                        return {
                                            label: item.name,                                    
                                            user_id: item.user_id                                    
                                        }
                                    }));
                                }
                            });
                        },
                        select: function( event, ui ) {
                            location.href = '/home/' + ui.item.user_id
                            return false;
                        },
                        minLength: 2 
                    });
                });
            </script>
<?=get_scripts()?>
<!--
<?php if(!empty(ViewInput::$scripts['ckeditor'])) : ?>
<script src="<?=base_url()?>js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            <?php foreach(ViewInput::$scripts['ckeditor'] as $val) : ?>
                CKEDITOR.replace('<?=$val?>');
            <?php endforeach; ?>
        });
    </script>
<?php endif; ?>
-->
<?php if(isset($js)): ?>
        <?php foreach($js as $val) : ?>
            <script src="<?=base_url()?>js/<?=$val?>.js" type="text/javascript"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
