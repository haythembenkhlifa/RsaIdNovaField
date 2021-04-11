<?php

namespace Haythem\RsaIdNovaField;

use Exception;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Haythem\RsaIdNovaField\rules\RsaIdValidator;

class RsaIdNovaField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'rsa-id-nova-field';
    protected $ageField = null;
    protected $genderField = null;
    protected $acceptValidIdOnly = true;


    public function showValidationIcon(bool $showValidationIcon = true){
        return $this->withMeta(["showValidationIcon" => $showValidationIcon]);
    }

    public function showAge(bool $showAge = true){
        return $this->withMeta(["showAge" => $showAge]);
    }
    public function showGender(bool $showGender = true){
        return $this->withMeta(["showGender" => $showGender]);
    }
    public function showBirthDay(bool $showBirthDay = true){
        return $this->withMeta(["showBirthDay" => $showBirthDay]);
    }
    public function showType(bool $showType = true){
        return $this->withMeta(["showType" => $showType]);
    }
    public function showGenderIcon(bool $showGenderIcon = true){
        return $this->withMeta(["showGenderIcon" => $showGenderIcon]);
    }


    public function maleIcon(String $icon = "male")
    {
        return $this->withMeta(["maleIcon" => $icon]);
    }
    public function femaleIcon(String $icon = "female")
    {
        return $this->withMeta(["femaleIcon" => $icon]);
    }

    /**
     * Store the age on a given field
     * @param string $age_field
     * @return $this
     */
    public function storeAge(String $age_field){
        $this->ageField = $age_field;
      return $this->withMeta([]);
    }

    /**
     *  Store the gender on a given field
     * @param string $gender_field
     * @return $this
     */
    public function storeGender(String $gender_field){
        $this->genderField = $gender_field;
      return $this->withMeta([]);
    }

    /**
     *  qadd a validatrion rule to the field
     * @param bool $addValidationRule
     * @return $this
     */
    public function acceptValidIdOnly(bool $acceptValidIdOnly =true){
        $this->acceptValidIdOnly = $acceptValidIdOnly;
      return $this->withMeta([]);
    }

    public function acceptFullIdOnly(bool $acceptFullIdOnly =false){
      return $this->withMeta(["acceptFullIdOnly"=>$acceptFullIdOnly]);
    }


    public function rules($rules)
    {
        if ($this->acceptValidIdOnly) {
            $rules = array_merge($this->rules,[new RsaIdValidator()]);
        }
        parent::rules($rules);
        return $this;
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
            parent::fillAttributeFromRequest($request, $requestAttribute, $model, $attribute);

            //if(!$request["rsa_id_is_valid"]) throw new Exception("test exception",400);
            if($this->ageField)$model->{$this->ageField} = $request["age"];
            if($this->genderField)$model->{$this->genderField} =$request["gender"];
            return;

    }
}
