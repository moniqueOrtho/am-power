<template>
    <div class="ribbon" :style="setVarWidth" ref="ribbon">
        <slot>	&nbsp; </slot>
    </div>
</template>

<script>
export default {
    name: 'ribbon',
    props: {
        color: {
            type: String,
            required: true
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.width = this.$refs.ribbon.clientWidth;
        });
    },
    data() {
        return {
            width: null
        }
    },
    computed: {
        setVarWidth() {
            return {
                '--ribbon-width' : `${this.width/4}px`,
                '--bottom' : `-${Math.floor(this.width/4)}px`,
                '--color' : this.color
            }
        }
    }

}
</script>

<style lang="scss" scoped>
    .ribbon {
        position: relative;
        display: flex;
        align-items: center;
        height: 30%;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%), inset 2px 8px 8px -6px white;
        background-color: var(--color);

        &::before, &::after {
                content: "";
                position: absolute;
                height: 0;
                width: 0;
                bottom: var(--bottom);
                border-top: var(--ribbon-width) solid var(--color);
                border-left: var(--ribbon-width) solid transparent;
                border-right: var(--ribbon-width) solid transparent;
            }

            &::before {
                left: 0;
            }

            &::after {
                left: 50%;
            }
    }

</style>
