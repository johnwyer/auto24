<template>
    <div>
        <div class="btn-list">
            <button class="btn-gray"
                    :class="{'active': showHowItWorksTab === 1}"
                    v-on:click="toggleHowItWorksTab(1)">Отправляете заявку во все сервисы</button>
            <button class="btn-gray"
                    :class="{'active': showHowItWorksTab === 2}"
                    v-on:click="toggleHowItWorksTab(2)">Ищете сервисы по цене на карте</button>
        </div>
        <div class="section-tabs-list">
            <transition v-if="showHowItWorksTab === 1"
                        name="fade"
                        mode="out-in"
                        appear
                        v-cloak>
                <template v-if="showHowItWorksTab === 1">
                    <div class="section-tab">
                        <div class="items-list">
                            <div class="item">
                                <div class="item-header">
                                    <img src="/img/tab1-1.png" alt="" />
                                </div>
                                <div class="item-footer">
                                    <p>Создаёте заявку</p>
                                </div>
                            </div>
                            <div class="item-arrow">
                                <div class="item-header">
                                    <img src="/img/tab-arrow.png" alt="" />
                                </div>
                                <div class="item-footer"></div>
                            </div>
                            <div class="item">
                                <div class="item-header">
                                    <img src="/img/tab1-2.png" alt="" />
                                </div>
                                <div class="item-footer">
                                    <p>Сравниваете цены</p>
                                </div>
                            </div>
                            <div class="item-arrow">
                                <div class="item-header">
                                    <img src="/img/tab-arrow.png" alt="" />
                                </div>
                                <div class="item-footer"></div>
                            </div>
                            <div class="item">
                                <div class="item-header">
                                    <img src="/img/tab1-3.png" alt="" />
                                </div>
                                <div class="item-footer">
                                    <p>Выбираете автосервис</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </transition>

            <transition v-if="showHowItWorksTab === 2"
                        name="fade"
                        mode="out-in"
                        appear
                        v-cloak>
                <template v-if="showHowItWorksTab === 2">
                    <div class="section-tab">
                        <div class="items-list">
                            <div class="item">
                                <div class="item-header">
                                    <img src="/img/tab1-1.png" alt="" />
                                </div>
                                <div class="item-footer">
                                    <p>Создаёте заявку2</p>
                                </div>
                            </div>
                            <div class="item-arrow">
                                <div class="item-header">
                                    <img src="/img/tab-arrow.png" alt="" />
                                </div>
                                <div class="item-footer"></div>
                            </div>
                            <div class="item">
                                <div class="item-header">
                                    <img src="/img/tab1-2.png" alt="" />
                                </div>
                                <div class="item-footer">
                                    <p>Сравниваете цены2</p>
                                </div>
                            </div>
                            <div class="item-arrow">
                                <div class="item-header">
                                    <img src="/img/tab-arrow.png" alt="" />
                                </div>
                                <div class="item-footer"></div>
                            </div>
                            <div class="item">
                                <div class="item-header">
                                    <img src="/img/tab1-3.png" alt="" />
                                </div>
                                <div class="item-footer">
                                    <p>Выбираете автосервис2</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </transition>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                showHowItWorksTab: 1
            }
        },
        computed:{

        },
        methods: {
            toggleHowItWorksTab(id){
                this.showHowItWorksTab = id;
            }
        }
    }
</script>
<style lang="less">

</style>