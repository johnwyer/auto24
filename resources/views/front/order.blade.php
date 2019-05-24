@extends('.front/layout/layout')
@section('meta')

@stop
@section('content')
    {{ ScriptVariables::render('inf') }}

    <div class="order" id="order">

        <phone-modal v-if="modal.confirmPhoneRemoving"
                     v-on:close="modal.confirmPhoneRemoving = false"
                     class="modal-auth modal-chat"
                     key="modal-auth"
                     v-cloak>
            <div class="block">
                <i18n tag="p" path="message.delete_phone_number">
                    <strong place="phone">+373 @{{ modal.phone.phone }}</strong>
                </i18n>
            </div>
            <div class="block">
                <el-button type="primary"
                           class="btn-blue"
                           v-on:click="$refs.userPhonesComponent.deletePhoneNumber()"
                           >@lang('v.confirmed')</el-button>
                <el-button type="secondary"
                           class="btn-gray"
                           v-on:click="modal.confirmPhoneRemoving = false">@lang('v.canceled_btn')</el-button>
            </div>
        </phone-modal>


        <div class="order-title">
            <h1>@lang('v.aplication_cost')</h1>
        </div>

        <div class="order-wrapper">
            <div class="order-head">
                <ul>
                    <li :class="{active: progress.step === 1}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(1)">
                            <svg class="order-head_svg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="26px" height="26px" viewBox="0 0 447.645 447.645" style="enable-background:new 0 0 447.645 447.645;" xml:space="preserve">
                                <path d="M447.639,244.402c0-8.805-1.988-17.215-5.578-24.909c-0.37-1.956-0.793-3.909-1.322-5.89l-38.884-96.365l-0.263-0.867
                                    c-13.605-40.509-32.963-78.001-82.049-78.001H131.868c-50.296,0-68.069,38.421-81.972,77.776l-40.673,96.6
                                    C3.343,222.167,0,232.944,0,244.402v29.986c0,4.636,0.548,9.171,1.59,13.539C0.577,290.566,0,293.41,0,296.408v89.185
                                    c0,13.078,10.602,23.682,23.68,23.682h49.14c13.071,0,23.673-10.604,23.673-23.682v-44.599h257.46v44.599
                                    c0,13.078,10.604,23.682,23.683,23.682h46.326c13.083,0,23.683-10.604,23.683-23.682v-89.195c0-2.987-0.583-5.844-1.588-8.474
                                    c1.038-4.375,1.588-8.905,1.588-13.54v-29.981H447.639z M78.754,125.821c15.483-43.683,27.934-57.018,53.114-57.018h187.664
                                    c24.995,0,38.913,14.873,53.056,56.83l28.375,57.502c-9.265-3.431-19.461-5.335-30.173-5.335H76.849
                                    c-9.645,0-18.862,1.551-27.366,4.358L78.754,125.821z M103.129,285.776H51.281c-9.335,0-16.906-7.578-16.906-16.912
                                    c0-9.337,7.571-16.91,16.906-16.91h51.848c9.339,0,16.91,7.573,16.91,16.91C120.039,278.198,112.463,285.776,103.129,285.776z
                                     M286.284,282.389h-120.6c-5.913,0-10.704-4.794-10.704-10.704c0-5.921,4.791-10.713,10.704-10.713h120.6
                                    c5.92,0,10.71,4.792,10.71,10.713C296.994,277.595,292.204,282.389,286.284,282.389z M395.051,285.776h-51.846
                                    c-9.343,0-16.91-7.578-16.91-16.912c0-9.337,7.573-16.91,16.91-16.91h51.846c9.343,0,16.916,7.573,16.916,16.91
                                    C411.967,278.198,404.394,285.776,395.051,285.776z"/>
                            </svg>
                            <span class="order-head_text">@lang('v.auto')</span>
                        </div>
                        <div class="order-head_next"><img src="{{asset('/img/right-arrow_2.svg')}}" alt=""></div>
                    </li>
                    <li :class="{active: progress.step === 2}">
                        <div class="order-head_back">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(2)">
                            <svg class="order-head_svg" width="26px" height="26px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 231.233 231.233" style="enable-background:new 0 0 231.233 231.233;" xml:space="preserve">
                                <path d="M230.505,102.78c-0.365-3.25-4.156-5.695-7.434-5.695c-10.594,0-19.996-6.218-23.939-15.842
                                    c-4.025-9.855-1.428-21.346,6.465-28.587c2.486-2.273,2.789-6.079,0.705-8.721c-5.424-6.886-11.586-13.107-18.316-18.498
                                    c-2.633-2.112-6.502-1.818-8.787,0.711c-6.891,7.632-19.27,10.468-28.836,6.477c-9.951-4.187-16.232-14.274-15.615-25.101
                                    c0.203-3.403-2.285-6.36-5.676-6.755c-8.637-1-17.35-1.029-26.012-0.068c-3.348,0.37-5.834,3.257-5.723,6.617
                                    c0.375,10.721-5.977,20.63-15.832,24.667c-9.451,3.861-21.744,1.046-28.621-6.519c-2.273-2.492-6.074-2.798-8.725-0.731
                                    c-6.928,5.437-13.229,11.662-18.703,18.492c-2.133,2.655-1.818,6.503,0.689,8.784c8.049,7.289,10.644,18.879,6.465,28.849
                                    c-3.99,9.505-13.859,15.628-25.156,15.628c-3.666-0.118-6.275,2.345-6.68,5.679c-1.016,8.683-1.027,17.535-0.049,26.289
                                    c0.365,3.264,4.268,5.688,7.582,5.688c10.07-0.256,19.732,5.974,23.791,15.841c4.039,9.855,1.439,21.341-6.467,28.592
                                    c-2.473,2.273-2.789,6.07-0.701,8.709c5.369,6.843,11.537,13.068,18.287,18.505c2.65,2.134,6.504,1.835,8.801-0.697
                                    c6.918-7.65,19.295-10.481,28.822-6.482c9.98,4.176,16.258,14.262,15.645,25.092c-0.201,3.403,2.293,6.369,5.672,6.755
                                    c4.42,0.517,8.863,0.773,13.32,0.773c4.23,0,8.461-0.231,12.692-0.702c3.352-0.37,5.834-3.26,5.721-6.621
                                    c-0.387-10.716,5.979-20.626,15.822-24.655c9.514-3.886,21.752-1.042,28.633,6.512c2.285,2.487,6.063,2.789,8.725,0.73
                                    c6.916-5.423,13.205-11.645,18.703-18.493c2.135-2.65,1.832-6.503-0.689-8.788c-8.047-7.284-10.656-18.879-6.477-28.839
                                    c3.928-9.377,13.43-15.673,23.65-15.673l1.43,0.038c3.318,0.269,6.367-2.286,6.768-5.671
                                    C231.476,120.379,231.487,111.537,230.505,102.78z M115.616,182.27c-36.813,0-66.654-29.841-66.654-66.653
                                    s29.842-66.653,66.654-66.653s66.654,29.841,66.654,66.653c0,12.495-3.445,24.182-9.428,34.176l-29.186-29.187
                                    c2.113-4.982,3.229-10.383,3.228-15.957c0-10.915-4.251-21.176-11.97-28.893c-7.717-7.717-17.978-11.967-28.891-11.967
                                    c-3.642,0-7.267,0.484-10.774,1.439c-1.536,0.419-2.792,1.685-3.201,3.224c-0.418,1.574,0.053,3.187,1.283,4.418
                                    c0,0,14.409,14.52,19.23,19.34c0.505,0.505,0.504,1.71,0.433,2.144l-0.045,0.317c-0.486,5.3-1.423,11.662-2.196,14.107
                                    c-0.104,0.103-0.202,0.19-0.308,0.296c-0.111,0.111-0.213,0.218-0.32,0.328c-2.477,0.795-8.937,1.743-14.321,2.225l0.001-0.029
                                    l-0.242,0.061c-0.043,0.005-0.123,0.011-0.229,0.011c-0.582,0-1.438-0.163-2.216-0.94c-5.018-5.018-18.862-18.763-18.862-18.763
                                    c-1.242-1.238-2.516-1.498-3.365-1.498c-1.979,0-3.751,1.43-4.309,3.481c-3.811,14.103,0.229,29.273,10.546,39.591
                                    c7.719,7.718,17.981,11.968,28.896,11.968c5.574,0,10.975-1.115,15.956-3.228l29.503,29.503
                                    C141.125,178.412,128.825,182.27,115.616,182.27z"/>
                            </svg>
                            <span class="order-head_text">@lang('v.service_repair')</span>
                        </div>
                        <div class="order-head_next"><img src="{{asset('/img/right-arrow_2.svg')}}" alt=""></div>
                    </li>
                    <li :class="{active: progress.step === 3}">
                        <div class="order-head_back">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                 width="9px" height="14px">
                                <path fill-rule="evenodd"  fill="rgb(142, 142, 142)"
                                      d="M7.500,13.999 L1.500,8.399 L1.500,8.400 L-0.000,7.000 L7.500,-0.001 L9.000,1.400 L3.000,6.999 L9.000,12.599 L7.500,13.999 Z"/>
                            </svg>
                        </div>
                        <div class="order-head_block" v-on:click="changeStep(3)">
                            <svg class="order-head_svg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="26px" height="26px" viewBox="0 0 31.939 31.939" style="enable-background:new 0 0 31.939 31.939;" xml:space="preserve">
                                <path d="M15.58,18.332h-0.777c-0.403,0-0.73-0.326-0.73-0.729c0-0.149,0.06-0.293,0.167-0.397c0.452-0.439,0.832-1.03,1.107-1.667
                                    c0.056,0.041,0.116,0.071,0.184,0.071c0.436,0,0.95-0.964,0.95-1.621c0-0.657-0.061-1.19-0.498-1.19
                                    c-0.052,0-0.106,0.009-0.162,0.023c-0.031-1.782-0.481-4.005-3.202-4.005c-2.839,0-3.17,2.219-3.202,3.999
                                    c-0.04-0.008-0.08-0.017-0.117-0.017c-0.437,0-0.497,0.533-0.497,1.19c0,0.657,0.512,1.621,0.949,1.621
                                    c0.054,0,0.104-0.015,0.151-0.042c0.274,0.627,0.649,1.206,1.094,1.641c0.107,0.104,0.167,0.246,0.167,0.396
                                    c0,0.403-0.327,0.73-0.73,0.73H9.656c-1.662,0-3.009,1.347-3.009,3.009v0.834c0,0.524,0.425,0.95,0.95,0.95h10.042
                                    c0.524,0,0.949-0.426,0.949-0.95v-0.834C18.589,19.68,17.242,18.332,15.58,18.332z"/>
                                <path d="M24.589,10.077h-8.421c0.243,0.538,0.417,1.2,0.489,2.019c0.18,0.111,0.315,0.29,0.425,0.506h7.507
                                    c0.39,0,0.704-0.315,0.704-0.704v-1.117C25.293,10.393,24.979,10.077,24.589,10.077z"/>
                                <path d="M24.589,14.678h-7.335c-0.199,0.752-0.689,1.539-1.368,1.749c-0.02,0.037-0.043,0.069-0.064,0.106v0.67h8.766
                                    c0.389,0,0.704-0.315,0.704-0.705v-1.116C25.293,14.993,24.979,14.678,24.589,14.678z"/>
                                <path d="M24.589,19.279h-5.726c0.378,0.598,0.6,1.303,0.6,2.062v0.463h5.126c0.39,0,0.704-0.315,0.704-0.704v-1.117
                                    C25.293,19.594,24.979,19.279,24.589,19.279z"/>
                                <path d="M27.615,3.057H4.325C1.936,3.057,0,4.993,0,7.382v17.176c0,2.39,1.936,4.325,4.325,4.325h23.29
                                    c2.389,0,4.324-1.936,4.324-4.325V7.382C31.939,4.993,30.004,3.057,27.615,3.057z M29.898,24.558c0,1.259-1.024,2.283-2.283,2.283
                                    H4.325c-1.259,0-2.283-1.024-2.283-2.283V7.382c0-1.259,1.024-2.283,2.283-2.283h23.29c1.259,0,2.283,1.024,2.283,2.283V24.558z"
                                                        />
                            </svg>
                            <span class="order-head_text">@lang('v.informaion_order')</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="order-body" v-cloak>
                <transition name="fade" mode="out-in">
                    <div v-if="progress.addcar || ( progress.step === 1 && !progress.log )">
                        <section class="order-body_up top-block" v-if="(progress.addcar && progress.log && getMyCarsCount > 0)">
                            <a class="back-to-my-cars"
                               href="#"
                               v-on:click.prevent="toCarsChoose()"><i class="icon-arrow-left-long"></i><span>@lang('v.select_in_order')</span></a>
                        </section>
                        <section class="order-body_ul" v-if="progress.markaProgress > 1">
                            <ul>
                                <li class="img" v-if="progress.markaProgress > 1" key="foto"><img v-on:click="progress.markaProgress = 1" :src="'{{asset('/images/marks')}}/' + selectedMarkaImage" alt="" /></li>
                                <li class="marka" v-if="progress.markaProgress > 1" key="marka"><span v-on:click="progress.markaProgress = 1" v-text="selectedMarkaName"></span></li>
                                <li class="model" v-if="progress.markaProgress > 2" key="model"><span v-on:click="progress.markaProgress = 2" v-text="selectedModelName"></span></li>
                                <li class="year" v-if="progress.markaProgress > 3" key="year"><span v-on:click="progress.markaProgress = 3" v-text="selectedYear"></span></li>
                            </ul>
                        </section>

                        <section class="order-body_up cars-list" v-if="progress.markaProgress === 1" key="marka">
                            <h2 class="order-body_title">@lang('v.enter_name')</h2>
                            <div class="order-marks_find">
                                <input type="text" v-model="getMarka" placeholder="@lang('v.example_bmw')">
                            </div>
                            <h2 class="order-body_title">@lang('v.select_in_list')</h2>
                            <div v-cloak>
                                <div class="order-marks_table">
                                    <div class="order-marks_block"
                                         v-for="(item,index) in allMarkaComputed"
                                         :title="item.name_ru"
                                         :key="item.id"
                                         v-on:click="selectM(item.id,item.image,item.name_ru)"
                                         :style="{'backgroundImage': 'url({{asset('/images/marks/')}}/' + item.image + ')'}">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="order-body_up" v-else-if="progress.markaProgress === 2" key="model">
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

                        <section class="order-body_up" v-else-if="progress.markaProgress === 3" key="year">
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

                        <section class="order-body_up" v-else-if="progress.markaProgress === 4" key="vin">
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
                                               autocomplete="off"
                                               :class="{error: errors.has('VIN')}" />
                                        <span :class="{error: errors.has('VIN')}"
                                              v-if="errors.has('VIN')"
                                              v-cloak>@lang('v.invalid_vin_code')</span>
                                    </div>
                                    <p class="text">@lang('v.enter_vin_text')</p>
                                </div>
                                <div class="order-vin_image">
                                    <img src="{{asset('img/vin-auto.png')}}" alt="" />
                                </div>
                            </div>
                            <div class="order-next_services">
                                <div v-if="progress.log">
                                    <button class="btn-red"
                                            v-on:click="secondStep(-1)"
                                            v-if="(progress.addcar && !checkCarData())">
                                        <div class="order-next_button">
                                            <span>@lang('v.add_auto')</span>
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                                <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                                345.606,368.713 476.213,238.106 "/>
                                            </svg>
                                        </div>
                                    </button>
                                    <button class="btn-red"
                                            v-on:click="secondStep(0)"
                                            v-if="(progress.addcar && checkCarData())"
                                            {{--v-if="((progress.addcar && checkCarData()) || (!progress.log && progress.addcar))"--}}>
                                        <div class="order-next_button">
                                            <span>@lang('v.to_service')</span>
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                                <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                                345.606,368.713 476.213,238.106 "/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                                <div v-else>
                                    <button class="btn-red" v-on:click="secondStep(0)">
                                        <div class="order-next_button">
                                            <span>@lang('v.to_service')</span>
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                        <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                            345.606,368.713 476.213,238.106 "/>
                                    </svg>
                                        </div>
                                    </button>
                                </div>
                                {{--
                                <button class="btn-red" v-on:click="secondStep(-1)" v-if="checkCar">
                                    <div class="order-next_button">
                                        <span v-if="progress.addcar">Добавить автомобиль</span>
                                        <span v-else>К услугам по ремонту</span>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                        <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                            345.606,368.713 476.213,238.106 "/>
                                    </svg>
                                    </div>
                                </button>
                                --}}
                            </div>
                        </section>
                    </div>

                    <div v-if="progress.step === 1 && progress.log && !progress.addcar" v-cloak>
                        @if(Auth::guard('customer') -> user())
                            <section class="order-body_up">
                                <h2 class="order-body_title">@lang('v.select_car')</h2>
                                <div class="order-cars_wrapper">
                                    <div class="order-cars">
                                        <div class="car"
                                             :class="{'selected': item.id === sendData.auto.id}"
                                             v-for="(item,index) in mycars"
                                             v-on:click="secondStep(index + 1)">
                                            <ul>
                                                <li class="img"><img :src="'{{asset('/images/marks')}}/' + item.image" alt="" /></li>
                                                <li class="marka"><span v-text="item.marka"></span></li>
                                                <li class="model"><span v-text="item.model"></span></li>
                                                <li class="year"><span v-text="item.year"></span></li>
                                                <li class="vin"><span v-text="item.vin"></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="order-cars_add">
                                        <button class="btn-red" v-on:click="addNewCar()">@lang('v.add_new_car')</button>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>

                    <div v-if="progress.step === 2">
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
                                            {{--<div v-html="item.image" class="img"></div>--}}
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
                                                {{--<div class="inner-title_mid"><span>Средняя цена</span></div>--}}
                                                <div class="inner-title">
                                                    <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            width="19px" height="6px">
                                                        <path fill-rule="evenodd"  fill="rgb(3, 99, 205)"
                                                              d="M5.370,6.000 L-0.000,3.000 L5.370,0.001 L5.370,2.249 L19.000,2.249 L19.000,3.751 L5.370,3.751 L5.370,6.000 Z"/>
                                                    </svg>
                                                    <span v-text="item.name_ru"></span>
                                                </div>
                                                {{--<div class="inner-in" v-if="item.next.length || item.service.length" v-on:click.stop>--}}
                                                <div class="inner-in" v-if="item.service.length" v-on:click.stop>
                                                    <div class="inner-scroll">
                                                        <el-scrollbar :noresize="false"
                                                                      :native="false"
                                                                      :ref="'scroll' + index"
                                                                      :view-style="{'max-height':'400px'}">
                                                            {{--
                                                            <template v-if="item.next.length">
                                                                <div class="inner-block" v-for="(item,index) in item.next">
                                                                    <div class="title-in">
                                                                        <b v-text="item.name_ru"></b>
                                                                    </div>
                                                                    <ul v-if="item.service.length" class="inner-ul">
                                                                        <li v-for="(item,index) in item.service"
                                                                            :tabindex="index + 20"
                                                                            :ref="item.id + '_' + item.ident" >
                                                                            <div>
                                                                                <label class="checkbox">
                                                                                    <input type="checkbox"
                                                                                            hidden
                                                                                           :value="item"
                                                                                           v-model="checkedServices">
                                                                                    <div class="checkbox-icon"></div>
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
                                                            </template>--}}
                                                            <template v-if="item.service.length">
                                                                <div class="inner-block">
                                                                    <ul  class="inner-ul">
                                                                        <li v-for="(item,index) in item.service"
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
                                                                                <span>@lang('v.mdl')</span>
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
                                              {{--v-on:keyup="checkOtherServicesMessage($event, 'false')"--}}
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

                        <section class="order-services_list price" v-if="(checkedServices.length || (otherServices.message.length !== 0 || otherServices.imagesThumbs.length !== 0))">
                            <div class="wrapper">
                                <div class="list">
                                    <div class="block"  v-if="checkedPrice > 0">
                                        <span>@lang('v.aproximativ_price') - <h4 class="list-title" v-text="checkedPrice"></h4><strong> @lang('v.mdl')</strong></span>
                                    </div>
                                </div>
                                <div class="button">
                                    <button class="btn-red" v-on:click="thirdStep(3)">@lang('v.continue')</button>
                                </div>
                            </div>
                            {{--
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
                                    <button class="btn-red"
                                            v-on:click="thirdStep(3)">Продолжить</button>
                                </div>
                            </div>--}}
                        </section>
                    </div>
                    <div v-if="progress.step === 3">
                        <section class="order-data_in" v-if="!progress.loginForm">
                            <h2 class="order-body_title">@lang('v.contacts_com')s</h2>
                            <section class="order-data_enter">
                                <div class="enter-buttons">
                                    <button v-on:click="progress.enter = 1" :class="{active: progress.enter === 1}">@lang('v.sign')</button>
                                    <button v-on:click="progress.enter = 2" :class="{active: progress.enter === 2}">@lang('v.new_user')</button>
                                </div>
                                <transition name="fade" mode="out-in">
                                    <div class="enter-wrapper" :class="{st1: progress.enter === 1}" v-if="progress.enter === 1">
                                        <div class="enter-form">
                                            <div class="input-wr">
                                                <label for="" class="label">@lang('v.email_phone')</label>
                                                <input type="text"
                                                       class="input"
                                                       v-validate="'required'"
                                                       name="order_name"
                                                       data-vv-validate-on="none"
                                                       :class="{ error: errors.has('order_name') }"
                                                       v-on:focus="removeError('order_name')"
                                                       autocomplete="name"
                                                       v-model="userData.name"
                                                        value="" />
                                                <span :class="{ error: errors.has('order_name') }"
                                                      v-if="errors.has('order_name')"
                                                >@lang('v.order_name')</span>
                                            </div>
                                            <div class="input-wr">
                                                <label for="" class="label">@lang('v.password')</label>
                                                <input type="password"
                                                       class="input"
                                                       value=""
                                                       v-validate="'required'"
                                                       name="order_pas"
                                                       data-vv-validate-on="none"
                                                       :class="{ error: errors.has('order_pas') }"
                                                       v-on:focus="removeError('order_pas')"
                                                       autocomplete="name"
                                                       v-model="userData.pas" />
                                                <span :class="{ error: errors.has('order_pas') }"
                                                      v-if="errors.has('order_pas')"
                                                >@lang('v.order_pas')</span>
                                                <transition name="fade" mode="in-out">
                                                    <span :class="{ error: userData.error_name !== ''}"
                                                          v-if="userData.error_name !== ''"
                                                          v-text="userData.error_name"></span>
                                                </transition>
                                            </div>
                                            <div class="enter-form_submit">
                                                <button class="btn-red" v-on:click="orderEnter">@lang('v.sign')</button>
                                                <span>@lang('v.forget_pass')</span>
                                            </div>
                                        </div>
                                        <div class="enter-social">
                                            <p>@lang('v.sign_socials')</p>
                                            <div class="enter-social_icons">
                                                <img src="{{asset('/img/fb.png')}}" alt="" tabindex="5">
                                                <img src="{{asset('/img/vk.png')}}" alt="" tabindex="6">
                                                <img src="{{asset('/img/g.png')}}" alt="" tabindex="7">
                                                <img src="{{asset('/img/ya.png')}}" alt="" tabindex="8">
                                                <img src="{{asset('/img/mru.png')}}" alt="" tabindex="9">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="enter-wrapper"
                                         :class="{st2: progress.enter === 2}"
                                         v-if="progress.enter === 2">
                                        <div class="enter-reg">
                                            <div class="enter-reg_form order-new-user">
                                                <div class="input-wr form-email">
                                                    <label for="" class="label req">@lang('v.email')</label>
                                                    <input type="text"
                                                           class="input" />
                                                </div>
                                                <div class="input-wr form-password">
                                                    <label for="" class="label req">@lang('v.password')</label>
                                                    <input type="password"
                                                           class="input" />
                                                </div>
                                                <div class="input-wr form-name">
                                                    <label for="" class="label req">@lang('v.you_name')</label>
                                                    <input type="text"
                                                           class="input" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                            </section>
                        </section>

                        <user-phones v-on:phones-list-changed="phonesListChanged"
                                        v-on:phone-changed="phoneChanged"
                                        :initial-user-phones="userPhones"
                                        :initial-progress="progress"
                                        :initial-sms-code-time="SMS_CODE_TIME"
                                        ref="userPhonesComponent">
                        </user-phones>

                        <section class="order-data_info">
                            <h2 class="order-body_title">@lang('v.informaion_order')</h2>
                            <div class="info-wrapper">
                                <div class="info-date">
                                    <div class="info-date_title">@lang('v.propun_date_repair')</div>
                                    <el-date-picker
                                            v-model="userData.orderdate"
                                            format="dd-MM-yyyy"
                                            type="date"
                                            :editable="false"
                                            placeholder="">
                                    </el-date-picker>
                                </div>
                                <div class="input-wr info-cost">
                                    <label for="" class="label">@lang('v.propun_price_customer2')</label>
                                    <input type="text" class="input" v-model="userData.optimalPrice">
                                </div>
                                <div class="info-city address" v-if="!isFromAutoservice()">
                                    {{--<div class="info-city_label">Желаемый город</div>--}}
                                    {{--<vue-google-autocomplete--}}
                                            {{--id="order_map"--}}
                                            {{--ref="order_address"--}}
                                            {{--placeholder="Введите адрес"--}}
                                            {{--:country="['md']"--}}
                                            {{--types="(cities)"--}}
                                            {{--name="order_map"--}}
                                            {{--v-on:placechanged="getAddressData"--}}
                                    {{-->--}}
                                    {{--</vue-google-autocomplete>--}}


                                    <get-address-order lang="{{Lang::getLocale()}}"
                                                      ref="getAddressOrder"
                                                      v-on:locationchanged="locationchanged"></get-address-order>
                                    {{--
                                    <span class="error adres"
                                          v-if="selectedAddress.errors.district.hasError">Выберите район</span>
                                    <span class="error adres"
                                          v-if="errors.has('orderChooseAddressForm', 'city')">Выберите город</span>
                                          --}}
                                    <input type="text"
                                           hidden
                                           name="district"
                                           v-model="selectedAddress.district">
                                    <input type="text"
                                           hidden
                                           name="city"
                                           v-model="selectedAddress.city">
                                </div>
                                <div class="info-dop">
                                    <div class="info-dop_title">
                                        <h4>@lang('v.dop_info')</h4>
                                    </div>
                                    <div class="info-dop_wrapper">
                                        <div class="info-dop_block" v-for="item in dop">
                                            <el-checkbox v-model.boolean="item.check"
                                                         el-name="item.name"><span v-text="item.name"></span></el-checkbox>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="order-data_next">
                            <div v-if="!progress.loginForm">
                            <el-button class="btn-red"
                                       :disabled="!progress.loginForm">@lang('v.send_review_btn')</el-button>
                            </div>
                            <div v-else>
                                <el-button class="btn-red"
                                           v-on:click.prevent="sendFinal"
                                           :disabled="progress.sendOrder">@lang('v.send_review_btn')</el-button>
                            </div>
                        </section>
                    </div>
                </transition>
            </div>
        </div>
    </div>
@stop
@section('script')
@stop