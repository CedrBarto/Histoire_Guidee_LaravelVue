<template>
    <div class="app">
        <Header/>
        <main class="main-content">
            <router-view />
        </main>
        <Footer/>
    </div>
</template>

<script setup>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
</script>

<style>
/* Reset CSS */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #1a1a1a;
    color: #e0e0e0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.app {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.main-content {
    flex: 1;
    padding: 20px;
    margin-bottom: 100px; /* Espace pour le footer */
}

/* Styles globaux */
h1, h2, h3, h4, h5, h6 {
    color: #e0e0e0;
    margin-bottom: 1rem;
}

a {
    color: #ff6b6b;
    text-decoration: none;
    transition: color 0.2s;
}

a:hover {
    color: #ff8f8f;
}

button {
    cursor: pointer;
    transition: all 0.2s;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1a1a1a;
}

::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>