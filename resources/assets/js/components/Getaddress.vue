
<template>
    <div class="input-wr address">

        <transition name="fadeCity" mode="out-in" v-if="expandedSearchCtrl">
            <div class="transition-wrapper">

                <div class="address-wrapper">
                    <div class="address-wrapper-item">
                        <label for="input1" class="label">
                            <span class="">Регион</span>
                        </label>
                        <el-select v-model="addressSelect.district.value"
                                   placeholder="Выбрать регион"
                                   ref="elSelect1"
                                   key="input1"
                                   name="elSelect1"
                                   v-on:change="addressGetDistrict"
                                   :disabled="addressSelect.disable.district"
                                   filterable>
                            <el-option
                                    v-for="(item,index) in addressSelect.options.districts"
                                    :key="item.id"
                                    :label="item['name_'+lang]"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </div>
                    <div class="address-wrapper-item">
                        <label for="input2" class="label">
                            <span class="">Город</span>
                        </label>
                        <el-select v-model="addressSelect.city.value"
                                   placeholder="Выбрать город"
                                   ref="elSelect2"
                                   key="input2"
                                   name="elSelect2"
                                   v-on:change="addressGetCity"
                                   :disabled="addressSelect.disable.city"
                                   filterable>
                            <el-option
                                    v-for="(item,index) in addressSelect.options.cities"
                                    :key="item.id"
                                    :label="item['name_'+lang]"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </div>
                </div>
            </div>
        </transition>

        <a href="#" class="btn btn-expand" @click.prevent="expandSearch()">Расширенный поиск <i v-bind:class="{ active: expandedSearchCtrl}" class="icon-long-arrow-down"></i></a>
    </div>
</template>
<script>
    export default {
        props:['lang', 'mapSizeHeight'],
        data(){
            return {
                addressSelect:{
                    tab:1,
                    progress:1,
                    district: {
                        value: ''
                    },
                    city: {
                        value: ''
                    },
                    options: {
                        districts: [],
                        cities: []
                    },
                    disable:{
                        district:false,
                        city:true
                    }
                },
                expandedSearchCtrl: false
            }
        },
        watch: {
            'addressSelect.district.value'(val){
                let elInput, closeTemplate;
                if(!document.getElementById('closeElSelect1')) {
                    elInput = $(".el-input__inner[name='elSelect1']").parent();
                    closeTemplate = $(`<div class="close-el-select" id="closeElSelect1"><i class="icon-close"></i></div>`);
                    elInput.append(closeTemplate);
                }

                if(val !== '') {
                    $("#closeElSelect1").removeClass('hidden');

                    let _self = this;
                    $(closeTemplate).on("click", function(){
                        _self.addressSelect.district.value = '';
                        _self.addressSelect.city.value = '';
                        _self.addressSelect.options.cities = [];
                        _self.$emit('locationchanged', _self.addressSelect);
                        $("#closeElSelect1").addClass('hidden');
                        $("#closeElSelect2").addClass('hidden');
                    });
                }
            },
            'addressSelect.city.value'(val){
                let elInput, closeTemplate;
                if(!document.getElementById('closeElSelect2')) {
                    elInput = $(".el-input__inner[name='elSelect2']").parent();
                    closeTemplate = $(`<div class="close-el-select" id="closeElSelect2"><i class="icon-close"></i></div>`);
                    elInput.append(closeTemplate);
                }

                if(val !== '') {
                    $("#closeElSelect2").removeClass('hidden');

                    let _self = this;
                    $(closeTemplate).on("click", function(){
                        _self.addressSelect.city.value = '';
                        _self.$emit('locationchanged', _self.addressSelect);
                        $("#closeElSelect2").addClass('hidden');
                    });
                }
            }
        },
        methods:{
            /*
            clearDistrict(){
                this.addressSelect.district.value = '';
                this.addressSelect.city.value = '';
                this.addressSelect.options.cities = [];
                this.$emit('locationchanged', this.addressSelect);
            },
            clearCity(){
                this.addressSelect.city.value = '';
                this.$emit('locationchanged', this.addressSelect);
            },
            */
            expandSearch(){
                this.expandedSearchCtrl = !this.expandedSearchCtrl;

                if(!this.expandedSearchCtrl) {
                    this.addressSelect.city.value = '';
                    this.addressSelect.district.value = '';
                    this.$emit('locationchanged', this.addressSelect);
                }

                if(document.getElementById('findonmap')) {
                    /*var _self = this;
                    setTimeout(function () {
                        _self.$parent.mapSizeHeight();
                    }, 100);
                    */
                    setTimeout(() => {
                        this.$parent.mapSizeHeight();
                    }, 50);

                }
            },
            districtchanged(){
                this.$emit('districtchanged', this.addressSelect)
            },
            getDistrict(){
                var data = {
                    tab: 1,
                    id: this.addressSelect.district.value
                };
                //console.log(this.addressSelect.district);
                //console.log("getDistrict: ", data);
                this.addressSelect.disable.city = true;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response =>
                {
                    this.addressSelect.options.districts = response.data;
                    this.addressSelect.disable.city = false;
                    this.addressSelect.city.value = "";
                },response => {
                    alert('No connection with server');
                    this.addressSelect.disable.city = true;
                });
                //console.log(this.addressSelect);
            },
            getCity(){
                var data = {
                    tab: 2,
                    id: this.addressSelect.district.value
                };
                console.log("getCity: ", data);
                this.addressSelect.disable.city = true;
                this.$http.post('\/'+'get-district', data, {headers:{'X-CSRF-TOKEN': bus.info.key}}).then(response =>
                {
                    this.addressSelect.options.cities = response.data;
                    this.addressSelect.disable.city = false;
                    this.addressSelect.city.value = '';
                },response => {
                    alert('No connection with server')
                    this.addressSelect.disable.city = true;
                });
            },
            addressGetDistrict(){
                if(this.addressSelect.district.value !== ''){
                    this.addressSelect.tab = 1;
                    this.addressSelect.progress = 1;
                    this.getCity();
                    this.$emit('locationchanged',this.addressSelect);
                }
            },
            addressGetCity(){
                if(this.addressSelect.city.value !== ''){
                    if(this.addressSelect.progress >= this.addressSelect.tab){
                        this.addressSelect.tab = 2;
                        this.addressSelect.progress = 2;
                        //this.getCity();
                    }
                    this.$emit('locationchanged', this.addressSelect);
                }
            }

        },
        mounted(){
            this.getDistrict();
        }
    }
</script>

<style scoped lang="less">

</style>