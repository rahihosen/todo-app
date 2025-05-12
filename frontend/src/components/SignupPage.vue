<template>
    <div class="signup-wrapper">
        <div class="signup-box">
            <!-- Logo -->
            <div class="logo">🔐 MyApp</div>

            <!-- Title -->
            <h2 class="title">Create Your Account</h2>

            <!-- Form -->
            <form @submit.prevent="handleSignup">
                <!-- Name -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <div class="input-icon">
                        <input
                            v-model="name"
                            type="text"
                            id="name"
                            required
                            placeholder="Your name"
                        />
                        <span class="icon">👤</span>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-icon">
                        <input
                            v-model="email"
                            type="email"
                            id="email"
                            required
                            placeholder="you@example.com"
                        />
                        <span class="icon">@</span>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <input
                            v-model="password"
                            type="password"
                            id="password"
                            required
                            placeholder="••••••••"
                        />
                        <span class="icon">🔒</span>
                    </div>
                </div>
                

                <!-- Submit -->
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>

            <!-- Footer -->
            <p class="footer-text">
                Already have an account?
                <a href="#">Sign in</a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import API from '../config/api'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const router = useRouter()

const handleSignup = async () => {
    try {
        const response = await API.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
        })

        localStorage.setItem('token', response.data.token)
        router.push('/login')
    } catch (error) {
        if (error.response) {
            alert(error.response.data.message || 'Signup failed')
        } else {
            alert('Network error. Please try again.')
        }
    }
}
</script>

<style scoped>
.signup-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: linear-gradient(to right, #3b82f6, #6366f1, #8b5cf6);
}

.signup-box {
    width: 100%;
    max-width: 400px;
    background: white;
    padding: 2.5rem;
    border-radius: 1.5rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    animation: fade-in 0.5s ease-out;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo {
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    color: #6366f1;
    margin-bottom: 1.5rem;
}

.title {
    text-align: center;
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 0.4rem;
}

.input-icon {
    position: relative;
}

.input-icon input {
    width: 91%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #ccc;
    border-radius: 1rem;
    font-size: 1rem;
    outline: none;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: border 0.2s;
}

.input-icon input:focus {
    border-color: #6366f1;
}

.icon {
    position: absolute;
    top: 50%;
    left: 0.75rem;
    transform: translateY(-50%);
    font-size: 1rem;
    color: #6366f1;
}

.submit-btn {
    width: 100%;
    padding: 0.75rem;
    font-size: 1rem;
    color: white;
    background-color: #6366f1;
    border: none;
    border-radius: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #4f46e5;
}

.footer-text {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.85rem;
    color: #777;
}

.footer-text a {
    color: #6366f1;
    text-decoration: none;
}

.footer-text a:hover {
    text-decoration: underline;
}
</style>
