export function useDebounce(callee, delay) {
    let timer
    return function (...args) {
        clearTimeout(timer)

        timer = setTimeout(() => {
            callee(...args)
        }, delay)
    }
}
