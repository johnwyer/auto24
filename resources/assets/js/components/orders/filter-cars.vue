<template>
    <el-select v-model="filter.marka" placeholder="Марка авто" v-on:change="markaChanged">
        <el-option
                v-for="item in allmarks"
                :key="item.id"
                :label="item.name"
                :value="item.id">
        </el-option>
    </el-select>
</template>
<script>

</script>