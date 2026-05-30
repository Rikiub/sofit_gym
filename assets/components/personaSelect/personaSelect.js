import Alpine from "alpinejs";
import { fetchApi } from "@/js/api.js";

Alpine.data("personaSelect", () => ({
    type: "trabajadores",
    filterIdRol: "1",

    items: [],
    search: null,
    popover: null,
    selectedCedula: null,
    
    async init() {
        document.addEventListener('click', (e) => {
            if (!this.popover) return;

            const clickedInsidePopover = e.target.closest('.popover');
            const clickedTriggerButton = this.$refs.selectButton.contains(e.target);

            if (!clickedInsidePopover && !clickedTriggerButton) {
                this.hidePopover();
            }
        });

        await this.handleSearch(); 
    },

    togglePopover() {
        if (this.popover) {
            this.hidePopover();
        } else {
            this.showPopover();
        }
    },
    showPopover() {
        if (!this.popover) {
            // Reveal the element right before giving it to Bootstrap
            this.$refs.popoverContent.style.display = 'block';

            this.popover = new bootstrap.Popover(this.$refs.selectButton, {
                content: () => this.$refs.popoverContent,
                html: true,
                trigger: 'manual',
                placement: 'auto',
                container: this.$refs.selectButton.closest('[x-data]') || 'body',
                customClass: 'select-popover',
                sanitize: false
            });

            this.$refs.selectButton.addEventListener('hidden.bs.popover', () => {
                if (this.popover) {
                    // Completely destroy the old Bootstrap instance wrapper
                    this.popover.dispose(); 
                    this.popover = null;
                    
                    // Hide your element and pull it safely back into its original home base
                    this.$refs.popoverContent.style.display = 'none';
                    this.$refs.popoverContainer.appendChild(this.$refs.popoverContent);
                }
            }, { once: true }); // { once: true } ensures listeners don't stack up endlessly
        }

        this.popover.show();
    },
    hidePopover() {
        if (this.popover) this.popover.hide();
    },

    setSelected(item) {
        this.search = "";
        this.selectedCedula = item.cedula;
        this.hidePopover();
    },
    
    async handleSearch() {
        let params = { page: this.type, action: "query" };
        if (this.search) {
            params = { ...params, search: this.search, id_rol: this.filterIdRol };
        }
        this.items = await fetchApi(params);
        
        if (this.popover) this.popover.update();
    },
}));