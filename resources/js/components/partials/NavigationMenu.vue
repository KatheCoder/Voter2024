<template>
<div>
    <nav class="navbar navbar-expand-lg shadow fixed-top" style="background-color: goldenrod">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link class="nav-link " to="/dashboard/A">
                            <i class="fas fa-chart-line me-1"></i> View 1
                        </router-link>
                    </li>
                    <li class="nav-item ">
                        <router-link class="nav-link " to="/dashboard/B">
                            <i class="fas fa-tasks me-1"></i> View 2
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" @click="logout" to="/logout">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </router-link>
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
    props: {
        totalRespondents: {
            type: String,
            default: ''
        }
    },
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
