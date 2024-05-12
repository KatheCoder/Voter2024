<template>
<div>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link  class="nav-link" to="/dashboard/A">Page 1</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link  class="nav-link" to="/dashboard/B">Page 2</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" @click="logout" to="/logout">Logout</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


</div>
</template>

<script>
import axios from 'axios';

export default {
    name: "NavigationMenu",
    methods: {
        logout() {
            axios.post('/logout')
                .then(response => {
                    window.location.href = '/';
                })
                .catch(error => {
                    console.error('Logout failed:', error);
                });
        }
    }
}
</script>

<style scoped>

</style>
