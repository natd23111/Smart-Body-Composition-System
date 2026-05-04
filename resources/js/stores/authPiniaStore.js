import { defineStore } from 'pinia'
import { loginUser, logoutUser } from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
  }),
  actions: {
    initAuth() {
      const storedUser = JSON.parse(localStorage.getItem('user'))
      if (storedUser) {
        this.user = storedUser
        this.isAuthenticated = true
      }
    },
    async login(email, password) {
      try {
        const response = await loginUser(email, password)
        this.user = response
        this.isAuthenticated = true
        localStorage.setItem('user', JSON.stringify(response))
        return true
      } catch (error) {
        this.user = null
        this.isAuthenticated = false
        throw error
      }
    },
    async logout() {
      try {
        await logoutUser()
        this.user = null
        this.isAuthenticated = false
        localStorage.removeItem('user')
        return true
      } catch (error) {
        return false
      }
    },
  },
})
