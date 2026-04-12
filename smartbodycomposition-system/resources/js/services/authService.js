// Real API service for user registration and authentication
// Using Laravel backend with Sanctum tokens

const API_BASE_URL = '/api'

const defaultHeaders = {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'X-Requested-With': 'XMLHttpRequest',
}

function getAuthHeaders() {
  const token = localStorage.getItem('auth_token')

  return token
    ? { ...defaultHeaders, Authorization: `Bearer ${token}` }
    : defaultHeaders
}

export async function registerUser(userData) {
  try {
    const response = await fetch(`${API_BASE_URL}/register`, {
      method: 'POST',
      headers: defaultHeaders,
      body: JSON.stringify({
        name: userData.fullName,
        email: userData.email,
        password: userData.password,
        password_confirmation: userData.password,
      }),
    })

    let data
        try {
        data = await response.json()
        } catch {
        throw new Error('Invalid server response')
        }

    if (!response.ok) {
        if (data.errors) {
            const firstError = Object.values(data.errors)[0][0]
            throw new Error(firstError)
        }
        throw new Error(data.message || 'Registration failed')
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
      headers: defaultHeaders,
      body: JSON.stringify({ email }),
    })

    let data
        try {
        data = await response.json()
        } catch {
        throw new Error('Invalid server response')
        }
    return typeof data.available === 'boolean' ? data.available : true
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
      headers: defaultHeaders,
      body: JSON.stringify({
        email,
        password,
      }),
    })

    let data
        try {
        data = await response.json()
        } catch {
        throw new Error('Invalid server response')
        }

    if (!response.ok) {
        if (data.errors) {
            const firstError = Object.values(data.errors)[0][0]
            throw new Error(firstError)
        }
        throw new Error(data.message || 'Invalid email or password')
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
        headers: getAuthHeaders(),
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

export async function getUserProfile() {
  try {
    const response = await fetch(`${API_BASE_URL}/user/profile`, {
      method: 'GET',
      headers: getAuthHeaders(),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.message || 'Failed to fetch profile')
    }

    return data
  } catch (error) {
    throw new Error(error.message || 'Failed to fetch profile. Please try again.')
  }
}

export async function getUserNotificationPreferences() {
  try {
    const response = await fetch(`${API_BASE_URL}/user/notification-preferences`, {
      method: 'GET',
      headers: getAuthHeaders(),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.message || 'Failed to fetch notification preferences')
    }

    return data
  } catch (error) {
    throw new Error(error.message || 'Failed to fetch notification preferences. Please try again.')
  }
}

export async function updateUserNotificationPreferences(preferences) {
  try {
    const response = await fetch(`${API_BASE_URL}/user/notification-preferences`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify(preferences),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      if (data.errors) {
        const firstError = Object.values(data.errors)[0][0]
        throw new Error(firstError)
      }
      throw new Error(data.message || 'Failed to update notification preferences')
    }

    return data.preferences
  } catch (error) {
    throw new Error(error.message || 'Failed to update notification preferences. Please try again.')
  }
}

export async function updateUserProfile(profileData) {
  try {
    const response = await fetch(`${API_BASE_URL}/user/profile`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify({
        name: profileData.fullName,
        email: profileData.email,
        age: profileData.age,
        gender: profileData.gender,
        height_cm: profileData.height_cm,
      }),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      if (data.errors) {
        const firstError = Object.values(data.errors)[0][0]
        throw new Error(firstError)
      }
      throw new Error(data.message || 'Update failed')
    }

    // Update local storage
    localStorage.setItem('user', JSON.stringify(data.user))

    return data.user
  } catch (error) {
    throw new Error(error.message || 'Profile update failed. Please try again.')
  }
}

export async function changePassword(passwordData) {
  try {
    const response = await fetch(`${API_BASE_URL}/user/change-password`, {
      method: 'POST',
      headers: getAuthHeaders(),
      body: JSON.stringify({
        current_password: passwordData.current,
        new_password: passwordData.new,
        new_password_confirmation: passwordData.confirm,
      }),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.error || data.message || 'Password change failed')
    }

    return data
  } catch (error) {
    throw new Error(error.message || 'Password change failed. Please try again.')
  }
}

export async function deleteAccount(password) {
  try {
    const response = await fetch(`${API_BASE_URL}/user/account`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
      body: JSON.stringify({ password }),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.error || data.message || 'Failed to delete account')
    }

    // Clear all local auth data
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    localStorage.removeItem('weightUnit')
    localStorage.removeItem('heightUnit')

    return true
  } catch (error) {
    throw new Error(error.message || 'Failed to delete account. Please try again.')
  }
}

export function setAuthToken(token) {
  if (token) {
    localStorage.setItem('auth_token', token)
  } else {
    localStorage.removeItem('auth_token')
  }
}

export async function getRecommendations() {
  try {
    const response = await fetch(`${API_BASE_URL}/recommendations`, {
      method: 'GET',
      headers: getAuthHeaders(),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.message || 'Failed to fetch recommendations')
    }

    return data
  } catch (error) {
    throw new Error(error.message || 'Failed to fetch recommendations. Please try again.')
  }
}

export async function generateRecommendations() {
  try {
    const response = await fetch(`${API_BASE_URL}/recommendations/generate`, {
      method: 'POST',
      headers: getAuthHeaders(),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.message || 'Failed to generate recommendations')
    }

    return data
  } catch (error) {
    throw new Error(error.message || 'Failed to generate recommendations. Please try again.')
  }
}

export async function updateRecommendationStatus(id, status) {
  try {
    const response = await fetch(`${API_BASE_URL}/recommendations/${id}/status`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify({ status }),
    })

    let data
    try {
      data = await response.json()
    } catch {
      throw new Error('Invalid server response')
    }

    if (!response.ok) {
      throw new Error(data.message || 'Failed to update recommendation status')
    }

    return data
  } catch (error) {
    throw new Error(error.message || 'Failed to update status. Please try again.')
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
