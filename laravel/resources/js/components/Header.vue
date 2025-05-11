<template>
    <div class="header-bar">
        <router-link to="/home" class="logo">Histoire Guidée</router-link>
        <div>
            <span>{{ userName }}</span>
            <form @submit.prevent="logout" class="inline-block ml-4">
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Header',
    data() {
        return {
            userName: ''
        }
    },
    mounted() {
        // Récupérer le nom de l'utilisateur depuis les données de l'application
        this.userName = document.querySelector('meta[name="user-name"]')?.content || '';
    },
    methods: {
        async logout() {
            try {
                await axios.post('/logout');
                window.location.href = '/login';
            } catch (error) {
                console.error('Erreur lors de la déconnexion:', error);
            }
        }
    }
}
</script>

<style scoped>
.header-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: rgba(25, 5, 5, 0.8);
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
}

.logo {
    font-weight: bold;
    color: white;
    text-decoration: none;
}

.logout-btn {
    background-color: #5a1414;
    color: white;
    border: none;
    padding: 5px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
}

.logout-btn:hover {
    background-color: #7a1c1c;
}
</style>