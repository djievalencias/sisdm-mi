<template>
    <div class="hold-transition lockscreen">
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                <img
                    src="/public/assets/CVMebelInternational.png"
                    alt="Attendance Live Logo"
                />
            </div>

            <!-- START LOCK SCREEN ITEM -->
            <div class="lockscreen-item text-center">
                <h2>Welcome Page</h2>
            </div>
            <!-- /.lockscreen-item -->
            <div class="help-block text-center">
                Enter your email and password to retrieve your session
            </div>
            <div class="text-center">
                <router-link to="/login">Login</router-link>
                <!-- Update this link to point to the Vue login route -->
            </div>
            <div class="lockscreen-footer text-center">Capstone S1T24K01</div>
        </div>
        <!-- /.center -->
    </div>
</template>

<script>
export default {
    name: "Home",
};
</script>

<style scoped>
.hold-transition {
    background-color: white; /* Grey background color */
    width: 100%;
    height: 75vh; /* Full height */
    display: flex;
    justify-content: center;
    align-items: center;
}

.lockscreen-wrapper {
    text-align: center;
}

.lockscreen-logo img {
    max-width: 100%; /* 3/4 of the full width */
    height: auto;
    margin: 0 auto; /* Center the image */
    display: block; /* Ensure the image is a block element */
}
</style>
