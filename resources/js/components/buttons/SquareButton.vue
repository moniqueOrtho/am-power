<template>
	<div class="square-button" @click="$emit('click', $event)" v-bind="$attrs" :style="cssVars">
		<slot />
	</div>
</template>

<script>
import ColorsMixin from "../../mixins/colors";
export default {
    name: 'square-button',
    mixins: [ColorsMixin],
    computed: {
        cssVars() {
            return {
                '--color': this.convertHex(this.$vuetify.theme.themes.light.secondary, 40),
            }
        }
    }
}
</script>

<style lang="scss" scoped>
.square-button {
	background: var(--color);
	display: flex;
	align-items: center;
	justify-content: center;
	height: 42px;
	width: 42px;
	margin-bottom: 10px;
	cursor: pointer;
	transition: background 0.5s;
	&:hover {
		background: var(--v-secondary-base);
	}
}
</style>
