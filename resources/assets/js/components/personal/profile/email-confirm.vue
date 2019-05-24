<template>
    <div>
        <div class="control-wrapper"
             :class="{'error': !isConfirmed}">
             <input type="text"
                    class="input"
                    v-model="email"
                    :disabled="isSending"
                    readonly />
             <div class="email-status">
                 <i class="icon-check"></i>
             </div>
        </div>
        <div class="links" v-if="!isConfirmed">
            <a href="#"
               v-on:click.prevent="confirmEmail"
               v-if="counter === 1 && !hasErrors"
               :disabled="isSending"
               :title="$t('message.confirm_email')">{{ $t('message.confirm_email') }}</a>
            <a href="#"
               v-on:click.prevent="confirmEmail"
               v-if="counter > 1 && !hasErrors"
               :disabled="isSending"
               :title="$t('message.resend')">{{ $t('message.resend') }}</a>
            <span v-if="hasErrors">{{ $t('message.request_limit_has_been_exceeded') }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {},
        data(){
            return {
                email: window.info.email,
                isConfirmed: window.info.confirm_email,
                hasErrors: false,
                isSending: false,
                counter: 1
            }
        },
        components: {},
        methods: {
            confirmEmail() {
                if(!this.isSending) {
                    this.isSending = true;
                    let data = {
                        email: ''
                    };

                    let that = this;
                    this.$http.post('email-repeat-confirm', data, {headers: {'X-CSRF-TOKEN': bus.info.key}}).then(response => {
                        if (response.data.success === 'sended') {
                            this.counter++;
                        }
                        if (response.data.success === 'confirmed') {
                            this.isConfirmed = true;
                        }

                        if (response.data.errors === true) {
                            this.hasErrors = true;
                        }

                        that.isSending = false;
                    }, response => {
                        this.isSending = false;
                        alert('No connection with server');
                    });
                }
            }
        }
    }
</script>

<style lang="less">
    .input-wr {
        &.email {
            .control-wrapper {
                position: relative;
                .input {
                    padding-right: 48px;
                    &:focus {
                        border-color: #d8d8d8;
                    }
                    &[disabled] {
                        background-color: #f8f8f8;
                    }
                }
                &.error {
                    .input {
                        color:#b3b3b3;
                        border-color: #e30613;
                        &:focus {
                            border-color: #e30613;
                        }
                    }
                    .email-status {
                        background-color: #afafaf;
                    }
                }
                &.confirmed {

                }
            }
            .links {
                padding: 0 0 0 16px;
                margin: 9px 0 0 0;
                span {
                    color:#e30613;
                }
                a {
                    color: #0363cd;
                    &:hover {
                        opacity: 0.8;
                    }
                }
            }
            .email-status {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background-color: #28bb00;
                color: #fff;
                display:flex;
                flex-flow: row nowrap;
                justify-content: center;
                align-items: center;
                i {
                    font-size: 11px;
                }
            }
        }
    }
</style>