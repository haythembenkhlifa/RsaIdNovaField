<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div>
                <div v-show="this.field.displaySmall" class="flex flex-row items-center">
                    <div class="inline-block mr-2 relative">
                        <font-awesome-icon  class="inline-block mx-2 absolute" style="margin-top:0.59em;"  :icon="['fas','id-card']" color="#d0d6dc" />
                        <input
                            :id="field.name"
                            type="number"
                            class="form-control form-input form-input-bordered inline-block pl-8"
                            :class="errorClasses"
                            :placeholder="field.name"
                            v-model="value"
                            @input="checkId"
                            :style="'background: linear-gradient(90deg, aliceblue '+percentage+'%, white 0%)'"
                        />
                    </div>

                    <font-awesome-icon v-show="this.field.showValidationIcon == null ? true : this.field.showValidationIcon" class="mx-2 "  :icon="['fas','check-circle']" :color="valid ? 'green' : 'red'" />


                    <div class="flex flex-row items-center">
                    <h4 v-show="valid && birth_day && (this.field.showBirthDay == null ? true : this.field.showBirthDay)"  class=" mr-2 ">{{birth_day}}</h4>
                    <h4 class="mr-2" v-show="valid && gender && (this.field.showGenderIcon == null ? true : this.field.showGenderIcon)"><font-awesome-icon  class=""  :icon="['fas',gender == 'F' ? this.field.femaleIcon == null ? 'female': this.field.femaleIcon : this.field.maleIcon == null ? 'male' : this.field.maleIcon]" /></h4>
                    <h4 v-show="valid && citizenship  && (this.field.showCitizenship== null ? true : this.field.showCitizenship)"  class="mr-2">{{citizenship}}</h4>
                    <h4 v-show="valid && age && (this.field.showAge== null ? true : this.field.showAge)"  class=" mr-2">{{age}}</h4>
                    </div>

                </div>
                <div v-show="this.field.displaySmall && this.helpText != null" class="help-text help-text mt-2">
                    {{ this.helpText }}
                </div>

                <div v-show="!this.field.displaySmall">
                    <div class="inline-block mr-2 relative">
                        <font-awesome-icon  class="inline-block mx-2 absolute" style="margin-top:0.59em;"  :icon="['fas','id-card']" color="#d0d6dc" />
                        <input
                            :id="field.name"
                            type="number"
                            class="form-control form-input form-input-bordered inline-block pl-8"
                            :class="errorClasses"
                            :placeholder="field.name"
                            v-model="value"
                            @input="checkId"
                            :style="'background: linear-gradient(90deg, aliceblue '+percentage+'%, white 0%)'"
                        />
                    </div>

                    <font-awesome-icon v-show="this.field.showValidationIcon == null ? true : this.field.showValidationIcon" class="mx-2 "  :icon="['fas','check-circle']" :color="valid ? 'green' : 'red'" />

                    <div v-show="this.helpText != null" class="help-text help-text mt-2">
                        {{ this.helpText }}
                    </div>

                    <h4 v-show="valid && birth_day && (this.field.showBirthDay == null ? true : this.field.showBirthDay)"  class=" mr-2 mb-2 mt-3 ">Birth Day : {{birth_day}}</h4>
                    <h4 class="mr-2 my-2" v-show="valid && gender && (this.field.showGenderIcon == null ? true : this.field.showGenderIcon)">Gender : <font-awesome-icon  class="mr-2"  :icon="['fas',gender == 'F' ? this.field.femaleIcon == null ? 'female': this.field.femaleIcon : this.field.maleIcon == null ? 'male' : this.field.maleIcon]" /></h4>
                    <h4 v-show="valid && citizenship  && (this.field.showCitizenship== null ? true : this.field.showCitizenship)"  class="mr-2 my-2">Citizenship : {{citizenship}}</h4>
                    <h4 v-show="valid && age && (this.field.showAge== null ? true : this.field.showAge)"  class=" mr-2 my-2"> Age : {{age}}</h4>
                </div>



            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors,FormError } from 'laravel-nova'
