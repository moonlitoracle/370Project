// API Base Configuration
const API_BASE = '/api';

// Helper function for API calls
async function apiCall(endpoint, options = {}) {
    try {
        const response = await fetch(`${API_BASE}${endpoint}`, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API call failed:', error);
        return { status: 'error', message: 'Network error occurred' };
    }
}

// ========== Authentication API ========== //
const Auth = {
    // Register new user
    register: async (name, email, password) => {
        return await apiCall('/auth.php?action=register', {
            method: 'POST',
            body: JSON.stringify({ name, email, password })
        });
    },

    // Login user
    login: async (email, password) => {
        return await apiCall('/auth.php?action=login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });
    },

    // Logout user
    logout: async () => {
        return await apiCall('/auth.php?action=logout', {
            method: 'POST'
        });
    },

    // Check if user is logged in
    checkSession: async () => {
        return await apiCall('/auth.php?action=check', {
            method: 'GET'
        });
    }
};

// ========== User Profile API ========== //
const User = {
    // Get current user profile
    getProfile: async () => {
        return await apiCall('/users.php', {
            method: 'GET'
        });
    },

    // Update user profile
    updateProfile: async (name, email) => {
        return await apiCall('/users.php', {
            method: 'POST',
            body: JSON.stringify({ name, email })
        });
    }
};

// ========== Career API ========== //
const Career = {
    // List all careers
    list: async () => {
        return await apiCall('/careers.php?action=list', {
            method: 'GET'
        });
    },

    // Get career details with required skills
    getDetails: async (careerId) => {
        return await apiCall(`/careers.php?action=details&id=${careerId}`, {
            method: 'GET'
        });
    },

    // Select a career
    select: async (careerId) => {
        return await apiCall('/careers.php?action=select', {
            method: 'POST',
            body: JSON.stringify({ career_id: careerId })
        });
    }
};

// ========== Goals API ========== //
const Goals = {
    // List all goals for current user
    list: async () => {
        return await apiCall('/goals.php?action=list', {
            method: 'GET'
        });
    },

    // Get goal details with milestones
    getDetail: async (goalId) => {
        return await apiCall(`/goals.php?action=detail&id=${goalId}`, {
            method: 'GET'
        });
    },

    // Create new goal
    create: async (title, deadline, status = 'pending') => {
        return await apiCall('/goals.php?action=create', {
            method: 'POST',
            body: JSON.stringify({ title, deadline, status })
        });
    },

    // Update goal
    update: async (goalId, updates) => {
        return await apiCall('/goals.php?action=update', {
            method: 'POST',
            body: JSON.stringify({ goal_id: goalId, ...updates })
        });
    },

    // Delete goal
    delete: async (goalId) => {
        return await apiCall(`/goals.php?action=delete&id=${goalId}`, {
            method: 'DELETE'
        });
    }
};

// ========== Milestones API ========== //
const Milestones = {
    // Add milestone to goal
    add: async (goalId, title, status = 'pending') => {
        return await apiCall('/goals.php?action=add_milestone', {
            method: 'POST',
            body: JSON.stringify({ goal_id: goalId, title, status })
        });
    },

    // Update milestone
    update: async (milestoneId, updates) => {
        return await apiCall('/goals.php?action=update_milestone', {
            method: 'POST',
            body: JSON.stringify({ milestone_id: milestoneId, ...updates })
        });
    },

    // Delete milestone
    delete: async (milestoneId) => {
        return await apiCall(`/goals.php?action=delete_milestone&id=${milestoneId}`, {
            method: 'DELETE'
        });
    }
};

// ========== Learning Resources API ========== //
const Resources = {
    // Get resources for a specific skill
    getBySkill: async (skillId) => {
        return await apiCall(`/learning_resources.php?action=by_skill&skill_id=${skillId}`, {
            method: 'GET'
        });
    },

    // Get all resources
    list: async () => {
        return await apiCall('/learning_resources.php?action=list', {
            method: 'GET'
        });
    }
};

// ========== Career Readiness API ========== //
const CareerReadiness = {
    // Evaluate user's readiness for a specific career
    evaluate: async (careerId) => {
        return await apiCall(`/career_readiness.php?action=evaluate&career_id=${careerId}`, {
            method: 'GET'
        });
    }
};

// ========== Insights API ========== //
const Insights = {
    // Get user progress insights
    get: async () => {
        return await apiCall('/insights.php', {
            method: 'GET'
        });
    }
};

// ========== Progress API ========== //
const Progress = {
    // Get user's overall progress
    get: async () => {
        return await apiCall('/users.php', {
            method: 'GET'
        });
    }
};

// ========== UI Helper Functions ========== //
const UI = {
    // Show message to user
    showMessage: (message, type = 'info') => {
        // Simple alert for now, can be enhanced with custom UI
        console.log(`[${type.toUpperCase()}] ${message}`);
        alert(message);
    },

    // Redirect to page
    redirect: (url) => {
        window.location.href = url;
    },

    // Store current user in localStorage
    setCurrentUser: (user) => {
        localStorage.setItem('currentUser', JSON.stringify(user));
    },

    // Get current user from localStorage
    getCurrentUser: () => {
        const user = localStorage.getItem('currentUser');
        return user ? JSON.parse(user) : null;
    },

    // Clear current user
    clearCurrentUser: () => {
        localStorage.removeItem('currentUser');
    }
};

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { Auth, User, Career, Goals, Milestones, Resources, CareerReadiness, Insights, Progress, UI };
}
