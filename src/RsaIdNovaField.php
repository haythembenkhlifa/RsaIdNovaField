<?php

namespace Haythem\RsaIdNovaField;

use Exception;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Haythem\RsaIdNovaField\rules\RsaIdValidator;
use PHPUnit\Framework\Constraint\Callback;

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
    protected $birthdayField = null;
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
    public function showCitizenship(bool $showCitizenship = true){
        return $this->withMeta(["showCitizenship" => $showCitizenship]);
    }
    public function showGenderIcon(bool $showGenderIcon = true){
        return $this->withMeta(["showGenderIcon" => $showGenderIcon]);
    }

    public function displaySmall(bool $displaySmall = true){
        return $this->withMeta(["displaySmall" => $displaySmall]);
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
     *  Store the birthday on a given field and a specific date format
     *
     *   d Day of the month as digits; no leading zero for single-digit days.
     *   dd	Day of the month as digits; leading zero for single-digit days.
     *   ddd	Day of the week as a three-letter abbreviation.
     *   DDD	"Ysd", "Tdy" or "Tmw" if date lies within these three days. Else fall back to ddd.
     *   dddd	Day of the week as its full name.
     *   DDDD	"Yesterday", "Today" or "Tomorrow" if date lies within these three days. Else fall back to dddd.
     *   m	Month as digits; no leading zero for single-digit months.
     *   mm	Month as digits; leading zero for single-digit months.
     *   mmm	Month as a three-letter abbreviation.
     *   mmmm	Month as its full name.
     *   yy	Year as last two digits; leading zero for years less than 10.
     *   yyyy	Year represented by four digits.
     *   shortDate	m/d/yy	6/9/07
     *   paddedShortDate	mm/dd/yyyy	06/09/2007
     *   mediumDate	mmm d, yyyy	Jun 9, 2007
     *   longDate	mmmm d, yyyy	June 9, 2007
     *   fullDate	dddd, mmmm d, yyyy	Saturday, June 9, 2007
     *
     *
     * @param string $gender_field
     * @return $this
     */
    public function storeBirthday(String $birthday_field,String $birthday_format = "yyyy/mm/dd"){
        $this->birthdayField = $birthday_field;
        $this->birthdayFormat = $birthday_format;
      return $this->withMeta(['birthdayFormat' => $birthday_format]);
    }

    /**
     *  add a validatrion rule to the field
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
            if($this->birthdayField)$model->{$this->birthdayField} =$request["birthday"];

            return;

    }
}
