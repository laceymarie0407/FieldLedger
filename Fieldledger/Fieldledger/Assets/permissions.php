<?php

function canManageEstimates() {
    return in_array($_SESSION['role'] ?? '', ['Estimator', 'Admin']);
}

function canViewEstimateCosts() {
    return in_array($_SESSION['role'] ?? '', ['Estimator', 'Admin', 'Executive']);
}

function canCreateDailyReports() {
    return in_array($_SESSION['role'] ?? '', ['Foreman', 'Admin']);
}

function canEditDailyReports() {
    return ($_SESSION['role'] ?? '') === 'Admin';
}

function canReviewReports() {
    return ($_SESSION['role'] ?? '') === 'Admin';
}

function canViewAdmin() {
    return in_array($_SESSION['role'] ?? '', ['Executive', 'Admin']);
}

function canViewExecutiveMetrics() {
    return in_array($_SESSION['role'] ?? '', ['Executive', 'Admin']);
}