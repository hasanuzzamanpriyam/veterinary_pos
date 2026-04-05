<?php

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        if ($number == 0) {
            return 'Zero Taka Only';
        }

        $words = '';

        // Check if the number is negative
        $isNegative = $number < 0;
        $number = abs($number); // Convert to absolute value for processing

        // Split the number into parts
        $crore = floor($number / 10000000);
        $lakh = floor(($number % 10000000) / 100000);
        $thousand = floor(($number % 100000) / 1000);
        $hundred = floor(($number % 1000) / 100);
        $remainder = $number % 100;

        $convertToWords = function ($num) use (&$convertToWords) {
            $dictionary = [
                0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
                10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
                20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
            ];

            if ($num < 20) {
                return $dictionary[$num];
            }
            if ($num < 100) {
                $tens = ((int)($num / 10)) * 10;
                $units = $num % 10;
                return $dictionary[$tens] . ($units ? '-' . $dictionary[$units] : '');
            }
            if ($num < 1000) {
                $hundreds = (int)($num / 100);
                $rem = $num % 100;
                return $dictionary[$hundreds] . ' hundred' . ($rem ? ' ' . $convertToWords($rem) : '');
            }
            if ($num < 100000) {
                $thousands = (int)($num / 1000);
                $rem = $num % 1000;
                return $convertToWords($thousands) . ' thousand' . ($rem ? ' ' . $convertToWords($rem) : '');
            }
            return (string)$num; // Fallback for very large numbers
        };

        if ($crore) {
            $words .= $convertToWords($crore) . ' crore ';
        }
        if ($lakh) {
            $words .= $convertToWords($lakh) . ' lakh ';
        }
        if ($thousand) {
            $words .= $convertToWords($thousand) . ' thousand ';
        }
        if ($hundred) {
            $words .= $convertToWords($hundred) . ' hundred ';
        }
        if ($remainder) {
            $words .= $convertToWords($remainder);
        }

        // Handle the negative sign
        if ($isNegative) {
            $words = '(Minus) ' . $words;
        }

        // Return the final string with "taka only"
        return ucwords(trim($words) . ' taka only');
    }
}

if (!function_exists('formatAmount')) {
    function formatAmount($amount)
    {
        // Check if the amount has a decimal part
        if (fmod($amount, 1) == 0) {
            // No decimal part, format with 0 precision
            return number_format($amount, 0);
        } else {
            // Has a decimal part, format with 2 precision
            return number_format($amount, 2);
        }
    }
}

if (!function_exists('splitHtml')) {
    // function splitHtml($html, $chunkSize = 50000)
    // {
    //     // Simple chunking by length (adjust based on your content)
    //     return str_split($html, $chunkSize);

    //     // For more sophisticated splitting, you could:
    //     // 1. Explode by </table> or </div> tags
    //     // 2. Use DOMDocument to parse and split logically
    // }
    function splitHtml($html, $maxChunkSize = 500000)
    {
        $result = [];
        $currentChunk = '';
        $offset = 0;

        // Find all complete table tags
        preg_match_all('/(<table[^>]*>.*?<\/table>)/is', $html, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            // If no tables found, return the whole HTML
            // (or use simple chunking as fallback)
            if (strlen($html) > $maxChunkSize) {
                return str_split($html, $maxChunkSize);
            }
            return [$html];
        }

        foreach ($matches[0] as $match) {
            $table = $match[0];
            $tableLength = strlen($table);
            $tablePosition = $match[1];

            // Add any content before this table
            $betweenContent = substr($html, $offset, $tablePosition - $offset);

            // If adding this table would exceed chunk size, save current chunk
            if (strlen($currentChunk) + strlen($betweenContent) + $tableLength > $maxChunkSize && !empty(trim($currentChunk))) {
                $result[] = $currentChunk;
                $currentChunk = '';
            }

            // Add the between content and table to current chunk
            $currentChunk .= $betweenContent . $table;
            $offset = $tablePosition + $tableLength;
        }

        // Add any remaining content after last table
        $remainingContent = substr($html, $offset);
        if (!empty(trim($remainingContent))) {
            $currentChunk .= $remainingContent;
        }

        // Add final chunk if not empty
        if (!empty(trim($currentChunk))) {
            $result[] = $currentChunk;
        }

        // If no chunks were created, return the original HTML
        return empty($result) ? [$html] : $result;
    }
}


if (!function_exists('cute_loader')) {
    function cute_loader()
    {
        echo '
            <div wire:loading.flex class="position-absolute w-100 h-100 p-5 align-items-start justify-content-center" style="min-height: 250px; top: 0; left: 0; z-index: 1050;">
                <div class="position-absolute w-100 h-100" style="z-index: 10; background-color: #000; opacity: .5; top: 0; left: 0;"></div>
                <div class="position-relative bg-white p-4 rounded-lg shadow-lg text-center" style="z-index: 20;">
                    <p class="font-weight-bold">Fetching Data...</p>
                    <div class="spinner-border text-primary mt-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>';
    }
}
