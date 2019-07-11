<template>
  <base-button 
    :action="switchShownState"
    :class-name="'inputShowPasswordButton font-size_22' + className"
    :label="!isShown ? 'Показать пароль' : 'Скрыть пароль'"
    unstyled
  >
    <eye-icon />
  </base-button>
</template>

<script>
import EyeIcon from "@/components/icons/EyeIcon";

export default {
  name: "ShowPasswordButton",
  components: {
    EyeIcon
  },
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    }
  },

  data: () => ({
    isShown: false
  }),

  methods: {
    switchShownState() {
      const isShownRevert = !this.isShown;
      this.isShown = isShownRevert;

      this.$emit("change-shown-state", isShownRevert);
    }
  }
};
</script>

<style lang="sass" scoped>
.inputShowPasswordButton
	position: absolute
	bottom: 1.5rem
	right: .5rem
</style>
