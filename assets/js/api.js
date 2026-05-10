export const API_PREFIX = "/api";

/**
 * Helper para comunicarse con la API en el backend.
 * Solo acepta y envia JSON.
 * @param string params,
 * @param {RequestInit} options
 * @returns {Promise<object>}
 */
export async function fetchApi(params = "", options = {}) {
    if (
        options.body !== null
        && typeof options.body === "object"
        && !Array.isArray(options.body)
    ) {
        options.body = JSON.stringify(options.body);
    }

    const headers = options.method !== "GET"
        ? { "Content-Type": "application/json" }
        : {};

    const res = await fetch(`${API_PREFIX}${params}`, {
        headers: headers,
        ...options,
    });

    if (res.status === 204) {
        return {};
    } else if (res.ok) {
        return await res.json();
    } else {
        let json = await res.json();
        console.log(json);
        throw new Error(res.status);
    }
}
