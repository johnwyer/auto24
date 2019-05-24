<template>
    <div class="template-wrapper">
        <div class="stat">
            <button class="stat-btn"
                    v-on:click="setFundsState(1)"
                    :class="{active: fundsState === 1}">
                <div>
                    <span>Пополнение счета</span>
                </div>
            </button>
            <button class="stat-btn"
                    v-on:click="setFundsState(2)"
                    :class="{active: fundsState === 2}">
                <div>
                    <span>Статистика</span>
                </div>
            </button>
        </div>
        <div class="sc-profile_funds-add" v-if="fundsState === 1" v-cloak>
            <h2 class="sc-profile_title">Выберите способ пополнения счета</h2>
            <template>
                <div class="funds-type-list" v-if="fundsType === null" Clickoutside>
                    <div class="item" v-on:click="setFundsType(1)">
                        <div class="column-left">
                            <i class="icon-emoney"></i>
                        </div>
                        <div class="column-right">
                            <h3 class="item_title">Электронные деньги</h3>
                            <p>Visa, Mastercard, WebMoney, Яндекс.Деньги, Bitcoin, Payeer</p>
                        </div>
                    </div>
                    <div class="item" v-on:click="setFundsType(2)">
                        <div class="column-left">
                            <i class="icon-bank"></i>
                        </div>
                        <div class="column-right">
                            <h3 class="item_title">Банковский перевод</h3>
                            <p>Перечисление на банковский счет (для юр. лиц).</p>
                        </div>
                    </div>
                </div>
            </template>
            <template>
                <div class="electronic-money" v-show="fundsType === 1" v-cloak>
                    <div class="p-back">
                        <button v-on:click.prevent="addFundsBack(fundsType)" class="p-back_button">
                            <div>
                                <i class="icon-arrow-left-long"></i>
                                <span>Электронные деньги</span>
                            </div>
                        </button>
                    </div>
                    <div class="sc-profile_data">
                        <form action="/" method="POST" novalidate autocomplete="off">
                            <div class="sc-profile_data--emoney grid-block">
                                <div class="input-wr">
                                    <label for="" class="label"><span>Введите сумму в леях</span></label>
                                    <div class="control-wrapper">
                                        <input type="text"
                                               class="input"
                                               name="emoney_amount"
                                               id="emoney_amount"
                                               maxlength="5"
                                               v-model="data.eMoney.amount"
                                               :class="{ error: errors.has('emoney_amount') }"
                                               v-on:keypress="keyValidator($event, data.eMoney.amount)"
                                               v-on:focus="errors.remove('emoney_amount')"
                                               placeholder="от 10 до 10 000 леев"
                                               v-validate="'required|max:5|min_value:0|max_value:10000'"/>
                                        <el-button type="primary"
                                                   class="btn-red"
                                                   v-on:click="sendEMoney"
                                                   :disabled="data.eMoney.process"
                                                   :loading="data.eMoney.process">ДАЛЕЕ</el-button>
                                    </div>
                                    <span :class="{ error: errors.has('emoney_amount') }"
                                          v-if="errors.has('emoney_amount')"
                                          v-cloak>Укажите правильно сумму в леях</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
            <template>
                <div class="bank-transfer" v-show="fundsType === 2" v-cloak>
                    <div class="p-back">
                        <button v-on:click.prevent="addFundsBack(fundsType)" class="p-back_button">
                            <div>
                                <i class="icon-arrow-left-long"></i>
                                <span>Банковский перевод</span>
                            </div>
                        </button>
                    </div>
                    <form id="bank-form" method="POST" action="/generatepage" novalidate autocomplete="off" target="_blank">
                        <input type="hidden" name="_token" :value="token" />
                        <div class="sc-profile_data">
                            <div class="sc-profile_data--bank-amount grid-block">
                                <div class="input-wr">
                                    <label for="bank_amount" class="label"><span>Укажите сумму в леях</span></label>
                                    <div class="control-wrapper">
                                        <input type="text"
                                               class="input"
                                               name="bank_amount"
                                               id="bank_amount"
                                               maxlength="5"
                                               v-model="data.bank.amount"
                                               :class="{ error: errors.has('bank_amount') }"
                                               v-on:keypress="keyValidator($event, data.bank.amount)"
                                               v-on:focus="errors.remove('bank_amount')"
                                               placeholder="от 10 до 10 000 леев"
                                               v-validate="'required|max:5|min_value:0|max_value:10000'" />
                                    </div>
                                    <span :class="{ error: errors.has('bank_amount') }"
                                          v-if="errors.has('bank_amount')"
                                          v-cloak>Укажите правильно сумму в леях</span>
                                </div>
                            </div>
                            <div class="sc-profile_data--bank-amount-text grid-block">
                                <div class="input-wr">
                                    <div class="control-wrapper">
                                        <label for="" class="label">&nbsp;</label>
                                        <span class="control-wrapper_text">
                                            <p>Минимальная сумма перечисления – 100 леев</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="sc-profile_data--bank-company-name grid-block">
                                <div class="input-wr">
                                    <label for="" class="label"><span>Название компании</span></label>
                                    <div class="control-wrapper">
                                        <input type="text"
                                               class="input"
                                               name="bank_company_name"
                                               maxlength="255"
                                               v-model="data.bank.companyName"
                                               :class="{ error: errors.has('bank_company_name') }"
                                               v-on:focus="errors.remove('bank_company_name')"
                                               placeholder=""
                                               v-validate="'required|max:255'" />
                                    </div>
                                    <span :class="{ error: errors.has('bank_company_name') }"
                                          v-if="errors.has('bank_company_name')"
                                          v-cloak>Укажите название Вашей компании</span>
                                </div>
                            </div>
                            <div class="sc-profile_data--bank-phone-number grid-block">
                                <div class="input-wr tel">
                                    <label for="" class="label"><span>Ваш номер телефона</span></label>
                                    <div class="control-wrapper">
                                        <input type="text"
                                               class="input"
                                               name="bank_phone"
                                               id="bank_phone"
                                               maxlength="255"
                                               v-model="data.bank.phone"
                                               :class="{ error: errors.has('bank_phone') }"
                                               v-on:focus="errors.remove('bank_phone')"
                                               placeholder=""
                                               v-validate="'required|max:255'" />
                                    </div>
                                    <span :class="{ error: errors.has('bank_phone') }"
                                          v-if="errors.has('bank_phone')"
                                          v-cloak>Укажите Ваш телефонный номер</span>
                                </div>
                            </div>
                            <div class="sc-profile_data--bank-captcha grid-block">
                                <div class="input-wr recaptcha">
                                    <div class="control-wrapper">
                                        <vue-recaptcha :sitekey="sitekey"
                                                       ref="bankrecaptcha"
                                                       @verify="onVerify"
                                                       @expired="onExpired"></vue-recaptcha>
                                    </div>
                                    <span :class="{ error: data.bank.errors.reCaptcha }"
                                          v-if="data.bank.errors.reCaptcha"
                                          v-cloak>Проверьте капчу</span>
                                </div>
                            </div>
                        </div>
                        <div class="sc-profile_submit">
                            <el-button class="btn-red sendInfo"
                                       type="primary"
                                       v-on:click="sendBank()"
                                       :disabled="data.bank.process"
                                       :loading="data.bank.process">СФОРМИРОВАТЬ СЧЕТ</el-button>
                        </div>
                        <div class="sc-profile_text">
                            <p>Есть вопросы? — Звоните по номеру <a href="tel:+37369123456">+(373) 69 123-456</a></p>
                        </div>
                    </form>
                </div>
            </template>
        </div>
        <div class="sc-profile_funds-stats" v-if="fundsState === 2" v-cloak>
            <h2 class="sc-profile_title">Входящие счета</h2>
            <div class="stats-list-wrapper">
                <div class="stats-list" v-if="data.inputStats.itemsList.length > 0" ref="inputFunds">
                    <div class="item item-head">
                        <div class="item-date">Дата</div>
                        <div class="item-type">Тип платежа</div>
                        <div class="item-price">Сумма</div>
                    </div>
                    <div class="item" v-for="item in data.inputStats.itemsList">
                        <div class="item-date">{{ item.created_at }}</div>
                        <div class="item-type">{{ item.description }}</div>
                        <div class="item-price">{{ item.amount }} леев</div>
                    </div>
                    <div class="item pagination">
                        <div class="item-prev"><a href="#"
                                                  v-on:click.prevent="loadLess('input')"
                                                  v-if="data.inputStats.page > 1"
                                                  title="Назад"><i class="icon-arrow-left-long"></i>Назад</a></div>
                        <div class="item-next"><a href="#"
                                                  v-on:click.prevent="loadMore('input')"
                                                  v-if="(data.inputStats.page === 1) && canLoadMore('input')"
                                                  title="Далее">Далее<i class="icon-arrow-right-long"></i></a></div>
                    </div>
                </div>
                <div class="stats-list" v-else>
                    <p>Нет данных</p>
                </div>
            </div>
            <h2 class="sc-profile_title">Исходящие счета</h2>
            <div class="stats-list-wrapper">
                <div class="stats-list" v-if="data.outputStats.itemsList.length > 0" ref="outputFunds">
                    <div class="item item-head">
                        <div class="item-date">Дата</div>
                        <div class="item-type">Событие</div>
                        <div class="item-price">Сумма</div>
                    </div>
                    <div class="item" v-for="item in data.outputStats.itemsList">
                        <div class="item-date">{{ item.created_at }}</div>
                        <div class="item-type">{{ item.description }}</div>
                        <div class="item-price" v-text="">{{ item.amount }} леев</div>
                    </div>
                    <div class="item pagination">
                        <div class="item-prev"><a href="#"
                                                  v-on:click.prevent="loadLess('output')"
                                                  v-if="data.outputStats.page > 1"
                                                  title="Назад"><i class="icon-arrow-left-long"></i>Назад</a></div>
                        <div class="item-next"><a href="#"
                                                  v-on:click.prevent="loadMore('output')"
                                                  v-if="(data.outputStats.page === 1) && canLoadMore('output')"
                                                  title="Далее">Далее<i class="icon-arrow-right-long"></i></a></div>
                    </div>
                </div>
                <div class="stats-list" v-else>
                    <p>Нет данных</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import VueRecaptcha from 'vue-recaptcha';
    import {Loading} from 'element-ui';

    export default {
        props: {
            initialToken: String
        },
        data(){
            return {
                token: this.initialToken,
                sitekey: '6LengGMUAAAAAK2l5zGbUZke5UCnm_uNC6tkDoTo',
                fundsState: 1,
                fundsType: null,
                data: {
                    eMoney: {
                        amount: null,
                        errors: {
                            amount: false
                        },
                        process: false,
                        success: false
                    },
                    bank: {
                        process: false,
                        success: false,
                        amount: null,
                        companyName: '',
                        phone: '',
                        reCaptcha: false,
                        errors: {
                            amount: false,
                            companyName: false,
                            phone: false,
                            reCaptcha: false
                        }
                    },
                    inputStats: {
                        itemsList: window.transaction_statistic.getInput.data,
                        total: window.transaction_statistic.getInput.total,
                        perPage: 10,
                        page: 1,
                        isLoading: false
                    },
                    outputStats: {
                        itemsList: window.transaction_statistic.getOutput.data,
                        total: window.transaction_statistic.getOutput.total,
                        perPage: 10,
                        page: 1,
                        isLoading: false
                    }
                }
            }
        },
        components: {
            'vue-recaptcha': VueRecaptcha,
            Loading
        },
        computed:{

        },
        methods: {
            canLoadMore(type){
                let leftItems;
                if(type === 'output') {
                    leftItems = this.data.outputStats.total - (this.data.outputStats.page * this.data.outputStats.perPage);
                    if (leftItems < 0) {
                        return false;
                    }
                    return true;
                }
                else if(type === 'input') {
                    leftItems = this.data.inputStats.total - (this.data.inputStats.page * this.data.inputStats.perPage);
                    if (leftItems < 0) {
                        return false;
                    }
                    return true;
                }
            },
            loadMore(type) {
                let loadingInstance;
                let data = {};
                if(type === 'output') {
                    loadingInstance = Loading.service({target: this.$refs.outputFunds});
                    data.type = type;
                    data.page = ++this.data.outputStats.page;
                }
                else if(type === 'input') {
                    loadingInstance = Loading.service({target: this.$refs.inputFunds});
                    data.type = type;
                    data.page = ++this.data.inputStats.page;
                }

                this.$http.post('\/' + bus.info.lang + '/get-statistics', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                    if(type === 'output') {
                        this.data.outputStats.itemsList = response.data.data;
                    }
                    else if(type === 'input') {
                        this.data.inputStats.itemsList = response.data.data;
                    }
                    loadingInstance.close();
                }, response => {
                    alert('No connection with server')
                });
            },
            loadLess(type) {
                let loadingInstance;
                let data = {};
                if(type === 'output') {
                    loadingInstance = Loading.service({target: this.$refs.outputFunds});
                    data.type = type;
                    data.page = --this.data.outputStats.page;
                }
                else if(type === 'input') {
                    loadingInstance = Loading.service({target: this.$refs.inputFunds});
                    data.type = type;
                    data.page = --this.data.inputStats.page;
                }

                this.$http.post('\/' + bus.info.lang + '/get-statistics', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                    if(type === 'output') {
                        this.data.outputStats.itemsList = response.data.data;
                    }
                    else if(type === 'input') {
                        this.data.inputStats.itemsList = response.data.data;
                    }
                    loadingInstance.close();
                }, response => {
                    alert('No connection with server')
                });
            },
            checkKeyboardKey(key){
                let allowedKeys = ['Backspace', 'Delete', 'Tab'];
                let flag = false;

                allowedKeys.forEach((item) => {
                    if(item === key) {
                        flag = true;
                    }
                });

                return flag;
            },
            keyValidator(event, field){
                let key = event.key || String.fromCharCode(event.keyCode);
                let pattern = /^\d+$/;
                let flag = false;

                if(pattern.test(key)) {
                    flag = true;
                }

                if(!flag && !this.checkKeyboardKey(key)) {
                    event.preventDefault();
                }
            },
            setFundsState(state){
                if(this.fundsState === state)
                    return;

                if(this.fundsType !== null) {
                    if(state === 2) {
                        this.resetAllForms();
                        this.fundsType = null;
                    }
                }

                this.fundsState = parseInt(state);
                //console.log('setFundsState ', this.fundsState);
            },
            setFundsType(type){
                this.fundsType = parseInt(type);
                console.log('setFundsType ', this.fundsType);
            },
            addFundsBack(type){
                if(type === 1) {
                    setTimeout(() => {
                        this.resetEMoneyForm();
                    }, 50)
                }
                else if(type === 2) {
                    setTimeout(() => {
                        this.resetBankForm();
                    }, 50);
                }

                //console.log('addFundsBack ', this.fundsType);
                this.fundsType = null;
                //console.log('addFundsBack ', this.fundsType);
            },
            onVerify(response){
                //console.log('Verify: ' + response);
                this.data.bank.reCaptcha = true;
                this.data.bank.errors.reCaptcha = false;
            },
            onExpired(){
                //console.log('Captcha Expired');
                this.$refs.bankrecaptcha.reset();
            },
            resetAllForms(){
                console.log('resetAllForms');
                if(this.fundsState === 1) {
                    if(this.fundsType === 1) {
                        this.resetEMoneyForm();
                    }
                    else if(this.fundsType === 2) {
                        this.resetBankForm();
                    }
                }
            },
            resetBankForm(){
                this.data.bank.amount = null;
                this.errors.remove('bank_amount');
                this.data.bank.companyName = '';
                this.errors.remove('bank_company_name');
                this.data.bank.phone = '';
                this.errors.remove('bank_phone');
                this.data.bank.reCaptcha = false;
                this.data.bank.errors.reCaptcha = false;
                this.data.bank.process = false;
                this.$refs.bankrecaptcha.reset();
            },
            resetEMoneyForm(){
                this.data.eMoney.amount = null;
                this.errors.remove('emoney_amount');
                this.data.eMoney.process = false;
            },
            sendBank(){
                let fields = {
                    bank_amount: this.data.bank.amount,
                    bank_company_name: this.data.bank.companyName,
                    bank_phone: this.data.bank.phone
                };
                this.data.bank.process = true;

                if(!this.data.bank.reCaptcha) {
                    this.data.bank.errors.reCaptcha = true;
                }

                this.$validator.validateAll(fields).then((result) => {
                    if (result && this.data.bank.reCaptcha) {
                        console.log('SendBank valid');
                        document.getElementById('bank-form').submit();
                    }
                    else {
                        console.log('SendBank INvalid');
                        this.$validator.validateAll(fields);
                        this.data.bank.process = false;
                    }
                }).catch(() => {

                });
            },
            sendEMoney(){
                let fields = {
                    emoney_amount: this.data.eMoney.amount
                };
                this.data.eMoney.process = true;

                this.$validator.validateAll(fields).then((result) => {
                    if (result) {
                        console.log('SendEMoney valid');
                        document.getElementById('emoney-form').submit();
                    }
                    else {
                        console.log('SendEMoney INvalid');
                        this.$validator.validateAll(fields);
                        this.data.eMoney.process = false;
                    }
                }).catch(() => {

                });
            }
        },
        mounted() {
            //console.log(this.data.inputStats);
            //console.log(this.data.outputStats);
        }
    }
</script>
<style lang="less">

</style>