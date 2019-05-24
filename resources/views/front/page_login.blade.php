@extends('front/layout/layout')
@section('meta')
@stop
@section('content')
    <div id="page-login">
        <!--<modal class="modal-auth" key="modal-auth">-->
        <div class="modal-mask modal-auth">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-body">
                        <h2 class="title">@lang('v.sign2')<a class="title-link" href="{{URL::route('registration')}}" title="@lang('v.reg')">@lang('v.reg')</a></h2>
                        <div class="block-enter">
                            <form action="" v-on:submit.prevent="enterValidate($event)" novalidate autocomplete="off">
                                <div class="input-wr">
                                    <label for="" class="label">@lang('v.email_phone')</label>
                                    <input type="text"
                                           v-validate="'required|min:6|max:125'"
                                           name="enter_email"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('enter_email') }"
                                           v-on:focus="removeError('enter_email')"
                                           class="input"
                                           autocomplete="off"
                                           tabindex="1" />
                                <span :class="{ error: errors.has('enter_email') }"
                                      v-if="errors.has('enter_email')"
                                      v-cloak
                                >@lang('v.error_email')</span>
                                </div>
                                <div class="input-wr">
                                    <label for="" class="label">@lang('v.password')</label>
                                    <input type="password"
                                           class="input"
                                           v-validate="'required|min:4|max:12'"
                                           name="enter_pas"
                                           data-vv-validate-on="none"
                                           :class="{ error: errors.has('enter_pas') }"
                                           v-on:focus="removeError('enter_pas')"
                                           autocomplete="off"
                                           tabindex="2" />
                                <span :class="{ error: errors.has('enter_pas') }"
                                      v-if="errors.has('enter_pas')"
                                      v-cloak
                                >@lang('v.enter_pas')</span>
                                <span class="error"
                                      v-if="hasServerErrors('login', 'notLoggedUser')"
                                      v-cloak
                                      v-text="getServerErrors('login', 'notLoggedUser')"></span>
                                </div>
                                <div class="submit">
                                    {{--<button tabindex="3">@lang('v.sign')</button>--}}
                                    <el-button type="primary"
                                               native-type="submit"
                                               :loading="isLoading.login"
                                               :disabled="isLoading.login">@lang('v.sign')</el-button>
                                    <span class="reset-password" tabindex="4"><a href="{{URL::route('reset_password')}}">@lang('v.forget_pass')</a></span>
                                </div>
                            </form>
                        </div>
                        <div class="block-social">
                            <p>@lang('v.login_social')</p>
                            <div class="block-social_icons">
                                <a href="{{URL::route('socials_login','facebook')}}"> <img src="{{asset('/img/fb.png')}}" alt="" tabindex="5" /></a>
                                <a href="{{URL::route('socials_login','vkontakte')}}"> <img src="{{asset('/img/vk.png')}}" alt="" tabindex="6" /></a>
                                <a href="{{URL::route('socials_login','google')}}"> <img src="{{asset('/img/g.png')}}" alt="" tabindex="7" /></a>
                                <a href="{{URL::route('socials_login','yandex')}}"> <img src="{{asset('/img/ya.png')}}" alt="" tabindex="8" /></a>
                                <a href="{{URL::route('socials_login','mailru')}}"> <img src="{{asset('/img/mru.png')}}" alt="" tabindex="9" /></a>
                            </div>
                            <p>@lang('v.undefined')</p>
                            <a class="type-button" href="{{URL::route('registration')}}" tabindex="10">@lang('v.reg')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--</modal>-->
    </div>
@stop
@section('script')

@stop