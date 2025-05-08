<template>
    <div style="display: flex; padding: 10px">
        <button @click="logout" class="logout-button">Logout</button>
    </div>

    <div class="todo-flex-wrapper">
        <!-- Add Task Section -->
        <section class="todo-section">
            <h2>Add New Task</h2>
            <form
                @submit.prevent="addTask"
                class="task-form"
                style="flex-direction: column; gap: 12px"
            >
                <input
                    v-model="newTask"
                    type="text"
                    placeholder="Enter task title"
                />
                <textarea
                    v-model="newDescription"
                    placeholder="Enter task description"
                    rows="3"
                    class="description-input"
                ></textarea>
                <button type="submit">
                    {{ isEditing ? 'Update' : 'Add' }}
                </button>
            </form>
        </section>

        <!-- Pending Tasks Section -->
        <section class="todo-section">
            <h2>📝 Pending Tasks</h2>
            <ul v-if="pendingTasks.length">
                <li v-for="(task, index) in pendingTasks" :key="index">
                    <div class="task-left">
                        <input
                            type="checkbox"
                            v-model="task.completed"
                            @change="completeTask(task)"
                        />
                        <span>{{ task.title }}</span>
                    </div>
                    <div class="task-actions">
                        <button @click="editTask(task)">Edit</button>
                        <button @click="deleteTask(task)">Delete</button>
                    </div>
                </li>
            </ul>
            <p v-else class="empty">No pending tasks 🎉</p>
        </section>

        <!-- Completed Tasks Section -->
        <section class="todo-section">
            <h2>✅ Completed Tasks</h2>
            <ul v-if="completedTasks.length">
                <li v-for="(task, index) in completedTasks" :key="index">
                    <div class="task-left completed">
                        <input type="checkbox" v-model="task.completed" />
                        <span>{{ task.title }}</span>
                    </div>
                    <div class="task-actions">
                        <button @click="editTask(task)">Edit</button>
                        <button @click="deleteTask(task)">Delete</button>
                    </div>
                </li>
            </ul>
            <p v-else class="empty">No completed tasks yet.</p>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import API from '../config/api'

const tasks = ref([])
const newTask = ref('')
const isEditing = ref(false)
const editingTask = ref(null)
const newDescription = ref('')

onMounted(() => getTasks())

const pendingTasks = computed(() =>
    tasks.value.filter(task => task.status === 'pending')
)

const completedTasks = computed(() =>
    tasks.value.filter(task => task.status === 'complete')
)

console.log('completedTasks', completedTasks.value)

function addTask() {
    const title = newTask.value.trim()
    const description = newDescription.value.trim()

    if (!title) return alert('Task name is required!')

    if (isEditing.value && editingTask.value) {
        const updatedTask = {
            ...editingTask.value,
            title,
            description,
        }

        API.put(`/todos/${editingTask.value.id}`, updatedTask)
            .then(res => {
                console.log('Task updated:', res.data)

                tasks.value = tasks.value.map(task =>
                    task.id === editingTask.value.id ? res.data : task
                )

                alert('Task updated successfully!')
                isEditing.value = false
                editingTask.value = null
            })
            .catch(err => {
                console.error('Error updating task:', err)
            })
    } else {
        const newTaskObj = {
            title,
            description,
        }

        API.post('/todos', newTaskObj)
            .then(res => {
                console.log('Task added:', res.data)

                tasks.value.push(res.data)

                alert('Task added successfully!')
            })
            .catch(err => {
                console.error('Error adding task:', err)
            })
    }

    newTask.value = ''
    newDescription.value = ''
}

function deleteTask(task) {
    if (confirm('Are you sure you want to delete this task?')) {
        API.delete(`/todos/${task.id}`)
            .then(() => {
                tasks.value = tasks.value.filter(t => t.id !== task.id)
                alert('Task deleted successfully!')
            })
            .catch(err => {
                console.error('Error deleting task:', err)
            })
    }
}

function editTask(task) {
    newTask.value = task.title
    newDescription.value = task.description
    isEditing.value = true
    editingTask.value = task
}

const getTasks = () => {
    API.get('/todos')
        .then(res => {
            console.log('Tasks:', res.data)
            tasks.value = res.data
        })
        .catch(err => {
            console.error('Fetch error:', err)
        })
}

// Function to handle task completion
function completeTask(task) {
    const updatedTask = {
        ...task,
        status: 'complete',
    }

    console.log('updatedTask', updatedTask)

    API.put(`/todos/${task.id}`, updatedTask)
        .then(res => {
            console.log('Task updated:', res.data)
            tasks.value = tasks.value.map(t =>
                t.id === task.id ? res.data : t
            )
        })
        .catch(err => {
            console.error('Error updating task:', err)
        })
}

function logout() {
    // Example: remove auth token and redirect to login
    localStorage.removeItem('token') // or any key you used
    window.location.href = '/login' // adjust as needed
}
</script>

<style scoped>
.todo-flex-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    gap: 20px;
    max-width: 100%;
    box-sizing: border-box;
}

.todo-section {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    min-width: 280px;
}

h2 {
    font-size: 18px;
    margin-bottom: 12px;
    color: #333;
}

.task-form {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.task-form input {
    flex: 1;
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.task-form button {
    padding: 8px 12px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f4f4f4;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
}

.task-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.task-left.completed span {
    text-decoration: line-through;
    color: gray;
}

.task-actions button {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    margin-left: 6px;
}

.task-actions button:hover {
    text-decoration: underline;
}

.empty {
    color: #999;
    font-style: italic;
}

.description-input {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    resize: vertical;
}

.logout-button {
    padding: 8px 16px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.logout-button:hover {
    background: #c82333;
}
</style>
