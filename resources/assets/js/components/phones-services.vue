<template>
    <div class="user-phones-component">
        <h2 class="sc-profile_title">{{ $t('message.phones_for_communication') }}</h2>
        <section class="order-data_enter">
            <transition name="fade" mode="out-in" tag="div" key="userPhones">
                <div class="enter-wrapper st2">
                    <div class="enter-reg">
                        <div class="enter-reg_form ph-list" v-if="userPhones.length > 0">
                            <div class="phones-list">
                                <div class="phones-list__item selected"
                                     v-for="phone in userPhones">
                                    <label for="" class="el-radio">
                                        <span class="el-radio__label">
                                            <em class="code">+373</em>
                                            <span class="number-text" v-text="phone.phone">69 433-433</span>
                                        </span>
                                    </label>
                                    <a class="remove-number" href="#" v-on:click.prevent="$parent.modal.confirmPhoneRemoving = true, $parent.modal.phone = phone"><i class="icon-close"></i>{{ $t('message.remove') }}</a>
                                </div>
                                <div class="phones-list__item phone-errors">
                                                        <span :class="{ error: userPhoneErrors.hasError }"
                                                              v-if="userPhoneErrors.hasError"
                                                              v-text="userPhoneErrors.errorText"
                                                              v-cloak></span>
                                </div>
                                <div class="phones-list__item">
                                    <a class="add-number"
                                       href="#"
                                       v-if="!newPhoneNumberForm"
                                       v-on:click.prevent="showNewPhoneNumberForm()"><i class="icon-plus-circle"></i>{{ $t('message.add_phone') }}</a>
                                    <a class="cancel-number"
                                       href="#"
                                       v-if="newPhoneNumberForm"
                                       v-on:click.prevent="resetNewPhoneNumberForm()"><i class="icon-close"></i>{{ $t('message.cancel') }}</a>
                                </div>
                            </div>
                        </div>

                        <transition name="fade" key="newPhoneNumberForm">
                            <div class="enter-reg_form phones" v-if="newPhoneNumberForm">
                                <div class="input-wr tel">
                                    <!--<label for="" class="label">&nbsp;</label>-->
                                    <div class="control-wrapper">
                                        <div class="prefix">+373</div>
                                        <input type="text"
                                               class="input not-verified"
                                               :maxlength="newPhoneMaxLength"
                                               :minlength="newPhoneMinLength"
                                               :readonly="newPhoneNumberCodeForm"
                                               novalidate
                                               name="newPhoneNumber"
                                               v-on:keypress="keyValidator($event,newPhoneNumber,true)"
                                               v-on:focus="newPhoneNumberErrors.phone.hasErrors = false"
                                               v-model="newPhoneNumber" />
                                        <el-button type="primary"
                                                   class="get_code"
                                                   v-on:click="checkNewPhoneNumber()"
                                                   :disabled="validateNewPhoneNumber() || newPhoneNumberSending">{{ $t('message.get_code') }}</el-button>
                                    </div>
                                    <span :class="{ error: newPhoneNumberErrors.phone.hasErrors }"
                                          v-if="newPhoneNumberErrors.phone.hasErrors"
                                          v-text="newPhoneNumberErrors.phone.errorText"
                                          v-cloak
                                    ></span>
                                </div>

                                <transition name="fade" key="newPhoneNumberConfirm">
                                    <div class="enter-reg_form-confirm-sms" v-if="newPhoneNumberCodeForm">
                                        <div class="input-wr tel">
                                            <label for="" class="label">{{ $t('message.input_code_sent_to_number') }} + 373 <b v-text="newPhoneNumberShort"></b></label>
                                            <div class="control-wrapper">
                                                <input type="text"
                                                       class="input"
                                                       :placeholder="$t('message.code_from_sms')"
                                                       :maxlength="newPhoneCodeLength"
                                                       :minlength="newPhoneCodeLength"
                                                       novalidate
                                                       v-on:keypress="keyValidator($event,newPhoneCode,true)"
                                                       v-on:focus="newPhoneNumberErrors.code.hasErrors = false"
                                                       v-model="newPhoneCode"
                                                       v-on:change="" />
                                                <el-button type="primary"
                                                           class="get_code"
                                                           v-on:click="sendNewPhoneNumberCode()"
                                                           :disabled="newPhoneNumberCodeSending">{{ $t('message.confirm') }}</el-button>
                                            </div>
                                            <span :class="{ error: newPhoneNumberErrors.code.hasErrors }"
                                                  v-if="newPhoneNumberErrors.code.hasErrors"
                                                  v-text="newPhoneNumberErrors.code.errorText"
                                                  v-cloak
                                            ></span>
                                        </div>
                                        <div class="input-wr confirm-text">
                                            <p v-if="newPhoneCodeSendAttempts < 3">{{ $t('message.phones_disclaimer_5') }}
                                                <a href="#"
                                                   class="resend-number"
                                                   :class="{'is-disabled': newPhoneNumberCodeReSending}"
                                                   v-on:click.prevent="resendNewPhoneNumberCode()">{{ $t('message.phones_disclaimer_7') }}</a> {{ $t('message.phones_disclaimer_8') }} <span v-text="newPhoneSecondsCounter">60</span> {{ $t('message.phones_disclaimer_9') }}</p>
                                            <p>{{ $t('message.phones_disclaimer_1') }}<br /> {{ $t('message.phones_disclaimer_2') }}</p>
                                            <p>{{ $t('message.phones_disclaimer_3') }}<a href="">{{ $t('message.phones_disclaimer_4') }}</a>.</p>
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </transition>
                    </div>
                </div>
            </transition>
        </section>
    </div>
