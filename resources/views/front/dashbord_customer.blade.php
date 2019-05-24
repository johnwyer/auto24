@extends('.front/layout/layout')
@section('meta')
@stop

@section('content')
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    {{ ScriptVariables::render('inf') }}
    <script>
        var info = {
            name:'{!!Auth::guard('customer')->user()->name !!}',
            img:'{!!Auth::guard('customer')->user()->image !!}',
            status:'{!! $data['status'] !!}',
            email: '{!! Auth::guard('customer')->user()->email !!}',
            confirm_email: {!! Auth::guard('customer')->user()->confEm == "1" ? 'true' : 'false' !!}
        };

        var phones = {!! json_encode( $data['phones']) !!};
        var orders_active = {!! json_encode( $data['order_active']) !!};
        var orders_archive = {!!json_encode( $data['order_arhive']) !!};

        var allmarks = {!! $data['mark_for_filter'] !!};
        var allcity = {!! $data['city_for_filter'] !!};

        var notify = {!! $data['notification'] !!};
    </script>
    <div id="serviceCabinet" class="sc" v-cloak>
        <chat-modal v-if="modal.client.subscribeRepairs && orderPageInfo.statusId === 1"
                    v-on:close="modal.client.subscribeRepairs = false"
                    class="modal-auth modal-chat"
                    key="modal-auth"
                    v-cloak>
            <div class="block">
                <i18n tag="p" path="message.confirm_order_client">
                    <strong place="name">#@{{ chat.services[chat.activeindex].name }}</strong>
                    <strong place="num">#@{{ orderPageInfo.num }}</strong>
                </i18n>
            </div>
            <div class="block">
                <el-button type="primary"
                           class="btn-blue"
                           v-on:click="subscribeRemont()"
                           :disabled="chat.services[chat.activeindex].autoservice_status > 1">@lang('v.confirmed')</el-button>
                <el-button type="secondary"
                           class="btn-gray"
                           v-on:click="modal.client.subscribeRepairs = false">@lang('v.canceled_btn')</el-button>
            </div>
        </chat-modal>

        <chat-modal v-if="modal.client.moveToArchive"
                    v-on:close="modal.client.moveToArchive = false"
                    class="modal-auth modal-chat"
                    key="modal-archive"
                    v-cloak>
            <div class="block">
                <i18n tag="p" path="message.move_to_archive">
                    <strong place="num">#@{{ orderPageInfo.num }}</strong>
                </i18n>
            </div>
            <div class="block">
                <el-button type="primary"
                           class="btn-blue"
                           v-on:click="moveToArchiveClient()">@lang('v.confirmed')</el-button>
                <el-button type="secondary"
                           class="btn-gray"
                           v-on:click="modal.client.moveToArchive = false">@lang('v.canceled_btn')</el-button>
            </div>
        </chat-modal>

        <phone-modal v-if="modal.confirmPhoneRemoving"
                     v-on:close="modal.confirmPhoneRemoving = false"
                     class="modal-auth modal-chat"
                     key="modal-auth"
                     v-cloak>
            <div class="block">
                {{--<p v-html="$refs.userPhonesComponent.getDeletePhoneNumberMessage()"></p>--}}
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

        <car-remove-modal v-if="modal.confirmCarRemoving.show"
                     v-on:close="closeCarRemovePopup()"
                     class="modal-auth modal-chat modal-car-remove"
                     key="modal-auth"
                     v-cloak>
            <div class="block">
                {{--<p v-html="getRemoveCarMessage()"></p>--}}
                <p v-t="'message.sure_want_to_delete'"></p>
                <section class="order-body_ul">
                    <ul>
                        <li class="img"><img :src="'{{asset('/images/marks')}}/' + choose.mycars[modal.confirmCarRemoving.index].image" alt="" /></li>
                        <li class="marka" v-if="choose.mycars[modal.confirmCarRemoving.index].marka !== ''"><span v-text="choose.mycars[modal.confirmCarRemoving.index].marka"></span></li>
                        <li class="model" v-if="choose.mycars[modal.confirmCarRemoving.index].model !== ''"><span v-text="choose.mycars[modal.confirmCarRemoving.index].model"></span></li>
                        <li class="year" v-if="choose.mycars[modal.confirmCarRemoving.index].year !== ''"><span v-text="choose.mycars[modal.confirmCarRemoving.index].year"></span></li>
                        <li class="vin" v-if="choose.mycars[modal.confirmCarRemoving.index].vin !== ''"><span v-text="choose.mycars[modal.confirmCarRemoving.index].vin"></span></li>
                    </ul>
                </section>
            </div>
            <div class="block">
                <el-button type="primary"
                           class="btn-blue"
                           v-on:click="removeMyCar()"
                >Удалить</el-button>
                <el-button type="secondary"
                           class="btn-gray"
                           v-on:click="closeCarRemovePopup">@lang('v.canceled_btn')</el-button>
            </div>
        </car-remove-modal>

        <div class="sc-title">
            <h1>
                <span v-if="tab === 1" key="tab1">@lang('v.profile')</span>
                <span v-else-if="tab === 2" key="tab2">@lang('v.review_to_repair')</span>
                <span v-else-if="tab === 3" key="tab3">@lang('v.my_car')</span>
            </h1>
        </div>
        <div class="sc-wrapper">
            <div class="sc-left">
                <div class="sc-left_wrapper">
                    <ul class="sc-aside">
                        <li v-on:click="changeTab(1)" :class="{active:tab === 1}">
                            <div class="svg">
                                <svg width="21px" height="30px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 489.5 489.5" style="enable-background:new 0 0 489.5 489.5;" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M242.85,238.9L242.85,238.9c0.1,0,0.3,0,0.4,0s0.3,0,0.4,0l0,0
                                        c74.6-0.8,63.8-100.4,63.8-100.4c-3.1-66.5-58.7-66-64.2-65.8c-5.5-0.2-61.1-0.8-64.2,65.8
                                        C179.15,138.6,168.35,238.2,242.85,238.9z"/>
                                    <path d="M244.75,0c-94.6,0-171.6,77-171.6,171.6c0,45.1,17.6,86.3,46.2,116.9c0.6,0.8,1.3,1.6,2.1,2.2
                                        c31.2,32.3,75,52.5,123.3,52.5c94.6,0,171.6-77,171.6-171.6S339.35,0,244.75,0z M144.55,287.6c46.6-15.2,63-33.7,68.7-45.7
                                        c8.6,3.9,18.2,5.9,29,6.1c0.2,0,0.5,0,0.7,0h0.4h0.4c0.2,0,0.5,0,0.7,0c11.6-0.2,22-2.6,31.1-7.1c5.3,11.9,21.3,31.1,69.3,46.7
                                        c-26.9,23.2-61.9,37.4-100.2,37.4C206.45,325,171.45,310.9,144.55,287.6z M243.65,229.9c-0.1,0-0.2,0-0.3,0l0,0
                                        c-0.1,0-0.2,0-0.3,0c-15.9-0.2-28-5.2-37-15.4c-22.8-25.6-17.8-74.4-17.8-74.9c0-0.2,0-0.4,0-0.6c2.4-53,41.6-57.1,53.6-57.1
                                        c0.5,0,1,0,1.2,0s0.5,0,0.7,0c0.3,0,0.7,0,1.2,0c11.9,0,51.1,4.1,53.6,57.1c0,0.2,0,0.4,0,0.6c0.1,0.5,5,49.2-17.8,74.9
                                        C271.65,224.7,259.55,229.7,243.65,229.9z M359.55,273.2c-66.2-19.2-68.4-42.7-68.4-42.9c0,0.3,0,0.5,0,0.5h-1
                                        c1.4-1.3,2.8-2.7,4.1-4.2c27.3-30.8,22.8-83.6,22.3-88.6c-2.6-54.8-40.2-74.3-71.7-74.3c-0.6,0-1.2,0-1.6,0c-0.4,0-0.9,0-1.6,0
                                        c-31.4,0-69,19.4-71.7,74.2c-0.5,4.9-5.1,57.8,22.3,88.6c1.7,1.9,3.6,3.7,5.4,5.4c-1.5,5-11.3,24.7-67.9,41.2
                                        c-24-27.1-38.6-62.6-38.6-101.6c0-84.6,68.8-153.5,153.5-153.5s153.5,68.8,153.5,153.5C398.25,210.5,383.55,246.1,359.55,273.2z"
                                    />
                                    <path d="M379.75,410.7c0-5-4.1-9.1-9.1-9.1h-251.8c-5,0-9.1,4.1-9.1,9.1s4.1,9.1,9.1,9.1h251.8
                                        C375.65,419.8,379.75,415.7,379.75,410.7z"/>
                                    <path d="M335.45,480.4c0-5-4.1-9.1-9.1-9.1h-163.2c-5,0-9.1,4.1-9.1,9.1s4.1,9.1,9.1,9.1h163.3
                                        C331.35,489.4,335.45,485.4,335.45,480.4z"/>
                                </g>
                            </g>
                        </svg>
                            </div>
                            <span>@lang('v.profile')</span>
                        </li>
                        <li v-on:click="changeTab(2)" :class="{active:tab === 2}">
                            <div class="svg">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 489.7 489.7" style="enable-background:new 0 0 489.7 489.7;" xml:space="preserve" width="22px" height="19px">
                            <g>
                                <g>
                                    <path d="M52.7,134.75c29.1,0,52.7-23.7,52.7-52.7s-23.6-52.8-52.7-52.8S0,52.95,0,81.95S23.7,134.75,52.7,134.75z M52.7,53.75
                                        c15.6,0,28.2,12.7,28.2,28.2s-12.7,28.2-28.2,28.2s-28.2-12.7-28.2-28.2S37.2,53.75,52.7,53.75z"/>
                                    <path d="M52.7,297.55c29.1,0,52.7-23.7,52.7-52.7s-23.6-52.7-52.7-52.7S0,215.75,0,244.85S23.7,297.55,52.7,297.55z M52.7,216.65
                                        c15.6,0,28.2,12.7,28.2,28.2s-12.7,28.2-28.2,28.2s-28.2-12.6-28.2-28.2S37.2,216.65,52.7,216.65z"/>
                                    <path d="M52.7,460.45c29.1,0,52.7-23.7,52.7-52.7c0-29.1-23.7-52.7-52.7-52.7S0,378.75,0,407.75C0,436.75,23.7,460.45,52.7,460.45
                                        z M52.7,379.45c15.6,0,28.2,12.7,28.2,28.2c0,15.6-12.7,28.2-28.2,28.2s-28.2-12.7-28.2-28.2C24.5,392.15,37.2,379.45,52.7,379.45
                                        z"/>
                                    <path d="M175.9,94.25h301.5c6.8,0,12.3-5.5,12.3-12.3s-5.5-12.3-12.3-12.3H175.9c-6.8,0-12.3,5.5-12.3,12.3
                                        S169.1,94.25,175.9,94.25z"/>
                                    <path d="M175.9,257.15h301.5c6.8,0,12.3-5.5,12.3-12.3s-5.5-12.3-12.3-12.3H175.9c-6.8,0-12.3,5.5-12.3,12.3
                                        S169.1,257.15,175.9,257.15z"/>
                                    <path d="M175.9,419.95h301.5c6.8,0,12.3-5.5,12.3-12.3s-5.5-12.3-12.3-12.3H175.9c-6.8,0-12.3,5.5-12.3,12.3
                                        S169.1,419.95,175.9,419.95z"/>
                                </g>
                            </g>
                        </svg>
                            </div>
                            <span>@lang('v.review_to_repair')</span>
                        </li>
                        <li v-on:click="changeTab(3)" :class="{active:tab === 3}">
                            <div class="svg">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     width="25px" height="20px" viewBox="0 0 98 78.753" enable-background="new 0 0 98 78.753" xml:space="preserve">
                                    <g>
                                        <defs>
                                            <rect id="SVGID_1_" width="98" height="78.753"/>
                                        </defs>
                                        <clipPath id="SVGID_2_">
                                            <use xlink:href="#SVGID_1_"  overflow="visible"/>
                                        </clipPath>
                                        <path clip-path="url(#SVGID_2_)" d="M21.63,52.902h9c3.209,0.02,5.826-2.564,5.845-5.773c0.017-2.67-1.788-5.007-4.375-5.666
                                            l-9-2.35c-3.103-0.817-6.281,1.036-7.099,4.139c-0.126,0.482-0.191,0.98-0.191,1.48v2.351C15.81,50.297,18.416,52.902,21.63,52.902
                                             M19.82,44.732c0-0.999,0.811-1.81,1.81-1.809c0.156,0,0.31,0.02,0.46,0.059l9,2.351c0.972,0.232,1.572,1.209,1.34,2.181
                                            c-0.199,0.829-0.948,1.408-1.8,1.389h-9c-0.997-0.005-1.804-0.813-1.81-1.81V44.732z M74.9,39.113l-9,2.35
                                            c-3.109,0.793-4.988,3.955-4.196,7.063c0.66,2.592,3.003,4.397,5.676,4.376h9c3.207-0.005,5.805-2.604,5.811-5.81v-2.36
                                            c0.001-3.208-2.6-5.811-5.809-5.811C75.882,38.922,75.384,38.986,74.9,39.113 M77.48,43.303c0.449,0.342,0.712,0.875,0.71,1.44
                                            v2.35c-0.006,0.997-0.813,1.805-1.811,1.81h-9c-0.999,0.022-1.827-0.771-1.849-1.77c-0.019-0.853,0.56-1.603,1.389-1.8l9-2.351
                                            c0.15-0.04,0.305-0.06,0.46-0.06C76.778,42.924,77.166,43.058,77.48,43.303 M23,31.643h52c1.104,0.001,2.001-0.893,2.002-1.998
                                            c0-0.254-0.048-0.506-0.142-0.742c-1.69-4.27-5-12.15-6.7-14.58c-1.22-1.75-3-2.68-6.061-3.22c-4.996-0.749-10.048-1.067-15.1-0.95
                                            c-5.038-0.118-10.076,0.197-15.06,0.94c-3.09,0.54-4.84,1.47-6.06,3.23c-1.69,2.43-5,10.31-6.7,14.58
                                            c-0.409,1.026,0.091,2.189,1.117,2.599C22.521,31.59,22.759,31.639,23,31.643 M31.13,16.643c0.3-0.44,0.76-1.1,3.47-1.57
                                            c4.765-0.715,9.583-1.023,14.4-0.92c4.807-0.112,9.614,0.186,14.37,0.89c2.7,0.47,3.16,1.13,3.46,1.56
                                            c1.979,3.54,3.703,7.215,5.16,11H26c1.458-3.781,3.182-7.453,5.16-10.99L31.13,16.643z M5.81,37.393
                                            c-1.758,3.396-2.632,7.178-2.54,11v23.46c0.005,3.81,3.091,6.896,6.9,6.901h6.33c3.809-0.006,6.895-3.092,6.9-6.901v-1.68h51.2
                                            v1.68c0.006,3.81,3.092,6.896,6.9,6.901h6.34c3.809-0.006,6.895-3.092,6.9-6.901V48.453c0.089-3.822-0.784-7.604-2.54-11
                                            c3.321-0.577,5.758-3.44,5.8-6.81v-3.6c-0.089-3.666-3.034-6.62-6.7-6.72H89.4c-1.614,0.033-3.192,0.485-4.58,1.31
                                            c-1.7-4.09-4.41-10.26-6.351-13.06c-3.7-5.31-9-6.78-12.63-7.42C60.271,0.286,54.636-0.092,49,0.023
                                            c-5.622-0.123-11.243,0.245-16.8,1.1c-3.65,0.64-8.94,2.1-12.64,7.42c-1.94,2.8-4.65,9-6.35,13.06
                                            c-1.395-0.827-2.979-1.278-4.6-1.31H6.74c-3.693,0.08-6.666,3.057-6.74,6.75v3.6C0.071,33.994,2.507,36.824,5.81,37.393 M4,27.193
                                            c0-1.572,1.268-2.849,2.84-2.86h1.72c1.573,0.087,3.053,0.77,4.14,1.91c0.807,0.755,2.072,0.713,2.827-0.094
                                            c0.167-0.18,0.301-0.388,0.393-0.616c2.79-6.93,5.44-12.55,6.91-14.66c2.81-4,6.84-5.2,10-5.76c5.331-0.799,10.721-1.133,16.11-1
                                            c5.389-0.133,10.779,0.201,16.11,1c3.2,0.56,7.23,1.72,10,5.76c1.47,2.12,4.12,7.73,6.91,14.66
                                            c0.415,1.024,1.581,1.518,2.604,1.103c0.228-0.092,0.437-0.225,0.615-0.393c1.079-1.134,2.548-1.817,4.11-1.91h1.75
                                            c1.578-0.055,2.903,1.18,2.958,2.758C93.999,27.125,94,27.159,94,27.193v3.36c0,1.657-1.343,3-3,3h-2.08h-0.28
                                            c-1.104,0-2,0.896-2,2c0,0.433,0.141,0.854,0.4,1.2c0.227,0.3,0.443,0.604,0.65,0.91c2.083,3.193,3.13,6.95,3,10.76v23.43
                                            c0,1.603-1.299,2.901-2.9,2.901H81.5c-1.602,0-2.9-1.299-2.9-2.901v-3.68c0-1.104-0.896-2-2-2H21.4c-1.105,0-2,0.896-2,2v3.68
                                            c0,1.603-1.298,2.901-2.9,2.901h-6.35c-1.602,0-2.9-1.299-2.9-2.901v-23.46c-0.125-3.796,0.923-7.539,3-10.72
                                            c0.207-0.307,0.423-0.61,0.65-0.91c0.662-0.883,0.484-2.137-0.4-2.8c-0.346-0.259-0.767-0.4-1.2-0.4H9.02L7,33.533
                                            c-1.657,0-3-1.343-3-3V27.193z"/>
                                    </g>
                                </svg>
                            </div>
                            <span>@lang('v.my_car')</span>
                        </li>
                        <li>
                            <a href="/">@lang('v.exit')</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sc-right">
                <div class="sc-right_wrapper">
                    <div class="sc-content">
                        <section class="sc-profile client" v-show="tab === 1"> {{--  add class client  --}}
                            <h2 class="sc-profile_title">@lang('v.contact_details')</h2>
                            <div class="sc-profile_data">
                                <div class="sc-profile_data--email grid-block">
                                    <div class="input-wr email">
                                        <label for="" class="label">@lang('v.email')</label>
                                        <email-confirm ref="emailConfirmComponent"></email-confirm>
                                    </div>
                                </div>
                                <div class="sc-profile_data--name grid-block">
                                    <div class="input-wr">
                                        <label for="" class="label">@lang('v.name')</label>
                                        <input type="text"
                                               v-validate="'required|min:1|max:50'"
                                               name="prof_data_name"
                                               data-vv-validate-on="none"
                                               v-model="data.info.name"
                                               value=""
                                               :class="{ error: errors.has('prof_data_name') }"
                                               v-on:focus="removeError('prof_data_name')"
                                               class="input">
                                        <span :class="{ error: errors.has('prof_data_name') }"
                                              v-if="errors.has('prof_data_name')"
                                        >@lang('v.error_name_customer')</span>
                                    </div>
                                </div>
                                <div class="sc-profile_data--mainfoto grid-block">
                                    <div class="input-wr">
                                        <label for="" class="label">@lang('v.foto_profile')</label>
                                        <upload-foto v-on:input-changed="mainFotoChanged"
                                                     :deleted="mainFoto = ''"
                                                     ref="mainfoto"
                                                     image="{!! Auth::guard('customer')->user()->image !!}">
                                            <span>@lang('v.select')</span>
                                        </upload-foto>
                                    </div>
                                </div>
                                {{--                                <div class="sc-profile_data--phone grid-block">
                                                                    <div class="input-wr">
                                                                        <label for="" class="label">Номер телефона</label>
                                                                        <div class="prefix">+373</div>
                                                                        <input type="text" class="input not-verified"> --}}{{-- not-verified , verified --}}{{--
                                                                        <button class="get_code" v-if="!viewResponse" v-on:click="verifyPhone($event)">Получить код</button>
                                                                        --}}{{--<div class="verified-simbol">--}}{{--
                                                                        --}}{{--<svg--}}{{--
                                                                        --}}{{--xmlns="http://www.w3.org/2000/svg"--}}{{--
                                                                        --}}{{--xmlns:xlink="http://www.w3.org/1999/xlink"--}}{{--
                                                                        --}}{{--width="24px" height="24px">--}}{{--
                                                                        --}}{{--<path fill-rule="evenodd"  fill="rgb(40, 187, 0)"--}}{{--
                                                                        --}}{{--d="M12.000,-0.000 C18.627,-0.000 24.000,5.372 24.000,12.000 C24.000,18.627 18.627,24.000 12.000,24.000 C5.373,24.000 -0.000,18.627 -0.000,12.000 C-0.000,5.372 5.373,-0.000 12.000,-0.000 Z"/>--}}{{--
                                                                        --}}{{--<path fill-rule="evenodd"  fill="rgb(255, 255, 255)"--}}{{--
                                                                        --}}{{--d="M18.820,9.299 L16.759,7.159 C16.520,6.910 16.131,6.910 15.891,7.159 L11.060,12.174 L9.083,10.122 C8.844,9.873 8.455,9.873 8.215,10.122 L6.154,12.262 C5.915,12.510 5.915,12.914 6.154,13.162 L8.485,15.582 C8.506,15.614 8.530,15.645 8.558,15.673 L10.619,17.813 C10.734,17.933 10.890,18.000 11.053,18.000 C11.216,18.000 11.372,17.933 11.487,17.813 L18.820,10.200 C18.935,10.081 19.000,9.918 19.000,9.749 C19.000,9.581 18.935,9.418 18.820,9.299 Z"/>--}}{{--
                                                                        --}}{{--</svg>--}}{{--
                                                                        --}}{{--</div>--}}{{--
                                                                    </div>
                                                                </div>
                                                                <transition name="fade">
                                                                    <div class="sc-profile_data--response grid-block"  v-if="viewResponse">
                                                                        <p>Введите код, отправленный на номер + 373 <b v-text="viewResponseTel"></b></p>
                                                                        <div class="input-wr">
                                                                            <input type="text" class="input" placeholder="Код из СМС">
                                                                            <button>Подтвердить</button>
                                                                        </div>
                                                                        <p>Сообщение успешно отправлено. Если вы не получили его в течении пяти минут, попробуйте
                                                                            <a href="">отправить SMS c кодом</a> повторно через 58 секунд.</p>
                                                                        <p>Внимание! SMS-сообщение для подтверждения номера может быть запрошено не более 3 раз в сутки.</p>
                                                                        <p>Если у вас возникли сложности обртатиесь в нашу <a href="">службу поддержки</a>.</p>
                                                                    </div>
                                                                </transition>--}}
                            </div>

                            <user-phones :initial-user-phones="userPhones"
                                         :initial-sms-code-time="SMS_CODE_TIME"
                                         ref="userPhonesComponent">
                            </user-phones>

                            <div class="sc-profile_data--alert">
                                <div class="title">@lang('v.notification')</div>
                                <div class="alert-wrapper">
                                    <div class="alert-item" v-for="item in notification">
                                        <el-checkbox
                                                v-model="item.check"
                                                :checked="item.check === 1"
                                        ><span v-text="item.name_ru"></span></el-checkbox>
                                    </div>
                                </div>
                            </div>
                            <div class="sc-profile_submit">
                                <button class="btn-red sendInfo" v-on:click="sendInfo(false)">
                                    <i class="el-icon-loading" v-if="data.info.process"></i>
                                    <span v-if="!data.info.process">@lang('v.save_changes')</span>
                                    <span v-if="data.info.process">@lang('v.save_chang')</span>
                                </button>
                                <transition name="fadelong">
                                    <span v-if="data.info.success" class="sendInfo-success">@lang('v.data_successfully_changed')</span>
                                </transition>
                            </div>
                        </section>

                        <section class="sc-profile client" v-show="tab === 2" ref="body">
                            <div class="stat">
                                {{--                                <button class="stat-btn"
                                                                        v-on:click="setOrderState(0)"
                                                                        :class="{active: orderState === 0}">
                                                                    <div>
                                                                        <span>Все заявки</span>
                                                                        <b v-text="orders_all.total"></b>
                                                                    </div>
                                                                </button>--}}
                                <button class="stat-btn active-orders"
                                        v-on:click="setOrderState(1)"
                                        :class="{active:orderState === 1}">
                                    <div>
                                        <span>@lang('v.active_order')</span>
                                        <b v-text="orders_active.total"></b>
                                    </div>
                                </button>
                                {{--                                <button class="stat-btn work-orders"
                                                                        v-on:click="setOrderState(2)"
                                                                        :class="{active:orderState === 2}">
                                                                    <div>
                                                                        <span>В работе</span>
                                                                        <b v-text="orders_work.total"></b>
                                                                    </div>
                                                                </button>--}}
                                <button class="stat-btn archive-orders"
                                        v-on:click="setOrderState(2)"
                                        :class="{active:orderState === 2}">
                                    <div>
                                        <span>@lang('v.arhive')</span>
                                        <b v-text="orders_archive.total"></b>
                                    </div>
                                </button>
                            </div>
                            <template v-if="!orderPage">
                                <div v-if="checkFilterTemplate()" v-cloak>
                                    <div class="stat-filter">
                                        <div class="number">
                                            <el-input type="text"
                                                      v-model="filter.num"
                                                      v-on:change="numberChanged"
                                                      placeholder="@lang('v.number_review')">
                                            </el-input>
                                        </div>
                                        <div class="day">
                                            <el-date-picker
                                                    v-model="filter.date"
                                                    type="daterange"
                                                    format="yyyy-MM-dd"
                                                    value-format="yyyy-MM-dd HH:mm:ss"
                                                    :editable="false"
                                                    v-on:change="dateChanged"
                                                    name="filterDate"
                                                    id="filterDate"
                                                    ref="filterDate"
                                                    placeholder="@lang('v.date_command')">
                                            </el-date-picker>
                                        </div>
                                        <div class="marka">
                                            <marka-select
                                                    v-model="filter.marka.value"
                                                    :disabled="filter.markaLoading"
                                                    placeholder="@lang('v.mark_auto')"
                                                    name="filterMarka"
                                                    id="filterMarka"
                                                    ref="filterMarka"
                                                    v-on:change="markaChanged">
                                                <el-option
                                                        v-for="item in allmarks"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                </el-option>
                                            </marka-select>
                                        </div>
                                        <div class="city">
                                            <marka-select
                                                    v-model="filter.city.value"
                                                    :disabled="filter.cityLoading"
                                                    placeholder="@lang('v.city')"
                                                    name="filterCity"
                                                    id="filterCity"
                                                    ref="filterCity"
                                                    v-on:change="cityChanged">
                                                <el-option
                                                        v-for="item in allcity"
                                                        :key="item.id"
                                                        :label="item.name"
                                                        :value="item.id">
                                                </el-option>
                                            </marka-select>
                                        </div>
                                        {{--<div class="status">--}}
                                        {{--<marka-select v-model="filter.status" placeholder="Статус"   v-on:change="statusChanged">--}}
                                        {{--<el-option--}}
                                        {{--v-for="item in allstatus"--}}
                                        {{--:key="item.id"--}}
                                        {{--:label="item.name"--}}
                                        {{--:value="item.id">--}}
                                        {{--</el-option>--}}
                                        {{--</marka-select>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                                <div class="stat-orders" v-cloak>
                                    <transition-group name="fade" mode="out-in" tag="div" :duration="100">
                                        {{--<div class="stat-orders_wrapper" key="orders_all" v-if="orderState === 0">
                                            <div class="or"
                                                 :class="{archived: getOrderStatusId(item.status) > 3}"
                                                 v-for="(item, index) in orders_all.order"
                                                 v-on:click="showMessage(item)">
                                                <div class="date">
                                                    <span v-text="'#'+item.number"></span><br>
                                                    <span v-text="item.date"></span>
                                                </div>
                                                <div class="text">
                                                    <h3 v-text="item.marka"></h3>
                                                    <p v-text="item.text"></p>
                                                </div>
                                                <div class="city"><span v-text="item.city"></span></div>
                                                <div class="message">
                                                    <div class="message-wrapper" v-cloak>
                                                        <div class="message-count"
                                                             v-text="item.messages"
                                                             v-if="item.messages > 0 && item.messages !== '0'"></div>
                                                        <svg :class="{active: item.messages > 0}"
                                                             version="1.1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 483.3 483.3"
                                                             style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve"
                                                             width="40px"
                                                             height="28px">
                                                        <path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                                                            v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                                                            c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"/>
                                                            <path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                                                            c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                                                            c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                                                            c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
                                                            />
                                                </svg>
                                                    </div>
                                                </div>
                                                <div class="status"><span v-text="item.status"></span></div>
                                            </div>
                                            <div class="or-load-more" v-if="(orders_all.order.length < orders_all.total)">
                                                <span v-on:click="pagingLoadMoreOrders()">Загрузить еще <span v-text="pagingCountLeftOrders()"></span> заявок</span>
                                            </div>
                                        </div>--}}
                                        <div v-if="orderState === 1" key="orders_active">
                                            <div v-if="orders_active.order.length > 0">
                                                <div class="stat-orders_title">
                                                    <div class="date">@lang('v.order_date')</div>
                                                    <div class="text">@lang('v.mark_description')</div>
                                                    <div class="city">@lang('v.city')</div>
                                                    <div class="message">@lang('v.message')</div>
                                                    <div class="status">@lang('v.status')</div>
                                                </div>
                                                <div class="stat-orders_wrapper">
                                                    <div class="or"
                                                         :class="{archived: getOrderStatusId(item.status) > 3}"
                                                         v-for="(item, index) in orders_active.order"
                                                         v-on:click="showMessage(item)">
                                                        <div class="date">
                                                            <span v-text="'#'+item.number"></span><br>
                                                            <span v-text="item.date"></span>
                                                        </div>
                                                        <div class="text">
                                                            <h3 v-text="item.marka"></h3>
                                                            <p v-text="item.text"></p>
                                                        </div>
                                                        <div class="city"><span v-text="item.city"></span></div>
                                                        <div class="message">
                                                            <div class="message-wrapper" v-cloak>
                                                                <div class="message-count"
                                                                     v-text="item.messages"
                                                                     v-if="item.messages > 0 && item.messages !== '0'"></div>
                                                                <svg :class="{active: item.messages > 0}"
                                                                     version="1.1"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                     viewBox="0 0 483.3 483.3"
                                                                     style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve"
                                                                     width="40px"
                                                                     height="28px">
                                                                <path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                                                                    v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                                                                    c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"/>
                                                                    <path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                                                                    c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                                                                    c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                                                                    c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
                                                                    />
                                                        </svg>
                                                            </div>
                                                        </div>
                                                        <div class="status"><span v-text="item.status"></span></div>
                                                    </div>
                                                    <div class="or-load-more" v-if="(orders_active.order.length < orders_active.total)">
                                                        <span v-on:click="pagingLoadMoreOrders()">@lang('v.load_more') <span v-text="pagingCountLeftOrders()"></span> @lang('v.orders')</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <div class="or-no-orders">
                                                    <p>@lang('v.no_orders')</p>
                                                </div>
                                            </div>
                                        </div>
                                        {{--<div class="stat-orders_wrapper" key="orders_work" v-if="orderState === 2">
                                            <div class="or"
                                                 :class="{archived: getOrderStatusId(item.status) > 3}"
                                                 v-for="(item, index) in orders_work.order"
                                                 v-on:click="showMessage(item)">
                                                <div class="date">
                                                    <span v-text="'#'+item.number"></span><br>
                                                    <span v-text="item.date"></span>
                                                </div>
                                                <div class="text">
                                                    <h3 v-text="item.marka"></h3>
                                                    <p v-text="item.text"></p>
                                                </div>
                                                <div class="city"><span v-text="item.city"></span></div>
                                                <div class="message">
                                                    <div class="message-wrapper" v-cloak>
                                                        <div class="message-count"
                                                             v-text="item.messages"
                                                             v-if="item.messages > 0 && item.messages !== '0'"></div>
                                                        <svg :class="{active: item.messages > 0}"
                                                             version="1.1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 483.3 483.3"
                                                             style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve"
                                                             width="40px"
                                                             height="28px">
                                                        <path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                                                            v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                                                            c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"/>
                                                            <path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                                                            c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                                                            c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                                                            c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
                                                            />
                                                </svg>
                                                    </div>
                                                </div>
                                                <div class="status"><span v-text="item.status"></span></div>
                                            </div>
                                            <div class="or-load-more" v-if="(orders_work.order.length < orders_work.total)">
                                                <span v-on:click="pagingLoadMoreOrders()">Загрузить еще <span v-text="pagingCountLeftOrders()"></span> заявок</span>
                                            </div>
                                        </div>--}}
                                        <div v-if="orderState === 2" key="orders_archive">
                                            <div v-if="orders_archive.order.length > 0">
                                                <div class="stat-orders_title">
                                                    <div class="date">@lang('v.order_date')</div>
                                                    <div class="text">@lang('v.mark_description')</div>
                                                    <div class="city">@lang('v.city')</div>
                                                    <div class="message">@lang('v.message')</div>
                                                    <div class="status">@lang('v.status')</div>
                                                </div>
                                                <div class="stat-orders_wrapper">
                                                    <div class="or"
                                                         :class="{archived: getOrderStatusId(item.status) > 3}"
                                                         v-for="(item, index) in orders_archive.order"
                                                         v-on:click="showMessage(item)">
                                                        <div class="date">
                                                            <span v-text="'#'+item.number"></span><br>
                                                            <span v-text="item.date"></span>
                                                        </div>
                                                        <div class="text">
                                                            <h3 v-text="item.marka"></h3>
                                                            <p v-text="item.text"></p>
                                                        </div>
                                                        <div class="city"><span v-text="item.city"></span></div>
                                                        <div class="message">
                                                            <div class="message-wrapper" v-cloak>
                                                                <div class="message-count"
                                                                     v-text="item.messages"
                                                                     v-if="item.messages > 0 && item.messages !== '0'"></div>
                                                                <svg :class="{active: item.messages > 0}"
                                                                     version="1.1"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                     viewBox="0 0 483.3 483.3"
                                                                     style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve"
                                                                     width="40px"
                                                                     height="28px">
                                                                <path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                                                                    v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                                                                    c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"/>
                                                                    <path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                                                                    c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                                                                    c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                                                                    c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
                                                                    />
                                                        </svg>
                                                            </div>
                                                        </div>
                                                        <div class="status"><span v-text="item.status"></span></div>
                                                    </div>
                                                    <div class="or-load-more" v-if="(orders_archive.order.length < orders_archive.total)">
                                                        <span v-on:click="pagingLoadMoreOrders()">@lang('v.load_more') <span v-text="pagingCountLeftOrders()"></span> @lang('v.orders')</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <div class="or-no-orders">
                                                    <p>@lang('v.no_orders')</p>
                                                </div>
                                            </div>
                                        </div>

{{--                                        <div class="stat-orders_wrapper" key="orders_none" v-if="!checkFilterTemplate()">
                                            <div class="or-no-orders">
                                                <p>@lang('v.no_orders')</p>
                                            </div>
                                        </div>--}}
                                    </transition-group>
                                </div>
                            </template>

                            <template v-else>
                                <div class="stat-page">
                                    <div class="p-back">
                                        <button v-on:click="orderPageBackCustomer(true)" class="p-back_button">
                                            <div>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     width="15px" height="7px" viewBox="7.5 3.386 15.208 8.227" enable-background="new 7.5 3.386 15.208 8.227"
                                                     xml:space="preserve">
                                                <polygon points="11.613,11.613 12.282,10.945 9.309,7.972 22.5,7.972 22.5,7.027 9.309,7.027 12.282,4.054 11.613,3.386 7.5,7.5 "/>
                                                </svg>
                                                <span>@lang('v.return')</span>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="p-title">
                                        <h2>Заявка #<span v-text="orderPageInfo.num"></span>
                                            <a class="move-to" href="#"
                                               v-if="checkMoveToArchiveBtn()"
                                               v-on:click.prevent="modal.client.moveToArchive = !modal.client.moveToArchive"><i class="icon-in-archive"></i>@lang('v.to_arhive')</a>
                                            {{--<a class="move-to" href="#" v-on:click.prevent="orderMoveFromArchive()"><i class="icon-from-archive"></i>Перенести из архива</a>--}}
                                        </h2>
                                    </div>
                                    <div class="p-model">
                                        <ul class="item">
                                            <li class="img">
                                                <img :src="'{{asset('/images/marks')}}/'+orderPageInfo.auto.image" alt="" />
                                            </li>
                                            <li class="marka">
                                                <span v-text="orderPageInfo.auto.marka"></span>
                                            </li>
                                            <li class="model">
                                                <span v-text="orderPageInfo.auto.model"></span>
                                            </li>
                                            <li class="year">
                                                <span v-text="orderPageInfo.auto.year"></span>
                                            </li>
                                            <li class="vin">
                                                <span v-text="orderPageInfo.auto.vin"></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="p-info">
                                        <ul>
                                            <li><span>@lang('v.date_command')</span><span>@{{ orderPageInfo.date | chatDate}}</span></li>
                                            {{--<li><span>Статус</span><span class="text-bold" v-text="getOrderStatusName()"></span></li>--}}
                                            <li><span>@lang('v.status')</span><span class="text-bold" v-text="orderPageInfo.status"></span></li>
                                            <li v-if="orderPageInfo.cost !== null"><span>@lang('v.propun_price_customer')</span><span class="text-bold" v-text="orderPageInfo.cost"></span></li>
                                            <li><span>@lang('v.city')</span><span v-text="getOrderLocation()"></span></li>
                                            <li v-if="orderPageInfo.propunDate !== ''"><span>@lang('v.propun_date_repair')</span><span class="text-bold" v-text="orderPageInfo.propunDate"></span></li>
                                        </ul>
                                    </div>
                                    <div class="p-services"
                                         v-if="orderPageInfo.services.length"
                                         :class="{'has-manually-section': otherServices.length != 0}">
                                        <h3 class="p-services_title">@lang('v.list_required_work')</h3>
                                        <ul class="p-services_ul">
                                            <li v-for="serviceCategory in orderPageInfo.services" v-if="serviceCategory.type === 'service'">
                                                <span v-text="serviceCategory.name"></span>
                                                <ul v-if="serviceCategory.child">
                                                    <li v-for="serviceCategoryItem in serviceCategory.child">
                                                        <span v-text="serviceCategoryItem"></span>
                                                        <span v-text="serviceCategory.type"></span>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        {{--                                        <ul class="p-services_ul">
                                                                                    <li v-for="pr in orderPageInfo.services">
                                                                                        <span v-text="pr.name"></span>
                                                                                        <ul v-if="pr.child">
                                                                                            <li v-for="sc in pr.child">
                                                                                                <span v-text="sc"></span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ul>--}}
                                    </div>
                                    <div class="p-services type-manually"
                                         v-if="otherServices.length">
                                        <h3 class="p-services_title" v-if="orderPageInfo.services.length === 0">@lang('v.list_required_work')</h3>
                                        <ul class="p-services_ul">
                                            <li>
                                                <span>Другая услуга (вручную)</span>
                                                <ul>
                                                    <li>
                                                        <span v-text="otherServices[0].name"></span>
                                                    </li>
                                                </ul>
                                                <ul class="images-list" v-if="otherServices[0].image">
                                                    <li v-for="image in otherServices[0].image">
                                                        <div class="image-wrapper"
                                                             v-on:click="showOtherServicesImage(image)"
                                                             :style="{'backgroundImage': 'url(/images/image_up/'+ image +')'}">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <modal v-if="modal.otherServices.show" v-on:close="hideOtherServicesImage" class="modal modal-img" key="modal-auth">
                                            <img :src="'/images/image_up/' + modal.otherServices.image" alt="" />
                                        </modal>
                                    </div>

                                    <div class="p-chat">
                                        <div v-if="chat.services.length">
                                            <div class="p-chat_button">
                                                <button v-on:click="chat.page = true" :class="{active: chat.page}">
                                                    <div><span>@lang('v.answer_order')</span></div>
                                                </button>
                                                <button v-on:click="chat.page = false" :class="{active: !chat.page}">
                                                    <div>
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 275.334 275.334" style="enable-background:new 0 0 275.334 275.334;" xml:space="preserve" width="23px" height="23px">
                                                        <g>
                                                            <path d="M137.667,168.021c16.737,0,30.354-13.617,30.354-30.354s-13.617-30.354-30.354-30.354s-30.354,13.617-30.354,30.354
                                                                S120.93,168.021,137.667,168.021z M137.667,119.313c10.121,0,18.354,8.233,18.354,18.354s-8.233,18.354-18.354,18.354
                                                                s-18.354-8.233-18.354-18.354S127.546,119.313,137.667,119.313z"/>
                                                            <path d="M269.334,131.667h-23.775c-3.015-54.818-47.074-98.877-101.892-101.892V6c0-3.313-2.687-6-6-6s-6,2.687-6,6v29.605
                                                                c0,3.313,2.687,6,6,6c52.969,0,96.062,43.093,96.062,96.062s-43.093,96.062-96.062,96.062s-96.062-43.093-96.062-96.062
                                                                c0-36.783,21.452-70.817,54.651-86.704c2.989-1.431,4.253-5.013,2.822-8.002c-1.43-2.988-5.015-4.252-8.002-2.822
                                                                c-18.131,8.676-33.473,22.217-44.366,39.158c-10.11,15.724-15.897,33.718-16.924,52.37H6c-3.313,0-6,2.687-6,6s2.687,6,6,6h23.775
                                                                c3.015,54.818,47.074,98.877,101.892,101.892v23.775c0,3.313,2.687,6,6,6s6-2.687,6-6v-23.775
                                                                c54.818-3.015,98.877-47.074,101.892-101.892h23.775c3.313,0,6-2.687,6-6S272.647,131.667,269.334,131.667z"/>
                                                        </g>
                                                    </svg>
                                                        <span>@lang('v.answer_order_map')</span>
                                                    </div>
                                                </button>
                                            </div>

                                            <div class="p-chat_orders" v-show="chat.page" key="chat" ref="chat">
                                                <div class="chat-wrapper">
                                                    <div class="chat-content" ref="chatcontent">

                                                        <div class="chat-content_side">
                                                            <el-scrollbar :noresize="false"
                                                                          :native="false"
                                                                          :view-style="{'max-height':'500px'}">
                                                                <div class="item-wrapper" v-if="chat.services.length">
                                                                    <div class="item"
                                                                         :tabindex="10+index"
                                                                         :class="{active: chat.activeindex == index}"
                                                                         :key="'auto'+index"
                                                                         :id="'chatitem'+item.auto_id"
                                                                         v-on:click="changeChat(item.auto_id, index)"
                                                                         v-for="(item, index) in chatServiserReverted">
                                                                        <div class="item-top">
                                                                            <div class="item-mes">
                                                                                <div class="count"
                                                                                     v-if="item.message > 0"
                                                                                     v-text="item.message"></div>
                                                                                <svg :class="{active: item.message > 0}"
                                                                                     version="1.1"
                                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                                                     viewBox="0 0 483.3 483.3"
                                                                                     style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve"
                                                                                     width="30px"
                                                                                     height="20px">
                                                                                <path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
                                                                                    v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
                                                                                    c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"/>
                                                                                    <path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
                                                                                    c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
                                                                                    c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
                                                                                    c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
                                                                                    />
                                                                        </svg>
                                                                            </div>
                                                                            <div class="item-title">
                                                                                <h4><a :href="item.link" v-text="item.name"></a></h4>
                                                                            </div>
                                                                            <div class="item-cost"><span v-text="getServicePrice(item) + currencyTitle[lang]"></span></div>
                                                                        </div>
                                                                        <div class="item-text">
                                                                            <p v-text="getServiceLocation(item)"></p>
                                                                        </div>
                                                                        <div class="item-bottom">
                                                                            <div v-if="item.rating.rating > 0">
                                                                                <div class="item-rate">
                                                                                    <el-rate
                                                                                            :value="item.rating.rating | numberF"
                                                                                            allow-half
                                                                                            disabled-void-color="#c8c8c8"
                                                                                            show-text
                                                                                            :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                            disabled>
                                                                                    </el-rate>
                                                                                </div>
                                                                                <div class="item-otz">
                                                                                    <span v-text="item.rating.total > 0 ? item.rating.total : '@lang("v.not")'"></span>
                                                                                    <span v-text="returnText(item.rating.total, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div v-else>
                                                                                <div class="item-rate">
                                                                                    <el-rate
                                                                                            :value="item.rating.rating | numberF"
                                                                                            allow-half
                                                                                            disabled-void-color="#c8c8c8"
                                                                                            :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                            disabled>
                                                                                    </el-rate>
                                                                                </div>
                                                                                <div class="item-otz">
                                                                                    <span v-text="item.rating.total > 0 ? item.rating.total : '@lang("v.not")'"></span>
                                                                                    <span v-text="returnText(item.rating.total, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </el-scrollbar>
                                                        </div>

                                                        <div class="chat-content_block" ref="chatblock" v-if="chat.activeindex !== null">
                                                            <el-scrollbar :noresize="false"
                                                                          :native="false"
                                                                          :view-style="{'max-height':'500px'}">
                                                                <transition-group class="block-wrapper"
                                                                                  name="list"
                                                                                  v-on:enter="chatBlockAppearHook"
                                                                                  tag="div">
                                                                    <div class="block"
                                                                         :key="'chatblock'+index"
                                                                         :ref="'chatblock' + index"
                                                                         :tabindex="index + 100"
                                                                         :class="{personal: clientId == item.sender_id}"
                                                                         v-for="(item, index) in chat.history">
                                                                        <div class="name">
                                                                            <div v-if="clientId === item.sender_id">
                                                                                <span v-text="data.info.name"></span>
                                                                                <span class="phone-number" v-if="clientId === item.sender_id" v-text="getClientPhone"></span>
                                                                            </div>

                                                                            <div v-else>
                                                                                <a class="autoservice-link"
                                                                                   :href="chat.services[chat.activeindex].link"
                                                                                   v-text="chat.services[chat.activeindex].name"></a>
                                                                                <span class="phone-number" v-text="getAutoserviceAddressClient"></span>
                                                                                <div class="rate" id="autoservice_rating">
                                                                                    <div v-if="chat.services[chat.activeindex].rating.total > 0">
                                                                                        <el-rate
                                                                                                :value="chat.services[chat.activeindex].rating.rating | numberF"
                                                                                                allow-half
                                                                                                disabled-void-color="#c8c8c8"
                                                                                                show-text
                                                                                                :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                                disabled>
                                                                                        </el-rate>
                                                                                        <span class="rate-counter">
                                                                                            <span v-text="chat.services[chat.activeindex].rating.total > 0 ? chat.services[chat.activeindex].rating.total : '@lang("v.not")'"></span>
                                                                                            <span v-text="returnText(chat.services[chat.activeindex].rating.total, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div v-else>
                                                                                        <el-rate
                                                                                                :value="chat.services[chat.activeindex].rating.rating | numberF"
                                                                                                allow-half
                                                                                                disabled-void-color="#c8c8c8"
                                                                                                :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                                disabled>
                                                                                        </el-rate>
                                                                                        <span class="rate-counter">
                                                                                            <span v-text="chat.services[chat.activeindex].rating.total > 0 ? chat.services[chat.activeindex].rating.total : '@lang("v.not")'"></span>
                                                                                            <span v-text="returnText(chat.services[chat.activeindex].rating.total, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <span v-text="clientId === item.sender_id ? data.info.name : chat.services[chat.activeindex].name"></span>--}}
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="text text-main"
                                                                                 v-text="item.message"
                                                                                 v-if="(item.message !== null) && item.message_status < 4"></div>
                                                                            <div class="text text-images">
                                                                                <div class="images-list">
                                                                                    <ul v-if="item.fromChat">
                                                                                        <li v-for="image in item.images" class="list-item">
                                                                                            <div class="image-wrapper"
                                                                                                 :style="{'backgroundImage':'url('+ image +')'}">
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                    <ul v-else>
                                                                                        <li v-for="image in item.images" class="list-item">
                                                                                            <div class="image-wrapper"
                                                                                                 :style="{'backgroundImage':'url(/images/message/'+ image +')'}"
                                                                                                 :title="index">
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 1">
                                                                                <div class="text">@lang('v.repair') - <span v-text="orderPageInfo.autoservice_price + 'лей'"></span></div>
                                                                                <div class="text"
                                                                                     v-if="orderPageInfo.autoservice_parts > 0">@lang('v.service') - <span v-text="orderPageInfo.autoservice_parts + '@lang("v.mdl")'"></span></div>
                                                                                <div class="text">@lang('v.total')) - <span v-text="getTotalPrice() + '@lang("v.mdl")'"></span></div>
                                                                                <div class="button-wrapper">
                                                                                    <el-button type="primary"
                                                                                               class="btn-blue"
                                                                                            {{--v-on:click="subscribeRemont($event)"--}}
                                                                                               v-on:click="modal.client.subscribeRepairs = true"
                                                                                               :disabled="chat.services[chat.activeindex].autoservice_status > 1">@lang('v.repair_record')</el-button>
                                                                                </div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 2">
                                                                                <div class="text"><strong>@lang('v.confirm_record_msg')</strong></div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 3">
                                                                                <div class="text"><strong>@lang('v.confirmed_record_msg')</strong></div>
                                                                                <div class="text"><i>@lang('v.confirmed_record_modal')</i></div>
                                                                                <div class="button-wrapper">
                                                                                    <a :href="'/show-pdf/' + orderPageInfo.num"
                                                                                       class="btn-blue"
                                                                                       target="_blank"
                                                                                       title="@lang('v.to_record_msg')">@lang('v.to_record_msg')</a>
                                                                                    {{--                                                                                    <el-button type="primary"
                                                                                                                                                                                   class="btn-blue">@lang('v.to_record_msg')</el-button>--}}
                                                                                </div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 4">
                                                                                <div class="text"><strong>@lang('v.repair_finish_msg')</strong></div>
                                                                                {{--<div class="text" v-if="item.message === null">@lang('v.repair_finish_msg')</div>--}}
                                                                                <div class="text" v-if="item.message !== null" v-text="item.message"></div>
                                                                                <div class="text"><i>@lang('v.leave_feedback_msg')</i></div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 5">
                                                                                <span v-if="item.message_status === 5 ? orderPageInfo.statusId = 5 : ''"></span>
                                                                                <div class="text"><strong>@lang('v.failure_repair')</strong></div>
                                                                                <div class="text" v-if="item.message === null">@lang('v.can_not_accept')</div>
                                                                                <div class="text" v-if="item.message !== null" v-text="item.message"></div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 6">
                                                                                <span v-if="item.message_status === 6 ? orderPageInfo.statusId = 6 : ''"></span>
                                                                                <div class="text"><strong>@lang('v.left_review')</strong></div>
                                                                                <div class="rate" v-if="item.rating != null">
                                                                                    <el-rate
                                                                                            :value="item.rating | numberF"
                                                                                            allow-half
                                                                                            disabled-void-color="#c8c8c8"
                                                                                            show-text
                                                                                            :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                            disabled>
                                                                                    </el-rate>
                                                                                </div>
                                                                                <div class="text text-main"
                                                                                     v-text="item.message"
                                                                                     v-if="item.message !== null"></div>
                                                                                <div class="text"><i>@lang('v.finished')</i></div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 7">
                                                                                <div class="text"><strong>@lang('v.canceled')</strong></div>
                                                                            </div>

                                                                            <div v-if="item.message_status === 8">
                                                                                <div class="text"><strong>@lang('v.archived')</strong></div>
                                                                            </div>

                                                                            <div class="date">@{{ item.created_at | chatDate}}</div>
                                                                        </div>
                                                                    </div>
                                                                </transition-group>
                                                            </el-scrollbar>
                                                        </div>
                                                    </div>
                                                    <transition name="fade" v-if="chat.activeindex !== null && orderPageInfo.statusId < 5 && orderPageInfo.autoserviceStatusId < 5">
                                                        <div class="chat-form">
                                                            <form action="" autocomplete="off" novalidate>
                                                                <div class="chat-form_header" v-if="orderPageInfo.statusId === 4">
                                                                    <div class="form-group-inline">
                                                                        <label class="control-label type-strong" for="">@lang('v.quality_performed')</label>
                                                                        <div class="rating-wrapper">
                                                                            <el-rate v-model="chat.clientRate"></el-rate>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="chat-form_wrapper">
                                                                    <div class="textarea">
                                                                        <textarea
                                                                                name="chatArea"
                                                                                id="chatArea"
                                                                                :maxlength="chat.messageLimit"
                                                                                v-validate="'required|min:1|max:@{{ chat.messageLimit }}'"
                                                                                data-vv-validate-on="none"
                                                                                :class="{ error: errors.has('chatArea') }"
                                                                                v-on:keydown="checkChatMessage($event, 'false')"
                                                                                v-on:keyup.delete="checkChatMessage($event, 'true')"
                                                                                v-on:focus="removeError('chatArea')"
                                                                                v-on:keyup.ctrl.enter="addToChatCustomer"
                                                                                placeholder="@lang('v.add_message')"
                                                                                v-model="chat.textarea">
                                                                        </textarea>
                                                                    </div>
                                                                    <div class="send" v-if="orderPageInfo.statusId < 4">
                                                                        <div class="send-foto">
                                                                            <button v-on:click.prevent="chatImageUpload">
                                                                                <div>
                                                                                    {{--<img src="{{asset('img/paper-clip.svg')}}" alt="">--}}
                                                                                    <i class="icon-paper-clip"></i>
                                                                                    <span>@lang('v.select_file')</span>
                                                                                </div>
                                                                            </button>
                                                                            <input type="file"
                                                                                   v-on:change="chatImageChanged"
                                                                                   ref="uploadfoto"
                                                                                   name="uploadfoto"
                                                                                   accept="image/*"
                                                                                   hidden />
                                                                        </div>
                                                                        <div class="textarea-counter">@lang('v.was_charset') <span v-text="chatMessageCounter()"></span></div>
                                                                    </div>
                                                                    <div class="send" v-if="(chat.imagesThumbs.length > 0) && (orderPageInfo.statusId < 4)">
                                                                        <div class="images-list">
                                                                            <ul>
                                                                                <li v-for="(item, index) in chat.imagesThumbs" class="list-item">
                                                                                    <div class="image-wrapper"
                                                                                         :style="{'backgroundImage':'url('+ chat.imagesThumbs[index] +')'}"
                                                                                         :title="item[0].name">
                                                                                    </div>
                                                                                    <span class="remove-image" title="" v-on:click="chatImageDelete(index)"><i class="icon-close"></i></span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="send">
                                                                        <div class="send-submit">
                                                                            <el-button type="primary"
                                                                                       class="btn-red"
                                                                                       v-on:click="addToChatCustomer"
                                                                                       :disabled="chat.loading"
                                                                                       :loading="chat.loading">@lang('v.sent')</el-button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </transition>
                                                </div>
                                            </div>
                                            <div class="p-chat_map" v-if="!chat.page"  key="gmap">
                                                <div id="ordersMap"></div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <h3>@lang('v.no_propun')</h3>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </section>

                        <section class="sc-profile client my-cars" v-show="tab === 3">
                            <template v-if="choose.addcar" v-cloak>
                                @if(Auth::guard('customer')->check())
                                    <div class="choose-back">
                                        <button class="choose-back_button"
                                                v-on:click="chooseCarBack()">
                                            <div>
                                                <i class="icon-arrow-left-long"></i>
                                                <span>@lang('v.return')</span>
                                            </div>
                                        </button>
                                    </div>
                                @endif
                                <section class="order-body_ul" v-if="choose.progress > 1">
                                    <ul>
                                        <li class="img" v-if="choose.progress > 1" key="foto"><img v-on:click="choose.progress = 1" :src="'{{asset('/images/marks')}}/' + selectedMarkaImage" alt=""></li>
                                        <li class="marka" v-if="choose.progress > 1" key="marka"><span v-on:click="choose.progress = 1" v-text="selectedMarkaName"></span></li>
                                        <li class="model" v-if="choose.progress > 2" key="model"><span v-on:click="choose.progress = 2" v-text="selectedModelName"></span></li>
                                        <li class="year" v-if="choose.progress > 3" key="year"><span v-on:click="choose.progress = 3" v-text="selectedYear"></span></li>
                                    </ul>
                                </section>
                                <section class="order-body_up" v-if="choose.progress === 1" key="marka">
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
                                                       autocomplete="off"
                                                       :class="{error: errors.has('VIN')}" />
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
                                        <span class="order_next_comment"
                                                v-if="choose.carExists"
                                                v-cloak>Данный автомобиль уже присутствует в списке</span>
                                        <el-button
                                                :disabled="choose.autoLoading"
                                                :loading="choose.autoLoading"
                                                type="primary"
                                                class="btn-red"
                                                v-on:click="@if(Auth::guard('customer') -> user()) addToMyCar @else makeAutoSelected @endif">
                                            <div class="order-next_button">
                                                <span>@if(Auth::guard('customer') -> user()) @lang('v.add_my_car') @else @lang('v.select_my_auto') @endif</span>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 476.213 476.213" style="enable-background:new 0 0 476.213 476.213;fill:white;" xml:space="preserve" width="25px" height="15px">
                                                    <polygon points="345.606,107.5 324.394,128.713 418.787,223.107 0,223.107 0,253.107 418.787,253.107 324.394,347.5
                                                        345.606,368.713 476.213,238.106 "/>
                                                </svg>
                                            </div>
                                        </el-button>
                                    </div>
                                </section>
                            </template>
                            <template v-if="choose.progress === 1 && !choose.addcar" v-cloak>
                                <section ref="lista">
                                    {{--<h2 class="order-body_title">Выберите авто из уже ранее добавленных или добавьте новый</h2>--}}
                                    <div class="order-cars_wrapper">
                                        <div class="order-cars_add">
                                            <button class="btn-red" v-on:click="choose.addcar = true">@lang('v.add_new_car')</button>
                                        </div>
                                        <transition-group tag="div" name="lista" mode="out-in" class="order-cars">
                                            <div class="car"
                                                 :key="'mycar'+index"
                                                 :ref="'mycars' + index"
                                                 :id="'mycar'+index"
                                                 v-for="(item, index) in choose.mycars">
                                                <ul>
                                                    <li class="img"><img :src="'{{asset('/images/marks')}}/' + item.image" alt="" /></li>
                                                    <li class="marka"><span v-text="item.marka"></span></li>
                                                    <li class="model"><span v-text="item.model"></span></li>
                                                    <li class="year"><span v-text="item.year"></span></li>
                                                    <li class="vin"><span v-text="item.vin"></span></li>
                                                </ul>
                                                <div class="close" v-on:click="openRemoveMyCarPopup(item.id,index)"><i class="icon-remove-icon"></i>{{--<img src="{{asset('img/cancel-red.svg')}}" alt="">--}}</div>
                                            </div>
                                            <div class="no-car" key="no-car" v-if="!choose.mycars.length" v-cloak>
                                                <p>@lang('v.pss_auto')</p>
                                            </div>
                                        </transition-group>
                                    </div>
                                </section>
                            </template>
                        </section>
                    </div>
                    <div class="sc-content second" v-if="tab === 1">
                        <div class="sc-profile" >
                            <h2 class="sc-profile_title">@lang('v.change_password')</h2>
                            <div class="sc-profile_pas">
                                <div class="wrapper">
                                    <div class="input-wr">
                                        <label for="" class="label">@lang('v.old_password')</label>
                                        <input type="password"
                                               class="input"
                                                {{--data-vv-scope="profile"--}}
                                               v-validate="'required|min:1|max:25'"
                                               name="prof_pas"
                                               data-vv-validate-on="none"
                                               v-model="data.pas.old"
                                               :class="{ error: errors.has('prof_pas') }"
                                               v-on:focus="removeError('prof_pas','profile')"
                                               autocomplete="none" />
                                        <span :class="{ error: errors.has('prof_pas') }"
                                              v-if="errors.has('prof_pas')"
                                        >@lang('v.error_password_old')</span>
                                    </div>
                                    <div class="input-wr">
                                        <label for="" class="label">@lang('v.new_password')</label>
                                        <input type="password"
                                               class="input"
                                               v-validate="'required|min:1|max:25'"
                                               name="prof_newpas"
                                               data-vv-validate-on="none"
                                               v-model="data.pas.new"
                                               :class="{ error: errors.has('prof_newpas') }"
                                               v-on:focus="removeError('prof_newpas')"
                                               autocomplete="new-password" />
                                        <span :class="{ error: errors.has('prof_newpas') }"
                                              v-if="errors.has('prof_newpas')"
                                        >@lang('v.error_new_password')</span>
                                    </div>
                                    <div class="input-wr">
                                        <label for="" class="label">@lang('v.new_passord_away')</label>
                                        <input type="password"
                                               class="input"
                                               v-validate="'required|confirmed:prof_newpas'"
                                               name="prof_newpasrepeat"
                                               data-vv-validate-on="none"
                                               v-model="data.pas.repeat"
                                               :class="{ error: errors.has('prof_newpasrepeat') }"
                                               v-on:focus="removeError('prof_newpasrepeat')"
                                               autocomplete="new-password" />
                                        <span :class="{ error: errors.has('prof_newpasrepeat') }"
                                              v-if="errors.has('prof_newpasrepeat')"
                                        >@lang('v.error_new_passord_away')</span>
                                    </div>
                                </div>
                                <button class="btn-red sendPas" v-on:click="sendPas">
                                    <i class="el-icon-loading" v-if="pasProcess"></i>
                                    <span v-if="!pasProcess">@lang('v.change_password2')</span>
                                    <span v-if="pasProcess">@lang('v.save_pasword')</span>
                                </button>
                                <transition name="fadelong">
                                    <span v-if="data.pas.success" class="sendPas-success">@lang('v.succesful_changed')</span>
                                </transition>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')

@stop