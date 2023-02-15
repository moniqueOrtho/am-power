<template>
	<div class="vertical-buttons">
		<slot />
	</div>
</template>

<style lang="scss" scoped>
.vertical-buttons {
	position: absolute;
	left: 10px;
	top: 50%;
	transform: translateY(-50%);
    z-index: 200;
}
</style>
