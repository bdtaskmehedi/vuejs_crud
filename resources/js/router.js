import { createRouter, createWebHistory } from "vue-router";
import TaskList from "./components/TaskList.vue";
import TaskAdd from "./components/TaskAdd.vue";
import Home from "./components/TaskComponent.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home, // Ensure this is correct
    },
    /* { path: "/", name: "TaskList", component: TaskList },
    { path: "/tasks", name: "TaskList", component: TaskList }, */
    { path: "/add", name: "TaskAdd", component: TaskAdd },
    {
        path: "/tasks",
        name: "tasks",
        component: () => import("./components/TaskList.vue"), // Example for lazy loading
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
