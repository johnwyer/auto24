<!DOCTYPE html>
<html lang="{{Lang::getLocale()}}">
<head>
    @yield('meta')
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{asset('css/app.css')}}" />

    @if(Auth::guard('dealer')->user())
    <?php
        $auth = Auth::guard('dealer')->user();
    ?>
        <script>
            var info = {
                autoservice_id:'{!! $auth->id !!}'
            }
        </script>
    @endif
</head>
<body @if(isset($log_page) && $log_page ) class="page-login" @endif @if(URL::route('home') == url()->current()) class="home-page" @endif>
<div class="goadmin">
    <a href="/admin"><img src="{{asset('/img/admin-with-cogwheels.svg')}}" alt=""></a>
</div>
<div class="content">
    <header id="header" class="header" :class="{bf: modal.reg}">
        <div class="header-container">
            <div class="header-button">
                <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="25px" height="19px">
                    <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                          d="M-0.000,-0.000 L25.000,-0.000 L25.000,3.000 L-0.000,3.000 L-0.000,-0.000 Z" />
                    <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                          d="M-0.000,8.000 L25.000,8.000 L25.000,11.000 L-0.000,11.000 L-0.000,8.000 Z" />
                    <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                          d="M-0.000,16.000 L25.000,16.000 L25.000,19.000 L-0.000,19.000 L-0.000,16.000 Z" />
                </svg>
            </div>
            <div class="header-logo">
                @if(Lang::getLocale() == 'ru')
                <a href="/{{Lang::getLocale()}}" title=""><img src="{{asset('/img/logo.svg')}}" alt="" /></a>
                @else
                <a href="/" title=""><img src="{{asset('/img/logo.svg')}}" alt="" /></a>
                @endif
            </div>
            <div class="header-menu">
                <div class="header-menu_base">
                    <ul>
                        <li class="active"><a href="#" title="">@lang('v.autoservices')</a></li>
                        <li><a href="#" title="">@lang('v.auto_motors')</a></li>
                    </ul>
                </div>
                <div class="header-menu_additional">
                    <ul>
                        <li class="active"><a href="">@lang('v.question_answer')</a></li>
                        <li><a href="">@lang('v.contacts')</a></li>
                    </ul>
                </div>
                {{-- nu este logat --}}
                @if(!Auth::guard('customer')->user() && !Auth::guard('dealer')->user())
                <div class="header-menu_log">
                    <ul>
                        <li><a class="bordered" href="{{URL::route('map')}}" title="@lang('v.search_map_btn')">@lang('v.search_map_btn')</a></li>
                        <li><a class="in" href="{{URL::route('enter')}}" title="@lang('v.sign')">@lang('v.sign')</a></li>
                        <li><a class="send-order" href="{!! URL::route('order') !!}" title="@lang('v.send_review_btn')">@lang('v.send_review_btn')</a></li>
                    </ul>
                </div>
                @endif
                {{-- #nu este logat --}}
                {{--  daca logat  --}}
                {{--  autoservice  --}}
                @if(Auth::guard('dealer')->user())
                    <?php
                        $wallet = Auth::guard('dealer')->user()->amount;
                    ?>
                <div class="header-auth">
                    <div class="header-auth_bell"
                         v-on:click="notifyList.show = !notifyList.show"
                         :class="{active : getNewNotificationsCount > 0}">
                        <i class="icon-bell"></i>
                        <div class="count" v-if="getNewNotificationsCount > 0" v-text="getNewNotificationsCount" v-cloak></div>
                    </div>
                    <div class="header-auth_cab"  v-on:mouseover="notifyList.show = false">
                        <span class="header-auth_cab--title" id="header-user-name">
                            <span class="header-auth_cab--title-text-wrapper">
                                <span class="title-text" v-text="getUserName">&nbsp;</span>
                            </span>
                        </span>
                        <span class="header-auth_cab--wallet"><span class="wallet-count" v-text="userBalanceAmount" v-cloak>{{$wallet}}</span> <span class="wallet-currency" v-text="">лей</span></span>
                        <ul class="header-auth_cab--ul">
                            <li><a href="{{URL::route('autoservice')}}?profile" title="@lang('v.profile')">@lang('v.profile')</a></li>
                            <li><a href="{{URL::route('autoservice')}}?orders" title="@lang('v.review_to_repair')">@lang('v.review_to_repair')</a></li>
                            <li><a href="{{URL::route('autoservice')}}?price-list" title="@lang('v.price_list')">@lang('v.price_list')</a></li>
                            <li><a href="{{URL::route('autoservice')}}?add-funds" title="@lang('v.add_funds')">@lang('v.add_funds')</a></li>
                            <li><a href="{{URL::route('logout_autoservice')}}" title="@lang('v.exit')">@lang('v.exit')</a></li>
                        </ul>
                    </div>

                    <div class="header-notify"
                         v-if="notifyList.show && getNotificationsCount"
                         :style="{left:notifyList.left}"
                         v-cloak>
                        <div class="header-notify__list">
                            <div class="header-notify__list-header">
                                <span class="title">@lang('v.notification')</span>
                                <a class="make-read"
                                   href="#"
                                   title="@lang('v.check_show')"
                                   v-on:click.prevent="notifyMarkRead()"
                                   v-if="getNewNotificationsCount > 0"
                                >@lang('v.check_show')</a>
                            </div>
                            <template>
                                <el-scrollbar
                                        :noresize="false"
                                        :native="false"
                                        v-loading="notificationsLoading"
                                        :view-style="{'max-height':'400px'}">
                                    <div class="header-notify__list-body" id="notifications-list">
                                        <div :key="'block'+index"
                                             class="block"
                                             :class="{'block-new': item.show === null}"
                                             v-for="(item, index) in notifyList.data"
                                             v-on:click="goNotify(item)">
                                            <div class="message" v-html="item.name"></div>
                                        </div>
                                    </div>
                                </el-scrollbar>
                            </template>
                        </div>
                    </div>
                </div>
                @endif
                {{--  #autoservice  --}}
                {{--  client  --}}
                @if(Auth::guard('customer')->user())
                    <?php
                      $wallet = Auth::guard('customer')->user()->amount;
                    ?>
                <div class="header-menu_wrapper">
                    <div class="header-menu_log">
                        <ul>
                            <li><a class="bordered" href="{{URL::route('map')}}" title="">@lang('v.search_map_btn')</a></li>
                        </ul>
                    </div>
                    <div class="header-auth">
                        <div class="header-auth_bell"
                             v-on:click="notifyList.show = !notifyList.show"
                             :class="{active: getNewNotificationsCount > 0}">
                            <i class="icon-bell"></i>
                            <div class="count" v-if="getNewNotificationsCount > 0" v-text="getNewNotificationsCount" v-cloak></div>
                        </div>
                        <div class="header-auth_cab type-client" v-on:mouseover="notifyList.show = false">
                            <span class="header-auth_cab--title" id="header-user-name">
                                <span class="header-auth_cab--title-text-wrapper">
                                    <span class="title-text" v-text="getUserName">&nbsp;</span>
                                </span>
                            </span>
                            <span class="header-auth_cab--wallet"><span class="wallet-count" v-text="">{{$wallet}}</span> <span class="wallet-currency" v-text="">@lang('v.mdl')</span></span>
                            <ul class="header-auth_cab--ul">
                                <li><a href="{!! URL::route('dashboard') !!}?profile" title="@lang('v.profile')">@lang('v.profile')</a></li>
                                <li><a href="{!! URL::route('dashboard') !!}?orders" title="@lang('v.review_to_repair')">@lang('v.review_to_repair')</a></li>
                                <li><a href="{!! URL::route('dashboard') !!}?my-cars" title="@lang('v.my_car')">@lang('v.my_car')</a></li>
                                <li><a href="{{URL::route('logout_customer')}}" title="@lang('v.exit')">@lang('v.exit')</a></li>
                            </ul>
                        </div>
                        <div class="header-notify"
                             v-if="notifyList.show && getNotificationsCount"
                             :style="{left:notifyList.left}"
                             v-cloak>
                            <div class="header-notify__list">
                                <div class="header-notify__list-header">
                                    <span class="title">@lang('v.notification')</span>
                                    <a class="make-read"
                                       href="#"
                                       title="@lang('v.check_show')"
                                       v-on:click.prevent="notifyMarkReadAll()"
                                       v-if="getNewNotificationsCount > 0"
                                    >@lang('v.check_show')</a>
                                </div>
                                <template>
                                <el-scrollbar :noresize="false"
                                              :native="false"
                                              :view-style="{'max-height':'400px'}">
                                    <div :key="'block'+index"
                                         class="block"
                                         :class="{'block-new': item.show === null}"
                                         v-for="(item, index) in notifyList.data"
                                         v-on:click="goNotify(item)">
                                        <div class="message" v-html="item.name" ></div>
                                    </div>
                                </el-scrollbar>
                                </template>
                            </div>
                            {{--<div :key="'block'+index"
                                 class="block"
                                 v-for="(item,index) in notifyList.data"
                                 v-on:click="goNotify(item)">
                                <div class="message" v-html="item.name"></div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="header-menu_log">
                        <ul>
                            <li><a class="send-order" href="{!! URL::route('order') !!}" title="@lang('v.send_review_btn')">@lang('v.send_review_btn')</a></li>
                        </ul>
                    </div>
                </div>
                @endif
                {{--  #client  --}}
                {{--  #daca logat  --}}
                <div class="header-menu_lang--mobile">
                    <div class="header-menu_lang--close">
                        <img src="{{asset('/img/close.svg')}}" alt="">
                    </div>
                    <ul>
                        <li @if(Lang::getLocale()=='ru') class="current" @endif ><a href="{{URL::to($url_ru)}}" title="">@lang('v.ru')</a></li>
                        <li @if(Lang::getLocale()=='ru') class="current" @endif ><a href="{{URL::to($url_ro)}}" title="">@lang('v.ro')</a></li>
                    </ul>
                </div>
                <div class="header-menu_lang--desc">
                    <ul>
                        <li @if(Lang::getLocale()=='ru') class="active" @endif ><a href="{{URL::to($url_ru)}}" title="">Ru</a></li>
                        <li @if(Lang::getLocale()=='ro') class="active" @endif ><a href="{{URL::to($url_ro)}}" title="">Ro</a></li>
                    </ul>
                </div>
            </div>
{{--        <div class="header-log" v-on:click="modal.cabinet = true">
            --}}{{--<div class="header-log" v-on:click="modal.auth = true">--}}{{--
            --}}{{-- daca logat v-on:click="modal.cabinet = true --}}{{--
            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="30px" height="30px">
                <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                      d="M15.000,-0.000 C23.284,-0.000 30.000,6.716 30.000,15.000 C30.000,23.284 23.284,30.000 15.000,30.000 C6.716,30.000 -0.000,23.284 -0.000,15.000 C-0.000,6.716 6.716,-0.000 15.000,-0.000 Z"/>
                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                      d="M15.000,5.000 C17.761,5.000 20.000,7.239 20.000,10.000 C20.000,12.761 17.761,15.000 15.000,15.000 C12.239,15.000 10.000,12.761 10.000,10.000 C10.000,7.239 12.239,5.000 15.000,5.000 Z"/>
                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                      d="M15.000,16.535 C19.971,16.535 24.000,18.919 24.000,20.768 C24.000,22.366 19.971,26.000 15.000,26.000 C10.029,26.000 6.000,22.116 6.000,20.768 C6.000,19.085 10.029,16.535 15.000,16.535 Z"/>
            </svg>
        </div>--}}
        </div>

{{--<div v-cloak>
    <modal v-if="modal.whoenter" v-on:close="modal.whoenter = false" class="modal-auth" key="modal-auth">
        {{Form::open(['url'=>route("user_login_confirm"),'method'=>'POST'])}}
        <div class="whoenter">
            <div class="explain">Выберите под каким аккаунтом войти</div>
            <div class="block">
                <input type="radio" name="whoenter" value="1" id="whoenter1" hidden>
                <label for="whoenter1">
                    <b>Войти как клиент</b>
                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
                </label>
            </div>
            <div class="block">
                <input type="radio" name="whoenter" value="2" id="whoenter2" hidden>
                <label for="whoenter2">
                    <b>Войти как автосервис</b>
                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
                </label>
            </div>
            <button type="submit" class="btn-red">Продолжить</button>
        </div>

        {{Form::close()}}
    </modal>

    <modal v-if="modal.cabinet" v-on:close="modal.cabinet = false" class="modal-auth" key="modal-auth">
        <div class="modal-logo"><a href="/"><img src="{{asset('/img/logo.svg')}}" alt=""></a></div>
        <h2 class="title">Личный кабинет</h2>
        <ul class="personal">
            <li><a href=""><img src="" alt=""><span>Профиль</span></a></li>
            <li><a href=""><img src="" alt=""><span>Заявки на ремонт</span></a></li>
            <li><a href=""><img src="" alt=""><span>Прайс-лист</span></a></li>
            <li><a href=""><img src="" alt=""><span>Финансовая история</span></a></li>
            <li><a href="">Выход</a></li>
        </ul>
    </modal>

    <modal v-if="modal.auth" v-on:close="modal.auth = false" class="modal-auth" key="modal-auth">
        <div class="modal-logo"><a href="/"><img src="{{asset('/img/logo.svg')}}" alt=""></a></div>
        <h2 class="title">Вход</h2>
        <div class="block-enter">
            <form action="" v-on:submit.prevent="enterValidate($event)" novalidate>
                <div class="input-wr">
                    <label for="" class="label">Электронная почта или номер телефона</label>
                    <input type="text"
                           v-validate="'required|min:6|max:125'"
                           name="enter_email"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('enter_email') }"
                           v-on:focus="removeError('enter_email')"
                           class="input"
                           tabindex="1">
                            <span :class="{ error: errors.has('enter_email') }"
                                  v-if="errors.has('enter_email')"
                            >@lang('v.error_email')</span>
                </div>
                <div class="input-wr">
                    <label for="" class="label">Пароль</label>
                    <input type="password"
                           class="input"
                           v-validate="'required|min:4|max:12'"
                           name="enter_pas"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('enter_pas') }"
                           v-on:focus="removeError('enter_pas')"
                           tabindex="2">
                            <span :class="{ error: errors.has('enter_pas') }"
                                  v-if="errors.has('enter_pas')"
                            >@lang('v.enter_pas')</span>
                </div>
                <div class="submit">
                    <button tabindex="3">Войти</button>
                    <span tabindex="4">Забыли пароль?</span>
                </div>
            </form>
        </div>
        <div class="block-social">
            <p>Войти используя соц. сети</p>
            <div class="block-social_icons">
                <img src="{{asset('/img/fb.png')}}" alt="" tabindex="5">
                <img src="{{asset('/img/vk.png')}}" alt="" tabindex="6">
                <img src="{{asset('/img/g.png')}}" alt="" tabindex="7">
            </div>
            <p>Нет данных?</p>
            <button tabindex="8" v-on:click="modal.auth = false;modal.reg = true">Регистрация</button>
        </div>
    </modal>

    <modal v-if="modal.reg"  v-on:close="modal.reg = false" class="modal-reg"  key="modal-reg">
        <div class="modal-logo"><a href="/"><img src="{{asset('/img/logo.svg')}}" alt=""></a></div>
        <h2 class="mob-title">Регистрация</h2>
        <div class="mob-choose">
            <div class="mob-choose_block" v-on:click="modal.choose = true" :class="{active: modal.choose === true}">
                <div class="title">Ищите автосервис?</div>
                <div class="text">Зарегистрируйтесь как клиент</div>
            </div>
            <div class="mob-choose_block" v-on:click="modal.choose = false" :class="{active: modal.choose === false}">
                <div class="title">Вы владелец автосервиса?</div>
                <div class="text">Добавьте ваш автосервис на наш сайт</div>
            </div>
        </div>
        <div class="block-person" v-if="width > 1279 || modal.choose" key="block-person">
            <h2 class="reg-title">Ищите автосервис?</h2>
            <p class="reg-undertitle">Зарегистрируйтесь как клиент</p>
            <form  novalidate v-on:submit.prevent="reguser($event,'reguser')" data-vv-scope="reguser" name="reguser">
                <div class="input-wr">
                    <label for="" class="label req">Электронная почта</label>
                    <input type="text"
                           v-validate="'required|email'"
                           name="reg_email"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('reguser.reg_email') }"
                           v-on:focus="removeError('reg_email','reguser')"
                           class="input"
                           tabindex="1">
                            <span :class="{ error: errors.has('reguser.reg_email') }"
                                  v-if="errors.has('reguser.reg_email')"
                            >@lang('v.reg_email')</span>
                </div>
                <div class="input-wr">
                    <label for="" class="label req">Имя</label>
                    <input type="text"
                           class="input"
                           v-validate="'required|min:4|max:50'"
                           name="reg_name"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('reguser.reg_name') }"
                           v-on:focus="removeError('reg_name','reguser')"
                           tabindex="2">
                            <span :class="{ error: errors.has('reguser.reg_name') }"
                                  v-if="errors.has('reguser.reg_name')"
                            >@lang('v.reg_name')</span>
                </div>
                <div class="input-wr">
                    <label for="" class="label req">Пароль</label>
                    <input type="password"
                           class="input"
                           v-validate="'required|min:6|max:50'"
                           name="reg_pas"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('reguser.reg_pas') }"
                           v-on:focus="removeError('reg_pas','reguser')"
                           tabindex="3">
                            <span :class="{ error: errors.has('reguser.reg_pas') }"
                                  v-if="errors.has('reguser.reg_pas')"
                            >@lang('v.reg_pas')</span>
                </div>
                <div class="submit">
                    <button tabindex="4">Зарегистрироваться</button>
                    <p>или</p>
                    <div class="submit-social">
                        <img src="{{asset('/img/fb.png')}}" alt="" tabindex="5">
                        <img src="{{asset('/img/vk.png')}}" alt="" tabindex="6">
                        <img src="{{asset('/img/g.png')}}" alt="" tabindex="7">
                    </div>
                </div>
                <div class="submit-manifest">
                    <p>Нажимая кнопку зарегистрироваться вы соглашаетесь с <a href="">Пользовательским соглашением</a> и даете
                        <a href="">Согласие на обработку персональных данных</a>.</p>
                </div>
            </form>
        </div>
        <div class="block-service" v-if="width > 1279 || !modal.choose" key="block-service">
            <h2 class="reg-title">Вы владелец автосервиса?</h2>
            <p class="reg-undertitle">Добавьте ваш автосервис на наш сайт</p>
            <form action=""
                  @submit.prevent="regService($event,'regService')"
                  novalidate data-vv-scope="regService"
                  name="regService">
                <div class="input-wr">
                    <label for="" class="label req">Электронная почта</label>
                    <input type="text"
                           class="input"
                           v-validate="'required|email'"
                           name="serv_email"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('regService.serv_email') }"
                           v-on:focus="removeError('serv_email','regService')"
                           tabindex="101">
                            <span :class="{ error: errors.has('regService.serv_email') }"
                                  v-if="errors.has('regService.serv_email')"
                            >@lang('v.serv_email')</span>
                </div>
                <div class="input-wr">
                    <label for="" class="label req">Название автосервиса</label>
                    <input type="text"
                           class="input"
                           v-validate="'required|min:3|max:50'"
                           name="serv_name"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('regService.serv_name') }"
                           v-on:focus="removeError('serv_name','regService')"
                           tabindex="102">
                            <span :class="{ error: errors.has('regService.serv_name') }"
                                  v-if="errors.has('regService.serv_name')"
                            >@lang('v.serv_email')</span>
                </div>
                <div class="input-wr">
                    <label for="" class="label req">Пароль</label>
                    <input type="password"
                           class="input"
                           v-validate="'required|min:3|max:50'"
                           name="serv_pas"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('regService.serv_pas') }"
                           v-on:focus="removeError('serv_pas','regService')"
                           tabindex="103">
                            <span :class="{ error: errors.has('regService.serv_pas') }"
                                  v-if="errors.has('regService.serv_pas')"
                            >@lang('v.serv_email')</span>
                </div>
                <div class="input-wr tel">
                    <label for="" class="label req">Номер телефона</label>
                    <span class="prefix">+373</span>
                    <input type="text"
                           class="input"
                           v-validate="'required|min:5|max:12|numeric'"
                           name="serv_tel"
                           data-vv-validate-on="none"
                           :class="{ error: errors.has('regService.serv_tel') }"
                           v-on:focus="removeError('serv_tel','regService')"
                           tabindex="105">
                            <span :class="{ error: errors.has('regService.serv_tel') }"
                                  v-if="errors.has('regService.serv_tel')"
                            >@lang('v.serv_tel')</span>
                </div>
                <get-address ref="mainChooseAddress"
                             v-on:locationchanged="locationchanged"
                             lang="{{Lang::getLocale()}}"></get-address>
                        <span class="error adres"
                              v-if="errors.has('regService.selected_municipiu')">Выберите муниципий</span>
                        <span class="error adres"
                              v-if="errors.has('regService.selected_city')">Выберите город</span>
                <input type="text"
                       hidden
                       v-validate="'required'"
                       name="selected_city"
                       v-model="selectedAddress.city">
                <input type="text"
                       hidden
                       v-validate="'required'"
                       name="selected_municipiu"
                       v-model="selectedAddress.municipiu">
                <input type="text"
                       hidden
                       name="selected_district"
                       v-model="selectedAddress.district">
                <div class="showOnMap"
                     :class="{ error: errors.has('regService.mp_coord') }">
                    <img v-on:click="getCoord = !getCoord" src="{{asset('/img/point_vue_map.svg')}}" alt="" id="vueMap"  tabindex="107">
                    <span  v-on:click="getCoord = !getCoord">Отметить местонахождение на карте</span>
                    <div class="vueMapInput-wrapper" v-if="getCoord">
                        <img src="{{asset('/img/close.svg')}}" alt="" class="vueMapInput-img" v-on:click="getCoord = false"  tabindex="108">
                        <button class="vueMapInput-button" v-on:click.prevent="addCoord"  tabindex="109">Продолжить</button>
                        <div class="vueMapInput" id="mapAdd" ></div>
                    </div>
                    <input type="text"
                           hidden
                           v-validate="'required'"
                           name="mp_coord"
                           v-model="mapsCoord">
                </div>
                <div class="input-wr check-wr">
                    <input type="checkbox" class="check" name="official_dealer" id="official_dealer"  tabindex="110">
                    <label for="official_dealer">Официальный дилер</label>official_dealer
                </div>
                <div class="submit">
                    <button tabindex="111">Зарегистрироваться</button>
                </div>
            </form>
        </div>
    </modal>
</div>--}}
    </header>
<main @if(URL::route('home') !== Request::url()) class="not-main" @endif>
    @yield('content')
</main>
</div>

@if(URL::route('map') != url()->current())
<footer class="footer">
    <div class="footer-grid">
        <section class="footer-info">
            <div class="footer-logo">
                @if(Lang::getLocale() == 'ru')
                    <a href="/{{Lang::getLocale()}}" title=""><img src="{{asset('/img/logo.svg')}}" alt="" /></a>
                @else
                    <a href="/" title=""><img src="{{asset('/img/logo.svg')}}" alt="" /></a>
                @endif
            </div>
            <div class="footer-copyright">
                {{--<p>@lang('v.project_right')</p>--}}
                <p>&copy; 2018 — AUTO24</p>
            </div>
            <div class="footer-tel">
                <a href="tel:+37322456789" title="">+ (373) 22 456-789</a>
            </div>
            <div class="footer-grafic">
                <p>Пн.- Пт. с 9:00 до 18:00</p>
            </div>
            <div class="footer-social">
                <a href=""><img src="{{asset('/img/footer-fb.png')}}" alt="" /></a>
                <a href=""><img src="{{asset('/img/footer-vk.png')}}" alt="" /></a>
                <a href=""><img src="{{asset('/img/footer-inst.png')}}" alt="" /></a>
                <a href=""><img src="{{asset('/img/footer-yt.png')}}" alt="" /></a>
            </div>
        </section>
        <section class="footer-menu footer-menuA">
            <h2 class="footer-title">AUTO24</h2>
            <ul>
                <li><a href="">@lang('v.search_map_btn')</a></li>
                <li><a href="">@lang('v.request_to_autoservice')</a></li>
                <li><a href="">@lang('v.for_autoservice')</a></li>
                {{--<li><a href="">@lang('v.other')</a></li>--}}
            </ul>
        </section>
        <section class="footer-menu footer-menuB">
            <h2 class="footer-title">@lang('v.information')</h2>
            <ul>
                <li><a href="">@lang('v.about_project')</a></li>
                <li><a href="">@lang('v.dogovor')</a></li>
                <li><a href="">@lang('v.confidential')</a></li>
            </ul>
        </section>
        <section class="footer-menu footer-menuC">
            <h2 class="footer-title">@lang('v.about_project')</h2>
            <ul>
                <li><a href="">@lang('v.how_it_work')</a></li>
                <li><a href="">@lang('v.question_answer')</a></li>
                <li><a href="">@lang('v.vacancies')</a></li>
                <li><a href="">@lang('v.contacts')</a></li>
            </ul>
        </section>
        <section class="footer-bottom">
            <ul>
                <li><a href="">@lang('v.about_project')</a></li>
                <li><a href="">@lang('v.user_right')</a></li>
                <li><a href="">@lang('v.reclama')</a></li>
                <li><a href="">@lang('v.vacancies')</a></li>
                <li><a href="">@lang('v.contacts')</a></li>
            </ul>
        </section>
    </div>
</footer>
@endif
<!-- ========= scripts ========= -->
<script>
    var SMS_CODE_TIME = 60;
    <?php
        $controller = new \App\Http\Controllers\ViewController();
    ?>
    let difUser = @if(Auth::guard('customer')->user()) 'client' @elseif(Auth::guard('dealer')->user()) 'service' @else 'guest' @endif;

    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
        'lang' => Lang::getLocale(), // ru, ro Auth::guard('customer') -> user()
        'log' => Auth::guard('customer') -> user() ? true : false,
        'notifications' => [],
        'user' => '',
        'wallet' => Auth::guard('customer') -> user() ? Auth::guard('customer')->user()->amount : (Auth::guard('dealer')->user() ? Auth::guard('dealer')->user()->amount : 0),
    ]); ?>

    @if(Auth::guard('customer')->user() || Auth::guard('dealer')->user())
        @if(count( $controller->getNotification()))
            let notifications = {!! json_encode($controller->getNotification()) !!};
        @endif

        @if(count( $controller->getNotification()))
            window.Laravel.notifications = {!! json_encode($controller->getNotification()) !!};
        @endif
    @endif

    @if(Auth::guard('customer')->user())
        window.Laravel.user = <?php echo json_encode(['name' => Auth::guard('customer')->user()->name]); ?>
    @endif

    @if(Auth::guard('dealer')->user())
        window.Laravel.user = <?php echo json_encode(['name' => Auth::guard('dealer')->user()->name]); ?>
    @endif

