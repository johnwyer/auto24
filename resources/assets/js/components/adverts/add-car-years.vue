<template>
    <div class="items-list">
        <el-radio-group v-model="selectedYearRadio.value"
                        v-on:change="setYearRadio"
                        v-if="selectedYearRadio.data.length > 0">
            <el-radio-button
                    v-for="(item) in selectedYearRadio.data"
                    :label="item"></el-radio-button>
        </el-radio-group>
        <el-select v-model="selectedYearSelect.value"
                   :class="{'is-selected': selectedYearRadio.value !== null}"
                   v-if="selectedYearSelect.data.length > 0"
                   v-on:change="setYearSelect"
                   placeholder="Старше">
            <el-option
                    v-for="(item,index) in selectedYearSelect.data"
                    :key="item"
                    :label="item"
                    :value="item">
            </el-option>
        </el-select>
    </div>
</template>
<script>
    export default {
        props: {
            initialYears: Array,
            initialSelectedYear: Number
        },
        data(){
            return {
                separator: 6,
                allYears: this.initialYears,
                selectedYear: this.initialSelectedYear,
                selectedYearRadio: {
                    value: null,
                    data: []
                },
                selectedYearSelect: {
                    value: null,
                    data: []
                }
            }
        },
        watch: {
            selectedYear(val) {
                if(val !== null) {
                    console.log('selectedYear: ', val);
                    this.$emit('cars-year-changed', this.selectedYear);
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
                    this.selectedYear = this.selectedYearSelect.value;
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
                            this.selectedYearRadio.value = this.selectedYear;
                        }
                        else {
                            this.selectedYearSelect.data.push(item);
                            this.selectedYearSelect.value = this.selectedYear;
                        }
                    });
                }
                else {
                    this.selectedYearRadio.data = this.allYears;
                    this.selectedYearRadio.value = this.selectedYear;
                }
            }
        },
        mounted() {
            this.initYears();
        }
    }
</script>
<style lang="less">

</style>