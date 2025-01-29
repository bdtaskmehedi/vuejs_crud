<template>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Tasks</h1>
        <table class="table table-border table-striped">
            <thead>
                <th>SL</th>
                <th>Title</th>
                <th>Image</th>
                <th class="text-center">Action</th>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id">
                    <td>{{ task.id }}</td>
                    <td>{{ task.title }}</td>
                    <td><img v-if="task.image" :src="`/storage/${task.image}`" alt="Task Image" width="100" /></td>
                    <td class="text-center">
                        <button
                            @click="deleteTask(task.id)"
                            class="btn btn-danger btn-sm">
                            Delete
                        </button> 
                        <button
                            @click="showEditForm(task)"
                            class="btn btn-success btn-sm">
                            Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Edit Form -->
        <div v-if="editingTask">
            <h2>Edit Task: {{ editingTask.title }}</h2>
              <form @submit.prevent="updateTask" class="card p-4 shadow">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input   class="form-control"
                        type="text"
                        id="title"
                        v-model="form.title"
                        placeholder="Enter task title"/>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        class="form-control"
                    ></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Choose an image:</label>
                    <input class="form-control" type="file" id="image" @change="handleFileChange" />
                </div>
               

                <button class="btn btn-success btn-sm" type="submit">Save Changes</button>
                <button class="btn btn-danger btn-sm" type="button" @click="cancelEdit">Cancel</button>
            </form>
        </div>
         <!-- Show image preview -->
         <div v-if="imageUrl">
            <h3>Preview:</h3>
            <img :src="imageUrl" alt="Image Preview" width="100" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import TaskAdd from './TaskAdd.vue';

export default {
    data() {
        return {
            tasks: [],
            editingTask: null, // Currently editing task
            form: {
                id: null,
                title: "",
                description: "",
                image: "",
            },
        };
    },
    mounted() {
        this.fetchTasks();
    },
    methods: {

        showEditForm(task) {
            this.editingTask = task; // Set the task being edited
            this.form.id = task.id; // Set form fields
            this.form.title = task.title;
            this.form.description = task.description;
            this.form.image = task.image;
        },

        async fetchTask() {
            try {
                const response = await axios.get(`/api/tasks/${this.taskId}`);
                const task = response.data;

                this.title = task.title;
                this.description = task.description;
                this.imageUrl = task.image ? `/storage/${task.image}` : null; // Set image URL for preview
            } catch (error) {
                console.error("Error fetching task:", error);
            }
        },

        // Handle file change (image selection)
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.image = file;
                this.imageUrl = URL.createObjectURL(file); // For preview
            }
        },

        // Update the task
        /* updateTask() {
            const formData = new FormData();
            formData.append("title", this.title);
            formData.append("description", this.description);
            if (this.image) {
                formData.append("image", this.image);
            }
            axios.put(`/api/tasks/${this.form.id}`, formData, { title: this.form.title, description: this.form.description })
                .then(() => {
                    const task = this.tasks.find((t) => t.id === this.form.id);
                    if (task) {
                        task.title = this.form.title;
                        task.description = this.form.description    ;
                    }
                    this.editingTask = null;
                    this.form.id = null;
                    this.form.title = "";
                    this.form.description = "";
                })
                .catch((error) => {
                    console.error("Failed to update task:", error);
                });
        }, */

        async  updateTask() {
            const formData = new FormData();
            formData.append("title", this.form.title);
            formData.append("description", this.form.description);
            if (this.form.image) {
                formData.append("image", this.form.image);
            }
            console.log('--------', formData);
            try {
                await axios.put(`/api/tasks/${this.form.id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                alert("Task updated successfully!");
                this.$router.push("/tasks"); // Redirect to tasks list page
            } catch (error) {
                console.error("Error updating task:", error);
                alert("Failed to update task");
            }
        },
        // Cancel editing
        cancelEdit() {
            this.editingTask = null; // Clear the editing state
            this.form.id = null;
            this.form.title = "";
            this.form.description = "";
        },

        async fetchTasks() {
            const response = await axios.get('/api/tasks');
            this.tasks = response.data;
        },
        async deleteTask(id) {
            await axios.delete(`/api/tasks/${id}`);
            this.tasks = this.tasks.filter((task) => task.id !== id);
        },
        async editTask(id) {
            await axios.get(`/api/tasks/${id}`)
                .then(function (res) {
                console.log(res.data.title);
                })
                .catch((error) => {
                    console.error("Error updating task:", error);
                });
            /* this.tasks = this.tasks.filter((task) => task.id !== id);
             console.log(`Task ${task.id} updated to: ${task.title}`); */
        },
    },
};
</script>
