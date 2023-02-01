<template>
    <div class="curl-btn__container">
        <button class="curl-btn" :class="getButtonClass" @click="btnClicked">
            <v-icon :color="color" class="curl-btn__icon">{{ icon }}</v-icon>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        obj: {
            type: [Object, String],
            required: true
        },
        icon: {
            type: String,
            default: 'mdi-delete'
        },
        small: {
            type: Boolean,
            default: true
        },
        color: {
            type: String,
            default: 'error'
        }
    },
    emits: ['curl-action'],
    methods: {
        btnClicked() {
           this.$emit('curl-action', this.obj);
        }
    },
    computed: {
        getButtonClass() {
            if(this.small) return 'curl-btn--small'
        }
    }

}
</script>

<style lang="scss" scoped>
    .curl-btn {
        &__container {
            position: absolute;
            right: 0;
            top: 0;
            z-index: 10;
        }
        position: absolute;
        right: 0;
        top: 0;
        display: flex;
        align-items: flex-end;
        justify-content: flex-start;
        border: none;
        text-decoration: none;
        background :
		linear-gradient(
			45deg,
			#fff,
			#f3f3f3 45%,
			#ddd 50%,
			#aaa 50%,
			#bbb 56%,
			#ccc 62%,
			#f3f3f3 80%,
			#fff 100%
		);
        box-shadow: 0 10px 20px -5px rgb(115 115 115 / 75%), -10px 0 20px -5px rgb(115 115 115 / 75%);
	    transition: all .5s ease;

        &--small {
            height: 2.4rem;
            width: 2.4rem;
        }

        &::before,
        &::after {
            content: '';
            position: absolute;
            z-index: -1;
            left: 12.5%;
            bottom: 5.8%;
            width: 70%;
            max-width: 300px;
            max-height: 100px;
            height: 55%;
            // box-shadow: 0 12px 15px rgba(0, 0, 0, .3);
            transform: skew(-10deg) rotate(-6deg);
        }

        &::after {
            left: auto;
            right: 5.8%;
            bottom: auto;
            top: 14.16%;
            transform: skew(-15deg) rotate(-84deg);
        }

        &--small:hover {
            width: 3rem;
            height: 3rem;
        }

        &--small:active {
            width: 3.5rem;
            height: 3.5rem;
        }


        &:hover:before,
        &:hover:after,
        &:active:before,
        &:active:after {
            box-shadow: 0 24px 30px rgba(0, 0, 0, .3);
        }


    }


</style>
