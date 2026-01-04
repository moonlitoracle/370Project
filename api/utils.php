<?php
// Utils

function updateUserLevel($userId, $conn) {
    if (!$userId) return;

    // Count passed tests
    $stmtTest = $conn->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ? AND passed = 1");
    $stmtTest->execute([$userId]);
    $passedTests = $stmtTest->fetchColumn();

    // Count completed goals
    $stmtGoal = $conn->prepare("SELECT COUNT(*) FROM goals WHERE user_id = ? AND status = 'completed'");
    $stmtGoal->execute([$userId]);
    $completedGoals = $stmtGoal->fetchColumn();

    // Calculate level
    // Level = 1 + floor((Tests*2 + Goals) / 5)
    $points = ($passedTests * 2) + ($completedGoals * 1);
    $newLevel = 1 + floor($points / 5);

    // Update user level
    $updateStmt = $conn->prepare("UPDATE users SET level = ? WHERE user_id = ?");
    $updateStmt->execute([$newLevel, $userId]);

    return $newLevel;
}
?>
