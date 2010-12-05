<?php if($callback) echo $callback,'(' ?>
{
  "at": "<?php echo $at ?>",
  "rate": <?php echo $rate ?>,
  "from": {
    "code": "<?php echo $from->getCode() ?>",
    "name": "<?php echo $from->getName() ?>",
    "loc": ["<?php echo utf8_decode(implode('","', $from->getRawValue()->getCountryNames())) ?>"],
    "amnt": <?php echo $amount ?>

  },
  "to": {
    "code": "<?php echo $to->getCode() ?>",
    "name": "<?php echo $to->getName() ?>",
    "loc": ["<?php echo utf8_decode(implode('","', $to->getRawValue()->getCountryNames())) ?>"],
    "amnt": <?php echo $result ?>

  }
}
<?php if($callback) echo ');' ?>