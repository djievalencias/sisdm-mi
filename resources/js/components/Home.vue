<template>
    <div class="hold-transition lockscreen">
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                <a href="#"><b>Attendance</b>Live</a>
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
    /* Add your custom styles here */
}
</style>
