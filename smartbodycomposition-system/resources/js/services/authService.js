// Real API service for user registration and authentication
// Using Laravel backend with Sanctum tokens

const API_BASE_URL = '/api'

export async function registerUser(userData) {
  try {
    const response = await fetch(`${API_BASE_URL}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        name: userData.fullName,
        email: userData.email,
        password: userData.password,
        password_confirmation: userData.password,
      }),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.error || data.message || 'Registration failed')
    }

    // Store token and user data
    if (data.token) {
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
    }

    return data.user
  } catch (error) {
    throw new Error(error.message || 'Registration failed. Please try again.')
  }
}

export async function verifyEmailAvailability(email) {
  try {
    const response = await fetch(`${API_BASE_URL}/check-email`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ email }),
    })

    const data = await response.json()
    return data.available
  } catch (error) {
    console.error('Error checking email availability:', error)
    // Fallback to true if check fails (allow user to try)
    return true
  }
}

export async function loginUser(email, password) {
  try {
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        email,
        password,
      }),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.error || 'Invalid email or password')
    }

    // Store token and user data
    if (data.token) {
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
    }

    return data.user
  } catch (error) {
    throw new Error(error.message || 'Login failed. Please try again.')
  }
}

export async function logoutUser() {
  try {
    const token = localStorage.getItem('auth_token')

    if (token) {
      const response = await fetch(`${API_BASE_URL}/logout`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`,
          'X-Requested-With': 'XMLHttpRequest',
        },
      })

      if (!response.ok) {
        console.error('Logout failed on server')
      }
    }

    // Clear local storage regardless
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    return true
  } catch (error) {
    console.error('Logout error:', error)
    // Still clear local storage on error
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    return true
  }
}

export function getAuthToken() {
  return localStorage.getItem('auth_token')
}

export function setAuthToken(token) {
  if (token) {
    localStorage.setItem('auth_token', token)
  } else {
    localStorage.removeItem('auth_token')
  }
}

export function getCurrentUser() {
  const user = localStorage.getItem('user')
  return user ? JSON.parse(user) : null
}

export function setCurrentUser(user) {
  if (user) {
    localStorage.setItem('user', JSON.stringify(user))
  } else {
    localStorage.removeItem('user')
  }
}
