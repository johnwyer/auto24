@extends('.front/layout/layout')
@section('meta')
@stop
@section('content')
    <?php
        $auth = Auth::guard('dealer')->user();
        $location_info = array_flatten(\App\Models\Localization::whereIn('id',[$auth->id_district,$auth->id_city])
                ->orderBy('id','asc')
                ->select('name_'.Lang::getLocale())
                ->get()
                ->toArray());
    ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    <script>
        var services = {!! $autoservice !!};
        var options = {!! $option !!};
        var marks = {!! $marks !!};
        var info = {
            name:'{!!$auth->name !!}',
            autoservice_id:'{!! $auth->id !!}',
            adr:'{!!$auth->address !!}',
            x:'{!!$auth->cord_x !!}',
            y:'{!!$auth->cord_y !!}',
            img:'{!!$auth->image !!}',
            link:'{{URL::route('autoservice_page' , $auth->slug)}}',
            location:{district:'{{$auth->id_district}}',city: '{{$auth->id_city}}'},
            location_text:{district:'{{isset($location_info[0]) ? $location_info[0] : null }}',city:'{{isset($location_info[1]) ? $location_info[1] : null }}'},
            rating:'{!! $data['review'] !!}',
            status:'{!! $data['status'] !!}',
            order_price: {!!(int)$data['order_price'] !!},
            email: '{!! $auth->email !!}',
            confirm_email: {!! $auth->confEm == "1" ? 'true' : 'false' !!},
            log_phone:'{!! $data['log_phone']!!}'
        };
                @if($auth->week)
                    var week = {!!$auth->week !!};
                @else
                    var week = [
                    {id:'1',from:'08:00',to:'23:00',no:false,name_ru:'Понедельник',name_ro:'Luni'},
                    {id:'2',from:'08:00',to:'23:00',no:false,name_ru:'Вторник',name_ro:'Marti'},
                    {id:'3',from:'08:00',to:'23:00',no:false,name_ru:'Среда',name_ro:'Miercuri'},
                    {id:'4',from:'08:00',to:'23:00',no:false,name_ru:'Четверг',name_ro:'Joi'},
                    {id:'5',from:'08:00',to:'23:00',no:false,name_ru:'Пятница',name_ro:'Vineri'},
                    {id:'6',from:'08:00',to:'23:00',no:true,name_ru:'Суббота',name_ro:'Simbata'},
                    {id:'7',from:'08:00',to:'23:00',no:true,name_ru:'Воскресенье',name_ro:'Duminica'}
                ];
                @endif

        var phones = {!! json_encode($phones) !!};
        var slider = {!! $slider !!};
        var orders_active  = {!! json_encode($data['order_active']) !!};
        var orders_work  = {!! json_encode($data['order_in_work']) !!};
        var orders_archive = {!! json_encode($data['order_arhive']) !!};
        var allmarks = {!! $data['mark_for_filter'] !!};
        var allcity = {!! $data['city_for_filter'] !!};
        var notify = {!! $notification !!};
        var transaction_statistic = {!! json_encode($data['transaction_statistic']) !!};
    </script>
    <div id="serviceCabinet" class="sc" v-cloak>

        <chat-modal v-if="modal.autoService.confirmRepairs && orderPageInfo.statusId === 2"
                    v-on:close="modal.autoService.confirmRepairs = false"
                    class="modal-auth modal-chat"
                    key="modal-auth"
                    v-cloak>
            <div class="block">
                <i18n tag="p" path="message.confirm_order_service">
                    <strong place="num">#@{{ orderPageInfo.num }}</strong>
                </i18n>
            </div>
            <div class="block">
                <el-button type="primary"
                           id="confirmRemontBtn"
                           v-model="orderPageInfo.statusId"
                           class="btn-blue"
                           v-on:click="confirmRemont()"
                           :disabled="orderPageInfo.statusId > 2"
                >@lang('v.confirmed')</el-button>
                <el-button type="secondary"
                           class="btn-gray"
                           v-on:click="modal.autoService.confirmRepairs = false"
                >@lang('v.canceled_btn')</el-button>
            </div>
        </chat-modal>

        <phone-modal v-if="modal.confirmPhoneRemoving"
                     v-on:close="modal.confirmPhoneRemoving = false"
                     class="modal-auth modal-chat"
                     key="modal-auth"
                     v-cloak>
            <div class="block">
                <p v-html="$refs.userPhonesComponent.getDeletePhoneNumberMessage()"></p>
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

        <balance-modal v-if="modal.autoService.wallet"
                                               v-on:close="modal.autoService.wallet = false"
                                               class="modal-auth modal-balance"
                                               key="modal-auth"
                                               v-cloak>
            <div class="block">
                <h3>@lang('v.estimating_cost')</h3>
                <p>@lang('v.not_money')</p>
                <p><a href="#" title="">@lang('v.found_count')</a> @lang('v.again')</p>
                <p>@lang('v.price_service') <span>@{{ orderPageInfo.orderPrice }} @lang('v.mdl')</span></p>
            </div>
        </balance-modal>


        <div class="sc-title">
            <h1>
                <a href="{{URL::route('autoservice_page',$auth->slug)}}">VEZI</a>
                <span v-if="tab === 1" key="tab1">@lang('v.profile')</span>
                <span v-else-if="tab === 2" key="tab2">@lang('v.review_to_repair')</span>
                <span v-else-if="tab === 3" key="tab3">@lang('v.price_list')</span>
                <span v-else-if="tab === 4" key="tab4">@lang('v.add_funds')</span>
                {{--<span v-else-if="tab === 4" key="tab4">Финансовая история</span>--}}
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
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="31px" height="31px">
                                    <text font-family="Roboto"  transform="matrix( 0.70343927560746, 0, 0, 0.70343927560746,6, 20)" font-size="18px">LEI</text>
                                    <path fill-rule="evenodd"  stroke-width="1px" :stroke="tab === 3 ? 'black' : '#0363cd'" fill-opacity="0"
                                          d="M15.500,2.500 C22.680,2.500 28.500,8.320 28.500,15.500 C28.500,22.680 22.680,28.500 15.500,28.500 C8.320,28.500 2.500,22.680 2.500,15.500 C2.500,8.320 8.320,2.500 15.500,2.500 Z"/>
                                </svg>
                            </div>
                            <span>@lang('v.price_list')</span>
                        </li>
                        <li v-on:click="changeTab(4)" :class="{active:tab === 4}">
                            <div class="svg">
                                <i class="icon-add-funds"></i>
                            </div>
                            <span>@lang('v.add_funds')</span>
                        </li>
                        {{--                        <li v-on:click="changeTab(4)" :class="{active:tab === 4}">
                                                    <div class="svg">
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                             viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve" width="25px" height="25px">
                                                    <g>
                                                        <circle cx="56" cy="23" r="1"/>
                                                        <circle cx="56" cy="29" r="1"/>
                                                        <circle cx="53" cy="32" r="1"/>
                                                        <circle cx="41" cy="32" r="1"/>
                                                        <circle cx="44" cy="29" r="1"/>
                                                        <circle cx="26" cy="41" r="1"/>
                                                        <circle cx="53" cy="38" r="1"/>
                                                        <circle cx="56" cy="35" r="1"/>
                                                        <circle cx="56" cy="41" r="1"/>
                                                        <circle cx="47" cy="38" r="1"/>
                                                        <circle cx="50" cy="35" r="1"/>
                                                        <circle cx="50" cy="41" r="1"/>
                                                        <circle cx="35" cy="38" r="1"/>
                                                        <circle cx="41" cy="38" r="1"/>
                                                        <circle cx="23" cy="44" r="1"/>
                                                        <circle cx="29" cy="44" r="1"/>
                                                        <circle cx="11" cy="44" r="1"/>
                                                        <circle cx="53" cy="44" r="1"/>
                                                        <circle cx="47" cy="44" r="1"/>
                                                        <circle cx="35" cy="44" r="1"/>
                                                        <circle cx="41" cy="44" r="1"/>
                                                        <circle cx="38" cy="35" r="1"/>
                                                        <circle cx="44" cy="35" r="1"/>
                                                        <circle cx="38" cy="41" r="1"/>
                                                        <circle cx="44" cy="41" r="1"/>
                                                        <circle cx="23" cy="50" r="1"/>
                                                        <circle cx="26" cy="47" r="1"/>
                                                        <circle cx="26" cy="53" r="1"/>
                                                        <circle cx="29" cy="50" r="1"/>
                                                        <circle cx="17" cy="50" r="1"/>
                                                        <circle cx="20" cy="53" r="1"/>
                                                        <circle cx="5" cy="50" r="1"/>
                                                        <circle cx="11" cy="50" r="1"/>
                                                        <circle cx="8" cy="47" r="1"/>
                                                        <circle cx="14" cy="47" r="1"/>
                                                        <circle cx="8" cy="53" r="1"/>
                                                        <circle cx="14" cy="53" r="1"/>
                                                        <circle cx="53" cy="50" r="1"/>
                                                        <circle cx="56" cy="47" r="1"/>
                                                        <circle cx="56" cy="53" r="1"/>
                                                        <circle cx="47" cy="50" r="1"/>
                                                        <circle cx="50" cy="47" r="1"/>
                                                        <circle cx="50" cy="53" r="1"/>
                                                        <circle cx="32" cy="47" r="1"/>
                                                        <circle cx="32" cy="53" r="1"/>
                                                        <circle cx="35" cy="50" r="1"/>
                                                        <circle cx="41" cy="50" r="1"/>
                                                        <circle cx="38" cy="47" r="1"/>
                                                        <circle cx="44" cy="47" r="1"/>
                                                        <circle cx="38" cy="53" r="1"/>
                                                        <circle cx="44" cy="53" r="1"/>
                                                        <path d="M7,29h2c0.553,0,1-0.447,1-1s-0.447-1-1-1H7c-0.553,0-1,0.447-1,1S6.447,29,7,29z"/>
                                                        <path d="M7,25h2c0.553,0,1-0.447,1-1s-0.447-1-1-1H7c-0.553,0-1,0.447-1,1S6.447,25,7,25z"/>
                                                        <path d="M7,21h2c0.553,0,1-0.447,1-1s-0.447-1-1-1H7c-0.553,0-1,0.447-1,1S6.447,21,7,21z"/>
                                                        <path d="M7,17h2c0.553,0,1-0.447,1-1s-0.447-1-1-1H7c-0.553,0-1,0.447-1,1S6.447,17,7,17z"/>
                                                        <path d="M7,13h2c0.553,0,1-0.447,1-1s-0.447-1-1-1H7c-0.553,0-1,0.447-1,1S6.447,13,7,13z"/>
                                                        <path d="M7,9h2c0.553,0,1-0.447,1-1S9.553,7,9,7H7C6.447,7,6,7.447,6,8S6.447,9,7,9z"/>
                                                        <path d="M7,5h2c0.553,0,1-0.447,1-1S9.553,3,9,3H7C6.447,3,6,3.447,6,4S6.447,5,7,5z"/>
                                                        <path d="M47.939,29.253l-6-8l-11,17l-6.141-8.188l-7,15l-5.69-7.588L4,45.586V1c0-0.553-0.447-1-1-1S2,0.447,2,1v46.586V56H1
                                                            c-0.553,0-1,0.447-1,1s0.447,1,1,1h1v1c0,0.553,0.447,1,1,1s1-0.447,1-1v-1h55h1V10.614L47.939,29.253z M54,56c0-0.552-0.448-1-1-1
                                                            s-1,0.448-1,1h-4c0-0.552-0.448-1-1-1s-1,0.448-1,1h-4c0-0.552-0.448-1-1-1s-1,0.448-1,1h-4c0-0.552-0.448-1-1-1s-1,0.448-1,1h-4
                                                            c0-0.552-0.448-1-1-1s-1,0.448-1,1h-4c0-0.552-0.448-1-1-1s-1,0.448-1,1h-4c0-0.552-0.448-1-1-1s-1,0.448-1,1h-4
                                                            c0-0.552-0.448-1-1-1s-1,0.448-1,1H6c0-0.552-0.448-1-1-1s-1,0.448-1,1v-6v-1.586l7.892-7.892l6.31,8.412l0.83-1.779
                                                            C19.109,47.631,19.503,48,20,48c0.552,0,1-0.448,1-1s-0.448-1-1-1c-0.187,0-0.352,0.065-0.501,0.154l5.702-12.219l5.859,7.813
                                                            l0.127-0.196C31.367,41.815,31.656,42,32,42c0.552,0,1-0.448,1-1c0-0.492-0.363-0.883-0.831-0.966l9.892-15.287l4.723,6.297
                                                            C46.339,31.145,46,31.525,46,32c0,0.552,0.448,1,1,1c0.375,0,0.689-0.215,0.86-0.52l0.2,0.267l4.067-6.285
                                                            C52.296,26.778,52.617,27,53,27c0.552,0,1-0.448,1-1c0-0.529-0.415-0.952-0.935-0.987L58,17.386V56H54z"/>
                                                    </g>
                                                </svg>

                                                    </div>
                                                    <span>Финансовая история</span>
                                                </li>--}}
                        <li>
                            <a href="{{URL::route('logout_autoservice')}}">@lang('v.exit')</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sc-right">
                <div class="sc-right_wrapper">
                    {{--
                    <div class="sc-bid" v-if="tab === 2">
                        <ul>
                            <li><b v-text="bid.order"></b><span>Заявки на ремонт</span></li>
                            <li><b v-text="bid.response"></b><span>Ответов на заявки</span></li>
                            <li><b v-text="bid.in"></b><span>Записи на ремонт</span></li>
                            <li><b v-text="bid.no"></b><span>Заявок без ответа</span></li>
                        </ul>
                    </div>--}}
                    <div class="sc-content">
                        {{--<router-view></router-view>--}}

                        <section class="sc-profile" v-if="tab === 1"> {{--  add class client  --}}
                            @if(!$auth->status)
                                <div class="test" style="color: #f0350b"> @lang('v.not_confirmed') </div> <br>
                            @endif
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
                                        <label for="" class="label">@lang('v.name_autoservice')</label>
                                        <input type="text"
                                               v-validate="'required|min:1|max:50'"
                                               name="prof_data_name"
                                               data-vv-validate-on="none"
                                               v-model="data.info.name"
                                               :class="{ error: errors.has('prof_data_name') }"
                                               v-on:focus="removeError('prof_data_name')"
                                               class="input">
                                        <span :class="{ error: errors.has('prof_data_name') }"
                                              v-if="errors.has('prof_data_name')"
                                        >@lang('v.autoservice_error')</span>
                                    </div>
                                </div>

                                <div class="sc-profile_data--map grid-block">
                                    <get-address ref="mainChooseAddress"
                                                 v-on:locationchanged="locationchanged"
                                                 :component-data="selectedAddress"
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
{{--                                    <div class="showOnMap"
                                         :class="{ error: errors.has('regService.mp_coord') }">
                                        --}}{{--<img v-on:click="getCoord = !getCoord" src="{{asset('/img/point_vue_map.svg')}}" alt="" id="vueMap"  tabindex="107">--}}{{--
                                        <div class="show-on-map-link" v-on:click="getCoord = !getCoord">
                                            <div v-if="!checkAutoserviceMapCoordinates()">
                                                <i class="icon-placeholder-filled-point"
                                                   id="vueMap"
                                                   tabindex="107"></i>
                                                <span>Отметить местонахождение на карте</span>
                                            </div>
                                            <div v-else>
                                                <i class="icon-placeholder-filled-point selected"
                                                   id="vueMap"
                                                   tabindex="107"></i>
                                                <span>Изменить местоположение на карте</span>
                                            </div>
                                        </div>
                                        <transition name="fade">
                                            <div class="vueMapInput-wrapper" v-if="getCoord">
                                                <img src="{{asset('/img/close.svg')}}" alt="" class="vueMapInput-img" v-on:click="getCoord = false"  tabindex="108">
                                                <button class="vueMapInput-button" v-on:click.prevent="addCoord"  tabindex="109">Продолжить</button>
                                                <div class="vueMapInput" id="mapAdd" ></div>
                                            </div>
                                        </transition>
                                        <input type="text" hidden v-validate="'required'" name="mp_coord" v-model="mapsCoord" />
                                    </div>--}}{{--                                    <div class="showOnMap"
                                         :class="{ error: errors.has('regService.mp_coord') }">
                                        --}}{{--<img v-on:click="getCoord = !getCoord" src="{{asset('/img/point_vue_map.svg')}}" alt="" id="vueMap"  tabindex="107">--}}{{--
                                        <div class="show-on-map-link" v-on:click="getCoord = !getCoord">
                                            <div v-if="!checkAutoserviceMapCoordinates()">
                                                <i class="icon-placeholder-filled-point"
                                                   id="vueMap"
                                                   tabindex="107"></i>
                                                <span>Отметить местонахождение на карте</span>
                                            </div>
                                            <div v-else>
                                                <i class="icon-placeholder-filled-point selected"
                                                   id="vueMap"
                                                   tabindex="107"></i>
                                                <span>Изменить местоположение на карте</span>
                                            </div>
                                        </div>
                                        <transition name="fade">
                                            <div class="vueMapInput-wrapper" v-if="getCoord">
                                                <img src="{{asset('/img/close.svg')}}" alt="" class="vueMapInput-img" v-on:click="getCoord = false"  tabindex="108">
                                                <button class="vueMapInput-button" v-on:click.prevent="addCoord"  tabindex="109">Продолжить</button>
                                                <div class="vueMapInput" id="mapAdd" ></div>
                                            </div>
                                        </transition>
                                        <input type="text" hidden v-validate="'required'" name="mp_coord" v-model="mapsCoord" />
                                    </div>--}}
                                </div>

                                <div class="sc-profile_data--address grid-block">
                                    <div class="showOnMap"
                                         :class="{ error: errors.has('regService.mp_coord') }">
                                        {{--<img v-on:click="getCoord = !getCoord" src="{{asset('/img/point_vue_map.svg')}}" alt="" id="vueMap"  tabindex="107">--}}
                                        <div class="show-on-map-link" v-on:click="getCoord = !getCoord">
                                            <div v-if="!checkAutoserviceMapCoordinates()">
                                                <i class="icon-placeholder-filled-point"
                                                   id="vueMap"
                                                   tabindex="107"></i>
                                                <span>@lang('v.select_to_map')</span>
                                            </div>
                                            <div v-else>
                                                <i class="icon-placeholder-filled-point selected"
                                                   id="vueMap"
                                                   tabindex="107"></i>
                                                <span>@lang('v.change_location_on_map')</span>
                                            </div>
                                        </div>
                                        <transition name="fade">
                                            <div class="vueMapInput-wrapper" v-if="getCoord">
                                                <img src="{{asset('/img/close.svg')}}" alt="" class="vueMapInput-img" v-on:click="getCoord = false"  tabindex="108">
                                                <button class="vueMapInput-button" v-on:click.prevent="addCoord"  tabindex="109">Продолжить</button>
                                                <div class="vueMapInput" id="mapAdd" ></div>
                                            </div>
                                        </transition>
                                        <input type="text" hidden v-validate="'required'" name="mp_coord" v-model="mapsCoord" />
                                    </div>
                                </div>

                                <div class="sc-profile_data--dopadr grid-block">
                                    <div class="input-wr">
                                        <label for="" class="label">Улица</label>
                                        <input class="input"
                                               type="text"
                                               name="adress"
                                               v-model="data.info.adr"
                                               id="dopAdr" />
                                    </div>
                                </div>

                                <div class="sc-profile_data--mainfoto grid-block">
                                    <div class="input-wr" :class="{error:mainFotoError}">
                                        <label for="" class="label">@lang('v.foto_profile')</label>
                                        <upload-foto v-on:input-changed="mainFotoChanged"
                                                     v-on:deleted="mainFoto = ''"
                                                     ref="mainfoto"
                                                     image="{!! $auth->image !!}">
                                            <span>@lang('v.select')</span>
                                        </upload-foto>
                                    </div>
                                </div>
                            </div>

                            <user-phones v-on:phones-list-changed="phonesListChanged"
                                         v-on:phone-changed="phoneChanged"
                                         :initial-user-phones="userPhones"
                                         :initial-sms-code-time="SMS_CODE_TIME"
                                         ref="userPhonesComponent">
                            </user-phones>

                            <div class="sc-profile_data">
                                <div class="sc-profile_data--time grid-block">
                                    {{--<div class="title">График работы</div>--}}
                                    <h2 class="sc-profile_title">@lang('v.chedule_work')</h2>
                                    <div class="block">
                                        <div class="block-item"
                                             :class="{disabled: item.no}"
                                             v-for="item in week">
                                            <div class="block-item_title" v-text="item.name_ru"></div>
                                            <div class="block-item_time">
                                                <el-time-select v-model="item.from"
                                                                :editable="false"
                                                                :picker-options="timeOptions"
                                                                :clearable="false"
                                                                :placeholder="item.from">
                                                </el-time-select>
                                                <span>—</span>
                                                <el-time-select v-model="item.to"
                                                                :editable="false"
                                                                :clearable="false"
                                                                :picker-options="timeOptions"
                                                                :placeholder="item.to">
                                                </el-time-select>
                                                <el-checkbox v-model.boolean="item.no"
                                                             el-name="grafic">@lang('v.output')</el-checkbox>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h2 class="sc-profile_title">@lang('v.detail_autoservice')</h2>
                            <div class="sc-profile_data--slider grid-block">
                                <div class="title">@lang('v.image_autoservice')</div>
                                <upload-slider v-on:del="addToDel"
                                               ref="uploadSlider"
                                               :images="defaultSlider"></upload-slider>
                            </div>

                            {{-- service --}}

                            <div class="sc-profile_data--goods">
                                <div class="title">@lang('v.confort')</div>
                                <div class="goods-wrapper">
                                    <div class="goods-item" v-for="(item,index) in goods">
                                        <el-checkbox
                                                v-model="item.check"
                                                :checked="item.check == 1">
                                            <div class="svg" v-html="item.image"></div>
                                            {{--<img :src="'{{asset('/images/facilities')}}/'+item.image" alt="">--}}
                                            <span v-text="item.name_ru"></span>
                                        </el-checkbox>
                                    </div>
                                </div>
                            </div>
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
                        <section class="sc-profile" v-show="tab === 2" ref="body">
                            <div class="stat">
                                {{--                                <button class="stat-btn"
                                                                        v-on:click="setOrderState(0)"
                                                                        :class="{active: orderState === 0}">
                                                                    <div>
                                                                        <span>Все заявки</span>
                                                                        <b v-text="orders_all.total"></b>
                                                                        --}}{{--<b v-text="getOrdersCount(0)"></b>--}}{{--
                                                                    </div>
                                                                </button>--}}
                                <button class="stat-btn active-orders"
                                        v-on:click="setOrderState(1)"
                                        :class="{active: orderState === 1}">
                                    <div>
                                        <span>@lang('v.active')</span>
                                        {{--<b>{{$data['order_active']['total']}}</b>--}}
                                        <b v-text="orders_active.total"></b>
                                    </div>
                                </button>
                                {{--
                                <button class="stat-btn direct-orders"
                                        v-on:click="orderState = 4"
                                        :class="{active:orderState ===4}">
                                    <div>
                                        <span>Прямые записи</span>
                                        <b>{{$data['']['total']}}</b>
                                    </div>
                                </button>--}}
                                <button class="stat-btn work-orders"
                                        v-on:click="setOrderState(2)"
                                        :class="{active: orderState === 2}">
                                    <div>
                                        <span>@lang('v.in_work')</span>
                                        {{--<b>{{$data['order_in_work']['total']}}</b>--}}
                                        <b v-text="orders_work.total"></b>
                                    </div>
                                </button>
                                <button class="stat-btn archive-orders"
                                        v-on:click="setOrderState(3)"
                                        :class="{active: orderState === 3}">
                                    <div>
                                        <span>@lang('v.arhive')</span>
                                        {{--<b>{{$data['order_arhive']['total']}}</b>--}}
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
                                                    placeholder="Город"
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
                                    <transition-group name="fade" mode="out-in" tag="div" :duration="100" appear>
                                        {{--<div class="stat-orders_wrapper" key="orders_all" v-if="orderState === 0">
                                            <div class="or"
                                                 :class="{archived: getOrderStatusId(item.status) > 3}"
                                                 v-for="(item, index) in orders_all.order"
                                                 v-on:click="showMessageAuto(item)">
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
                                                    <div class="message-wrapper"
                                                         v-cloak>
                                                        <div class="message-count"
                                                             v-text="item.messages"
                                                             v-if="item.messages > 0 && item.messages !== 0"></div>
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
                                                         v-on:click="showMessageAuto(item)">
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
                                                                     v-if="item.messages > 0 && item.messages !== 0"></div>
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

                                        <div v-if="orderState === 2" key="orders_work">
                                            <div v-if="orders_work.order.length > 0">
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
                                                         v-for="(item, index) in orders_work.order"
                                                         v-on:click="showMessageAuto(item)">
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

                                        <div v-if="orderState === 3" key="orders_archive">
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
                                                         v-on:click="showMessageAuto(item)">
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
                                        <button v-on:click="orderPageBack(true)" class="p-back_button">
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
                                        <h2>@lang('v.review') #<span v-text="orderPageInfo.num"></span></h2>
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
                                            <li><span>@lang('v.status')</span><span class="text-bold" v-text="getOrderStatusName()"></span></li>
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
                                            <li v-for="serviceCategory in orderPageInfo.services"
                                                v-if="serviceCategory.type === 'service'">
                                                <span v-text="serviceCategory.name"></span>
                                                <ul v-if="serviceCategory.child">
                                                    <li v-for="serviceCategoryItem in serviceCategory.child">
                                                        <span v-text="serviceCategoryItem"></span>
                                                        <span v-text="serviceCategory.type"></span>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="p-services type-manually"
                                         v-if="otherServices.length">
                                        <h3 class="p-services_title" v-if="orderPageInfo.services.length === 0">@lang('v.list_required_work')</h3>
                                        <ul class="p-services_ul">
                                            <li>
                                                <span>@lang('v.other_service')</span>
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
                                    <div class="a-chat">
                                        <div class="a-chat_title">@lang('v.answer_order')</div>
                                        <div class="chat-wrapper">
                                            <div class="chat-content">
                                                <div class="chat-content_block" ref="chatblock">
                                                    <el-scrollbar :noresize="false"
                                                                  :native="false"
                                                                  :view-style="{'max-height':'500px'}">
                                                        <transition-group class="block-wrapper"
                                                                          name="list"
                                                                          appear
                                                                          v-on:enter="chatBlockAppearHook"
                                                                          tag="div">
                                                            <div class="block"
                                                                 :key="'chatblock'+index"
                                                                 :ref="'chatblock' + index"
                                                                 :tabindex="index + 100"
                                                                 :class="{personal: clientId == item.resever_id}"
                                                                 v-for="(item, index) in chat.history">
                                                                <div class="name">
                                                                    <div v-if="clientId === item.resever_id">
                                                                        <span v-text="chat.clientName"></span>
                                                                        <span class="phone-number"
                                                                              v-if="clientId === item.resever_id"
                                                                              v-text="getClientPhone"></span>
                                                                    </div>
                                                                    <div v-else>
                                                                        <a class="autoservice-link"
                                                                           :href="data.info.link"
                                                                           v-text="data.info.name"></a>
                                                                        <span class="phone-number" v-text="getAutoserviceAddress"></span>
                                                                        <div class="rate">
                                                                            <div v-if="orderPageInfo.autoservice_rating.total > 0">
                                                                                <el-rate
                                                                                        :value="orderPageInfo.autoservice_rating.rating | numberF"
                                                                                        allow-half
                                                                                        disabled-void-color="#c8c8c8"
                                                                                        show-text
                                                                                        :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                        disabled>
                                                                                </el-rate>
                                                                                <span class="rate-counter">
                                                                                    <span v-text="orderPageInfo.autoservice_rating.total > 0 ? orderPageInfo.autoservice_rating.total : '@lang("v.not")'"></span>
                                                                                    <span v-text="returnText(orderPageInfo.autoservice_rating.total, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                                                </span>
                                                                            </div>
                                                                            <div v-else>
                                                                                <el-rate
                                                                                        :value="orderPageInfo.autoservice_rating.rating | numberF"
                                                                                        allow-half
                                                                                        disabled-void-color="#c8c8c8"
                                                                                        :colors="['#ffc323','#ffc323','#ffc323']"
                                                                                        disabled>
                                                                                </el-rate>
                                                                                <span class="rate-counter">
                                                                                    <span v-text="orderPageInfo.autoservice_rating.total > 0 ? orderPageInfo.autoservice_rating.total : '@lang("v.not")'"></span>
                                                                                    <span v-text="returnText(orderPageInfo.autoservice_rating.total, ['@lang("v.com")','@lang("v.comment")','@lang("v.comments")'])"></span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {{--<span v-text="clientId == item.resever_id ? chat.clientName : '{{Auth::guard('dealer')->user()->name}}'"></span>--}}
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

                                                                    <div v-if="item.message_status === 1 && orderPageInfo.autoservice_price !== null">
                                                                        <div class="text">Ремонт - <span v-text="orderPageInfo.autoservice_price + 'лей'"></span></div>
                                                                        <div class="text" v-if="orderPageInfo.autoservice_parts > 0">@lang('v.service')  - <span v-text="orderPageInfo.autoservice_parts + 'лей'"></span></div>
                                                                        <div class="text">Итого - <span v-text="getTotalPrice() + 'лей'"></span></div>
                                                                    </div>

                                                                    <div v-if="item.message_status === 2">
                                                                        <div class="text"><strong>@lang('v.confirm_record_msg')</strong></div>
                                                                        <div class="button-wrapper">
                                                                            <el-button type="primary"
                                                                                    {{--id="confirmRemontBtn"--}}
                                                                                    {{--v-model="orderPageInfo.statusId"--}}
                                                                                       class="btn-blue"
                                                                                       v-on:click="modal.autoService.confirmRepairs = true"
                                                                                       :disabled="orderPageInfo.statusId > 2"
                                                                            >Подтверждаю</el-button>
                                                                        </div>
                                                                        <div class="text"><i>@lang('v.btn_confirm')</i></div>
                                                                    </div>

                                                                    <div v-if="item.message_status === 3">
                                                                        <div class="text"><strong>@lang('v.confirm_')</strong></div>
                                                                    </div>

                                                                    <div v-if="item.message_status === 4">
                                                                        <div class="text"><strong>@lang('v.repair_finish_msg')</strong></div>
                                                                        {{--<div class="text" v-if="item.message === null">@lang('v.close_finish_msg')</div>--}}
                                                                        <div class="text" v-if="item.message !== null" v-text="item.message"></div>
                                                                        <div class="text"><i>@lang('v.leave_feedback_msg')</i></div>
                                                                    </div>

                                                                    <div v-if="item.message_status === 5">
                                                                        <div class="text"><strong>@lang('v.failure_repair')</strong></div>
                                                                        <div class="text" v-if="item.message === null">@lang('v.can_not_accept')</div>
                                                                        <div class="text" v-if="item.message !== null" v-text="item.message"></div>
                                                                    </div>

                                                                    <div v-if="item.message_status === 6">
                                                                        <div class="text"><strong>@lang('v.customer_set')</strong></div>
                                                                        <div class="rate" v-if="item.rating !== null">
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
                                            <transition name="fade" v-if="orderPageInfo.statusId < 4">
                                                <div class="chat-form">
                                                    <form action="" autocomplete="off" novalidate>
                                                        <div class="chat-form_header"
                                                             :class="{'type-short': orderPageInfo.statusId >= 3}">
                                                            <div v-if="chat.showPriceForm">
                                                                <div class="form-group-inline">
                                                                    <label class="control-label" for="">@lang('v.repair')</label>
                                                                    {{--<input type="text"
                                                                           class="form-control"
                                                                           name="firstPrice"
                                                                           v-model="chat.firstPrice"
                                                                           v-validate="'required|min:2|numeric'"
                                                                           data-vv-validate-on="none"
                                                                           :class="{ error: errors.has('firstPrice') }"
                                                                           v-on:focus="removeError('firstPrice')"
                                                                    />--}}
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="firstPrice"
                                                                           id="firstPrice"
                                                                           v-model="chat.firstPrice"
                                                                           v-on:keypress="numbersKeyValidator($event, chat.firstPrice)"
                                                                           v-on:focus="removeError2('firstPrice')" />
                                                                    <span class="form-control_notice">@lang('v.mdl')</span>
                                                                </div>
                                                                <div class="form-group-inline">
                                                                    <label class="control-label" for="">@lang('v.service')</label>
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           v-model="chat.partsPrice"
                                                                           name="partsPrice"
                                                                           id="partsPrice"
                                                                           v-on:keypress="numbersKeyValidator($event, chat.partsPrice)"
                                                                           v-on:focus="removeError2('partsPrice')" />
                                                                    <span class="form-control_notice">@lang('v.mdl')</span>
                                                                </div>
                                                                <div class="form-group-inline" v-if="getFormReparationPrice() > 0">
                                                                    <span class="total-price">@lang('v.total')  —  <em v-text="getFormReparationPrice()"></em> @lang('v.mdl')</span>
                                                                </div>
                                                            </div>

                                                            <div  v-if="orderPageInfo.statusId >= 3">
                                                                <div class="form-group-inline">
                                                                    <label class="control-label" for="order-select">@lang('v.status_rev')</label>
                                                                    <order-select v-model="orderPageFormSelect.value"
                                                                                  ref="elOrderSelect"
                                                                                  key="orderSelect"
                                                                                  name="order-select"
                                                                                  id="order-select">
                                                                        <el-option
                                                                                v-for="item in orderPageFormSelect.optionsList"
                                                                                :key="item.value"
                                                                                :label="item.label"
                                                                                :value="item.value">
                                                                        </el-option>
                                                                    </order-select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="chat-form_wrapper">
                                                            <div class="textarea">
                                                                <textarea name="chatArea"
                                                                          id="chatArea"
                                                                          :maxlength="chat.messageLimit"
                                                                          v-on:keydown="checkChatMessage($event, 'false')"
                                                                          v-on:keyup.delete="checkChatMessage($event, 'true')"
                                                                          v-on:focus="removeError2('chatArea')"
                                                                          v-on:keyup.ctrl.enter="addToChatService"
                                                                          placeholder="@lang('v.add_message')"
                                                                          v-model="chat.textarea">
                                                                </textarea>
                                                            </div>
                                                            <div class="send">
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
                                                            <div class="send" v-if="chat.imagesThumbs.length > 0">
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
                                                                <span class="wallet-balance-info"
                                                                      v-if="chat.showPriceForm">@lang('v.account_charged') -<em>@{{ orderPageInfo.orderPrice }} @lang('v.mdl')</em></span>
                                                                <div class="send-submit">
                                                                    <el-button type="primary"
                                                                               native-type="button"
                                                                               class="btn-red"
                                                                               v-on:click="addToChatService"
                                                                               :loading="chat.loading"
                                                                               :disabled="chat.loading">@lang('v.sent')
                                                                    </el-button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </transition>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </section>
                        <section class="sc-price" v-if="tab === 3">
                            <div class="sc-price_wrapper">
                                <h2 class="sc-price_title">@lang('v.select_special')</h2>
                                <ul>
                                    <li v-for="(item, index) in priceList" v-on:click="showInner($event)">
                                        <div class="svg" v-html="item.image ? item.image : priceListBlock"></div>
                                        <div class="link" v-text="item.name_ru"></div>
                                        <div class="inner" v-if="item.next.length || item.service.length" v-on:click.stop>
                                            <div class="inner-title">
                                                <span>@lang('v.services')</span>
                                                <span>@lang('v.price_at')</span>
                                            </div>
                                            <div class="inner-scroll">
                                                <el-scrollbar :noresize="false"
                                                              :native="false"
                                                              :view-style="{'max-height':'50vh'}">
                                                    <template v-if="item.next.length">
                                                        <div class="inner-block" v-for="(item,index) in item.next">
                                                            <div class="title" v-text="item.name_ru"></div>
                                                            <ul v-if="item.service.length">
                                                                <li v-for="(item,index) in item.service">
                                                                    <div>
                                                                        <p v-text="item.name_ru"></p>
                                                                    </div>
                                                                    <div>
                                                                        <input type="text" :key="'serv' + index" :value="item.price ? item.price : 0" :id="item.id" v-on:change.lazy="sendValue($event,item.id)">
                                                                        <span>@lang('v.mdl')</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </template>
                                                    <template v-if="item.service.length">
                                                        <div class="inner-block">
                                                            <ul>
                                                                <li v-for="(item,index) in item.service">
                                                                    <div>
                                                                        <p v-text="item.name_ru"></p>
                                                                    </div>
                                                                    <div>
                                                                        <input type="text" :key="'sr' + index" :value="item.price ? item.price : 0" :id="item.id" v-on:change.lazy="sendValue($event,item.id)">
                                                                        <span>@lang('v.mdl')</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </template>
                                                </el-scrollbar>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section>
                        <section class="sc-profile add-funds" v-if="tab === 4" ref="funds">
                            <personal-add-funds ref="personalAddFunds"
                                                :initial-token="token"></personal-add-funds>
                        </section>
                    </div>
                    <div class="sc-content second" v-if="tab === 1">
                        <div class="sc-profile">
                            <h2 class="sc-profile_title"><span>@lang('v.service_brands')</span>
                                <el-checkbox
                                        v-model="data.marks.check"
                                        v-on:change="setMarksChecked"
                                        :checked="data.marks.check"><span>@lang('v.select_all')</span>
                                </el-checkbox>
                            </h2>
                            <div class="sc-profile_data--service">
                                {{--<div class="title">Ремонтируемые марки</div>--}}
                                <div class="marks-wrapper">
                                    <div class="marks-item"
                                         :style="{'background-image':'url({{asset('/images/marks/')}}/'+item.image+')'}"
                                         :data-title="item.name_ru"
                                         :class="{active: item.check === 1}"
                                         v-on:click="marksCheck(index)"
                                         v-for="(item, index) in marks">
                                    </div>
                                </div>
                            </div>
                            <div class="sc-profile_submit">
                                <button class="btn-red sendInfo" v-on:click="sendInfo(true)">
                                    <i class="el-icon-loading" v-if="data.marks.process"></i>
                                    <span v-if="!data.marks.process">@lang('v.save_changes')</span>
                                    <span v-if="data.marks.process">@lang('v.save_chang')</span>
                                </button>
                                <transition name="fadelong">
                                    <span v-if="data.marks.success" class="sendInfo-success">@lang('v.data_successfully_changed')</span>
                                </transition>
                            </div>
                        </div>
                    </div>
                    <div class="sc-content third" v-if="tab === 1">
                        <div class="sc-profile">
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
                                               autocomplete="none">
                                        <span :class="{ error: errors.has('prof_pas') }"
                                              v-if="errors.has('prof_pas')"
                                        >error</span>
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
                                               autocomplete="new-password">
                                        <span :class="{ error: errors.has('prof_newpas') }"
                                              v-if="errors.has('prof_newpas')"
                                        >error</span>
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
                                               autocomplete="new-password">
                                        <span :class="{ error: errors.has('prof_newpasrepeat') }"
                                              v-if="errors.has('prof_newpasrepeat')"
                                        >error</span>
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