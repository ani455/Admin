<?php

/**
 * Formatter Utility Class
 * Handles formatting of various data types
 */

class Formatter {

    /**
     * Format currency
     */
    public static function currency($amount, $currency = 'PKR', $decimals = 2) {
        $formatted = number_format($amount, $decimals, '.', ',');
        
        $symbols = [
            'PKR' => 'Rs.',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'INR' => '₹'
        ];
        
        $symbol = $symbols[$currency] ?? $currency;
        return $symbol . ' ' . $formatted;
    }

    /**
     * Format percentage
     */
    public static function percentage($value, $decimals = 2) {
        return number_format($value, $decimals) . '%';
    }

    /**
     * Format date
     */
    public static function date($dateString, $format = 'M d, Y') {
        if (empty($dateString)) return '-';
        try {
            return date($format, strtotime($dateString));
        } catch (Exception $e) {
            return '-';
        }
    }

    /**
     * Format datetime
     */
    public static function datetime($dateString, $format = 'M d, Y H:i') {
        return self::date($dateString, $format);
    }

    /**
     * Format time ago
     */
    public static function timeAgo($dateString) {
        if (empty($dateString)) return '-';
        
        $timestamp = strtotime($dateString);
        $now = time();
        $diff = $now - $timestamp;

        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            $months = floor($diff / 2592000);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        }
    }

    /**
     * Format number with abbreviation (1000 -> 1K)
     */
    public static function abbreviateNumber($number) {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return $number;
    }

    /**
     * Format phone number
     */
    public static function phone($phone) {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 10) {
            return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
        } elseif (strlen($phone) === 11) {
            return substr($phone, 0, 4) . '-' . substr($phone, 4, 3) . '-' . substr($phone, 7);
        }
        return $phone;
    }

    /**
     * Format file size (bytes to KB, MB, GB)
     */
    public static function fileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Format slug (URL friendly)
     */
    public static function slug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }

    /**
     * Truncate text
     */
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Format status badge
     */
    public static function statusBadge($status) {
        $badges = [
            'active' => '<span class="badge bg-green-500 text-white">Active</span>',
            'inactive' => '<span class="badge bg-gray-500 text-white">Inactive</span>',
            'pending' => '<span class="badge bg-yellow-500 text-white">Pending</span>',
            'approved' => '<span class="badge bg-blue-500 text-white">Approved</span>',
            'rejected' => '<span class="badge bg-red-500 text-white">Rejected</span>',
            'blocked' => '<span class="badge bg-red-700 text-white">Blocked</span>'
        ];
        return $badges[$status] ?? '<span class="badge bg-gray-500 text-white">' . ucfirst($status) . '</span>';
    }

    /**
     * Format to title case
     */
    public static function titleCase($string) {
        return ucwords(str_replace('_', ' ', $string));
    }
}
