<?php

/**
 * Currency form base class.
 *
 * @method Currency getObject() Returns the current form's model object
 *
 * @package    currency-converter
 * @subpackage form
 * @author     Steve Lacey
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCurrencyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'           => new sfWidgetFormInputHidden(),
      'number'         => new sfWidgetFormInputText(),
      'name'           => new sfWidgetFormInputText(),
      'digits'         => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'countries_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Country')),
    ));

    $this->setValidators(array(
      'code'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('code')), 'empty_value' => $this->getObject()->get('code'), 'required' => false)),
      'number'         => new sfValidatorString(array('max_length' => 3)),
      'name'           => new sfValidatorString(array('max_length' => 255)),
      'digits'         => new sfValidatorInteger(),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
      'countries_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Country', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('currency[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Currency';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['countries_list']))
    {
      $this->setDefault('countries_list', $this->object->Countries->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCountriesList($con);

    parent::doSave($con);
  }

  public function saveCountriesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['countries_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Countries->getPrimaryKeys();
    $values = $this->getValue('countries_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Countries', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Countries', array_values($link));
    }
  }

}