</template>

<script>
    export default {
        props: {
            initialUserPhones: Array,
            initialSmsCodeTime: Number
        },
        data(){
            return {
                userPhones: this.initialUserPhones,
                userPhoneErrors: {
                    hasError: false,
                    errorText: ''
                },
                userPhoneNumber: '',
                newPhoneNumber: '',
                newPhoneNumberShort: '',
                newPhoneCode: '',
                newPhoneNumberErrors: {
                    phone: {
                        hasErrors: false,
                        errorText: ''
                    },
                    code: {
                        hasErrors: false,
                        errorText: ''
                    }
                },
                newPhoneNumberForm: false,
                newPhoneNumberCodeForm: false,
                newPhoneNumberSending: false,
                newPhoneNumberCodeSending: false,
                newPhoneNumberCodeReSending: false,
                newPhoneTimeout: null,
                SMS_CODE_TIME: this.initialSmsCodeTime,
                newPhoneSecondsCounter: this.initialSmsCodeTime,
                newPhoneCodeSendAttempts: 0,
                newPhoneMinLength: 8,
                newPhoneMaxLength: 9,
                newPhoneCodeLength: 4
            }
        },
        watch: {
            /*
            userPhoneNumber(val) {
                if(this.userPhoneNumber !== '') {
                    this.userPhoneErrors.hasError = false;
                    this.userPhoneErrors.errorText = '';
                    this.$emit('phone-changed', this.userPhoneNumber);
                }
            }*/
        },
        components:{

        },
        methods:{
            checkKeyboardKey(key){
                let allowedKeys = ['Backspace', 'Delete', 'Tab', 'End', 'Home', 'ArrowRight', 'ArrowLeft'];
                let flag = false;

                allowedKeys.forEach((item) => {
                    if(item === key) {
                        flag = true;
                    }
                });

                return flag;
            },
            keyValidator(event, field, zero){
                let key = event.key || String.fromCharCode(event.keyCode);
                //console.log(key);
                let pattern = /^\d+$/;
                let flag = false;

                if(pattern.test(key)) {
                    flag = true;
                }

                /*
                if(field.length === 0 && key === '0' && flag && zero) {
                    event.preventDefault();
                }*/

                if(!flag && !this.checkKeyboardKey(key)) {
                    event.preventDefault();
                }
            },/*
            getDeletePhoneNumberMessage(){
                return `Вы действительно хотите удалить Ваш телефонный номер: <strong>+373 ${this.$parent.modal.phone.phone}</strong>?`;
            },*/
            setupPhones(){
                if(this.userPhones.length === 0) {
                    this.newPhoneNumberForm = true;
                }
                if(difUser === 'service') {
                    if(window.info.log_phone !== '') {
                        this.newPhoneNumber = window.info.log_phone;
                    }
                }
            },
            showNewPhoneNumberForm(){
                this.newPhoneNumberForm = !this.newPhoneNumberForm;
            },
            resetNewPhoneNumberForm(){
                this.newPhoneNumberErrors.phone.hasErrors = false;
                this.newPhoneNumberErrors.phone.errorText = '';
                this.newPhoneNumberErrors.code.hasErrors = false;
                this.newPhoneNumberErrors.code.errorText = '';
                this.newPhoneNumberForm = false;
                this.newPhoneNumberCodeForm = false;
                this.newPhoneNumber = '';
                this.newPhoneNumberShort = '';
                this.newPhoneCode = '';
                this.newPhoneCodeSendAttempts = 0;
                this.newPhoneSecondsCounter = this.SMS_CODE_TIME;
                this.newPhoneNumberSending = false;
                this.newPhoneNumberCodeSending = false;
                if(this.newPhoneTimeout !== null) {
                    clearTimeout(this.newPhoneTimeout);
                }
                this.newPhoneTimeout = null;
            },
            validateNewPhoneNumber(){
                let disabled = false;
                //if(this.newPhoneNumber.length !== this.newPhoneLength) {
                if(this.newPhoneNumber.length < this.newPhoneMinLength || this.newPhoneNumber.length > this.newPhoneMaxLength) {
                    disabled = true;
                }

                return disabled;
            },
            runNewPhoneCodeTimer(){
                let that = this;
                that.newPhoneTimeout = setInterval(function() {
                    --that.newPhoneSecondsCounter;

                    if (that.newPhoneSecondsCounter === 0) {
                        clearInterval(that.newPhoneTimeout);
                        if(that.newPhoneCodeSendAttempts < 3) {
                            that.newPhoneNumberCodeReSending = false;
                        }
                    }
                }, 1000);
            },
            checkNewPhoneNumber(){
                this.newPhoneNumberShort = this.newPhoneNumber;
                if(this.newPhoneNumberShort.length === this.newPhoneMaxLength) {
                    this.newPhoneNumberShort = this.newPhoneNumber.substring(1);
                }

                if(this.userPhones.length !== 0) {
                    this.userPhones.forEach((item) => {
                        if(item.phone === this.newPhoneNumberShort) {
                            this.newPhoneNumberErrors.phone.hasErrors = true;
                            this.newPhoneNumberErrors.phone.errorText = 'Текущий телефон уже есть в вашем списке!';
                        }
                    });
                }

                if(!this.newPhoneNumberErrors.phone.hasErrors) {
                    this.newPhoneNumberSending = true;
                    let data = {
                        phone: this.newPhoneNumberShort
                    };
                    this.newPhoneNumberErrors.phone.errorText = '';

                    this.$http.post('\/' + bus.info.lang + '/add-phone', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                        //console.log('checkNewPhoneNumber: ', JSON.stringify(response, null, 4));

                        if (response.body.hasOwnProperty('errors')) {
                            this.newPhoneNumberErrors.phone.hasErrors = true;
                            this.newPhoneNumberErrors.phone.errorText = response.body.errors;
                            this.newPhoneNumberSending = false;
                        }

                        if (response.body.success === true) {
                            this.newPhoneNumberCodeForm = true;
                            this.newPhoneNumberSending = true;
                            this.newPhoneNumberCodeReSending = true;
                            this.newPhoneCodeSendAttempts++;
                            this.runNewPhoneCodeTimer();
                        }
                    }, response => {
                        alert('No connection with server')
                    });
                }
            },
            resendNewPhoneNumberCode(){
                if(!this.newPhoneNumberErrors.phone.hasErrors && !this.newPhoneNumberCodeReSending) {
                    this.newPhoneNumberErrors.code.hasErrors = false;
                    this.newPhoneNumberErrors.code.errorText = '';
                    this.newPhoneCode = '';
                    this.newPhoneNumberCodeSending = true;
                    this.newPhoneNumberCodeReSending = true;

                    let data = {
                        phone: this.newPhoneNumberShort
                    };

                    this.$http.post('\/' + bus.info.lang + '/resend-code', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                        console.log('/resend-code: ', JSON.stringify(response, null, 4));
                        this.newPhoneNumberCodeSending = false;
                        if(response.body.hasOwnProperty('errors')) {
                            this.newPhoneNumberErrors.code.hasErrors = true;
                            this.newPhoneNumberErrors.code.errorText = response.body.errors;
                            this.newPhoneCodeSendAttempts = 3;
                            clearInterval(this.newPhoneTimeout);
                        }

                        if(response.body.success === true) {
                            clearInterval(this.newPhoneTimeout);
                            this.newPhoneSecondsCounter = this.SMS_CODE_TIME;
                            ++this.newPhoneCodeSendAttempts;
                            if(this.newPhoneCodeSendAttempts < 3) {
                                this.runNewPhoneCodeTimer();
                            }
                        }
                    }, response => {
                        alert('No connection with server')
                    });
                }
            },
            sendNewPhoneNumberCode(){
                if(this.newPhoneCode.length === 4) {
                    this.newPhoneNumberCodeSending = true;
                }

                if(this.newPhoneNumberCodeSending){
                    let data = {
                        code: this.newPhoneCode
                    };

                    this.$http.post('\/' + bus.info.lang + '/confirm-code', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                        console.log(JSON.stringify(response, null, 4));
                        if(response.body.hasOwnProperty('errors')) {
                            this.newPhoneNumberErrors.code.hasErrors = true;
                            this.newPhoneNumberErrors.code.errorText = response.body.errors;
                            this.newPhoneNumberCodeSending = false;
                        }

                        if(response.body.success === true) {
                            this.userPhones = [];
                            this.userPhones = response.body.phones;
                            this.$emit('phones-list-changed', this.userPhones);

                            clearInterval(this.newPhoneTimeout);
                            this.resetNewPhoneNumberForm();
                        }
                    }, response => {
                        alert('No connection with server')
                    });
                }
            },
            deletePhoneNumber(){
                let phone = this.$parent.modal.phone;
                let data = {
                    id: phone.id,
                    phone: phone.phone
                };

                this.userPhones.forEach((item) => {
                    if(item.phone === phone.phone) {
                        this.userPhoneNumber = '';
                        this.$emit('phone-changed', this.userPhoneNumber);
                    }
                });

                this.$http.post('\/' + bus.info.lang + '/delete-phone', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                    console.log(JSON.stringify(response, null, 4));
                    if(response.body.success === true) {
                        this.$parent.modal.confirmPhoneRemoving = false;
                        this.$parent.modal.phone = {};

                        this.userPhones.forEach((item, index) => {
                            if(item.id === phone.id){
                                this.userPhones.splice(index, 1);
                                //this.$emit('phones-list-changed', this.userPhones);
                            }
                        });

                        if(this.userPhones.length === 0) {
                            this.newPhoneNumberForm = true;
                        }
                    }
                }, response => {
                    alert('No connection with server')
                });
            },
        },
        mounted() {
            this.setupPhones();
        }
    }
