// ========== TOGGLE SIDEBAR ==========
const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("sidebarToggle");
if (sidebar && toggleBtn) {
    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
        localStorage.setItem(
            "sidebarCollapsed",
            sidebar.classList.contains("collapsed"),
        );
    });
    if (localStorage.getItem("sidebarCollapsed") === "true") {
        sidebar.classList.add("collapsed");
    }
}