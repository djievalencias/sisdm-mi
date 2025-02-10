// filepath: /c:/xampp/htdocs/Tugas_Akhir/fullstackfixcoba/attendance-backend/resources/js/router/index.js
import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/Home.vue";
import Login from "../components/Login.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    // Add more routes here as needed
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
