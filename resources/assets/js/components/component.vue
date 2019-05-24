<template>

</template>
<script>
    function wordend(num, words){
        return words[ ((num=Math.abs(num%100)) > 10 && num < 15 || (num%=10) > 4 || num === 0) + (num !== 1) ];
    }
    function isANumber( n ) {
        var numStr = /^-?(\d+\.?\d*)$|(\d*\.?\d+)$/;
        return numStr.test( n.toString() );
    }
    export default {

    }
</script>