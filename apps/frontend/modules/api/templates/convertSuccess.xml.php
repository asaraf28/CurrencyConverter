  <at><?php echo $at->format('d M Y H:i') ?></at>
  <rate><?php echo $rate ?></rate>
  <from>
    <code><?php echo $from->getCode() ?></code>
    <curr><?php echo $from->getName() ?></curr>
    <loc><?php echo implode(', ', $from->getRawValue()->getCountryNames()) ?></loc>
    <amnt><?php echo $amount ?></amnt>
  </from>
  <to>
    <code><?php echo $to->getCode() ?></code>
    <curr><?php echo $to->getName() ?></curr>
    <loc><?php echo implode(', ', $to->getRawValue()->getCountryNames()) ?></loc>
    <amnt><?php echo $result ?></amnt>
  </to>