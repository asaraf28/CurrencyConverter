<?php

/**
 * CurrencyCountry filter form base class.
 *
 * @package    currency-converter
 * @subpackage filter
 * @author     Steve Lacey
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCurrencyCountryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('currency_country_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CurrencyCountry';
  }

  public function getFields()
  {
    return array(
      'currency_code' => 'Text',
      'country_id'    => 'Number',
    );
  }
}
