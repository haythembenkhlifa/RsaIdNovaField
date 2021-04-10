<template>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">{{this.field.name}}</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90 inline-block mx-2">
                        {{this.field.value}}
                </p>
                <font-awesome-icon v-show="this.field.showValidationIcon == null ? true : this.field.showValidationIcon" class="inline-block mx-2"  :icon="['fas','check-circle']" :color="valid ? 'green' : 'red'" />
                <h4 v-show="valid && (this.field.showBirthDay == null ? true : this.field.showBirthDay)"  class="inline-block mr-2">{{birth_day}}</h4>
                <font-awesome-icon v-show="valid  && (this.field.showGenderIcon == null ? true : this.field.showGenderIcon)" class="inline-block mr-2"  :icon="['fas',gender == 'F' ? this.field.femaleIcon == null ? 'female': this.field.femaleIcon : this.field.maleIcon == null ? 'male' : this.field.maleIcon]" />
                <h4 v-show="valid  && (this.field.showType== null ? true : this.field.showType)"  class="inline-block mr-2">{{status}}</h4>
                <h4 v-show="valid  && (this.field.showAge== null ? true : this.field.showAge)"  class="inline-block mr-2">{{age}}</h4>
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
            status : null,
            valid:false,
        }
    },
    created: function () {
        this.checkId()
    },

    methods: {
        checkId(){
             this.error =null;
            var idNumber = this.field.value.toString();
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
                this.status = Number(idNumber[10])  === 0 ? "Citizen" : "Resident";
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
     },
}
</script>
