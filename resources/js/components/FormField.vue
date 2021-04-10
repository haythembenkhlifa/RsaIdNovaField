<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div>
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
                    />
                </div>

                <font-awesome-icon v-show="this.field.showValidationIcon == null ? true : this.field.showValidationIcon" class="mx-2 "  :icon="['fas','check-circle']" :color="valid ? 'green' : 'red'" />
                <h4 v-show="valid && (this.field.showBirthDay == null ? true : this.field.showBirthDay)"  class=" mr-2 mb-2 mt-3 ">Birth Day : {{birth_day}}</h4>
                <h4 class="mr-2 my-2" v-show="valid  && (this.field.showGenderIcon == null ? true : this.field.showGenderIcon)">Gender : <font-awesome-icon  class="mr-2"  :icon="['fas',gender == 'F' ? this.field.femaleIcon == null ? 'female': this.field.femaleIcon : this.field.maleIcon == null ? 'male' : this.field.maleIcon]" /></h4>
                <h4 v-show="valid  && (this.field.showType== null ? true : this.field.showType)"  class="mr-2 my-2">Citizenship : {{type}}</h4>
                <h4 v-show="valid  && (this.field.showAge== null ? true : this.field.showAge)"  class=" mr-2 my-2"> Age : {{age}}</h4>
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
            type : null,
            valid:false,
        }
    },
    methods: {
        checkId(){
             this.error =null;
            var idNumber = this.value.toString();
            var regex = new RegExp('e', 'g');
            idNumber = idNumber.replace(regex,"");
            if(idNumber.length == 13 && this.validateId(idNumber)){
                this.valid = true;
                var year = idNumber.substring(0,2);
                var currentYear = new Date().getFullYear() % 100;
                var prefix      = "19";
                if (year < currentYear) {
                    prefix = "20";
                }
                var id_year = prefix + year;
                var id_month = idNumber.substring(2,4);
                var id_day = idNumber.substring(4,6);

                this.birth_day = id_year + "/" + id_month +"/" + id_day
                this.age = this.ageFromDateOfBirthday(this.birth_day);
                this.gender = Number(idNumber[6]) < 5 ? "F" : "M";
                this.type = Number(idNumber[10])  === 0 ? "Citizen" : "Resident";
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

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
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
input[type=number] {
  -moz-appearance: idNumberfield;
}
</style>
