@extends('front/layout/layout')
@section('meta')
@stop

@section('content')
    <div id="page-login" xmlns:v-on="http://www.w3.org/1999/xhtml">
    <!--<modal v-if="modal.reg"  v-on:close="modal.reg = false" class="modal-reg"  key="modal-reg">-->
        <div class="modal-mask modal-reg">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-body">
                        {{--
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
                        </div>--}}
                        {{--<transition v-on:enter="chooseSlideIn" v-on:leave="chooseSlideOut" :css="false" appear>--}}
                        <div class="block-person" v-if="modal.choose" key="block-person" v-cloak>
                            <h2 class="reg-title">@lang('v.reg')<a class="reg-title-link" href="{{URL::route('enter')}}" title="@lang('v.sign2')">@lang('v.sign2')</a></h2>
                            {{--<p class="reg-undertitle">Зарегистрируйтесь как клиент</p>--}}
                            <form action=""
                                  v-on:submit.prevent="reguser($event,'reguser')"
                                  data-vv-scope="reguser"
                                  name="reguser"
                                  autocomplete="off"
                                  novalidate>
                                <div class="input-wr">
                                    <label for="" class="label req">@lang('v.email')</label>
                                    <input type="text"
                                           v-validate="'required|email'"
                                           name="reg_email"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('reguser.reg_email') }"
                                           v-on:focus="removeError('reg_email','reguser')"
                                           class="input"
                                           tabindex="1" />
                                    <span :class="{ error: errors.has('reguser.reg_email') }"
                                          v-if="errors.has('reguser.reg_email')"
                                          v-cloak>@lang('v.reg_email')</span>
                                    <span class="error"
                                          v-if="hasServerErrors('regUser', 'email')"
                                          v-cloak
                                          v-text="getServerErrors('regUser', 'email')"></span>
                                </div>
                                <div class="input-wr">
                                    <label for="" class="label req">@lang('v.name')</label>
                                    <input type="text"
                                           class="input"
                                           v-validate="'required|min:4|max:50'"
                                           name="reg_name"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('reguser.reg_name') }"
                                           v-on:focus="removeError('reg_name','reguser')"
                                           tabindex="2" />
                                    <span :class="{ error: errors.has('reguser.reg_name') }"
                                          v-if="errors.has('reguser.reg_name')"
                                          v-cloak>@lang('v.reg_name')</span>
                                </div>
                                <div class="input-wr">
                                    <label for="" class="label req">@lang('v.password')</label>
                                    <input type="password"
                                           class="input"
                                           v-validate="'required|min:6|max:50'"
                                           name="reg_pas"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('reguser.reg_pas') }"
                                           v-on:focus="removeError('reg_pas','reguser')"
                                           tabindex="3" />
                                    <span :class="{ error: errors.has('reguser.reg_pas') }"
                                          v-if="errors.has('reguser.reg_pas')"
                                          v-cloak>@lang('v.reg_pas')</span>
                                </div>
                                <div class="submit">
                                    {{--<button tabindex="4">@lang('v.registration')</button>--}}
                                    <el-button type="primary"
                                               native-type="submit"
                                               tabindex="4"
                                               :loading="isLoading.registerClient"
                                               :disabled="isLoading.registerClient">@lang('v.registration')</el-button>
                                </div>
                                <div class="submit-manifest">
                                    <p>@lang('v.check_btn')<a href="#" title="">@lang('v.license_user')</a>@lang('v.and_give') <a href="#" title="">@lang('v.unread_message_conf')</a></p>
                                </div>
                                <div class="submit">
                                    <p>@lang('v.register_social')</p>
                                    <div class="submit-social">
                                        <img src="{{asset('/img/fb.png')}}" alt="" tabindex="5">
                                        <img src="{{asset('/img/vk.png')}}" alt="" tabindex="6">
                                        <img src="{{asset('/img/g.png')}}" alt="" tabindex="7">
                                        <img src="{{asset('/img/ya.png')}}" alt="" tabindex="8">
                                        <img src="{{asset('/img/mru.png')}}" alt="" tabindex="9">
                                    </div>
                                </div>
                                <div class="toggle-registration">
                                    <a href="#" v-on:click.prevent="modal.choose = !modal.choose" title="@lang('v.register_autoservice')">@lang('v.register_autoservice')</a>
                                </div>
                            </form>
                        </div>
                        {{--</transition>--}}

                        {{--<transition v-on:enter="chooseSlideIn2" v-on:leave="chooseSlideOut2" :css="false" appear>--}}
                        <div class="block-service" v-if="!modal.choose" key="block-service" v-cloak>
                            <h2 class="reg-title">@lang('v.owner_autoservice')</h2>
                            <p class="reg-undertitle">@lang('v.add_autoservice')</p>
                            <form action=""
                                  @submit.prevent="regService($event,'regService')"
                                  novalidate
                                  autocomplete="off"
                                  data-vv-scope="regService"
                                  name="regService">
                                <div class="input-wr">
                                    <label for="" class="label req">@lang('v.email')</label>
                                    <input type="text"
                                           class="input"
                                           v-validate="'required|email'"
                                           name="serv_email"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('regService.serv_email') }"
                                           v-on:focus="removeError('serv_email','regService')"
                                           tabindex="101" />
                                    <span :class="{ error: errors.has('regService.serv_email') }"
                                          v-if="errors.has('regService.serv_email')"
                                          v-cloak>@lang('v.reg_email')</span>
                                    <span class="error"
                                          v-if="hasServerErrors('regService', 'email')"
                                          v-cloak
                                          v-text="getServerErrors('regService', 'email')"></span>
                                </div>
                                <div class="input-wr">
                                    <label for="" class="label req">@lang('v.name_autoservice')</label>
                                    <input type="text"
                                           class="input"
                                           v-validate="'required|min:3|max:50'"
                                           name="serv_name"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('regService.serv_name') }"
                                           v-on:focus="removeError('serv_name','regService')"
                                           tabindex="102" />
                                    <span :class="{ error: errors.has('regService.serv_name') }"
                                          v-if="errors.has('regService.serv_name')"
                                          v-cloak>@lang('v.reg_name_autoservice')</span>
                                </div>
                                <div class="input-wr">
                                    <label for="" class="label req">@lang('v.password')</label>
                                    <input type="password"
                                           class="input"
                                           v-validate="'required|min:3|max:50'"
                                           name="serv_pas"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('regService.serv_pas') }"
                                           v-on:focus="removeError('serv_pas','regService')"
                                           tabindex="103" />
                                    <span :class="{ error: errors.has('regService.serv_pas') }"
                                          v-if="errors.has('regService.serv_pas')"
                                          v-cloak>@lang('v.reg_pas')</span>
                                </div>
                                <div class="input-wr tel">
                                    <label for="" class="label req">@lang('v.phone')</label>
                                    <span class="prefix">+373</span>
                                    <input type="text"
                                           class="input"
                                           v-validate="'required|min:8|max:9|numeric'"
                                           maxlength="9"
                                           minlength="8"
                                           name="serv_tel"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('regService.serv_tel') }"
                                           v-on:focus="removeError('serv_tel','regService')"
                                           tabindex="105" />
                                    <span :class="{ error: errors.has('regService.serv_tel') }"
                                          v-if="errors.has('regService.serv_tel')"
                                          v-cloak>@lang('v.reg_phone')</span>
                                    <span class="error"
                                          v-if="hasServerErrors('regService', 'phone')"
                                          v-cloak
                                          v-text="getServerErrors('regService', 'phone')"></span>
                                </div>
                                <div class="input-wr check-wr">
                                    <input type="checkbox" class="check" name="official_dealer" id="official_dealer"  tabindex="110">
                                    <label for="official_dealer">@lang('v.official_dealer')</label>
                                </div>
                                <div class="submit">
                                    {{--<button tabindex="111">@lang('v.registration')</button>--}}
                                    <el-button type="primary"
                                               native-type="submit"
                                               tabindex="111"
                                               :loading="isLoading.registerAutoservice"
                                               :disabled="isLoading.registerAutoservice">@lang('v.registration')</el-button>
                                </div>
                                <div class="toggle-registration">
                                    <a href="#" v-on:click.prevent="modal.choose = !modal.choose" title="@lang('v.simple_client')">@lang('v.simple_client')</a>
                                </div>
                            </form>
                        </div>
                        {{--</transition>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--</modal>-->
@stop

@section('scripts')
@stop