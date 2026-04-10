import axios from 'axios';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
  }
});

// Request interceptor to add auth token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor for error handling
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      // Handle unauthorized - clear token and redirect to login
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// ==================== AUTH ENDPOINTS ====================
export const authService = {
  login: (email, password) =>
    api.post('/login', { email, password }),

  logout: () =>
    api.post('/logout'),

  forgotPassword: (email) =>
    api.post('/forgot-password', { email }),

  resetPassword: (token, email, password, password_confirmation) =>
    api.post('/reset-password', { token, email, password, password_confirmation }),
};

// ==================== BODY COMPOSITION ENDPOINTS ====================
export const bodyCompositionService = {
  // Get all body compositions
  getAll: (params) =>
    api.get('/body-compositions', { params }),

  // Get single body composition
  getById: (id) =>
    api.get(`/body-compositions/${id}`),

  // Create new body composition record
  create: (data) =>
    api.post('/body-compositions', data),

  // Update body composition record
  update: (id, data) =>
    api.put(`/body-compositions/${id}`, data),

  // Delete body composition record
  delete: (id) =>
    api.delete(`/body-compositions/${id}`),
};

// ==================== HEALTH RECOMMENDATION ENDPOINTS ====================
export const healthRecommendationService = {
  // Get all recommendations
  getAll: (params) =>
    api.get('/recommendations', { params }),

  // Generate new recommendations based on body composition
  generate: () =>
    api.post('/recommendations/generate'),

  updateStatus: (id, status) =>
    api.put(`/recommendations/${id}/status`, { status }),
};

// ==================== GOALS ENDPOINTS ====================
export const goalService = {
  getAll: ()           => api.get('/goals'),
  create: (data)       => api.post('/goals', data),
  update: (id, data)   => api.put(`/goals/${id}`, data),
  remove: (id)         => api.delete(`/goals/${id}`),
};

// ==================== ADMIN ENDPOINTS ====================
export const adminService = {
  // Get dashboard stats
  getDashboard: () =>
    api.get('/admin/dashboard'),

  // Get all users
  getUsers: (params) =>
    api.get('/admin/users', { params }),

  // Delete user
  deleteUser: (id) =>
    api.delete(`/admin/users/${id}`),
};

export default api;
