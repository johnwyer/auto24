'use strict';
import { Rate, Carousel, Select, Loading } from 'element-ui';
import orderFind from './components/autoservice-card-order-find';

import VueI18n from 'vue-i18n';
window.Vue.use(VueI18n);
var i18n = new VueI18n({
    locale: bus.info.lang,
    messages: window.appMessages
});

Vue.filter('chatDate', function(value) {
    let date = moment(value, "YYYY-MM-DD HH:mm:ss").format("DD.MM.YYYY HH:mm");
    return date;
});

if (document.getElementById('card')) {
    var bindCords;
    window.card = new Vue({
        el: '#card',
        i18n,
        data: {
            showTel: false,
            isScheduleOpened: false,
            info: {},
            selectedService: '',
            review: window.review ? window.review : [],
            totalReview: window.totalReview ? window.totalReview : 0,
            isActviveDay: window.isActviveDay ? window.isActviveDay : 0,
            priceList: window.priceList ? window.priceList : [],
            serviceSelected: [],
            cords: {},
            commentNumber: 2,
            priceListBlock: `
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 303.862 303.862" style="enable-background:new 0 0 303.862 303.862;" xml:space="preserve">
                <g>
                    <path d="M282.973,121.195h-11.038v-20.662c0-8.506-6.896-15.402-15.402-15.402h-52.54c-8.506,0-15.402,6.896-15.402,15.402v20.662
                        H87.843v-7.93c0-8.506-6.896-15.402-15.402-15.402h-22.54c-8.506,0-15.402,6.897-15.402,15.402v7.93H20.889
                        c-9.918,0-17.958,8.04-17.958,17.959v146.75c0,9.918,8.04,17.958,17.958,17.958h262.084c9.918,0,17.958-8.04,17.958-17.958v-146.75
                        C300.931,129.235,292.891,121.195,282.973,121.195z M151.932,266.933c-30.047,0-54.404-24.357-54.404-54.403
                        c0-30.046,24.358-54.403,54.404-54.403c30.046,0,54.403,24.357,54.403,54.403C206.335,242.576,181.978,266.933,151.932,266.933z"/>
                    <path d="M28.09,73.353c3.599,0,6.756-2.4,7.719-5.868l3.515-12.66h20.664L63.769,67.4c1.062,3.533,4.316,5.952,8.006,5.952
                        c2.655,0,5.153-1.262,6.729-3.4c1.575-2.139,2.041-4.897,1.254-7.435L62.562,7.062c-1.085-3.499-4.321-5.884-7.984-5.884H45.51
                        c-3.676,0-6.92,2.401-7.994,5.916L20.429,63c-0.742,2.429-0.289,5.065,1.222,7.107C23.162,72.148,25.55,73.353,28.09,73.353z
                         M45.958,27.308c1.178-4.176,2.25-9.634,3.322-13.915h0.209c1.072,4.281,2.363,9.634,3.645,13.915l4.499,15.31H41.678
                        L45.958,27.308z"/>
                    <path d="M117.846,74.529c18.85,0,30.089-10.602,30.089-32.87V9.316c0-4.495-3.644-8.139-8.139-8.139
                        c-4.496,0-8.14,3.643-8.14,8.139v33.415c0,12.738-4.708,18.737-13.27,18.737c-8.353,0-13.069-6.321-13.069-18.737V9.369
                        c0-4.524-3.667-8.191-8.191-8.191s-8.191,3.667-8.191,8.191v32.072C88.934,64.468,99.641,74.529,117.846,74.529z"/>
                    <path d="M163.498,14.884h12.529v50.277c0,4.524,3.667,8.191,8.191,8.191s8.191-3.667,8.191-8.191V14.884h12.852
                        c3.785,0,6.854-3.068,6.854-6.852c0-3.785-3.068-6.853-6.854-6.853h-41.764c-3.784,0-6.853,3.068-6.853,6.853
                        C156.645,11.816,159.713,14.884,163.498,14.884z"/>
                    <path d="M248.341,74.529c21.091,0,35.442-14.351,35.442-38.015C283.784,16.601,271.682,0,249.518,0
                        c-21.309,0-35.119,16.174-35.119,37.806C214.399,58.364,226.928,74.529,248.341,74.529z M249.091,12.956
                        c11.238,0,17.455,11.144,17.455,24.1c0,14.02-6.321,24.518-17.351,24.518c-10.916,0-17.56-9.957-17.56-24.091
                        C231.636,23.454,238.062,12.956,249.091,12.956z"/>
                </g>
            </svg>
        `,
            showMoreMarks: true,
            commentsLoading: false
        },
        watch: {
            selectedService(val) {
                let id = val.split(',');
                id.forEach((item, index) => {
                    id[index] = parseInt(item);
                });
                this.priceList.forEach(item => {
                    if (item.id === parseInt(id[0])) {
                        let atr = 'li' + id[0];
                        let el = this.$refs[atr][0];
                        if (!el.classList.contains('active')) {
                            eventFire(el, 'click');
                        }
                        let num = id[1] + '_' + id[0];
                        this.$refs[num][0].focus();
                    }
                })
            }
        },
        components: {
            Rate,
            Carousel,
            findSelect: Select,
            serviceFind: orderFind
        },
        methods: {
            showSchedule() {
                this.isScheduleOpened = !this.isScheduleOpened;
            },
            showMarks() {
                var elements = document.getElementById("marks-list").getElementsByClassName("marka-block");
                Array.prototype.forEach.call(elements, item => {
                    if (item.classList.contains('hidden')) {
                        item.classList.remove('hidden');
                    }
                });
                this.showMoreMarks = false;
            },
            showInner(event) {
                let _event = event || window.event;
                let ul = _event.currentTarget.parentNode;
                let currentLi = _event.currentTarget;
                let arrayLi = Array.prototype.filter.call(currentLi.parentNode.children, function(child) {
                    return child !== currentLi;
                });
                Array.prototype.forEach.call(arrayLi, item => {
                    item.classList.remove('active');
                    item.style.marginBottom = '';
                });
                if (currentLi.classList.contains('active')) {
                    currentLi.classList.remove('active');
                    currentLi.style.marginBottom = '';
                } else {
                    currentLi.classList.add('active');
                    let h = window.getComputedStyle(currentLi, null).getPropertyValue("height");
                    if (currentLi.querySelector('.inner')) {
                        let inner = currentLi.querySelector('.inner');
                        inner.style.marginTop = h;
                        let ih = window.getComputedStyle(inner, null).getPropertyValue("height");
                        currentLi.style.marginBottom = parseInt(ih) + 25 + 'px';
                    }
                }
            },
            addReview() {
                let ls = Loading.service({ target: document.querySelector('.card-review_wrapper') });
                let data = {
                    num: window.perPage.comment,
                    auto_id: window.asId,
                    page: this.commentNumber,
                };
                this.$http.post('\/' + bus.info.lang + '/get-comment', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    ls.close();
                    response.data.comment.forEach(item => {
                        this.review.push(item);
                    });
                    this.totalReview = this.totalReview - response.data.comment.length;
                    this.commentNumber++;
                }, response => {
                    ls.close();
                    alert('No connection with server')
                });
            },
            goToReview(count) {
                if (count > 0) {
                    let el = document.getElementById('card-review');
                    let bodyRect = document.body.getBoundingClientRect(),
                        elemRect = el.getBoundingClientRect(),
                        offset = elemRect.top - bodyRect.top;
                    velocity(document.body, 'scroll', { duration: 750, offset: offset });
                }
            },
            goMap() {
                let el = document.getElementById('card-map');
                let bodyRect = document.body.getBoundingClientRect(),
                    elemRect = el.getBoundingClientRect(),
                    offset = elemRect.top - bodyRect.top;
                velocity(document.body, 'scroll', { duration: 750, offset: offset });
            },
            returnText(num, text) {
                return wordend(num, text);
            }
        },
        computed: {
            height() {
                return '500px'
            }
        },
        directives: {
            cord: {
                bind(el, binding, vnode) {
                    var data = binding.value;
                    bindCords = data;
                }
            }
        },
        mounted() {
            WhenGoogleLoaded(cardMap);
            (function() {
                let el = document.querySelector('.card-info_grid');
                let title = document.querySelector('.card-around_wrapper');
                if (title !== null) {
                    let offset = el.getBoundingClientRect().top - title.getBoundingClientRect().top;
                    title.style.paddingTop = offset + 'px';
                }
            })();
        }
    });
}

