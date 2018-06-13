NodeList.prototype.forEach = Array.prototype.forEach

MK = {// eslint-disable-line no-undef
    init: function () {
        // Nothing to do here on old IE
        if (!window.getComputedStyle) {
            return
        }

        // Parallax-scrolling header image
        // Assuming background position is set in %
        const header = document.querySelector('.header')
        const initialHeaderBgPos = parseInt(window.getComputedStyle(
                header).backgroundPositionY, 10)
        window.addEventListener('scroll', () => {
            const scrollPos = window.pageYOffset || document.documentElement.scrollTop
            const newBgPos = initialHeaderBgPos * (1 - (scrollPos / 320))
            if (newBgPos > 0) {
                header.style.backgroundPositionY = `${newBgPos}%`
            }
        })

        // Keep nav sticky if fitting into viewport
        window.addEventListener('resize', () => this.stickyNav())
        this.stickyNav()

        // Triangle ripple FX
        const elements = 'button, input:not([type=checkbox]):not([type=radio]), select, textarea'
        document.querySelectorAll(elements).forEach((el) => {
            el.addEventListener('click', event => this.ripple(el, event))
        })

        // Focus first text input when filter box is clicked anywhere
        document.querySelectorAll('.filter_content').forEach((el) => {
            el.addEventListener('click', (event) => {
                if (event.target === el) {
                    const firstInput = el.querySelector(
                            'input:not([type=checkbox]):not([type=radio])')
                    if (firstInput) {
                        firstInput.focus()
                    }
                }
            })
        })
    },

    offset: function (el) {
        const rect = el.getBoundingClientRect()
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop
        return {top: rect.top + scrollTop, left: rect.left + scrollLeft}
    },

    open: function (event, url) {
        // If triggered by key event, only open link if return or space was pressed
        if (event.keyCode && event.keyCode !== 13 && event.keyCode !== 32) {
            return
        }
        window.location = url
    },

    ripple: function (el, event) {
        const rect = el.getBoundingClientRect()

        const triangle = document.createElement('div')
        triangle.className = 'fx-triangle'
        triangle.style.width = `${el.clientWidth}px`
        triangle.style.height = `${el.clientHeight}px`
        triangle.style.paddingLeft = `${event.clientX - rect.left}px`
        triangle.style.paddingTop = `${event.clientY - rect.top}px`

        el.parentNode.insertBefore(triangle, el.nextSibling)

        // Continuously update ripple position in case button moves
        let count = 0
        updatePosition()
        function updatePosition() {
            const rect2 = el.getBoundingClientRect()
            triangle.style.left = `${rect2.left}px`
            triangle.style.top = `${rect2.top}px`

            count++
            if (count < 10) {
                setTimeout(() => updatePosition(), 50)
            }
        }

        setTimeout(() => el.parentNode.removeChild(triangle), 500)
    },

    stickyNav: function () {
        const nav = document.querySelector('.nav')

        if (!nav) {
            return
        }

        if (window.innerHeight > nav.clientHeight + 48) {
            nav.classList.add('-sticky')
        } else {
            nav.classList.remove('-sticky')
        }
    },

    removeAllFilters: function () {
        document.querySelectorAll('.filters_button').forEach((el) => {
            el.classList.remove('-active')
        })
        document.querySelectorAll('.filter').forEach((el) => {
            el.classList.remove('-active')
        })
    },

    toggleAriaExpanded: function (button) {
        const isExpanded = button.getAttribute('aria-expanded') === 'true'
        button.setAttribute('aria-expanded', isExpanded ? 'false' : 'true')
    },

    toggleFilter: function (id) {
        // Set class after ripple FX determined dimensions
        setTimeout(() => {
            const button = document.querySelector(`[aria-controls=${id}]`)
            button.classList.toggle('-active')
            this.toggleAriaExpanded(button)
        }, 0)

        const filter = document.getElementById(id)
        filter.classList.toggle('-active')

        if (filter.classList.contains('-active')) {
            return
        }

        const firstRadio = filter.querySelector('[type=radio]')
        if (firstRadio) {
            firstRadio.checked = true
        }

        const textInputs = filter.querySelectorAll('[type=text]')
        if (textInputs.length) {
            textInputs.forEach(el => {
                el.value = ''
            })
        }
    },

    toggleNav: function (button) {
        document.querySelector('.nav').classList.toggle('-open')
        button.classList.toggle('-active')
        this.toggleAriaExpanded(button)
    }
}

MK.init() // eslint-disable-line no-undef
