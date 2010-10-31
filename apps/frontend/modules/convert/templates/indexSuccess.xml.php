  <at><?php echo $transaction->getDateTimeObject('updated_at')->format('d M Y H:i') ?></at>
  <rate><?php echo $transaction->getRate() ?></rate>
  <from>
    <code><?php echo $from->getCode() ?></code>
    <curr><?php echo $from->getName() ?></curr>
    <loc><?php echo $from->getLocations() ?></loc>
    <amnt><?php echo $amount ?></amnt>
  </from>
  <to>
    <code><?php echo $to->getCode() ?></code>
    <curr><?php echo $to->getName() ?></curr>
    <loc><?php echo $to->getLocations() ?></loc>
    <amnt><?php echo $result ?></amnt>
  </to>
