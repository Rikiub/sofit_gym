const ISO_DATETIME_PATTERN = /^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?)/;
const ISO_DATE_PATTERN = /^\d{4}-\d{2}-\d{2}T/;
const ISO_TIME_PATTERN = /T(\d{2}:\d{2}(:\d{2})?)/;

/**
 * Convierte cualquier fecha para ser compatible con <input type="date"\>
 * @param {string?} value
 * @returns {string?}
 */
export function extractDate(value) {
    if (!value) return value;
    if (ISO_DATE_PATTERN.test(value)) return value.split("T")[0];
}

/**
 * Convierte cualquier fecha para ser compatible con <input type="datetime-local"\>
 * @param {string?} value
 * @returns {string?}
 */
export function extractDateTimeLocal(value) {
    if (!value) return value;

    // Case 1: It's an ISO Datetime (contains a 'T')
    if (value.includes("T")) {
        const match = value.match(ISO_DATETIME_PATTERN);
        return match ? match[1] : value; // Returns "YYYY-MM-DDTHH:mm:ss" or "YYYY-MM-DDTHH:mm"
    }

    // Case 2: It's a plain date from an all-day event (e.g., "2026-04-26")
    if (ISO_DATE_PATTERN.test(value)) {
        return `${value}T00:00:00`; // datetime-local demands a time component to render
    }

    return value;
}

/**
 * Convierte cualquier fecha en tiempo (HH:mm:ss)
 * @param {string?} value - e.g., "2026-04-26T14:30:00+02:00"
 * @param {boolean} includeSeconds - If true, keeps the seconds component
 * @returns {string?} - e.g., "14:30"
 */
export function extractTime(value, includeSeconds = false) {
    if (!value || !value.includes("T")) return null;

    const match = value.match(ISO_TIME_PATTERN);
    if (!match) return null;

    const fullTime = match[1]; // This is "14:30:00"

    if (includeSeconds) {
        return fullTime;
    }

    return fullTime.substring(0, 5); // Drops seconds, returns "14:30"
}