const ISO_DATE_PATTERN = /^\d{4}-\d{2}-\d{2}T/;

/**
 * @param {string?} value
 * @returns {string?}
 */
export function extractDate(value) {
    if (value && ISO_DATE_PATTERN.test(value)) {
        value = value.split("T")[0];
    }
    return value;
}
