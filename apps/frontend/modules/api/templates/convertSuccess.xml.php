  <at><?php echo $at ?></at>
  <rate><?php echo $rate ?></rate>
  <from>
    <code><?php echo $from->getCode() ?></code>
    <curr><?php echo $from->getName() ?></curr>
    <loc><?php echo utf8_encode(implode(', ', $from->getRawValue()->getCountryNames())) ?></loc>
    <amnt><?php echo $amount ?></amnt>
  </from>
  <to>
    <code><?php echo $to->getCode() ?></code>
    <curr><?php echo $to->getName() ?></curr>
    <loc><?php echo utf8_encode(implode(', ', $to->getRawValue()->getCountryNames())) ?></loc>
    <amnt><?php echo $result ?></amnt>
  </to>