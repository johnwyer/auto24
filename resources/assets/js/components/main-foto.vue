<template>
    <div class="foto-upload">
        <input type="file" id="mainFoto" @change="test">
    </div>
</template>
<script>
    export default {
        methods:{
            test(){
                var el = document.getElementById('mainFoto');
                el.files = [];
                console.log(el.files);
            }
        }
    }
</script>