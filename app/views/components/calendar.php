<?php
$alpineComponent ??= "calendar";

// Importar libreria FullCalendar junto a sus plugins
$this->pushJs("lib/fullcalendar/index.global.min.js", false);
$this->pushJs("lib/fullcalendar/bootstrap5/index.global.min.js", false);
$this->pushJs("lib/fullcalendar/core/locales/es.global.min.js", false);

$this->pushJs("components/calendar/calendar.js");
?>

<div
    x-data="<?= $alpineComponent ?>"
    @form-success.window="handleFormSucess($event.detail)"></div>