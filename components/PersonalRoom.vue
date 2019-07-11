<template>
	<modal-container
		:on-close-modal="closePersonalRoom"
	>

	</modal-container>
</template>

<script>
import ModalContainer from '@/components/ModalContainer';

export default {
	name: "PersonalRoom",
	props: {
		className: {
			type: String,
			required: false,
			default: ""
		},
		modifier: {
			type: String,
			required: false,
			default: ""
		},
	},
	    components: {
	    	ModalContainer
    },
    mixins: [],
    data: () => ({
    }),
    beforeCreate() {
    },
    created() {
    },
    beforeMount () {
    },
    mounted() {
    },
    computed: {
    },
    methods: {
    	closePersonalRoom() {
    		this.$store.commit('personalRoom/switchPersonalRoomDisplayState');
    	}
    },
    beforeUpdate() {
    },
    updated() {
    },
    beforeDestroy() {
    }
};
</script>

<style lang="sass" scoped>
	
</style>