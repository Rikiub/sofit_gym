// ========== CALENDARIO CON DÍA ACTUAL MARCADO ==========
let currentDate = new Date(2026, 3); // Abril 2026

function generarCalendario() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    let offset = firstDay === 0 ? 6 : firstDay - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const totalCells = 42;
    let html = "";
    const today = new Date();
    const todayYear = today.getFullYear();
    const todayMonth = today.getMonth();
    const todayDay = today.getDate();

    for (let i = 0; i < totalCells; i++) {
        let day = i - offset + 1;
        if (day >= 1 && day <= daysInMonth) {
            let cls = "";
            if (
                year === todayYear && month === todayMonth && day === todayDay
            ) {
                cls = 'class="today"';
            }
            html += `<span ${cls}>${day}</span>`;
        } else {
            html += `<span></span>`;
        }
    }
    document.getElementById("calendarDays").innerHTML = html;
    const meses = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    ];
    document.getElementById("monthYearDisplay").innerText = `${meses[month]
        } ${year}`;
}

function cambiarMes(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    generarCalendario();
}

document.getElementById("prevMonthBtn")?.addEventListener(
    "click",
    () => cambiarMes(-1),
);
document.getElementById("nextMonthBtn")?.addEventListener(
    "click",
    () => cambiarMes(1),
);

// ========== GRÁFICO ==========
const ctx = document.getElementById("weeklyProgress").getContext("2d");
new Chart(ctx, {
    type: "line",
    data: {
        labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
        datasets: [{
            label: "Asistencias",
            data: [112, 135, 148, 127, 158, 92, 65],
            borderColor: "#dc2626",
            backgroundColor: "rgba(220,38,38,0.1)",
            fill: true,
            tension: 0.3,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { labels: { color: "#1e293b" } } },
        scales: {
            y: { ticks: { color: "#1e293b" } },
            x: { ticks: { color: "#1e293b" } },
        },
    },
});

// ========== INICIALIZAR CALENDARIO ==========
generarCalendario();

// ========== DROPDOWNS ==========
// Reporte
const reporteTrigger = document.getElementById("reporteTrigger");
const reporteMenu = document.getElementById("reporteMenu");
if (reporteTrigger && reporteMenu) {
    reporteTrigger.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        reporteMenu.classList.toggle("show");
    });
    document.addEventListener("click", (e) => {
        if (
            !reporteTrigger.contains(e.target) &&
            !reporteMenu.contains(e.target)
        ) {
            reporteMenu.classList.remove("show");
        }
    });
    reporteMenu.querySelectorAll("a").forEach((item) => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
            const tipo = item.getAttribute("data-reporte") || "personalizado";
            alert(`Generando reporte ${tipo}... (simulación)`);
            reporteMenu.classList.remove("show");
        });
    });
}
// Perfil
const profileIcon = document.getElementById("profileIcon");
const profileMenu = document.getElementById("profileMenu");
if (profileIcon && profileMenu) {
    profileIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle("show");
    });
    document.addEventListener("click", (e) => {
        if (
            !profileIcon.contains(e.target) && !profileMenu.contains(e.target)
        ) {
            profileMenu.classList.remove("show");
        }
    });
}

// ========== ACORDEÓN MENÚ ==========
document.querySelectorAll(".group-title").forEach((title) => {
    title.addEventListener("click", (e) => {
        e.stopPropagation();
        const groupId = title.getAttribute("data-group");
        const items = document.getElementById(groupId);
        if (items) {
            const isVisible = items.style.display === "flex";
            items.style.display = isVisible ? "none" : "flex";
            const icon = title.querySelector(".toggle-icon");
            if (icon) {
                icon.style.transform = isVisible
                    ? "rotate(0deg)"
                    : "rotate(180deg)";
            }
        }
    });
});
