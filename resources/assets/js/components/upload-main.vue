<template>
    <div class="uf-wrapper">
        <div class="uf-image"
             :style="{'background-image': 'url('+bgs+')'}"
             v-if="img !== '' || changed">
            <div class="uf-image-wrapper">
                <!--<i class="el-icon-view" v-on:click="show = true"></i>-->
                <!--<i class="el-icon-delete2" v-on:click="deleteImg"></i>-->
                <div class="uf-image-wrapper-icon">
                    <i class="icon-remove-icon" v-on:click="deleteImg"></i>
                </div>
            </div>
        </div>
        <modal v-if="show"
               v-on:close="show = false"
               class="modal modal-img"
               key="modal-auth">
            <img :src="bgs" alt="" v-if="!changed">
            <img :src="nimg" alt="" v-else>
        </modal>
        <div class="uf-foto"
             v-if="img === '' && !changed"
             @click="makeClick">
            <i class="icon-auto"></i>
            <button><slot></slot></button>
        </div>
        <div class="uf-input" v-show="false">
            <input type="file"
                   v-on:change="inputChange($event)"
                   name="mainFoto"
                   ref="btn"
                   accept="image/*" />
        </div>
    </div>
</template>
<script>
    export default {
        props:['image'],
        data(){
            return {
                img:this.image,
                show:false,
                changed:false,
                nimg:''
            }
        },
        computed:{
            bgs(){
                if(this.changed){
                    return this.nimg;
                }
                else{
                    if(document.querySelectorAll('.client').length){
                        return '/images/custom/'+this.img;
                    }
                    else{
                        return '/images/autoservice/'+this.img;
                    }
                }
            }
        },
        methods:{
            deleteImg(){
                this.img = '';
                this.$emit('deleted');
                this.changed = false;
            },
            inputChange(event){
                var el = event.currentTarget;

                this.$emit('input-changed',el.files);

                console.log(event.currentTarget.files);

                var files = event.currentTarget.files;

                if (FileReader && files && files.length) {
                    var fr = new FileReader;
                    fr.onload = function () {
                        serviceCabinet.$refs.mainfoto.nimg = fr.result;
                        serviceCabinet.$refs.mainfoto.changed = true;
                        console.log(serviceCabinet.$refs.mainfoto.nimg);
                    }
                    fr.readAsDataURL(files[0]);
                }
            },
            makeClick(){
                console.log('makeClick');
                var el = this.$refs.btn;
                el.click();
            }
        },
        mounted() {
            console.log(this.img, this.show, this.changed, this.nimg);
        }
    }
</script>
<style lang="less">
    .uf{
        &-image{
            width:155px;
            height:125px;
            background-repeat:no-repeat;
            background-position: center;
            background-size:cover;
            position: relative;
            border-radius:5px;
            transition: all 0.2s ease;
            //overflow: hidden;
            /*
            &:hover{
                .uf-image-wrapper{
                    opacity:1;
                }
            }
            */
            &-wrapper {
                height: 100%;
                //position: absolute;
                position: relative;
                width:100%;
                //background-color: rgba(0,0,0,0.3);
                display: flex;
                flex-flow:row nowrap;
                justify-content: center;
                align-items: center;
                //opacity: 0;
                //transition:opacity 0.3s;
                &-icon {
                    position: absolute;
                    width: 22px;
                    height: 22px;
                    background-color: #fff;
                    border-radius: 50%;
                    right: -11px;
                    top: -11px;
                    transition: all 0.15s ease-in-out;
                    i {
                        font-size: 22px;
                        color: #e30613;
                        cursor: pointer;
                    }
                    &:hover {
                        transform: scale(1.09);
                        i {
                            font-size: 24px;
                        }
                    }
                }
                i{
/*                    margin:10%;
                    color:white;
                    font-size:150%;
                    cursor: pointer;*/
                }
                &.slider {
                    transition:opacity 0.3s;
                    opacity: 0;
                    &:hover {
                        background-color: rgba(0,0,0,0.3);
                        opacity: 1;
                    }
                    i {
                        margin:10%;
                        color:white;
                        font-size:150%;
                        cursor: pointer;
                        opacity: 1;
                    }
                }
            }
        }
        &-foto{
            border: 1px dashed rgb(218, 218, 218);
            border-radius: 5px;
            background-color: rgb(239, 245, 254);
            width: 155px;
            height: 125px;
            display: flex;
            flex-flow:column nowrap;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            i {
                font-size: 32px;
                color: #b4c9e7;
                margin-bottom: 11px;
            }
            button{
                font-size:14px;
                color:#c8c8c8;
                background-color: #fff;
                border:1px solid #e8e8e8;
                border-radius:5px;
                outline:none;
                padding:7px 13px 8px 13px;
                line-height: 1.3;
                transition: all 0.3s;
            }
            &:hover {
                border-color: #0363cd;
                button {
                    background-color: #0363cd;
                    color:#fff;
                }
            }
        }
    }
</style>