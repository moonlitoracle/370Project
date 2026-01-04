<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        sendResponse('error', 'Unauthorized');
    }
}

if ($method === 'GET') {
    requireAuth();
    $userId = $_SESSION['user_id'];

    // Get all careers the user is interested in (from user_careers)
    $stmt = $conn->prepare("
        SELECT c.career_id, c.name
        FROM user_careers uc
        JOIN careers c ON uc.career_id = c.career_id
        WHERE uc.user_id = ?
    ");
    $stmt->execute([$userId]);
    $careers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $gaps = [];
    foreach ($careers as $career) {
        // Reuse logic similar to career_readiness.php for each career
        $stmt = $conn->prepare("
            SELECT s.name,
                   cs.level AS required_level,
                   IFNULL(us.proficiency, 'None') AS user_level,
                   CASE TRIM(cs.level)
                       WHEN 'Beginner' THEN 1
                       WHEN 'Intermediate' THEN 2
                       WHEN 'Advanced' THEN 3
                       WHEN 'Expert' THEN 4
                       ELSE 0
                   END AS req_val,
                   CASE IFNULL(us.proficiency, 'None')
                       WHEN 'Beginner' THEN 1
                       WHEN 'Intermediate' THEN 2
                       WHEN 'Advanced' THEN 3
                       WHEN 'Expert' THEN 4
                       ELSE 0
                   END AS user_val
            FROM career_skills cs
            JOIN skills s ON cs.skill_id = s.skill_id
            LEFT JOIN user_skills us
                ON us.skill_id = s.skill_id AND us.user_id = ?
            WHERE cs.career_id = ?
        ");
        $stmt->execute([$userId, $career['career_id']]);
        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = count($skills);
        $matched = 0;
        $careerGaps = [];

        foreach ($skills as $s) {
            if ($s['user_val'] >= $s['req_val']) {
                $matched++;
            } else {
                unset($s['req_val']);
                unset($s['user_val']);
                $careerGaps[] = $s;
            }
        }

        $percentage = $total > 0 ? round(($matched / $total) * 100, 2) : 0;

        $gaps[] = [
            'career_id' => $career['career_id'],
            'career_name' => $career['name'],
            'match_percentage' => $percentage,
            'missing_skills' => $careerGaps
        ];
    }

    // Overall summary
    $overallMatch = count($gaps) > 0 ? array_sum(array_column($gaps, 'match_percentage')) / count($gaps) : 0;
    $overallMatch = round($overallMatch, 2);

    sendResponse('success', 'Skill gap analysis complete', [
        'overall_match_percentage' => $overallMatch,
        'career_gaps' => $gaps
    ]);
}

sendResponse('error', 'Invalid request');
?>