<template>
    <div class="sliderImg">
        <div class="sliderImg-default">
            <div class="item" v-for="(item,index) in images" :style="{'backgroundImage':'url(/images/autoservice/'+item.url+')'}">
                <div class="uf-image-wrapper slider">
                    <i class="el-icon-view" v-on:click="show = true; imageURL = item.url"></i>
                    <i class="el-icon-delete2" v-on:click="deleteImg(index,item.id)"></i>
                </div>
            </div>
<!--        </div>
        <div class="sliderImg-default">-->
            <div class="item" v-for="(item,index) in uploaded" :style="{'backgroundImage':'url('+item+')'}">
                <div class="uf-image-wrapper slider">
                    <i class="el-icon-view" v-on:click="show = true; imageURL = item"></i>
                    <i class="el-icon-delete2" v-on:click="deleteAr(index)"></i>
                </div>
            </div>
<!--        </div>
        <div class="sliderImg-input">-->
            <div class="sliderImg-plus" @click="makeClick" v-show="count < totalMaxImages">
                <i class="el-icon-plus"></i>
            </div>
            <input v-show="false" type="file" v-on:change="inputChange($event)" ref="btn" accept="image/x-png,image/jpeg,image/jpg">
        </div>
        <modal v-if="show" v-on:close="show = false" class="modal modal-img" key="modal-auth">
            <img :src="'/images/autoservice/'+imageURL" alt="" />
        </modal>
    </div>
</template>
<script>
    export default {
        props:['images'],
        data(){
            return {
                show:false,
                uploaded:[],
                imageURL:'',
                totalMaxImages: 15,
                file:[]
            }
        },
        computed:{
            count(){
                return this.images.length + this.uploaded.length;
            }
        },
        methods:{
            deleteAr(index){
              this.uploaded.splice(index,1);
              this.file.splice(index,1);
            },
            deleteImg(index,id){
                this.$emit('del',id);
                this.images.splice(index,1)
            },
            makeClick(){
                var el = this.$refs.btn;
                el.click();
            },
            inputChange(event){
                var event = event || window.event;
                var files = event.currentTarget.files;
                this.file.push(files[0]);
                if (FileReader && files && files.length) {
                    var fr = new FileReader;
                    fr.onload = function () {
                        //serviceCabinet.$refs.mainfoto.nimg = fr.result;
                        //serviceCabinet.$refs.mainfoto.changed = true;
                        serviceCabinet.$refs.uploadSlider.uploaded.push(fr.result);
                    }
                    fr.readAsDataURL(files[0]);
                }
            }
        }
    }
</script>
<style lang="less">
    .sliderImg{
        &-plus{
            border: 1px dashed rgb(218, 218, 218);
            border-radius: 5px;
            background-color: rgb(239, 245, 254);
            width: 120px;
            height: 120px;
            display: flex;
            flex-flow:column nowrap;
            align-items: center;
            justify-content: center;
            font-size:25px;
            //opacity: 0.7;
            cursor: pointer;
            color:#5994d3;
            transition:all 0.3s ease;
            &:hover{
                //opacity:1;
                border-color: #0363cd;
                color: #0363cd;
            }
        }
        .item{
            width:120px;
            height: 120px;
            border-radius:5px;
            background-repeat:no-repeat;
            background-position: center;
            background-size:cover;
            margin: 0 10px 10px 0;
            position: relative;
            overflow: hidden;
            &:hover{
                .uf-image-wrapper{
                    opacity:1;
                }
            }
        }
        &-default{
            display:flex;
            flex-flow:row wrap;
        }
        .modal-img {
            .modal-close {
                background-color: #e30613;
            }
        }
    }
</style>