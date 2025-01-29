<template>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Tasks</h1>
        <table class="table table-border table-striped">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th class="text-center">Action</th>
                </tr>
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
            <form @submit.prevent="updateTask" class="card p-4 shadow" enctype="multipart/form-data" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input 
                        class="form-control"
                        type="text"
                        id="title"
                        v-model="form.title"
                        placeholder="Enter task title"
                    />
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        class="form-control"
                        placeholder="Enter task description"
                    ></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Choose an image:</label>
                    <input 
                        class="form-control"
                        type="file"
                        id="image"
                        @change="handleFileChange"
                    />
                </div>
        
                <!-- Display Image Preview -->
                <div v-if="imageUrl" class="mb-3">
                    <label>Preview:</label>
                    <img :src="imageUrl" alt="Image Preview" class="img-fluid" />
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

export default {
    data() {
        return {
            tasks: [],
            editingTask: null,
            form: {
                id: null,
                title: "",
                description: "",
                image: null, // Image will be stored here
            },
            imageUrl: null, // This will be used to preview the image
        };
    },
    mounted() {
        this.fetchTasks();
    },
    methods: {
        showEditForm(task) {
            this.editingTask = task;
            this.form.id = task.id;
            this.form.title = task.title;
            this.form.description = task.description;
            this.imageUrl = task.image ? `/storage/${task.image}` : null; // Preview the existing image
        },

        // Handle image file selection
        handleFileChange(event) {
            console.log(event.target.files[0]);
            this.form.image = event.target.files[0];
            console.log("Selected file:", this.form.image); // Debugging file selection
        },

        // Update the task with form data
        async updateTask() {
            const formData = new FormData();
            formData.append("title", this.form.title);
            formData.append("description", this.form.description);

            if (this.form.image) {
                formData.append("image", this.form.image);

                console.log("image-----------", this.form.image);
            }

            try {
                const response = await axios.put(`/api/tasks/${this.form.id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                console.log("Task updated successfully!", response.data);
            } catch (error) {
                console.log("Task update failed");
            }
        },

        // Cancel editing and reset form
        cancelEdit() {
            this.editingTask = null;
            this.form = {
                id: null,
                title: "",
                description: "",
                image: null,
            };
            this.imageUrl = null;
        },

        // Fetch tasks
        async fetchTasks() {
            const response = await axios.get('/api/tasks');
            this.tasks = response.data;
        },
    },
};

</script>
