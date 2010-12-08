<?php

/**
 * Currency filter form base class.
 *
 * @package    currency-converter
 * @subpackage filter
 * @author     Steve Lacey
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCurrencyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'number'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'digits'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'countries_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Country')),
    ));

    $this->setValidators(array(
      'number'         => new sfValidatorPass(array('required' => false)),
      'name'           => new sfValidatorPass(array('required' => false)),
      'digits'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'countries_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Country', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('currency_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addCountriesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.CurrencyCountry CurrencyCountry')
      ->andWhereIn('CurrencyCountry.country_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Currency';
  }

  public function getFields()
  {
    return array(
      'code'           => 'Text',
      'number'         => 'Text',
      'name'           => 'Text',
      'digits'         => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
      'countries_list' => 'ManyKey',
    );
  }
}
