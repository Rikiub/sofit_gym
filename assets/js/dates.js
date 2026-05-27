import dayjs from 'dayjs';

/** @param {string|Date} date */
export function toIsoDate(date) {
    return dayjs(date).format("YYYY-MM-DD");
}

/** @param {string|Date} date */
export function toIsoDateTime(date) {
    return dayjs(date).format("YYYY-MM-DD[T]hh:mm:ss");
}

/** @param {string|Date} date */
export function toTime(date) {
    return dayjs(date).format("hh:mm:ss");
}