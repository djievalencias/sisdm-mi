<template>
    <div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-12">
                        <div
                            v-if="status"
                            class="alert alert-success"
                            role="alert"
                        >
                            {{ status }}
                        </div>

                        <!-- If admin, show attendance chart -->
                        <div v-if="isAdmin" class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="ion ion-clipboard mr-1"></i>
                                    Attendance
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                        <div v-else class="alert alert-warning">
                            <h5>
                                <i class="icon fas fa-exclamation-triangle"></i>
                                eitss hanya admin yang boleh akses yaa
                            </h5>
                        </div>
                        <!-- /.card -->
                    </section>
                    <!-- /.Left col -->
                </div>
                <!-- /.row (main row) -->
            </div>
        </section>
        <!-- /.content -->
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import axios from "axios";

export default {
    name: "Home",
    setup() {
        const status = ref(null);
        const isAdmin = ref(false);

        onMounted(async () => {
            try {
                const response = await axios.get("/api/user/status");
                status.value = response.data.status;
                isAdmin.value = response.data.is_admin;

                if (isAdmin.value) {
                    const attendanceChart = new Chart(
                        document.getElementById("attendanceChart"),
                        {
                            type: "bar",
                            data: {
                                labels: response.data.chart.labels,
                                datasets: [
                                    {
                                        label: "Attendance",
                                        data: response.data.chart.data,
                                        backgroundColor:
                                            "rgba(75, 192, 192, 0.2)",
                                        borderColor: "rgba(75, 192, 192, 1)",
                                        borderWidth: 1,
                                    },
                                ],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                    },
                                },
                            },
                        }
                    );
                }
            } catch (error) {
                console.error("Error fetching user status:", error);
            }
        });

        return {
            status,
            isAdmin,
        };
    },
};
</script>

<style scoped>
.content-header {
    margin-bottom: 20px;
}

.card-title {
    font-size: 1.25rem;
}

.alert {
    margin-top: 20px;
}
</style>
