<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="chat_body <?=(isset($_COOKIE['chat_open']) && $_COOKIE['chat_open']) ? 'opened' : ''?>" style="bottom: <?=(isset($_COOKIE['chat_open']) && $_COOKIE['chat_open']) ? '0' : '-375px'?>; width: <?=(isset($_COOKIE['chat_size'])) ? $_COOKIE['chat_size'] . 'px' : '90%'?>;">
    <div class="chat_head">
        <div class="new_m" style="display: none;">
            <table>
                <tr>
                    <td><div class="chat_red_block"></div></td>
                    <td><span class="new_m_text">У Вас есть непрочитанные сообщения</span></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="chat_users"></div>
    <div class="chat_block">
        <div class="chat_messages"></div>
        <div class="chat_text" style="display: table;">
            <table style="width: 100%;border: none; border-spacing: 0;">
                <tr>
                    <td style="width: 95%; vertical-align: top;"><textarea class="chat_send"></textarea></td>
                    <td style="width: 5px; vertical-align: top;"><span class="chat_send_btn"></span></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="resizable-e" style="z-index: 90;"></div>
</div>
