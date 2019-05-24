<template>
    <div class="input-wr address">
        <div class="transition-wrapper">
            <div class="address-wrapper">
                <div class="address-wrapper-item">

                    <label for="input1" class="label">
                        <span class="">Регион</span>
                    </label>
                    <el-select v-model="addressSelect.district"
                               :placeholder="addressSelect.placeholders.district"
                               ref="elSelect1"
                               key="input1"
                               v-on:change="addressGetDistrict"
                               :disabled="addressSelect.disable.district"
                               filterable
                               >
                        <el-option
                                v-for="(item,index) in addressSelect.options.districts"
                                :key="item.id"
                                :label="item['name_'+lang]"
                                :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </div>
                <div class="address-wrapper-item">
                    <label for="input2" class="label">
                        <span class="">Город</span>
                    </label>
                    <el-select v-model="addressSelect.city"
                               :placeholder="addressSelect.placeholders.city"
                               ref="elSelect2"
                               key="input2"
                               v-on:change="addressGetCity"
                               :disabled="addressSelect.disable.city"
                               filterable
                               >
                        <el-option
                                v-for="(item,index) in addressSelect.options.cities"
                                :key="item.id"
                                :label="item['name_'+lang]"
                                :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:['lang'],
        data(){
            return {
                addressSelect:{
                    tab:1,
                    progress:1,
                    district: '',
                    city: '',
                    firstLoad: true,
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
            setAddress() {
                console.log("setAddress() district", window.info.location.district);
                console.log("setAddress() city", window.info.location.city);
                if(window.info !== undefined) {
                    this.addressSelect.district = parseInt(window.info.location.district);
                    //this.$emit('locationchanged', this.addressSelect);
                }

                //this.setPlaceholder();
                this.getDistrict();
            },
            setPlaceholder(){
                //if(type === 'district') {
                //if(window.info)
                    if (window.info.location_text.district !== '' && this.addressSelect.firstLoad) {
                        console.log('getPlaceholder(district) ', window.info.location_text.district);
                        this.addressSelect.placeholders.district = window.info.location_text.district;
                    }
                //}
                //if(type === 'city') {
                    if (window.info.location_text.city !== ''  && this.addressSelect.firstLoad) {
                        console.log('getPlaceholder(city) ', window.info.location_text.city);
                        this.addressSelect.placeholders.city = window.info.location_text.city;
                    }
                //}
            },
            districtchanged(){
                this.$emit('districtchanged', this.addressSelect);
            },
            getDistrict(){
                var data = {
                    tab: 1,
                    id: this.addressSelect.district
                };
                console.log("getDistrict()", data);
                this.addressSelect.disable.city = true;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response =>
                {
                    this.addressSelect.options.districts = response.data;
                    this.addressSelect.disable.city = false;
                    this.addressSelect.city = "";

                    if(window.info.location.district !== undefined && this.addressSelect.firstLoad) {
                        this.getCity();
                    }
                },response => {
                    alert('No connection with server');
                    this.addressSelect.disable.city = true;
                });
            },
            getCity(){
                var data = {
                    tab: 2,
                    id: this.addressSelect.district
                };

                //console.log('firstLoad: ', this.addressSelect.firstLoad);
                if(this.addressSelect.firstLoad) {
                    data.id = window.info.location.district;
                    this.addressSelect.firstLoad = false;
                    //console.log(window.info.location.district);
                    //this.addressSelect.district = window.info.location.district;
                }

                console.log("getCity(): ", data);
                this.addressSelect.disable.city = true;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response =>
                {
                    this.addressSelect.options.cities = response.data;
                    this.addressSelect.disable.city = false;
                },response => {
                    alert('No connection with server');
                    this.addressSelect.disable.city = true;
                });


                /*
                if(this.addressSelect.firstLoad) {
                    let _that = this;

                    setTimeout(function(){
                        _that.addressSelect.firstLoad = false;
                    }, 4000);

                    setTimeout(function(){
                        _that.addressSelect.district = window.info.location.district;
                        _that.addressSelect.city = window.info.location.city;
                    }, 4000);
                }
                */
            },
            addressGetDistrict(){
                if(this.addressSelect.district !== '' && !this.addressSelect.firstLoad){
                    this.addressSelect.tab = 1;
                    this.addressSelect.progress = 1;
                    this.getCity();
                    console.log('addressGetDistrict(): locationchanged');
                    this.$emit('locationchanged', this.addressSelect);
                    /*
                    if(!this.addressSelect.firstLoad) {
                        this.addressSelect.placeholders.city = "Выберите город";
                    }
                    */
                }
            },
            addressGetCity(){
                if(this.addressSelect.city !== '' && !this.addressSelect.firstLoad){
                    if(this.addressSelect.progress >= this.addressSelect.tab){
                        this.addressSelect.tab = 2;
                        this.addressSelect.progress = 2;
                        //this.getCity();
                    }
                    console.log('addressGetCity(): locationchanged');
                    this.$emit('locationchanged', this.addressSelect);
                }
            }

        },
        mounted(){
            //this.setPlaceholder();
            this.getDistrict();
            //this.setAddress();
            /*
            if(this.$parent.addressSelect.district.length > 0) {
                this.selected = this.$parent.addressSelect.district;

            }
            */
        }
    }
</script>
<style scoped lang="less">

</style>