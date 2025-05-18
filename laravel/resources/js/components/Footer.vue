<template>
    <footer class="app-footer">
        <p>© 2025 Histoires Guidées</p>
        <p>Projet HEIG FullStack</p>
    </footer>
</template>

<script>
export default {
    name: 'Footer'
}
</script>

<style scoped>
.app-footer {
    padding: 12px 20px;
    background-color: rgba(25, 5, 5, 0.8);
    color: white;
    text-align: center;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 100;
    font-size: 0.95rem;
    height: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.app-footer p {
    margin: 0 0 1px 0;
}

/* Ajoute un padding-bottom au contenu principal pour éviter que le footer ne cache du contenu */
:deep(body) {
    padding-bottom: 100px;
}
</style>