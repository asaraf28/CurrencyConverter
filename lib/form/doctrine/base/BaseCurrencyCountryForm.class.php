<?php

/**
 * CurrencyCountry form base class.
 *
 * @method CurrencyCountry getObject() Returns the current form's model object
 *
 * @package    currency-converter
 * @subpackage form
 * @author     Steve Lacey
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCurrencyCountryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'currency_code' => new sfWidgetFormInputHidden(),
      'country_id'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'currency_code' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('currency_code')), 'empty_value' => $this->getObject()->get('currency_code'), 'required' => false)),
      'country_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('country_id')), 'empty_value' => $this->getObject()->get('country_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('currency_country[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CurrencyCountry';
  }

}