function wordend(num, words) {
    return words[((num = Math.abs(num % 100)) > 10 && num < 15 || (num %= 10) > 4 || num === 0) + (num !== 1)];
}

function cardMap() {
    var map = new google.maps.Map(document.getElementById('card-map'), {
        center: bindCords,
        zoom: 14,
    });
    var marker = new google.maps.Marker({
        position: bindCords,
        map: map,
        icon: '/img/placeholder-filled-point.png'
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });

    var infoData = {};
    infoData['title'] = document.getElementsByTagName('h1')[0].textContent;
    infoData['adr'] = document.querySelector('.card-info_adr .adr span').textContent;
    infoData['rate'] = document.querySelector('.card-info_grid .rate').cloneNode(true).innerHTML;
    infoData['img'] = `<img src="${autoImage}"/>`;
    infoData['str'] = `
                        <div class="card-map_inner">
                            <div class="img">${infoData.img}</div>
                            <div class="text">
                                <h3>${infoData.title}</h3>
                                <div class="text-adr">${infoData.adr}</div>
                                <div class="text-rate">${infoData.rate}</div>
                            </div>
                        </div>
                        `;
    var infowindow = new google.maps.InfoWindow({
        content: infoData.str,
    });
    infowindow.open(map, marker);
}

function WhenGoogleLoaded(fnt) {
    if (typeof google != 'undefined') {
        fnt();
    } else {
        setTimeout(function() {
            (function(fnt) {
                WhenGoogleLoaded(fnt);
            })(fnt)
        }, 500);
    }
}

function eventFire(el, etype) {
    if (el.fireEvent) {
        el.fireEvent('on' + etype);
    } else {
        let evObj = document.createEvent('Events');
        evObj.initEvent(etype, true, false);
        el.dispatchEvent(evObj);
    }
}