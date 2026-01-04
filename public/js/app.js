// API configuration

// API helper
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

// Auth API //
const Auth = {
    register: async (name, email, password) => {
        return await apiCall('/auth.php?action=register', {
            method: 'POST',
            body: JSON.stringify({ name, email, password })
        });
    },

    login: async (email, password) => {
        return await apiCall('/auth.php?action=login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });
    },

    logout: async () => {
        return await apiCall('/auth.php?action=logout', {
            method: 'POST'
        });
    },

    checkSession: async () => {
        return await apiCall('/auth.php?action=check', {
            method: 'GET'
        });
    }
};

// User API //
const User = {
    getProfile: async () => {
        return await apiCall('/users.php', {
            method: 'GET'
        });
    },

    updateProfile: async (name, email) => {
        return await apiCall('/users.php', {
            method: 'POST',
            body: JSON.stringify({ name, email })
        });
    }
};

// Career API //
const Career = {
    list: async () => {
        return await apiCall('/careers.php?action=list', {
            method: 'GET'
        });
    },

    getDetails: async (careerId) => {
        return await apiCall(`/careers.php?action=details&id=${careerId}`, {
            method: 'GET'
        });
    },

    select: async (careerId) => {
        return await apiCall('/careers.php?action=select', {
            method: 'POST',
            body: JSON.stringify({ career_id: careerId })
        });
    },

    remove: async (careerId) => {
        return await apiCall('/careers.php?action=remove', {
            method: 'POST',
            body: JSON.stringify({ career_id: careerId })
        });
    }
};

// Goals API //
const Goals = {
    list: async () => {
        return await apiCall('/goals.php?action=list', {
            method: 'GET'
        });
    },

    getDetail: async (goalId) => {
        return await apiCall(`/goals.php?action=detail&id=${goalId}`, {
            method: 'GET'
        });
    },

    create: async (title, deadline, status = 'pending') => {
        return await apiCall('/goals.php?action=create', {
            method: 'POST',
            body: JSON.stringify({ title, deadline, status })
        });
    },

    update: async (goalId, updates) => {
        return await apiCall('/goals.php?action=update', {
            method: 'POST',
            body: JSON.stringify({ goal_id: goalId, ...updates })
        });
    },

    delete: async (goalId) => {
        return await apiCall(`/goals.php?action=delete&id=${goalId}`, {
            method: 'DELETE'
        });
    }
};

// Milestones API //
const Milestones = {
    add: async (goalId, title, status = 'pending') => {
        return await apiCall('/goals.php?action=add_milestone', {
            method: 'POST',
            body: JSON.stringify({ goal_id: goalId, title, status })
        });
    },

    update: async (milestoneId, updates) => {
        return await apiCall('/goals.php?action=update_milestone', {
            method: 'POST',
            body: JSON.stringify({ milestone_id: milestoneId, ...updates })
        });
    },

    delete: async (milestoneId) => {
        return await apiCall(`/goals.php?action=delete_milestone&id=${milestoneId}`, {
            method: 'DELETE'
        });
    }
};

// Skills API //
const Skills = {
    getAll: async () => {
        return await apiCall('/skills.php?action=all', {
            method: 'GET'
        });
    },

    list: async () => {
        return await apiCall('/skills.php?action=list', {
            method: 'GET'
        });
    },

    add: async (skillId, proficiency = 'Beginner') => {
        return await apiCall('/skills.php?action=add', {
            method: 'POST',
            body: JSON.stringify({ skill_id: skillId, proficiency })
        });
    },

    update: async (skillId, proficiency) => {
        return await apiCall('/skills.php?action=update', {
            method: 'POST',
            body: JSON.stringify({ skill_id: skillId, proficiency })
        });
    },

    delete: async (skillId) => {
        return await apiCall(`/skills.php?action=delete&skill_id=${skillId}`, {
            method: 'DELETE'
        });
    }
};

// Proficiency Tests API //
const ProficiencyTests = {
    getTest: async (skillId, level) => {
        return await apiCall(`/proficiency_tests.php?action=get_test&skill_id=${skillId}&level=${level}`, {
            method: 'GET'
        });
    },
    submitTest: async (skillId, level, answers) => {
        return await apiCall('/proficiency_tests.php?action=submit_test', {
            method: 'POST',
            body: JSON.stringify({ skill_id: skillId, level, answers })
        });
    },
    getAttempts: async (skillId) => {
        return await apiCall(`/proficiency_tests.php?action=attempts&skill_id=${skillId}`, {
            method: 'GET'
        });
    }
};


// Resources API //
const Resources = {
    getBySkill: async (skillId) => {
        return await apiCall(`/learning_resources.php?action=by_skill&skill_id=${skillId}`, {
            method: 'GET'
        });
    },

    list: async () => {
        return await apiCall('/learning_resources.php?action=list', {
            method: 'GET'
        });
    }
};

// Career Readiness API //
const CareerReadiness = {
    evaluate: async (careerId) => {
        return await apiCall(`/career_readiness.php?action=evaluate&career_id=${careerId}`, {
            method: 'GET'
        });
    }
};

// Insights API //
const Insights = {
    get: async () => {
        return await apiCall('/insights.php', {
            method: 'GET'
        });
    }
};

// Progress API //
const Progress = {
    get: async () => {
        return await apiCall('/progress.php', {
            method: 'GET'
        });
    }
};

// Skill Gap API //
const SkillGap = {
    analyze: async () => {
        return await apiCall('/skill_gap.php', {
            method: 'GET'
        });
    }
};

// UI Helpers //
const UI = {
    // Toast notification
    showMessage: (message, type = 'info') => {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;cursor:pointer;font-size:1.2em;">&times;</button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('hide');
            toast.addEventListener('transitionend', () => toast.remove());
        }, 3000);
    },

    redirect: (url) => {
        window.location.href = url;
    },

    setCurrentUser: (user) => {
        localStorage.setItem('currentUser', JSON.stringify(user));
    },

    getCurrentUser: () => {
        const user = localStorage.getItem('currentUser');
        return user ? JSON.parse(user) : null;
    },

    clearCurrentUser: () => {
        localStorage.removeItem('currentUser');
    }
};

if (typeof module !== 'undefined' && module.exports) {
    module.exports = { Auth, User, Career, Goals, Milestones, Resources, CareerReadiness, Insights, Progress, SkillGap, UI };
}
