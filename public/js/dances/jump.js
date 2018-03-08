export default (elem, value, options = {}) => {
  const max = !isNaN(options.max) ? options.max : 30
  const min = !isNaN(options.min) ? options.min : 0
  const jump = (max - min) * value
  elem.style.transform = `translateY(${-jump}px)`
}