</script>
<script src="{{asset('js/io.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVQH7RWzUanIGdKio0eIRKf42cXY0o0Xg&libraries=places"></script>
<script src="https://cdn.sobekrepository.org/includes/gmaps-markerwithlabel/1.9.1/gmaps-markerwithlabel-1.9.1.min.js"></script>
<script src="{{asset('js/app.js')}}"></script>
<script>
    let socketURL = 'http://{{env('ClIENT_HOST')}}:{{env('CLIENT_PORT')}}';

    @if(Auth::guard('customer')->user())
        let sc_socket = '{{md5(env('CLIENT_KEY').Auth::guard('customer')->user()->id.'customer')}}';
        USER_NAME = <?php echo json_encode(['name' => Auth::guard('customer')->user()->name]); ?>
    @endif

    @if(Auth::guard('dealer')->user())
        let sc_socket = '{{md5(env('CLIENT_KEY').Auth::guard('dealer')->user()->id.'dealer')}}';
        USER_NAME = <?php echo json_encode(['name' => Auth::guard('dealer')->user()->name]); ?>
    @endif

    @if(Auth::guard('customer')->check() || Auth::guard('dealer')->check())
        let socket;
        socket = io(socketURL, { secure: true, reconnection:false, origins:'*:*' });
        socket.on('connect', function () {
            log('connect', 'connected');
        }).on(sc_socket, function (event) {
            bus.socketData = event;
        });
        function log(eventName, message) {
            console.log(eventName + '-' + message);
        }
    @endif
</script>
@yield('script')
</body>
</html>