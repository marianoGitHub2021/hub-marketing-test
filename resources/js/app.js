import Alpine from 'alpinejs';

// Tema scuro predefinito; la scelta del chiaro resta nel browser dell'utente.
window.hubTheme = {
    current() {
        try {
            return localStorage.getItem('hub-theme') === 'light' ? 'light' : 'dark';
        } catch {
            return 'dark';
        }
    },
    toggle() {
        const next = this.current() === 'light' ? 'dark' : 'light';
        try {
            localStorage.setItem('hub-theme', next);
        } catch {
            // Archiviazione non disponibile: il tema vale solo per questa pagina.
        }
        document.documentElement.dataset.theme = next;
        return next;
    },
};

window.Alpine = Alpine;
Alpine.start();
