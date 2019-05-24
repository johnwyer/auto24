'use strict';
import { Loading, Checkbox, Scrollbar, RadioButton, RadioGroup } from 'element-ui';

if (document.getElementById('adverts-add')) {
    window.advertsAdd = new Vue({
        el: '#adverts-add',
        data: {
            lang: bus.info.lang,
            progress: {
                step: 3,
                bar: 3,
                markaProgress: 1,
                log: window.bus.info.log,
                loginForm: window.bus.info.log,
                addcar: false,
                sendOrder: false,
                enter: 1
            },
            //mycars: window.inf.myAuto ? window.inf.myAuto : [],
            autoId: '',
            selectedMarka: '',
            selectedMarkaImage: '',
            selectedMarkaName: '',
            selectedModelName: '',
            selectedModelId: '',
            selectedYear: null,
            //selectedYearId: '',
            selectedBodyTypeName: null,
            selectedBodyTypeId: null,
            selectedWheelType: false,
            selectedGenerationId: null,
            selectedEngine: null,
            selectedEngineId: null,
            selectedEngineGasEquipment: false,
            selectedDrive: null,
            selectedDriveId: null,
            selectedGearbox: null,
            selectedGearboxId: null,
            selectedModificationType: null,
            selectedModificationTypeId: null,
            selectedColor: null,
            VIN: '',
            getMarka: '',
            getModel: '',
            getModelShowLimit: 30,
            allMarks: window.info.marks ? window.info.marks : [],
            returnedMarks: window.info.marks ? window.info.marks : [],
            allModels: [],
            returnedModels: [],
            allDate: [],
            allBodies: [],
            allGenerations: [],
            allEngines: [],
            allDrives: [],
            returnedDrives: [],
            allGearboxes: [],
            returnedGearboxes: [],
            allModifications: [],
            returnedModifications: [],

            allColors: window.info.colors ? window.info.colors : [],
            sendData: {
                auto: {
                    marka: '',
                    model: '',
                    image: '',
                    id: '',
                    id_mark: '',
                    id_model: '',
                    id_year: '',
                    year: '',
                    vin: ''
                }
            },
            showAllMarks: false,
            isMarkaFilter: false,
            showAllModels: false,
            isModelsFilter: false,

            allComplectation: '',
            allComfort: '',
            allSaloonAndInteriors: '',
            allAdjustingDriverSeat: '',
            allAlarmSystems: '',
            allSecurity: '',
            allExteriorFeatures: '',
            allAdjustingPassengerSeat: '',
            allHelpWithDrivingAndParking: '',
            allOverview: '',
            allMultimedia: '',

            selectedComfort: {
                value: []
            },
            selectedSaloonAndInteriors: {
                value: []
            },
            selectedAdjustingDriverSeat: {
                value: []
            },
            selectedAlarmSystems: {
                value: []
            },
            selectedSecurity: {
                value: []
            },
            selectedExteriorFeatures: {
                value: []
            },
            selectedAdjustingPassengerSeat: {
                value: []
            },
            selectedHelpWithDrivingAndParking: {
                value: []
            },
            selectedOverview: {
                value: {}
            },
            selectedMultimedia: {
                value: []
            },

            /*
            selectedClimate: {
                value: null
            },
            selectedPowerWindows: {
                value: null
            },
            selectedSeatHeating: {
                value: null
            },
            selectedSteeringWheelAdjustment: {
                value: null
            },
            selectedSteeringWheelHeating: null,
            selectedTintedWindows: null,
            selectedUpholstery: {
                value: null
            },
            selectedNumberOfSeats: {
                value: null
            },
            selectedLuke: null,
            selectedLeatherSteeringWheel: null,
            selectedPanoramicRoof: null,
            selectedDriverSearElectro: null,
            selectedDriverSeatMemory: null,
            selectedCentralLocking: null,
            selectedAlarm: null,
            selectedImmobilizer: null,

            selectedDriverAirbag: null,
            selectedPassengerAirbag: null,
            selectedSideAirbagsFront: null,
            selectedWindAirbags: null,
            selectedTCR: null,
            selectedESP: null,
            selectedABS: null,
            selectedBreakAssist: null,

            selectedAlloyWheels: null,
            selectedAerodynamicBodyKit: null,
            selectedTrailerHook: null,
            selectedWinterTyres: null,

            selectedPassengerSeatElectro: null,
            selectedPassengerSeatMemory: null,

            selectedPowerSteering: {
                value: null
            },
            selectedRoadAdaptationSystem: null,
            selectedCruiseControl: null,

            selectedReviewHeadLight: {
                value: null
            },
            selectedReviewFogLights: null,
            selectedReviewHeadlightWashers: null,
            selectedReviewLightSensor: null,
            selectedReviewElectricMirrors: null,
            selectedReviewRainSensor: null,
            selectedReviewHeatedMirrors: null,

            selectedMultimediaCD: null,
            selectedMultimediaDVD: null,
            selectedMultimediaMP3: null,
            selectedMultimediaUSB: null,
            selectedMultimediaTV: null,
            selectedMultimediaOnBoardPC: null,
            selectedMultimediaGPS: null
            */
        },
        watch: {
            getMarka(val) {
                if (val.length) {
                    var arr = [];
                    if (!this.showAllMarks) {
                        //console.log('getMarka() filter popular');
                        arr = this.allMarks.filter((item) => {
                            if (parseInt(item.popular) === 1) {
                                return item;
                            }
                        });
                    } else {
                        arr = _.sortBy(this.allMarks, 'name');
                    }

                    this.returnedMarks = arr.filter((item) => {
                        //this.returnedMarks = this.returnedMarks.filter(item => {
                        let a = item.name.toLowerCase();
                        let b = val.toLowerCase();
                        let c = item.cyrillic_name.toLowerCase();

                        if (bus.info.lang === 'ru') {
                            if (a.includes(b) || c.includes(b)) {
                                return item;
                            }
                        } else {
                            if (a.includes(b)) {
                                return item;
                            }
                        }
                    });

                    this.isMarkaFilter = true;
                } else {
                    this.returnedMarks = this.allMarks;
                    this.isMarkaFilter = false;
                }
            },
            getModel(val) {
                if (val.length) {
                    var arr = [];

                    if (this.checkModelsFilter()) {
                        if (!this.showAllModels) {
                            arr = this.allModels.filter((item) => {
                                if (parseInt(item.popular) === 1) {
                                    return item;
                                }
                            });
                        } else {
                            arr = _.sortBy(this.allModels, 'name');
                        }
                    } else {
                        arr = _.sortBy(this.allModels, 'name');
                    }

                    this.returnedModels = arr.filter((item) => {
                        let a = item.name.toLowerCase();
                        let b = val.toLowerCase();
                        let c = item.cyrillic_name.toLowerCase();

                        if (bus.info.lang === 'ru') {
                            if (a.includes(b) || c.includes(b)) {
                                return item;
                            }
                        } else {
                            if (a.includes(b)) {
                                return item;
                            }
                        }
                    });

                    this.isModelsFilter = true;
                } else {
                    this.returnedModels = this.allModels;
                    this.isModelsFilter = false;
                }
                /*
                if(val.length > 0){
                    this.returnedModels = this.allModels.filter(item => {
                        let a = item.name.toLowerCase();
                        let b = val.toLowerCase();
                        //if(a.indexOf(b) !== -1){
                        if(a.includes(b)){
                            return true;
                        }
                    })
                }*/
            }
        },
        computed: {
            allMarkaComputed() {
                var self = this;
                if (!this.showAllMarks) {
                    if (this.getMarka.length === 0) {
                        return self.returnedMarks = self.allMarks.filter((item) => {
                            if (parseInt(item.popular) === 1) {
                                return item;
                            }
                        });
                    } else {
                        return self.returnedMarks = self.returnedMarks.filter((item) => {
                            if (parseInt(item.popular) === 1) {
                                return item;
                            }
                        });
                    }
                } else {
                    if (this.getMarka.length === 0) {
                        return self.returnedMarks = _.sortBy(self.allMarks, 'name');
                    } else {
                        return self.returnedMarks = _.sortBy(self.returnedMarks, 'name');
                    }
                }
                //return this.returnedMarks;
            },
            allModelsComputed() {
                var self = this;
                if (this.allModels.length > this.getModelShowLimit) {
                    if (!this.showAllModels) {
                        if (this.getModel.length === 0) {
                            return self.returnedModels = self.allModels.filter((item) => {
                                if (parseInt(item.popular) === 1) {
                                    return item;
                                }
                            });
                        } else {
                            return self.returnedModels = self.returnedModels.filter((item) => {
                                if (parseInt(item.popular) === 1) {
                                    return item;
                                }
                            });
                        }
                    } else {
                        if (this.getModel.length === 0) {
                            return self.returnedModels = _.sortBy(self.allModels, 'name');
                        } else {
                            return self.returnedModels = _.sortBy(self.returnedModels, 'name');
                        }
                    }
                    //return this.returnedModels;
                } else {
                    return self.returnedModels = self.allModels;
                }
            }
        },
        components: {
            Loading,
            Checkbox,
            Scrollbar,
            RadioButton,
            RadioGroup,
            carYears: require('../components/adverts/add-car-years'),
            carColors: require('../components/adverts/add-car-colors')
        },
        methods: {
            getStep3Data() {
                let url = '/api/get-complectation';
                if (bus.info.lang === 'ru') {
                    url = '/ru' + url;
                }

                this.$http.post(url, {}, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allComplectation = response.data;
                    this.allComplectation.option.forEach((item, index) => {
                        this.allComplectation.option[index] = JSON.parse(item);
                        if (index === 0) {
                            this.allComfort = JSON.parse(item);
                            //console.log('this.allComplectation.prop',JSON.stringify(this.allComplectation.prop));
                            //console.log('this.allComfort', JSON.stringify(this.allComfort));
                            //this.selectedComfort.value[4] = "39";
                            //this.selectedComfort.value[5] = "40";
                            //this.selectedComfort.value[6] = "41";
                        }
                        if (index === 1) {
                            this.allSaloonAndInteriors = JSON.parse(item);
                            //console.log('this.allSaloonAndInteriors',JSON.stringify(this.allSaloonAndInteriors));
                        }
                        if (index === 2) {
                            this.allAdjustingDriverSeat = JSON.parse(item);
                            //console.log('this.allAdjustingDriverSeat',JSON.stringify(this.allAdjustingDriverSeat));
                        }
                        if (index === 3) {
                            this.allAlarmSystems = JSON.parse(item);
                        }
                        if (index === 4) {
                            this.allSecurity = JSON.parse(item);
                        }
                        if (index === 5) {
                            this.allExteriorFeatures = JSON.parse(item);
                        }
                        if (index === 6) {
                            this.allAdjustingPassengerSeat = JSON.parse(item);
                        }
                        if (index === 7) {
                            this.allHelpWithDrivingAndParking = JSON.parse(item);
                        }
                        if (index === 8) {
                            this.allOverview = JSON.parse(item);
                        }
                        if (index === 9) {
                            this.allMultimedia = JSON.parse(item);
                        }
                    });
                }, response => {
                    alert('No connection with server');
                });
            },
            checkModelsFilter() {
                if (this.allModels.length > this.getModelShowLimit) {
                    return true;
                }
                return false;
            },
            setMarkaProgress(value) {
                //console.log('setMarkaProgress() ', value);
                this.resetMarkaData(value);
            },
            resetReturnedMarks() {
                this.returnedMarks = this.allMarks.filter((item) => {
                    if (parseInt(item.popular) === 1) {
                        return item;
                    }
                });
            },
            resetReturnedModels() {
                if (this.checkModelsFilter) {
                    this.returnedModels = this.allModels.filter((item) => {
                        if (parseInt(item.popular) === 1) {
                            return item;
                        }
                    });
                } else {
                    this.returnedModels = _.sortBy(this.allModels, 'name');
                }
            },
            resetMarkaData(value) {
                //console.log('resetMarkaData()', value);
                if (value < 9) {
                    this.selectedGearbox = null;
                    this.selectedGearboxId = null;
                    this.selectedModificationType = null;
                    this.selectedModificationTypeId = null;
                }
                if (value < 8) {
                    this.selectedDrive = null;
                    this.selectedDriveId = null;
                    this.selectedGearbox = null;
                    this.selectedGearboxId = null;
                    this.selectedModificationType = null;
                    this.selectedModificationTypeId = null;
                }
                if (value < 7) {
                    this.selectedDrive = null;
                    this.selectedDriveId = null;
                    this.selectedGearbox = null;
                    this.selectedGearboxId = null;
                    this.selectedModificationType = null;
                    this.selectedModificationTypeId = null;
                }
                if (value < 6) {
                    this.selectedGenerationId = null;
                    this.selectedEngine = null;
                    this.selectedEngineId = null;
                    this.selectedEngineGasEquipment = false;
                    this.selectedDrive = null;
                    this.selectedDriveId = null;
                    this.selectedGearbox = null;
                    this.selectedGearboxId = null;
                    this.selectedModificationType = null;
                    this.selectedModificationTypeId = null;
                }
                if (value < 5) {
                    this.selectedBodyTypeName = null;
                    this.selectedBodyTypeId = null;
                }
                if (value < 4) {
                    console.log('reset < 4');
                    this.selectedYear = null;
                }
                if (value < 3) {
                    console.log('reset < 3');
                    this.selectedModelName = '';
                    this.selectedModelId = '';
                    this.resetReturnedModels();
                }
                if (value < 2) {
                    console.log('reset < 2');
                    this.selectedMarka = '';
                    //this.selectedMarkaImage = image;
                    this.selectedMarkaName = '';
                    this.resetReturnedMarks();
                }

                if (value > 3) {
                    this.showAllModels = false;
                    this.isModelsFilter = false;

                    this.showAllMarks = false;
                    this.isMarkaFilter = false;
                }

                this.progress.markaProgress = value;
            },
            toggleAllMarksControl() {
                this.showAllMarks = !this.showAllMarks;
            },
            toggleAllModelsControl() {
                this.showAllModels = !this.showAllModels;
            },
            selectM(id, image, name) {
                //console.log('selectM() ', id, image, name);
                this.selectedMarka = id;
                this.selectedMarkaImage = image;
                this.selectedMarkaName = name;
                var ls = Loading.service({ fullscreen: true });

                this.allMarks.forEach((item) => {
                    if (item.id === id) {
                        this.allModels = item.my_model;
                        this.returnedModels = item.my_model;
                        this.progress.markaProgress = 2;
                    }
                });

                setTimeout(() => {
                    ls.close();
                }, 1000);
            },
            selectModel(id, name) {
                //console.log('selectModel() ', id, name);
                var ls = Loading.service({ fullscreen: true });
                this.selectedModelName = name;
                this.selectedModelId = id;
                let data = {
                    model_id: id
                };

                this.$http.post('\/' + bus.info.lang + '/api/get-year', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allDate = response.data;
                    this.progress.markaProgress = 3;
                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            carsYearChanged(data) {
                //this.selectedYear = data;
                this.selectDate(data);
            },
            carsColorChanged(data) {
                this.selectedColor = data;
                //this.selectDate(data);
            },
            selectDate(year) {
                var ls = Loading.service({ fullscreen: true });
                if (this.progress.markaProgress > 3) {
                    this.resetMarkaData(3);
                }

                this.selectedYear = year;
                let data = {
                    year: year,
                    model_id: this.selectedModelId
                };

                this.$http.post('\/' + bus.info.lang + '/api/get-body', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allBodies = response.data;
                    this.progress.markaProgress = 4;
                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            setSelectedBodyName(id) {
                this.allBodies.forEach((item) => {
                    if (item.id === id) {
                        this.selectedBodyTypeName = item.name;
                    }
                });
            },
            selectBody() {
                var ls = Loading.service({ fullscreen: true });

                if (this.progress.markaProgress > 5) {
                    this.resetMarkaData(5);
                }

                this.setSelectedBodyName(this.selectedBodyTypeId);
                console.log('selectBody() selectedBodyTypeId', this.selectedBodyTypeId);
                console.log('selectBody() selectedBodyTypeName', this.selectedBodyTypeName);
                let data = {
                    year: this.selectedYear,
                    model_id: this.selectedModelId,
                    body_id: this.selectedBodyTypeId
                };

                this.$http.post('\/' + bus.info.lang + '/api/get-generation', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    this.allGenerations = response.data;
                    this.progress.markaProgress = 5;
                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            selectGeneration() {
                var ls = Loading.service({ fullscreen: true });
                //this.setSelectedBodyName(this.selectedBodyTypeId);
                let data = {
                    generation_id: this.selectedGenerationId
                };

                this.$http.post('\/' + bus.info.lang + '/api/get-option', data, { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                    //this.allOptions = response.data;
                    this.allEngines = response.data.engines;
                    this.allDrives = response.data.drives;
                    this.allGearboxes = response.data.gearboxes;
                    this.allModifications = response.data.modifications;
                    this.progress.markaProgress = 6;
                    ls.close();
                }, response => {
                    ls.close();
                    alert('No connection with server');
                });
            },
            selectEngine() {
                var ls = Loading.service({ fullscreen: true });
                console.log('selectEngine()', this.selectedEngineId);
                if (this.progress.markaProgress > 7) {
                    this.resetMarkaData(6);
                }

                if (this.selectedEngineId !== null) {
                    this.returnedDrives = [];
                    this.allDrives.forEach((item) => {
                        if (this.selectedEngineId === item.engine_id) {
                            this.returnedDrives.push(item);
                        }
                    });
                }

                setTimeout(() => {
                    this.progress.markaProgress = 7;
                    ls.close();
                }, 1000);
            },
            selectDrive() {
                var ls = Loading.service({ fullscreen: true });
                console.log('selectDrive()', this.selectedDriveId);
                if (this.progress.markaProgress > 8) {
                    this.resetMarkaData(7);
                }

                if (this.selectedDriveId !== null) {
                    this.returnedGearboxes = [];
                    this.allGearboxes.forEach((item) => {
                        if (this.selectedEngineId === item.engine_id && this.selectedDriveId === item.drive_id) {
                            this.returnedGearboxes.push(item);
                        }
                    });
                }

                setTimeout(() => {
                    this.progress.markaProgress = 8;
                    ls.close();
                }, 1000);
            },
            selectGearbox() {
                var ls = Loading.service({ fullscreen: true });
                //this.progress.markaProgress = 7;
                console.log('selectGearbox()', this.selectedGearboxId);
                if (this.progress.markaProgress > 9) {
                    this.resetMarkaData(8);
                }

                //if(this.selectedGearboxId !== null) {
                this.returnedModifications = [];
                this.allModifications.forEach((item) => {
                    if (this.selectedEngineId === item.engine_id && this.selectedDriveId === item.drive_id && this.selectedGearboxId === item.gearbox_id) {
                        this.returnedModifications.push(item);
                    }
                });
                //}

                setTimeout(() => {
                    this.progress.markaProgress = 9;
                    ls.close();
                }, 1000);
            },
            selectModification() {
                var ls = Loading.service({ fullscreen: true });
                console.log('selectModification()', this.selectedModificationTypeId);

                let data = {
                    selectedMarka: this.selectedMarka,
                    selectedMarkaImage: '73f02c755563d9f6a6c5dea67bef9f1d.png',
                    selectedMarkaName: 'Mercedes-Benz',
                    selectedModelName: 'CLC AMG',
                    selectedModelId: this.selectedModelId,
                    selectedYear: this.selectedYear,
                    selectedBodyTypeName: this.selectedBodyTypeName,
                    selectedBodyTypeId: this.selectedBodyTypeId,
                    selectedWheelType: this.selectedWheelType,
                    selectedGenerationId: this.selectedGenerationId,
                    selectedEngine: this.selectedEngine,
                    selectedEngineId: this.selectedEngineId,
                    selectedEngineGasEquipment: this.selectedEngineGasEquipment,
                    selectedDrive: this.selectedDrive,
                    selectedDriveId: this.selectedDriveId,
                    selectedGearbox: this.selectedGearbox,
                    selectedGearboxId: this.selectedGearboxId,
                    selectedModificationType: this.selectedModificationType,
                    selectedModificationTypeId: this.selectedModificationTypeId
                };

                this.$localStorage.set('addStep1', JSON.stringify(data));


                setTimeout(() => {
                    this.progress.markaProgress = 10;
                    ls.close();
                }, 1000);
            },
            goToStep(step) {
                this.progress.step = step;
                this.progress.bar = step;
            },

            resetCarData() {
                this.selectedMarka = '';
                this.selectedMarkaImage = '';
                this.selectedMarkaName = '';
                this.selectedModelName = '';
                this.selectedModelId = '';
                this.selectedYear = '';
                this.selectedYearId = '';
                this.VIN = '';
                this.progress.markaProgress = 1;
                this.getMarka = '';
                this.getModel = '';
            },
            collectCarData() {
                var data = {
                    id_mark: this.selectedMarka,
                    id_model: this.selectedModelId,
                    id_year: this.selectedYearId,
                    vin: this.VIN,
                    id: this.autoId
                };

                return data;
            },
            collectAllCarData() {
                var data = {
                    marka: this.selectedMarkaName,
                    id_mark: this.selectedMarka,
                    image: this.selectedMarkaImage,
                    model: this.selectedModelName,
                    id_model: this.selectedModelId,
                    year: this.selectedYear,
                    id_year: this.selectedYearId,
                    id: this.autoId,
                    vin: this.VIN
                };
                //this.sendData.auto = data;

                //console.log('collectAllCarData(): ', JSON.stringify(data));
                return data;
            },
            prepareSendDataAuto(auto) {
                //console.log('prepareSendDataAuto(): ', JSON.stringify(auto));
                this.sendData.auto = auto;
                //console.log('prepareSendDataAuto(): ', JSON.stringify(this.sendData.auto));
            },
            secondStep(num) {
                // num < 0 if no parameter passed
                //console.log('num at start: ', num);
                //console.log('progress.addcar: ', this.progress.addcar);
                if (!this.errors.has('VIN')) {
                    if (num === 0) {
                        this.progress.addcar = false;
                        this.checkCarData();
                    }
                    if (this.progress.addcar && !this.checkCarData()) {
                        var ls = Loading.service({ fullscreen: true });
                        this.$http.post('\/' + bus.info.lang + '/dashboard/add-car', this.collectCarData(), { headers: { 'X-CSRF-TOKEN': bus.info.key } }).then(response => {
                            var el = {
                                marka: this.selectedMarkaName,
                                model: this.selectedModelName,
                                year: this.selectedYear,
                                image: this.selectedMarkaImage,
                                vin: this.VIN,
                                markaId: this.selectedMarka,
                                id_mark: this.selectedMarka,
                                modelId: this.selectedModelId,
                                id_model: this.selectedModelId,
                                yearId: this.selectedYearId,
                                id_year: this.selectedYearId,
                                id: response.data.auto_id
                            };
                            console.log('add-car: ', JSON.stringify(el));
                            this.mycars.push(el);
                            this.resetCarData();
                            //this.prepareSendDataAuto(el);
                            this.progress.addcar = false;
                            ls.close();
                        }, response => {
                            ls.close();
                            alert('No connection with server');
                        });
                    } else {
                        // var el = {
                        //     marka: this.selectedMarkaName,
                        //     model:this.selectedModelName,
                        //     year:this.selectedYear,
                        //     image:this.selectedMarkaImage,
                        //     vin:this.VIN,
                        //     markaId: this.selectedMarka,
                        //     modelId: this.selectedModelId,
                        //     yearId: this.selectedYearId,
                        // };
                        // this.mycars.push(el);
                        // this.resetCarData();
                        console.log('num: ', num);
                        this.progress.step = 2;
                        this.progress.bar = 2;
                        if (num < 0) {
                            this.prepareSendDataAuto(this.mycars[0]);
                        } else if (num === 0) {
                            let auto = this.collectAllCarData();
                            this.prepareSendDataAuto(auto);
                        } else {
                            this.prepareSendDataAuto(this.mycars[num - 1]);
                        }
                    }
                }
            },
            changeStep(step) {
                if (this.progress.bar >= step) {
                    this.progress.step = step;
                }
            }
        },
        mounted() {
            if (this.progress.bar === 3) {
                this.getStep3Data();
            }
        }
    });
}