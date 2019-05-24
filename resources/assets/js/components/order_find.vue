<template>
    <div class="input-wr of" v-clickoutside="handleClose">
        <div class="of-input">
            <input type="text"
                   v-model="inputText"
                   placeholder="Например: замена масла"
                   @keyup.down="moveFocus('next')"
                   @keyup.up="moveFocus('prev')"
                   class="input of-input_input" />

<!--            <button class="btn-red of-button" v-if="!fp.length && inputText.length > 2" @click="addService">
                <div class="of-button_in">
                    <img src="/img/add-tool.svg" alt="">
                    <span>Добавить</span>
                </div>
            </button>-->
            <div class="of-drop_list" v-show="inputText.length > 2 && fp.length">
                <el-scrollbar :noresize="false"
                              :native="false"
                              :view-style="{'max-height': '418px'}">
                    <ul>
                        <li v-for="(item, index) in fp"
                            class="of-drop_item"
                            :tabindex="index + 1"
                            @click="makePush(item)">
                            <div class="of-drop_text" v-html="getText(item.name_ru)"></div>
                            <div class="of-drop_price"><span v-text="'~' + item.price"></span> лей</div>
                        </li>
                    </ul>
                </el-scrollbar>
            </div>
        </div>
    </div>
</template>
<script>
//    import Clickoutside from 'element-ui/src/utils/clickoutside';
    export default {
        data(){
            return {
                inputText:'',
                fp: '',
                selectedarray:[]
            }
        },
        props:['priceList'],
        computed:{
            priceListReverted(){
                var el = [];
                this.priceList.forEach(item=>{
                    if(!!item.next && item.next.length){
                        if(item.service.length){
                            item.service.forEach(item3=>{
                                var item_f = {
                                    name_ru:item3.name_ru,
                                    name_ro:item3.name_ro,
                                    id_unic:item3.id,
                                    id:item3.ident,
                                    price:item3.price,
                                };

                                el.push(item_f);
                            })
                        }
                        item.next.forEach(item1=>{
                            if(item1.service.length){
                                item1.service.forEach(item3=>{
                                    var item_f = {
                                        name_ro:item3.name_ro,
                                        name_ru:item3.name_ru,
                                        id_unic:item3.id,
                                        id:item3.ident,
                                        price:item3.price,
                                    };

                                    el.push(item_f);
                                })
                            }
                        })
                    }
                    else{
                        if(item.service.length){
                            item.service.forEach(item3=>{
                                var item_f = {
                                    name_ru:item3.name_ru,
                                    name_ro:item3.name_ro,
                                    id_unic:item3.id,
                                    id:item3.ident,
                                    price:item3.price,
                                };

                                el.push(item_f);
                            })
                        }
                    }
                });
                return el;
            }
        },
        methods:{
            /*
            addService(){
                var item = {};
                item['added'] = true;
                item['price'] = 0;
                item['name_ru'] = this.inputText;
                item['name_ro'] = this.inputText;
                this.$parent.checkedServices.push(item);
            },*/
            getText(text){
                let pattern = new RegExp(this.inputText, 'g');
                return text.toLowerCase().replace(pattern, '<strong>' + this.inputText + '</strong>');
            },
            makePush(item){
                this.selectedarray = [];
                this.selectedarray.push(item);
                this.$emit('update:selectedarray', this.selectedarray);

                this.inputText = '';
            },
            handleClose(){
                this.inputText = '';
            },
            moveFocus(val){
                if(val === 'next'){

                }
            }
        },
        watch:{
            inputText(val){
                this.fp = this.priceListReverted.filter(item=>{
                    let a = item.name_ru.toLowerCase();
                    let b = val.toLowerCase();
                    if(a.indexOf(b) !== -1){
                        return true;
                    }
                })
            }
        },
        mounted(){
            this.fp = this.priceListReverted;
        }
    }

    function wordend(num, words){
        return words[ ((num=Math.abs(num%100)) > 10 && num < 15 || (num%=10) > 4 || num === 0) + (num !== 1) ];
    }
    function isANumber( n ) {
        var numStr = /^-?(\d+\.?\d*)$|(\d*\.?\d+)$/;
        return numStr.test( n.toString() );
    }
</script>