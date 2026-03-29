import { ref, computed } from 'vue'
import axios from 'axios'

// Singleton state - lives for the lifetime of the app
let store = null

// Create singleton store
function createAuthStore() {
  const user = ref(null)
  const isAuthenticated = computed(() => user.value !== null)

  // Mock user data (replace with API call to Laravel backend)
  const mockUser = {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    role: 'user', // 'user' or 'admin'
  }

  // Initialize from localStorage or session
  const initAuth = () => {
    const storedUser = localStorage.getItem('user')
    if (storedUser) {
      try {
        user.value = JSON.parse(storedUser)
      } catch (e) {
        localStorage.removeItem('user')
        user.value = null
      }
    }
  }

  // Login
  const login = async (email, password) => {
    try {
      // In production, call your Laravel login endpoint
      // const response = await axios.post('/api/login', { email, password })

      // Mock login - replace with actual API call
      user.value = mockUser
      localStorage.setItem('user', JSON.stringify(mockUser))
      return true
    } catch (error) {
      console.error('Login failed:', error)
      return false
    }
  }

  // Logout - clear everything
  const logout = async () => {
    try {
      // In production: await axios.post('/api/logout')

      // Clear all auth data
      localStorage.removeItem('user')
      localStorage.removeItem('auth_token')
      user.value = null

      console.log('Logout successful - user cleared:', user.value)
      return true
    } catch (error) {
      console.error('Logout failed:', error)
      return false
    }
  }

  // Set user role (for testing admin features)
  const setUserRole = (role) => {
    if (user.value) {
      user.value.role = role
      localStorage.setItem('user', JSON.stringify(user.value))
    }
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
    setUserRole,
    initAuth
  }
}

// Export singleton store
export const useAuthStore = () => {
  if (!store) {
    store = createAuthStore()
  }
  return store
}
