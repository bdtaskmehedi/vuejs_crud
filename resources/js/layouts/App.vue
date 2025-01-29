<template>
    <div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <router-link to="/" class="navbar-brand">Task Manager</router-link>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <router-link to="/" class="nav-link">View Tasks</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/add" class="nav-link">Add Task</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Layout with Sidebar -->
        <div class="d-flex">
            <!-- Sidebar -->
            <aside class="bg-dark text-white p-3" style="width: 250px;">
                <nav>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <router-link to="/tasks" class="nav-link text-white">View Tasks</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/add" class="nav-link text-white">Add Task</router-link>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-grow-1 p-4">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style scoped>
aside {
    min-height: 100vh;
}
</style>