</script>

<style lang="less">
    .user-phones-component {
        margin: 25px 0 13px 0;
        .sc-profile_title {

        }
        .el-radio {
            cursor: default;
            &__label {
                padding-left: 0;
            }
        }
        .order-data_enter {
            .enter-reg_form {
                > div {
                    grid-column:1;
                    grid-row:1;
                    width: 100%;
                    padding-right: 0;
                    //padding-right: 30px;
                }
                &.ph-list {
                    margin-bottom: 15px;
                }
                &.phones {
                    >.input-wr {
                        &.tel {
                            grid-column:1;
                            grid-row:1;
                            margin-bottom: 0;
                        }
                    }
                    .enter-reg_form-confirm-sms {
                        grid-column:2 /span 2;
                        grid-row:1;
                        width: 100% !important;
                        //width: 50% !important;
                        margin-top: -30px;
                        .control-wrapper {
                            width: 58%;
                        }
                    }
                }
            }
        }
    }
    .sc-profile {
        &.client {
            .sc-profile_data {
                position: relative;
                margin-bottom: 0;
                .sc-profile_data--mainfoto {
                    position: relative;
                    .input-wr {
                        position: absolute;
                    }
                }
            }
            .user-phones-component {
                margin-top:20px;
            }
            .sc-profile_data--alert {
                margin-top: 45px;
            }
        }
    }
</style>