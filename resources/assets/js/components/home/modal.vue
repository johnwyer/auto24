<template>
    <transition name="modal">
        <div class="modal-mask" @click="$emit('close')">
            <div class="modal-wrapper">
                <div class="modal-container">
<!--                    <el-scrollbar :noresize="false"
                                  :native="false"
                                  :ref="'scroll-modal'"
                                  :view-style="{'max-height':'calc(100vh - 160px)'}">-->
                        <div class="modal-close" @click="$emit('close')">
                            <i class="icon-close"></i>
                        </div>
                        <div class="modal-body" @click.stop>
                            <slot></slot>
                        </div>
<!--                    </el-scrollbar>-->
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
    import {Scrollbar} from 'element-ui';
    export default {
        data(){
            return {

            }
        },
        computed:{

        },
        components: {
            Scrollbar
        },
        methods: {

        }
    }
</script>
<style lang="less">

</style>