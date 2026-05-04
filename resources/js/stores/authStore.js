import { ref, computed } from 'vue'
import { loginUser, logoutUser, getCurrentUser, setCurrentUser, getAuthToken } from '@/services/authService'

// Singleton state - lives for the lifetime of the app
let store = null

// Create singleton store
function createAuthStore() {
  const user = ref(null)
  const isAuthenticated = computed(() => user.value !== null)

  // Initialize from localStorage or session
  const initAuth = () => {
    const storedUser = getCurrentUser()
    if (storedUser) {
      user.value = storedUser
      console.log('Auth initialized with user:', storedUser.email)
    }
  }

  // Login with real backend API
  const login = async (email, password) => {
    try {
      console.log('Attempting login with:', email)
      const response = await loginUser(email, password)
      user.value = response
      console.log('Login successful:', response.email)
      return true
    } catch (error) {
      console.error('Login error:', error.message)
      throw error
    }
  }

  // Logout - call backend API and clear everything
  const logout = async () => {
    try {
      console.log('Starting logout...')
      await logoutUser()
      user.value = null
      console.log('Logout successful')
      return true
    } catch (error) {
      console.error('Logout error:', error.message)
      // Still clear user state even if API call fails
      user.value = null
      return true
    }
  }

  // Set user role (for testing/admin features)
  const setUserRole = (role) => {
    if (user.value) {
      user.value.role = role
      setCurrentUser(user.value)
      console.log('User role updated to:', role)
    }
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
    setUserRole,
    initAuth,
    getToken: () => getAuthToken(),
  }
}

// Export singleton store
export const useAuthStore = () => {
  if (!store) {
    store = createAuthStore()
  }
  return store
}
