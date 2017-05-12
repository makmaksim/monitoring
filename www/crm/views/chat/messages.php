<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php if(!empty($messages)) : ?>
    <table style="width: 100%;" cellpadding="0" cellspacing="0" border="0" class="chat_messages_table">
        <?php foreach($messages as $message) : ?>
            <tr>
                    <?php if($message->autor == $autor) : ?>
                        <td><div class="my_mess"><?=$message->message?></div></td>
                    <?php else : ?>
                        <td><div class="to_my_mess"><?=$message->message?></div></td>
                    <?php endif ?>
                    <td class="date_mess"><?=date( 'd.m.Y H:i', strtotime($message->date))?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    
<?php endif; ?>