'use strict';
//import VueGoogleAutocomplete from './components/vue-google-autocomplete';
//import VueRecaptcha  from 'vue-recaptcha';
//import Multiselect from 'vue-multiselect';
//import getAddress from './components/Getaddress.vue';

//import vClickOutside from 'v-click-outside';
//Vue.use(vClickOutside);

import { Scrollbar, Loading } from "element-ui";
window.Vue.use(Scrollbar);
window.bus = new Vue({
    data: {
        info: {
            key: Laravel.csrfToken,
            lang: Laravel.lang,
            log: Laravel.log
        },
        user: {
            name: '',
            pas: ''
        },
        socketData: ''
    },
    watch: {
        socketData(val) {
            //console.log(val);
            //console.log('bus, socket data: ', JSON.stringify(val, null, 4));
            if (val.content) {
                console.log('bus.content');
                console.log(JSON.stringify(val, null, 4));
                if (val.content.message) {
                    if (document.getElementById('serviceCabinet')) {
                        //if(serviceCabinet.autoserviceId !== ""){
                        val.content.message.order_id = parseInt(val.content.message.order_id);
                        val.content.propun_price = parseInt(val.content.propun_price);
                        val.content.propun_parts = parseInt(val.content.propun_parts);
                        serviceCabinet.socket.message = val.content;
                        //}
                    }
                }
                if (val.content.new_order) {
                    if (document.getElementById('serviceCabinet')) {
                        serviceCabinet.socket.order = val.content.new_order;
                    }
                }
                if (val.content.archive_order) {
                    if (document.getElementById('serviceCabinet')) {
                        serviceCabinet.socket.archive_order = val.content.archive_order;
                    }
                }
                if (val.content.auto) {
                    if (document.getElementById('serviceCabinet')) {
                        serviceCabinet.socket.autoservice = val.content.auto;
                    }
                }
            } else if (val.notification) {
                console.log('bus.notification');
                //console.log(val.notification)
                if (document.getElementById('serviceCabinet')) {
                    if (serviceCabinet.autoserviceId === val.notification.autoservice_id) {
                        console.log('client in chat');
                        val.notification.show = 1;
                        this.pushNotify(val.notification);
                        // client in chat with autoservice
                    } else if (val.notification.order_id === serviceCabinet.orderPageInfo.num && difUser === 'service') {
                        console.log('autoservice in chat');
                        // autoservice in chat
                        this.pushNotify(val.notification);
                    } else if (val.notification.order_id === serviceCabinet.orderPageInfo.num && difUser === 'client') {
                        this.pushNotify(val.notification);
                        this.serviceNotify(val.notification);
                    } else {
                        this.pushNotify(val.notification);
                    }
                } else {
                    console.log('not in service cabinet');
                    this.pushNotify(val.notification);
                }
            }
        }
    },
    methods: {
        pushNotify(item) {
            let permissionToNotify = true;
            headerVue.notificationsLoading = true;

            item.order_id = parseInt(item.order_id);
            item.autoservice_id = parseInt(item.autoservice_id);
            //console.log('item.autoservice_id: ', item.autoservice_id);
            //console.log('condition: ', Number.isNaN(item.autoservice_id) && difUser === 'service');
            //console.log('Number.isNaN: ', Number.isNaN(item.autoservice_id));
            if (Number.isNaN(item.autoservice_id) && difUser === 'service') {
                item.autoservice_id = parseInt(window.info.autoservice_id);
            }
            //console.log('item.autoservice_id: ', item.autoservice_id);
            console.log('new notify: ', JSON.stringify(item, null, 4));

            if (document.getElementById('serviceCabinet')) {
                serviceCabinet.requestToUpdateOrderMessagesCounter(item);
            }

            headerVue.notifyList.data.forEach((note, index) => {
                //console.log(index, ' note item: ', JSON.stringify(note, null, 4));
                //console.log(index, ' note item: ', note.order_id);
                //console.log(note.autoservice_id !== item.autoservice_id && note.order_id === item.order_id && note.type === item.type && note.show === item.show);
                //console.log(typeof note.type, ' - ', note.type);
                //console.log(typeof item.type, ' - ', item.type);

                if (difUser === 'client') {
                    if (document.getElementById('serviceCabinet')) {
                        if (serviceCabinet.autoserviceId === item.autoservice_id && note.order_id === item.order_id && note.type === item.type) {
                            headerVue.notifyMarkRead(item.order_id, item.autoservice_id);
                            headerVue.notifyList.data.splice(index, 1);
                        } else if (note.order_id === item.order_id && note.type === item.type) { //&& note.show === item.show
                            headerVue.notifyList.data.splice(index, 1);
                        }
                    } else {
                        if (note.order_id === item.order_id && note.type === item.type) {
                            headerVue.notifyList.data.splice(index, 1);
                        }
                    }
                }

                if (difUser === 'service') {
                    if (note.autoservice_id === item.autoservice_id && note.order_id === item.order_id && note.type === item.type) {
                        if (document.getElementById('serviceCabinet')) {
                            if (serviceCabinet.isChatActive) {
                                item.show = 1;
                                if (note.order_id !== serviceCabinet.orderId) {
                                    item.show = null;
                                }
                                if (item.show === 1) {
                                    headerVue.notifyMarkRead(item.order_id);
                                }
                            }
                        } else {
                            item.show = null;
                        }
                        headerVue.notifyList.data.splice(index, 1);
                        headerVue.notifyList.data.unshift(item);
                        permissionToNotify = false;
                    }
                }
            });
            if (permissionToNotify) {
                //console.log('intra in permissionToNotify');
                if (document.getElementById('serviceCabinet') && difUser === 'service') {
                    if (serviceCabinet.isChatActive && item.type === 2 && serviceCabinet.clientId === item.autoservice_id && item.order_id === serviceCabinet.orderPageInfo.num) {
                        //console.log('autoservice in chat and get new zapisi na remont');
                        item.show = 1;
                        headerVue.notifyMarkRead(item.order_id);
                    }
                } else {
                    //  item.show = null;
                }

                headerVue.notifyList.data.unshift(item);
                headerVue.notificationsLoading = false;
            } else {
                headerVue.notificationsLoading = false;
            }
        },
        serviceNotify(note) {
            let el = serviceCabinet.chat;
            for (let i = 0; i < el.services.length; i++) {
                if (parseInt(el.services[i].auto_id) === note.autoservice_id) {
                    let count = parseInt(el.services[i].message);
                    count++;
                    serviceCabinet.chat.services[i].message = count;
                }
            }
        },
        checkNotify() {

        }
    }
});

