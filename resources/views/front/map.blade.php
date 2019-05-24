@extends('.front/layout/layout')

@section('content')
    {{ ScriptVariables::render('inf') }}
    <div id="findonmap" class="map">
        <div id="fmap" class="map-google"></div>

        <div id="fmap-gps-indicator-control" class="fmap-gps-indicator-control">
            <a href="#" id="fmap-gps-indicator" title="Мое местоположение"><i class="icon-gps-indicator"></i></a>
        </div>
        <div class="fmap-zoom-control" id="fmap-zoom-control">
            <ul>
                <li><a href="#" id="fmap-zoom-in" title="Увеличить карту"><i class="icon-plus2"></i></a></li>
                <li><a href="#" id="fmap-zoom-out" title="Уменьшить карту"><i class="icon-minus2"></i></a></li>
            </ul>
        </div>

        <div class="map-side" id="map-sidebar">
            <div class="map-side_wrapper">
                <div class="map-side_top">
                    <div class="block">
                        <div class="title">@lang('v.auto')</div>
                        <div class="input">
                            <input type="text"
                                   readonly
                                    :class="{active: choose.auto || autoInInput.length}"
                                    v-model="autoInInput"
                                    placeholder="@lang('v.select_auto')"
                                    v-on:click="choose.auto = !choose.auto, choose.main = false" />
                            <div class="close" v-if="choose.auto || autoInInput.length" v-cloak>
                                <svg  v-on:click.stop="closeAutoChoose" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                      viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve" width="13px" height="13px">
                                <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88
                                    c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242
                                    C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879
                                    s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="title">@lang('v.exact_need')</div>
                        <div class="input">
                            <input type="text"
                                   readonly
                                   :class="{active: choose.main || servicesInInput.length}"
                                   v-model="servicesInInput"

                                   placeholder="@lang('v.select_service')"
                                   v-on:click="choose.main = !choose.main, choose.auto = false" />
                            <div class="close" v-if="choose.main || servicesInInput.length" v-cloak>
                                <svg  v-on:click.stop="closeServiceChoose(true)" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                      viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve" width="13px" height="13px">
                                <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88
                                    c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242
                                    C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879
                                    s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/>
                                </svg>
                            </div>
                            <div class="count" v-if="getServicesInInput()" v-text="getServicesInInput()" v-cloak></div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="title">@lang('v.tip_autoservice')</div>
                        <div class="block-wrapper">
                            <el-checkbox v-model.boolean="sort.type.of" el-name="sort_type_of">@lang('v.official_dealer')</el-checkbox>
                            <el-checkbox v-model.boolean="sort.type.universal" el-name="sort_type_universal">@lang('v.universal')</el-checkbox>
                        </div>
                    </div>

                    <div class="block" v-cloak>
                        {{--<div class="title">Выберите город</div>--}}
                        <get-address ref="mainChooseAddress"
                                     v-on:locationchanged="locationchanged"
                                     v-on:districtchanged="locationchanged"
                                     lang="{{Lang::getLocale()}}"></get-address>
                        <span class="error adres"
                              v-if="errors.has('regService.selected_district')">@lang('v.select_district2')</span>
                        <span class="error adres"
                              v-if="errors.has('regService.selected_city')">@lang('v.select_city2')</span>
                        <input type="text"
                               hidden
                               name="selected_district"
                               v-model="selectedAddress.district" />
                        <input type="text"
                               hidden
                               v-validate="'required'"
                               name="selected_city"
                               v-model="selectedAddress.city" />
                    </div>
                </div>

                <div class="map-side_middle">
                    <template>
                        <el-scrollbar
                                :noresize="false"
                                :native="false"
                                v-loading="autoservicesLoading"
                                :view-style="{'max-height':some.scrollHeight}">
                            <div class="map-auto_wrapper">
                                <div class="no-data-found" v-if="some.noDataFound">
                                    <p>@lang('v.nothing_request')</p>
                                </div>
                                <div class="map-auto"
                                     v-on:mouseover="addClass($event,true)"
                                     v-on:mouseleave="addClass($event,false)"
                                     v-on:click="selectAuto($event,item)"
                                     :id="'auto_map'+item.id"
                                     :tabindex="888 + index"
                                     :key="'autoservice' + item.id"
                                     :data-in="'autoservice' + item.id"
                                     v-for="(item, index) in info.auto">
                                    <div class="map-auto-inner">
                                        <div class="image">
                                            <img :src="item.image ? ('{{asset('images/autoservice')}}/'+item.image) : autoImageDefault" alt="" />
                                        </div>
                                        <div class="text">
                                            <div class="title">
                                                <a :href="'{{URL::route('autoservice_page','/')}}/'+ item.slug"
                                                   v-text="item.name"
                                                   class="title-link"
                                                   :id="'link'+item.id"></a>
                                            </div>
                                            <div class="adr">
                                                <p v-text="returnAddress(item)"></p>
                                            </div>
                                            <div class="rate" :id="'rate'+item.id">
                                                <el-rate
                                                        v-if="item.rating.rating > 0"
                                                        :value="item.rating.rating | numberF"
                                                        allow-half
                                                        disabled-void-color="#c8c8c8"
                                                        show-text
                                                        :colors="['#ffc323','#ffc323','#ffc323']"
                                                        disabled>
                                                </el-rate>
                                                <span>
                                                    <b v-text="item.rating.total > 0 ? item.rating.total : '@lang("v.not")'"></b>
                                                    <span v-text="returnText(item.rating.total,['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-scrollbar>
                    </template>
                </div>
                <div class="map-side_bottom">
                    <button class="map-submit" v-on:click="sendInfoSubmit">
                        <div class="map-submit_wrapper">
                            <div v-if="!checkedServicesAutoByUser.length" v-cloak>@lang('v.order_all_autoservice')</div>
                            <div v-else v-cloak>@lang('v.order_all_selected_autoservice') (<b v-text="checkedServicesAuto.length"></b>)</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <transition v-on:enter="chooseSlideIn" v-on:leave="chooseSlideOut" :css="false">
            <div class="map-choose"
                 v-if="choose.auto">
                <div class="map-choose_wrapper map-choose_wrapper_auto">
                    <div class="close" v-on:click="closeAutoChooseSlide" {{--v-on:click="choose.auto = false"--}} {{--v-on:click.stop="closeAutoChoose"--}}>
                        <svg  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                              viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve" width="17px" height="17px">
                                <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88
                                    c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242
                                    C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879
                                    s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/>
                                </svg><span>@lang('v.close')</span>
                    </div>
                    <div v-if="choose.addcar">
                        {{--
                        @if(Auth::guard('customer')->check())
                            <div class="choose-back"><img v-on:click="choose.addcar = false, choose.progress = 1" src="{{asset('img/back-arrow.svg')}}" alt=""></div>
                        @endif
                        --}}

                        <section class="order-body_ul" v-if="choose.progress > 1 ">
                            <ul>
                                <li class="img" v-if="choose.progress > 1" key="foto"><img v-on:click="choose.progress = 1, updateCarData(1)" :src="'{{asset('/images/marks')}}/' + selectedMarkaImage" alt=""></li>
                                <li class="marka" v-if="choose.progress > 1" key="marka"><span v-on:click="choose.progress = 1, updateCarData(1)" v-text="selectedMarkaName"></span></li>
                                <li class="model" v-if="choose.progress > 2" key="model"><span v-on:click="choose.progress = 2, updateCarData(2)" v-text="selectedModelName"></span></li>
                                <li class="year" v-if="choose.progress > 3" key="year"><span v-on:click="choose.progress = 3, updateCarData(3)" v-text="selectedYear"></span></li>
                            </ul>
                        </section>
                            {{-- MARKA section --}}
                        <section class="order-body_up" v-if="choose.progress === 1" key="marka">
                            <h2 class="order-body_title">@lang('v.enter_name')</h2>
                            <div class="order-marks_find">
                                <input type="text" v-model="getMarka" placeholder="@lang('v.example_bmw')" />
                            </div>
                            <h2 class="order-body_title">@lang('v.select_in_list')</h2>
                            <div v-cloak>
                                <div class="order-marks_table">
                                    <div class="order-marks_block"
                                         v-for="(item,index) in allMarkaComputed"
                                         :title="item.name_ru"
                                         :key="item.id"
                                         v-on:click="selectM(item.id, item.image, item.name_ru)"
                                         :style="{'backgroundImage': 'url({{asset('/images/marks/')}}/' + item.image + ')'}">
                                    </div>
                                </div>
                            </div>
                        </section>
                            {{-- //MARKA section --}}
                            {{-- MODEL section --}}
                        <section class="order-body_up" v-else-if="choose.progress === 2" key="model">
                            <h2 class="order-body_title">@lang('v.name_model')</h2>
                            <div class="order-marks_find">
                                <input type="text"  placeholder="@lang('v.for_example')" v-model="getModel">
                            </div>
                            <h2 class="order-body_title">@lang('v.select_in_list')</h2>
                            <div v-cloak>
                                <div class="order-models_table">
                                    <div class="order-models_block"
                                         v-for="(item,index) in allModelsComputed"
                                         :key="item.id"
                                         v-on:click="selectModel(item.id, item.name)">
                                        <span v-text="item.name" :title="item.name"></span>
                                    </div>
                                </div>
                            </div>
                        </section>
                            {{-- //MODEL section --}}
                            {{-- YEAR section --}}
                        <section class="order-body_up" v-else-if="choose.progress === 3" key="year">
                            <h2 class="order-body_title">@lang('v.select_year')</h2>
                            <div v-cloak>
                                <div class="order-date_table">
                                    <div class="order-date_block"
                                         v-for="(item,index) in allDate"
                                         :key="item.id"
                                         v-on:click="selectDate(item.id, item.year)">
                                        <span v-text="item.year" :title="item.year"></span>
                                    </div>
                                </div>
                            </div>
                        </section>
                            {{-- //YEAR section --}}
                        {{-- VIN section --}}
                        <section class="order-body_up" v-else-if="choose.progress === 4" key="vin">
                            <div class="order-vin_wrapper">
                                <div class="order-vin_input">
                                    <h2 class="order-body_title">@lang('v.enter_vin')</h2>
                                    <div class="input-wr">
                                        <input type="text"
                                               class="input"
                                               name="VIN"
                                               v-model="VIN"
                                               maxlength="17"
                                               minlength="17"
                                               v-validate="'alpha_num|min:17|max:17'"
                                               data-vv-validate-on="blur"
                                               v-on:focus="removeError('VIN')"
                                               {{--v-on:blur="validateVIN"--}}
                                               autocomplete="off"
                                               :class="{error: errors.has('VIN')}"/>
                                        <span :class="{error: errors.has('VIN')}"
                                            v-if="errors.has('VIN')"
                                            v-cloak>@lang('v.invalid_vin_code')</span>
                             </div>
                             <p class="text">
                                 @lang('v.enter_vin_text')
                             </p>
                         </div>
                         <div class="order-vin_image">
                             <img src="{{asset('img/vin-auto.png')}}" alt="">
                         </div>
                     </div>
                     <div class="order-next_services">
                         <button class="btn-red"
                                 v-on:click="makeAutoSelected">
                             <div class="order-next_button">
                                 <span>@lang('v.select_my_auto')</span>
                                 <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                 <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                     345.606,368.713 476.213,238.106 "/>
                             </svg>
                             </div>
                         </button>
                         {{--
                         <button class="btn-red"
                                 v-on:click=" @if(Auth::guard('customer') -> user()) addToMyCar @else makeAutoSelected @endif">
                             <div class="order-next_button">
                                 <span> @if(Auth::guard('customer') -> user()) Добавить в мои автомобили  @else Выбрать автомобиль @endif</span>
                                 <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                 <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                     345.606,368.713 476.213,238.106 "/>
                             </svg>
                             </div>
                         </button>
                         --}}
                            </div>
                        </section>
                            {{-- //VIN section --}}
                    </div>
                    {{-- added cars  --}}
                    {{--
                    <div v-if="choose.progress === 1 && !choose.addcar" v-cloak>
                        @if(Auth::guard('customer') -> user())
                            <section class="order-body_up">
                                <h2 class="order-body_title">Выберите авто из уже ранее добавленных или добавьте новый</h2>
                                <div class="order-cars_wrapper">
                                    <div class="order-cars">
                                        <div class="car" v-for="(item , index) in choose.mycars" >
                                            <ul v-on:click="putAutoToSelected(item)" >
                                                <li class="img"><img  :src="'{{asset('/images/marks')}}/' + item.image" alt=""></li>
                                                <li class="marka"><span  v-text="item.marka"></span></li>
                                                <li class="model"><span  v-text="item.model"></span></li>
                                                <li class="year"><span  v-text="item.year"></span></li>
                                                <li class="vin"><span  v-text="item.vin"></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="order-cars_add">
                                        <button class="btn-red" v-on:click="choose.addcar = true, resetCarData()">Добавить новый автомобиль</button>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                    --}}
                    {{-- //added cars --}}
                </div>
            </div>
        </transition>

        <transition v-on:enter="chooseSlideIn" v-on:leave="chooseSlideOut" :css="false">
            <div class="map-choose"
                 v-if="choose.main">
                <div class="map-choose_wrapper">
                    <section class="order-services">
                        <div class="close" {{--v-on:click="choose.main = false"--}} v-on:click.stop="closeServiceChoose(false)">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                  viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve" width="17px" height="17px">
                                <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88
                                    c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242
                                    C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879
                                    s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/>
                                </svg><span>@lang('v.close')</span>
                        </div>
                        <h2 class="order-body_title">@lang('v.introduce_service')</h2>
                        <div class="order-services_find">
                            <service-find :price-list="priceList" :selectedarray.sync="serviceSelected"></service-find>
                        </div>
                        <div class="order-services_wr">
                            <h2 class="order-body_title">@lang('v.select_special')</h2>
                            <div class="order-services_price">
                                <div v-for="(item,index) in priceList" :ref="item.id" class="block" v-on:click="showInner($event)">
                                    <div class="block-in">
                                        <div v-if="checkedServicesCounter[item.id] === 0"
                                             :ref="item"
                                             class="img"
                                             v-html="item.image">
                                        </div>
                                        <div v-else
                                             class="counter"
                                             v-text="checkedServicesCounter[item.id]">
                                        </div>
                                        <div v-text="item.name_ru" class="text"></div>
                                    </div>

                                    <div class="inner" hidden>
                                        <div class="inner-wrapper">
                                            <div class="inner-title">
                                                <svg version="1.1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        width="19px" height="6px">
                                                    <path fill-rule="evenodd"  fill="rgb(3, 99, 205)"
                                                          d="M5.370,6.000 L-0.000,3.000 L5.370,0.001 L5.370,2.249 L19.000,2.249 L19.000,3.751 L5.370,3.751 L5.370,6.000 Z"/>
                                                </svg>
                                                <span v-text="item.name_ru"></span>
                                            </div>

                                            <div class="inner-in" v-if="item.service.length" v-on:click.stop>
                                                <div class="inner-scroll">
                                                    <el-scrollbar :noresize="false"
                                                                  :native="false"
                                                                  :ref="'scroll' + index"
                                                                  :view-style="{'max-height':'400px'}">
                                                        <template v-if="item.service.length">
                                                            <div class="inner-block">
                                                                <ul  class="inner-ul services-list">
                                                                    <li v-for="(item, index) in item.service"
                                                                        :tabindex="index + 20"
                                                                        :ref="item.id + '_' + item.ident">
                                                                        <div>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       hidden
                                                                                       :value="item"
                                                                                       v-model="checkedServices">
                                                                                <div class="checkbox-icon"><i class="icon-check"></i></div>
                                                                                <span v-text="item.name_ru"></span>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <b v-text="'~' + item.price"></b>
                                                                            <span>лей</span>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </template>
                                                    </el-scrollbar>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block" v-on:click="toggleOtherServices()">
                                    <div class="block-in block-other-services">
                                        <div class="img" v-if="checkedServicesCounter[0] === 0">
                                            <svg version="1.1"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 width="27" height="8"
                                                 viewBox="0 0 27 8">
                                                <path d="M1399,448.844a3.5,3.5,0,1,1-3.5,3.5A3.5,3.5,0,0,1,1399,448.844Zm10,0a3.5,3.5,0,1,1-3.5,3.5A3.5,3.5,0,0,1,1409,448.844Zm10,0a3.5,3.5,0,1,1-3.5,3.5A3.5,3.5,0,0,1,1419,448.844Z" transform="translate(-1395.5 -448.844)"/>
                                            </svg>
                                        </div>
                                        <div v-else
                                             class="counter"
                                             v-text="checkedServicesCounter[0]">
                                        </div>
                                        <div class="text">@lang('v.other_service')</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <transition name="fade">
                            <div class="order-services_other" v-if="otherServices.progress" v-cloak>
                                <h2 class="order-body_title">@lang('v.should_need')</h2>
                                <div class="order-services_other-textarea-wrapper">
                                    <textarea name="" id="" cols="30" rows="10"
                                              placeholder="Например: замена масла"
                                              v-model="otherServices.message"
                                              :maxlength="otherServices.messageLimit"
                                             {{-- v-on:keyup="checkOtherServicesMessage($event, 'false')"--}}
                                              v-on:keydown="checkOtherServicesMessage($event, 'false')"
                                              {{--v-on:keypress="checkOtherServicesMessage($event, 'false')"--}}
                                              v-on:keyup.delete="checkOtherServicesMessage($event, 'true')"></textarea>
                                </div>
                                <div class="order-services_other-footer">
                                    <div class="top-row clearfix">
                                        <div class="button-wrapper">
                                            <button class="btn-white"
                                                    v-on:click.prevent="otherServicesImageUpload"
                                                    >
                                                <div class="btn-white_inner">
                                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                         viewBox="0 0 792 792" style="enable-background:new 0 0 792 792;" xml:space="preserve">
                                                        <g>
                                                            <path d="M552.061,229.118c-13.685,0-24.723,10.595-24.723,24.29v358.911c0,36.646-13.242,66.662-38.851,92.271
                                                                s-56.067,38.851-93.146,38.851c-36.204,0-67.105-12.8-92.713-38.408c-25.608-25.607-38.408-56.509-38.408-92.713V476.79v-72.398
                                                                V226.029v-85.64v-0.886v-0.885c0-24.28,8.938-45.358,26.051-62.688c17.33-17.546,38.398-26.927,63.573-26.927
                                                                c25.166,0,46.244,9.381,63.563,26.927c17.113,17.33,26.052,38.408,26.052,62.688v0.885v1.762v400.427
                                                                c0,25.156-19.421,44.586-44.586,44.586c-24.281,0-44.153-20.306-44.153-44.586V331.984c0-13.685-10.596-24.28-24.28-24.28
                                                                s-24.28,10.595-24.28,24.28v209.708c0,26.041,8.834,48.117,26.927,66.22c18.102,18.102,39.736,26.927,65.787,26.927
                                                                c26.041,0,47.9-9.051,66.219-26.927c17.886-17.452,26.928-39.293,26.928-66.22V139.503v-0.885
                                                                c0-37.965-13.798-70.524-40.612-97.564C424.368,13.788,391.809,0,353.844,0c-37.974,0-70.533,13.788-97.573,41.054
                                                                c-26.813,27.04-40.611,59.599-40.611,97.564v0.885v86.526v178.363v72.398v135.528c0,49.889,17.659,92.271,52.535,127.146
                                                                S345.452,792,395.341,792s93.146-16.773,128.465-52.093c35.318-35.318,52.535-78.576,52.535-127.589V253.408
                                                                C576.341,239.713,565.745,229.118,552.061,229.118z"/>
                                                        </g>
                                                    </svg>
                                                    <span>@lang('v.load_foto')</span>
                                                </div>
                                            </button>
                                            <input type="file"
                                                   v-on:change="otherServicesImageChanged"
                                                   ref="uploadfoto"
                                                   name="uploadfoto"
                                                   accept="image/*"
                                                   hidden />
                                            <span class="upload-notice">@lang('v.load_foto_text')</span>
                                        </div>
                                        <div class="textarea-counter">@lang('v.was_charset') <span v-text="otherServicesMessageCounter()"></span></div>
                                    </div>
                                    <div class="images-list" v-if="otherServices.imagesThumbs.length">
                                        <ul>
                                            <li v-for="(item, index) in otherServices.imagesThumbs" class="list-item">
                                                <div class="image-wrapper"
                                                     :style="{'backgroundImage':'url('+ otherServices.imagesThumbs[index] +')'}"
                                                     :title="item[0].name">
                                                </div>
                                                <span class="remove-image" title="" v-on:click="otherServicesImageDelete(index)"><i class="icon-close"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </section>

                    <section class="order-services_list price">
                        <div class="wrapper">
                            <div class="list">
                                <div class="block" v-if="checkedPrice > 0">
                                    <span>@lang('v.aproximativ_price') - <h4 class="list-title" v-text="checkedPrice"></h4><strong> @lang('v.mdl')</strong></span>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-red" v-on:click="continueServiceChoose">@lang('v.continue')</button>
                            </div>
                        </div>
                    </section>
                    {{--
                    <section class="order-services_list" v-if="checkedServices.length">
                        <div class="wrapper">
                            <div class="list">
                                <h4 class="list-title">Выбранные услуги: </h4>
                                <div class="block" v-for="(item, index) in checkedServices">
                                    <span v-text="item.name_ru"></span>
                                    <svg version="1.1"
                                         v-on:click="removeFromChecked(index)"
                                         width="12px" height="12px"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         x="0px" y="0px" viewBox="0 0 371.23 371.23"
                                         style="enable-background:new 0 0 371.23 371.23;fill:#e30613;"
                                         xml:space="preserve">
                                            <polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23
                                                185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
                                        </svg>
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn-red" v-on:click="continueServiceChoose">Продолжить</button>
                            </div>
                        </div>
                    </section>
                    --}}
                </div>
            </div>
        </transition>
    </div>

    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
@stop

@section('script')

@stop