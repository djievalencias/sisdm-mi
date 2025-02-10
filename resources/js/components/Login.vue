<template>
    <div class="login-box">
        <div class="login-logo">
            <div class="lockscreen-logo">
                <img
                    src="/public/assets/CVMebelInternational.png"
                    alt="Attendance Live Logo"
                />
            </div>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form @submit.prevent="submitForm">
                    <div class="input-group mb-3">
                        <input
                            v-model="email"
                            id="email"
                            type="email"
                            placeholder="Email"
                            class="form-control"
                            :class="{ 'is-invalid': errors.email }"
                            required
                            autocomplete="email"
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
                            v-model="password"
                            id="password"
                            type="password"
                            placeholder="Password"
                            class="form-control"
                            :class="{ 'is-invalid': errors.password }"
                            required
                            autocomplete="current-password"
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
                                    v-model="remember"
                                    id="remember"
                                />
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >
                                Sign In
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="/password/reset">I forgot my password</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
            remember: false,
            errors: {},
        };
    },
    methods: {
        async submitForm() {
            try {
                // Clear previous errors
                this.errors = {};

                // Retrieve the CSRF token
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");

                // Submit the form data using fetch
                const response = await fetch("/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    credentials: "include", // Include cookies in the request
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password,
                        remember: this.remember,
                    }),
                });

                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    const data = await response.json();

                    if (!response.ok) {
                        this.errors = data.errors || {};
                    } else {
                        // Handle successful login
                        window.location.href = data.redirect;
                    }
                } else {
                    console.error(
                        "Unexpected response format:",
                        await response.text()
                    );
                }
            } catch (error) {
                console.error("Error submitting form:", error);
            }
        },
    },
};
</script>

<style scoped>
.login-box {
    width: 360px;
    margin: 7% auto;
}

.login-logo a {
    font-size: 2rem;
    font-weight: bold;
}

.login-card-body {
    padding: 20px;
}

.invalid-feedback {
    display: block;
}

.lockscreen-logo img {
    max-width: 100%; /* 3/4 of the full width */
    height: auto;
    display: block; /* Ensure the image is a block element */
}
</style>
