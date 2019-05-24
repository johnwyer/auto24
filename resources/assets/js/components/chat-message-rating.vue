<template>
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
                <b v-text="orderPageInfo.autoservice_rating.total > 0 ? orderPageInfo.autoservice_rating.total : 'нет'"></b>
                <span v-text="returnText(orderPageInfo.autoservice_rating.total, ['отзыв','отзыва','отзывов'])"></span>
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
                <b v-text="orderPageInfo.autoservice_rating.total > 0 ? orderPageInfo.autoservice_rating.total : 'нет'"></b>
                <span v-text="returnText(orderPageInfo.autoservice_rating.total, ['отзыв','отзыва','отзывов'])"></span>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        props: ['orderPageInfo'],
        computed: {}
    }
</script>