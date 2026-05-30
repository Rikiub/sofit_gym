<?php
$title = "Inicio de Sesión";
$this->layout("layout", ["title" => $title, "sidebar" => false]);
?>

<script type="module">
    import Alpine from "alpinejs";
    import {
        fetchApi
    } from "@/js/api.js";
    import FormDataJson from "form-data-json";

    Alpine.data("login", () => ({
        error: "",

        async handleSubmit() {
            try {
                const json = await fetchApi({
                    page: "login",
                    action: "login"
                }, {
                    method: "POST",
                    body: FormDataJson.toJson(this.$refs.form),
                });

                // Redirigir a pagina indicada
                self.location.href = json.redirect;
            } catch (e) {
                this.error = e.cause.message;
                return;
            }
        }
    }));
</script>

<?= $this->insert("card", [
    "class" => "main-card",
    "icon" => "fa-solid fa-arrow-right-to-bracket",
    "title" => $title,
    "body" => <<<HTML
        <div x-data="login">
            <form class="row container" x-ref="form" @submit.prevent="handleSubmit">
                <label class="form-label">Usuario
                    <input class="form-control" name="nombre_usuario" required>
                </label>
                
                <label class="form-label">Contraseña
                    <input class="form-control" name="contrasena" required>
                </label>

                <small x-show="error" x-text="error"></small>

                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>
        </div>
    HTML,
]) ?>

<style>
    .main-card {
        margin: auto;
        max-width: 500px;
    }
</style>