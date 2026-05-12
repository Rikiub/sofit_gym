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

    const defaultHeaders = options.method !== "GET"
        ? { "Content-Type": "application/json" }
        : {};

    const response = await fetch(`${API_PREFIX}${params}`, {
        headers: { ...defaultHeaders, ...options.headers },
        ...options,
    });

    if (!response.ok) {
        let body;

        try {
            body = await response.clone().json();
        } catch {
            body = await response.clone().text();
        }

        console.error(body);
        throw new Error(
            `API error ${response.status}: ${response.statusText}`,
            { cause: { ...body, status: response.status } }
        );
    }

    return response.status === 204 ? null : await response.json();
}
