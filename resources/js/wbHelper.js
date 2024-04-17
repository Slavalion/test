export const WbHelperImage = {
    volHostV2(e) {
        return e >= 0 && e <= 143
            ? '//basket-01.wbbasket.ru/'
            : e >= 144 && e <= 287
              ? '//basket-02.wbbasket.ru/'
              : e >= 288 && e <= 431
                ? '//basket-03.wbbasket.ru/'
                : e >= 432 && e <= 719
                  ? '//basket-04.wbbasket.ru/'
                  : e >= 720 && e <= 1007
                    ? '//basket-05.wbbasket.ru/'
                    : e >= 1008 && e <= 1061
                      ? '//basket-06.wbbasket.ru/'
                      : e >= 1062 && e <= 1115
                        ? '//basket-07.wbbasket.ru/'
                        : e >= 1116 && e <= 1169
                          ? '//basket-08.wbbasket.ru/'
                          : e >= 1170 && e <= 1313
                            ? '//basket-09.wbbasket.ru/'
                            : e >= 1314 && e <= 1601
                              ? '//basket-10.wbbasket.ru/'
                              : e >= 1602 && e <= 1655
                                ? '//basket-11.wbbasket.ru/'
                                : e >= 1656 && e <= 1919
                                  ? '//basket-12.wbbasket.ru/'
                                  : e >= 1920 && e <= 2045
                                    ? '//basket-13.wbbasket.ru/'
                                    : e >= 2046 && e <= 2189
                                      ? '//basket-14.wbbasket.ru/'
                                      : e >= 2091 && e <= 2405
                                        ? '//basket-15.wbbasket.ru/'
                                        : '//basket-16.wbbasket.ru/'
    },
    constructHostV2(vendorCode) {
        const n = parseInt(vendorCode, 10),
            r = ~~(n / 1e5),
            o = ~~(n / 1e3)
        return `${this.volHostV2(r)}vol${r}/part${o}/${n}`
    },
}

export function wbFeedbackUrlDigit(productCode) {
    const numToUint8Array = function (r) {
        const t = new Uint8Array(8)

        for (let n = 0; n < 8; n++) {
            t[n] = r % 256
            r = Math.floor(r / 256)
        }

        return t
    }

    const crc16Arc = function (r) {
        const t = numToUint8Array(r)
        let n = 0
        for (let r = 0; r < t.length; r++) {
            n ^= t[r]
            for (let r = 0; r < 8; r++) (1 & n) > 0 ? (n = (n >> 1) ^ 40961) : (n >>= 1)
        }
        return n
    }
    const prepareUrl = function (t) {
        const e = crc16Arc(t)
        return e % 100 >= 50 ? '2' : '1'
    }

    return prepareUrl(productCode)
}
