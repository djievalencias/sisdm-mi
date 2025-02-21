<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Attendance</b>Live</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form @submit.prevent="login">
                    <div class="input-group mb-3">
                        <input
                            id="email"
                            type="email"
                            placeholder="Email"
                            v-model="email"
                            class="form-control"
                            :class="{ 'is-invalid': errors.email }"
                            required
                            autofocus
                        />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <span
                            v-if="errors.email"
                            class="invalid-feedback"
                            role="alert"
                        >
                            <strong>{{ errors.email }}</strong>
                        </span>
                    </div>

                    <div class="input-group mb-3">
                        <input
                            id="password"
                            type="password"
                            placeholder="Password"
                            v-model="password"
                            class="form-control"
                            :class="{ 'is-invalid': errors.password }"
                            required
                        />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <span
                            v-if="errors.password"
                            class="invalid-feedback"
                            role="alert"
                        >
                            <strong>{{ errors.password }}</strong>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    v-model="remember"
                                />
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >
                                Sign In
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <router-link to="/forgot-password"
                        >I forgot my password</router-link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    setup() {
        const email = ref("");
        const password = ref("");
        const remember = ref(false);
        const errors = ref({});

        const login = async () => {
            try {
                const response = await axios.post("/api/login", {
                    email: email.value,
                    password: password.value,
                    remember: remember.value,
                });
                console.log("Login successful:", response.data);
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    errors.value = error.response.data.errors;
                } else {
                    console.error("Login error:", error);
                }
            }
        };

        return { email, password, remember, errors, login };
    },
};
</script>

<style scoped>
.invalid-feedback {
    color: red;
    font-size: 0.875rem;
}
</style>