export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],
    data() {
        return {
            birth_day:null,
            rest_of_id:null,
            age :  null,
            gender :  null,
            citizenship : null,
            valid:false,
            percentage:50,
            helpText:null,
        }
    },
    methods: {
        checkId(){
            this.reset();
            var idNumber = this.value.toString();
            this.percentage = (idNumber.length*100)/13;
            var regex = new RegExp('e', 'g');
            idNumber = idNumber.replace(regex,"");
            if (this.field.acceptFullIdOnly != null ? !this.field.acceptFullIdOnly : true) {
                    if (idNumber.length == 6) {
                        this.valid = true;
                        this.validateBirthDay(idNumber);
                        return;
                    }
                    if (idNumber.length == 10) {

                        this.valid = true;
                        this.validateBirthDay(idNumber);
                        this.gender = Number(idNumber[6]) < 5 ? "F" : "M";
                        return;
                    }
                    if (idNumber.length == 11) {

                        this.valid = true;
                        this.validateBirthDay(idNumber);
                        this.gender = Number(idNumber[6]) < 5 ? "F" : "M";
                        this.citizenship = Number(idNumber[10])  === 0 ? "SA Citizen" : "Non-SA Citizen";
                        return;
                    }
            }
            if(idNumber.length == 13 && this.validateId(idNumber)){

                this.valid = true;
                this.validateBirthDay(idNumber);
                this.gender = Number(idNumber[6]) < 5 ? "F" : "M";
                this.citizenship = Number(idNumber[10])  === 0 ? "SA Citizen" : "Non-SA Citizen";
            }else{
                this.valid = false;
            }
        },
        ageFromDateOfBirthday(dateOfBirth) {
            const today = new Date();
            const birthDate = new Date(dateOfBirth);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
            }

            return age;
        },
        validateId(idNumber){
                var  total = 0;
                var count = 0;
                for (let index = 0; index < idNumber.length; index++) {
                    var multiplier = count % 2 + 1;
                    count = count +1;
                    var temp = multiplier * Number(idNumber[index]);
                    temp = Math.floor(temp / 10) + (temp % 10);
                    total = total + temp;
                }
                total = (total * 9) % 10;
                if (total % 10 != 0) {
                    this.error = "ID number does not appear to be authentic - check digit is not valid";
                    return false;
                }
                return true;
        },
        validateBirthDay(idNumber)
        {
            var year = idNumber.substring(0,2);
            var currentYear = new Date().getFullYear() % 100;
            var prefix      = "19";
            if (year < currentYear) {
                prefix = "20";
            }
            var id_year = prefix + year;
            var id_month = idNumber.substring(2,4);
            var id_day = idNumber.substring(4,6);
            var dateFormat = require("dateformat");
            this.birth_day = dateFormat(new Date(id_year + "/" + id_month +"/" + id_day), this.field.birthdayFormat || "yyyy/mm/dd");
            this.age = this.ageFromDateOfBirthday(id_year + "/" + id_month +"/" + id_day);
        },
        reset(){
            this.percentage = 0;
            this.birth_day=null;
            this.age=null;
            this.gender=null;
            this.citizenship=null;
        },

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.helpText=this.field.helpText,
            this.field.helpText = null;
            this.$children[0].$el.lastElementChild.className = "py-6 px-8";
            this.value = this.field.value || ''
            this.checkId();
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append("is_rsa_id_valid",this.valid || false);
            formData.append(this.field.attribute, this.value || '');
            formData.append('age', this.age || '');
            formData.append('gender', this.gender || '');
            formData.append('birthday', this.birth_day || '');
        },
        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },
}
</script>
<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[Citizenship=number] {
  -moz-appearance: idNumberfield;
}
</style>
