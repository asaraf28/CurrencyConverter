<?php if($callback) echo $callback,'(' ?>
{
  "error": {
    "code": <?php echo $code ?>,
    "message": "<?php echo $message ?>"
  }
}
<?php if($callback) echo ');' ?>