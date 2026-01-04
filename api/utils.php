<?php
// api/utils.php

function updateUserLevel($userId, $conn) {
    if (!$userId) return;

    // 1. Count passed tests
    $stmtTest = $conn->prepare("SELECT COUNT(*) FROM test_attempts WHERE user_id = ? AND passed = 1");
    $stmtTest->execute([$userId]);
    $passedTests = $stmtTest->fetchColumn();

    // 2. Count completed goals
    $stmtGoal = $conn->prepare("SELECT COUNT(*) FROM goals WHERE user_id = ? AND status = 'completed'");
    $stmtGoal->execute([$userId]);
    $completedGoals = $stmtGoal->fetchColumn();

    // 3. Apply Formula
    // Level = 1 + floor((Passed_Tests * 2 + Completed_Goals * 1) / 5)
    // Points: Test=2, Goal=1. Threshold=5 points per level.
    $points = ($passedTests * 2) + ($completedGoals * 1);
    $newLevel = 1 + floor($points / 5);

    // 4. Update User Level in Database
    $updateStmt = $conn->prepare("UPDATE users SET level = ? WHERE user_id = ?");
    $updateStmt->execute([$newLevel, $userId]);

    return $newLevel;
}
?>
