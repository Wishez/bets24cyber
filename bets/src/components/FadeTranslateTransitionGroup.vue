<template>
  <transition-group 
    :class="{
      [className]: className
    }"
    :tag="tag"
    :css="false"
    name="fadeTranslateToBottom"
    @before-enter="beforeEnter"
    @enter="enter"
    @leave="leave"
    @before-appear="beforeEnter"
    @appear="enter"
  >
    <slot/>
  </transition-group>
</template>

<script>
import { timeout } from "@/constants/pureFunctions";
import anime from "animejs";

export default {
  name: "FadeTranslateTransitionGroup",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    tag: {
      type: String,
      required: false,
      default: "ul"
    },
    translateY: {
      type: [Number, String, Object],
      required: false,
      default: "1.5em"
    },
    duration: {
      type: [Number, Function],
      required: false,
      default: 1000
    },
    elasticity: {
      type: [Number, Function],
      required: false,
      default: 100
    },
    delay: {
      type: Number,
      required: false,
      default: 250
    },
    isLeave: {
      type: Boolean,
      required: false,
      default: true
    }
  },
  methods: {
    beforeEnter: function(el) {
      const that = this;
      const styles = el.style;

      styles.opacity = 0;
      styles.transform = `translateY(${this.translateY})`;
      styles.willChange = "opacitym, transform";
    },
    enter: function(el, complete) {
      // var delay = el.dataset.index * 150
      const index = el.dataset.index;
      const that = this;

      anime({
        targets: el,
        delay: function(el, i, l) {
          return index * that.delay;
        },
        duration: that.duration,
        translateY: "0",
        elasticity: that.elasticity,
        opacity: 1,
        complete
      });
    },
    leave: function(el, complete) {
      let options = {};
      const that = this;

      if (this.isLeave) {
        const index = el.dataset.index;
        options = {
          delay: function(el, i, l) {
            return index * that.delay;
          },
          elasticity: that.elasticity,
          translateY: that.translateY,
          opacity: 0,
          duration: that.duration,
          complete
        };
      } else {
        options = {
          complete,
          opacity: {
            value: 0,
            duration: 150
          },
          height: {
            value: 0,
            duration: 200
          }
        };
      }

      anime({
        targets: el,
        ...options
      });
    }
  }
};
</script>
