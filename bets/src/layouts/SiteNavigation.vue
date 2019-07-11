<template>
  <nav
    :aria-label="label"
    :class="{
      'navigation width_fill-phone': true,
      ['navigation_' + modifier]: modifier,
      [className]: className,
      [`color_${navigationStyle}`]: navigationStyle
  }">
    <ul class="parent row wrap">

      <li
        :class="{
          'logo baseChild margin-top_zero width_fill-phone': true,
          [`logo_${modifier}`]: modifier
      }">
        <h1 >
          <a
            :class="{
              'parent font-weight_extrabold text_nowrap h-centered_phone': true,
              [`color_${logoColorName}`]: logoColorName
            }"
            href="/"
            title="На главную"
            aria-label="На главную">
            <span 
              :class="{
                color_blue: logoColored
            }">ESX.</span>
            <span>
              BET
            </span>
          </a>
        </h1>
        <p :class="{'logoAbbr parent h-centered_phone': true, [`color_${logoColorName}`]: logoColorName}">
          <span :class="{color_blue: logoColored}">e</span>-<span :class="{color_blue: logoColored}">s</span>ports e<span :class="{color_blue: logoColored}">x</span>change
        </p>

      </li>


      <li
        v-for="(link, index) in links"
        :key="index"
        :class="{ 'parent centered navigationItem margin-top_zero': true,
                  navigationItem_footer: modifier === 'footer'
        }"
      >
        <a
          :aria-describedby="`
                ${link.href === currentPage ? 'currentPage' : ''}
              `"
          :href="link.href"
          :class="{
            'navigationLink parent h-centered font-family_decorative text_upper': true,
            'navigationLink_active ': link.name === currentPage,
            [`color_${navigationStyle}`]: navigationStyle,
            [`hover_${activeLinkColor}`]: activeLinkColor
          }"
        >
          {{ link.label }}
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "SiteNavigation",
  components: {},
  mixins: [],
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
    label: {
      type: String,
      required: true
    },
    navigationStyle: {
      type: String,
      required: false,
      default: ""
    },
    logoColorName: {
      type: String,
      required: false,
      default: ""
    },
    logoColored: {
      type: Boolean,
      required: false,
      default: true
    },
    activeLinkColor: {
      type: String,
      required: false,
      default: "blue"
    }
  },
  data: () => ({
    /*
     * Для определения текущий страницы,
     * можно использовать localStorage,
     * помещая в константную строку currentPage
     * имя текущей страницы, которое
     * соответстует имени одной из
     * нижеуказанных ссылок.
     *
     */
    currentPage: window.location.pathname,
    links: [
      {
        href: "/about",
        name: "about",
        label: "О бирже"
      },
      {
        href: "/news",
        name: "news",
        label: "Новости"
      },
      {
        href: "/comunity",
        name: "comunity",
        label: "Сообщество"
      }
    ]
  }),
  computed: {
    // currentPage() {
    //   return localStorage.currentPage;
    // },
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_easing.sass'
@import '../assets/sass/conf/_breakpoints.sass'

.logoAbbr
  margin-top: -.85em
  margin-bottom: .75em

$navigationWidth: 727
$logoWidth: 296
$scale: 1.2
.navigationLink
  transition: color .2s $standart, opacity .2s $standart
  will-change: opacity
  &_active, &:focus, &:hover
    opacity: .9
  &_active.hover_blue, &.hover_blue:focus, &.hover_blue:hover
      color: $blue
  &_active.hover_white, &.hover_white:focus, &.hover_white:hover
      color: $white
  &_active
    font-weight: bold

  // @include breakpoint($tablet)
  //   max-width: 30%
.logo
  @include breakpoint($tablet)
    max-width: em(180)
  @include breakpoint($desktop)
    max-width: em($logoWidth)
  @include breakpoint($phone-less)
    min-height: em(65)
  &_footer
    max-width: em(222) $i

.navigation
  @include breakpoint($phone)
    min-width: 100%
  @include breakpoint($tablet)
      min-width: 67%
  @include breakpoint($desktop)
      min-width: em($navigationWidth)
      max-width: em($navigationWidth)

  &_footer
    min-width: 61.6%

    @include breakpoint($phone-less)
      min-width: 100%
  // @include breakpoint($phone-less)
  //     min-width: 100%
  &Item
    &:nth-of-type(2)
      min-width: em(116)
    &:nth-of-type(3)
      min-width: em(100)
    &:nth-of-type(4)
      min-width: em(134)
    &:nth-of-type(1n+2)

      @include breakpoint($phone-less)
          min-width: 33%
          font-size: 3vw
    &_footer
      // transform: translateY(.2rem)


</style>
