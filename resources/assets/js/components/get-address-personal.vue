<template>
    <div class="input-wr address">
        <div class="transition-wrapper">
            <div class="address-wrapper">
                <div class="address-wrapper-item">
                    <label for="input1" class="label">
                        <span class="">Регион</span>
                    </label>
                    <el-select v-model="addressSelect.district.value"
                               :placeholder="addressSelect.placeholders.district"
                               ref="elSelect1"
                               key="input1"
                               name="select-district"
                               id="select-district"
                               v-on:change="addressGetDistrict"
                               :disabled="addressSelect.disable.district"
                               :class="{ error: addressSelect.district.hasError }"
                               filterable>
                        <el-option
                                v-for="(item,index) in addressSelect.options.districts"
                                :key="item.id"
                                :label="item['name_'+lang]"
                                :value="item.id">
                        </el-option>
                    </el-select>
                    <span class="error adres"
                          v-if="addressSelect.district.hasError"
                          v-cloak>Выберите район</span>
                </div>
                <div class="address-wrapper-item">
                    <label for="input2" class="label">
                        <span class="">Город</span>
                    </label>
                    <el-select v-model="addressSelect.city.value"
                               :placeholder="addressSelect.placeholders.city"
                               ref="elSelect2"
                               key="input2"
                               name="select-city"
                               id="select-city"
                               v-on:change="addressGetCity"
                               :disabled="addressSelect.disable.city"
                               :class="{ error: addressSelect.city.hasError }"
                               filterable>
                        <el-option
                                v-for="(item,index) in addressSelect.options.cities"
                                :key="item.id"
                                :label="item['name_'+lang]"
                                :value="item.id">
                        </el-option>
                    </el-select>
                    <span class="error adres"
                          v-if="addressSelect.city.hasError"
                          v-cloak>Выберите город</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            lang: String,
            componentData: Object
        },
        data(){
            return {
                initialData: this.componentData,
                firstLoad: true,
                hasData: false,
                addressSelect:{
                    tab:1,
                    progress:1,
                    district: {
                        value: '',
                        hasError: false
                    },
                    city: {
                        value: '',
                        hasError: false
                    },
                    placeholders: {
                        district: 'Выберите регион',
                        city: 'Выберите город'
                    },
                    options: {
                        districts: [],
                        cities: []
                    },
                    disable:{
                        district: false,
                        city: true
                    }
                }
            }
        },
        computed: {

        },
        methods:{
            checkAddress(){
                //let hasData = false;
                if(this.initialData.district !== '') {
                    this.getDefaultDistrict();
                    //this.addressSelect.district.value = this.$parent.selectedAddress.district;
                    this.hasData = true;
                }
                if(this.initialData.city !== '') {
                    this.getDefaultCity();
                    //this.addressSelect.city.value = this.$parent.selectedAddress.city;
                    this.hasData = true;
                }

                console.log("Getaddress3: hasData", this.hasData);
                if(!this.hasData) {
                    this.getDistrict();
                }
            },
            /*
            districtchanged(){
                this.$emit('districtchanged', this.addressSelect);
            },
            */
            getDefaultDistrict(){
                if(this.firstLoad){
                    //console.log('firstLoad: ', this.firstLoad);
                    this.addressSelect.district.value = this.initialData.text.district;
                    //console.log('select district', this.addressSelect.district.value);
                    //console.log('data for select', this.initialData.text.district);
                }

                let data = {
                    tab: 1,
                    id: ''
                };

                console.log("getDefaultDistrict()", data);
                this.addressSelect.disable.city = true;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                    this.addressSelect.options.districts = response.data;
                    this.addressSelect.disable.city = false;
                    this.addressSelect.district.value = this.initialData.district;
                    if(this.initialData.city === '') {
                        this.firstLoad = false;
                    }
                    //this.addressSelect.city.value = "";
                },response => {
                    alert('No connection with server');
                    this.addressSelect.disable.city = true;
                });
            },
            getDistrict(){
                let data = {
                    tab: 1,
                    id: this.addressSelect.district.value
                };

                if(this.firstLoad && !this.hasData) {
                    this.firstLoad = false;
                }

                console.log("getDistrict()", data);
                this.addressSelect.disable.city = true;
                this.hasData = false;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                    this.addressSelect.options.districts = response.data;
                    this.addressSelect.disable.city = false;

                    if(this.addressSelect.district.value !== '') {
                        this.addressSelect.city.value = "";
                        this.getCity();
                    }
                },response => {
                    alert('No connection with server');
                    this.addressSelect.disable.city = true;
                });
            },
            getDefaultCity(){
                let data = {
                    tab: 2,
                    id: this.addressSelect.district.value
                };

                if(this.firstLoad){
                    this.addressSelect.city.value = this.initialData.text.city;
                    data.id = this.initialData.district;
                }

                console.log("getDefaultCity(): ", data);
                this.addressSelect.disable.city = true;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                    this.addressSelect.options.cities = response.data;
                    this.addressSelect.disable.city = false;
                    this.addressSelect.city.value = this.initialData.city;
                    if(this.firstLoad) {
                        let that = this;
                        setTimeout(function(){
                            that.firstLoad = false;
                        }, 100);
                    }
                },response => {
                    alert('No connection with server');
                    this.addressSelect.disable.city = true;
                });
            },
            getCity(){
                let data = {
                    tab: 2,
                    id: this.addressSelect.district.value
                };

                if(!this.firstLoad) {
                    console.log("getCity(): ", data);
                    this.addressSelect.disable.city = true;
                    if(this.addressSelect.city.value !== ''){
                        this.addressSelect.city.value = '';
                    }
                    this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                        this.addressSelect.options.cities = response.data;
                        this.addressSelect.disable.city = false;
                        //console.log('city DOM: ', document.getElementById('select-city'));
                        //console.log('city Value: ', document.getElementById('select-city').value);

                        //document.getElementById('select-city').value="";
                    },response => {
                        alert('No connection with server');
                        this.addressSelect.disable.city = true;
                    });
                }
            },
            addressGetDistrict(){
                if(this.addressSelect.district.value !== ''){
                    this.addressSelect.district.hasError = false;
                    this.$parent.selectedAddress.errors.district.hasError = false;
                    this.addressSelect.tab = 1;
                    this.addressSelect.progress = 1;
                    //this.addressSelect.city.value = '';
                    this.getCity();

                    if(!this.firstLoad) {
                        console.log('addressGetDistrict(): locationchanged');
                        this.$emit('locationchanged', this.addressSelect);
                    }
                }
                else {
                    this.addressSelect.district.hasError = true;
                    this.$parent.selectedAddress.errors.district.hasError = true;
                }
            },
            addressGetCity(){
                if(this.addressSelect.city.value !== ''){
                    if(this.addressSelect.progress >= this.addressSelect.tab){
                        this.addressSelect.city.hasError = false;
                        this.addressSelect.tab = 2;
                        this.addressSelect.progress = 2;
                        //this.getCity();
                    }
                    if(!this.firstLoad) {
                        console.log('addressGetCity(): locationchanged');
                        this.$emit('locationchanged', this.addressSelect);
                    }
                }
            },
            initData(){
                if(this.componen === undefined) {
                    this.initialData = this.$parent.selectedAddress;
                }
            }
        },
        mounted(){
            //this.setPlaceholder();
            //this.initData();
            this.checkAddress();
            //this.getDistrict();
        }
    }
</script>

<style scoped lang="less">

</style>