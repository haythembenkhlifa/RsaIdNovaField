<template>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">{{this.field.name}}</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90 inline-block mr-2">
                    <font-awesome-icon  class="inline-block mr-2 " style="margin-top:0.59em;"  :icon="['fas','id-card']" color="#d0d6dc" />
                    {{this.field.value}}
                </p>
                <!-- <font-awesome-icon v-show="this.field.showValidationIcon == null ? true : this.field.showValidationIcon" class="inline-block mx-2"  :icon="['fas','check-circle']" :color="valid ? 'green' : 'red'" />
                <h4 v-show="valid && (this.field.showBirthDay == null ? true : this.field.showBirthDay)"  class="inline-block mr-2">{{birth_day}}</h4>
                <font-awesome-icon v-show="valid  && (this.field.showGenderIcon == null ? true : this.field.showGenderIcon)" class="inline-block mr-2"  :icon="['fas',gender == 'F' ? this.field.femaleIcon == null ? 'female': this.field.femaleIcon : this.field.maleIcon == null ? 'male' : this.field.maleIcon]" />
                <h4 v-show="valid  && (this.field.showType== null ? true : this.field.showType)"  class="inline-block mr-2">{{citizenship}}</h4>
                <h4 v-show="valid  && (this.field.showAge== null ? true : this.field.showAge)"  class="inline-block mr-2">{{age}}</h4> -->
                <font-awesome-icon v-show="this.field.showValidationIcon == null ? true : this.field.showValidationIcon" class="mx-2 "  :icon="['fas','check-circle']" :color="valid ? 'green' : 'red'" />
                <h4 v-show="valid && birth_day && (this.field.showBirthDay == null ? true : this.field.showBirthDay)"  class=" mr-2 mb-2 mt-3 ">Birth Day : {{birth_day}}</h4>
                <h4 class="mr-2 my-2" v-show="valid && gender && (this.field.showGenderIcon == null ? true : this.field.showGenderIcon)">Gender : <font-awesome-icon  class="mr-2"  :icon="['fas',gender == 'F' ? this.field.femaleIcon == null ? 'female': this.field.femaleIcon : this.field.maleIcon == null ? 'male' : this.field.maleIcon]" /></h4>
                <h4 v-show="valid && citizenship  && (this.field.showCitizenship== null ? true : this.field.showCitizenship)"  class="mr-2 my-2">Citizenship : {{citizenship}}</h4>
                <h4 v-show="valid && age && (this.field.showAge== null ? true : this.field.showAge)"  class=" mr-2 my-2"> Age : {{age}}</h4>
            </div>
        </div>



</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            birth_day:null,
            rest_of_id:null,
            age :  null,
            gender :  null,
            citizenship : null,
            valid:false,
            percentage:50
        }
    },
    created: function () {
        this.checkId()
    },

    methods: {
        checkId(){
            this.reset();
            var idNumber = this.field.value.toString();
            this.percentage = (idNumber.length*100)/13;
            var regex = new RegExp('e', 'g');
            idNumber = idNumber.replace(regex,"");
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

            if(idNumber.length == 13 && this.validateId(idNumber)){

                this.valid = true;
                this.validateBirthDay(idNumber);
                this.gender = Number(idNumber[6]) < 5 ? "F" : "M";
                this.citizenship = Number(idNumber[10])  === 0 ? "SA Citizen" : "Non-SA Citizen";
                return;
            }else{
                this.valid = false;
                return;
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
    },
}
</script>
