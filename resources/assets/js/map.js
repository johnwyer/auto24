'use strict';

import { Checkbox, Scrollbar, Select, Loading } from 'element-ui';
// import MarkerClusterer from './clust';
import orderFind from './components/order_find';
import uploadFoto from './components/upload-main';
// import ElScrollbar from 'element-ui';
window.Vue.use(Scrollbar);

Vue.filter('numberF', function(value) {
    var v = parseFloat(value).toFixed(1);
    return parseFloat(v);
});

const TILE_SIZE = { height: 256, width: 256 }; // google World tile size, as of v3.22
const ZOOM_MAX = 21; // max google maps zoom level, as of v3.22
const BUFFER = 15;

if (document.getElementById('findonmap')) {
    window.fmap = new Vue({
        el: '#findonmap',
        data: {
            sort: {
                service: [],
                auto: {},
                adr: {},
                type: {
                    of: false,
                    universal: false
                },
            },
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
                //addcar: difUser === 'client' ? false : true
                addcar: true,
                addMarks: false,
                addYears: false
            },
            some: {
                scrollHeight: '',
                noDataFound: false,
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
            autoservicesLoading: false,
            mapOptions: {
                id: 'fmap',
                maxZoom: 21,
                minZoom: 4,
                startZoom: 14,
                defaultLat: 47.0148715,
                defaultLng: 28.7354937
            }
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
            // for filterAutoservice
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
        methods: {
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
                console.log('setServiceCounter()');
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
            locationchanged(data) {
                //console.log('locationchanged(): ' /*JSON.stringify(data)*/ );
                this.selectedAddress.district = data.district.value;
                this.selectedAddress.city = data.city.value;
                let that = this;

                setTimeout(function() {
                    that.updateAuto(false);
                }, 100);
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
            sendInfoSubmit() {
                var csrv = [];
                var csrvByUser = [];

                if (this.checkedServicesAuto.length) {
                    this.checkedServicesAuto.forEach(item => {
                        csrv.push(item.id);
                    })
                } else if (this.info.auto.length) {
                    this.info.auto.forEach(item => {
                        csrv.push(item.id);
                    })
                }

                if (this.checkedServicesAutoByUser.length) {
                    this.checkedServicesAutoByUser.forEach(item => {
                        csrvByUser.push(item.id);
                    })
                }

                var type = [];
                if (this.sort.type.of && this.sort.type.universal) {
                    type['official'] = [];
                } else if (this.sort.type.of) {
                    type['official'] = 1;
                } else if (this.sort.type.universal) {
                    type['official'] = 0;
                } else {
                    type['official'] = [];
                }

                var data = {
                    autoservicesId: csrv,
                    autoservicesIdByUser: csrvByUser,
                    services: this.checkedServices,
                    otherServices: this.otherServices,
                    checkedServicesCounter: this.checkedServicesCounter,
                    checkedPrice: this.checkedPrice,
                    auto: this.selectedAuto(),
                    address: this.getAddressId(),
                    official: type['official']
                };

                let location = '/order';
                if (bus.info.lang === 'ru') {
                    location = '/' + bus.info.lang + location;
                }

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
            notifyMe(data) {
                this.$notify({
                    title: data.title,
                    message: data.message,
                    type: data.type,
                    duration: data.duration
                })
            },
            selectedAuto() {
                var data = {
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
            mapSizeHeight() {
                let w = window.innerWidth;
                let s = document.querySelector('.map-side').offsetHeight;
                let t = document.querySelector('.map-side_top').offsetHeight;
                let b = document.querySelector('.map-side_bottom').offsetHeight;
                let m = (s - t - b - 20);
                this.some.scrollHeight = m + 'px';
            },
            chooseSlideIn(el) {
                let element = el;

                velocity(element, {
                    translateX: ['0%', '100%'],
                }, { duration: 500 })
            },
            chooseSlideOut(el, done) {
                var el = el;
                velocity(el, {
                    translateX: '100%',
                }, { complete: done });
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
            updateMap() {
                this.markersOnMap = [];
                let markersStore = [];
                window.mapBounds = new google.maps.LatLngBounds();

                let orderinfo = new google.maps.InfoWindow({
                    pixelOffset: new google.maps.Size(0, 0),
                    content: ''
                });

                if (this.info.auto.length) {
                    this.info.auto.forEach((item, index) => {
                        let position = {
                            lat: parseFloat(item.cord_x),
                            lng: parseFloat(item.cord_y)
                        };
                        let positionObj = new google.maps.LatLng(parseFloat(item.cord_x), parseFloat(item.cord_y));;

                        let img = item.image ? item.image : 'elevator.png';
                        let adrServ = item.address ? item.address : '';
                        let contentString = makeContentString(item, img, adrServ);
                        let marker = addMarker(window.gfmap, positionObj, item.name);

                        marker.addListener('click', function() {
                            orderinfo.setContent(contentString);
                            orderinfo.open(window.gfmap, marker);
                            fmap.removeHovered();
                            focusElOnMap(item.id);
                        });

                        markersStore.push(marker);
                        this.markersOnMap.push(marker);

                        //-------------click on service ----------//
                        makeEventsOnAuto(this, item.id, index, gfmap, marker, position, orderinfo, contentString);
                        //------------- end click on service ----------//
                    });
                    window.markerCluster.addMarkers(markersStore);
                    fmap.setupMapBounds(true);
                } else {
                    window.gfmap.setZoom(11);
                    window.gfmap.setCenter({
                        lat: this.mapOptions.defaultLat,
                        lng: this.mapOptions.defaultLng
                    });
                }
            },
            initMap() {
                let markersStore = [];
                window.mapBounds = new google.maps.LatLngBounds();

                let orderinfo = new google.maps.InfoWindow({
                    pixelOffset: new google.maps.Size(0, 0),
                    content: ''
                });

                window.gfmap = makeMap(this.mapOptions.id, { lat: 0, lng: 0 }, this.mapOptions.startZoom, this.mapOptions.minZoom);

                if (Object.keys(this.info.auto).length) {
                    this.autoservicesLoading = true;
                    this.info.auto.forEach((item, index) => {

                        let position = {
                            lat: parseFloat(item.cord_x),
                            lng: parseFloat(item.cord_y)
                        };
                        let positionObj = new google.maps.LatLng(parseFloat(item.cord_x), parseFloat(item.cord_y));

                        let img = item.image ? item.image : 'elevator.png';
                        let adrServ = item.address ? item.address : '';
                        let contentString = makeContentString(item, img, adrServ);
                        let marker = addMarker(window.gfmap, positionObj, item.name);

                        markersStore.push(marker);
                        this.markersOnMap.push(marker);

                        marker.addListener('click', function() {
                            orderinfo.setContent(contentString);
                            orderinfo.open(window.gfmap, marker);
                            fmap.removeHovered();
                            focusElOnMap(item.id);
                        });

                        //-------------click on service ----------//
                        makeEventsOnAuto(this, item.id, index, gfmap, marker, position, orderinfo, contentString);
                        //------------- end click on service ----------//
                        this.autoservicesLoading = false;
                    });
                }

                //adding custom indicator control
                let gpsControlDiv = document.getElementById('fmap-gps-indicator-control');
                let gpsControl = new GPSIndicatorControl(gpsControlDiv, window.gfmap);
                gpsControlDiv.index = 1;
                window.gfmap.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(gpsControl[0]);

                //addinmg custom zoom control
                let zoomControlDiv = document.getElementById('fmap-zoom-control');
                let zoomControl = new ZoomControl(zoomControlDiv, window.gfmap);
                zoomControlDiv.index = 2;
                window.gfmap.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(zoomControl[0]);
                window.markerCluster = new MarkerClusterer(gfmap, markersStore, {
                    imagePath: 'img/cluster',
                    styles: [{
                        textColor: '#fff',
                        url: '../img/cluster1.png',
                        width: 55,
                        height: 55,
                        anchor: [0, 0],
                        textSize: 15,
                        fontWeight: 400
                    }]
                });

                let lastValidCenter;

                function setOutOfBoundsListener(map) {
                    google.maps.event.addListener(map, 'dragend', function() {
                        checkLatitude(map);
                    });
                    google.maps.event.addListener(map, 'idle', function() {
                        checkLatitude(map);
                    });
                    google.maps.event.addListener(map, 'zoom_changed', function() {
                        checkLatitude(map);
                    });
                }

                function checkLatitude(map) {
                    let bounds = map.getBounds();
                    let sLat = map.getBounds().getSouthWest().lat();
                    let nLat = map.getBounds().getNorthEast().lat();
                    if (sLat < -85 || nLat > 85) {
                        if (lastValidCenter) {
                            map.setCenter(lastValidCenter);
                        }
                    } else {
                        lastValidCenter = map.getCenter();
                    }
                }
                setOutOfBoundsListener(window.gfmap);

                google.maps.event.addListenerOnce(window.gfmap, 'idle', function() {
                    fmap.setupMapBounds(true);
                });
            },
            setupMapBounds(hasZoom) {
                const mapDimensions = {};
                const mapOffset = { x: 0, y: 0 };
                const mapEl = document.getElementById(this.mapOptions.id);
                const overlayEl = document.getElementById('map-sidebar');

                function updateMapDimensions() {
                    mapDimensions.height = mapEl.offsetHeight;
                    mapDimensions.width = mapEl.offsetWidth;
                }

                function getBoundsZoomLevel(bounds, dimensions) {
                    const latRadian = lat => {
                        let sin = Math.sin(lat * Math.PI / 180);
                        let radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
                        return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
                    };
                    const zoom = (mapPx, worldPx, fraction) => {
                        return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
                    };
                    const ne = bounds.getNorthEast();
                    const sw = bounds.getSouthWest();
                    const latFraction = (latRadian(ne.lat()) - latRadian(sw.lat())) / Math.PI;
                    const lngDiff = ne.lng() - sw.lng();
                    const lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;
                    const latZoom = zoom(dimensions.height, TILE_SIZE.height, latFraction);
                    const lngZoom = zoom(dimensions.width, TILE_SIZE.width, lngFraction);

                    return Math.min(latZoom, lngZoom, ZOOM_MAX);
                }

                function getBounds(services) {
                    let northeastLat;
                    let northeastLong;
                    let southwestLat;
                    let southwestLong;
                    services.forEach((item) => {
                        if (!northeastLat) {
                            northeastLat = southwestLat = item.cord_x;
                            southwestLong = northeastLong = item.cord_y;
                            return;
                        }

                        if (item.cord_x > northeastLat) {
                            northeastLat = item.cord_x;
                        } else if (item.cord_x < southwestLat) {
                            southwestLat = item.cord_x;
                        }

                        if (item.cord_y < northeastLong) {
                            northeastLong = item.cord_y;
                        } else if (item.cord_y > southwestLong) {
                            southwestLong = item.cord_y;
                        }
                    });
                    const northeast = new google.maps.LatLng(northeastLat, northeastLong);
                    const southwest = new google.maps.LatLng(southwestLat, southwestLong);
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(northeast);
                    bounds.extend(southwest);

                    return bounds;
                }

                function zoomWithOffset(shouldZoom) {
                    const currentZoom = window.gfmap.getZoom();
                    const newZoom = shouldZoom ? currentZoom + 1 : currentZoom - 1;
                    const offset = {
                        x: shouldZoom ? -mapOffset.x / 4 : mapOffset.x / 2,
                        y: shouldZoom ? -mapOffset.y / 4 : mapOffset.y / 2
                    };
                    const newCenter = offsetLatLng(window.gfmap.getCenter(), offset.x, offset.y);
                    if (shouldZoom) {
                        window.gfmap.setZoom(newZoom);
                        window.gfmap.panTo(newCenter);
                    } else {
                        window.gfmap.setCenter(newCenter);
                        window.gfmap.setZoom(newZoom);
                    }
                }

                function setMapBounds(services) {
                    updateMapDimensions();
                    const bounds = getBounds(services);
                    const dimensions = {
                        width: mapDimensions.width - mapOffset.x - BUFFER * 2,
                        height: mapDimensions.height - mapOffset.y - BUFFER * 2
                    };
                    const zoomLevel = getBoundsZoomLevel(bounds, dimensions);
                    window.gfmap.setZoom(zoomLevel);
                    setOffsetCenter(bounds.getCenter());
                }

                function offsetLatLng(latlng, offsetX, offsetY) {
                    offsetX = offsetX || 0;
                    offsetY = offsetY || 0;
                    const scale = Math.pow(2, window.gfmap.getZoom());
                    const point = window.gfmap.getProjection().fromLatLngToPoint(latlng);
                    const pixelOffset = new google.maps.Point((offsetX / scale), (offsetY / scale));
                    const newPoint = new google.maps.Point(
                        point.x - pixelOffset.x,
                        point.y + pixelOffset.y
                    );

                    return window.gfmap.getProjection().fromPointToLatLng(newPoint);
                }

                function setOffsetCenter(latlng) {
                    const newCenterLatLng = offsetLatLng(latlng, mapOffset.x / 2, mapOffset.y / 2);
                    window.gfmap.panTo(newCenterLatLng);
                }

                mapOffset.x = overlayEl.offsetWidth;

                zoomWithOffset(hasZoom);
                setMapBounds(fmap.info.auto);
            },
            updateAuto(fromChooseAuto) {
                if (!fromChooseAuto) {
                    this.choose.auto = false;
                    this.choose.main = false;
                }

                let data = {
                    'service_id': this.chs.length ? this.chs : null,
                    'mark': this.selectedMarka !== '' ? this.selectedMarka : ''
                };

                if (this.sort.type.of && this.sort.type.universal) {
                    data['official'] = null;
                } else if (this.sort.type.of) {
                    data['official'] = 1;
                } else if (this.sort.type.universal) {
                    data['official'] = 0;
                } else {
                    data['official'] = null;
                }

                data['district'] = this.selectedAddress.district;
                data['city'] = this.selectedAddress.city;
                this.autoservicesLoading = true;

                this.$http.post('get-autoservices', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    if (window.markerCluster.clearMarkers) {
                        window.markerCluster.clearMarkers();
                    }

                    this.info.auto = Array.from(response.data);

                    fmap.markersOnMap.forEach(item => {
                        item.setMap(null);
                    });

                    setTimeout(function() {
                        fmap.updateMap();
                    }, 500);

                    this.autoservicesLoading = false;
                    this.updateAutoservicesIdByUser();
                }, response => {
                    this.autoservicesLoading = false;
                    alert('No connection with server');
                });
            },
            removeHovered() {
                let neiborhoods = document.querySelectorAll('.hovered');
                if (neiborhoods.length) {
                    neiborhoods.forEach(item => {
                        item.classList.remove('hovered')
                    });
                }
            },
            addClass(event, condition) { // hover effect on auto-block
                let _event = event || window.event;
                let el = _event.currentTarget;
                this.removeHovered();
                if (condition) {
                    el.classList.add('hovered');
                } else {
                    el.classList.remove('hovered')
                }
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
                console.log('openService()', JSON.stringify(item, null, 4));
                if (typeof item === 'object') {
                    let id = item[0].id;
                    let el = this.$refs[id][0];
                    if (!el.classList.contains('active')) {
                        eventFire(el, 'click');
                    }
                    let ident = item[0].id_unic;
                    let num = ident + '_' + id;
                    this.$refs[num][0].focus();

                    console.log('openService(): ', this.$refs[num][0]);
                }
            },
            removeFromChecked(index) {
                console.log(index, this.checkedServices[index]);

                this.checkedServices.splice(index, 1);
            },
            selectM(id, image, name) {
                this.selectedMarka = id;
                this.selectedMarkaImage = image;
                this.selectedMarkaName = name;
                let ls = Loading.service({ fullscreen: true });

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
                let ls = Loading.service({ fullscreen: true });

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
                if (progress === 1) {
                    this.resetCarData();
                    this.updateAuto(true);
                } else if (progress === 2) {
                    this.selectedModelName = '';
                    this.selectedModelId = '';
                    this.selectedYear = '';
                    this.selectedYearId = '';
                    this.VIN = '';
                    this.VINtext = '';
                    this.choose.progress = 2;

                    if (this.choose.addMarks) {
                        this.getModelMarks()
                    }
                } else if (progress === 3) {
                    this.selectedYear = '';
                    this.selectedYearId = '';
                    this.VIN = '';
                    this.VINtext = '';
                    this.choose.progress = 3;

                    if (this.choose.addYears) {
                        this.getModelYears()
                    }
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
            },
            getModelMarks() {
                let ls = Loading.service({ fullscreen: true });
                this.$http.post('\/' + bus.info.lang + '/get-model', this.selectedMarka, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allModels = response.data;
                    this.returnedModels = response.data;
                    this.choose.addMarks = false;
                    this.choose.addYears = false;

                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            getModelYears() {
                let ls = Loading.service({ fullscreen: true });
                this.$http.post('\/' + bus.info.lang + '/get-date', this.selectedModelId, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allDate = response.data;
                    this.choose.addYears = false;
                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            initMapData() {
                var mapdata = JSON.parse(Vue.localStorage.get('mapData'));
                console.log('mapData: ', JSON.stringify(mapdata, null, 4));
                if (mapdata !== null) {
                    console.log('mapData: in');
                    Vue.localStorage.remove('mapData');
                    this.official = mapdata.official;
                    this.checkedServices = mapdata.services;

                    this.selectedMarka = mapdata.auto.markaId;
                    this.selectedMarkaName = mapdata.auto.markaName;
                    this.selectedMarkaImage = mapdata.auto.markaImage;
                    this.selectedModelName = mapdata.auto.modelName;
                    this.selectedModelId = mapdata.auto.modelId;
                    this.selectedYear = mapdata.auto.year;
                    this.selectedYearId = mapdata.auto.yearId;
                    this.VIN = mapdata.auto.vin;
                    this.VINtext = mapdata.auto.vin;
                    this.autoId = mapdata.auto.id;

                    if (this.selectedMarka !== '') {
                        this.choose.progress = 2;
                        this.choose.addMarks = true;
                        console.log('choose: 2', this.choose.progress);
                    }
                    if (this.selectedModelId !== '') {
                        this.choose.progress = 3;
                        this.choose.addYears = true;
                        console.log('choose: 3', this.choose.progress);
                    }
                    if (this.selectedYear !== '') {
                        this.choose.progress = 4;
                        console.log('choose: 4', this.choose.progress);
                    }
                    this.selectedAddress.district = mapdata.address.district;
                    this.selectedAddress.city = mapdata.address.city;

                    this.otherServices = mapdata.otherServices;
                    this.checkedServicesCounter = mapdata.checkedServicesCounter;
                    this.checkedPrice = mapdata.checkedPrice;

                    this.updateAuto(false);
                }
            }
        },
        components: {
            serviceFind: orderFind,
            Checkbox,
            getAddress: require('./components/Getaddress.vue'),
            uploadFoto
        },
        mounted() {
            this.initMapData();
            this.setServicesCounter();
            WhenGoogleLoaded(this.initMap);
            this.mapSizeHeight();
            this.onWindowResize(function() {
                fmap.mapSizeHeight();
                fmap.setupMapBounds(true);
                console.log('test winresize');
            });
        }
    });
}

function GPSIndicatorControl(controlDiv, map) {
    if (window.location.protocol === 'https:' && navigator.geolocation) {
        controlDiv.style.display = 'block';
    }

    let gpsButton = document.getElementById('fmap-gps-indicator');
    google.maps.event.addDomListener(gpsButton, 'click', function(e) {
        e.preventDefault();
        if (window.location.protocol === 'https:') {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                        map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
                    },
                    function() {
                        handleLocationError(true);
                    }, {
                        maximumAge: 60000,
                        timeout: 5000,
                        enableHighAccuracy: true
                    });
            } else {
                handleLocationError(false);
            }
        } else {
            console.log('GPS location - insecure protocol');
        }
    });
}

function handleLocationError(browserHasGeolocation) {
    console.log(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
}

function ZoomControl(controlDiv, map) {
    controlDiv.style.display = 'block';

    let zoomInButton = document.getElementById('fmap-zoom-in');
    google.maps.event.addDomListener(zoomInButton, 'click', function(e) {
        e.preventDefault();
        map.setZoom(map.getZoom() + 1);
    });

    let zoomOutButton = document.getElementById('fmap-zoom-out');
    google.maps.event.addDomListener(zoomOutButton, 'click', function(e) {
        e.preventDefault();
        map.setZoom(map.getZoom() - 1);
    });
}

function WhenGoogleLoaded(fnt) {
    //google.maps.event.addDomListener(window, 'load', fnt);

    if (typeof google != 'undefined') {
        fnt();
    } else {
        setTimeout(function() {
            (function(fnt) {
                WhenGoogleLoaded(fnt)
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

function wordend(num, words) {
    return words[((num = Math.abs(num % 100)) > 10 && num < 15 || (num %= 10) > 4 || num === 0) + (num !== 1)];
}

(function(e) {
    e.closest = e.closest || function(css) {
        let node = this;

        while (node) {
            if (node.matches(css)) return node;
            else node = node.parentElement;
        }
        return null;
    }
})(Element.prototype);

//  google map functions
function panTo(newLat, newLng) {
    if (panPath.length > 0) {
        // We are already panning...queue this up for next move
        panQueue.push([newLat, newLng]);
    } else {
        // Lets compute the points we'll use
        panPath.push("LAZY SYNCRONIZED LOCK"); // make length non-zero - 'release' this before calling setTimeout
        let curLat = gfmap.getCenter().lat();
        let curLng = gfmap.getCenter().lng();
        let dLat = (newLat - curLat) / STEPS;
        let dLng = (newLng - curLng) / STEPS;

        for (let i = 0; i < STEPS; i++) {
            panPath.push([curLat + dLat * i, curLng + dLng * i]);
        }
        panPath.push([newLat, newLng]);
        panPath.shift(); // LAZY SYNCRONIZED LOCK
        setTimeout(doPan, 20);
    }
}
// smooth pan
function doPan() {
    let next = panPath.shift();
    if (next != null) {
        // Continue our current pan action
        gfmap.panTo(new google.maps.LatLng(next[0], next[1]));
        setTimeout(doPan, 20);
    } else {
        // We are finished with this pan - check if there are any queue'd up locations to pan to
        let queued = panQueue.shift();
        if (queued != null) {
            panTo(queued[0], queued[1]);
        }
    }
}
// for panTo
function smoothZoom(map, max, cnt) {
    console.log('smoothZoom() ', max, cnt);

    if (cnt >= max) {
        return;
    } else {
        let z = google.maps.event.addListener(map, 'zoom_changed', function(event) {
            console.log('smoothZoom zoom_changed ', map.getZoom());
            google.maps.event.removeListener(z);
            smoothZoom(map, max, cnt + 1);
        });
        setTimeout(function() {
            map.setZoom(cnt);
        }, 80); // 80ms is what I found to work well on my system -- it might not work well on all systems
    }
}
// smooth zoom

function makeMap(id, position, zoom, minZoom) {
    let map = new google.maps.Map(document.getElementById(id), {
        center: position,
        zoom: zoom,
        minZoom: minZoom,
        streetViewControl: false,
        fullscreenControl: false,
        mapTypeControl: false,
        disableDefaultUI: true,
        scrollwheel: true
    });

    return map;
}

function makeContentString(item, img, adrServ) {
    let hrefs = document.getElementById('link' + item.id).href;
    let el = `
    <div class="infowindow">
        <div class="image"><a href="${hrefs}"><img src="/images/autoservice/${img}" alt=""></a></div>
        <div class="text">
            <div class="title"><a href="${hrefs}"><span>${item.name}</span></a></div>
            <div class="adr">${adrServ}</div>
            <div class="bottom">${document.getElementById('rate'+item.id).innerHTML}</div>
        </div>
    </div>
    `;

    return el;
}

function addMarker(map, position, name) {
    let marker = new google.maps.Marker({
        position: position,
        icon: '/img/placeholder-filled-point2.png',
        title: name,
        map: map
    });

    return marker;
}

function focusElOnMap(id) {
    let el = document.getElementById('auto_map' + id);
    el.classList.add('hovered');
    el.focus();
}

function makeEventsOnAuto(vue, id, index, map, marker, position, orderInfo, contentString) {
    let el = document.getElementById('auto_map' + id);
    el.onclick = function() {
        if (el.classList.contains('active')) {
            map.setZoom(10);
            slowPanTo(map, new google.maps.LatLng(position), 20, 400);
            orderInfo.setContent(contentString);
            orderInfo.open(map, marker);
            map.setZoom(14);
        } else {
            orderInfo.close();
        }
    };
}

function slowPanTo(map, endPosition, n_intervals, T_msec) {
    let f_timeout, getStep, i, j, lat_array, lat_delta, lat_step, lng_array, lng_delta, lng_step, pan, ref, startPosition;
    getStep = function(delta) {
        return parseFloat(delta) / n_intervals;
    };
    startPosition = map.getCenter();
    lat_delta = endPosition.lat() - startPosition.lat();
    lng_delta = endPosition.lng() - startPosition.lng();
    lat_step = getStep(lat_delta);
    lng_step = getStep(lng_delta);
    lat_array = [];
    lng_array = [];
    for (i = j = 1, ref = n_intervals; j <= ref; i = j += +1) {
        lat_array.push(map.getCenter().lat() + i * lat_step);
        lng_array.push(map.getCenter().lng() + i * lng_step);
    }

    f_timeout = function(i, i_min, i_max) {
        return parseFloat(T_msec) / n_intervals;
    };

    pan = function(i) {
        if (i < lat_array.length) {
            return setTimeout(function() {
                map.panTo(new google.maps.LatLng({
                    lat: lat_array[i],
                    lng: lng_array[i]
                }));

                return pan(i + 1);
            }, f_timeout(i, 0, lat_array.length - 1));
        }
    };

    return pan(0);
}