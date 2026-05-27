import { format } from "date-fns";

/** @param {string|Date} date */
export function toIsoDate(date) {
    return format(date, "yyyy-MM-dd");
}

/** @param {string|Date} date */
export function toIsoDateTime(date) {
    return format(date, "yyyy-MM-dd'T'hh:mm:ss");
}

/** @param {string|Date} date */
export function toTime(date) {
    return format(date, "hh:mm:ss");
}
