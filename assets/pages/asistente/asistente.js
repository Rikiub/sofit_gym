import { fetchApi } from "@/js/api.js";
import Alpine from "alpinejs";

Alpine.data('chatBot', () => ({
    messages: [{
        sender: 'bot',
        text: '¡Hola! 👋 Soy tu asistente virtual. ¿En qué puedo ayudarte?',
        time: new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        })
    }],
    inputText: '',
    isTyping: false,

    getTime() {
        return new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    async sendMessage() {
        const text = this.inputText.trim();
        if (!text || this.isTyping) return;

        // Agregar mensaje del usuario
        this.messages.push({
            sender: 'user',
            text,
            time: this.getTime()
        });
        this.inputText = '';

        // Scroll al final
        this.$nextTick(() => {
            this.$refs.chatBody.scrollTop = this.$refs.chatBody.scrollHeight;
        });

        // Activar indicador de escritura
        this.isTyping = true;

        try {
            // Conectar con tu backend (reemplaza la URL por la real)
            const data = await fetchApi({
                page: "asistente",
                action: "generateText",
            }, {
                method: "POST",
                body: {
                    message: text
                }
            });

            const botReply = data.message || 'Lo siento, no obtuve una respuesta.';

            this.messages.push({
                sender: 'bot',
                text: botReply,
                time: this.getTime()
            });
        } catch (error) {
            console.error(error);
            this.messages.push({
                sender: 'bot',
                text: '⚠️ Error al conectar con el asistente. Intenta de nuevo.',
                time: this.getTime()
            });
        } finally {
            this.isTyping = false;
            this.$nextTick(() => {
                this.$refs.chatBody.scrollTop = this.$refs.chatBody.scrollHeight;
            });
        }
    },

    resetChat() {
        this.messages = [{
            sender: 'bot',
            text: '¡Hola! 👋 Soy tu asistente virtual. ¿En qué puedo ayudarte?',
            time: this.getTime()
        }];
        this.inputText = '';
        this.isTyping = false;
    }
}));