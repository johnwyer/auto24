'use strict';

import VueGoogleAutocomplete from './components/vue-google-autocomplete';
import VueRecaptcha from 'vue-recaptcha';
import Multiselect from 'vue-multiselect';
//import getAddress from './components/Getaddress.vue';

var googlePas = false;
var googlePas2 = false;
//import Clickoutside from 'element-ui/src/utils/clickoutside';

if (document.getElementById('page-login')) {
    window.loginRegisterVue = new Vue({
        el: '#page-login',
        performance: true,
        data: {
            modal: {
                auth: false,
                reg: false,
                choose: true,
                whoenter: false,
                cabinet: false
            },
            defaultCoord: [47.0481033, 28.861622],
            width: window.innerWidth,
            sitekey: '6Leo3S0UAAAAAPxKOHD_Lbi_1u2niJwoJs9ywp68',
            notifyList: {
                left: 0,
                show: false,
                data: window.notifications ? window.notifications : []
            },
            serverErrors: {
                'login': {
                    'notLoggedUser': {
                        hasError: false,
                        serverMessage: ""
                    }
                },
                'regService': {
                    'email': {
                        hasError: false,
                        serverMessage: ""
                    },
                    'name': {
                        hasError: false,
                        serverMessage: ""
                    },
                    'pass': {
                        hasError: false,
                        serverMessage: ""
                    },
                    'phone': {
                        hasError: false,
                        serverMessage: ""
                    }
                },
                'regUser': {
                    'email': {
                        hasError: false,
                        serverMessage: ""
                    },
                },
                'passwordReset': {
                    'emailPhone': {
                        hasError: false,
                        serverMessage: ""
                    }
                },
                'passwordResetStr': {
                    'password': {
                        hasError: false,
                        serverMessage: ""
                    }
                }
            },
            passwordReset: {
                progress: 1
            },
            passwordResetStr: {
                progress: 1
            },
            isLoading: {
                login: false,
                registerAutoservice: false,
                registerClient: false,
                passwordReset: false,
                passwordResetStr: false
            }
        },
        components: {
            VueGoogleAutocomplete,
            VueRecaptcha,
            Multiselect
        },
        methods: {
            chooseSlideIn(el) {
                let element = el;

                velocity(element, {
                    translateX: ['0%', '100%'],
                    zIndex: 10
                }, { duration: 1500 })
            },
            chooseSlideOut(el, done) {
                var el = el;
                velocity(el, {
                    translateX: ['100%', '0%'],
                }, { duration: 1500, complete: done });
            },
            chooseSlideIn2(el) {
                let element = el;

                velocity(element, {
                    translateX: ['0%', '-100%'],
                    zIndex: 9
                }, { duration: 1500 })
            },
            chooseSlideOut2(el, done) {
                var el = el;
                velocity(el, {
                    translateX: ['-100%', '0%'],
                }, { duration: 1500, complete: done });
            },
            hasServerErrors(where, what) {
                return this.serverErrors[where][what].hasError;
            },
            getServerErrors(where, what) {
                return this.serverErrors[where][what].serverMessage;
            },
            removeServerError(where, what) {
                console.log(where, what);
                this.serverErrors[where][what].hasError = false;
                this.serverErrors[where][what].serverMessage = '';
            },
            addServerError(where, what, message) {
                //console.log(where, what, message);
                this.serverErrors[where][what].hasError = true;
                this.serverErrors[where][what].serverMessage = message;
            },
            locationChange(url) {
                if (url.length != 0) {
                    window.location.href = url;
                }
            },
            goNotify(item) {
                // console.log(item)
                if (item.type === 4 || item.type === 3) {
                    this.$localStorage.set('goData', JSON.stringify(item));
                    if (difUser === 'client') {
                        window.location.href = '/dashboard';
                    } else {
                        window.location.href = '/personal';
                    }
                } else if (item.type === 1 && difUser === 'service') {
                    this.$localStorage.set('newOrder', JSON.stringify(item));
                    window.location.href = '/personal';
                } else if (item.type === 2 && difUser === 'client') {
                    this.$localStorage.set('newPrice', JSON.stringify(item));
                    window.location.href = '/dashboard';
                }
            },
            onVerifyA(response) {
                googlePas = true;
                this.removeServerError('regUser', 'email');

                var form = document.getElementsByName('reguser')[0];
                var data = {};
                data['email'] = form.reg_email.value;
                data['name'] = form.reg_name.value;
                data['pass'] = form.reg_pas.value;

                let location = '/dashboard';
                if (bus.info.lang === 'ru') {
                    location = '/' + bus.info.lang + location;
                }

                this.$http.post('\/' + bus.info.lang + '/user-registration', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    //console.log(response.data);
                    // this.$refs.invisibleRecaptchaA.reset();
                    if (response.data.hasOwnProperty('success')) {
                        window.location.href = location;
                    } else {
                        if (response.data.hasOwnProperty('email')) {
                            this.addServerError('regUser', 'email', response.data.email[0]);
                        }

                        this.isLoading.registerClient = false;
                    }
                }, response => {
                    alert('No connection with server');
                    this.isLoading.registerClient = false;
                });
            },
            onVerifyB(response) {
                googlePas2 = true;

                this.removeServerError('regService', 'email');
                this.removeServerError('regService', 'phone');

                var form = document.getElementsByName('regService')[0];
                let data = {};

                data.email = form.serv_email.value;
                data.name = form.serv_name.value;
                data.phone = form.serv_tel.value;

                if (data.phone.length === 9) {
                    data.phone = data.phone.substring(1);
                }
                data.pass = form.serv_pas.value;
                data['cord_x'] = loginRegisterVue.defaultCoord[0];
                data['cord_y'] = loginRegisterVue.defaultCoord[1];
                data.official = 0;

                if (document.getElementById('official_dealer').checked) {
                    data.official = 1;
                }

                let location = '/personal';
                if (bus.info.lang === 'ru') {
                    location = '/' + bus.info.lang + location;
                }
                this.$http.post('\/' + bus.info.lang + '/auto-registration', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    if (response.data.hasOwnProperty('success')) {
                        window.location.href = location;
                    } else {
                        if (response.data.hasOwnProperty('phone')) {
                            //console.log('phone-error ', response.data.phone);
                            this.addServerError('regService', 'phone', response.data.phone[0]);
                        }
                        if (response.data.hasOwnProperty('email')) {
                            //console.log('phone-email ', response.data.email);
                            this.addServerError('regService', 'email', response.data.email[0]);
                        }
                        this.isLoading.registerAutoservice = false;
                    }

                }, response => {
                    alert('No connection with server');
                    this.isLoading.registerAutoservice = false;
                });
            },
            removeError(name, scope) {
                this.errors.remove(name, scope);
            },
            validateEmail(email) {
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            },
            validatePhone(phone) {
                let re = /\d/g;
                if (re.test(phone)) {
                    if (phone.length < 8 || phone.length > 9) {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            },
            resetPassword(event) {
                if (!this.isLoading.passwordReset) {
                    this.isLoading.passwordReset = true;
                    let form = event.currentTarget;
                    let data = {};
                    data.login = form.email_phone.value;
                    let isValid = false;
                    let that = this;

                    this.removeServerError('passwordReset', 'emailPhone');

                    this.$validator.validateAll().then((result) => {
                        //console.log('Validate: ', result);
                        if (result) {
                            isValid = this.validateEmail(data.login);
                            if (!isValid) {
                                isValid = this.validatePhone(data.login);
                            }

                            //console.log('Validate isValid: ',isValid);
                            if (isValid) {
                                this.$http.post('\/' + bus.info.lang + '/user/reset', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                                    //console.log(response.data);

                                    if (response.data.success) {
                                        this.passwordReset.progress = 2;
                                    }

                                    if (response.data.hasOwnProperty('errors')) {
                                        this.addServerError('passwordReset', 'emailPhone', response.data.errors);
                                    }

                                    this.isLoading.passwordReset = false;
                                }, response => {
                                    alert('No connection with server')
                                });
                            } else {
                                this.addServerError('passwordReset', 'emailPhone', 'Неверный емэйл или номер телефона');
                                this.isLoadingpasswordReset = false;
                            }
                        } else {
                            this.isLoading.passwordReset = false;
                        }
                        this.$validator.validateAll();
                    }).catch(() => {

                    });
                }
            },
            validatePasswords(password, re_password) {
                if (password !== re_password) {
                    return false
                }
                return true;
            },
            resetPasswordStr(event) {
                if (!this.isLoading.passwordResetStr) {
                    this.isLoading.passwordResetStr = true;
                    let form = event.currentTarget;
                    let data = {};
                    data.password = form.new_password.value;
                    data.re_password = form.re_new_password.value;
                    let isValid = false;

                    this.removeServerError('passwordResetStr', 'password');

                    this.$validator.validateAll().then((result) => {
                        console.log('Validate: ', result);
                        if (result) {
                            isValid = this.validatePasswords(data.password, data.re_password);

                            console.log('Validate isValid: ', isValid);
                            if (isValid) {
                                form.submit();
                            } else {
                                this.isLoading.passwordResetStr = false;
                                this.addServerError('passwordResetStr', 'password', 'Пароли не совпадают');
                            }
                        } else {
                            this.isLoading.passwordResetStr = false;
                        }
                        this.$validator.validateAll();
                    }).catch(() => {

                    });
                }
            },
            enterValidate(event) {
                if (!this.isLoading.login) {
                    let form = event.currentTarget;
                    this.isLoading.login = true;

                    this.removeServerError('login', 'notLoggedUser');

                    this.$validator.validateAll().then((result) => {
                        if (result) {
                            let data = {};
                            bus.user.pas = form.enter_pas.value;
                            bus.user.name = form.enter_email.value;
                            data['login'] = form.enter_email.value;
                            data['pass'] = form.enter_pas.value;
                            this.$http.post('\/' + bus.info.lang + '/user-login', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                                if (response.data.success !== undefined) {
                                    let location = '/';
                                    if (bus.info.lang === 'ru') {
                                        location += bus.info.lang;
                                    }
                                    window.location.href = location;
                                } else {
                                    //console.log(response.data);
                                    this.addServerError('login', 'notLoggedUser', response.data.error);
                                    this.isLoading.login = false;
                                }
                            }, response => {
                                alert('No connection with server')
                            });
                        } else {
                            this.isLoading.login = false;
                        }
                        this.$validator.validateAll();
                    }).catch(() => {

                    });
                }
            },
            reguser(event, scope) {
                if (!this.isLoading.registerClient) {
                    console.log('reg-user');
                    this.isLoading.registerClient = true;
                    var form = event.currentTarget;

                    this.$validator.validateAll(scope).then((result) => {
                        if (result) {
                            loginRegisterVue.onVerifyA();
                        } else {
                            this.isLoading.registerClient = false;
                        }
                        this.$validator.validateAll(scope);
                    }).catch(() => {
                        this.isLoading.registerClient = false;
                    });
                }
            },
            regService(event, scope) {
                if (!this.isLoading.registerAutoservice) {
                    this.isLoading.registerAutoservice = true;
                    //console.log('reg-service');
                    var form = event.currentTarget;

                    this.$validator.validateAll(scope).then((result) => {
                        if (result) {
                            loginRegisterVue.onVerifyB();
                        } else {
                            this.isLoading.registerAutoservice = false;
                        }
                        this.$validator.validateAll(scope);
                    }).catch(() => {
                        //console.log('regService-false');
                        this.isLoading.registerAutoservice = false;
                    });
                }
            },
            locationchanged(data) {
                this.selectedAddress = data;
            },
        },
        directives: {
            //Clickoutside
        },
        computed: {
            /*
            notify() {
                return parseInt(bus.notify);
            },
            */
        },
        watch: {

        },
        mounted() {

        }
    });
}