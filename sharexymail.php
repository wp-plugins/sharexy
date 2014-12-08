<?php
$msg = @$_GET['at_msg']; $msg = trim($msg); //$msg = html_entity_decode($msg);
$from = @$_GET['at_from']; $from = trim($from);
$to = @$_GET['at_to']; $to = trim($to);
$subj = @$_GET['subj']; $subj = trim($subj);
mail($to, $subj, $msg, 'Content-type: text/html'."\r\n".'From: <'.$from.'>');
echo '<br /><p style="color: #fff; font-family: Lucida Grande,Arial,Lucida Sans Unicode,sans-serif; font-size: 20px; font-weight: bold; text-align: center;">Your message has been sent!</p>';
?>