<?php

/**
 * CurrencyRate form base class.
 *
 * @method CurrencyRate getObject() Returns the current form's model object
 *
 * @package    currency-converter
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCurrencyRateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'from_code'  => new sfWidgetFormInputHidden(),
      'to_code'    => new sfWidgetFormInputHidden(),
      'rate'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'from_code'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('from_code')), 'empty_value' => $this->getObject()->get('from_code'), 'required' => false)),
      'to_code'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('to_code')), 'empty_value' => $this->getObject()->get('to_code'), 'required' => false)),
      'rate'       => new sfValidatorPass(),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('currency_rate[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CurrencyRate';
  }

}