Vue.component('modal', {
    template: `<transition name="modal">
   <div class="modal-mask" @click="$emit(\'close\')">
      <div class="modal-wrapper">
         <div class="modal-container">
            <div class="modal-close" @click="$emit(\'close\')">
               <svg width="20" version="1.1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
                  <g>
                     <path  d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                  </g>
               </svg>
            </div>
            <div class="modal-body" @click.stop>
               <slot></slot>
            </div>
         </div>
      </div>
   </div>
</transition>`
});

//var googlePas = false;
//var googlePas2 = false;
//import Clickoutside from 'element-ui/src/utils/clickoutside';
window.headerVue = new Vue({
    el: '#header',
    //performance: true,
    data: {
        modal: {
            auth: false,
            reg: false,
            choose: true,
            whoenter: false,
            cabinet: false
        },
        notificationsLoading: false,
        getCoord: false,
        defaultCoord: [47.0481033, 28.861622],
        mapsCoord: [],
        mapsTitle: '',
        width: window.innerWidth,
        sitekey: '6Leo3S0UAAAAAPxKOHD_Lbi_1u2niJwoJs9ywp68',
        notifyList: {
            left: 0,
            show: false,
            data: window.Laravel.notifications ? window.Laravel.notifications : []
                //data:window.notifications ? window.notifications : []
        },
        userName: '',
        userBalanceAmount: null
    },
    components: {},
    methods: {
        locationChange(url) {
            if (url.length != 0) {
                window.location.href = url;
            }
        },
        getLocationPrefix() {
            let locationPrefix = '';
            if (bus.info.lang !== 'ro') {
                locationPrefix = '/' + bus.info.lang;
            }

            return locationPrefix;
        },
        goNotify(item) {
            console.log('goNotify(): ', JSON.stringify(item, null, 4));

            if ((item.type >= 3 && item.type <= 7) || item.type === null) {
                this.$localStorage.set('goData', JSON.stringify(item));
                if (difUser === 'client') {
                    window.location.href = this.getLocationPrefix() + '/dashboard?orders';
                } else {
                    window.location.href = this.getLocationPrefix() + '/personal?orders';
                }
            } else if (item.type === 1) {
                if (difUser === 'service') {
                    this.$localStorage.set('newOrder', JSON.stringify(item));
                    window.location.href = this.getLocationPrefix() + '/personal?orders';
                } else {
                    this.$localStorage.set('goData', JSON.stringify(item));
                    window.location.href = this.getLocationPrefix() + '/dashboard?orders';
                }
            } else if (item.type === 2) {
                if (difUser === 'client') {
                    this.$localStorage.set('newPrice', JSON.stringify(item));
                    window.location.href = this.getLocationPrefix() + '/dashboard?order';
                } else {
                    this.$localStorage.set('goData', JSON.stringify(item));
                    window.location.href = this.getLocationPrefix() + '/personal?orders';
                }
            } else if (item.type === 8) {
                let that = this;
                let data = {
                    id: item.id
                };

                this.$http.post('mark-read', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    that.notifyList.data.forEach((listItem) => {
                        if (listItem.id === item.id) {
                            item.show = 1;
                        }
                    });
                }, response => {
                    alert('No connection with server');
                });
            }
        },
        notifyPosition() {
            if (document.querySelector('.header-auth_bell')) {
                let el = document.querySelector('.header-auth_bell');
                let l = el.offsetLeft;
                let o = 125; /// offset
                //console.log(l);
                this.notifyList.left = l - o + 'px';
            }
        },
        calcualteUsernameBlock() {
            if (document.getElementById('header-user-name')) {
                let el = document.getElementById('header-user-name');
                let width = el.getBoundingClientRect().width;

                if (width > 120) {
                    el.classList.add('with-gradient');
                }
            }
        },
        updateNotifyList(orderId) {
            if (orderId !== undefined) {
                this.notifyList.data.forEach((item) => {
                    if (item.order_id === orderId) {
                        item.show = 1;
                    }
                });
            } else {
                this.notifyList.data.forEach((item) => {
                    if (item.show === null) {
                        item.show = 1;
                    }
                });
            }
        },
        notifyMarkReadAll() {
            //let _self = this;
            let data = {};
            let orderId;

            this.$http.post('mark-read', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                this.updateNotifyList(orderId);
            }, response => {
                alert('No connection with server');
            });
        },
        notifyMarkRead(orderId, autoserviceId = null) {
            let _self = this;
            let data = {};

            if (orderId !== undefined) {
                data.order_id = orderId;
            }

            if (autoserviceId) {
                data.autoservice_id = autoserviceId;
            }

            this.$http.post('mark-read-message', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {}, response => {
                alert('No connection with server');
            });

            this.$http.post('mark-read', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                _self.updateNotifyList(orderId);
            }, response => {
                alert('No connection with server');
            });
        },
        initWallet() {
            if (this.userBalanceAmount === null) {
                this.userBalanceAmount = window.Laravel.wallet;
            }
        }
    },
    directives: {},
    computed: {
        getUserName() {
            if (window.Laravel.user.hasOwnProperty('name')) {
                this.userName = window.Laravel.user.name;
            }

            return this.userName;
        },
        getNotificationsCount() {
            if (this.notifyList.data.length === 0) {
                this.notifyList.show = false;
                return false;
            }

            return true;
        },
        getNewNotificationsCount() {
            let counter = 0;
            this.notifyList.data.forEach((item) => {
                if (item.show === null) {
                    counter++;
                }
            });

            return counter;
        }
    },
    watch: {},
    mounted() {
        var el = document.querySelector('.header-button');
        el.addEventListener('click', animateMenuOpen);
        var el = document.querySelector('.header-menu_lang--close');
        el.addEventListener('click', animateMenuClose);
        window.addEventListener('resize', removeStyle, true);
        this.popupItem = this.$el;
        this.notifyPosition();
        this.calcualteUsernameBlock();
        window.addEventListener(
            'resize',
            () => {
                this.notifyPosition();
                this.calcualteUsernameBlock();
            },
            true
        );

        this.initWallet();
        //let _self = this;
        //window.addEventListener('resize', function(){ _self.notifyPosition() }, true);
        // console.log(document.querySelector('.el-select').getBoundingClientRect().width)
    }
});

function animateMenuOpen() {
    var el = document.querySelector('.header-menu');
    velocity(el, {
        translateX: ['0%', '-100%'],
    }, { duration: 200 });
    var body = document.getElementsByTagName('body')[0];
    body.classList.add('scr-no');
}

function animateMenuClose() {
    var el = document.querySelector('.header-menu');
    velocity(el, {
        translateX: ['-100%', '0%'],
    }, { duration: 200 });
    var body = document.getElementsByTagName('body')[0];
    body.classList.remove('scr-no');
}

function removeStyle() {
    headerVue.width = window.innerWidth;
    var el = document.querySelector('.header-menu');
    el.style.removeProperty('transform');
    var body = document.getElementsByTagName('body')[0];
    body.classList.remove('scr-no');
}