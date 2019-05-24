<template>
    <div class="items-list">
        <el-radio-group v-model="selectedColor">
            <el-radio-button
                    :style="{'backgroundColor': item.color}"
                    v-for="(item) in allColors"
                    :label="item.id">
                <el-tooltip class="item" effect="dark" :content="item.name" placement="bottom">
                <span class="circle">
                    <span class="circle-content">
                        <i class="icon-color-check"></i>
                    </span>
                </span>
                </el-tooltip>
            </el-radio-button>
        </el-radio-group>
    </div>
</template>
<script>
    export default {
        props: {
            initialColors: Array,
            initialSelectedColor: Number
        },
        data(){
            return {
                allColors: this.initialColors,
                selectedColor: this.initialSelectedColor
            }
        },
        watch: {
            selectedColor(val) {
                if(val !== null) {
                    console.log('selectedColor: ', val);
                    this.$emit('cars-color-changed', this.selectedColor);
                }
            }
        },
        methods: {
            setYearRadio($val){
                if($val !== null) {
                    this.selectedYear = this.selectedYearRadio.value;
                    if(this.selectedYearSelect.value !== null) {
                        this.selectedYearSelect.value = null;
                    }
                }
            },
            setYearSelect($val){
                if($val !== null) {
                    this.selectedColor = this.selectedYearSelect.value;
                    if(this.selectedYearRadio.value !== null) {
                        this.selectedYearRadio.value = null;
                    }
                }
            },
            initYears(){
                if(this.allYears.length > this.separator) {
                    this.allYears.forEach((item, index) => {
                        if(index < this.separator) {
                            this.selectedYearRadio.data.push(item);
                        }
                        else {
                            this.selectedYearSelect.data.push(item);
                        }
                    });
                }
                else {
                    this.selectedYearRadio.data = this.allYears;
                }
            }
        },
        mounted() {
            //this.initYears();
        }
    }
</script>
<style lang="less">

</style>