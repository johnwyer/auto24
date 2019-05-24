'use strict';

import { Scrollbar, Select, Loading } from 'element-ui';
import orderFind from './components/order_find';
import uploadFoto from './components/upload-main';

window.Vue.use(Scrollbar);

if (document.getElementById('home')) {
    window.home = new Vue({
        el: '#home',
        data: {
            checkedServices: [],
            checkedServicesCounter: [],
            checkedPrice: 0,
            otherServices: {
                progress: false,
                price: 0,
                message: '',
                messageLimit: 360,
                imagesLimit: 5,
                images: [],
                imagesThumbs: [],
                imagesLinks: []
            },
            checkedServicesAuto: [],
            checkedServicesAutoByUser: [],
            allMarkers: [],
            info: window.inf.data ? window.inf.data : {},
            autoImageDefault: '/img/elevator.svg',
            choose: {
                service: {

                },
                main: false,
                auto: false,
                progress: 1,
                mycars: window.inf.data.myCar ? window.inf.data.myCar : [],
                addcar: true
            },
            priceList: window.inf.data.services ? window.inf.data.services : [],
            serviceSelected: [],
            markersOnMap: [],
            returnedMarks: window.inf.data.mark ? window.inf.data.mark : [],
            returnedModels: '',
            allModels: '',
            allMarks: window.inf.data.mark ? window.inf.data.mark : [],
            selectedMarka: '',
            selectedMarkaImage: '',
            selectedMarkaName: '',
            selectedModelName: '',
            selectedModelId: '',
            selectedYear: '',
            selectedYearId: '',
            selectedIdMyCar: '',
            VIN: '',
            VINtext: '',
            getMarka: '',
            getModel: '',
            allDate: '',
            selectedAddress: {
                district: '',
                city: ''
            },
            autoservicesLoading: false
        },
        watch: {
            serviceSelected(val) {
                this.openService(val);
            },
            'info.auto' (val) {
                if (!val.length) {
                    this.some.noDataFound = true;
                } else {
                    this.some.noDataFound = false;
                }
            },
            'sort.type.of' (val) {
                this.updateAuto(false);
            },
            'sort.type.universal' (val) {
                this.updateAuto(false);
            },
            getMarka(val) {
                if (toString(val).length) {
                    this.returnedMarks = this.allMarks.filter(item => {
                        let a = item.name_ru.toLowerCase();
                        let b = val.toLowerCase();
                        if (a.indexOf(b) !== -1) {
                            return true;
                        }
                    })
                }
            },
            getModel(val) {
                if (toString(val).length) {
                    this.returnedModels = this.allModels.filter(item => {
                        let a = item.name.toLowerCase();
                        let b = val.toLowerCase();
                        if (a.indexOf(b) !== -1) {
                            return true;
                        }
                    })
                }
            },
            'checkedServices' (val) {
                document.activeElement.blur();
                this.updateServicesCounter();
            },
            'otherServicesImage' (val) {
                if (val === '') {
                    this.$refs.uploadfoto.value = '';
                }
            },
            'otherServices.message' () {
                (this.otherServices.message.length > 0 || this.otherServices.imagesThumbs.length > 0) ? this.checkedServicesCounter[0] = 1: this.checkedServicesCounter[0] = 0;
            },
            'otherServices.imagesThumbs' () {
                (this.otherServices.message.length > 0 || this.otherServices.imagesThumbs.length > 0) ? this.checkedServicesCounter[0] = 1: this.checkedServicesCounter[0] = 0;
            },
            'choose.auto' (val) {
                this.preparePageForModal(val);
            },
            'choose.main' (val) {
                this.preparePageForModal(val);
            }
        },
        mixins: [
            require("vue-mixins/onWindowResize"),
        ],
        computed: {
            servicesInInput() {
                var arr = [];
                this.checkedServices.forEach(item => {
                    arr.push(item['name_' + bus.info.lang]);
                });

                if (this.otherServices.message.length || this.otherServices.imagesThumbs.length) {
                    arr.push('Другая услуга');
                }

                arr = arr.join(", ");
                return arr;
            },
            autoInInput() {
                let auto = this.selectedMarkaName;

                if (this.selectedModelName.length) {
                    auto += ', ' + this.selectedModelName;

                    if (this.selectedYear !== '') {
                        auto += ', ' + this.selectedYear;
                    }
                    if (this.VINtext.length) {
                        auto += ', ' + this.VINtext;
                    }
                }

                return auto;
            },
            chs() {
                var ar = [];
                this.checkedServices.forEach(item => {
                    if (item['service_id']) {
                        ar.push(item['service_id']);
                    }
                });

                return ar;
            },
            allMarkaComputed() {
                return this.returnedMarks;
            },
            allModelsComputed() {
                return this.returnedModels;
            }
        },
        components: {
            Select,
            Loading,
            serviceFind: orderFind,
            uploadFoto,
            carSelectModal: require('./components/home/modal.vue'),
            autoserviceSelectModal: require('./components/home/modal.vue'),
            howItWorks: require('./components/home/how-it-works.vue')
        },
        methods: {
            preparePageForModal(val) {
                //let body = document.getElementsByTagName('body')[0];
                let body = document.body;
                if (val) {
                    body.classList.add('modal-open');
                } else {
                    body.classList.remove('modal-open');
                }
            },
            getServicesInInput() {
                let counter = 0;
                if (this.checkedServices.length) {
                    counter = this.checkedServices.length;
                }
                if (this.otherServices.message.length || this.otherServices.imagesThumbs.length) {
                    counter++;
                }

                return counter;
            },
            checkOtherServicesMessage(e, remove) {
                if (this.otherServices.message.length >= this.otherServices.messageLimit) {
                    if (remove === 'true') {
                        this.otherServices.message = this.otherServices.message.substring(0, this.otherServices.messageLimit - 1);
                    } else {
                        e.preventDefault();
                        this.otherServices.message = this.otherServices.message.substring(0, this.otherServices.messageLimit);
                    }
                }
            },
            otherServicesMessageCounter() {
                if (this.otherServices.messageLimit - this.otherServices.message.length < 0) {
                    return 0;
                } else {
                    return (this.otherServices.messageLimit - this.otherServices.message.length);
                }
            },
            setServicesCounter() {
                //console.log('setServiceCounter()');
                this.priceList.forEach((item, index) => {
                    this.checkedServicesCounter[item.id] = parseInt(0);
                });
                this.checkedServicesCounter[0] = parseInt(0);
            },
            updateServicesCounter() {
                this.checkedServicesCounter.forEach((item, index) => {
                    if (index !== 0) {
                        this.checkedServicesCounter[index] = 0;
                    }
                });

                let price = 0;
                this.checkedServices.forEach((item, index) => {
                    this.checkedServicesCounter[item.category_id]++;
                    price += item.price;
                });

                this.checkedPrice = price;
            },
            otherServicesRemove() {
                this.otherServices.progress = false;
                this.otherServices.message = '';
                this.otherServices.images = [];
                this.otherServices.imagesThumbs = [];
                this.otherServices.imagesLinks = [];
                this.checkedServicesCounter[0] = 0;
            },
            otherServicesImageDelete(index) {
                this.otherServices.images.splice(index, 1);
                this.otherServices.imagesThumbs.splice(index, 1);
            },
            otherServicesImageChanged() {
                let files = this.$refs.uploadfoto.files;
                let that = this;
                if (FileReader && files && files.length) {
                    let fr = new FileReader;
                    fr.onload = function() {
                        that.otherServices.images.push(files[0]);
                        that.otherServices.imagesThumbs.push(fr.result);
                    };
                    fr.readAsDataURL(files[0]);
                }
            },
            otherServicesImageUpload(e) {
                if (this.otherServices.imagesThumbs.length < this.otherServices.imagesLimit) {
                    this.$refs.uploadfoto.click();
                }
                e.preventDefault();
            },
            toggleOtherServices() {
                this.otherServices.progress = !this.otherServices.progress;
            },

            updateAutoservicesIdByUser() {
                let arr = [];
                this.info.auto.forEach((item, index) => {
                    this.checkedServicesAutoByUser.forEach((itm, indx) => {
                        if (parseInt(item['id']) === parseInt(itm['id'])) {
                            arr.push(item);
                        }
                    })
                });

                this.checkedServicesAutoByUser = arr;
            },
            addToMyCar() {
                if (this.choose.addcar) {
                    var ls = Loading.service({ fullscreen: true });
                    this.$http.post('\/' + bus.info.lang + '/dashboard/add-car', this.collectCarDataMyCar(), { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                        var el = {
                            marka: this.selectedMarkaName,
                            model: this.selectedModelName,
                            year: this.selectedYear,
                            image: this.selectedMarkaImage,
                            vin: this.VIN,
                            markaId: this.selectedMarka,
                            id_marka: this.selectedMarka,
                            modelId: this.selectedModelId,
                            id_model: this.selectedModelId,
                            yearId: this.selectedYearId,
                            id_year: this.selectedYearId,
                            id: response.data.auto_id
                        };
                        this.choose.mycars.push(el);
                        this.resetCarData();
                        this.choose.addcar = false;
                        ls.close();
                    }, response => {
                        ls.close();
                        alert('No connection with server')
                    });
                }
            },
            removeError(name, scope) {
                this.errors.remove(name, scope);
            },
            validateVIN() {
                this.$validator.validate('VIN');
                //console.log('validateVIN(): ', this.errors.has('VIN'));
            },
            makeAutoSelected() {
                if (!this.errors.has('VIN')) {
                    this.VINtext = this.VIN;
                    this.choose.auto = false;
                }
            },
            putAutoToSelected(el) {
                this.selectedIdMyCar = el.id;
                this.selectedMarka = el.id_mark;
                this.selectedModelId = el.id_model;
                this.selectedMarkaImage = el.image;
                this.selectedMarkaName = el.marka;
                this.selectedModelName = el.model;
                this.VIN = el.vin;
                this.VINtext = el.vin;
                this.selectedYear = el.year;

                this.choose.auto = false;
            },
            collectCarDataMyCar() {
                var data = {
                    id_mark: this.selectedMarka,
                    id_model: this.selectedModelId,
                    id_year: this.selectedYearId,
                    vin: this.VIN
                };

                return data;
            },
            getAddressId() {
                var el = this.selectedAddress;

                let data = {
                    district: '',
                    city: ''
                };

                if (el.district !== '' || el.city !== '') {
                    data.district = el.district;
                    data.city = el.city;
                }

                return data;
            },
            getPageLocation(page) {
                let slash = '/';
                let location = slash + page;
                if (bus.info.lang === 'ru') {
                    location = slash + bus.info.lang + location;
                }

                return location;
            },
            searchOnMap() {
                let data = {
                    autoservicesId: [],
                    autoservicesIdByUser: [],
                    services: this.checkedServices,
                    otherServices: this.otherServices,
                    checkedServicesCounter: this.checkedServicesCounter,
                    checkedPrice: this.checkedPrice,
                    auto: this.selectedAuto(),
                    address: this.getAddressId(),
                    official: []
                };

                let location = this.getPageLocation('map');

                if (this.otherServices.imagesThumbs.length !== 0) {
                    let fd = new FormData();
                    this.otherServices.images.forEach((item) => {
                        fd.append('files[]', item);
                    });

                    this.$http.post('\/' + bus.info.lang + '/temp-image', fd, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                        let arr = response.data['temp_image'];
                        arr.forEach((item) => {
                            this.otherServices.imagesLinks.push(item);

                        });
                        this.otherServices.images = [];
                        data.otherServices = this.otherServices;

                        this.$localStorage.set('mapData', JSON.stringify(data));
                        window.location.replace(location);
                    }, response => {
                        alert('No connection with server')
                    });
                } else {
                    this.$localStorage.set('mapData', JSON.stringify(data));
                    window.location.replace(location);
                }
            },
            sendToAllServices() {
                let data = {
                    autoservicesId: [],
                    autoservicesIdByUser: [],
                    services: this.checkedServices,
                    otherServices: this.otherServices,
                    checkedServicesCounter: this.checkedServicesCounter,
                    checkedPrice: this.checkedPrice,
                    auto: this.selectedAuto(),
                    address: this.getAddressId(),
                    official: []
                };

                let location = this.getPageLocation('order');
                if (this.otherServices.imagesThumbs.length !== 0) {
                    let fd = new FormData();
                    this.otherServices.images.forEach((item) => {
                        fd.append('files[]', item);
                    });

                    this.$http.post('\/' + bus.info.lang + '/temp-image', fd, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                        let arr = response.data['temp_image'];
                        arr.forEach((item) => {
                            this.otherServices.imagesLinks.push(item);

                        });
                        this.otherServices.images = [];
                        data.otherServices = this.otherServices;

                        this.$localStorage.set('orderData', JSON.stringify(data));
                        window.location.replace(location);
                    }, response => {
                        alert('No connection with server')
                    });
                } else {
                    this.$localStorage.set('orderData', JSON.stringify(data));
                    window.location.replace(location);
                }
            },
            selectedAuto() {
                let data = {
                    markaId: this.selectedMarka,
                    markaName: this.selectedMarkaName,
                    markaImage: this.selectedMarkaImage,
                    modelId: this.selectedModelId,
                    modelName: this.selectedModelName,
                    yearId: this.selectedYearId,
                    year: this.selectedYear,
                    vin: this.VIN,
                    autoId: this.selectedIdMyCar
                };

                return data;
            },
            closeServiceChoose(type) {
                if (type) {
                    this.choose.main = false;
                    this.checkedServices = [];
                    this.checkedPrice = 0;
                    this.otherServicesRemove();
                    this.updateAuto(false);
                } else {
                    this.choose.main = false;
                    this.otherServices.progress = false;
                }
            },
            closeAutoChoose() {
                this.choose.auto = false;
                this.selectedMarka = '';
                this.updateAuto();
                this.resetCarData();
            },
            closeAutoChooseSlide() {
                if (this.choose.progress > 3) {
                    if (!this.errors.has('VIN')) {
                        this.VINtext = this.VIN;
                        this.choose.auto = false;
                    }
                } else {
                    this.choose.auto = false;
                }
            },
            continueServiceChoose() {
                this.choose.main = false;
                this.otherServices.progress = false;
                this.updateAuto(false);
            },
            updateAuto(fromChooseAuto) {
                if (!fromChooseAuto) {
                    this.choose.auto = false;
                    this.choose.main = false;
                }

                this.autoservicesLoading = true;
                setTimeout(() => {
                    this.updateAutoservicesIdByUser();
                    this.autoservicesLoading = false;
                }, 1000);
            },
            returnText(num, text) {
                return wordend(num, text);
            },
            returnAddress(item) {
                let address = '';

                if (item.district !== undefined) {
                    address += item.district;
                }
                if (item.city !== undefined) {
                    address += ', ' + item.city;
                }
                if (item.address !== null) {
                    address += ', ' + item.address;
                }

                return address;
            },
            selectAuto(event, item) {
                let _event = event || window.event;
                let el = _event.currentTarget;
                let target = _event.target || _event.srcElement;
                if (!target.classList.contains('title-link')) {
                    if (el.classList.contains('active')) {
                        el.classList.remove('active');
                        this.checkedServicesAuto.splice(this.checkedServicesAuto.indexOf(item), 1); //comment
                        this.checkedServicesAutoByUser.splice(this.checkedServicesAuto.indexOf(item), 1);
                    } else {
                        el.classList.add('active');
                        this.checkedServicesAuto.push(item); //comment
                        this.checkedServicesAutoByUser.push(item);
                    }
                }
            },
            showInner(event) {
                this.otherServices.progress = false;

                let orderServicesObj = document.querySelector('.order-services_wr');
                let orderServiceHeight = window.getComputedStyle(orderServicesObj, null).getPropertyValue("height");
                orderServicesObj.style.height = 'auto';

                let _event = event || window.event;
                let ul = _event.currentTarget.parentNode;
                let currentLi = _event.currentTarget;
                let arrayLi = Array.prototype.filter.call(currentLi.parentNode.children, function(child) {
                    return child !== currentLi;
                });
                Array.prototype.forEach.call(arrayLi, item => {
                    item.classList.remove('active');
                    item.parentNode.style.height = 'auto';
                });
                if (currentLi.classList.contains('active')) {
                    currentLi.classList.remove('active');
                    currentLi.parentNode.style.height = 'auto';
                } else {
                    currentLi.classList.add('active');
                    let parent = currentLi.querySelector('.inner-wrapper');
                    let parentHeight = window.getComputedStyle(parent, null).getPropertyValue("height");
                    if (parseInt(parentHeight) < parseInt(orderServiceHeight)) {
                        parentHeight = orderServiceHeight;
                    }
                    orderServicesObj.style.height = parentHeight;
                }
            },
            openService(item) {
                //console.log('openService()', JSON.stringify(item, null, 4));
                if (typeof item === 'object') {
                    let id = item[0].id;
                    let el = this.$refs[id][0];
                    if (!el.classList.contains('active')) {
                        eventFire(el, 'click');
                    }
                    let ident = item[0].id_unic;
                    let num = ident + '_' + id;
                    this.$refs[num][0].focus();
                }
            },
            removeFromChecked(index) {
                this.checkedServices.splice(index, 1);
            },
            selectM(id, image, name) {
                this.selectedMarka = id;
                this.selectedMarkaImage = image;
                this.selectedMarkaName = name;
                let ls = Loading.service({ target: this.$refs.modalHome });

                this.$http.post('\/' + bus.info.lang + '/get-model', this.selectedMarka, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allModels = response.data;
                    this.returnedModels = response.data;
                    this.choose.progress = 2;

                    this.updateAuto(true);

                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            selectModel(id, name) {
                this.selectedModelName = name;
                this.selectedModelId = id;
                let ls = Loading.service({ target: this.$refs.modalHome });

                this.$http.post('\/' + bus.info.lang + '/get-date', this.selectedModelId, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allDate = response.data;
                    this.choose.progress = 3;
                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            selectDate(id, year) {
                this.selectedYear = year;
                this.selectedYearId = id;
                this.choose.progress = 4;
            },
            resetCarData() {
                this.selectedMarkaImage = '';
                this.selectedMarkaName = '';
                this.selectedModelName = '';
                this.selectedModelId = '';
                this.selectedYear = '';
                this.selectedYearId = '';
                this.VIN = '';
                this.VINtext = '';
                this.choose.progress = 1;
                this.getMarka = '';
                this.getModel = '';
                this.selectedIdMyCar = '';
                this.selectedMarka = '';
            },
            updateCarData(progress) {
                if (progress == 1) {
                    this.resetCarData();
                    this.updateAuto(true);
                } else if (progress == 2) {
                    this.selectedModelName = '';
                    this.selectedModelId = '';
                    this.selectedYear = '';
                    this.selectedYearId = '';
                    this.VIN = '';
                    this.VINtext = '';
                    this.choose.progress = 2;
                } else if (progress == 3) {
                    this.selectedYear = '';
                    this.selectedYearId = '';
                    this.VIN = '';
                    this.VINtext = '';
                    this.choose.progress = 3;
                }
            },
            collectCarData() {
                let data = {
                    mark: this.selectedMarka,
                    model: this.selectedModelId,
                    year: this.selectedYearId,
                    vin: this.VIN
                };
                if (this.selectedIdMyCar !== '' || this.selectedIdMyCar !== undefined) {
                    data['id'] = this.selectedIdMyCar;
                }

                return data;
            }
        },
        mounted() {
            this.setServicesCounter();
            this.onWindowResize(function() {

            });
        }
    });
}

function wordend(num, words) {
    return words[((num = Math.abs(num % 100)) > 10 && num < 15 || (num %= 10) > 4 || num === 0) + (num !== 1)];
}