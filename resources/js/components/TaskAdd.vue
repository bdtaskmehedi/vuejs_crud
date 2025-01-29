<template>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Add Task</h1>
        <div v-if="message" class="alert alert-success">
            {{ message }}
          </div>
        <form @submit.prevent="addTask" class="card p-4 shadow">
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input
                    v-model="title"
                    type="text"
                    id="title"
                    class="form-control"/>
                      <!-- Display title error -->
                    <span v-if="errors.title" class="text-danger">{{ errors.title[0] }}</span>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea
                    v-model="description"
                    id="description"
                    class="form-control"
                ></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Choose an image:</label>
                <input class="form-control" type="file" id="image" ref="image" @change="handleFileChange" />
                <span v-if="errors.image" class="text-danger">{{ errors.image[0] }}</span>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
        <!-- Show uploaded image preview -->
        <div v-if="imageUrl">
            <h3>Preview:</h3>
            <img :src="imageUrl" alt="Image Preview" width="200" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            title: '',
            description: '',
            image: null,
            imageUrl: null,
            errors: {},
            message:'',
        };
    },
    methods: {
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.image = file;
                this.imageUrl = URL.createObjectURL(file); // For preview
            }
        },
        async addTask() {
            const formData = new FormData();
            formData.append("image", this.image);
            formData.append("title", this.title);
            formData.append("description", this.description);

            try {
                const response = await axios.post('/api/tasks', formData, {
                    headers: {
                            "Content-Type": "multipart/form-data",
                        },
                });

                this.message = response.data.message;
                this.title = "";
                this.description = "";
                this.image = null;
                this.$refs.imageInput.value = ""; // Reset file input field

            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                    console.log(this.errors);
                }
            }
        },
    },
};
</script>
