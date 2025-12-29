// ========== Learning Resources API ========== //
const Resources = {
    // Get resources for a specific skill
    getBySkill: async (skillId) => {
        return await apiCall(`/resources.php?action=by_skill&id=${skillId}`, {
            method: 'GET'
        });
    },

    // Get all resources (optional filter by type)
    list: async (type = null) => {
        const query = type ? `?action=list&type=${type}` : '?action=list';
        return await apiCall(`/resources.php${query}`, {
            method: 'GET'
        });
    },

    // Add a new learning resource (admin/future use)
    add: async (skillId, title, type, url) => {
        return await apiCall('/resources.php?action=add', {
            method: 'POST',
            body: JSON.stringify({
                skill_id: skillId,
                title,
                type,
                url
            })
        });
    }
};

// ========== Skill Progress API ========== //
const SkillProgress = {
    // Get user's progress for all skills
    list: async () => {
        return await apiCall('/skills.php?action=progress', {
            method: 'GET'
        });
    },

    // Update progress for a skill
    update: async (skillId, level, progressPercent) => {
        return await apiCall('/skills.php?action=update_progress', {
            method: 'POST',
            body: JSON.stringify({
                skill_id: skillId,
                level,
                progress: progressPercent
            })
        });
    }
};

// ========== Recommendations API ========== //
const Recommendations = {
    // Get career recommendations
    careers: async () => {
        return await apiCall('/recommendations.php?action=careers', {
            method: 'GET'
        });
    },

    // Get recommended resources for user
    resources: async () => {
        return await apiCall('/recommendations.php?action=resources', {
            method: 'GET'
        });
    }
};

// ========== Notifications API ========== //
const Notifications = {
    // Get user notifications
    list: async () => {
        return await apiCall('/notifications.php?action=list', {
            method: 'GET'
        });
    },

    // Mark notification as read
    markRead: async (notificationId) => {
        return await apiCall('/notifications.php?action=read', {
            method: 'POST',
            body: JSON.stringify({ notification_id: notificationId })
        });
    }
};

// ================= FINAL EXPORT =================
// Export all modules for use in other scripts or backend testing
// =================================================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        Auth,
        User,
        Career,
        Goals,
        Milestones,
        Resources,
        SkillProgress,
        Recommendations,
        Notifications,
        UI
    };
}
