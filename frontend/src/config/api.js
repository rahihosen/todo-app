import axios from 'axios'

// if user is not logged in, redirect to login  page and if user is logged in, redirect set the token in the header

const token = localStorage.getItem('token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

const API = axios.create({
    baseURL: 'http://todo-task.test/api',
    headers: {
        'Content-Type': 'application/json',
    },
})

export default API
