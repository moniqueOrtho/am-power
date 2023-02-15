export default {
    methods: {
        convertHex (color, opacity) {
            color = color.replace('#', '')
            let r = parseInt(color.substring(0, 2), 16)
            let g = parseInt(color.substring(2, 4), 16)
            let b = parseInt(color.substring(4, 6), 16)
            let result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')'
            return result
          },
    }
}
