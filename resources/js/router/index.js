// Filepath: /resources/js/router/index.js
import { createRouter, createWebHistory } from "vue-router";
import Welcome from "../components/Welcome.vue";
import Login from "../components/auth/Login.vue";
import Home from "../components/Home.vue"; // Tambahkan import Home.vue

const routes = [
    {
        path: "/",
        name: "Welcome",
        component: Welcome,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/home",
        name: "Home",
        component: Home, // Tambahkan Home ke route
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
