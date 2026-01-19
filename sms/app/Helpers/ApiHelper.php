<?php
// app/Helpers/ApiHelper.php

if (!function_exists('getActiveSessionId')) {
    /**
     * Get the currently active academic session ID
     */
    function getActiveSessionId()
    {
        try {
            // Check if AcademicSession model exists
            if (class_exists(\App\Models\AcademicSession::class)) {
                $session = \App\Models\AcademicSession::where('is_active', true)->first();
                return $session ? $session->id : null;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('getActiveTermId')) {
    /**
     * Get the currently active term ID
     */
    function getActiveTermId()
    {
        try {
            if (class_exists(\App\Models\Term::class)) {
                $term = \App\Models\Term::where('is_active', true)->first();
                return $term ? $term->id : null;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('apiResponse')) {
    /**
     * Standard API response format
     */
    function apiResponse($data = null, $message = '', $status = 200, $errors = null)
    {
        $response = [
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toDateTimeString(),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}

if (!function_exists('apiError')) {
    /**
     * Standard API error response
     */
    function apiError($message = 'An error occurred', $status = 400, $errors = null)
    {
        return apiResponse(null, $message, $status, $errors);
    }
}

if (!function_exists('apiSuccess')) {
    /**
     * Standard API success response
     */
    function apiSuccess($data = null, $message = 'Success', $status = 200)
    {
        return apiResponse($data, $message, $status);
    }
}