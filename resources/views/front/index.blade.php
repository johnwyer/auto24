@extends('.front/layout/layout')
@section('meta')
@stop
@section('content')
    {{ ScriptVariables::render('inf') }}
    <div class="home" id="home" v-cloak>
        <car-select-modal v-if="choose.auto"
                          v-on:close="closeAutoChooseSlide"
                          class="modal-auth modal-home"
                          key="modal-home">
            <div class="map-choose" ref="modalHome">
                <div class="map-choose_wrapper map-choose_wrapper_auto">
                    <div v-if="choose.addcar">
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
                            <template v-cloak>
                                <el-scrollbar :noresize="false"
                                              :native="false"
                                              :ref="'scroll-cars'"
                                              :view-style="{'max-height':'410px'}">
                                    <div class="order-marks_table">
                                        <div class="order-marks_block"
                                             v-for="(item,index) in allMarkaComputed"
                                             :title="item.name_ru"
                                             :key="item.id"
                                             v-on:click="selectM(item.id,item.image,item.name_ru)"
                                             :style="{'backgroundImage': 'url({{asset('/images/marks/')}}/' + item.image + ')'}">
                                        </div>
                                    </div>
                                </el-scrollbar>
                            </template>
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
                                         v-on:click="selectModel(item.id,item.name)">
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
                                         v-on:click="selectDate(item.id,item.year)">
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
                                    </div>
                                </button>
                            </div>
                        </section>
                        {{-- //VIN section --}}
                    </div>
                </div>
            </div>
        </car-select-modal>
        <autoservice-select-modal v-if="choose.main"
                                  v-on:close="closeServiceChoose(false)"
                                  class="modal-auth modal-home"
                                  key="modal-home">
            <div class="map-choose"
                 :class="{'large': servicesInInput.length > 0}">
                <div class="map-choose_wrapper">
                    <section class="order-services">
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
                                                    v-on:click.prevent="otherServicesImageUpload">
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

                    <section class="order-services_list price"
                             v-if="servicesInInput.length > 0"
                             v-cloak>
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
                </div>
            </div>
        </autoservice-select-modal>
        <section class="home-order" style="background-image: url('{{asset('/img/main.jpg')}}')">
            <div class="home-content">
                <div class="content-inner">
                    <h2 class="section-title">Автосервисы Молдовы</h2>
                    <h3 class="section-subtitle">весь список всегда под рукой</h3>
                    <div class="home-order-form">
                        <div class="form-group">
                            <label class="control-label">Автомобиль</label>
                            <div class="control-wrapper">
                                <input type="text"
                                       class="control-input"
                                       :class="{'is-selected': autoInInput.length > 0}"
                                       v-model="autoInInput"
                                       readonly
                                       placeholder="Выберите марку"
                                       v-on:click="choose.auto = !choose.auto, choose.main = false" />
                                <div class="arrow"
                                     v-if="autoInInput.length === 0"
                                     v-cloak>
                                    <i class="icon-arrow-down"></i>
                                </div>
                                <div class="close"
                                     v-if="autoInInput.length > 0"
                                     v-on:click.stop="closeAutoChoose"
                                     v-cloak>
                                    <i class="icon-close"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Что именно нужно сделать?</label>
                            <div class="control-wrapper">
                                <input type="text"
                                       class="control-input"
                                       :class="{'is-selected in': servicesInInput.length > 0}"
                                       v-model="servicesInInput"
                                       readonly
                                       placeholder="Выберите услугу"
                                       v-on:click="choose.main = !choose.main, choose.auto = false" />
                                <div class="arrow"
                                     v-if="servicesInInput.length === 0"
                                     v-cloak>
                                    <i class="icon-arrow-down"></i>
                                </div>
                                <div class="close"
                                     v-if="servicesInInput.length > 0"
                                     v-on:click.stop="closeServiceChoose(true)"
                                     v-cloak>
                                    <i class="icon-close"></i>
                                </div>
                                <div class="count" v-if="getServicesInInput()" v-text="getServicesInInput()" v-cloak></div>
                            </div>
                        </div>
                        <div class="form-group type-btns">
                            <button class="btn-gray" type="button" v-on:click="searchOnMap">Поиск на карте</button>
                            <button class="btn-red" type="button" v-on:click="sendToAllServices">Заявка во все сервисы</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="home-order-down" style="background-image: url('{{asset('/img/main_down.jpg')}}')">
            <div class="home-order-down_in">
                <b>@lang('v.need_truck')</b>
                <p>@lang('v.need_truck_text')</p>
                <div>
                    {{--<img src="{{asset('/img/phone-call.svg')}}" class="img" alt="">--}}
                    <a href="tel:+373022123456" class="tel" title=""><i class="icon-phone-call"></i>022 123-456</a>
                </div>
            </div>
        </section>
        {{--<img src="{{asset('/img/zaglushka.jpg')}}" alt="" style="width:100%;">--}}
        <section class="home-links">
            <div class="home-content">
                <div class="content-inner">
                    <div class="latest-orders">
                        <h2 class="section-title">Последние заявки</h2>
                        <div class="items-list">
                            @if(count($orders))
                                @foreach($orders as $item)
                                    @if(!$item['errors'])
                                        <div class="item">
                                            <div class="item-image"><img src="{{asset('images/marks/'.$item['order']->image)}}" alt=""></div>
                                            <div class="item-title"><p>{{$item['order']->marka}} {{$item['order']->model}}</p></div>
                                            <div class="item-content"><p>
                                                    @foreach($item['service'] as $ser)
                                                        @if(isset($ser['child']))
                                                            @foreach($ser['child'] as $child)
                                                                @if($loop->last)
                                                                    {{$child}}
                                                                @else
                                                                    {{$child}},
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{$ser['name']}}
                                                        @endif
                                                    @endforeach
                                                </p></div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="services-of-the-month">
                        <h2 class="section-title">Автосервисы месяца</h2>
                        <div class="items-list">
                            @foreach($top_auto as $auto_s)

                                <div class="item">
                                    <?php
                                    if(!empty($auto_s->image ) && file_exists(public_path().'/images/autoservice/'.$auto_s->image)){
                                        $url_img = asset('images/autoservice/'.$auto_s->image);
                                        $no_image = false;

                                    }else{
                                        $url_img = asset('/img/elevator.svg');
                                        $no_image = true;
                                    }
                                    ?>

                                    <div class="item-image">
                                        <a href="{{URL::route('autoservice_page',$auto_s->slug)}}" title="">
                                            @if(!$no_image)
                                            <div class="item-image-wrapper" style="background-image: url({{$url_img}})"></div>
                                            @else
                                                <div class="item-image-wrapper">
                                                    <img class="no-image" src="{{$url_img}}" alt="" />
                                                </div>
                                            @endif
                                        </a>
                                            {{--<img @if($no_image) class="no-image" @endif src="{{$url_img}}" alt="" /></a>--}}
                                    </div>

                                    <div class="item-content">
                                        <div class="item-title"><a href="{{URL::route('autoservice_page',$auto_s->slug)}}">{{$auto_s->name}}</a></div>
                                        <div class="item-address">{{$auto_s->district}} {{$auto_s->city}} {{$auto_s->address}}</div>

                                        <div class="item-rating">
                                            <el-rate
                                                    :value="{{$auto_s['rating']['rating']}}"
                                                    allow-half
                                                    disabled-void-color="#c8c8c8"
                                                    show-text
                                                    :colors="['#ffc323','#ffc323','#ffc323']"
                                                    disabled>
                                            </el-rate>
                                        <span class="rate-counter">
                                            <span v-text="{{$auto_s['rating']['total']}} > 0 ? {{$auto_s['rating']['total']}} : 'нет'"></span>
                                            <span v-text="returnText({{$auto_s['rating']['total']}},['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>

                                        </span>
                                        </div>
                                    </div>
                                    <div class="item-subscribe">
                                        <a href="{{URL::route('order',$auto_s->slug)}}" class="btn-blue" title="Запись на ремонт">Запись на ремонт</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="home-diagnostics" style="background-image: url(/img/diagnostics.jpg);">
            <div class="home-content">
                <div class="content-inner">
                    <h2 class="section-title">Бесплатная диагностика вашего автомобиля</h2>
                    <a href="#" class="btn-red" title="Подробнее">Подробнее</a>
                </div>
            </div>
        </section>
        <section class="home-how-it-works">
            <div class="home-content">
                <div class="content-inner">
                    <h2 class="section-title">Как здесь все работает</h2>
                    <p class="section-subtitle">На нашем сайте вы можете отправить заявку во все сервисы либо<br />найти подходящий сервис по цене на карте</p>
                    <how-it-works></how-it-works>
                </div>
            </div>
        </section>
        <section class="home-news">
            <div class="home-content">
                <div class="content-inner">
                    <h2 class="section-title">Последние новости</h2>
                    <div class="items-list">
                        <div class="item">
                            <a href="#" title="">
                                <div class="item-image"><img src="/img/temp/n1.jpg" alt="" /></div>
                                <div class="item-title">В России появится новый доступный кроссовер</div>
                                <div class="item-date">26 Апреля</div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#" title="">
                                <div class="item-image"><img src="/img/temp/n2.jpg" alt="" /></div>
                                <div class="item-title">Источник: на «УАЗ Патриот» будут ставить задние дисковые тормоза</div>
                                <div class="item-date">26 Апреля</div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#" title="">
                                <div class="item-image"><img src="/img/temp/n3.jpg" alt="" /></div>
                                <div class="item-title">Ограниченная партия лаймовой Lada Vesta в «Автогруп Крым»</div>
                                <div class="item-date">16 Апреля</div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#" title="">
                                <div class="item-image"><img src="/img/temp/n4.jpg" alt="" /></div>
                                <div class="item-title">Audi SQ5 с 354-сильным мотором оценили в 4,1 млн рублей</div>
                                <div class="item-date">13 Апреля</div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="#" title="">
                                <div class="item-image"><img src="/img/temp/n5.jpg" alt="" /></div>
                                <div class="item-title">ГИБДД выпустила разъяснения по новым правилам выдачи водительских прав</div>
                                <div class="item-date">10 Апреля</div>
                            </a>
                        </div>
                    </div>
                    <div class="item-more">
                        <a class="btn-gray" href="#" title="Все новости">Все новости</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop
