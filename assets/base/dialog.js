// Cerrar cualquier <dialog> de PicoCSS
document.addEventListener('click', (e) => {
    /** @type {HTMLDialogElement} */
    const dialog = e.target.closest('dialog');

    if (!dialog || !dialog.open) return;
    const article = dialog.querySelector(':scope > article');    // PicoCSS espera <article>

    // Cerrar si se cliquea fuera de <article> (o en cualquier momento)
    if (dialog.closedBy === "none") return;

    if (!article || !article.contains(e.target)) {
        dialog.close();
    }
});
